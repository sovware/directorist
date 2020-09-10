<?php

if ( ! class_exists( 'ATBDP_CPT_Manager' ) ) {
    class ATBDP_CPT_Manager {
        // run
        public function run() {
            add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ] );
            add_action( 'init', [ $this, 'register_terms' ] );
            add_action( 'admin_menu', [ $this, 'add_menu_pages' ] );
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
            $post_types_list_table = new Listing_Types_List_Table();
            
            $action = $post_types_list_table->current_action();
            $post_types_list_table->prepare_items();
            $add_new_link = '?post_type=at_biz_dir&page=' . esc_attr( $_REQUEST['page'] ) . '&action=add_new';

            $data = [
                'class'                 => $this,
                'post_types_list_table' => $post_types_list_table,
                'action'                => $action,
                'action_link'           => admin_url() . '?post_type=at_biz_dir&page=atbdp-listing-types',
                'add_new_link'          => $add_new_link,
            ];
            
            // var_dump( $action );

            // handle_listing_type_delete_request
            $this->handle_listing_type_delete_request();


            if ( isset( $_GET['action'] ) && ( 'edit' === $_GET['action'] || 'add_new' === $_GET['action'] )  ) {
                $this->enqueue_scripts();
                atbdp_load_admin_template( 'post-types-manager/edit-listing-type' );

                return;
            }

            atbdp_load_admin_template( 'post-types-manager/all-listing-types', $data );
        }

        // handle_listing_type_delete_request
        public function handle_listing_type_delete_request() {
            
            if ( isset( $_GET['action'] ) && isset( $_GET['listing_type_id'] ) && 'delete' === $_GET['action']  ) {
                $term_id = absint( $_GET['listing_type_id'] );
                $status = wp_delete_term( $term_id , 'atbdp_listing_types' );

                if ( $status && ! is_wp_error( $status ) ) {
                    atbdp_add_flush_alert([
                        'id'      => 'deleting_listing_type_status',
                        'page'    => 'all-listing-type',
                        'page'    => 'global',
                        'message' => 'Successfully Deleted the listing type',
                    ]);
                } else {
                    atbdp_add_flush_alert([ 
                        'id'      => 'deleting_listing_type_status',
                        'page'    => 'all-listing-type',
                        'page'    => 'global',
                        'type'    => 'error',
                        'message' => 'Failed to delete the listing type'
                    ]);
                }
                
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
                'show_ui' => true,
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