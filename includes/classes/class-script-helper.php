<?php
namespace Directorist;

class Script_Helper {
    /**
     * Load search form scripts
     *
     * @return array
     */
    public static function load_search_form_script( $args = [] ) {
        wp_enqueue_script( 'directorist-search-form-listing' );
		wp_enqueue_script( 'directorist-range-slider' );
		wp_enqueue_script( 'directorist-search-listing' );

        $directory_type_id = ( isset( $args['directory_type_id'] ) ) ? $args['directory_type_id'] : '';
		$data = self::get_search_script_data([
            'directory_type_id' => $directory_type_id
        ]);
		
        wp_localize_script( 'directorist-search-form-listing', 'atbdp_search_listing', $data );
		wp_localize_script( 'directorist-search-listing', 'atbdp_search', [
			'ajaxnonce' => wp_create_nonce('bdas_ajax_nonce'),
			'ajax_url' => admin_url('admin-ajax.php'),
		]);
        
		wp_localize_script( 'directorist-search-listing', 'atbdp_search_listing', $data );
		wp_localize_script( 'directorist-range-slider', 'atbdp_range_slider', $data );
    }


    /**
     * Get Main Script Data
     *
     * @return array
     */
    public static function get_main_script_data() {
        return self::get_listings_data();
    }

    /**
     * Get search listing Data
     *
     * @return array
     */
    public static function get_search_script_data( $args = [] ) {
 
        $directory_type = ( is_array( $args ) && isset( $args['directory_type_id'] ) ) ? $args['directory_type_id'] : default_directory_type();
        $directory_type_term_data = [
            'submission_form_fields' => get_term_meta( $directory_type, 'submission_form_fields', true ),
            'search_form_fields' => get_term_meta( $directory_type, 'search_form_fields', true ),
        ];

        /*Internationalization*/
        $category_placeholder    = ( isset( $directory_type_term_data['submission_form_fields']['fields']['category']['placeholder'] ) ) ? $directory_type_term_data['submission_form_fields']['fields']['category']['placeholder'] : __( 'Select a category', 'directorist' );
        $location_placeholder    = ( isset( $directory_type_term_data['submission_form_fields']['fields']['location']['placeholder'] ) ) ? $directory_type_term_data['submission_form_fields']['fields']['location']['placeholder'] : __( 'Select a location', 'directorist' );
        $select_listing_map      = get_directorist_option( 'select_listing_map', 'google' );
        $radius_search_unit      = get_directorist_option( 'radius_search_unit', 'miles' );
        $default_radius_distance = get_directorist_option( 'search_default_radius_distance', 0 );
        
        if ( 'kilometers' == $radius_search_unit ) {
            $miles = __( ' Kilometers', 'directorist' );
        } else {
            $miles = __( ' Miles', 'directorist' );
        }
        
        $data = array(
            'i18n_text'   => array(
                'category_selection' => ! empty( $category_placeholder ) ? $category_placeholder : __( 'Select a category', 'directorist' ),
                'location_selection' => ! empty( $location_placeholder ) ? $location_placeholder : __( 'Select a location', 'directorist' ),
                'show_more'          => __( 'Show More', 'directorist' ),
                'show_less'          => __( 'Show Less', 'directorist' ),
                'added_favourite'    => __( 'Added to favorite', 'directorist' ),
                'please_login'          => __( 'Please login first', 'directorist' ),
                'select_listing_map' => $select_listing_map,
                'Miles'              => !empty( $_GET['miles'] ) ? $_GET['miles'] : $miles,
            ),
            'args'                     => $args,
            'directory_type'           => $directory_type,
            'directory_type_term_data' => $directory_type_term_data,
            'ajax_url'                 => admin_url( 'admin-ajax.php' ),
            'miles'                    => !empty( $_GET['miles'] ) ? $_GET['miles'] : $miles,
            'default_val'              => $default_radius_distance,
            'countryRestriction'       => get_directorist_option( 'country_restriction' ),
            'restricted_countries'     => get_directorist_option( 'restricted_countries' ),
        );
        return $data;
    }

