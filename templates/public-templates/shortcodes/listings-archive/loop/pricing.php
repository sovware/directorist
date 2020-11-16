<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$id = get_the_ID();
$price = get_post_meta( $id, '_price', true );
$price_range = get_post_meta( $id, '_price_range', true );
$atbd_listing_pricing = get_post_meta( $id, '_atbd_listing_pricing', true );
?>

<div class="atbd_listing_meta">
	<?php
	if (!empty($price_range) && ('range' === $atbd_listing_pricing)) {
		echo atbdp_display_price_range($price_range);
	}
	else {
		echo apply_filters('atbdp_listing_card_price', atbdp_display_price($price, $listings->is_disable_price, $currency = null, $symbol = null, $c_position = null, $echo = false));
	}
	?>
</div>