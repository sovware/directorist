<?php
/**
 * Plugin Name: Directorist - Business Directory Plugin
 * Plugin URI: https://wpwax.com
 * Description: A comprehensive solution to create professional looking directory site of any kind. Like Yelp, Foursquare, etc.
 * Version: 7.0.3.2
 * Author: wpWax
 * Author URI: https://wpwax.com
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

Copyright (c) 2020 wpWax (website: wpwax.com). All rights reserved.
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
     * ATBDP_SEO Object.
     *
     * @var ATBDP_SEO
     * @since 4.7.0
     */
    public $seo;

    /**
     * ATBDP_Tools Object.
     *
     * @var ATBDP_Tools
     * @since 4.7.2
     */
    public $tools;

    /**
     * ATBDP_Single_Templates Object.
     *
     * @var ATBDP_Single_Templates
     * @since 5.0.5
     */
    public $ATBDP_Single_Templates;

    /**
     * ATBDP_Review_Custom_Post Object.
     *
     * @var ATBDP_Review_Custom_Post
     * @since 5.6.5
     */
    public $ATBDP_Review_Custom_Post;

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
            add_action('plugins_loaded', array(self::$instance, 'add_polylang_swicher_support') );
            add_action('widgets_init', array(self::$instance, 'register_widgets'));

            add_action( 'template_redirect', [ self::$instance, 'check_single_listing_page_restrictions' ] );
            add_action( 'atbdp_show_flush_messages', [ self::$instance, 'show_flush_messages' ] );

            self::$instance->includes();

            self::$instance->custom_post = new ATBDP_Custom_Post; // create custom post
            self::$instance->taxonomy = new ATBDP_Custom_Taxonomy;

            self::$instance->enquirer = new ATBDP_Enqueuer;
            self::$instance->enqueue_assets = new Directorist\Enqueue_Assets;

            // ATBDP_Listing_Type_Manager
            self::$instance->multi_directory_manager = new Directorist\Multi_Directory_Manager;
            self::$instance->multi_directory_manager->run();

            self::$instance->settings_panel = new ATBDP_Settings_Panel;
            self::$instance->settings_panel->run();

            self::$instance->hooks = new ATBDP_Hooks;
            self::$instance->metabox = new ATBDP_Metabox;
            self::$instance->ajax_handler = new ATBDP_Ajax_Handler;
            self::$instance->helper = new ATBDP_Helper;
            self::$instance->listing = new ATBDP_Listing;
            self::$instance->user = new ATBDP_User;
            self::$instance->roles = new ATBDP_Roles;
            self::$instance->gateway = new ATBDP_Gateway;
            self::$instance->order = new ATBDP_Order;
            self::$instance->shortcode = new \Directorist\ATBDP_Shortcode;
            self::$instance->email = new ATBDP_Email;
            self::$instance->seo = new ATBDP_SEO;
            // self::$instance->validator = new ATBDP_Validator;
            // self::$instance->ATBDP_Single_Templates = new ATBDP_Single_Templates;
            self::$instance->tools = new ATBDP_Tools;
            self::$instance->announcement = new ATBDP_Announcement;
            self::$instance->ATBDP_Review_Custom_Post = new ATBDP_Review_Custom_Post;
            self::$instance->update_database();
            
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
            add_filter('body_class', array(self::$instance, 'atbdp_body_class'), 99);

            // display related listings
            // add_action('atbdp_after_single_listing', array(self::$instance, 'show_related_listing'));

            //review and rating
            add_action('atbdp_after_map', array(self::$instance, 'show_review'));
            // plugin deactivated popup
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(self::$instance, 'atbdp_plugin_link'));
            add_action('admin_footer', array(self::$instance, 'atbdp_deactivate_popup'));

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
            // add uninstall menu
            add_filter('atbdp_settings_menus', array(self::$instance, 'add_uninstall_menu'));

            self::init_hooks();

            // Initialize appsero tracking
            self::$instance->init_appsero();
        }

        return self::$instance;
    }

    // show_flush_messages
    public function show_flush_messages() {
        atbdp_get_flush_messages();
    }

    // check_single_listing_page_restrictions
    public function check_single_listing_page_restrictions() {
        $restricted_for_logged_in_user = get_directorist_option( 'restrict_single_listing_for_logged_in_user', false );
        $current_user_id = get_current_user_id();

        if ( is_singular( ATBDP_POST_TYPE ) && ! empty( $restricted_for_logged_in_user ) && empty( $current_user_id ) ) {

            atbdp_auth_guard();
            die;
        }
    }

    // add_polylang_swicher_support
    public function add_polylang_swicher_support() {
        add_filter('pll_the_language_link', function($url, $current_lang) {
            // Adjust the category link
            $category_url = $this->get_polylang_swicher_link_for_term([
                'term_type'            => 'category',
                'term_default_page_id' => get_directorist_option('single_category_page'),
                'term_query_var'       => ( ! empty( $_GET['category'] ) ) ? $_GET['category'] : get_query_var('atbdp_category'),
                'current_lang'         => $current_lang,
                'url'                  => $url,
            ]);

            if ( ! empty( $category_url ) ) { return $category_url; }

            // Adjust the location link
            $location_url = $this->get_polylang_swicher_link_for_term([
                'term_type'            => 'location',
                'term_default_page_id' => get_directorist_option('single_location_page'),
                'term_query_var'       => ( ! empty( $_GET['location'] ) ) ? $_GET['location'] : get_query_var('atbdp_location'),
                'current_lang'         => $current_lang,
                'url'                  => $url,
            ]);

            if ( ! empty( $location_url ) ) { return $location_url; }

            return $url;
        }, 10, 2);
    }

    // get_polylang_swicher_link_for_term
    public function get_polylang_swicher_link_for_term( $args ) {
        $default = [
            'term_type'            => '',
            'term_query_var'       => '',
            'term_default_page_id' => '',
            'current_lang'         => '',
            'url'                  => '',
        ];

        $args = array_merge( $default, $args );

        if ( empty( $args[ 'term_query_var' ] ) ) { return false; }

        // Get language slug of the default page
        $page_lang = pll_get_post_language( $args[ 'term_default_page_id' ] );

        // If current lang slug != default page
        // modyfy the url
        if ( $args[ 'current_lang' ] !== $page_lang ) {
            return $args['url'] ."?". $args['term_type'] ."=". $args['term_query_var'];
        }

        if ( $args[ 'current_lang' ] === $page_lang  ) {
            return $args['url'] . $args['term_query_var'];
        }

        return false;
    }

    /**
     * Update Database
     *
     * @access private
     * @since 6.4.4
     * @return void
     */
    private function update_database()
    {
        $this->update_review_table();
    }

    /**
     * Init Hooks
     *
     * @access private
     * @since 6.4.5
     * @return void
     */
    public static function init_hooks()
    {
        ATBDP_Cache_Helper::reset_cache();
    }


    /**
     * Update Review Table
     *
     * @access private
     * @since 6.4.4
     * @return void
     */
    private function update_review_table()
    {
        $current_charset_collate = get_option('atbdp_review_table_charset_collate');
        $review_rating = new ATBDP_Review_Rating_DB();

        $charset_collate = $review_rating->get_charset_collate();

        if ( $charset_collate !== $current_charset_collate ) {
            add_action('admin_init', array( $review_rating, 'update_table_collation'));
            update_option('atbdp_review_table_charset_collate', $charset_collate);
        }
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

    function autoload( $dir = '' ) {
        if ( !file_exists( $dir ) ) return;
        foreach ( scandir( $dir ) as $file ) {
            if ( preg_match( "/.php$/i", $file ) ) {
                require_once( $dir . $file );
            }
        }
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
        $this->autoload( ATBDP_INC_DIR . 'helpers/' );

        self::require_files([
            ATBDP_INC_DIR . 'class-helper',
            ATBDP_INC_DIR . 'helper-functions',
            ATBDP_INC_DIR . 'template-functions',
            ATBDP_INC_DIR . 'custom-actions',
            ATBDP_INC_DIR . 'custom-filters',
            ATBDP_INC_DIR . 'elementor/init',
            ATBDP_INC_DIR . 'system-status/class-system-status'
        ]);

        load_dependencies('all', ATBDP_INC_DIR . 'data-store/');

        if ( \Directorist\Helper::is_legacy_mode() ) {
            load_dependencies('all', ATBDP_INC_DIR . 'model-legacy/');
        }
        else {
            load_dependencies('all', ATBDP_INC_DIR . 'model/');
        }

        load_dependencies('all', ATBDP_INC_DIR . 'hooks/');
        load_dependencies('all', ATBDP_INC_DIR . 'modules/');
        load_dependencies('all', ATBDP_INC_DIR . 'modules/multi-directory-setup/');

        load_dependencies('all', ATBDP_CLASS_DIR); // load all php files from ATBDP_CLASS_DIR

        /*LOAD Rating and Review functionality*/
        load_dependencies('all', ATBDP_INC_DIR . 'review-rating/');
        /*Load gateway related stuff*/
        load_dependencies('all', ATBDP_INC_DIR . 'gateways/');
        /*Load payment related stuff*/
        load_dependencies('all', ATBDP_INC_DIR . 'payments/');
        load_dependencies('all', ATBDP_INC_DIR . 'checkout/');


    }

    // require_files
    public static function require_files( array $files = [] ) {
        foreach ( $files as $file ) {
            if ( file_exists( "{$file}.php" ) ) {
                require_once "{$file}.php";
            }
        }
    }

    public static function prepare_plugin()
    {
        include ATBDP_INC_DIR . 'classes/class-installation.php';
        ATBDP_Installation::install();
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
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'directorist'), '1.0');
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
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'directorist'), '1.0');
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
                'name' => apply_filters('atbdp_right_sidebar_name', __('Directorist - Listing Right Sidebar', 'directorist')),
                'id' => 'right-sidebar-listing',
                'description' => __('Add widgets for the right sidebar on single listing page', 'directorist'),
                'before_widget' => '<div class="widget atbd_widget %2$s">',
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
        // register_widget('BD_All_Map_Widget');
        register_widget('BD_Similar_Listings_Widget');
        register_widget('BD_Author_Info_Widget');
        register_widget('BD_Featured_Listings_Widget');

    }

    public function load_textdomain()
    {

        load_plugin_textdomain('directorist', false, ATBDP_LANG_DIR);
        if ( get_transient( '_directorist_setup_page_redirect' ) ) {
            directorist_redirect_to_admin_setup_wizard();
        }
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
        $path = ATBDP_VIEWS_DIR . $name . '.php';
        if ($return_path) return $path;
        include($path);
    }

    public function add_custom_directorist_pages()
    {
        $create_permission = apply_filters('atbdp_create_required_pages', true);
        if ($create_permission){
            atbdp_create_required_pages();
        }
    }

    public function add_uninstall_menu($menus) {
        $menus['uninstall_menu'] = array(
            'title' => __('Uninstall', 'directorist'),
            'name' => 'uninstall_menu',
            'icon' => 'font-awesome:fa-window-close',
            'controls' => apply_filters('atbdp_uninstall_settings_controls', array(
                'currency_section' => array(
                    'type' => 'section',
                    'title' => __('Uninstall Settings', 'directorist'),
                    'fields' => get_uninstall_settings_submenus(),
                ),
            )),
        );
        $menus['csv_import'] = array(
            'title' => __('Listings Import', 'directorist'),
            'name' => 'csv_import',
            'icon' => 'font-awesome:fa-upload',
            'controls' => apply_filters('atbdp_csv_import_settings_controls', array(
                'currency_section' => array(
                    'type' => 'section',
                    'title' => __('Listings Import', 'directorist'),
                    'fields' => get_csv_import_settings_submenus(),
                ),
            )),
        );
        return $menus;
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
                        $listing_prv_img = get_post_meta($pop_post->ID, '_listing_prv_img', true);
                        $cats = get_the_terms($pop_post->ID, ATBDP_CATEGORY);
                        ?>
                        <li>
                            <div class="atbd_left_img">
                                <?php
                                $disable_single_listing = get_directorist_option('disable_single_listing');
                                if (empty($disable_single_listing)){
                                ?>
                                <a href="<?php echo esc_url(get_post_permalink($pop_post->ID)); ?>">
                                    <?php
                                    }
                                    $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                                    if (!empty($listing_prv_img)) {
                                        echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_prv_img, array(90, 90))) . '" alt="' . esc_html($pop_post->post_title) . '">';
                                    } elseif (!empty($listing_img[0]) && empty($listing_prv_img)) {
                                        echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_img[0], array(90, 90))) . '" alt="' . esc_html($pop_post->post_title) . '">';
                                    } else {
                                        echo '<img src="' . $default_image . '" alt="' . esc_html($pop_post->post_title) . '">';
                                    }
                                    if (empty($disable_single_listing)) {
                                        echo '</a>';
                                    }
                                    ?>
                            </div>
                            <div class="atbd_right_content">
                                <div class="cate_title">
                                    <h4>
                                        <?php
                                        if (empty($disable_single_listing)) {
                                            ?>
                                            <a href="<?php echo esc_url(get_post_permalink($pop_post->ID)); ?>"><?php echo esc_html($pop_post->post_title); ?></a>
                                            <?php
                                        } else {
                                            echo esc_html($pop_post->post_title);
                                        } ?>
                                    </h4>
                                </div>

                                <?php if (!empty($cats)) {
                                    $totalTerm = count($cats);
                                    ?>

                                    <p class="directory_tag">
                                        <span class="<?php atbdp_icon_type(true); ?>-tags"></span>
                                        <span>
                                                <a href="<?php echo ATBDP_Permalink::atbdp_get_category_page($cats[0]); ?>">
                                                                     <?php echo esc_html($cats[0]->name); ?>
                                                </a>
                                            <?php
                                            if ($totalTerm > 1) {
                                                ?>
                                                <span class="atbd_cat_popup">  +<?php echo $totalTerm - 1; ?>
                                                    <span class="atbd_cat_popup_wrapper">
                                                                    <?php
                                                                    $output = array();
                                                                    foreach (array_slice($cats, 1) as $cat) {
                                                                        $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                                        $space = str_repeat(' ', 1);
                                                                        $output [] = "{$space}<a href='{$link}'>{$cat->name}<span>,</span></a>";
                                                                    } ?>
                                                        <span><?php echo join($output) ?></span>
                                                                </span>
                                                            </span>
                                            <?php } ?>

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
    public function get_popular_listings($count = 5, $listing_id = '')
    {
        /*Popular post related stuff*/
        $p_count = !empty($count) ? $count : 5;

        $view_to_popular = get_directorist_option('views_for_popular');
        /**
         * It filters the number of the popular listing to display
         * @since 1.0.0
         * @param int $p_count The number of popular listing  to show
         */
        $p_count = apply_filters('atbdp_popular_listing_number', $p_count);
        $args = array(
            'post_type' => ATBDP_POST_TYPE,
            'post_status' => 'publish',
            'posts_per_page' => (int)$p_count,

        );
        $has_featured = get_directorist_option('enable_featured_listing');
        if ($has_featured || is_fee_manager_active()) {
            $has_featured = 1;
        }

        $listings = get_atbdp_listings_ids();
        $rated = array();
        $listing_popular_by = get_directorist_option('listing_popular_by');
        $average_review_for_popular = get_directorist_option('average_review_for_popular', 4);
        $view_to_popular = get_directorist_option('views_for_popular');

        $meta_queries = array();
        if ($has_featured) {
            if ('average_rating' === $listing_popular_by) {
                if ($listings->have_posts()) {
                    while ($listings->have_posts()) {
                        $listings->the_post();
                        $listing_id = get_the_ID();
                        $average = ATBDP()->review->get_average($listing_id);
                        if ($average_review_for_popular <= $average) {
                            $rated[] = get_the_ID();
                        }

                    }
                    $rating_id = array(
                        'post__in' => !empty($rated) ? $rated : array()
                    );
                    $args = array_merge($args, $rating_id);
                }
            } elseif ('view_count' === $listing_popular_by) {
                $meta_queries['views'] = array(
                    'key' => '_atbdp_post_views_count',
                    'value' => $view_to_popular,
                    'type' => 'NUMERIC',
                    'compare' => '>=',
                );

                $args['orderby'] = array(
                    '_featured' => 'DESC',
                    'views' => 'DESC',
                );

            } else {
                $meta_queries['views'] = array(
                    'key' => '_atbdp_post_views_count',
                    'value' => $view_to_popular,
                    'type' => 'NUMERIC',
                    'compare' => '>=',
                );
                $args['orderby'] = array(
                    '_featured' => 'DESC',
                    'views' => 'DESC',
                );
                if ($listings->have_posts()) {
                    while ($listings->have_posts()) {
                        $listings->the_post();
                        $listing_id = get_the_ID();
                        $average = ATBDP()->review->get_average($listing_id);
                        if ($average_review_for_popular <= $average) {
                            $rated[] = get_the_ID();
                        }

                    }
                    $rating_id = array(
                        'post__in' => !empty($rated) ? $rated : array()
                    );
                    $args = array_merge($args, $rating_id);
                }
            }

        } else {
            if ('average_rating' === $listing_popular_by) {
                if ($listings->have_posts()) {
                    while ($listings->have_posts()) {
                        $listings->the_post();
                        $listing_id = get_the_ID();
                        $average = ATBDP()->review->get_average($listing_id);
                        if ($average_review_for_popular <= $average) {
                            $rated[] = get_the_ID();
                        }

                    }
                    $rating_id = array(
                        'post__in' => !empty($rated) ? $rated : array()
                    );
                    $args = array_merge($args, $rating_id);
                }
            } elseif ('view_count' === $listing_popular_by) {
                $meta_queries['views'] = array(
                    'key' => '_atbdp_post_views_count',
                    'value' => $view_to_popular,
                    'type' => 'NUMERIC',
                    'compare' => '>=',
                );
                $args['orderby'] = array(
                    'views' => 'DESC',
                );
            } else {
                $meta_queries['views'] = array(
                    'key' => '_atbdp_post_views_count',
                    'value' => (int)$view_to_popular,
                    'type' => 'NUMERIC',
                    'compare' => '>=',
                );
                $args['orderby'] = array(
                    'views' => 'DESC',
                );
                if ($listings->have_posts()) {
                    while ($listings->have_posts()) {
                        $listings->the_post();
                        $listing_id = get_the_ID();
                        $average = ATBDP()->review->get_average($listing_id);
                        if ($average_review_for_popular <= $average) {
                            $rated[] = get_the_ID();
                        }

                    }
                    $rating_id = array(
                        'post__in' => !empty($rated) ? $rated : array()
                    );
                    $args = array_merge($args, $rating_id);
                }
            }
        }
        $count_meta_queries = count($meta_queries);
        if ($count_meta_queries) {
            $args['meta_query'] = ($count_meta_queries > 1) ? array_merge(array('relation' => 'AND'), $meta_queries) : $meta_queries;
        }

        return new WP_Query(apply_filters('atbdp_popular_listing_args', $args));

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
            <?php echo ATBDP()->review->print_static_rating($average); ?>
        </div>
        <?php

    }

    /**
     * It displays related listings of the given post
     * @param object|WP_Post $post The current post object
     */
    public function show_related_listing($post)
    {
        /**
         * @package Directorist
         * @since 5.10.0
         */
        do_action('atbdp_before_related_listing_start', $post);
        $enable_rel_listing = get_directorist_option('enable_rel_listing', 1);
        if (empty($enable_rel_listing)) return; // vail if related listing is not enabled
        $related_listings = $this->get_related_listings($post);
        $is_disable_price = get_directorist_option('disable_list_price');
        $rel_listing_column = get_directorist_option('rel_listing_column', 3);
        if ($related_listings->have_posts()) {
            $templete = apply_filters('atbdp_related_listing_template', 'default');
            related_listing_slider($related_listings, $pagenation = null, $is_disable_price, $templete);
        } ?>
        <script>
            jQuery(document).ready(function ($) {
                $('.related__carousel').slick({
                    dots: false,
                    arrows: false,
                    infinite: true,
                    speed: 300,
                    slidesToShow: <?php echo $rel_listing_column;?>,
                    slidesToScroll: 1,
                    autoplay: true,
                    rtl: <?php echo is_rtl() ? 'true' : 'false'; ?>,
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: <?php echo $rel_listing_column;?>,
                                slidesToScroll: 1,
                                infinite: true,
                                dots: false
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 575,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });
            });
        </script>
        <?php
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
        $relationship = get_directorist_option('rel_listings_logic','OR');
        $args = array(
            'post_type' => ATBDP_POST_TYPE,
            'tax_query' => array(
                'relation' => $relationship,
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

        $meta_queries = array();
        $meta_queries[] = array(
            'relation' => 'OR',
            array(
                'key' => '_expiry_date',
                'value' => current_time('mysql'),
                'compare' => '>', // eg. expire date 6 <= current date 7 will return the post
                'type' => 'DATETIME'
            ),
            array(
                'key' => '_never_expire',
                'value' => 1,
            )
        );

        $meta_queries = apply_filters('atbdp_related_listings_meta_queries', $meta_queries);
        $count_meta_queries = count($meta_queries);
        if ($count_meta_queries) {
            $args['meta_query'] = ($count_meta_queries > 1) ? array_merge(array('relation' => 'AND'), $meta_queries) : $meta_queries;
        }

        //return new WP_Query(apply_filters('atbdp_related_listing_args', $args));

    }

    /**
     * It gets the related listings widget of the given listing/post
     * @param object|WP_Post $post The WP Post Object of whose related listing we would like to show
     * @return object|WP_Query It returns the related listings if found.
     */
    public function get_related_listings_widget($post, $count)
    {   
        $directory_type = get_the_terms( get_the_ID(), ATBDP_TYPE );
        $type_id        = ! empty( $directory_type ) ? $directory_type[0]->term_id : '';
        $same_author    = get_directorist_type_option( $type_id, 'listing_from_same_author', false );
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
        if( ! empty( $same_author ) ){
			$args['author']  = get_post_field( 'post_author', get_the_ID() );
		}

        return new WP_Query(apply_filters('atbdp_related_listing_args', $args));

    }

    /**
     * Plugin links
     * @since 4.4.0
     * */
    public function atbdp_plugin_link($links)
    {
        if (array_key_exists('deactivate', $links)) {
            $links['deactivate'] = str_replace('<a', '<a class="atbdp-deactivate-popup"', $links['deactivate']);
        }

        return $links;
    }

    /**
     * Deactivate Reasons
     * @since 4.4.0
     */
    public function atbdp_deactivate_popup()
    {
        global $pagenow;

        if ('plugins.php' != $pagenow) {
            return;
        }
        $deactivate_reasons = atbdp_deactivate_reasons();
        ?>

        <div class="aazz-deactivate" id="atbdp-aazz-deactivate">
            <div class="aazz-deactivate-wrap">
                <div class="aazz-deactivate-header">
                    <h3><?php _e('If you have time, please let us know why you are deactivating so that we can improve.', 'directorist'); ?></h3>
                </div>

                <div class="aazz-deactivate-body">
                    <ul class="reasons">
                        <?php foreach ($deactivate_reasons as $reason) { ?>
                            <li data-type="<?php echo esc_attr($reason['type']); ?>"
                                data-placeholder="<?php echo esc_attr($reason['placeholder']); ?>">
                                <label><input type="radio" name="selected-reason"
                                              value="<?php echo $reason['id']; ?>"> <?php echo $reason['text']; ?>
                                </label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="aazz-deactivate-footer">
                    <a href="#" class="atbdp-no-comment"><?php _e('Skip & Deactivate', 'directorist'); ?></a>
                    <button class="atbdp-reason-submit atbdp-reason-submit"><?php _e('Submit & Deactivate', 'directorist'); ?></button>
                    <button class="button-primary"><?php _e('Cancel', 'directorist'); ?></button>
                </div>
            </div>
        </div>

        <style type="text/css">
            .aazz-deactivate {
                right: 0;
                bottom: 0;
                left: 0;
                position: fixed;
                z-index: 99999;
                top: 0;
                background: rgba(0, 0, 0, 0.5);
                display: none;
            }

            .aazz-deactivate.modal-active {
                display: block;
            }

            .aazz-deactivate-wrap {
                width: 475px;
                position: relative;
                margin: 10% auto;
                background: #fff;
            }

            .aazz-deactivate-header {
                border-bottom: 1px solid #eee;
                padding: 8px 20px;
            }

            .aazz-deactivate-header h3 {
                line-height: 150%;
                margin: 0;
            }

            .aazz-deactivate-body .reason-input {
                margin-top: 5px;
                margin-left: 20px;
            }

            .aazz-deactivate-body {
                padding: 5px 20px 20px 20px;
            }

            .aazz-deactivate-footer {
                border-top: 1px solid #eee;
                padding: 12px 20px;
                text-align: right;
            }
        </style>

        <script type="text/javascript">
            (function ($) {
                $(function () {
                    var modal = $('#atbdp-aazz-deactivate');
                    var deactivateLink = '';

                    $('#the-list').on('click', 'a.atbdp-deactivate-popup', function (e) {
                        e.preventDefault();

                        modal.addClass('modal-active');
                        deactivateLink = $(this).attr('href');
                        modal.find('a.atbdp-no-comment').attr('href', deactivateLink).css('float', 'left');
                    });

                    modal.on('click', 'button.button-primary', function (e) {
                        e.preventDefault();

                        modal.removeClass('modal-active');
                    });

                    modal.on('click', 'input[type="radio"]', function () {
                        var parent = $(this).parents('li:first');

                        modal.find('.reason-input').remove();

                        var inputType = parent.data('type'),
                            inputPlaceholder = parent.data('placeholder'),
                            reasonInputHtml = '<div class="reason-input">' + (('text' === inputType) ? '<input type="text" size="40" />' : '<textarea rows="5" cols="45"></textarea>') + '</div>';

                        if (inputType !== '') {
                            parent.append($(reasonInputHtml));
                            parent.find('input, textarea').attr('placeholder', inputPlaceholder).focus();
                        }
                    });

                    modal.on('click', 'button.atbdp-reason-submit', function (e) {
                        e.preventDefault();

                        var button = $(this);

                        if (button.hasClass('disabled')) {
                            return;
                        }

                        var $radio = $('input[type="radio"]:checked', modal);

                        var $selected_reason = $radio.parents('li:first'),
                            $input = $selected_reason.find('textarea, input[type="text"]');

                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'atbdp_submit-uninstall-reason',
                                reason_id: (0 === $radio.length) ? 'none' : $radio.val(),
                                reason_info: (0 !== $input.length) ? $input.val().trim() : ''
                            },
                            beforeSend: function () {
                                button.addClass('disabled');
                                button.text('Processing...');
                            },
                            complete: function () {
                                window.location.href = deactivateLink;
                            }
                        });
                    });
                });
            }(jQuery));
        </script>
        <?php
    }

    /**
     * It displays reviews of the given post
     * @param object|WP_Post $post The current post object
     */
    public function show_review($post)
    {
        /**
         * @since 5.10.0
         * It fires before review section
         */

        $enable_review = get_directorist_option('enable_review', 1);
        $guest_review = get_directorist_option('guest_review', 0);
        $approve_immediately = get_directorist_option('approve_immediately', 1);
        $review_duplicate = tract_duplicate_review(wp_get_current_user()->display_name, $post->ID);
        if (!$enable_review) return; // vail if review is not enabled
        $enable_owner_review = get_directorist_option('enable_owner_review');
        $reviews_count = ATBDP()->review->db->count(array('post_id' => $post->ID)); // get total review count for this post
        $plan_review = true;
        $review = true;
        $allow_review = apply_filters('atbdp_single_listing_before_review_block', $review);
        if (is_fee_manager_active()) {
            $plan_review = is_plan_allowed_listing_review(get_post_meta($post->ID, '_fm_plans', true));
        }
        if ($plan_review && $allow_review) {
            $count_review = (($reviews_count > 1) || ($reviews_count === 0)) ? __(' Reviews', 'directorist') : __(' Review', 'directorist');
            ?>
            <div class="atbd_content_module atbd_review_module" id="atbd_reviews_block">
                <div class="atbd_content_module_title_area">
                    <div class="atbd_area_title">
                        <h4><span class="<?php atbdp_icon_type(true); ?>-star atbd_area_icon"></span><span
                                    id="reviewCounter"><?php echo $reviews_count; ?></span><?php
                            echo $count_review;
                            ?></h4>
                    </div>
                    <?php if (atbdp_logged_in_user() || $guest_review) { ?>
                        <label for="review_content"
                               class="btn btn-primary btn-sm"><?php _e('Add a review', 'directorist'); ?></label>
                    <?php } ?>
                </div>
                <div class="atbdb_content_module_contents">
                    <input type="hidden" id="review_post_id" data-post-id="<?php echo $post->ID; ?>">
                    <div id="client_review_list">
                    </div>
                    <div id="clint_review"></div>
                </div>

            </div><!-- end .atbd_review_module -->
            <?php
            // check if the user is logged in and the current user is not the owner of this listing.
            if (atbdp_logged_in_user() || $guest_review) {
                global $wpdb;
                // if the current user is NOT the owner of the listing print review form
                // get the settings of the admin whether to display review form even if the user is the owner of the listing.
                if (get_current_user_id() != $post->post_author || $enable_owner_review) {

                    // if user has a review then fetch it.
                    $cur_user_review = ATBDP()->review->db->get_user_review_for_post(get_current_user_id(), get_the_ID());
                    ?>
                    <div class="atbd_content_module">
                        <div class="atbd_content_module_title_area">
                            <div class="atbd_area_title">
                                <h4><span class="<?php atbdp_icon_type(true); ?>-star"
                                          aria-hidden="true"></span><?php echo !empty($cur_user_review) ? __('Update Review', 'directorist') : __('Leave a Review', 'directorist'); ?>
                                </h4>
                            </div>
                        </div>

                        <div class="atbdb_content_module_contents atbd_give_review_area">
                            <form action="#" id="atbdp_review_form" method="post">
                                <?php wp_nonce_field('atbdp_review_action_form', 'atbdp_review_nonce_form'); ?>
                                <input type="hidden" name="post_id" value="<?php the_ID(); ?>">

                                <!--<input type="email" name="email" class="directory_field" placeholder="Your email" required>-->
                                <input type="hidden" name="name" class="btn btn-default"
                                       value="<?php echo wp_get_current_user()->display_name; ?>"
                                       id="reviewer_name">
                                <?php
                                $author_id = wp_get_current_user()->ID;
                                $u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
                                $u_pro_pic = !empty($u_pro_pic) ? wp_get_attachment_image_src($u_pro_pic, 'thumbnail') : '';
                                $u_pro_pic = is_array($u_pro_pic) ? $u_pro_pic[0] : $u_pro_pic;
                                $enable_reviewer_content = get_directorist_option( 'enable_reviewer_content', 1 );
                                $custom_gravatar = "<img src='$u_pro_pic' alt='Author'>";
                                $avatar_img = get_avatar($author_id, apply_filters('atbdp_avatar_size', 32));
                                $user_img = !empty($u_pro_pic) ? $custom_gravatar : $avatar_img;
                                ?>
                                <input type="hidden" name="name" id="reviewer_img" class="btn btn-default"
                                       value='<?php echo esc_attr($user_img); ?>'>

                                <div class="atbd_review_rating_area"> <!--It should be displayed on the left side -->
                                    <?php
                                    // color the stars if user has rating
                                    if (!empty($cur_user_review)) { ?>
                                        <div class="atbd_review_current_rating">
                                            <p class="atbd_rating_label"><?php _e('Current Rating:', 'directorist'); ?></p>
                                            <div class="atbd_rated_stars">
                                                <?php echo ATBDP()->review->print_static_rating($cur_user_review->rating); ?>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="atbd_review_update_rating">
                                        <p class="atbd_rating_label"><?php echo !empty($cur_user_review) ? __('Update Rating:', 'directorist') : __('Your Rating:', 'directorist'); ?></p>
                                        <div class="atbd_rating_stars">
                                            <select class="stars" name="rating" id="review_rating">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5" selected>5</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <?php if( !empty( $enable_reviewer_content ) ) { ?>
                                <div class="form-group">
                                <textarea name="content" id="review_content" class="form-control" cols="20" rows="5"
                                          placeholder="<?php echo !empty($cur_user_review) ? __('Update your review.....', 'directorist') : __('Write your review.....', 'directorist'); ?>"><?php echo !empty($cur_user_review) ? stripslashes($cur_user_review->content) : ''; ?></textarea>
                                </div>
                                <?php } ?>
                                <?php
                                if ($guest_review && !atbdp_logged_in_user()){
                                ?>
                                <div class="form-group">
                                    <label for="guest_user"><?php
                                        $guest_email_label = get_directorist_option('guest_email', __('Your Email', 'directorist'));
                                        $guest_email_placeholder = get_directorist_option('guest_email_placeholder', __('example@gmail.com', 'directorist'));
                                        esc_html_e($guest_email_label . ':', 'directorist');
                                        echo '<span class="atbdp_make_str_red">*</span>'; ?></label>
                                    <input type="text" id="guest_user_email" name="guest_user_email" required
                                           value="<?php echo !empty($guest_user_email) ? esc_url($guest_user_email) : ''; ?>"
                                           class="form-control directory_field"
                                           placeholder="<?php echo esc_attr($guest_email_placeholder); ?>"/>
                                </div>
                                <?php } if (!empty($cur_user_review)) { ?>
                                    <button class="<?php echo atbdp_directorist_button_classes(); ?>" type="submit"
                                            id="atbdp_review_form_submit"><?php _e('Update', 'directorist'); ?></button> <!-- ends update  button -->
                                    <button class="btn btn-danger" type="button" id="atbdp_review_remove"
                                            data-review_id="<?php echo $cur_user_review->id; ?>"><?php _e('Remove', 'directorist'); ?></button> <!-- ends delete button -->
                                <?php } else { ?>
                                    <button class="btn btn-primary" type="submit"
                                            id="atbdp_review_form_submit"><?php _e('Submit Review', 'directorist'); ?></button> <!-- submit button -->
                                <?php } ?>
                                <input type="hidden" name="approve_immediately" id="approve_immediately" value="<?php echo empty($approve_immediately) ? 'no' : 'yes';?>">
                                <input type="hidden" name="review_duplicate" id="review_duplicate" value="<?php echo !empty($review_duplicate) ? 'yes' : '';?>">
                            </form>
                        </div>
                    </div><!-- end .atbd_custom_fields_contents -->
                <?php };
            } else { ?>
                <div class="atbd_notice atbd-alert atbd-alert-info">
                    <span class="<?php atbdp_icon_type(true); ?>-info-circle" aria-hidden="true"></span>
                    <?php
                    $login_url = apply_filters('atbdp_review_login_link', "<a href='" . ATBDP_Permalink::get_login_page_link() . "'> " . __('Login', 'directorist') . "</a>");
                    $register_url = apply_filters('atbdp_review_signup_link', "<a href='" . ATBDP_Permalink::get_registration_page_link() . "'> " . __('Sign Up', 'directorist') . "</a>");

                    printf(__('You need to %s or %s to submit a review', 'directorist'), $login_url, $register_url);
                    ?>
                </div>
            <?php }
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
        if( directorist_legacy_mode() ){
            $c_classes[] = 'atbd_content_active';//class name goes here
            $c_classes[] = 'directorist-preload';//class name goes here
        } else{
            $c_classes[] = 'directorist-content-active';//class name goes here
            $c_classes[] = 'directorist-preload';//class name goes here
        }

        return $c_classes;
    }

    /**
     * Initialize appsero tracking
     * 
     * @see https://github.com/Appsero/client
     *
     * @return void
     */
    public function init_appsero() {
        if ( ! class_exists( '\Appsero\Client' ) ) {
            require_once ATBDP_INC_DIR . 'modules/appsero/src/Client.php';
        }

        $client = new \Appsero\Client( 'd9f81baf-2b03-49b1-b899-b4ee71c1d1b1', 'Directorist &#8211; Business Directory Plugin', __FILE__ );

        // Active insights
        $client->insights()
            ->add_extra( function() {
                return array(
                    'used_active_plugins' => $this->get_used_active_plugins()
                );
            } )
            ->init();
    }

    /**
     * Get the list of active plugins
     *
     * @return array
     */
    protected function get_used_active_plugins() {
        // Ensure get_plugins function is loaded
        if ( ! function_exists( 'get_plugins' ) ) {
            include ABSPATH . '/wp-admin/includes/plugin.php';
        }

        $plugins             = get_plugins();
        $active_plugins_keys = get_option( 'active_plugins', array() );
        $active_plugins      = array();

        foreach ( $plugins as $k => $v ) {
            // Take care of formatting the data how we want it.
            $formatted = array();
            $formatted['name'] = strip_tags( $v['Name'] );

            // Remove self reference
            if ( strpos( __FILE__, $k ) !== false ) {
                continue;
            }

            if ( isset( $v['Version'] ) ) {
                $formatted['version'] = strip_tags( $v['Version'] );
            }

            if ( isset( $v['Author'] ) ) {
                $formatted['author'] = strip_tags( $v['Author'] );
            }

            if ( isset( $v['Network'] ) ) {
                $formatted['network'] = strip_tags( $v['Network'] );
            }

            if ( isset( $v['PluginURI'] ) ) {
                $formatted['plugin_uri'] = strip_tags( $v['PluginURI'] );
            }

            if ( in_array( $k, $active_plugins_keys ) ) {
                $active_plugins[$k] = $formatted;
            }
        }

        return $active_plugins;
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
 *
 * @since 1.0
 * @return object|Directorist_Base The one true Directorist_Base Instance.
 */
function ATBDP()
{
    return Directorist_Base::instance();
}

ATBDP();
register_activation_hook(__FILE__, array('Directorist_Base', 'prepare_plugin'));

