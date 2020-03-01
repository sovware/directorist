<?php
/**
 * ATBDP Add_Listing class
 *
 * This class is for interacting with Add_Listing, eg, saving data to the database
 *
 * @package     ATBDP
 * @subpackage  inlcudes/classes Add_Listing
 * @copyright   Copyright (c) 2018, AazzTech
 * @since       1.0
 */

// Exit if accessed directly
defined('ABSPATH') || die('Direct access is not allowed.');

if (!class_exists('ATBDP_Add_Listing')):

    /**
     * Class ATBDP_Add_Listing
     */
    class ATBDP_Add_Listing
    {


        /**
         * @var string
         */
        public $nonce = 'add_listing_nonce';
        /**
         * @var string
         */
        public $nonce_action = 'add_listing_action';


        /**
         * ATBDP_Add_Listing constructor.
         */
        public function __construct()
        {
            // show the attachment of the current users only
            add_filter('ajax_query_attachments_args', array($this, 'show_current_user_attachments'), 10, 1);
            add_action('parse_query', array($this, 'parse_query')); // do stuff likes adding, editing, renewing, favorite etc in this hook
            add_action('wp_ajax_add_listing_action', array($this, 'atbdp_submit_listing'));
            add_action('wp_ajax_nopriv_add_listing_action', array($this, 'atbdp_submit_listing'));
        }


        private function atbdp_get_file_attachment_id($array, $name)
        {
            $id = null;
            foreach ($array as $item) {
                if ($item['name'] === $name) {
                    $id = $item['id'];
                    break;
                }
            }
            return $id;
        }

        /**
         * @since 5.6.3
         */
        public function atbdp_submit_listing()
        {
            $p = $_POST;

            $data = array();
            /**
             * @toda later validate add listing nonce with ja nonce
             */
            //if (ATBDP()->helper->verify_nonce($this->nonce, $this->nonce_action )) {
            if (1) {
                /**
                 * It fires before processing a submitted listing from the front end
                 * @param array $_POST the array containing the submitted listing data.
                 * */

                do_action('atbdp_before_processing_submitted_listing_frontend', $p);

                // add listing form has been submitted
                //if (ATBDP()->helper->verify_nonce($this->nonce, $this->nonce_action, $_REQUEST['data'] ))
                // guest user
                if (!atbdp_logged_in_user()) {
                    $guest = get_directorist_option('guest_listings', 0);
                    $guest_email = isset($p['guest_user_email']) ? esc_attr($p['guest_user_email']) : '';
                    if (!empty($guest && $guest_email)) {
                        atbdp_guest_submission($guest_email);
                    }
                }
                // we have data and passed the security
                // we not need to sanitize post vars to be saved to the database,
                // because wp_insert_post() does this inside that like : $postarr = sanitize_post($postarr, 'db');
                $display_title_for = get_directorist_option('display_title_for', 0);
                $display_desc_for = get_directorist_option('display_desc_for', 0);
                $featured_enabled = get_directorist_option('enable_featured_listing');
                $display_tagline_field = get_directorist_option('display_tagline_field', 0);
                $display_tagline_for = get_directorist_option('display_tagline_for', 0);
                $display_pricing_field = get_directorist_option('display_pricing_field', 1);
                $display_price_for = get_directorist_option('display_price_for', 'admin_users');
                $display_price_range_field = get_directorist_option('display_price_range_field', 1);
                $display_price_range_for = get_directorist_option('display_price_range_for', 'admin_users');
                $display_excerpt_field = get_directorist_option('display_excerpt_field', 0);
                $display_short_desc_for = get_directorist_option('display_short_desc_for', 0);
                $display_address_field = get_directorist_option('display_address_field', 1);
                $display_address_for = get_directorist_option('display_address_for', 0);
                $display_views_count = get_directorist_option('display_views_count', 1);
                $display_views_count_for = get_directorist_option('display_views_count_for', 1);
                $display_phone_field = get_directorist_option('display_phone_field', 1);
                $display_phone_for = get_directorist_option('display_phone_for', 0);
                $display_phone2_field = get_directorist_option('display_phone_field2', 1);
                $display_phone2_for = get_directorist_option('display_phone2_for', 0);
                $display_fax_field = get_directorist_option('display_fax', 1);
                $display_fax_for = get_directorist_option('display_fax_for', 0);
                $display_email_field = get_directorist_option('display_email_field', 1);
                $display_email_for = get_directorist_option('display_email_for', 0);
                $display_website_field = get_directorist_option('display_website_field', 1);
                $display_website_for = get_directorist_option('display_website_for', 0);
                $display_zip_field = get_directorist_option('display_zip_field', 1);
                $display_zip_for = get_directorist_option('display_zip_for', 0);
                $display_social_info_field = get_directorist_option('display_social_info_field', 1);
                $display_social_info_for = get_directorist_option('display_social_info_for', 0);
                $display_prv_field = get_directorist_option('display_prv_field', 1);
                $display_prv_img_for = get_directorist_option('display_prv_img_for', 0);
                $display_gallery_field = get_directorist_option('display_gallery_field', 1);
                $display_glr_img_for = get_directorist_option('display_glr_img_for', 0);
                $display_video_field = get_directorist_option('display_video_field', 1);
                $display_video_for = get_directorist_option('display_video_for', 0);
                $custom_field = !empty($p['custom_field']) ? ($p['custom_field']) : array();
                $preview_enable = get_directorist_option('preview_enable', 1);
                // because wp_insert_post() does this inside that like : $postarr = sanitize_post($postarr, 'db');
                $metas = array();
                $content = !empty($p['listing_content']) ? wp_kses($p['listing_content'], wp_kses_allowed_html('post')) : '';
                $title = !empty($p['listing_title']) ? sanitize_text_field($p['listing_title']) : '';
                $tag = !empty($p['tax_input']['at_biz_dir-tags']) ? ($p['tax_input']['at_biz_dir-tags']) : array();
                $location = !empty($p['tax_input']['at_biz_dir-location']) ? ($p['tax_input']['at_biz_dir-location']) : array();
                $admin_category_select = !empty($p['tax_input']['at_biz_dir-category']) ? ($p['tax_input']['at_biz_dir-category']) : array();

                $metas['_listing_type'] = !empty($p['listing_type']) ? sanitize_text_field($p['listing_type']) : 0;
                if (empty($display_price_for) && !empty($display_pricing_field)) {
                    $metas['_price'] = !empty($p['price']) ? (float)$p['price'] : 0;
                }

                if (empty($display_price_range_for) && !empty($display_price_range_field)) {
                    $metas['_price_range'] = !empty($p['price_range']) ? $p['price_range'] : '';
                }
                $metas['_atbd_listing_pricing'] = !empty($p['atbd_listing_pricing']) ? $p['atbd_listing_pricing'] : '';
                if (empty($display_video_for) && !empty($display_video_field)) {
                    $metas['_videourl'] = !empty($p['videourl']) ? sanitize_text_field($p['videourl']) : '';
                }
                if (!empty($display_tagline_field) && empty($display_tagline_for)) {
                    $metas['_tagline'] = !empty($p['tagline']) ? sanitize_text_field($p['tagline']) : '';
                }
                if (!empty($display_excerpt_field) && empty($display_short_desc_for)) {
                    $metas['_excerpt'] = !empty($p['excerpt']) ? sanitize_text_field($p['excerpt']) : '';
                }
                if (!empty($display_views_count) && empty($display_views_count_for)) {
                    $metas['_atbdp_post_views_count'] = !empty($p['atbdp_post_views_count']) ? (int)$p['atbdp_post_views_count'] : '';
                }
                if (empty($display_address_for) && !empty($display_address_field)) {
                    $metas['_address'] = !empty($p['address']) ? sanitize_text_field($p['address']) : '';
                }
                if (empty($display_phone_for) && !empty($display_phone_field)) {
                    $metas['_phone'] = !empty($p['phone']) ? sanitize_text_field($p['phone']) : '';
                }
                if (empty($display_phone2_for) && !empty($display_phone2_field)) {
                    $metas['_phone2'] = !empty($p['phone2']) ? sanitize_text_field($p['phone2']) : '';
                }
                if (empty($display_fax_for) && !empty($display_fax_field)) {
                    $metas['_fax'] = !empty($p['fax']) ? sanitize_text_field($p['fax']) : '';
                }
                if (empty($display_email_for) && !empty($display_email_field)) {
                    $metas['_email'] = !empty($p['email']) ? sanitize_text_field($p['email']) : '';
                }
                if (empty($display_website_for) && !empty($display_website_field)) {
                    $metas['_website'] = !empty($p['website']) ? sanitize_text_field($p['website']) : '';
                }
                if (empty($display_zip_for) && !empty($display_zip_field)) {
                    $metas['_zip'] = !empty($p['zip']) ? sanitize_text_field($p['zip']) : '';
                }
                if (empty($display_social_info_for) && !empty($display_social_info_field)) {
                    $metas['_social'] = !empty($p['social']) ? atbdp_sanitize_array($p['social']) : array(); // we are expecting array value
                }
                $metas['_faqs'] = !empty($p['faqs']) ? ($p['faqs']) : array(); // we are expecting array value
                $metas['_bdbh'] = !empty($p['bdbh']) ? atbdp_sanitize_array($p['bdbh']) : array();
                $metas['_enable247hour'] = !empty($p['enable247hour']) ? sanitize_text_field($p['enable247hour']) : '';
                $metas['_disable_bz_hour_listing'] = !empty($p['disable_bz_hour_listing']) ? sanitize_text_field($p['disable_bz_hour_listing']) : '';
                $metas['_manual_lat'] = !empty($p['manual_lat']) ? sanitize_text_field($p['manual_lat']) : '';
                $metas['_manual_lng'] = !empty($p['manual_lng']) ? sanitize_text_field($p['manual_lng']) : '';
                $metas['_hide_map'] = !empty($p['hide_map']) ? sanitize_text_field($p['hide_map']) : '';
                $metas['_hide_contact_info'] = !empty($p['hide_contact_info']) ? sanitize_text_field($p['hide_contact_info']) : 0;
                $metas['_hide_contact_owner'] = !empty($p['hide_contact_owner']) ? sanitize_text_field($p['hide_contact_owner']) : 0;
                $metas['_t_c_check'] = !empty($p['t_c_check']) ? sanitize_text_field($p['t_c_check']) : 0;
                $metas['_privacy_policy'] = !empty($p['privacy_policy']) ? sanitize_text_field($p['privacy_policy']) : 0;
                /**
                 * It applies a filter to the meta values that are going to be saved with the listing submitted from the front end
                 * @param array $metas the array of meta keys and meta values
                 */

                //@todo need to shift FM validation code to extension itself
                if (is_fee_manager_active()) {
                    $user_id = get_current_user_id();
                    $midway_package_id = selected_plan_id();
                    $sub_plan_id = get_post_meta($p['listing_id'], '_fm_plans', true);
                    $midway_package_id = !empty($midway_package_id) ? $midway_package_id : $sub_plan_id;
                    $plan_purchased = subscribed_package_or_PPL_plans($user_id, 'completed', $midway_package_id);
                    if (!class_exists('DWPP_Pricing_Plans')) {
                        $plan_purchased = $plan_purchased[0];
                    }
                    $subscribed_package_id = $midway_package_id;
                    $plan_type = package_or_PPL($subscribed_package_id);
                    $order_id = !empty($plan_purchased) ? (int)$plan_purchased->ID : '';
                    $user_featured_listing = listings_data_with_plan($user_id, '1', $subscribed_package_id, $order_id);
                    $user_regular_listing = listings_data_with_plan($user_id, '0', $subscribed_package_id, $order_id);
                    $num_regular = get_post_meta($subscribed_package_id, 'num_regular', true);
                    $num_featured = get_post_meta($subscribed_package_id, 'num_featured', true);
                    $total_regular_listing = $num_regular;
                    $total_featured_listing = $num_featured;

                    if ($plan_purchased) {
                        $listing_id = get_post_meta($plan_purchased->ID, '_listing_id', true);
                        $featured = get_post_meta($listing_id, '_featured', true);
                        $total_regular_listing = $num_regular - ('0' === $featured ? $user_regular_listing + 1 : $user_regular_listing);
                        $total_featured_listing = $num_featured - ('1' === $featured ? $user_featured_listing + 1 : $user_featured_listing);
                        $subscribed_date = $plan_purchased->post_date;
                        $package_length = get_post_meta($subscribed_package_id, 'fm_length', true);
                        $regular_unl = get_post_meta($subscribed_package_id, 'num_regular_unl', true);
                        $featured_unl = get_post_meta($subscribed_package_id, 'num_featured_unl', true);
                        $package_length = $package_length ? $package_length : '1';
                        // Current time
                        $start_date = !empty($subscribed_date) ? $subscribed_date : '';
                        // Calculate new date
                        $date = new DateTime($start_date);
                        $date->add(new DateInterval("P{$package_length}D")); // set the interval in days
                        $expired_date = $date->format('Y-m-d H:i:s');
                        $current_d = current_time('mysql');
                        $remaining_days = ($expired_date > $current_d) ? (floor(strtotime($expired_date) / (60 * 60 * 24)) - floor(strtotime($current_d) / (60 * 60 * 24))) : 0; //calculate the number of days remaining in a plan
                        if ((((0 >= $total_regular_listing) && empty($regular_unl)) && ((0 >= $total_featured_listing)) && empty($featured_unl)) || ($remaining_days <= 0)) {
                            //if user exit the plan allowance the change the status of that order to cancelled
                            $order_id = $plan_purchased->ID;
                            if (class_exists('woocommerce') && class_exists('DWPP_Pricing_Plans')) {
                                if (('pay_per_listng' != $plan_type)) {
                                    $order = new WC_Order($order_id);
                                    $order->update_status('cancelled', 'order_note');
                                }
                            } else {
                                if (('pay_per_listng' != $plan_type)) {
                                    update_post_meta($order_id, '_payment_status', 'cancelled');
                                }
                            }
                        }
                    }

                    $listing_type = !empty($p['listing_type']) ? sanitize_text_field($p['listing_type']) : '';
                    //store the plan meta
                    $plan_meta = get_post_meta($subscribed_package_id);

                    if (('regular' === $listing_type) && ('package' === $plan_type)) {
                        if ((($plan_meta['num_regular'][0] < $total_regular_listing) || (0 >= $total_regular_listing)) && empty($plan_meta['num_regular_unl'][0])) {
                            $msg = '<div class="alert alert-danger"><strong>' . __('You have already crossed your limit for regular listing!', 'directorist') . '</strong></div>';
                            $data['message'] = $msg;
                        }
                    }
                    if (('featured' === $listing_type) && ('package' === $plan_type)) {
                        if ((($plan_meta['num_featured'][0] < $total_featured_listing) || (0 === $total_featured_listing)) && empty($plan_meta['num_featured_unl'][0])) {
                            $msg = '<div class="alert alert-danger"><strong>' . __('You have already crossed your limit for featured listing!', 'directorist') . '</strong></div>';
                            $data['message'] = $msg;

                        }
                    }

                    if (class_exists('BD_Gallery')) {
                        $gallery_images = !empty($metas['_gallery_img']) ? $metas['_gallery_img'] : array();
                        $_gallery_img = count($gallery_images);
                        if ($plan_meta['num_gallery_image'][0] < $_gallery_img && empty($plan_meta['num_gallery_image_unl'][0])) {
                            $msg = '<div class="alert alert-danger"><strong>' . __('You can upload a maximum of ' . $plan_meta['num_gallery_image'][0] . ' gallery image(s)', 'directorist') . '</strong></div>';
                            $data['message'] = $msg;
                        }
                    }
                }
                $metas = apply_filters('atbdp_listing_meta_user_submission', $metas);
                $args = array(
                    'post_content' => $content,
                    'post_title' => $title,
                    'post_type' => ATBDP_POST_TYPE,
                    'tax_input' => !empty($p['tax_input']) ? atbdp_sanitize_array($p['tax_input']) : array(),
                    'meta_input' => $metas,

                );
                /**
                 * @since 4.4.0
                 *
                 */
                do_action('atbdp_after_add_listing_afrer_validation');

                // is it update post ? @todo; change listing_id to atbdp_listing_id later for consistency with rewrite tags
                if (!empty($p['listing_id'])) {
                    /**
                     * @since 5.4.0
                     */
                    do_action('atbdp_before_processing_to_update_listing');
                    $edit_l_status = get_directorist_option('edit_listing_status');
                    if ('pending' === $edit_l_status) {
                        $data['pending'] = true;
                    }
                    // update the post
                    $args['ID'] = absint($p['listing_id']); // set the ID of the post to update the post
                    if (!empty($edit_l_status)) {
                        $args['post_status'] = $edit_l_status; // set the status of edit listing.
                    }
                    // Check if the current user is the owner of the post
                    $post = get_post($args['ID']);
                    // update the post if the current user own the listing he is trying to edit. or we and give access to the editor or the admin of the post.
                    if (get_current_user_id() == $post->post_author || current_user_can('edit_others_at_biz_dirs')) {
                        // Convert taxonomy input to term IDs, to avoid ambiguity.
                        if (isset($args['tax_input'])) {
                            foreach ((array)$args['tax_input'] as $taxonomy => $terms) {
                                // Hierarchical taxonomy data is already sent as term IDs, so no conversion is necessary.
                                if (is_taxonomy_hierarchical($taxonomy)) {
                                    continue;
                                }

                                /*
                                 * Assume that a 'tax_input' string is a comma-separated list of term names.
                                 * Some languages may use a character other than a comma as a delimiter, so we standardize on
                                 * commas before parsing the list.
                                 */
                                if (!is_array($terms)) {
                                    $comma = _x(',', 'tag delimiter');
                                    if (',' !== $comma) {
                                        $terms = str_replace($comma, ',', $terms);
                                    }
                                    $terms = explode(',', trim($terms, " \n\t\r\0\x0B,"));
                                }

                                $clean_terms = array();
                                foreach ($terms as $term) {
                                    // Empty terms are invalid input.
                                    if (empty($term)) {
                                        continue;
                                    }

                                    $_term = get_terms($taxonomy, array(
                                        'name' => $term,
                                        'fields' => 'ids',
                                        'hide_empty' => false,
                                    ));

                                    if (!empty($_term)) {
                                        $clean_terms[] = intval($_term[0]);
                                    } else {
                                        // No existing term was found, so pass the string. A new term will be created.
                                        $clean_terms[] = $term;
                                    }
                                }

                                $args['tax_input'][$taxonomy] = $clean_terms;
                            }
                        }
                        if (!empty($display_title_for)) {
                            $args['post_title'] = get_the_title(absint($p['listing_id']));
                        }
                        if (!empty($display_desc_for)) {
                            $post_object = get_post(absint($p['listing_id']));
                            $content = apply_filters('get_the_content', $post_object->post_content);
                            $args['post_content'] = $content;
                        }
                        $post_id = wp_update_post($args);


                        if (!empty($location)) {
                            $append = false;
                            if (count($location) > 1) {
                                $append = true;
                            }
                            foreach ($location as $single_loc) {
                                $locations = get_term_by('term_id', $single_loc, ATBDP_LOCATION);
                                wp_set_object_terms($post_id, $locations->name, ATBDP_LOCATION, $append);
                            }
                        }else{
                            wp_set_object_terms($post_id, '', ATBDP_LOCATION);
                        }
                        if (!empty($tag)) {
                            if (count($tag) > 1) {
                                foreach ($tag as $single_tag) {
                                    $tag = get_term_by('slug', $single_tag, ATBDP_TAGS);
                                    wp_set_object_terms($post_id, $tag->name, ATBDP_TAGS, true);
                                }
                            } else {
                                wp_set_object_terms($post_id, $tag[0], ATBDP_TAGS);//update the term relationship when a listing updated by author
                            }
                        }else{
                            wp_set_object_terms($post_id, '', ATBDP_TAGS);
                        }

                        if (!empty($admin_category_select)) {
                            update_post_meta($post_id, '_admin_category_select', $admin_category_select);
                            $append = false;
                            if (count($admin_category_select) > 1) {
                                $append = true;
                            }
                            foreach ($admin_category_select as $single_category) {
                                $cat = get_term_by('term_id', $single_category, ATBDP_CATEGORY);
                                wp_set_object_terms($post_id, $cat->name, ATBDP_CATEGORY, $append);
                            }
                        }else{
                            wp_set_object_terms($post_id, '', ATBDP_CATEGORY);
                        }

                        /*
                             * send the custom field value to the database
                              */
                        if (isset($custom_field)) {
                            foreach ($custom_field as $key => $value) {

                                $type = get_post_meta($key, 'type', true);

                                switch ($type) {
                                    case 'text' :
                                        $value = sanitize_text_field($value);
                                        break;
                                    case 'textarea' :
                                        $value = esc_textarea($value);
                                        break;
                                    case 'select' :
                                    case 'radio'  :
                                        $value = sanitize_text_field($value);
                                        break;
                                    case 'checkbox' :
                                        $value = array_map('esc_attr', $value);
                                        $value = implode("\n", array_filter($value));
                                        break;
                                    case 'url' :
                                        $value = esc_url_raw($value);
                                        break;
                                    default :
                                        $value = sanitize_text_field($value);
                                        break;
                                    case 'email' :
                                        $value = sanitize_text_field($value);
                                        break;
                                    case 'date' :
                                        $value = sanitize_text_field($value);

                                }

                                update_post_meta($post_id, $key, $value);
                            }
                        }

                        // for dev
                        do_action('atbdp_listing_updated', $post_id);//for sending email notification
                    } else {
                        // kick the user out because he is trying to modify the listing of other user.
                        $data['redirect_url'] = $_SERVER['REQUEST_URI'] . '?error=true';
                        $data['error'] = true;
                    }


                } else {
                    // the post is a new post, so insert it as new post.
                    if (current_user_can('publish_at_biz_dirs')) {
                        $new_l_status = get_directorist_option('new_listing_status', 'pending');
                        if ('pending' === $new_l_status) {
                            $data['pending'] = true;
                        }
                        $monitization = get_directorist_option('enable_monetization', 0);
                        $args['post_status'] = $new_l_status;
                        //if listing under a purchased package
                        if (is_fee_manager_active()) {
                            if (('package' === package_or_PPL($plan = null)) && $plan_purchased && ('publish' === $new_l_status)) {
                                // status for paid users
                                $args['post_status'] = $new_l_status;
                            } else {
                                // status for non paid users
                                $args['post_status'] = 'pending';
                            }
                        } elseif (!empty($featured_enabled && $monitization)) {
                            $args['post_status'] = 'pending';
                        } else {
                            $args['post_status'] = $new_l_status;
                        }
                        if (!empty($preview_enable)) {
                            $args['post_status'] = 'draft';
                        }

                        if (isset($args['tax_input'])) {
                            foreach ((array)$args['tax_input'] as $taxonomy => $terms) {
                                // Hierarchical taxonomy data is already sent as term IDs, so no conversion is necessary.
                                if (is_taxonomy_hierarchical($taxonomy)) {
                                    continue;
                                }

                                /*
                                 * Assume that a 'tax_input' string is a comma-separated list of term names.
                                 * Some languages may use a character other than a comma as a delimiter, so we standardize on
                                 * commas before parsing the list.
                                 */
                                if (!is_array($terms)) {
                                    $comma = _x(',', 'tag delimiter');
                                    if (',' !== $comma) {
                                        $terms = str_replace($comma, ',', $terms);
                                    }
                                    $terms = explode(',', trim($terms, " \n\t\r\0\x0B,"));
                                }

                                $clean_terms = array();
                                foreach ($terms as $term) {
                                    // Empty terms are invalid input.
                                    if (empty($term)) {
                                        continue;
                                    }

                                    $_term = get_terms($taxonomy, array(
                                        'name' => $term,
                                        'fields' => 'ids',
                                        'hide_empty' => false,
                                    ));

                                    if (!empty($_term)) {
                                        $clean_terms[] = intval($_term[0]);
                                    } else {
                                        // No existing term was found, so pass the string. A new term will be created.
                                        $clean_terms[] = $term;
                                    }
                                }

                                $args['tax_input'][$taxonomy] = $clean_terms;
                            }
                        }

                        $post_id = wp_insert_post($args);
                        do_action('atbdp_listing_inserted', $post_id);//for sending email notification

                        //Every post with the published status should contain all the post meta keys so that we can include them in query.
                        if ('publish' == $new_l_status || 'pending' == $new_l_status) {
                            $expire_in_days = get_directorist_option('listing_expire_in_days');
                            $never_expire = empty($expire_in_days) ? 1 : 0;
                            $exp_dt = calc_listing_expiry_date();
                            update_post_meta($post_id, '_expiry_date', $exp_dt);
                            update_post_meta($post_id, '_never_expire', $never_expire);
                            update_post_meta($post_id, '_featured', 0);
                            update_post_meta($post_id, '_listing_status', 'post_status');
                            update_post_meta($post_id, '_admin_category_select', $admin_category_select);
                            /*
                              * It fires before processing a listing from the front end
                              * @param array $_POST the array containing the submitted fee data.
                              * */
                            do_action('atbdp_before_processing_listing_frontend', $post_id);

                            /*
                            * send the custom field value to the database
                         */

                            if (isset($custom_field)) {

                                foreach ($custom_field as $key => $value) {

                                    $type = get_post_meta($key, 'type', true);

                                    switch ($type) {
                                        case 'text' :
                                            $value = sanitize_text_field($value);
                                            break;
                                        case 'textarea' :
                                            $value = esc_textarea($value);
                                            break;
                                        case 'select' :
                                        case 'radio'  :
                                            $value = sanitize_text_field($value);
                                            break;
                                        case 'checkbox' :
                                            $value = array_map('esc_attr', $value);
                                            $value = implode("\n", array_filter($value));
                                            break;
                                        case 'url' :
                                            $value = esc_url_raw($value);
                                            break;
                                        default :
                                            $value = sanitize_text_field($value);
                                            break;
                                        case 'email' :
                                            $value = sanitize_text_field($value);
                                            break;
                                        case 'date' :
                                            $value = sanitize_text_field($value);

                                    }

                                    update_post_meta($post_id, $key, $value);
                                }
                            }

                            // set up terms
                            // location
                            if (!empty($location)) {
                                $append = false;
                                if (count($location) > 1) {
                                    $append = true;
                                }
                                foreach ($location as $single_loc) {
                                    $locations = get_term_by('term_id', $single_loc, ATBDP_LOCATION);
                                    wp_set_object_terms($post_id, $locations->name, ATBDP_LOCATION, $append);
                                }
                            }else{
                                wp_set_object_terms($post_id, '', ATBDP_LOCATION);
                            }
                            // tag
                            if (!empty($tag)) {
                                if (count($tag) > 1) {
                                    foreach ($tag as $single_tag) {
                                        $tag = get_term_by('slug', $single_tag, ATBDP_TAGS);
                                        wp_set_object_terms($post_id, $tag->name, ATBDP_TAGS, true);
                                    }
                                } else {
                                    wp_set_object_terms($post_id, $tag[0], ATBDP_TAGS);//update the term relationship when a listing updated by author
                                }
                            }else{
                                wp_set_object_terms($post_id, '', ATBDP_TAGS);
                            }
                            // category
                            if (!empty($admin_category_select)) {
                                update_post_meta($post_id, '_admin_category_select', $admin_category_select);
                                $append = false;
                                if (count($admin_category_select) > 1) {
                                    $append = true;
                                }
                                foreach ($admin_category_select as $single_category) {
                                    $cat = get_term_by('term_id', $single_category, ATBDP_CATEGORY);
                                    wp_set_object_terms($post_id, $cat->name, ATBDP_CATEGORY, $append);
                                }
                            }else{
                                wp_set_object_terms($post_id, '', ATBDP_CATEGORY);
                            }



                        }
                        if ('publish' == $new_l_status) {
                            do_action('atbdp_listing_published', $post_id);//for sending email notification
                        }
                    }
                }
                if (!empty($post_id)) {
                    do_action('atbdp_after_created_listing', $post_id);
                    // handling media files
                    $listing_images = atbdp_get_listing_attachment_ids($post_id);
                    $files = !empty($_FILES["listing_img"]) ? $_FILES["listing_img"] : array();
                    $files_meta = !empty($_POST['files_meta']) ? $_POST['files_meta'] : array();
                    if (!empty($listing_images)) {
                        foreach ($listing_images as $__old_id) {
                            $match_found = false;
                            foreach ($files_meta as $__new_id) {
                                $new_id = (int)$__new_id['attachmentID'];
                                if ($new_id === (int)$__old_id) {
                                    $match_found = true;
                                    break;
                                }
                            }
                            if (!$match_found) {
                                wp_delete_attachment((int)$__old_id, true);
                            }
                        }
                    }
                    $attach_data = array();
                    if ($files) {
                        foreach ($files['name'] as $key => $value) {
                            if ($files['name'][$key]) {
                                $file = array(
                                    'name' => $files['name'][$key],
                                    'type' => $files['type'][$key],
                                    'tmp_name' => $files['tmp_name'][$key],
                                    'error' => $files['error'][$key],
                                    'size' => $files['size'][$key]
                                );
                                $_FILES["my_file_upload"] = $file;
                                $meta_data = [];
                                $meta_data['name'] = $files['name'][$key];
                                $meta_data['id'] = atbdp_handle_attachment("my_file_upload", $post_id);
                                array_push($attach_data, $meta_data);
                            }
                        }
                    }

                    $new_files_meta = [];
                    foreach ($files_meta as $key => $value) {
                        if ($key === 0 && $value['oldFile'] === 'true') {
                            if (empty($display_prv_img_for) && !empty($display_prv_field)) {
                                update_post_meta($post_id, '_listing_prv_img', $value['attachmentID']);
                                set_post_thumbnail($post_id, $value['attachmentID']);
                            }
                        }
                        if ($key === 0 && $value['oldFile'] !== 'true') {
                            foreach ($attach_data as $item) {
                                if ($item['name'] === $value['name']) {
                                    $id = $item['id'];
                                    if (empty($display_prv_img_for) && !empty($display_prv_field)) {
                                        update_post_meta($post_id, '_listing_prv_img', $id);
                                        set_post_thumbnail($post_id, $id);
                                    }
                                }
                            }
                        }
                        if ($key !== 0 && $value['oldFile'] === 'true') {
                            array_push($new_files_meta, $value['attachmentID']);
                        }
                        if ($key !== 0 && $value['oldFile'] !== 'true') {
                            foreach ($attach_data as $item) {
                                if ($item['name'] === $value['name']) {
                                    $id = $item['id'];
                                    array_push($new_files_meta, $id);
                                }
                            }
                        }
                    }
                    if (empty($display_glr_img_for) && !empty($display_gallery_field)) {
                        update_post_meta($post_id, '_listing_img', $new_files_meta);
                    }

                    // Redirect to avoid duplicate form submissions
                    // if monetization on, redirect to checkout page
                    // vail if monetization is not active.
                    if (is_fee_manager_active()) {
                        if (class_exists('DWPP_Pricing_Plans')) {
                            $regular_price = get_post_meta($subscribed_package_id, '_regular_price', true);
                            $redirect_page = get_directorist_option('edit_listing_redirect', 'view_listing');
                            if ('pay_per_listng' === package_or_PPL($plan = null)) {
                                if (!empty($regular_price)) {
                                    global $woocommerce;
                                    $woocommerce->cart->empty_cart();
                                    $woocommerce->cart->add_to_cart($subscribed_package_id);
                                    $data['redirect_url'] = add_query_arg('atbdp_listing_id', $post_id, wc_get_checkout_url());
                                    $data['need_payment'] = true;
                                } else {
                                    update_user_meta(get_current_user_id(), '_used_free_plan', array($subscribed_package_id, $post_id));
                                    if ('view_listing' == $redirect_page) {
                                        $data['redirect_url'] = get_permalink($post_id);
                                        $data['success'] = true;
                                    } else {
                                        $data['redirect_url'] = ATBDP_Permalink::get_dashboard_page_link();
                                        $data['success'] = true;
                                    }
                                }

                            } elseif (('package' === package_or_PPL($plan = null)) && !$plan_purchased) {
                                //lets redirect to woo checkout page
                                if (!empty($regular_price)) {
                                    global $woocommerce;
                                    $woocommerce->cart->empty_cart();
                                    $woocommerce->cart->add_to_cart($subscribed_package_id);
                                    $data['redirect_url'] = add_query_arg('atbdp_listing_id', $post_id, wc_get_checkout_url());
                                    $data['need_payment'] = true;
                                } else {
                                    update_user_meta(get_current_user_id(), '_used_free_plan', array($subscribed_package_id, $post_id));

                                    if ('view_listing' == $redirect_page) {
                                        $data['redirect_url'] = get_permalink($post_id);
                                        $data['success'] = true;
                                    } else {
                                        $data['redirect_url'] = ATBDP_Permalink::get_dashboard_page_link();
                                        $data['success'] = true;
                                    }
                                }
                            } else {
                                //yep! listing is saved to db and redirect user to admin panel or listing itself
                                if ('view_listing' == $redirect_page) {
                                    $data['redirect_url'] = get_permalink($post_id);
                                    $data['success'] = true;
                                } else {
                                    $data['redirect_url'] = ATBDP_Permalink::get_dashboard_page_link();
                                    $data['success'] = true;
                                }
                            }
                        } else {
                            if ('pay_per_listng' === package_or_PPL($plan = null)) {
                                $data['redirect_url'] = ATBDP_Permalink::get_checkout_page_link($post_id);
                                $data['need_payment'] = true;
                            } elseif (('package' === package_or_PPL($plan = null)) && !$plan_purchased) {
                                //lets redirect to directorist checkout page
                                $data['redirect_url'] = ATBDP_Permalink::get_checkout_page_link($post_id);
                                $data['need_payment'] = true;
                            } else {
                                //yep! listing is saved to db and redirect user to admin panel or listing itself
                                $redirect_page = get_directorist_option('edit_listing_redirect', 'view_listing');
                                if ('view_listing' == $redirect_page) {
                                    $data['redirect_url'] = get_permalink($post_id);
                                    $data['success'] = true;
                                } else {
                                    $data['redirect_url'] = ATBDP_Permalink::get_dashboard_page_link();
                                    $data['success'] = true;
                                }
                            }
                        }

                    } else {
                        //no pay extension own yet let treat as general user
                        if (get_directorist_option('enable_monetization') && !$p['listing_id'] && $featured_enabled && (!is_fee_manager_active())) {
                            $data['redirect_url'] = ATBDP_Permalink::get_checkout_page_link($post_id);
                            $data['need_payment'] = true;
                        } else {
                            //yep! listing is saved to db and redirect user to admin panel or listing itself
                            $redirect_page = get_directorist_option('edit_listing_redirect', 'view_listing');
                            if ('view_listing' == $redirect_page) {
                                $data['redirect_url'] = get_permalink($post_id);
                                $data['success'] = true;
                            } else {
                                $data['redirect_url'] = ATBDP_Permalink::get_dashboard_page_link();
                                $data['success'] = true;
                            }
                        }

                    }

                } else {
                    $data['redirect_url'] = site_url() . '?error=true';
                    $data['error'] = true;
                }
                if (!empty($data['success']) && $data['success'] === true) {
                    $data['success_msg'] = __('Your Submission is Completed! redirecting..', 'directorist');
                }
                if (!empty($data['error']) && $data['error'] === true) {
                    $data['error_msg'] = __('Sorry! Something Wrong with Your Submission', 'directorist');
                }
                if (!empty($data['need_payment']) && $data['need_payment'] === true) {
                    $data['success_msg'] = __('Payment Required! redirecting to checkout..', 'directorist');
                }
                if ($preview_enable) {
                    $data['preview_mode'] = true;
                }
                $data['preview_url'] = get_permalink($post_id);
                if ($p['listing_id']) {
                    $data['id'] = $post_id;
                    $data['edited_listing'] = true;
                }
                wp_send_json($data);
                die();
            }
        }


        /**
         * It sets the author parameter of the attachment query for showing the attachment of the user only
         * @param array $query
         * @return array
         */
        public function show_current_user_attachments($query = array())
        {
            $user_id = get_current_user_id();
            if (!current_user_can('delete_pages')) {
                if ($user_id) $query['author'] = $user_id;
            }
            return $query;
        }

        /**
         *It outputs nonce field to any any form
         * @param bool $referrer Optional. Whether to set the referer field for validation. Default true.
         * @param bool $echo Optional. Whether to display or return hidden form field. Default true.
         */
        public function show_nonce_field($referrer = true, $echo = true)
        {
            wp_nonce_field($this->nonce_action, $this->nonce, $referrer, $echo);

        }


        /**
         * It helps to perform different db related action to the listing
         *
         * @param WP_Query $query
         * @since 3.1.0
         */
        public function parse_query($query)
        {
            $action = $query->get('atbdp_action');
            $id = $query->get('atbdp_listing_id');
            if (!empty($action) && !empty($id)) {
                // handle renewing the listing
                if ('renew' == $action) {
                    $this->renew_listing($id);
                }
            }
        }


        /**
         * It renews the given listing
         * @param $listing_id
         * @return mixed
         * @since 3.1.0
         */
        private function renew_listing($listing_id)
        {
            $can_renew = get_directorist_option('can_renew_listing');
            if (!$can_renew) return false;// vail if renewal option is turned off on the site.
            // Hook for developers
            do_action('atbdp_before_renewal', $listing_id);
            update_post_meta($listing_id, '_featured', 0); // delete featured
            //for listing package extensions...
            $has_paid_submission = apply_filters('atbdp_has_paid_submission', 0, $listing_id, 'renew');

            $active_monetization = get_directorist_option('enable_monetization');
            if ($has_paid_submission && $active_monetization) {
                // if paid submission enabled/triggered by an extension, redirect to the checkout page and let that handle it, and vail out.
                wp_safe_redirect(ATBDP_Permalink::get_checkout_page_link($listing_id));
                exit;
            }

            $time = current_time('mysql');
            $post_array = array(
                'ID' => $listing_id,
                'post_status' => 'publish',
                'post_date' => $time,
                'post_date_gmt' => get_gmt_from_date($time)
            );

            //Updating listing
            wp_update_post($post_array);

            // Update the post_meta into the database
            $old_status = get_post_meta($listing_id, '_listing_status', true);
            if ('expired' == $old_status) {
                $expiry_date = calc_listing_expiry_date();
            } else {
                $old_expiry_date = get_post_meta($listing_id, '_expiry_date', true);
                $expiry_date = calc_listing_expiry_date($old_expiry_date);
            }
            // update related post metas
            update_post_meta($listing_id, '_expiry_date', $expiry_date);
            update_post_meta($listing_id, '_listing_status', 'post_status');

            $exp_days = get_directorist_option('listing_expire_in_days', 999, 999);
            if ($exp_days <= 0) {
                update_post_meta($listing_id, '_never_expire', 1);
            } else {
                update_post_meta($listing_id, '_never_expire', 0);
            }
            do_action('atbdp_after_renewal', $listing_id);


            $featured_active = get_directorist_option('enable_featured_listing');
            $has_paid_submission = apply_filters('atbdp_has_paid_submission', $featured_active, $listing_id, 'renew');
            if ($has_paid_submission && $active_monetization) {
                $r_url = ATBDP_Permalink::get_checkout_page_link($listing_id);
            } else {
                //@todo; Show notification on the user page after renewing.
                $r_url = add_query_arg('renew', 'success', ATBDP_Permalink::get_dashboard_page_link());
            }
            // hook for dev
            do_action('atbdp_before_redirect_after_renewal', $listing_id);
            wp_safe_redirect($r_url);
            exit;

        }


    } // ends ATBDP_Add_Listing


endif;