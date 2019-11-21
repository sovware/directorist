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
defined('ABSPATH') || die( 'Direct access is not allowed.' );

if (!class_exists('ATBDP_Add_Listing')):

    /**
     * Class ATBDP_Add_Listing
     */
    class ATBDP_Add_Listing{


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
            add_filter( 'ajax_query_attachments_args', array($this, 'show_current_user_attachments'), 10, 1 );
            add_action ('wp_loaded', array($this, 'add_listing_to_db'));/*@todo; check if it is better to use init hook instead of wp_loaded*/
            add_action('parse_query', array($this, 'parse_query')); // do stuff likes adding, editing, renewing, favorite etc in this hook

        }


        /**
         * It sets the author parameter of the attachment query for showing the attachment of the user only
         * @TODO; add functionality to show all image to the administrator
         * @See; https://wordpress.stackexchange.com/questions/1482/restricting-users-to-view-only-media-library-items-they-have-uploaded
         * @param array $query
         * @return array
         */
        public function show_current_user_attachments( $query = array() ) {
            $user_id = get_current_user_id();
            if( !current_user_can('delete_pages') ){
                if( $user_id ) $query['author'] = $user_id;
            }
            return $query;
        }

        /**
         * It inserts & Updates a listing to the database and redirects use to the checkout page
         * when a new post is saved & monetization is active
         */
        public function add_listing_to_db() {

            // has the listing for been submitted ?
            if ( !empty( $_POST['add_listing_form'] ) ) {
                /**
                 * It fires before processing a submitted listing from the front end
                 * @param array $_POST the array containing the submitted listing data.
                 * */
                do_action('atbdp_before_processing_submitted_listing_frontend', $_POST);
                // add listing form has been submitted
                if (ATBDP()->helper->verify_nonce($this->nonce, $this->nonce_action )) {

                    // guest user
                    /*$guest = get_directorist_option('guest_listings', 0);
                    $guest_email = isset($_POST['guest_user_email']) ? esc_attr($_POST['guest_user_email']) : '';
                    if (!empty($guest && $guest_email)){
                        atbdp_guest_submission($guest_email);
                    }*/

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
                    $admin_category_select= !empty($_POST['admin_category_select']) ? atbdp_sanitize_array($_POST['admin_category_select']) : array();
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
                    $display_gellery_field = get_directorist_option('display_gellery_field', 1);
                    $display_glr_img_for = get_directorist_option('display_glr_img_for', 0);
                    $display_video_field = get_directorist_option('display_video_field', 1);
                    $display_video_for = get_directorist_option('display_video_for', 0);
                    $custom_field= !empty($_POST['custom_field']) ? ($_POST['custom_field']) : array();
                    // because wp_insert_post() does this inside that like : $postarr = sanitize_post($postarr, 'db');
                    $metas = array();
                    $p = $_POST; // save some character
                    $content = !empty($p['listing_content']) ? wp_kses($p['listing_content'], wp_kses_allowed_html('post')) : '';
                    $title= !empty($p['listing_title']) ? sanitize_text_field($p['listing_title']) : '';
                    $tag = !empty($_POST['tax_input']['at_biz_dir-tags'])?($_POST['tax_input']['at_biz_dir-tags']):array();
                    $location = !empty($_POST['tax_input']['at_biz_dir-location'])?($_POST['tax_input']['at_biz_dir-location']):array();

                    $metas['_listing_type']      = !empty($p['listing_type']) ? sanitize_text_field($p['listing_type']) : 0;
                    if(empty($display_price_for) && !empty($display_pricing_field)) {
                        $metas['_price'] = !empty($p['price']) ? (float)$p['price'] : 0;
                    }
                    if ( empty($display_price_range_for) && !empty($display_price_range_field )) {
                        $metas['_price_range'] = !empty($p['price_range']) ? $p['price_range'] : '';
                    }
                    $metas['_atbd_listing_pricing'] = !empty($p['atbd_listing_pricing'])?  $p['atbd_listing_pricing'] : '';
                    if (empty($display_video_for) && !empty($display_video_field)) {
                        $metas['_videourl'] = !empty($p['videourl']) ? sanitize_text_field($p['videourl']) : '';
                    }
                    if (!empty($display_tagline_field) && empty($display_tagline_for)) {
                        $metas['_tagline'] = !empty($p['tagline']) ? sanitize_text_field($p['tagline']) : '';
                    }

                    if (!empty($display_excerpt_field) && empty($display_short_desc_for)) {
                        $metas['_excerpt'] = !empty($p['excerpt']) ? sanitize_text_field($p['excerpt']) : '';
                    }

                    if(!empty($display_views_count) && empty($display_views_count_for)) {
                        $metas['_atbdp_post_views_count'] = !empty($p['atbdp_post_views_count']) ? (int)$p['atbdp_post_views_count'] : '';
                    }
                    if ( empty($display_address_for) && !empty($display_address_field) ) {
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
                    $metas['_faqs']              = !empty($p['faqs']) ? ($p['faqs']) : array(); // we are expecting array value
                    $metas['_bdbh']              = !empty($p['bdbh'])? atbdp_sanitize_array($p['bdbh']) : array();
                    $metas['_enable247hour']      = !empty($p['enable247hour'])? sanitize_text_field($p['enable247hour']) : '';
                    $metas['_disable_bz_hour_listing']      = !empty($p['disable_bz_hour_listing'])? sanitize_text_field($p['disable_bz_hour_listing']) : '';
                    $metas['_manual_lat']        = !empty($p['manual_lat'])? sanitize_text_field($p['manual_lat']) : '';
                    $metas['_manual_lng']        = !empty($p['manual_lng'])? sanitize_text_field($p['manual_lng']) : '';
                    $metas['_hide_map']             = !empty($p['hide_map'])? sanitize_text_field($p['hide_map']) : '';
                    if( empty($display_glr_img_for) && !empty($display_gellery_field)) {
                        $metas['_listing_img'] = !empty($p['listing_img']) ? atbdp_sanitize_array($p['listing_img']) : array();
                    }
                    if (empty($display_prv_img_for) && !empty($display_prv_field)) {
                        $metas['_listing_prv_img'] = !empty($p['listing_prv_img']) ? sanitize_text_field($p['listing_prv_img']) : '';
                    }
                    $metas['_hide_contact_info'] = !empty($p['hide_contact_info'])? sanitize_text_field($p['hide_contact_info']) : 0;
                    $metas['_hide_contact_owner'] = !empty($p['hide_contact_owner'])? sanitize_text_field($p['hide_contact_owner']) : 0;
                    $metas['_t_c_check']         = !empty($p['t_c_check'])? sanitize_text_field($p['t_c_check']) : 0;
                    /**
                     * It applies a filter to the meta values that are going to be saved with the listing submitted from the front end
                     * @param array $metas the array of meta keys and meta values
                    */

                    //@todo need to shift FM validation code to extension itself
                    if (is_fee_manager_active()) {
                        $user_id = get_current_user_id();
                        $midway_package_id = selected_plan_id();
                        $sub_plan_id = get_post_meta($_POST['listing_id'], '_fm_plans', true);
                        $midway_package_id =!empty($midway_package_id)?$midway_package_id:$sub_plan_id;
                        $plan_purchased = subscribed_package_or_PPL_plans($user_id, 'completed',$midway_package_id);
                        if(!class_exists('DWPP_Pricing_Plans')){
                            $plan_purchased = $plan_purchased[0];
                        }
                        $subscribed_package_id = $midway_package_id;
                        $plan_type = package_or_PPL($subscribed_package_id);
                        $order_id = !empty($plan_purchased)?(int)$plan_purchased->ID:'';
                        $user_featured_listing = listings_data_with_plan($user_id, '1', $subscribed_package_id, $order_id);
                        $user_regular_listing = listings_data_with_plan($user_id, '0', $subscribed_package_id, $order_id);
                        $num_regular = get_post_meta($subscribed_package_id, 'num_regular', true);
                        $num_featured = get_post_meta($subscribed_package_id, 'num_featured', true);
                        $total_regular_listing = $num_regular;
                        $total_featured_listing = $num_featured;

                        if ($plan_purchased){
                            $listing_id = get_post_meta($plan_purchased->ID, '_listing_id', true);
                            $featured = get_post_meta($listing_id, '_featured', true);
                            $total_regular_listing = $num_regular - ('0' === $featured?$user_regular_listing+1:$user_regular_listing);
                            $total_featured_listing = $num_featured - ('1' === $featured?$user_featured_listing+1:$user_featured_listing);
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
                            if ((((0 >= $total_regular_listing) && empty($regular_unl)) && ((0 >= $total_featured_listing)) && empty($featured_unl)) || ($remaining_days <= 0)){
                                //if user exit the plan allowance the change the status of that order to cancelled
                                $order_id = $plan_purchased->ID;
                                if (class_exists('woocommerce') && class_exists('DWPP_Pricing_Plans')){
                                    if (('pay_per_listng' != $plan_type)) {
                                        $order = new WC_Order($order_id);
                                        $order->update_status('cancelled', 'order_note');
                                    }
                                }else{
                                    if (('pay_per_listng' != $plan_type)) {
                                        update_post_meta($order_id, '_payment_status', 'cancelled');
                                    }
                                }
                            }
                        }

                        $listing_type = !empty($_POST['listing_type']) ? sanitize_text_field($_POST['listing_type']) : '';
                        //store the plan meta
                        $plan_meta = get_post_meta($subscribed_package_id);
                        $slider_image = $plan_meta['fm_allow_slider'][0];
                        $slider = !empty($slider_image)?$slider_image:'';

                        if (('regular' === $listing_type) && ('package' === $plan_type)) {
                            if (( ($plan_meta['num_regular'][0] < $total_regular_listing) || (0 >= $total_regular_listing)) && empty($plan_meta['num_regular_unl'][0])) {
                                $msg = '<div class="alert alert-danger"><strong>' . __('You have already crossed your limit for regular listing!', 'directorist') . '</strong></div>';
                                return $msg;
                            }
                        }
                        if (('featured' === $listing_type) && ('package' === $plan_type)) {
                            if (( ($plan_meta['num_featured'][0] < $total_featured_listing) || (0 === $total_featured_listing)) && empty($plan_meta['num_featured_unl'][0])) {
                                $msg = '<div class="alert alert-danger"><strong>' . __('You have already crossed your limit for featured listing!', 'directorist') . '</strong></div>';
                                return $msg;

                            }
                        }
                        $listing_images = !empty($metas['_listing_img'])?$metas['_listing_img']:array();
                        $totat_image = count($listing_images);
                        if ($slider){
                            if ($plan_meta['num_image'][0]<$totat_image && empty($plan_meta['num_image_unl'][0])){
                                $msg = '<div class="alert alert-danger"><strong>' . __('You can upload a maximum of '.$plan_meta['num_image'][0].' image(s)', 'directorist') . '</strong></div>';
                                return $msg;
                            }
                        }
                        if (class_exists('BD_Gallery')){
                            $gallery_images = !empty($metas['_gallery_img'])?$metas['_gallery_img']:array();
                            $_gallery_img = count($gallery_images);
                            if ($plan_meta['num_gallery_image'][0]<$_gallery_img && empty($plan_meta['num_gallery_image_unl'][0])){
                                $msg = '<div class="alert alert-danger"><strong>' . __('You can upload a maximum of '.$plan_meta['num_gallery_image'][0].' gallery image(s)', 'directorist') . '</strong></div>';
                                return $msg;
                            }
                        }
                    }
                    $metas = apply_filters('atbdp_listing_meta_user_submission', $metas);
                    $args = array(
                        'post_content' => $content,
                        'post_title' => $title,
                        'post_type' => ATBDP_POST_TYPE,
                        'tax_input' =>!empty($_POST['tax_input'])? atbdp_sanitize_array( $_POST['tax_input'] ) : array(),
                        'meta_input'=>  $metas,

                    );
                    /**
                     * @since 4.4.0
                     *
                     */
                    do_action('atbdp_after_add_listing_afrer_validation');

                    // is it update post ? @todo; change listing_id to atbdp_listing_id later for consistency with rewrite tags
                    if (!empty($_POST['listing_id'])){
                        /**
                         * @since 5.4.0
                         */
                        do_action('atbdp_before_processing_to_update_listing');
                        $edit_l_status = get_directorist_option('edit_listing_status');
                        // update the post
                        $args['ID']= absint($_POST['listing_id']); // set the ID of the post to update the post
                        if (!empty($edit_l_status)){
                            $args['post_status']= $edit_l_status; // set the status of edit listing.
                        }
                        // Check if the current user is the owner of the post
                        $post = get_post($args['ID']);
                        // update the post if the current user own the listing he is trying to edit. or we and give access to the editor or the admin of the post.
                        if (get_current_user_id() == $post->post_author || current_user_can('edit_others_at_biz_dirs')){
                            // Convert taxonomy input to term IDs, to avoid ambiguity.
                            if ( isset( $args['tax_input'] ) ) {
                                foreach ( (array) $args['tax_input'] as $taxonomy => $terms ) {
                                    // Hierarchical taxonomy data is already sent as term IDs, so no conversion is necessary.
                                    if ( is_taxonomy_hierarchical( $taxonomy ) ) {
                                        continue;
                                    }

                                    /*
                                     * Assume that a 'tax_input' string is a comma-separated list of term names.
                                     * Some languages may use a character other than a comma as a delimiter, so we standardize on
                                     * commas before parsing the list.
                                     */
                                    if ( ! is_array( $terms ) ) {
                                        $comma = _x( ',', 'tag delimiter' );
                                        if ( ',' !== $comma ) {
                                            $terms = str_replace( $comma, ',', $terms );
                                        }
                                        $terms = explode( ',', trim( $terms, " \n\t\r\0\x0B," ) );
                                    }

                                    $clean_terms = array();
                                    foreach ( $terms as $term ) {
                                        // Empty terms are invalid input.
                                        if ( empty( $term ) ) {
                                            continue;
                                        }

                                        $_term = get_terms( $taxonomy, array(
                                            'name' => $term,
                                            'fields' => 'ids',
                                            'hide_empty' => false,
                                        ) );

                                        if ( ! empty( $_term ) ) {
                                            $clean_terms[] = intval( $_term[0] );
                                        } else {
                                            // No existing term was found, so pass the string. A new term will be created.
                                            $clean_terms[] = $term;
                                        }
                                    }

                                    $args['tax_input'][ $taxonomy ] = $clean_terms;
                                }
                            }
                            if (!empty($display_title_for)){
                                $args['post_title' ] = get_the_title(absint($_POST['listing_id']));
                            }
                            if (!empty($display_desc_for)){
                                $post_object = get_post(absint($_POST['listing_id']));
                                $content = apply_filters('get_the_content', $post_object->post_content);
                                $args['post_content' ] = $content;
                            }
                            $post_id = wp_update_post($args);
                            if (!empty($p['listing_prv_img'])){
                                set_post_thumbnail( $post_id, sanitize_text_field($p['listing_prv_img']) );
                            }else{
                                delete_post_thumbnail($post_id);
                            }

                            if (!empty($location)){
                                $append = false;
                                if (count($location)>1){
                                    $append = true;
                                }
                                foreach ($location as $single_loc){
                                    $locations =  get_term_by('term_id', $single_loc, ATBDP_LOCATION);
                                    wp_set_object_terms($post_id, $locations->name, ATBDP_LOCATION, $append);
                                }
                            }
                            if (!empty($tag)){
                                if (count($tag)>1){
                                    foreach ($tag as $single_tag){
                                        $tag =  get_term_by('slug', $single_tag, ATBDP_TAGS);
                                        wp_set_object_terms($post_id, $tag->name, ATBDP_TAGS, true);
                                    }
                                }else{
                                    wp_set_object_terms($post_id, $tag[0], ATBDP_TAGS);//update the term relationship when a listing updated by author
                                }
                            }

                            if (!empty($admin_category_select)):
                            update_post_meta( $post_id, '_admin_category_select', $admin_category_select );
                            if (count($admin_category_select)>1){
                                foreach ($admin_category_select as $category){
                                    $term_by_id =  get_term_by('term_id', $category, ATBDP_CATEGORY);
                                    wp_set_object_terms($post_id, $term_by_id->name, ATBDP_CATEGORY, true);//update the term relationship when a listing updated by author
                                }
                            }else{
                                $term_by_id =  get_term_by('term_id', $admin_category_select[0], ATBDP_CATEGORY);
                                wp_set_object_terms($post_id, $term_by_id->name, ATBDP_CATEGORY);//update the term relationship when a listing updated by author
                            }
                            endif;


                            /*
                                 * send the custom field value to the database
                                  */
                            if( isset( $custom_field ) ) {
                                foreach( $custom_field as $key => $value ) {

                                    $type = get_post_meta( $key, 'type', true );

                                    switch( $type ) {
                                        case 'text' :
                                            $value = sanitize_text_field( $value );
                                            break;
                                        case 'textarea' :
                                            $value = esc_textarea( $value );
                                            break;
                                        case 'select' :
                                        case 'radio'  :
                                            $value = sanitize_text_field( $value );
                                            break;
                                        case 'checkbox' :
                                            $value = array_map( 'esc_attr', $value );
                                            $value = implode( "\n", array_filter( $value ) );
                                            break;
                                        case 'url' :
                                            $value = esc_url_raw( $value );
                                            break;
                                        default :
                                            $value = sanitize_text_field( $value );
                                            break;
                                        case 'email' :
                                            $value = sanitize_text_field( $value );
                                            break;
                                        case 'date' :
                                            $value = sanitize_text_field( $value );

                                    }

                                    update_post_meta( $post_id, $key, $value );
                                }
                            }

                            // for dev
                            do_action('atbdp_listing_updated', $post_id);//for sending email notification
                        }else{
                            // kick the user out because he is trying to modify the listing of other user.
                            wp_redirect($_SERVER['REQUEST_URI'].'?error=true');
                            exit();
                        }


                    }else{
                        // the post is a new post, so insert it as new post.
                        if (current_user_can('publish_at_biz_dirs')){
                            $new_l_status = get_directorist_option('new_listing_status', 'pending');
                            $monitization = get_directorist_option('enable_monetization',0);
                            $args['post_status'] = $new_l_status;
                            //if listing under a purchased package
                            if(is_fee_manager_active()){

                                if (('package' === package_or_PPL($plan=null)) && $plan_purchased && ('publish' === $new_l_status)){
                                    // status for paid users
                                    $args['post_status'] = $new_l_status;
                                }else{
                                    // status for non paid users
                                    $args['post_status'] = 'pending';
                                }
                            }elseif (!empty($featured_enabled && $monitization)){
                                $args['post_status'] = 'pending';
                            }
                            else{
                                $args['post_status'] = $new_l_status;
                            }

                            if ( isset( $args['tax_input'] ) ) {
                                foreach ( (array) $args['tax_input'] as $taxonomy => $terms ) {
                                    // Hierarchical taxonomy data is already sent as term IDs, so no conversion is necessary.
                                    if ( is_taxonomy_hierarchical( $taxonomy ) ) {
                                        continue;
                                    }

                                    /*
                                     * Assume that a 'tax_input' string is a comma-separated list of term names.
                                     * Some languages may use a character other than a comma as a delimiter, so we standardize on
                                     * commas before parsing the list.
                                     */
                                    if ( ! is_array( $terms ) ) {
                                        $comma = _x( ',', 'tag delimiter' );
                                        if ( ',' !== $comma ) {
                                            $terms = str_replace( $comma, ',', $terms );
                                        }
                                        $terms = explode( ',', trim( $terms, " \n\t\r\0\x0B," ) );
                                    }

                                    $clean_terms = array();
                                    foreach ( $terms as $term ) {
                                        // Empty terms are invalid input.
                                        if ( empty( $term ) ) {
                                            continue;
                                        }

                                        $_term = get_terms( $taxonomy, array(
                                            'name' => $term,
                                            'fields' => 'ids',
                                            'hide_empty' => false,
                                        ) );

                                        if ( ! empty( $_term ) ) {
                                            $clean_terms[] = intval( $_term[0] );
                                        } else {
                                            // No existing term was found, so pass the string. A new term will be created.
                                            $clean_terms[] = $term;
                                        }
                                    }

                                    $args['tax_input'][ $taxonomy ] = $clean_terms;
                                }
                            }

                            $post_id = wp_insert_post($args);
                            do_action('atbdp_listing_inserted', $post_id);//for sending email notification


                            //Every post with the published status should contain all the post meta keys so that we can include them in query.
                            if ('publish' == $new_l_status || 'pending' == $new_l_status) {
                                $expire_in_days = get_directorist_option('listing_expire_in_days');
                                $never_expire =empty($expire_in_days) ? 1 : 0;
                                $exp_dt = calc_listing_expiry_date();
                                update_post_meta( $post_id, '_expiry_date', $exp_dt );
                                update_post_meta( $post_id, '_never_expire', $never_expire );
                                update_post_meta( $post_id, '_featured', 0 );
                                update_post_meta( $post_id, '_listing_status', 'post_status' );
                                update_post_meta( $post_id, '_admin_category_select', $admin_category_select );
                                /*
                                  * It fires before processing a listing from the front end
                                  * @param array $_POST the array containing the submitted fee data.
                                  * */
                                do_action('atbdp_before_processing_listing_frontend', $post_id);

                                /*
                                * send the custom field value to the database
                             */

                                if( isset( $custom_field ) ) {

                                    foreach( $custom_field as $key => $value ) {

                                        $type = get_post_meta( $key, 'type', true );

                                        switch( $type ) {
                                            case 'text' :
                                                $value = sanitize_text_field( $value );
                                                break;
                                            case 'textarea' :
                                                $value = esc_textarea( $value );
                                                break;
                                            case 'select' :
                                            case 'radio'  :
                                                $value = sanitize_text_field( $value );
                                                break;
                                            case 'checkbox' :
                                                $value = array_map( 'esc_attr', $value );
                                                $value = implode( "\n", array_filter( $value ) );
                                                break;
                                            case 'url' :
                                                $value = esc_url_raw( $value );
                                                break;
                                            default :
                                                $value = sanitize_text_field( $value );
                                                break;
                                            case 'email' :
                                                $value = sanitize_text_field( $value );
                                                break;
                                            case 'date' :
                                                $value = sanitize_text_field( $value );

                                        }

                                        update_post_meta( $post_id, $key, $value );
                                    }
                                }
                                if(!empty($admin_category_select)){
                                    update_post_meta( $post_id, '_admin_category_select', $admin_category_select );
                                    if (count($admin_category_select)>1){
                                        foreach ($admin_category_select as $category){
                                            $term_by_id =  get_term_by('term_id', $category, ATBDP_CATEGORY);
                                            wp_set_object_terms($post_id, $term_by_id->name, ATBDP_CATEGORY, true);//update the term relationship when a listing updated by author
                                        }
                                    }else{
                                        $term_by_id =  get_term_by('term_id', $admin_category_select[0], ATBDP_CATEGORY);
                                        wp_set_object_terms($post_id, $term_by_id->name, ATBDP_CATEGORY);//update the term relationship when a listing updated by author
                                    }
                                }
                                if (!empty($location)){
                                    //update location for user
                                    if (count($location)>1){
                                        foreach ($location as $single_location){
                                            $term_by_id =  get_term_by('term_id', $single_location, ATBDP_LOCATION);
                                            wp_set_object_terms($post_id, $term_by_id->name, ATBDP_LOCATION, true);//update the term relationship when a listing updated by author
                                        }
                                    }else{
                                        $term_by_id =  get_term_by('term_id', $location[0], ATBDP_LOCATION);
                                        wp_set_object_terms($post_id, $term_by_id->name, ATBDP_LOCATION);//update the term relationship when a listing updated by author
                                    }
                                }


                                if(!empty($tag)){
                                    //update TAG for user
                                    if (count($tag)>1){
                                        foreach ($tag as $single_tag){
                                            $term_by_id =  get_term_by('term_id', $single_tag, ATBDP_TAGS);
                                            wp_set_object_terms($post_id, $term_by_id->name, ATBDP_TAGS, true);//update the term relationship when a listing updated by author
                                        }
                                    }else{
                                        wp_set_object_terms($post_id, $tag[0], ATBDP_TAGS);//update the term relationship when a listing updated by author
                                    }
                                }

                            }
                            if ('publish' == $new_l_status){
                                do_action('atbdp_listing_published', $post_id);//for sending email notification
                            }
                        }

                    }
                    if (!empty($post_id)){

                        // Redirect to avoid duplicate form submissions
                        // if monetization on, redirect to checkout page
                        // vail if monetization is not active.
                        if (is_fee_manager_active() ){
                            if(class_exists('DWPP_Pricing_Plans')){
                                $regular_price = get_post_meta($subscribed_package_id, '_regular_price', true);
                                $redirect_page = get_directorist_option('edit_listing_redirect', 'view_listing');
                                if ('pay_per_listng' === package_or_PPL($plan=null)){
                                    if (!empty($regular_price)){
                                        global $woocommerce;
                                        $woocommerce->cart->empty_cart();
                                        $woocommerce->cart->add_to_cart( $subscribed_package_id );
                                        wp_redirect(add_query_arg( 'atbdp_listing', $post_id,  wc_get_checkout_url() ) );
                                        exit;
                                    }else{
                                        update_user_meta(get_current_user_id(), '_used_free_plan', array($subscribed_package_id,$post_id));
                                        if ('view_listing' == $redirect_page){
                                            wp_redirect(get_permalink($post_id));
                                        }else{
                                            wp_redirect(ATBDP_Permalink::get_dashboard_page_link());
                                        }
                                        exit;
                                    }

                                }elseif(('package' === package_or_PPL($plan=null)) && !$plan_purchased){
                                    //lets redirect to woo checkout page
                                    if (!empty($regular_price)){
                                        global $woocommerce;
                                        $woocommerce->cart->empty_cart();
                                        $woocommerce->cart->add_to_cart( $subscribed_package_id );
                                        wp_redirect(add_query_arg( 'atbdp_listing', $post_id,  wc_get_checkout_url() ) );
                                        exit;
                                    }else{
                                        update_user_meta(get_current_user_id(), '_used_free_plan', array($subscribed_package_id,$post_id));

                                        if ('view_listing' == $redirect_page){
                                            wp_redirect(get_permalink($post_id));
                                        }else{
                                            wp_redirect(ATBDP_Permalink::get_dashboard_page_link());
                                        }
                                        exit;
                                    }
                                }else{
                                    //yep! listing is saved to db and redirect user to admin panel or listing itself
                                    if ('view_listing' == $redirect_page){
                                        wp_redirect(get_permalink($post_id));
                                    }else{
                                        wp_redirect(ATBDP_Permalink::get_dashboard_page_link());
                                    }
                                    exit;
                                }
                            }else{
                                if ('pay_per_listng' === package_or_PPL($plan=null)){
                                    wp_redirect(ATBDP_Permalink::get_checkout_page_link($post_id));
                                    exit;
                                }elseif(('package' === package_or_PPL($plan=null)) && !$plan_purchased){
                                    //lets redirect to directorist checkout page
                                    wp_redirect(ATBDP_Permalink::get_checkout_page_link($post_id));
                                    exit;
                                }else{
                                    //yep! listing is saved to db and redirect user to admin panel or listing itself
                                    $redirect_page = get_directorist_option('edit_listing_redirect', 'view_listing');
                                    if ('view_listing' == $redirect_page){
                                        wp_redirect(get_permalink($post_id));
                                    }else{
                                        wp_redirect(ATBDP_Permalink::get_dashboard_page_link());
                                    }
                                    exit;
                                }
                            }

                        }else{
                            //no pay extension own yet let treat as general user
                            if (get_directorist_option('enable_monetization') && !$_POST['listing_id'] && $featured_enabled && (!is_fee_manager_active())){
                                wp_redirect(ATBDP_Permalink::get_checkout_page_link($post_id));
                                exit;
                            }
                            //yep! listing is saved to db and redirect user to admin panel or listing itself
                            $redirect_page = get_directorist_option('edit_listing_redirect', 'view_listing');
                            if ('view_listing' == $redirect_page){
                                wp_redirect(get_permalink($post_id));
                            }else{
                                wp_redirect(ATBDP_Permalink::get_dashboard_page_link());
                            }
                            exit;
                        }

                    }else{
                        /*@todo; redirect back to the listing creation page with data's saying something went wrong*/
                        wp_redirect(site_url().'?error=true');
                    }

                }
                exit;
            }
        }

        /**
         *It outputs nonce field to any any form
         * @param bool       $referrer Optional. Whether to set the referer field for validation. Default true.
         * @param bool       $echo    Optional. Whether to display or return hidden form field. Default true.
         */
        public function show_nonce_field( $referrer = true , $echo = true)
        {
            wp_nonce_field($this->nonce_action, $this->nonce, $referrer, $echo);

        }


        /**
         * It helps to perform different db related action to the listing
         *
         * @since 3.1.0
         * @param WP_Query $query
         */
        public function parse_query($query)
        {
            $action = $query->get('atbdp_action');
            $id = $query->get('atbdp_listing_id');
            if (!empty($action) && !empty($id)){
                // handle renewing the listing
                if ('renew' == $action){
                    $this->renew_listing($id);
                }
            }
        }


        /**
         * It renews the given listing
         * @since 3.1.0
         * @param $listing_id
         * @return mixed
         */
        private function renew_listing($listing_id)
        {
            $can_renew = get_directorist_option('can_renew_listing');
            if (!$can_renew) return false;// vail if renewal option is turned off on the site.
            // Hook for developers
            do_action( 'atbdp_before_renewal', $listing_id );
            update_post_meta( $listing_id, '_featured', 0 ); // delete featured
            //for listing package extensions...
            $has_paid_submission = apply_filters( 'atbdp_has_paid_submission', 0, $listing_id, 'renew' );

            $active_monetization = get_directorist_option('enable_monetization');
            if( $has_paid_submission && $active_monetization) {
                // if paid submission enabled/triggered by an extension, redirect to the checkout page and let that handle it, and vail out.
                wp_safe_redirect(ATBDP_Permalink::get_checkout_page_link( $listing_id ));
                exit;
            }

            $time = current_time('mysql');
            $post_array = array(
                'ID'          	=> $listing_id,
                'post_status' 	=> 'publish',
                'post_date'   	=> $time,
                'post_date_gmt' => get_gmt_from_date( $time )
            );

            //Updating listing
            wp_update_post( $post_array );

            // Update the post_meta into the database
            $old_status = get_post_meta( $listing_id, '_listing_status', true );
            if( 'expired' == $old_status ) {
                $expiry_date = calc_listing_expiry_date();
            } else {
                $old_expiry_date = get_post_meta( $listing_id, '_expiry_date', true );
                $expiry_date = calc_listing_expiry_date( $old_expiry_date );
            }
            // update related post metas
            update_post_meta( $listing_id, '_expiry_date', $expiry_date );
            update_post_meta( $listing_id, '_listing_status', 'post_status' );

            $exp_days = get_directorist_option('listing_expire_in_days', 999, 999);
            if ($exp_days <= 0) { update_post_meta($listing_id, '_never_expire', 1);
            }else{ update_post_meta($listing_id, '_never_expire', 0); }
            do_action( 'atbdp_after_renewal', $listing_id );


            $featured_active = get_directorist_option('enable_featured_listing');
            $has_paid_submission = apply_filters( 'atbdp_has_paid_submission', $featured_active, $listing_id, 'renew' );
            if( $has_paid_submission && $active_monetization) {
                $r_url = ATBDP_Permalink::get_checkout_page_link( $listing_id );
            }else{
                //@todo; Show notification on the user page after renewing.
               $r_url = add_query_arg('renew', 'success', ATBDP_Permalink::get_dashboard_page_link());
            }
            // hook for dev
            do_action( 'atbdp_before_redirect_after_renewal', $listing_id );
            wp_safe_redirect($r_url);
            exit;

        }


    } // ends ATBDP_Add_Listing


endif;