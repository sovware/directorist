<?php

/**
 * Setup wizard class
 *
 * Walkthrough to the basic setup upon installation
 */
class SetupWizard {
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

            add_action('admin_menu', array( $this, 'admin_menus') );
            add_action('admin_init', array( $this, 'setup_wizard' ), 99);

    }

    /**
     * Add admin menus/screens.
     */
    public function admin_menus() {
        add_submenu_page( null, '', '', 'manage_options', 'directorist-setup', '' );
    }

    /**
     * Show the setup wizard.
     */
    public function setup_wizard() {

        if ( empty( $_GET['page'] ) || 'directorist-setup' !== $_GET['page'] ) {
            return;
        }

        $this->set_steps();



        $this->step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );

        //$this->enqueue_scripts();

        if ( ! empty( $_POST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) { // WPCS: CSRF ok.
            call_user_func_array( $this->steps[ $this->step ]['handler'], array( $this ) );
        }

        ob_start();
        $this->set_setup_wizard_template();
        exit;
    }

    /**
     * Set wizard steps
     *
     * @since 2.9.27
     *
     * @return void
     */
    protected function set_steps() {
        $this->steps = apply_filters( 'dokan_admin_setup_wizard_steps', array(
            'introduction' => array(
                'name'    =>  __( 'Introduction', 'directorist' ),
                'view'    => array( $this, 'dokan_setup_introduction' ),
            ),
            'step-one' => array(
                'name'    =>  __( 'Step One', 'directorist' ),
                'view'    => array( $this, 'dokan_setup_store' ),
                'handler' => array( $this, 'dokan_setup_store_save' ),
            ),
            'step-two' => array(
                'name'    =>  __( 'Step Two', 'directorist' ),
                'view'    => array( $this, 'dokan_setup_selling' ),
                'handler' => array( $this, 'dokan_setup_selling_save' ),
            ),
            'step-three' => array(
                'name'    =>  __( 'Step Three', 'directorist' ),
                'view'    => array( $this, 'dokan_setup_withdraw' ),
                'handler' => array( $this, 'dokan_setup_withdraw_save' ),
            ),
        ) );
    }
    public function dokan_setup_store() {
        $general_options        = get_option( 'dokan_general', array() );
        $custom_store_url       = ! empty( $general_options['custom_store_url'] ) ? $general_options['custom_store_url'] : 'store';

        $selling_options        = get_option( 'dokan_selling', array() );
        $shipping_fee_recipient = ! empty( $selling_options['shipping_fee_recipient'] ) ? $selling_options['shipping_fee_recipient'] : 'seller';
        $tax_fee_recipient      = ! empty( $selling_options['tax_fee_recipient'] ) ? $selling_options['tax_fee_recipient'] : 'seller';
       

        $recipients = array(
            'seller' => __( 'Vendor', 'directorist' ),
            'admin'  => __( 'Admin', 'directorist' ),
        );

            ?>
    <h1><?php esc_html_e( 'Store Setup', 'directorist' ); ?></h1>
    <form method="post">
        <div>
            <select name="select_listing_map" id="">
                <option value="openstreet">Openstreetmap</option>
                <option value="google">google</option>
            </select>
        </div>
        <div>
            <label for="google_api"> Google Map API key
                <input type="text" name="map_api_key">
            </label>
        </div>
        <div>
            <label for="enable_monetization"> Enable Monetization Feature
                <input type="checkbox" name='enable_monetization' id='enable_monetization' value= 1 >
            </label>
        </div>
        <div>
            <label for="enable_featured_listing"> Monetize by Featured Listing
                <input type="checkbox" name='enable_featured_listing' id='enable_featured_listing' value= 1 >
            </label>
        </div>
        <div>
            <label for="featured_listing_price"> Price in USD
                <input type="text" name='featured_listing_price' id='featured_listing_price' value= 19.99 >
            </label>
        </div>
        <p class="wc-setup-actions step">
            <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', 'directorist' ); ?>" name="save_step" />
            <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next"><?php esc_html_e( 'Skip this step', 'directorist' ); ?></a>
            <?php wp_nonce_field( 'dokan-setup' ); ?>
        </p>
    </form>
    <?php
       // dokan_get_template( 'admin-setup-wizard/step-store.php', $args );
    }

    /**
     * Save store options.
     */
    public function dokan_setup_store_save() {
        check_admin_referer( 'dokan-setup' );

        $_post_data = wp_unslash($_POST);

        $atbdp_option = get_option('atbdp_option');
        $pages = !empty( $_post_data['share_essentials'] ) ? $_post_data['share_essentials'] : '';
        $atbdp_option['select_listing_map'] = !empty( $_post_data['select_listing_map'] ) ? $_post_data['select_listing_map'] : '';
        $atbdp_option['map_api_key'] = !empty( $_post_data['map_api_key'] ) ? $_post_data['map_api_key'] : '';
        $atbdp_option['enable_monetization'] = !empty( $_post_data['enable_monetization'] ) ? $_post_data['enable_monetization'] : '';
        $atbdp_option['enable_featured_listing'] = !empty( $_post_data['enable_featured_listing'] ) ? $_post_data['enable_featured_listing'] : '';
        $atbdp_option['featured_listing_price'] = !empty( $_post_data['featured_listing_price'] ) ? $_post_data['featured_listing_price'] : '';
        
        do_action( 'dokan_admin_setup_wizard_save_step_store' );

        
        $create_pages = [
            'checkout_page'        => [
                'post_title'         => 'Checkout',
                'post_content'       => '[directorist_transaction_failure]',
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
        
        if ( ! empty( $atbdp_option['enable_monetization'] ) ) {
            foreach ( $create_pages as $key => $name ) {

                $args = [
                    'post_title' => $name['post_title'],
                    'post_content' => $name['post_content'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'comment_status' => 'closed'
                ];
                if ( empty ( $atbdp_option[ $key ] ) ) {
                    $id = wp_insert_post( $args );

                    if( $id ) {
                        $atbdp_option[ $key ] = $id; 
                    }
                }

            }
        }
        update_option( 'atbdp_option', $atbdp_option );

        wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
        exit;
    }

    public function dokan_setup_selling() { ?>
    <form method="post">
        <h4>Dummy data</h4>
        <p class="wc-setup-actions step">
            <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', 'directorist' ); ?>" name="save_step" />
            <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next"><?php esc_html_e( 'Skip this step', 'directorist' ); ?></a>
            <?php wp_nonce_field( 'dokan-setup' ); ?>
        </p>
    </form>
    <?php
        
    }

    public function dokan_setup_selling_save() {
        check_admin_referer( 'dokan-setup' );

        $_post_data = wp_unslash($_POST);

        $pages = !empty( $_post_data['map'] ) ? $_post_data['map'] : '';
        wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
        exit;
    }

    public function dokan_setup_withdraw() { ?>
        
        <div>
            <h2>Awesome, your directory is ready!</h2>
        </div>
        <div>
            <h2>Next steps</h2>
            <a href="">Create your first listing!</a>
        </div>
        <div>
            <a href="">Getting started guide</a>
            <a href="">Have questions? Get help.</a>
        </div>
        <?php
    }

    public function get_steps() {
        return $this->steps;
    }

    /**
     * Introduction step.
     */
    public function dokan_setup_introduction() {
        ?>
        <h1><?php esc_html_e( 'Welcome to the world of Directorist!', 'directorist' ); ?></h1>
        <p><?php echo wp_kses( __( 'Thank you for choosing Directorist to power your online marketplace! This quick setup wizard will help you configure the basic settings. <strong>It’s completely optional and shouldn’t take longer than three minutes.</strong>', 'directorist' ), [ 'strong' => [] ] ); ?></p>
        <p><?php esc_html_e( 'No time right now? If you don’t want to go through the wizard, you can skip and return to the WordPress dashboard. Come back anytime if you change your mind!', 'directorist' ); ?></p>
        <p class="wc-setup-actions step">
            <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-primary button button-large button-next"><?php esc_html_e( 'Let\'s Go!', 'directorist' ); ?></a>
            <a href="<?php echo esc_url( admin_url() ); ?>" class="button button-large"><?php esc_html_e( 'Not right now', 'directorist' ); ?></a>
        </p>
        <?php
    }

    public function get_next_step_link() {
        $keys = array_keys( $this->steps );
    
        return add_query_arg( 'step', $keys[ array_search( $this->step, array_keys( $this->steps ) ) + 1 ] );
    }

    /**
     * Wizard templates
     *
     * @since 2.9.27
     *
     * @return void
     */
    protected function set_setup_wizard_template() {
        $this->setup_wizard_header();
        $this->setup_wizard_steps();
        $this->setup_wizard_content();
        $this->setup_wizard_footer();
    }

    /**
     * Setup Wizard Header.
     */
    public function setup_wizard_header() {
        set_current_screen();
        ?>
        <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
        <head>
            <meta name="viewport" content="width=device-width" />
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title><?php esc_html_e( 'Directorist &rsaquo; Setup Wizard', 'directorist' ); ?></title>
            <?php wp_print_scripts( 'wc-setup' ); ?>
            <?php do_action( 'admin_print_styles' ); ?>
            <?php do_action( 'admin_head' ); ?>
            <?php do_action( 'directorist_setup_wizard_styles' ); ?>
        </head>
    <body class="wc-setup wp-core-ui<?php echo get_transient( 'dokan_setup_wizard_no_wc' ) ? ' dokan-setup-wizard-activated-wc' : '';  ?>">
        <?php
       /* $logo_url = ( ! empty( $this->custom_logo ) ) ? $this->custom_logo : plugins_url( 'assets/images/dokan-logo.png', DOKAN_FILE );*/
        ?>
        <!--<h1 id="wc-logo"><a href="https://wedevs.com/dokan/"><img src="<?php /*echo esc_url( $logo_url ); */?>" alt="Dokan Logo" width="135" height="auto" /></a></h1>-->
        <?php
    }

    /**
     * Output the steps.
     */
    public function setup_wizard_steps() {
        $ouput_steps = $this->steps;
        array_shift( $ouput_steps );
        ?>
        <ol class="wc-setup-steps">
            <?php foreach ( $ouput_steps as $step_key => $step ) : ?>
                <li class="<?php
                if ( $step_key === $this->step ) {
                    echo 'active';
                } elseif ( array_search( $this->step, array_keys( $this->steps ) ) > array_search( $step_key, array_keys( $this->steps ) ) ) {
                    echo 'done';
                }
                ?>"><?php echo esc_html( $step['name'] ); ?></li>
            <?php endforeach; ?>
        </ol>
        <?php
    }

    /**
     * Output the content for the current step.
     */
    public function setup_wizard_content() {
        if ( empty( $this->steps[ $this->step ]['view'] ) ) {
            wp_redirect( esc_url_raw( add_query_arg( 'step', 'introduction' ) ) );
            exit;
        }

        echo '<div class="wc-setup-content">';
        call_user_func( $this->steps[ $this->step ]['view'] );
        echo '</div>';
    }

    /**
     * Setup Wizard Footer.
     */
    public function setup_wizard_footer() {
        ?>
        <?php if ( 'next_steps' === $this->step ) : ?>
            <a class="wc-return-to-dashboard" href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e( 'Return to the WordPress Dashboard', 'directorist' ); ?></a>
        <?php endif; ?>
        </body>
        </html>
        <?php
    }
}
new SetupWizard();
