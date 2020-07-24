<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
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
	<input type="hidden" class="atbdrs-value" name="miles" value="<?php echo esc_attr( $searchform->default_radius_distance ); ?>" />
</div>