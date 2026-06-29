<?php
/**
 * Database update and migration functions.
 *
 * @since 7.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function directorist_get_payment_currency_settings() {
    // Get the payment currency settings, and use the general currency settings if the payment currency setting is empty.
    $currency_settings = [
        'currency'            => get_directorist_option( 'payment_currency', directorist_get_currency() ),
        'thousands_separator' => get_directorist_option( 'payment_thousand_separator', get_directorist_option( 'g_thousand_separator', ',' ) ),
        'decimal_separator'   => get_directorist_option( 'payment_decimal_separator', get_directorist_option( 'g_decimal_separator', '.' ) ),
        'position'            => get_directorist_option( 'payment_currency_position', directorist_get_currency_position() ),

    ];

    return apply_filters( 'atbdp_payment_currency_settings', $currency_settings ); // return the currency settings array
}

function directorist_get_payment_currency() {
    $cs = directorist_get_payment_currency_settings();
    return ! empty( $cs['currency'] ) ? strtoupper( $cs['currency'] ) : 'USD';
}

// Migrate old reviews data from review table to comments table
function directorist_710_migrate_reviews_table_to_comments_table() {
    if ( get_option( 'directorist_old_reviews_table_migrated' ) ) {
        return;
    }

    global $wpdb;

    $review_table = $wpdb->prefix . 'atbdp_review';

    $review_table_exists = $wpdb->get_results( "SHOW TABLES LIKE '{$review_table}'" );

    // No need to move forward if table doesn't exist
    if ( empty( $review_table_exists ) ) {
        return;
    }

    $reviews = $wpdb->get_results( "SELECT * FROM {$review_table}" );

    if ( ! empty( $reviews ) ) {
        foreach ( $reviews as $review ) {
            wp_insert_comment(
                [
                    'comment_type'         => ( ( isset( $review->rating ) && $review->rating > 0 ) ? 'review' : 'comment' ),
                    'comment_post_ID'      => $review->post_id,
                    'comment_author'       => $review->name,
                    'comment_author_email' => $review->email,
                    'comment_content'      => $review->content,
                    'comment_date'         => $review->date_created,
                    'comment_date_gmt'     => $review->date_created,
                    'user_id'              => ! empty( $review->by_user_id ) ? absint( $review->by_user_id ) : 0,
                    'comment_approved'     => 1,
                    'comment_meta'         => [
                        'rating' => $review->rating
                    ]
                ]
            );
        }
    }

    update_option( 'directorist_old_reviews_table_migrated', 1, false );

    //Delete review table
    // TODO: Delete this table in future.
    // $wpdb->query( "DROP TABLE IF EXISTS {$review_table}" );
}

// pending -> pending:0
// declined -> trash
// approved -> approved:1
function directorist_710_migrate_posts_table_to_comments_table() {
    if ( get_option( 'directorist_old_reviews_posts_migrated' ) ) {
        return;
    }

    global $wpdb;

    $reviews = $wpdb->get_results(
        "SELECT posts_meta_join.post_id as meta_post_id,
			posts_meta_join.post_date AS comment_date,
			MAX(CASE WHEN posts_meta_join.meta_key = '_review_listing' THEN posts_meta_join.meta_value END) AS `post_id`,
			MAX(CASE WHEN posts_meta_join.meta_key = '_listing_reviewer' THEN posts_meta_join.meta_value END) AS `author`,
			MAX(CASE WHEN posts_meta_join.meta_key = '_email' THEN posts_meta_join.meta_value END) AS `author_email`,
			MAX(CASE WHEN posts_meta_join.meta_key = '_by_user_id' THEN posts_meta_join.meta_value END) AS `user_id`,
			MAX(CASE WHEN posts_meta_join.meta_key = '_by_guest' THEN posts_meta_join.meta_value END) AS `guest`,
			MAX(CASE WHEN posts_meta_join.meta_key = '_reviewer_details' THEN posts_meta_join.meta_value END) AS `comment`,
			MAX(CASE WHEN posts_meta_join.meta_key = '_reviewer_rating' THEN posts_meta_join.meta_value END) AS `rating`,
			MAX(CASE WHEN posts_meta_join.meta_key = '_review_status' THEN posts_meta_join.meta_value END) AS `status`
		FROM (SELECT posts_meta.post_id, posts_meta.meta_key, posts_meta.meta_value, posts.post_date FROM {$wpdb->posts} AS posts LEFT JOIN {$wpdb->postmeta} AS posts_meta ON posts.ID=posts_meta.post_id WHERE posts.post_type='atbdp_listing_review') AS posts_meta_join GROUP BY meta_post_id"
    );

    if ( ! empty( $reviews ) ) {
        foreach ( $reviews as $review ) {
            wp_insert_comment(
                [
                    'comment_type'         => ( ( isset( $review->rating ) && $review->rating > 0 ) ? 'review' : 'comment' ),
                    'comment_post_ID'      => $review->post_id,
                    'comment_author'       => $review->author,
                    'comment_author_email' => $review->author_email,
                    'comment_content'      => $review->comment,
                    'comment_date'         => $review->comment_date,
                    'comment_date_gmt'     => $review->comment_date,
                    'user_id'              => ! empty( $review->user_id ) ? absint( $review->user_id ) : 0,
                    'comment_approved'     => _directorist_get_comment_status_by_review_status( $review->status ),
                    'comment_meta'         => [
                        'rating' => $review->rating
                    ]
                ]
            );
        }

        // Delete review type posts
        // TODO: Delete this in the future
        // foreach ( $reviews as $review ) {
        //  wp_delete_post( $review->post_id, true );
        // }
    }

    update_option( 'directorist_old_reviews_posts_migrated', 1, false );
}

function directorist_710_review_rating_clear_transients() {
    global $wpdb;

    $listings = $wpdb->get_results( "SELECT listings.ID as id FROM {$wpdb->posts} AS listings WHERE listings.post_type='at_biz_dir' AND listings.comment_count >= 1" );

    if ( ! empty( $listings ) ) {
        foreach ( $listings as $listing ) {
            \Directorist\Review\Comment::maybe_clear_transients( $listing->id );
        }
    }
}

/**
 * Get wp comment status by review post type review status.
 *
 * @access private
 * @return mixed
 */
