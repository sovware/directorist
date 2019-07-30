<?php
defined('ABSPATH') || die('Direct access is not allowed.');
if (!class_exists('ATBDP_Email')):
    /**
     * Class ATBDP_Email
     */
    class ATBDP_Email
    {
        /*@todo; later make all admin email template customization by setting page just like user email templates*/


        public function __construct()
        {
            /*Fire up emails when a listing is inserted in the front end*/
            add_action('atbdp_listing_inserted', array($this, 'notify_admin_listing_submitted'));
            add_action('atbdp_listing_inserted', array($this, 'notify_owner_listing_submitted'));
            /*Fire up emails for updated/edited listings */
            add_action('atbdp_listing_updated', array($this, 'notify_admin_listing_edited'));
            add_action('atbdp_listing_updated', array($this, 'notify_owner_listing_edited'));
            /*Fire up emails for published listings */
            add_action('atbdp_listing_published', array($this, 'notify_admin_listing_published'));
            add_action('atbdp_listing_published', array($this, 'notify_owner_listing_published'));
            /*Fire up emails for created order*/
            add_action('atbdp_order_created', array($this, 'notify_admin_order_created'), 10, 2);
            add_action('atbdp_order_created', array($this, 'notify_owner_order_created'), 10, 2);
            /*Offline Payment Made*/
            add_action('atbdp_offline_payment_created', array($this, 'notify_owner_offline_payment_created'), 10, 2);
            /*Fire up email for Completed Orders*/
            add_action('atbdp_order_completed', array($this, 'notify_owner_order_completed'), 10, 2);
            add_action('atbdp_order_completed', array($this, 'notify_admin_order_completed'), 10, 2);
            /*Fire up email for renewal notification*/
            add_action('atbdp_status_updated_to_renewal', array($this, 'notify_owner_listing_to_expire'));
            /*Fire up email for expired listings*/
            add_action('atbdp_listing_expired', array($this, 'notify_owner_listing_expired'));
            //@todo; send admin a notification too for expired listings. Think about it later or give admin option??
            add_action('atbdp_send_renewal_reminder', array($this, 'notify_owner_to_renew'));
            /*Fire up email for deleted/trashed listings*/
            add_action('atbdp_deleted_expired_listings', array($this, 'notify_owner_listing_deleted'));
            add_action('atbdp_deleted_expired_listings', array($this, 'notify_admin_listing_deleted'));

            //send new user confirmation notification to user
            add_filter('wp_new_user_notification_email', array($this, 'custom_wp_new_user_notification_email'), 10, 3);
            add_filter('wp_mail_from_name', array($this, 'atbdp_wp_mail_from_name'));
            //notification on new review submit
            //add_action('atbdp_post_insert_review', array($this, 'notify_admin_review_submitted'));
            //add_action('atbdp_post_insert_review', array($this, 'notify_user_review_submitted'));

        }


        /**
         * @since 5.8
         */
        public function atbdp_wp_mail_from_name()
        {
            $site_name = get_option('blogname');
            return $site_name;
        }

        /**
         * It notifies user when an offline payment made
         * @param int $order_id Order id
         * @param int $listing_id Listing ID
         */
        public function notify_owner_offline_payment_created($order_id, $listing_id)
        {
            $this->notify_owner_order_created($order_id, $listing_id, true);
        }

        /**
         * It replaces predefined placeholders in the given content.
         *
         * @since 3.1.0
         * @param string $content The content in which placeholders should be replaced
         * @param int $order_id [optional] Order ID
         * @param int $listing_id [optional] Listing ID
         * @param WP_User $user [optional] User Object
         * @see strtr() is better than str_replace() in our case : https://stackoverflow.com/questions/8177296/when-to-use-strtr-vs-str-replace
         * @return string               It returns the content after replacing the placeholder with proper data.
         */
        public function replace_in_content($content, $order_id = 0, $listing_id = 0, $user = null)
        {
            if (empty($listing_id)) {
                $listing_id = (int)get_post_meta($order_id, '_listing_id', true);
            }
            if (empty($user)) {
                $post_author_id = get_post_field('post_author', $listing_id);
                $user = get_userdata($post_author_id);
            } else {
                if (!$user instanceof WP_User) {
                    $user = get_userdata((int)$user);
                }
            }

            $site_name = get_option('blogname');
            $site_url = site_url();
            $l_title = get_the_title($listing_id);
            $listing_url = get_permalink($listing_id);
            $l_edit_url = admin_url("post.php?post={$listing_id}&action=edit");
            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            $current_time = current_time('timestamp');
            $exp_date = get_post_meta($listing_id, '_expiry_date', true);
            $never_exp = get_post_meta($listing_id, '_never_expire', true);
            $renewal_link = ATBDP_Permalink::get_renewal_page_link($listing_id);
            $order_receipt_link = ATBDP_Permalink::get_payment_receipt_page_link($order_id);
            $cats = wp_get_object_terms($listing_id, ATBDP_CATEGORY, array('fields' => 'names'));/*@todo, maybe we can use get_the_terms() for utilizing some default caching???*/
            $cat_name = !empty($cats) ? $cats[0] : '';/*@todo; if a listing is attached to multiple cats, we can print more than one cat later.*/
            $find_replace = array(
                '==NAME==' => !empty($user->display_name) ? $user->display_name : '',
                '==USERNAME==' => !empty($user->user_login) ? $user->user_login : '',
                '==SITE_NAME==' => $site_name,
                '==SITE_LINK==' => sprintf('<a href="%s">%s</a>', $site_url, $site_name),
                '==SITE_URL==' => sprintf('<a href="%s">%s</a>', $site_url, $site_url),
                '==EXPIRATION_DATE==' => !empty($never_exp) ? __('Never Expires', ATBDP_TEXTDOMAIN) : date_i18n($date_format, strtotime($exp_date)),
                '==CATEGORY_NAME==' => $cat_name,
                '==RENEWAL_LINK==' => sprintf('<a href="%s">%s</a>', $renewal_link, __('Visit Listing Renewal Page', ATBDP_TEXTDOMAIN)),
                '==LISTING_ID==' => $listing_id,
                '==LISTING_TITLE==' => $l_title,
                '==LISTING_EDIT_URL==' => sprintf('<a href="%s">%s</a>', $l_edit_url, $l_title),
                '==LISTING_LINK==' => sprintf('<a href="%s">%s</a>', $listing_url, $l_title),
                '==LISTING_URL==' => sprintf('<a href="%s">%s</a>', $listing_url, $listing_url),
                '==ORDER_ID==' => $order_id,
                '==ORDER_RECEIPT_URL==' => sprintf('<a href="%s">%s</a>', $order_receipt_link, __('View Order/Payment Receipt', ATBDP_TEXTDOMAIN)),
                //'==ORDER_DETAILS=='         => ATBDP_Order::get_order_details( $order_id ),
                '==TODAY==' => date_i18n($date_format, $current_time),
                '==NOW==' => date_i18n($date_format . ' ' . $time_format, $current_time),
            );
            $c = nl2br(strtr($content, $find_replace));
            // we do not want to use br for line break in the order details markup. so we removed that from bulk replacement.
            return str_replace('==ORDER_DETAILS==', ATBDP_Order::get_order_details($order_id), $c);

        }

        /**
         * Get the list of emails to send admin notification
         *
         * @since    3.1.0
         * @param     array|string $email_lists [optional] Email Lists.
         * @return     string|array    $to                    Array or comma-separated list of email addresses to send message. Default admin_email
         */
        public function get_admin_email_list($email_lists = '')
        {
            return !empty($email_lists)
                ? $email_lists
                : array_map('trim',
                    explode(',', get_directorist_option('admin_email_lists', get_bloginfo('admin_email')))
                );
        }

        /**
         * Get the owner data of the given listings ID
         *
         * @since 3.1.0
         * @param int $listing_id The Listing ID
         * @return WP_User   It returns the owner listing data
         */
        public function get_owner($listing_id)
        {
            return get_userdata(get_post_field('post_author', $listing_id));
        }

        /**
         * Get the email of the given listing owner
         *
         * @since 3.1.0
         * @param int $listing_id The Listing ID
         * @return string   It returns the owner email of a listing
         */
        public function get_owner_email($listing_id)
        {
            return get_the_author_meta('user_email', get_post_field('post_author', $listing_id));
        }

        /**
         * Get admin email template for listing deleted email
         *
         * @since 3.1.0
         * @return string It returns the email template to send to the admin when a listing is deleted or archived
         */
        public function get_listing_deleted_admin_tmpl()
        {
            return __("
Dear Administrator,

The following Listing has been deleted on your website ==SITE_NAME==

Listing Summery:
ID: ==LISTING_ID==
Title: ==LISTING_TITLE==


This email is sent automatically for information purpose only. Please do not respond to this.
", ATBDP_TEXTDOMAIN);
        }

        /**
         * Get admin email template for order created email
         *
         * @since 3.1.0
         * @return string It returns the email template to send to the admin when an order is created.
         */
        public function get_order_created_admin_tmpl()
        {
            return __("
Dear Administrator,

You have received a new order

This notification was for the order #==ORDER_ID== on the website ==SITE_NAME==.
You can access the order details directly by clicking on the link below after logging in your back end:

==ORDER_RECEIPT_URL==

Here is the order summery:

==ORDER_DETAILS==

This email is sent automatically for information purpose only. Please do not respond to this.
", ATBDP_TEXTDOMAIN);
        }

        /**
         * Get admin email template for order completed email
         *
         * @since 3.1.0
         * @return string It returns the email template to send to the admin when an order is completed.
         */
        public function get_order_completed_admin_tmpl()
        {
            return __("
Dear Administrator,

Congratulations!
You have received a payment against the order #==ORDER_ID==. The order is now completed.

You can access the order details directly by clicking on the link below after logging in your back end:

==ORDER_RECEIPT_URL==

Here is the order summery:

==ORDER_DETAILS==

This email is sent automatically for information purpose only. Please do not respond to this.
", ATBDP_TEXTDOMAIN);
        }

        /**
         * Get admin email template for a listing submitted email
         *
         * @since 3.1.0
         * @return string It returns the email template to send to the admin when a listing is submitted.
         */
        public function get_listing_submitted_admin_tmpl()
        {
            return __("
Dear Administrator,

A new listing has been submitted on your website [==SITE_NAME==].

Listing Summery:
ID: ==LISTING_ID==
Title: ==LISTING_TITLE==
Link: ==LISTING_LINK==
You can Edit/Review the listing using the link below:
==LISTING_EDIT_URL==

This email is sent automatically for information purpose only. Please do not respond to this.
", ATBDP_TEXTDOMAIN);
        }

        /**
         * Get admin email template for a listing published email
         *
         * @since 3.1.0
         * @return string It returns the email template to send to the admin when a listing is published.
         */
        public function get_listing_published_admin_tmpl()
        {
            return __("
Dear Administrator,

The listing #==LISTING_ID== has been been published on your website [==SITE_NAME==].

Listing Summery:
ID: ==LISTING_ID==
Title: ==LISTING_TITLE==
Link: ==LISTING_LINK==
You can Edit/Review the listing using the link below:
==LISTING_EDIT_URL==

This email is sent automatically for information purpose only. Please do not respond to this.
", ATBDP_TEXTDOMAIN);
        }

        /**
         * Get admin email template for a listing edited email
         *
         * @since 3.1.0
         * @return string It returns the email template to send to the admin when a listing is edited.
         */
        public function get_listing_edited_admin_tmpl()
        {
            return __("
Dear Administrator,

The listing #==LISTING_ID== has been been edited on your website [==SITE_NAME==].

Listing Summery:
ID: ==LISTING_ID==
Title: ==LISTING_TITLE==
Link: ==LISTING_LINK==
You can Edit/Review the listing using the link below:
==LISTING_EDIT_URL==

This email is sent automatically for information purpose only. Please do not respond to this.
", ATBDP_TEXTDOMAIN);
        }

        /**
         * Get admin email template for a listing edited email
         *
         * @since 5.4.1
         * @return string It returns the email template to send to the admin when a listing is edited.
         */
        public function get_review_submitted_admin_tmpl()
        {
            return __("
Dear Administrator,

The listing #==LISTING_ID== has a new review [==SITE_NAME==].

Listing Summery:
ID: ==LISTING_ID==
Title: ==LISTING_TITLE==
Link: ==LISTING_LINK==
You can see the review using the link below:
==LISTING_EDIT_URL==

This email is sent automatically for information purpose only. Please do not respond to this.
", ATBDP_TEXTDOMAIN);
        }

        /**
         * It sends an email
         *
         * @since 3.1.0
         * @param string|array $to Array or comma-separated list of email addresses to send message.
         * @param string $subject Email's Subject
         * @param string $message Email's body
         * @param string $headers Email's Header
         * @return bool         It returns true if mail is sent successfully. False otherwise.
         */
        public function send_mail($to, $subject, $message, $headers)
        {
            add_filter('wp_mail_content_type', array($this, 'html_content_type')); // set content type to html
            $sent = wp_mail($to, html_entity_decode($subject), $message, $headers);
            /*@todo; check if we really need to remove the filter, as the above filter change the content type only when we call this function.*/
            remove_filter('wp_mail_content_type', array($this, 'html_content_type')); // remove content type from html
            return $sent;
        }

        /**
         * It returns content type 'text/html'
         *
         * @since 3.1.0
         * @return string
         */
        public function html_content_type()
        {
            return 'text/html'; // default is 'text/plain'; @pluggable.php @line 418
        }

        /**
         * Get the email header eg. From: and Reply-to:
         *
         * @since 3.1.0
         * @param array $data [optional] The array of name and the reply to email
         * @return string It returns the header of the email that contains From: $name and Reply to: $email
         */
        public function get_email_headers($data = array())
        {
            // get the data from the db
            $name = !empty($data['name']) ? sanitize_text_field($data['name']) : get_directorist_option('email_from_name', get_option('blogname'));
            $email = !empty($data['email']) ? sanitize_email($data['email']) : get_directorist_option('email_from_email', get_option('admin_email'));
            // build the header for email and return it @todo; is it better to trim here? test on free time.
            return "From: {$name} <{$email}>\r\nReply-To: {$email}\r\n";
        }

        /**
         * It notifies the listing owner via email when his order is created
         *
         * @since 3.1.0
         * @param int $order_id The Order ID
         * @param int $listing_id The listing ID
         * @param bool $offline Whether the order is made using online payment or offline payment
         * @return bool Whether the message was sent successfully or not.
         */
        public function notify_owner_order_created($order_id, $listing_id, $offline = false)
        {
            if (get_directorist_option('disable_email_notification')) return false;
            if (!in_array('order_created', get_directorist_option('notify_user', array()))) return false;
            $user = $this->get_owner($listing_id);
            // Send email according to the type of the payment that user used during checkout. get email template from the db.
            $offline = (!empty($offline)) ? '_offline' : '';
            $sub = $this->replace_in_content(get_directorist_option("email_sub{$offline}_new_order"), $order_id, $listing_id, $user);
            $body = $this->replace_in_content(get_directorist_option("email_tmpl{$offline}_new_order"), $order_id, $listing_id, $user);

            return $this->send_mail($user->user_email, $sub, $body, $this->get_email_headers());
        }

        /**
         * It notifies the listing owner when an order is completed
         *
         * @since 3.1.0
         * @param int $order_id The order ID
         * @param int $listing_id The Listing ID
         * @return bool Whether the message was sent successfully or not.
         */
        public function notify_owner_order_completed($order_id, $listing_id)
        {
            if (get_directorist_option('disable_email_notification')) return false;
            if (!in_array('order_completed', get_directorist_option('notify_user', array()))) return false;
            $user = $this->get_owner($listing_id);
            $sub = $this->replace_in_content(get_directorist_option('email_sub_completed_order'), $order_id, $listing_id, $user);
            $body = $this->replace_in_content(get_directorist_option('email_tmpl_completed_order'), $order_id, $listing_id, $user);
            return $this->send_mail($user->user_email, $sub, $body, $this->get_email_headers());
        }

        /**
         * It notifies the listing owner via email when his listing is received
         *
         * @since 3.1.0
         * @param int $listing_id The listing ID
         * @return bool Whether the message was sent successfully or not.
         */
        public function notify_owner_listing_submitted($listing_id)
        {
            if (get_directorist_option('disable_email_notification')) return false;
            if (!in_array('listing_submitted', get_directorist_option('notify_user', array()))) return false;
            $user = $this->get_owner($listing_id);
            $sub = $this->replace_in_content(get_directorist_option("email_sub_new_listing"), null, $listing_id, $user);
            $body = $this->replace_in_content(get_directorist_option("email_tmpl_new_listing"), null, $listing_id, $user);

            return $this->send_mail($user->user_email, $sub, $body, $this->get_email_headers());
        }


        /**
         * It notifies admin via email when a listing is published
         *
         * @since 3.1.0
         * @param int $listing_id
         * @return bool Whether the email was sent successfully or not.
         */
        public function notify_admin_listing_published($listing_id)
        {
            if (get_directorist_option('disable_email_notification')) return false;
            if (!in_array('listing_published', get_directorist_option('notify_admin', array()))) return false;
            $s = __('[==SITE_NAME==] The Listing #==LISTING_ID== has been published on your website', ATBDP_TEXTDOMAIN);
            $sub = $this->replace_in_content($s, null, $listing_id);

            $body = $this->get_listing_published_admin_tmpl();
            return $this->send_mail($this->get_admin_email_list(), $sub, $this->replace_in_content($body, null, $listing_id), $this->get_email_headers());

        }


        /**
         * It notifies the listing owner via email when his listing is published
         *
         * @since 3.1.0
         * @param int $listing_id The listing ID
         * @return bool Whether the message was sent successfully or not.
         */
        public function notify_owner_listing_published($listing_id)
        {
            if (get_directorist_option('disable_email_notification')) return false;
            if (!in_array('listing_published', get_directorist_option('notify_user', array()))) return false;
            $user = $this->get_owner($listing_id);
            $sub = $this->replace_in_content(get_directorist_option("email_sub_pub_listing"), null, $listing_id, $user);
            $body = $this->replace_in_content(get_directorist_option("email_tmpl_pub_listing"), null, $listing_id, $user);

            return $this->send_mail($user->user_email, $sub, $body, $this->get_email_headers());
        }

        /**
         * It notifies the listing owner via email when his listing is edited
         *
         * @since 3.1.0
         * @param int $listing_id The listing ID
         * @return bool Whether the message was sent successfully or not.
         */
        public function notify_owner_listing_edited($listing_id)
        {
            if (get_directorist_option('disable_email_notification')) return false;
            if (!in_array('listing_edited', get_directorist_option('notify_user', array()))) return false;
            $user = $this->get_owner($listing_id);
            $sub = $this->replace_in_content(get_directorist_option("email_sub_edit_listing"), null, $listing_id, $user);
            $body = $this->replace_in_content(get_directorist_option("email_tmpl_edit_listing"), null, $listing_id, $user);
            return $this->send_mail($user->user_email, $sub, $body, $this->get_email_headers());
        }


        /**
         * @since 3.1.0
         * @param int $listing_id The listing ID
         * @return bool Whether the message was sent successfully or not.
         */
        public function notify_owner_listing_to_expire($listing_id)
        {
            if (empty($listing_id)) return false;
            if (get_directorist_option('disable_email_notification')) return false;
            if (!in_array('listing_to_expire', get_directorist_option('notify_user', array()))) return false;
            $user = $this->get_owner($listing_id);
            $sub = $this->replace_in_content(get_directorist_option("email_sub_to_expire_listing"), null, $listing_id, $user);
            $body = $this->replace_in_content(get_directorist_option("email_tmpl_to_expire_listing"), null, $listing_id, $user);

            return $this->send_mail($user->user_email, $sub, $body, $this->get_email_headers());
        }

        /**
         * It notifies the listing owner via email when his listing has expired
         *
         * @since 3.1.0
         * @param int $listing_id The listing ID
         * @return bool Whether the message was sent successfully or not.
         */
        public function notify_owner_listing_expired($listing_id)
        {
            //if (get_directorist_option('disable_email_notification')) return false;
            // if(! in_array( 'listing_expired', get_directorist_option('notify_user', array()) ) ) return false;
            $user = $this->get_owner($listing_id);
            $sub = $this->replace_in_content(get_directorist_option("email_sub_expired_listing"), null, $listing_id, $user);
            $body = $this->replace_in_content(get_directorist_option("email_tmpl_expired_listing"), null, $listing_id, $user);

            return $this->send_mail($user->user_email, $sub, $body, $this->get_email_headers());
        }

        /**
         * It notifies the listing owner via email to renew his listings
         *
         * @since 3.1.0
         * @param int $listing_id The listing ID
         * @return bool Whether the message was sent successfully or not.
         */
        public function notify_owner_to_renew($listing_id)
        {
            if (get_directorist_option('disable_email_notification')) return false;
            if (!in_array('remind_to_renew', get_directorist_option('notify_user', array()))) return false;
            $user = $this->get_owner($listing_id);
            $sub = $this->replace_in_content(get_directorist_option("email_sub_to_renewal_listing"), null, $listing_id, $user);
            $body = $this->replace_in_content(get_directorist_option("email_tmpl_to_renewal_listing"), null, $listing_id, $user);

            return $this->send_mail($user->user_email, $sub, $body, $this->get_email_headers());
        }

        /**
         * It notifies the listing owner via email his listings has renewed
         *
         * @since 3.1.0
         * @param int $listing_id The listing ID
         * @return bool Whether the message was sent successfully or not.
         */
        public function notify_owner_listing_renewed($listing_id)
        {
            if (get_directorist_option('disable_email_notification')) return false;
            if (!in_array('listing_renewed', get_directorist_option('notify_user', array()))) return false;
            $user = $this->get_owner($listing_id);
            $sub = $this->replace_in_content(get_directorist_option("email_sub_renewed_listing"), null, $listing_id, $user);
            $body = $this->replace_in_content(get_directorist_option("email_tmpl_renewed_listing"), null, $listing_id, $user);

            return $this->send_mail($user->user_email, $sub, $body, $this->get_email_headers());
        }


        /**
         * It notifies the listing owner when the listing is deleted
         *
         * @since 3.1.0
         * @param int $listing_id
         * @return bool Whether the email was sent successfully or not.
         */
        public function notify_owner_listing_deleted($listing_id)
        {
            if (get_directorist_option('disable_email_notification')) return false;
            if (!in_array('listing_deleted', get_directorist_option('notify_user', array()))) return false;
            $user = $this->get_owner($listing_id);
            $sub = $this->replace_in_content(get_directorist_option("email_sub_deleted_listing"), null, $listing_id, $user);
            $body = $this->replace_in_content(get_directorist_option("email_tmpl_deleted_listing"), null, $listing_id, $user);

            return $this->send_mail($user->user_email, $sub, $body, $this->get_email_headers());

        }


        /**
         * It notifies admin via email when a listing has been deleted
         *
         * @since 3.1.0
         * @param int $listing_id The listing ID
         * @return bool Whether the email was sent successfully or not.
         */
        public function notify_admin_listing_deleted($listing_id)
        {
            if (get_directorist_option('disable_email_notification')) return false; //vail if email notification is off
            if (!in_array('listing_deleted', get_directorist_option('notify_admin', array()))) return false; // vail if order created notification to admin off
            $s = __('[==SITE_NAME==] A Listing has been deleted [ID#: ==LISTING_ID==] on your website', ATBDP_TEXTDOMAIN);
            $sub = $this->replace_in_content($s, null, $listing_id);
            $body = $this->replace_in_content($this->get_listing_deleted_admin_tmpl(), null, $listing_id);
            return $this->send_mail($this->get_admin_email_list(), $sub, $body, $this->get_email_headers());

        }


        /**
         * It notifies admin via email when an order is created
         *
         * @since 3.1.0
         * @param int $listing_id The listing ID
         * @param int $order_id The order ID
         * @return bool Whether the email was sent successfully or not.
         */
        public function notify_admin_order_created($order_id, $listing_id)
        {
            /*@todo; think if it is better to assign disabled_email_notification to the class prop*/
            if (get_directorist_option('disable_email_notification')) return false; //vail if email notification is off
            if (!in_array('order_created', get_directorist_option('notify_admin', array()))) return false; // vail if order created notification to admin off
            $s = __('[==SITE_NAME==] You have a new order #==ORDER_ID== on your website', ATBDP_TEXTDOMAIN);
            $sub = $this->replace_in_content($s, $order_id);

            $t = $this->get_order_created_admin_tmpl(); // get the email template & replace order_receipt placeholder in it
            $body = str_replace('==ORDER_RECEIPT_URL==', admin_url("edit.php?post_type=atbdp_orders"), $t); /*@todo; MAYBE ?? it would be good if there is a dedicated page for viewing the payment receipt by the admin regardless the order_receipt shortcode is used or not.*/
            return $this->send_mail($this->get_admin_email_list(), $sub, $this->replace_in_content($body, $order_id, $listing_id), $this->get_email_headers());

        }


        /**
         * It notifies admin when an order is completed and payment is received therefore.
         *
         * @since 3.1.0
         * @param int $order_id the order id
         * @param int $listing_id the listing id
         * @return bool Whether the email was sent correctly or not
         */
        public function notify_admin_order_completed($order_id, $listing_id)
        {
            if (get_directorist_option('disable_email_notification')) return false;
            if (!in_array('order_completed', get_directorist_option('notify_admin', array()))) return false;
            $s = __('[==SITE_NAME==] Payment Notification : Order #==ORDER_ID== Completed', ATBDP_TEXTDOMAIN);
            $sub = $this->replace_in_content($s, $order_id);

            $t = $this->get_order_completed_admin_tmpl(); // get the email template & replace order_receipt placeholder in it
            $body = str_replace('==ORDER_RECEIPT_URL==', admin_url("edit.php?post_type=atbdp_orders"), $t);
            return $this->send_mail($this->get_admin_email_list(), $sub, $this->replace_in_content($body, $order_id, $listing_id), $this->get_email_headers());
        }

        /**
         * It notifies admin via email when a listing is submitted
         *
         * @since 3.1.0
         * @param int $listing_id
         * @return bool Whether the email was sent successfully or not.
         */
        public function notify_admin_listing_submitted($listing_id)
        {
            if (get_directorist_option('disable_email_notification')) return false;
            if (!in_array('listing_submitted', get_directorist_option('notify_admin', array()))) return false;
            $s = __('[==SITE_NAME==] A new listings is received on your website', ATBDP_TEXTDOMAIN);
            $sub = str_replace('==SITE_NAME==', get_option('blogname'), $s);

            $body = $this->get_listing_submitted_admin_tmpl();
            return $this->send_mail($this->get_admin_email_list(), $sub, $this->replace_in_content($body, null, $listing_id), $this->get_email_headers());

        }


        /**
         * It notifies admin via email when a listing is edited
         *
         * @since 3.1.0
         * @param int $listing_id
         * @return bool Whether the email was sent successfully or not.
         */
        public function notify_admin_listing_edited($listing_id)
        {

            if (get_directorist_option('disable_email_notification')) return false;
            if (!in_array('listing_edited', get_directorist_option('notify_admin', array()))) return false;
            $s = __('[==SITE_NAME==] The Listing #==LISTING_ID== has been edited on your website', ATBDP_TEXTDOMAIN);
            $sub = $this->replace_in_content($s, null, $listing_id);
            $body = $this->get_listing_edited_admin_tmpl();
            return $this->send_mail($this->get_admin_email_list(), $sub, $this->replace_in_content($body, null, $listing_id), $this->get_email_headers());

        }


             /**
         * It notifies admin via email when a listing is edited
         *
         * @since 5.4.2
         * @param int $reveiw_id
         * @param mixed $data
         * @return bool Whether the email was sent successfully or not.
         */
        public function notify_admin_review_submitted($reveiw_id, $data)
        {
            $listing_id = $data['post_id'];
            if (get_directorist_option('disable_email_notification')) return false;
            //if (!in_array('listing_edited', get_directorist_option('notify_admin', array()))) return false;
            $s = __('[==SITE_NAME==] The Listing #==LISTING_ID== has a new review.', ATBDP_TEXTDOMAIN);
            $sub = $this->replace_in_content($s, null, $listing_id);

            $body = $this->get_review_submitted_admin_tmpl();
            return $this->send_mail($this->get_admin_email_list(), $sub, $this->replace_in_content($body, null, $listing_id), $this->get_email_headers());

        }

        /**
         * It notifies the listing owner when the listing is deleted
         *
         * @since 5.4.2
         * @param int $reveiw_id
         * @return bool Whether the email was sent successfully or not.
         */
        public function notify_user_review_submitted($reveiw_id, $data)
        {
            $listing_id = $data['post_id'];
            if (get_directorist_option('disable_email_notification')) return false;
            //if (!in_array('listing_deleted', get_directorist_option('notify_user', array()))) return false;
            $user = $this->get_owner($listing_id);
            $sub = $this->replace_in_content(get_directorist_option("email_sub_deleted_listing"), null, $listing_id, $user);
            $body = $this->replace_in_content(get_directorist_option("email_tmpl_deleted_listing"), null, $listing_id, $user);

            return $this->send_mail($user->user_email, $sub, $body, $this->get_email_headers());

        }


        /**
         * @since 5.8
         */
        public function custom_wp_new_user_notification_email($wp_new_user_notification_email, $user, $blogname)
        {
            if (is_admin()){
                return $wp_new_user_notification_email;
            }
            $display_password = get_directorist_option('display_password_reg', 0);
            $require_password = get_directorist_option('require_password_reg',0);
            $user_password = get_user_meta($user->ID, '_atbdp_generated_password',true);
            if (empty($display_password)) {
                $sub = get_directorist_option('email_sub_registration_confirmation', __('Registration Confirmation!', ATBDP_TEXTDOMAIN));
                $body = get_directorist_option('email_tmpl_registration_confirmation', __("
Dear User,

Congratulations! Your registration is completed!

This email is sent automatically for information purpose only. Please do not respond to this.
You can login now using the below credentials:

", ATBDP_TEXTDOMAIN));
                $wp_new_user_notification_email['subject'] = sprintf('%s', $sub);
                $wp_new_user_notification_email['message'] = $body."
                
Username: $user->user_login
Password: $user_password";
                return $wp_new_user_notification_email;
            }elseif (empty($require_password)){
                $sub = get_directorist_option('email_sub_registration_confirmation', __('Registration Confirmation!', ATBDP_TEXTDOMAIN));
                $body =  get_directorist_option('email_tmpl_registration_confirmation', __("
Dear User,

Congratulations! Your registration is completed!

This email is sent automatically for information purpose only. Please do not respond to this.
You can login now using the below credentials:

", ATBDP_TEXTDOMAIN));
                $wp_new_user_notification_email['subject'] = sprintf('%s', $sub);
                $wp_new_user_notification_email['message'] = $body."
                
Username: $user->user_login
Password: $user_password";
                return $wp_new_user_notification_email;
            } else {
                $sub = get_directorist_option('email_sub_registration_confirmation', __('Registration Confirmation!', ATBDP_TEXTDOMAIN));
                $body = get_directorist_option('email_tmpl_registration_confirmation', __("
Dear User,

Congratulations! Your registration is completed!

This email is sent automatically for information purpose only. Please do not respond to this.
You can login now using the below credentials:

", ATBDP_TEXTDOMAIN));
                $wp_new_user_notification_email['subject'] = sprintf('%s', $sub);
                $wp_new_user_notification_email['message'] = $body."
                
Username: $user->user_login
Password: $user_password";
                return $wp_new_user_notification_email;
            }
        }

    } // ends class
endif;
