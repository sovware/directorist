<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Directorist\Asset_Loader\Localized_Data;
use Directorist\Multi_Directory\Multi_Directory_Manager;
/**
 * Setup wizard class
 *
 * Walkthrough to the basic setup upon installation
 */
class SetupWizard
{
    /** @var string Currenct Step */
    public $step   = '';

    /** @var array Steps for the setup wizard */
    public $steps  = array();

    /**
     * Actions to be executed after the HTTP response has completed
     *
     * @var array
     */
    private $deferred_actions = array();

    /**
     * Hook in tabs.
     */
    public function __construct() {
            add_action( 'admin_menu', array( $this, 'admin_menus' ) );
            add_action( 'admin_init', array( $this, 'setup_wizard' ), 99 );
            add_action( 'admin_notices', array( $this, 'render_run_admin_setup_wizard_notice' ) );
            add_action( 'wp_ajax_atbdp_dummy_data_import', array( $this, 'atbdp_dummy_data_import' ) );
            add_action( 'wp_ajax_directorist_setup_wizard', array( $this, 'directorist_setup_wizard' ) );
            add_action( 'wp_loaded', array( $this, 'hide_notices' ) );
    }

    public function directorist_setup_wizard() {
        if ( ! current_user_can( 'import' ) ) {
            wp_send_json( array(
                'error' => esc_html__( 'Invalid request!', 'directorist' ),
            ) );
        }

        if ( ! directorist_verify_nonce() ) {
            wp_send_json( array(
                'error' => esc_html__( 'Invalid nonce!', 'directorist' ),
            ) );
        }

        if( isset( $_POST['directory_type_settings'] ) ) {
            $request_directory_types = wp_remote_get( 'https://app.directorist.com/wp-json/directorist/v1/get-directory-types' );
            if( is_wp_error( $request_directory_types ) ) {
                return false;
            }
            $multi_directory_manager = new Multi_Directory_Manager;
            $get_types      = get_transient( 'directory_type' );
            $response_body  = wp_remote_retrieve_body( $request_directory_types );
            $pre_made_types = json_decode( $response_body, true );
            if ( $get_types ) {
                foreach ( $get_types as $type ) {
                    
                    if ( array_key_exists( $type, $pre_made_types ) ) {
                        $selected_type = $pre_made_types[$type];
                        $type_url      = $selected_type['url'];
                        $file_contents = directorist_get_json_from_url( $type_url );
                        if( $file_contents ) {
                            $multi_directory_manager->prepare_settings();
                            $multi_directory_manager->add_directory([
                                'directory_name' => $selected_type['name'],
                                'fields_value'   => $file_contents,
                                'is_json'        => false
                            ]);
                        }
                        
                        
                    }
                }
            }
        }

        if( isset( $_POST['share_non_sensitive_data'] ) ) {
            ATBDP()->insights->optin();
        } else {
            ATBDP()->insights->optout();
        }

        
        $data['url']           = admin_url('index.php?page=directorist-setup&step=step-four');

        wp_send_json_success( $data );
    }

