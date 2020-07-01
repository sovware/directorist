<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Directorist_Listing_Dashboard {

    protected static $instance = null;

    public $id;

    private function __construct() {
        $this->id = get_current_user_id();
    }

    public static function instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function get_id() {
        return $this->id;
    }

    private function enqueue_scripts() {
        wp_enqueue_script( 'atbdp-search-listing', ATBDP_PUBLIC_ASSETS . 'js/search-form-listing.js' );
        wp_localize_script( 'atbdp-search-listing', 'atbdp_search', array(
            'ajaxnonce'       => wp_create_nonce( 'bdas_ajax_nonce' ),
            'ajax_url'        => admin_url( 'admin-ajax.php' ),
            'added_favourite' => __( 'Added to favorite', 'directorist' ),
            'please_login'    => __( 'Please login first', 'directorist' ),
        ) );
    }

    public function get_listing_tab_args( $listings ) {
    	$listing_items = array();

    	if ($listings->have_posts()) {
    		foreach ( $listings->posts as $post ) {
    			$featured = get_post_meta($post->ID, '_featured', true);

				$has_thumbnail = false;
				$thumbnail_img = '';

                $listing_img = get_post_meta($post->ID, '_listing_img', true);
                $listing_prv_img = get_post_meta($post->ID, '_listing_prv_img', true);
				$default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                if (!empty($listing_prv_img)) {
                    $prv_image = atbdp_get_image_source($listing_prv_img, 'large');
                    $prv_image_full = atbdp_get_image_source($listing_prv_img, 'full');
                }
                if (!empty($listing_img[0])) {
                    $gallery_img_full = atbdp_get_image_source($listing_img[0], 'full');
                }
                if (!empty($listing_img[0]) && empty($listing_prv_img)) {
                    $thumbnail_img = $gallery_img_full;
                    $has_thumbnail = true;
                }
                if (empty($listing_img[0]) && empty($listing_prv_img) && !empty($default_image)) {
                    $thumbnail_img = $default_image;
                    $has_thumbnail = true;
                }
                if (!empty($listing_prv_img)) {
                    $thumbnail_img = $prv_image_full;
                    $has_thumbnail = true;
                }

                $date_format = get_option('date_format');
                $exp_date  = get_post_meta($post->ID, '_expiry_date', true);
                $never_exp = get_post_meta($post->ID, '_never_expire', true);
                $status    = get_post_meta($post->ID, '_listing_status', true);
                $exp_text  = !empty($never_exp) ? __('Never Expires', 'directorist') : date_i18n($date_format, strtotime($exp_date));
                $exp_html  = ( $status == 'expired' ) ? '<span style="color: red">' . __('Expired', 'directorist') . '</span>' : $exp_text;
                $status_label = get_post_status_object($post->post_status)->label;

		    	$listing_items[] = array(
		    		'obj'                => $post,
		    		'id'                 => $post->ID,
		    		'featured'           => $featured,
		    		'permalink'          => get_post_permalink($post->ID),
		    		'has_thumbnail'      => $has_thumbnail,
		    		'thumbnail_img'      => $thumbnail_img,
		    		'title'              => !empty($post->post_title) ? $post->post_title : __('Untitled!', 'directorist'),
		    		'exp_html'           => $exp_html,
		    		'status'             => $status,
		    		'status_label'       => $status_label,
		    		'status_label_class' => 'atbdp__' . strtolower($status_label),
		    		'modal_id'           => apply_filters('atbdp_pricing_plan_change_modal_id', 'atpp-plan-change-modal', $post->ID),
		    	);
    		}
    	}

        $args = array(
        	'listings'        => $listings,
            'listing_items'   => $listing_items,
            'date_format'     => get_option( 'date_format' ),
    		'featured_class'  => $featured ? ' directorist-featured-listings' : '',
    		'featured_text'   => get_directorist_option('feature_badge_text', __('Featured', 'directorist')),
    		'can_renew'       => get_directorist_option('can_renew_listing'),
            'featured_active' => get_directorist_option( 'enable_featured_listing' ),
            'has_pagination'  => get_directorist_option('user_listings_pagination', 1),
            'paged'           => atbdp_get_paged_num(),
        );

        return $args;
    }

    public function get_favourite_tab_args() {

        $fav_listing_items = array();

        $fav_listings = ATBDP()->user->current_user_fav_listings();

        if ( $fav_listings->have_posts() ):
            foreach ( $fav_listings->posts as $post ) {
                $title         = ! empty( $post->post_title ) ? $post->post_title : __( 'Untitled', 'directorist' );
                $cats          = get_the_terms( $post->ID, ATBDP_CATEGORY );
                $category      = get_post_meta( $post->ID, '_admin_category_select', true );
                $category_name = ! empty( $cats ) ? $cats[0]->name : 'Uncategorized';
                $category_icon = ! empty( $cats ) ? esc_attr( get_cat_icon( $cats[0]->term_id ) ) : atbdp_icon_type() . '-tags';
                $mark_fav_html = atbdp_listings_mark_as_favourite( $post->ID );

                $icon_type     = substr( $category_icon, 0, 2 );
                $icon          = ( 'la' === $icon_type ) ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;
                $category_link = ! empty( $cats ) ? esc_url( ATBDP_Permalink::atbdp_get_category_page( $cats[0] ) ) : '#';
                $post_link     = esc_url( get_post_permalink( $post->ID ) );

                $listing_img     = get_post_meta( $post->ID, '_listing_img', true );
                $listing_prv_img = get_post_meta( $post->ID, '_listing_prv_img', true );
                $crop_width      = get_directorist_option( 'crop_width', 360 );
                $crop_height     = get_directorist_option( 'crop_height', 300 );

                if ( ! empty( $listing_prv_img ) ) {
                    $prv_image = atbdp_get_image_source( $listing_prv_img, 'large' );
                }
                if ( ! empty( $listing_img[0] ) ) {
                    $gallery_img = atbdp_get_image_source( $listing_img[0], 'medium' );
                }

                if ( ! empty( $listing_prv_img ) ) {
                    $img_src = $prv_image;

                }
                if ( ! empty( $listing_img[0] ) && empty( $listing_prv_img ) ) {
                    $img_src = $gallery_img;

                }
                if ( empty( $listing_img[0] ) && empty( $listing_prv_img ) ) {
                    $img_src = ATBDP_PUBLIC_ASSETS . 'images/grid.jpg';
                }

                $fav_listing_items[] = array(
                	'obj'           => $post,
                    'post_link'     => $post_link,
                    'img_src'       => $img_src,
                    'title'         => $title,
                    'category_link' => $category_link,
                    'category_name' => $category_name,
                    'icon'          => $icon,
                    'mark_fav_html' => $mark_fav_html,
                );
            }
        endif;

        $args = array(
            'fav_listings'      => $fav_listings,
            'fav_listing_items' => $fav_listing_items,
        );

        return $args;
    }

    public function get_profile_tab_args() {
        $uid          = $this->get_id();
        $c_user       = get_userdata( $uid );
        $u_pro_pic_id = get_user_meta( $uid, 'pro_pic', true );
        $u_pro_pic    = $u_pro_pic_id ? wp_get_attachment_image_src( $u_pro_pic_id, 'directory-large' ) : '';

        $args = array(
            'u_pro_pic_id' => $u_pro_pic_id,
            'u_pro_pic'    => $u_pro_pic,
            'c_user'       => $c_user,
            'u_phone'      => get_user_meta( $uid, 'atbdp_phone', true ),
            'u_website'    => $c_user->user_url,
            'u_address'    => get_user_meta( $uid, 'address', true ),
            'facebook'     => get_user_meta( $uid, 'atbdp_facebook', true ),
            'twitter'      => get_user_meta( $uid, 'atbdp_twitter', true ),
            'linkedIn'     => get_user_meta( $uid, 'atbdp_linkedin', true ),
            'youtube'      => get_user_meta( $uid, 'atbdp_youtube', true ),
            'bio'          => get_user_meta( $uid, 'description', true ),
        );

        return $args;
    }

    public function dashboard_listings_query() {
    	$has_pagination = get_directorist_option('user_listings_pagination', 1);
    	$listings_per_page = get_directorist_option('user_listings_per_page', 9);

    	$args = array(
    		'author'=> $this->id,
    		'post_type'=> ATBDP_POST_TYPE,
    		'posts_per_page'=> (int) $listings_per_page,
    		'order'=> 'DESC',
    		'orderby' => 'date',
    		'post_status' => array('publish', 'pending', 'private'),
    	);

    	if($has_pagination) {
    		$args['paged'] = atbdp_get_paged_num();
    	}
    	else {
    		$args['no_found_rows']  = true;
    	}

    	$args = apply_filters('atbdp_user_dashboard_query_arguments',$args);

    	return new WP_Query($args);
    }

    public function get_dashboard_tabs() {
        // Tabs
        $dashboard_tabs = array();

        $my_listing_tab   = get_directorist_option( 'my_listing_tab', 1 );
        $my_profile_tab   = get_directorist_option( 'my_profile_tab', 1 );
        $fav_listings_tab = get_directorist_option( 'fav_listings_tab', 1 );

        if ( $my_listing_tab ) {
            $my_listing_tab_text = get_directorist_option( 'my_listing_tab_text', __( 'My Listing', 'directorist' ) );

            $listings   = ATBDP()->user->current_user_listings();
            $list_found = $listings->found_posts;

            $dashboard_tabs['my_listings'] = array(
                'title'              => sprintf(__('%s (%s)', 'directorist'), $my_listing_tab_text, $list_found),
                'content'            => atbdp_return_shortcode_template('dashboard/listings', $this->get_listing_tab_args($listings) ),
                'after_nav_hook'     => 'atbdp_tab_after_my_listings',
                'after_content_hook' => 'atbdp_after_loop_dashboard_listings',
            );
        }

        if ( $my_profile_tab ) {
            $dashboard_tabs['profile'] = array(
                'title'    => get_directorist_option('my_profile_tab_text', __('My Profile', 'directorist')),
                'content'  => atbdp_return_shortcode_template('dashboard/profile', $this->get_profile_tab_args() ),
            );
        }

        if ( $fav_listings_tab ) {
            $dashboard_tabs['saved_items'] = array(
                'title'              => get_directorist_option('fav_listings_tab_text', __('Favorite Listings', 'directorist')),
                'content'            => atbdp_return_shortcode_template('dashboard/favourite', $this->get_favourite_tab_args() ),
                'after_nav_hook'     => 'atbdp_tab_after_favorite_listings',
                'after_content_hook' => 'atbdp_tab_content_after_favorite',
            );
        }

        return apply_filters( 'atbdp_dashboard_tabs', $dashboard_tabs );
    }

    public function error_message_template() {
    	$login_link_html = apply_filters('atbdp_user_dashboard_login_link', "<a href='" . ATBDP_Permalink::get_login_page_link() . "'> " . __('Here', 'directorist') . "</a>");
    	$signup_link_html = apply_filters('atbdp_user_dashboard_signup_link', "<a href='" . ATBDP_Permalink::get_registration_page_link() . "'> " . __('Sign Up', 'directorist') . "</a>");
        $args = array(
            'error_message' => sprintf(__('You need to be logged in to view the content of this page. You can login %s. Don\'t have an account? %s', 'directorist'), $login_link_html, $signup_link_html),
        );
        return atbdp_return_shortcode_template( 'dashboard/error-message', $args );
    }

    public function dashboard_alert_message_template() {
        if ( isset($_GET['renew'] ) ) {
            $renew_token_expired = $_GET['renew'] == 'token_expired' ? true : false;
            $renew_succeed = $_GET['renew'] == 'success' ? true : false;
        }
        else {
            $renew_token_expired = $renew_succeed = false;
        }
        atbdp_get_shortcode_template( 'dashboard/alert-message', compact('renew_token_expired', 'renew_succeed') );
    }

    public function dashboard_title( $display_title ) {
        if ($display_title) {
            atbdp_get_shortcode_template( 'dashboard/title' );
        }
    }

    public function dashboard_nav_tabs_template() {
        $args = array(
            'dashboard_tabs' => $this->get_dashboard_tabs(),
        );

        atbdp_get_shortcode_template( 'dashboard/navigation-tabs', $args );
    }

    public function dashboard_nav_buttons_template() {
        $args = array(
            'display_submit_btn' => get_directorist_option('submit_listing_button', 1),
        );
        atbdp_get_shortcode_template( 'dashboard/nav-buttons', $args );
    }

    public function dashboard_tab_contents_html() {
        $dashboard_tabs = $this->get_dashboard_tabs();

        foreach ($dashboard_tabs as $key => $value) {
            echo $value['content'];
            if (!empty($value['after_content_hook'])) {
                do_action($value['after_content_hook']);
            }
        }
    }

    public function render_shortcode_user_dashboard($atts) {

        $atts = shortcode_atts(array(
            'show_title' => '',
        ), $atts);

        $this->enqueue_scripts();

        // show user dashboard if the user is logged in, else kick him out of this page or show a message
        if (!atbdp_logged_in_user()) {
            return $this->error_message_template();
        }

        ATBDP()->enquirer->front_end_enqueue_scripts(true); // all front end scripts forcibly here

        $display_title   = $atts['show_title'] == 'yes' ? true : false;
        $container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
        $container_fluid = apply_filters( 'atbdp_deshboard_container_fluid', $container_fluid );

        /*@todo; later show featured listing first on the user dashboard maybe??? */

        return atbdp_return_shortcode_template( 'dashboard/user-dashboard', compact('display_title','container_fluid') );
    }
}