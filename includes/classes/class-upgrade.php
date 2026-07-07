<?php

use Directorist\Core\API;
use Directorist\Multi_Directory\Multi_Directory_Manager;

// it handles directorist upgrade
class ATBDP_Upgrade
{
    public $upgrade_notice_id = 'migrate_to_7';

    public $legacy_notice_id = 'directorist_legacy_template';

    public $directorist_notices = [];

    public $directorist_migration = [];

    public $deprecated_extensions = [
        'directorist-pricing-plans/directorist-pricing-plans.php'                     => '4.0.0',
        'directorist-stripe/directorist-stripe.php'                                   => '3.0.0',
        'directorist-paypal/directorist-paypal.php'                                   => '3.0.0',
        'directorist-authorize-net/directorist-authorize-net.php'                     => '3.0.0',
        'directorist-coupon/directorist-coupon.php'                                   => '2.5.0',
        'directorist-booking/directorist-booking.php'                                 => '3.1.0',
        'directorist-claim-listing/directorist-claim-listing.php'                     => '2.6.0',
        'directorist-mark-as-sold/directorist-mark-as-sold.php'                       => '2.3.0',
        'directorist-business-hours/bd-business-hour.php'                             => '3.8.0',
        'directorist-job-manager/directorist-job-manager.php'                         => '2.3.0',
        'directorist-faqs/directorist-faqs.php'                                       => '3.3.0',
        'directorist-live-chat/directorist-live-chat.php'                             => '2.5.0',
        'directorist-woocommerce-pricing-plans/directorist-woocommerce-pricing-plans' => '3.7.0',
    ];

    public $deprecated_themes = [
        'dcar'           => '2.0.19',
        'dclassified'    => '2.0.19',
        'ddoctors'       => '2.0.18',
        'dhotels'        => '2.0.16',
        'djobs'          => '2.0.15',
        'dlawyers'       => '2.0.15',
        'drealestate'    => '2.0.16',
        'drestaurant'    => '2.0.15',
        'onelisting-pro' => '2.0.23',
        'onelisting'     => '2.0.23',
    ];

    public function __construct() {
        if ( ! is_admin() ) return;

        add_action( 'admin_init', [$this, 'configure_notices'] );

        add_action( 'directorist_search_setting_sections', [$this, 'support_themes_hook'] );

        //add_action( 'admin_notices', [$this, 'upgrade_notice'], 100 );

        add_action( 'directorist_before_settings_panel_header', [$this, 'promo_banner'] );

        add_action( 'directorist_before_all_directory_types', [$this, 'promo_banner'] );

        add_action( 'admin_notices', [ $this, 'bfcm_notice'] );

        add_action( 'admin_notices', [ $this, 'deprecated_item_upgrade_notice' ], 100 );

        add_action( 'admin_init', [ $this, 'v8_force_migration' ] );

        // Migrate assign_to to conditional logic (custom fields only)
        add_action( 'admin_init', [ $this, 'migrate_assign_to_conditional_logic' ] );

        add_action( 'admin_notices', [ $this, 'dashboard_upgrade_notice' ] );
    }

