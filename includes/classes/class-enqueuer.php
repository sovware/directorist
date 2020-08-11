<?php

if ( ! class_exists( 'ATBDP_Enqueuer' ) ):
    class ATBDP_Enqueuer {
        /**
         * Whether to enable multiple image upload feature on add listing page
         *
         * Default behavior is to not to show multiple image upload feature
         * evaluated to true.
         *
         * @since 1.2.2
         * @access public
         * @var bool
         */
        public $enable_multiple_image = 0;

        public function __construct() {
            add_action( 'wp_enqueue_scripts', array( $this, 'custom_color_picker_scripts' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
            // best hook to enqueue scripts for front-end is 'template_redirect'
            // 'Professional WordPress Plugin Development' by Brad Williams
            add_action( 'wp_enqueue_scripts', array( $this, 'front_end_enqueue_scripts' ), -10 );
            add_action( 'wp_enqueue_scripts', array( $this, 'search_listing_scripts_styles' ) );

        }

        public function custom_color_picker_scripts() {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script(
                'iris',
                admin_url( 'js/iris.min.js' ),
                array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
                false,
                1
            );
            wp_enqueue_script(
                'wp-color-picker',
                admin_url( 'js/color-picker.min.js' ),
                array( 'iris', 'wp-i18n' ),
                false,
                1
            );
            wp_enqueue_script( 'wp-color-picker' );

            $colorpicker_l10n = array(
                'clear'         => __( 'Clear' ),
                'defaultString' => __( 'Default' ),
                'pick'          => __( 'Select Color' ),
                'current'       => __( 'Current Color' ),
            );
            wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n );

        }

        public function admin_enqueue_scripts( $page ) {
            if ( is_admin() ) {
                wp_register_script( 'extension-update', ATBDP_ADMIN_ASSETS . 'js/extension-update.js', array( 'jquery' ), ATBDP_VERSION, true );
                wp_enqueue_script( 'extension-update' );
                if('at_biz_dir_page_tools' === $page){
                    wp_register_script( 'atbdp-import-export', ATBDP_ADMIN_ASSETS . 'js/import-export.js', array( 'jquery' ), ATBDP_VERSION, true );
                    wp_enqueue_script( 'atbdp-import-export' );
                    $tool_data = array(
                        'ajaxurl'        => admin_url( 'admin-ajax.php' ),
                    );
                    wp_localize_script( 'atbdp-import-export', 'import_export_data', $tool_data );
                }
            }
            global $typenow;

            if ( ATBDP_POST_TYPE == $typenow ) {
                // make a list of dependency of admin script and then add to this list some other scripts using different conditions to handle different situation.
                $admin_scripts_dependency = array(
                    'jquery',
                    'wp-color-picker',
                    'sweetalert',
                    'select2script',
                    'bs-tooltip',
                );
                $disable_map = get_directorist_option( 'display_map_field' );
                if ( ! empty( $disable_map ) ) {
                    // get the map api from the user settings
                    $map_api_key = get_directorist_option( 'map_api_key', 'AIzaSyCwxELCisw4mYqSv_cBfgOahfrPFjjQLLo' ); // eg. zaSyBtTwA-Y_X4OMsIsc9WLs7XEqavZ3ocQLQ
                    //Google map needs to be enqueued from google server with a valid API key. So, it is not possible to store map js file locally as this file will be unique for all users based on their MAP API key.
                    wp_register_script( 'atbdp-google-map-admin', '//maps.googleapis.com/maps/api/js?key=' . $map_api_key . '&libraries=places', false, ATBDP_VERSION, true );
                    $admin_scripts_dependency[] = 'atbdp-google-map-admin';
                }

                //Register all styles for the admin pages
                /*Public Common Asset: */

                wp_register_style( 'atbdp-font-awesome', ATBDP_PUBLIC_ASSETS . 'css/font-awesome.min.css', false, ATBDP_VERSION );
                wp_register_style( 'atbdp-line-awesome', ATBDP_PUBLIC_ASSETS . 'css/line-awesome.min.css', false, ATBDP_VERSION );
                wp_register_style( 'sweetalertcss', ATBDP_PUBLIC_ASSETS . 'css/sweetalert.min.css', false, ATBDP_VERSION );
                wp_register_style( 'select2style', ATBDP_PUBLIC_ASSETS . 'css/select2.min.css', false, ATBDP_VERSION );
                wp_register_style( 'atbdp-admin-bootstrap-style', ATBDP_PUBLIC_ASSETS . 'css/bootstrap.css', false, ATBDP_VERSION );
                wp_register_style( 'atbdp-admin', ATBDP_ADMIN_ASSETS . 'css/style.css', array( 'atbdp-line-awesome', 'atbdp-font-awesome', 'select2style' ), ATBDP_VERSION );
                wp_register_style( 'atbdp-pluploadcss', ATBDP_ADMIN_ASSETS . 'css/directorist-plupload.min.css', array( 'atbdp-font-awesome', 'select2style' ), ATBDP_VERSION );

                wp_register_script( 'sweetalert', ATBDP_PUBLIC_ASSETS . 'js/sweetalert.min.js', array( 'jquery' ), ATBDP_VERSION, true );

                /*Public Common Asset: */
                wp_register_script( 'select2script', ATBDP_PUBLIC_ASSETS . 'js/select2.min.js', array( 'jquery' ), ATBDP_VERSION, true );
                wp_register_script( 'popper', ATBDP_ADMIN_ASSETS . 'js/popper.min.js', array( 'jquery' ), ATBDP_VERSION, true );
                wp_register_script( 'bs-tooltip', ATBDP_ADMIN_ASSETS . 'js/tooltip.js', array( 'jquery', 'popper' ), ATBDP_VERSION, true );
                wp_register_script( 'atbdp-admin-script', ATBDP_ADMIN_ASSETS . 'js/main.js', $admin_scripts_dependency, ATBDP_VERSION, true );
                wp_register_script( 'atbdp-plupload-min', ATBDP_ADMIN_ASSETS . 'js/directorist-plupload.min.js', array( 'jquery' ), ATBDP_VERSION );
                wp_register_script( 'atbdp-plupload', ATBDP_ADMIN_ASSETS . 'js/directorist-plupload.js', array( 'atbdp-plupload-min', 'jquery-ui-datepicker' ), ATBDP_VERSION );
                // Styles
                //@todo; later minify the bootstrap
                /*@todo; we can also load scripts and style using very strict checking like loading based on post.php or edit.php etc. For example, sweetalert should be included in the post.php file where the user will add/edit listing */
                /* enqueue all styles*/

                wp_enqueue_style( 'atbdp-admin-bootstrap-style' );
                wp_enqueue_style( 'atbdp-font-awesome' );
                wp_enqueue_style( 'atbdp-line-awesome' );
                wp_enqueue_style( 'sweetalertcss' );
                wp_enqueue_style( 'select2style' );
                wp_enqueue_style( 'custom-colors' );
                /* WP COLOR PICKER */
                wp_enqueue_style( 'wp-color-picker' );

                /* Enqueue all scripts */
                //wp_enqueue_script('atbdp-bootstrap'); // maybe we do not need the bootstrap js in the admin panel at the moment.
                wp_enqueue_script( 'sweetalert' );
                if ( ! $disable_map ) {wp_enqueue_script( 'atbdp-google-map-admin' );}
                wp_enqueue_script( 'select2script' );
                wp_enqueue_script( 'atbdp-admin-script' );

                // Internationalization text for javascript file especially add-listing.js
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
                    'nonce'          => wp_create_nonce( 'atbdp_nonce_action_js' ),
                    'ajaxurl'        => admin_url( 'admin-ajax.php' ),
                    'import_page_link'      => admin_url( 'edit.php?post_type=at_biz_dir&page=tools' ),
                    'nonceName'      => 'atbdp_nonce_js',
                    'AdminAssetPath' => ATBDP_ADMIN_ASSETS,
                    'i18n_text'      => $i18n_text,
                );
                wp_localize_script( 'atbdp-admin-script', 'atbdp_admin_data', $data );
                wp_enqueue_media();

            }

            if ( ATBDP_CUSTOM_FIELD_POST_TYPE == $typenow ) {
                wp_enqueue_style( 'atbdp-custom-field', ATBDP_PUBLIC_ASSETS . 'css/bootstrap.css', false, ATBDP_VERSION );

            }
            // lets add a small stylesheet for order listing page
            if (  ( ATBDP_ORDER_POST_TYPE || ATBDP_CUSTOM_FIELD_POST_TYPE ) == $typenow ) {
                wp_enqueue_style( 'atbdp-admin', ATBDP_ADMIN_ASSETS . 'css/style.css', false, ATBDP_VERSION );
            }
        }

        /**
         * It loads all scripts for front end if the current post type is our custom post type
         * @param bool $force [optional] whether to load the style in the front end forcibly(even if the post type is not our custom post). It is needed for enqueueing file from a inside the short code call
         */
        public function front_end_enqueue_scripts( $force = false ) {
            global $typenow, $post;
            $select_listing_map       = get_directorist_option( 'select_listing_map', 'google' );
            $front_scripts_dependency = array( 'jquery' );
            $disable_map              = false;
            if ( ! $disable_map ) {
                if ( 'google' == $select_listing_map ) {
                    // get the map api from the user settings
                    $map_api_key = get_directorist_option( 'map_api_key', 'AIzaSyCwxELCisw4mYqSv_cBfgOahfrPFjjQLLo' ); // eg. zaSyBtTwA-Y_X4OMsIsc9WLs7XEqavZ3ocQLQ
                    //Google map needs to be enqueued from google server with a valid API key. So, it is not possible to store map js file locally as this file will be unique for all users based on their MAP API key.
                    wp_register_script( 'atbdp-google-map-front', '//maps.googleapis.com/maps/api/js?key=' . $map_api_key . '&libraries=places', false, ATBDP_VERSION, true );
                    wp_register_script( 'atbdp-markerclusterer', ATBDP_PUBLIC_ASSETS . 'js/markerclusterer.js', array( 'atbdp-google-map-front' ) );
                    $front_scripts_dependency[] = 'atbdp-google-map-front';
                } elseif ( 'openstreet' == $select_listing_map ) {
                wp_enqueue_style( 'leaf-style', ATBDP_URL . 'templates/front-end/all-listings/maps/openstreet/css/openstreet.css' );
                wp_enqueue_script( 'unpkg', ATBDP_URL . 'templates/front-end/all-listings/maps/openstreet/js/unpkg-min.js' );
                wp_enqueue_script( 'unpkg-index', ATBDP_URL . 'templates/front-end/all-listings/maps/openstreet/js/unpkg-index.js', array( 'unpkg' ) );
                wp_enqueue_script( 'unpkg-libs', ATBDP_URL . 'templates/front-end/all-listings/maps/openstreet/js/unpkg-libs.js', array( 'unpkg-index' ) );
                wp_enqueue_script( 'leaflet-versions', ATBDP_URL . 'templates/front-end/all-listings/maps/openstreet/js/leaflet-versions.js', array( 'unpkg-libs' ) );
                wp_enqueue_script( 'markercluster-version', ATBDP_URL . 'templates/front-end/all-listings/maps/openstreet/js/leaflet.markercluster-versions.js', array( 'leaflet-versions' ) );
                wp_enqueue_script( 'leaflet-setup', ATBDP_URL . 'templates/front-end/all-listings/maps/openstreet/js/libs-setup.js', array( 'markercluster-version' ) );
            }
        }
        // Registration of all styles and js for the front end should be done here
        // but the inclusion should be limited to the scope of the page user viewing.
        // This way,  we can just enqueue any styles and scripts in side the shortcode or any other functions.

        // @Todo; make unminified css minified then enqueue them.
        wp_register_style( 'atbdp-bootstrap-style', ATBDP_PUBLIC_ASSETS . 'css/bootstrap.css', false, ATBDP_VERSION );
        wp_register_style( 'atbdp-bootstrap-style-rtl', ATBDP_PUBLIC_ASSETS . 'css/bootstrap-rtl.css', false, ATBDP_VERSION );
        wp_register_style( 'atbdp-font-awesome', ATBDP_PUBLIC_ASSETS . 'css/font-awesome.min.css', false, ATBDP_VERSION );
        wp_register_style( 'atbdp-line-awesome', ATBDP_PUBLIC_ASSETS . 'css/line-awesome.min.css', false, ATBDP_VERSION );
        wp_register_style( 'sweetalertcss', ATBDP_PUBLIC_ASSETS . 'css/sweetalert.min.css', false, ATBDP_VERSION );
        wp_register_style( 'select2style', ATBDP_PUBLIC_ASSETS . 'css/select2.min.css', false, ATBDP_VERSION );
        wp_register_style( 'slickcss', ATBDP_PUBLIC_ASSETS . 'css/slick.css', false, ATBDP_VERSION );
        wp_register_style( 'atmodal', ATBDP_PUBLIC_ASSETS . 'css/atmodal.css', false, ATBDP_VERSION );
        wp_register_style( 'atbd_googlefonts', '//fonts.googleapis.com/css?family=Roboto:400,500', false, ATBDP_VERSION );
        wp_register_style( 'atbdp-style', ATBDP_PUBLIC_ASSETS . 'css/style.css', array( 'atbdp-font-awesome', 'atbdp-line-awesome' ), ATBDP_VERSION );
        if ( is_rtl() ) {
            wp_register_style( 'atbdp-media-uploader-style-rtl', ATBDP_PUBLIC_ASSETS . 'css/ez-media-uploader-rtl.css', array( 'atbdp-font-awesome', 'atbdp-line-awesome' ), ATBDP_VERSION );
        } else {
            wp_register_style( 'atbdp-media-uploader-style', ATBDP_PUBLIC_ASSETS . 'css/ez-media-uploader.css', array( 'atbdp-font-awesome', 'atbdp-line-awesome' ), ATBDP_VERSION );
        }
        wp_register_style( 'atbdp-style-rtl', ATBDP_PUBLIC_ASSETS . 'css/style-rtl.css', array( 'atbdp-font-awesome', 'atbdp-line-awesome' ), ATBDP_VERSION );
        wp_register_style( 'atbdp-pluploadcss', ATBDP_ADMIN_ASSETS . 'css/directorist-plupload.min.css', array( 'atbdp-font-awesome', 'select2style' ), ATBDP_VERSION );

        wp_register_script( 'atbdp-popper-script', ATBDP_PUBLIC_ASSETS . 'js/popper.js', array( 'jquery' ), ATBDP_VERSION, false );
        if ( ! get_directorist_option( 'fix_js_conflict' ) ) {
            wp_register_script( 'atbdp-bootstrap-script', ATBDP_PUBLIC_ASSETS . 'js/bootstrap.min.js', array( 'jquery', 'atbdp-popper-script' ), ATBDP_VERSION, true );
        }

        wp_register_script( 'atbdp_form_handler', ATBDP_PUBLIC_ASSETS . 'js/atbdp-form-handler.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_enqueue_script( 'atbdp_form_handler' );

        wp_register_script( 'atbdp_media_uploader', ATBDP_PUBLIC_ASSETS . 'js/ez-media-uploader.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_enqueue_script( 'atbdp_media_uploader' );

        wp_register_script('openstreet_layer', ATBDP_PUBLIC_ASSETS . 'js/openstreetlayers.js', array('jquery'), ATBDP_VERSION, true);
        wp_register_style('leaflet-css', ATBDP_PUBLIC_ASSETS . 'css/leaflet.css');

        wp_register_script( 'atbdp-plasma-slider-script', ATBDP_PUBLIC_ASSETS . 'js/plasma-slider.js', null, ATBDP_VERSION, true );
        wp_register_script( 'atbdp-rating', ATBDP_PUBLIC_ASSETS . 'js/jquery.barrating.min.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_register_script( 'atbdp-uikit', ATBDP_PUBLIC_ASSETS . 'js/uikit.min.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_register_script( 'atbdp-uikit-grid', ATBDP_PUBLIC_ASSETS . 'js/grid.min.js', array( 'jquery', 'atbdp-uikit' ), ATBDP_VERSION, true );
        wp_register_script( 'sweetalert', ATBDP_PUBLIC_ASSETS . 'js/sweetalert.min.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_register_script( 'atbdp-user-dashboard', ATBDP_PUBLIC_ASSETS . 'js/user-dashboard.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_register_script( 'select2script', ATBDP_PUBLIC_ASSETS . 'js/select2.min.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_register_script( 'atbdp_validator', ATBDP_PUBLIC_ASSETS . 'js/validator.min.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_register_script( 'atbdp_checkout_script', ATBDP_PUBLIC_ASSETS . 'js/checkout.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_register_script( 'atbdp_slick_slider', ATBDP_PUBLIC_ASSETS . 'js/slick.min.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_register_script( 'adminmainassets', ATBDP_PUBLIC_ASSETS . 'js/main.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_register_script( 'loc_cat_assets', ATBDP_PUBLIC_ASSETS . 'js/loc_cat.js', array( 'jquery' ), ATBDP_VERSION, true );
        $modal_dependency = apply_filters( 'atbdp_modal_dependency', array( 'jquery' ) );
        wp_register_script( 'at_modal', ATBDP_PUBLIC_ASSETS . 'js/atmodal.js', $modal_dependency, ATBDP_VERSION, true );
        wp_register_script( 'atbdp_open_street', ATBDP_PUBLIC_ASSETS . 'openstreet/openlayers/OpenLayers.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_register_script( 'atbdp_open_street_src', ATBDP_PUBLIC_ASSETS . 'openstreet/openlayers4jgsi/Crosshairs.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_register_script( 'atbdp-plupload-min', ATBDP_ADMIN_ASSETS . 'js/directorist-plupload.min.js', array( 'jquery' ), ATBDP_VERSION );
        wp_register_script( 'atbdp-plupload', ATBDP_ADMIN_ASSETS . 'js/directorist-plupload.js', array( 'atbdp-plupload-min' ), ATBDP_VERSION );
        wp_register_script( 'atbdp-geolocation', ATBDP_PUBLIC_ASSETS . 'js/geolocation.js', array( 'jquery' ), ATBDP_VERSION );
        wp_register_script( 'atbdp-geolocation-widget', ATBDP_PUBLIC_ASSETS . 'js/geolocation-widget.js', array( 'jquery' ), ATBDP_VERSION );
        wp_register_script( 'atbdp-range-slider', ATBDP_PUBLIC_ASSETS . 'js/range-slider.js', array(), ATBDP_VERSION, true );
        
        wp_register_script( 'atbdp-related-listings-slider', ATBDP_PUBLIC_ASSETS . 'js/releated-listings-slider.js', array( 'atbdp_slick_slider', 'jquery' ), ATBDP_VERSION, true );

        wp_register_script( 'atbdp-search-listing', ATBDP_PUBLIC_ASSETS . 'js/search-form-listing.js', array(), ATBDP_VERSION, true );

        wp_register_script( 'leaflet-load-scripts', ATBDP_PUBLIC_ASSETS . 'js/openstreet-map/load-scripts.js', array(), ATBDP_VERSION, true );
        wp_register_script( 'leaflet-subgroup-realworld', ATBDP_URL . 'public/assets/js/openstreet-map/_subGroup-markercluster-controlLayers-realworld.388.js');
        
        wp_register_script( 'atbdp-map-view', ATBDP_PUBLIC_ASSETS . 'js/map-view.js' );
        wp_register_script( 'atbdp-range-slider-rtl', ATBDP_PUBLIC_ASSETS . 'js/range-slider-rtl.js', array(), ATBDP_VERSION, true );

        // we need select2 js on taxonomy edit screen to let the use to select the fonts-awesome icons ans search the icons easily
        // @TODO; make the styles and the scripts specific to the scripts where they are used specifically. For example. load select2js scripts and styles in
        wp_enqueue_style( 'select2style' );
        wp_enqueue_script( 'select2script' );
        wp_enqueue_script( 'atbdp_validator' );
        wp_enqueue_script( 'at_modal' );
        $disable_map = get_directorist_option( 'display_map_field' );
        if ( ! empty( $disable_map ) ) {
            wp_enqueue_script( 'atbdp_open_street' );
            wp_enqueue_script( 'atbdp_open_street_src' );
        }
        /* Enqueue all styles*/
        if ( is_rtl() ) {
            wp_enqueue_style( 'atbdp-bootstrap-style-rtl' );
            wp_enqueue_style( 'atbdp-style-rtl' );
            wp_enqueue_style( 'atbdp-media-uploader-style-rtl' );
        } else {
            wp_enqueue_style( 'atbdp-bootstrap-style' );
            wp_enqueue_style( 'atbdp-style' );
            wp_enqueue_style( 'atbdp-media-uploader-style' );
        }
        wp_enqueue_style( 'atbdp-font-awesome' );
        wp_enqueue_style( 'atbdp-line-awesome' );

        /* Enqueue google Directorist google font */
        wp_enqueue_style( 'atbd_googlefonts' );
        wp_enqueue_style( 'slickcss' );
        wp_enqueue_style( 'atmodal' );

        /* Enqueue all scripts */
        wp_enqueue_script( 'atbdp-bootstrap-script' );
        wp_enqueue_script( 'atbdp-rating' );
        wp_enqueue_script( 'atbdp-plasma-slider-script' );
        if ( ! empty( $disable_map ) && 'google' == $select_listing_map ) {
            wp_enqueue_script( 'atbdp-google-map-front' );
            wp_enqueue_script( 'atbdp-markerclusterer' );
        }
        wp_enqueue_script( 'atbdp-uikit' );
        wp_enqueue_script( 'atbdp-uikit-grid' );
        wp_enqueue_script( 'atbdp_slick_slider' );

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );

        wp_register_script( 'leaflet-subgroup-realworld', ATBDP_URL . 'templates/front-end/all-listings/maps/openstreet/js/subGroup-merkercluster-controlLayers-realworld.388.js' );

        //listings data

        $review_approval = get_directorist_option( 'review_approval_text', __( 'Your review has been received. It requires admin approval to publish.', 'directorist' ) );
        $enable_reviewer_content = get_directorist_option( 'enable_reviewer_content', 1 ); 
        $data            = array(
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
            'plugin_url'                  => ATBDP_URL,
            'currentDate'                 => get_the_date(),
            'enable_reviewer_content'     => $enable_reviewer_content

        );
        wp_localize_script( 'atbdp_checkout_script', 'atbdp_checkout', $data );

        // enqueue the style and the scripts on the page when the post type is our registered post type.
        if (  ( is_object( $post ) && ATBDP_POST_TYPE == $post->post_type ) || $force ) {
            wp_enqueue_style( 'sweetalertcss' );
            wp_enqueue_script( 'sweetalert' );
            wp_enqueue_script( 'atbdp-public-script', ATBDP_PUBLIC_ASSETS . 'js/main.js', apply_filters( 'atbdp_front_script_dependency', $front_scripts_dependency ), ATBDP_VERSION, true );

            wp_localize_script( 'atbdp-public-script', 'atbdp_public_data', $data );
            wp_enqueue_style( 'wp-color-picker' );

            wp_enqueue_media();
        }

        wp_register_style('atbdp-search-style-rtl', ATBDP_PUBLIC_ASSETS . 'css/search-style-rtl.css');
        wp_register_style('atbdp-search-style', ATBDP_PUBLIC_ASSETS . 'css/search-style.css');

        // Template Scrips
        wp_register_script( 'atbdp-single-listing-osm', ATBDP_PUBLIC_ASSETS . 'js/template-scripts/single-listing/openstreet-map.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_register_script( 'atbdp-single-listing-gmap', ATBDP_PUBLIC_ASSETS . 'js/template-scripts/single-listing/google-map.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_register_script( 'atbdp-add-listing-osm', ATBDP_PUBLIC_ASSETS . 'js/template-scripts/add-listing/openstreet-map.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_register_script( 'atbdp-add-listing-gmap', ATBDP_PUBLIC_ASSETS . 'js/template-scripts/add-listing/google-map.js', array( 'jquery' ), ATBDP_VERSION, true );

        // Inline Style
        wp_register_style( 'atbdp-inline-style', ATBDP_PUBLIC_ASSETS . 'css/inline-style.css', false, ATBDP_VERSION );

        // Settings Style
        wp_register_style( 'atbdp-settings-style', ATBDP_PUBLIC_ASSETS . 'css/settings-style.css', false, ATBDP_VERSION );
        wp_add_inline_style( 'atbdp-settings-style', ATBDP_Stylesheet::style_settings_css() );

        $this->load_template_scripts();
    }

    // load_template_scripts
    public function load_template_scripts() {
        $enqueue_inline = false;
        $is_single_listing_page = is_singular( ATBDP_POST_TYPE );

        // Settings Style
        $stylesheet_is_required = $this->has_shortcodes_in_post([
            'directorist_search_listing',
            'directorist_all_listing',
            'directorist_all_categories',
            'directorist_all_locations',
            'directorist_category',
            'directorist_location',
            'directorist_tag',
            'directorist_author_profile',
            'directorist_custom_registration',
            'directorist_user_login',
            'directorist_user_dashboard',
            'directorist_checkout',
            'directorist_add_listing',
            'directorist_listing_custom_fields',
        ]);

        $include = apply_filters( 'include_style_settings', true );

        if ( $include && $stylesheet_is_required ) {
            wp_enqueue_style( 'atbdp-settings-style' );
        }
        
        // Map Scripts
        $disable_map     = get_directorist_option( 'display_map_field' );
        $map_type        = get_directorist_option('select_listing_map', 'openstreet');
        $map_is_required = $this->has_shortcodes_in_post([
            'directorist_search_listing',
            'directorist_all_listing',
            'directorist_search_result',
            'directorist_category',
            'directorist_location',
            'directorist_tag',
            'directorist_add_listing',
            'directorist_listing_map',
        ]);

        if ( $map_is_required || $is_single_listing_page ) {
            if ( 'openstreet' === $map_type ) {
                wp_enqueue_style('leaflet-css');
                wp_enqueue_script('openstreet_layer');
                wp_enqueue_style( 'leaf-style' );
                wp_enqueue_script( 'unpkg' );
                wp_enqueue_script( 'unpkg-index' );
                wp_enqueue_script( 'unpkg-libs' );
                wp_enqueue_script( 'leaflet-versions' );
                wp_enqueue_script( 'markercluster-version' );
                wp_enqueue_script( 'leaflet-setup' );
                wp_enqueue_script( 'atbdp_open_street' );
                wp_enqueue_script( 'atbdp_open_street_src' );

                $this->add_inline_css( 'osm_css' );
                $enqueue_inline = true;
            }

            if ( 'google' === $map_type ) {
                wp_enqueue_script( 'atbdp-google-map-front' );
                wp_enqueue_script( 'atbdp-markerclusterer' );
            }
        }

        // directorist_listing_map Scripts
        /* if ( $this->has_shortcodes_in_post( ['directorist_listing_map'] ) ) {
            wp_enqueue_script('atbdp-single-listing-gmap');
        } */

        // Add Listing Scripts
        if ( $this->has_shortcodes_in_post( ['directorist_add_listing'] ) ) {
            $this->add_inline_css( 'add_listing_css' );
            $enqueue_inline = true;
        }

        // Search Style
        $search_style_is_required = $this->has_shortcodes_in_post([
            'directorist_all_listing',
            'directorist_search_result',
            'directorist_category',
            'directorist_location',
            'directorist_tag',
        ]);

        if ( $search_style_is_required && is_rtl() ) {
            wp_enqueue_style('atbdp-search-style-rtl');
        } 
        
        if ( $search_style_is_required && ! is_rtl() ) {
            wp_enqueue_style('atbdp-search-style');
        }

        // $disable_bz_hour_listing = get_post_meta(get_the_ID(), '_disable_bz_hour_listing', true);
        if ( is_business_hour_active() && '2.2.6' > BDBH_VERSION) {
            $this->add_inline_css( 'business_hour_css' );
            $enqueue_inline = true;
        }

        if ( true === $enqueue_inline ) {
            wp_enqueue_style('atbdp-inline-style');
        }
    }
    
    // has_shortcodes_in_post
    public function has_shortcodes_in_post( array $dependency ) {
        global $post;
        $has_shortcode = false;

        if ( is_a( $post, 'WP_Post' ) ) {
            foreach ( $dependency as $dep ) {
                if ( has_shortcode( $post->post_content, $dep ) ) {
                    $has_shortcode = true;
                    break;
                }
            }
        }

        return $has_shortcode;
    }

    // add_inline_css
    public function add_inline_css( $stylesheet_method ) {
        if ( method_exists( 'ATBDP_Stylesheet', $stylesheet_method ) ) {
            wp_add_inline_style( 'atbdp-inline-style', ATBDP_Stylesheet::$stylesheet_method() );
        }
    }

    public function add_listing_scripts_styles() {
        wp_enqueue_script( 'wp-color-picker' );
        $select_listing_map = get_directorist_option( 'select_listing_map', 'google' );
        wp_register_style( 'sweetalertcss', ATBDP_ADMIN_ASSETS . 'css/sweetalert.min.css', false, ATBDP_VERSION );
        wp_register_script( 'sweetalert', ATBDP_ADMIN_ASSETS . 'js/sweetalert.min.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_enqueue_style( 'sweetalertcss' );
        wp_enqueue_script( 'atbdp-bootstrap-script' );
        $dependency = array(
            'jquery',
            'atbdp-rating',
            'sweetalert',
            'jquery-ui-sortable',
            'select2script',
            'wp-color-picker',
        );

        $disable_map     = get_directorist_option( 'display_map_field' );
        $map_is_disabled = ( ! empty( $disable_map ) ) ? true : false;
        
        if ( ! $map_is_disabled && 'google' == $select_listing_map ) {$dependency[] = 'atbdp-google-map-front';}

        wp_register_script( 'atbdp_add_listing_js', ATBDP_PUBLIC_ASSETS . 'js/template-scripts/add-listing.js', $dependency, ATBDP_VERSION, true );
        wp_register_script( 'atbdp_media_uploader', ATBDP_PUBLIC_ASSETS . 'js/ez-media-uploader.js', $dependency, ATBDP_VERSION, true );
        wp_enqueue_script( 'atbdp_media_uploader' );
        wp_enqueue_script( 'atbdp_add_listing_js' );
        wp_register_script( 'atbdp_add_listing_validator', ATBDP_PUBLIC_ASSETS . 'js/validator.js', $dependency, ATBDP_VERSION, true );
        wp_register_script( 'atbdp_custom_field_validator', ATBDP_PUBLIC_ASSETS . 'js/custom_field_validator.js', $dependency, ATBDP_VERSION, true );
        wp_enqueue_script( 'atbdp_add_listing_validator' );
        wp_enqueue_script( 'atbdp_custom_field_validator' );

        $new_tag         = get_directorist_option( 'create_new_tag', 0 );
        $tag_placeholder = get_directorist_option( 'tag_placeholder', __( 'Select or insert new tags separated by a comma, or space', 'directorist' ) );
        $cat_placeholder = get_directorist_option( 'cat_placeholder', __( 'Select Category', 'directorist' ) );
        $loc_placeholder = get_directorist_option( 'loc_placeholder', __( 'Select Location', 'directorist' ) );
        // Internationalization text for javascript file especially add-listing.js
        $i18n_text = array(
            'confirmation_text'       => __( 'Are you sure', 'directorist' ),
            'ask_conf_sl_lnk_del_txt' => __( 'Do you really want to remove this Social Link!', 'directorist' ),
            'ask_conf_faqs_del_txt'   => __( 'Do you really want to remove this FAQ!', 'directorist' ),
            'confirm_delete'          => __( 'Yes, Delete it!', 'directorist' ),
            'deleted'                 => __( 'Deleted!', 'directorist' ),
            'location_selection'      => esc_attr( $loc_placeholder ),
            'category_selection'      => esc_attr( $cat_placeholder ),
            'tag_selection'           => esc_attr( $tag_placeholder ),
        );

        //get listing is if the screen in edit listing
        global $wp;
        global $pagenow;
        $current_url = home_url( add_query_arg( array(), $wp->request ) );
        $fm_plans    = '';
        $listing_id  = '';
        if (  ( strpos( $current_url, '/edit/' ) !== false ) && ( $pagenow = 'at_biz_dir' ) ) {
            $listing_id = substr( $current_url, strpos( $current_url, '/edit/' ) + 6 );
            $fm_plans   = get_post_meta( $listing_id, '_fm_plans', true );
        }

        $cat_placeholder = get_directorist_option( 'cat_placeholder', __( 'Select Category', 'directorist' ) );
        $data            = array(
            'nonce'           => wp_create_nonce( 'atbdp_nonce_action_js' ),
            'ajaxurl'         => admin_url( 'admin-ajax.php' ),
            'nonceName'       => 'atbdp_nonce_js',
            'PublicAssetPath' => ATBDP_PUBLIC_ASSETS,
            'i18n_text'       => $i18n_text,
            'create_new_tag'  => $new_tag,
            'cat_placeholder' => $cat_placeholder,
            'image_notice'    => __( 'Sorry! You have crossed the maximum image limit', 'directorist' ),
        );

        wp_localize_script( 'atbdp_add_listing_js', 'atbdp_add_listing', $data );

        wp_enqueue_media();

        //validation staff start
        $title_visable = get_directorist_option( 'display_title_for', 0 );
        $require_title = get_directorist_option( 'require_title' );
        $title         = '';
        if ( ! empty( $require_title ) && empty( $title_visable ) ) {
            $title = __( 'Title field is required!', 'directorist' );
        }
        $show_description    = get_directorist_option( 'display_desc_for', 0 );
        $description         = '';
        $require_description = get_directorist_option( 'require_long_details' );
        if ( ! empty( $require_description ) && empty( $show_description ) ) {
            $description = __( 'Description field is required!', 'directorist' );
        }
        $category = '';
        if (  ( get_directorist_option( 'require_category' ) == 1 ) ) {
            $category = __( 'Category field is required!', 'directorist' );
        }
        $excerpt         = '';
        $require_excerpt = get_directorist_option( 'require_excerpt' );
        $display_excerpt = get_directorist_option( 'display_excerpt_field', 0 );
        $excerpt_visable = get_directorist_option( 'display_short_desc_for', 0 );
        if ( ! empty( $require_excerpt && $display_excerpt ) && empty( $excerpt_visable ) ) {
            $excerpt = __( 'Excerpt field is required!', 'directorist' );
        }
        //price
        $plan_price = true;
        if ( is_fee_manager_active() ) {
            $plan_price = is_plan_allowed_price( $fm_plans );
        }
        $price_visable  = get_directorist_option( 'display_price_for', 0 );
        $price_display  = get_directorist_option( 'display_pricing_field', 1 );
        $price_required = get_directorist_option( 'require_price', 0 );
        $price          = '';
        if ( ! empty( $price_required && $price_display ) && $plan_price && empty( $price_visable ) ) {
            $price = __( 'Price field is required!', 'directorist' );
        }
        //price range
        $plan_price_range = true;
        if ( is_fee_manager_active() ) {
            $plan_price_range = is_plan_allowed_average_price_range( $fm_plans );
        }
        $price_range_visable  = get_directorist_option( 'display_price_range_for', 0 );
        $price_range_display  = get_directorist_option( 'display_price_range_field', 1 );
        $price_range_required = get_directorist_option( 'require_price_range', 0 );
        $price_range          = '';
        if ( ! empty( $price_range_required && $price_range_display ) && $plan_price_range && empty( $price_range_visable ) ) {
            $price_range = __( 'Price range field is required!', 'directorist' );
        }
        //tag
        $plan_tag = true;
        if ( is_fee_manager_active() ) {
            $plan_tag = is_plan_allowed_tag( $fm_plans );
        }
        $tag    = '';
        $p_tags = true;
        if ( ! empty( $listing_id ) ) {
            $p_tags = wp_get_post_terms( $listing_id, ATBDP_TAGS );
            if ( ! empty( $p_tags ) ) {
                $p_tags = false;
            } else {
                $p_tags = true;
            }
        }

        $require_tag = get_directorist_option( 'require_tags' );
        $tag_visable = get_directorist_option( 'display_tag_for', 0 );
        if ( ! empty( $require_tag && $p_tags ) && $plan_tag && empty( $tag_visable ) ) {
            $tag = __( 'Tag field is required!', 'directorist' );
        }

        //location
        $location    = '';
        $p_locations = true;
        if ( ! empty( $listing_id ) ) {
            $p_locations = wp_get_post_terms( $listing_id, ATBDP_LOCATION );
            if ( ! empty( $p_locations ) ) {
                $p_locations = false;
            } else {
                $p_locations = true;
            }
        }

        $required_location = get_directorist_option( 'require_location' );
        $location_visable  = get_directorist_option( 'display_loc_for', 0 );
        if (  ( ! empty( $required_location && $p_locations ) ) && empty( $location_visable ) ) {
            $location = __( 'Location field is required!', 'directorist' );
        }

        //address
        $address         = '';
        $require_address = get_directorist_option( 'require_address' );
        $display_address = get_directorist_option( 'display_address_field', 1 );
        $address_visable = get_directorist_option( 'display_address_for', 0 );
        if ( ! empty( $require_address && $display_address ) && empty( $address_visable ) ) {
            $address = __( 'Address field is required!', 'directorist' );
        }
        //phone
        $plan_phone = true;
        if ( is_fee_manager_active() ) {
            $plan_phone = is_plan_allowed_listing_phone( $fm_plans );
        }
        $phone          = '';
        $phone2         = '';
        $fax            = '';
        $require_phone  = get_directorist_option( 'require_phone_number' );
        $require_phone2 = get_directorist_option( 'require_phone_number2' );
        $require_fax    = get_directorist_option( 'require_fax' );

        $display_phone  = get_directorist_option( 'display_phone_field', 1 );
        $display_phone2 = get_directorist_option( 'display_phone2_field', 1 );
        $display_fax    = get_directorist_option( 'display_fax', 1 );

        $phone_visable  = get_directorist_option( 'display_phone_for', 0 );
        $phone2_visable = get_directorist_option( 'display_phone2_for', 0 );
        $fax_visable    = get_directorist_option( 'display_fax_for', 0 );
        if (  ( $require_phone && $display_phone ) && $plan_phone && empty( $phone_visable ) ) {
            $phone = __( 'Phone field is required!', 'directorist' );
        }
        if (  ( $require_phone2 && $display_phone2 ) && $plan_phone && empty( $phone2_visable ) ) {
            $phone2 = __( 'This field is required!', 'directorist' );
        }
        if (  ( $require_fax && $display_fax ) && empty( $fax_visable ) ) {
            $fax = __( 'This field is required!', 'directorist' );
        }

        //email
        $plan_email = true;
        if ( is_fee_manager_active() ) {
            $plan_email = is_plan_allowed_listing_email( $fm_plans );
        }
        $email         = '';
        $require_email = get_directorist_option( 'require_email' );
        $display_email = get_directorist_option( 'display_email_field', 1 );
        $email_visable = get_directorist_option( 'display_email_for', 0 );
        if ( ! empty( $require_email && $display_email ) && $plan_email && empty( $email_visable ) ) {
            $email = __( 'Email field is required!', 'directorist' );
        }
        //website
        $plan_webLink = true;
        if ( is_fee_manager_active() ) {
            $plan_webLink = is_plan_allowed_listing_webLink( $fm_plans );
        }
        $web         = '';
        $require_web = get_directorist_option( 'require_website' );
        $display_web = get_directorist_option( 'display_website_field', 1 );
        $web_visable = get_directorist_option( 'display_website_for', 0 );
        if ( ! empty( $require_web && $display_web ) && $plan_webLink && empty( $web_visable ) ) {
            $web = __( 'Website link field is required!', 'directorist' );
        }

        //zip

        $zip         = '';
        $require_zip = get_directorist_option( 'require_zip' );
        $display_zip = get_directorist_option( 'display_zip_field', 1 );
        $zip_visable = get_directorist_option( 'display_zip_for', 0 );
        if ( ! empty( $require_zip && $display_zip ) && empty( $zip_visable ) ) {
            $zip = __( 'Zip/Post Code field is required!', 'directorist' );
        }

        //social link
        $plan_social_networks = true;
        if ( is_fee_manager_active() ) {
            $plan_social_networks = is_plan_allowed_listing_social_networks( $fm_plans );
        }
        $Sinfo         = '';
        $require_Sinfo = get_directorist_option( 'require_social_info' );
        $display_Sinfo = get_directorist_option( 'display_social_info_field', 1 );
        $Sinfo_visable = get_directorist_option( 'display_social_info_for', 0 );
        if ( ! empty( $require_Sinfo && $display_Sinfo ) && $plan_social_networks && empty( $Sinfo_visable ) ) {
            $Sinfo = __( 'Social information is required!', 'directorist' );
        }
        //image slider
        $plan_slider = true;
        if ( is_fee_manager_active() ) {
            $plan_slider = is_plan_allowed_slider( $fm_plans );
        }
        $preview_visable       = get_directorist_option( 'display_prv_img_for', 0 );
        $gallery_visable       = get_directorist_option( 'display_glr_img_for', 0 );
        $preview_image         = '';
        $req_preview_image     = get_directorist_option( 'require_preview_img' );
        $display_preview_image = get_directorist_option( 'display_prv_field', 1 );
        $gallery_image         = '';
        $req_gallery_image     = get_directorist_option( 'require_gallery_img' );
        $display_gallery_image = get_directorist_option( 'display_gallery_field', 1 );
        if ( ! empty( $req_preview_image && $display_preview_image ) && empty( $preview_visable ) ) {
            $preview_image = __( 'Preview image is required!', 'directorist' );
        }if ( ! empty( $req_gallery_image && $display_gallery_image ) && $plan_slider && empty( $gallery_visable ) ) {
            $gallery_image = __( 'Gallery image is required!', 'directorist' );
        }

        //video
        $plan_video = true;
        if ( is_fee_manager_active() ) {
            $plan_video = is_plan_allowed_listing_video( $fm_plans );
        }
        $video_visable = get_directorist_option( 'display_video_for', 0 );
        $require_video = get_directorist_option( 'require_video' );
        $display_video = get_directorist_option( 'display_video_field', 1 );
        $video         = '';
        if ( ! empty( $require_video && $display_video ) && $plan_video && empty( $video_visable ) ) {
            $video = __( 'Video is required!', 'directorist' );
        }
        //t & c
        $term_visable = get_directorist_option( 'listing_terms_condition' );
        $terms        = '';
        $req_terms    = get_directorist_option( 'require_terms_conditions' );
        if ( ! empty( $req_terms ) && $term_visable ) {
            $terms = __( 'Agree to Terms & Conditions is required!', 'directorist' );
        }

        //privacy policy
        $listing_privacy = get_directorist_option( 'listing_privacy' );
        $privacy         = '';
        $require_privacy = get_directorist_option( 'require_privacy' );
        if ( ! empty( $require_privacy ) && $listing_privacy ) {
            $privacy = __( 'Agree to Privacy Policy is required!', 'directorist' );
        }

        $guest_user     = '';
        $guest_listings = get_directorist_option( 'guest_listings', 0 );
        if ( ! empty( $guest_listings ) ) {
            $guest_user = __( 'Your email is required!', 'directorist' );
        }

        $validator = array(
            'title'           => $title,
            'description'     => $description,
            'category'        => $category,
            'excerpt'         => $excerpt,
            'price'           => $price,
            'price_range'     => $price_range,
            'tag'             => $tag,
            'location'        => $location,
            'address'         => $address,
            'phone'           => $phone,
            'phone2'          => $phone2,
            'fax'             => $fax,
            'email'           => $email,
            'web'             => $web,
            'zip'             => $zip,
            'Sinfo'           => $Sinfo,
            'listing_prv_img' => $preview_image,
            'gallery_image'   => $gallery_image,
            'video'           => $video,
            'terms'           => $terms,
            'guest_user'      => $guest_user,
            'require_privacy' => $privacy,
        );

        wp_localize_script( 'atbdp_add_listing_validator', 'add_listing_validator', $validator );

        //custom fields
        $required_custom_fields = array();
        $radio_field            = '';
        $cus_check              = '';
        $custom_fields          = new WP_Query( array(
            'post_type'      => ATBDP_CUSTOM_FIELD_POST_TYPE,
            'posts_per_page' => -1,
            'post_status'    => 'publish',

        ) );
        $plan_custom_field = true;
        if ( is_fee_manager_active() ) {
            $selected_plan     = selected_plan_id();
            $planID            = ! empty( $selected_plan ) ? $selected_plan : $fm_plans;
            $plan_custom_field = is_plan_allowed_custom_fields( $planID );
        }
        if ( $plan_custom_field ) {
            $fields = $custom_fields->posts;
        } else {
            $fields = array();
        }
        foreach ( $fields as $post ) {
            setup_postdata( $post );
            $cf_required = get_post_meta( $post->ID, 'required', true );
            $cf_type     = get_post_meta( $post->ID, 'type', true );
            if ( $cf_required ) {
                $required_custom_fields[] = $post->ID;
                switch ( $cf_type ) {
                case 'radio':
                    $radio_field = $post->ID;
                    break;
                case 'checkbox':
                    $cus_check = $post->ID;
                    break;
                }
            }
        }
        wp_reset_postdata();

        $custom_field_validator = array(
            'required_cus_fields' => $required_custom_fields,
            'cus_radio'           => $radio_field,
            'cus_check'           => $cus_check,
            'msg'                 => __( 'This field is required!', 'directorist' ),
        );
        wp_localize_script( 'atbdp_custom_field_validator', 'custom_field_validator', $custom_field_validator );
        wp_enqueue_media();

    }

    public function user_dashboard_scripts_styles() {
        /* Enqueue all styles*/
        wp_enqueue_style( 'atbdp-bootstrap-style' );
        wp_enqueue_style( 'atbdp-stars' );
        wp_enqueue_style( 'atbdp-line-awesome' );
        wp_enqueue_style( 'atbdp-font-awesome' );
        wp_enqueue_style( 'atbdp-style' );
        wp_enqueue_style( 'atbdp-responsive' );

        /* Enqueue all scripts */
        wp_enqueue_script( 'atbdp-bootstrap-script' );
        wp_enqueue_script( 'atbdp-rating' );
        wp_enqueue_script( 'atbdp-google-map-front' );
        wp_enqueue_script( 'atbdp-user-dashboard' );

        $data = array(
            'nonce'           => wp_create_nonce( 'atbdp_nonce' ),
            'PublicAssetPath' => ATBDP_PUBLIC_ASSETS,
        );
        wp_enqueue_style( 'wp-color-picker' );
        wp_localize_script( 'atbdp-user-dashboard', 'atbdp_data', $data );

    }

    public function search_listing_scripts_styles() {
        $search_dependency = array( 'jquery', 'jquery-ui-slider',
            'select2script' );
        wp_enqueue_script( 'atbdp_search_listing', ATBDP_PUBLIC_ASSETS . 'js/search-listing.js',
            /**
             * @since 5.0.1
             * It returns the dependencies for search form js
             */
            apply_filters( 'atbdp_search_listing_jquery_dependency', $search_dependency ), ATBDP_VERSION, true );
        $handel = is_rtl() ? 'atbdp-range-slider-rtl' : 'atbdp-range-slider';
        wp_enqueue_script( $handel );

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
                'select_listing_map' => $select_listing_map,
                'Miles'              => $miles,
            ),
            'ajax_url'    => admin_url( 'admin-ajax.php' ),
            'Miles'       => $miles,
            'default_val' => $default_radius_distance,
        );
        wp_localize_script( 'atbdp_search_listing', 'atbdp_search_listing', $data );
        wp_localize_script( $handel, 'atbdp_range_slider', $data );
    }
}

endif;
