<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
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
            add_action( 'wp_loaded', array( $this, 'hide_notices' ) );
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

        wp_enqueue_style('directorist-admin-style');
        wp_enqueue_script('directorist-admin-setup-wizard-script');

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

    public function directorist_step_one() { ?>
        <div class="directorist-setup-wizard__box">
            <div class="directorist-setup-wizard__box__content directorist-setup-wizard__box__content--location">
                <h1 class="directorist-setup-wizard__box__content__title">Default Location</h1>
                <p class="directorist-setup-wizard__box__content__desc">Drag the map or marker to the middle of your city</p>
                <h4 class="directorist-setup-wizard__box__content__title directorist-setup-wizard__box__content__title--section">Add your location</h4>
                <input type="text" placeholder="Search your location" />
                <div id="map" style="height: 400px; width: 100%;"></div>
            </div>
        </div>

        <?php
    }

    public function directorist_step_one_save() {
        check_admin_referer('directorist-setup');

        $_post_data = wp_unslash( $_POST );
        $all_categories     = !empty( $_post_data['all_categories'] ) ? $_post_data['all_categories'] : '';
        $all_locations      = !empty( $_post_data['all_locations'] ) ? $_post_data['all_locations'] : '';
        $all_authors        = !empty( $_post_data['all_authors'] ) ? $_post_data['all_authors'] : '';
        $terms_conditions   = !empty( $_post_data['terms_conditions'] ) ? $_post_data['terms_conditions'] : '';
        $privacy_policy     = !empty( $_post_data['privacy_policy'] ) ? $_post_data['privacy_policy'] : '';

        $atbdp_option = get_option('atbdp_option');
        $create_pages = [];
        if ( ! empty( $all_categories ) ) {
            $create_pages['all_categories_page'] = array(
                'post_title' => __('All Categories', 'directorist'),
                'post_content' => '[directorist_all_categories]'
            );
        }
        if ( ! empty( $all_locations ) ) {
            $create_pages['all_locations_page'] = array(
                'post_title' => __('All Locations', 'directorist'),
                'post_content' => '[directorist_all_locations]'
            );
        }
        if ( ! empty( $all_authors ) ) {
            $create_pages['all_authors_page'] = array(
                'post_title' => __('All Authors', 'directorist'),
                'post_content' => '[directorist_all_authors]'
            );
        }
        if ( ! empty( $terms_conditions ) ) {
            $create_pages['terms_conditions'] = array(
                'post_title' => __('Terms and Conditions', 'directorist'),
                'post_content' => ''
            );
        }
        if ( ! empty( $privacy_policy ) ) {
            $create_pages['privacy_policy'] = array(
                'post_title' => __('Privacy Policy', 'directorist'),
                'post_content' => ''
            );
        }

        if ( ! empty( $create_pages ) ) {
            foreach ( $create_pages as $key => $name ) {

                $args = [
                    'post_title' => $name['post_title'],
                    'post_content' => $name['post_content'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'comment_status' => 'closed'
                ];
                if ( empty( $atbdp_option[ $key ] ) ) {
                    $id = wp_insert_post($args);

                    if ($id) {
                        $atbdp_option[$key] = $id;
                    }
                }
            }
        }

        update_option('atbdp_option', $atbdp_option);

        /**
        * @since 7.3.0
        */
        do_action( 'directorist_setup_wizard_page_created' );

        wp_redirect(esc_url_raw($this->get_next_step_link()));
        exit;
    }

    public function directorist_step_two()
    {

    ?>
        <div class="directorist-setup-wizard__content">
            <div class="directorist-setup-wizard__content__header">
                <h1 class="directorist-setup-wizard__content__header__title"><?php esc_html_e('Yes You can use directorist core for free', 'directorist'); ?></h1>
            </div>
            <div class="directorist-setup-wizard__content__items directorist-setup-wizard__content__items--listings">
                <div class="directorist-setup-wizard__content__pricing">
                    <div class="directorist-setup-wizard__content__pricing__checkbox">
                        <input type="checkbox" name="enable_featured" id="enable_featured" />
                        <label for="enable_featured">Featured Listings</label>
                    </div>
                    <div class="directorist-setup-wizard__content__pricing__amount">
                        <span class="price-title">Pricing</span>
                        <span class="price-amount">$19.99</label>
                    </div>
                </div>
                <div class="directorist-setup-wizard__content__gateway">
                    <h4 class="directorist-setup-wizard__content__gateway__title">Gateways</h4>
                    <div class="directorist-setup-wizard__content__gateway__checkbox">
                        <input type="checkbox" name="enable_bank_transfer" id="enable_bank_transfer" />
                        <label for="enable_bank_transfer">Bank Transfer</label>
                    </div>
                    <div class="directorist-setup-wizard__content__gateway__checkbox">
                        <input type="checkbox" name="enable_paypal" id="enable_paypal" />
                        <label for="enable_paypal">Paypal</label>
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
        $atbdp_option['select_listing_map'] = !empty($_post_data['select_listing_map']) ? $_post_data['select_listing_map'] : '';
        $atbdp_option['map_api_key'] = !empty($_post_data['map_api_key']) ? $_post_data['map_api_key'] : '';
        $atbdp_option['enable_monetization'] = !empty($_post_data['enable_monetization']) ? $_post_data['enable_monetization'] : '';
        $atbdp_option['enable_featured_listing'] = !empty($_post_data['enable_featured_listing']) ? $_post_data['enable_featured_listing'] : '';
        $atbdp_option['featured_listing_price'] = !empty($_post_data['featured_listing_price']) ? $_post_data['featured_listing_price'] : '';

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
        update_option('atbdp_option', $atbdp_option);

        wp_redirect(esc_url_raw($this->get_next_step_link()));
        exit;
    }

    public function directorist_step_three()
    {
        
    ?>
        <div class="directorist-setup-wizard__content">
            <div class="directorist-setup-wizard__content__header text-center">
                <h1 class="directorist-setup-wizard__content__header__title"><?php esc_html_e('Yes You can use directorist core for free', 'directorist'); ?></h1>
            </div>
            <div class="directorist-setup-wizard__content__items directorist-setup-wizard__content__import">
                <div class="directorist-setup-wizard__content__import__wrapper">
                    <h3 class="directorist-setup-wizard__content__import__title">Install required tools</h3>
                    <div class="directorist-setup-wizard__content__import__single">
                        <input type="checkbox" name="business" id="import-listing" />
                        <label for="import-listing">Import Listing</label>
                    </div>
                    <div class="directorist-setup-wizard__content__import__single">
                        <input type="checkbox" name="business" id="import-directory-settings" />
                        <label for="import-directory-settings">Import Directory Settings</label>
                    </div>
                    <div class="directorist-setup-wizard__content__import__single">
                        <input type="checkbox" name="business" id="install-required-plugins" />
                        <label for="install-required-plugins">Install Required Plugins</label>
                    </div>
                    <div class="directorist-setup-wizard__content__import__single">
                        <input type="checkbox" name="business" id="share-data" />
                        <label for="share-data">Share Non-Sensitive Data</label>
                    </div>
                </div>
                <a href="#" class="directorist-setup-wizard__content__import__btn directorist-setup-wizard__btn directorist-setup-wizard__btn--full">Submit & Build My Directory Website</a>
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
                <h1 class="directorist-setup-wizard__content__title"><?php esc_html_e('Congratulations', 'directorist'); ?></h1>
                <h4 class="directorist-setup-wizard__content__desc"><?php esc_html_e('Your directory website is ready. Thank you for using Directorist', 'directorist'); ?></h4>
                <h2 class="directorist-setup-wizard__content__title--section"><?php esc_html_e('What\'s Next', 'directorist'); ?></h2>
                <div class="directorist-setup-wizard__content__btns">
                    <a href="<?php echo esc_url(admin_url().'edit.php?post_type=at_biz_dir'); ?>" class="directorist-setup-wizard__btn"><?php esc_html_e('Create Your First Listing', 'directorist'); ?></a>  
                    <a href="<?php echo esc_url(admin_url().'edit.php?post_type=at_biz_dir'); ?>" class="directorist-setup-wizard__btn directorist-setup-wizard__btn--return"><?php esc_html_e('Return to the wordpress dashboard', 'directorist'); ?></a>  
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
                    <input type="checkbox" name="business" id="business-directory" />
                    <label for="business-directory">Business Directory</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="classified" id="classified-listing" />
                    <label for="classified-listing">Classified Listing</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="car" id="car-listing" />
                    <label for="car-listing">Car Listing</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="restaurant" id="restaurant-listing" />
                    <label for="restaurant-listing">Restaurant Listing</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="lawyers" id="lawyers-listing" />
                    <label for="lawyers-listing">Lawyers Listing</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="doctors" id="doctors-listing" />
                    <label for="doctors-listing">Doctors Listing</label>
                </div>
                <div class="directorist-setup-wizard__checkbox">
                    <input type="checkbox" name="others" id="others-listing" />
                    <label for="others-listing">Others</label>
                </div>
                <div class="directorist-setup-wizard__checkbox directorist-setup-wizard__checkbox--custom">
                    <input type="text" name="others" id="others-listing" placeholder="Type Your Prefered Directory Name" />
                </div>
            </div>
            <div class="directorist-setup-wizard__content__notice">
                <svg xmlns="http://www.w3.org/2000/svg" width="14.932" height="16" viewBox="570 654 14.932 16"><path d="M580.32 669.25a.75.75 0 0 1-.75.75h-7.07a2.503 2.503 0 0 1-2.5-2.5v-11a2.503 2.503 0 0 1 2.5-2.5h7.07a.75.75 0 0 1 0 1.5h-7.07c-.552 0-1 .448-1 1v11c0 .552.448 1 1 1h7.07a.75.75 0 0 1 .75.75Zm4.393-7.78-3.564-3.564a.75.75 0 1 0-1.061 1.06l2.284 2.284h-5.905a.75.75 0 0 0 0 1.5h5.905l-2.284 2.284a.75.75 0 1 0 1.06 1.06l3.565-3.564a.75.75 0 0 0 0-1.06Z" fill="#484848" fill-rule="evenodd" data-name="Path 1620"/></svg> Not Right Now. Exit to Dashboard
            </div>
        </div>
    <?php
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
    ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>

        <head>
            <meta name="viewport" content="width=device-width" />
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title><?php esc_html_e('Directorist &rsaquo; Setup Wizard', 'directorist'); ?></title>
            <?php wp_print_scripts('directorist-admin-setup-wizard-script'); ?>
            <?php wp_print_scripts('directorist-select2'); ?>
            <?php do_action('admin_print_styles'); ?>
            <?php do_action('admin_head'); ?>
            <?php do_action('directorist_setup_wizard_styles'); ?>
        </head>

        <body class="atbdp-setup directorist-setup-wizard wp-core-ui<?php echo get_transient('directorist_setup_wizard_no_wc') ? esc_attr( ' directorist-setup-wizard-activated-wc' ) : '';  ?> <?php echo esc_attr( $hide ); ?>">
            <form method="post" class="directorist-setup-wizard-wrapper directorist-setup-wizard__wrapper">
                <div class="directorist-setup-wizard__header">
                    <div class="directorist-setup-wizard__logo">
                        <img src="<?php echo esc_url(DIRECTORIST_ASSETS . 'images/directorist-logo.svg');?>" alt="Directorist">
                    </div>
                    <div class="directorist-setup-wizard__header__step">
                        <ul class="atbdp-setup-steps <?php echo esc_attr( $hide ); ?>">
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
                                    ?>">
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <span class="step-count">Choose a directory type 1 of 4</span>
                    </div>
                    <div class="directorist-setup-wizard__close">
                        <a href="#" class="directorist-setup-wizard__close__btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="11.998" viewBox="1237 31 12 11.998"><path d="m1244.409 36.998 4.295-4.286a1.003 1.003 0 0 0-1.418-1.418L1243 35.59l-4.286-4.296a1.003 1.003 0 0 0-1.418 1.418l4.295 4.286-4.295 4.286a.999.999 0 0 0 0 1.419.999.999 0 0 0 1.418 0l4.286-4.296 4.286 4.296a1 1 0 0 0 1.418 0 .999.999 0 0 0 0-1.419l-4.295-4.286Z" fill="#b7b7b7" fill-rule="evenodd" data-name="times"/></svg>
                        </a>
                    </div>
                </div>
            <?php
            /* $logo_url = ( ! empty( $this->custom_logo ) ) ? $this->custom_logo : plugins_url( 'assets/images/directorist-logo.svg', directorist_FILE );*/
            ?>
            <!--<h1 id="atbdp-logo"><a href="https://wedevs.com/directorist/"><img src="<?php /*echo esc_url( $logo_url ); */ ?>" alt="directorist Logo" width="135" height="auto" /></a></h1>-->
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

                <div class="directorist-setup-wizard__footer">
                    <div class="directorist-setup-wizard__back">
                        <a href="#" class="directorist-setup-wizard__back__btn">Back</a>
                    </div>
                    <div class="directorist-setup-wizard__next">
                        <a href="/wp-admin/index.php?page=directorist-setup&amp;step=step-three" class="w-skip-link">Skip this step</a>
                        <a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="directorist-setup-wizard__btn">Continue</a>
                    </div>
                </div>
            </form>
        </body>

        </html>
<?php
    }
}
new SetupWizard();