<?php
/**
 * Recent Directorist activity for the connected Themes & Extensions dashboard.
 *
 * @package Directorist
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'ATBDP_Extension_Activity' ) ) {
    /**
     * Build a small, paginated activity stream from existing WordPress data.
     */
    class ATBDP_Extension_Activity {
        /**
         * Cached modern orders-table availability for the current request.
         *
         * @var bool|null
         */
        private $modern_orders_table_exists = null;

        /**
         * Get connected-dashboard metrics from canonical Directorist data.
         *
         * @return array
         */
        public function get_dashboard_metrics() {
            global $wpdb;

            $post_counts        = wp_count_posts( ATBDP_POST_TYPE );
            $published_listings = isset( $post_counts->publish ) ? (int) $post_counts->publish : 0;
            $pending_listings   = isset( $post_counts->pending ) ? (int) $post_counts->pending : 0;
            $views_meta_key     = directorist_get_listing_views_count_meta_key();
            $listing_views      = (int) $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COALESCE( SUM( CAST( postmeta.meta_value AS UNSIGNED ) ), 0 )
                    FROM {$wpdb->posts} AS posts
                    INNER JOIN {$wpdb->postmeta} AS postmeta
                        ON posts.ID = postmeta.post_id
                    WHERE posts.post_type = %s
                    AND posts.post_status = 'publish'
                    AND postmeta.meta_key = %s",
                    ATBDP_POST_TYPE,
                    $views_meta_key
                )
            );
            $payment_stats      = $this->get_payment_stats( time() - ( 30 * DAY_IN_SECONDS ) );
            $metric_charts      = $this->get_dashboard_metric_charts();

            return [
                'published_listings' => $published_listings,
                'listing_views'      => $listing_views,
                'pending_listings'   => $pending_listings,
                'expiring_this_week' => $this->get_expiring_listing_count( 7 ),
                'revenue'            => (float) $payment_stats['amount'],
                'paid_orders'        => (int) $payment_stats['count'],
                'currency'           => atbdp_get_payment_currency(),
                'charts'             => $metric_charts,
            ];
        }

        /**
         * Get truthful sparkline data for dashboard metrics.
         *
         * Published listings and revenue use daily totals for the last 30 days
         * and compare them with the preceding 30 days. Listing views use the
         * optional Analytics daily table when available; otherwise the chart
         * shows the current per-listing view distribution without inventing a
         * historical comparison.
         *
         * @return array
         */
        private function get_dashboard_metric_charts() {
            $today        = current_datetime();
            $period_start = $today->modify( '-59 days' )->format( 'Y-m-d' );
            $period_end   = $today->format( 'Y-m-d' );
            $listing_map  = $this->get_published_listing_daily_totals( $period_start, $period_end );
            $revenue_map  = $this->get_revenue_daily_totals( $period_start, $period_end );
            $views_map    = $this->get_listing_views_daily_totals( $period_start, $period_end );

            $charts = [
                'published_listings' => $this->prepare_daily_metric_chart( $listing_map, $period_start ),
            ];

            if ( null !== $views_map ) {
                $charts['listing_views'] = $this->prepare_daily_metric_chart( $views_map, $period_start );
                $charts['listing_views']['source'] = 'analytics_daily';
            } else {
                $view_distribution = $this->get_listing_views_distribution();

                $charts['listing_views'] = [
                    'series'         => $this->bucket_metric_series( $view_distribution, 8 ),
                    'has_data'       => 0 < array_sum( $view_distribution ),
                    'has_comparison' => false,
                    'change_label'   => '',
                    'direction'      => 'flat',
                    'source'         => 'listing_meta',
                ];
            }

            $charts['revenue']                    = $this->prepare_daily_metric_chart( $revenue_map, $period_start );
            $charts['published_listings']['source'] = 'posts_daily';
            $charts['revenue']['source']            = 'orders_daily';

            /**
             * Filter connected-dashboard metric chart data.
             *
             * @param array $charts Prepared chart data.
             */
            return apply_filters( 'directorist_dashboard_metric_charts', $charts );
        }

        /**
         * Get current published listings grouped by their post date.
         *
         * @param string $start_date First included date in Y-m-d format.
         * @param string $end_date   Last included date in Y-m-d format.
         *
         * @return array<string,int>
         */
        private function get_published_listing_daily_totals( $start_date, $end_date ) {
            global $wpdb;

            $end_exclusive = ( new DateTimeImmutable( $end_date ) )->modify( '+1 day' )->format( 'Y-m-d' ) . ' 00:00:00';

            $rows = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT DATE( post_date ) AS metric_date, COUNT( ID ) AS metric_total
                    FROM {$wpdb->posts}
                    WHERE post_type = %s
                    AND post_status = 'publish'
                    AND post_date >= %s
                    AND post_date < %s
                    GROUP BY DATE( post_date )
                    ORDER BY metric_date ASC",
                    ATBDP_POST_TYPE,
                    $start_date . ' 00:00:00',
                    $end_exclusive
                )
            );

            return $this->index_daily_totals( $rows );
        }

        /**
         * Get paid revenue grouped by day from modern and legacy order storage.
         *
         * @param string $start_date First included date in Y-m-d format.
         * @param string $end_date   Last included date in Y-m-d format.
         *
         * @return array<string,float>
         */
        private function get_revenue_daily_totals( $start_date, $end_date ) {
            global $wpdb;

            $orders_table  = $wpdb->prefix . 'directorist_orders';
            $table_exists  = $this->modern_orders_table_exists();
            $totals        = [];
            $end_exclusive = ( new DateTimeImmutable( $end_date ) )->modify( '+1 day' )->format( 'Y-m-d' ) . ' 00:00:00';

            if ( $table_exists ) {
                // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- The table name uses the trusted WordPress prefix.
                $modern_rows = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT DATE( created_at ) AS metric_date, SUM( amount ) AS metric_total
                        FROM {$orders_table}
                        WHERE status = %s
                        AND amount > 0
                        AND created_at >= %s
                        AND created_at < %s
                        GROUP BY DATE( created_at )
                        ORDER BY metric_date ASC",
                        'paid',
                        $start_date . ' 00:00:00',
                        $end_exclusive
                    )
                );
                // phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared.

                $totals = $this->index_daily_totals( $modern_rows, true );
            }

            $legacy_join  = $table_exists
                ? "LEFT JOIN {$orders_table} AS modern_orders ON modern_orders.legacy_id = posts.ID"
                : '';
            $legacy_where = $table_exists ? 'AND modern_orders.id IS NULL' : '';

            // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- WordPress core table names and the checked Directorist table are trusted.
            $legacy_rows = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT DATE( posts.post_date ) AS metric_date,
                        SUM( CAST( amount_meta.meta_value AS DECIMAL(10,2) ) ) AS metric_total
                    FROM {$wpdb->posts} AS posts
                    INNER JOIN {$wpdb->postmeta} AS status_meta
                        ON posts.ID = status_meta.post_id
                        AND status_meta.meta_key = '_payment_status'
                        AND status_meta.meta_value = 'completed'
                    INNER JOIN {$wpdb->postmeta} AS amount_meta
                        ON posts.ID = amount_meta.post_id
                        AND amount_meta.meta_key = '_amount'
                    {$legacy_join}
                    WHERE posts.post_type = %s
                    AND posts.post_status = 'publish'
                    AND CAST( amount_meta.meta_value AS DECIMAL(10,2) ) > 0
                    AND posts.post_date >= %s
                    AND posts.post_date < %s
                    {$legacy_where}
                    GROUP BY DATE( posts.post_date )
                    ORDER BY metric_date ASC",
                    ATBDP_ORDER_POST_TYPE,
                    $start_date . ' 00:00:00',
                    $end_exclusive
                )
            );
            // phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared.

            foreach ( $this->index_daily_totals( $legacy_rows, true ) as $date => $amount ) {
                $totals[ $date ] = (float) ( $totals[ $date ] ?? 0 ) + $amount;
            }

            return $totals;
        }

        /**
         * Get listing views grouped by day from Directorist Analytics.
         *
         * A null return means that the optional daily table is unavailable, so
         * callers can use a truthful non-historical fallback.
         *
         * @param string $start_date First included date in Y-m-d format.
         * @param string $end_date   Last included date in Y-m-d format.
         *
         * @return array<string,int>|null
         */
        private function get_listing_views_daily_totals( $start_date, $end_date ) {
            global $wpdb;

            $stats_table = $wpdb->prefix . 'directorist_analytics_daily_stats';
            $table_name  = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $stats_table ) );

            if ( $stats_table !== $table_name ) {
                return null;
            }

            // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- The analytics table name uses the trusted WordPress prefix.
            $rows = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT daily.stat_date AS metric_date, SUM( daily.views ) AS metric_total
                    FROM {$stats_table} AS daily
                    INNER JOIN {$wpdb->posts} AS posts ON posts.ID = daily.listing_id
                    WHERE posts.post_type = %s
                    AND posts.post_status = 'publish'
                    AND daily.stat_date BETWEEN %s AND %s
                    GROUP BY daily.stat_date
                    ORDER BY daily.stat_date ASC",
                    ATBDP_POST_TYPE,
                    $start_date,
                    $end_date
                )
            );
            // phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared.

            return $this->index_daily_totals( $rows );
        }

        /**
         * Get current view totals per published listing for a fallback chart.
         *
         * @return int[]
         */
        private function get_listing_views_distribution() {
            global $wpdb;

            $rows = $wpdb->get_col(
                $wpdb->prepare(
                    "SELECT SUM( CAST( postmeta.meta_value AS UNSIGNED ) ) AS listing_views
                    FROM {$wpdb->posts} AS posts
                    INNER JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id
                    WHERE posts.post_type = %s
                    AND posts.post_status = 'publish'
                    AND postmeta.meta_key = %s
                    GROUP BY posts.ID, posts.post_date
                    ORDER BY posts.post_date ASC, posts.ID ASC",
                    ATBDP_POST_TYPE,
                    directorist_get_listing_views_count_meta_key()
                )
            );
            $values = array_map( 'absint', is_array( $rows ) ? $rows : [] );

            return $values;
        }

        /**
         * Convert daily query rows to a date-indexed totals map.
         *
         * @param array $rows      Query rows.
         * @param bool  $as_float Preserve decimal totals.
         *
         * @return array
         */
        private function index_daily_totals( $rows, $as_float = false ) {
            $totals = [];

            foreach ( is_array( $rows ) ? $rows : [] as $row ) {
                $date = isset( $row->metric_date ) ? sanitize_text_field( $row->metric_date ) : '';

                if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
                    continue;
                }

                $totals[ $date ] = $as_float
                    ? (float) $row->metric_total
                    : absint( $row->metric_total );
            }

            return $totals;
        }

        /**
         * Prepare a 30-day sparkline and its previous-period comparison.
         *
         * @param array  $daily_totals Date-indexed values for 60 days.
         * @param string $start_date   First date in Y-m-d format.
         *
         * @return array
         */
        private function prepare_daily_metric_chart( $daily_totals, $start_date ) {
            $series = [];
            $cursor = new DateTimeImmutable( $start_date );

            for ( $day = 0; $day < 60; $day++ ) {
                $date     = $cursor->modify( '+' . $day . ' days' )->format( 'Y-m-d' );
                $series[] = isset( $daily_totals[ $date ] ) ? (float) $daily_totals[ $date ] : 0;
            }

            $previous_series = array_slice( $series, 0, 30 );
            $current_series  = array_slice( $series, 30, 30 );
            $previous_total  = array_sum( $previous_series );
            $current_total   = array_sum( $current_series );
            $comparison      = $this->prepare_metric_comparison( $current_total, $previous_total );

            return array_merge(
                [
                    'series'   => $this->bucket_metric_series( $current_series, 8 ),
                    'has_data' => 0 < $current_total,
                ],
                $comparison
            );
        }

        /**
         * Prepare a truthful current/previous-period change label.
         *
         * @param float $current_total  Current period total.
         * @param float $previous_total Previous period total.
         *
         * @return array
         */
        private function prepare_metric_comparison( $current_total, $previous_total ) {
            if ( 0.0 === (float) $previous_total ) {
                return [
                    'has_comparison' => 0 < $current_total,
                    'change_label'   => 0 < $current_total ? __( 'New', 'directorist' ) : '',
                    'direction'      => 0 < $current_total ? 'up' : 'flat',
                ];
            }

            $change    = ( ( $current_total - $previous_total ) / $previous_total ) * 100;
            $precision = abs( $change ) < 10 && 0 !== ( (int) round( $change * 10 ) % 10 ) ? 1 : 0;

            return [
                'has_comparison' => true,
                'change_label'   => number_format_i18n( abs( $change ), $precision ) . '%',
                'direction'      => $change > 0 ? 'up' : ( $change < 0 ? 'down' : 'flat' ),
            ];
        }

        /**
         * Reduce a large metric series to a bounded number of summed buckets.
         *
         * @param array $series     Raw values.
         * @param int   $max_points Maximum chart points.
         *
         * @return float[]
         */
        private function bucket_metric_series( $series, $max_points ) {
            $series     = array_values( array_map( 'floatval', $series ) );
            $max_points = max( 2, absint( $max_points ) );
            $count      = count( $series );

            if ( $count <= $max_points ) {
                return $series;
            }

            $buckets = [];

            for ( $index = 0; $index < $max_points; $index++ ) {
                $start     = (int) floor( ( $index * $count ) / $max_points );
                $end       = (int) floor( ( ( $index + 1 ) * $count ) / $max_points );
                $buckets[] = array_sum( array_slice( $series, $start, max( 1, $end - $start ) ) );
            }

            return $buckets;
        }

        /**
         * Get the connected dashboard's canonical setup progress.
         *
         * @param array $metrics Pre-collected dashboard metrics.
         *
         * @return array
         */
        public function get_dashboard_setup( $metrics = [] ) {
            $directories   = directory_types();
            $directories   = is_array( $directories ) && ! is_wp_error( $directories ) ? $directories : [];

            if ( ! $this->is_dashboard_setup_visible( $directories ) ) {
                return [
                    'is_visible' => false,
                    'progress'   => 0,
                    'steps'      => [],
                ];
            }

            $category_count = wp_count_terms(
                [
                    'taxonomy'   => ATBDP_CATEGORY,
                    'hide_empty' => false,
                ]
            );
            $category_count = is_wp_error( $category_count ) ? 0 : (int) $category_count;
            $gateway_setup   = $this->get_dashboard_gateway_setup();
            $has_directories = ! empty( $directories );
            $builder_url     = admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-layout-builder' );

            if ( directorist_is_multi_directory_enabled() ) {
                $builder_url = admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-directory-types' );
            }

            $steps           = [
                [
                    'label'    => $has_directories
                        ? __( 'Manage directory type', 'directorist' )
                        : __( 'Create a directory type', 'directorist' ),
                    'complete' => $has_directories,
                    'url'      => $builder_url,
                ],
                [
                    'label'    => $category_count > 0
                        ? __( 'Manage listing categories', 'directorist' )
                        : __( 'Add your real categories', 'directorist' ),
                    'complete' => $category_count > 0,
                    'url'      => admin_url( 'edit-tags.php?taxonomy=' . ATBDP_CATEGORY . '&post_type=' . ATBDP_POST_TYPE ),
                ],
                [
                    'label'    => $gateway_setup['label'],
                    'complete' => $gateway_setup['complete'],
                    'url'      => admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-settings#monetization_settings__gateway' ),
                ],
                [
                    'label'    => ! empty( $metrics['published_listings'] )
                        ? __( 'Manage published listings', 'directorist' )
                        : __( 'Publish your first real listing', 'directorist' ),
                    'complete' => ! empty( $metrics['published_listings'] ),
                    'url'      => admin_url( 'edit.php?post_type=' . ATBDP_POST_TYPE ),
                ],
            ];
            $completed_steps = count(
                array_filter(
                    $steps,
                    static function( $step ) {
                        return ! empty( $step['complete'] );
                    }
                )
            );
            $progress = $steps ? (int) round( ( $completed_steps / count( $steps ) ) * 100 ) : 0;

            return [
                'is_visible' => true,
                'progress'    => $progress,
                'title'       => 100 === $progress
                    ? __( 'Your directory foundation is ready', 'directorist' )
                    : __( 'A few steps to launch your directory', 'directorist' ),
                'description' => 100 === $progress
                    ? __( 'Core setup is complete. Use these links whenever you need to make changes.', 'directorist' )
                    : __( 'Complete the remaining setup tasks before accepting live submissions.', 'directorist' ),
                'steps'       => $steps,
            ];
        }

        /**
         * Get the payment-gateway checklist state from current settings.
         *
         * Active gateways are authoritative. When none are active, the saved
         * default gateway is used as a fallback so the built-in offline default
         * can still satisfy the setup step.
         *
         * @return array{label:string,complete:bool}
         */
        private function get_dashboard_gateway_setup() {
            $available_gateways = $this->get_available_gateway_labels();
            $active_gateways    = ATBDP_Gateway::get_active_gateways();
            $active_gateways    = is_array( $active_gateways ) ? $active_gateways : [];
            $selected_gateways  = [];

            foreach ( $active_gateways as $gateway_key ) {
                $gateway_key = sanitize_key( is_scalar( $gateway_key ) ? (string) $gateway_key : '' );

                if ( $gateway_key && isset( $available_gateways[ $gateway_key ] ) ) {
                    $selected_gateways[ $gateway_key ] = $available_gateways[ $gateway_key ];
                }
            }

            if ( empty( $selected_gateways ) ) {
                $default_gateway = get_directorist_option( 'default_gateway', 'bank_transfer' );
                $default_gateway = sanitize_key( is_scalar( $default_gateway ) ? (string) $default_gateway : '' );

                if ( $default_gateway && isset( $available_gateways[ $default_gateway ] ) ) {
                    $selected_gateways[ $default_gateway ] = $available_gateways[ $default_gateway ];
                }
            }

            $is_complete         = ! empty( $selected_gateways );
            $incomplete_gateways = [];

            foreach ( array_keys( $selected_gateways ) as $gateway_key ) {
                if ( ! $this->is_dashboard_gateway_configured( $gateway_key ) ) {
                    $is_complete = false;

                    if ( 'bank_transfer' !== $gateway_key ) {
                        $incomplete_gateways[] = $selected_gateways[ $gateway_key ];
                    }
                }
            }

            return [
                'label'    => empty( $selected_gateways )
                    ? __( 'Configure a payment gateway', 'directorist' )
                    : $this->get_dashboard_gateway_label( $incomplete_gateways ),
                'complete' => $is_complete,
            ];
        }

        /**
         * Get registered gateway labels without hard-coding display names.
         *
         * Both current and legacy gateway filters are read so extensions using
         * either settings contract can contribute their translated label.
         *
         * @return array<string,string>
         */
        private function get_available_gateway_labels() {
            $default_gateway = [
                [
                    'value' => 'bank_transfer',
                    'label' => __( 'Bank Transfer (Offline Gateway)', 'directorist' ),
                ],
            ];
            $gateway_lists   = [
                apply_filters( 'directorist_active_gateways', $default_gateway ),
                apply_filters( 'atbdp_active_gateways', $default_gateway ),
                apply_filters( 'atbdp_default_gateways', $default_gateway ),
            ];
            $gateway_labels  = [];

            foreach ( $gateway_lists as $gateways ) {
                if ( ! is_array( $gateways ) ) {
                    continue;
                }

                foreach ( $gateways as $gateway ) {
                    if ( ! is_array( $gateway ) ) {
                        continue;
                    }

                    $gateway_key   = isset( $gateway['value'] ) && is_scalar( $gateway['value'] )
                        ? sanitize_key( (string) $gateway['value'] )
                        : '';
                    $gateway_label = isset( $gateway['label'] ) && is_scalar( $gateway['label'] )
                        ? wp_strip_all_tags( (string) $gateway['label'] )
                        : '';

                    if ( $gateway_key && $gateway_label ) {
                        $gateway_labels[ $gateway_key ] = $gateway_label;
                    }
                }
            }

            return $gateway_labels;
        }

        /**
         * Build checklist copy for incomplete non-default gateways.
         *
         * @param array $gateway_labels Incomplete translated gateway labels.
         *
         * @return string
         */
        private function get_dashboard_gateway_label( $gateway_labels ) {
            if ( empty( $gateway_labels ) ) {
                return __( 'Review payment gateways', 'directorist' );
            }

            $gateway_names = wp_sprintf_l( '%l', $gateway_labels );

            return sprintf(
                /* translators: %s: one or more payment gateway names. */
                _n(
                    'Review %s payment gateway',
                    'Review %s payment gateways',
                    count( $gateway_labels ),
                    'directorist'
                ),
                $gateway_names
            );
        }

        /**
         * Check whether a selected gateway has the credentials for its mode.
         *
         * Unknown extension gateways retain the prior active-only behavior.
         * They can opt into credential checks with the final filter.
         *
         * @param string $gateway_key Gateway identifier.
         *
         * @return bool
         */
        private function is_dashboard_gateway_configured( $gateway_key ) {
            $is_configured = true;

            switch ( $gateway_key ) {
                case 'stripe_gateway':
                    $is_test_mode = (bool) get_directorist_option( 'stripe_gateway_test_mode', true );
                    $required     = $is_test_mode
                        ? [ 'stripe_test_pk', 'stripe_test_sk' ]
                        : [ 'stripe_live_pk', 'stripe_live_sk' ];
                    $is_configured = $this->has_dashboard_gateway_credentials( $required );
                    break;

                case 'paypal_gateway':
                    $is_test_mode = (bool) get_directorist_option( 'paypal_gateway_test_mode', true );
                    $required     = $is_test_mode
                        ? [ 'paypal_test_client_id', 'paypal_test_secret' ]
                        : [ 'paypal_live_client_id', 'paypal_live_secret' ];
                    $is_configured = $this->has_dashboard_gateway_credentials( $required );
                    break;

                case 'authorize_net_gateway':
                    $login_id        = get_directorist_option(
                        'authorize_net_gateway_login_id',
                        get_directorist_option( 'authorize_login_id', '' )
                    );
                    $transaction_key = get_directorist_option(
                        'authorize_net_gateway_transaction_key',
                        get_directorist_option( 'authorize_transaction_key', '' )
                    );
                    $is_configured   = $this->is_filled_gateway_value( $login_id )
                        && $this->is_filled_gateway_value( $transaction_key );
                    break;
            }

            /**
             * Filter whether a selected payment gateway is configured.
             *
             * Extensions can validate their own settings without exposing any
             * credential value in the connected dashboard response.
             *
             * @param bool   $is_configured Whether the gateway is configured.
             * @param string $gateway_key   Gateway identifier.
             */
            return (bool) apply_filters(
                'directorist_dashboard_gateway_is_configured',
                $is_configured,
                $gateway_key
            );
        }

        /**
         * Check that all required gateway options contain a non-empty value.
         *
         * @param array $option_keys Directorist option keys.
         *
         * @return bool
         */
        private function has_dashboard_gateway_credentials( $option_keys ) {
            foreach ( $option_keys as $option_key ) {
                if ( ! $this->is_filled_gateway_value( get_directorist_option( $option_key, '' ) ) ) {
                    return false;
                }
            }

            return true;
        }

        /**
         * Check whether a credential-like option contains a scalar value.
         *
         * @param mixed $value Option value.
         *
         * @return bool
         */
        private function is_filled_gateway_value( $value ) {
            return is_scalar( $value ) && '' !== trim( (string) $value );
        }

        /**
         * Check whether the first-directory setup window is still active.
         *
         * @param array $directories Current directory type terms.
         *
         * @return bool
         */
        private function is_dashboard_setup_visible( $directories ) {
            if ( empty( $directories ) ) {
                return true;
            }

            $created_dates = [];

            foreach ( $directories as $directory ) {
                $term_id    = is_object( $directory ) ? (int) ( $directory->term_id ?? 0 ) : 0;
                $created_at = $term_id > 0 ? get_term_meta( $term_id, '_created_date', true ) : null;

                if ( ! is_scalar( $created_at ) || ! is_numeric( $created_at ) || (int) $created_at < 1 ) {
                    return false;
                }

                $created_dates[] = (int) $created_at;
            }

            $created_at = min( $created_dates );
            $now        = time();

            if ( $created_at > $now ) {
                return false;
            }

            return $now < ( $created_at + ( 30 * DAY_IN_SECONDS ) );
        }

        /**
         * Supported activity filters.
         *
         * @var array
         */
        private $supported_types = [ 'all', 'listing', 'review', 'payment', 'user' ];

        /**
         * Get one activity page.
         *
         * @param int    $page     Page number.
         * @param int    $per_page Items per page.
         * @param string $type     Activity type.
         *
         * @return array
         */
        public function get_page( $page = 1, $per_page = 10, $type = 'all' ) {
            $page     = max( 1, min( 10, absint( $page ) ) );
            $per_page = max( 1, min( 20, absint( $per_page ) ) );
            $type     = sanitize_key( $type );
            $type     = in_array( $type, $this->supported_types, true ) ? $type : 'all';
            $offset   = ( $page - 1 ) * $per_page;
            $limit    = min( 101, $offset + $per_page + 1 );
            $items    = [];

            if ( in_array( $type, [ 'all', 'listing' ], true ) ) {
                $items = array_merge(
                    $items,
                    $this->get_listing_activity( $limit ),
                    $this->get_expiring_listing_activity( min( $limit, 10 ) )
                );
            }

            if ( in_array( $type, [ 'all', 'review' ], true ) ) {
                $items = array_merge( $items, $this->get_review_activity( $limit ) );
            }

            if ( in_array( $type, [ 'all', 'payment' ], true ) ) {
                $items = array_merge( $items, $this->get_payment_activity( $limit ) );
            }

            if ( in_array( $type, [ 'all', 'user' ], true ) ) {
                $items = array_merge( $items, $this->get_user_activity( $limit ) );
            }

            $unique_items = [];

            foreach ( array_filter( $items ) as $item ) {
                $unique_items[ $item['id'] ] = $item;
            }

            $items = array_values( $unique_items );

            usort(
                $items,
                static function( $left, $right ) {
                    $left_upcoming  = ! empty( $left['upcoming'] );
                    $right_upcoming = ! empty( $right['upcoming'] );

                    if ( $left_upcoming !== $right_upcoming ) {
                        return $left_upcoming ? 1 : -1;
                    }

                    if ( $left_upcoming ) {
                        return (int) $left['timestamp'] <=> (int) $right['timestamp'];
                    }

                    return (int) $right['timestamp'] <=> (int) $left['timestamp'];
                }
            );

            $has_more = $page < 10 && count( $items ) > ( $offset + $per_page );
            $items    = array_slice( $items, $offset, $per_page );

            foreach ( $items as &$item ) {
                $item['group']       = $this->get_group( $item );
                $item['group_label'] = $this->get_group_label( $item['group'] );
                $item['time_label']  = $this->get_time_label( $item );
            }
            unset( $item );

            $data = [
                'items'     => $items,
                'has_more'  => $has_more,
                'next_page' => $has_more ? $page + 1 : null,
                'page'      => $page,
                'type'      => $type,
            ];

            /**
             * Filter connected dashboard activity data.
             *
             * @param array  $data     Prepared activity page.
             * @param int    $page     Current page.
             * @param int    $per_page Items per page.
             * @param string $type     Current activity filter.
             */
            return apply_filters( 'directorist_themes_extensions_activity_data', $data, $page, $per_page, $type );
        }

        /**
         * Get recent listing activity.
         *
         * @param int $limit Query limit.
         *
         * @return array
         */
        private function get_listing_activity( $limit ) {
            $query = new WP_Query(
                [
                    'post_type'              => ATBDP_POST_TYPE,
                    'post_status'            => [ 'publish', 'pending', 'draft' ],
                    'posts_per_page'         => $limit,
                    'orderby'                => 'modified',
                    'order'                  => 'DESC',
                    'no_found_rows'          => true,
                    'update_post_meta_cache' => false,
                    'update_post_term_cache' => false,
                ]
            );
            $items        = [];
            $presentation = $this->get_activity_presentation( 'listing' );

            foreach ( $query->posts as $listing ) {
                $author = get_userdata( $listing->post_author );
                $status = get_post_status( $listing );
                $title  = 'pending' === $status
                    ? __( 'Listing awaiting review', 'directorist' )
                    : ( 'publish' === $status ? __( 'Listing published', 'directorist' ) : __( 'Listing draft saved', 'directorist' ) );
                $action = 'pending' === $status ? __( 'Review', 'directorist' ) : __( 'Edit', 'directorist' );

                $items[] = $this->prepare_item(
                    [
                        'id'           => 'listing-' . $listing->ID,
                        'type'         => 'listing',
                        'title'        => $title,
                        'subject'      => get_the_title( $listing ) ?: __( 'Untitled listing', 'directorist' ),
                        'context'      => $author ? sprintf(
                            /* translators: %s: Listing author display name. */
                            __( 'by %s', 'directorist' ),
                            $author->display_name
                        ) : '',
                        'timestamp'    => $this->get_post_timestamp( $listing, true ),
                        'icon'         => $presentation['icon'],
                        'tone'         => $presentation['tone'],
                        'action_label' => $action,
                        'action_url'   => get_edit_post_link( $listing->ID, 'raw' ),
                    ]
                );
            }

            return array_filter( $items );
        }

        /**
         * Get recent review activity.
         *
         * @param int $limit Query limit.
         *
         * @return array
         */
        private function get_review_activity( $limit ) {
            $comments = get_comments(
                [
                    'type'      => 'review',
                    'post_type' => ATBDP_POST_TYPE,
                    'status'    => 'all',
                    'parent'    => 0,
                    'number'    => min( 202, $limit * 2 ),
                    'orderby'   => 'comment_date_gmt',
                    'order'     => 'DESC',
                ]
            );
            $items        = [];
            $presentation = $this->get_activity_presentation( 'review' );

            foreach ( $comments as $comment ) {
                if ( ! in_array( (string) $comment->comment_approved, [ '0', '1' ], true ) ) {
                    continue;
                }

                $rating  = class_exists( '\Directorist\Review\Comment' )
                    ? \Directorist\Review\Comment::get_rating( $comment->comment_ID )
                    : (float) get_comment_meta( $comment->comment_ID, 'rating', true );
                $context = $rating
                    ? sprintf(
                        /* translators: 1: Review rating, 2: Reviewer name. */
                        __( '%1$s-star review by %2$s', 'directorist' ),
                        number_format_i18n( $rating, 1 ),
                        $comment->comment_author
                    )
                    : sprintf(
                        /* translators: %s: Reviewer name. */
                        __( 'Review by %s', 'directorist' ),
                        $comment->comment_author
                    );

                $items[] = $this->prepare_item(
                    [
                        'id'           => 'review-' . $comment->comment_ID,
                        'type'         => 'review',
                        'title'        => '0' === (string) $comment->comment_approved
                            ? __( 'Review awaiting moderation', 'directorist' )
                            : __( 'New review received', 'directorist' ),
                        'subject'      => get_the_title( $comment->comment_post_ID ) ?: __( 'Untitled listing', 'directorist' ),
                        'context'      => $context,
                        'timestamp'    => strtotime( $comment->comment_date_gmt . ' UTC' ),
                        'icon'         => $presentation['icon'],
                        'tone'         => $presentation['tone'],
                        'action_label' => __( 'Review', 'directorist' ),
                        'action_url'   => add_query_arg(
                            [
                                'action' => 'editcomment',
                                'c'      => $comment->comment_ID,
                            ],
                            admin_url( 'comment.php' )
                        ),
                    ]
                );

                if ( count( $items ) >= $limit ) {
                    break;
                }
            }

            return array_filter( $items );
        }

        /**
         * Get completed paid-order activity.
         *
         * @param int $limit Query limit.
         *
         * @return array
         */
        private function get_payment_activity( $limit ) {
            $items             = $this->get_modern_payment_activity( $limit );
            $modern_legacy_ids = array_values(
                array_filter(
                    array_map(
                        static function( $item ) {
                            return absint( $item['legacy_id'] ?? 0 );
                        },
                        $items
                    )
                )
            );

            foreach ( $items as &$item ) {
                unset( $item['legacy_id'] );
            }
            unset( $item );

            if ( count( $items ) >= $limit ) {
                return array_slice( $items, 0, $limit );
            }

            $presentation = $this->get_activity_presentation( 'payment' );

            $query = new WP_Query(
                [
                    'post_type'              => ATBDP_ORDER_POST_TYPE,
                    'post_status'            => 'publish',
                    'posts_per_page'         => min( 202, $limit * 2 ),
                    'orderby'                => 'date',
                    'order'                  => 'DESC',
                    'no_found_rows'          => true,
                    'update_post_meta_cache' => true,
                    'update_post_term_cache' => false,
                    'meta_query'             => [
                        [
                            'key'   => '_payment_status',
                            'value' => 'completed',
                        ],
                    ],
                ]
            );

            foreach ( $query->posts as $order ) {
                if ( in_array( (int) $order->ID, $modern_legacy_ids, true ) ) {
                    continue;
                }

                $amount = (float) get_post_meta( $order->ID, '_amount', true );

                if ( $amount <= 0 ) {
                    continue;
                }

                $listing_id    = absint( get_post_meta( $order->ID, '_listing_id', true ) );
                $listing_title = $listing_id ? get_the_title( $listing_id ) : '';
                $customer      = get_userdata( $order->post_author );
                $context_parts = [];

                if ( $customer ) {
                    $context_parts[] = sprintf(
                        /* translators: %s: Customer display name. */
                        __( 'from %s', 'directorist' ),
                        $customer->display_name
                    );
                }

                if ( $listing_title ) {
                    $context_parts[] = sprintf(
                        /* translators: %s: Listing title. */
                        __( 'for %s', 'directorist' ),
                        $listing_title
                    );
                }

                $items[] = $this->prepare_item(
                    [
                        'id'           => 'payment-' . $order->ID,
                        'type'         => 'payment',
                        'title'        => __( 'Payment received', 'directorist' ),
                        'subject'      => html_entity_decode( atbdp_currency_symbol( atbdp_get_payment_currency() ), ENT_QUOTES, get_bloginfo( 'charset' ) ) . number_format_i18n( $amount, 2 ),
                        'context'      => implode( ' ', $context_parts ),
                        'timestamp'    => $this->get_post_timestamp( $order ),
                        'icon'         => $presentation['icon'],
                        'tone'         => $presentation['tone'],
                        'action_label' => __( 'View order', 'directorist' ),
                        'action_url'   => get_edit_post_link( $order->ID, 'raw' ),
                    ]
                );

                if ( count( $items ) >= $limit ) {
                    break;
                }
            }

            return array_values( array_filter( $items ) );
        }

        /**
         * Get completed payments from the current table-based order system.
         *
         * @param int $limit Query limit.
         *
         * @return array
         */
        private function get_modern_payment_activity( $limit ) {
            global $wpdb;

            $orders_table = $wpdb->prefix . 'directorist_orders';

            if ( ! $this->modern_orders_table_exists() ) {
                return [];
            }

            // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- The table name uses the trusted WordPress prefix.
            $orders = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT id, legacy_id, user_id, listing_id, amount, currency, created_at,
                        UNIX_TIMESTAMP( created_at ) AS created_timestamp
                    FROM {$orders_table}
                    WHERE status = %s
                    AND amount > 0
                    ORDER BY created_at DESC, id DESC
                    LIMIT %d",
                    'paid',
                    max( 1, $limit )
                )
            );
            // phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared.

            $items        = [];
            $presentation = $this->get_activity_presentation( 'payment' );

            foreach ( $orders as $order ) {
                $amount        = (float) $order->amount;
                $listing_title = $order->listing_id ? get_the_title( (int) $order->listing_id ) : '';
                $customer      = get_userdata( (int) $order->user_id );
                $context_parts = [];
                $currency      = $order->currency ?: atbdp_get_payment_currency();

                if ( $customer ) {
                    $context_parts[] = sprintf(
                        /* translators: %s: Customer display name. */
                        __( 'from %s', 'directorist' ),
                        $customer->display_name
                    );
                }

                if ( $listing_title ) {
                    $context_parts[] = sprintf(
                        /* translators: %s: Listing title. */
                        __( 'for %s', 'directorist' ),
                        $listing_title
                    );
                }

                $item = $this->prepare_item(
                    [
                        'id'           => 'payment-db-' . $order->id,
                        'type'         => 'payment',
                        'title'        => __( 'Payment received', 'directorist' ),
                        'subject'      => html_entity_decode( atbdp_currency_symbol( $currency ), ENT_QUOTES, get_bloginfo( 'charset' ) ) . number_format_i18n( $amount, 2 ),
                        'context'      => implode( ' ', $context_parts ),
                        'timestamp'    => absint( $order->created_timestamp ),
                        'icon'         => $presentation['icon'],
                        'tone'         => $presentation['tone'],
                        'action_label' => __( 'View order', 'directorist' ),
                        'action_url'   => add_query_arg(
                            [
                                'post_type' => ATBDP_POST_TYPE,
                                'page'      => 'directorist-orders',
                            ],
                            admin_url( 'edit.php' )
                        ) . '#/edit/' . absint( $order->id ),
                    ]
                );

                if ( $item ) {
                    $item['legacy_id'] = absint( $order->legacy_id );
                    $items[]           = $item;
                }
            }

            return $items;
        }

        /**
         * Get paid-order totals from modern and unmigrated legacy storage.
         *
         * @param int $after_timestamp Earliest included order timestamp.
         *
         * @return array
         */
        private function get_payment_stats( $after_timestamp ) {
            global $wpdb;

            $orders_table = $wpdb->prefix . 'directorist_orders';
            $stats        = [
                'amount' => 0.0,
                'count'  => 0,
            ];
            $table_exists = $this->modern_orders_table_exists();

            if ( $table_exists ) {
                // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- The table name uses the trusted WordPress prefix.
                $modern_stats = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT COALESCE( SUM( amount ), 0 ) AS amount, COUNT( id ) AS order_count
                        FROM {$orders_table}
                        WHERE status = %s
                        AND amount > 0
                        AND created_at >= FROM_UNIXTIME( %d )",
                        'paid',
                        $after_timestamp
                    )
                );
                // phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared.

                if ( $modern_stats ) {
                    $stats['amount'] += (float) $modern_stats->amount;
                    $stats['count']  += (int) $modern_stats->order_count;
                }
            }

            $legacy_join = $table_exists
                ? "LEFT JOIN {$orders_table} AS modern_orders ON modern_orders.legacy_id = posts.ID"
                : '';
            $legacy_where = $table_exists ? 'AND modern_orders.id IS NULL' : '';

            // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- WordPress core table names and the checked Directorist table are trusted.
            $legacy_stats = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT COALESCE( SUM( CAST( amount_meta.meta_value AS DECIMAL(10,2) ) ), 0 ) AS amount,
                        COUNT( DISTINCT posts.ID ) AS order_count
                    FROM {$wpdb->posts} AS posts
                    INNER JOIN {$wpdb->postmeta} AS status_meta
                        ON posts.ID = status_meta.post_id
                        AND status_meta.meta_key = '_payment_status'
                        AND status_meta.meta_value = 'completed'
                    INNER JOIN {$wpdb->postmeta} AS amount_meta
                        ON posts.ID = amount_meta.post_id
                        AND amount_meta.meta_key = '_amount'
                    {$legacy_join}
                    WHERE posts.post_type = %s
                    AND posts.post_status = 'publish'
                    AND CAST( amount_meta.meta_value AS DECIMAL(10,2) ) > 0
                    AND posts.post_date_gmt >= %s
                    {$legacy_where}",
                    ATBDP_ORDER_POST_TYPE,
                    gmdate( 'Y-m-d H:i:s', $after_timestamp )
                )
            );
            // phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared.

            if ( $legacy_stats ) {
                $stats['amount'] += (float) $legacy_stats->amount;
                $stats['count']  += (int) $legacy_stats->order_count;
            }

            return $stats;
        }

        /**
         * Check the modern orders table once per service instance.
         *
         * @return bool
         */
        private function modern_orders_table_exists() {
            global $wpdb;

            if ( null !== $this->modern_orders_table_exists ) {
                return $this->modern_orders_table_exists;
            }

            $orders_table                     = $wpdb->prefix . 'directorist_orders';
            $this->modern_orders_table_exists = $orders_table === $wpdb->get_var(
                $wpdb->prepare(
                    'SHOW TABLES LIKE %s',
                    $orders_table
                )
            );

            return $this->modern_orders_table_exists;
        }

        /**
         * Count published listings expiring within the requested number of days.
         *
         * @param int $days Number of days.
         *
         * @return int
         */
        private function get_expiring_listing_count( $days ) {
            $query = new WP_Query(
                [
                    'post_type'              => ATBDP_POST_TYPE,
                    'post_status'            => 'publish',
                    'posts_per_page'         => 1,
                    'fields'                 => 'ids',
                    'no_found_rows'          => false,
                    'update_post_meta_cache' => false,
                    'update_post_term_cache' => false,
                    'meta_query'             => [
                        'relation' => 'AND',
                        [
                            'key'     => '_never_expire',
                            'compare' => 'NOT EXISTS',
                        ],
                        [
                            'key'     => '_expiry_date',
                            'value'   => [
                                current_time( 'mysql' ),
                                current_datetime()->modify( '+' . max( 1, absint( $days ) ) . ' days' )->format( 'Y-m-d H:i:s' ),
                            ],
                            'compare' => 'BETWEEN',
                            'type'    => 'DATETIME',
                        ],
                    ],
                ]
            );

            return (int) $query->found_posts;
        }

        /**
         * Get recent Directorist user registrations.
         *
         * @param int $limit Query limit.
         *
         * @return array
         */
        private function get_user_activity( $limit ) {
            $query = new WP_User_Query(
                [
                    'number'     => min( 202, $limit * 2 ),
                    'orderby'    => 'registered',
                    'order'      => 'DESC',
                    'meta_query' => [
                        [
                            'key'     => '_user_type',
                            'compare' => 'EXISTS',
                        ],
                    ],
                ]
            );
            $items        = [];
            $presentation = $this->get_activity_presentation( 'user' );

            foreach ( $query->get_results() as $user ) {
                $user_type = (string) get_user_meta( $user->ID, '_user_type', true );

                if ( '' === $user_type ) {
                    continue;
                }

                $items[] = $this->prepare_item(
                    [
                        'id'           => 'user-' . $user->ID,
                        'type'         => 'user',
                        'title'        => __( 'New user registered', 'directorist' ),
                        'subject'      => $user->display_name ?: $user->user_login,
                        'context'      => 'author' === $user_type
                            ? __( 'Registered as a listing owner', 'directorist' )
                            : __( 'Registered for the directory', 'directorist' ),
                        'timestamp'    => strtotime( $user->user_registered . ' UTC' ),
                        'icon'         => $presentation['icon'],
                        'tone'         => $presentation['tone'],
                        'action_label' => __( 'View user', 'directorist' ),
                        'action_url'   => get_edit_user_link( $user->ID ),
                    ]
                );

                if ( count( $items ) >= $limit ) {
                    break;
                }
            }

            return array_filter( $items );
        }

        /**
         * Get listings that will expire soon.
         *
         * @param int $limit Query limit.
         *
         * @return array
         */
        private function get_expiring_listing_activity( $limit ) {
            $now       = current_time( 'mysql' );
            $threshold = gmdate( 'Y-m-d H:i:s', strtotime( '+30 days', current_time( 'timestamp' ) ) );
            $query     = new WP_Query(
                [
                    'post_type'              => ATBDP_POST_TYPE,
                    'post_status'            => 'publish',
                    'posts_per_page'         => $limit,
                    'orderby'                => 'meta_value',
                    'meta_key'               => '_expiry_date',
                    'order'                  => 'ASC',
                    'no_found_rows'          => true,
                    'update_post_meta_cache' => true,
                    'update_post_term_cache' => false,
                    'meta_query'             => [
                        'relation'     => 'AND',
                        [
                            'key'     => '_never_expire',
                            'compare' => 'NOT EXISTS',
                        ],
                        [
                            'key'     => '_expiry_date',
                            'value'   => [
                                $now,
                                $threshold,
                            ],
                            'compare' => 'BETWEEN',
                            'type'    => 'DATETIME',
                        ],
                    ],
                ]
            );
            $items        = [];
            $presentation = $this->get_activity_presentation( 'expiry' );

            foreach ( $query->posts as $listing ) {
                $expiry_value = (string) get_post_meta( $listing->ID, '_expiry_date', true );
                $expiry_gmt   = $expiry_value ? get_gmt_from_date( $expiry_value ) : '';
                $timestamp    = $expiry_gmt ? strtotime( $expiry_gmt . ' UTC' ) : false;

                if ( ! $timestamp ) {
                    continue;
                }

                $days = max( 1, (int) ceil( ( $timestamp - time() ) / DAY_IN_SECONDS ) );

                $items[] = $this->prepare_item(
                    [
                        'id'           => 'expiry-' . $listing->ID,
                        'type'         => 'listing',
                        'title'        => __( 'Listing expiring soon', 'directorist' ),
                        'subject'      => get_the_title( $listing ) ?: __( 'Untitled listing', 'directorist' ),
                        'context'      => sprintf(
                            /* translators: %d: Number of days before listing expiration. */
                            _n( 'Expires in %d day', 'Expires in %d days', $days, 'directorist' ),
                            $days
                        ),
                        'timestamp'    => $timestamp,
                        'icon'         => $presentation['icon'],
                        'tone'         => $presentation['tone'],
                        'action_label' => __( 'Review', 'directorist' ),
                        'action_url'   => get_edit_post_link( $listing->ID, 'raw' ),
                        'upcoming'     => true,
                    ]
                );
            }

            return array_filter( $items );
        }

        /**
         * Get the reference presentation shared by every activity of a type.
         *
         * @param string $activity_type Activity presentation type.
         *
         * @return array{icon:string,tone:string}
         */
        private function get_activity_presentation( $activity_type ) {
            $presentations = [
                'listing' => [
                    'icon' => 'las la-plus',
                    'tone' => 'blue',
                ],
                'review'  => [
                    'icon' => 'las la-star',
                    'tone' => 'green',
                ],
                'payment' => [
                    'icon' => 'las la-dollar-sign',
                    'tone' => 'violet',
                ],
                'user'    => [
                    'icon' => 'las la-user-plus',
                    'tone' => 'info',
                ],
                'expiry'  => [
                    'icon' => 'las la-hourglass-half',
                    'tone' => 'amber',
                ],
            ];

            return $presentations[ $activity_type ] ?? [
                'icon' => 'las la-history',
                'tone' => 'blue',
            ];
        }

        /**
         * Normalize one activity item.
         *
         * @param array $item Activity item.
         *
         * @return array|null
         */
        private function prepare_item( $item ) {
            $timestamp = ! empty( $item['timestamp'] ) ? absint( $item['timestamp'] ) : 0;
            $url       = ! empty( $item['action_url'] ) ? esc_url_raw( $item['action_url'] ) : '';

            if ( ! $timestamp || empty( $item['id'] ) || empty( $item['type'] ) || empty( $item['title'] ) ) {
                return null;
            }

            return [
                'id'           => sanitize_key( $item['id'] ),
                'type'         => sanitize_key( $item['type'] ),
                'title'        => sanitize_text_field( $item['title'] ),
                'subject'      => sanitize_text_field( $item['subject'] ?? '' ),
                'context'      => sanitize_text_field( $item['context'] ?? '' ),
                'timestamp'    => $timestamp,
                'icon'         => sanitize_text_field( $item['icon'] ?? 'las la-history' ),
                'tone'         => sanitize_key( $item['tone'] ?? 'blue' ),
                'action_label' => $url ? sanitize_text_field( $item['action_label'] ?? __( 'Open', 'directorist' ) ) : '',
                'action_url'   => $url,
                'upcoming'     => ! empty( $item['upcoming'] ),
            ];
        }

        /**
         * Get a post timestamp in UTC.
         *
         * @param WP_Post $post         Post object.
         * @param bool    $use_modified Use the modified date.
         *
         * @return int
         */
        private function get_post_timestamp( $post, $use_modified = false ) {
            $date = $use_modified ? $post->post_modified_gmt : $post->post_date_gmt;

            if ( ! $date || '0000-00-00 00:00:00' === $date ) {
                $date = get_gmt_from_date( $use_modified ? $post->post_modified : $post->post_date );
            }

            return absint( strtotime( $date . ' UTC' ) );
        }

        /**
         * Get the item's date group.
         *
         * @param array $item Activity item.
         *
         * @return string
         */
        private function get_group( $item ) {
            if ( ! empty( $item['upcoming'] ) ) {
                return 'upcoming';
            }

            $timestamp = (int) $item['timestamp'];
            $item_date = $this->format_site_date( 'Y-m-d', $timestamp );
            $today     = $this->format_site_date( 'Y-m-d', time() );
            $yesterday = $this->format_site_date( 'Y-m-d', time() - DAY_IN_SECONDS );

            if ( $item_date === $today ) {
                return 'today';
            }

            if ( $item_date === $yesterday ) {
                return 'yesterday';
            }

            return 'earlier';
        }

        /**
         * Get a localized group label.
         *
         * @param string $group Group key.
         *
         * @return string
         */
        private function get_group_label( $group ) {
            $labels = [
                'today'     => __( 'Today', 'directorist' ),
                'yesterday' => __( 'Yesterday', 'directorist' ),
                'earlier'   => __( 'Earlier', 'directorist' ),
                'upcoming'  => __( 'Upcoming', 'directorist' ),
            ];

            return $labels[ $group ] ?? $labels['earlier'];
        }

        /**
         * Get a concise localized time label.
         *
         * @param array $item Activity item.
         *
         * @return string
         */
        private function get_time_label( $item ) {
            $timestamp = (int) $item['timestamp'];
            $now       = time();

            if ( ! empty( $item['upcoming'] ) ) {
                return $this->format_site_date( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ), $timestamp );
            }

            if ( $timestamp <= $now && ( $now - $timestamp ) < DAY_IN_SECONDS ) {
                return sprintf(
                    /* translators: %s: Human-readable time difference. */
                    __( '%s ago', 'directorist' ),
                    human_time_diff( $timestamp, $now )
                );
            }

            return $this->format_site_date( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ), $timestamp );
        }

        /**
         * Format a UTC timestamp in the site timezone across supported WordPress versions.
         *
         * @param string $format    Date format.
         * @param int    $timestamp UTC timestamp.
         *
         * @return string
         */
        private function format_site_date( $format, $timestamp ) {
            if ( function_exists( 'wp_date' ) ) {
                return wp_date( $format, $timestamp );
            }

            $local_date = get_date_from_gmt( gmdate( 'Y-m-d H:i:s', $timestamp ) );

            return mysql2date( $format, $local_date, true );
        }
    }
}
