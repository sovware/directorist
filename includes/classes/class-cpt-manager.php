<?php

if ( ! class_exists( 'ATBDP_CPT_Manager' ) ) {
    class ATBDP_CPT_Manager {
        // run
        public function run() {
            add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ] );
            add_action( 'init', [ $this, 'register_cpt' ] );
            add_action( 'init', [ $this, 'add_meta_boxes' ] );
            add_action( 'save_post', [ $this, 'save_meta_box_data' ] );
        }


        // register_cpt
        public function register_cpt() {
            register_post_type( 'atbdp-listings-types', [
                'label'         => 'Listings Types',
                'labels'        => 'Listings Types',
                'public'        => true,
                'menu_icon'     => 'dashicons-location',
                'supports'      => ['title'],
                'menu_position' => 5,
            ]);
        }

        // add_meta_boxes
        public function add_meta_boxes() {
            add_meta_box( 
                'atbdp_cpt_options_metabox', 
                'Listings Options',
                [ $this, 'atbdp_cpt_options_metabox_callback' ], 
                'atbdp-listings-types', 
                'advanced', 
                'high'
            );
        }

        // atbdp_cpt_options_metabox_callback
        public function atbdp_cpt_options_metabox_callback() {
            $this->enqueue_scripts();

            $meta_keys = [
                'general',
                'has_listing_packages',
                'listing_packages',
                'review_stars_mode',
            ];

            $meta_fields = [];
            foreach ( $meta_keys as $meta_key ) {
                $post_meta = get_post_meta( get_the_ID(), $meta_key, true );
                $meta_fields[$meta_key] = $post_meta;
            }

            // var_dump( $meta_fields );
            
            atbdp_load_admin_template( 'cpt-manager/cpt-options-metabox' );
        }

        // save_meta_box_data
        public function save_meta_box_data( $post_id ) {
            $meta_keys = [
                'general'              => 'json',
                'has_listing_packages' => 'boolean',
                'listing_packages'     => 'json',
                'review_stars_mode'    => 'string',
            ];

            foreach ( $meta_keys as $meta_key => $meta_type ) {
                if ( array_key_exists( $meta_key, $_POST ) ) {
                    $post_meta  = sanitize_text_field( $_POST[ $meta_key ] );
                    
                    switch ( $meta_type ) {
                        case 'json':
                            // $post_meta = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $post_meta), true );
                            // $type = gettype( $post_meta );
                            
                            // update_post_meta( $post_id, $meta_key, $type );
                            // update_post_meta( $post_id, $meta_key, serialize( $post_meta ) );
                            break;
                        case 'boolean':
                            update_post_meta( $post_id, $meta_key, $post_meta );
                            break;
                        case 'string':
                            update_post_meta( $post_id, $meta_key, $post_meta );
                            break;
                        case 'integer':
                            update_post_meta( $post_id, $meta_key, $post_meta );
                            break;
                    }

                };
            }
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