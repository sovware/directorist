<?php

defined( "ABSPATH" ) || exit;

/**
 * @var stdClass $order
 */
?>
<input type="hidden" name="order_id" value="<?php echo esc_attr( $order->id ); ?>">

<?php directorist_template_render( 'checkout/checkout-order-sub-total', [ 'sub_total' => $order->sub_total ] ) ?>
<?php directorist_template_render( 'checkout/checkout-order-tax', [ 'tax_rate' => $order->tax_rate, 'tax_type' => $order->tax_type, 'amount' => $order->sub_total] ) ?>

