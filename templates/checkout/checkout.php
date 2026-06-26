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
                     do_action( 'atbdp_before_checkout_table', $request );
                    ?>
                    <div class="directorist-card directorist-checkout-card">
                        <div class="directorist-card__header">
                            <h3 class="directorist-card__header__title"><?php esc_html_e( 'Order Summary', 'directorist' ); ?></h3>
                        </div>
                        <div class="directorist-card__body">
                            <div class="directorist-table-responsive">
                                <table id="directorist-checkout-table" class="directorist-table">
                                    <?php do_action( 'directorist_checkout_table', $checkout_type, $total, $subtotal, $request ); ?>
                                    <tr class="directorist-summery-total directorist-row--order-total" data-order-total="<?php echo esc_attr( $total ) ?>">
                                        <td colspan="2">
                                            <span class="directorist-summery-label directorist-row-label--order-total">
                                                <?php printf( esc_html__( 'Total amount', 'directorist' ) ); ?>
                                            </span>
                                        </td>
                                        <td class="directorist-text-right">
                                            <div id="atbdp_checkout_total_amount" class="directorist-summery-amount directorist-row-value--order-total">
                                                <?php echo wp_kses_post( directorist_price( $total ) ) ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php do_action( 'directorist_checkout_table_after_total', $checkout_type, $total, $subtotal, $request ); ?>
                                </table> <!--ends table-->
                            </div>
                        </div>
                    </div>

                    <?php
                    $show_payment_gateways = apply_filters( 'directorist_checkout_show_payment_gateways', $subtotal > 0, $checkout_type, $subtotal, $request );
                    if ( $show_payment_gateways ) : ?>
                    <?php $active_gateways = apply_filters( 'directorist_checkout_active_gateways', ATBDP_Gateway::get_active_gateways(), $checkout_type, $request ); ?>
                    <div class="directorist-card directorist-mt-30 directorist-payment-gateways directorist-mb-15 directorist-checkout-card directorist-checkout-payment" id="directorist_payment_gateways">
                        <div class="directorist-card__header">
                            <h3 class="directorist-card__header__title"><?php esc_html_e( 'Choose a payment method', 'directorist' ); ?></h3>
                        </div>

                        <div class="directorist-card__body">
                            <?php echo directorist_kses( ATBDP_Gateway::gateways_markup( $active_gateways ), 'all' ); ?>
                            <?php do_action( 'directorist_checkout_payment_gateways_after', $checkout_type, $request, $active_gateways ); ?>
                        </div>
                    </div>
                    <?php endif;
                    $submit_button_label = ( $subtotal < 1 ) ? __( 'Complete Submission', 'directorist' ) : __( 'Pay Now', 'directorist' );
                    $submit_button_label = apply_filters( 'directorist_checkout_submit_button_label', $submit_button_label, $checkout_type, $subtotal, $request );
                    $dashboard_page_link = \ATBDP_Permalink::get_dashboard_page_link();
                    ?>

                    <p id="atbdp_checkout_errors" class="text-danger"></p>

                    <div class="directorist-payment-action directorist-flex directorist-justify-content-between" id="atbdp_pay_notpay_btn">
                        <a href="<?php echo $dashboard_page_link; ?>" class="directorist-btn directorist-btn-lg directorist-btn-light atbdp_not_now_button"><?php esc_html_e( 'Not Now', 'directorist' ); ?></a>
                        <button type="submit" id="atbdp_checkout_submit_btn" class="directorist-btn directorist-btn-lg directorist-btn-payment-submit" data-loading-text="<?php esc_html_e( 'Processing...', 'directorist' ); ?>">
                            <span class="directorist-btn-text"><?php echo esc_html( $submit_button_label ); ?></span>
                            <span class="directorist-btn-spinner" style="display: none;">
                                <svg class="directorist-spinner" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round" stroke-dasharray="31.416" stroke-dashoffset="31.416">
                                        <animate attributeName="stroke-dasharray" dur="2s" values="0 31.416;15.708 15.708;0 31.416" repeatCount="indefinite"/>
                                        <animate attributeName="stroke-dashoffset" dur="2s" values="0;-15.708;-31.416" repeatCount="indefinite"/>
                                    </circle>
                                </svg>
                            </span>
                        </button>
                    </div> <!--ends pull-right-->

                    <?php do_action( 'atbdp_before_checkout_form_end' ); ?>

                </form> <!--ends FORM  -->
                <?php do_action( 'atbdp_after_checkout_form_end' ); ?>
            </div>
        </div>
    </div>
</div>
<!-- ends directorist directorist-checkout-form-->
