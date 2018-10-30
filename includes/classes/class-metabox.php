<?php

if ( !class_exists('ATBDP_Metabox') ):
class ATBDP_Metabox {

    /**
     * Add meta boxes for ATBDP_POST_TYPE and ATBDP_SHORT_CODE_POST_TYPE
     * and Save the meta data
     */
    public function __construct() {
        if ( is_admin() ) {
            add_action('add_meta_boxes_'.ATBDP_POST_TYPE,	array($this, 'listing_info_meta'));
            // edit_post hooks is better than save_post hook for nice checkbox
            // http://wordpress.stackexchange.com/questions/228322/how-to-set-default-value-for-checkbox-in-wordpress
            add_action( 'edit_post', array($this, 'save_post_meta'), 10, 2);
            add_action('post_submitbox_misc_actions', array($this, 'post_submitbox_meta'));
        }
     }

    /**
     * Render Metaboxes for ATBDP_POST_TYPE
     * @param Object $post Current Post Object being displayed
     */
    public function listing_info_meta( $post ) {
        add_meta_box( '_listing_info',
        __( 'Listing Information', ATBDP_TEXTDOMAIN ),
        array($this, 'listing_info'),
        ATBDP_POST_TYPE,
        'normal' );

        add_meta_box( '_listing_gallery',
        __( 'Upload Image for the listing', ATBDP_TEXTDOMAIN ),
        array($this, 'listing_gallery'),
        ATBDP_POST_TYPE,
        'normal' );


    }

    /**
     * It displays Listing information meta on Add listing page on the backend
     * @param WP_Post $post
     */
    public function listing_info( $post ) {
        // get all the meta values from the db, prepare them for use and then send in in a single bar to the add listing view
        $listing_info['never_expire']           = get_post_meta($post->ID, '_never_expire', true);
        $listing_info['featured']               = get_post_meta($post->ID, '_featured', true);
        $listing_info['price']                  = get_post_meta($post->ID, '_price', true);
        $listing_info['listing_status']         = get_post_meta($post->ID, '_listing_status', true);
        $listing_info['tagline']                = get_post_meta($post->ID, '_tagline', true);
        $listing_info['excerpt']                = get_post_meta($post->ID, '_excerpt', true);
        $listing_info['address']                = get_post_meta($post->ID, '_address', true);
        $listing_info['phone']                  = get_post_meta($post->ID, '_phone', true);
        $listing_info['email']                  = get_post_meta($post->ID, '_email', true);
        $listing_info['website']                = get_post_meta($post->ID, '_website', true);
        $listing_info['social']                 = get_post_meta($post->ID, '_social', true);
        $listing_info['manual_lat']             = get_post_meta($post->ID, '_manual_lat', true);
        $listing_info['manual_lng']             = get_post_meta($post->ID, '_manual_lng', true);
        $listing_info['bdbh']                   = get_post_meta($post->ID, '_bdbh', true);
        $listing_info['listing_img']            = get_post_meta($post->ID, '_listing_img', true);
        $listing_info['hide_contact_info']      = get_post_meta($post->ID, '_hide_contact_info', true);
        $listing_info['expiry_date']           = get_post_meta($post->ID, '_expiry_date', true);


        // add nonce security token
        wp_nonce_field( 'listing_info_action', 'listing_info_nonce' );
        ATBDP()->load_template('add-listing', compact('listing_info') ); // load metabox view and pass data to it.
    }

    /**
     * It displays meta box for uploading image from the backend editor of ATBDP_POST_TYPE
     * @param WP_Post $post
     */
    public function listing_gallery($post )
    {
        $listing_img= get_post_meta($post->ID, '_listing_img', true);
        ATBDP()->load_template('media-upload', compact('listing_img') );
    }

    /**
     * It outputs expiration date and featured checkbox custom field on the submit box metabox.
     * @param WP_Post $post
     */
    public function post_submitbox_meta($post)
    {
        if(ATBDP_POST_TYPE !=$post->post_type) return; // vail if it is not our post type
        // show expiration date and featured listing.
        $expire_in_days = get_directorist_option('listing_expire_in_days');
        $f_active               = get_directorist_option('enable_featured_listing');
        $never_expire           = get_post_meta($post->ID, '_never_expire', true);
        $never_expire = !empty($never_expire) ? (int) $never_expire : (empty($expire_in_days) ? 1 : 0);

        $e_d            = get_post_meta($post->ID, '_expiry_date', true);
        $e_d            = !empty($e_d) ? $e_d : calc_listing_expiry_date();
        $expiry_date = atbdp_parse_mysql_date($e_d);

        $featured               = get_post_meta($post->ID, '_featured', true);
        $listing_status         = get_post_meta($post->ID, '_listing_status', true);
        // load the meta fields
        $data = compact('f_active', 'never_expire', 'expiry_date', 'featured', 'listing_status', 'default_expire_in_days');
        ATBDP()->load_template('meta-partials/expiration-featured-fields', array('data'=> $data));
    }

