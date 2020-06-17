<?php $geodata = $listings->geolocation_field_data(); ?>
<div class="col-md-6 col-sm-12 col-lg-4">
	<div class="atbdp_map_address_field">
		<div class="atbdp_get_address_field">
			<input type="text" name="address" id="address" value="<?php echo esc_attr( $geodata['value'] ); ?>" placeholder="<?php echo esc_attr($geodata['placeholder'] ); ?>" autocomplete="off" class="form-control location-name">
			<?php echo $geodata['geo_loc']; ?>
		</div>
		<div class="address_result" style="display: none">
		</div>
		<input type="hidden" id="cityLat" name="cityLat" value="<?php echo esc_attr($geodata['cityLat']); ?>" />
		<input type="hidden" id="cityLng" name="cityLng" value="<?php echo esc_attr($geodata['cityLng']); ?>" />
	</div>
</div>