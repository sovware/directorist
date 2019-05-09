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
if ( ! defined('ABSPATH') ) { die( 'You should not access this file directly...' ); }

if (!class_exists('ATBDP_Listing')):

class ATBDP_Listing{

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
        remove_action( 'wp_head',  array($this, 'adjacent_posts_rel_link_wp_head', 10));
        add_action( 'wp_head',  array($this, 'track_post_views'));
        add_filter('the_content', array($this, 'the_content'), 20); // add the output of the single page when the content filter fires in our post type. This way is better than using a custom post template because it will not match the style of all theme.

    }

    public function the_content($content)
    {
        if( is_singular(ATBDP_POST_TYPE ) && in_the_loop() && is_main_query() ) {
            global $post;
            ob_start();

            // echo 'hello from the function';
            include ATBDP_TEMPLATES_DIR . 'single-at_biz_dir.php';
            return ob_get_clean();
        }

        return $content;
    }



    public function modify_search_query(WP_Query $query)
    {
        global $wp_query;
        $post_type = get_query_var('post_type');
        $s = get_query_var('s');
        $post_type = (!empty( $post_type)) ?  $post_type : ( !empty( $query->post_type ) ? $query->post_type : 'any');

        if( $query->is_search() && $post_type == ATBDP_POST_TYPE )
        {
            /*@TODO; make the number of items to show dynamic using setting panel*/
            $srch_p_num = get_directorist_option('search_posts_num', 6);
           $query->set('posts_per_page', absint($srch_p_num));

        }
        return $query;
    }

    public function include_files()
    {
        load_some_file(array('class-template'), ATBDP_CLASS_DIR);
        load_some_file(array('class-add-listing'), ATBDP_CLASS_DIR);
        load_some_file(array('class-listing-db'), ATBDP_CLASS_DIR);
    }



    public function set_post_views($postID) {
        /*@todo; add option to verify the user using his/her IP address so that reloading the page multiple times by the same user does not increase his post view of the same post on the same day.*/
            $count_key = '_atbdp_post_views_count';
            $count = get_post_meta($postID, $count_key, true);
            if('' == $count){
                delete_post_meta($postID, $count_key);
                add_post_meta($postID, $count_key, '0');
            }else{
                $count++;
                update_post_meta($postID, $count_key, $count);
            }
        }


    /**
     * Track the posts view to show popular posts based on number of views
     * @param $postID
     */
    public function track_post_views ($postID) {
                // vail if user is logged in or if the post is not single..

                $count_loggedin = get_directorist_option('count_loggedin_user');
                if ( !is_single() || is_user_logged_in() && empty($count_loggedin) ) return;

                if ( empty ( $postID) ) {
                    global $post;
                    $postID = $post->ID;
                }
                $this->set_post_views($postID);
            }
    }

endif;









