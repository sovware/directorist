<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.5.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$default_distance = $data['default_radius_distance'];
$value = !empty( $_GET['miles'] ) ? $_GET['miles'] : $default_distance;
?>

<div class="directorist-search-field">

	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>

	<div class="directorist-range-slider-wrap">
		<div class="directorist-range-slider" data-slider="<?php echo esc_attr( $searchform->range_slider_data( $data ) );?>"></div>
		<p class="directorist-range-slider-current-value"></p>
		<input type="hidden" class="atbdrs-value" name="miles" value="<?php echo esc_attr( $value ); ?>" />
	</div>

</div>