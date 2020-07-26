<?php
/**
 * ATBDP Listing class
 *
 * This class is for interacting with Listing, eg, searching, displaying etc.
 *
 * @package     ATBDP
 * @subpackage  inlcudes/classes Listing
 * @copyright   Copyright (c) 2018, AazzTech
 * @since       1.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    die('You should not access this file directly...');
}

if (!class_exists('ATBDP_Listing')):

    class ATBDP_Listing
    {

        /**
         * ATBDP_Template Object.
         *
         * @var object|ATBDP_Template
         * @since 1.0
         */
        public $template;
        /**
         * ATBDP_Add_Listing Object.
         *
         * @var object|ATBDP_Add_Listing
         * @since 1.0
         */
        public $add_listing;

        /**
         * ATBDP_Add_Listing Object.
         *
         * @var object|ATBDP_Add_Listing
         * @since 1.0
         */
        public $db;

        public function __construct()
        {
            $this->include_files();
            $this->template = new ATBDP_Template;
            $this->add_listing = new ATBDP_Add_Listing;
            $this->db = new ATBDP_Listing_DB;
            // for search functionality
            //add_action('pre_get_posts', array($this, 'modify_search_query'), 1, 10);
            // remove adjacent_posts_rel_link_wp_head for accurate post views
            remove_action('wp_head', array($this, 'adjacent_posts_rel_link_wp_head', 10));
            add_action('wp_head', array($this, 'track_post_views'));
            add_filter('post_thumbnail_html', array($this, 'post_thumbnail_html'), 10, 3);
            add_action('wp_head', array($this, 'og_metatags'));
            add_action('template_redirect', array($this, 'atbdp_listing_status_controller'));

        }

        /**
         * @since 6.3.5
         */

      public function atbdp_listing_status_controller()
        {
            $status = isset($_GET['listing_status']) ? esc_attr($_GET['listing_status']) : '';
            $preview = isset($_GET['preview']) ? esc_attr($_GET['preview']) : '';
            $reviewed = isset($_GET['reviewed']) ? esc_attr($_GET['reviewed']) : '';
            $id = isset($_GET['listing_id']) ? (int)($_GET['listing_id']) : '';
            $edited = isset($_GET['edited']) ? esc_attr($_GET['edited']) : '';
            $new_l_status = get_directorist_option('new_listing_status', 'pending');
            $monitization = get_directorist_option('enable_monetization', 0);
            $featured_enabled = get_directorist_option('enable_featured_listing');
            $edit_l_status = get_directorist_option('edit_listing_status');
            $payment = isset($_GET['payment']) ? $_GET['payment'] : '';
            $listing_id = isset($_GET['atbdp_listing_id']) ? $_GET['atbdp_listing_id'] : '';
            $listing_id = isset($_GET['post_id']) ? $_GET['post_id'] : $listing_id;
            $id = $id ? $id : $listing_id;
            $listing_status = $edited ? $edit_l_status : $new_l_status;
            if ($preview || $status || $reviewed) {
                //if listing under a purchased package
                if (is_fee_manager_active()) {
                    $plan_id = get_post_meta($id, '_fm_plans', true);
                    $plan_purchased = subscribed_package_or_PPL_plans(get_current_user_id(), 'completed', $plan_id);
                    if($edited && $plan_purchased){
                        $post_status = $listing_status;
                    }else{
                        if ((('package' === package_or_PPL($plan_id)) || $plan_purchased) && ('publish' === $new_l_status)) {
                            // status for paid users
                            $post_status = $listing_status;
                        } else {
                            // status for non paid users
                            $post_status = 'pending';
                        }
                    }  
                } elseif (!empty($featured_enabled && $monitization)) {
                    if($payment){
                        $post_status = 'pending';
                    }else{
                        $post_status = $listing_status;
                    }
                } else {
                    $post_status = $listing_status;
                }

                $post_status = $status ? $status : $post_status;
                
                $args = array(
                    'ID' => $id ? $id : get_the_ID(),
                    'post_status' => $post_status,
                );
                if('at_biz_dir' === get_post_type($id ? $id : get_the_ID())){
                    wp_update_post(apply_filters('atbdp_reviewed_listing_status_controller_argument', $args));
                }
            }     
        }

        /**
         * Adds the Facebook OG tags and Twitter Cards.
         *
         * @since    1.0.0
         * @access   public
         */
        public function og_metatags()
        {

            global $post;

            if (!isset($post)) return;

            if (is_singular('at_biz_dir')) {


                $title = get_the_title();

                // Get Location page title

                echo '<meta property="og:url" content="' . atbdp_get_current_url() . '" />';
                echo '<meta property="og:type" content="article" />';
                echo '<meta property="og:title" content="' . $title . '" />';
                echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '" />';
                echo '<meta name="twitter:card" content="summary" />';

                if (!empty($post->post_content)) {
                    echo '<meta property="og:description" content="' . wp_trim_words($post->post_content, 150) . '" />';
                }

                $images = get_post_meta($post->ID, '_listing_prv_img', true);
                if (!empty($images)) {
                    $thumbnail = atbdp_get_image_source($images, 'full');
                    if (!empty($thumbnail)) {
                        echo '<meta property="og:image" content="' . esc_attr($thumbnail) . '" />';
                        echo '<meta name="twitter:image" content="' . esc_attr($thumbnail) . '" />';
                    }

                }

            }

        }

        /**
         * Filter the post content.
         *
         * @param string $html The post thumbnail HTML.
         * @return   string    $html    Filtered thumbnail HTML.
         * @since    5.4.0
         * @access   public
         *
         */
        public function post_thumbnail_html($html)
        {
            $double_thumb = get_directorist_option('fix_listing_double_thumb', 0);
            if (!empty($double_thumb)) {
                if (is_singular('at_biz_dir')) {
                    global $post;
                    if (!isset($post)) return '';
                    if (ATBDP_POST_TYPE === $post->post_type) {

                        $html = '';
                    }

                }
            }
            return $html;

        }

        public function modify_search_query(WP_Query $query)
        {
            if (!is_admin() && $query->is_main_query() && $query->is_archive()) {
                global $wp_query;
                $post_type = get_query_var('post_type');
                $s = get_query_var('s');
                $post_type = (!empty($post_type)) ? $post_type : (!empty($query->post_type) ? $query->post_type : 'any');

                if ($query->is_search() && $post_type == ATBDP_POST_TYPE) {
                    /*@TODO; make the number of items to show dynamic using setting panel*/
                    $srch_p_num = get_directorist_option('search_posts_num', 6);
                    $query->set('posts_per_page', absint($srch_p_num));

                }
                return $query;
            } else {
                return $query;
            }

        }

        public function include_files()
        {
            load_some_file(array('class-template'), ATBDP_CLASS_DIR);
            load_some_file(array('class-add-listing'), ATBDP_CLASS_DIR);
            load_some_file(array('class-listing-db'), ATBDP_CLASS_DIR);
        }


        public function set_post_views($postID)
        {
            /*@todo; add option to verify the user using his/her IP address so that reloading the page multiple times by the same user does not increase his post view of the same post on the same day.*/
            $count_key = '_atbdp_post_views_count';
            $count = get_post_meta($postID, $count_key, true);
            if ('' == $count) {
                delete_post_meta($postID, $count_key);
                add_post_meta($postID, $count_key, '0');
            } else {
                $count++;
                update_post_meta($postID, $count_key, $count);
            }
        }


        /**
         * Track the posts view to show popular posts based on number of views
         * @param $postID
         */
        public function track_post_views($postID)
        {
            // vail if user is logged in or if the post is not single..

            $count_loggedin = get_directorist_option('count_loggedin_user');
            if (!is_single() || atbdp_logged_in_user() && empty($count_loggedin)) return;

            if (empty ($postID)) {
                global $post;
                $postID = $post->ID;
            }
            $this->set_post_views($postID);
        }
    }

endif;









