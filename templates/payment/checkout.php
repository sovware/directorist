<?php
extract( $checkout );
use \Directorist\Helper;
('after' == $c_position) ? $after = $symbol : $before = $symbol; ?>
<div id="directorist" class="atbd_wrapper directorist directorist-checkout-form directorist-w-100">
<div class="<?php Helper::directorist_container_fluid(); ?>">
        <div class="<?php Helper::directorist_row(); ?>">
            <div class="directorist-col-md-8 directorist-offset-md-2">
        <?php do_action('atbdp_before_checkout_form_start'); ?>
        <form id="atbdp-checkout-form" class="form-vertical clearfix" method="post" action="" role="form">
            <?php do_action('atbdp_after_checkout_form_start'); ?>
            <div class="directorist-alert directorist-alert-info directorist-mb-15" role="alert">
                <div class="directorist-alert__content">
                    <span class="fa fa-info-circle"></span>
                    <?php esc_html_e('Your order details are given below. Please review it and click on Proceed to Payment to complete this order.', 'directorist'); ?>
                    <a href="#" class="directorist-alert__close"><span aria-hidden="true">×</span></a>
                </div>
            </div>
            <?php
            /**
             * @since 6.5.6
             */
            do_action( 'atbdp_before_checkout_table', $form_data );
            ?>
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
                $subtotal         = 0;
                $selected_product = 0;

                foreach ( $form_data as $key => $option ) {
                    if ( 'header' == $option['type'] ) { ?><?php } else { /* Display other type of item here */ ?>
                        <tr>
                            <td colspan="2" class="text-right vertical-middle">
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
                                    $input_field = "<input type='hidden' id='{$atts['id']}' name='{$atts['name']}' class='{$atts['class']}' value='{$atts['value']}' data-price-type='{$atts['data-price-type']}' {$atts['checked']}/>";

                                    // Add the price and product
                                    if ( is_numeric( $atts['value'] ) && $option['selected'] && 'addition' === $atts['data-price-type'] ) {
                                        $price = ( preg_match( '/[.]/', $atts['value'] ) ) ? ( float ) $atts['value'] : ( int ) $atts['value'];
                                        $subtotal += $price;
                                        $selected_product++;
                                    }

                                    //echo str_replace('checkbox', $option['type'], $input_field);
                                    echo $input_field;
                                ?>
                                <?php if ( ! empty( $option['title'] ) ) echo "<label for='{$atts['id']}'><h4>" . esc_html($option['title']) . "</h4></label>"; ?>
                                <?php if ( ! empty( $option['desc'] ) ) echo '<span>'. esc_html($option['desc']) . '</span>'; ?>
                            </td>
                            <td class="text-right vertical-middle">
                                <span class="atbd-plan-price">
                                <?php if (!empty($option['price'])) {
                                    echo $before . esc_html(atbdp_format_payment_amount($option['price'])) . $after;
                                    do_action('atbdp_checkout_after_total_price', $form_data);
                                } ?>
                                </span>
                            </td>
                        </tr>
                    <?php }
                }

                /**
                 * @since 6.5.6
                 */
                do_action( 'atbdp_before_checkout_subtotal_tr', $form_data );
                ?>
                <tr class="atbdp_ch_subtotal">
                    <td colspan="2" class="text-right vertical-middle">
                        <h4><?php echo __( 'Subtotal', 'directorist' ); ?></h4>
                    </td>
                    <td class="text-right vertical-middle">
                        <div id="atbdp_checkout_subtotal_amount"><?php
                            echo $before;
                            echo esc_html( atbdp_format_payment_amount( $subtotal ) );
                            echo $after;
                        ?></div>

                    </td>
                </tr>
                <tr class="atbdp_ch_total">
                    <td colspan="2" class="text-right vertical-middle">
                        <h4 class="atbdp_ch_total_text"><?php printf(__('Total amount [%s]', 'directorist'), $currency); ?></h4>
                    </td>
                    <td class="text-right vertical-middle">
                        <div id="atbdp_checkout_total_amount"><?php echo number_format( $subtotal, 2 ) ?></div>
                        <input type="hidden" name="price" id="atbdp_checkout_total_amount_hidden" value="<?php echo $subtotal ?>">
                    </td>
                </tr>
                </tbody>
            </table> <!--ends table-->

            <?php if ( $subtotal > 0 ) : ?>
            <div class="directorist-content-module directorist-mt-30 directorist-payment-gateways directorist-mb-15" id="directorist_payment_gateways">
                <div class="directorist-content-module__title">
                    <h4><?php esc_html_e('Choose a payment method', 'directorist'); ?></h4>
                </div>

                <div class="directorist-content-module__contents">
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
            $directory_type 	 = get_post_meta( $listing_id, '_directory_type', true );
		    $new_l_status 	     = get_term_meta( $directory_type, 'new_listing_status', true );
            $monitization        = get_directorist_option('enable_monetization',0);
            $featured_enabled    = get_directorist_option('enable_featured_listing',0);
            $submit_button_label = ( $selected_product > 0 && $subtotal < 1 ) ? __( 'Complete Submission', 'directorist' ) : __( 'Pay Now', 'directorist' );

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
            <div class="directorist-payment-action" id="atbdp_pay_notpay_btn">
                <a href="<?php echo esc_url( apply_filters( 'atbdp_checkout_not_now_link', $url ) ); ?>" class="directorist-btn directorist-btn-outline-primary atbdp_not_now_button"><?php _e('Not Now', 'directorist'); ?></a>
                <button type="submit" id="atbdp_checkout_submit_btn" class="directorist-btn directorist-btn-primary" value="<?php echo $submit_button_label; ?>"><?php echo $submit_button_label; ?></button>
                <input type="hidden" id="atbdp_checkout_submit_btn_label" value="<?php echo $submit_button_label; ?>"/>
            </div> <!--ends pull-right-->

            <?php do_action('atbdp_before_checkout_form_end'); ?>

        </form> <!--ends FORM  -->
        <?php do_action('atbdp_after_checkout_form_end'); ?>
            </div>
        </div>
    </div>
</div>
<!-- ends directorist directorist-checkout-form-->