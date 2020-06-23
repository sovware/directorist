<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="ads-advanced">
	<form action="<?php atbdp_search_result_page_link(); ?>" class="atbd_ads-form">
		<div class="atbd_seach_fields_wrapper" style="border: none;">
			<div class="row atbdp-search-form">
				<?php if ($searchform->has_search_text_field) { ?>
					<div class="col-md-6 col-sm-12 col-lg-4">
						<?php $searchform->search_text_template();?>
					</div>
				<?php
				}

				if ($searchform->has_category_field) {
					?>
					<div class="col-md-6 col-sm-12 col-lg-4">
						<?php $searchform->category_template();?>
					</div>
				<?php
				}

				if ($searchform->location_source == 'address') {
				?>
					<div class="col-md-12 col-sm-12 col-lg-4">
						<?php $searchform->location_template();?>
					</div>
				<?php
				}

				if ($searchform->location_source == 'map') {
					$geodata = $listings->geolocation_field_data();
				?>
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
				<?php
				}
				/**
				 * @since 5.0
				 */
				do_action('atbdp_search_field_after_location');
				?>
			</div>
		</div>

		<?php
		$searchform->price_range_template();
		$searchform->rating_template();
		$searchform->radius_search_template();
		$searchform->open_now_template();
		$searchform->tag_template();
		$searchform->custom_fields_template();
		$searchform->information_template();
		$searchform->buttons_template();
		?>
	</form>
</div>