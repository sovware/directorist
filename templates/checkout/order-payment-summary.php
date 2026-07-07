<?php

defined( "ABSPATH" ) || exit;

/**
 * @var stdClass $order
 * @var float $discount_amount
 * @var float $discounted_subtotal
 * @var float $tax_amount
 */
?>
<input type="hidden" name="order_id" value="<?php echo esc_attr( $order->id ); ?>">

<?php directorist_template_render( 'checkout/checkout-order-sub-total', [ 'sub_total' => $order->sub_total ] ) ?>
<?php directorist_template_render( 'checkout/checkout-order-discount', [ 'rate' => $order->coupon_discount, 'type' => $order->coupon_discount_type, 'amount' => $discount_amount ] ) ?>
<?php directorist_template_render( 'checkout/checkout-order-tax', [ 'rate' => $order->tax_rate, 'type' => $order->tax_type, 'amount' => $tax_amount ] ) ?>

