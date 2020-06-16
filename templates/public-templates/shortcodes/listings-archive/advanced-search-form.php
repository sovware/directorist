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
				<?php if ($listings->has_search_field()) { ?>
					<div class="col-md-6 col-sm-12 col-lg-4">
						<div class="single_search_field search_query">
							<input class="form-control search_fields" type="text" name="q" placeholder="<?php echo esc_attr($listings->text_placeholder); ?>">
						</div>
					</div>
				<?php
				}

				if ($listings->has_category_field()) {
					?>
					<div class="col-md-6 col-sm-12 col-lg-4">
						<div class="single_search_field search_category">
							<select name="in_cat" id="cat-type" class="form-control directory_field bdas-category-search">
								<option><?php echo $listings->category_placeholder; ?></option>
								<?php echo $listings->categories_fields; ?>
							</select>
						</div>
					</div>
				<?php
				}

				if ($listings->location_field_type('listing_location')) {
				?>
					<div class="col-md-12 col-sm-12 col-lg-4">
						<div class="single_search_field search_location">
							<select name="in_loc" id="loc-type" class="form-control directory_field bdas-category-location">
								<option><?php echo $listings->location_placeholder; ?></option>
								<?php echo $listings->locations_fields; ?>
							</select>
						</div>
					</div>
				<?php
				}

				if (!$listings->location_field_type('listing_location')) {
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