<?php
defined('ABSPATH') || die('Direct access is not allowed.');

if (!class_exists('ATBDP_Ajax_Handler')):

    /**
     * Class ATBDP_Ajax_Handler.
     * It handles all ajax requests from our plugin
     */
    /**
     * Class ATBDP_Ajax_Handler
     */
    class ATBDP_Ajax_Handler
    {

        /**
         * It registers our ajax functions to our ajax hooks
         */
        public function __construct()
        {
            add_action('wp_ajax_atbdp_social_info_handler', array($this, 'atbdp_social_info_handler'));
            add_action('wp_ajax_nopriv_atbdp_social_info_handler', array($this, 'atbdp_social_info_handler'));
            add_action('wp_ajax_remove_listing_review', array($this, 'remove_listing_review'));
            add_action('wp_ajax_save_listing_review', array($this, 'save_listing_review'));
            add_action('wp_ajax_nopriv_save_listing_review', array($this, 'save_listing_review')); // don not allow unregistered user to submit review
            // paginate review
            add_action('wp_ajax_atbdp_review_pagination', array($this, 'atbdp_review_pagination_output'));
            add_action('wp_ajax_nopriv_atbdp_review_pagination', array($this, 'atbdp_review_pagination_output'));

            add_action('wp_ajax_remove_listing', array($this, 'remove_listing')); //delete a listing
            add_action('wp_ajax_update_user_profile', array($this, 'update_user_profile'));

            /*CHECKOUT RELATED STUFF*/
            add_action('wp_ajax_atbdp_format_total_amount', array('ATBDP_Checkout', 'ajax_atbdp_format_total_amount'));
            add_action('wp_ajax_nopriv_atbdp_format_total_amount', array('ATBDP_Checkout', 'ajax_atbdp_format_total_amount'));

            /*REPORT ABUSE*/
            add_action('wp_ajax_atbdp_public_report_abuse', array($this, 'ajax_callback_report_abuse'));
            add_action('wp_ajax_nopriv_atbdp_public_report_abuse', array($this, 'ajax_callback_report_abuse'));

            /*CONTACT FORM*/
            add_action('wp_ajax_atbdp_public_send_contact_email', array($this, 'ajax_callback_send_contact_email'));
            add_action('wp_ajax_nopriv_atbdp_public_send_contact_email', array($this, 'ajax_callback_send_contact_email'));

            /*
             * stuff for handling add to favourites
             */
            add_action('wp_ajax_atbdp_public_add_remove_favorites', array($this, 'atbdp_public_add_remove_favorites'));
            add_action('wp_ajax_nopriv_atbdp_public_add_remove_favorites', array($this, 'atbdp_public_add_remove_favorites'));

            add_action('wp_ajax_atbdp_submit-uninstall-reason', array($this, 'atbdp_deactivate_reason'));
            add_action('wp_ajax_nopriv_atbdp_submit-uninstall-reason', array($this, 'atbdp_deactivate_reason'));

            // location & category child term
            add_action('wp_ajax_bdas_public_dropdown_terms', array($this, 'bdas_dropdown_terms'));
            add_action('wp_ajax_nopriv_bdas_public_dropdown_terms', array($this, 'bdas_dropdown_terms'));
            //custom field search
            add_action('wp_ajax_atbdp_custom_fields_search', array($this, 'custom_field_search'), 10, 1);
            add_action('wp_ajax_nopriv_atbdp_custom_fields_search', array($this, 'custom_field_search'), 10, 1);
            add_action('wp_ajax_atbdp-favourites-all-listing', array($this, 'atbdp_public_add_remove_favorites_all'));
            add_action('wp_ajax_nopriv_atbdp-favourites-all-listing', array($this, 'atbdp_public_add_remove_favorites_all'));
            add_action('wp_ajax_atbdp_post_attachment_upload', array($this, 'atbdp_post_attachment_upload'));
            add_action('wp_ajax_nopriv_atbdp_post_attachment_upload', array($this, 'atbdp_post_attachment_upload'));

            //login
            add_action('wp_ajax_nopriv_ajaxlogin', array($this, 'atbdp_ajax_login'));
        }

        public function atbdp_ajax_login()
        {
            // First check the nonce, if it fails the function will break
            check_ajax_referer('ajax-login-nonce', 'security');
            // Nonce is checked, get the POST data and sign user on
            $username = $_POST['username'];
            $user_password = $_POST['password'];
            $keep_signed_in = !empty($_POST['rememberme']) ? true : false;
            $user = wp_authenticate($username, $user_password);
            if (is_wp_error($user)) {
                if (empty($username && $user_password)) {
                    echo json_encode(array('loggedin' => false, 'message' => __('Fields are required.', 'directorist')));
                } else {
                    echo json_encode(array('loggedin' => false, 'message' => __('Wrong username or password.', 'directorist')));
                }
            } else {
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID, $keep_signed_in);
                echo json_encode(array('loggedin' => true, 'message' => __('Login successful, redirecting...', 'directorist')));
            }
            exit();
        }

        public function atbdp_post_attachment_upload()
        {
            // security
            check_ajax_referer('atbdp_attachment_upload', '_ajax_nonce');
            $field_id = isset($_POST["imgid"]) ? esc_attr($_POST["imgid"]) : '';
            $post_id = isset($_POST["post_id"]) ? absint($_POST["post_id"]) : '';
            // set directory temp upload dir
            add_filter('upload_dir', array(__CLASS__, 'temp_upload_dir'));

            $fixed_file = $_FILES[$field_id . 'async-upload'];

            // handle file upload
            $status = wp_handle_upload($fixed_file, array(
                'test_form' => true,
                'action' => 'atbdp_post_attachment_upload'
            ));
            // unset GD temp upload dir
            remove_filter('upload_dir', array(__CLASS__, 'temp_upload_dir'));

            if (!isset($status['url']) && isset($status['error'])) {
                print_r($status);
            }

            // send the uploaded file url in response
            if (isset($status['url']) && $post_id) {

                // insert to DB
                $file_info = update_post_meta($post_id, $field_id, $status['url']);

                if (is_wp_error($file_info)) {
                    //atbdp_error_log( $file_info->get_error_message(), 'post_attachment_upload', __FILE__, __LINE__ );
                } else {
                    $wp_upload_dir = wp_upload_dir();
                    echo $status['url'];
                }

            } elseif (isset($status['url'])) {
                echo $status['url'];
            } else {
                echo 'x';
            }

            // if file exists it should have been moved if uploaded correctly so now we can remove it
            /*if(!empty($status['file']) && $post_id){
                wp_delete_file( $status['file'] );
            }*/
            // atbdp_Media::post_attachment_upload();
            //ATBDP()->atbdp_Media->post_attachment_upload();
            wp_die();
        }

        public static function temp_upload_dir($upload)
        {
            $upload['subdir'] = "/atbdp_temp";
            $upload['path'] = $upload['basedir'] . $upload['subdir'];
            $upload['url'] = $upload['baseurl'] . $upload['subdir'];

            return $upload;
        }

        /**
         * Add or Remove favourites.
         *
         * @since    4.0
         * @access   public
         */
        public function atbdp_public_add_remove_favorites_all()
        {

            $user_id = get_current_user_id();
            $post_id = (int)$_POST['post_id'];

            if (!$user_id) {
                $data = "login_required";
                echo esc_attr($data);
                wp_die();
            }

            $favourites = (array)get_user_meta($user_id, 'atbdp_favourites', true);

            if (in_array($post_id, $favourites)) {
                if (($key = array_search($post_id, $favourites)) !== false) {
                    unset($favourites[$key]);
                }
            } else {
                $favourites[] = $post_id;
            }

            $favourites = array_filter($favourites);
            $favourites = array_values($favourites);

            delete_user_meta($user_id, 'atbdp_favourites');
            update_user_meta($user_id, 'atbdp_favourites', $favourites);

            $favourites = (array)get_user_meta(get_current_user_id(), 'atbdp_favourites', true);
            if (in_array($post_id, $favourites)) {
                $data = $post_id;
            } else {
                $data = false;
            }
            echo wp_json_encode($data);
            wp_die();
        }

        /**
         * Add or Remove favourites.
         *
         * @since    4.0
         * @access   public
         */
        public function atbdp_public_add_remove_favorites()
        {

            $post_id = (int)$_POST['post_id'];

            $favourites = (array)get_user_meta(get_current_user_id(), 'atbdp_favourites', true);

            if (in_array($post_id, $favourites)) {
                if (($key = array_search($post_id, $favourites)) !== false) {
                    unset($favourites[$key]);
                }
            } else {
                $favourites[] = $post_id;
            }

            $favourites = array_filter($favourites);
            $favourites = array_values($favourites);

            delete_user_meta(get_current_user_id(), 'atbdp_favourites');
            update_user_meta(get_current_user_id(), 'atbdp_favourites', $favourites);

            echo the_atbdp_favourites_link($post_id);

            wp_die();

        }

        /**
         * It update user from from the front end dashboard using ajax
         */
        public function update_user_profile()
        {
            // process the data and the return a success
            if (valid_js_nonce()) {
                // passed the security
                // update the user data and also its meta
                $success = ATBDP()->user->update_profile($_POST['user']); // update_profile() will handle sanitisation, so we can just the pass the data through it
                if ($success) {
                    wp_send_json_success(array('message' => __('Profile updated successfully', 'directorist')));
                } else {
                    wp_send_json_error(array('message' => __('Ops! something went wrong. Try again.', 'directorist')));
                };
            }
            wp_die();
        }

        public function remove_listing()
        {
            // delete the listing from here. first check the nonce and then delete and then send success.
            // save the data if nonce is good and data is valid
            if (valid_js_nonce() && !empty($_POST['listing_id'])) {
                $pid = (int)$_POST['listing_id'];
                // Check if the current user is the owner of the post
                $listing = get_post($pid);
                // delete the post if the current user is the owner of the listing
                if (get_current_user_id() == $listing->post_author || current_user_can('delete_at_biz_dirs')) {
                    $success = ATBDP()->listing->db->delete_listing_by_id($pid);
                    if ($success) {
                        echo 'success';
                    } else {
                        echo 'error';
                    }
                }
            } else {
                echo 'error';
                // show error message
            }
            wp_die();
        }

        public function remove_listing_review()
        {
            // save the data if nonce is good and data is valid
            if (valid_js_nonce()) {
                if (!empty($_POST['review_id'])) {
                    $success = ATBDP()->review->db->delete(absint($_POST['review_id']));
                    if ($success) {
                        echo 'success';
                    } else {
                        echo 'error';
                    }
                }
            } else {
                echo 'error';
                // show error message
            }
            wp_die();
        }


        public function atbdp_review_pagination_output()
        {
            $msg = '';
            if (isset($_POST['page'])) {
                // Sanitize the received page
                $page = sanitize_text_field($_POST['page']);
                $listing_id = sanitize_text_field($_POST['listing_id']);
                $cur_page = $page;
                $page -= 1;
                // Set the number of results to display
                $per_page = 1;
                $previous_btn = true;
                $next_btn = true;
                $first_btn = true;
                $last_btn = true;
                $start = $page * $per_page;
                // Query the necessary reviews
                $all_blog_posts = ATBDP()->review->db->get_reviews_by('post_id', (int)$listing_id, $start, $per_page);
                // At the same time, count the number of queried review
                $count = ATBDP()->review->db->count(array('post_id' => $listing_id));
                // Loop into all the posts
                foreach ($all_blog_posts as $key => $post):
                    // Set the desired output into a variable
                    $msg .= '<div class="single_review">
                                <div class="review_top">
                                    <div class="reviewer">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                        <p>'.$post->name.'</p>
                                    </div>
                                    <p class="review_time">'.$post->date_created.'</p>
                                    <div class="br-theme-css-stars-static">'.$post->rating.'</div>
                                </div>
                                <div class="review_content"><p>'.$post->content.'</p></div>
                            </div>';
                endforeach;
                // Optional, wrap the output into a container
                $msg = "<div class='atbdp-universal-content'>" . $msg . "</div><br class = 'clear' />";

                // This is where the magic happens
                $no_of_paginations = ceil($count / $per_page);
                if ($cur_page >= 7) {
                    $start_loop = $cur_page - 3;
                    if ($no_of_paginations > $cur_page + 3)
                        $end_loop = $cur_page + 3;
                    else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
                        $start_loop = $no_of_paginations - 6;
                        $end_loop = $no_of_paginations;
                    } else {
                        $end_loop = $no_of_paginations;
                    }
                } else {
                    $start_loop = 1;
                    if ($no_of_paginations > 7)
                        $end_loop = 7;
                    else
                        $end_loop = $no_of_paginations;
                }

                $pag_container = '';
                // Pagination Buttons logic
                $pag_container .= "
        <div class='atbdp-universal-pagination'>
            <ul>";

                if ($first_btn && $cur_page > 1) {
                    $pag_container .= "<li data-page='1' class='active'>First</li>";
                } else if ($first_btn) {
                    $pag_container .= "<li data-page='1' class='inactive'>First</li>";
                }

                if ($previous_btn && $cur_page > 1) {
                    $pre = $cur_page - 1;
                    $pag_container .= "<li data-page='$pre' class='active'>Previous</li>";
                } else if ($previous_btn) {
                    $pag_container .= "<li class='inactive'>Previous</li>";
                }
                for ($i = $start_loop; $i <= $end_loop; $i++) {

                    if ($cur_page == $i)
                        $pag_container .= "<li data-page='$i' class = 'selected' >{$i}</li>";
                    else
                        $pag_container .= "<li data-page='$i' class='active'>{$i}</li>";
                }

                if ($next_btn && $cur_page < $no_of_paginations) {
                    $nex = $cur_page + 1;
                    $pag_container .= "<li data-page='$nex' class='active'>Next</li>";
                } else if ($next_btn) {
                    $pag_container .= "<li class='inactive'>Next</li>";
                }

                if ($last_btn && $cur_page < $no_of_paginations) {
                    $pag_container .= "<li data-page='$no_of_paginations' class='active'>Last</li>";
                } else if ($last_btn) {
                    $pag_container .= "<li data-page='$no_of_paginations' class='inactive'>Last</li>";
                }

                $pag_container = $pag_container . "
            </ul>
        </div>";
                // We echo the final output
                echo
                    '<div class = "atbdp-pagination-content">' . $msg . '</div>' .
                    '<div class = "atbdp-pagination-nav">' . $pag_container . '</div>';

            }
            // Always exit to avoid further execution
            exit();
        }


        /**
         * @since 6.3.0
         */

        public function insert_guest_user()
        {

        }

        public function save_listing_review()
        {
            $guest_review = get_directorist_option('guest_review', 0);
            $guest_email = isset($_POST['guest_user_email']) ? esc_attr($_POST['guest_user_email']) : '';

            if ($guest_review && $guest_email) {

                $string = $guest_email;
                $explode = explode("@", $string);
                array_pop($explode);
                $userName = join('@', $explode);
                //check if username already exist
                if (username_exists($userName)) {
                    $random = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 1, 5);
                    $userName = $userName . $random;
                }

                // Check if user exist by email
                if (email_exists($guest_email)) {
                    $data = array(
                        'error' => __('Email already exists!', 'directorist')
                    );
                    echo wp_json_encode($data);
                    die();
                } else {
                    // lets register the user
                    $reg_errors = new WP_Error;
                    if (empty($reg_errors->get_error_messages())) {
                        $password = wp_generate_password(12, false);
                        $userdata = array(
                            'user_login' => $userName,
                            'user_email' => $guest_email,
                            'user_pass' => $password,
                        );
                        $user_id = wp_insert_user($userdata); // return inserted user id or a WP_Error
                        wp_set_current_user($user_id, $guest_email);
                        wp_set_auth_cookie($user_id);
                        do_action('atbdp_user_registration_completed', $user_id);
                        update_user_meta($user_id, '_atbdp_generated_password', $password);
                        // user has been created successfully, now work on activation process
                        wp_new_user_notification($user_id, null, 'both');
                    }
                }
            }
            // save the data if nonce is good and data is valid

            if ($this->validate_listing_review()) {
                $u_name = !empty($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
                $u_email = !empty($_POST['email']) ? sanitize_email($_POST['email']) : '';
                $user = wp_get_current_user();
                $data = array(
                    'post_id' => absint($_POST['post_id']),
                    'name' => !empty($user->display_name) ? $user->display_name : $u_name,
                    'email' => !empty($user->user_email) ? $user->user_email : $u_email,
                    'content' => sanitize_textarea_field($_POST['content']),
                    'rating' => floatval($_POST['rating']),
                    'by_guest' => !empty($user->ID) ? 0 : 1,
                    'by_user_id' => !empty($user->ID) ? $user->ID : 0,
                );
                $approve_immediately = get_directorist_option('approve_immediately', 1);
                $review_duplicate = !empty($_POST['review_duplicate']) ? sanitize_text_field($_POST['review_duplicate']) : '';
                if (empty($approve_immediately)) {
                    if (empty($review_duplicate)) {
                        $this->atbdp_send_email_review_to_admin();
                        send_review_for_approval($data);
                    }
                } elseif ($id = ATBDP()->review->db->add($data)) {
                    //$this->atbdp_send_email_review_to_user();
                    $this->atbdp_send_email_review_to_admin();
                    wp_send_json_success(array('id' => $id));
                }
            } else {
                echo 'Errors: make sure you wrote something about your review.';
                // show error message
            }


            die();
        }

        /*
         * send email to listing's owner for review
         * */
        public function atbdp_send_email_review_to_user()
        {

            if (!in_array('listing_review', get_directorist_option('notify_user', array()))) return false;
            // sanitize form values
            $post_id = (int)$_POST["post_id"];
            $message = esc_textarea($_POST["content"]);

            // vars
            $user = wp_get_current_user();
            $site_name = get_bloginfo('name');
            $site_url = get_bloginfo('url');
            $listing_title = get_the_title($post_id);
            $listing_url = get_permalink($post_id);

            $placeholders = array(
                '{site_name}' => $site_name,
                '{site_link}' => sprintf('<a href="%s">%s</a>', $site_url, $site_name),
                '{site_url}' => sprintf('<a href="%s">%s</a>', $site_url, $site_url),
                '{listing_title}' => $listing_title,
                '{listing_link}' => sprintf('<a href="%s">%s</a>', $listing_url, $listing_title),
                '{listing_url}' => sprintf('<a href="%s">%s</a>', $listing_url, $listing_url),
                '{sender_name}' => $user->display_name,
                '{sender_email}' => $user->user_email,
                '{message}' => $message
            );
            $send_email = get_directorist_option('admin_email_lists');

            $to = $user->user_email;

            $subject = __('[{site_name}] New review at "{listing_title}"', 'directorist');
            $subject = strtr($subject, $placeholders);

            $message = __("Dear User,<br /><br />A new review at {listing_url}.<br /><br />", 'directorist');
            $message = strtr($message, $placeholders);

            $headers = "From: {$user->display_name} <{$user->user_email}>\r\n";
            $headers .= "Reply-To: {$user->user_email}\r\n";

            // return true or false, based on the result
            return ATBDP()->email->send_mail($to, $subject, $message, $headers) ? true : false;

        }

        /*
         * send email to admin for review
         * */
        public function atbdp_send_email_review_to_admin()
        {

            if (get_directorist_option('disable_email_notification')) return false; //vail if email notification is off

            if (!in_array('listing_review', get_directorist_option('notify_admin', array()))) return false; // vail if order created notification to admin off
            // sanitize form values
            $post_id = (int)$_POST["post_id"];
            $message = esc_textarea($_POST["content"]);

            // vars
            $user = wp_get_current_user();
            $site_name = get_bloginfo('name');
            $site_url = get_bloginfo('url');
            $listing_title = get_the_title($post_id);
            $listing_url = get_permalink($post_id);

            $placeholders = array(
                '{site_name}' => $site_name,
                '{site_link}' => sprintf('<a href="%s">%s</a>', $site_url, $site_name),
                '{site_url}' => sprintf('<a href="%s">%s</a>', $site_url, $site_url),
                '{listing_title}' => $listing_title,
                '{listing_link}' => sprintf('<a href="%s">%s</a>', $listing_url, $listing_title),
                '{listing_url}' => sprintf('<a href="%s">%s</a>', $listing_url, $listing_url),
                '{sender_name}' => $user->display_name,
                '{sender_email}' => $user->user_email,
                '{message}' => $message
            );
            $send_email = get_directorist_option('admin_email_lists');

            $to = !empty($send_email) ? $send_email : get_bloginfo('admin_email');

            $subject = __('[{site_name}] New review at "{listing_title}"', 'directorist');
            $subject = strtr($subject, $placeholders);

            $message = __("Dear Administrator,<br /><br />A new review at {listing_url}.<br /><br />Name: {sender_name}<br />Email: {sender_email}", 'directorist');
            $message = strtr($message, $placeholders);

            $headers = "From: {$user->display_name} <{$user->user_email}>\r\n";
            $headers .= "Reply-To: {$user->user_email}\r\n";

            // return true or false, based on the result
            return ATBDP()->email->send_mail($to, $subject, $message, $headers) ? true : false;

        }


        /**
         * It checks if the user has filled up proper data for adding a review
         * @return bool It returns true if the review data is perfect and false otherwise
         */
        public function validate_listing_review()
        {
            if (!empty($_POST['rating']) && !empty($_POST['content']) && !empty($_POST['post_id'])) {
                return true;
            }
            return false;
        }

        /**
         *  Add new Social Item in the member page in response to Ajax request
         */
        public function atbdp_social_info_handler()
        {
            $id = (!empty($_POST['id'])) ? absint($_POST['id']) : 0;
            ATBDP()->load_template('ajax/social', array('id' => $id,));
            die();

        }

        public function atbdp_email_admin_report_abuse()
        {

            // sanitize form values
            $post_id = (int)$_POST["post_id"];
            $message = esc_textarea($_POST["message"]);

            // vars
            $user = wp_get_current_user();
            $site_name = get_bloginfo('name');
            $site_url = get_bloginfo('url');
            $listing_title = get_the_title($post_id);
            $listing_url = get_permalink($post_id);

            $placeholders = array(
                '{site_name}' => $site_name,
                '{site_link}' => sprintf('<a href="%s">%s</a>', $site_url, $site_name),
                '{site_url}' => sprintf('<a href="%s">%s</a>', $site_url, $site_url),
                '{listing_title}' => $listing_title,
                '{listing_link}' => sprintf('<a href="%s">%s</a>', $listing_url, $listing_title),
                '{listing_url}' => sprintf('<a href="%s">%s</a>', $listing_url, $listing_url),
                '{sender_name}' => $user->display_name,
                '{sender_email}' => $user->user_email,
                '{message}' => $message
            );
            $send_email = get_directorist_option('admin_email_lists');

            $to = !empty($send_email) ? $send_email : get_bloginfo('admin_email');

            $subject = __('{site_name} Report Abuse via "{listing_title}"', 'directorist');
            $subject = strtr($subject, $placeholders);

            $message = __("Dear Administrator,<br /><br />This is an email abuse report for a listing at {listing_url}.<br /><br />Name: {sender_name}<br />Email: {sender_email}<br />Message: {message}", 'directorist');
            $message = strtr($message, $placeholders);
            $message = atbdp_email_html($subject, $message);
            $headers = "From: {$user->display_name} <{$user->user_email}>\r\n";
            $headers .= "Reply-To: {$user->user_email}\r\n";

            // return true or false, based on the result
            return ATBDP()->email->send_mail($to, $subject, $message, $headers) ? true : false;

        }

        public function ajax_callback_report_abuse()
        {


            $data = array('error' => 0);


            if ($this->atbdp_email_admin_report_abuse()) {

                $data['message'] = __('Your message sent successfully.', 'directorist');

            } else {

                $data['error'] = 1;
                $data['message'] = __('Sorry! Please try again.', 'directorist');

            }


            echo wp_json_encode($data);
            wp_die();

        }

        /**
         * Send contact message to the listing owner.
         *
         * @return   string    $result    Message based on the result.
         * @since    4.0.0
         *
         */
        function atbdp_email_listing_owner_listing_contact()
        {

            /**
             * If fires sending processing the submitted contact information
             * @since 4.4.0
             */
            do_action('atbdp_before_processing_contact_to_owner');
            if (!in_array('listing_contact_form', get_directorist_option('notify_user', array()))) return false;
            // sanitize form values
            $post_id = (int)$_POST["post_id"];
            $name = sanitize_text_field($_POST["name"]);
            $email = sanitize_email($_POST["email"]);
            $listing_email = sanitize_email($_POST["listing_email"]);
            $message = stripslashes(esc_textarea($_POST["message"]));

            // vars
            $post_author_id = get_post_field('post_author', $post_id);
            $user = get_userdata($post_author_id);
            $site_name = get_bloginfo('name');
            $site_url = get_bloginfo('url');
            $site_email = get_bloginfo('admin_email');
            $listing_title = get_the_title($post_id);
            $listing_url = get_permalink($post_id);
            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            $current_time = current_time('timestamp');
            $contact_email_subject = get_directorist_option('email_sub_listing_contact_email');
            $contact_email_body = get_directorist_option('email_tmpl_listing_contact_email');
            $user_email = get_directorist_option('user_email', 'author');

            $placeholders = array(
                '==NAME==' => $user->display_name,
                '==USERNAME==' => $user->user_login,
                '==SITE_NAME==' => $site_name,
                '==SITE_LINK==' => sprintf('<a href="%s">%s</a>', $site_url, $site_name),
                '==SITE_URL==' => sprintf('<a href="%s">%s</a>', $site_url, $site_url),
                '==LISTING_TITLE==' => $listing_title,
                '==LISTING_LINK==' => sprintf('<a href="%s">%s</a>', $listing_url, $listing_title),
                '==LISTING_URL==' => sprintf('<a href="%s">%s</a>', $listing_url, $listing_url),
                '==SENDER_NAME==' => $name,
                '==SENDER_EMAIL==' => $email,
                '==MESSAGE==' => $message,
                '==TODAY==' => date_i18n($date_format, $current_time),
                '==NOW==' => date_i18n($date_format . ' ' . $time_format, $current_time)
            );
            if ('listing_email' == $user_email) {
                $to = $listing_email;
            } else {
                $to = $user->user_email;
            }


            $subject = strtr($contact_email_subject, $placeholders);

            $message = strtr($contact_email_body, $placeholders);
            $message = nl2br($message);

            $headers = "From: {$name} <{$site_email}>\r\n";
            $headers .= "Reply-To: {$email}\r\n";
            $message = atbdp_email_html($subject, $message);
            // return true or false, based on the result
            return ATBDP()->email->send_mail($to, $subject, $message, $headers) ? true : false;

        }

        /**
         * Send contact message to the admin.
         *
         * @since    4.0
         */
        function atbdp_email_admin_listing_contact()
        {

            if (get_directorist_option('disable_email_notification')) return false; //vail if email notification is off

            if (!in_array('listing_contact_form', get_directorist_option('notify_admin', array()))) return false; // vail if order created notification to admin off

            // sanitize form values
            $post_id = (int)$_POST["post_id"];
            $name = sanitize_text_field($_POST["name"]);
            $email = sanitize_email($_POST["email"]);
            $message = esc_textarea($_POST["message"]);

            // vars
            $site_name = get_bloginfo('name');
            $site_url = get_bloginfo('url');
            $listing_title = get_the_title($post_id);
            $listing_url = get_permalink($post_id);
            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            $current_time = current_time('timestamp');

            $placeholders = array(
                '{site_name}' => $site_name,
                '{site_link}' => sprintf('<a href="%s">%s</a>', $site_url, $site_name),
                '{site_url}' => sprintf('<a href="%s">%s</a>', $site_url, $site_url),
                '{listing_title}' => $listing_title,
                '{listing_link}' => sprintf('<a href="%s">%s</a>', $listing_url, $listing_title),
                '{listing_url}' => sprintf('<a href="%s">%s</a>', $listing_url, $listing_url),
                '{sender_name}' => $name,
                '{sender_email}' => $email,
                '{message}' => $message,
                '{today}' => date_i18n($date_format, $current_time),
                '{now}' => date_i18n($date_format . ' ' . $time_format, $current_time)
            );
            $send_emails = ATBDP()->email->get_admin_email_list();
            $to = !empty($send_emails) ? $send_emails : get_bloginfo('admin_email');

            $subject = __('{site_name} Contact via {listing_title}', 'directorist');
            $subject = strtr($subject, $placeholders);

            $message = __("Dear Administrator,<br /><br />A listing on your website {site_name} received a message.<br /><br />Listing URL: {listing_url}<br /><br />Name: {sender_name}<br />Email: {sender_email}<br />Message: {message}<br />Time: {now}<br /><br />This is just a copy of the original email and was already sent to the listing owner. You don't have to reply this unless necessary.", 'directorist');
            $message = strtr($message, $placeholders);

            $headers = "From: {$name} <{$email}>\r\n";
            $headers .= "Reply-To: {$email}\r\n";
            $message = atbdp_email_html($subject, $message);

            return ATBDP()->email->send_mail($to, $subject, $message, $headers) ? true : false;


        }

        /**
         * Send contact email.
         *
         * @since    4.0
         * @access   public
         */
        public function ajax_callback_send_contact_email()
        {

            $data = array('error' => 0);


            if ($this->atbdp_email_listing_owner_listing_contact()) {

                // Send a copy to admin( only if applicable ).
                $this->atbdp_email_admin_listing_contact();

                $data['message'] = __('Your message sent successfully.', 'directorist');

            } else {

                $data['error'] = 1;
                $data['message'] = __('Sorry! Please try again.', 'directorist');

            }


            echo wp_json_encode($data);
            wp_die();

        }


        public function atbdp_diactivate_reason_mail()
        {
            global $wpdb;

            if (!isset($_POST['reason_id'])) {
                wp_send_json_error();
            }
            $site_name = get_bloginfo('name');
            $current_user = wp_get_current_user();

            $data = array(
                'reason_id' => sanitize_text_field($_POST['reason_id']),
                'url' => home_url(),
                'user_email' => $current_user->user_email,
                'user_name' => $current_user->display_name,
                'reason_info' => isset($_REQUEST['reason_info']) ? trim(stripslashes($_REQUEST['reason_info'])) : '',
                'software' => $_SERVER['SERVER_SOFTWARE'],
                'php_version' => phpversion(),
                'mysql_version' => $wpdb->db_version(),
                'wp_version' => get_bloginfo('version'),
                'locale' => get_locale(),
                'multisite' => is_multisite() ? 'Yes' : 'No'
            );
            $to = 'contact@aazztech.com';
            $subject = 'Deactivate directorist plugin';
            $message = $data['reason_info'];
            $headers = 'From ' . $data['user_email'];
            return ATBDP()->email->send_mail($to, $subject, $message, $headers) ? true : false;
        }

        public function atbdp_deactivate_reason()
        {

            $data = array('error' => 0);

            if ($this->atbdp_diactivate_reason_mail()) {


                $data['message'] = __('Thanks for information', 'directorist');

            }


            echo wp_json_encode($data);
            wp_die();


        }

        public function bdas_dropdown_terms()
        {
            check_ajax_referer('bdas_ajax_nonce', 'security');

            if (isset($_POST['taxonomy']) && isset($_POST['parent'])) {

                $args = array(
                    'taxonomy' => sanitize_text_field($_POST['taxonomy']),
                    'base_term' => 0,
                    'parent' => (int)$_POST['parent']
                );

                if ('at_biz_dir-location' == $args['taxonomy']) {

                    $args['orderby'] = 'date';
                    $args['order'] = 'ASC';
                }

                if ('at_biz_dir-category' == $args['taxonomy']) {

                    $args['orderby'] = 'date';
                    $args['order'] = 'ASC';
                }

                if (isset($_POST['class']) && '' != trim($_POST['class'])) {
                    $args['class'] = sanitize_text_field($_POST['class']);
                }

                if ($args['parent'] != $args['base_term']) {
                    ob_start();
                    bdas_dropdown_terms($args);
                    $output = ob_get_clean();
                    print $output;
                }

            }

            wp_die();
        }

        public function custom_field_search($term_id = 0)
        {
            $ajax = false;

            if (isset($_POST['term_id'])) {

                check_ajax_referer('bdas_ajax_nonce', 'security');

                $ajax = true;
                $term_id = (int)$_POST['term_id'];

            }
            // Get custom fields
            $custom_field_ids = atbdp_get_custom_field_ids($term_id);

            $args = array(
                'post_type' => ATBDP_CUSTOM_FIELD_POST_TYPE,
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'post__in' => $custom_field_ids,
                'meta_query' => array(
                    array(
                        'key' => 'searchable',
                        'value' => 1,
                        'type' => 'NUMERIC',
                        'compare' => '='
                    ),
                ),
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
            );
            $acadp_query = new WP_Query($args);

            // Start the Loop
            global $post;

            // Process output
            ob_start();
            require ATBDP_TEMPLATES_DIR . 'custom-field-search-form.php';
            wp_reset_postdata(); // Restore global post data stomped by the_post()
            $output = ob_get_clean();

            print $output;

            if ($ajax) {
                wp_die();
            }
        }

    }


endif;