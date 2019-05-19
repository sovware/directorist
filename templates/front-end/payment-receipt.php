<?php
/*Template for displaying Checkout form*/
// prepare all the variables required by the payment receipt page.
$data = !empty($args['data']) ? $args['data'] : array();
extract($data);
$c_position      = get_directorist_option('payment_currency_position');
$currency        = atbdp_get_payment_currency();
$symbol          = atbdp_currency_symbol($currency);
$container_fluid = 'container-fluid';
?>
<div id="directorist" class="atbd_wrapper directorist directory_wrapper single_area">
    <div class="<?php echo apply_filters('atbdp_payment_receipt_container_fluid',$container_fluid) ?>">
        <div class="atbd_payment_recipt">
            <p class="atbd_thank_you"><?php _e( 'Thank you for your order!', ATBDP_TEXTDOMAIN ); ?></p>

            <?php
            // show the user instruction for banking gateway
            if( isset( $o_metas['_payment_gateway'] ) && 'bank_transfer' == $o_metas['_payment_gateway'][0] && 'created' == $o_metas['_payment_status'][0] ) {
                $ins = get_directorist_option('bank_transfer_instruction');
                echo !empty($ins) ? '<p class="atbd_payment_instructions">'.ATBDP()->email->replace_in_content($ins, @$order_id, @$o_metas['_listing_id'][0]).'</p>' : '';
            }
            ?>

            <div class="row atbd_payment_summary_wrapper">
                <div class="col-md-12">
                    <p class="atbd_payment_summary"><?php _e( 'Here is your order summery:', ATBDP_TEXTDOMAIN ); ?></p>
                </div>
                <div class="col-lg-6">
                    <div class="table-responsive"><table class="table table-bordered">
                        <tr>
                            <td><?php _e( 'ORDER', ATBDP_TEXTDOMAIN ); ?> #</td>
                            <td><?php echo (!empty($order_id)) ? $order_id : ''; ?></td>
                        </tr>

                        <tr>
                            <td><?php _e( 'Total Amount', ATBDP_TEXTDOMAIN ); ?></td>
                            <td>
                                <?php
                                if( !empty( $o_metas['_amount'] ) ) {
                                    $amount = atbdp_format_payment_amount( $o_metas['_amount'][0] );
                                    $amount = atbdp_payment_currency_filter( $amount );
                                    echo $amount;
                                }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td><?php _e( 'Date', ATBDP_TEXTDOMAIN ); ?></td>
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
                            <td><?php _e( 'Payment Method', ATBDP_TEXTDOMAIN ); ?></td>
                            <td>
                                <?php
                                $gateway = !empty($o_metas['_payment_gateway'][0]) ? $o_metas['_payment_gateway'][0] : 'unknown';
                                if( 'free' == $gateway ) {
                                    _e( 'Free Listing', ATBDP_TEXTDOMAIN );
                                } else {
                                    $gw_title = get_directorist_option("{$gateway}_title");
                                    echo ! empty( $gw_title ) ? $gw_title : $gateway;
                                }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td><?php _e( 'Payment Status', ATBDP_TEXTDOMAIN ); ?></td>
                            <td>
                                <?php
                                $status = isset( $o_metas['_payment_status'] ) ? $o_metas['_payment_status'][0] : 'Invalid';
                                echo atbdp_get_payment_status_i18n( $status );
                                ?>
                            </td>
                        </tr

                        ><tr>
                            <td><?php _e( 'Transaction ID', ATBDP_TEXTDOMAIN ); ?></td>
                            <td><?php echo isset( $o_metas['_transaction_id'] ) ? $o_metas['_transaction_id'][0] : 'NIL'; ?></td>
                        </tr>
                    </table>
                    </div>
                </div>
            </div>

            <?php
            /*Show orders item if we have some*/
            if (!empty($order_items)){
                echo '<p class="atbd_payment_summary">'.__( 'Ordered Item(s)', ATBDP_TEXTDOMAIN ).'</p>';
                ?>
                <div class="table-responsive"><table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th><?php _e( 'Name', ATBDP_TEXTDOMAIN ); ?></th>
                            <th><?php printf(__('Price [%s]', ATBDP_TEXTDOMAIN), $currency); ?></th>
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
                                    //display price with proper currency symbol place
                                    $before = ''; $after = '';
                                    ('after' == $c_position) ? $after = $symbol : $before = $symbol;
                                    echo $before.esc_html(atbdp_format_payment_amount($order_item['price'])).$after;
                                    // increase the total amount
                                    $total += $order_item['price'];
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="text-right atbdp-vertical-middle"><strong><?php printf( __( 'Total amount [%s]', ATBDP_TEXTDOMAIN ), $currency ); ?></strong></td>
                        <td class="atbd_tottal">
                            <strong><?php echo atbdp_format_payment_amount($total) ; ?></strong>
                        </td>
                    </tr>
                </table></div>
            <?php } ?>

        </div>
        <div class="text-center"><a href="<?php echo ATBDP_Permalink::get_dashboard_page_link(); ?>" class="btn btn-lg btn-primary"><?php _e( 'View your listings', ATBDP_TEXTDOMAIN ); ?></a></div>
    </div>
</div>
