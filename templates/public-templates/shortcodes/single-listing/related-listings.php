<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="<?php echo esc_attr( $class ); ?>">
	<div class="atbdp-related-listing-header">
		<h4><?php echo esc_html( $title ); ?></h4>
	</div>
	<div class="atbd_margin_fix">
		<div class="related__carousel">
			<?php $listings->setup_loop(); ?>
		</div>
	</div>
</div>