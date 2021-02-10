<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$default_distance = $data['default_radius_distance'];
$value = !empty( $_GET['miles'] ) ? $_GET['miles'] : $default_distance;

$searchform->load_radius_search_scripts( $data );
?>

<div class="directorist-search-field">

	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>

	<div class="directorist-range-slider-wrap">
		<div id="atbdp-range-slider"></div>
		<p class="atbd-current-value"></p>
		<input type="hidden" class="atbdrs-value" name="miles" value="<?php echo esc_attr( $value ); ?>" />
	</div>
	
</div>