    public function dashboard_upgrade_notice() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! function_exists( 'get_current_screen' ) ) {
            return;
        }

        $screen = get_current_screen();

        if ( ! $screen || 'dashboard' !== $screen->base ) {
            return;
        }

        ATBDP()->load_template( 'admin-templates/admin-dashboard-notice' );
    }

     /**
     * Migrate assign_to category fields to conditional logic
     *
     * @return void
     */
    public function migrate_assign_to_conditional_logic() {
        // Security: Check capability
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Optimization: Check migration flag first (fastest check)
        if ( get_option( 'directorist_assign_to_conditional_logic_migrated', false ) ) {
            return;
        }

        $directory_types = get_terms(
            array(
                'taxonomy'   => ATBDP_DIRECTORY_TYPE,
                'hide_empty' => false,
            )
        );

        if ( is_wp_error( $directory_types ) || empty( $directory_types ) ) {
            update_option( 'directorist_assign_to_conditional_logic_migrated', true );
            return;
        }

        foreach ( $directory_types as $directory_type ) {
            if ( ! is_object( $directory_type ) || ! isset( $directory_type->term_id ) ) {
                continue;
            }
            $this->migrate_directory_assign_to_fields( $directory_type->term_id );
        }

        update_option( 'directorist_assign_to_conditional_logic_migrated', true );
    }

    /**
     * Migrate assign_to fields for a specific directory type
     *
     * @param int $directory_id Directory type term ID
     * @return void
     */
    private function migrate_directory_assign_to_fields( $directory_id ) {
        $directory_id = absint( $directory_id );

        if ( empty( $directory_id ) ) {
            return;
        }

        $submission_form_fields = get_term_meta( $directory_id, 'submission_form_fields', true );
        $search_form_fields     = get_term_meta( $directory_id, 'search_form_fields', true );

        if ( ! is_array( $submission_form_fields ) ) {
            $submission_form_fields = array();
        }

        if ( ! is_array( $search_form_fields ) ) {
            $search_form_fields = array();
        }

        $migrated_fields   = array();
        $submission_updated = false;
        $search_updated     = false;

        // Step 1: Migrate submission form custom fields.
        if ( ! empty( $submission_form_fields['fields'] ) && is_array( $submission_form_fields['fields'] ) ) {
            foreach ( $submission_form_fields['fields'] as $field_key => $field ) {
                if ( ! is_array( $field ) || ! $this->is_custom_field_for_migration( $field ) ) {
                    continue;
                }

                $existing_logic = $this->get_existing_conditional_logic( $field );
                if ( ! empty( $existing_logic['enabled'] ) && ! empty( $existing_logic['groups'] ) ) {
                    continue;
                }

                if ( empty( $field['assign_to'] ) || empty( $field['category'] ) ) {
                    continue;
                }

                $category_id = is_numeric( $field['category'] ) ? absint( $field['category'] ) : 0;
                if ( empty( $category_id ) ) {
                    continue;
                }

                $conditional_logic = $this->convert_assign_to_to_conditional_logic( $category_id, 'submission' );
                if ( empty( $conditional_logic ) || ! is_array( $conditional_logic ) ) {
                    continue;
                }

                // Cleanup broken old migration shape for option-list fields.
                if ( ! empty( $field['options'] ) && is_array( $field['options'] ) && isset( $field['options'][0] ) && isset( $field['options']['conditional_logic'] ) ) {
                    unset( $field['options']['conditional_logic'] );
                }

                // Keep conditional logic at root level (safe for all field structures).
                $field['conditional_logic'] = $conditional_logic;

                $submission_form_fields['fields'][ $field_key ] = $field;
                $migrated_fields[ $field_key ]                  = $category_id;
                $submission_updated                              = true;
            }

            if ( $submission_updated ) {
                update_term_meta( $directory_id, 'submission_form_fields', $submission_form_fields );
            }
        }

        // Step 2: Sync migrated submission custom fields to search custom fields.
        if ( ! empty( $migrated_fields ) && ! empty( $search_form_fields['fields'] ) && is_array( $search_form_fields['fields'] ) ) {
            foreach ( $search_form_fields['fields'] as $search_field_key => $search_field ) {
                if ( ! is_array( $search_field ) || ! $this->is_custom_field_for_migration( $search_field ) ) {
                    continue;
                }

                $existing_logic = $this->get_existing_conditional_logic( $search_field );
                if ( ! empty( $existing_logic['enabled'] ) && ! empty( $existing_logic['groups'] ) ) {
                    continue;
                }

                $form_key = ( isset( $search_field['original_widget_key'] ) && is_string( $search_field['original_widget_key'] ) ) ? $search_field['original_widget_key'] : '';
                if ( '' === $form_key || ! isset( $migrated_fields[ $form_key ] ) ) {
                    continue;
                }

                $category_id = absint( $migrated_fields[ $form_key ] );
                if ( empty( $category_id ) ) {
                    continue;
                }

                $search_conditional_logic = $this->convert_assign_to_to_conditional_logic( $category_id, 'search' );
                if ( empty( $search_conditional_logic ) || ! is_array( $search_conditional_logic ) ) {
                    continue;
                }

                // Cleanup broken old migration shape for option-list fields.
                if ( ! empty( $search_field['options'] ) && is_array( $search_field['options'] ) && isset( $search_field['options'][0] ) && isset( $search_field['options']['conditional_logic'] ) ) {
                    unset( $search_field['options']['conditional_logic'] );
                }

                $search_field['conditional_logic'] = $search_conditional_logic;

                $search_form_fields['fields'][ $search_field_key ] = $search_field;
                $search_updated                                      = true;
            }

            if ( $search_updated ) {
                update_term_meta( $directory_id, 'search_form_fields', $search_form_fields );
            }
        }

        // Step 3: Backward compatibility - migrate search fields that still have own assign_to.
        if ( ! empty( $search_form_fields['fields'] ) && is_array( $search_form_fields['fields'] ) ) {
            $updated = $this->migrate_form_fields( $search_form_fields['fields'], 'search' );
            if ( $updated ) {
                update_term_meta( $directory_id, 'search_form_fields', $search_form_fields );
            }
        }
    }

    /**
     * Migrate assign_to fields in a fields array
     *
     * @param array  $fields    Fields array (passed by reference)
     * @param string $form_type Form type: 'submission' or 'search'
     * @return bool True if any field was updated, false otherwise
     */
    private function migrate_form_fields( &$fields, $form_type = 'submission' ) {
        if ( ! is_array( $fields ) ) {
            return false;
        }

        $updated = false;

        foreach ( $fields as $field_key => $field ) {
            if ( ! is_array( $field ) || ! $this->is_custom_field_for_migration( $field ) ) {
                continue;
            }

            $existing_logic = $this->get_existing_conditional_logic( $field );
            if ( ! empty( $existing_logic['enabled'] ) && ! empty( $existing_logic['groups'] ) ) {
                continue;
            }

            if ( empty( $field['assign_to'] ) || empty( $field['category'] ) ) {
                continue;
            }

            $category_id = is_numeric( $field['category'] ) ? absint( $field['category'] ) : 0;
            if ( empty( $category_id ) ) {
                continue;
            }

            $conditional_logic = $this->convert_assign_to_to_conditional_logic( $category_id, $form_type );
            if ( empty( $conditional_logic ) || ! is_array( $conditional_logic ) ) {
                continue;
            }

            // Cleanup broken old migration shape for option-list fields.
            if ( ! empty( $field['options'] ) && is_array( $field['options'] ) && isset( $field['options'][0] ) && isset( $field['options']['conditional_logic'] ) ) {
                unset( $field['options']['conditional_logic'] );
            }

            $field['conditional_logic'] = $conditional_logic;

            $fields[ $field_key ] = $field;
            $updated = true;
        }

        return $updated;
    }

    /**
     * Convert assign_to category value to conditional logic format
     *
     * Supports single category only. If multiple categories provided, uses the first one.
     *
     * @param int $category_id Category ID (must be validated integer)
     * @param string $form_type Form type: 'submission' or 'search'
     * @return array|null Conditional logic array or null if invalid
     */
    private function convert_assign_to_to_conditional_logic( $category_id, $form_type = 'submission' ) {
        // Security: Validate input
        $category_id = absint( $category_id );
        if ( empty( $category_id ) ) {
            return null;
        }

        // Security: Verify category exists and belongs to correct taxonomy
        $category = get_term( $category_id, ATBDP_CATEGORY );
        if ( is_wp_error( $category ) || empty( $category ) || ! is_object( $category ) ) {
            return null;
        }

        // Security: Validate form_type
        $form_type = ( $form_type === 'search' ) ? 'search' : 'submission';

        // Use correct field key based on form type
        // Listing form: 'admin_category_select[]' (for builder compatibility)
        // Search form: 'category' (as SearchForm normalizes it)
        $field_key = ( $form_type === 'submission' ) ? 'admin_category_select[]' : 'category';

        // Build conditional logic structure for single category
        return array(
            'enabled'        => true,
            'action'         => 'show',
            'globalOperator' => 'OR',
            'groups'         => array(
                array(
                    'operator'   => 'AND',
                    'conditions' => array(
                        array(
                            'field'    => $field_key,
                            'operator' => 'contains',
                            'value'    => (string) $category_id,
                        ),
                    ),
                ),
            ),
        );
    }

    /**
     * Check if field is a custom field for migration
     *
     * @param array $field Field data array
     * @return bool True if custom field, false otherwise
     */
    private function is_custom_field_for_migration( $field ) {
        if ( ! is_array( $field ) ) {
            return false;
        }

        // Security: Validate widget_group
        if ( ! empty( $field['widget_group'] ) && is_string( $field['widget_group'] ) && $field['widget_group'] === 'custom' ) {
            return true;
        }

        // Optimization: Use strict comparison and validate widget_name
        $custom_field_types = array( 'checkbox', 'color_picker', 'date', 'file', 'number', 'radio', 'select', 'text', 'textarea', 'time', 'url' );
        if ( ! empty( $field['widget_name'] ) && is_string( $field['widget_name'] ) && in_array( $field['widget_name'], $custom_field_types, true ) ) {
            return true;
        }

        // Optimization: Check if field_key starts with 'custom-' (prefix check)
        if ( ! empty( $field['field_key'] ) && is_string( $field['field_key'] ) && strpos( $field['field_key'], 'custom-' ) === 0 ) {
            return true;
        }

        return false;
    }

    /**
     * Get conditional logic from field safely.
     *
     * @param array $field Field data.
     * @return array|null
     */
    private function get_existing_conditional_logic( $field ) {
        if ( ! is_array( $field ) ) {
            return null;
        }

        if ( ! empty( $field['conditional_logic'] ) && is_array( $field['conditional_logic'] ) ) {
            return $field['conditional_logic'];
        }

        if ( ! empty( $field['options'] )
            && is_array( $field['options'] )
            && ! empty( $field['options']['conditional_logic']['value'] )
            && is_array( $field['options']['conditional_logic']['value'] )
        ) {
            return $field['options']['conditional_logic']['value'];
        }

        return null;
    }

    public function v8_force_migration() {

        if ( get_option( 'directorist_v8_force_migrated' ) ) {
            return;
        }

        $listings = wp_count_posts( 'at_biz_dir' )->publish;

        if ( empty( $listings ) ) {
            update_option( 'directorist_v8_force_migrated', true );
            return;
        }

        $this->run_v8_migration();

        update_option( 'directorist_v8_force_migrated', true );
    }

    public function run_v8_migration() {

        //create account page
        $options = get_option( 'atbdp_option' );
        $account = wp_insert_post(
            [
                'post_title'     => 'Sign In',
                'post_content'   => '[directorist_signin_signup]',
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'comment_status' => 'closed'
            ]
        );

        if ( $account ) {
            $options['signin_signup_page']      = (int) $account;
            $options['marker_shape_color']      = '#444752';
            $options['marker_icon_color']       = '#ffffff';
            $options['all_listing_layout']      = 'no_sidebar';
            $options['search_result_layout']    = 'no_sidebar';

            update_option( 'atbdp_option', $options );
        }
        // need to update adons kit for elementor
        $path = WP_PLUGIN_DIR . '/addonskit-for-elementor/addonskit-for-elementor.php';

        if ( did_action( 'elementor/loaded' ) && ! file_exists( $path ) ) {

            directorist_download_plugin( [ 'url' => 'https://downloads.wordpress.org/plugin/addonskit-for-elementor.zip' ] );

            if ( ! is_plugin_active( $path ) ) {
                activate_plugin( $path );
            }
        }

        $directory_types = get_terms(
            [
                'taxonomy'   => ATBDP_DIRECTORY_TYPE,
                'hide_empty' => false,
            ]
        );


        if ( is_wp_error( $directory_types ) || empty( $directory_types ) ) {
            return;
        }

        foreach ( $directory_types as $directory_type ) {

            $this->search_field_label_migration( $directory_type->term_id );

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

            // Initialize contents if empty
            if ( empty( $contents ) ) {
                $contents = [
                    'fields' => [],
                    'groups' => [],
                ];
            }

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
                                            'label' => __( "Tagline", "directorist" ),
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
    }

    private function search_field_label_migration( $directory_id ) {
        $search_fields = get_term_meta( $directory_id, 'search_form_fields', true );
        $search_fields = is_array( $search_fields ) ? $search_fields : [];
        $fields        = isset( $search_fields['fields'] ) && is_array( $search_fields['fields'] ) ? $search_fields['fields'] : [];

        foreach ( $fields as $key => $field ) {
            $placeholder    = empty( $field['placeholder'] ) ? '' : $field['placeholder'];
            $label          = empty( $field['label'] ) ? $placeholder : $field['label'];
            $field['label'] = $label;

            $search_fields['fields'][ $key ] = $field;
        }

        if ( ! empty( $search_fields ) ) {
            update_term_meta( $directory_id, 'search_form_fields', $search_fields );
        }
    }

    public function support_themes_hook( $data ) {
        $theme = wp_get_theme( is_child_theme() ? get_template() : '' );

        // Check if theme author is 'wpWax'
        if ( $theme->display( 'Author', false ) === 'wpWax' ) {

            if ( ( 'Pixetiq' !== $theme['Name'] ) && ( version_compare( 2, $theme['Version'], '>' ) ) ) {
                $data['search_form'] = [
                    'fields' => [],
                ];
            }

            if ( ( ( 'Direo' === $theme['Name'] ) || ( 'dList' === $theme['Name'] ) ) && ( version_compare( 3, $theme['Version'], '>' ) ) ) {
                $data['search_form'] = [
                    'fields' => [],
                ];
            }
        }

        return $data;
    }

    public function is_pro_user() {
        $plugin = get_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions', true );
        $theme  = get_user_meta( get_current_user_id(), '_themes_available_in_subscriptions', true );

        if ( $plugin || $theme ) {
            return true;
        } else {
            return false;
        }
    }

    public function promo_banner() {
        if ( self::can_manage_plugins() && ! self::is_pro_user() ) {
            ATBDP()->load_template( 'admin-templates/admin-promo-banner' );
        }
    }

    protected static function can_manage_plugins() {
        return ( current_user_can( 'install_plugins' ) || current_user_can( 'manage_options' ) );
    }

    public function deprecated_item_upgrade_notice() {
        if ( ! self::can_manage_plugins() ) {
            return;
        }

        $deprecated_items = $this->get_deprecated_items();

        if ( empty( $deprecated_items ) ) {
            return;
        }

        $is_multiple = count( $deprecated_items ) > 1;
        $title       = $is_multiple ? __( 'Deprecated Directorist items require upgrades', 'directorist' ) : __( 'Deprecated Directorist item requires an upgrade', 'directorist' );
        $description = $is_multiple
            ? __( 'Directorist detected deprecated themes or extensions that are not compatible with this version. Upgrade them to the compatible versions listed below.', 'directorist' )
            : __( 'Directorist detected a deprecated theme or extension that is not compatible with this version. Upgrade it to the compatible version listed below.', 'directorist' );
        $toggle_id   = wp_unique_id( 'directorist-deprecated-items-' );
        $content_id  = $toggle_id . '-content';
        $has_extensions = false;
        $has_themes     = false;

        foreach ( $deprecated_items as $deprecated_item ) {
            if ( 'extension' === $deprecated_item['type'] ) {
                $has_extensions = true;
            }

            if ( 'theme' === $deprecated_item['type'] ) {
                $has_themes = true;
            }
        }

        ?>
        <div class="notice notice-warning directorist-deprecated-item-notice">
            <style>
                .directorist-deprecated-item-toggle {
                    margin: 0;
                }

                .directorist-deprecated-item-actions {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 8px;
                    align-items: center;
                    margin: 0;
                }

                .directorist-deprecated-item-table-wrap {
                    max-height: 0;
                    margin-top: 0;
                    overflow: hidden;
                    opacity: 0;
                    transition: max-height 0.3s ease, opacity 0.3s ease, margin-top 0.3s ease;
                }

                .directorist-deprecated-item-table-wrap.is-open {
                    margin-top: 12px;
                    opacity: 1;
                }

                .directorist-deprecated-item-table {
                    width: 100%;
                    margin: 0 0 12px;
                    border: 1px solid #c3c4c7;
                    border-spacing: 0;
                    background: #fff;
                }

                .directorist-deprecated-item-table th,
                .directorist-deprecated-item-table td {
                    padding: 8px 10px;
                    border-bottom: 1px solid #c3c4c7;
                    text-align: left;
                }

                .directorist-deprecated-item-table th {
                    font-weight: 600;
                }

                .directorist-deprecated-item-table tr:nth-child(odd) td {
                    background: #f6f7f7;
                }
            </style>
            <p><strong><?php echo esc_html( $title ); ?></strong></p>
            <p><?php echo esc_html( $description ); ?></p>
            <p class="directorist-deprecated-item-actions">
                <button
                    type="button"
                    class="button button-secondary directorist-deprecated-item-toggle"
                    data-directorist-toggle-target="<?php echo esc_attr( $content_id ); ?>"
                    aria-expanded="false"
                    aria-controls="<?php echo esc_attr( $content_id ); ?>"
                >
                    <?php esc_html_e( 'Show deprecated items', 'directorist' ); ?>
                </button>
                <?php if ( $has_extensions && ! $this->is_plugins_screen() ) : ?>
                    <a class="button button-secondary" href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>">
                        <?php esc_html_e( 'Manage Plugins', 'directorist' ); ?>
                    </a>
                <?php endif; ?>
                <?php if ( $has_themes && ! $this->is_themes_screen() ) : ?>
                    <a class="button button-secondary" href="<?php echo esc_url( admin_url( 'themes.php' ) ); ?>">
                        <?php esc_html_e( 'Manage Themes', 'directorist' ); ?>
                    </a>
                <?php endif; ?>
            </p>
            <div
                id="<?php echo esc_attr( $content_id ); ?>"
                class="directorist-deprecated-item-table-wrap"
                hidden
            >
                <table class="directorist-deprecated-item-table">
                    <thead>
                        <tr>
                            <th scope="col"><?php esc_html_e( 'Type', 'directorist' ); ?></th>
                            <th scope="col"><?php esc_html_e( 'Item', 'directorist' ); ?></th>
                            <th scope="col"><?php esc_html_e( 'Installed Version', 'directorist' ); ?></th>
                            <th scope="col"><?php esc_html_e( 'Required Version', 'directorist' ); ?></th>
                            <th scope="col"><?php esc_html_e( 'Status', 'directorist' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $deprecated_items as $deprecated_item ) : ?>
                            <tr>
                                <td><?php echo esc_html( $deprecated_item['type_label'] ); ?></td>
                                <td><strong><?php echo esc_html( $deprecated_item['name'] ); ?></strong></td>
                                <td><?php echo esc_html( $deprecated_item['current_version'] ); ?></td>
                                <td>
                                    <?php
                                    if ( 'theme' === $deprecated_item['type'] ) {
                                        printf(
                                            /* translators: %s: required theme version. */
                                            esc_html__( 'Grater then %s', 'directorist' ),
                                            esc_html( $deprecated_item['required_version'] )
                                        );
                                    } else {
                                        printf(
                                            /* translators: %s: minimum compatible extension version. */
                                            esc_html__( '%s or later', 'directorist' ),
                                            esc_html( $deprecated_item['required_version'] )
                                        );
                                    }
                                    ?>
                                </td>
                                <td><?php echo esc_html( $deprecated_item['status'] ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <script>
                ( function() {
                    const toggleButton = document.querySelector( '[data-directorist-toggle-target="<?php echo esc_js( $content_id ); ?>"]' );

                    if ( ! toggleButton ) {
                        return;
                    }

                    const tableWrap = document.getElementById( toggleButton.getAttribute( 'data-directorist-toggle-target' ) );

                    if ( ! tableWrap ) {
                        return;
                    }

                    const openLabel = '<?php echo esc_js( __( 'Show deprecated items', 'directorist' ) ); ?>';
                    const closeLabel = '<?php echo esc_js( __( 'Hide deprecated items', 'directorist' ) ); ?>';

                    toggleButton.addEventListener( 'click', function() {
                        const isOpen = 'true' === toggleButton.getAttribute( 'aria-expanded' );

                        if ( isOpen ) {
                            tableWrap.style.maxHeight = tableWrap.scrollHeight + 'px';

                            window.requestAnimationFrame( function() {
                                tableWrap.classList.remove( 'is-open' );
                                tableWrap.style.maxHeight = '0px';
                            } );

                            toggleButton.setAttribute( 'aria-expanded', 'false' );
                            toggleButton.textContent = openLabel;

                            window.setTimeout( function() {
                                if ( 'false' === toggleButton.getAttribute( 'aria-expanded' ) ) {
                                    tableWrap.hidden = true;
                                    tableWrap.style.maxHeight = '';
                                }
                            }, 300 );

                            return;
                        }

                        tableWrap.hidden = false;
                        tableWrap.style.maxHeight = '0px';

                        window.requestAnimationFrame( function() {
                            tableWrap.classList.add( 'is-open' );
                            tableWrap.style.maxHeight = tableWrap.scrollHeight + 'px';
                        } );

                        toggleButton.setAttribute( 'aria-expanded', 'true' );
                        toggleButton.textContent = closeLabel;
                    } );
                }() );
            </script>
        </div>
        <?php
    }

    public function get_deprecated_items() {
        $deprecated_items = array_merge(
            $this->get_deprecated_extensions(),
            $this->get_deprecated_themes()
        );

        return apply_filters( 'directorist_deprecated_items', $deprecated_items, $this->deprecated_extensions, $this->deprecated_themes );
    }

    public function get_deprecated_extensions() {
        if ( ! function_exists( 'get_plugins' ) || ! function_exists( 'is_plugin_active' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugins_data        = get_plugins();
        $outdated_extensions = [];

        foreach ( $this->deprecated_extensions as $plugin_file => $required_version ) {
            if ( empty( $plugins_data[ $plugin_file ] ) ) {
                continue;
            }

            $plugin_data     = $plugins_data[ $plugin_file ];
            $current_version = isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : '';

            if ( empty( $current_version ) || ! version_compare( $current_version, $required_version, '<' ) ) {
                continue;
            }

            $outdated_extensions[ $plugin_file ] = [
                'type'             => 'extension',
                'type_label'       => __( 'Extension', 'directorist' ),
                'name'             => ! empty( $plugin_data['Name'] ) ? $plugin_data['Name'] : $plugin_file,
                'current_version'  => $current_version,
                'required_version' => $required_version,
                'status'           => $this->get_plugin_status_label( $plugin_file ),
            ];
        }

        return apply_filters( 'directorist_deprecated_extensions', $outdated_extensions, $plugins_data, $this->deprecated_extensions );
    }

    public function get_deprecated_themes() {
        $installed_themes = wp_get_themes();
        $outdated_themes  = [];

        foreach ( $this->deprecated_themes as $theme_slug => $required_version ) {
            if ( empty( $installed_themes[ $theme_slug ] ) ) {
                continue;
            }

            $theme           = $installed_themes[ $theme_slug ];
            $current_version = $theme->get( 'Version' );

            if ( empty( $current_version ) || ! version_compare( $current_version, $required_version, '<=' ) ) {
                continue;
            }

            $outdated_themes[ $theme_slug ] = [
                'type'             => 'theme',
                'type_label'       => __( 'Theme', 'directorist' ),
                'name'             => $theme->get( 'Name' ) ? $theme->get( 'Name' ) : $theme_slug,
                'current_version'  => $current_version,
                'required_version' => $required_version,
                'status'           => $this->get_theme_status_label( $theme_slug ),
            ];
        }

        return apply_filters( 'directorist_deprecated_themes', $outdated_themes, $installed_themes, $this->deprecated_themes );
    }

    private function get_plugin_status_label( $plugin_file ) {
        if ( is_plugin_active( $plugin_file ) ) {
            return __( 'Active', 'directorist' );
        }

        if ( function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( $plugin_file ) ) {
            return __( 'Network active', 'directorist' );
        }

        return __( 'Installed', 'directorist' );
    }

    private function get_theme_status_label( $theme_slug ) {
        if ( $theme_slug === get_stylesheet() || $theme_slug === get_template() ) {
            return __( 'Active', 'directorist' );
        }

        return __( 'Installed', 'directorist' );
    }

    private function is_plugins_screen() {
        global $pagenow;

        if ( 'plugins.php' === $pagenow ) {
            return true;
        }

        if ( ! function_exists( 'get_current_screen' ) ) {
            return false;
        }

        $screen = get_current_screen();

        return ! empty( $screen->base ) && 'plugins' === $screen->base;
    }

    private function is_themes_screen() {
        global $pagenow;

        if ( 'themes.php' === $pagenow ) {
            return true;
        }

        if ( ! function_exists( 'get_current_screen' ) ) {
            return false;
        }

        $screen = get_current_screen();

        return ! empty( $screen->base ) && 'themes' === $screen->base;
    }

    public function bfcm_notice() {
        if ( ! self::can_manage_plugins() || self::is_pro_user() ) {
            return;
        }

        $response_body  = self::promo_remote_get();
        $display        = ! empty( $response_body->promo_2_display ) ? $response_body->promo_2_display : '';
        $text           = ! empty( $response_body->promo_2_text ) ? $response_body->promo_2_text : '';
        $version        = ! empty( $response_body->promo_2_version ) ? $response_body->promo_2_version : '';
        $link           = ! empty( $response_body->get_now_button_link ) ? self::promo_link( $response_body->get_now_button_link ) : '';

        $closed_version = get_user_meta( get_current_user_id(), 'directorist_promo2_closed_version', true );

        if ( ! $display || $version == $closed_version || ! $text ) {
            return;
        }

        $text = str_replace( '{{link}}', $link, $text );

        $dismiss_url = add_query_arg(
            [
                'directorist_promo2_closed_version' => $version,
                'directorist_promo_nonce'          => wp_create_nonce( 'directorist_promo_nonce' ),
            ],
            atbdp_get_current_url()
        );

        $notice = '<div class="notice notice-info is-dismissible"><p style="font-size: 16px;">' . $text . '</p><a href="' . esc_url( $dismiss_url ) . '" class="notice-dismiss" style="text-decoration: none;"><span class="screen-reader-text">' . __( 'Dismiss this notice.', 'directorist' ) . '</span></a></div>';

        echo wp_kses_post( $notice );
    }

    public static function promo_remote_get() {
        return API::get_promotion();
    }

    public static function dashboard_promo_remote_get() {
        return API::get_dashboard_promo();
    }

    public function upgrade_notice() {
        if ( ! self::can_manage_plugins() ) {
            return;
        }

        // v8.0 compatibility notice
        // theme check
        $theme = wp_get_theme( is_child_theme() ? get_template() : '' );
        if ( ( $theme->display( 'Author', FALSE ) === 'wpWax' ) && ( version_compare( 2, $theme['Version'], '>' ) ) && ( 'Pixetiq' !== $theme['Name'] ) ) {
            // show theme
            $this->v8_theme_upgrade_notice( $theme );
        }

        // extension check
        $plugins_data        = get_plugins();
        $outdated_extensions = [];

        foreach ( ATBDP_Extensions::get_default_extensions() as $slug => $extension ) {
            $extension_base = $extension['base'] ?? $slug . '/' . $slug . '.php';

            if ( isset( $plugins_data[ $extension_base ] ) && version_compare( 2, $plugins_data[ $extension_base ]['Version'], '>' ) ) {
                $outdated_extensions[ $extension_base ] = $plugins_data[ $extension_base ]['Name'];
            }
        }

        if ( ! empty( $outdated_extensions ) ) {
            $this->v8_extension_upgrade_notice( $outdated_extensions );
        }
    }

    public function v8_extension_upgrade_notice( $outdated_extensions = [] ) {
        if ( ! self::can_manage_plugins() ) {
            return;
        }

        $text                   = '';
        $link                   = 'https://directorist.com/blog/directorist-version-8-0/';
        $wp_rollback            = admin_url( 'plugin-install.php?s=rollback&tab=search&type=term' );
        $extension_update_links = [];

        foreach ( $outdated_extensions as $extension_base => $extension ) {
            $extension_update_links[] = '- <a class="directorist-update-extension" href="' . $extension_base . '">' . $extension . '</a>';
        }

        $text .= sprintf(
            __( '<p class="directorist__notice_new"><span style="font-size: 16px;">📣 Directorist Extension Compatibility Notice!</span><br/> Congratulations and welcome to Directorist v8.0 with <a href="%s" target="_blank">some cool new features</a>. You are using <b style="color:#dba617">%s outdated extension(s)</b> which are incompatible with Directorist v8.0. Please update the following outdated extensions:<br>%s</p><br>', 'directorist' ),
            $link,
            count( $outdated_extensions ),
            implode( '<br>', $extension_update_links )
        );

        $text .= sprintf(
            __( '<p class="directorist__notice_new_action">Mistakenly updated? Use <a target="_blank" href="%s">WP Rollback</a> to install your old Directorist</p>', 'directorist' ),
            $wp_rollback
        );

        $notice = '<div class="notice notice-warning is-dismissible directorist-plugin-updater-notice" style="padding-top: 5px;padding-bottom: 5px;">' . $text . '</div>';
        $notice_script = <<<SCRIPT
		<script>
		document.querySelectorAll('.directorist-update-extension').forEach(anchor => {
			anchor.addEventListener('click', function (e) {
				e.preventDefault();

				document.querySelector('[data-plugin="'+ this.getAttribute('href') +'"]').scrollIntoView({
					behavior: 'smooth'
				});
			});
		});
		</script>
SCRIPT;
        echo wp_kses_post( $notice );
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Admin-only inline JS for smooth scroll to extension updates. No user input.
        echo $notice_script;
    }

    public function v8_theme_upgrade_notice( $theme ) {
        if ( ! self::can_manage_plugins() ) {
            return;
        }

        $text = '';
        $link = 'https://directorist.com/blog/directorist-version-8-0/';
        $membership_page = admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-extension' );

        $wp_rollback = admin_url( 'plugin-install.php?s=rollback&tab=search&type=term' );

        $text .= sprintf( __( '<p class="directorist__notice_new"><span style="font-size: 16px;">📣 Directorist Theme Compatibility Notice!</span><br/> Congratulations and welcome to Directorist v8.0 with some cool <a href="%s" target="_blank">new features</a>.Please update <a target="_blank" href="%s">%s theme</a> </p>', 'directorist' ), $link, $membership_page, $theme['Name'] );

        $text .= sprintf(
            __( '<p class="directorist__notice_new_action">Mistakenly updated? Use <a target="_blank" href="%s">WP Rollback</a> to install your old Directorist</p>', 'directorist' ),
            $wp_rollback
        );

        $notice = '<div class="notice notice-warning is-dismissible directorist-plugin-updater-notice" style="padding-top: 5px;padding-bottom: 5px;">' . $text . '</div>';

        echo wp_kses_post( $notice );
    }

    public function configure_notices() {
        if ( ! self::can_manage_plugins() ) {
            return;
        }

        $this->directorist_notices      = get_option( 'directorist_notices' );

        if ( isset( $_GET['close-directorist-promo-version'] ) ) {
            if ( empty( $_GET['directorist_promo_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['directorist_promo_nonce'] ) ), 'directorist_promo_nonce' ) ) {
                wp_die( esc_html__( 'Security check failed.', 'directorist' ), '', array( 'response' => 403 ) );
            }
            update_user_meta( get_current_user_id(), '_directorist_promo_closed', directorist_clean( wp_unslash( $_GET['close-directorist-promo-version'] ) ) );
        }

        if ( isset( $_GET['directorist_promo2_closed_version'] ) ) {
            if ( empty( $_GET['directorist_promo_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['directorist_promo_nonce'] ) ), 'directorist_promo_nonce' ) ) {
                wp_die( esc_html__( 'Security check failed.', 'directorist' ), '', array( 'response' => 403 ) );
            }
            update_user_meta( get_current_user_id(), 'directorist_promo2_closed_version', directorist_clean( wp_unslash( $_GET['directorist_promo2_closed_version'] ) ) );
        }

        if ( isset( $_GET['close-directorist-dashboard-promo-version'] ) ) {
            if ( empty( $_GET['directorist_dashboard_promo_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['directorist_dashboard_promo_nonce'] ) ), 'directorist_dashboard_promo_nonce' ) ) {
                wp_die( esc_html__( 'Security check failed.', 'directorist' ), '', array( 'response' => 403 ) );
            }

            update_user_meta(
                get_current_user_id(),
                'directorist_dashboard_promo_closed_version',
                directorist_clean( wp_unslash( $_GET['close-directorist-dashboard-promo-version'] ) )
            );
        }
    }

    public static function promo_link( $link ) {
        if ( defined( 'DIRECTORIST_AFFLILIATE_ID' ) && DIRECTORIST_AFFLILIATE_ID !== null ) {
            $link = $link . "ref/" . DIRECTORIST_AFFLILIATE_ID;
        }

        return $link;
    }
}
