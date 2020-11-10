<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$address_map_link = get_directorist_option('address_map_link', 0);

if (!empty($address_map_link)) {
	$value = '<a target="google_map" href="https://www.google.de/maps/search/?' . esc_html($value) . '">' . esc_html($value) . '</a>';
}
?>
<div class="directorist-single-info directorist-single-info-address">
	<div class="directorist-single-info-label"><?php directorist_icon( $icon );?><?php echo esc_html( $data['label'] ); ?></div>
	<div class="directorist-single-info-value"><?php echo wp_kses_post( $value ); ?></div>
</div>