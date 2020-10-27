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
                $info = $_POST;

                // wp_send_json( $info );
                // die();
                $data = array();
                /**
                 * It fires before processing a submitted listing from the front end
                 * @param array $_POST the array containing the submitted listing data.
                 * */

                do_action('atbdp_before_processing_submitted_listing_frontend', $info);

                // add listing form has been submitted
                //if (ATBDP()->helper->verify_nonce($this->nonce, $this->nonce_action, $_REQUEST['data'] ))
                // guest user
                if (!atbdp_logged_in_user()) {
                    $guest = get_directorist_option('guest_listings', 0);
                    $guest_email = isset($info['guest_user_email']) ? esc_attr($info['guest_user_email']) : '';
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
                $display_prv_field = get_directorist_option('display_prv_field', 1);
                $display_prv_img_for = get_directorist_option('display_prv_img_for', 0);
                $display_gallery_field = get_directorist_option('display_gallery_field', 1);
                $display_glr_img_for = get_directorist_option('display_glr_img_for', 0);
                $preview_enable = get_directorist_option('preview_enable', 1);


                 // data validation
                 $listing_type = !empty( $_POST['directory_type'] ) ? sanitize_text_field( $_POST['directory_type'] ) : '';
                 $submission_form_fields = [];
                 $metas = [];
                 if( $listing_type ){
                    $term = get_term_by( 'id', $listing_type, 'atbdp_listing_types' );
                    $submission_form = get_term_meta( $term->term_id, 'submission_form_fields', true );
                    $submission_form_fields = $submission_form['fields'];
                 }
                // isolate data
                // wp_send_json([
                //     'form_fields' => $submission_form_fields,
                //     'form_data'   => $info,
                // ]);
                $error = [];
                foreach( $submission_form_fields as $key => $value ){
                    $field_key = !empty( $value['field_key'] ) ? $value['field_key'] : '';
                    $submitted_data = !empty( $info[ $field_key ] ) ? $info[ $field_key ] : '';
                    $required = !empty( $value['required'] ) ? $value['required'] : '';
                    $label = !empty( $value['label'] ) ? $value['label'] : '';
                    if( $required && !$submitted_data ){
                        $msg = $label .__( ' field is required!', 'directorist' );
                        array_push( $error, $msg );
                    }
                    if( $field_key === 'listing_title' ){
                        $title = sanitize_text_field( $info[ $field_key ] );
                    }
                    if( $field_key === 'listing_content' ){
                        $content =  wp_kses(  $info[ $field_key ], wp_kses_allowed_html('post') );
                    }
                    if( $field_key == 'tax_input' ){
                        foreach(  $info[ $field_key ] as $tax_key => $tax_value ){
                            if( $tax_key === 'at_biz_dir-tags' ){
                                $tag = $tax_value;
                            }
                            if( $tax_key === 'at_biz_dir-location' ){
                                $location = $tax_value;
                            }
                            if( $tax_key === 'at_biz_dir-category' ){
                                $admin_category_select = $tax_value;
                            }
                        }
                    }
                    if( ( $field_key !== 'listing_title' ) && ( $field_key !== 'listing_content' ) && ( $field_key !== 'tax_input' ) ){
                        $key = '_'. $field_key;
                        $metas[ $key ] = !empty( $info[ $field_key ] ) ? $info[ $field_key ] : '';
                    }                    
                }
                $metas['_directory_type'] = $listing_type;
                if( $error ){
                    $data['error_msg'] = $error;
                    $data['error'] = true;
                }
                // wp_send_json( $metas );
                /**
                 * It applies a filter to the meta values that are going to be saved with the listing submitted from the front end
                 * @param array $metas the array of meta keys and meta values
                 */

                //@todo need to shift FM validation code to extension itself
                if (is_fee_manager_active()) {
                    $user_id = get_current_user_id();
                    $midway_package_id = selected_plan_id();
                    $sub_plan_id = get_post_meta($info['listing_id'], '_fm_plans', true);
                    $midway_package_id = !empty($midway_package_id) ? $midway_package_id : $sub_plan_id;
                    $plan_purchased = subscribed_package_or_PPL_plans($user_id, 'completed', $midway_package_id);
                    if (!class_exists('DWPP_Pricing_Plans')) {
                        $plan_purchased = $plan_purchased ? $plan_purchased[0] : '';
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
                        $total_regular_listing = max($total_regular_listing, 0);
                        $total_featured_listing = max($total_featured_listing, 0);
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
                            $msg = '<div class="alert alert-danger"><strong>' . __('You have crossed the limit! Please try again', 'directorist') . '</strong></div>';
                            if (class_exists('woocommerce') && class_exists('DWPP_Pricing_Plans')) {
                                if (('pay_per_listng' != $plan_type)) {
                                    $order = new WC_Order($order_id);
                                    $order->update_status('cancelled', 'order_note');
                                    $data['error_msg'] = $msg;
                                    $data['error'] = true;
                                }
                            } else {
                                if (('pay_per_listng' != $plan_type)) {
                                    update_post_meta($order_id, '_payment_status', 'cancelled');
                                    $data['error_msg'] = $msg;
                                    $data['error'] = true;
                                }
                            }
                        }
                    }

                    $listing_type = !empty($info['listing_type']) ? sanitize_text_field($info['listing_type']) : '';
                    //store the plan meta
                    $plan_meta = get_post_meta($subscribed_package_id);

                    if (('regular' === $listing_type) && ('package' === $plan_type)) {
                        if ((($plan_meta['num_regular'][0] < $total_regular_listing) || (0 >= $total_regular_listing)) && empty($plan_meta['num_regular_unl'][0])) {
                            $msg = '<div class="alert alert-danger"><strong>' . __('You have already crossed your limit for regular listing, please try again.', 'directorist') . '</strong></div>';
                            $data['error_msg'] = $msg;
                            $data['error'] = true;
                        }
                    }
                    if (('featured' === $listing_type) && ('package' === $plan_type)) {
                        if ((($plan_meta['num_featured'][0] < $total_featured_listing) || (0 === $total_featured_listing)) && empty($plan_meta['num_featured_unl'][0])) {
                            $msg = '<div class="alert alert-danger"><strong>' . __('You have already crossed your limit for featured listing, please try again', 'directorist') . '</strong></div>';
                            $data['error_msg'] = $msg;
                            $data['error'] = true;
                        }
                    }

                    if (class_exists('BD_Gallery')) {
                        $gallery_images = !empty($metas['_gallery_img']) ? $metas['_gallery_img'] : array();
                        $_gallery_img = count($gallery_images);
                        if ($plan_meta['num_gallery_image'][0] < $_gallery_img && empty($plan_meta['num_gallery_image_unl'][0])) {
                            $msg = '<div class="alert alert-danger"><strong>' . __('You can upload a maximum of ' . $plan_meta['num_gallery_image'][0] . ' gallery image(s)', 'directorist') . '</strong></div>';
                            $data['error_msg'] = $msg;
                            $data['error'] = true;
                        }
                    }
                }
                $metas = apply_filters('atbdp_listing_meta_user_submission', $metas);
                $args = array(
                    'post_content' => $content,
                    'post_title' => $title,
                    'post_type' => ATBDP_POST_TYPE,
                    'tax_input' => !empty($info['tax_input']) ? atbdp_sanitize_array($info['tax_input']) : array(),
                    'meta_input' => $metas,
                );
                /**
                 * @since 4.4.0
                 *
                 */
                do_action('atbdp_after_add_listing_afrer_validation');

                // is it update post ? @todo; change listing_id to atbdp_listing_id later for consistency with rewrite tags
                if (!empty($info['listing_id'])) {
                    /**
                     * @since 5.4.0
                     */
                    do_action('atbdp_before_processing_to_update_listing');

                    $listing_id = absint( $info['listing_id'] );
                    $_args = [ 'id' => $listing_id, 'edited' => true ];
                    $post_status = atbdp_get_listing_status_after_submission( $_args );
                    $args['post_status'] = $post_status;

                    if ( 'pending' === $post_status ) {
                        $data['pending'] = true;
                    }

                    // update the post
                    $args['ID'] = $listing_id; // set the ID of the post to update the post
                    
                    if (!empty($preview_enable)) {
                        $args['post_status'] = 'private';
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
                            $args['post_title'] = get_the_title(absint($info['listing_id']));
                        }
                        if (!empty($display_desc_for)) {
                            $post_object = get_post(absint($info['listing_id']));
                            $content = apply_filters('get_the_content', $post_object->post_content);
                            $args['post_content'] = $content;
                        }

                        $post_id = wp_update_post($args);

                        if( !empty( $_POST['directory_type'] ) ){
                            wp_set_object_terms($post_id, (int)$_POST['directory_type'], 'atbdp_listing_types');
                        }

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

                      

                        // for dev
                        do_action('atbdp_listing_updated', $post_id);//for sending email notification
                    } else {
                        // kick the user out because he is trying to modify the listing of other user.
                        $data['redirect_url'] = $_SERVER['REQUEST_URI'] . '?error=true';
                        $data['error'] = true;
                    }


                } else {
                    
                    // the post is a new post, so insert it as new post.
                    if (current_user_can('publish_at_biz_dirs') && (!isset($data['error']))) {
                        $new_l_status = get_directorist_option('new_listing_status', 'pending');
                        $args['post_status'] = $new_l_status;

                        if ('pending' === $new_l_status) {
                            $data['pending'] = true;
                        }
                        
                        $monitization = get_directorist_option('enable_monetization', 0);
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
                            $args['post_status'] = 'private';
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
                            
                            // set up terms
                            if( !empty( $_POST['directory_type'] ) ){
                                wp_set_object_terms($post_id, (int)$_POST['directory_type'], 'atbdp_listing_types');
                            }
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
                    $data['id'] = $post_id;
                    
                    // handling media files
                    $listing_images = atbdp_get_listing_attachment_ids($post_id);
                    $files = !empty($_FILES["listing_img"]) ? $_FILES["listing_img"] : array();
                    $files_meta = !empty($_POST['files_meta']) ? $_POST['files_meta'] : array();
                    if (!empty($listing_images)) {
                        foreach ($listing_images as $__old_id) {
                            $match_found = false;
                            if (!empty($files_meta)){
                                foreach ($files_meta as $__new_id) {
                                    $new_id = isset($__new_id['attachmentID']) ? (int)$__new_id['attachmentID'] : '';
                                    if ($new_id === (int)$__old_id) {
                                        $match_found = true;
                                        break;
                                    }
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
                        if (get_directorist_option('enable_monetization') && !$info['listing_id'] && $featured_enabled && (!is_fee_manager_active())) {
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
                    $data['error_msg'] = isset($data['error_msg']) ? $data['error_msg'] : __('Sorry! Something Wrong with Your Submission', 'directorist');
                }else{
                    $data['preview_url'] = get_permalink($post_id);
                }
                if (!empty($data['need_payment']) && $data['need_payment'] === true) {
                    $data['success_msg'] = __('Payment Required! redirecting to checkout..', 'directorist');
                }
                if ($preview_enable) {
                    $data['preview_mode'] = true;
                }
                if ($info['listing_id']) {
                    $data['edited_listing'] = true;
                }
                wp_send_json($data);
                die();
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
            $temp_token = isset($_GET['token']) ? $_GET['token'] : '';
            $renew_from = isset($_GET['renew_from']) ? $_GET['renew_from'] : '';
            $token = get_post_meta($id, '_renewal_token', true);
            
            if (!empty($action) && !empty($id)) {
            if ('renew' == $action) {
            if(($temp_token === $token) || $renew_from){
                // handle renewing the listing
                $this->renew_listing($id);
            }else{
                $r_url = add_query_arg('renew', 'token_expired', ATBDP_Permalink::get_dashboard_page_link());
              wp_safe_redirect($r_url);
              exit;
                }
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
            $active_monetization = get_directorist_option('enable_monetization');
            $enable_featured_listing = get_directorist_option('enable_featured_listing');
            if (  $active_monetization && $enable_featured_listing ) {
                // if paid submission enabled/triggered by an extension, redirect to the checkout page and let that handle it, and vail out.
                update_post_meta( $listing_id, '_refresh_renewal_token', 1 );
                wp_safe_redirect( ATBDP_Permalink::get_checkout_page_link( $listing_id ) );
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
                $r_url = add_query_arg('renew', 'success', ATBDP_Permalink::get_dashboard_page_link());
                update_post_meta($listing_id, '_renewal_token', 0);
                // hook for dev
                do_action('atbdp_before_redirect_after_renewal', $listing_id);
                wp_safe_redirect($r_url);
                exit;
        }


    } // ends ATBDP_Add_Listing


endif;