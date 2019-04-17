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
                __('Get Extensions', ATBDP_TEXTDOMAIN),
                __('<span>Extensions</span>', ATBDP_TEXTDOMAIN),
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
            ATBDP()->load_template('extension');
        }






    }


}