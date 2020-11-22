<?php
/*Template for displaying Checkout form*/
// prepare all the variables required by the checkout page.
$form_data  = ! empty( $args['form_data'] ) ? $args['form_data'] : array();
$listing_id = ! empty( $args['listing_id'] ) ? $args['listing_id'] : 0;
$c_position = get_directorist_option('payment_currency_position');
$currency   = atbdp_get_payment_currency();
$symbol     = atbdp_currency_symbol( $currency );
// displaying data for checkout
?>
<div id="directorist" class="atbd_wrapper directorist directorist-checkout-form">
    <?php do_action('atbdp_before_checkout_form_start'); ?>
    <form id="atbdp-checkout-form" class="form-vertical clearfix" method="post" action="" role="form">
        <?php do_action('atbdp_after_checkout_form_start'); ?>
        <div class="alert alert-info alert-dismissable fade show" role="alert">
            <span class="fa fa-info-circle"></span>
            <?php esc_html_e('Your order details are given below. Please review it and click on Proceed to Payment to complete this order.', 'directorist'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <table id="directorist-checkout-table" class="table table-bordered table-responsive-sm">
            <thead class="thead-light">
            <tr>
                <th colspan="2">
                    <?php _e("Details","directorist");?>
                </th>
                <th><strong><?php printf(__('Price [%s]', 'directorist'), $currency); ?></strong></th>
            </tr>
            </thead>

            <tbody>
            <?php
            // $args is auto available available through the load_template().
            // var_dump( $form_data );
            $selected_pricing_element = 0;
            $addition_price           = 0;
            $substruction_price       = 0;

            foreach ( $form_data as $key => $option ) {
                if ('header' == $option['type']) { ?>

                <?php } else { /*Display other type of item here*/ ?>
                    <tr>
                        <td>
                            <?php
                                /* display proper type of checkbox/radio etc */
                                $atts = [
                                    'id'              => $option['name'] . '_' . $key,
                                    'name'            => $option['name'],
                                    'value'           => $option['price'],
                                    'class'           => 'atbdp-checkout-price-item atbdp_checkout_item_field',
                                    'data-price-type' => 'addition',
                                    'checked'         => isset( $option['selected'] ) ? checked( 1, $option['selected'], false ) : '',
                                ];
                                $input_field = "<input type='checkbox' id='{$atts['id']}' name='{$atts['name']}' class='{$atts['class']}' value='{$atts['value']}' data-price-type='{$atts['data-price-type']}' {$atts['checked']}/>";
                                
                                // Store Addtion Price
                                if ( is_numeric( $atts['value'] ) && $option['selected'] && 'addition' === $atts['data-price-type'] ) {
                                    $price = ( preg_match( '/[.]/', $atts['value'] ) ) ? ( float ) $atts['value'] : ( int ) $atts['value'];
                                    $addition_price = $addition_price +  $price;
                                    $selected_pricing_element++;
                                }

                                echo str_replace('checkbox', $option['type'], $input_field);
                            ?>
                            <?php if ( ! empty( $option['title'] ) ) echo "<label for='{$atts['id']}'><h4>" . esc_html($option['title']) . "</h4></label>"; ?>
                            <?php if ( ! empty( $option['desc'] ) ) echo esc_html($option['desc']); ?>
                        </td>
                        <td align="right" class="text-right">
                            <?php if (!empty($option['price'])) {
                                $before = '';
                                $after = '';
                                ('after' == $c_position) ? $after = $symbol : $before = $symbol;
                                echo $before . esc_html(atbdp_format_payment_amount($option['price'])) . $after;
                                do_action('atbdp_checkout_after_total_price', $args);
                            } ?>
                        </td>
                    </tr>
                <?php }
            }
            
            $net_price = $addition_price - $substruction_price;
            ?>
            <tr>
                <td colspan="2" class="text-right vertical-middle">
                    <strong><?php printf(__('Total amount [%s]', 'directorist'), $currency); ?></strong>
                </td>
                <td class="text-right vertical-middle">
                    <div id="atbdp_checkout_total_amount"><?php echo number_format( $net_price, 2 ) ?></div><!--total amount will be populated by JS-->
                    <input type="hidden" id="atbdp_checkout_total_amount_hidden" value="<?php echo $net_price ?>">
                </td>
            </tr>
            </tbody>
        </table> <!--ends table-->
        
        <?php if ( $net_price > 0 ) : ?>
        <div class="atbd_content_module" id="directorist_payment_gateways">
            <div class="atbd_content_module_title_area">
                <div class="atbd_area_title">
                    <h4><?php esc_html_e('Choose a payment method', 'directorist'); ?></h4>
                </div>
            </div>
            
            <div class="atbdb_content_module_contents">
                <?php echo ATBDP_Gateway::gateways_markup(); ?>
            </div>
        </div>
        <?php endif; ?>

        <?php
            do_action('atbdp_before_cc_form'); // Hook for dev
            do_action('atbdp_cc_form');        // placeholder action for credit card form
            do_action('atbdp_after_cc_form');  // Hook for dev
        ?>

        <p id="atbdp_checkout_errors" class="text-danger"></p>

        <?php wp_nonce_field('checkout_action', 'checkout_nonce');
        $new_l_status        = get_directorist_option('new_listing_status', 'pending');
        $monitization        = get_directorist_option('enable_monetization',0);
        $featured_enabled    = get_directorist_option('enable_featured_listing',0);
        $submit_button_label = ( $selected_pricing_element > 0 && $net_price < 1 ) ? __( 'Complete Submission', 'directorist' ) : __( 'Pay Now', 'directorist' );

        if ( is_fee_manager_active() ){
            $url = ATBDP_Permalink::get_dashboard_page_link();
        }

        if (!empty($monitization && $featured_enabled)){
            $url = add_query_arg('listing_status', $new_l_status,  ATBDP_Permalink::get_dashboard_page_link().'?listing_id='.$listing_id );
        } else{
            $url = add_query_arg('listing_status', $new_l_status,  ATBDP_Permalink::get_dashboard_page_link().'?listing_id='.$listing_id );
        }
        ?>
        <input type="hidden" id="listing_id" name="listing_id" value="<?php echo $listing_id; ?>"/>
        <div class="pull-right" id="atbdp_pay_notpay_btn">
            <a href="<?php echo esc_url( $url ); ?>" class="btn btn-danger atbdp_not_now_button"><?php _e('Not Now', 'directorist'); ?></a>
            <input type="submit" id="atbdp_checkout_submit_btn" class="btn btn-primary" value="<?php echo $submit_button_label; ?>"/>
            <input type="hidden" id="atbdp_checkout_submit_btn_label" value="<?php echo $submit_button_label; ?>"/>
        </div> <!--ends pull-right-->

        <?php do_action('atbdp_before_checkout_form_end'); ?>

    </form> <!--ends FORM  -->
    <?php do_action('atbdp_after_checkout_form_end'); ?>
</div>
<!-- ends directorist directorist-checkout-form-->