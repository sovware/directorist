<?php
/**
 * ATBDP Extensions class
 *
 * This class is for interacting with Extensions eg. showing extensions lists
 *
 * @package     ATBDP
 * @subpackage  inlcudes/classes Extensions
 * @copyright   Copyright (c) 2018, AazzTech
 * @since       1.0
 */


// Exit if accessed directly
if ( ! defined('ABSPATH') ) { die( 'Direct access is not allowed.' ); }

if (!class_exists('ATBDP_Extensions')) {

    /**
     * Class ATBDP_Extensions
     */
    class ATBDP_Extensions
    {
        public function __construct()
        {
            add_action( 'admin_menu', array($this, 'admin_menu'), 100 );
        }

        /**
         * It Adds menu item
         */
        public function admin_menu()
        {
            add_submenu_page('edit.php?post_type=at_biz_dir',
                __('Get Extensions', 'directorist'),
                __('<span>Themes & Extensions</span>', 'directorist'),
                'manage_options',
                'atbdp-extension',
                array($this, 'show_extension_view')
            );

        }

        /**
         * It Loads Extension view
         */
        public function show_extension_view()
        {
            // Get Extensions
            $all_plugins_list = get_plugins();
            $directorist_extensions = [];

            foreach ( $all_plugins_list as $plugin_base => $plugin_data ) {
                if ( preg_match( '/^directorist-/', $plugin_base ) ) {
                    $directorist_extensions[ $plugin_base ] = $plugin_data;
                }
            }

            // Get Themes
            $all_sovware_themes = [
                'direo',
                'dlist',
                'dservice',
            ];

            $all_themes = wp_get_themes();
            $sovware_themes = [];

            foreach ( $all_themes as $theme_base => $theme_data ) {
                if ( in_array( $theme_base, $all_sovware_themes ) ) {
                    $sovware_themes[ $theme_base ] = $theme_data;
                }
            }


            ATBDP()->load_template('theme-extensions/theme-extension');
        }
    }


}