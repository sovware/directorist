<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Directorist\Multi_Directory\Multi_Directory_Manager;

/**
 * AI-first setup wizard.
 *
 * This is intentionally isolated from the legacy setup wizard and the
 * directory builder's existing Create with AI flow.
 */
class Directorist_AI_Setup_Wizard {
    const PAGE_SLUG = 'directorist-ai-setup';
    const WAXAI_API_BASE = 'https://app.directorist.com/wp-json/waxai/v1/';

    private $preset_fields = [
        'title'         => 'title',
        'description'   => 'description',
        'tagline'       => 'tagline',
        'pricing'       => 'pricing',
        'excerpt'       => 'excerpt',
        'location'      => 'location',
        'locations'     => 'location',
        'tag'           => 'tag',
        'tags'          => 'tag',
        'category'      => 'category',
        'categories'    => 'category',
        'map'           => 'map',
        'address'       => 'address',
        'postcode'      => 'zip',
        'postal_code'   => 'zip',
        'zip'           => 'zip',
        'phone'         => 'phone',
        'phone2'        => 'phone2',
        'fax'           => 'fax',
        'email'         => 'email',
        'website'       => 'website',
        'socialinfo'    => 'social_info',
        'social_info'   => 'social_info',
        'images'        => 'image_upload',
        'image'         => 'image_upload',
        'image_upload'  => 'image_upload',
        'video'         => 'video',
        'terms_privacy' => 'terms_privacy',
    ];