function _directorist_get_comment_status_by_review_status( $status = 'approved' ) {
    $statuses = [
        'approved' => 1,
        'declined' => 'trash',
        'pending'  => 0,
    ];

    return isset( $statuses[ $status ] ) ? $statuses[ $status ] : $statuses['pending'];
}

function directorist_710_update_db_version() {
    \ATBDP_Installation::update_db_version( '7.1.0' );
}

function directorist_7100_clean_falsy_never_expire_meta() {
    global $wpdb;

    $wp_postmeta = $wpdb->prefix . 'postmeta';
    $wp_posts    = $wpdb->prefix . 'posts';

    $query = "
		DELETE pm FROM {$wp_postmeta} AS pm
		LEFT JOIN {$wp_posts} AS posts ON (pm.post_id = posts.ID)
		WHERE posts.post_type = 'at_biz_dir'
			AND meta_key = '_never_expire'
			AND(meta_value IN('', 0, '0') || meta_value IS NULL);
	";

    $wpdb->query( $query );
}

function directorist_7100_migrate_expired_meta_to_expired_status( $updater ) {
    $listings = new \WP_Query(
        [
            'post_status'    => 'private',
            'post_type'      => ATBDP_POST_TYPE,
            'posts_per_page' => 10,
            'cache_results'  => false,
            'nopaging'       => true,
            'meta_key'       => '_listing_status',
            'meta_value'     => 'expired',
        ]
    );

    while ( $listings->have_posts() ) {
        $listings->the_post();

        wp_update_post(
            [
                'ID'          => get_the_ID(),
                'post_status' => 'expired',
            ]
        );
    }
    wp_reset_postdata();

    return $listings->have_posts();
}

function directorist_7100_clean_listing_status_expired_meta() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'postmeta';
    $meta_key = '_listing_status';
    $meta_value = 'expired';

    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM $table_name WHERE meta_key = %s AND meta_value = %s",
            $meta_key,
            $meta_value
        )
    );
}

function directorist_7100_update_db_version() {
    \ATBDP_Installation::update_db_version( '7.10.0' );
}

function directorist_7110_merge_dashboard_login_registration_page() {
    $migrated = get_option( 'directorist_merge_dashboard_login_reg_page', false );

    if ( $migrated ) {
        return;
    }

    update_option( 'directorist_merge_dashboard_login_reg_page', true );
}

function directorist_7110_update_db_version() {
    \ATBDP_Installation::update_db_version( '7.11.0' );
}

