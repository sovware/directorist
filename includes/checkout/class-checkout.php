<?php
/**
 * Checkout
 *
 * @package       directorist
 * @subpackage    directorist/includes/checkout
 * @copyright     Copyright 2018. AazzTech
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GNU Public License
 * @since         3.1.0
 */
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * ATBDP_Checkout Class
 *
 * @since    3.1.0
 * @access   public
 */
class ATBDP_Checkout
{
    /**
     * @var string
     */
    public $nonce = 'checkout_nonce';
    /**
     * @var string
     */
    public $nonce_action = 'checkout_action';

    public function __construct()
    {
        add_action('init', array($this, 'buffer_to_fix_redirection'));
    }

    /**
     *
     */
    public static function ajax_atbdp_format_total_amount()
    {
        if (valid_js_nonce()) {
            if (!empty($_POST['amount'])) {
                echo atbdp_format_payment_amount($_POST['amount']);
            }
        }
        wp_die();
    }

    /**
     * @return string
     */
    public function display_checkout_content()
    {
        // vail out showing a friendly-message, if user is not logged in. No need to run further code
        if (!atbdp_is_user_logged_in()) return null;
        ob_start();
        $enable_monetization = apply_filters('atbdp_enable_monetization_checkout',get_directorist_option('enable_monetization'));
        // vail if monetization is not active.
        if (!$enable_monetization) {
            return __('Monetization is not active on this site. if you are an admin, you can enable it from the settings panel.', 'directorist');
        }
        wp_enqueue_script('atbdp_checkout_script');
        // user logged in & monetization is active, so lets continue
        // get the listing id from the url query var
        $listing_id = get_query_var('atbdp_listing_id');
        // vail if the id is empty or post type is not our post type.
        if ( directorist_payment_guard() ) {
            return __('Sorry, Something went wrong. Listing ID is missing. Please try again.', 'directorist');
        }
        // if the checkout form is submitted, then process placing order
        if ('POST' == $_SERVER['REQUEST_METHOD'] && ATBDP()->helper->verify_nonce($this->nonce, $this->nonce_action)) {
            // Process the order
            $this->create_order($listing_id, $_POST);
        } else {
            // Checkout form is not submitted, so show the content of the checkout items here
            $form_data = apply_filters('atbdp_checkout_form_data', array(), $listing_id); // this is the hook that an extension can hook to, to add new items on checkout page.eg. plan
            // let's add featured listing data
            $featured_active = apply_filters('atbdp_featured_active_checkout',get_directorist_option('enable_featured_listing'));
            if ($featured_active && !is_fee_manager_active()) {
                $title = get_directorist_option('featured_listing_title', __('Featured', 'directorist'));
                $desc = get_directorist_option('featured_listing_desc');
                $price = get_directorist_option('featured_listing_price');
                $form_data[] = array(
                    'type' => 'header',
                    'title' => $title,
                    'name' => 'feature',
                    'value' => 1,
                    'selected' => 1,
                    'desc' => $desc,
                    'price' => $price,
                );
                $form_data[] = array(
                    'type' => 'checkbox',
                    'name' => 'feature',
                    'value' => 1,
                    'selected' => 1,
                    'title' => $title,
                    'desc' => $desc,
                    'price' => $price,
                );
            }
            // if data is empty then vail,
            if (empty($form_data)) {
                return __('Sorry, Nothing is available to buy. Please try again.', 'directorist');
            }
            // pass the data using a data var, so that we can add to it more item later.
            $data = array(
                'form_data' => apply_filters('atbdp_checkout_form_final_data', $form_data, $listing_id),
                'listing_id' => $listing_id,
            );
            // prepare all the variables required by the checkout page.
            $form_data  = !empty($data['form_data']) ? $data['form_data'] : array();
            $listing_id = !empty($data['listing_id']) ? $data['listing_id'] : 0;
            $c_position = get_directorist_option('payment_currency_position');
            $currency   = atbdp_get_payment_currency();
            $symbol     = atbdp_currency_symbol($currency);
            $before     = '';
            $after      = '';
            $args       = array( 
                'form_data'     => $form_data,
                'listing_id'    => $listing_id,
                'c_position'    => $c_position,
                'currency'      => $currency,
                'symbol'        => $symbol,
                'before'        => $before, 
                'after'         => $after, 
            );

            \Directorist\Helper::add_shortcode_comment( 'directorist_checkout' );

            //displaying data for checkout
            \Directorist\Helper::get_template( 'payment/checkout', array( 'checkout' => $args ) );
        }
        return ob_get_clean();
    }

