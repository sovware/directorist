<?php
$average = ATBDP()->review->get_average(get_the_ID());
$atbd_listing_pricing = !empty($atbd_listing_pricing) ? $atbd_listing_pricing : '';
?>
asddddddddddd
<div class="atbd_listing_meta">
	<span class="atbd_meta atbd_listing_rating"><?php echo $average;?><i class="<?php echo atbdp_icon_type();?>-star"></i></span>
	<?php
	if (!empty($display_price) && !empty($display_pricing_field)) {
		if (!empty($price_range) && ('range' === $atbd_listing_pricing)) {
			echo atbdp_display_price_range($price_range);
		}
		else {
			echo apply_filters('atbdp_listing_card_price', atbdp_display_price($price, $is_disable_price, $currency = null, $symbol = null, $c_position = null, $echo = false));
		}
	}
	do_action('atbdp_after_listing_price');
	?>
</div>