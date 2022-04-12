<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.2.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$location_source = !empty($data['location_source']) && $data['location_source'] == 'from_map_api' ? 'map' : 'listing';

if ( $location_source == 'listing' ) {
	$selected_item = $searchform::get_selected_location_option_data();
	?>

	<div class="directorist-search-field">
		<div class="directorist-select directorist-search-location">
			<select name="in_loc" class="<?php echo esc_attr($searchform->location_class); ?>" data-placeholder="<?php echo esc_attr($data['placeholder']); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true" data-selected-id="<?php echo $selected_item['id'] ?>" data-selected-label="<?php echo $selected_item['label'] ?>">
				<?php
				echo '<option value="">' . __( 'Select Location', 'directorist' ) . '</option>';
				echo $searchform->locations_fields;
				?>
			</select>
		</div>
	</div>

	<?php
}

elseif ( $location_source == 'map' ) {
	$cityLat = isset( $_GET['cityLat'] ) ? wp_unslash( $_GET['cityLat'] ) : '';
	$cityLng = isset( $_GET['cityLng'] ) ? wp_unslash( $_GET['cityLng'] ) : '';
	$value   = isset( $_GET['address'] ) ? wp_unslash( $_GET['address'] ) : '';

	$searchform->load_map_scripts();
	?>

	<div class="directorist-search-field directorist-form-group directorist-icon-left">
		<span class="directorist-input-icon directorist-filter-location-icon"><span class="la la-crosshairs"></span></span>
		<input type="text" name="address" id="addressId" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr($data['placeholder']); ?>" autocomplete="off" class="directorist-form-element directorist-location-js location-name" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>

		<div class="address_result location-names" style="display: none"></div>
		<input type="hidden" id="cityLat" name="cityLat" value="<?php echo esc_attr($cityLat); ?>" />
		<input type="hidden" id="cityLng" name="cityLng" value="<?php echo esc_attr($cityLng); ?>" />
	</div>

	<?php
}