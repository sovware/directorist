<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.4
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$location_source = !empty($data['location_source']) && $data['location_source'] == 'from_map_api' ? 'map' : 'listing';

if ( $location_source == 'listing' ) { ?>

	<div class="directorist-search-field">
		<div class="directorist-select directorist-search-location">
			<select name="in_loc" id="<?php echo esc_attr($searchform->location_id); ?>" class="<?php echo esc_attr($searchform->location_class); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true">
				<option value=""><?php echo esc_html($data['placeholder']); ?></option>
				<?php echo $searchform->locations_fields; ?>
			</select>
		</div>
	</div>

	<?php
}

elseif ( $location_source == 'map' ) {
	$cityLat = isset( $_GET['cityLat'] ) ? $_GET['cityLat'] : '';
	$cityLng = isset( $_GET['cityLng'] ) ? $_GET['cityLng'] : '';
	$value   = isset( $_GET['address'] ) ? $_GET['address'] : '';

	$searchform->load_map_scripts();
	?>

	<div class="directorist-search-field directorist-form-group directorist-icon-left">
		<span class="directorist-input-icon directorist-filter-location-icon"><span class="la la-crosshairs"></span></span>
		<input type="text" name="address" id="address" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr($data['placeholder']); ?>" autocomplete="off" class="directorist-form-element location-name" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>

		<div class="address_result" style="display: none"></div>
		<input type="hidden" id="cityLat" name="cityLat" value="<?php echo esc_attr($cityLat); ?>" />
		<input type="hidden" id="cityLng" name="cityLng" value="<?php echo esc_attr($cityLng); ?>" />
	</div>

	<?php
}