function directorist_7123_remove_upload_files_cap() {
    // contributor
    $contributor = get_role( 'contributor' );
    if ( $contributor ) {
        $contributor->remove_cap( 'upload_files' );
    }

    // subscriber
    $subscriber = get_role( 'subscriber' );
    if ( $subscriber ) {
        $subscriber->remove_cap( 'upload_files' );
    }

    // customer
    $customer = get_role( 'customer' );
    if ( $customer ) {
        $customer->remove_cap( 'upload_files' );
    }
}

function directorist_7123_update_db_version() {
    \ATBDP_Installation::update_db_version( '7.12.3' );
}

function directorist_800_update_db_version() {
    \ATBDP_Installation::update_db_version( '8.0.0' );
}

function directorist_830_update_db_version() {
    \ATBDP_Installation::update_db_version( '8.3.0' );
}

function directorist_830_sync_listing_author_and_order_author() {
    global $wpdb;

    $wpdb->query(
        $wpdb->prepare(
            "UPDATE wp_posts AS p
		INNER JOIN wp_postmeta AS pm
			ON p.ID = pm.post_id
			AND pm.meta_key = '_listing_id'
		INNER JOIN wp_posts AS p2
			ON p2.ID = CAST(pm.meta_value AS UNSIGNED)
		SET p.post_author = p2.post_author
		WHERE p.post_type = 'atbdp_orders'
		AND p.post_author <> p2.post_author;",
        )
    );
}

function directorist_850_migrate_archive_base() {
    $tag_base      = directorist_get_default_tag_base();
    $location_base = directorist_get_default_location_base();
    $category_base = directorist_get_default_category_base();

    if ( get_option( 'permalink_structure' ) ) {
        if ( get_directorist_option( 'single_tag_page' ) ) {
            $tag_base = basename( get_permalink( get_directorist_option( 'single_tag_page' ) ) );
        }

        if ( get_directorist_option( 'single_location_page' ) ) {
            $location_base = basename( get_permalink( get_directorist_option( 'single_location_page' ) ) );
        }

        if ( get_directorist_option( 'single_category_page' ) ) {
            $category_base = basename( get_permalink( get_directorist_option( 'single_category_page' ) ) );
        }
    }

    update_directorist_option( 'tag_base', $tag_base );
    update_directorist_option( 'category_base', $category_base );
    update_directorist_option( 'location_base', $location_base );
}

function directorist_850_update_db_version() {
    \ATBDP_Installation::update_db_version( '8.5.0' );
}

/**
 * Migrate legacy atbdp_orders posts into directorist_orders and directorist_payments tables.
 *
 * Follows the batched callback convention: return true to re-queue, false when done.
 *
 * @since 8.8.0
 * @return bool True if more posts remain, false when migration is complete.
 */
