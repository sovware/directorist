<?php
/**
 * @author  wpWax
 * @since   7.0
 * @version 7.3.1
 */
use \Directorist\Helper;
?>

<div id="directorist" class="atbd_wrapper directorist directory_wrapper single_area directorist-w-100">
    <div class="<?php Helper::directorist_container_fluid(); ?>">
        <div class="<?php Helper::directorist_row(); ?>">
            <div class="directorist-col-md-8 directorist-offset-md-2">
                <div class="atbd_payment_recipt">
                    <p class="atbd_thank_you"><?php esc_html_e( 'Thank you for your order!', 'directorist' ); ?></p>

                    <?php
                    // show the user instruction for banking gateway
                    if( isset( $o_metas['_payment_gateway'] ) && 'bank_transfer' == $o_metas['_payment_gateway'][0] && 'created' == $o_metas['_payment_status'][0] ) {
                        $ins = get_directorist_option('bank_transfer_instruction');
                        $output = !empty($ins) ? '<p class="atbd_payment_instructions">'.ATBDP()->email->replace_in_content($ins, @$order_id, @$o_metas['_listing_id'][0]).'</p>' : '';
						echo wp_kses_post( $output );
                    }
                    ?>

                    <div class="<?php Helper::directorist_row(); ?> atbd_payment_summary_wrapper">
                        <div class="<?php Helper::directorist_column(12); ?>">
                            <p class="atbd_payment_summary"><?php esc_html_e( 'Here is your order summary:', 'directorist' ); ?></p>
                        </div>
                        <div class="<?php Helper::directorist_column('md-6'); ?>">
                            <div class="table-responsive"><table class="table table-bordered">
                                <tr>
                                    <td><?php esc_html_e( 'ORDER', 'directorist' ); ?> #</td>
                                    <td><?php echo (!empty($order_id)) ? esc_html( $order_id ) : ''; ?></td>
                                </tr>

                                <tr>
                                    <td><?php esc_html_e( 'Total Amount', 'directorist' ); ?></td>
                                    <td>
                                        <?php
                                        if( !empty( $o_metas['_amount'] ) ) {
                                            $amount =  $o_metas['_amount'][0] ;
                                            $before = '';
                                            $after = '';
                                            ('after' == $c_position) ? $after = $symbol : $before = $symbol;
                                            $output = $before . $amount . $after;
											echo wp_kses_post( $output );
                                        }
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?php esc_html_e( 'Date', 'directorist' ); ?></td>
                                    <td>
                                        <?php echo !empty($order) ? esc_html( get_the_time( get_option( 'date_format' ), $order_id ) ) : ''; ?>
                                    </td>
                                </tr>
                            </table>
                            </div>
                        </div>

                        <div class="<?php Helper::directorist_column('md-6'); ?>">
                            <div class="table-responsive"><table class="table table-bordered">
                                <tr>
                                    <td><?php esc_html_e( 'Payment Method', 'directorist' ); ?></td>
                                    <td>
                                        <?php
                                        $gateway = !empty($o_metas['_payment_gateway'][0]) ? $o_metas['_payment_gateway'][0] : 'unknown';
                                        if( 'free' == $gateway ) {
                                            esc_html_e( 'Free Listing', 'directorist' );
                                        } else {
                                            $gw_title = get_directorist_option("{$gateway}_title");
                                            echo ! empty( $gw_title ) ? esc_html( $gw_title ) : esc_html( $gateway );
                                        }
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?php esc_html_e( 'Payment Status', 'directorist' ); ?></td>
                                    <td>
                                        <?php
                                        $status = isset( $o_metas['_payment_status'] ) ? $o_metas['_payment_status'][0] : 'Invalid';
                                        echo esc_html( atbdp_get_payment_status_i18n( $status ) );
                                        ?>
                                    </td>
                                </tr

                                ><tr>
                                    <td><?php esc_html_e( 'Transaction ID', 'directorist' ); ?></td>
                                    <td><?php echo isset( $o_metas['_transaction_id'] ) ? esc_html( $o_metas['_transaction_id'][0] ) : 'NIL'; ?></td>
                                </tr>
                            </table>
                            </div>
                        </div>
                    </div>

                    <?php
                    /*Show orders item if we have some*/
                    if (!empty($order_items)){
                        echo '<p class="atbd_payment_summary directorist-mt-30">'.esc_html__( 'Ordered Item(s)', 'directorist' ).'</p>';
                        ?>
                        <div class="table-responsive directorist-payment-receipt-table"><table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th><?php esc_html_e( 'Name', 'directorist' ); ?></th>
                                    <th><?php printf( esc_html__('Price [%s]', 'directorist' ), esc_html( $currency ) ); ?></th>
                                </tr>
                            </thead>
                            <?php
                            $total = 0;
                            foreach( $order_items as $order_item ) {  ?>
                                <tr>
                                    <td>
                                        <?php
										if ( !empty( $order_item['title'] ) ) {
											printf( '<h3>%s</h3>', esc_html( $order_item['title'] ) );
										}

                                        if( !empty( $order_item['desc'] ) ) {
											echo esc_html( $order_item['desc'] );
										}
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if( !empty( $order_item['price'] ) ){
                                            $price = $order_item['price'];
                                            $output = $before.atbdp_format_payment_amount($order_item['price']).$after;
											echo wp_kses_post( $output );
                                            do_action('atbdp_payment_receipt_after_total_price', $o_metas);
                                            // increase the total amount
                                            $total += $order_item['price'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php }
                            $discount = ! empty( $o_metas['_discount'] ) ? $o_metas['_discount'][0] : 0;
                            if( !empty( $discount ) ) { ?>
                            <tr>
                                <td class="text-right atbdp-vertical-middle"><strong><?php esc_html_e( 'Subtotal', 'directorist' ); ?></strong></td>
                                <td class="atbd_tottal">
									<?php
									$output = $before.atbdp_format_payment_amount($total).$after;
									?>
                                    <strong><?php echo wp_kses_post( $output ); ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right atbdp-vertical-middle"><strong><?php esc_html_e( 'Discount', 'directorist' ); ?></strong></td>
                                <td class="">
                                    <?php
									$output = $before . atbdp_format_payment_amount( $discount ) . $after ;
									echo wp_kses_post( $output );
									?>
                                </td>
                            </tr>
                        <?php } ?>
                            <tr>
                                <td class="text-right atbdp-vertical-middle"><strong><?php esc_html_e( 'Total amount', 'directorist' ); ?></strong></td>
                                <td class="atbd_tottal">
									<?php
                                    $grand_total = !empty( $discount ) ? $total - $discount : $total;
                                    $output = $before . atbdp_format_payment_amount( $grand_total ) . $after ;
									?>
                                    <strong><?php echo wp_kses_post( $output ); ?></strong>
                                </td>
                            </tr>
                        </table></div>
                    <?php } ?>

                </div>
                <?php
                $url = apply_filters('atbdp_payment_receipt_button_link', ATBDP_Permalink::get_dashboard_page_link(), $order_id);
                $text = apply_filters('atbdp_payment_receipt_button_text', __( 'View your listings', 'directorist' ), $order_id);
                ?>
                <div class="directorist-text-center directorist-mt-30"><a href="<?php echo esc_url($url); ?>" class="directorist-btn directorist-btn-primary"><?php  echo esc_attr($text); ?></a></div>
            </div>
        </div>
    </div>
</div>