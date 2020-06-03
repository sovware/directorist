<?php
$average = ATBDP()->review->get_average(get_the_ID());
?>
<div class="atbd_listing_meta">
	<span class="atbd_meta atbd_listing_rating"><?php echo $average;?><i class="<?php echo atbdp_icon_type();?>-star"></i></span>
	<?php
	if ($listings->display_price && $listings->display_pricing_field) {
		if (!empty($listings->loop['price_range']) && ('range' === $listings->loop['atbd_listing_pricing'])) {
			echo atbdp_display_price_range($listings->loop['price_range']);
		}
		else {
			echo apply_filters('atbdp_listing_card_price', atbdp_display_price($listings->loop['price'], $listings->is_disable_price, $currency = null, $symbol = null, $c_position = null, $echo = false));
		}
	}
	do_action('atbdp_after_listing_price');
	?>
</div>