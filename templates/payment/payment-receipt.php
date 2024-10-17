<?php
/**
 * @author  wpWax
 * @since   7.0
 * @version 7.7.0
 */
use \Directorist\Helper;
?>

<div id="directorist" class="atbd_wrapper directorist directory_wrapper single_area directorist-w-100">
    <div class="<?php Helper::directorist_container_fluid(); ?>">
        <div class="<?php Helper::directorist_row(); ?>">
            <div class="directorist-col-md-8 directorist-offset-md-2">
                <div class="directorist-payment-receipt">
                    <p class="directorist-payment-thanks-text"><?php esc_html_e( 'Thank you for your order!', 'directorist' ); ?></p>
                    <?php
                    // show the user instruction for banking gateway
                    if( isset( $o_metas['_payment_gateway'] ) && 'bank_transfer' == $o_metas['_payment_gateway'][0] && 'created' == $o_metas['_payment_status'][0] ) {
                        $ins = get_directorist_option('bank_transfer_instruction');
                        $output = !empty($ins) ? '<p class="directorist-payment-instructions">'.ATBDP()->email->replace_in_content($ins, @$order_id, @$o_metas['_listing_id'][0]).'</p>' : '';
						echo wp_kses_post( $output );
                    }
                    ?>

                    <div class="directorist-payment-table directorist-table-responsive directorist-mb-30">
                        <table class="directorist-table">
                            <thead>
                                <tr>
                                    <th colspan="2">Order Summery</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="directorist-payment-table__label"><?php esc_html_e( 'Order ID', 'directorist' ); ?></td>
                                    <td><?php echo (!empty($order_id)) ? esc_html( $order_id ) : ''; ?></td>
                                </tr>
                                <tr>
                                    <td class="directorist-payment-table__label"><?php esc_html_e( 'Date', 'directorist' ); ?></td>
                                    <td><?php echo !empty($order) ? esc_html( get_the_time( get_option( 'date_format' ), $order_id ) ) : ''; ?></td>
                                </tr>
                                <tr>
                                    <td class="directorist-payment-table__label"><?php esc_html_e( 'Transaction ID', 'directorist' ); ?></td>
                                    <td><?php echo isset( $o_metas['_transaction_id'] ) ? esc_html( $o_metas['_transaction_id'][0] ) : 'NIL'; ?></td>
                                </tr>
                                <tr>
                                    <td class="directorist-payment-table__label"><?php esc_html_e( 'Payment Method', 'directorist' ); ?></td>
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
                                    <td class="directorist-payment-table__label"><?php esc_html_e( 'Payment Status', 'directorist' ); ?></td>
                                    <td>
                                        <?php
                                            $status = isset( $o_metas['_payment_status'] ) ? $o_metas['_payment_status'][0] : 'Invalid';
                                            echo esc_html( atbdp_get_payment_status_i18n( $status ) );
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="directorist-payment-table__label"><?php esc_html_e( 'Amount', 'directorist' ); ?></td>
                                    <td>
                                    <?php
                                        if( !empty( $o_metas['_amount'] ) ) {
                                            $amount =  $o_metas['_amount'][0] ;
                                            $amount = atbdp_format_payment_amount( $amount );
                                            $before = '';
                                            $after = '';
                                            ('after' == $c_position) ? $after = $symbol : $before = $symbol;
                                            $output = $before . $amount . $after;
											echo wp_kses_post( $output );
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    

                    <?php
                    /*Show orders item if we have some*/
                    if (!empty($order_items)){ ?>
                        <div class="directorist-payment-table directorist-table-responsive directorist-payment-summery-table">
                            <table class="directorist-table">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php esc_html_e( 'Item summary', 'directorist' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $total = 0;
                                foreach( $order_items as $order_item ) {  ?>
                                    <tr>
                                        <td>

                                            <?php
                                            if ( !empty( $order_item['title'] ) ) {
                                                printf( '<h5 class="directorist-payment-table__title">%s</h5>', esc_html( $order_item['title'] ) );
                                            }

                                            if( !empty( $order_item['desc'] ) ) { ?>
                                                <p> <?php echo esc_html( $order_item['desc'] ); ?> </p> 
                                            <?php } ?>

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
                                if( !empty( $discount ) ) { ?>
                                <tr>
                                    <td class="directorist-payment-table__title"><?php esc_html_e( 'Subtotal', 'directorist' ); ?></td>
                                    <td>
                                        <?php
                                        $output = $before.atbdp_format_payment_amount($total).$after;
                                        ?>
                                        <?php echo wp_kses_post( $output ); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="directorist-payment-table__title"><?php esc_html_e( 'Discount', 'directorist' ); ?></td>
                                    <td>
                                        <?php
                                        $output = $before . atbdp_format_payment_amount( $discount ) . $after ;
                                        echo wp_kses_post( $output );
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                                <tr class="directorsit-payment-table-total">
                                    <td class="directorist-payment-table__title"><?php esc_html_e( 'Total amount', 'directorist' ); ?></td>
                                    <td>
                                        <?php
                                        $grand_total = !empty( $discount ) ? atbdp_format_payment_amount( $total - $discount ) : atbdp_format_payment_amount( $total );
                                        $output = $before . atbdp_format_payment_amount( $grand_total ) . $after ;
                                        ?>
                                        <?php echo wp_kses_post( $output ); ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
                <?php
                $url = apply_filters('atbdp_payment_receipt_button_link', ATBDP_Permalink::get_dashboard_page_link(), $order_id);
                $text = apply_filters('atbdp_payment_receipt_button_text', __( 'View your listings', 'directorist' ), $order_id);
                ?>
                <div class="directorist-text-center directorist-mt-30"><a href="<?php echo esc_url($url); ?>" class="directorist-btn directorist-btn-lg directorist-btn-view-listing"><?php  echo esc_attr($text); ?></a></div>
            </div>
        </div>
    </div>
</div>