<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="containess-fluid">
	<div class="atbdp-related-listing-header">
		<h4><?php echo esc_html( $title ); ?></h4>
	</div>
	<div class="atbd_margin_fix">
		<div class="related__carousel">
			<?php $related_listings->setup_loop(); ?>
		</div>
	</div>
</div>