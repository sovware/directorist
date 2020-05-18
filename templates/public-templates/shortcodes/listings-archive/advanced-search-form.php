<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="ads-advanced">
	<form action="<?php atbdp_search_result_page_link(); ?>" class="atbd_ads-form">
		<div class="atbd_seach_fields_wrapper" <?php $listing->search_fields_wrapper_style(); ?>>
			<div class="row atbdp-search-form">
				<?php if ($listing->has_search_field()) { ?>
					<div class="col-md-6 col-sm-12 col-lg-4">
						<div class="single_search_field search_query">
							<input class="form-control search_fields" type="text" name="q" placeholder="<?php echo esc_attr($listing->text_placeholder); ?>">
						</div>
					</div>
				<?php
				}

				if ($listing->has_category_field()) {
					?>
					<div class="col-md-6 col-sm-12 col-lg-4">
						<div class="single_search_field search_category">
							<select name="in_cat" id="cat-type" class="form-control directory_field bdas-category-search">
								<option><?php echo $listing->category_placeholder; ?></option>
								<?php echo $listing->categories_fields; ?>
							</select>
						</div>
					</div>
				<?php
				}

				if ($listing->location_field_type('listing_location')) {
				?>
					<div class="col-md-12 col-sm-12 col-lg-4">
						<div class="single_search_field search_location">
							<select name="in_loc" id="loc-type" class="form-control directory_field bdas-category-location">
								<option><?php echo $listing->location_placeholder; ?></option>
								<?php echo $listing->locations_fields; ?>
							</select>
						</div>
					</div>
				<?php
				}

				if (!$listing->location_field_type('listing_location')) {
					$geodata = $listing->geolocation_field_data();
				?>
					<div class="col-md-6 col-sm-12 col-lg-4">
						<div class="atbdp_map_address_field">
							<div class="atbdp_get_address_field">
								<input type="text" name="address" id="address" value="<?php echo $geodata['value']; ?>" placeholder="<?php echo $geodata['placeholder']; ?>" autocomplete="off" class="form-control location-name">
								<?php echo $geodata['geo_loc']; ?>
							</div>
							<div class="address_result" style="display: none">
							</div>
							<input type="hidden" id="cityLat" name="cityLat" value="<?php echo $geodata['cityLat']; ?>" />
							<input type="hidden" id="cityLng" name="cityLng" value="<?php echo $geodata['cityLng']; ?>" />
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

		<!-- has_any_price_field() -->
		<?php if ( $listing->has_any_price_field() ) { ?>
			<div class="form-group ">
				<label class=""><?php _e('Price Range', 'directorist'); ?></label>
				<div class="price_ranges">
					<?php
					if ( $listing->has_price_field() ) {
						$price_field_data = $listing->price_field_data();
						?>

						<div class="range_single"><input type="text" name="price[0]" class="form-control" placeholder="<?php _e('Min Price', 'directorist'); ?>" value="<?php echo $price_field_data['min_price_value']; ?>"></div>

						<div class="range_single"><input type="text" name="price[1]" class="form-control" placeholder="<?php _e('Max Price', 'directorist'); ?>" value="<?php echo $price_field_data['max_price_value']; ?>"></div>

					<?php
					}

					if ( $listing->has_price_range_field() ) {
						$price_range_field = $listing->price_range_field_data();
						$c_symbol = $listing->c_symbol;
						?>
'										<div class="price-frequency">
								<label class="pf-btn">
									<input type="radio" name="price_range" value="bellow_economy"<?php echo $price_range_field['bellow_economy_value']; ?>>
									<span><?php echo $c_symbol; ?></span>
								</label>
								<label class="pf-btn">
									<input type="radio" name="price_range" value="economy" <?php echo $price_range_field['economy_value']; ?>>
									<span><?php echo $c_symbol, $c_symbol; ?></span>
								</label>
								<label class="pf-btn">
									<input type="radio" name="price_range" value="moderate" <?php echo $price_range_field['moderate_value']; ?>>
									<span><?php echo $c_symbol, $c_symbol, $c_symbol; ?></span>
								</label>
								<label class="pf-btn">
									<input type="radio" name="price_range" value="skimming" <?php echo $price_range_field['skimming_value']; ?>>
									<span><?php echo $c_symbol, $c_symbol, $c_symbol, $c_symbol; ?></span>
								</label>
							</div>'
					<?php } ?>
				</div>
			</div>
		<!-- ends: .form-group -->
		<?php
		}

		if ( $listing->has_rating_field() ) { 
		?>
			<div class="form-group">
				<label><?php _e('Filter by Ratings', 'directorist'); ?></label>
				<select name='search_by_rating' class="select-basic form-control">
				<?php
					foreach ( $listing->rating_field_data() as $option ) {
						printf('<option value="%s"%s>%s</option>', $option['value'], $option['selected'], $option['label']);
					}
					?>
				</select>
			</div>
			<!-- ends: .form-group -->
		<?php
		}

		if ( $listing->has_radius_search_field() ) {
		?>
			<!--range slider-->
			<div class="form-group">
				<div class="atbdp-range-slider-wrapper">
					<span><?php _e('Radius Search', 'directorist'); ?></span>
					<div>
						<div id="atbdp-range-slider"></div>
					</div>
					<p class="atbd-current-value"></p>
				</div>
				<input type="hidden" class="atbdrs-value" name="miles" value="<?php echo $listing->default_radius_distance; ?>" />
			</div>
		<?php
		}

		if ( $listing->has_open_now_field() ) {
		?>
			<div class="form-group">
				<label><?php _e('Open Now', 'directorist'); ?></label>
				<div class="check-btn">
					<div class="btn-checkbox">
						<label>
							<input type="checkbox" name="open_now" value="open_now"<?php echo $listing->open_now_field_data(); ?>>
							<span><i class="fa fa-clock-o"></i><?php _e('Open Now', 'directorist'); ?> </span>
						</label>
					</div>
				</div>
			</div>
		<!-- ends: .form-group -->
		<?php
		}
		if ( $listing->has_tag_field() && $listing->tag_field_data() ) {
		?>
			<div class="form-group ads-filter-tags">
				<label><?php echo !empty($listing->tag_label) ? $listing->tag_label : __('Tags', 'directorist'); ?></label>
				<div class="bads-tags">
					<?php
						$rand = rand();
						foreach ($listing->tag_field_data() as $term) {
						?>
					<div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
						<input type="checkbox" class="custom-control-input" name="in_tag[]" value="<?php echo $term->term_id; ?>" id="<?php echo $rand . $term->term_id; ?>" <?php if (!empty($_GET['in_tag']) && in_array($term->term_id, $_GET['in_tag'])) {echo "checked";} ?>>
						<span class="check--select"></span>
						<label for="<?php echo $rand . $term->term_id; ?>" class="custom-control-label"><?php echo $term->name; ?></label>
					</div>
					<?php } ?>
				</div>
				<a href="#" class="more-less ad"><?php _e('Show More', 'directorist'); ?></a>
			</div>
		<!-- ends: .form-control -->
		
		<?php
		}
			
		if (in_array('search_custom_fields', $listing->search_more_filters_fields)) {
		?>
			<div id="atbdp-custom-fields-search" class="atbdp-custom-fields-search">
				<?php do_action('wp_ajax_atbdp_custom_fields_search', isset($_GET['in_cat']) ? $_GET['in_cat'] : 0); ?>
			</div>
		<?php
		}

		if (in_array('search_website', $listing->search_more_filters_fields) || in_array('search_email', $listing->search_more_filters_fields) || in_array('search_phone', $listing->search_more_filters_fields) || in_array('search_address', $listing->search_more_filters_fields) || in_array('search_zip_code', $listing->search_more_filters_fields)) {
		?>
			<div class="form-group">
				<div class="bottom-inputs">
					<?php if (in_array('search_website', $listing->search_more_filters_fields)) { ?>
					<div>
						<input type="text" name="website" placeholder="<?php echo !empty($listing->website_label) ? $listing->website_label : __('Website', 'directorist'); ?>" value="<?php echo !empty($_GET['website']) ? $_GET['website'] : ''; ?>" class="form-control">
					</div>
					<?php
					}

					if (in_array('search_email', $listing->search_more_filters_fields)) {
					?>
						<div>
							<input type="text" name="email" placeholder="<?php echo !empty($listing->email_label) ? $listing->email_label : __('Email', 'directorist'); ?>" value="<?php echo !empty($_GET['email']) ? $_GET['email'] : ''; ?>" class="form-control">
						</div>
					<?php
					}

					if (in_array('search_phone', $listing->search_more_filters_fields)) {
					?>
						<div>
							<input type="text" name="phone" placeholder="<?php _e('Phone Number', 'directorist'); ?>" value="<?php echo !empty($_GET['phone']) ? $_GET['phone'] : ''; ?>" class="form-control">
						</div>
					<?php
					}

					if (in_array('search_fax', $listing->search_more_filters_fields)) { ?>
						<div>
							<input type="text" name="fax" placeholder="<?php echo !empty($listing->fax_label) ? $listing->fax_label : __('Fax', 'directorist'); ?>" value="<?php echo !empty($_GET['fax']) ? $_GET['fax'] : ''; ?>" class="form-control">
						</div>
					<?php
					}
					
					if (in_array('search_zip_code', $listing->search_more_filters_fields)) {
					?>
						<div>
							<input type="text" name="zip_code" placeholder="<?php echo !empty($listing->zip_label) ? $listing->zip_label : __('Zip/Post Code', 'directorist'); ?>" value="<?php echo !empty($_GET['zip_code']) ? $_GET['zip_code'] : ''; ?>" class="form-control">
						</div>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
		<div class="bdas-filter-actions">
			<?php if (in_array('reset_button', $listing->filters_button)) {?>
				<a href="" class="btn btn-outline btn-outline-primary btn-sm" id="atbdp_reset"><?php _e($listing->reset_filters_text, 'directorist'); ?></a>
			<?php
			}
			if (in_array('apply_button', $listing->filters_button)) { ?>
				<button type="submit" class="btn btn-primary btn-sm"><?php _e($listing->apply_filters_text, 'directorist'); ?></button>
			<?php } ?>
		</div>
		<!-- ends: .bdas-filter-actions -->
	</form>
</div>