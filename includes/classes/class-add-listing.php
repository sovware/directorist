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
         * @return void
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

                    $metas['_price']             = !empty($p['price'])? (float) $p['price'] : 0;
                    $metas['_videourl']           = !empty($p['videourl'])? sanitize_text_field($p['videourl']) : '';
                    $metas['_tagline']           = !empty($p['tagline'])? sanitize_text_field($p['tagline']) : '';
                    $metas['_excerpt']           = !empty($p['excerpt'])? sanitize_text_field($p['excerpt']) : '';
                    $metas['_address']           = !empty($p['address'])? sanitize_text_field($p['address']) : '';
                    $metas['_phone']             = !empty($p['phone'])? sanitize_text_field($p['phone']) : '';
                    $metas['_email']             = !empty($p['email'])? sanitize_text_field($p['email']) : '';
                    $metas['_website']           = !empty($p['website'])? sanitize_text_field($p['website']) : '';
                    $metas['_social']            = !empty($p['social']) ? atbdp_sanitize_array($p['social']) : array(); // we are expecting array value
                    $metas['_manual_lat']        = !empty($p['manual_lat'])? sanitize_text_field($p['manual_lat']) : '';
                    $metas['_manual_lng']        = !empty($p['manual_lng'])? sanitize_text_field($p['manual_lng']) : '';
                    $metas['_listing_img']       = !empty($p['listing_img'])? atbdp_sanitize_array($p['listing_img']) : array();
                    $metas['_hide_contact_info'] = !empty($p['hide_contact_info'])? sanitize_text_field($p['hide_contact_info']) : 0;
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

                    //let check all the required custom field
                    foreach ($custom_field as $key => $value) {
                        $require = get_post_meta($key, 'required', true);
                        if ($require){
                            switch( $require ) {
                                case '1' :
                                    $Check_require = $value;
                                    break;
                            }
                            if (empty($Check_require)){
                                $msg = '<div class="alert alert-danger"><strong>Please fill up the require field marked with <span style="color:                            red">*</span></strong></div>';
                                return $msg;
                            }
                        }
                    }
                    //check the title is empty or not
                    if (empty($title)){
                        $msg = '<div class="alert alert-danger"><strong>Please fill up the require field marked with <span                                          style="color: red">*</span></strong></div>';
                        return $msg;
                    }

                    if(get_directorist_option('listing_terms_condition') == 1){
                        if ($t_c_check == ''){
                            $msg = '<div class="alert alert-danger"><strong>Please fill up the require field marked with <span                                          style="color: red">*</span></strong></div>';
                            return $msg;
                        }
                    }
                    if (class_exists('ATBDP_Fee_Manager') && empty($p['fm_plans'])){
                        $msg = '<div class="alert alert-danger"><strong>You need to select a plan in order to submit a listing <span                                          style="color: red">*</span></strong></div>';
                        return $msg;
                    }
                    
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
                                wp_set_object_terms($post_id, $term_by_id->name, ATBDP_CATEGORY);//update the term relationship when a listing updated by author
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
                       if (get_directorist_option('enable_monetization')){
                           wp_redirect(ATBDP_Permalink::get_checkout_page_link($post_id));
                           exit;
                       }
                        wp_redirect(get_permalink($post_id));
                        exit;
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