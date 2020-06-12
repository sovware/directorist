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

		<?php if ( $listings->has_any_price_field() ) { ?>
			<div class="form-group ">
				<label class=""><?php _e('Price Range', 'directorist'); ?></label>
				<div class="price_ranges">
					<?php
					if ( $listings->has_price_field() ) {
						$price_field_data = $listings->price_field_data();
						?>

						<div class="range_single"><input type="text" name="price[0]" class="form-control" placeholder="<?php esc_attr_e('Min Price', 'directorist'); ?>" value="<?php echo esc_attr( $price_field_data['min_price_value'] ); ?>"></div>

						<div class="range_single"><input type="text" name="price[1]" class="form-control" placeholder="<?php esc_attr_e('Max Price', 'directorist'); ?>" value="<?php echo esc_attr( $price_field_data['max_price_value'] ); ?>"></div>

					<?php
					}

					if ( $listings->has_price_range_field() ) { ?>
							<div class="price-frequency">
								<label class="pf-btn">
									<?php $listings->the_price_range_input('bellow_economy');?><span><?php echo str_repeat($listings->c_symbol, 1); ?></span>
								</label>
								<label class="pf-btn">
									<?php $listings->the_price_range_input('economy');?><span><?php echo str_repeat($listings->c_symbol, 2); ?></span>
								</label>
								<label class="pf-btn">
									<?php $listings->the_price_range_input('moderate');?><span><?php echo str_repeat($listings->c_symbol, 3); ?></span>
								</label>
								</label>
								<label class="pf-btn">
									<?php $listings->the_price_range_input('skimming');?><span><?php echo str_repeat($listings->c_symbol, 4); ?></span>
								</label>
								</label>
							</div>
					<?php } ?>
				</div>
			</div>
		<?php
		}

		if ( $listings->has_rating_field() ) { 
		?>
			<div class="form-group">
				<label><?php _e('Filter by Ratings', 'directorist'); ?></label>
				<select name='search_by_rating' class="select-basic form-control">
				<?php
					foreach ( $listings->rating_field_data() as $option ) {
						printf('<option value="%s"%s>%s</option>', $option['value'], $option['selected'], $option['label']);
					}
					?>
				</select>
			</div>
		<?php
		}

		if ( $listings->has_radius_search_field() ) {
		?>
			<div class="form-group">
				<div class="atbdp-range-slider-wrapper">
					<span><?php _e('Radius Search', 'directorist'); ?></span>
					<div>
						<div id="atbdp-range-slider"></div>
					</div>
					<p class="atbd-current-value"></p>
				</div>
				<input type="hidden" class="atbdrs-value" name="miles" value="<?php echo $listings->default_radius_distance; ?>" />
			</div>
		<?php
		}

		if ( $listings->has_open_now_field() ) {
		?>
			<div class="form-group">
				<label><?php _e('Open Now', 'directorist'); ?></label>
				<div class="check-btn">
					<div class="btn-checkbox">
						<label>
							<input type="checkbox" name="open_now" value="open_now"<?php echo $listings->open_now_field_data(); ?>>
							<span><i class="fa fa-clock-o"></i><?php _e('Open Now', 'directorist'); ?> </span>
						</label>
					</div>
				</div>
			</div>

		<?php
		}
		if ( $listings->has_tag_field() && $listings->tag_field_data() ) {
		?>
			<div class="form-group ads-filter-tags">
				<label><?php echo !empty($listings->tag_label) ? $listings->tag_label : __('Tags', 'directorist'); ?></label>
				<div class="bads-tags">
					<?php
						$rand = rand();
						foreach ($listings->tag_field_data() as $term) {
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
		
		<?php
		}
			
		if (in_array('search_custom_fields', $listings->search_more_filters_fields)) {
		?>
			<div id="atbdp-custom-fields-search" class="atbdp-custom-fields-search">
				<?php do_action('wp_ajax_atbdp_custom_fields_search', isset($_GET['in_cat']) ? $_GET['in_cat'] : 0); ?>
			</div>
		<?php
		}

		if (in_array('search_website', $listings->search_more_filters_fields) || in_array('search_email', $listings->search_more_filters_fields) || in_array('search_phone', $listings->search_more_filters_fields) || in_array('search_address', $listings->search_more_filters_fields) || in_array('search_zip_code', $listings->search_more_filters_fields)) {
		?>
			<div class="form-group">
				<div class="bottom-inputs">
					<?php if (in_array('search_website', $listings->search_more_filters_fields)) { ?>
					<div>
						<input type="text" name="website" placeholder="<?php echo !empty($listings->website_label) ? $listings->website_label : __('Website', 'directorist'); ?>" value="<?php echo !empty($_GET['website']) ? $_GET['website'] : ''; ?>" class="form-control">
					</div>
					<?php
					}

					if (in_array('search_email', $listings->search_more_filters_fields)) {
					?>
						<div>
							<input type="text" name="email" placeholder="<?php echo !empty($listings->email_label) ? $listings->email_label : __('Email', 'directorist'); ?>" value="<?php echo !empty($_GET['email']) ? $_GET['email'] : ''; ?>" class="form-control">
						</div>
					<?php
					}

					if (in_array('search_phone', $listings->search_more_filters_fields)) {
					?>
						<div>
							<input type="text" name="phone" placeholder="<?php _e('Phone Number', 'directorist'); ?>" value="<?php echo !empty($_GET['phone']) ? $_GET['phone'] : ''; ?>" class="form-control">
						</div>
					<?php
					}

					if (in_array('search_fax', $listings->search_more_filters_fields)) { ?>
						<div>
							<input type="text" name="fax" placeholder="<?php echo !empty($listings->fax_label) ? $listings->fax_label : __('Fax', 'directorist'); ?>" value="<?php echo !empty($_GET['fax']) ? $_GET['fax'] : ''; ?>" class="form-control">
						</div>
					<?php
					}
					
					if (in_array('search_zip_code', $listings->search_more_filters_fields)) {
					?>
						<div>
							<input type="text" name="zip_code" placeholder="<?php echo !empty($listings->zip_label) ? $listings->zip_label : __('Zip/Post Code', 'directorist'); ?>" value="<?php echo !empty($_GET['zip_code']) ? $_GET['zip_code'] : ''; ?>" class="form-control">
						</div>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
		<div class="bdas-filter-actions">
			<?php if (in_array('reset_button', $listings->filters_buttons)) {?>
				<a href="" class="btn btn-outline btn-outline-primary btn-sm" id="atbdp_reset"><?php _e($listings->reset_filters_text, 'directorist'); ?></a>
			<?php
			}
			if (in_array('apply_button', $listings->filters_buttons)) { ?>
				<button type="submit" class="btn btn-primary btn-sm"><?php _e($listings->apply_filters_text, 'directorist'); ?></button>
			<?php } ?>
		</div>
	</form>
</div>