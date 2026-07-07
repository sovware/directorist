<?php
/**
 * @author  wpWax
 * @since   7.0
 * @version 7.7.0
 */
use Directorist\Enums\Payment\Status;
use Directorist\Helper;
use Directorist\DTO\Order\DTO;
use Directorist\DTO\Payment\DTO as PaymentDTO;
use Directorist\Enums\Order\Status as OrderStatus;
use Directorist\Enums\Order\TaxType as OrderTaxType;
use Directorist\Enums\Order\DiscountType as OrderDiscountType;

/**
 * @var array $order_items
 * @var DTO $order
 * @var ?PaymentDTO $payment
 * @var float $discount_amount
 * @var float $tax_amount
 */
?>
<div id="directorist" class="atbd_wrapper directorist directory_wrapper single_area directorist-w-100">
    <div class="<?php Helper::directorist_container_fluid(); ?>">
        <div class="<?php Helper::directorist_row(); ?>">
            <div class="directorist-col-md-8 directorist-offset-md-2">
                <div class="directorist-payment-receipt">
                    <p class="directorist-payment-thanks-text"><?php esc_html_e( 'Thank you for your order!', 'directorist' ); ?></p>

                    <?php do_action( 'directorist_payment_receipt_before_order_details', $order ); ?>
                    
                    <div class="directorist-payment-table directorist-table-responsive directorist-mb-30">
                        <table class="directorist-table">
                            <thead>
                                <tr>
                                    <th colspan="2"><?php esc_html_e( 'Order Summary', 'directorist' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="directorist-payment-table__label"><?php esc_html_e( 'Order ID', 'directorist' ); ?></td>
                                    <td><?php echo esc_html( $order->get_id() ); ?></td>
                                </tr>
                                <tr>
                                    <td class="directorist-payment-table__label"><?php esc_html_e( 'Date', 'directorist' ); ?></td>
                                    <td><?php echo esc_html( $order->get_created_at()->format( get_option( 'date_format' ) ) ); ?></td>
                                </tr>
                                <tr>
                                    <td class="directorist-payment-table__label"><?php esc_html_e( 'Transaction ID', 'directorist' ); ?></td>
                                    <td><?php echo isset( $payment ) ? esc_html( $payment->get_transaction_id() ) : 'NIL'; ?></td>
                                </tr>
                                <?php if ( $payment ) { ?>
                                <tr>
                                    <td class="directorist-payment-table__label"><?php esc_html_e( 'Payment Method', 'directorist' ); ?></td>
                                    <td>
                                        <?php
                                            $gw_title = get_directorist_option( "{$payment->get_method()}_title" );
                                            echo ! empty( $gw_title ) ? esc_html( $gw_title ) : esc_html( $payment->get_method() );
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td class="directorist-payment-table__label"><?php esc_html_e( 'Payment Status', 'directorist' ); ?></td>
                                    <td>
                                        <?php echo esc_html( Status::get_i18n( $order->get_status() ) ); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="directorist-payment-table__label"><?php esc_html_e( 'Amount', 'directorist' ); ?></td>
                                    <td><?php echo wp_kses_post( directorist_price( $order->get_sub_total() ) ); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="directorist-payment-table directorist-table-responsive directorist-payment-summery-table">
                        <table class="directorist-table">
                            <thead>
                                <tr>
                                    <th colspan="2"><?php esc_html_e( 'Item summary', 'directorist' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php if ( ! empty( $order_items ) ): ?>
                            <?php foreach ( $order_items as $order_item ): ?>
                            <tr>
                                <td>
                                    <?php
                                    if ( ! empty( $order_item['title'] ) ) {
                                        printf( '<h5 class="directorist-payment-table__title">%s</h5>', esc_html( $order_item['title'] ) );
                                    }

                                    if ( ! empty( $order_item['desc'] ) ) { ?>
                                    <p> <?php echo esc_html( $order_item['desc'] ); ?> </p> 
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php echo wp_kses_post( directorist_price( $order_item['price'] ) ); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>

                            <?php if ( ! empty( $order->get_coupon_discount() ) ): ?>
                            <tr>
                                <td class="directorist-payment-table__title">
                                    <?php echo $order->get_coupon_discount_type() === OrderDiscountType::PERCENT ? sprintf( esc_html__('Discount ( %d%% )', 'directorist' ), $order->get_coupon_discount() ) : esc_html__( 'Discount', 'directorist' ); ?>
                                </td>
                                <td>
                                    <?php echo '-' . wp_kses_post( directorist_price( $discount_amount ) ); ?>
                                </td>
                            </tr>
                            <?php endif; ?>

                            <?php if ( ! empty( $order->get_tax_rate() ) ): ?>
                            <tr>
                                <td class="directorist-payment-table__title">
                                    <?php echo $order->get_tax_type() === OrderTaxType::PERCENT ? sprintf( esc_html__( 'Tax ( %d%% )', 'directorist' ), $order->get_tax_rate() ) : esc_html__( 'Tax', 'directorist' ); ?>
                                </td>
                                <td>
                                    <?php echo wp_kses_post( directorist_price( $tax_amount ) ) ?>
                                </td>
                            </tr>
                            <?php endif; ?>

                            <tr class="directorsit-payment-table-total">
                                <td class="directorist-payment-table__title"><?php esc_html_e( 'Total amount', 'directorist' ); ?></td>
                                <td>
                                    <?php 
                                        echo wp_kses_post( directorist_price( directorist_compute_order_total_amount(
                                            $order->get_sub_total(), 
                                            $order->get_tax_rate(), 
                                            $order->get_tax_type(), 
                                            $order->get_coupon_discount(), 
                                            $order->get_coupon_discount_type()
                                        ) ) );
                                    ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php
                $url  = apply_filters( 'atbdp_payment_receipt_button_link', ATBDP_Permalink::get_dashboard_page_link(), $order->get_id() );
                $text = apply_filters( 'atbdp_payment_receipt_button_text', __( 'View your listings', 'directorist' ), $order->get_id() );
                ?>
                <div class="directorist-text-center directorist-mt-30"><a href="<?php echo esc_url( $url ); ?>" class="directorist-btn directorist-btn-lg directorist-btn-view-listing"><?php echo esc_attr( $text ); ?></a></div>
                <?php
                    $is_retry                 = $payment && $order->get_amount() > 0 && in_array( $order->get_status(), [OrderStatus::PENDING, OrderStatus::FAILED] );
                    $is_allowed_retry_payment = apply_filters( 'directorist_payment_receipt_is_allowed_retry_payment', $is_retry, $payment, $order );

                if ( $is_allowed_retry_payment ) {
                    ?>
                        <div class="directorist-text-center directorist-mt-30">
                            <button id="directorist-payment-receipt-retry-payment" data-order-id="<?php echo esc_attr( $order->get_id() ); ?>" class="directorist-btn directorist-btn-lg directorist-btn-view-listing">Retry Payment</button>
                        </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>