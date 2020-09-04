<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="form-group">
	<div class="atbdp-range-slider-wrapper">
		<span><?php esc_html_e('Radius Search', 'directorist'); ?></span>
		<div>
			<div id="atbdp-range-slider"></div>
		</div>
		<p class="atbd-current-value"></p>
	</div>
	<input type="hidden" class="atbdrs-value" name="miles" value="<?php echo !empty( $_GET['miles'] ) ? $_GET['miles'] : esc_attr( $searchform->default_radius_distance ); ?>" />
</div>