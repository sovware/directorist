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
                    // we have data and passed the security
                    // we not need to sanitize post vars to be saved to the database,
                    // because wp_insert_post() does this inside that like : $postarr = sanitize_post($postarr, 'db');;

                    $admin_category_select= !empty($_POST['admin_category_select']) ? sanitize_text_field($_POST['admin_category_select']) : '';
                    $t_c_check= !empty($_POST['t_c_check']) ? sanitize_text_field($_POST['t_c_check']) : '';
                    $custom_field= !empty($_POST['custom_field']) ? ($_POST['custom_field']) : array();
                    // because wp_insert_post() does this inside that like : $postarr = sanitize_post($postarr, 'db');
                    $metas = array();
                    $p = $_POST; // save some character
                    $content = !empty($p['listing_content']) ? wp_kses($p['listing_content'], wp_kses_allowed_html('post')) : '';
                    $title= !empty($p['listing_title']) ? sanitize_text_field($p['listing_title']) : '';/*@todo; in future, do not let the user add a post without a title. Return here with an error shown to the user*/
                    $tagcount = !empty($_POST['tax_input']['at_biz_dir-tags'])?(count($_POST['tax_input']['at_biz_dir-tags'])):'';
                    $location = !empty($_POST['tax_input']['at_biz_dir-location'])?true:false;

                    $metas['_listing_type']      = !empty($p['listing_type']) ? sanitize_text_field($p['listing_type']) : 0;
                    $metas['_price']             = !empty($p['price'])? (float) $p['price'] : 0;
                    $metas['_price_range']       = !empty($p['price_range'])?  $p['price_range'] : '';
                    $metas['_atbd_listing_pricing'] = !empty($p['atbd_listing_pricing'])?  $p['atbd_listing_pricing'] : '';
                    $metas['_videourl']          = !empty($p['videourl'])? sanitize_text_field($p['videourl']) : '';
                    $metas['_tagline']           = !empty($p['tagline'])? sanitize_text_field($p['tagline']) : '';
                    $metas['_excerpt']           = !empty($p['excerpt'])? sanitize_text_field($p['excerpt']) : '';
                    $metas['_address']           = !empty($p['address'])? sanitize_text_field($p['address']) : '';
                    $metas['_phone']             = !empty($p['phone'])? sanitize_text_field($p['phone']) : '';
                    $metas['_email']             = !empty($p['email'])? sanitize_text_field($p['email']) : '';
                    $metas['_website']           = !empty($p['website'])? sanitize_text_field($p['website']) : '';
                    $metas['_social']            = !empty($p['social']) ? atbdp_sanitize_array($p['social']) : array(); // we are expecting array value
                    $metas['_faqs']              = !empty($p['faqs']) ? atbdp_sanitize_array($p['faqs']) : array(); // we are expecting array value
                    $metas['_bdbh']              = !empty($p['bdbh'])? atbdp_sanitize_array($p['bdbh']) : array();
                    $metas['_enable247hour']      = !empty($p['enable247hour'])? sanitize_text_field($p['enable247hour']) : '';
                    $metas['_disable_bz_hour_listing']      = !empty($p['disable_bz_hour_listing'])? sanitize_text_field($p['disable_bz_hour_listing']) : '';
                    $metas['_manual_lat']        = !empty($p['manual_lat'])? sanitize_text_field($p['manual_lat']) : '';
                    $metas['_manual_lng']        = !empty($p['manual_lng'])? sanitize_text_field($p['manual_lng']) : '';
                    $metas['_hide_map']             = !empty($p['hide_map'])? sanitize_text_field($p['hide_map']) : '';
                    $metas['_hide_map']             = !empty($p['hide_map'])? sanitize_text_field($p['hide_map']) : '';
                    $metas['_listing_img']       = !empty($p['listing_img'])? atbdp_sanitize_array($p['listing_img']) : array();
                    $metas['_listing_prv_img']   = !empty($p['listing_prv_img'])? sanitize_text_field($p['listing_prv_img']) :'';
                    $metas['_hide_contact_info'] = !empty($p['hide_contact_info'])? sanitize_text_field($p['hide_contact_info']) : 0;
                    $metas['_hide_contact_owner'] = !empty($p['hide_contact_owner'])? sanitize_text_field($p['hide_contact_owner']) : 0;
                    $metas['_t_c_check']         = !empty($p['t_c_check'])? sanitize_text_field($p['t_c_check']) : 0;
                    /**
                     * It applies a filter to the meta values that are going to be saved with the listing submitted from the front end
                     * @param array $metas the array of meta keys and meta values
                    */
                    $metas = apply_filters('atbdp_listing_meta_user_submission', $metas);
                    $args = array(
                        'post_content' => $content,
                        'post_title' => $title,
                        'post_type' => ATBDP_POST_TYPE,
                        'tax_input' =>!empty($_POST['tax_input'])? atbdp_sanitize_array( $_POST['tax_input'] ) : array(),
                        'meta_input'=>  $metas,

                    );

                    $msg = '<div class="alert alert-danger"><strong>'.__('Please fill up the required field marked with ', ATBDP_TEXTDOMAIN).'<span style="color: red">*</span></strong></div>';
                    //let check all the required custom field

                    if (is_fee_manager_active()){
                        $user_id = get_current_user_id();
                        $midway_package_id = selected_plan_id();
                        $sub_plan_id = get_post_meta($_POST['listing_id'], '_fm_plans', true);
                        $midway_package_id =!empty($midway_package_id)?$midway_package_id:$sub_plan_id;
                        $plan_purchased = subscribed_package_or_PPL_plans($user_id, 'completed',$midway_package_id);
                        $subscribed_package_id = $midway_package_id;
                    }
                    //check the title is empty or not
                    $term_visable = get_directorist_option('listing_terms_condition');
                    if((get_directorist_option('require_terms_conditions') == 1) && empty($t_c_check) &&  $term_visable){
                      return $msg;
                    }



                    $plan_social_networks = true;
                    if (is_fee_manager_active()){
                        $plan_social_networks = is_plan_allowed_listing_social_networks($subscribed_package_id);
                    }
                    $Sinfo_visable = get_directorist_option('display_social_info_for', 'admin_users');
                    if((get_directorist_option('require_social_info') == 1) && empty($p['social']) && $plan_social_networks && ('admin_users' === $Sinfo_visable)){
                        return $msg;
                    }
                    $plan_slider = true;
                    if (is_fee_manager_active()){
                        $plan_slider =is_plan_allowed_slider($subscribed_package_id);
                    }
                    $preview_visable = get_directorist_option('display_prv_img_for', 'admin_users');
                    $gallery_visable = get_directorist_option('display_glr_img_for', 'admin_users');
                    if((get_directorist_option('require_preview_img') == 1) && empty($p['listing_prv_img']) && $plan_slider && ('admin_users' === $preview_visable)){
                        return $msg;
                    }if((get_directorist_option('require_gallery_img') == 1) && empty($p['listing_img']) && $plan_slider && ('admin_users' === $gallery_visable)){
                        return $msg;
                    }
                    $plan_video = true;
                    if (is_fee_manager_active()){
                        $plan_video =is_plan_allowed_listing_video($subscribed_package_id);
                    }
                    $video_visable = get_directorist_option('display_video_for', 'admin_users');
                    if((get_directorist_option('require_video') == 1) && empty($p['videourl']) && $plan_video && ('admin_users' === $video_visable)){
                        return $msg;
                    }
                    //@todo need to shift FM validation code to extension itself
                    if (is_fee_manager_active()) {
                        $subscribed_date = get_user_meta($user_id, '_subscribed_time', true);
                        $package_length = get_post_meta($subscribed_package_id, 'fm_length', true);
                        $plan_type = get_post_meta($subscribed_package_id, 'plan_type', true);
                        $package_length = $package_length ? $package_length : '1';

                        // Current time
                        $start_date = !empty($subscribed_date) ? $subscribed_date : '';
                        // Calculate new date
                        $date = new DateTime($start_date);
                        $date->add(new DateInterval("P{$package_length}D")); // set the interval in days
                        $expired_date = $date->format('Y-m-d H:i:s');
                        $current_d = current_time('mysql');
                        $remaining_days = ($expired_date > $current_d) ? (floor(strtotime($expired_date) / (60 * 60 * 24)) - floor(strtotime($current_d) / (60 * 60 * 24))) : 0; //calculate the number of days remaining in a plan
                        $listing_type = !empty($_POST['listing_type']) ? sanitize_text_field($_POST['listing_type']) : '';
                        $_general_type = listings_data_with_plan($user_id, '0', $subscribed_package_id, 'regular');// find the user has subscribed or not
                        $has_featured_type = listings_data_with_plan($user_id, '1', $subscribed_package_id, 'featured');
                        //store the plan meta
                        $plan_meta = get_post_meta($subscribed_package_id);
                        $slider_image = $plan_meta['fm_allow_slider'][0];
                        $slider = !empty($slider_image)?$slider_image:'';
                        $fm_allow_price_range = $plan_meta['fm_allow_price'][0];
                        $fm_allow_tag = $plan_meta['fm_allow_tag'][0];
                        if ($fm_allow_tag){
                            if ($plan_meta['fm_tag_limit'][0]<$tagcount && empty($plan_meta['fm_tag_limit_unl'][0])){
                                $msg = '<div class="alert alert-danger"><strong>' . __('You can use a maximum of '.$plan_meta['fm_tag_limit'][0].' tag(s)', ATBDP_TEXTDOMAIN) . '</strong></div>';
                                return $msg;
                            }
                        }

                        if ($fm_allow_price_range){
                            if (empty($plan_meta['price_range_unl'][0]) || !empty($plan_meta['price_range'][0])){
                                $price = !empty($metas['_price'])?$metas['_price']:'';
                                if ($price>$plan_meta['price_range'][0]){
                                    //var_dump($plan_meta['price_range'][0]);die();
                                    $msg = '<div class="alert alert-danger"><strong>' . __('Given price is not included in this plan!', ATBDP_TEXTDOMAIN) . '</strong></div>';
                                    return $msg;
                                }
                            }
                        }


                        if (('regular' === $listing_type) && ('package' === $plan_type)) {
                            if (($plan_meta['num_regular'][0] < $_general_type) && empty($plan_meta['num_regular_unl'][0])) {
                                $msg = '<div class="alert alert-danger"><strong>' . __('You have already crossed your limit for regular listing!', ATBDP_TEXTDOMAIN) . '</strong></div>';
                                return $msg;
                            }
                        }
                        if (('featured' === $listing_type) && ('package' === $plan_type)) {
                            //var_dump($has_featured_type);die();
                            if (($plan_meta['num_featured'][0] <= $has_featured_type) && empty($plan_meta['num_featured_unl'][0])) {
                                $msg = '<div class="alert alert-danger"><strong>' . __('You have already crossed your limit for featured listing!', ATBDP_TEXTDOMAIN) . '</strong></div>';
                                return $msg;

                            }
                        }
                        $totat_image = count($metas['_listing_img']);
                        if ($slider){
                            if ($plan_meta['num_image'][0]<$totat_image && empty($plan_meta['num_image_unl'][0])){
                                $msg = '<div class="alert alert-danger"><strong>' . __('You can upload a maximum of '.$plan_meta['num_image'][0].' image(s)', ATBDP_TEXTDOMAIN) . '</strong></div>';
                                return $msg;
                            }
                        }
                       if (class_exists('BD_Gallery')){
                           $_gallery_img = count($metas['_gallery_img']);
                           if ($plan_meta['num_gallery_image'][0]<$_gallery_img && empty($plan_meta['num_gallery_image_unl'][0])){
                               $msg = '<div class="alert alert-danger"><strong>' . __('You can upload a maximum of '.$plan_meta['num_gallery_image'][0].' gallery image(s)', ATBDP_TEXTDOMAIN) . '</strong></div>';
                               return $msg;
                           }
                       }

                    }
                    /**
                     * @since 4.4.0
                     *
                     */
                    do_action('atbdp_after_add_listing_afrer_validation');

                    // is it update post ? @todo; change listing_id to atbdp_listing_id later for consistency with rewrite tags
                    if (!empty($_POST['listing_id'])){
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


                            $post_id = wp_update_post($args);
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
                                update_post_meta( $post_id, '_admin_category_select', $admin_category_select );


                                $term_by_id =  get_term_by('term_id', $admin_category_select, ATBDP_CATEGORY);
                                wp_set_object_terms($post_id, $term_by_id->name, ATBDP_CATEGORY);//update the term relationship when a listing updated by author
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
                            $args['post_status'] = $new_l_status;
                            //if listing under a purchased package
                            if(is_fee_manager_active()){
                                if (('package' === package_or_PPL($plan=null)) && $plan_purchased){
                                    $args['post_status'] = 'publish';
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
                                $term_by_id =  get_term_by('term_id', $admin_category_select, ATBDP_CATEGORY);
                                $term_by_id = !empty($term_by_id)?$term_by_id->name:'';
                                wp_set_object_terms($post_id, $term_by_id, ATBDP_CATEGORY);//update the term relationship when a listing updated by author
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
                                if ('pay_per_listng' === package_or_PPL($plan=null)){
                                    global $woocommerce;
                                    $woocommerce->cart->empty_cart();
                                    $woocommerce->cart->add_to_cart( $subscribed_package_id );
                                    wp_redirect( wc_get_checkout_url() );
                                    exit;
                                }elseif(('package' === package_or_PPL($plan=null)) && !$plan_purchased){
                                    //lets redirect to woo checkout page
                                  global $woocommerce;
                                    $woocommerce->cart->empty_cart();
                                    $woocommerce->cart->add_to_cart( $subscribed_package_id );
                                    wp_redirect( wc_get_checkout_url() );
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
                            $featured_enabled = get_directorist_option('enable_featured_listing');
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