    public function render_run_admin_setup_wizard_notice() {

        $setup_wizard = get_option( 'directorist_setup_wizard_completed' );
        $atpdp_setup_wizard = apply_filters( 'atbdp_setup_wizard', true );
        if( $setup_wizard || ! $atpdp_setup_wizard ) {
            return;
        }

        ?>
        <div id="message" class="updated atbdp-message">
            <p><?php echo wp_kses_post( __( '<strong>Welcome to Directorist</strong> &#8211; You&lsquo;re almost ready to start your directory!', 'directorist' ) ); ?></p>
            <p class="submit">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=directorist-setup' ) ); ?>" class="button-primary"><?php esc_html_e( 'Run the Setup Wizard', 'directorist' ); ?></a>
                <a class="button-secondary skip" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'directorist-hide-notice', 'install' ), 'directorist_hide_notices_nonce', '_atbdp_notice_nonce' ) ); ?>"><?php esc_html_e( 'Skip setup', 'directorist' ); ?></a>
            </p>
        </div>
    <?php
    }

    public function hide_notices() {
        if ( isset( $_GET['directorist-hide-notice'] ) && isset( $_GET['_atbdp_notice_nonce'] ) ) { // WPCS: input var ok, CSRF ok.
			if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_atbdp_notice_nonce'] ) ), 'directorist_hide_notices_nonce' ) ) { // WPCS: input var ok, CSRF ok.
				wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'directorist' ) );
			}

			update_option( 'directorist_setup_wizard_completed', true );
        }

        $create_pages = [
            'checkout_page'        => [
                'post_title'         => 'Checkout',
                'post_content'       => '[directorist_checkout]',
            ],
            'payment_receipt_page' => [
                'post_title'         => 'Payment Receipt',
                'post_content'       => '[directorist_payment_receipt]',
            ],
            'transaction_failure_page' => [
                'post_title'         => 'Transaction Failure',
                'post_content'       => '[directorist_transaction_failure]',
            ],
        ];
        $atbdp_option = get_option( 'atbdp_option' );

        if (!empty($atbdp_option['enable_monetization'])) {
            foreach ($create_pages as $key => $name) {

                $args = [
                    'post_title' => $name['post_title'],
                    'post_content' => $name['post_content'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'comment_status' => 'closed'
                ];
                if (empty($atbdp_option[$key])) {
                    $id = wp_insert_post($args);

                    if ($id) {
                        $atbdp_option[$key] = $id;
                    }
                }
            }
        }

        /**
         * @since 7.3.0
         */

        do_action( 'directorist_setup_wizard_completed' );

        update_option('atbdp_option', $atbdp_option);
    }

    public function atbdp_dummy_data_import()
    {

        if ( ! current_user_can( 'import' ) ) {
            wp_send_json( array(
                'error' => esc_html__( 'Invalid request!', 'directorist' ),
            ) );
        }

        if ( ! directorist_verify_nonce() ) {
            wp_send_json( array(
                'error' => esc_html__( 'Invalid nonce!', 'directorist' ),
            ) );
        }

        $data               = array();
        $imported           = 0;
        $failed             = 0;
        $count              = 0;
        $preview_image      = isset($_POST['image']) ? sanitize_text_field( wp_unslash( $_POST['image'] ) ) : '';
        $file               = isset($_POST['file']) ? sanitize_text_field( wp_unslash( $_POST['file'] ) ) : '';
        $total_length       = isset($_POST['limit']) ? sanitize_text_field( wp_unslash( $_POST['limit'])) : 0;
        $position           = isset($_POST['position']) ? sanitize_text_field( wp_unslash( $_POST['position'] ) ) : 0;
        $all_posts          = $this->read_csv($file);
        $posts              = array_slice($all_posts, $position);
        $limit              = 1;
        if ( ! $total_length ) {
            $data['error'] = __('No data found', 'directorist');
            die();
        }
        $listing_types = get_terms([
            'taxonomy'   => 'atbdp_listing_types',
            'hide_empty' => false,
            'showposts' => 1,
        ]);
        $directory_id = !empty( $listing_types[0] ) ? $listing_types[0]->term_id : '';
        $directory_slug = !empty( $listing_types[0] ) ? $listing_types[0]->slug : '';

		$allowed_meta_data_keys = array(
			'tagline',
			'price',
			'price_range',
			'atbdp_post_views_count',
			'hide_contact_owner',
			'address',
			'manual_lat',
			'manual_lng',
			'hide_map',
			'zip',
			'phone',
			'email',
			'website',
			'videourl',
		);

        foreach ( $posts as $index => $post ) {
                if ( $count === $limit ) {
					break;
				}

                // start importing listings
                $args = array(
                    'post_title'   => isset( $post['Title'] ) ? $post['Title'] : '',
                    'post_content' => isset( $post['Description'] ) ? $post['Description'] : '',
                    'post_type'    => 'at_biz_dir',
                    'post_status'  => 'publish',
                );

                $post_id = wp_insert_post( $args );

				// No need to process further since it's a failed insertion.
                if ( is_wp_error( $post_id ) ) {
					$failed++;
					continue;
                }

				$imported++;

                foreach($post as $key => $value){
                    $key = directorist_translate_to_listing_field_key( $key );
                    if ('category' == $key) {
                        $taxonomy = ATBDP_CATEGORY;
                        $term_exists = get_term_by( 'name', $value, $taxonomy );
                        if ( ! $term_exists ) { // @codingStandardsIgnoreLine.
                            $result = wp_insert_term( $value, $taxonomy );
                            if( !is_wp_error( $result ) ){
                                $term_id = $result['term_id'];
                                update_term_meta($term_id, '_directory_type', [ $directory_id ] );
                                wp_set_object_terms($post_id, $term_id, $taxonomy);
                            }
                        }else{
                            wp_set_object_terms($post_id, $term_exists->term_id, $taxonomy);
                        }
                    } elseif ('location' == $key) {
                        $taxonomy = ATBDP_LOCATION;
                        $term_exists = get_term_by( 'name', $value, $taxonomy );
                        if ( ! $term_exists ) { // @codingStandardsIgnoreLine.
                            $result = wp_insert_term( $value, $taxonomy );
                            if( !is_wp_error( $result ) ){
                                $term_id = $result['term_id'];
                                update_term_meta($term_id, '_directory_type',  [ $directory_id ] );
                                wp_set_object_terms($post_id, $term_id, $taxonomy);
                            }
                        }else{
                            wp_set_object_terms($post_id, $term_exists->term_id, $taxonomy);
                        }
                    } elseif ('tag' == $key){
                        $taxonomy = ATBDP_TAGS;
                        $term_exists = get_term_by( 'name', $value, $taxonomy );
                        if ( ! $term_exists ) { // @codingStandardsIgnoreLine.
                            $result = wp_insert_term( $value, $taxonomy );
                            if( !is_wp_error( $result ) ){
                                $term_id = $result['term_id'];
                                wp_set_object_terms($post_id, $term_id, $taxonomy);
                            }
                        }else{
                            wp_set_object_terms($post_id, $term_exists->term_id, $taxonomy);
                        }
                    }

                    if ( in_array( $key, $allowed_meta_data_keys, true ) && $value !== '' ) {
                        update_post_meta( $post_id, '_' . $key, $value );
                    }
                }

                $exp_dt = calc_listing_expiry_date();
                update_post_meta($post_id, '_expiry_date', $exp_dt);
                update_post_meta($post_id, '_featured', 0);
                update_post_meta($post_id, '_listing_status', 'post_status');
                $preview_url = isset($post['Image']) ? $post['Image'] : '';

                if ( $preview_image && $preview_url ) {
                   $attachment_id = ATBDP_Tools::atbdp_insert_attachment_from_url($preview_url, $post_id);
                   update_post_meta($post_id, '_listing_prv_img', $attachment_id);
                }

                //directory type
                if( !empty( $directory_id ) ){
                    update_post_meta($post_id, '_directory_type', $directory_id);
                    wp_set_object_terms($post_id, (int)$directory_id, 'atbdp_listing_types');
                }

                $count++;
        }
        $data['next_position'] = (int) $position + (int) $count;
        $data['percentage']    = absint(min(round((($data['next_position']) / $total_length) * 100), 100));
        $data['url']           = admin_url('index.php?page=directorist-setup&step=step-four');
        $data['total']         = $total_length;
        $data['imported']      = $imported;
        $data['failed']        = $failed;

        wp_send_json($data);
    }

    public function read_csv($file){
        $fp = fopen($file, 'r');
        $header = fgetcsv($fp);

        // get the rest of the rows
        $data = array();
        while ($row = fgetcsv($fp)) {
        $arr = array();
        foreach ($header as $i => $col)
            $arr[$col] = $row[$i];
        $data[] = $arr;
        }
        return $data;
    }

    /**
     * Add admin menus/screens.
     */
    public function admin_menus() {
		add_menu_page(
			__( 'Directorist Setup Wizard', 'directorist' ),
			__( 'Setup', 'directorist' ),
			'manage_options',
			'directorist-setup'
		);

		// Remove to remove the menu item only, page will just work fine.
		remove_menu_page( 'directorist-setup' );
    }

    /**
     * Show the setup wizard.
     */
    public function setup_wizard()
    {

        if (empty($_GET['page']) || 'directorist-setup' !== $_GET['page']) {
            return;
        }

        $this->set_steps();

        $this->step = isset($_GET['step']) ? sanitize_key($_GET['step']) : current(array_keys($this->steps));

        $this->enqueue_scripts();

        if (!empty($_POST['save_step']) && isset($this->steps[$this->step]['handler'])) { // WPCS: CSRF ok.
            call_user_func_array($this->steps[$this->step]['handler'], array($this));
        }

        ob_start();
        $this->set_setup_wizard_template();
        exit;
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style('atbdp_setup_select2', DIRECTORIST_VENDOR_CSS . 'select2.min.css', ATBDP_VERSION, true);
        wp_register_script('directorist-select2', DIRECTORIST_VENDOR_JS . 'select2.min.js', array('jquery'), ATBDP_VERSION, true);
       
        wp_enqueue_script('directorist-setup');
        wp_enqueue_script('directorist-select2');

        wp_register_style('directorist-admin-style', DIRECTORIST_CSS . 'admin-main.css', ATBDP_VERSION, true);
        wp_register_script('directorist-admin-setup-wizard-script', DIRECTORIST_JS . 'admin-setup-wizard.js', array('jquery'), ATBDP_VERSION, true);

        wp_enqueue_script('directorist-openstreet-layers', DIRECTORIST_VENDOR_JS . 'openstreet-map/openstreetlayers.js');
        wp_enqueue_script('directorist-openstreet-unpkg-index', DIRECTORIST_VENDOR_JS . 'openstreet-map/unpkg-index.js');
        wp_enqueue_script('directorist-openstreet-unpkg-libs', DIRECTORIST_VENDOR_JS . 'openstreet-map/unpkg-libs.js');
        wp_enqueue_script('directorist-openstreet-leaflet-versions', DIRECTORIST_VENDOR_JS . 'openstreet-map/leaflet-versions.js');
        wp_enqueue_script('directorist-openstreet-libs-setup', DIRECTORIST_VENDOR_JS . 'openstreet-map/libs-setup.js');
       
        wp_enqueue_script('directorist-openstreet-leaflet-markercluster-versions', DIRECTORIST_VENDOR_JS . 'openstreet-map/leaflet.markercluster-versions.js');

        wp_enqueue_script('directorist-test', DIRECTORIST_JS . 'openstreet-map.js', [
            'jquery',
            'directorist-openstreet-layers',
            'directorist-openstreet-unpkg-libs',
            'directorist-openstreet-leaflet-versions',
            'directorist-openstreet-libs-setup',
        ], ATBDP_VERSION, true);
        
        wp_enqueue_style('directorist-admin-style');
        wp_enqueue_script('directorist-admin-setup-wizard-script');

        wp_localize_script( 'jquery', 'directorist', Localized_Data::public_data() );

        wp_localize_script('directorist-admin-setup-wizard-script', 'import_export_data', [ 'ajaxurl' => admin_url('admin-ajax.php'), 'directorist_nonce' => wp_create_nonce( directorist_get_nonce_key() ) ] );
    }

    /**
     * Set wizard steps
     *
     * @since 2.9.27
     *
     * @return void
     */
    protected function set_steps()
    {
        $this->steps = apply_filters('directorist_admin_setup_wizard_steps', array(
            'introduction' => array(
                'name'    =>  __('Introduction', 'directorist'),
                'view'    => array( $this, 'directorist_setup_introduction' ),
                'handler' => array( $this, 'directorist_step_intro_save' ),
            ),
            'step-one' => array(
                'name'    =>  __('Step One', 'directorist'),
                'view'    => array( $this, 'directorist_step_one' ),
                'handler' => array( $this, 'directorist_step_one_save' ),
            ),
            'step-two' => array(
                'name'    =>  __('Step Two', 'directorist'),
                'view'    => array( $this, 'directorist_step_two' ),
                'handler' => array( $this, 'directorist_step_two_save' ),
            ),
            'step-three' => array(
                'name'    =>  __('Step Three', 'directorist'),
                'view'    => array( $this, 'directorist_step_three' ),
                'handler' => array( $this, 'directorist_step_three_save' ),
            ),
            'step-four' => array(
                'name'    =>  __('Step Four', 'directorist'),
                'view'    => array( $this, 'directorist_step_four' ),
            ),
        ));
    }

    public function get_map_data() {
		

		$data = array(
			'p_id'               => '',
			//'listing_form'       => $this,
			'listing_info'       => '',
			'select_listing_map' => get_directorist_option( 'select_listing_map', 'google' ),
			'display_map_for'    => get_directorist_option( 'display_map_for', 0 ),
			'display_map_field'  => get_directorist_option( 'display_map_field', 1 ),
			'manual_lat'         => '',
			'manual_lng'         => '',
			'default_latitude'   => get_directorist_option( 'default_latitude', '40.7127753', true ),
			'default_longitude'  => get_directorist_option( 'default_longitude', '-74.0059728', true ),
			'info_content'       => '',
			'map_zoom_level'     => get_directorist_option( 'map_zoom_level', 4 ),
			'marker_title'       => __( 'You can drag the marker to your desired place to place a marker', 'directorist' ),
			'geocode_error_msg'  => __( 'Geocode was not successful for the following reason: ', 'directorist' ),
			'map_icon'           => directorist_icon( 'fas fa-map-pin', false ),
		);

		return $data;
	}

    public function directorist_step_one() { 
        $map_data = $this->get_map_data();
        Directorist\Helper::add_hidden_data_to_dom( 'map_data', $map_data );
        ?>
        <div class="directorist-setup-wizard__box">
            <div class="directorist-setup-wizard__box__content">
                <h1 class="directorist-setup-wizard__box__content__title">Default Location</h1>
                <p class="directorist-setup-wizard__box__content__desc">Drag the map or marker to the middle of your city</p>
                <h4 class="directorist-setup-wizard__box__content__title directorist-setup-wizard__box__content__title--section">Add your location</h4>
                <div class="directorist-setup-wizard__box__content__form directorist-form-address-field">

                    <input type="text" autocomplete="off" name="" class="directorist-setup-wizard__box__content__input directorist-location-js" value="" placeholder="Search your location">
                    <input type="hidden" name="default_latitude" id="manual_lat" value="" />
                    <input type="hidden" name="default_longitude" id="manual_lng" value="" />
	                <div class="address_result"><ul></ul></div>
                    
                </div>

                <div class="directorist-setup-wizard__map directorist-form-map-field__maps">
                    <div id="osm">
                        <div id="gmap">
                            <div id="gmap_full_screen_button">
                                <span class="fullscreen-enable"><?php directorist_icon( 'fas fa-expand' ); ?></span>
                                <span class="fullscreen-disable"><?php directorist_icon( 'fas fa-compress' ); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <?php
    }

    public function directorist_step_one_save() {
        check_admin_referer('directorist-setup');

        $_post_data   = wp_unslash( $_POST );
        $atbdp_option = get_option('atbdp_option');
        
        $atbdp_option['default_latitude'] = !empty($_post_data['default_latitude']) ? $_post_data['default_latitude'] : '';
        $atbdp_option['default_longitude'] = !empty($_post_data['default_longitude']) ? $_post_data['default_longitude'] : '';

        update_option('atbdp_option', $atbdp_option);

        /**
        * @since 7.3.0
        */
        do_action( 'directorist_setup_wizard_map' );

        wp_redirect(esc_url_raw($this->get_next_step_link()));
        exit;
    }

    public function directorist_step_two()
    {

    ?>
        <div class="directorist-setup-wizard__content">
            <div class="directorist-setup-wizard__content__header--listings">
                <h1 class="directorist-setup-wizard__content__header__title"><?php esc_html_e('Yes! You can earn using Directorist Core for FREE', 'directorist'); ?></h1>
            </div>
            <div class="directorist-setup-wizard__content__items directorist-setup-wizard__content__items--listings">
                <div class="directorist-setup-wizard__content__pricing">
                    <div class="directorist-setup-wizard__content__pricing__checkbox">
                        <span class="feature-title">Featured Listings</span>
                        <input type="checkbox" name="enable_monetization" id="enable_featured" value=1 />
                        <label for="enable_featured"></label>
                    </div>
                    <div class="directorist-setup-wizard__content__pricing__amount">
                        <span class="price-title">Pricing</span>
                        <div class="price-amount">
                            <span class="price-prefix">$</span>
                            <input type="text" name='featured_listing_price' id='featured_listing_price' value=19.99 />
                        </div>
                    </div>
                </div>
                <div class="directorist-setup-wizard__content__gateway">
                    <h4 class="directorist-setup-wizard__content__gateway__title">Gateways</h4>
                    <div class="directorist-setup-wizard__content__gateway__checkbox">
                        <span class="gateway-title">Bank Transfer</span>
                        <input type="checkbox" name="active_gateways[]" id="enable_bank_transfer" value="bank_transfer" />
                        <label for="enable_bank_transfer"></label>
                    </div>
                    <div class="directorist-setup-wizard__content__gateway__checkbox">
                        <span class="gateway-title">Paypal</span>
                        <input type="checkbox" name="active_gateways[]" id="enable_paypal" value="paypal_gateway" />
                        <label for="enable_paypal"></label>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    /**
     * Save store options.
     */
    public function directorist_step_two_save()
    {
        check_admin_referer('directorist-setup');

        $_post_data = wp_unslash( $_POST );

        $atbdp_option = get_option('atbdp_option');
        $pages = !empty( $_post_data['share_essentials'] ) ? $_post_data['share_essentials'] : '';
        $atbdp_option['map_api_key'] = !empty($_post_data['map_api_key']) ? $_post_data['map_api_key'] : '';
        $atbdp_option['enable_monetization'] = !empty($_post_data['enable_monetization']) ? $_post_data['enable_monetization'] : '';
        $atbdp_option['enable_featured_listing'] = !empty($_post_data['enable_featured_listing']) ? $_post_data['enable_featured_listing'] : '';
        $atbdp_option['featured_listing_price'] = !empty($_post_data['featured_listing_price']) ? $_post_data['featured_listing_price'] : '';
        $atbdp_option['active_gateways'] = !empty($_post_data['active_gateways']) ? $_post_data['active_gateways'] : array();

        do_action('directorist_admin_setup_wizard_save_step_two');

        $create_pages = [
            'checkout_page'        => [
                'post_title'         => 'Checkout',
                'post_content'       => '[directorist_checkout]',
            ],
            'payment_receipt_page' => [
                'post_title'         => 'Payment Receipt',
                'post_content'       => '[directorist_payment_receipt]',
            ],
            'transaction_failure_page' => [
                'post_title'         => 'Transaction Failure',
                'post_content'       => '[directorist_transaction_failure]',
            ],
        ];

        // if (!empty($atbdp_option['enable_monetization'])) {
        //     foreach ($create_pages as $key => $name) {

        //         $args = [
        //             'post_title' => $name['post_title'],
        //             'post_content' => $name['post_content'],
        //             'post_status' => 'publish',
        //             'post_type' => 'page',
        //             'comment_status' => 'closed'
        //         ];
        //         if (empty($atbdp_option[$key])) {
        //             $id = wp_insert_post($args);

        //             if ($id) {
        //                 $atbdp_option[$key] = $id;
        //             }
        //         }
        //     }
        // }
        update_option('atbdp_option', $atbdp_option);

        wp_redirect(esc_url_raw($this->get_next_step_link()));
        exit;
    }

    public function directorist_step_three()
    {
        
    ?>
        <div class="directorist-setup-wizard__content">
            <div class="directorist-setup-wizard__content__header text-center">
                <h1 class="directorist-setup-wizard__content__header__title"><?php esc_html_e('Insert Content', 'directorist'); ?></h1>
                <p class="directorist-setup-wizard__content__header__desc"><?php echo wp_kses(__('Install required tools, Import listings, share non-sensitive data, etc', 'directorist'), ['strong' => []]); ?></p>
            </div>
            <div class="directorist-setup-wizard__content__items directorist-setup-wizard__content__import">
                <div class="directorist-setup-wizard__content__import__wrapper">
                    <h3 class="directorist-setup-wizard__content__import__title">Install required tools</h3>
                    <div class="directorist-setup-wizard__content__import__single">
                        <input type="checkbox" name="import_listings" id="import-listing" value="yes" />
                        <label for="import-listing">Import Listing</label>
                    </div>
                    <div class="directorist-setup-wizard__content__import__single">
                        <input type="checkbox" name="directory_type_settings" value="yes" id="import-directory-settings" />
                        <label for="import-directory-settings">Import Directory Settings</label>
                    </div>
                    <div class="directorist-setup-wizard__content__import__single">
                        <input type="checkbox" name="business" id="install-required-plugins" />
                        <label for="install-required-plugins">Install Required Plugins</label>
                    </div>
                    <div class="directorist-setup-wizard__content__import__single">
                        <input type="checkbox" name="share_non_sensitive_data" id="share-data" value="yes" checked/>
                        <label for="share-data">Share Non-Sensitive Data</label>
                    </div>
                </div>
                <a href="#" class="directorist-setup-wizard__content__import__btn directorist-setup-wizard__btn directorist-setup-wizard__btn--full directorist-submit-importing">
                    Submit & Build My Directory Website 
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="12.007" viewBox="284 4 14 12.007"><g data-name="Group 2970"><path d="M284.841 9.02c.058-.009.116-.013.174-.012h9.876l-.215-.1c-.21-.1-.402-.236-.566-.401l-2.77-2.77a1.037 1.037 0 0 1-.145-1.327 1.002 1.002 0 0 1 1.503-.13l5.008 5.008a1.002 1.002 0 0 1 0 1.418l-5.008 5.008a1.002 1.002 0 0 1-1.503-.1c-.28-.419-.22-.98.145-1.327l2.765-2.775c.147-.147.316-.27.501-.366l.3-.135h-9.836a1.037 1.037 0 0 1-1.057-.841 1.002 1.002 0 0 1 .828-1.15Z" fill="#fff" fill-rule="evenodd" data-name="Path 1600"/></g></svg>
                </a>
                <div class="directorist-setup-wizard__content__import__notice">
                    By clicking "Submit & Build My Website", you agree to our <a href="#">Terms</a> & <a href="#">Privacy Policy</a>
                </div>
            </div>
        </div>
    <?php
    }

    public function directorist_step_three_save()
    {
        check_admin_referer('directorist-setup');

        $_post_data = wp_unslash($_POST);

        $pages = !empty($_post_data['map']) ? $_post_data['map'] : '';
        wp_redirect(esc_url_raw($this->get_next_step_link()));
        exit;
    }

    public function directorist_step_four()
    {
        update_option( 'directorist_setup_wizard_completed', true );

        /**
         * @since 7.3.0
         */

        do_action( 'directorist_setup_wizard_completed' );
        ?>
        <div class="directorist-setup-wizard__content">
            <div class="directorist-setup-wizard__content__items directorist-setup-wizard__content__items--completed">
                <svg class="congratulations-img" xmlns="http://www.w3.org/2000/svg" width="58.999" height="58.999" viewBox="611.001 174.001 58.999 58.999">
                    <g data-name="Group 2976"><path d="m658.324 217.969-44.67 14.927a2.013 2.013 0 0 1-2.549-2.548l14.927-44.671 32.292 32.292Z" fill="#fdc70e" fill-rule="evenodd" data-name="Path 1603"/><path d="M658.324 217.97s-6.579-1.49-18.684-13.606c-11.784-11.773-13.524-18.34-13.607-18.683v-.012s6.591 1.49 18.7 13.6c12.11 12.109 13.591 18.702 13.591 18.702Z" fill="#d39518" fill-rule="evenodd" data-name="Path 1604"/><path d="m629.457 227.614-6.62 2.21a107.26 107.26 0 0 1-4.45-4.21c-1.58-1.58-2.97-3.06-4.21-4.45l2.21-6.62c1.68 2.03 3.73 4.3 6.25 6.82 2.52 2.52 4.79 4.57 6.82 6.25Z" fill="#2167d8" fill-rule="evenodd" data-name="Path 1605"/><path d="m647.477 221.594-7.11 2.37a82.592 82.592 0 0 1-10.93-9.4 82.593 82.593 0 0 1-9.4-10.93l2.37-7.11a74.4 74.4 0 0 0 11.28 13.79 74.4 74.4 0 0 0 13.79 11.28Z" fill="#d3374e" fill-rule="evenodd" data-name="Path 1606"/><path d="M635.53 194.41a1 1 0 0 1-.707-1.707 12.2 12.2 0 0 0 2.36-14.675 1 1 0 1 1 1.714-1.024 14.247 14.247 0 0 1-2.66 17.118.993.993 0 0 1-.707.288Z" fill="#2167d8" fill-rule="evenodd" data-name="Path 1607"/><path d="M633.857 184.37a1 1 0 0 1-.707-.293l-1.674-1.673a1 1 0 0 1 1.415-1.414l1.673 1.673a1 1 0 0 1-.707 1.707Z" fill="#23af6f" fill-rule="evenodd" data-name="Path 1608"/><path d="M645.57 182.697a1 1 0 0 1-1-1v-1.673a1 1 0 0 1 2 0v1.673a1 1 0 0 1-1 1Z" fill="#d3374e" fill-rule="evenodd" data-name="Path 1609"/><path d="M647.244 189.39h-1.674a1 1 0 0 1 0-2h1.674a1 1 0 0 1 0 2Z" fill="#23af6f" fill-rule="evenodd" data-name="Path 1610"/><path d="M650.59 209.47a1 1 0 0 1-.707-1.706 14.248 14.248 0 0 1 17.12-2.66 1 1 0 0 1-1.028 1.715 12.067 12.067 0 0 0-14.678 2.359 1 1 0 0 1-.707.293Z" fill="#2167d8" fill-rule="evenodd" data-name="Path 1611"/><path d="M662.304 212.817a1 1 0 0 1-.707-.292l-1.674-1.674a1 1 0 1 1 1.414-1.414l1.674 1.673a1 1 0 0 1-.707 1.707Z" fill="#23af6f" fill-rule="evenodd" data-name="Path 1612"/><path d="M663.976 199.43h-1.672a1 1 0 0 1 0-2h1.672a1 1 0 0 1 0 2Z" fill="#d3374e" fill-rule="evenodd" data-name="Path 1613"/><path d="M655.61 199.43a1 1 0 0 1-1-1v-1.673a1 1 0 0 1 2 0v1.674a1 1 0 0 1-1 1Z" fill="#d3374e" fill-rule="evenodd" data-name="Path 1614"/><path d="M647.244 196.084a1 1 0 0 1-.707-1.707 4.061 4.061 0 0 1 3.216-1.128h.022a2.1 2.1 0 0 0 1.78-.542 2.119 2.119 0 0 0 .541-1.8 3.94 3.94 0 0 1 4.346-4.348 2 2 0 0 0 2.355-2.355 4.067 4.067 0 0 1 1.131-3.218 1 1 0 1 1 1.41 1.418 2.128 2.128 0 0 0-.546 1.8 3.942 3.942 0 0 1-4.349 4.349 1.993 1.993 0 0 0-2.346 2.35 4.054 4.054 0 0 1-1.128 3.217 4.105 4.105 0 0 1-3.216 1.128h-.023a2.094 2.094 0 0 0-1.778.542 1 1 0 0 1-.708.294Z" fill="#fdc70e" fill-rule="evenodd" data-name="Path 1615"/><g data-name="Group 2975"><path d="M650.591 184.37a1 1 0 0 1-.925-1.381 9.272 9.272 0 0 0 .042-7.515 1 1 0 1 1 1.764-.944 11.275 11.275 0 0 1 .044 9.22 1 1 0 0 1-.925.62Z" fill="#d3374e" fill-rule="evenodd" data-name="Path 1616"/><path d="M660.63 194.41a1 1 0 0 1-.38-1.925 11.266 11.266 0 0 1 9.221.044 1 1 0 0 1-.944 1.764 9.267 9.267 0 0 0-7.516.042c-.12.05-.25.075-.38.075Z" fill="#d3374e" fill-rule="evenodd" data-name="Path 1617"/></g></g>
                </svg>
                <h1 class="directorist-setup-wizard__content__title"><?php esc_html_e('Congratulations', 'directorist'); ?></h1>
                <h4 class="directorist-setup-wizard__content__desc"><?php esc_html_e('Your directory website is ready. Thank you for using Directorist', 'directorist'); ?></h4>
                <h2 class="directorist-setup-wizard__content__title--section"><?php esc_html_e('What\'s next?', 'directorist'); ?></h2>
                <div class="directorist-setup-wizard__content__btns">
                    <a href="<?php echo esc_url(admin_url().'edit.php?post_type=at_biz_dir'); ?>" class="directorist-setup-wizard__btn"><?php esc_html_e('Create Your First Listing', 'directorist'); ?></a>  
                    <a href="<?php echo esc_url(admin_url().'edit.php?post_type=at_biz_dir'); ?>" class="directorist-setup-wizard__btn directorist-setup-wizard__btn--return"><?php esc_html_e('Return to the Wordpress Dashboard', 'directorist'); ?></a>  
                </div>
            </div>
        </div>
    <?php
    }

    public function get_steps()
    {
        return $this->steps;
    }

    /**
     * Introduction step.
     */
    public function directorist_setup_introduction()
    {
    ?>
        <div class="directorist-setup-wizard__content">
            <div class="directorist-setup-wizard__content__header">
                <h1 class="directorist-setup-wizard__content__header__title"><?php esc_html_e('What type of directory are you creating?', 'directorist'); ?></h1>
                <p class="directorist-setup-wizard__content__header__desc"><?php echo wp_kses(__('Select the directory type you’re building. Weather it’s a business directory, a classifieds platform, or something else, we’ve got you covered.', 'directorist'), ['strong' => []]); ?></p>
            </div>
            <div class="directorist-setup-wizard__content__items">
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="directory_type[]" id="business-directory" value="business" />
                    <label for="business-directory">Business Directory</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="classified" id="classified-listing" value="classified" />
                    <label for="classified-listing">Classified Listing</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="directory_type[]" id="car-directory" value="car" />
                    <label for="car-directory">Car Directory</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="directory_type[]" id="car-rent-directory" value="car_rent" />
                    <label for="car-rent-directory">Car Rent Directory</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="directory_type[]" id="real-estate" value="real-estate" />
                    <label for="real-estate">Real Estate</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="directory_type[]" id="travel-directory" value="travel" />
                    <label for="travel-directory">Travel Directory</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="directory_type[]" id="service-directory" value="service" />
                    <label for="service-directory">Service Directory</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="directory_type[]" id="job-directory" value="job" />
                    <label for="job-directory">Job Directory</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="directory_type[]" id="hotel-directory" value="hotel" />
                    <label for="hotel-directory">Hotel Directory</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="directory_type[]" id="restaurant-directory" value="restaurant" />
                    <label for="restaurant-directory">Restaurant Directory</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="directory_type[]" id="multipurpose-directory" value="multipurpose"/>
                    <label for="multipurpose-directory">Multipurpose Directory</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="directory_type[]" id="lawyers-directory" value="lawyers" />
                    <label for="lawyers-directory">Lawyers Directory</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="directory_type[]" id="doctors-directory" value="doctors" />
                    <label for="doctors-directory">Doctors Directory</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="other_directory" id="others-listing" value="other" />
                    <label for="others-listing">Others</label>
                </div>
                <div class="directorist-setup-wizard__checkbox directorist-setup-wizard__checkbox--custom">
                    <input type="text" name="other_directory_type" id="others-listing" placeholder="Type Your Preferred Directory Name" />
                </div>
            </div>
            <div class="directorist-setup-wizard__content__notice">
                <svg xmlns="http://www.w3.org/2000/svg" width="14.932" height="16" viewBox="570 654 14.932 16"><path d="M580.32 669.25a.75.75 0 0 1-.75.75h-7.07a2.503 2.503 0 0 1-2.5-2.5v-11a2.503 2.503 0 0 1 2.5-2.5h7.07a.75.75 0 0 1 0 1.5h-7.07c-.552 0-1 .448-1 1v11c0 .552.448 1 1 1h7.07a.75.75 0 0 1 .75.75Zm4.393-7.78-3.564-3.564a.75.75 0 1 0-1.061 1.06l2.284 2.284h-5.905a.75.75 0 0 0 0 1.5h5.905l-2.284 2.284a.75.75 0 1 0 1.06 1.06l3.565-3.564a.75.75 0 0 0 0-1.06Z" fill="#484848" fill-rule="evenodd" data-name="Path 1620"/></svg> Not Right Now. Exit to Dashboard
            </div>
        </div>
    <?php
    }

    public function directorist_step_intro_save() {
        check_admin_referer('directorist-setup');

        $_post_data      = wp_unslash( $_POST );
        $expiration_time = 24 * HOUR_IN_SECONDS;
        $atbdp_option    = get_option('atbdp_option');
        
        $directory_type = ! empty( $_post_data['directory_type'] ) ? $_post_data['directory_type'] : array();

        if( count( $directory_type ) > 1 ) {
            $atbdp_option['enable_multi_directory'] = true;
            update_option('atbdp_option', $atbdp_option);
        }

        if( ! empty( $_post_data['other_directory_type'] ) ) {
            $other_directory_type = array(
                'other_directory_type' => $_post_data['other_directory_type'],
            );
            ATBDP()->insights->add_extra( $other_directory_type );
        }
       
        set_transient( 'directory_type', $directory_type, $expiration_time );

        /**
        * @since 7.3.0
        */
        do_action( 'directorist_setup_wizard_introduction' );

        wp_redirect(esc_url_raw($this->get_next_step_link()));
        exit;
    }

    public function get_next_step_link()
    {
        $keys = array_keys($this->steps);

        return add_query_arg('step', $keys[array_search($this->step, array_keys($this->steps)) + 1]);
    }

    /**
     * Wizard templates
     *
     * @since 2.9.27
     *
     * @return void
     */
    protected function set_setup_wizard_template()
    {
        $this->setup_wizard_header();
        $this->setup_wizard_steps();
        $this->setup_wizard_content();
        $this->setup_wizard_footer();
    }

    /**
     * Setup Wizard Header.
     */
    public function setup_wizard_header()
    {
        set_current_screen();
        $hide = ! isset( $_GET['step'] ) ? 'directorist-setup-wizard-vh' : 'directorist-setup-wizard-vh-none';
        
        $ouput_steps = $this->steps;
        array_shift($ouput_steps);
        $hide = ! isset( $_GET['step'] ) ? 'atbdp-none' : '';
        $step = ! empty( $_GET['step'] ) ? $_GET['step'] : '';
        $introduction_step = empty( $step ) || 'step-one' == $step || 'step-two' == $step || 'step-three' == $step ? 'active' : ''; 
        $step_one = ( ! empty( $step ) && ( 'step-one' == $step || 'step-two' == $step || 'step-three' == $step ) ) ? 'active' : '' ; 
        $step_two = ( ! empty( $step ) && ( 'step-two' == $step || 'step-three' == $step ) ) ? 'active' : '' ; 
        $step_three = ( ! empty( $step ) && ( 'step-three' == $step || 'step-three' == $step ) ) ? 'active' : '' ;
        
        $header_title = __( 'Choose a directory type', 'directorist' );
        $active_number = 1;

        switch ( $step ) {
            case 'step-one':
                $active_number = 2;
                $header_title = __( 'Choose Default Location', 'directorist' );
                break;
            case 'step-two':
                $active_number = 3;
                $header_title = __( 'Earn with Directorist', 'directorist' );
                break;
            case 'step-three':
                $active_number = 4;
                $header_title = __( 'Insert Content', 'directorist' );
                break;
            default:
                $active_number = 1;
        }
    ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>

        <head>
            <meta name="viewport" content="width=device-width" />
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title><?php esc_html_e('Directorist &rsaquo; Setup Wizard', 'directorist'); ?></title>
            <?php wp_print_scripts('directorist-admin-setup-wizard-script'); ?>
            <?php wp_print_scripts('directorist-test'); ?>
            <?php wp_print_scripts('directorist-select2'); ?>
            <?php do_action('admin_print_styles'); ?>
            <?php do_action('admin_head'); ?>
            <?php do_action('directorist_setup_wizard_styles'); ?>
        </head>

        <body class="atbdp-setup directorist-setup-wizard wp-core-ui<?php echo get_transient('directorist_setup_wizard_no_wc') ? esc_attr( ' directorist-setup-wizard-activated-wc' ) : '';  ?> <?php echo esc_attr( $hide ); ?>">
            <form method="post" class="directorist-setup-wizard-wrapper directorist-setup-wizard__wrapper">
            <?php if (!(isset($_GET['step']) && $_GET['step'] == 'step-four')) : ?>
                <div class="directorist-setup-wizard__header">
                    <div class="directorist-setup-wizard__logo">
                        <img src="<?php echo esc_url( DIRECTORIST_ASSETS . 'images/directorist-logo.svg' );?>" alt="Directorist">
                    </div>
                    <div class="directorist-setup-wizard__header__step">
                        <ul class="atbdp-setup-steps <?php echo esc_attr( $hide ); ?>">
                                <li class="<?php echo esc_attr( $introduction_step ); ?>"></li>
                                <li class="<?php echo esc_attr( $step_one ); ?>"></li>
                                <li class="<?php echo esc_attr( $step_two ); ?>"></li>
                                <li class="<?php echo esc_attr( $step_three ); ?>"></li>
                        </ul>
                        <span class="step-count"><?php esc_html_e( sprintf( '%s %d of 4', $header_title, $active_number ), 'your-text-domain' ); ?></span>
                    </div>
                    <div class="directorist-setup-wizard__close">
                        <a href="#" class="directorist-setup-wizard__close__btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="11.998" viewBox="1237 31 12 11.998"><path d="m1244.409 36.998 4.295-4.286a1.003 1.003 0 0 0-1.418-1.418L1243 35.59l-4.286-4.296a1.003 1.003 0 0 0-1.418 1.418l4.295 4.286-4.295 4.286a.999.999 0 0 0 0 1.419.999.999 0 0 0 1.418 0l4.286-4.296 4.286 4.296a1 1 0 0 0 1.418 0 .999.999 0 0 0 0-1.419l-4.295-4.286Z" fill="#b7b7b7" fill-rule="evenodd" data-name="times"/></svg>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            
        <?php
    }

    /**
     * Output the steps.
     */
    public function setup_wizard_steps()
    {
        $ouput_steps = $this->steps;
        array_shift($ouput_steps);
        $hide = ! isset( $_GET['step'] ) ? 'atbdp-none' : '';
        ?>
            <!-- <ul class="atbdp-setup-steps <?php echo esc_attr( $hide ); ?>">
            <li class="atbdsw-logo"><img src="<?php echo esc_url(DIRECTORIST_ASSETS . 'images/directorist-logo.svg');?>" alt="Directorist"></li>
                <?php foreach ($ouput_steps as $step_key => $step) : ?>
                    <li class="<?php
                        if ($step_key === $this->step && 'step-four' != $step_key ) {
                            echo 'active';
                        } elseif ( array_search( $this->step, array_keys($this->steps ) ) > array_search( $step_key, array_keys( $this->steps ) ) ) {
                            echo 'done';
                        } elseif ( isset( $_GET['step'] ) && 'step-four' == $_GET['step'] ) {
                            echo 'done';
                        }
                        $number = 1;
                        if ( 'step-one' == $step_key ) {
                            $number = 1;
                        } else if ( 'step-two' == $step_key ) {
                            $number = 2;
                        } else if ( 'step-three' == $step_key ) {
                            $number = 3;
                        } else if ( 'step-four' == $step_key ) {
                            $number = 4;
                        }
                        ?>"><span class="atbdp-sw-circle"><span><?php echo esc_html( $number ); ?></span> <span class="dashicons dashicons-yes"></span></span><?php echo esc_html( $step['name'] ); ?> </li>
                <?php endforeach; ?>
            </ul> -->
        <?php
    }

    /**
     * Output the content for the current step.
     */
    public function setup_wizard_content()
    {
        if ( empty( $this->steps[ $this->step ]['view'] ) ) {
            wp_redirect(esc_url_raw(add_query_arg('step', 'introduction')));
            exit;
        }
        $introduction_class = ! isset( $_GET['step'] ) ? 'directorist-setup-wizard__introduction' : '';
        echo '<div class="directorist-setup-wizard__step '. esc_attr( $introduction_class ) .'">';
        call_user_func($this->steps[$this->step]['view']);
        echo '</div>';
    }

    /**
     * Setup Wizard Footer.
     */
    public function setup_wizard_footer()
    {
        ?>
            <?php if ( 'next_steps' === $this->step ) : ?>
                <a class="atbdp-return-to-dashboard" href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Return to the WordPress Dashboard', 'directorist'); ?></a>
            <?php endif; ?>
            <?php if (!(isset($_GET['step']) && ($_GET['step'] == 'step-three' || $_GET['step'] == 'step-four'))) : ?>
                <div class="directorist-setup-wizard__footer">
                    <?php if ( ! empty( $_GET['step'] ) && 'step-four' != $_GET['step'] ) : ?>
                    <div class="directorist-setup-wizard__back">
                        <a href="<?php echo esc_url( wp_get_referer() ) ?>" class="directorist-setup-wizard__back__btn">
                            <img src="<?php echo esc_url(DIRECTORIST_ASSETS . 'images/angle-down.svg');?>" />
                            Back
                        </a>
                    </div>
                    <?php endif; ?>
                    <div class="directorist-setup-wizard__next">
                        <?php if ( ! empty( $_GET['step'] ) && 'step-four' != $_GET['step'] ) : ?>
                            <a href="/wp-admin/index.php?page=directorist-setup&amp;step=step-three" class="w-skip-link">Skip this step</a>
                        <?php endif; ?>
                        <?php wp_nonce_field('directorist-setup'); ?>
                        <button type="submit" class="directorist-setup-wizard__btn directorist-setup-wizard__btn--next" name="save_step" value="submit">Next <img src="<?php echo esc_url(DIRECTORIST_ASSETS . 'images/arrow-right.svg');?>" /></button>
                    </div>
                </div>
            <?php endif; ?>
            </form>
        </body>

        </html>
<?php
    }
}
new SetupWizard();