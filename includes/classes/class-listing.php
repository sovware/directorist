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
            add_action('template_redirect', array($this, 'atbdp_listing_status_controller'));

        }

        /**
         * @since 6.3.5
         */

      public function atbdp_listing_status_controller()
        {
            $status = isset($_GET['listing_status']) ? esc_attr($_GET['listing_status']) : '';
            $preview = isset($_GET['preview']) ? esc_attr($_GET['preview']) : '';
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
            //$id = $id ? $id : get_the_ID();
            if ($preview || $status) {
                //if listing under a purchased package
                if (is_fee_manager_active()) {
                    $plan_id = get_post_meta($id, '_fm_plans', true);
                    $plan_purchased = subscribed_package_or_PPL_plans(get_current_user_id(), 'completed', $plan_id);
                    if (('package' === package_or_PPL($plan_id)) && $plan_purchased && ('publish' === $new_l_status)) {
                        // status for paid users
                        $post_status = $listing_status;
                        
                    } else {
                        // status for non paid users
                        $post_status = 'pending';
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
                wp_update_post(apply_filters('atbdp_reviewed_listing_status_controller_argument', $args));
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

        public function the_content($content)
        {
            $id = get_directorist_option('single_listing_page');
            if (is_singular(ATBDP_POST_TYPE) && in_the_loop() && is_main_query()) {
                $include = apply_filters('include_style_settings', true);
                if ($include) {
                    include ATBDP_DIR . 'public/assets/css/style.php';
                }
                if (!empty($id)) {
                    global $post;
                    /**
                     * @since 5.10.0
                     * It fires before single listing load
                     */
                    do_action('atbdp_before_single_listing_load', $post->ID);
                    ob_start();
                    $listing_author_id = get_post_field('post_author', $post->ID);
                    $content = get_post_field('post_content', $id);
                    $content = do_shortcode($content);
                    $main_col_size = is_active_sidebar('right-sidebar-listing') ? 'col-lg-8' : 'col-lg-12';
                    $class = isset($_GET['redirect']) ? 'atbdp_float_active' : 'atbdp_float_none';
                    // run block content if its available
                    ?>
                    <section id="directorist" class="directorist atbd_wrapper">
                        <div class="row">
                            <div class="<?php echo esc_attr($main_col_size); ?> col-md-12 atbd_col_left">

                                <?php
                                $display_back_link = get_directorist_option('display_back_link', 1);
                                $html_edit_back = '';
                                //is current user is logged in and the original author of the listing
                                if (atbdp_logged_in_user() && $listing_author_id == get_current_user_id()) {
                                    //ok show the edit option

                                    $html_edit_back .= '<div class="edit_btn_wrap">';
                                    if (!empty($display_back_link)) {
                                        if (!isset($_GET['redirect'])) {
                                            $html_edit_back .= '<a href="javascript:history.back()" class="atbd_go_back"><i class="' . atbdp_icon_type() . '-angle-left"></i>' . esc_html__(' Go Back', 'directorist') . '</a> ';
                                        }
                                    }
                                    $html_edit_back .= '<div class="' . $class . '">';
                                    $html_edit_back .= atbdp_get_preview_button();
                                    $payment = isset($_GET['payment']) ? $_GET['payment'] : '';
                                    $url = isset($_GET['redirect']) ? $_GET['redirect'] : '';
                                    $edit_link = !empty($payment)?add_query_arg('redirect', $url, ATBDP_Permalink::get_edit_listing_page_link($post->ID)):ATBDP_Permalink::get_edit_listing_page_link($post->ID);
                                    $html_edit_back .= '<a href="' . esc_url($edit_link) . '" class="btn btn-outline-light">
                            <span class="' . atbdp_icon_type() . '-edit"></span>' . apply_filters('atbdp_listing_edit_btn_text', esc_html__(' Edit', 'directorist')) . '</a>';
                                    $html_edit_back .= '</div>';
                                    $html_edit_back .= '</div>';
                                } else {
                                    if (!empty($display_back_link)) {
                                        $html_edit_back .= '<div class="edit_btn_wrap">
                                <a href="javascript:history.back()" class="atbd_go_back">
                                    <i class="' . atbdp_icon_type() . '-angle-left"></i>' . esc_html__(' Go Back', 'directorist') . '
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
            if (!is_single() || atbdp_logged_in_user() && empty($count_loggedin)) return;

            if (empty ($postID)) {
                global $post;
                $postID = $post->ID;
            }
            $this->set_post_views($postID);
        }
    }

endif;









