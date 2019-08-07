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
            add_filter('the_content', array($this, 'the_content'), 20); // add the output of the single page when the content filter fires in our post type. This way is better than using a custom post template because it will not match the style of all theme.
            add_filter('post_thumbnail_html', array($this, 'post_thumbnail_html'), 10, 3);
            add_action('wp_head', array($this, 'og_metatags'));

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

                if (!empty(get_post_thumbnail_id($post))) {
                    delete_post_thumbnail($post);
                }

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
                    $thumbnail = wp_get_attachment_image_src($images, 'full')[0];
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
         * @since    5.4.0
         * @access   public
         *
         * @param    string $html The post thumbnail HTML.
         * @return   string    $html    Filtered thumbnail HTML.
         */
        public function post_thumbnail_html($html)
        {

            if (is_singular('at_biz_dir')) {
                return '';
            }

            return $html;

        }

        public function the_content($content)
        {
            $id = get_directorist_option('single_listing_page');
            if (is_singular(ATBDP_POST_TYPE) && in_the_loop() && is_main_query()) {
                include ATBDP_DIR .'public/assets/css/style.php';
                if (!empty($id)) {
                    ob_start();
                    global $post;
                    $listing_author_id = get_post_field('post_author', $post->ID);
                    $content = get_post_field('post_content', $id);
                    $content = do_shortcode($content);
                    $main_col_size = is_active_sidebar('right-sidebar-listing') ? 'col-lg-8' : 'col-lg-12';
                    // run block content if its available
                    ?>
                    <section id="directorist" class="directorist atbd_wrapper">
                        <div class="row">
                            <div class="<?php echo esc_attr($main_col_size); ?> col-md-12 atbd_col_left">

                                <?php
                                $html_edit_back = '';
                                //is current user is logged in and the original author of the listing
                                if (is_user_logged_in() && $listing_author_id == get_current_user_id()) {
                                    //ok show the edit option

                                    $html_edit_back .= '<div class="edit_btn_wrap">';
                                    $display_back_link = get_directorist_option('display_back_link', 1);
                                    if (!empty($display_back_link)) {
                                        $html_edit_back .= '<a href="javascript:history.back()" class="atbd_go_back"><i class="' . atbdp_icon_type() . '-angle-left"></i>' . esc_html__(' Go Back', ATBDP_TEXTDOMAIN) . '</a> ';
                                    }
                                    $html_edit_back .= '<a href="' . esc_url(ATBDP_Permalink::get_edit_listing_page_link($post->ID)) . '" class="btn btn-success">
                            <span class="' . atbdp_icon_type() . '-edit"></span>' . esc_html__(' Edit Listing', ATBDP_TEXTDOMAIN) . '</a>';
                                    $html_edit_back .= '</div>';
                                } else {
                                    if (!empty($display_back_link)) {
                                        $html_edit_back .= '<div class="edit_btn_wrap">
                                <a href="javascript:history.back()" class="atbd_go_back">
                                    <i class="' . atbdp_icon_type() . '-angle-left"></i>' . esc_html__(' Go Back', ATBDP_TEXTDOMAIN) . '
                                </a>
                           </div>';
                                    }
                                }

                                /**
                                 * @since 5.5.4
                                 */
                                echo apply_filters('atbdp_single_listing_edit_back', $html_edit_back);

                                if (function_exists('do_blocks')) {
                                    echo $content;
                                } ?>
                            </div>
                            <?php include ATBDP_TEMPLATES_DIR . 'sidebar-listing.php'; ?>
                        </div>
                    </section>
                    <?php
                    return ob_get_clean();
                } else {
                    global $post;
                    ob_start();
                    include ATBDP_TEMPLATES_DIR . 'single-at_biz_dir.php';
                    return ob_get_clean();
                }

            }

            return $content;
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
            if (!is_single() || is_user_logged_in() && empty($count_loggedin)) return;

            if (empty ($postID)) {
                global $post;
                $postID = $post->ID;
            }
            $this->set_post_views($postID);
        }
    }

endif;









