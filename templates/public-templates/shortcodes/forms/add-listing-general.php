<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */

$forms = Directorist_Listing_Forms::instance();
?>
<div class="atbd_content_module atbd_general_information_module">
	<div class="atbd_content_module_title_area">
		<div class="atbd_area_title">
			<h4><?php esc_html_e('General information', 'directorist') ?></h4>
		</div>
	</div>
	<div class="atbdb_content_module_contents">
		<?php if (empty($display_title_for)) { ?>
			<div class="form-group" id="atbdp_listing_title">
				<label for="listing_title"><?php
					esc_html_e($title . ':', 'directorist');
					if ($require_title) {
						echo '<span class="atbdp_make_str_red"> *</span>';
					} ?></label>
				<input type="text" name="listing_title" id="listing_title"
					   value="<?php echo !empty($listing->post_title) ? esc_attr($listing->post_title) : ''; ?>"
					   class="form-control directory_field"
					   placeholder="<?php echo __('Enter a title', 'directorist'); ?>"/>
			</div>
		<?php } ?>
		<?php if (empty($display_desc_for)) { ?>
			<div class="form-group" id="atbdp_listing_content">
				<label for="listing_content"><?php
					esc_html_e($long_details . ':', 'directorist');
					if ($require_long_details) {
						echo '<span class="atbdp_make_str_red"> *</span>';
					} ?></label>
				<?php wp_editor(
					!empty($listing->post_content) ? wp_kses_post($listing->post_content) : '',
					'listing_content',
					apply_filters('atbdp_add_listing_wp_editor_settings', array(
						'media_buttons' => false,
						'quicktags' => true,
						'editor_height' => 200
					))); ?>
			</div>
		<?php } ?>
		<?php if (!empty($display_tagline_field) && empty($display_tagline_for)) { ?>
			<div class="form-group" id="atbdp_excerpt">
				<label for="atbdp_excerpt"><?php
					esc_html_e($tagline_label . ':', 'directorist');
					?></label>
				<input type="text" name="tagline"
					   id="has_tagline"
					   value="<?php echo !empty($tagline) ? esc_attr($tagline) : ''; ?>"
					   class="form-control directory_field"
					   placeholder="<?php echo esc_attr($tagline_placeholder); ?>"/>
			</div>
		<?php } ?>
		<?php
		//data for average price range
		$plan_average_price = true;
		if (is_fee_manager_active()) {
			$plan_average_price = is_plan_allowed_average_price_range($fm_plan);
		}
		$plan_price = true;
		if (is_fee_manager_active()) {
			$plan_price = is_plan_allowed_price($fm_plan);
		}
		$price_range = !empty($price_range) ? $price_range : '';
		$atbd_listing_pricing = !empty($atbd_listing_pricing) ? $atbd_listing_pricing : '';

		if (empty($display_price_for && $display_price_range_for) && !empty($display_pricing_field || $display_price_range_field) && ($plan_average_price || $plan_price)) {
			?>
			<div class="form-group" id="atbd_pricing">
				<input type="hidden" id="atbd_listing_pricing"
					   value="<?php echo $atbd_listing_pricing ?>">
				<label><?php
					esc_html_e($pricing_label . ':', 'directorist');
					?></label>
				<div class="atbd_pricing_options">
					<?php
					if ($plan_price && empty($display_price_for) && !empty($display_pricing_field)) {
						?>
						<label for="price_selected" data-option="price">
							<input type="checkbox" id="price_selected" value="price"
								   name="atbd_listing_pricing"
								<?php echo ('price' === $atbd_listing_pricing) ? 'checked' : (empty($p_id) ? 'checked' : ''); ?>>
							<?php
							/*Translator: % is the name of the currency such eg. USD etc.*/
							printf(esc_html__('%s [%s]%s', 'directorist'), $price_label, $currency, $require_price ? '<span class="atbdp_make_str_red">*</span>' : ''); ?>
						</label>
						<?php
					}
					if ($plan_average_price && empty($display_price_range_for) && !empty($display_price_range_field)) {
						if ($plan_price && empty($display_price_for) && !empty($display_pricing_field)) {
							printf('<span>%s</span>', __('Or', 'directorist'));
						}
						?>
						<label for="price_range_selected" data-option="price_range">
							<input type="checkbox" id="price_range_selected"
								   value="range"
								   name="atbd_listing_pricing" <?php echo ('range' === $atbd_listing_pricing) ? 'checked' : ''; ?>>
							<?php
							echo esc_attr($price_range_label);
							echo $require_price_range ? '<span class="atbdp_make_str_red">*</span>' : ''; ?>
							<!--<p id='price_range_option'><?php /*echo __('Price Range', 'directorist'); */ ?></p></label>-->
						</label>
						<?php
					}
					?>

					<small><?php _e('(Optional - Uncheck to hide pricing for this listing)', 'directorist') ?></small>
				</div>

				<?php
				if ($plan_price && empty($display_price_for) && !empty($display_pricing_field)) {
					/**
					 * @since 6.2.1
					 */
					do_action('atbdp_add_listing_before_price_field', $p_id);
					?>
					<input type="number" <?php echo !empty($allow_decimal) ? 'step="any"' : ''; ?>
						   id="price" name="price"
						   value="<?php echo !empty($price) ? esc_attr($price) : ''; ?>"
						   class="form-control directory_field"
						   placeholder="<?php echo esc_attr($price_placeholder); ?>"/>

				<?php }
				if ($plan_average_price && empty($display_price_range_for) && !empty($display_price_range_field)) {
					$c_symbol = atbdp_currency_symbol($currency);
					?>
					<select class="form-control directory_field" id="price_range"
							name="price_range">
						<option value=""><?php echo esc_attr($price_range_placeholder); ?></option>
						<option value="skimming" <?php selected($price_range, 'skimming'); ?>>
							<?php echo __('Ultra High ', 'directorist') . '(' . $c_symbol, $c_symbol, $c_symbol, $c_symbol . ')'; ?>
						</option>
						<option value="moderate" <?php selected($price_range, 'moderate'); ?>>
							<?php echo __('Expensive ', 'directorist') . '(' . $c_symbol, $c_symbol, $c_symbol . ')'; ?>
						</option>
						<option value="economy" <?php selected($price_range, 'economy'); ?>>
							<?php echo __('Moderate ', 'directorist') . '(' . $c_symbol, $c_symbol . ')'; ?>
						</option>
						<option value="bellow_economy" <?php selected($price_range, 'bellow_economy'); ?>>
							<?php echo __('Cheap ', 'directorist') . '(' . $c_symbol . ')'; ?>
						</option>
					</select>
				<?php }

				/**
				 * @since 4.7.1
				 * It fires after the price field
				 */
				do_action('atbdp_add_listing_after_price_field', $p_id);
				?>
			</div>

		<?php }
		if (!empty($display_views_count) && empty($display_views_count_for)) { ?>
			<div class="form-group">
				<label for="atbdp_views_count"><?php
					esc_html_e($views_count_label . ':', 'directorist'); ?></label>

				<input type="text" id="views_Count" name="atbdp_post_views_count"
					   value="<?php echo !empty($atbdp_post_views_count) ? esc_attr($atbdp_post_views_count) : ''; ?>"
					   class="form-control directory_field"/>
			</div>
		<?php }
		/**
		 * @since 4.7.1
		 * It fires after the price field
		 */
		do_action('atbdp_add_listing_after_price', $p_id);
		?>
		<?php if (!empty($display_excerpt_field) && empty($display_short_desc_for)) { ?>
			<div class="form-group" id="atbdp_excerpt_area">
				<label for="atbdp_excerpt"><?php
					esc_html_e($excerpt_label . ':', 'directorist');
					echo $require_excerpt ? '<span class="atbdp_make_str_red">*</span>' : ''; ?></label>
				<!--@todo; later let user decide if he wants to show tinymce or normal textarea-->
				<input type="hidden" id="has_excerpt"
					   value="<?php echo !empty($excerpt) ? esc_textarea(stripslashes($excerpt)) : ''; ?>">
				<textarea name="excerpt" id="atbdp_excerpt"
						  class="form-control directory_field" cols="30" rows="5"
						  placeholder="<?php echo esc_attr($excerpt_placeholder); ?>"><?php echo !empty($excerpt) ? esc_textarea(stripslashes($excerpt)) : ''; ?></textarea>
			</div>
		<?php }

		/**
		 * @since 5.10.0
		 * It fires after the excerpt field
		 */
		do_action('atbdp_add_listing_after_excerpt', $p_id);


        /**
         * @since 7.0
         * @hooked Directorist_Template_Hooks::dashboard_title - 10
         */
        do_action( 'directorist_add_listing_before_location' );
		?>

		<?php if (empty($display_loc_for)) { ?>
			<div class="form-group" id="atbdp_locations">
				<label for="at_biz_dir-location"><?php
					esc_html_e($location_label . ':', 'directorist');
					echo $require_location ? '<span class="atbdp_make_str_red">*</span>' : ''; ?></label>
				<?php
				$current_val = get_the_terms($p_id, ATBDP_LOCATION);;
				$ids = array();
				if (!empty($current_val)) {
					foreach ($current_val as $single_val) {
						$ids[] = $single_val->term_id;
					}
				}
				?>
				<select name="tax_input[at_biz_dir-location][]" class="form-control"
						id="at_biz_dir-location" <?php echo !empty($multiple_loc_for_user) ? 'multiple="multiple"' : '' ?>>
					<?php
					if (empty($multiple_loc_for_user)) {
						echo '<option>' . $loc_placeholder . '</option>';
					}
					$location_fields = add_listing_category_location_filter($query_args, ATBDP_LOCATION, $ids);
					echo $location_fields;
					?>
				</select>

			</div>
		<?php } ?>
		<?php
		if ($plan_tag && empty($display_tag_for)) {
			?>
			<div class="form-group tag_area" id="atbdp_tags">
				<label for="at_biz_dir-tags"><?php
					esc_html_e($tag_label . ':', 'directorist');
					echo get_directorist_option('require_tags') ? '<span class="atbdp_make_str_red">*</span>' : ''; ?></label>
				<?php
				$output = array();
				if (!empty($p_tags)) {
					foreach ($p_tags as $p_tag) {
						$output[] = $p_tag->term_id;
					}
				} ?>
				<select name="tax_input[at_biz_dir-tags][]" class="form-control"
						id="at_biz_dir-tags" multiple="multiple">

					<?php foreach ($listing_tags as $l_tag) {
						$checked = in_array($l_tag->term_id, $output) ? 'selected' : '';
						?>
						<option <?php echo $checked; ?>
								value='<?php echo $l_tag->name ?>'><?php echo esc_html($l_tag->name) ?></option>
					<?php } ?>
				</select>
				<?php
				/**
				 * @since 4.7.2
				 * It fires after the tag field
				 */
				do_action('atbdp_add_listing_after_tag_field', $p_id);
				?>
			</div>
			<?php
		}
		/**
		 * @since 4.7.1
		 * It fires after the tag field
		 */
		do_action('atbdp_add_listing_after_tag', $p_id);
		?>
		<!--***********************************************************************
			Run the custom field loop to show all published custom fields asign to Category
		 **************************************************************************-->
		<!--@ Options for select the category.-->
		<div class="form-group" id="atbdp_categories">
			<label for="atbdp_select_cat"><?php
				esc_html_e($category_label . ':', 'directorist');
				echo get_directorist_option('require_category') ? '<span class="atbdp_make_str_red">*</span>' : ''; ?></label>
			<?php
			$category = wp_get_object_terms($p_id, ATBDP_CATEGORY, array('fields' => 'ids'));
			$selected_category = count($category) ? $category[0] : -1;
			$current_val = get_the_terms($p_id, ATBDP_CATEGORY);;
			$ids = array();
			if (!empty($current_val)) {
				foreach ($current_val as $single_val) {
					$ids[] = $single_val->term_id;
				}
			}
			$categories = get_terms(ATBDP_CATEGORY, array('hide_empty' => 0, 'exclude' => $plan_cat));
			?>
			<select name="admin_category_select[]" id="atbdp_select_cat" class="form-control"
					id="at_biz_dir-categories" <?php echo !empty($multiple_cat_for_user) ? 'multiple="multiple"' : ''; ?>>
				<?php
				if (empty($multiple_cat_for_user)) {
					echo '<option>' . $cat_placeholder . '</option>';
				}
				$categories_field = add_listing_category_location_filter($query_args, ATBDP_CATEGORY, $ids, '', $plan_cat);
				echo $categories_field;
				?>
			</select>
		</div>
		<?php
		if ($plan_custom_field) {
			?>
			<div id="atbdp-custom-fields-list" data-post_id="<?php echo $p_id; ?>">
				<?php
				$selected_category = '';
				do_action('wp_ajax_atbdp_custom_fields_listings_front', $p_id, $selected_category); ?>
			</div>
			<?php
		}
		?>

		<?php
		if ($ids) {
			?>
			<div id="atbdp-custom-fields-list-selected" data-post_id="<?php echo $p_id; ?>">
				<?php
				$selected_category = !empty($selected_category) ? $selected_category : '';
				do_action('wp_ajax_atbdp_custom_fields_listings_front_selected', $p_id, $selected_category); ?>
			</div>
			<?php
		}
		do_action('atbdp_after_general_information', $p_id);
		?>

	</div>
</div>