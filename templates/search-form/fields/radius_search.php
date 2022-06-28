<?php

/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3
 */

if (!defined('ABSPATH')) exit;

$default_distance = $data['default_radius_distance'];
$value = !empty($_REQUEST['miles']) ? $_REQUEST['miles'] : $default_distance;
?>

<div class="directorist-search-field">

	<?php if (!empty($data['label'])) : ?>
		<label><?php echo esc_html($data['label']); ?></label>
	<?php endif; ?>

	<div class="directorist-range-slider-wrap">
		<div class="directorist-range-slider" data-slider-unit="<?php echo esc_attr($searchform->range_slider_unit($data)); ?>"></div>
		<p class="directorist-range-slider-current-value"></p>
		<input type="hidden" class="directorist-range-slider-value" name="miles" value="<?php echo esc_attr($value); ?>" />
	</div>

</div>