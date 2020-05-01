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

    private function enqueue_scripts() {
        wp_enqueue_script('atbdp-search-listing', ATBDP_PUBLIC_ASSETS . 'js/search-form-listing.js');
        wp_localize_script('atbdp-search-listing', 'atbdp_search', array(
            'ajaxnonce' => wp_create_nonce('bdas_ajax_nonce'),
            'ajax_url' => admin_url('admin-ajax.php'),
            'added_favourite' => __('Added to favorite', 'directorist'),
            'please_login' => __('Please login first', 'directorist')
        ));
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
            $error_message = sprintf(__('You need to be logged in to view the content of this page. You can login %s. Don\'t have an account? %s', 'directorist'), apply_filters('atbdp_user_dashboard_login_link', "<a href='" . ATBDP_Permalink::get_login_page_link() . "'> " . __('Here', 'directorist') . "</a>"), apply_filters('atbdp_user_dashboard_signup_link', "<a href='" . ATBDP_Permalink::get_registration_page_link() . "'> " . __('Sign Up', 'directorist') . "</a>")); ?>

            <section class="directory_wrapper single_area">
                <?php ATBDP()->helper->show_login_message($error_message); ?>
            </section>
            <?php
            return ob_get_clean();
        }

        $show_title = !empty($atts['show_title']) ? $atts['show_title'] : '';
        
        ATBDP()->enquirer->front_end_enqueue_scripts(true); // all front end scripts forcibly here
        
        $listings = ATBDP()->user->current_user_listings();
        $list_found = ($listings->found_posts > 0) ? $listings->found_posts : '0';
        $fav_listings = ATBDP()->user->current_user_fav_listings();
        $uid = get_current_user_id();
        $c_user = get_userdata($uid);
        $u_website = $c_user->user_url;
        $avatar = get_user_meta($uid, 'avatar', true);
        $u_phone = get_user_meta($uid, 'atbdp_phone', true);
        $u_pro_pic_id = get_user_meta($uid, 'pro_pic', true);
        $u_pro_pic = !empty($u_pro_pic_id) ? wp_get_attachment_image_src($u_pro_pic_id, 'directory-large') : '';
        $facebook = get_user_meta($uid, 'atbdp_facebook', true);
        $twitter = get_user_meta($uid, 'atbdp_twitter', true);
        $linkedIn = get_user_meta($uid, 'atbdp_linkedin', true);
        $youtube = get_user_meta($uid, 'atbdp_youtube', true);
        $bio = get_user_meta($uid, 'description', true);
        $u_address = get_user_meta($uid, 'address', true);
        $date_format = get_option('date_format');
        $featured_active = get_directorist_option('enable_featured_listing');
        $is_disable_price = get_directorist_option('disable_list_price');
        $my_listing_tab = get_directorist_option('my_listing_tab', 1);
        $my_listing_tab_text = get_directorist_option('my_listing_tab_text', __('My Listing', 'directorist'));
        $my_profile_tab = get_directorist_option('my_profile_tab', 1);
        $my_profile_tab_text = get_directorist_option('my_profile_tab_text', __('My Profile', 'directorist'));
        $fav_listings_tab = get_directorist_option('fav_listings_tab', 1);
        $fav_listings_tab_text = get_directorist_option('fav_listings_tab_text', __('Favorite Listings', 'directorist'));
        $submit_listing_button = get_directorist_option('submit_listing_button', 1);
        $container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
        /*@todo; later show featured listing first on the user dashboard maybe??? */


        $listing_args = array(
            'listings' => $listings,
            'date_format' => $date_format,
            'featured_active' => $featured_active,
        );

        $profile_args = array(
            'u_pro_pic' => $u_pro_pic,
            'c_user' => $c_user,
            'u_phone' => $u_phone,
            'u_website' => $u_website,
            'u_address' => $u_address,
            'facebook' => $facebook,
            'twitter' => $twitter,
            'linkedIn' => $linkedIn,
            'youtube' => $youtube,
            'bio' => $bio,
        );

        $favourite_args = array(
            'fav_listings' => $fav_listings,
        );

        $dashoard_items = array(
            'my_listings' => array(
                'title'      => sprintf(__('%s (%s)', 'directorist'), $my_listing_tab_text, $list_found),
                'content'    => atbdp_return_shortcode_template('dashboard/listings', $listing_args ),
                'after_hook' => 'atbdp_tab_after_my_listings'
            ),
            'profile' => array(
                'title'   => $my_profile_tab_text,
                'content' => atbdp_return_shortcode_template('dashboard/profile', $profile_args ),
            ),
            'saved_items' => array(
                'title'   => $fav_listings_tab_text,
                'content' => atbdp_return_shortcode_template('dashboard/favourite', $favourite_args ),
                'after_hook' => 'atbdp_tab_after_favorite_listings'
            ),
        );


        $path = atbdp_get_theme_file("/directorist/shortcodes/dashboard/user-dashboard.php");
        if ( $path ) {
            include $path;
        } else {
            include ATBDP_TEMPLATES_DIR . "public-templates/shortcodes/dashboard/user-dashboard.php";
        }

            //ATBDP()->user->user_dashboard($show_title);
        return ob_get_clean();

    }
}