    /**
     * Get Admin Script Data
     *
     * @return array
     */
    public static function get_admin_script_data() {
        $font_type = get_directorist_option( 'font_type', 'line' );
        $icon_type = ( 'line' == $font_type ) ? 'la' : 'fa';

        $i18n_text = array(
            'confirmation_text'       => __( 'Are you sure', 'directorist' ),
            'ask_conf_sl_lnk_del_txt' => __( 'Do you really want to remove this Social Link!', 'directorist' ),
            'confirm_delete'          => __( 'Yes, Delete it!', 'directorist' ),
            'deleted'                 => __( 'Deleted!', 'directorist' ),
            'icon_choose_text'        => __( 'Select an icon', 'directorist' ),
            'upload_image'            => __( 'Select or Upload Slider Image', 'directorist' ),
            'upload_cat_image'        => __( 'Select Category Image', 'directorist' ),
            'choose_image'            => __( 'Use this Image', 'directorist' ),
            'select_prv_img'          => __( 'Select Preview Image', 'directorist' ),
            'insert_prv_img'          => __( 'Insert Preview Image', 'directorist' ),
        );
        // is MI extension enabled and active?
        $data = array(
            'nonce'                => wp_create_nonce( 'atbdp_nonce_action_js' ),
            'ajaxurl'              => admin_url( 'admin-ajax.php' ),
            'import_page_link'     => admin_url( 'edit.php?post_type=at_biz_dir&page=tools' ),
            'nonceName'            => 'atbdp_nonce_js',
            'countryRestriction'   => get_directorist_option( 'country_restriction' ),
            'restricted_countries' => get_directorist_option( 'restricted_countries' ),
            'AdminAssetPath'       => ATBDP_ADMIN_ASSETS,
            'i18n_text'            => $i18n_text,
            'icon_type'            => $icon_type
        );

        return $data;
    }

    /**
     * Get Listings Data
     *
     * @return void
     */
    public static function get_listings_data() {
        // listings data
        $review_approval = get_directorist_option( 'review_approval_text', __( 'Your review has been received. It requires admin approval to publish.', 'directorist' ) );
        $enable_reviewer_content = get_directorist_option( 'enable_reviewer_content', 1 );
        
        $data = array(
            'nonce'                       => wp_create_nonce( 'atbdp_nonce_action_js' ),
            'ajax_nonce'                  => wp_create_nonce( 'bdas_ajax_nonce' ),
            'ajaxurl'                     => admin_url( 'admin-ajax.php' ),
            'nonceName'                   => 'atbdp_nonce_js',
            'login_alert_message'         => __( 'Sorry, you need to login first.', 'directorist' ),
            'rtl'                         => is_rtl() ? 'true' : 'false',
            'warning'                     => __( 'WARNING!', 'directorist' ),
            'success'                     => __( 'SUCCESS!', 'directorist' ),
            'not_add_more_than_one'       => __( 'You can not add more than one review. Refresh the page to edit or delete your review!,', 'directorist' ),
            'duplicate_review_error'      => __( 'Sorry! your review already in process.', 'directorist' ),
            'review_success'              => __( 'Reviews Saved Successfully!', 'directorist' ),
            'review_approval_text'        => $review_approval,
            'review_error'                => __( 'Something went wrong. Check the form and try again!!!', 'directorist' ),
            'review_loaded'               => __( 'Reviews Loaded!', 'directorist' ),
            'review_not_available'        => __( 'NO MORE REVIEWS AVAILABLE!,', 'directorist' ),
            'review_have_not_for_delete'  => __( 'You do not have any review to delete. Refresh the page to submit new review!!!,', 'directorist' ),
            'review_sure_msg'             => __( 'Are you sure?', 'directorist' ),
            'review_want_to_remove'       => __( 'Do you really want to remove this review!', 'directorist' ),
            'review_delete_msg'           => __( 'Yes, Delete it!', 'directorist' ),
            'review_cancel_btn_text'      => __( 'Cancel', 'directorist' ),
            'review_wrong_msg'            => __( 'Something went wrong!, Try again', 'directorist' ),
            'listing_remove_title'        => __( 'Are you sure?', 'directorist' ),
            'listing_remove_text'         => __( 'Do you really want to delete this item?!', 'directorist' ),
            'listing_remove_confirm_text' => __( 'Yes, Delete it!', 'directorist' ),
            'listing_delete'              => __( 'Deleted!!', 'directorist' ),
            'listing_error_title'         => __( 'ERROR!!', 'directorist' ),
            'listing_error_text'          => __( 'Something went wrong!!!, Try again', 'directorist' ),
            'upload_pro_pic_title'        => __( 'Select or Upload a profile picture', 'directorist' ),
            'upload_pro_pic_text'         => __( 'Use this Image', 'directorist' ),
            'payNow'                      => __( 'Pay Now', 'directorist' ),
            'completeSubmission'          => __( 'Complete Submission', 'directorist' ),
            'waiting_msg'                 => __( 'Sending the message, please wait...', 'directorist' ),
            'plugin_url'                  => ATBDP_URL,
            'currentDate'                 => get_the_date(),
            'enable_reviewer_content'     => $enable_reviewer_content,
            'add_listing_data'            => self::get_add_listings_data()

        );

        return $data;
    }

