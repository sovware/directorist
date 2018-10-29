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


    }


    /**
     * It update user from from the front end dashboard using ajax
     */
    public function update_user_profile()
    {
        /*
         * Sample data
         * array (size=3)
          'user' =>
            array (size=10)
              'full_name' =>  'Kamal Ahmed' ,
              'first_name' =>  'Kamal' ,
              'last_name' =>  'Ahmed' ,
              'req_email' =>  'kamalacca@gmail.com' ,
              'phone' =>  '8937-4958-5905' ,
              'website' =>  '' ,
              'address' =>  '' ,
              'current_pass' =>  '' ,
              'new_pass' =>  '' ,
              'confirm_pass' =>  '' ,
          'action' =>  'update_user_profile' ,
          'atbdp_nonce_js' =>  'b49cc5b8dd' ,
        */

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

}


endif;