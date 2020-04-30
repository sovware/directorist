<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Template_Hooks {

    protected static $instance = null;

    private function __construct( $id = '' ) {

        // Author Profile
        add_action( 'directorist_author_profile_content', array( __CLASS__, 'author_profile_header' ), 10 );
        // add_action( 'directorist_author_profile_content', array( __CLASS__, 'author_profile_about' ), 15 );
        // add_action( 'directorist_author_profile_content', array( __CLASS__, 'author_profile_listings' ), 20 );
    }

    public static function instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public static function author_profile_header() {
        $author = Directorist_Listing_Author();
        $author_id = $author->get_id();

        $args = array(
            'author'          => $author,
            'u_pro_pic'       => get_user_meta($author_id, 'pro_pic', true),
            'author_name'     => get_the_author_meta('display_name', $author_id),
            'user_registered' => get_the_author_meta('user_registered', $author_id),
            'enable_review'   => get_directorist_option('enable_review', 1),
        );

        atbdp_get_shortcode_template( 'author/author-header', $args );
    }

    public static function author_profile_about() {
        atbdp_get_shortcode_template( 'author/author-about', $args );
    }

    public static function author_profile_listings() {
        atbdp_get_shortcode_template( 'author/author-listings', $args );
    }
}

//Directorist_Template_Hooks::instance();