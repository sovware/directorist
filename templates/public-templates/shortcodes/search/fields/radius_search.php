<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$default_radius_distance = get_directorist_option('search_default_radius_distance', 0);
$value = !empty( $_GET['miles'] ) ? $_GET['miles'] : $default_radius_distance;

if ( !empty($data['label']) ): ?>
	<label><?php echo esc_html( $data['label'] ); ?></label>
<?php endif; ?>

<div class="atbdp-range-slider-wrapper">
	<div id="atbdp-range-slider"></div>
	<p class="atbd-current-value"></p>
</div>
<input type="hidden" class="atbdrs-value" name="miles" value="<?php echo esc_attr( $value ); ?>" />