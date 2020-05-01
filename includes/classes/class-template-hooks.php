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
        add_action( 'directorist_author_profile_content', array( __CLASS__, 'author_profile_about' ), 15 );
        add_action( 'directorist_author_profile_content', array( __CLASS__, 'author_profile_listings' ), 20 );
    }

    public static function instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public static function author_profile_header() {
        $author = new Directorist_Listing_Author();
        $author_id = $author->get_id();

        $args = array(
            'author'          => $author,
            'u_pro_pic'       => get_user_meta($author_id, 'pro_pic', true),
            'avatar_img'      => get_avatar($author_id, apply_filters('atbdp_avatar_size', 96)),
            'author_name'     => get_the_author_meta('display_name', $author_id),
            'user_registered' => get_the_author_meta('user_registered', $author_id),
            'enable_review'   => get_directorist_option('enable_review', 1),
        );

        atbdp_get_shortcode_template( 'author/author-header', $args );
    }

    public static function author_profile_about() {
        $author = new Directorist_Listing_Author();
        $author_id = $author->get_id();

        $bio = get_user_meta($author_id, 'description', true);

        $args = array(
            'author'       => $author,
            'bio'          => $bio,
            'content'      => apply_filters('the_content', $bio),
            'address'      => get_user_meta($author_id, 'address', true),
            'phone'        => get_user_meta($author_id, 'phone', true),
            'email_show'   => get_directorist_option('display_author_email', 'public'),
            'email'        => get_the_author_meta('user_email', $author_id),
            'website'      => get_the_author_meta('user_url', $author_id),
            'facebook'     => get_user_meta($author_id, 'atbdp_facebook', true),
            'twitter'      => get_user_meta($author_id, 'atbdp_twitter', true),
            'linkedIn'     => get_user_meta($author_id, 'atbdp_linkedin', true),
            'youtube'      => get_user_meta($author_id, 'youtube', true),
        );

        atbdp_get_shortcode_template( 'author/author-about', $args );
    }

    public static function author_profile_listings() {
        $author = new Directorist_Listing_Author();
        $author_id = $author->get_id();

        $args = array(
            'author'             => $author,
            'header_title'       => apply_filters('atbdp_author_listings_header_title', 1),
            'author_cat_filter'  => get_directorist_option('author_cat_filter',1),
            'listings'           => apply_filters('atbdp_author_listings', true),
            'all_listings'       => $author->all_listings_query(),
            'paginate'           => get_directorist_option('paginate_author_listings', 1),
            'is_disable_price'   => get_directorist_option('disable_list_price'),
        );

        atbdp_get_shortcode_template( 'author/author-listings', $args );
    }
}

Directorist_Template_Hooks::instance();