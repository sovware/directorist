<?php
/**
 * ATBDP Themes class
 *
 * This class is for interacting with Themes eg. showing themess lists
 *
 * @package     ATBDP
 * @subpackage  inlcudes/classes Themes
 * @copyright   Copyright (c) 2018, AazzTech
 * @since       1.0
 */


// Exit if accessed directly
if ( ! defined('ABSPATH') ) { die( 'Direct access is not allowed.' ); }

if (!class_exists('ATBDP_Themes')) {

    /**
     * Class ATBDP_Themes
     */
    class ATBDP_Themes
    {
        public function __construct()
        {
            add_action( 'admin_menu', array($this, 'admin_menu'), 101 );
        }

        /**
         * It Adds menu item
         */
        public function admin_menu()
        {
            add_submenu_page('edit.php?post_type=at_biz_dir',
                __('Get Themes', ATBDP_TEXTDOMAIN),
                __('<span style="color: #01b0ff; font-weight: 600;">Directory Themes</span>', ATBDP_TEXTDOMAIN),
                'manage_options',
                'atbdp-themes',
                array($this, 'show_themes_view')
            );

        }

        /**
         * It Loads Extension view
         */
        public function show_themes_view()
        {
            ATBDP()->load_template('themes');
        }






    }


}