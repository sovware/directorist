<?php

if ( ! class_exists('ATBDP_Settings_Panel') ) {
    class ATBDP_Settings_Panel
    {
        public $fields            = [];
        public $layouts           = [];
        public $config            = [];
        public $default_form      = [];
        public $old_custom_fields = [];
        public $cetagory_options  = [];

        // run
        public function run()
        {

            add_action( 'admin_enqueue_scripts', [$this, 'register_scripts'] );
            add_action( 'init', [$this, 'prepare_settings'] );
            // add_action( 'init', [$this, 'initial_setup'] );
            add_action( 'admin_menu', [$this, 'add_menu_pages'] );

            add_action( 'wp_ajax_save_settings_data', [ $this, 'handle_save_settings_data_request' ] );
        }

        // initial_setup
        public function initial_setup() {
            
        }

        // handle_save_settings_data_request
        public function handle_save_settings_data_request()
        {
            /* wp_send_json([
                'status' => false,
                'field_list' => $this->maybe_json( $_POST['field_list'] ),
                'status_log' => [
                    'name_is_missing' => [
                        'type' => 'error',
                        'message' => 'Debugging',
                    ],
                ],
            ], 200 ); */


            $status = [ 'success' => false, 'status_log' => [] ];
            $field_list = ( ! empty( $_POST['field_list'] ) ) ? $this->maybe_json( $_POST['field_list'] ) : [];
            
            // If field list is empty
            if ( empty( $field_list ) || ! is_array( $field_list ) ) {
                $status['status_log'] = [
                    'type' => 'success',
                    'message' => __( 'No changes made', 'directorist' ),
                ];

                wp_send_json( [ 'status' => $status, '$field_list' => $field_list ] );
            }

            $options = [];
            foreach ( $field_list as $field_key ) {
                if ( ! isset( $_POST[ $field_key ] ) ) { continue; }

                $options[ $field_key ] = $_POST[ $field_key ];
            }

            $update_settings_options = $this->update_settings_options( $options );

            wp_send_json( $update_settings_options );
        }

        // update_settings_options
        public function update_settings_options( array $options = [] ) {
            $status = [ 'success' => false, 'status_log' => [] ];

            // If field list is empty
            if ( empty( $options ) || ! is_array( $options ) ) {
                $status['status_log'] = [
                    'type' => 'success',
                    'message' => __( 'Nothing to save', 'directorist' ),
                ];

                return [ 'status' => $status ];
            }

            
            // Update the options
            $atbdp_options = get_option('atbdp_option');
            foreach ( $options as $option_key => $option_value ) {
                if ( ! isset( $this->fields[ $option_key ] ) ) { continue; }

                $atbdp_options[ $option_key ] = $this->maybe_json( $option_value );
            }

            update_option( 'atbdp_option', $atbdp_options );
            
            // Send Status
            $status['options'] = $options;
            $status['success'] = true;
            $status['status_log'] = [
                'type' => 'success',
                'message' => __( 'Saving Successful', 'directorist' ),
            ];

            return [ 'status' => $status ];
        }

        // maybe_json
        public function maybe_json( $string )
        {
            $string_alt = $string;

            if ( 'string' !== gettype( $string )  ) { return $string; }

            if (preg_match('/\\\\+/', $string_alt)) {
                $string_alt = preg_replace('/\\\\+/', '', $string_alt);
                $string_alt = json_decode($string_alt, true);
                $string     = (!is_null($string_alt)) ? $string_alt : $string;
            }

            $test_json = json_decode($string, true);
            if (!is_null($test_json)) {
                $string = $test_json;
            }

            return $string;
        }

        // maybe_serialize
        public function maybe_serialize($value = '')
        {
            return maybe_serialize($this->maybe_json($value));
        }

        // prepare_settings
        public function prepare_settings()
        {
            $this->fields = apply_filters('atbdp_listing_type_settings_field_list', [
                'new_listing_status' => [
                    'label' => __('New Listing Default Status', 'directorist'),
                    'type'  => 'select',
                    'value' => 'pending',
                    'options' => [
                        [
                            'label' => __('Pending', 'directorist'),
                            'value' => 'pending',
                        ],
                        [
                            'label' => __('Publish', 'directorist'),
                            'value' => 'publish',
                        ],
                    ],
                ],

                'edit_listing_status' => [
                    'label' => __('Edited Listing Default Status', 'directorist'),
                    'type'  => 'select',
                    'value' => 'pending',
                    'options' => [
                        [
                            'label' => __('Pending', 'directorist'),
                            'value' => 'pending',
                        ],
                        [
                            'label' => __('Publish', 'directorist'),
                            'value' => 'publish',
                        ],
                    ],
                ],

            ]);

            $this->layouts = apply_filters('atbdp_listing_type_settings_layout', [
                'general' => [
                    'label' => __( 'Listing Settings', 'directorist' ),
                    'icon' => '<i class="fa fa-list"></i>',
                    'submenu' => apply_filters('atbdp_listing_settings_submenu', [
                        'general' => [
                            'label' => __('General Settings', 'directorist'),
                            'icon' => '<i class="fa fa-sliders-h"></i>',
                            'sections' => apply_filters( 'atbdp_listing_settings_general_sections', [
                                'labels' => [
                                    'title'       => __('General Settings', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'new_listing_status',
                                        'edit_listing_status',
                                    ],
                                ],
                            ] ),
                        ],
                        'listings_page' => [
                            'label' => __('Listings Page', 'directorist'),
                            'sections' => apply_filters( 'atbdp_listing_settings_listings_page_sections', [
                                'labels' => [
                                    'title'       => __('Listings Page', 'directorist'),
                                    'description' => '',
                                    'fields'      => [],
                                ],
                            ] ),
                        ],
                        'single_listing' => [
                            'label' => __('Single Listing', 'directorist'),
                            'sections' => apply_filters( 'atbdp_listing_settings_listing_page_sections', [
                                'labels' => [
                                    'title'       => __('Single Listing', 'directorist'),
                                    'description' => '',
                                    'fields'      => [],
                                ],
                            ] ),
                        ],
                        'badge' => [
                            'label' => __('Badge', 'directorist'),
                            'sections' => apply_filters( 'atbdp_listing_settings_badge_sections', [
                                'labels' => [
                                    'title'       => __('Badge', 'directorist'),
                                    'description' => '',
                                    'fields'      => [],
                                ],
                            ] ),
                        ],

                        'review' => [
                            'label' => __('Reveiw', 'directorist'),
                            'sections' => apply_filters( 'atbdp_listing_settings_review_sections', [
                                'labels' => [
                                    'title'       => __('Reveiw', 'directorist'),
                                    'description' => '',
                                    'fields'      => [],
                                ],
                            ] ),
                        ],

                        'map' => [
                            'label' => __('Map', 'directorist'),
                            'sections' => apply_filters( 'atbdp_listing_settings_map_sections', [
                                'labels' => [
                                    'title'       => __('Map', 'directorist'),
                                    'description' => '',
                                    'fields'      => [],
                                ],
                            ] ),
                        ],

                        'user_dashboard' => [
                            'label' => __('User Dashboard', 'directorist'),
                            'sections' => apply_filters( 'atbdp_listing_settings_user_dashboard_sections', [
                                'labels' => [
                                    'title'       => __('User Dashboard', 'directorist'),
                                    'description' => '',
                                    'fields'      => [],
                                ],
                            ] ),
                        ],
                    ]),
                ],

            ]);

            $this->config = [
                'fields_theme' => 'butterfly',
                'submission' => [
                    'url' => admin_url('admin-ajax.php'),
                    'with' => [ 'action' => 'save_settings_data' ],
                ],
            ];
        }

        // add_menu_pages
        public function add_menu_pages()
        {
            add_submenu_page(
                'edit.php?post_type=at_biz_dir',
                'Settings Panel',
                'Settings Panel',
                'manage_options',
                'atbdp-settings',
                [ $this, 'menu_page_callback__settings_manager' ],
                5
            );
        }

        // menu_page_callback__settings_manager
        public function menu_page_callback__settings_manager()
        {   
            // Get Saved Data
            $atbdp_options = get_option('atbdp_option');
            foreach( $this->fields as $field_key => $field_opt ) {
                if ( ! isset(  $atbdp_options[ $field_key ] ) ) { continue; }

                $this->fields[ $field_key ]['value'] = $atbdp_options[ $field_key ];
            }

            $atbdp_settings_manager_data = [
                'fields'  => $this->fields,
                'layouts' => $this->layouts,
                'config'  => $this->config,
            ];

            $this->enqueue_scripts();
            wp_localize_script('atbdp_settings_manager', 'atbdp_settings_manager_data', $atbdp_settings_manager_data);
        
            /* $status = $this->update_settings_options([
                'new_listing_status' => 'publish',
                'edit_listing_status' => 'publish',
            ]);
            
            $check_new = get_directorist_option( 'new_listing_status' );
            $check_edit = get_directorist_option( 'edit_listing_status' );

            var_dump( [ '$check_new' => $check_new,  '$check_edit' => $check_edit] ); */
            
            atbdp_load_admin_template('settings-manager/settings');
        }

        // update_fields_with_old_data
        public function update_fields_with_old_data()
        {
            
        }

        // enqueue_scripts
        public function enqueue_scripts()
        {
            wp_enqueue_media();
            wp_enqueue_style('atbdp-unicons');
            wp_enqueue_style('atbdp-font-awesome');
            wp_enqueue_style('atbdp-select2-style');
            wp_enqueue_style('atbdp-select2-bootstrap-style');
            wp_enqueue_style('atbdp_admin_css');

            wp_localize_script('atbdp_settings_manager', 'ajax_data', ['ajax_url' => admin_url('admin-ajax.php')]);
            wp_enqueue_script('atbdp_settings_manager');
        }

        // register_scripts
        public function register_scripts()
        {
            wp_register_style('atbdp-unicons', '//unicons.iconscout.com/release/v3.0.3/css/line.css', false);
            wp_register_style('atbdp-select2-style', '//cdn.bootcss.com/select2/4.0.0/css/select2.css', false);
            wp_register_style('atbdp-select2-bootstrap-style', '//select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css', false);
            wp_register_style('atbdp-font-awesome', ATBDP_PUBLIC_ASSETS . 'css/font-awesome.min.css', false);

            wp_register_style( 'atbdp_admin_css', ATBDP_PUBLIC_ASSETS . 'css/admin_app.css', [], '1.0' );
            wp_register_script( 'atbdp_settings_manager', ATBDP_PUBLIC_ASSETS . 'js/settings_manager.js', ['jquery'], false, true );
        }
    }
}
