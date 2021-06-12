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
                    
                $guest                 = get_directorist_option('guest_listings', 0);
                $featured_enabled      = get_directorist_option('enable_featured_listing');

                 // data validation
                 $directory = !empty( $info['directory_type'] ) ? sanitize_text_field( $info['directory_type'] ) : '';
                 $submission_form_fields = [];
                 $metas = [];

                 if ( $directory ){
                    $term                   = get_term_by( is_numeric( $directory ) ? 'id' : 'slug' , $directory, ATBDP_TYPE );
                    $directory_type         = $term->term_id;
                    $submission_form        = get_term_meta( $directory_type, 'submission_form_fields', true );
                    $new_l_status           = get_term_meta( $directory_type, 'new_listing_status', true );
                    $edit_l_status          = get_term_meta( $directory_type, 'edit_listing_status', true );
                    $default_expiration     = get_term_meta( $directory_type, 'default_expiration', true );
                    $preview_enable         = atbdp_is_truthy( get_term_meta( $directory_type, 'preview_mode', true ) );
                    $submission_form_fields = $submission_form['fields'];
                 }
                // isolate data
                $error = [];
                $dummy = [];

                $tag = !empty( $info['tax_input']['at_biz_dir-tags']) ? ( $info['tax_input']['at_biz_dir-tags']) : array();
                $location = !empty( $info['tax_input']['at_biz_dir-location']) ? ( $info['tax_input']['at_biz_dir-location']) : array();
                $admin_category_select = !empty( $info['tax_input']['at_biz_dir-category']) ? ( $info['tax_input']['at_biz_dir-category']) : array();
                $images = !empty( $info['files_meta']) ? ( $info['files_meta']) : array();
                $manual_lat = !empty( $info['manual_lat']) ? ( $info['manual_lat']) : array();
                $manual_lng = !empty( $info['manual_lng']) ? ( $info['manual_lng']) : array();
                $map        = !empty( $manual_lat ) && !empty( $manual_lng ) ? true : false;
                // meta input
                foreach( $submission_form_fields as $key => $value ){
                    $field_key = !empty( $value['field_key'] ) ? $value['field_key'] : '';
                    $submitted_data = !empty( $info[ $field_key ] ) ? $info[ $field_key ] : '';
                    $required = !empty( $value['required'] ) ? $value['required'] : '';
                    $only_for_admin = !empty( $value['only_for_admin'] ) ? $value['only_for_admin'] : '';
                    $label = !empty( $value['label'] ) ? $value['label'] : '';
                    $additional_logic = apply_filters( 'atbdp_add_listing_form_validation_logic', true, $value, $info );
                    
                    $field_category = !empty( $value['category'] ) ? $value['category'] : '';
                    if( $field_category && ! in_array( $field_category, $admin_category_select ) ) {
                        $additional_logic = false;
                    }

                    if( $additional_logic ) {
                        // error handling
                        if( ( 'category' === $key ) && $required && !$only_for_admin && !$admin_category_select) {
                            $msg = $label .__( ' field is required!', 'directorist' );
                            array_push( $error, $msg );
                        }

                        if( ( 'location' === $key ) && $required && !$only_for_admin && !$location) {
                            $msg = $label .__( ' field is required!', 'directorist' );
                            array_push( $error, $msg );
                        }

                        if( ( 'tag' === $key ) && $required && !$only_for_admin && !$tag) {
                            $msg = $label .__( ' field is required!', 'directorist' );
                            array_push( $error, $msg );
                        }

                        if( ( 'image_upload' === $key ) && $required && !$only_for_admin && !$images) {
                            $msg = $label .__( ' field is required!', 'directorist' );
                            array_push( $error, $msg );
                        }

                        if( ( 'map' === $key ) && $required && !$only_for_admin && !$map) {
                            $msg = $label .__( ' field is required!', 'directorist' );
                            array_push( $error, $msg );
                        }

                        if( ( 'category' !== $key ) && ( 'tag' !== $key ) && ( 'location' !== $key ) && ( 'image_upload' !== $key ) && ( 'map' !== $key ) ) {
                            if( $required && !$submitted_data && !$only_for_admin ){
                                $msg = $label .__( ' field is required!', 'directorist' );
                                array_push( $error, $msg );
                            }
                        }
                    }
                    
                    // array_push( $dummy, [
                    //     'label' => $label,
                    //     'key' => $key,
                    //     'value' => $value,
                    //     'submitted_data' => $submitted_data,
                    //     'additional_logic' => $additional_logic,
                    //     'form_data' => $info,
                    //     'category' => $admin_category_select,
                    //     ] );

                    // process meta
                    if( 'pricing' === $key ) {
                        $metas[ '_atbd_listing_pricing' ] = !empty( $info['atbd_listing_pricing'] ) ? $info['atbd_listing_pricing'] : '';
                        $metas[ '_price' ] = !empty( $info['price'] ) ? $info['price'] : '';
                        $metas[ '_price_range' ] = !empty( $info['price_range'] ) ? $info['price_range'] : '';
                    }
                    if( 'map' === $key ) {
                        $metas[ '_hide_map' ]   = !empty( $info['hide_map'] ) ? $info['hide_map'] : '';
                        $metas[ '_manual_lat' ] = !empty( $info['manual_lat'] ) ? $info['manual_lat'] : '';
                        $metas[ '_manual_lng' ] = !empty( $info['manual_lng'] ) ? $info['manual_lng'] : '';
                    }
                    if( ( $field_key !== 'listing_title' ) && ( $field_key !== 'listing_content' ) && ( $field_key !== 'tax_input' ) ){
                        $key = '_'. $field_key;
                        $metas[ $key ] = !empty( $info[ $field_key ] ) ? $info[ $field_key ] : '';
                    }                    
                }
    
                // wp_send_json( $error );
                $title = !empty( $info['listing_title']) ? sanitize_text_field( $info['listing_title']) : '';
                $content = !empty( $info['listing_content']) ? wp_kses( $info['listing_content'], wp_kses_allowed_html('post')) : '';

                if( !empty( $info['privacy_policy'] ) ) {
                    $metas[ '_privacy_policy' ] = $info['privacy_policy'] ? $info['privacy_policy'] : '';
                }
                if( !empty( $info['t_c_check'] ) ) {
                    $metas[ '_t_c_check' ] = $info['t_c_check'] ? $info['t_c_check'] : '';
                }
                $metas['_directory_type'] = $directory_type;
                // guest user
                if (!atbdp_logged_in_user()) {
                    $guest_email = isset($info['guest_user_email']) ? esc_attr($info['guest_user_email']) : '';
                    if (!empty($guest && $guest_email)) {
                        atbdp_guest_submission($guest_email);
                    }
                }

                if( $error ){
                    $data['error_msg'] = $error;
                    $data['error'] = true;
                }
                /**
                 * It applies a filter to the meta values that are going to be saved with the listing submitted from the front end
                 * @param array $metas the array of meta keys and meta values
                 */
             
                $metas = apply_filters('atbdp_listing_meta_user_submission', $metas);
                // wp_send_json($metas);
                $args = array(
                    'post_content' => $content,
                    'post_title' => $title,
                    'post_type' => ATBDP_POST_TYPE,
                    'tax_input' => !empty($info['tax_input']) ? atbdp_sanitize_array($info['tax_input']) : array(),
                    'meta_input' => apply_filters( 'atbdp_ultimate_listing_meta_user_submission', $metas, $info ),
                );
                // is it update post ? @todo; change listing_id to atbdp_listing_id later for consistency with rewrite tags
                if (!empty($info['listing_id'])) {
                    /**
                     * @since 5.4.0
                     */
                    do_action('atbdp_before_processing_to_update_listing');

                    $listing_id = absint( $info['listing_id'] );
                    $_args = [ 'id' => $listing_id, 'edited' => true, 'new_l_status' => $new_l_status, 'edit_l_status' => $edit_l_status];
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

                        $post_id = wp_update_post($args);
                        update_post_meta($post_id, '_directory_type', $directory_type);

                        if( !empty( $directory_type ) ){
                            wp_set_object_terms($post_id, (int)$directory_type, 'atbdp_listing_types');
                        }
                       
                        if (!empty($location)) {
                            $append = false;
                            if (count($location) > 1) {
                                $append = true;
                            }
                            foreach ($location as $single_loc) {
                                $locations = get_term_by('term_id', $single_loc, ATBDP_LOCATION);
                                if( !$locations ){
                                    $result = wp_insert_term( $single_loc, ATBDP_LOCATION );
                                    if( !is_wp_error( $result ) ){
                                        $term_id = $result['term_id'];
                                        wp_set_object_terms($post_id, $term_id, ATBDP_LOCATION, $append);
                                        update_term_meta($term_id, '_directory_type', [ $directory_type ]);

                                    }
                                }else{
                                    wp_set_object_terms($post_id, $locations->name, ATBDP_LOCATION, $append);
                                }
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
                                if( !$cat ){
                                    $result = wp_insert_term( $single_category, ATBDP_CATEGORY );
                                    if( !is_wp_error( $result ) ){
                                        $term_id = $result['term_id'];
                                        wp_set_object_terms($post_id, $term_id, ATBDP_CATEGORY, $append);
                                        update_term_meta($term_id, '_directory_type', [ $directory_type ]);
                                    }
                                }else{
                                    wp_set_object_terms($post_id, $cat->name, ATBDP_CATEGORY, $append);
                                }
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
                        $_args = [ 'id' => '', 'new_l_status' => $new_l_status, 'edit_l_status' => $edit_l_status];
                        $post_status = atbdp_get_listing_status_after_submission( $_args );
                        
                        $args['post_status'] = $post_status;

                        if ('pending' === $post_status) {
                            $data['pending'] = true;
                        }
                        
                        $monitization = get_directorist_option('enable_monetization', 0);
                        //if listing under a purchased package
                        // if (is_fee_manager_active()) {
                        //     if (('package' === package_or_PPL($plan = null)) && $plan_purchased && ('publish' === $new_l_status)) {
                        //         // status for paid users
                        //         $args['post_status'] = $new_l_status;
                        //     } else {
                        //         // status for non paid users
                        //         $args['post_status'] = 'pending';
                        //     }
                        // } 
                        if (!empty($featured_enabled && $monitization) && ('featured' === $info['listing_type'] ) ) {
                            $args['post_status'] = 'pending';
                        } else {
                            $args['post_status'] = $post_status;
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
                        
                        update_post_meta($post_id, '_directory_type', $directory_type);
                        do_action('atbdp_listing_inserted', $post_id);//for sending email notification

                        //Every post with the published status should contain all the post meta keys so that we can include them in query.
                        if ('publish' == $new_l_status || 'pending' == $new_l_status) {

                            if( ! $default_expiration ){
                                update_post_meta($post_id, '_never_expire', 1);
                            }else{
                                $exp_dt = calc_listing_expiry_date( '', $default_expiration );
                                update_post_meta($post_id, '_expiry_date', $exp_dt);
                            }
                           
                            update_post_meta($post_id, '_featured', 0);
                            update_post_meta($post_id, '_listing_status', 'post_status');
                            update_post_meta($post_id, '_admin_category_select', $admin_category_select);
                            /*
                              * It fires before processing a listing from the front end
                              * @param array $_POST the array containing the submitted fee data.
                              * */
                            do_action('atbdp_before_processing_listing_frontend', $post_id);
                            
                            // set up terms
                            if( !empty( $directory_type ) ){
                                wp_set_object_terms($post_id, (int)$directory_type, 'atbdp_listing_types');
                            }
                            // location
                            if (!empty($location)) {
                                $append = false;
                                if (count($location) > 1) {
                                    $append = true;
                                }
                                foreach ($location as $single_loc) {
                                    $locations = get_term_by('term_id', $single_loc, ATBDP_LOCATION);
                                    if( !$locations ) {
                                        $result = wp_insert_term( $single_loc, ATBDP_LOCATION );
                                        if( !is_wp_error( $result ) ){
                                            $term_id = $result['term_id'];
                                            wp_set_object_terms($post_id, $term_id, ATBDP_LOCATION, $append);
                                            update_term_meta($term_id, '_directory_type', [ $directory_type ]);
                                        }
                                    } else {
                                        wp_set_object_terms($post_id, $locations->name, ATBDP_LOCATION, $append);
                                    }                                }
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
                                    if( !$cat ){
                                        $result = wp_insert_term( $single_category, ATBDP_CATEGORY );
                                        if( !is_wp_error( $result ) ){
                                            $term_id = $result['term_id'];
                                            wp_set_object_terms($post_id, $term_id, ATBDP_CATEGORY, $append);
                                            update_term_meta($term_id, '_directory_type', [ $directory_type ]);
                                        }
                                    }else{
                                        wp_set_object_terms($post_id, $cat->name, ATBDP_CATEGORY, $append);
                                    }
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
                            update_post_meta($post_id, '_listing_prv_img', $value['attachmentID']);
                            set_post_thumbnail($post_id, $value['attachmentID']);
                        }
                        if ($key === 0 && $value['oldFile'] !== 'true') {
                            foreach ($attach_data as $item) {
                                if ($item['name'] === $value['name']) {
                                    $id = $item['id'];
                                    update_post_meta($post_id, '_listing_prv_img', $id);
                                    set_post_thumbnail($post_id, $id);
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
                    update_post_meta($post_id, '_listing_img', $new_files_meta);
                    $permalink = ATBDP_Permalink::get_listing_permalink( $post_id, get_permalink( $post_id ) );
                    //no pay extension own yet let treat as general user

                    $submission_notice = get_directorist_option('submission_confirmation', 1);
                    $redirect_page = get_directorist_option('edit_listing_redirect', 'view_listing');
                    
                    if ('view_listing' == $redirect_page) {
                        $data['redirect_url'] = $submission_notice ? add_query_arg( 'notice', true, $permalink ) : $permalink ;
                    } else {
                        $data['redirect_url'] = $submission_notice ? add_query_arg( 'notice', true, ATBDP_Permalink::get_dashboard_page_link() ) : ATBDP_Permalink::get_dashboard_page_link();
                    }

                    $states = [];
                    $states['monetization_is_enable'] = get_directorist_option('enable_monetization');
                    $states['featured_enabled']       = $featured_enabled;
                    $states['listing_is_featured']    = ('featured' === $info['listing_type'] ) ? true : false;
                    $states['is_monetizable']         = ( $states['monetization_is_enable'] && $states['featured_enabled'] && $states['listing_is_featured'] ) ? true : false;

                    if ( $states['is_monetizable'] ) {
                        $payment_status = Directorist\Helper::get_listing_payment_status( $post_id );
                        $rejectable_payment_status = [ 'failed', 'cancelled', 'refunded' ];

                        if ( empty( $payment_status ) || in_array( $payment_status, $rejectable_payment_status ) ) {
                            $data['redirect_url'] = ATBDP_Permalink::get_checkout_page_link($post_id);
                            $data['need_payment'] = true;
                        }
                    }

                    $data['success'] = true;

                } else {
                    $data['redirect_url'] = site_url() . '?error=true';
                    $data['error'] = true;
                }

                if (!empty($data['success']) && $data['success'] === true) {
                    $data['success_msg'] = __('Your Submission is Completed! redirecting..', 'directorist');
                }

                if (!empty($data['error']) && $data['error'] === true) {
                    $data['error_msg'] = isset($data['error_msg']) ? $data['error_msg'] : __('Sorry! Something Wrong with Your Submission', 'directorist');
                } else{
                    $data['preview_url'] = $permalink;
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

                wp_send_json( apply_filters( 'atbdp_listing_form_submission_info', $data ) );
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
                $directory_type = get_post_meta( $listing_id, '_directory_type', true );
                $exp_days = get_term_meta( $directory_type, 'default_expiration', true);
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