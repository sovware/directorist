<?php
/**
 * Plugin Name: Directorist - Business Directory Plugin
 * Plugin URI: https://aazztech.com/product/directorist-business-directory-plugin
 * Description: Create a professional directory listing website like Yelp by a few clicks only. You can list place, any business etc.  with this plugin very easily.
 * Version: 4.0.2
 * Author: AazzTech
 * Author URI: https://aazztech.com
 * License: GPLv2 or later
 * Text Domain: directorist
 * Domain Path: /languages
 */
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2018 AazzTech.com.
*/
// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');

/**
 * Main Directorist_Base Class.
 *
 * @since 1.0
 */
final class Directorist_Base
{
    /** Singleton *************************************************************/

    /**
     * @var Directorist_Base The one true Directorist_Base
     * @since 1.0
     */
    private static $instance;


    /**
     * ATBDP_Metabox Object.
     *
     * @var object|ATBDP_Metabox
     * @since 1.0
     */
    public $metabox;


    /**
     * ATBDP_Custom_Post Object.
     *
     * @var object|ATBDP_Custom_Post
     * @since 1.0
     */
    public $custom_post;

    /**
     * ATBDP_Custom_Taxonomy Object.
     *
     * @var object|ATBDP_Custom_Taxonomy
     * @since 1.0
     */
    public $taxonomy;

    /**
     * ATBDP_Enqueuer Object.
     *
     * @var object|ATBDP_Enqueuer
     * @since 1.0
     */
    public $enquirer;


    /**
     * ATBDP_Ajax_Handler Object.
     *
     * @var object|ATBDP_Ajax_Handler
     * @since 1.0
     */
    public $ajax_handler;


    /**
     * ATBDP_Shortcode Object.
     *
     * @var object|ATBDP_Shortcode
     * @since 1.0
     */
    public $shortcode;

    /**
     * ATBDP_Helper Object.
     *
     * @var object|ATBDP_Helper
     * @since 1.0
     */
    public $helper;


    /**
     * ATBDP_Review_Rating Object.
     *
     * @var object|ATBDP_Review_Rating
     * @since 1.0
     */
    public $review;

    /**
     * ATBDP_Listing Object.
     *
     * @var object|ATBDP_Listing
     * @since 1.0
     */
    public $listing;

    /**
     * ATBDP_User Object.
     *
     * @var object|ATBDP_User
     * @since 1.0
     */
    public $user;

    /**
     * ATBDP_Roles Object.
     *
     * @var object|ATBDP_Roles
     * @since 3.0
     */
    public $roles;

    /**
     * ATBDP_Gateway Object.
     *
     * @var ATBDP_Gateway
     * @since 3.1.0
     */
    public $gateway;

    /**
     * ATBDP_Order Object.
     *
     * @var ATBDP_Order
     * @since 3.1.0
     */
    public $custom_field;

    /**
     * ATBDP_Custom_Field Object.
     *
     * @var ATBDP_Custom_Field
     * @since 3.1.6
     */
    public $order;

    /**
     * ATBDP_Email Object.
     *
     * @var ATBDP_Email
     * @since 3.1.0
     */
    public $email;