    /**
     * @return string
     */
    public function payment_receipt()
    {
        if (!atbdp_is_user_logged_in()) return null; // vail out showing a friendly-message, if user is not logged in.
        //content of order receipt should be outputted here.
        $order_id = (int)get_query_var('atbdp_order_id');
        if (empty($order_id)) {
            return __('Sorry! No order id has been provided.', 'directorist');
        }

        $meta = get_post_meta($order_id);

        if ( empty($meta['_listing_id']) ) {
            return __('Sorry! order not found.', 'directorist');
        }

        $listing_id = $meta['_listing_id'];

        $data = apply_filters('atbdp_payment_receipt_data', array(), $order_id, $listing_id);
        $data = !empty($data) ? $data : array();
        $order = get_post($order_id); // we need that order to use its time
        $data = array_merge($data, array(
            'order' => $order,
            'order_id' => $order_id,
            'o_metas' => $meta,
        ));

        // we need to provide payment receipt shortcode with the order details array as we passed in the order checkout form page.
        $order_items = apply_filters('atbdp_order_items', array(), $order_id, $listing_id, $data); // this is the hook that an extension can hook to, to add new items on checkout page.eg. plan
        // let's add featured listing data if the order has featured listing in it
        $featured_active = get_directorist_option('enable_featured_listing');
        if ($featured_active && !empty($meta['_featured'])) {
            $title = get_directorist_option('featured_listing_title', __('Featured', 'directorist'));
            $desc = get_directorist_option('featured_listing_desc');
            $price = get_directorist_option('featured_listing_price');
            $order_items[] = array(
                'title' => $title,
                'desc' => $desc,
                'price' => $price,
            );
        }
        $data['order_items'] = $order_items;

        ob_start();
        extract($data);
        $data['c_position']     = get_directorist_option('payment_currency_position');
        $data['currency']         = atbdp_get_payment_currency();
        $data['symbol']          = atbdp_currency_symbol( atbdp_get_payment_currency() );
        $data['container_fluid']  = 'container-fluid';
        $data['order_id']         = (!empty($order_id)) ? $order_id : '';

        \Directorist\Helper::add_shortcode_comment( 'directorist_payment_receipt' );
        \Directorist\Helper::get_template( 'payment/payment-receipt', apply_filters( 'directorist_payment_receipt_data', $data, $order_id) );

        return ob_get_clean();
    }

    /**
     * It creates an order for the given listing id
     * @param int $listing_id Listing ID
     * @param array $data Optional Data
     */
    private function create_order($listing_id = 0, $data = array())
    {
        if ( directorist_payment_guard() ) return; // vail if not listing id is provided
        // create an order
        $order_id = wp_insert_post(array(
            'post_content' => '',
            'post_title' => sprintf('Order for the listing ID #%d', $listing_id),
            'post_status' => 'publish',
            'post_type' => 'atbdp_orders',
            'comment_status' => false,
        ));
        // if order is created successfully then process the order
        apply_filters('atbdp_before_order_recipt', array(), $listing_id);
        if ($order_id) {
            /*@todo; Find a better way to search for a order with a given ID*/
            /*wp_update_post(array(
                'ID'=> (int) $order_id,
                'post_type' => 'atbdp_orders',
                'post_title' => sprintf('Order #%d for the listing ID #%d', $order_id, $listing_id)
                ));*/
            $order_details = apply_filters('atbdp_order_details', array(), $order_id, $listing_id);
            //If featured item is bought, attach it to the order.
            if (!empty($data['feature'])) {
                update_post_meta($order_id, '_featured', 1);
                //lets add the settings of featured listing to the order details
                $order_details[] = atbdp_get_featured_settings_array();
            }
            // now lets calculate the total price of all order item's price
            $amount = 0.00;
            foreach ($order_details as $detail) {
                if (isset($detail['price'])) {
                    $amount = $detail['price'];
                }
            }
            /*Lowercase alphanumeric characters, dashes and underscores are allowed.*/
            $gateway = !empty($amount) && !empty($data['payment_gateway']) ? sanitize_key($data['payment_gateway']) : 'free';
            // save required data as order post meta

            $amount = apply_filters( 'atbdp_order_amount', $amount, $order_id );
            update_post_meta($order_id, '_listing_id', $listing_id);
            update_post_meta($order_id, '_amount', $amount);
            update_post_meta($order_id, '_payment_gateway', $gateway);
            update_post_meta($order_id, '_payment_status', 'created');
            // Hook for developer
            do_action('atbdp_order_created', $order_id, $listing_id); /*@todo; do something to prevent multiple order creation when user try to repeat failed payment*/
            $this->process_payment($amount, $gateway, $order_id, $listing_id, $data);
        }
    }

