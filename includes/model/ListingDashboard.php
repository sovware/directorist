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

    public function get_listing_tab_args($listings) {
        $args = array(
            'listings'        => $listings,
            'date_format'     => get_option('date_format'),
            'featured_active' => get_directorist_option('enable_featured_listing'),
        );

        return $args;
    }

    public function get_profile_tab_args() {
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

    public function get_favourite_tab_args() {

        $fav_listing_items = array();

        $fav_listings = ATBDP()->user->current_user_fav_listings();

        foreach ($fav_listings->posts as $post) {
            $title = !empty($post->post_title) ? $post->post_title : __('Untitled', 'directorist');
            $cats = get_the_terms($post->ID, ATBDP_CATEGORY);
            $category = get_post_meta($post->ID, '_admin_category_select', true);
            $category_name = !empty($cats) ? $cats[0]->name : 'Uncategorized';
            $category_icon = !empty($cats) ? esc_attr(get_cat_icon($cats[0]->term_id)) : atbdp_icon_type() . '-tags';
            $mark_fav = atbdp_listings_mark_as_favourite($post->ID);

            $icon_type = substr($category_icon, 0, 2);
            $icon = ('la' === $icon_type) ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;
            $category_link = !empty($cats) ? esc_url(ATBDP_Permalink::atbdp_get_category_page($cats[0])) : '#';
            $post_link = esc_url(get_post_permalink($post->ID));

            $listing_img = get_post_meta($post->ID, '_listing_img', true);
            $listing_prv_img = get_post_meta($post->ID, '_listing_prv_img', true);
            $crop_width = get_directorist_option('crop_width', 360);
            $crop_height = get_directorist_option('crop_height', 300);

            if (!empty($listing_prv_img)) {
                $prv_image = atbdp_get_image_source($listing_prv_img, 'large');
            }
            if (!empty($listing_img[0])) {
                $gallery_img = atbdp_get_image_source($listing_img[0], 'medium');
            }

            if (!empty($listing_prv_img)) {
                $img_src = $prv_image;

            }
            if (!empty($listing_img[0]) && empty($listing_prv_img)) {
                $img_src = $gallery_img;

            }
            if (empty($listing_img[0]) && empty($listing_prv_img)) {
                $img_src = ATBDP_PUBLIC_ASSETS . 'images/grid.jpg';
            }

            $fav_listing_items[] = array(
                'post_link'      => $post_link,
                'img_src'        => $img_src,
                'title'          => $title,
                'category_link'  => $category_link,
                'category_name'  => $category_name,
                'icon'           => $icon,
                'mark_fav'       => $mark_fav,
            );
        }

        $args = array(
            'fav_listing_items' => $fav_listing_items,
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
        $dashboard_items = array();

        $my_listing_tab   = get_directorist_option('my_listing_tab', 1);
        $my_profile_tab   = get_directorist_option('my_profile_tab', 1);
        $fav_listings_tab = get_directorist_option('fav_listings_tab', 1);

        if ( $my_listing_tab ) {
            $my_listing_tab_text = get_directorist_option('my_listing_tab_text', __('My Listing', 'directorist'));
            $listings = ATBDP()->user->current_user_listings();
            $list_found = ($listings->found_posts > 0) ? $listings->found_posts : '0';

            $dashboard_items['my_listings'] = array(
                'title'              => sprintf(__('%s (%s)', 'directorist'), $my_listing_tab_text, $list_found),
                'content'            => atbdp_return_shortcode_template('dashboard/listings', $this->get_listing_tab_args($listings) ),
                'after_nav_hook'     => 'atbdp_tab_after_my_listings',
                'after_content_hook' => 'atbdp_after_loop_dashboard_listings'
            );
        }

        if ( $my_profile_tab ) {
            $dashboard_items['profile'] = array(
                'title'    => get_directorist_option('my_profile_tab_text', __('My Profile', 'directorist')),
                'content'  => atbdp_return_shortcode_template('dashboard/profile', $this->get_profile_tab_args() ),
            );
        }

        if ( $fav_listings_tab ) {
            $dashboard_items['saved_items'] = array(
                'title'              => get_directorist_option('fav_listings_tab_text', __('Favorite Listings', 'directorist')),
                'content'            => atbdp_return_shortcode_template('dashboard/favourite', $this->get_favourite_tab_args() ),
                'after_nav_hook'     => 'atbdp_tab_after_favorite_listings',
                'after_content_hook' => 'atbdp_tab_content_after_favorite'
            );
        }

        $dashboard_items = apply_filters( 'atbdp_dashboard_items', $dashboard_items );

        $show_title = !empty($atts['show_title']) ? $atts['show_title'] : '';
        $container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
        $container_fluid = apply_filters('atbdp_deshboard_container_fluid', $container_fluid);

        /*@todo; later show featured listing first on the user dashboard maybe??? */

        atbdp_get_shortcode_template( 'dashboard/user-dashboard', compact('show_title', 'dashboard_items','container_fluid') );

        return ob_get_clean();
    }
}