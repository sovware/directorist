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
        $lf= get_post_meta($post->ID, '_listing_info', true);
        $listing_info = (!empty($lf))? aazztech_enc_unserialize($lf) : array();
        $listing_info['price'] = get_post_meta($post->ID, '_price', true);
        wp_nonce_field( 'listing_info_action', 'listing_info_nonce' );
        ATBDP()->load_template('add-listing', compact('listing_info') );
    }

    /**
     * It displays meta box for uploading image from the backend editor of ATBDP_POST_TYPE
     * @param WP_Post $post
     */
    public function listing_gallery($post )
    {
        $lf= get_post_meta($post->ID, '_listing_info', true);
        $listing_info= (!empty($lf)) ? aazztech_enc_unserialize($lf) : array();
        $attachment_ids= (!empty($listing_info['attachment_id'])) ? $listing_info['attachment_id'] : array();
        ATBDP()->load_template('media-upload', compact('attachment_ids') );
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
        if ( ! $this->passSecurity($post_id, $post) )  return;
        $expire_in_days = get_directorist_option('listing_expire_in_days');
        $p = $_POST; // save some character
        $exp_dt = $p['exp_date']; // get expiry date
        // if the posted data has info about never_expire, then use it, otherwise, use the data from the settings.
        $never_expire = !empty($p['never_expire']) ? (int) $p['never_expire'] : (empty($expire_in_days) ? 1 : 0);
        $featured = !empty($p['featured'])? (int) $p['featured'] : 0;
        $price = !empty($p['price'])? (int) $p['price'] : 0;
        $listing_status = !empty($p['listing_status'])? sanitize_text_field($p['listing_status']) : 'post_status';
        $listing_info = (!empty($p['listing'])) ? aazztech_enc_serialize($p['listing']) : aazztech_enc_serialize(array());
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

        // save the meta data to the database
        update_post_meta( $post_id, '_listing_info', $listing_info );
        update_post_meta( $post_id, '_expiry_date', $exp_dt );
        update_post_meta( $post_id, '_never_expire', $never_expire );
        update_post_meta( $post_id, '_featured', $featured );
        update_post_meta( $post_id, '_listing_status', $listing_status );
        update_post_meta( $post_id, '_price', $price );


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
    public function get_listing_info($id)
    {
        $lf= get_post_meta($id, '_listing_info', true);
        return (!empty($lf)) ? aazztech_enc_unserialize($lf) : array();

    }


}

endif;