function directorist_880_migrate_legacy_orders() {
    global $wpdb;

    $orders_table   = $wpdb->prefix . 'directorist_orders';
    $payments_table = $wpdb->prefix . 'directorist_payments';

    // Guard: abort if target tables do not exist yet.
    $orders_exists   = $wpdb->get_var( "SHOW TABLES LIKE '{$orders_table}'" );
    $payments_exists = $wpdb->get_var( "SHOW TABLES LIKE '{$payments_table}'" );

    if ( ! $orders_exists || ! $payments_exists ) {
        error_log( 'Directorist 8.8.0 migration: target tables missing, skipping order migration.' );
        return false;
    }

    // Resolve site currency once for the entire batch.
    $currency = directorist_get_payment_currency();

    // Status normalization map: legacy _payment_status -> OrderStatus enum value.
    $status_map = [
        'completed' => 'paid',
        'created'   => 'unpaid',
        'pending'   => 'pending',
        'failed'    => 'failed',
        'cancelled' => 'cancelled',
        'refunded'  => 'refunded',
    ];

    // Query up to 100 eligible posts: exclude _fm_plan_ordered and already-migrated posts.
    $query = new WP_Query( [
        'post_type'      => 'atbdp_orders',
        'post_status'    => ['any', 'trash'],
        'posts_per_page' => 100,
        'cache_results'  => false,
        'no_found_rows'  => false,
        'orderby'        => 'date',
        'order'          => 'ASC',
        'meta_query'     => [
            'relation' => 'AND',
            [
                'key'     => '_fm_plan_ordered',
                'compare' => 'NOT EXISTS',
            ],
            [
                'key'     => '_is_migrated',
                'compare' => 'NOT EXISTS',
            ],
        ],
    ] );

    if ( ! $query->have_posts() ) {
        return false;
    }

    while ( $query->have_posts() ) {
        $query->the_post();
        $post = get_post( get_the_ID() );

        $post_id = (int) $post->ID;

        // Read legacy meta.
        $listing_id     = (int) get_post_meta( $post_id, '_listing_id', true );
        $amount         = (float) get_post_meta( $post_id, '_amount', true );
        $is_featured    = (int) get_post_meta( $post_id, '_featured', true );
        $payment_status = (string) get_post_meta( $post_id, '_payment_status', true );
        $transaction_id = get_post_meta( $post_id, '_transaction_id', true );
        $gateway        = (string) get_post_meta( $post_id, '_payment_gateway', true );

        // Normalize status.
        $status = isset( $status_map[ $payment_status ] ) ? $status_map[ $payment_status ] : 'pending';

        if ( $post->post_status === 'trash' ) {
            $status = 'cancelled';
        }

        // Insert into directorist_orders.
        $inserted = $wpdb->insert(
            $orders_table,
            [
                'legacy_id'          => $post_id,
                'subscription_id'    => null,
                'user_id'            => (int) $post->post_author,
                'listing_id'         => $listing_id ?: null,
                'is_featured_listing'=> $is_featured ? 1 : 0,
                'ref'                => null,
                'ref_type'           => $is_featured ? 'featured_listing' : null,
                'amount'             => $amount,
                'currency'           => $currency,
                'coupon_code'        => null,
                'coupon_discount'    => 0.00,
                'coupon_discount_type'=> null,
                'tax_rate'           => 0.00,
                'tax_type'           => null,
                'sub_total'          => $amount,
                'status'             => $status,
                'expires_at'         => null,
                'created_at'         => $post->post_date,
                'updated_at'         => $post->post_modified,
            ],
            [
                '%d', // legacy_id
                '%s', // subscription_id (null handled by wpdb)
                '%d', // user_id
                '%d', // listing_id
                '%d', // is_featured_listing
                '%s', // ref
                '%s', // ref_type
                '%f', // amount
                '%s', // currency
                '%s', // coupon_code
                '%f', // coupon_discount
                '%s', // coupon_discount_type
                '%f', // tax_rate
                '%s', // tax_type
                '%f', // sub_total
                '%s', // status
                '%s', // expires_at
                '%s', // created_at
                '%s', // updated_at
            ]
        );

        if ( false === $inserted ) {
            // Insert failed; skip this post without marking it migrated so it can be retried.
            continue;
        }

        $new_order_id = (int) $wpdb->insert_id;

        // Write _is_migrated immediately. If it fails, roll back the order row.
        $meta_saved = add_post_meta( $post_id, '_is_migrated', '1', true );

        if ( ! $meta_saved ) {
            $wpdb->delete( $orders_table, [ 'id' => $new_order_id ], [ '%d' ] );
            continue;
        }

        // Insert corresponding payment row.
        $wpdb->insert(
            $payments_table,
            [
                'order_id'       => $new_order_id,
                'amount'         => $amount,
                'currency'       => $currency,
                'status'         => $status,
                'transaction_id' => $transaction_id ?: null,
                'method'         => $gateway ?: '',
                'created_at'     => $post->post_date,
                'updated_at'     => $post->post_modified,
            ],
            [
                '%d', // order_id
                '%f', // amount
                '%s', // currency
                '%s', // status
                '%s', // transaction_id
                '%s', // method
                '%s', // created_at
                '%s', // updated_at
            ]
        );
    }

    wp_reset_postdata();

    // Check if any eligible posts remain to decide whether to re-queue.
    $remaining = new WP_Query( [
        'post_type'      => 'atbdp_orders',
        'post_status'    => ['any', 'trash'],
        'posts_per_page' => 1,
        'no_found_rows'  => false,
        'fields'         => 'ids',
        'meta_query'     => [
            'relation' => 'AND',
            [
                'key'     => '_fm_plan_ordered',
                'compare' => 'NOT EXISTS',
            ],
            [
                'key'     => '_is_migrated',
                'compare' => 'NOT EXISTS',
            ],
        ],
    ] );

    return $remaining->found_posts > 0;
}

function directorist_880_update_db_version() {
    \ATBDP_Installation::update_db_version( '8.8.0' );
}
