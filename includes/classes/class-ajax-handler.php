<?php
defined('ABSPATH') || die( 'Direct access is not allowed.' );

if(!class_exists('ATBDP_Ajax_Handler')):

/**
 * Class ATBDP_Ajax_Handler.
 * It handles all ajax requests from our plugin
 */
    /**
     * Class ATBDP_Ajax_Handler
     */
    class ATBDP_Ajax_Handler {

    /**
     * It registers our ajax functions to our ajax hooks
     */
    public function __construct()
    {
        add_action( 'wp_ajax_atbdp_social_info_handler', array($this, 'atbdp_social_info_handler'));
        add_action( 'wp_ajax_save_listing_review', array($this, 'save_listing_review'));
        add_action( 'wp_ajax_remove_listing_review', array($this, 'remove_listing_review'));
/*        add_action( 'wp_ajax_nopriv_save_listing_review', array($this, 'save_listing_review')); // don not allow unregistered user to submit review*/
        add_action( 'wp_ajax_load_more_review', array($this, 'load_more_review')); // load more reviews to the front end single page
        add_action( 'wp_ajax_nopriv_load_more_review', array($this, 'load_more_review'));// load more reviews for non logged in user too
        add_action('wp_ajax_remove_listing', array($this, 'remove_listing')); //delete a listing
        add_action('wp_ajax_update_user_profile', array($this, 'update_user_profile'));

        /*CHECKOUT RELATED STUFF*/
        add_action( 'wp_ajax_atbdp_format_total_amount', array('ATBDP_Checkout', 'ajax_atbdp_format_total_amount') );
        add_action( 'wp_ajax_nopriv_atbdp_format_total_amount', array('ATBDP_Checkout', 'ajax_atbdp_format_total_amount') );

        /*REPORT ABUSE*/
        add_action( 'wp_ajax_atbdp_public_report_abuse', array($this,'ajax_callback_report_abuse') );
        add_action( 'wp_ajax_nopriv_atbdp_public_report_abuse', array($this,'ajax_callback_report_abuse') );

        /*CONTACT FORM*/
        add_action( 'wp_ajax_atbdp_public_send_contact_email', array($this, 'ajax_callback_send_contact_email') );
        add_action( 'wp_ajax_nopriv_atbdp_public_send_contact_email', array($this, 'ajax_callback_send_contact_email') );

        /*
         * stuff for handling add to favourites
         */
        add_action( 'wp_ajax_atbdp_public_add_remove_favorites', array($this, 'atbdp_public_add_remove_favorites') );
        add_action( 'wp_ajax_nopriv_atbdp_public_add_remove_favorites', array($this, 'atbdp_public_add_remove_favorites') );

        //add_action( 'wp_ajax_atbdp-favourites-all-listing', array($this, 'atbdp_public_add_remove_favorites_all') );
        //add_action( 'wp_ajax_nopriv_atbdp-favourites-all-listing', array($this, 'atbdp_public_add_remove_favorites_all') );
    }


        /**
         * Add or Remove favourites.
         *
         * @since    4.0
         * @access   public
         */
        public function atbdp_public_add_remove_favorites_all() {

            $post_id = (int) $_POST['post_id'];
            var_dump($post_id);

            $favourites = (array) get_user_meta( get_current_user_id(), 'atbdp_favourites', true );

            if( in_array( $post_id, $favourites ) ) {
                if( ( $key = array_search( $post_id, $favourites ) ) !== false ) {
                    unset( $favourites[ $key ] );
                }
            } else {
                $favourites[] = $post_id;
            }

            $favourites = array_filter( $favourites );
            $favourites = array_values( $favourites );

            delete_user_meta( get_current_user_id(), 'atbdp_favourites' );
            update_user_meta( get_current_user_id(), 'atbdp_favourites', $favourites );

            the_atbdp_favourites_all_listing( $post_id );


        }


        /**
         * Add or Remove favourites.
         *
         * @since    4.0
         * @access   public
         */
        public function atbdp_public_add_remove_favorites() {

            $post_id = (int) $_POST['post_id'];

            $favourites = (array) get_user_meta( get_current_user_id(), 'atbdp_favourites', true );

            if( in_array( $post_id, $favourites ) ) {
                if( ( $key = array_search( $post_id, $favourites ) ) !== false ) {
                    unset( $favourites[ $key ] );
                }
            } else {
                $favourites[] = $post_id;
            }

            $favourites = array_filter( $favourites );
            $favourites = array_values( $favourites );

            delete_user_meta( get_current_user_id(), 'atbdp_favourites' );
            update_user_meta( get_current_user_id(), 'atbdp_favourites', $favourites );

            the_atbdp_favourites_link( $post_id );

            wp_die();

        }

    /**
     * It update user from from the front end dashboard using ajax
     */
    public function update_user_profile()
    {

        // process the data and the return a success

        if (valid_js_nonce()){
            // passed the security
            // update the user data and also its meta
            $success = ATBDP()->user->update_profile($_POST['user']); // update_profile() will handle sanitisation, so we can just the pass the data through it
            if ($success) {
                wp_send_json_success(array('message'=>__('Profile updated successfully', ATBDP_TEXTDOMAIN)));
            }else{
                wp_send_json_error(array('message'=>__('Ops! something went wrong. Try again.', ATBDP_TEXTDOMAIN)));
            };
        }
        wp_die();
    }

        /**
         *
         */
        public function remove_listing()
    {
        // delete the listing from here. first check the nonce and then delete and then send success.
        // save the data if nonce is good and data is valid
        if (valid_js_nonce() && !empty($_POST['listing_id'])) {
            $pid = (int) $_POST['listing_id'];
            // Check if the current user is the owner of the post
            $listing = get_post($pid);
            // delete the post if the current user is the owner of the listing
            if (get_current_user_id() == $listing->post_author || current_user_can('delete_at_biz_dirs')){

                $success = ATBDP()->listing->db->delete_listing_by_id($pid);
                if ($success){
                    echo 'success';
                }else{
                    echo 'error';
                }
            }
        }else{

            echo 'error';
            // show error message
        }

        wp_die();
    }

    public function remove_listing_review()
    {
        // save the data if nonce is good and data is valid
        if (valid_js_nonce()){
            if ( !empty($_POST['review_id'])){
                $success = ATBDP()->review->db->delete(absint($_POST['review_id']));
                if ($success){
                    echo 'success';
                }else{
                    echo 'error';
                }
            }
        }else{

            echo 'error';
            // show error message
        }


        wp_die();
    }

    public function load_more_review()
    {
        // save the data if nonce is good and data is valid
        if (valid_js_nonce()){
            if (!empty($_POST['offset']) && !empty($_POST['post_id'])){
                $reviews = ATBDP()->review->db->get_reviews_by('post_id', absint($_POST['post_id']), absint($_POST['offset']), 3,'date_created', 'DESC', ARRAY_A); // get only 3
                if (!is_wp_error($reviews)){
                    echo json_encode($reviews);
                }else{
                    echo 'error';
                }
                wp_die();

            }

            die();
        }else{

            echo 'error';
            // show error message
        }


        wp_die();
    }


    public function save_listing_review()
    {
        // save the data if nonce is good and data is valid
        if (valid_js_nonce() && $this->validate_listing_review()){
            /*
             * $args = array(
					'post_id'          => $post_id,
            		'name'           => $user ? $user->display_name : '',
					'email'          => $email,
					'by_user_id'        => $user ? $user->ID : 0,
				);
            */

            $u_name = !empty($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
            $u_email = !empty($_POST['email']) ? sanitize_email($_POST['email']) : '';
            $user= wp_get_current_user();
            $data = array(
                'post_id'          => absint($_POST['post_id']),
                'name'           => !empty( $user->display_name ) ? $user->display_name : $u_name,
                'email'          => !empty( $user->user_email ) ? $user->user_email : $u_email,
                'content'        => sanitize_textarea_field($_POST['content']),
                'rating'        => floatval($_POST['rating']),
                'by_guest'        => !empty( $user->ID )? 0 : 1,
                'by_user_id'        => !empty( $user->ID )? $user->ID : 0,
            );
            if ($id = ATBDP()->review->db->add($data)){
                wp_send_json_success(array('id'=>$id));
            }
        }else{
            echo 'Errors: make sure you wrote something about your review.';
            // show error message
        }


        die();
    }


    /**
     * It checks if the user has filled up proper data for adding a review
     * @return bool It returns true if the review data is perfect and false otherwise
     */
    public function validate_listing_review()
    {
        if (!empty($_POST['rating']) && !empty($_POST['content']) && !empty($_POST['post_id']) ){
            return true;
        }
        return false;
    }

    /**
     *  Add new Social Item in the member page in response to Ajax request
     */
    public function atbdp_social_info_handler() {
            $id = (!empty($_POST['id'])) ? absint($_POST['id']) : 0;
            ATBDP()->load_template('ajax/social', array( 'id' => $id, ));
            die();

    }

    public function atbdp_email_admin_report_abuse() {

        // sanitize form values
        $post_id = (int) $_POST["post_id"];
        $message = esc_textarea( $_POST["message"] );

        // vars
        $user          = wp_get_current_user();
        $site_name     = get_bloginfo( 'name' );
        $site_url      = get_bloginfo( 'url' );
        $listing_title = get_the_title( $post_id );
        $listing_url   = get_permalink( $post_id );

        $placeholders = array(
            '{site_name}'       => $site_name,
            '{site_link}'       => sprintf( '<a href="%s">%s</a>', $site_url, $site_name ),
            '{site_url}'        => sprintf( '<a href="%s">%s</a>', $site_url, $site_url ),
            '{listing_title}'   => $listing_title,
            '{listing_link}'    => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_title ),
            '{listing_url}'     => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_url ),
            '{sender_name}'     => $user->display_name,
            '{sender_email}'    => $user->user_email,
            '{message}'         => $message
        );
        $send_email = get_directorist_option('admin_email_lists');

        $to = !empty($send_email) ? $send_email : get_bloginfo('admin_email');

        $subject = __( '[{site_name}] Report Abuse via "{listing_title}"', ATBDP_TEXTDOMAIN );
        $subject = strtr( $subject, $placeholders );

        $message =  __( "Dear Administrator,<br /><br />This is an email abuse report for a listing at {listing_url}.<br /><br />Name: {sender_name}<br />Email: {sender_email}<br />Message: {message}", ATBDP_TEXTDOMAIN );
        $message = strtr( $message, $placeholders );

        $headers  = "From: {$user->display_name} <{$user->user_email}>\r\n";
        $headers .= "Reply-To: {$user->user_email}\r\n";

        // return true or false, based on the result
        return ATBDP()->email->send_mail( $to, $subject, $message, $headers ) ? true : false;

    }

    public function ajax_callback_report_abuse() {


        $data = array( 'error' => 0 );



            if( $this->atbdp_email_admin_report_abuse() ) {

                $data['message'] = __( 'Your message sent successfully.', ATBDP_TEXTDOMAIN );

            } else {

                $data['error']   = 1;
                $data['message'] = __( 'Sorry! Please try again.', ATBDP_TEXTDOMAIN );

            }



        echo wp_json_encode( $data );
        wp_die();

    }

    /**
     * Send contact message to the listing owner.
     *
     * @since    4.0.0
     *
     * @return   string    $result    Message based on the result.
     */
    function atbdp_email_listing_owner_listing_contact() {

        if(! in_array( 'listing_contact_form', get_directorist_option('notify_user', array()) ) ) return false;
        // sanitize form values
        $post_id = (int) $_POST["post_id"];
        $name    = sanitize_text_field( $_POST["name"] );
        $email   = sanitize_email( $_POST["email"] );
        $message = stripslashes( esc_textarea( $_POST["message"] ) );

        // vars
        $post_author_id         = get_post_field( 'post_author', $post_id );
        $user                   = get_userdata( $post_author_id );
        $site_name              = get_bloginfo( 'name' );
        $site_url               = get_bloginfo( 'url' );
        $site_email		        = get_bloginfo( 'admin_email' );
        $listing_title          = get_the_title( $post_id );
        $listing_url            = get_permalink( $post_id );
        $date_format            = get_option( 'date_format' );
        $time_format            = get_option( 'time_format' );
        $current_time           = current_time( 'timestamp' );
        $contact_email_subject  = get_directorist_option('email_sub_listing_contact_email');
        $contact_email_body     = get_directorist_option('email_tmpl_listing_contact_email');

        $placeholders = array(
            '==NAME=='            => $user->display_name,
            '==USERNAME=='        => $user->user_login,
            '==SITE_NAME=='       => $site_name,
            '==SITE_LINK=='       => sprintf( '<a href="%s">%s</a>', $site_url, $site_name ),
            '==SITE_URL=='        => sprintf( '<a href="%s">%s</a>', $site_url, $site_url ),
            '==LISTING_TITLE=='   => $listing_title,
            '==LISTING_LINK=='    => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_title ),
            '==LISTING_URL=='     => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_url ),
            '==SENDER_NAME=='     => $name,
            '==SENDER_EMAIL=='    => $email,
            '==MESSAGE=='         => $message,
            '==TODAY=='           => date_i18n( $date_format, $current_time ),
            '==NOW=='             => date_i18n( $date_format . ' ' . $time_format, $current_time )
        );

        $to      = $user->user_email;

        $subject = strtr( $contact_email_subject, $placeholders );

        $message = strtr( $contact_email_body, $placeholders );
        $message = nl2br( $message );

        $headers  = "From: {$name} <{$site_email}>\r\n";
        $headers .= "Reply-To: {$email}\r\n";

        // return true or false, based on the result
        return ATBDP()->email->send_mail( $to, $subject, $message, $headers ) ? true : false;

    }

    /**
     * Send contact message to the admin.
     *
     * @since    4.0
     */
    function atbdp_email_admin_listing_contact() {

        if (get_directorist_option('disable_email_notification')) return false; //vail if email notification is off

        if( ! in_array( 'listing_contact_form', get_directorist_option('notify_admin', array()) ) ) return false; // vail if order created notification to admin off

            // sanitize form values
            $post_id = (int) $_POST["post_id"];
            $name    = sanitize_text_field( $_POST["name"] );
            $email   = sanitize_email( $_POST["email"] );
            $message = esc_textarea( $_POST["message"] );

            // vars
            $site_name      = get_bloginfo( 'name' );
            $site_url       = get_bloginfo( 'url' );
            $listing_title  = get_the_title( $post_id );
            $listing_url    = get_permalink( $post_id );
            $date_format    = get_option( 'date_format' );
            $time_format    = get_option( 'time_format' );
            $current_time   = current_time( 'timestamp' );

            $placeholders = array(
                '{site_name}'     => $site_name,
                '{site_link}'     => sprintf( '<a href="%s">%s</a>', $site_url, $site_name ),
                '{site_url}'      => sprintf( '<a href="%s">%s</a>', $site_url, $site_url ),
                '{listing_title}' => $listing_title,
                '{listing_link}'  => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_title ),
                '{listing_url}'   => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_url ),
                '{sender_name}'   => $name,
                '{sender_email}'  => $email,
                '{message}'       => $message,
                '{today}'         => date_i18n( $date_format, $current_time ),
                '{now}'           => date_i18n( $date_format . ' ' . $time_format, $current_time )
            );
            $send_emails = ATBDP()->email->get_admin_email_list();
            $to = !empty($send_emails) ? $send_emails : get_bloginfo('admin_email');

            $subject = __( '[{site_name}] Contact via "{listing_title}"', ATBDP_TEXTDOMAIN );
            $subject = strtr( $subject, $placeholders );

            $message =  __( "Dear Administrator,<br /><br />A listing on your website {site_name} received a message.<br /><br />Listing URL: {listing_url}<br /><br />Name: {sender_name}<br />Email: {sender_email}<br />Message: {message}<br />Time: {now}<br /><br />This is just a copy of the original email and was already sent to the listing owner. You don't have to reply this unless necessary.", ATBDP_TEXTDOMAIN );
            $message = strtr( $message, $placeholders );

            $headers  = "From: {$name} <{$email}>\r\n";
            $headers .= "Reply-To: {$email}\r\n";

            return ATBDP()->email->send_mail( $to, $subject, $message, $headers ) ? true : false;



    }

    /**
     * Send contact email.
     *
     * @since    4.0
     * @access   public
     */
    public function ajax_callback_send_contact_email() {

        $data = array( 'error' => 0 );


        if( $this->atbdp_email_listing_owner_listing_contact() ) {

            // Send a copy to admin( only if applicable ).
            $this->atbdp_email_admin_listing_contact();

            $data['message'] = __( 'Your message sent successfully.', ATBDP_TEXTDOMAIN );

        } else {

            $data['error']   = 1;
            $data['message'] = __( 'Sorry! Please try again.', ATBDP_TEXTDOMAIN );

        }


        echo wp_json_encode( $data );
        wp_die();

    }

}


endif;