    public static function get_add_listings_data() {

        $listing_id  = 0;
        $current_url = $current_url="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $current_listing_type = isset( $_GET['directory_type'] ) ? $_GET['directory_type'] : get_post_meta( $listing_id, '_directory_type', true );
        if( ! empty( $current_listing_type ) && ! is_numeric( $current_listing_type ) ) {
			$term = get_term_by( 'slug', $current_listing_type, ATBDP_TYPE );
			$current_listing_type = ! empty( $term ) ? $term->term_id : '';
		}
        if (  ( strpos( $current_url, '/edit/' ) !== false ) && ( $pagenow = 'at_biz_dir' ) ) {
            $arr = explode('/edit/', $current_url);
            $important = $arr[1];
            $listing_id = (int) $important;
        }

        $submission_form  = get_term_meta( $current_listing_type, 'submission_form_fields', true );
        $new_tag          = !empty( $submission_form['fields']['tag']['allow_new'] ) ? $submission_form['fields']['tag']['allow_new'] : '';
        $new_loc          = !empty( $submission_form['fields']['location']['create_new_loc'] ) ? $submission_form['fields']['location']['create_new_loc'] : '';
        $new_cat          = !empty( $submission_form['fields']['category']['create_new_cat'] ) ? $submission_form['fields']['category']['create_new_cat'] : '';
        $max_loc_creation = !empty( $submission_form['fields']['location']['max_location_creation'] ) ? $submission_form['fields']['location']['max_location_creation'] : '';
        // Internationalization text for javascript file especially add-listing.js

        $i18n_text = array(
            'confirmation_text'       => __( 'Are you sure', 'directorist' ),
            'ask_conf_sl_lnk_del_txt' => __( 'Do you really want to remove this Social Link!', 'directorist' ),
            'ask_conf_faqs_del_txt'   => __( 'Do you really want to remove this FAQ!', 'directorist' ),
            'confirm_delete'          => __( 'Yes, Delete it!', 'directorist' ),
            'deleted'                 => __( 'Deleted!', 'directorist' ),
            'max_location_creation'   => esc_attr( $max_loc_creation ),
            'max_location_msg'        => sprintf( __('You can only use %s', 'directorist'), $max_loc_creation ),
        );

        //get listing is if the screen in edit listing
        $fm_plans = get_post_meta( $listing_id, '_fm_plans', true );
        $data = array(
            'nonce'           => wp_create_nonce( 'atbdp_nonce_action_js' ),
            'ajaxurl'         => admin_url( 'admin-ajax.php' ),
            'nonceName'       => 'atbdp_nonce_js',
            'is_admin'		    => is_admin(),
            'media_uploader'  => apply_filters( 'atbdp_media_uploader', [
                [
                    'element_id'        => '_listing_gallery',
                    'meta_name'         => 'listing_img',
                    'files_meta_name'   => 'files_meta',
                    'error_msg'         => __('Listing gallery has invalid files', 'directorist'),
                ]
            ]),
            'i18n_text'       => $i18n_text,
            'create_new_tag'  => $new_tag,
            'create_new_loc'  => $new_loc,
            'create_new_cat'  => $new_cat,
            'image_notice'    => __( 'Sorry! You have crossed the maximum image limit', 'directorist' ),
        );


        return $data;
    }


    /**
     * Get Admin Script Dependency
     *
     * @return array
     */
    public static function get_admin_script_dependency() {
        $admin_scripts_dependency = [
            'jquery',
            'wp-color-picker',
            'sweetalert',
            'select2script',
            'bs-tooltip',
        ];

        $disable_map = get_directorist_option( 'display_map_field' );
        if ( ! empty( $disable_map ) ) {
            $admin_scripts_dependency[] = 'atbdp-google-map-front';
        }

        return $admin_scripts_dependency;
    }


    // ez_media_uploader
    public static function is_enable__ez_media_uploader() {
        
        
        return true;
    }


    // is_enable_map
    public static function is_enable_map( string $map_type = '' ) {
        $map_type_option = get_directorist_option('select_listing_map', 'openstreet');
        if ( ! empty( $map_type ) && $map_type !== $map_type_option ) { return false; }
        
        return true;
    }

    // add_listing_brfore_enqueue_task
    public static function add_listing_brfore_enqueue_task() {
        wp_enqueue_media();
    }
}
