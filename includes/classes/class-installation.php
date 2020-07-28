<?php
/**
 * Install Function
 *
 * @package     Directorist
 * @copyright   Copyright (c) 2018, AazzTech
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if (!class_exists('ATBDP_Installation')):
    class ATBDP_Installation {

        /**
         *It installs the required features or options for the plugin to run properly.
         * @link https://codex.wordpress.org/Function_Reference/register_post_type
         * @return void
         */
        public static function install()
        {
            include_once  ATBDP_INC_DIR.'review-rating/class-review-rating-database.php'; // include review class
            require_once ATBDP_CLASS_DIR . 'class-custom-post.php'; // include custom post class
            require_once ATBDP_CLASS_DIR . 'class-roles.php'; // include custom roles and Caps
            $ATBDP_Custom_Post = new ATBDP_Custom_Post();
            $ATBDP_Custom_Post->register_new_post_types();
            $Review_DB = new ATBDP_Review_Rating_DB();
            $Review_DB->create_table(); // create table for storing reviews and ratings of the listings
            flush_rewrite_rules(); // lets flash the rewrite rules as we have registered the custom post

            // Add custom ATBDP_Roles & Capabilities
            if( ! get_option( 'atbdp_roles_mapped' ) ) {
                $roles = new ATBDP_Roles;
                $roles->add_caps();
            }
            
            // Insert atbdp_roles_mapped option to the db to prevent mapping meta cap
            add_option( 'atbdp_roles_mapped', true );

            $atbdp_option = get_option('atbdp_option');
            $atpdp_setup_wizard = apply_filters( 'atbdp_setup_wizard', true );

            if( ! $atbdp_option && $atpdp_setup_wizard ) {
                set_transient( '_directorist_setup_page_redirect', true, 30 );
            }

        }


}

endif;
