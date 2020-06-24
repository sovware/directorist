<div class="single_search_field atbdp_map_address_field">
	<div class="atbdp_get_address_field">
		<input type="text" name="address" id="address" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr($searchform->location_placeholder); ?>" autocomplete="off" class="form-control location-name"<?php echo esc_attr($searchform->loc_required_text); ?>>
		<span class="atbd_get_loc la la-crosshairs"></span>
	</div>
	<div class="address_result" style="display: none"></div>
	<input type="hidden" id="cityLat" name="cityLat" value="<?php echo esc_attr($cityLat); ?>" />
	<input type="hidden" id="cityLng" name="cityLng" value="<?php echo esc_attr($cityLng); ?>" />
</div>