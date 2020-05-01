<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Listing_Dashboard {

	public $id;

	public function __construct( $id = '' ) {
		if ( !$id ) {
            $id = get_current_user_id();
        }
        $this->id = $id;
    }

    public function get_id() {
        return $this->id;
    }

    private function enqueue_scripts() {
        wp_enqueue_script('atbdp-search-listing', ATBDP_PUBLIC_ASSETS . 'js/search-form-listing.js');
        wp_localize_script('atbdp-search-listing', 'atbdp_search', array(
            'ajaxnonce' => wp_create_nonce('bdas_ajax_nonce'),
            'ajax_url' => admin_url('admin-ajax.php'),
            'added_favourite' => __('Added to favorite', 'directorist'),
            'please_login' => __('Please login first', 'directorist')
        ));
    }

    private function get_listing_tab_args($listings) {
        $args = array(
            'listings'        => $listings,
            'date_format'     => get_option('date_format'),
            'featured_active' => get_directorist_option('enable_featured_listing'),
        );

        return $args;
    }

    private function get_profile_tab_args() {
        $uid          = $this->get_id();
        $c_user       = get_userdata($uid);
        $u_pro_pic_id = get_user_meta($uid, 'pro_pic', true);
        $u_pro_pic    = !empty($u_pro_pic_id) ? wp_get_attachment_image_src($u_pro_pic_id, 'directory-large') : '';

        $args = array(
            'u_pro_pic'  => $u_pro_pic,
            'c_user'     => $c_user,
            'u_phone'    => get_user_meta($uid, 'atbdp_phone', true),
            'u_website'  => $c_user->user_url,
            'u_address'  => get_user_meta($uid, 'address', true),
            'facebook'   => get_user_meta($uid, 'atbdp_facebook', true),
            'twitter'    => get_user_meta($uid, 'atbdp_twitter', true),
            'linkedIn'   => get_user_meta($uid, 'atbdp_linkedin', true),
            'youtube'    => get_user_meta($uid, 'atbdp_youtube', true),
            'bio'        => get_user_meta($uid, 'description', true),
        );

        return $args;
    }

    private function get_favourite_tab_args() {
        $args = array(
            'fav_listings' => ATBDP()->user->current_user_fav_listings(),
        );

        return $args;
    }

    public function render_shortcode_user_dashboard($atts) {

        $atts = shortcode_atts(array(
            'show_title' => '',
        ), $atts);

        $this->enqueue_scripts();

        ob_start();
        $include = apply_filters('include_style_settings', true);
        if ($include) {
            include ATBDP_DIR . 'public/assets/css/style.php';
        }

        // show user dashboard if the user is logged in, else kick him out of this page or show a message
        if (!atbdp_logged_in_user()) {
            // user not logged in;
            atbdp_get_shortcode_template( 'dashboard/error-message' );
            return ob_get_clean();
        }

        ATBDP()->enquirer->front_end_enqueue_scripts(true); // all front end scripts forcibly here

        // Tabs
        $dashoard_items = array();

        $my_listing_tab   = get_directorist_option('my_listing_tab', 1);
        $my_profile_tab   = get_directorist_option('my_profile_tab', 1);
        $fav_listings_tab = get_directorist_option('fav_listings_tab', 1);

        if ( $my_listing_tab ) {
            $my_listing_tab_text = get_directorist_option('my_listing_tab_text', __('My Listing', 'directorist'));
            $listings = ATBDP()->user->current_user_listings();
            $list_found = ($listings->found_posts > 0) ? $listings->found_posts : '0';

            $dashoard_items['my_listings'] = array(
                'title'              => sprintf(__('%s (%s)', 'directorist'), $my_listing_tab_text, $list_found),
                'content'            => atbdp_return_shortcode_template('dashboard/listings', $this->get_listing_tab_args($listings) ),
                'after_nav_hook'     => 'atbdp_tab_after_my_listings',
                'after_content_hook' => 'atbdp_after_loop_dashboard_listings'
            );
        }

        if ( $my_profile_tab ) {
            $dashoard_items['profile'] = array(
                'title'    => get_directorist_option('my_profile_tab_text', __('My Profile', 'directorist')),
                'content'  => atbdp_return_shortcode_template('dashboard/profile', $this->get_profile_tab_args() ),
            );
        }

        if ( $fav_listings_tab ) {
            $dashoard_items['saved_items'] = array(
                'title'              => get_directorist_option('fav_listings_tab_text', __('Favorite Listings', 'directorist')),
                'content'            => atbdp_return_shortcode_template('dashboard/favourite', $this->get_favourite_tab_args() ),
                'after_nav_hook'     => 'atbdp_tab_after_favorite_listings',
                'after_content_hook' => 'atbdp_tab_content_after_favorite'
            );
        }

        $dashoard_items = apply_filters( 'atbdp_dashboard_items', $dashoard_items );

        $show_title = !empty($atts['show_title']) ? $atts['show_title'] : '';
        $submit_listing_button = get_directorist_option('submit_listing_button', 1);
        $container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
        /*@todo; later show featured listing first on the user dashboard maybe??? */

        atbdp_get_shortcode_template( 'dashboard/user-dashboard', compact('show_title', 'dashoard_items', 'submit_listing_button','container_fluid') );

        return ob_get_clean();
    }
}