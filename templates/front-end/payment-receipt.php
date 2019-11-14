<?php
/*Template for displaying Checkout form*/
// prepare all the variables required by the payment receipt page.
$data = !empty($args['data']) ? $args['data'] : array();
extract($data);
$c_position      = get_directorist_option('payment_currency_position');
$currency        = atbdp_get_payment_currency();
$symbol          = atbdp_currency_symbol($currency);
$container_fluid = 'container-fluid';
$order_id = (!empty($order_id)) ? $order_id : '';
?>
<div id="directorist" class="atbd_wrapper directorist directory_wrapper single_area">
    <div class="<?php echo apply_filters('atbdp_payment_receipt_container_fluid',$container_fluid) ?>">
        <div class="atbd_payment_recipt">
            <p class="atbd_thank_you"><?php _e( 'Thank you for your order!', 'directorist' ); ?></p>

            <?php
            // show the user instruction for banking gateway
            if( isset( $o_metas['_payment_gateway'] ) && 'bank_transfer' == $o_metas['_payment_gateway'][0] && 'created' == $o_metas['_payment_status'][0] ) {
                $ins = get_directorist_option('bank_transfer_instruction');
                echo !empty($ins) ? '<p class="atbd_payment_instructions">'.ATBDP()->email->replace_in_content($ins, @$order_id, @$o_metas['_listing_id'][0]).'</p>' : '';
            }
            ?>

            <div class="row atbd_payment_summary_wrapper">
                <div class="col-md-12">
                    <p class="atbd_payment_summary"><?php _e( 'Here is your order summary:', 'directorist' ); ?></p>
                </div>
                <div class="col-lg-6">
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
                                <?php
                                echo !empty($order) ?
                                    date_i18n(
                                        get_option( 'date_format' ) . ' ' . get_option( 'time_format' )
                                        , strtotime( $order->post_date )
                                    )
                                    : '';

                                ?>
                            </td>
                        </tr>
                    </table>
                    </div>
                </div>

                <div class="col-lg-6">
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
                echo '<p class="atbd_payment_summary">'.__( 'Ordered Item(s)', 'directorist' ).'</p>';
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
                                    //display price with proper currency symbol place
                                    $before = ''; $after = '';
                                    ('after' == $c_position) ? $after = $symbol : $before = $symbol;
                                    echo $before.atbdp_format_payment_amount($order_item['price']).$after;
                                    // increase the total amount
                                    $total = $order_item['price'];
                                }
                                ?>
                            </td>
                        </tr>
                    <?php }
                    ?>
                    <tr>
                        <td class="text-right atbdp-vertical-middle"><strong><?php printf( __( 'Total amount [%s]', 'directorist' ), $currency ); ?></strong></td>
                        <td class="atbd_tottal">
                            <strong><?php echo atbdp_format_payment_amount($total) ; ?></strong>
                        </td>
                    </tr>
                </table></div>
            <?php } ?>

        </div>
        <?php
        $url = apply_filters('atbdp_payment_receipt_button_link', ATBDP_Permalink::get_dashboard_page_link(), $order_id);
        $text = apply_filters('atbdp_payment_receipt_button_text', __( 'View your listings', 'directorist' ));
        ?>
        <div class="atbd-text-center"><a href="<?php echo esc_url($url); ?>" class="btn btn-lg btn-primary"><?php  echo esc_attr($text); ?></a></div>
    </div>
</div>
