<?php

defined( 'ABSPATH' ) || exit;

use \Directorist\Helper;

?>
<div id="directorist" class="atbd_wrapper directorist directorist-checkout-form directorist-w-100">
<div class="<?php Helper::directorist_container_fluid(); ?>">
    <div class="<?php Helper::directorist_row(); ?>">
        <div class="directorist-col-md-8 directorist-offset-md-2">
    <?php do_action( 'atbdp_before_checkout_form_start' ); ?>
    <form id="atbdp-checkout-form" class="form-vertical clearfix" method="post" action="#">
        <input type="hidden" name="checkout_type" value="<?php echo esc_attr( $checkout_type ); ?>">
        <?php do_action( 'atbdp_after_checkout_form_start' ); ?>
        <div class="directorist-checkout-text directorist-text-center directorist-mb-40">
            <?php esc_html_e( 'Your order details are given below. Please review it and click on Proceed to Payment to complete this order.', 'directorist' ); ?>
        </div>
        <?php
        /**
         * @since 6.5.6
         */
        // do_action( 'atbdp_before_checkout_table', $form_data );
        ?>
        <div class="directorist-card directorist-checkout-card">
            <div class="directorist-card__header">
                <h3 class="directorist-card__header__title"><?php esc_html_e( 'Order Summary', 'directorist' ); ?></h3>
            </div>
            <div class="directorist-card__body">
                <div class="directorist-table-responsive">
                    <table id="directorist-checkout-table" class="directorist-table">
                        <?php do_action( 'directorist_checkout_table', $checkout_type, $subtotal, $request ); ?>
                        <tr class="directorist-summery-total">
                            <td colspan="2" class="">
                                <span class="directorist-summery-label"><?php printf( esc_html__( 'Total amount', 'directorist' ) ); ?></h4>
                            </td>
                            <td class="directorist-text-right">
                                <div id="atbdp_checkout_total_amount" class="directorist-summery-amount"><?php echo esc_html( directorist_price( $subtotal ) ) ?></div>
                            </td>
                        </tr>
                    </table> <!--ends table-->
                </div>
            </div>
        </div>

        <?php if ( $subtotal > 0 ) : ?>
        <div class="directorist-card directorist-mt-30 directorist-payment-gateways directorist-mb-15 directorist-checkout-card directorist-checkout-payment" id="directorist_payment_gateways">
            <div class="directorist-card__header">
                <h3 class="directorist-card__header__title"><?php esc_html_e( 'Choose a payment method', 'directorist' ); ?></h3>
            </div>

            <div class="directorist-card__body">
                <?php echo directorist_kses( ATBDP_Gateway::gateways_markup(), 'all' ); ?>
            </div>
        </div>
        <?php endif; 
        
        
        $submit_button_label = ( $subtotal < 1 ) ? __( 'Complete Submission', 'directorist' ) : __( 'Pay Now', 'directorist' );
        ?>

        <p id="atbdp_checkout_errors" class="text-danger"></p>

        <div class="directorist-payment-action directorist-flex directorist-justify-content-between" id="atbdp_pay_notpay_btn">
            <a href="" class="directorist-btn directorist-btn-lg directorist-btn-light atbdp_not_now_button"><?php esc_html_e( 'Not Now', 'directorist' ); ?></a>
            <button type="submit" id="atbdp_checkout_submit_btn" class="directorist-btn directorist-btn-lg directorist-btn-payment-submit"><?php echo esc_html( $submit_button_label ); ?></button>
        </div> <!--ends pull-right-->

        <?php do_action( 'atbdp_before_checkout_form_end' ); ?>

        </form> <!--ends FORM  -->
        <?php do_action( 'atbdp_after_checkout_form_end' ); ?>
            </div>
        </div>
    </div>
</div>
<!-- ends directorist directorist-checkout-form-->