<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$location_source = get_directorist_option('search_location_address', 'listing_location');

if ( $location_source == 'listing_location' ) { ?>
	<div class="single_search_field search_location">
		<select name="in_loc" id="<?php echo esc_attr($searchform->location_id); ?>" class="<?php echo esc_attr($searchform->location_class); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>
			<option value=""><?php echo esc_html($data['placeholder']); ?></option>
			<?php echo $searchform->locations_fields; ?>
		</select>
	</div>
	<?php
}

elseif ( $location_source == 'map_api' ) {
	$cityLat = isset( $_GET['cityLat'] ) ? $_GET['cityLat'] : '';
	$cityLng = isset( $_GET['cityLng'] ) ? $_GET['cityLng'] : '';
	$value   = isset( $_GET['address'] ) ? $_GET['address'] : '';

	$searchform->load_map_scripts();
	?>
	<div class="single_search_field atbdp_map_address_field">
		<div class="atbdp_get_address_field">
			<input type="text" name="address" id="address" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr($data['placeholder']); ?>" autocomplete="off" class="form-control location-name" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>
			<span class="atbd_get_loc la la-crosshairs"></span>
		</div>
		<div class="address_result" style="display: none"></div>
		<input type="hidden" id="cityLat" name="cityLat" value="<?php echo esc_attr($cityLat); ?>" />
		<input type="hidden" id="cityLng" name="cityLng" value="<?php echo esc_attr($cityLng); ?>" />
	</div>
	<?php
}