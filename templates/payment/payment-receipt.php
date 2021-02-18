<?php
use \Directorist\Helper;
?>
<div id="directorist" class="atbd_wrapper directorist directory_wrapper single_area directorist-w-100">
    <div class="<?php Helper::directorist_container_fluid(); ?>">
        <div class="<?php Helper::directorist_row(); ?>">
            <div class="directorist-col-md-8 directorist-offset-md-2">
                <div class="atbd_payment_recipt">
                    <p class="atbd_thank_you"><?php _e( 'Thank you for your order!', 'directorist' ); ?></p>

                    <?php
                    // show the user instruction for banking gateway
                    if( isset( $o_metas['_payment_gateway'] ) && 'bank_transfer' == $o_metas['_payment_gateway'][0] && 'created' == $o_metas['_payment_status'][0] ) {
                        $ins = get_directorist_option('bank_transfer_instruction');
                        echo !empty($ins) ? '<p class="atbd_payment_instructions">'.ATBDP()->email->replace_in_content($ins, @$order_id, @$o_metas['_listing_id'][0]).'</p>' : '';
                    }
                    ?>

                    <div class="<?php Helper::directorist_row(); ?> atbd_payment_summary_wrapper">
                        <div class="<?php Helper::directorist_column(12); ?>">
                            <p class="atbd_payment_summary"><?php _e( 'Here is your order summary:', 'directorist' ); ?></p>
                        </div>
                        <div class="<?php Helper::directorist_column('md-6'); ?>">
                            <div class="table-responsive"><table class="table table-bordered">
                                <tr>
                                    <td><?php _e( 'ORDER', 'directorist' ); ?> #</td>
                                    <td><?php echo (!empty($order_id)) ? $order_id : ''; ?></td>
                                </tr>

                                <tr>
                                    <td><?php _e( 'Total Amount', 'directorist' ); ?></td>
                                    <td>
                                        <?php
                                        if( !empty( $o_metas['_amount'] ) ) {
                                            $amount =  $o_metas['_amount'][0] ;
                                            $amount = atbdp_format_payment_amount( $amount );
                                            $before = '';
                                            $after = '';
                                            ('after' == $c_position) ? $after = $symbol : $before = $symbol;
                                            echo $before . $amount . $after;
                                        }
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?php _e( 'Date', 'directorist' ); ?></td>
                                    <td>
                                        <?php echo !empty($order) ? get_the_time(get_option('date_format'), $order_id) : ''; ?>
                                    </td>
                                </tr>
                            </table>
                            </div>
                        </div>

                        <div class="<?php Helper::directorist_column('md-6'); ?>">
                            <div class="table-responsive"><table class="table table-bordered">
                                <tr>
                                    <td><?php _e( 'Payment Method', 'directorist' ); ?></td>
                                    <td>
                                        <?php
                                        $gateway = !empty($o_metas['_payment_gateway'][0]) ? $o_metas['_payment_gateway'][0] : 'unknown';
                                        if( 'free' == $gateway ) {
                                            _e( 'Free Listing', 'directorist' );
                                        } else {
                                            $gw_title = get_directorist_option("{$gateway}_title");
                                            echo ! empty( $gw_title ) ? $gw_title : $gateway;
                                        }
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?php _e( 'Payment Status', 'directorist' ); ?></td>
                                    <td>
                                        <?php
                                        $status = isset( $o_metas['_payment_status'] ) ? $o_metas['_payment_status'][0] : 'Invalid';
                                        echo atbdp_get_payment_status_i18n( $status );
                                        ?>
                                    </td>
                                </tr

                                ><tr>
                                    <td><?php _e( 'Transaction ID', 'directorist' ); ?></td>
                                    <td><?php echo isset( $o_metas['_transaction_id'] ) ? $o_metas['_transaction_id'][0] : 'NIL'; ?></td>
                                </tr>
                            </table>
                            </div>
                        </div>
                    </div>

                    <?php
                    /*Show orders item if we have some*/
                    if (!empty($order_items)){
                        echo '<p class="atbd_payment_summary directorist-mt-30">'.__( 'Ordered Item(s)', 'directorist' ).'</p>';
                        ?>
                        <div class="table-responsive"><table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th><?php _e( 'Name', 'directorist' ); ?></th>
                                    <th><?php printf(__('Price [%s]', 'directorist'), $currency); ?></th>
                                </tr>
                            </thead>
                            <?php
                            $total = 0;
                            foreach( $order_items as $order_item ) {  ?>
                                <tr>
                                    <td>
                                        <?php
                                        echo (!empty($order_item['title'])) ? '<h3>'.$order_item['title'].'</h3>' : '';
                                        if( !empty( $order_item['desc'] ) ) {echo $order_item['desc']; }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if( !empty( $order_item['price'] ) ){
                                            $price = $order_item['price'];
                                            echo $before.atbdp_format_payment_amount($order_item['price']).$after;
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
                                <td class="text-right atbdp-vertical-middle"><strong><?php printf( __( 'Subtotal', 'directorist' ), $currency ); ?></strong></td>
                                <td class="atbd_tottal">
                                    <strong><?php echo $before.atbdp_format_payment_amount($total).$after; ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right atbdp-vertical-middle"><strong><?php printf( __( 'Discount', 'directorist' ), $currency ); ?></strong></td>
                                <td class="">
                                    <?php echo $before . atbdp_format_payment_amount( $discount ) . $after ; ?>
                                </td>
                            </tr>
                        <?php } ?>
                            <tr>
                                <td class="text-right atbdp-vertical-middle"><strong><?php printf( __( 'Total amount', 'directorist' ), $currency ); ?></strong></td>
                                <td class="atbd_tottal">
                                    <strong><?php
                                    $grand_total = !empty( $discount ) ? atbdp_format_payment_amount( $total - $discount ) : atbdp_format_payment_amount( $total );
                                    echo $before . atbdp_format_payment_amount( $grand_total ) . $after ; ?></strong>
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