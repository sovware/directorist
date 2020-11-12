<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$default_distance = $data['default_radius_distance'];
$value = !empty( $_GET['miles'] ) ? $_GET['miles'] : $default_distance;

if ( !empty($data['label']) ): ?>
	<label><?php echo esc_html( $data['label'] ); ?></label>
<?php endif; ?>

<div class="atbdp-range-slider-wrapper">
	<div id="atbdp-range-slider"></div>
	<p class="atbd-current-value"></p>
	<input type="hidden" class="atbdrs-value" name="miles" value="<?php echo esc_attr( $value ); ?>" />
</div>