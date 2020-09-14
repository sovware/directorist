<?php

if ( ! class_exists( 'ATBDP_Listing_Type_Manager' ) ) {
    class ATBDP_Listing_Type_Manager {
        public $settings = [];

        // run
        public function run() {
            $this->prepare_settings();
            add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ] );
            add_action( 'init', [ $this, 'register_terms' ] );
            add_action( 'admin_menu', [ $this, 'add_menu_pages' ] );
            add_action( 'admin_post_delete_listing_type', [ $this, 'handle_delete_listing_type_request' ] );
        }

        // prepare_settings
        public function prepare_settings() {
            $this->settings = apply_filters( 'atbdp_listing_type_settings_submenu', [ 
                'general' => apply_filters('atbdp_listing_type_general_menu', [
                    'label' => 'General',
                    'icon' => '',
                    'submenu' => apply_filters('atbdp_listing_type_general_submenu', [
                        'general' => [
                            'label' => 'General',
                            'sections' => [
                                'labels' => [
                                    'title'       => 'Labels',
                                    'description' => '',
                                    'fields'      => [
                                        'name' => [
                                            'label' => 'Name',
                                            'type'  => 'text',
                                            'value' => '',
                                            'rules' => [
                                                'required' => true,
                                            ],
                                        ],
    
                                        'icon' => [
                                            'label' => 'Icon',
                                            'type'  => 'icon',
                                            'value' => '',
                                            'rules' => [
                                                'required' => false,
                                            ],
                                        ],
    
                                        'singular_name' => [
                                            'label' => 'Singular name (e.g. Business)',
                                            'type'  => 'text',
                                            'value' => '',
                                            'rules' => [
                                                'required' => false,
                                            ],
                                        ],
                                        
                                        'plural_name' => [
                                            'label' => 'Plural name (e.g. Businesses)',
                                            'type'  => 'text',
                                            'value' => '',
                                            'rules' => [
                                                'required' => false,
                                            ],
                                        ],
    
                                        'permalink' => [
                                            'label' => 'Permalink',
                                            'type'  => 'text',
                                            'value' => '',
                                            'rules' => [
                                                'required' => false,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'packages' => [
                            'label' => 'Packages',
                            'sections' => [
                                'labels' => [
                                    'title'       => 'Labels',
                                    'description' => '',
                                    'fields'      => [
                                        'name' => [
                                            'label' => 'Name',
                                            'type'  => 'text',
                                            'value' => '',
                                            'rules' => [
                                                'required' => true,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ]),
            ]);
        }

        // add_menu_pages
        public function add_menu_pages() {
            add_submenu_page(
                'edit.php?post_type=at_biz_dir',
                'Listing Types',
                'Listing Types',
                'manage_options',
                'atbdp-listing-types',
                [ $this, 'menu_page_callback__listing_types' ],
                5
            );
        }

        // menu_page_callback__listing_types
        public function menu_page_callback__listing_types() {
            $post_types_list_table = new Listing_Types_List_Table( $this );
            
            $action = $post_types_list_table->current_action();
            $post_types_list_table->prepare_items();

            $data = [
                'post_types_list_table' => $post_types_list_table,
                'settings'              => json_encode( $this->settings ),
                'add_new_link'          => admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-listing-types&action=add_new' ),
            ];

            if ( ! empty( $action ) && ( 'edit' === $action || 'add_new' === $action )  ) {
                $this->enqueue_scripts();
                atbdp_load_admin_template( 'post-types-manager/edit-listing-type', $data );

                return;
            }

            atbdp_load_admin_template( 'post-types-manager/all-listing-types', $data );
        }

        // handle_delete_listing_type_request
        public function handle_delete_listing_type_request() {

            if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'delete_listing_type' ) ) {
                wp_die( 'Are you cheating? | _wpnonce' );
            }

            if ( ! current_user_can( 'manage_options' ) ) {
                wp_die( 'Are you cheating? | manage_options' );
            }

            $term_id = isset( $_REQUEST['listing_type_id'] ) ? absint( $_REQUEST['listing_type_id'] ) : 0;

            $this->delete_listing_type( $term_id );


            wp_redirect( admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-listing-types' ) );
            exit;
        }

        // delete_listing_type
        public function delete_listing_type( $term_id = 0 ) {
            if ( wp_delete_term( $term_id , 'atbdp_listing_types' ) ) {
                atbdp_add_flush_alert([
                    'id'      => 'deleting_listing_type_status',
                    'page'    => 'all-listing-type',
                    'message' => 'Successfully Deleted the listing type',
                ]);
            } else {
                atbdp_add_flush_alert([ 
                    'id'      => 'deleting_listing_type_status',
                    'page'    => 'all-listing-type',
                    'type'    => 'error',
                    'message' => 'Failed to delete the listing type'
                ]);
            }
        }

        // register_terms
        public function register_terms() {
            register_taxonomy( 'atbdp_listing_types', [ ATBDP_POST_TYPE ], [
                'hierarchical' => false,
                'labels' => [
                    'name' => _x( 'Listing Type', 'taxonomy general name', 'directorist' ),
                    'singular_name' => _x('Listing Type', 'taxonomy singular name', 'directorist'),
                    'search_items' => __('Search Listing Type', 'directorist'),
                    'menu_name' => __('Listing Type', 'directorist'),
                ],
                'show_ui' => false,
             ]);
        }


       
        // enqueue_scripts
        public function enqueue_scripts() {
            wp_enqueue_style( 'atbdp-font-awesome' );
            wp_enqueue_script( 'atbdp_admin_app' );
            wp_enqueue_style( 'atbdp_admin_css' );
            
        }

        // register_scripts
        public function register_scripts() {
            wp_register_style( 'atbdp-font-awesome', ATBDP_PUBLIC_ASSETS . 'css/font-awesome.min.css', false, ATBDP_VERSION );
        }

    }
}