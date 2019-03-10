<?php

if (!class_exists('ATBDP_Enqueuer')):
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
    var $enable_multiple_image = 0;

    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        // best hook to enqueue scripts for front-end is 'template_redirect'
        // 'Professional WordPress Plugin Development' by Brad Williams
        add_action( 'wp_enqueue_scripts', array( $this, 'front_end_enqueue_scripts' ), -10 );
        add_action( 'wp_enqueue_scripts', array( $this, 'custom_color_picker_scripts' ) );

        /*The plugins_loaded action hook fires early, and precedes the setup_theme, after_setup_theme, init and wp_loaded action hooks.
        This is why calling is_multiple_images_active() which is a wrapper function of vp_option() returned false all the time here.
        Because we bootstrapped the Vafpress at the after_theme_setup hook as directed by the author for various good reason.
        This bug took very hard time to fix :'( I should be more aware of the sequence of hook.*/
        add_action('init', array($this, 'everything_has_loaded_and_ready')); // otherwise, vp_option would return null

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
            array( 'iris' ),
            false,
            1
        );
        $colorpicker_l10n = array(
            'clear' => __( 'Clear' ),
            'defaultString' => __( 'Default' ),
            'pick' => __( 'Select Color' ),
            'current' => __( 'Current Color' ),
        );
        wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n );

    }


    public function everything_has_loaded_and_ready()
    {
        $this->enable_multiple_image = is_multiple_images_active() ? 1 : 0; // is the MI Extension is installed???
    }



    public function admin_enqueue_scripts( $page ) {
        global $typenow;

        if ( ATBDP_POST_TYPE == $typenow ) {
            // make a list of dependency of admin script and then add to this list some other scripts using different conditions to handle different situation.
            $admin_scripts_dependency = array(
                'jquery',
                'wp-color-picker',
                'sweetalert',
                'select2script',
                'bs-tooltip'
            );
            $disable_map = get_directorist_option('disable_map');
            if (!$disable_map){
                // get the map api from the user settings
                $map_api_key = get_directorist_option('map_api_key'); // eg. zaSyBtTwA-Y_X4OMsIsc9WLs7XEqavZ3ocQLQ
                //Google map needs to be enqueued from google server with a valid API key. So, it is not possible to store map js file locally as this file will be unique for all users based on their MAP API key.
                wp_register_script( 'atbdp-google-map-admin', '//maps.googleapis.com/maps/api/js?key='.$map_api_key.'&libraries=places', false, ATBDP_VERSION, true );
                $admin_scripts_dependency[] = 'atbdp-google-map-admin';
            }

            //Register all styles for the admin pages
            /*Public Common Asset: */

            wp_register_style( 'atbdp-font-awesome', ATBDP_PUBLIC_ASSETS . 'css/font-awesome.min.css', false, ATBDP_VERSION);
            wp_register_style( 'sweetalertcss', ATBDP_PUBLIC_ASSETS.'css/sweetalert.min.css', false, ATBDP_VERSION );
            wp_register_style( 'select2style', ATBDP_PUBLIC_ASSETS.'css/select2.min.css', false, ATBDP_VERSION );
            wp_register_style( 'atbdp-admin-bootstrap-style', ATBDP_PUBLIC_ASSETS . 'css/bootstrap.css', false, ATBDP_VERSION);
            wp_register_style( 'atbdp-admin', ATBDP_ADMIN_ASSETS . 'css/style.css', array( 'atbdp-font-awesome', 'select2style'), ATBDP_VERSION);


            wp_register_script( 'sweetalert', ATBDP_PUBLIC_ASSETS . 'js/sweetalert.min.js', array( 'jquery' ), ATBDP_VERSION, true );
            /*Public Common Asset: */
            wp_register_script( 'select2script', ATBDP_PUBLIC_ASSETS . 'js/select2.min.js', array( 'jquery' ), ATBDP_VERSION, true );
            wp_register_script('popper', ATBDP_ADMIN_ASSETS.'js/popper.min.js', array('jquery'), ATBDP_VERSION, true);
            wp_register_script('bs-tooltip', ATBDP_ADMIN_ASSETS.'js/tooltip.js', array('jquery', 'popper'), ATBDP_VERSION, true);




            wp_register_script( 'atbdp-admin-script', ATBDP_ADMIN_ASSETS . 'js/main.js', $admin_scripts_dependency , ATBDP_VERSION, true );


            // Styles
            //@todo; later minify the bootstrap
            /*@todo; we can also load scripts and style using very strict checking like loading based on post.php or edit.php etc. For example, sweetalert should be included in the post.php file where the user will add/edit listing */

            /* enqueue all styles*/
            wp_enqueue_style('atbdp-admin-bootstrap-style');
            wp_enqueue_style('atbdp-font-awesome');
            wp_enqueue_style('sweetalertcss');
            wp_enqueue_style('select2style');
            /* WP COLOR PICKER */
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_style('atbdp-admin');


            /* Enqueue all scripts */
            //wp_enqueue_script('atbdp-bootstrap'); // maybe we do not need the bootstrap js in the admin panel at the moment.
            wp_enqueue_script('sweetalert');
            if (!$disable_map){ wp_enqueue_script('atbdp-google-map-admin'); }
            wp_enqueue_script('select2script');
            wp_enqueue_script('atbdp-admin-script');




            // Internationalization text for javascript file especially add-listing.js
            $i18n_text = array(
                'confirmation_text' => __('Are you sure', ATBDP_TEXTDOMAIN),
                'ask_conf_sl_lnk_del_txt' => __('Do you really want to remove this Social Link!', ATBDP_TEXTDOMAIN),
                'confirm_delete' => __('Yes, Delete it!', ATBDP_TEXTDOMAIN),
                'deleted' => __('Deleted!', ATBDP_TEXTDOMAIN),
                'icon_choose_text' => __('Select an icon', ATBDP_TEXTDOMAIN),
                'upload_image' => __('Select or Upload Slider Image', ATBDP_TEXTDOMAIN),
                'choose_image' => __('Use this Image', ATBDP_TEXTDOMAIN),
            );
            // is MI extension enabled and active?
            $data = array(
                'nonce'             => wp_create_nonce('atbdp_nonce_action_js'),
                'ajaxurl'           => admin_url('admin-ajax.php'),
                'nonceName'         => 'atbdp_nonce_js',
                'AdminAssetPath'    => ATBDP_ADMIN_ASSETS,
                'i18n_text'         => $i18n_text,
                'active_mi_ext'     => $this->enable_multiple_image, // 1 or 0
            );
            wp_localize_script( 'atbdp-admin-script', 'atbdp_admin_data', $data );
            wp_enqueue_media();


        }

        if (ATBDP_CUSTOM_FIELD_POST_TYPE == $typenow){
            wp_enqueue_style( 'atbdp-custom-field', ATBDP_PUBLIC_ASSETS . 'css/bootstrap.css', false, ATBDP_VERSION);

        }
        // lets add a small stylesheet for order listing page
        if ((ATBDP_ORDER_POST_TYPE || ATBDP_CUSTOM_FIELD_POST_TYPE)  == $typenow){
            wp_enqueue_style( 'atbdp-admin', ATBDP_ADMIN_ASSETS . 'css/style.css', false, ATBDP_VERSION);
        }
    }

    /**
     * It loads all scripts for front end if the current post type is our custom post type
     * @param bool $force [optional] whether to load the style in the front end forcibly(even if the post type is not our custom post). It is needed for enqueueing file from a inside the short code call
     */
    public function front_end_enqueue_scripts($force=false) {
        global $typenow, $post;
        $front_scripts_dependency = array('jquery',);
        $disable_map = get_directorist_option('disable_map');
        if (!$disable_map){
            // get the map api from the user settings
            $map_api_key = get_directorist_option('map_api_key'); // eg. zaSyBtTwA-Y_X4OMsIsc9WLs7XEqavZ3ocQLQ
            //Google map needs to be enqueued from google server with a valid API key. So, it is not possible to store map js file locally as this file will be unique for all users based on their MAP API key.
            wp_register_script( 'atbdp-google-map-front', '//maps.googleapis.com/maps/api/js?key='.$map_api_key.'&libraries=places', false, ATBDP_VERSION, true );
            $front_scripts_dependency[] = 'atbdp-google-map-front';
        }
        // Registration of all styles and js for the front end should be done here
        // but the inclusion should be limited to the scope of the page user viewing.
        // This way,  we can just enqueue any styles and scripts in side the shortcode or any other functions.

        // @Todo; make unminified css minified then enqueue them.
        wp_register_style( 'atbdp-bootstrap-style', ATBDP_PUBLIC_ASSETS . 'css/bootstrap.css', false, ATBDP_VERSION);
        wp_register_style( 'atbdp-font-awesome', ATBDP_PUBLIC_ASSETS . 'css/font-awesome.min.css', false, ATBDP_VERSION);
        wp_register_style( 'sweetalertcss', ATBDP_PUBLIC_ASSETS.'css/sweetalert.min.css', false, ATBDP_VERSION );
        wp_register_style( 'select2style', ATBDP_PUBLIC_ASSETS.'css/select2.min.css', false, ATBDP_VERSION );
        wp_register_style( 'slickcss', ATBDP_PUBLIC_ASSETS.'css/slick.css', false, ATBDP_VERSION );
        wp_register_style( 'atbd_googlefonts', '//fonts.googleapis.com/css?family=Roboto:400,500', false, ATBDP_VERSION );
        wp_register_style( 'atbdp-style', ATBDP_PUBLIC_ASSETS . 'css/style.css', array( 'atbdp-font-awesome',), ATBDP_VERSION);


        wp_register_script('atbdp-popper-script', ATBDP_PUBLIC_ASSETS . 'js/popper.js', array('jquery'), ATBDP_VERSION, false);
        wp_register_script('atbdp-bootstrap-script', ATBDP_PUBLIC_ASSETS . 'js/bootstrap.min.js', array('jquery', 'atbdp-popper-script'), ATBDP_VERSION, true);
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


        // we need select2 js on taxonomy edit screen to let the use to select the fonts-awesome icons ans search the icons easily
        // @TODO; make the styles and the scripts specific to the scripts where they are used specifically. For example. load select2js scripts and styles in
        wp_enqueue_style('select2style');
        wp_enqueue_script('select2script');
        wp_enqueue_script('atbdp_validator');

        /* Enqueue all styles*/
        wp_enqueue_style('atbdp-bootstrap-style');
        wp_enqueue_style('atbdp-font-awesome');
        wp_enqueue_style('atbdp-style');

        /* Enqueue google Directorist google font */
        wp_enqueue_style('atbd_googlefonts');
        wp_enqueue_style('slickcss');


        /* Enqueue all scripts */
        wp_enqueue_script('atbdp-bootstrap-script');
        wp_enqueue_script('atbdp-rating');
        if (!$disable_map) { wp_enqueue_script('atbdp-google-map-front'); }
        wp_enqueue_script('atbdp-uikit');
        wp_enqueue_script('atbdp-uikit-grid');
        wp_enqueue_script('atbdp_slick_slider');

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        $data = array(
            'nonce'       => wp_create_nonce('atbdp_nonce_action_js'),
            'ajaxurl'       => admin_url('admin-ajax.php'),
            'nonceName'       => 'atbdp_nonce_js',
            'PublicAssetPath'  => ATBDP_PUBLIC_ASSETS,
            'login_alert_message' => __( 'Sorry, you need to login first.', ATBDP_TEXTDOMAIN ),
            'rtl'                 => is_rtl() ? 'true': 'false'
        );
        wp_localize_script( 'atbdp_checkout_script', 'atbdp_checkout', $data );





        // enqueue the style and the scripts on the page when the post type is our registered post type.
        if ( (is_object($post) && ATBDP_POST_TYPE == $post->post_type) || $force) {
            wp_enqueue_style('sweetalertcss' );
            wp_enqueue_script('sweetalert' );
            wp_enqueue_script( 'atbdp-public-script', ATBDP_PUBLIC_ASSETS . 'js/main.js', apply_filters('atbdp_front_script_dependency', $front_scripts_dependency), ATBDP_VERSION, true );

            wp_localize_script( 'atbdp-public-script', 'atbdp_public_data', $data );
            wp_enqueue_style('wp-color-picker');

            wp_enqueue_media();
        }
    }



    public function add_listing_scripts_styles()
    {
        wp_register_style( 'sweetalertcss', ATBDP_ADMIN_ASSETS.'css/sweetalert.min.css', false, ATBDP_VERSION );
        wp_register_script( 'sweetalert', ATBDP_ADMIN_ASSETS . 'js/sweetalert.min.js', array( 'jquery' ), ATBDP_VERSION, true );
        wp_enqueue_style('sweetalertcss');
        wp_enqueue_script('atbdp-bootstrap-script');
        $dependency = array(
            'jquery',
            'atbdp-rating',
            'sweetalert',
            'jquery-ui-sortable',
            'select2script'
        );
        $disable_map = get_directorist_option('disable_map');
        if (!$disable_map){ $dependency[]= 'atbdp-google-map-front'; }
        wp_register_script('atbdp_add_listing_js', ATBDP_PUBLIC_ASSETS . 'js/add-listing.js', $dependency, ATBDP_VERSION, true );
        wp_enqueue_script('atbdp_add_listing_js');
        wp_register_script('atbdp_add_listing_validator', ATBDP_PUBLIC_ASSETS . 'js/validator.js', $dependency, ATBDP_VERSION, true );
        wp_enqueue_script('atbdp_add_listing_validator');

        // Internationalization text for javascript file especially add-listing.js
        $i18n_text = array(
            'confirmation_text' => __('Are you sure', ATBDP_TEXTDOMAIN),
            'ask_conf_sl_lnk_del_txt' => __('Do you really want to remove this Social Link!', ATBDP_TEXTDOMAIN),
            'confirm_delete' => __('Yes, Delete it!', ATBDP_TEXTDOMAIN),
            'deleted' => __('Deleted!', ATBDP_TEXTDOMAIN),
            'location_selection' => __('Select a location', ATBDP_TEXTDOMAIN),
            'category_selection' => __('Select a category', ATBDP_TEXTDOMAIN),
            'tag_selection' => __('Select or insert new tags separated by a comma, or space', ATBDP_TEXTDOMAIN),
            'upload_image' => __('Select or Upload Slider Image', ATBDP_TEXTDOMAIN),
            'choose_image' => __('Use this Image', ATBDP_TEXTDOMAIN),
        );

        //get listing is if the screen in edit listing
        global $wp;
        global $pagenow;
        $current_url = home_url(add_query_arg(array(),$wp->request));
        $fm_plans = '';
        if( (strpos( $current_url, '/edit/' ) !== false) && ($pagenow = 'at_biz_dir')){
            $listing_id = substr($current_url, strpos($current_url, "/edit/") + 6);
            $fm_plans = get_post_meta($listing_id, '_fm_plans', true);
        }

        // is MI extension enabled and active?
        $active_mi_extension = $this->enable_multiple_image; // 1 or 0
        $plan_image = 999;
        if (is_fee_manager_active()){
            $selected_plan = selected_plan_id();
            $planID = !empty($selected_plan)?$selected_plan:$fm_plans;
            $plan_image = is_plan_slider_limit($planID);
        }
        $data = array(
            'nonce'            => wp_create_nonce('atbdp_nonce_action_js'),
            'ajaxurl'          => admin_url('admin-ajax.php'),
            'nonceName'        => 'atbdp_nonce_js',
            'PublicAssetPath'  => ATBDP_PUBLIC_ASSETS,
            'i18n_text'        => $i18n_text,
            'active_mi_ext'    => $active_mi_extension, // 1 or 0
            'plan_image'       => $plan_image
        );

        wp_localize_script( 'atbdp_add_listing_js', 'atbdp_add_listing', $data );

        wp_enqueue_media();

        //validation staff start
        $title_visable = get_directorist_option('display_title_for', 'users');
        $title = '';
        if((get_directorist_option('require_title') == 1) && ('users' === $title_visable)){
            $title = __('Title field is required!', ATBDP_TEXTDOMAIN);
        }
        $title_description = get_directorist_option('display_desc_for', 'users');
        $description = '';
        if((get_directorist_option('require_long_details') == 1) && ('users' === $title_description)){
            $description = __('Description field is required!', ATBDP_TEXTDOMAIN);
        }
        $category = '';
        if((get_directorist_option('require_category') == 1)){
            $category = __('Category field is required!', ATBDP_TEXTDOMAIN);
        }
        $excerpt = '';
        $excerpt_visable = get_directorist_option('display_short_desc_for', 'none');
        if((get_directorist_option('require_excerpt') == 1) && empty($p['excerpt']) && ('admin_users' === $excerpt_visable)){
            $excerpt = __('Excerpt field is required!', ATBDP_TEXTDOMAIN);
        }
        //custom fields
        $required_custom_fields = array();
        $custom_fields = new WP_Query(array(
            'post_type' => ATBDP_CUSTOM_FIELD_POST_TYPE,
            'posts_per_page' => -1,
            'post_status' => 'publish',

        ));
       $plan_custom_field = true;
        if (is_fee_manager_active()){
            $selected_plan = selected_plan_id();
            $planID = !empty($selected_plan)?$selected_plan:$fm_plans;
            $plan_custom_field = is_plan_allowed_custom_fields($planID);
        }
        if ($plan_custom_field){
            $fields = $custom_fields->posts;
        }else{
            $fields = array();
        }
        foreach ($fields as $post) {
            setup_postdata($post);
            $cf_required = get_post_meta($post->ID, 'required', true);
            if ($cf_required){
                $required_custom_fields[] = $post->ID;
            }

        }
        wp_reset_postdata();

        //price
        $plan_price = true;
        if (is_fee_manager_active()){
            $plan_price = is_plan_allowed_price($fm_plans);
        }
        $price_visable = get_directorist_option('display_price_for', 'admin_users');
        $price = '';
        if((get_directorist_option('require_price') == 1) && $plan_price && ('admin_users' === $price_visable)){
            $price = __('Price field is required!', ATBDP_TEXTDOMAIN);
        }
        $plan_price_range = true;
        if (is_fee_manager_active()){
            $plan_price_range = is_plan_allowed_average_price_range($fm_plans);
        }
        $price_range = '';
        if((get_directorist_option('require_price_range') == 1) && $plan_price_range && ('admin_users' === $price_visable)){
            $price_range = __('Price range field is required!', ATBDP_TEXTDOMAIN);
        }
        //tag
        $plan_tag = true;
        if (is_fee_manager_active()){
            $plan_tag = is_plan_allowed_tag($fm_plans);
        }
        $tag = '';
        $tag_visable = get_directorist_option('display_tag_for', 'users');
        if((get_directorist_option('require_tags') == 1) && $plan_tag && ('users' === $tag_visable)){
            $tag = __('Tag field is required!', ATBDP_TEXTDOMAIN);
        }

        //location
        $location = '';
        $location_visable = get_directorist_option('display_loc_for', 'users');
        if((get_directorist_option('require_location') == 1) && ('users' === $location_visable)) {
            $location = __('Location field is required!', ATBDP_TEXTDOMAIN);
        }

        //address
        $address = '';
        $address_visable = get_directorist_option('display_address_for', 'admin_users');
        if((get_directorist_option('require_address') == 1) && ('admin_users' === $address_visable)){
            $address = __('Address field is required!', ATBDP_TEXTDOMAIN);
        }
        //phone
        $plan_phone = true;
        if (is_fee_manager_active()){
            $plan_phone = is_plan_allowed_listing_phone($fm_plans);
        }
        $phone = '';
        $phone_visable = get_directorist_option('display_phone_for', 'admin_users');
        if((get_directorist_option('require_phone_number') == 1) && $plan_phone && ('admin_users' === $phone_visable)){
            $phone = __('Phone field is required!', ATBDP_TEXTDOMAIN);
        }
        //email
        $plan_email = true;
        if (is_fee_manager_active()){
            $plan_email = is_plan_allowed_listing_email($fm_plans);
        }
        $email = '';
        $email_visable = get_directorist_option('display_email_for', 'admin_users');
        if((get_directorist_option('require_email') == 1) && empty($p['email']) && $plan_email && ('admin_users' === $email_visable)){
            $email = __('Email field is required!', ATBDP_TEXTDOMAIN);
        }
        //website
        $plan_webLink = true;
        if (is_fee_manager_active()){
            $plan_webLink = is_plan_allowed_listing_webLink($fm_plans);
        }
        $web = '';
        $web_visable = get_directorist_option('display_website_for', 'admin_users');
        if((get_directorist_option('require_website') == 1) && empty($p['website']) && $plan_webLink && ('admin_users' === $web_visable)){
            $web = __('Website link field is required!', ATBDP_TEXTDOMAIN);
        }

        //social link
        $plan_social_networks = true;
        if (is_fee_manager_active()){
            $plan_social_networks = is_plan_allowed_listing_social_networks($fm_plans);
        }
        $Sinfo = '';
        $Sinfo_visable = get_directorist_option('display_social_info_for', 'admin_users');
        if((get_directorist_option('require_social_info') == 1) && $plan_social_networks && ('admin_users' === $Sinfo_visable)){
            $Sinfo = __('Social information is required!', ATBDP_TEXTDOMAIN);
        }
        //image slider
        $plan_slider = true;
        if (is_fee_manager_active()){
            $plan_slider =is_plan_allowed_slider($fm_plans);
        }
        $preview_visable = get_directorist_option('display_prv_img_for', 'admin_users');
        $gallery_visable = get_directorist_option('display_glr_img_for', 'admin_users');
        $preview_image = '';
        $gallery_image = '';
        if((get_directorist_option('require_preview_img') == 1) && ('admin_users' === $preview_visable)){
            $preview_image = __('Preview image is required!', ATBDP_TEXTDOMAIN);
        }if((get_directorist_option('require_gallery_img') == 1) && $plan_slider && ('admin_users' === $gallery_visable)){
        $gallery_image = __('Gallery image is required!', ATBDP_TEXTDOMAIN);
    }

    //video
        $plan_video = true;
        if (is_fee_manager_active()){
            $plan_video =is_plan_allowed_listing_video($fm_plans);
        }
        $video_visable = get_directorist_option('display_video_for', 'admin_users');
        $video = '';
        if((get_directorist_option('require_video') == 1) && $plan_video && ('admin_users' === $video_visable)){
            $video = __('Video is required!', ATBDP_TEXTDOMAIN);
        }
        //t & c
        $term_visable = get_directorist_option('listing_terms_condition');
        $terms = '';
        if((get_directorist_option('require_terms_conditions') == 1) &&  $term_visable){
            $terms = __('Agree to Terms & Conditions is required!', ATBDP_TEXTDOMAIN);
        }

        $validator = array(
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'excerpt' => $excerpt,
            'required_cus_fields' => $required_custom_fields,
            'price'    => $price,
            'price_range'    => $price_range,
            'tag'    => $tag,
            'location'    => $location,
            'address'    => $address,
            'phone'    => $phone,
            'email'    => $email,
            'web'    => $web,
            'Sinfo'    => $Sinfo,
            'listing_prv_img'    => $preview_image,
            'gallery_image'    => $gallery_image,
            'video'    => $video,
            'terms'    => $terms,
        );

        wp_localize_script( 'atbdp_add_listing_validator', 'add_listing_validator', $validator );

        wp_enqueue_media();

    }

    public function user_dashboard_scripts_styles()
    {
        /* Enqueue all styles*/
        wp_enqueue_style('atbdp-bootstrap-style');
        wp_enqueue_style('atbdp-stars');
        wp_enqueue_style('atbdp-font-awesome');
        wp_enqueue_style('atbdp-style');
        wp_enqueue_style('atbdp-responsive');

        /* Enqueue all scripts */
        wp_enqueue_script('atbdp-bootstrap-script');
        wp_enqueue_script('atbdp-rating');
//        wp_enqueue_script('atbdp-google-map-front');
        wp_enqueue_script('atbdp-user-dashboard');


        $data = array(
            'nonce'       => wp_create_nonce('atbdp_nonce'),
            'PublicAssetPath'  => ATBDP_PUBLIC_ASSETS,
        );
        wp_enqueue_style('wp-color-picker');
        wp_localize_script( 'atbdp-user-dashboard', 'atbdp_data', $data );

    }

    public function search_listing_scripts_styles(){
        wp_enqueue_script( 'atbdp_search_listing', ATBDP_PUBLIC_ASSETS . 'js/search-listing.js', array(
            'jquery',
            'select2script',
        ), ATBDP_VERSION, true );

        /*Internationalization*/
        $data = array(
            'i18n_text'        => array(
                'location_selection' => __('Select a location', ATBDP_TEXTDOMAIN),
                'category_selection' => __('Select a category', ATBDP_TEXTDOMAIN),
            )
        );
        wp_localize_script( 'atbdp_search_listing', 'atbdp_search_listing', $data );
    }
}



endif;