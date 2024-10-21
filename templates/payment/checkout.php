<?php
/**
 * @author  wpWax
 * @since   7.0
 * @version8.0
 */

extract( $checkout );
use \Directorist\Helper;
('after' == $c_position) ? $after = $symbol : $before = $symbol; ?>
<div id="directorist" class="atbd_wrapper directorist directorist-checkout-form directorist-w-100">
<div class="<?php Helper::directorist_container_fluid(); ?>">
        <div class="<?php Helper::directorist_row(); ?>">
            <div class="directorist-col-md-8 directorist-offset-md-2">
        <?php do_action('atbdp_before_checkout_form_start'); ?>
        <form id="atbdp-checkout-form" class="form-vertical clearfix" method="post" action="#">
            <?php do_action('atbdp_after_checkout_form_start'); ?>
            <div class="directorist-checkout-text directorist-text-center directorist-mb-40">
                <?php esc_html_e('Your order details are given below. Please review it and click on Proceed to Payment to complete this order.', 'directorist'); ?>
            </div>
            <?php
            /**
             * @since 6.5.6
             */
            do_action( 'atbdp_before_checkout_table', $form_data );
            ?>
            <div class="directorist-card directorist-checkout-card">
                <div class="directorist-card__header">
                    <h3 class="directorist-card__header__title">Order Summary</h3>
                </div>
                <div class="directorist-card__body">
                    <div class="directorist-table-responsive">
                        <table id="directorist-checkout-table" class="directorist-table">
                            <tbody>
                            <?php
                            // $args is auto available available through the load_template().
                            // var_dump( $form_data );
                            $subtotal         = 0;
                            $selected_product = 0;

                            foreach ( $form_data as $key => $option ) {
                                if ( 'header' == $option['type'] ) { ?><?php } else { /* Display other type of item here */ ?>
                                    <tr>
                                        <td colspan="2" class="">
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
                                                ?>

                                                <input type='hidden' id="<?php echo esc_attr( $atts['id'] ); ?>" name="<?php echo esc_attr( $atts['name'] ); ?>" class="<?php echo esc_attr( $atts['class'] ); ?>" value="<?php echo esc_attr( $atts['value'] ); ?>" data-price-type="<?php echo esc_attr( $atts['data-price-type'] ); ?>" <?php echo esc_attr( $atts['checked'] ); ?>/>

                                                <?php
                                                // Add the price and product
                                                if ( is_numeric( $atts['value'] ) && $option['selected'] && 'addition' === $atts['data-price-type'] ) {
                                                    $price = ( preg_match( '/[.]/', $atts['value'] ) ) ? ( float ) $atts['value'] : ( int ) $atts['value'];
                                                    $subtotal += $price;
                                                    $selected_product++;
                                                }
                                            ?>
                                            <?php
                                            if ( ! empty( $option['title'] ) ) {
                                                printf( '<label for="%s"></label>', esc_attr( $atts['id'] ) );
                                            }
                                            ?>
                                            <span class="directorist-summery-label"> <?php echo esc_html( $option['title'] ) ?> </span>
                                            <?php if ( ! empty( $option['desc'] ) ) echo '<p class="directorist-summery-label-description">'. esc_html($option['desc']) . '</p>'; ?>
                                        </td>
                                        <td class="directorist-text-right">
                                            <span class="directorist-summery-amount">
                                            <?php if (!empty($option['price'])) {
                                                $output = $before. atbdp_format_payment_amount($option['price']) . $after;
                                                echo wp_kses_post( $output );
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
                                <td colspan="2" class="">
                                    <span class="directorist-summery-label"><?php esc_html_e( 'Subtotal', 'directorist' ); ?></span>
                                </td>
                                <td class="directorist-text-right">
                                    <div id="atbdp_checkout_subtotal_amount" class="directorist-summery-amount">
                                        <?php
                                        $output = $before. atbdp_format_payment_amount( $subtotal ) . $after;
                                        echo wp_kses_post( $output );
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="directorist-summery-total">
                                <td colspan="2" class="">
                                    <span class="directorist-summery-label"><?php printf( esc_html__( 'Total amount [%s]', 'directorist' ), esc_html( $currency ) ); ?></h4>
                                </td>
                                <td class="directorist-text-right">
                                    <div id="atbdp_checkout_total_amount" class="directorist-summery-amount"><?php echo number_format( $subtotal, 2 ) ?></div>
                                    <input type="hidden" name="price" id="atbdp_checkout_total_amount_hidden" value="<?php echo esc_attr( $subtotal ) ?>">
                                </td>
                            </tr>
                            </tbody>
                        </table> <!--ends table-->
                    </div>
                </div>
            </div>
            

            <?php if ( $subtotal > 0 ) : ?>
            <div class="directorist-card directorist-mt-30 directorist-payment-gateways directorist-mb-15 directorist-checkout-card directorist-checkout-payment" id="directorist_payment_gateways">
                <div class="directorist-card__header">
                    <h3 class="directorist-card__header__title"><?php esc_html_e('Choose a payment method', 'directorist'); ?></h3>
                </div>

                <div class="directorist-card__body">
					<?php echo directorist_kses( ATBDP_Gateway::gateways_markup(), 'all' ); ?>
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
            $monitization        = directorist_is_monetization_enabled();
            $featured_enabled    = directorist_is_featured_listing_enabled();
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
            <input type="hidden" id="listing_id" name="listing_id" value="<?php echo esc_attr( $listing_id ); ?>"/>
            <div class="directorist-payment-action directorist-flex directorist-justify-content-between" id="atbdp_pay_notpay_btn">
                <a href="<?php echo esc_url( apply_filters( 'atbdp_checkout_not_now_link', $url ) ); ?>" class="directorist-btn directorist-btn-lg directorist-btn-light atbdp_not_now_button"><?php esc_html_e('Not Now', 'directorist'); ?></a>
                <button type="submit" id="atbdp_checkout_submit_btn" class="directorist-btn directorist-btn-lg directorist-btn-payment-submit" value="<?php echo esc_attr( $submit_button_label ); ?>"><?php echo esc_html( $submit_button_label ); ?></button>
                <input type="hidden" id="atbdp_checkout_submit_btn_label" value="<?php echo esc_attr( $submit_button_label ); ?>"/>
            </div> <!--ends pull-right-->

            <?php do_action('atbdp_before_checkout_form_end'); ?>

        </form> <!--ends FORM  -->
        <?php do_action('atbdp_after_checkout_form_end'); ?>
            </div>
        </div>
    </div>
</div>
<!-- ends directorist directorist-checkout-form-->