    /**
     * It process the payment of the order
     *
     * @param float $amount The order amount
     * @param string $gateway The name of the gateway
     * @param int $order_id The order ID
     * @param int $listing_id The Listing ID for which the order has been created.
     * @param array $data The $_POST data basically
     */
    private function process_payment($amount, $gateway, $order_id, $listing_id, $data = array())
    {
        /*Process paid listing*/
        if ($amount > 0) {
            if ('bank_transfer' == $gateway) {
                update_post_meta($order_id, '_transaction_id', wp_generate_password(15, false));
                //hook for developer
                do_action('atbdp_offline_payment_created', $order_id, $listing_id);
                // admin will mark the order completed manually once he get the payment on his bank.
                // let's redirect the user to the payment receipt page.
                $redirect_url = apply_filters('atbdp_payment_receipt_page_link', ATBDP_Permalink::get_payment_receipt_page_link($order_id), $order_id);
                wp_safe_redirect($redirect_url);
                exit();
            } else {
                /**
                 * fires 'atbdp_process_gateway_name_payment', it helps extensions and other payment plugin to process the payment
                 * atbdp_orders post has all the required information in its meta data like listing id and featured data etc.
                 *
                 * @param string $gateway The name of the gateway
                 * @param int $order_id The Order ID
                 * @param int $listing_id The Listing ID
                 * @param array $data The $_POST data basically
                 */
                do_action('atbdp_process_' . $gateway . '_payment', $order_id, $listing_id, $data);
                do_action('atbdp_online_order_processed', $order_id, $listing_id);
            }
        } else {
            /*@todo; Notify owner based on admin settings that order CREATED*/
            /*complete Free listing Order */
            $this->complete_free_order(
                array(
                    'ID' => $order_id,
                    'transaction_id' => wp_generate_password(15, false),
                    'listing_id' => $listing_id
                )
            );
            $redirect_url = apply_filters('atbdp_payment_receipt_page_link', ATBDP_Permalink::get_payment_receipt_page_link($order_id), $order_id);
            wp_safe_redirect($redirect_url);
            exit;
        }
    }

    /**
     * /**
     * It completes order that are free of charge
     * @param array $order_data The array of order data
     */
    private function complete_free_order($order_data)
    {
        // add payment status, tnx_id etc.
        update_post_meta($order_data['ID'], '_payment_status', 'completed');
        update_post_meta($order_data['ID'], '_transaction_id', $order_data['transaction_id']);
        // If the order has featured, make the related listing featured.
        $featured = get_post_meta($order_data['ID'], '_featured', true);
        if (!empty($featured)) {
            update_post_meta($order_data['listing_id'], '_featured', 1);
        }
        // Order has been completed. Let's fire a hook for a developer to extend if they wish
        do_action('atbdp_order_completed', $order_data['ID'], $order_data['listing_id']);
    }

    /**
     * It starts output buffering if the checkout form has been submitted in order to fix redirection problem.
     */
    public function buffer_to_fix_redirection()
    {
        // if the checkout form is submitted, then init buffering to solve redirection problem because of header already sent
        if ('POST' == $_SERVER['REQUEST_METHOD'] && ATBDP()->helper->verify_nonce($this->nonce, $this->nonce_action)) {
            ob_start();
        }
    }

    /**
     * It output content for payment failure page
     * @return string
     * @since 3.1.2
     * @todo; improve this content or page later.
     */
    public function transaction_failure()
    {
        ob_start();

        \Directorist\Helper::add_shortcode_comment( 'directorist_transaction_failure' );
        \Directorist\Helper::get_template( 'payment/transaction-failure' );

        return ob_get_clean();
    }
} // ends class