    /**
     * Main Directorist_Base Instance.
     *
     * Insures that only one instance of Directorist_Base exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     *
     * @since 1.0
     * @static
     * @static_var array $instance
     * @uses Directorist_Base::setup_constants() Setup the constants needed.
     * @uses Directorist_Base::includes() Include the required files.
     * @uses Directorist_Base::load_textdomain() load the language files.
     * @see  ATBDP()
     * @return object|Directorist_Base The one true Directorist_Base
     */
    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof Directorist_Base)) {
            self::$instance = new Directorist_Base;
            self::$instance->setup_constants();

            add_action('plugins_loaded', array(self::$instance, 'load_textdomain'));
            add_action('widgets_init', array(self::$instance, 'register_widgets'));

            self::$instance->includes();
            self::$instance->custom_post = new ATBDP_Custom_Post; // create custom post
            self::$instance->taxonomy = new ATBDP_Custom_Taxonomy;
            self::$instance->enquirer = new ATBDP_Enqueuer;
            self::$instance->metabox = new ATBDP_Metabox;
            self::$instance->ajax_handler = new ATBDP_Ajax_Handler;
            self::$instance->helper = new ATBDP_Helper;
            self::$instance->listing = new ATBDP_Listing;
            self::$instance->user = new ATBDP_User;
            self::$instance->roles = new ATBDP_Roles;
            self::$instance->gateway = new ATBDP_Gateway;
            self::$instance->custom_field = new ATBDP_Custom_Field;
            self::$instance->order = new ATBDP_Order;
            self::$instance->shortcode = new ATBDP_Shortcode;
            self::$instance->email = new ATBDP_Email;


            // new settings
            new ATBDP_Settings_Manager();

            /*Extensions Link*/
            /*initiate extensions link*/
            new ATBDP_Extensions();
            /*Initiate Review and Rating Features*/
            self::$instance->review = new ATBDP_Review_Rating;

            //activate rewrite api
            new ATBDP_Rewrite;

            //map custom capabilities
            add_filter('map_meta_cap', array(self::$instance->roles, 'meta_caps'), 10, 4);

            //add dtbdp custom body class
            add_filter('body_class', array(self::$instance, 'atbdp_body_class'));
            // display related listings
            add_action('atbdp_after_single_listing', array(self::$instance, 'show_related_listing'));
            //review and rating
            add_action('atbdp_after_listing_tagline', array(self::$instance, 'show_review_after_tagliine')); // show rating after the tagline of the normal post on single page and also the search result page.
            add_action('atbdp_after_map', array(self::$instance, 'show_review'));
            // Attempt to create listing related custom pages with plugin's custom shortcode to give user best experience.
            // we can check the database if our custom pages have been installed correctly or not here first.
            // This way we can minimize the adding of our custom function to the WordPress hooks.

            if (get_option('atbdp_pages_version') < 1) {
                add_action('wp_loaded', array(self::$instance, 'add_custom_directorist_pages'));
            }
            //fire up one time compatibility increasing function.
            if (get_option('atbdp_meta_version') < 1) {
                add_action('init', array(self::$instance, 'add_custom_meta_keys_for_old_listings'));
            }
            // init offline gateway
            new ATBDP_Offline_Gateway;
            // Init Cron jobs to run some periodic tasks
            new ATBDP_Cron;
            // add upgrade feature
            new ATBDP_Upgrade;
            // add upgrade feature
            new ATBDP_Help_Support;

        }

        return self::$instance;
    }


    /**
     * Throw error on object clone.
     *
     * The whole idea of the singleton design pattern is that there is a single
     * object therefore, we don't want the object to be cloned.
     *
     * @since 1.0
     * @access public
     * @return void
     */
    public function __clone()
    {
        // Cloning instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', ATBDP_TEXTDOMAIN), '1.0');
    }

    /**
     * Disable unserializing of the class.
     *
     * @since 1.0
     * @access public
     * @return void
     */
    public function __wakeup()
    {
        // Unserializing instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', ATBDP_TEXTDOMAIN), '1.0');
    }

    /**
     * It registers widgets and sidebar support
     *
     * @since 1.0
     * @access public
     * @return void
     */
    public function register_widgets()
    {
        if (!is_registered_sidebar('right-sidebar-listing')) {
            register_sidebar(array(
                'name' => __('Listing Right Sidebar', ATBDP_TEXTDOMAIN),
                'id' => 'right-sidebar-listing',
                'description' => __('Add widgets for the right sidebar on single listing page', ATBDP_TEXTDOMAIN),
                'before_widget' => '<div class="widget atbd_widget">',
                'after_widget' => '</div>',
                'before_title' => '<div class="atbd_widget_title"><h4>',
                'after_title' => '</h4></div>',
            ));
        }

        register_widget('BD_Popular_Listing_Widget');
        register_widget('BD_video_Widget');
        register_widget('BD_contact_form_Widget');
        register_widget('BD_Submit_Item_Widget');
        register_widget('BD_Login_Form_Widget');
        register_widget('BD_Categories_Widget');
        register_widget('BD_Locations_Widget');
        register_widget('BD_Tags_Widget');
        register_widget('BD_Search_Widget');
        register_widget('BD_Map_Widget');
        register_widget('BD_Similar_Listings_Widget');
        register_widget('BD_Author_Info_Widget');

    }

    /**
     * Setup plugin constants.
     *
     * @access private
     * @since 1.0
     * @return void
     */
    private function setup_constants()
    {
        // test
        require_once plugin_dir_path(__FILE__) . '/config.php'; // loads constant from a file so that it can be available on all files.
    }

    /**
     * Include required files.
     *
     * @access private
     * @since 1.0
     * @return void
     */
    private function includes()
    {
        require_once ATBDP_LIB_DIR . 'vafpress/bootstrap.php'; // load option framework.
        require_once ATBDP_INC_DIR . 'helper-functions.php';
        require_once ATBDP_INC_DIR . 'login-register.php';
        load_dependencies('all', ATBDP_CLASS_DIR); // load all php files from ATBDP_CLASS_DIR
        load_dependencies('all', ATBDP_LIB_DIR); // load all php files from ATBDP_LIB_DIR
        /*LOAD Rating and Review functionality*/
        load_dependencies('all', ATBDP_INC_DIR . 'review-rating/');
        /*Load gateway related stuff*/
        load_dependencies('all', ATBDP_INC_DIR . 'gateways/');

        /*Load gateway related stuff*/
        load_dependencies('all', ATBDP_INC_DIR . 'custom-fields/');
        /*Load payment related stuff*/
        load_dependencies('all', ATBDP_INC_DIR . 'payments/');
        load_dependencies('all', ATBDP_INC_DIR . 'checkout/');
        /*Load payment related stuff*/
        require_once ATBDP_INC_DIR . 'custom-actions.php';
        require_once ATBDP_INC_DIR . 'custom-filters.php';

    }

    public function load_textdomain()
    {

        load_plugin_textdomain(ATBDP_TEXTDOMAIN, false, ATBDP_LANG_DIR);
    }


    /**
     * It  loads a template file from the Default template directory.
     * @todo; Improve this method in future so that it lets user/developers to change/override any templates this plugin uses
     * @param string $name Name of the file that should be loaded from the template directory.
     * @param array $args Additional arguments that should be passed to the template file for rendering dynamic  data.
     * @param bool $return_path Whether to return the path instead of including it
     * @return string|void
     */
    public function load_template($name, $args = array(), $return_path = false)
    {
        global $post;
        $path = ATBDP_TEMPLATES_DIR . $name . '.php';
        if ($return_path) return $path;
        include($path);
    }

    public static function prepare_plugin()
    {

        include ATBDP_INC_DIR . 'classes/class-installation.php';
        ATBDP_Installation::install();
    }

    public function add_custom_directorist_pages()
    {
        $options = get_option('atbdp_option'); // we are retrieving all of our custom options because it contains all the page options too. and we can filter this array instead of calling get_directorist_option() over and over.
        /*
        Remember: We can not add new option to atbdp_option if there is no key matched. Because VafPress will override it.
        Use normal update_option() instead if you need to add custom option that is not available in the settings fields of VP Framework.
        */

        $directorist_pages = array(
            'search_listing' => array(
                'title' => __('Search Home', ATBDP_TEXTDOMAIN),
                'content' => '[directorist_search_listing]'
            ),
            'search_result_page' => array(
                'title' => __('Search Result', ATBDP_TEXTDOMAIN),
                'content' => '[directorist_search_result]'
            ),
            'add_listing_page' => array(
                'title' => __('Add Listing', ATBDP_TEXTDOMAIN),
                'content' => '[directorist_add_listing]'
            ),
            'all_listing_page' => array(
                'title' => __('All Listings', ATBDP_TEXTDOMAIN),
                'content' => '[directorist_all_listing]'
            ),
            'all_categories_page' => array(
                'title'   => __( 'All Categories', ATBDP_TEXTDOMAIN ),
                'content' => '[directorist_all_categories]'
            ),
            'all_locations_page' => array(
                'title'   => __( 'All Locations', ATBDP_TEXTDOMAIN ),
                'content' => '[directorist_all_locations]'
            ),
            'author_profile_page' => array(
                'title'   => __( 'Author Profile', ATBDP_TEXTDOMAIN ),
                'content' => '[directorist_author_profile]'
            ),
            'user_dashboard' => array(
                'title' => __('Dashboard', ATBDP_TEXTDOMAIN),
                'content' => '[directorist_user_dashboard]'
            ),
            'custom_registration' => array(
                'title' => __('Registration', ATBDP_TEXTDOMAIN),
                'content' => '[directorist_custom_registration]'
            ), 'user_login' => array(
                'title' => __('Login', ATBDP_TEXTDOMAIN),
                'content' => '[directorist_user_login]'
            ),
            'checkout_page' => array(
                'title' => __('Checkout', ATBDP_TEXTDOMAIN),
                'content' => '[directorist_checkout]'
            ),
            'payment_receipt_page' => array(
                'title' => __('Payment Receipt', ATBDP_TEXTDOMAIN),
                'content' => '[directorist_payment_receipt]'
            ),
            'transaction_failure_page' => array(
                'title' => __('Transaction Failure', ATBDP_TEXTDOMAIN),
                'content' => '[directorist_transaction_failure]'
            ),
        );
        $new_settings = 0; // lets keep track of new settings so that we do not update option unnecessarily.
        // lets iterate over the array and insert a new page with with the appropriate shortcode if the page id is not available in the option array.
        foreach ($directorist_pages as $op_name => $page_settings) {
            // $op_name is the page option name in the database.
            // if we do not have the page id assigned in the settings with the given page option name, then create an page
            // and update the option.
            if (empty($options[$op_name])) {

                $id = wp_insert_post(
                    array(
                        'post_title' => $page_settings['title'],
                        'post_content' => $page_settings['content'],
                        'post_status' => 'publish',
                        'post_type' => 'page',
                        'comment_status' => 'closed'
                    )
                );
                // if we have added the page successfully, lets add the page id to the options array to save the page settings in the database after the loop.
                if ($id) {
                    $options[$op_name] = (int)$id;

                    /*TRYING TO SET THE DEFAULT PAGE TEMPLATE FOR THIS PAGE WHERE OUR SHORTCODE IS USED */
                    // get the template list of the theme and if it has any full width template then assign it.
                    $page_templates = wp_get_theme()->get_page_templates();
                    $custom_template = ''; // place holder for full width template
                    $temp_type = ('search_listing' == $op_name) ? 'home-page.php' : 'full'; // look for home template for search_listing page
                    // lets see if we can find any full width template, then use it for the page where our shortcode is used.
                    foreach ($page_templates as $slug => $name) {
                        // checkout page and payment receipt page looks better on non full-width template, so skip them.
                        if (in_array($op_name, array('checkout_page', 'payment_receipt_page'))) break;
                        if (strpos($slug, $temp_type)) {
                            $custom_template = $slug;
                            break;
                        }
                    }
                    if (!empty($custom_template)) update_post_meta($id, '_wp_page_template', sanitize_text_field($custom_template));


                }
                $new_settings++;
            }else{
                $replace_shortcode = wp_update_post(
                    array(
                            'ID'  => $options[$op_name],
                        'post_title' => $page_settings['title'],
                        'post_content' => $page_settings['content'],
                        'post_status' => 'publish',
                        'post_type' => 'page',
                        'comment_status' => 'closed'
                    ),true
                );
                if (!is_wp_error($replace_shortcode)){
                    update_user_meta( get_current_user_id(), '_atbdp_shortcode_regenerate_notice', 'true' );
                }
            }
            // if we have new options then lets update the options with new option values.
            if ($new_settings) {
                update_option('atbdp_option', $options);
            };
            update_option('atbdp_pages_version', 1);
        }

    }



    /**
     * It displays popular listings
     * @param int $count [optional] Number of popular listing to show. Default 5.
     * If the count is more than one then it uses it, else the function will use the value from the settings page.
     * Count variable is handy if we want to show different number of popular listings on different pages. For example, on different widgets place
     * @todo Try to move popular listings related functionalities to a dedicated listing related class that handles popular listings, related listings etc. when have time.
     */
    public function show_popular_listing($count = 5)
    {
        $popular_listings = $this->get_popular_listings($count);


        if ($popular_listings->have_posts()) { ?>
            <div class="atbd_categorized_listings">
                <ul class="listings">
                    <?php foreach ($popular_listings->posts as $pop_post) {
                        // get only one parent or high level term object
                        $top_category = ATBDP()->taxonomy->get_one_high_level_term($pop_post->ID, ATBDP_CATEGORY);
                        $listing_img = get_post_meta($pop_post->ID, '_listing_img', true);
                        $cats              =  get_the_terms($pop_post->ID, ATBDP_CATEGORY);
                        ?>
                        <li>
                            <div class="atbd_left_img">
                                <?= (!empty($listing_img[0])) ? '<img src="' . esc_url(wp_get_attachment_image_url($listing_img[0], array(90, 90))) . '" alt="listing image">' : '<img src="'.ATBDP_PUBLIC_ASSETS . 'images/grid.jpg'.'" alt="listing image">' ?>
                            </div>
                            <div class="atbd_right_content">
                                <div class="cate_title">
                                    <h4>
                                        <a href="<?= esc_url(get_post_permalink($pop_post->ID)); ?>"><?= esc_html($pop_post->post_title); ?></a>
                                    </h4>
                                </div>

                                    <?php if (!empty($cats)){ ?>

                                    <p class="directory_tag">
                                        <span class="fa <?= esc_attr(get_cat_icon(@$cats[0]->term_id)); ?>" aria-hidden="true"></span>
                                        <span>
                                                <a href="<?= ATBDP_Permalink::get_category_archive($cats[0]); ?>">
                                                                     <?= esc_html($cats[0]->name); ?>
                                                </a>

                                        </span>
                                    </p>
                                <?php }
                                ATBDP()->show_static_rating($pop_post);
                                ?>
                            </div>
                        </li>
                    <?php } // ends the loop
                    ?>

                </ul>
            </div> <!--ends .categorized_listings-->
        <?php }

    }


    /**
     * It gets the popular listings of the given listing/post
     *
     * @param int $count [optional] Number of popular listing to show.  If the count is more than one then it uses it,
     *                   else the function will use the value from the settings page.
     *                   Count variable is handy if we want to show different number of popular listings on different pages.
     *                   For example, on different widgets place. Default 5.
     * @return WP_Query It returns the popular listings if found.
     */
    public function get_popular_listings($count = 5)
    {
        /*Popular post related stuff*/
        $p_count = !empty($count) ? $count : 5;

        /**
         * It filters the number of the popular listing to display
         * @since 1.0.0
         * @param int $p_count The number of popular listing  to show
         */
        $p_count = apply_filters('atbdp_popular_listing_number', $p_count);

        $args = array(
            'post_type' => ATBDP_POST_TYPE,
            'meta_key' => '_atbdp_post_views_count',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'posts_per_page' => (int)$p_count,
            'meta_query' =>
                array(
                    'key' => '_atbdp_post_views_count',
                    'value' => 10,
                    'compare' => '>=',
                )
            /*@todo; later sort by featured listings*/
        );
        return new WP_Query(apply_filters('atbdp_popular_listing_args', $args));

    }

    /**
     * It displays related listings of the given post
     * @param object|WP_Post $post The current post object
     */
    public function show_related_listing($post)
    {

        $enable_rel_listing = get_directorist_option('enable_rel_listing', 1);
        if (1 != $enable_rel_listing) return; // vail if related listing is not enabled
        $related_listings = $this->get_related_listings($post);
        $is_disable_price = get_directorist_option('disable_list_price');


        if ($related_listings->have_posts()) {
            ?>
            <!--Related Listings starts-->
            <div class="atbd_related_listings">
                <div class="atbd_related_listing_title">
                    <h3><?php _e('Related Items', ATBDP_TEXTDOMAIN); ?></h3>
                </div>
                <div class="row" data-uk-grid>
                    <?php foreach ($related_listings->posts as $r_post) {
                        $cats = get_the_terms($r_post->ID, ATBDP_CATEGORY);
                        $locs = get_the_terms($r_post->ID, ATBDP_LOCATION);
                        $featured = get_post_meta($r_post->ID, '_featured', true);
                        $price = get_post_meta($r_post->ID, '_price', true);
                        $price_range = get_post_meta($r_post->ID, '_price_range', true);
                        $listing_img = get_post_meta($r_post->ID, '_listing_img', true);
                        $listing_prv_img = get_post_meta($r_post->ID, '_listing_prv_img', true);
                        $excerpt = get_post_meta($r_post->ID, '_excerpt', true);
                        $tagline = get_post_meta($r_post->ID, '_tagline', true);
                        $address = get_post_meta($r_post->ID, '_address', true);
                        $phone_number = get_post_meta($r_post->ID, '_phone', true);
                        $category = get_post_meta($r_post->ID, '_admin_category_select', true);
                        $post_view = get_post_meta($r_post->ID, '_atbdp_post_views_count', true);
                        $hide_contact_info = get_post_meta($r_post->ID, '_hide_contact_info', true);
                        $disable_contact_info = get_directorist_option('disable_contact_info', 0);
                        $display_title        = get_directorist_option('display_title',1);
                        $display_review       = get_directorist_option('display_review',1);
                        $display_price        = get_directorist_option('display_price',1);
                        $display_category     = get_directorist_option('display_category',1);
                        $display_view_count   = get_directorist_option('display_view_count',1);
                        $display_author_image = get_directorist_option('display_author_image',1);
                        $display_publish_date = get_directorist_option('display_publish_date',1);
                        $display_contact_info    = get_directorist_option('display_contact_info',1);
                        $display_feature_badge_cart     = get_directorist_option('display_feature_badge_cart',1);
                        $display_popular_badge_cart     = get_directorist_option('display_popular_badge_cart',1);
                        $popular_badge_text             = get_directorist_option('popular_badge_text','Popular');
                        $feature_badge_text             = get_directorist_option('feature_badge_text','Feature');
                        /*Code for Business Hour Extensions*/
                        $bdbh = get_post_meta($r_post->ID, '_bdbh', true);
                        $enable247hour = get_post_meta($r_post->ID, '_enable247hour', true);
                        $business_hours = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist
                        /*Code for Business Hour Extensions*/
                        $author_id = get_the_author_meta('ID');
                        $u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
                        $u_pro_pic = wp_get_attachment_image_src($u_pro_pic, 'thumbnail');
                        $avata_img = get_avatar($author_id, 32);
                        $thumbnail_cropping = get_directorist_option('thumbnail_cropping',1);
                        $crop_width                    = get_directorist_option('crop_width', 360);
                        $crop_height                   = get_directorist_option('crop_height', 300);
                        if(!empty($listing_prv_img)) {

                            if($thumbnail_cropping) {

                                $prv_image = atbdp_image_cropping($listing_prv_img, $crop_width, $crop_height, true, 100)['url'];

                            }else{
                                $prv_image   = wp_get_attachment_image_src($listing_prv_img, 'large')[0];
                            }

                        }
                        if(!empty($listing_img[0])) {
                            if( $thumbnail_cropping ) {
                                $gallery_img = atbdp_image_cropping($listing_img[0], $crop_width, $crop_height, true, 100)['url'];

                            }else{
                                $gallery_img = wp_get_attachment_image_src($listing_img[0], 'medium')[0];
                            }

                        }
                        ?>
                        <div class="col-md-6 col-sm-6">
                            <div class="atbd_single_listing atbd_listing_card">
                                <article class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                                    <figure class="atbd_listing_thumbnail_area">
                                        <div class="atbd_listing_image">
                                            <?php if(!empty($listing_prv_img)){

                                                echo '<a href="'.esc_url(get_post_permalink(get_the_ID())).'"><img src="'.esc_url($prv_image).'" alt="listing image"></a>';

                                            } if(!empty($listing_img[0]) && empty($listing_prv_img)) {

                                                echo '<a href="'.esc_url(get_post_permalink(get_the_ID())).'"><img src="' . esc_url($gallery_img) . '" alt="listing image"></a>';

                                            }if (empty($listing_img[0]) && empty($listing_prv_img)){

                                                echo '<a href="'.esc_url(get_post_permalink(get_the_ID())).'"><img src="'.ATBDP_PUBLIC_ASSETS . 'images/grid.jpg'.'" alt="listing image"></a>';

                                            }
                                            ?>
                                        </div>

                                        <figcaption class="atbd_thumbnail_overlay_content">
                                            <?php if (class_exists('BD_Business_Hour')){ ?>
                                            <div class="atbd_upper_badge">
                                                <?php //lets check is it 24/7
                                                if (!empty($enable247hour)) {
                                                    $open =  get_directorist_option('open_badge_text');
                                                    ?>
                                                        <span class="atbd_badge atbd_badge_open"><?php echo $open;?>></span>
                                                    <?php
                                                } else {
                                                    BD_Business_Hour()->show_business_open_close($business_hours); // show the business hour in an unordered list
                                                }?>
                                            </div><!-- END /.atbd_upper_badge -->
                                            <?php  } ?>
                                            <div class="atbd_lower_badge">
                                                <?php
                                                if ($featured && !empty($display_feature_badge_cart)){ printf(
                                                    '<span class="atbd_badge atbd_badge_featured">%s</span>',
                                                    $feature_badge_text
                                                );}
                                                $count = !empty($count) ? $count : '';
                                                $popular_listings = ATBDP()->get_popular_listings($count);
                                                if ($popular_listings->have_posts() && !empty($display_popular_badge_cart)) {
                                                    foreach ($popular_listings->posts as $pop_post) {
                                                        if ($pop_post->ID == get_the_ID()){
                                                            echo ' <span class="atbd_badge atbd_badge_popular">'. $popular_badge_text .'</span>';
                                                        }
                                                    }
                                                }
                                                //print the new badge
                                                echo new_badge();
                                                ?>
                                            </div>
                                        </figcaption>
                                    </figure>

                                    <div class="atbd_listing_info">
                                        <div class="atbd_content_upper">
                                            <?php if(!empty($display_title)) {?>
                                            <h4 class="atbd_listing_title">
                                                <a href="<?= esc_url(get_post_permalink($r_post->ID)); ?>"><?php echo esc_html(stripslashes(get_the_title($r_post->ID))); ?></a>
                                            </h4>
                                            <?php } if (!empty($tagline)) { ?>
                                                <p class="atbd_listing_tagline"><?php echo esc_html(stripslashes($tagline)); ?></p>
                                            <?php } ?>
                                            <?php if(!empty($display_review) && !empty($display_price)) {?>
                                            <div class="atbd_listing_meta">
                                                <?php
                                                /**
                                                 * Fires after the title and sub title of the listing is rendered
                                                 *
                                                 *
                                                 * @since 1.0.0
                                                 */

                                               if(!empty($display_review)) {
                                                do_action('atbdp_after_listing_tagline');
                                                }
                                                if(!empty($display_price)) {
                                                    if(!empty($price_range)) {
                                                     $output = atbdp_display_price_range($price_range);
                                                     echo $output;
                                                    }else{
                                                        atbdp_display_price($price, $is_disable_price);
                                                    }
                                                }

                                                /**
                                                 * Fires after the price of the listing is rendered
                                                 *
                                                 *
                                                 * @since 3.1.0
                                                 */
                                                do_action('atbdp_after_listing_price');
                                                ?>
                                            </div><!-- End atbd listing meta -->

                                            <?php } ?>
                                            <?php if(!empty($display_contact_info) || !empty($display_publish_date)) {?>
                                                <div class="atbd_listing_data_list">
                                                    <ul>
                                                        <?php
                                                        if (!empty($display_contact_info)) {
                                                            if( !empty( $address )) { ?>
                                                                <li><p><span class="fa fa-location-arrow"></span><?php echo esc_html(stripslashes($address));?></p></li>
                                                            <?php } ?>
                                                            <?php if( !empty( $phone_number )) {?>
                                                                <li><p><span class="fa fa-phone"></span><?php echo esc_html(stripslashes($phone_number));?></p></li>
                                                                <?php
                                                            } }

                                                        if(!empty($display_publish_date)) { ?>
                                                            <li><p><span class="fa fa-clock-o"></span><?php
                                                                    printf( __( 'Posted %s ago', ATBDP_TEXTDOMAIN ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
                                                                    ?></p></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div><!-- End atbd listing meta -->
                                                <?php
                                            }
                                            //show category and location info
                                            /*ATBDP()->helper->output_listings_taxonomy_info($top_category, $deepest_location);*/ ?>
                                            <?php if (!empty($excerpt)) { ?>
                                                <p><?php echo esc_html(stripslashes(wp_trim_words($excerpt, 30))); ?></p>
                                            <?php } ?>
                                            <?php /* @todo: deleted the read more link */ ?>
                                        </div><!-- end ./atbd_content_upper -->
                                        <?php if(!empty($display_category) || !empty($$display_view_count) || !empty($display_author_image)) {?>
                                        <div class="atbd_listing_bottom_content">
                                            <?php
                                            if(!empty($display_category)) {
                                            if (!empty($cats)) { ?>
                                                <div class="atbd_content_left">
                                                    <div class="atbd_listting_category">
                                                        <a href="<?php echo esc_url(ATBDP_Permalink::get_category_archive($cats[0]));;?>"><?php if ('none' != get_cat_icon($cats[0]->term_id)){ ?>
                                                                <span class="fa <?php echo esc_attr(get_cat_icon($cats[0]->term_id)); ?>"></span> <?php }?><?php  echo $cats[0]->name;?></a>
                                                    </div>
                                                </div>
                                            <?php } else {
                                                ?>
                                                <div class="atbd_content_left">
                                                    <div class="atbd_listting_category">
                                                        <a href=""><?php echo __('Uncategorized', ATBDP_TEXTDOMAIN); ?>
                                                        </a>
                                                    </div>
                                                </div>

                                            <?php } }?>
                                            <?php if(!empty($display_view_count) || !empty($display_author_image)) {?>
                                                <ul class="atbd_content_right">
                                                    <?php if(!empty($display_view_count)) {?>
                                                        <li class="atbd_count"><span class="fa fa-eye"></span><?php echo !empty($post_view) ? $post_view : 0 ;?></li> <?php } ?>


                                                    <li class="atbd_save">
                                                        <div id="atbdp-favourites-all-listing">
                                                            <input type="hidden" id="listing_ids" value="<?php echo get_the_ID(); ?>">
                                                            <?php
                                                            // do_action('wp_ajax_atbdp-favourites-all-listing', get_the_ID()); ?>
                                                        </div>
                                                    </li>
                                                    <?php if(!empty($display_author_image)) {
                                                        $author = get_userdata(get_current_user_id());
                                                        ?>
                                                        <li class="atbd_author">
                                                            <a href="<?= ATBDP_Permalink::get_user_profile_page_link($author_id); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $author->first_name.' '.$author->last_name;?>"><?php if (empty($u_pro_pic)) {echo $avata_img;} if (!empty($u_pro_pic)) { ?>
                                                                    <img
                                                                    src="<?php echo esc_url($u_pro_pic[0]); ?>"
                                                                    alt="Author Image"><?php } ?>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } ?>
                                        </div><!-- end ./atbd_listing_bottom_content -->
                                        <?php } ?>
                                    </div>
                                </article>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div> <!--ends .related_listings-->
        <?php }

    }


    /**
     * It gets the related listings of the given listing/post
     * @param object|WP_Post $post The WP Post Object of whose related listing we would like to show
     * @return object|WP_Query It returns the related listings if found.
     */
    public function get_related_listings($post)
    {
        $rel_listing_num = get_directorist_option('rel_listing_num', 2);
        $atbd_cats = get_the_terms($post, ATBDP_CATEGORY);
        $atbd_tags = get_the_terms($post, ATBDP_TAGS);
        // get the tag ids of the listing post type
        $atbd_cats_ids = array();
        $atbd_tags_ids = array();

        if (!empty($atbd_cats)) {
            foreach ($atbd_cats as $atbd_cat) {
                $atbd_cats_ids[] = $atbd_cat->term_id;
            }
        }
        if (!empty($atbd_tags)) {
            foreach ($atbd_tags as $atbd_tag) {
                $atbd_tags_ids[] = $atbd_tag->term_id;
            }
        }
        $args = array(
            'post_type' => ATBDP_POST_TYPE,
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => ATBDP_CATEGORY,
                    'field' => 'term_id',
                    'terms' => $atbd_cats_ids,
                ),
                array(
                    'taxonomy' => ATBDP_TAGS,
                    'field' => 'term_id',
                    'terms' => $atbd_tags_ids,
                ),
            ),
            'posts_per_page' => (int)$rel_listing_num,
            'post__not_in' => array($post->ID),
        );

        return new WP_Query(apply_filters('atbdp_related_listing_args', $args));

    }

    /**
     * It gets the related listings widget of the given listing/post
     * @param object|WP_Post $post The WP Post Object of whose related listing we would like to show
     * @return object|WP_Query It returns the related listings if found.
     */
    public function get_related_listings_widget($post, $count)
    {
        $rel_listing_num = !empty($count) ? $count : 5;
        $atbd_cats = get_the_terms($post, ATBDP_CATEGORY);
        $atbd_tags = get_the_terms($post, ATBDP_TAGS);
        // get the tag ids of the listing post type
        $atbd_cats_ids = array();
        $atbd_tags_ids = array();

        if (!empty($atbd_cats)) {
            foreach ($atbd_cats as $atbd_cat) {
                $atbd_cats_ids[] = $atbd_cat->term_id;
            }
        }
        if (!empty($atbd_tags)) {
            foreach ($atbd_tags as $atbd_tag) {
                $atbd_tags_ids[] = $atbd_tag->term_id;
            }
        }
        $args = array(
            'post_type' => ATBDP_POST_TYPE,
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => ATBDP_CATEGORY,
                    'field' => 'term_id',
                    'terms' => $atbd_cats_ids,
                ),
                array(
                    'taxonomy' => ATBDP_TAGS,
                    'field' => 'term_id',
                    'terms' => $atbd_tags_ids,
                ),
            ),
            'posts_per_page' => (int)$rel_listing_num,
            'post__not_in' => array($post->ID),
        );

        return new WP_Query(apply_filters('atbdp_related_listing_args', $args));

    }

    public function show_review_after_tagliine()
    {
        $enable_review = get_directorist_option('enable_review', 1);
        if (!$enable_review) return; // vail if review is not enabled
        global $post;
        $average = ATBDP()->review->get_average($post->ID);
        $reviews_count = ATBDP()->review->db->count(array('post_id' => $post->ID)); // get total review count for this post

        ?>
        <span class="atbd_meta atbd_listing_rating">
            <?php echo $average; ?><i class="fa fa-star"></i>
        </span>
        <?php
    }

    /**
     * It displays reviews of the given post
     * @param object|WP_Post $post The current post object
     */
    public function show_review($post)
    {
        $enable_review = get_directorist_option('enable_review', 1);
        if (!$enable_review) return; // vail if review is not enabled
        $enable_owner_review = get_directorist_option('enable_owner_review');
        $review_num = get_directorist_option('review_num', 5); // how many reviews to show?
        $reviews = ATBDP()->_get_reviews($post, $review_num);
        $reviews_count = ATBDP()->review->db->count(array('post_id' => $post->ID)); // get total review count for this post

        ?>

        <!-- Review_area Section-->
        <?php /*@todo shahadat -> restructured the review area*/
        ?>
        <div class="atbd_content_module atbd_review_module">
            <div class="atbd_content_module__tittle_area">
                <div class="atbd_area_title">
                    <h4><span class="fa fa-star atbd_area_icon"></span><?php echo $reviews_count;
                        _e($reviews_count > 1 ? ' Reviews' : ' Review', ATBDP_TEXTDOMAIN);
                        ?></h4>
                </div>
                <?php if (is_user_logged_in()) { ?>
                    <label for="review_content" class="btn btn-primary btn-sm">Add a review</label>

                <?php } ?>
            </div>

            <div class="atbdb_content_module_contents">
                <div id="client_review_list">
                    <?php if (!empty($reviews)) {
                        ?>
                        <?php foreach ($reviews as $review) {
                            ?>
                            <div class="atbd_single_review" id="single_review_<?= $review->id; ?>">
                                <div class="atbd_review_top">
                                    <div class="atbd_avatar_wrapper">
                                        <?php $avata_img = get_avatar($review->by_user_id, 32); ?>
                                        <div class="atbd_review_avatar"><?php if ($avata_img) {
                                                echo $avata_img;
                                            } else { ?><img
                                                src="<?php echo ATBDP_PUBLIC_ASSETS . 'images/revav.png' ?>"
                                                alt="Avatar Image"><?php } ?></div>
                                        <div class="atbd_name_time">
                                            <p><?= esc_html($review->name); ?></p>
                                            <span class="review_time"><?php
                                                printf(__('%s ago', ATBDP_TEXTDOMAIN), human_time_diff(strtotime($review->date_created), current_time('timestamp'))); ?></span>
                                        </div>
                                    </div>
                                    <div class="atbd_rated_stars">
                                        <?= ATBDP()->review->print_static_rating($review->rating); ?>
                                    </div>
                                </div>
                                <div class="review_content">
                                    <p><?= esc_html($review->content); ?></p>
                                    <!--<a href="#"><span class="fa fa-mail-reply-all"></span>Reply</a>-->
                                </div>
                            </div>
                        <?php }
                    } else { ?>
                        <div class="notice alert alert-info" role="alert" id="review_notice">
                            <span class="fa fa-info-circle" aria-hidden="true"></span>
                            <?php _e('No reviews found. Be the first to post a review !', ATBDP_TEXTDOMAIN);
                            ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div><!-- end .atbd_review_module -->


        <?php
        // check if the user is logged in and the current user is not the owner of this listing.
        if (is_user_logged_in()) {
            global $wpdb;
            // if the current user is NOT the owner of the listing print review form
            // get the settings of the admin whether to display review form even if the user is the owner of the listing.
            if (get_current_user_id() != $post->post_author || $enable_owner_review) {

                // if user has a review then fetch it.
                $cur_user_review = ATBDP()->review->db->get_user_review_for_post(get_current_user_id(), get_the_ID());
                ?>
                <div class="atbd_content_module">
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4><span class="fa fa-star"
                                      aria-hidden="true"></span><?= !empty($cur_user_review) ? __('Update Review', ATBDP_TEXTDOMAIN) : __('Leave a Review', ATBDP_TEXTDOMAIN); ?>
                            </h4>
                        </div>
                    </div>

                    <div class="atbdb_content_module_contents atbd_give_review_area">
                        <form action="" id="atbdp_review_form" method="post">
                            <?php wp_nonce_field('atbdp_review_action_form', 'atbdp_review_nonce_form'); ?>
                            <input type="hidden" name="post_id" value="<?php the_ID(); ?>">
                            <!--<input type="email" name="email" class="directory_field" placeholder="Your email" required>-->
                            <input type="hidden" name="name" class="btn btn-default"
                                   value="<?= wp_get_current_user()->display_name; ?>"
                                   placeholder="<?php esc_attr_e('Your name', ATBDP_TEXTDOMAIN); ?>" id="reviewer_name">

                            <div class="atbd_review_rating_area"> <!--It should be displayed on the left side -->
                                <?php
                                // color the stars if user has rating
                                if (!empty($cur_user_review)) { ?>
                                    <div class="atbd_review_current_rating">
                                        <p class="atbd_rating_label"><?php _e('Current Rating:', ATBDP_TEXTDOMAIN); ?></p>
                                        <div class="atbd_rated_stars">
                                            <?= ATBDP()->review->print_static_rating($cur_user_review->rating); ?>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="atbd_review_update_rating">
                                    <p class="atbd_rating_label"><?= !empty($cur_user_review) ? __('Update Rating:', ATBDP_TEXTDOMAIN) : __('Your Rating:', ATBDP_TEXTDOMAIN); ?></p>
                                    <div class="atbd_rating_stars">
                                        <select class="stars" name="rating" id="review_rating">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea name="content" id="review_content" class="form-control" cols="20" rows="5"
                                          placeholder="<?= !empty($cur_user_review) ? __('Update your review.....', ATBDP_TEXTDOMAIN) : __('Write your review.....', ATBDP_TEXTDOMAIN); ?>"><?= !empty($cur_user_review) ? $cur_user_review->content : ''; ?></textarea>
                            </div>

                            <!-- <div class="form-group">
                                 <div id="atbd_up_preview"></div>
                                 <div class="atbd_upload_btn_wrap">
                                     <label for="atbd_review_attachment">
                                         <input type="file" id="atbd_review_attachment" hidden multiple>
                                         <span class="btn atbd_upload_btn"><span class="fa fa-upload"></span>Upload Photo</span>
                                     </label>
                                 </div>
                             </div>-->

                            <!--If current user has a review then show him update and delete button-->
                            <?php if (!empty($cur_user_review)) { ?>
                                <button class="<?= atbdp_directorist_button_classes(); ?>" type="submit"
                                        id="atbdp_review_form_submit"><?php _e('Update', ATBDP_TEXTDOMAIN); ?></button> <!-- ends update  button -->
                                <button class="btn btn-danger" type="button" id="atbdp_review_remove"
                                        data-review_id="<?= $cur_user_review->id; ?>"><?php _e('Remove', ATBDP_TEXTDOMAIN); ?></button> <!-- ends delete button -->
                            <?php } else { ?>
                                <button class="btn btn-primary" type="submit"
                                        id="atbdp_review_form_submit"><?php _e('Submit Review', ATBDP_TEXTDOMAIN); ?></button> <!-- submit button -->
                            <?php } ?>
                        </form>
                    </div>
                </div><!-- end .atbd_custom_fields_contents -->
            <?php };
        } else { ?>
            <div class="atbd_notice alert alert-info" role="alert">
                <span class="fa fa-info-circle" aria-hidden="true"></span>
                <?php
                // get the custom registration page id from the db and create a permalink
                $reg_link_custom = ATBDP_Permalink::get_registration_page_link();
                //if we have custom registration page, use it, else use the default registration url.
                $reg_link = !empty($reg_link_custom) ? $reg_link_custom : wp_registration_url();

                $login_url = '<a href="' . esc_url(wp_login_url()) . '">' . __('Login', ATBDP_TEXTDOMAIN) . '</a>';
                $register_url = '<a href="' . esc_url($reg_link) . '">' . __('Register', ATBDP_TEXTDOMAIN) . '</a>';

                printf(__('You need to %s or %s to submit a review', ATBDP_TEXTDOMAIN), $login_url, $register_url);
                ?>
            </div>
        <?php } ?>
        <?php

        // if the count of review is more than the number of showing reviews then show the more review button, eg. here we will show the read more button  if the number of the review in the database is more than $review_num=5 default
        if (!empty($reviews_count) && $reviews_count > $review_num) {
            echo "<button class='directory_btn' type='button' id='load_more_review' data-id='{$post->ID}''>" . __('View More Review', ATBDP_TEXTDOMAIN) . "</button>";
        }

    }


    /**
     * It gets the reviews of the given listing/post
     * @param object|WP_Post $post The WP Post Object of whose review we would like to show
     * @param int $review_number The number of reviews to return, Default 5
     * @return object|WP_Query It returns the reviews if found.
     */
    private function _get_reviews($post, $review_number = 5)
    {

        return ATBDP()->review->db->get_reviews_by('post_id', $post->ID, 0, $review_number); // get the amount of reviews set by $review_number
    }


    /**
     * It displays static rating of the given post
     * @param object|WP_Post $post The current post object
     */
    public function show_static_rating($post)
    {
        $enable_review = get_directorist_option('enable_review', 1);
        if (!$enable_review) return; // vail if review is not enabled
        $average = ATBDP()->review->get_average($post->ID);
        ?>
        <div class="atbd_rated_stars">
            <?= ATBDP()->review->print_static_rating($average); ?>
        </div>
        <?php

    }

    public function add_custom_meta_keys_for_old_listings()
    {
        // get all the listings that does not have any of the following meta key missing
        // loop through then and find which one does not contain a meta key
        // if they return false then add new meta keys to them
        $args = array(
            'post_type' => ATBDP_POST_TYPE,
            'post_status' => 'any',
            'posts_per_page' => -1,
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => '_featured',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key' => '_expiry_date',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key' => '_never_expire',
                    'compare' => 'NOT EXISTS',
                ),
                array(
                    'key' => '_listing_status',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key' => '_price',
                    'compare' => 'NOT EXISTS',
                ),
            )

        );
        $listings = new WP_Query($args);

        foreach ($listings->posts as $l) {
            $ft = get_post_meta($l->ID, '_featured', true);
            $ep = get_post_meta($l->ID, '_expiry_date', true);
            $np = get_post_meta($l->ID, '_never_expire', true);
            $ls = get_post_meta($l->ID, '_listing_status', true);
            $pr = get_post_meta($l->ID, '_price', true);
            $exp_d = calc_listing_expiry_date();
            if (empty($ft)) {
                update_post_meta($l->ID, '_featured', 0);
            }
            if (empty($ep)) {
                update_post_meta($l->ID, '_expiry_date', $exp_d);
            }
            if (empty($np)) {
                update_post_meta($l->ID, '_never_expire', 0);
            }
            if (empty($ls)) {
                update_post_meta($l->ID, '_listing_status', 'post_status');
            }
            if (empty($pr)) {
                update_post_meta($l->ID, '_price', 0);
            }
        }
        // update db version to avoid duplication
        update_option('atbdp_meta_version', 1);

    }

    /**
     * Parse the video URL and determine it's valid embeddable URL for usage.
     */
    public function atbdp_parse_videos($url)
    {

        $embeddable_url = '';

        // Check for YouTube
        $is_youtube = preg_match('/youtu\.be/i', $url) || preg_match('/youtube\.com\/watch/i', $url);

        if ($is_youtube) {
            $pattern = '/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/';
            preg_match($pattern, $url, $matches);
            if (count($matches) && strlen($matches[7]) == 11) {
                $embeddable_url = 'https://www.youtube.com/embed/' . $matches[7];
            }
        }

        // Check for Vimeo
        $is_vimeo = preg_match('/vimeo\.com/i', $url);

        if ($is_vimeo) {
            $pattern = '/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/';
            preg_match($pattern, $url, $matches);
            if (count($matches)) {
                $embeddable_url = 'https://player.vimeo.com/video/' . $matches[2];
            }
        }

        // Return
        return $embeddable_url;

    }

    public function atbdp_body_class($c_classes)
    {
        $c_classes[] = 'atbd_content_active';//class name goes here
        return $c_classes;
    }

} // ends Directorist_Base


/**
 * The main function for that returns Directorist_Base
 *
 * The main function responsible for returning the one true Directorist_Base
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $ATBDP = ATBDP(); ?>
 *
 * @since 1.0
 * @return object|Directorist_Base The one true Directorist_Base Instance.
 */
function ATBDP()
{
    return Directorist_Base::instance();
}

// Get ATBDP ( AazzTech Business Directory Plugin) Running.
ATBDP();

register_activation_hook(__FILE__, array('Directorist_Base', 'prepare_plugin'));