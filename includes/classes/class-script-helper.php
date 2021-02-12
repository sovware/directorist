<?php
namespace Directorist;

class Script_Helper {
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
    public static function get_search_script_data() {
        /*Internationalization*/
        $category_placeholder    = get_directorist_option( 'search_category_placeholder', __( 'Select a category', 'directorist' ) );
        $location_placeholder    = get_directorist_option( 'search_location_placeholder', __( 'Select a location', 'directorist' ) );
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
                'location_selection' => $location_placeholder,
                'category_selection' => $category_placeholder,
                'show_more'          => __( 'Show More', 'directorist' ),
                'show_less'          => __( 'Show Less', 'directorist' ),
                'added_favourite'    => __( 'Added to favorite', 'directorist' ),
                'please_login'          => __( 'Please login first', 'directorist' ),
                'select_listing_map' => $select_listing_map,
                'Miles'              => !empty( $_GET['miles'] ) ? $_GET['miles'] : $miles,
            ),
            'ajax_url'              => admin_url( 'admin-ajax.php' ),
            'Miles'                 => !empty( $_GET['miles'] ) ? $_GET['miles'] : $miles,
            'default_val'           => $default_radius_distance,
            'countryRestriction'    => get_directorist_option( 'country_restriction' ),
            'restricted_countries'  => get_directorist_option( 'restricted_countries' ),
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
            'PublicAssetPath'             => ATBDP_PUBLIC_ASSETS,
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
            'enable_reviewer_content'     => $enable_reviewer_content

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
}