    private $custom_fields = [
        'text',
        'textarea',
        'html',
        'number',
        'url',
        'date',
        'time',
        'color_picker',
        'select',
        'checkbox',
        'radio',
        'file',
        'button',
    ];

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menus' ] );
        add_action( 'admin_init', [ $this, 'render_page' ], 99 );
        add_action( 'wp_ajax_directorist_ai_setup_wizard_generate', [ $this, 'handle_generate' ] );
        add_action( 'wp_ajax_directorist_ai_setup_wizard_regenerate_fields', [ $this, 'handle_regenerate_fields' ] );
        add_action( 'wp_ajax_directorist_ai_setup_wizard_reverse_geocode', [ $this, 'handle_reverse_geocode' ] );
        add_action( 'wp_ajax_directorist_ai_setup_wizard_launch', [ $this, 'handle_launch' ] );
    }

    public function admin_menus() {
        add_menu_page(
            __( 'Directorist AI Setup', 'directorist' ),
            __( 'AI Setup', 'directorist' ),
            'manage_options',
            self::PAGE_SLUG
        );

        remove_menu_page( self::PAGE_SLUG );
    }

    public function render_page() {
        if ( empty( $_GET['page'] ) || self::PAGE_SLUG !== $_GET['page'] ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $this->enqueue_assets();

        $this->setup_page_template();
        exit;
    }

    private function enqueue_assets() {
        wp_register_style(
            'directorist-ai-setup-wizard',
            DIRECTORIST_BUILD_ASSETS . 'css/admin/ai-setup-wizard.css',
            [],
            ATBDP_VERSION
        );

        wp_register_script(
            'directorist-ai-setup-wizard',
            DIRECTORIST_BUILD_ASSETS . 'js/admin/ai-setup-wizard.js',
            [ 'jquery' ],
            ATBDP_VERSION,
            true
        );

        wp_enqueue_style( 'directorist-ai-setup-wizard' );
        wp_enqueue_script( 'directorist-ai-setup-wizard' );

        wp_localize_script(
            'directorist-ai-setup-wizard',
            'directoristAiSetup',
            [
                'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
                'nonce'     => wp_create_nonce( directorist_get_nonce_key() ),
                'dashboard' => admin_url( 'edit.php?post_type=' . ATBDP_POST_TYPE . '&page=atbdp-extension' ),
                'storage'   => [
                    'key' => sprintf(
                        'directorist-ai-setup-wizard-%d-%d-v1',
                        get_current_blog_id(),
                        get_current_user_id()
                    ),
                    'ttl' => DAY_IN_SECONDS,
                ],
                'actions'   => [
                    'generate'   => 'directorist_ai_setup_wizard_generate',
                    'regenerate' => 'directorist_ai_setup_wizard_regenerate_fields',
                    'geocode'    => 'directorist_ai_setup_wizard_reverse_geocode',
                    'launch'     => 'directorist_ai_setup_wizard_launch',
                ],
                'i18n'      => [
                    'generateError'      => __( 'Could not generate setup data. Please try again.', 'directorist' ),
                    'fallbackNotice'     => __( 'AI is taking longer than expected, so we prepared a starter setup. You can edit it before launch.', 'directorist' ),
                    'launchError'        => __( 'Could not launch your directory. Please try again.', 'directorist' ),
                    'categoryName'       => __( 'Category name', 'directorist' ),
                    'exitConfirm'        => __( 'Exit setup and go to the dashboard? Your progress will stay available in this browser tab.', 'directorist' ),
                    'requestInterrupted' => __( 'The request was interrupted by the reload. Your saved progress has been restored.', 'directorist' ),
                    'launchInterrupted'  => __( 'The launch was interrupted by the reload. Check your listings before trying again.', 'directorist' ),
                    'fieldCount'         => __( '%d fields selected', 'directorist' ),
                    'noFields'           => __( 'No fields selected yet.', 'directorist' ),
                    'addCategory'        => __( '+ Add category', 'directorist' ),
                    'categoryRequired'   => __( 'Enter a category name.', 'directorist' ),
                    'categoryExists'     => __( 'This category has already been added.', 'directorist' ),
                    'regenerate'         => __( 'Regenerate', 'directorist' ),
                    'regenerating'       => __( 'Regenerating...', 'directorist' ),
                    'regenerateNote'     => __( 'You can regenerate fields %d more times.', 'directorist' ),
                    'regenerateDone'     => __( 'Regeneration limit reached.', 'directorist' ),
                    'launch'             => __( 'Launch my directory', 'directorist' ),
                    'launching'          => __( 'Launching...', 'directorist' ),
                    'detectLocation'     => __( 'Use my current location', 'directorist' ),
                    'detectingLocation'  => __( 'Detecting your current location...', 'directorist' ),
                    'locationDetected'   => __( 'Current address detected.', 'directorist' ),
                    'locationFallback'   => __( 'Location detected, but the exact address could not be found. Coordinates were added instead.', 'directorist' ),
                    'locationInsecure'   => __( 'Location detection needs HTTPS or localhost. Open this admin page over HTTPS and try again.', 'directorist' ),
                    'locationBlocked'    => __( 'Location access is blocked. Allow it from your browser address bar, then try again.', 'directorist' ),
                    'locationUnavailable' => __( 'Your current location is unavailable. Please try again.', 'directorist' ),
                    'locationTimeout'     => __( 'Location detection timed out. Please try again.', 'directorist' ),
                    'locationUnsupported' => __( 'Your browser does not support location detection.', 'directorist' ),
                ],
            ]
        );
    }

    private function setup_page_template() {
        set_current_screen();
        ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>
        <head>
            <meta name="viewport" content="width=device-width" />
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title><?php esc_html_e( 'Directorist AI Setup', 'directorist' ); ?></title>
            <?php
            wp_print_styles( 'directorist-ai-setup-wizard' );
            wp_print_scripts( 'directorist-ai-setup-wizard' );
            ?>
        </head>
        <body class="directorist-ai-setup wp-core-ui">
            <?php atbdp_load_admin_template( 'setup-wizard/ai-setup-wizard' ); ?>
        </body>
        </html>
        <?php
    }

    public function handle_generate() {
        $this->verify_ajax_request();

        $prompt = isset( $_POST['prompt'] ) ? sanitize_textarea_field( wp_unslash( $_POST['prompt'] ) ) : '';

        if ( strlen( trim( $prompt ) ) < 5 ) {
            wp_send_json_error( [ 'message' => __( 'Please describe your directory first.', 'directorist' ) ], 400 );
        }

        $response = $this->request_waxai( 'setup-wizard/generate', [ 'prompt' => $prompt ] );

        if ( is_wp_error( $response ) ) {
            wp_send_json_success(
                [
                    'setup'    => $this->get_fallback_setup( $prompt ),
                    'fallback' => true,
                    'message'  => __( 'AI is taking longer than expected, so we prepared a starter setup. You can edit it before launch.', 'directorist' ),
                ]
            );
        }

        wp_send_json_success( [ 'setup' => $this->normalize_setup_payload( $response ) ] );
    }

    public function handle_regenerate_fields() {
        $this->verify_ajax_request();

        $prompt = isset( $_POST['prompt'] ) ? sanitize_textarea_field( wp_unslash( $_POST['prompt'] ) ) : '';
        $fields = isset( $_POST['fields'] ) ? json_decode( wp_unslash( $_POST['fields'] ), true ) : [];
        $selected = isset( $_POST['selected'] ) ? json_decode( wp_unslash( $_POST['selected'] ), true ) : [];

        $response = $this->request_waxai(
            'setup-wizard/regenerate-fields',
            [
                'prompt'   => $prompt,
                'fields'   => $fields,
                'selected' => $selected,
            ]
        );

        if ( is_wp_error( $response ) ) {
            wp_send_json_error( [ 'message' => $response->get_error_message() ], 400 );
        }

        $payload = $this->normalize_setup_payload( [ 'fields' => $response['fields'] ?? $response ] );
        $payload['fields'] = $this->remove_unselected_locked_regenerated_fields( $payload['fields'], $fields, $selected );
        $payload['fields'] = $this->preserve_regenerated_selected_field_context( $payload['fields'], $fields, $selected );
        wp_send_json_success( [ 'fields' => $payload['fields'] ] );
    }

    public function handle_reverse_geocode() {
        $this->verify_ajax_request();

        $coordinates = $this->normalize_coordinates(
            [
                'lat' => isset( $_POST['latitude'] ) ? sanitize_text_field( wp_unslash( $_POST['latitude'] ) ) : null,
                'lng' => isset( $_POST['longitude'] ) ? sanitize_text_field( wp_unslash( $_POST['longitude'] ) ) : null,
            ]
        );

        if ( ! $coordinates ) {
            wp_send_json_error( [ 'message' => __( 'Invalid location coordinates.', 'directorist' ) ], 400 );
        }

        $response = wp_remote_get(
            add_query_arg(
                [
                    'format'         => 'jsonv2',
                    'lat'            => $coordinates['lat'],
                    'lon'            => $coordinates['lng'],
                    'zoom'           => 18,
                    'addressdetails' => 0,
                    'accept-language' => str_replace( '_', '-', get_user_locale() ),
                ],
                'https://nominatim.openstreetmap.org/reverse'
            ),
            [
                'timeout' => 8,
                'headers' => [
                    'Accept'     => 'application/json',
                    'User-Agent' => 'Directorist AI Setup Wizard/' . ATBDP_VERSION . '; ' . home_url( '/' ),
                ],
            ]
        );

        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            wp_send_json_error( [ 'message' => __( 'The address could not be resolved.', 'directorist' ) ], 502 );
        }

        $result  = json_decode( wp_remote_retrieve_body( $response ), true );
        $address = isset( $result['display_name'] ) ? sanitize_text_field( $result['display_name'] ) : '';

        if ( '' === $address ) {
            wp_send_json_error( [ 'message' => __( 'No address was found for this location.', 'directorist' ) ], 404 );
        }

        wp_send_json_success( [ 'address' => $address ] );
    }

    public function handle_launch() {
        $this->verify_ajax_request();

        $raw_setup = isset( $_POST['setup'] ) ? wp_unslash( $_POST['setup'] ) : '';
        $setup     = json_decode( $raw_setup, true );

        if ( empty( $setup ) || ! is_array( $setup ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid setup data.', 'directorist' ) ], 400 );
        }

        $setup = $this->normalize_setup_payload( $setup );
        $directory = $this->build_directory( $setup );

        if ( is_wp_error( $directory ) ) {
            wp_send_json_error( [ 'message' => $directory->get_error_message() ], 400 );
        }

        $this->set_default_directory( $directory['id'] );

        $this->create_terms( $setup['categories'], ATBDP_CATEGORY, $directory['id'] );

        $import_result = [
            'imported'       => 0,
            'failed'         => 0,
            'image_imported' => 0,
        ];

        if ( ! empty( $setup['demo_content'] ) ) {
            $import_result = $this->import_demo_listings( $setup, $directory );
        }

        $this->save_setup_options( $setup );

        update_option( 'directorist_setup_wizard_completed', true );
        do_action( 'directorist_setup_wizard_completed' );

        wp_send_json_success(
            [
                'url'    => admin_url( 'edit.php?post_type=' . ATBDP_POST_TYPE . '&page=atbdp-extension' ),
                'id'     => $directory['id'],
                'import' => $import_result,
            ]
        );
    }

    private function verify_ajax_request() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( [ 'message' => __( 'You are not allowed to access this resource.', 'directorist' ) ], 403 );
        }

        if ( ! directorist_verify_nonce() ) {
            wp_send_json_error( [ 'message' => __( 'Something is wrong! Please refresh and retry.', 'directorist' ) ], 403 );
        }
    }

    private function request_waxai( $endpoint, array $params ) {
        $api_base = apply_filters( 'directorist_ai_setup_wizard_api_base', self::WAXAI_API_BASE );
        $url      = trailingslashit( $api_base ) . ltrim( $endpoint, '/' );

        $response = wp_remote_post(
            $url,
            [
                'timeout' => 30,
                'headers' => [
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'body'    => wp_json_encode( $params ),
            ]
        );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $code = wp_remote_retrieve_response_code( $response );
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( $code < 200 || $code >= 300 ) {
            return new WP_Error( 'waxai_error', ! empty( $data['message'] ) ? $data['message'] : __( 'AI setup service failed.', 'directorist' ) );
        }

        if ( empty( $data ) || ! is_array( $data ) ) {
            return new WP_Error( 'invalid_waxai_response', __( 'AI setup service returned invalid data.', 'directorist' ) );
        }

        if ( isset( $data['response'] ) && is_array( $data['response'] ) ) {
            return $data['response'];
        }

        return $data;
    }

    private function get_fallback_setup( $prompt ) {
        $templates = $this->get_fallback_templates();
        $key       = $this->match_fallback_template_key( $prompt, $templates );
        $setup     = $templates[ $key ]['setup'];

        /**
         * Filters the setup payload used when AI setup generation is unavailable.
         *
         * @param array  $setup  Fallback setup payload.
         * @param string $prompt User prompt.
         * @param string $key    Matched fallback template key.
         */
        $setup = apply_filters( 'directorist_ai_setup_wizard_fallback_setup', $setup, $prompt, $key );

        return $this->normalize_setup_payload( is_array( $setup ) ? $setup : $templates['local']['setup'] );
    }

    private function match_fallback_template_key( $prompt, array $templates ) {
        $prompt = strtolower( sanitize_text_field( $prompt ) );

        foreach ( $templates as $key => $template ) {
            if ( empty( $template['keywords'] ) || ! is_array( $template['keywords'] ) ) {
                continue;
            }

            foreach ( $template['keywords'] as $keyword ) {
                if ( '' !== $keyword && false !== strpos( $prompt, strtolower( $keyword ) ) ) {
                    return $key;
                }
            }
        }

        return 'local';
    }

    private function get_fallback_templates() {
        return [
            'restaurant' => [
                'keywords' => [ 'restaurant', 'food', 'dining', 'cafe', 'coffee', 'menu' ],
                'setup'    => [
                    'directory_name'  => __( 'Restaurant Directory', 'directorist' ),
                    'categories'      => [ __( 'Restaurants', 'directorist' ), __( 'Cafes', 'directorist' ), __( 'Fast Food', 'directorist' ), __( 'Fine Dining', 'directorist' ) ],
                    'default_address' => '',
                    'fields'          => [
                        [ 'label' => __( 'Title', 'directorist' ), 'type' => 'title' ],
                        [ 'label' => __( 'Description', 'directorist' ), 'type' => 'description' ],
                        [ 'label' => __( 'Category', 'directorist' ), 'type' => 'category' ],
                        [ 'label' => __( 'Address', 'directorist' ), 'type' => 'address' ],
                        [ 'label' => __( 'Map', 'directorist' ), 'type' => 'map' ],
                        [ 'label' => __( 'Phone', 'directorist' ), 'type' => 'phone' ],
                        [ 'label' => __( 'Email', 'directorist' ), 'type' => 'email' ],
                        [ 'label' => __( 'Website', 'directorist' ), 'type' => 'website' ],
                        [ 'label' => __( 'Price Range', 'directorist' ), 'type' => 'pricing' ],
                        [ 'label' => __( 'Cuisine Type', 'directorist' ), 'type' => 'select', 'options' => [ __( 'Local', 'directorist' ), __( 'Italian', 'directorist' ), __( 'Chinese', 'directorist' ), __( 'Fast Food', 'directorist' ) ] ],
                        [ 'label' => __( 'Photos', 'directorist' ), 'type' => 'image_upload' ],
                    ],
                    'monetization'    => true,
                    'demo_content'    => true,
                ],
            ],
            'job'        => [
                'keywords' => [ 'job', 'career', 'hiring', 'recruit', 'vacancy', 'employment' ],
                'setup'    => [
                    'directory_name'  => __( 'Job Board', 'directorist' ),
                    'categories'      => [ __( 'Full Time', 'directorist' ), __( 'Part Time', 'directorist' ), __( 'Remote', 'directorist' ), __( 'Contract', 'directorist' ) ],
                    'default_address' => '',
                    'fields'          => [
                        [ 'label' => __( 'Job Title', 'directorist' ), 'type' => 'title' ],
                        [ 'label' => __( 'Job Description', 'directorist' ), 'type' => 'description' ],
                        [ 'label' => __( 'Category', 'directorist' ), 'type' => 'category' ],
                        [ 'label' => __( 'Company', 'directorist' ), 'type' => 'text' ],
                        [ 'label' => __( 'Location', 'directorist' ), 'type' => 'location' ],
                        [ 'label' => __( 'Salary Range', 'directorist' ), 'type' => 'pricing' ],
                        [ 'label' => __( 'Job Type', 'directorist' ), 'type' => 'select', 'options' => [ __( 'Full Time', 'directorist' ), __( 'Part Time', 'directorist' ), __( 'Remote', 'directorist' ), __( 'Contract', 'directorist' ) ] ],
                        [ 'label' => __( 'Application Link', 'directorist' ), 'type' => 'url' ],
                        [ 'label' => __( 'Application Deadline', 'directorist' ), 'type' => 'date' ],
                    ],
                    'monetization'    => true,
                    'demo_content'    => true,
                ],
            ],
            'realestate' => [
                'keywords' => [ 'real estate', 'property', 'apartment', 'home', 'house', 'rent', 'sale' ],
                'setup'    => [
                    'directory_name'  => __( 'Real Estate Directory', 'directorist' ),
                    'categories'      => [ __( 'Apartments', 'directorist' ), __( 'Houses', 'directorist' ), __( 'Commercial', 'directorist' ), __( 'Land', 'directorist' ) ],
                    'default_address' => '',
                    'fields'          => [
                        [ 'label' => __( 'Property Title', 'directorist' ), 'type' => 'title' ],
                        [ 'label' => __( 'Description', 'directorist' ), 'type' => 'description' ],
                        [ 'label' => __( 'Category', 'directorist' ), 'type' => 'category' ],
                        [ 'label' => __( 'Address', 'directorist' ), 'type' => 'address' ],
                        [ 'label' => __( 'Map', 'directorist' ), 'type' => 'map' ],
                        [ 'label' => __( 'Price', 'directorist' ), 'type' => 'pricing' ],
                        [ 'label' => __( 'Bedrooms', 'directorist' ), 'type' => 'number' ],
                        [ 'label' => __( 'Bathrooms', 'directorist' ), 'type' => 'number' ],
                        [ 'label' => __( 'Area', 'directorist' ), 'type' => 'number' ],
                        [ 'label' => __( 'Phone', 'directorist' ), 'type' => 'phone' ],
                        [ 'label' => __( 'Photos', 'directorist' ), 'type' => 'image_upload' ],
                    ],
                    'monetization'    => true,
                    'demo_content'    => true,
                ],
            ],
            'classified' => [
                'keywords' => [ 'classified', 'marketplace', 'buy', 'sell', 'product', 'item' ],
                'setup'    => [
                    'directory_name'  => __( 'Classifieds Marketplace', 'directorist' ),
                    'categories'      => [ __( 'For Sale', 'directorist' ), __( 'Electronics', 'directorist' ), __( 'Vehicles', 'directorist' ), __( 'Home Goods', 'directorist' ) ],
                    'default_address' => '',
                    'fields'          => [
                        [ 'label' => __( 'Listing Title', 'directorist' ), 'type' => 'title' ],
                        [ 'label' => __( 'Description', 'directorist' ), 'type' => 'description' ],
                        [ 'label' => __( 'Category', 'directorist' ), 'type' => 'category' ],
                        [ 'label' => __( 'Price', 'directorist' ), 'type' => 'pricing' ],
                        [ 'label' => __( 'Condition', 'directorist' ), 'type' => 'select', 'options' => [ __( 'New', 'directorist' ), __( 'Used', 'directorist' ), __( 'Refurbished', 'directorist' ) ] ],
                        [ 'label' => __( 'Location', 'directorist' ), 'type' => 'location' ],
                        [ 'label' => __( 'Phone', 'directorist' ), 'type' => 'phone' ],
                        [ 'label' => __( 'Photos', 'directorist' ), 'type' => 'image_upload' ],
                    ],
                    'monetization'    => true,
                    'demo_content'    => true,
                ],
            ],
            'automotive' => [
                'keywords' => [ 'car', 'vehicle', 'automotive', 'auto', 'motor', 'mileage' ],
                'setup'    => [
                    'directory_name'  => __( 'Car Listings Directory', 'directorist' ),
                    'categories'      => [ __( 'Sedan', 'directorist' ), __( 'SUV', 'directorist' ), __( 'Truck', 'directorist' ), __( 'Electric', 'directorist' ) ],
                    'default_address' => '',
                    'fields'          => [
                        [ 'label' => __( 'Vehicle Title', 'directorist' ), 'type' => 'title' ],
                        [ 'label' => __( 'Description', 'directorist' ), 'type' => 'description' ],
                        [ 'label' => __( 'Category', 'directorist' ), 'type' => 'category' ],
                        [ 'label' => __( 'Make', 'directorist' ), 'type' => 'text' ],
                        [ 'label' => __( 'Model', 'directorist' ), 'type' => 'text' ],
                        [ 'label' => __( 'Year', 'directorist' ), 'type' => 'number' ],
                        [ 'label' => __( 'Mileage', 'directorist' ), 'type' => 'number' ],
                        [ 'label' => __( 'Price', 'directorist' ), 'type' => 'pricing' ],
                        [ 'label' => __( 'Location', 'directorist' ), 'type' => 'location' ],
                        [ 'label' => __( 'Phone', 'directorist' ), 'type' => 'phone' ],
                        [ 'label' => __( 'Photos', 'directorist' ), 'type' => 'image_upload' ],
                    ],
                    'monetization'    => true,
                    'demo_content'    => true,
                ],
            ],
            'hotel'      => [
                'keywords' => [ 'hotel', 'room', 'booking', 'resort', 'stay', 'accommodation' ],
                'setup'    => [
                    'directory_name'  => __( 'Hotel Directory', 'directorist' ),
                    'categories'      => [ __( 'Hotels', 'directorist' ), __( 'Resorts', 'directorist' ), __( 'Guest Houses', 'directorist' ), __( 'Apartments', 'directorist' ) ],
                    'default_address' => '',
                    'fields'          => [
                        [ 'label' => __( 'Hotel Name', 'directorist' ), 'type' => 'title' ],
                        [ 'label' => __( 'Description', 'directorist' ), 'type' => 'description' ],
                        [ 'label' => __( 'Category', 'directorist' ), 'type' => 'category' ],
                        [ 'label' => __( 'Address', 'directorist' ), 'type' => 'address' ],
                        [ 'label' => __( 'Map', 'directorist' ), 'type' => 'map' ],
                        [ 'label' => __( 'Room Type', 'directorist' ), 'type' => 'select', 'options' => [ __( 'Single', 'directorist' ), __( 'Double', 'directorist' ), __( 'Suite', 'directorist' ) ] ],
                        [ 'label' => __( 'Price Range', 'directorist' ), 'type' => 'pricing' ],
                        [ 'label' => __( 'Amenities', 'directorist' ), 'type' => 'checkbox', 'options' => [ __( 'WiFi', 'directorist' ), __( 'Parking', 'directorist' ), __( 'Pool', 'directorist' ) ] ],
                        [ 'label' => __( 'Phone', 'directorist' ), 'type' => 'phone' ],
                        [ 'label' => __( 'Booking Link', 'directorist' ), 'type' => 'url' ],
                        [ 'label' => __( 'Photos', 'directorist' ), 'type' => 'image_upload' ],
                    ],
                    'monetization'    => true,
                    'demo_content'    => true,
                ],
            ],
            'medical'    => [
                'keywords' => [ 'doctor', 'medical', 'clinic', 'health', 'dentist', 'physician' ],
                'setup'    => [
                    'directory_name'  => __( 'Medical Directory', 'directorist' ),
                    'categories'      => [ __( 'Doctors', 'directorist' ), __( 'Clinics', 'directorist' ), __( 'Dentists', 'directorist' ), __( 'Specialists', 'directorist' ) ],
                    'default_address' => '',
                    'fields'          => [
                        [ 'label' => __( 'Name', 'directorist' ), 'type' => 'title' ],
                        [ 'label' => __( 'Profile', 'directorist' ), 'type' => 'description' ],
                        [ 'label' => __( 'Category', 'directorist' ), 'type' => 'category' ],
                        [ 'label' => __( 'Specialty', 'directorist' ), 'type' => 'select', 'options' => [ __( 'General', 'directorist' ), __( 'Dental', 'directorist' ), __( 'Cardiology', 'directorist' ), __( 'Pediatrics', 'directorist' ) ] ],
                        [ 'label' => __( 'Address', 'directorist' ), 'type' => 'address' ],
                        [ 'label' => __( 'Map', 'directorist' ), 'type' => 'map' ],
                        [ 'label' => __( 'Phone', 'directorist' ), 'type' => 'phone' ],
                        [ 'label' => __( 'Email', 'directorist' ), 'type' => 'email' ],
                        [ 'label' => __( 'Appointment Link', 'directorist' ), 'type' => 'url' ],
                        [ 'label' => __( 'Photos', 'directorist' ), 'type' => 'image_upload' ],
                    ],
                    'monetization'    => false,
                    'demo_content'    => true,
                ],
            ],
            'legal'      => [
                'keywords' => [ 'law', 'lawyer', 'legal', 'attorney', 'firm', 'solicitor' ],
                'setup'    => [
                    'directory_name'  => __( 'Legal Directory', 'directorist' ),
                    'categories'      => [ __( 'Lawyers', 'directorist' ), __( 'Law Firms', 'directorist' ), __( 'Consultants', 'directorist' ), __( 'Notaries', 'directorist' ) ],
                    'default_address' => '',
                    'fields'          => [
                        [ 'label' => __( 'Name', 'directorist' ), 'type' => 'title' ],
                        [ 'label' => __( 'Profile', 'directorist' ), 'type' => 'description' ],
                        [ 'label' => __( 'Category', 'directorist' ), 'type' => 'category' ],
                        [ 'label' => __( 'Practice Area', 'directorist' ), 'type' => 'select', 'options' => [ __( 'Family Law', 'directorist' ), __( 'Business Law', 'directorist' ), __( 'Criminal Law', 'directorist' ), __( 'Real Estate Law', 'directorist' ) ] ],
                        [ 'label' => __( 'Address', 'directorist' ), 'type' => 'address' ],
                        [ 'label' => __( 'Map', 'directorist' ), 'type' => 'map' ],
                        [ 'label' => __( 'Phone', 'directorist' ), 'type' => 'phone' ],
                        [ 'label' => __( 'Email', 'directorist' ), 'type' => 'email' ],
                        [ 'label' => __( 'Website', 'directorist' ), 'type' => 'website' ],
                    ],
                    'monetization'    => true,
                    'demo_content'    => true,
                ],
            ],
            'service'    => [
                'keywords' => [ 'service', 'professional', 'lead', 'provider', 'contractor', 'expert' ],
                'setup'    => [
                    'directory_name'  => __( 'Service Marketplace', 'directorist' ),
                    'categories'      => [ __( 'Home Services', 'directorist' ), __( 'Business Services', 'directorist' ), __( 'Creative Services', 'directorist' ), __( 'Consulting', 'directorist' ) ],
                    'default_address' => '',
                    'fields'          => [
                        [ 'label' => __( 'Service Title', 'directorist' ), 'type' => 'title' ],
                        [ 'label' => __( 'Description', 'directorist' ), 'type' => 'description' ],
                        [ 'label' => __( 'Category', 'directorist' ), 'type' => 'category' ],
                        [ 'label' => __( 'Service Area', 'directorist' ), 'type' => 'location' ],
                        [ 'label' => __( 'Starting Price', 'directorist' ), 'type' => 'pricing' ],
                        [ 'label' => __( 'Experience', 'directorist' ), 'type' => 'number' ],
                        [ 'label' => __( 'Phone', 'directorist' ), 'type' => 'phone' ],
                        [ 'label' => __( 'Email', 'directorist' ), 'type' => 'email' ],
                        [ 'label' => __( 'Portfolio Link', 'directorist' ), 'type' => 'url' ],
                        [ 'label' => __( 'Photos', 'directorist' ), 'type' => 'image_upload' ],
                    ],
                    'monetization'    => true,
                    'demo_content'    => true,
                ],
            ],
            'local'      => [
                'keywords' => [ 'business', 'directory', 'local', 'place', 'city', 'community' ],
                'setup'    => [
                    'directory_name'  => __( 'Local Business Directory', 'directorist' ),
                    'categories'      => [ __( 'Restaurants', 'directorist' ), __( 'Shopping', 'directorist' ), __( 'Services', 'directorist' ), __( 'Health', 'directorist' ) ],
                    'default_address' => '',
                    'fields'          => [
                        [ 'label' => __( 'Business Name', 'directorist' ), 'type' => 'title' ],
                        [ 'label' => __( 'Description', 'directorist' ), 'type' => 'description' ],
                        [ 'label' => __( 'Category', 'directorist' ), 'type' => 'category' ],
                        [ 'label' => __( 'Address', 'directorist' ), 'type' => 'address' ],
                        [ 'label' => __( 'Map', 'directorist' ), 'type' => 'map' ],
                        [ 'label' => __( 'Phone', 'directorist' ), 'type' => 'phone' ],
                        [ 'label' => __( 'Email', 'directorist' ), 'type' => 'email' ],
                        [ 'label' => __( 'Website', 'directorist' ), 'type' => 'website' ],
                        [ 'label' => __( 'Photos', 'directorist' ), 'type' => 'image_upload' ],
                        [ 'label' => __( 'Social Links', 'directorist' ), 'type' => 'social_info' ],
                    ],
                    'monetization'    => true,
                    'demo_content'    => true,
                ],
            ],
        ];
    }

    private function normalize_setup_payload( array $payload ) {
        $directory_name = '';

        foreach ( [ 'directory_name', 'name', 'type', 'directory_type' ] as $key ) {
            if ( ! empty( $payload[ $key ] ) && is_scalar( $payload[ $key ] ) ) {
                $directory_name = sanitize_text_field( $payload[ $key ] );
                break;
            }
        }

        if ( empty( $directory_name ) ) {
            $directory_name = __( 'Business Directory', 'directorist' );
        }

        $categories = [];
        if ( ! empty( $payload['categories'] ) && is_array( $payload['categories'] ) ) {
            foreach ( $payload['categories'] as $category ) {
                if ( is_scalar( $category ) && trim( $category ) !== '' ) {
                    $categories[] = sanitize_text_field( $category );
                }
            }
        }

        $fields = $this->normalize_fields( ! empty( $payload['fields'] ) && is_array( $payload['fields'] ) ? $payload['fields'] : [] );
        $default_address = '';

        foreach ( [ 'default_address', 'default_location', 'location' ] as $key ) {
            if ( ! empty( $payload[ $key ] ) && is_scalar( $payload[ $key ] ) ) {
                $default_address = sanitize_text_field( $payload[ $key ] );
                break;
            }
        }

        return [
            'directory_name'  => $directory_name,
            'categories'      => array_values( array_unique( $categories ) ),
            'default_address' => $default_address,
            'fields'          => $fields,
            'monetization'    => ! empty( $payload['monetization'] ) || ! empty( $payload['payment'] ),
            'data_sharing'    => array_key_exists( 'data_sharing', $payload ) ? (bool) $payload['data_sharing'] : true,
            'demo_content'    => array_key_exists( 'demo_content', $payload ) ? (bool) $payload['demo_content'] : true,
        ];
    }

    private function normalize_fields( array $fields ) {
        $normalized = [];

        foreach ( $fields as $field ) {
            if ( ! is_array( $field ) ) {
                continue;
            }

            $label = ! empty( $field['label'] ) && is_scalar( $field['label'] ) ? sanitize_text_field( $field['label'] ) : '';
            $label = $label ?: ( ! empty( $field['name'] ) && is_scalar( $field['name'] ) ? sanitize_text_field( $field['name'] ) : '' );

            if ( empty( $label ) ) {
                continue;
            }

            if ( $this->is_hours_field( $field, $label ) ) {
                continue;
            }

            $type = ! empty( $field['type'] ) && is_scalar( $field['type'] ) ? $this->normalize_field_type( $field['type'], $label ) : 'text';

            if ( empty( $type ) ) {
                continue;
            }

            $normalized[] = [
                'label'   => $label,
                'type'    => $type,
                'group'   => ! empty( $field['group'] ) && is_scalar( $field['group'] ) ? sanitize_text_field( $field['group'] ) : __( 'General Information', 'directorist' ),
                'options' => $this->normalize_field_options( $field ),
            ];
        }

        $normalized = $this->dedupe_locked_preset_fields( $normalized );
        $normalized = $this->normalize_dependent_preset_fields( $normalized );

        if ( ! $this->has_field_type( $normalized, 'title' ) ) {
            array_unshift(
                $normalized,
                [
                    'label'   => __( 'Title', 'directorist' ),
                    'type'    => 'title',
                    'group'   => __( 'General Information', 'directorist' ),
                    'options' => [],
                ]
            );
        }

        if ( ! $this->has_field_type( $normalized, 'description' ) ) {
            $normalized[] = [
                'label'   => __( 'Description', 'directorist' ),
                'type'    => 'description',
                'group'   => __( 'General Information', 'directorist' ),
                'options' => [],
            ];
        }

        return $normalized;
    }

    private function dedupe_locked_preset_fields( array $fields ) {
        $seen = [];

        foreach ( $fields as $index => $field ) {
            if ( empty( $field['type'] ) || ! isset( $this->preset_fields[ $field['type'] ] ) ) {
                continue;
            }

            $preset_type = $this->preset_fields[ $field['type'] ];

            if ( ! empty( $seen[ $preset_type ] ) ) {
                unset( $fields[ $index ] );
                continue;
            }

            $seen[ $preset_type ] = true;
        }

        return array_values( $fields );
    }

    private function normalize_dependent_preset_fields( array $fields ) {
        $has_phone = $this->fields_have_type( $fields, 'phone' );

        foreach ( $fields as $index => $field ) {
            $label = ! empty( $field['label'] ) && is_scalar( $field['label'] ) ? $field['label'] : '';
            $type  = ! empty( $field['type'] ) && is_scalar( $field['type'] ) ? $field['type'] : '';

            if ( 'phone2' === $type && ! $has_phone ) {
                $fields[ $index ]['type']  = 'phone';
                $fields[ $index ]['label'] = __( 'Phone', 'directorist' );
                $has_phone = true;
                continue;
            }

            if ( 'phone' === $type && $this->is_secondary_phone_label( $label ) ) {
                $fields[ $index ]['label'] = __( 'Phone', 'directorist' );
            }
        }

        if ( ! $this->fields_have_type( $fields, 'map' ) ) {
            foreach ( $fields as $index => $field ) {
                if ( empty( $field['type'] ) || 'address' !== $field['type'] ) {
                    continue;
                }

                array_splice(
                    $fields,
                    $index + 1,
                    0,
                    [
                        [
                            'label'   => __( 'Map', 'directorist' ),
                            'type'    => 'map',
                            'group'   => ! empty( $field['group'] ) ? $field['group'] : __( 'General Information', 'directorist' ),
                            'options' => [],
                        ],
                    ]
                );
                break;
            }
        }

        return $fields;
    }

    private function remove_unselected_locked_regenerated_fields( array $regenerated_fields, array $existing_fields, array $selected_fields ) {
        foreach ( [ 'title', 'description' ] as $type ) {
            if ( ! $this->raw_fields_have_type( $existing_fields, $type ) || $this->raw_fields_have_type( $selected_fields, $type ) ) {
                continue;
            }

            foreach ( $regenerated_fields as $index => $field ) {
                if ( ! empty( $field['type'] ) && $field['type'] === $type ) {
                    unset( $regenerated_fields[ $index ] );
                }
            }
        }

        return array_values( $regenerated_fields );
    }

    private function preserve_regenerated_selected_field_context( array $regenerated_fields, array $existing_fields, array $selected_fields ) {
        if ( 1 !== count( $selected_fields ) || empty( $regenerated_fields ) ) {
            return $regenerated_fields;
        }

        $selected_field = reset( $selected_fields );

        if ( ! is_array( $selected_field ) ) {
            return $regenerated_fields;
        }

        $selected_label = ! empty( $selected_field['label'] ) && is_scalar( $selected_field['label'] ) ? sanitize_text_field( $selected_field['label'] ) : __( 'Phone', 'directorist' );
        $selected_type  = ! empty( $selected_field['type'] ) && is_scalar( $selected_field['type'] ) ? $this->normalize_field_type( $selected_field['type'], $selected_label ) : '';

        if ( ! in_array( $selected_type, [ 'phone', 'phone2' ], true ) ) {
            return $regenerated_fields;
        }

        $existing_phone_count = $this->raw_fields_type_count( $existing_fields, 'phone' );
        $selected_phone_count = $this->raw_fields_type_count( $selected_fields, 'phone' );
        $has_unselected_phone = $existing_phone_count > $selected_phone_count;
        $target_type          = ( 'phone2' === $selected_type && $has_unselected_phone ) ? 'phone2' : 'phone';
        $target_label         = ( 'phone2' === $target_type ) ? $selected_label : $this->primary_phone_label( $selected_label );
        $target_index         = $this->find_phone_like_regenerated_field_index( $regenerated_fields );

        if ( null === $target_index ) {
            foreach ( $regenerated_fields as $index => $field ) {
                $target_index = $index;
                break;
            }
        }

        $regenerated_fields[ $target_index ]['type']  = $target_type;
        $regenerated_fields[ $target_index ]['label'] = $target_label;

        return $this->normalize_dependent_preset_fields( array_values( $regenerated_fields ) );
    }

    private function find_phone_like_regenerated_field_index( array $fields ) {
        foreach ( $fields as $index => $field ) {
            if ( ! is_array( $field ) ) {
                continue;
            }

            $label = ! empty( $field['label'] ) && is_scalar( $field['label'] ) ? sanitize_text_field( $field['label'] ) : '';
            $type  = ! empty( $field['type'] ) && is_scalar( $field['type'] ) ? $this->normalize_field_type( $field['type'], $label ) : '';

            if ( in_array( $type, [ 'phone', 'phone2' ], true ) || preg_match( '/\b(phone|telephone|contact number|mobile)\b/i', $label ) ) {
                return $index;
            }
        }

        return null;
    }

    private function raw_fields_type_count( array $fields, $type ) {
        $count = 0;

        foreach ( $fields as $field ) {
            if ( ! is_array( $field ) ) {
                continue;
            }

            $label = ! empty( $field['label'] ) && is_scalar( $field['label'] ) ? sanitize_text_field( $field['label'] ) : '';
            $raw_type = ! empty( $field['type'] ) && is_scalar( $field['type'] ) ? $this->normalize_field_type( $field['type'], $label ) : '';

            if ( $raw_type === $type ) {
                $count++;
            }
        }

        return $count;
    }

    private function fields_have_type( array $fields, $type ) {
        foreach ( $fields as $field ) {
            if ( ! empty( $field['type'] ) && $field['type'] === $type ) {
                return true;
            }
        }

        return false;
    }

    private function raw_fields_have_type( array $fields, $type ) {
        foreach ( $fields as $field ) {
            if ( ! is_array( $field ) ) {
                continue;
            }

            $label = ! empty( $field['label'] ) && is_scalar( $field['label'] ) ? sanitize_text_field( $field['label'] ) : '';
            $raw_type = ! empty( $field['type'] ) && is_scalar( $field['type'] ) ? $this->normalize_field_type( $field['type'], $label ) : '';

            if ( $raw_type === $type ) {
                return true;
            }
        }

        return false;
    }

    private function normalize_field_type( $type, $label = '' ) {
        $type = strtolower( trim( str_replace( [ ' ', '-' ], '_', (string) $type ) ) );

        $map = [
            'dropdown'      => 'select',
            'long_text'     => 'textarea',
            'multi_line'    => 'textarea',
            'wp_editor'     => 'html',
            'image'         => 'image_upload',
            'photos'        => 'image_upload',
            'photo'         => 'image_upload',
            'file_upload'   => 'file',
            'postal_code'   => 'zip',
            'postcode'      => 'zip',
            'phone_number'  => 'phone',
            'business_hours' => '',
            'opening_hours' => '',
            'hours'         => '',
        ];

        $type = isset( $map[ $type ] ) ? $map[ $type ] : $type;
        $label_key = strtolower( trim( (string) $label ) );

        if ( preg_match( '/\b(social|facebook|twitter|x profile|instagram|linkedin|youtube|tiktok|pinterest|snapchat)\b/i', $label_key . ' ' . $type ) ) {
            return 'social_info';
        }

        if ( preg_match( '/\b(image|images|photo|photos|gallery|logo)\b/i', $label_key ) ) {
            return 'image_upload';
        }

        if ( preg_match( '/\b(website|site url|business url|company url)\b/i', $label_key ) ) {
            return 'website';
        }

        if ( preg_match( '/\b(email|e-mail)\b/i', $label_key ) ) {
            return 'email';
        }

        if ( $this->is_secondary_phone_label( $label_key . ' ' . $type ) ) {
            return 'phone2';
        }

        if ( preg_match( '/\b(phone|telephone|contact number|mobile)\b/i', $label_key ) ) {
            return 'phone';
        }

        if ( isset( $this->preset_fields[ $type ] ) ) {
            return $this->preset_fields[ $type ];
        }

        if ( in_array( $type, $this->custom_fields, true ) ) {
            return $type;
        }

        if ( preg_match( '/url|link|website/i', $label ) ) {
            return 'url';
        }

        if ( preg_match( '/price|cost|amount|salary/i', $label ) ) {
            return 'number';
        }

        return 'text';
    }

    private function is_secondary_phone_label( $label ) {
        return (bool) preg_match( '/\b(phone\s*2|phone2|alternative\s+phone|alternate\s+phone|secondary\s+phone|second\s+phone|additional\s+phone|other\s+phone|backup\s+phone|alternative\s+contact(?:\s+number)?|alternate\s+contact(?:\s+number)?|secondary\s+contact(?:\s+number)?)\b/i', (string) $label );
    }

    private function primary_phone_label( $label ) {
        return $this->is_secondary_phone_label( $label ) ? __( 'Phone', 'directorist' ) : $label;
    }

    private function is_hours_field( array $field, $label ) {
        $raw_type = ! empty( $field['type'] ) && is_scalar( $field['type'] ) ? strtolower( trim( str_replace( [ ' ', '-' ], '_', (string) $field['type'] ) ) ) : '';

        if ( in_array( $raw_type, [ 'business_hours', 'opening_hours', 'hours' ], true ) ) {
            return true;
        }

        return (bool) preg_match( '/\b(business|opening|office|working)?\s*hours\b/i', $label );
    }

    private function normalize_field_options( array $field ) {
        if ( empty( $field['options'] ) || ! is_array( $field['options'] ) ) {
            return [];
        }

        $options = [];
        foreach ( $field['options'] as $option ) {
            if ( is_array( $option ) ) {
                $value = ! empty( $option['value'] ) ? $option['value'] : ( $option['option_value'] ?? '' );
                $label = ! empty( $option['label'] ) ? $option['label'] : ( $option['option_label'] ?? $value );
            } else {
                $value = $option;
                $label = $option;
            }

            if ( is_scalar( $value ) && is_scalar( $label ) && '' !== trim( $value ) ) {
                $options[] = [
                    'option_value' => sanitize_text_field( $value ),
                    'option_label' => sanitize_text_field( $label ),
                ];
            }
        }

        return $options;
    }

    private function has_field_type( array $fields, $type ) {
        foreach ( $fields as $field ) {
            if ( ! empty( $field['type'] ) && $field['type'] === $type ) {
                return true;
            }
        }

        return false;
    }

    private function build_directory( array $setup ) {
        $directory_name = $this->unique_directory_name( $setup['directory_name'] );
        $directory_config_file = DIRECTORIST_ASSETS_DIR . 'sample-data/directory/directory.json';
        $directory_config      = json_decode( file_get_contents( $directory_config_file ), true );

        if ( empty( $directory_config ) || ! is_array( $directory_config ) ) {
            return new WP_Error( 'invalid_directory_config', __( 'Directory configuration is missing.', 'directorist' ) );
        }

        $form_fields   = $this->prepare_form_fields( $setup['fields'] );
        $single_fields = $this->prepare_single_fields( $form_fields );

        $directory_config['submission_form_fields'] = $form_fields;
        $directory_config['search_form_fields']     = $this->prepare_search_fields( $form_fields, $directory_config['search_form_fields'] ?? [] );
        $directory_config['single_listing_header']  = $single_fields['header'];
        unset( $single_fields['header'] );
        $directory_config['single_listings_contents'] = $single_fields;

        Multi_Directory_Manager::load_builder_data();

        $directory = Multi_Directory_Manager::add_directory(
            [
                'directory_name' => $directory_name,
                'fields_value'   => $directory_config,
                'is_json'        => false,
            ]
        );

        if ( ! empty( $directory['status']['success'] ) ) {
            $term_id = $directory['term_id'];
        } elseif ( ! empty( $directory['status']['term_id'] ) ) {
            $term_id = $directory['status']['term_id'];
        } else {
            return new WP_Error( 'directory_create_failed', __( 'Could not create the directory.', 'directorist' ) );
        }

        return [
            'id'        => $term_id,
            'structure' => $directory_config,
        ];
    }

    private function set_default_directory( $directory_id ) {
        $directory_id = absint( $directory_id );

        if ( ! $directory_id ) {
            return;
        }

        $directories = get_terms(
            [
                'taxonomy'   => ATBDP_TYPE,
                'hide_empty' => false,
            ]
        );

        if ( ! is_wp_error( $directories ) ) {
            foreach ( $directories as $directory ) {
                update_term_meta( $directory->term_id, '_default', ( (int) $directory->term_id === $directory_id ) );
            }
        }

        if ( function_exists( 'update_directorist_option' ) ) {
            update_directorist_option( 'atbdp_default_derectory', $directory_id );
        }
    }

    private function prepare_form_fields( array $fields ) {
        $field_templates = $this->get_field_templates();
        $prepared_fields = [];
        $prepared_groups = [];
        $counter         = [];
        $used_preset     = [];

        foreach ( $fields as $field ) {
            $type = $field['type'];
            $group = ! empty( $field['group'] ) ? $field['group'] : __( 'General Information', 'directorist' );
            $field_name = '';

            if ( isset( $this->preset_fields[ $type ] ) ) {
                $field_name = $this->preset_fields[ $type ];

                if ( isset( $used_preset[ $field_name ] ) || empty( $field_templates[ $field_name ] ) ) {
                    continue;
                }

                $_field = $field_templates[ $field_name ];
                $_field['label'] = $field['label'];
                $prepared_fields[ $field_name ] = $_field;
                $used_preset[ $field_name ] = true;
            } elseif ( isset( $field_templates[ $type ] ) ) {
                $_field = $field_templates[ $type ];
                $_field['label'] = $field['label'];

                if ( in_array( $type, [ 'select', 'radio', 'checkbox' ], true ) ) {
                    $_field['options'] = ! empty( $field['options'] ) ? $field['options'] : [
                        [ 'option_value' => 'option_1', 'option_label' => __( 'Option 1', 'directorist' ) ],
                        [ 'option_value' => 'option_2', 'option_label' => __( 'Option 2', 'directorist' ) ],
                    ];
                }

                if ( isset( $counter[ $type ] ) ) {
                    $counter[ $type ]++;
                    $field_name = $type . '_' . $counter[ $type ];
                    $_field['field_key'] = 'custom-' . str_replace( '_', '-', $type ) . '-' . $counter[ $type ];
                } else {
                    $counter[ $type ] = 1;
                    $field_name = $type;
                    $_field['field_key'] = 'custom-' . str_replace( '_', '-', $type );
                }

                $_field['widget_key'] = $field_name;
                $prepared_fields[ $field_name ] = $_field;
            }

            if ( $field_name ) {
                if ( empty( $prepared_groups[ $group ] ) ) {
                    $prepared_groups[ $group ] = $this->prepare_form_group( $group );
                }

                $prepared_groups[ $group ]['fields'][] = $field_name;
            }
        }

        return [
            'groups' => array_values( $this->prepare_form_group_icons( $prepared_groups, $prepared_fields ) ),
            'fields' => $prepared_fields,
        ];
    }

    private function prepare_form_group( $label ) {
        return [
            'type'                            => 'general_group',
            'label'                           => $label,
            'fields'                          => [],
            'defaultGroupLabel'               => __( 'Section', 'directorist' ),
            'disableTrashIfGroupHasWidgets'   => [
                [
                    'widget_name'  => 'title',
                    'widget_group' => 'preset',
                ],
            ],
            'icon'                            => 'las la-pen-nib',
        ];
    }

    private function prepare_form_group_icons( array $groups, array $fields ) {
        foreach ( $groups as $group_key => $group ) {
            $groups[ $group_key ]['icon'] = $this->get_form_group_icon( $group, $fields );
        }

        return $groups;
    }

    private function get_form_group_icon( array $group, array $fields ) {
        $label = ! empty( $group['label'] ) ? strtolower( $group['label'] ) : '';

        $label_icons = [
            'contact'  => 'las la-phone',
            'phone'    => 'las la-phone',
            'email'    => 'las la-phone',
            'location' => 'las la-map-marked-alt',
            'address'  => 'las la-map-marked-alt',
            'map'      => 'las la-map-marked-alt',
            'media'    => 'las la-images',
            'image'    => 'las la-images',
            'photo'    => 'las la-images',
            'video'    => 'las la-images',
            'pricing'  => 'las la-money-bill-alt',
            'price'    => 'las la-money-bill-alt',
            'feature'  => 'las la-check-double',
            'amenity'  => 'las la-check-double',
            'social'   => 'las la-share',
            'category' => 'las la-tag',
            'tag'      => 'las la-tag',
            'detail'   => 'las la-tag',
            'general'  => 'las la-pen-nib',
            'basic'    => 'las la-pen-nib',
        ];

        foreach ( $label_icons as $needle => $icon ) {
            if ( false !== strpos( $label, $needle ) ) {
                return $icon;
            }
        }

        $field_widget_names = [];
        foreach ( $group['fields'] as $field_key ) {
            if ( empty( $fields[ $field_key ] ) || empty( $fields[ $field_key ]['widget_name'] ) ) {
                continue;
            }

            $field_widget_names[] = $fields[ $field_key ]['widget_name'];
        }

        $field_icons = [
            'las la-phone'          => [ 'phone', 'phone2', 'fax', 'email', 'website', 'social_info', 'button' ],
            'las la-map-marked-alt' => [ 'location', 'address', 'map', 'zip' ],
            'las la-images'         => [ 'image_upload', 'video', 'file' ],
            'las la-money-bill-alt' => [ 'pricing' ],
            'las la-tag'            => [ 'category', 'tag' ],
            'las la-check-double'   => [ 'checkbox', 'select', 'radio' ],
        ];

        foreach ( $field_icons as $icon => $widget_names ) {
            if ( array_intersect( $widget_names, $field_widget_names ) ) {
                return $icon;
            }
        }

        return 'las la-pen-nib';
    }

    private function prepare_search_fields( array $form_fields, array $existing_search_fields = [] ) {
        $search_fields = [
            'title' => $this->prepare_search_title_field( $existing_search_fields ),
        ];
        $searchable_fields = [];

        foreach ( $form_fields['fields'] as $field_key => $field ) {
            if ( 'title' === $field_key ) {
                continue;
            }

            $widget_name = $this->get_search_widget_name( $field_key, $field );

            if ( ! $widget_name ) {
                continue;
            }

            $searchable_fields[ $field_key ] = $this->prepare_search_field( $field_key, $field, $widget_name );
        }

        $search_bar_fields = [ 'title' ];
        $preferred_fields  = [];

        foreach ( [ 'category', 'location' ] as $field_key ) {
            if ( isset( $searchable_fields[ $field_key ] ) ) {
                $preferred_fields[] = $field_key;
            }
        }

        foreach ( $this->get_search_bar_fallback_fields( $searchable_fields, $preferred_fields ) as $field_key ) {
            if ( count( $preferred_fields ) >= 2 ) {
                break;
            }

            $preferred_fields[] = $field_key;
        }

        $search_filter_fields = [];
        foreach ( $searchable_fields as $field_key => $field ) {
            if ( in_array( $field_key, $preferred_fields, true ) ) {
                $field = $this->prepare_search_bar_field( $field );
            } else {
                $search_filter_fields[] = $field_key;
            }

            $search_fields[ $field_key ] = $field;
        }

        $search_bar_fields = array_merge( $search_bar_fields, $preferred_fields );

        return [
            'fields' => $search_fields,
            'groups' => [
                [
                    'label'     => __( 'Search Bar', 'directorist' ),
                    'lock'      => true,
                    'draggable' => false,
                    'fields'    => $search_bar_fields,
                    'type'      => 'general_group',
                ],
                [
                    'label'     => __( 'Search Filter', 'directorist' ),
                    'lock'      => true,
                    'draggable' => false,
                    'fields'    => $search_filter_fields,
                    'type'      => 'general_group',
                ],
            ],
        ];
    }

    private function prepare_search_title_field( array $existing_search_fields ) {
        $title_field = [
            'required'            => false,
            'label'               => __( 'What are you looking for?', 'directorist' ),
            'placeholder'         => '',
            'widget_name'         => 'title',
            'widget_group'        => 'available_widgets',
            'original_widget_key' => 'title',
            'widget_key'          => 'title',
        ];

        if ( ! empty( $existing_search_fields['fields']['title'] ) && is_array( $existing_search_fields['fields']['title'] ) ) {
            $title_field = array_merge( $title_field, $existing_search_fields['fields']['title'] );
        }

        $title_field['widget_name']         = 'title';
        $title_field['widget_group']        = 'available_widgets';
        $title_field['original_widget_key'] = 'title';
        $title_field['widget_key']          = 'title';

        return $title_field;
    }

    private function get_search_widget_name( $field_key, array $field ) {
        $preset_search_fields = [
            'category',
            'location',
            'tag',
            'pricing',
            'zip',
            'phone',
            'phone2',
            'fax',
            'email',
            'website',
        ];

        if ( in_array( $field_key, $preset_search_fields, true ) ) {
            return $field_key;
        }

        $widget_name = ! empty( $field['widget_name'] ) && is_scalar( $field['widget_name'] ) ? $field['widget_name'] : '';

        if ( in_array( $widget_name, [ 'text', 'textarea', 'number', 'url', 'date', 'time', 'color_picker', 'select', 'checkbox', 'radio' ], true ) ) {
            return $widget_name;
        }

        return '';
    }

    private function prepare_search_field( $field_key, array $field, $widget_name ) {
        $label = ! empty( $field['label'] ) && is_scalar( $field['label'] ) ? $field['label'] : ucwords( str_replace( [ '_', '-' ], ' ', $field_key ) );

        $search_field = [
            'label'               => $label,
            'placeholder'         => $label,
            'required'            => false,
            'widget_name'         => $widget_name,
            'widget_group'        => 'available_widgets',
            'original_widget_key' => $field_key,
            'widget_key'          => $field_key,
        ];

        if ( 'category' === $widget_name ) {
            $search_field['label']       = '';
            $search_field['placeholder'] = __( 'Select a category', 'directorist' );
        }

        if ( 'location' === $widget_name ) {
            $search_field['label']           = '';
            $search_field['placeholder']     = __( 'Select a location', 'directorist' );
            $search_field['location_source'] = 'from_listing_location';
        }

        if ( 'pricing' === $widget_name ) {
            $search_field['label']                       = $label ?: __( 'Pricing', 'directorist' );
            $search_field['price_range_min_placeholder'] = __( 'Min', 'directorist' );
            $search_field['price_range_max_placeholder'] = __( 'Max', 'directorist' );
        }

        if ( 'textarea' === $widget_name ) {
            $rows                 = ! empty( $field['rows'] ) && is_scalar( $field['rows'] ) ? absint( $field['rows'] ) : 8;
            $search_field['rows'] = $rows ? $rows : 8;
        }

        if ( 'number' === $widget_name ) {
            $search_field['type'] = 'number';
        }

        return $search_field;
    }

    private function prepare_search_bar_field( array $field ) {
        if ( ! empty( $field['label'] ) && empty( $field['placeholder'] ) ) {
            $field['placeholder'] = $field['label'];
        }

        if ( 'title' !== $field['widget_name'] ) {
            $field['label'] = '';
        }

        return $field;
    }

    private function get_search_bar_fallback_fields( array $searchable_fields, array $selected_fields ) {
        $candidates = [];
        $index      = 0;

        foreach ( $searchable_fields as $field_key => $field ) {
            if ( in_array( $field_key, $selected_fields, true ) ) {
                continue;
            }

            $candidates[] = [
                'key'   => $field_key,
                'score' => $this->get_search_bar_field_score( $field ),
                'index' => $index,
            ];
            $index++;
        }

        usort(
            $candidates,
            function ( $a, $b ) {
                if ( $a['score'] === $b['score'] ) {
                    return $a['index'] - $b['index'];
                }

                return $a['score'] - $b['score'];
            }
        );

        return wp_list_pluck( $candidates, 'key' );
    }

    private function get_search_bar_field_score( array $field ) {
        $priority = [
            'tag'          => 10,
            'pricing'      => 20,
            'select'       => 30,
            'radio'        => 40,
            'checkbox'     => 50,
            'number'       => 60,
            'date'         => 70,
            'time'         => 80,
            'zip'          => 90,
            'website'      => 100,
            'url'          => 110,
            'email'        => 120,
            'phone'        => 130,
            'phone2'       => 140,
            'fax'          => 150,
            'text'         => 160,
            'textarea'     => 170,
            'color_picker' => 180,
        ];

        $widget_name = ! empty( $field['widget_name'] ) ? $field['widget_name'] : '';

        return isset( $priority[ $widget_name ] ) ? $priority[ $widget_name ] : 999;
    }

    private function get_field_templates() {
        $file = DIRECTORIST_ASSETS_DIR . 'sample-data/listing-form-fields.json';
        $templates = json_decode( file_get_contents( $file ), true );

        $templates['html'] = [
            'type'           => 'wp_editor',
            'field_key'      => 'custom-html',
            'label'          => 'Html',
            'description'    => '',
            'placeholder'    => '',
            'required'       => false,
            'only_for_admin' => false,
            'widget_group'   => 'custom',
            'widget_name'    => 'html',
            'widget_key'     => 'html',
        ];

        $templates['button'] = [
            'type'                    => 'button',
            'field_key'               => 'custom-button',
            'label'                   => 'Button',
            'button_text_placeholder' => 'Visit Now',
            'button_text_description' => '',
            'button_url_label'        => 'Website URL',
            'button_url_placeholder'  => 'https://yourlink.com',
            'button_url_description'  => '',
            'button_style'            => 'default',
            'open_in_new_tab'         => true,
            'required'                => false,
            'only_for_admin'          => false,
            'widget_group'            => 'custom',
            'widget_name'             => 'button',
            'widget_key'              => 'button',
        ];

        return $templates;
    }

    private function prepare_single_fields( array $form_fields ) {
        $fields = [];
        $ignorable_fields = [
            'title'        => false,
            'tagline'      => false,
            'image_upload' => false,
            'location'     => false,
            'category'     => false,
            'pricing'      => false,
            'terms_privacy' => false,
        ];

        foreach ( $form_fields['fields'] as $field_key => $field ) {
            if ( isset( $ignorable_fields[ $field_key ] ) ) {
                $ignorable_fields[ $field_key ] = true;
                continue;
            }

            $fields[ $field_key ] = [
                'icon'                => 'las la-tag',
                'widget_group'        => 'preset_widgets',
                'widget_name'         => $field['widget_name'],
                'original_widget_key' => $field_key,
                'widget_key'          => $field_key,
            ];

            if ( $field_key === 'address' ) {
                $fields[ $field_key ]['address_link_with_map'] = false;
            }

            if ( $field_key === 'website' ) {
                $fields[ $field_key ]['use_nofollow'] = true;
            }
        }

        $groups = [];
        $ignorable_field_keys = array_keys( $ignorable_fields );
        $map_address_fields = [];
        $contact_info_fields = [];
        $section_id = 0;

        foreach ( $form_fields['groups'] as $group ) {
            $group_fields = array_values( array_diff( $group['fields'], $ignorable_field_keys ) );

            if ( empty( $group_fields ) ) {
                continue;
            }

            $regular_group_fields = [];

            foreach ( $group_fields as $field_key ) {
                $field = isset( $form_fields['fields'][ $field_key ] ) ? $form_fields['fields'][ $field_key ] : [];

                if ( $this->is_single_map_address_field( $field_key ) ) {
                    $map_address_fields[] = $field_key;
                    continue;
                }

                if ( $this->is_single_contact_info_field( $field_key, $field ) ) {
                    $contact_info_fields[] = $field_key;
                    continue;
                }

                $regular_group_fields[] = $field_key;
            }

            if ( empty( $regular_group_fields ) ) {
                continue;
            }

            $groups[] = [
                'type'       => 'general_group',
                'label'      => $group['label'],
                'fields'     => array_values( $regular_group_fields ),
                'section_id' => ++$section_id,
                'icon'       => $this->get_form_group_icon(
                    [
                        'label'  => $group['label'],
                        'fields' => array_values( $regular_group_fields ),
                    ],
                    $form_fields['fields']
                ),
            ];
        }

        $map_address_fields = $this->sort_single_map_address_fields( array_values( array_unique( $map_address_fields ) ) );

        if ( ! empty( $map_address_fields ) ) {
            $groups[] = [
                'type'       => 'general_group',
                'label'      => __( 'Map & Address', 'directorist' ),
                'fields'     => $map_address_fields,
                'section_id' => ++$section_id,
                'icon'       => 'las la-map-marked-alt',
            ];
        }

        $contact_info_fields = array_values( array_unique( $contact_info_fields ) );

        if ( ! empty( $contact_info_fields ) ) {
            $groups[] = [
                'type'       => 'general_group',
                'label'      => __( 'Contact Information', 'directorist' ),
                'fields'     => $contact_info_fields,
                'section_id' => ++$section_id,
                'icon'       => 'las la-phone',
            ];
        }

        $groups[] = [
            'type'          => 'section',
            'label'         => 'Author Info',
            'section_id'    => ++$section_id,
            'icon'          => 'las la-user',
            'display_email' => true,
            'widget_group'  => 'other_widgets',
            'widget_name'   => 'author_info',
            'fields'        => [],
        ];

        $fields['contact_name'] = [
            'enable'            => 1,
            'placeholder'       => 'Name',
            'widget_group'      => 'other_widgets',
            'widget_name'       => 'contact_listings_owner',
            'widget_child_name' => 'contact_name',
            'widget_key'        => 'contact_name',
        ];

        $fields['contact_email'] = [
            'placeholder'       => 'Email',
            'widget_group'      => 'other_widgets',
            'widget_name'       => 'contact_listings_owner',
            'widget_child_name' => 'contact_email',
            'widget_key'        => 'contact_email',
        ];

        $fields['contact_message'] = [
            'placeholder'       => 'Message...',
            'widget_group'      => 'other_widgets',
            'widget_name'       => 'contact_listings_owner',
            'widget_child_name' => 'contact_message',
            'widget_key'        => 'contact_message',
        ];

        $groups[] = [
            'type'             => 'section',
            'label'            => 'Contact Listings Owner Form',
            'fields'           => [ 'contact_name', 'contact_email', 'contact_message' ],
            'section_id'       => ++$section_id,
            'icon'             => 'las la-phone',
            'accepted_widgets' => [
                [
                    'widget_group'      => 'other_widgets',
                    'widget_name'       => 'contact_listings_owner',
                    'widget_child_name' => 'contact_name',
                ],
                [
                    'widget_group'      => 'other_widgets',
                    'widget_name'       => 'contact_listings_owner',
                    'widget_child_name' => 'contact_email',
                ],
                [
                    'widget_group'      => 'other_widgets',
                    'widget_name'       => 'contact_listings_owner',
                    'widget_child_name' => 'contact_message',
                ],
            ],
            'widget_group' => 'other_widgets',
            'widget_name'  => 'contact_listings_owner',
        ];

        return [
            'header' => $this->prepare_single_header_fields( $ignorable_fields ),
            'groups' => $groups,
            'fields' => $fields,
        ];
    }

    private function is_single_map_address_field( $field_key ) {
        return in_array( $field_key, [ 'address', 'map' ], true );
    }

    private function is_single_contact_info_field( $field_key, array $field ) {
        $contact_fields = [ 'phone', 'phone2', 'fax', 'email', 'website', 'social_info' ];
        $widget_name    = ! empty( $field['widget_name'] ) && is_scalar( $field['widget_name'] ) ? $field['widget_name'] : '';

        return in_array( $field_key, $contact_fields, true ) || in_array( $widget_name, $contact_fields, true );
    }

    private function sort_single_map_address_fields( array $fields ) {
        $priority = [
            'address' => 0,
            'map'     => 1,
        ];

        usort(
            $fields,
            function ( $a, $b ) use ( $priority ) {
                return ( $priority[ $a ] ?? 99 ) - ( $priority[ $b ] ?? 99 );
            }
        );

        return $fields;
    }

    private function prepare_single_header_fields( array $header_fields ) {
        $fields = [
            'quick-widgets-placeholder' => [
                'type'           => 'placeholder_group',
                'placeholderKey' => 'quick-widgets-placeholder',
                'placeholders'   => [
                    [
                        'type'           => 'placeholder_group',
                        'placeholderKey' => 'quick-info-placeholder',
                        'selectedWidgets' => [
                            [
                                'type'        => 'button',
                                'label'       => 'Back',
                                'widget_name' => 'back',
                                'widget_key'  => 'back',
                            ],
                        ],
                    ],
                    [
                        'type'           => 'placeholder_group',
                        'placeholderKey' => 'quick-action-placeholder',
                        'selectedWidgets' => [
                            [
                                'type'        => 'button',
                                'label'       => 'Bookmark',
                                'widget_name' => 'bookmark',
                                'widget_key'  => 'bookmark',
                            ],
                            [
                                'type'        => 'badge',
                                'label'       => 'Share',
                                'widget_name' => 'share',
                                'widget_key'  => 'share',
                                'icon'        => 'las la-share',
                            ],
                            [
                                'type'        => 'badge',
                                'label'       => 'Report',
                                'widget_name' => 'report',
                                'widget_key'  => 'report',
                                'icon'        => 'las la-flag',
                            ],
                        ],
                    ],
                ],
            ],
            'slider-placeholder' => [
                'type'           => 'placeholder_item',
                'placeholderKey' => 'slider-placeholder',
                'selectedWidgets' => [
                    [
                        'type'             => 'thumbnail',
                        'label'            => 'Listing Image/Slider',
                        'widget_name'      => 'slider',
                        'widget_key'       => 'slider',
                        'footer_thumbnail' => true,
                    ],
                ],
            ],
            'listing-title-placeholder' => [
                'type'           => 'placeholder_item',
                'placeholderKey' => 'listing-title-placeholder',
                'selectedWidgets' => [
                    [
                        'type'           => 'title',
                        'label'          => 'Listing Title',
                        'widget_name'    => 'title',
                        'widget_key'     => 'title',
                        'enable_tagline' => true,
                    ],
                ],
            ],
            'more-widgets-placeholder' => [
                'type'           => 'placeholder_item',
                'placeholderKey' => 'more-widgets-placeholder',
                'selectedWidgets' => [
                    [
                        'type'        => 'badge',
                        'label'       => 'Pricing',
                        'widget_name' => 'price',
                        'widget_key'  => 'price',
                    ],
                    [
                        'type'        => 'ratings-count',
                        'label'       => 'Rating',
                        'widget_name' => 'ratings_count',
                        'widget_key'  => 'ratings_count',
                    ],
                    [
                        'type'        => 'badge',
                        'label'       => 'Category',
                        'widget_name' => 'category',
                        'widget_key'  => 'category',
                    ],
                    [
                        'type'        => 'badge',
                        'label'       => 'Location',
                        'widget_name' => 'location',
                        'widget_key'  => 'location',
                    ],
                ],
            ],
        ];

        if ( empty( $header_fields['image_upload'] ) ) {
            $fields['slider-placeholder']['selectedWidgets'] = [];
        }

        if ( empty( $header_fields['title'] ) ) {
            $fields['listing-title-placeholder']['selectedWidgets'] = [];
        }

        foreach ( $fields['more-widgets-placeholder']['selectedWidgets'] as $index => $widget ) {
            if ( ( $widget['widget_key'] === 'price' && empty( $header_fields['pricing'] ) ) ||
                ( $widget['widget_key'] === 'location' && empty( $header_fields['location'] ) ) ||
                ( $widget['widget_key'] === 'category' && empty( $header_fields['category'] ) )
            ) {
                unset( $fields['more-widgets-placeholder']['selectedWidgets'][ $index ] );
            }
        }

        $fields['more-widgets-placeholder']['selectedWidgets'] = array_values( $fields['more-widgets-placeholder']['selectedWidgets'] );

        return array_values( $fields );
    }

    private function create_terms( array $terms, $taxonomy, $directory_id ) {
        foreach ( $terms as $term_name ) {
            $term_name = sanitize_text_field( $term_name );

            if ( empty( $term_name ) ) {
                continue;
            }

            $term = get_term_by( 'name', $term_name, $taxonomy );

            if ( $term && ! is_wp_error( $term ) ) {
                $term_id = $term->term_id;
            } else {
                $term = wp_insert_term( $term_name, $taxonomy );

                if ( is_wp_error( $term ) ) {
                    continue;
                }

                $term_id = $term['term_id'];
            }

            $directory_types = get_term_meta( $term_id, '_directory_type', true );
            $directory_types = is_array( $directory_types ) ? array_map( 'absint', $directory_types ) : [ absint( $directory_types ) ];
            $directory_types[] = (int) $directory_id;

            update_term_meta( $term_id, '_directory_type', array_values( array_unique( array_filter( $directory_types ) ) ) );
        }
    }

    private function import_demo_listings( array $setup, array $directory ) {
        $rows = $this->get_demo_listing_pool();

        if ( empty( $rows ) ) {
            return [
                'imported'       => 0,
                'failed'         => 0,
                'image_imported' => 0,
            ];
        }

        $limit  = absint( apply_filters( 'directorist_ai_setup_wizard_demo_listing_limit', 6, $setup, $directory ) );
        $limit  = $limit ? $limit : 6;
        $rows_with_local_images = $this->filter_rows_with_local_demo_images( $rows );

        if ( count( $rows_with_local_images ) >= $limit ) {
            $rows = $rows_with_local_images;
        }

        $rows   = $this->select_demo_rows( $rows, $setup, $limit );
        $fields = ! empty( $directory['structure']['submission_form_fields']['fields'] ) && is_array( $directory['structure']['submission_form_fields']['fields'] )
            ? $directory['structure']['submission_form_fields']['fields']
            : [];

        $result = [
            'imported'       => 0,
            'failed'         => 0,
            'image_imported' => 0,
            'listing_ids'    => [],
        ];

        foreach ( $rows as $index => $row ) {
            $listing = $this->create_demo_listing( $row, $setup, absint( $directory['id'] ), $fields, $index );

            if ( is_wp_error( $listing ) ) {
                $result['failed']++;
                continue;
            }

            $result['imported']++;
            $result['listing_ids'][] = $listing['id'];

            if ( ! empty( $listing['image_id'] ) ) {
                $result['image_imported']++;
            }
        }

        return $result;
    }

    private function get_demo_listing_pool() {
        $file = ATBDP_VIEWS_DIR . 'admin-templates/import-export/data/dummy.csv';

        if ( ! is_readable( $file ) ) {
            return [];
        }

        $handle = fopen( $file, 'r' );

        if ( false === $handle ) {
            return [];
        }

        $headers = fgetcsv( $handle, 0, ',', '"', '\\' );
        $rows    = [];

        if ( empty( $headers ) || ! is_array( $headers ) ) {
            fclose( $handle );
            return [];
        }

        $headers = array_map( [ $this, 'normalize_demo_key' ], $headers );

        while ( ( $data = fgetcsv( $handle, 0, ',', '"', '\\' ) ) !== false ) {
            $row = [];

            foreach ( $headers as $index => $header ) {
                if ( '' === $header ) {
                    continue;
                }

                $row[ $header ] = isset( $data[ $index ] ) && is_scalar( $data[ $index ] ) ? trim( $data[ $index ] ) : '';
            }

            if ( ! empty( $row['title'] ) ) {
                $rows[] = $row;
            }
        }

        fclose( $handle );

        return $rows;
    }

    private function filter_rows_with_local_demo_images( array $rows ) {
        $filtered = [];

        foreach ( $rows as $row ) {
            if ( $this->get_local_demo_image_path( $this->get_demo_value( $row, [ 'image' ] ) ) ) {
                $filtered[] = $row;
            }
        }

        return $filtered;
    }

    private function select_demo_rows( array $rows, array $setup, $limit ) {
        $keywords = array_merge( [ $setup['directory_name'] ], $setup['categories'], [ $setup['default_address'] ] );
        $keywords = $this->extract_keywords( implode( ' ', array_filter( $keywords ) ) );

        foreach ( $rows as $index => $row ) {
            $haystack = strtolower(
                implode(
                    ' ',
                    [
                        $this->get_demo_value( $row, [ 'title' ] ),
                        $this->get_demo_value( $row, [ 'tagline' ] ),
                        $this->get_demo_value( $row, [ 'locations' ] ),
                        $this->get_demo_value( $row, [ 'tags' ] ),
                        $this->get_demo_value( $row, [ 'categories' ] ),
                    ]
                )
            );

            $score = 0;

            foreach ( $keywords as $keyword ) {
                if ( false !== strpos( $haystack, strtolower( $keyword ) ) ) {
                    $score++;
                }
            }

            $rows[ $index ]['_demo_score'] = $score;
            $rows[ $index ]['_demo_order'] = $index;
        }

        usort(
            $rows,
            static function( $a, $b ) {
                if ( $a['_demo_score'] === $b['_demo_score'] ) {
                    return $a['_demo_order'] - $b['_demo_order'];
                }

                return $b['_demo_score'] - $a['_demo_score'];
            }
        );

        return array_slice( $rows, 0, $limit );
    }

    private function create_demo_listing( array $row, array $setup, $directory_id, array $fields, $index ) {
        $title       = $this->get_demo_value( $row, [ 'title' ], $setup['directory_name'] . ' Sample ' . ( $index + 1 ) );
        $description = $this->get_demo_value( $row, [ 'description' ], $title );
        $excerpt     = $this->get_demo_value( $row, [ 'excerpt', 'tagline' ], wp_trim_words( wp_strip_all_tags( $description ), 18, '' ) );

        $post_id = wp_insert_post(
            [
                'post_type'      => ATBDP_POST_TYPE,
                'post_status'    => 'publish',
                'post_title'     => sanitize_text_field( $title ),
                'post_content'   => wp_kses_post( $description ),
                'post_excerpt'   => sanitize_textarea_field( $excerpt ),
                'post_author'    => get_current_user_id(),
                'comment_status' => 'closed',
            ],
            true
        );

        if ( is_wp_error( $post_id ) ) {
            return $post_id;
        }

        update_post_meta( $post_id, '_directory_type', $directory_id );
        update_post_meta( $post_id, '_expiry_date', function_exists( 'calc_listing_expiry_date' ) ? calc_listing_expiry_date( '', '', $directory_id ) : gmdate( 'Y-m-d H:i:s' ) );
        update_post_meta( $post_id, '_featured', 0 );
        update_post_meta( $post_id, '_listing_status', 'post_status' );
        update_post_meta( $post_id, '_atbdp_post_views_count', absint( $this->get_demo_value( $row, [ 'views_count' ], 0 ) ) );

        wp_set_object_terms( $post_id, $directory_id, ATBDP_TYPE );

        $this->assign_listing_terms( $post_id, $this->get_listing_categories( $row, $setup, $index ), ATBDP_CATEGORY, $directory_id );
        $this->assign_listing_terms( $post_id, $this->get_listing_locations( $row, $setup ), ATBDP_LOCATION, $directory_id );
        $this->assign_listing_terms( $post_id, $this->split_demo_values( $this->get_demo_value( $row, [ 'tags' ] ) ), ATBDP_TAGS );

        $image_id = 0;

        foreach ( $fields as $field ) {
            $value = $this->save_demo_field_value( $post_id, $field, $row, $setup, $index );

            if ( ! empty( $value['image_id'] ) ) {
                $image_id = absint( $value['image_id'] );
            }
        }

        return [
            'id'       => $post_id,
            'image_id' => $image_id,
        ];
    }

    private function assign_listing_terms( $post_id, array $terms, $taxonomy, $directory_id = 0 ) {
        $term_ids = [];

        foreach ( $terms as $term_name ) {
            $term_name = sanitize_text_field( $term_name );

            if ( '' === $term_name ) {
                continue;
            }

            $term = get_term_by( 'name', $term_name, $taxonomy );

            if ( $term && ! is_wp_error( $term ) ) {
                $term_id = $term->term_id;
            } else {
                $term = wp_insert_term( $term_name, $taxonomy );

                if ( is_wp_error( $term ) ) {
                    continue;
                }

                $term_id = $term['term_id'];
            }

            if ( $directory_id && in_array( $taxonomy, [ ATBDP_CATEGORY, ATBDP_LOCATION ], true ) ) {
                $directory_types = get_term_meta( $term_id, '_directory_type', true );
                $directory_types = is_array( $directory_types ) ? array_map( 'absint', $directory_types ) : [ absint( $directory_types ) ];
                $directory_types[] = (int) $directory_id;

                update_term_meta( $term_id, '_directory_type', array_values( array_unique( array_filter( $directory_types ) ) ) );
            }

            $term_ids[] = (int) $term_id;
        }

        if ( ! empty( $term_ids ) ) {
            wp_set_object_terms( $post_id, $term_ids, $taxonomy );
        }
    }

    private function save_demo_field_value( $post_id, array $field, array $row, array $setup, $index ) {
        $widget_name = ! empty( $field['widget_name'] ) && is_scalar( $field['widget_name'] ) ? $field['widget_name'] : '';
        $field_key   = ! empty( $field['field_key'] ) && is_scalar( $field['field_key'] ) ? $field['field_key'] : '';

        switch ( $widget_name ) {
            case 'title':
            case 'description':
            case 'category':
            case 'location':
            case 'tag':
            case 'terms_privacy':
                return [];

            case 'tagline':
                update_post_meta( $post_id, '_tagline', sanitize_text_field( $this->get_demo_value( $row, [ 'tagline' ], $setup['directory_name'] ) ) );
                return [];

            case 'excerpt':
                update_post_meta( $post_id, '_excerpt', sanitize_textarea_field( $this->get_demo_value( $row, [ 'excerpt', 'tagline' ] ) ) );
                return [];

            case 'pricing':
                update_post_meta( $post_id, '_atbd_listing_pricing', 'price' );
                update_post_meta( $post_id, '_price', sanitize_text_field( $this->get_demo_value( $row, [ 'price' ], 100 + ( $index * 25 ) ) ) );
                update_post_meta( $post_id, '_price_range', $this->normalize_price_range( $this->get_demo_value( $row, [ 'price_range' ] ) ) );
                return [];

            case 'map':
                update_post_meta( $post_id, '_manual_lat', sanitize_text_field( $this->get_demo_value( $row, [ 'map_latitude' ] ) ) );
                update_post_meta( $post_id, '_manual_lng', sanitize_text_field( $this->get_demo_value( $row, [ 'map_longitude', 'map_logitude' ] ) ) );
                update_post_meta( $post_id, '_hide_map', sanitize_text_field( $this->get_demo_value( $row, [ 'hide_map' ], 0 ) ) );
                return [];

            case 'address':
                update_post_meta( $post_id, '_address', sanitize_text_field( $this->get_demo_address( $row, $setup ) ) );
                return [];

            case 'zip':
                update_post_meta( $post_id, '_zip', sanitize_text_field( $this->get_demo_value( $row, [ 'zip' ] ) ) );
                return [];

            case 'phone':
                update_post_meta( $post_id, '_phone', sanitize_text_field( $this->get_demo_value( $row, [ 'phone' ] ) ) );
                return [];

            case 'phone2':
                update_post_meta( $post_id, '_phone2', sanitize_text_field( $this->get_demo_value( $row, [ 'phone2' ] ) ) );
                return [];

            case 'fax':
                update_post_meta( $post_id, '_fax', sanitize_text_field( $this->get_demo_value( $row, [ 'fax' ] ) ) );
                return [];

            case 'email':
                update_post_meta( $post_id, '_email', sanitize_email( $this->get_demo_value( $row, [ 'email' ] ) ) );
                return [];

            case 'website':
                update_post_meta( $post_id, '_website', esc_url_raw( $this->get_demo_value( $row, [ 'website' ] ) ) );
                return [];

            case 'video':
                update_post_meta( $post_id, '_videourl', esc_url_raw( $this->get_demo_value( $row, [ 'video' ] ) ) );
                return [];

            case 'image_upload':
                return [ 'image_id' => $this->import_demo_image( $post_id, $row ) ];

            case 'social_info':
                update_post_meta(
                    $post_id,
                    '_social',
                    [
                        [
                            'id'  => 'facebook',
                            'url' => 'https://example.com',
                        ],
                    ]
                );
                return [];
        }

        if ( empty( $field_key ) ) {
            return [];
        }

        $value = $this->get_custom_demo_value( $field, $row, $setup, $index );

        if ( '' === $value || [] === $value ) {
            return [];
        }

        update_post_meta( $post_id, '_' . $field_key, $value );

        return [];
    }

    private function get_custom_demo_value( array $field, array $row, array $setup, $index ) {
        $widget_name = ! empty( $field['widget_name'] ) && is_scalar( $field['widget_name'] ) ? $field['widget_name'] : 'text';
        $label       = ! empty( $field['label'] ) && is_scalar( $field['label'] ) ? strtolower( $field['label'] ) : '';

        switch ( $widget_name ) {
            case 'email':
                return sanitize_email( $this->get_demo_value( $row, [ 'email' ], 'example@example.com' ) );

            case 'url':
                return esc_url_raw( $this->get_demo_value( $row, [ 'website' ], 'https://example.com' ) );

            case 'textarea':
            case 'html':
                return wp_kses_post( $this->get_demo_value( $row, [ 'description' ] ) );

            case 'number':
                return $this->get_number_demo_value( $label, $row, $index );

            case 'date':
                return gmdate( 'Y-m-d', strtotime( '+' . ( $index + 1 ) . ' days' ) );

            case 'time':
                return '09:00';

            case 'color_picker':
                return '#2271b1';

            case 'select':
            case 'radio':
                return $this->get_first_field_option( $field );

            case 'checkbox':
                $option = $this->get_first_field_option( $field );
                return $option ? [ $option ] : [];

            case 'button':
                return [
                    'button_text'      => $this->get_button_text( $label ),
                    'button_url_label' => esc_url_raw( $this->get_demo_value( $row, [ 'website' ], 'https://example.com' ) ),
                ];

            case 'file':
                return esc_url_raw( $this->get_demo_value( $row, [ 'website' ], 'https://example.com' ) );
        }

        if ( false !== strpos( $label, 'phone' ) ) {
            return sanitize_text_field( $this->get_demo_value( $row, [ 'phone' ] ) );
        }

        if ( false !== strpos( $label, 'address' ) ) {
            return sanitize_text_field( $this->get_demo_address( $row, $setup ) );
        }

        if ( false !== strpos( $label, 'price' ) || false !== strpos( $label, 'cost' ) ) {
            return sanitize_text_field( $this->get_demo_value( $row, [ 'price' ], 100 + ( $index * 25 ) ) );
        }

        return sanitize_text_field( $this->get_demo_value( $row, [ 'tagline', 'title' ], $setup['directory_name'] ) );
    }

    private function get_number_demo_value( $label, array $row, $index ) {
        if ( preg_match( '/bed(room)?/i', $label ) ) {
            return (string) ( ( $index % 4 ) + 1 );
        }

        if ( preg_match( '/bath(room)?/i', $label ) ) {
            return (string) ( ( $index % 3 ) + 1 );
        }

        if ( preg_match( '/area|sqft|size/i', $label ) ) {
            return (string) ( 800 + ( $index * 150 ) );
        }

        if ( preg_match( '/year/i', $label ) ) {
            return (string) ( (int) gmdate( 'Y' ) - $index );
        }

        if ( preg_match( '/mileage/i', $label ) ) {
            return (string) ( 10000 + ( $index * 5000 ) );
        }

        if ( preg_match( '/price|cost|rent|salary|amount/i', $label ) ) {
            return sanitize_text_field( $this->get_demo_value( $row, [ 'price' ], 100 + ( $index * 25 ) ) );
        }

        return (string) ( $index + 1 );
    }

    private function import_demo_image( $post_id, array $row ) {
        $image = esc_url_raw( $this->get_demo_value( $row, [ 'image' ] ) );

        if ( empty( $image ) ) {
            return 0;
        }

        $local_image = $this->get_local_demo_image_path( $image );

        if ( empty( $local_image ) ) {
            return 0;
        }

        $source_key = 'local:' . wp_basename( $local_image );
        $attachment_id = $this->get_cached_demo_image_id( $source_key );

        if ( $attachment_id ) {
            $this->set_listing_preview_image( $post_id, $attachment_id );
            return $attachment_id;
        }

        $attachment_id = $this->insert_attachment_from_local_demo_image( $local_image, $post_id );

        if ( empty( $attachment_id ) ) {
            return 0;
        }

        $attachment_id = absint( $attachment_id );

        update_post_meta( $attachment_id, '_directorist_ai_setup_demo_image_source', $source_key );
        $this->set_listing_preview_image( $post_id, $attachment_id );

        return $attachment_id;
    }

    private function get_local_demo_image_path( $image ) {
        $path = wp_parse_url( $image, PHP_URL_PATH );
        $filename = sanitize_file_name( wp_basename( $path ? $path : $image ) );

        if ( empty( $filename ) ) {
            return '';
        }

        $local_image = DIRECTORIST_ASSETS_DIR . 'sample-data/images/' . $filename;

        return is_readable( $local_image ) ? $local_image : '';
    }

    private function insert_attachment_from_local_demo_image( $local_image, $post_id ) {
        $upload_dir = wp_upload_dir();

        if ( ! empty( $upload_dir['error'] ) || empty( $upload_dir['path'] ) || empty( $upload_dir['url'] ) ) {
            return 0;
        }

        if ( ! wp_mkdir_p( $upload_dir['path'] ) ) {
            return 0;
        }

        $filename = wp_unique_filename( $upload_dir['path'], wp_basename( $local_image ) );
        $target   = trailingslashit( $upload_dir['path'] ) . $filename;

        if ( ! copy( $local_image, $target ) ) {
            return 0;
        }

        $filetype = wp_check_filetype( $filename );

        if ( empty( $filetype['type'] ) ) {
            wp_delete_file( $target );
            return 0;
        }

        $attachment_id = wp_insert_attachment(
            [
                'post_mime_type' => $filetype['type'],
                'post_title'     => sanitize_text_field( pathinfo( $filename, PATHINFO_FILENAME ) ),
                'post_content'   => '',
                'post_status'    => 'inherit',
                'guid'           => trailingslashit( $upload_dir['url'] ) . $filename,
            ],
            $target,
            $post_id,
            true
        );

        if ( is_wp_error( $attachment_id ) ) {
            wp_delete_file( $target );
            return 0;
        }

        if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
        }

        $metadata = wp_generate_attachment_metadata( $attachment_id, $target );

        if ( ! is_wp_error( $metadata ) ) {
            wp_update_attachment_metadata( $attachment_id, $metadata );
        }

        return absint( $attachment_id );
    }

    private function get_cached_demo_image_id( $source ) {
        $attachments = get_posts(
            [
                'post_type'              => 'attachment',
                'post_status'            => 'inherit',
                'posts_per_page'         => 1,
                'fields'                 => 'ids',
                'no_found_rows'          => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
                'meta_key'               => '_directorist_ai_setup_demo_image_source',
                'meta_value'             => sanitize_text_field( $source ),
            ]
        );

        if ( empty( $attachments[0] ) ) {
            return 0;
        }

        return absint( $attachments[0] );
    }

    private function set_listing_preview_image( $post_id, $attachment_id ) {
        $attachment_id = absint( $attachment_id );

        if ( ! $attachment_id ) {
            return;
        }

        update_post_meta( $post_id, '_listing_prv_img', $attachment_id );
        delete_post_meta( $post_id, '_listing_img' );
        set_post_thumbnail( $post_id, $attachment_id );
    }

    private function get_demo_address( array $row, array $setup ) {
        $address  = $this->get_demo_value( $row, [ 'address' ], 'Sample address' );
        $location = ! empty( $setup['default_address'] ) ? $setup['default_address'] : $this->get_demo_value( $row, [ 'locations' ] );

        return trim( $address . ( $location ? ', ' . $location : '' ), ', ' );
    }

    private function get_listing_categories( array $row, array $setup, $index ) {
        $categories = ! empty( $setup['categories'] ) ? $setup['categories'] : $this->split_demo_values( $this->get_demo_value( $row, [ 'categories' ] ) );

        if ( empty( $categories ) ) {
            return [ __( 'General', 'directorist' ) ];
        }

        return [ $categories[ $index % count( $categories ) ] ];
    }

    private function get_listing_locations( array $row, array $setup ) {
        return $this->split_demo_values( $this->get_demo_value( $row, [ 'locations' ] ) );
    }

    private function split_demo_values( $value ) {
        if ( ! is_scalar( $value ) || '' === trim( $value ) ) {
            return [];
        }

        $items = preg_split( '/[,|]+/', (string) $value );
        $items = array_map( 'trim', $items );

        return array_values( array_filter( $items ) );
    }

    private function get_first_field_option( array $field ) {
        if ( empty( $field['options'] ) || ! is_array( $field['options'] ) ) {
            return '';
        }

        $option = reset( $field['options'] );

        if ( is_array( $option ) ) {
            return isset( $option['option_value'] ) ? sanitize_text_field( $option['option_value'] ) : '';
        }

        return is_scalar( $option ) ? sanitize_text_field( $option ) : '';
    }

    private function get_button_text( $label ) {
        if ( false !== strpos( $label, 'book' ) || false !== strpos( $label, 'appointment' ) ) {
            return __( 'Book Now', 'directorist' );
        }

        if ( false !== strpos( $label, 'apply' ) ) {
            return __( 'Apply Now', 'directorist' );
        }

        return __( 'Visit Now', 'directorist' );
    }

    private function normalize_price_range( $value ) {
        $value = strtolower( trim( (string) $value ) );
        $map   = [
            'ultra high' => 'skimming',
            'high'       => 'skimming',
            'moderate'   => 'moderate',
            'medium'     => 'moderate',
            'economy'    => 'economy',
            'low'        => 'bellow_economy',
            'cheap'      => 'bellow_economy',
        ];

        return isset( $map[ $value ] ) ? $map[ $value ] : 'moderate';
    }

    private function get_demo_value( array $row, array $keys, $default = '' ) {
        foreach ( $keys as $key ) {
            $key = $this->normalize_demo_key( $key );

            if ( isset( $row[ $key ] ) && '' !== trim( (string) $row[ $key ] ) ) {
                return $row[ $key ];
            }
        }

        return $default;
    }

    private function normalize_demo_key( $key ) {
        $key = strtolower( (string) $key );
        $key = preg_replace( '/[^a-z0-9]+/', '_', $key );

        return trim( $key, '_' );
    }

    private function extract_keywords( $text ) {
        $words = preg_split( '/[^a-z0-9]+/i', strtolower( (string) $text ) );
        $words = array_filter(
            $words,
            static function( $word ) {
                return strlen( $word ) > 2 && ! in_array( $word, [ 'directory', 'listing', 'listings', 'board', 'marketplace' ], true );
            }
        );

        return array_values( array_unique( $words ) );
    }

    private function save_setup_options( array $setup ) {
        $options = get_option( 'atbdp_option', [] );

        if ( ! is_array( $options ) ) {
            $options = [];
        }

        $options['enable_monetization'] = ! empty( $setup['monetization'] ) ? 1 : false;
        $options['enable_featured_listing'] = ! empty( $setup['monetization'] ) ? 1 : '';

        if ( ! empty( $setup['default_address'] ) ) {
            $options['default_address'] = $setup['default_address'];

            $coordinates = $this->geocode_default_address( $setup['default_address'] );

            if ( ! empty( $coordinates ) ) {
                $options['default_latitude']  = $coordinates['lat'];
                $options['default_longitude'] = $coordinates['lng'];
            }
        }

        update_option( 'atbdp_option', $options );

        if ( ! empty( $setup['data_sharing'] ) ) {
            ATBDP()->insights->optin();
        } else {
            ATBDP()->insights->optout();
        }
    }

    private function geocode_default_address( $address ) {
        $address = trim( sanitize_text_field( $address ) );

        if ( '' === $address ) {
            return null;
        }

        $filtered_coordinates = apply_filters( 'directorist_ai_setup_wizard_default_address_coordinates', null, $address );

        if ( false === $filtered_coordinates ) {
            return null;
        }

        if ( is_array( $filtered_coordinates ) ) {
            return $this->normalize_coordinates( $filtered_coordinates );
        }

        $coordinates = $this->parse_coordinates_from_address( $address );

        if ( $coordinates ) {
            return $coordinates;
        }

        $response = wp_remote_get(
            add_query_arg(
                [
                    'format' => 'jsonv2',
                    'limit'  => 1,
                    'q'      => $address,
                ],
                'https://nominatim.openstreetmap.org/search'
            ),
            [
                'timeout' => 8,
                'headers' => [
                    'Accept'     => 'application/json',
                    'User-Agent' => 'Directorist AI Setup Wizard/' . ATBDP_VERSION . '; ' . home_url( '/' ),
                ],
            ]
        );

        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            return null;
        }

        $results = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( empty( $results[0]['lat'] ) || empty( $results[0]['lon'] ) ) {
            return null;
        }

        return $this->normalize_coordinates(
            [
                'lat' => $results[0]['lat'],
                'lng' => $results[0]['lon'],
            ]
        );
    }

    private function parse_coordinates_from_address( $address ) {
        if ( ! preg_match( '/^\s*(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)/', (string) $address, $matches ) ) {
            return null;
        }

        return $this->normalize_coordinates(
            [
                'lat' => $matches[1],
                'lng' => $matches[2],
            ]
        );
    }

    private function normalize_coordinates( array $coordinates ) {
        $lat = isset( $coordinates['lat'] ) ? $coordinates['lat'] : ( $coordinates['latitude'] ?? null );
        $lng = isset( $coordinates['lng'] ) ? $coordinates['lng'] : ( $coordinates['lon'] ?? ( $coordinates['longitude'] ?? null ) );

        if ( null === $lat || null === $lng || ! is_numeric( $lat ) || ! is_numeric( $lng ) ) {
            return null;
        }

        $lat = (float) $lat;
        $lng = (float) $lng;

        if ( $lat < -90 || $lat > 90 || $lng < -180 || $lng > 180 ) {
            return null;
        }

        return [
            'lat' => (string) $lat,
            'lng' => (string) $lng,
        ];
    }

    private function unique_directory_name( $name ) {
        $name = sanitize_text_field( $name );

        if ( ! term_exists( $name, ATBDP_TYPE ) ) {
            return $name;
        }

        $base = $name;
        $index = 2;

        while ( term_exists( $name, ATBDP_TYPE ) ) {
            $name = $base . ' ' . $index;
            $index++;
        }

        return $name;
    }
}

new Directorist_AI_Setup_Wizard();
