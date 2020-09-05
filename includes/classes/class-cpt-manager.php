<?php

if ( ! class_exists( 'ATBDP_CPT_Manager' ) ) {
    class ATBDP_CPT_Manager {
        // run
        public function run() {
            add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ] );
            add_action( 'init', [ $this, 'register_cpt' ] );
            add_action( 'init', [ $this, 'add_meta_boxes' ] );
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
            
            atbdp_load_admin_template( 'cpt-manager/cpt-options-metabox' );
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