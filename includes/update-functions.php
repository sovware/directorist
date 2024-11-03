<?php
/**
 * Database update and migration functions.
 *
 * @since 7.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Directorist\Multi_Directory\Multi_Directory_Manager;

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
			wp_insert_comment( array(
				'comment_type'         => ( ( isset( $review->rating ) && $review->rating > 0 ) ? 'review' : 'comment' ),
				'comment_post_ID'      => $review->post_id,
				'comment_author'       => $review->name,
				'comment_author_email' => $review->email,
				'comment_content'      => $review->content,
				'comment_date'         => $review->date_created,
				'comment_date_gmt'     => $review->date_created,
				'user_id'              => ! empty( $review->by_user_id ) ? absint( $review->by_user_id ) : 0,
				'comment_approved'     => 1,
				'comment_meta'         => array(
					'rating' => $review->rating
				)
			) );
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
			wp_insert_comment( array(
				'comment_type'         => ( ( isset( $review->rating ) && $review->rating > 0 ) ? 'review' : 'comment' ),
				'comment_post_ID'      => $review->post_id,
				'comment_author'       => $review->author,
				'comment_author_email' => $review->author_email,
				'comment_content'      => $review->comment,
				'comment_date'         => $review->comment_date,
				'comment_date_gmt'     => $review->comment_date,
				'user_id'              => ! empty( $review->user_id ) ? absint( $review->user_id ) : 0,
				'comment_approved'     => _directorist_get_comment_status_by_review_status( $review->status ),
				'comment_meta'         => array(
					'rating' => $review->rating
				)
			) );
		}

		// Delete review type posts
		// TODO: Delete this in the future
		// foreach ( $reviews as $review ) {
		// 	wp_delete_post( $review->post_id, true );
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
	$statuses = array(
		'approved' => 1,
		'declined' => 'trash',
		'pending'  => 0,
	);

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
	$listings = new \WP_Query( array(
		'post_status'    => 'private',
		'post_type'      => ATBDP_POST_TYPE,
		'posts_per_page' => 10,
		'cache_results'  => false,
		'nopaging'       => true,
		'meta_key'       => '_listing_status',
		'meta_value'     => 'expired',
	) );

	while ( $listings->have_posts() ) {
		$listings->the_post();

		wp_update_post( array(
			'ID'          => get_the_ID(),
			'post_status' => 'expired',
		) );
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


function directorist_800_migrate_builder_data() {
	$migrated = get_option( 'directorist_builder_header_migrated', false );

	if ( $migrated ) {
		return;
	}

	//create account page
	$options = get_option( 'atbdp_option' );
	$account = wp_insert_post(
		array(
			'post_title' 	 => 'Sign In',
			'post_content' 	 => '[directorist_signin_signup]',
			'post_status' 	 => 'publish',
			'post_type' 	 => 'page',
			'comment_status' => 'closed'
		)
	);

	if ( $account ) {
		$options['signin_signup_page'] = (int) $account;
		$options['marker_shape_color'] 	= '#444752';
		$options['marker_icon_color'] 	= '#ffffff';
		
		update_option( 'atbdp_option', $options );
	}

	$directory_types = get_terms([
		'taxonomy'   => ATBDP_DIRECTORY_TYPE,
		'hide_empty' => false,
	]);

	if ( is_wp_error( $directory_types ) || empty( $directory_types ) ) {
		return;
	}

	foreach ( $directory_types as $directory_type ) {

		// backup the builder data
		Multi_Directory_Manager::builder_data_backup( $directory_type->term_id );
		//migrate custom field
		Multi_Directory_Manager::migrate_custom_field( $directory_type->term_id );
		//migrate review settings
		Multi_Directory_Manager::migrate_review_settings( $directory_type->term_id );
		//migrate contact form
		Multi_Directory_Manager::migrate_contact_owner_settings( $directory_type->term_id );
		//migrate related listing settings
		Multi_Directory_Manager::migrate_related_listing_settings( $directory_type->term_id );
		//migrate privacy policy
		Multi_Directory_Manager::migrate_privacy_policy( $directory_type->term_id );

		//migrate builder single listing header
		$new_structure   = [];
		$header_contents = get_term_meta( $directory_type->term_id, 'single_listing_header', true );

		if ( empty( $header_contents ) ) {
			continue;
		}

		$description = ! empty( $header_contents['options']['content_settings']['listing_description']['enable'] ) ? $header_contents['options']['content_settings']['listing_description']['enable'] : false;
		$tagline     = ! empty( $header_contents['options']['content_settings']['listing_title']['enable_tagline'] ) ? $header_contents['options']['content_settings']['listing_title']['enable_tagline'] : false;
		$contents    = get_term_meta( $directory_type->term_id, 'single_listings_contents', true );

		if ( $description ) {

			$contents['fields']['description'] = [
				"icon" => "las la-tag",
				"widget_group" => "preset_widgets",
				"widget_name" => "description",
				"original_widget_key" => "description",
				"widget_key" => "description"
			];

			$details = [
				"type" => "general_group",
				"label" => "Description",
				"fields" => [
					"description"
				],
				"section_id" => "1627188303" . $directory_type->term_id
			];

			array_unshift( $contents['groups'], $details );

			update_term_meta( $directory_type->term_id, 'single_listings_contents', $contents );

		}

		if ( empty( $header_contents['listings_header'] ) ) {
			continue;
		}

		foreach ( $header_contents['listings_header'] as $section_name => $widgets ) {

			if ( 'quick_actions' === $section_name ) {
				$quick_widget = [
					"type" => "placeholder_group",
					"placeholderKey" => "quick-widgets-placeholder",
					"placeholders" => [
						[
							"type" => "placeholder_group",
							"placeholderKey" => "quick-info-placeholder",
							"selectedWidgets" => [
								[
									"type" => "button",
									"label" => "Back",
									"widget_name" => "back",
									"widget_key" => "back"
								]
							]
						],
						[
							"type" => "placeholder_group",
							"placeholderKey" => "quick-action-placeholder",
							"selectedWidgets" => $widgets,
						]
					]
				];

				array_push( $new_structure, $quick_widget );
			}


			if ( 'thumbnail' === $section_name ) {
				$footer_thumbnail = ! empty( $widgets[0]['footer_thumbail'] ) ? $widgets[0]['footer_thumbail'] : true;
				$slider_widget = [
					"type" => "placeholder_item",
					"placeholderKey" => "slider-placeholder",
					"selectedWidgets" => [
						[
							"type" => "thumbnail",
							"label" => "Listing Image/Slider",
							"widget_name" => "slider",
							"widget_key" => "slider",
							'options'  => [
								'title'  => __( 'Listings Slider Settings', 'directorist' ),
								'fields' => [
									'footer_thumbnail' => [
										'type'  => 'toggle',
										'label' => __( 'Enable Footer Thumbnail', 'directorist' ),
										'value' => $footer_thumbnail,
									],
								],
							],
						]
					]
				];

				array_push( $new_structure, $slider_widget );
			}

			if ( 'quick_info' === $section_name ) {

				$title_widget = [
					"type" => "placeholder_item",
					"placeholderKey" => "listing-title-placeholder",
					"selectedWidgets" => [
						[
							"type" => "title",
							"label" => "Listing Title",
							"widget_name" => "title",
							"widget_key" => "title",
							'options' => [
								'title' => __( "Listing Title Settings", "directorist" ),
								'fields' => [
									'enable_tagline' => [
										'type' => "toggle",
										'label' => __( "Show Tagline", "directorist" ),
										'value' => $tagline,
									],
								],
							],
						]
					]
				];

				array_push( $new_structure, $title_widget );

				$more_widget = [
					"type" => "placeholder_item",
					"placeholderKey" => "more-widgets-placeholder",
					"selectedWidgets" => $widgets,
				];

				array_push( $new_structure, $more_widget );
			}

		}

		$new_structure = apply_filters( 'directorist_single_listing_header_migration_data', $new_structure, $header_contents );

		update_term_meta( $directory_type->term_id, 'single_listing_header', $new_structure );

	}

	update_option( 'directorist_builder_header_migrated', true );
}

function directorist_800_update_db_version() {
    \ATBDP_Installation::update_db_version( '8.0.0' );
}