    /**
     * Save Meta Data of ATBDP_POST_TYPE
     * @param int       $post_id    Post ID of the current post being saved
     * @param object    $post       Current post object being saved
     */
    public function save_post_meta( $post_id, $post ) {
        if ( ! $this->passSecurity($post_id, $post) )  return; // vail if security check fails
        $metas = array();
        $expire_in_days = get_directorist_option('listing_expire_in_days');
        $p = $_POST; // save some character
        $exp_dt = $p['exp_date']; // get expiry date from the $_POST and then later sanitize it.
        // if the posted data has info about never_expire, then use it, otherwise, use the data from the settings.
        $metas['_never_expire']      = !empty($p['never_expire']) ? (int) $p['never_expire'] : (empty($expire_in_days) ? 1 : 0);
        $metas['_featured']          = !empty($p['featured'])? (int) $p['featured'] : 0;
        $metas['_price']             = !empty($p['price'])? (float) $p['price'] : 0;
        $metas['_listing_status']    = !empty($p['listing_status'])? sanitize_text_field($p['listing_status']) : 'post_status';
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
        $metas['_hide_contact_info']       = !empty($p['hide_contact_info'])? sanitize_text_field($p['hide_contact_info']) : 0;

        //$listing_info = (!empty($p['listing'])) ? aazztech_enc_serialize($p['listing']) : aazztech_enc_serialize(array());
        //prepare expiry date, if we receive complete expire date from the submitted post, then use it, else use the default data
        if (!is_empty_v($exp_dt) && !empty($exp_dt['aa'])){
            $exp_dt = array(
                'year'  => (int) $exp_dt['aa'],
                'month' => (int) $exp_dt['mm'],
                'day'   => (int) $exp_dt['jj'],
                'hour'  => (int) $exp_dt['hh'],
                'min'   => (int) $exp_dt['mn']
            );
            $exp_dt = get_date_in_mysql_format($exp_dt);
        }else{
            $exp_dt = calc_listing_expiry_date(); // get the expiry date in mysql date format using the default expiration date.
        }
        $metas['_expiry_date']              = $exp_dt;
        $metas = apply_filters('atbdp_listing_meta_admin_submission', $metas);
        // save the meta data to the database
        foreach ($metas as $meta_key => $meta_value) {
            update_post_meta($post_id, $meta_key, $meta_value); // array value will be serialize automatically by update post meta
        }
    }


    /**
     * Check if the the nonce, revision, auto_save are valid/true and the post type is ATBDP_POST_TYPE
     * @param int       $post_id    Post ID of the current post being saved
     * @param object    $post       Current post object being saved
     *
     * @return bool            Return true if all the above check passes the test.
     */
    private function passSecurity( $post_id, $post ) {
        if ( ATBDP_POST_TYPE == $post->post_type) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $nonce = !empty($_POST['listing_info_nonce']) ? $_POST['listing_info_nonce'] : '';
        $is_valid_nonce = wp_verify_nonce($nonce, 'listing_info_action');

        if ( $is_autosave || $is_revision || !$is_valid_nonce || ( !current_user_can( 'edit_'.ATBDP_POST_TYPE, $post_id )) ) return false;
        return true;
        }
        return false;
    }


    /**
     * It fetches all the information of the listing
     * @param int $id The id of the post whose meta we want to collect
     * @return array It returns the listing information of the given listing/post id.
     */
    public function get_listing_info($id=0)
    {
        global $post;
        //@todo;clean
        //$lf= get_post_meta($id, '_listing_info', true);
        //return (!empty($lf)) ? aazztech_enc_unserialize($lf) : array();
        $id = !empty($id) ? (int) $id : $post->ID;

        $listing_info['never_expire']           = get_post_meta($id, '_never_expire', true);
        $listing_info['featured']               = get_post_meta($id, '_featured', true);
        $listing_info['price']                  = get_post_meta($id, '_price', true);
        $listing_info['listing_status']         = get_post_meta($id, '_listing_status', true);
        $listing_info['tagline']                = get_post_meta($id, '_tagline', true);
        $listing_info['excerpt']                = get_post_meta($id, '_excerpt', true);
        $listing_info['address']                = get_post_meta($id, '_address', true);
        $listing_info['phone']                  = get_post_meta($id, '_phone', true);
        $listing_info['email']                  = get_post_meta($id, '_email', true);
        $listing_info['website']                = get_post_meta($id, '_website', true);
        $listing_info['social']                 = get_post_meta($id, '_social', true);
        $listing_info['manual_lat']             = get_post_meta($id, '_manual_lat', true);
        $listing_info['manual_lng']             = get_post_meta($id, '_manual_lng', true);
        $listing_info['bdbh']                   = get_post_meta($id, '_bdbh', true);
        $listing_info['bdbh_settings']          = get_post_meta($id, '_bdbh_settings', true);
        $listing_info['listing_img']            = get_post_meta($id, '_listing_img', true);
        $listing_info['hide_contact_info']      = get_post_meta($id, '_hide_contact_info', true);
        $listing_info['expiry_date']            = get_post_meta($id, '_expiry_date', true);

        return apply_filters('atbdp_get_listing_info', $listing_info);

    }


}

endif;