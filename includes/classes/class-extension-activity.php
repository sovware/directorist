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

            return [
                'published_listings' => $published_listings,
                'listing_views'      => $listing_views,
                'pending_listings'   => $pending_listings,
                'expiring_this_week' => $this->get_expiring_listing_count( 7 ),
                'revenue'            => (float) $payment_stats['amount'],
                'paid_orders'        => (int) $payment_stats['count'],
                'currency'           => atbdp_get_payment_currency(),
            ];
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
            $active_gateways = ATBDP_Gateway::get_active_gateways();
            $active_gateways = is_array( $active_gateways ) ? array_filter( $active_gateways ) : [];
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
                    'label'    => ! empty( $active_gateways )
                        ? __( 'Review payment gateways', 'directorist' )
                        : __( 'Configure a payment gateway', 'directorist' ),
                    'complete' => ! empty( $active_gateways ),
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
            $items = [];

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
                        'icon'         => 'la la-plus',
                        'tone'         => 'blue',
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
            $items = [];

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
                        'icon'         => 'la la-star',
                        'tone'         => 'green',
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
                        'icon'         => 'la la-dollar',
                        'tone'         => 'violet',
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

            $items = [];

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
                        'icon'         => 'la la-dollar',
                        'tone'         => 'violet',
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
            $items = [];

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
                        'icon'         => 'la la-user-plus',
                        'tone'         => 'info',
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
            $items = [];

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
                        'icon'         => 'la la-hourglass-half',
                        'tone'         => 'amber',
                        'action_label' => __( 'Review', 'directorist' ),
                        'action_url'   => get_edit_post_link( $listing->ID, 'raw' ),
                        'upcoming'     => true,
                    ]
                );
            }

            return array_filter( $items );
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
                'icon'         => sanitize_text_field( $item['icon'] ?? 'la la-history' ),
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
