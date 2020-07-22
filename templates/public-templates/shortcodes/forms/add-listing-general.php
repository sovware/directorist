<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */


// var_dump($p_id);
?>
<div class="atbd_content_module atbd_general_information_module">

	<div class="atbd_content_module_title_area">
		<div class="atbd_area_title">
			<h4><?php esc_html_e('General information', 'directorist') ?></h4>
		</div>
	</div>

	<div class="atbdb_content_module_contents">

		<?php if ($display_title) { ?>
			<div class="form-group" id="atbdp_listing_title">
				<label for="listing_title"><?php echo wp_kses_post( $title_label_html );?></label>
				<input type="text" name="listing_title" id="listing_title" value="<?php echo !empty($listing->post_title) ? esc_attr($listing->post_title) : ''; ?>" class="form-control directory_field" placeholder="<?php esc_attr_e('Enter a title', 'directorist'); ?>"/>
			</div>
			<?php
		}

		if ($display_desc) { ?>
			<div class="form-group" id="atbdp_listing_content">
				<label for="listing_content"><?php echo wp_kses_post( $long_details_label_html );?></label>
				<?php
				wp_editor(
					!empty($listing->post_content) ? wp_kses_post($listing->post_content) : '',
					'listing_content',
					apply_filters('atbdp_add_listing_wp_editor_settings', array(
						'media_buttons' => false,
						'quicktags' => true,
						'editor_height' => 200
					))
				);
				?>
			</div>
			<?php
		}

		if ($display_tagline) { ?>
			<div class="form-group" id="atbdp_excerpt">
				<label for="atbdp_excerpt"><?php echo esc_html($tagline_label);?>:</label>
				<input type="text" name="tagline" id="has_tagline" value="<?php echo esc_attr($tagline); ?>" class="form-control directory_field" placeholder="<?php echo esc_attr($tagline_placeholder); ?>"/>
			</div>
			<?php
		}

		if ( ($display_price || $display_price_range) && ($plan_average_price || $plan_price)) { ?>
			<div class="form-group" id="atbd_pricing">
				<input type="hidden" id="atbd_listing_pricing" value="<?php echo esc_attr( $atbd_listing_pricing ); ?>">

				<label><?php echo esc_html($pricing_label); ?>:</label>

				<div class="atbd_pricing_options">
					<?php
					if ($plan_price && $display_price) {
						$checked =  ( $atbd_listing_pricing == 'price' || empty($p_id) ) ? ' checked' : '';
						?>
						<label for="price_selected" data-option="price"><input type="checkbox" id="price_selected" value="price" name="atbd_listing_pricing"<?php echo $checked; ?>> <?php echo wp_kses_post( $price_label_html );?></label>
						<?php
					}

					if ($plan_average_price && $display_price_range) {
						if ($plan_price && $display_price) {
							?>
							<span><?php esc_html_e('Or', 'directorist'); ?></span>
							<?php
						}
						?>

						<label for="price_range_selected" data-option="price_range"><input type="checkbox" id="price_range_selected" value="range" name="atbd_listing_pricing"<?php checked( $atbd_listing_pricing, 'range' ); ?>> <?php echo wp_kses_post( $price_range_label_html );?></label>
						<?php
					}
					?>

					<small><?php esc_html_e('(Optional - Uncheck to hide pricing for this listing)', 'directorist') ?></small>
				</div>

				<?php if ($plan_price && $display_price) {

					/**
					 * @since 6.2.1
					 */
					do_action('atbdp_add_listing_before_price_field', $p_id);

					$step = $allow_decimal ? ' step="any"' : '';
					?>

					<input type="number"<?php echo $step; ?> id="price" name="price" value="<?php echo esc_attr($price); ?>" class="form-control directory_field" placeholder="<?php echo esc_attr($price_placeholder); ?>"/>
					<?php
				}

				if ($plan_average_price && $display_price_range) { ?>
					<select class="form-control directory_field" id="price_range" name="price_range">
						<option value=""><?php echo esc_html($price_range_placeholder); ?></option>

						<option value="skimming"<?php selected($price_range, 'skimming'); ?>><?php printf( '%s (%s)', esc_html__('Ultra High', 'directorist'), str_repeat($c_symbol, 4) );?></option>

						<option value="moderate" <?php selected($price_range, 'moderate'); ?>><?php printf( '%s (%s)', esc_html__('Expensive ', 'directorist'), str_repeat($c_symbol, 3) );?></option>

						<option value="economy" <?php selected($price_range, 'economy'); ?>><?php printf( '%s (%s)', esc_html__('Moderate ', 'directorist'), str_repeat($c_symbol, 2) );?></option>

						<option value="bellow_economy" <?php selected($price_range, 'bellow_economy'); ?>><?php printf( '%s (%s)', esc_html__('Cheap', 'directorist'), str_repeat($c_symbol, 1) );?></option>
					</select>
					<?php
				}

				/**
				 * @since 4.7.1
				 */
				do_action('atbdp_add_listing_after_price_field', $p_id);
				?>
			</div>
			<?php
		}

		if ($display_views_count) { ?>
			<div class="form-group">
				<label for="atbdp_views_count"><?php echo esc_html($views_count_label);?>:</label>
				<input type="text" id="views_Count" name="atbdp_post_views_count" value="<?php echo esc_attr($atbdp_post_views_count); ?>" class="form-control directory_field"/>
			</div>
			<?php
		}

		/**
		 * @since 4.7.1
		 */
		do_action('atbdp_add_listing_after_price', $p_id);
		
		if ($display_excerpt) { ?>
			<div class="form-group" id="atbdp_excerpt_area">
				<label for="atbdp_excerpt"><?php echo esc_html($excerpt_label_html);?></label>
				<textarea name="excerpt" id="atbdp_excerpt" class="form-control directory_field" cols="30" rows="5" placeholder="<?php echo esc_attr($excerpt_placeholder); ?>"><?php echo esc_textarea( $excerpt ); ?></textarea>
				<input type="hidden" id="has_excerpt" value="<?php echo esc_attr( $excerpt ); ?>">
			</div>
			<?php
		}

		/**
		 * @since 5.10.0
		 */
		do_action('atbdp_add_listing_after_excerpt', $p_id);

        /**
         * @since 7.0
         * @hooked Directorist_Listing_Forms > add_listing_custom_fields_template - 10
         */
        do_action( 'directorist_add_listing_before_location' );
		
		if ($display_loc) { ?>
			<div class="form-group" id="atbdp_locations">
				<label for="at_biz_dir-location"><?php echo esc_html($location_label_html);?></label>
				<select name="tax_input[at_biz_dir-location][]" class="form-control" id="at_biz_dir-location"<?php echo $multiple_loc ? ' multiple="multiple"' : '' ?>>

					<?php
					if (!$multiple_loc) {
						printf('<option>%s</option>', $loc_placeholder);
					}
					
					echo $location_fields;
					?>
				</select>
			</div>
			<?php
		}

		if ($plan_tag && $display_tag) { ?>
			<div class="form-group tag_area" id="atbdp_tags">
				<label for="at_biz_dir-tags"><?php echo esc_html($tag_label_html);?></label>
				<select name="tax_input[at_biz_dir-tags][]" class="form-control" id="at_biz_dir-tags" multiple="multiple">
					<?php foreach ($all_tags as $tag) {
						$checked = in_array($tag->term_id, $listing_tag_ids) ? ' selected' : '';
						?>

						<option<?php echo esc_attr($checked); ?> value='<?php echo esc_attr($tag->name); ?>'><?php echo esc_html($tag->name) ?></option>
						<?php
					}
					?>
				</select>

				<?php
				/**
				 * @since 4.7.2
				 */
				do_action('atbdp_add_listing_after_tag_field', $p_id);
				?>

			</div>
			<?php
		}

		/**
		 * @since 4.7.1
		 */
		do_action('atbdp_add_listing_after_tag', $p_id);
		?>

		<div class="form-group" id="atbdp_categories">
			<label for="at_biz_dir-categories"><?php echo esc_html($cat_label_html);?></label>
			<select name="admin_category_select[]" id="at_biz_dir-categories" class="form-control"<?php echo $display_multiple_cat ? ' multiple="multiple"' : ''; ?>>
				
				<?php
				if (!$display_multiple_cat) {
					printf('<option>%s</option>', $cat_placeholder);
				}

				echo $cat_fields;
				?>
			</select>
		</div>

		<?php if ($plan_custom_field) { ?>
			<div id="atbdp-custom-fields-list" data-post_id="<?php echo esc_attr( $p_id ); ?>">
				<?php do_action('wp_ajax_atbdp_custom_fields_listings_front', $p_id, ''); ?>
			</div>
			<?php
		}

		if ($listing_cat_ids) { ?>
			<div id="atbdp-custom-fields-list-selected" data-post_id="<?php echo esc_attr( $p_id ); ?>">
				<?php
		        $category = wp_get_object_terms($p_id, ATBDP_CATEGORY, array('fields' => 'ids'));
		        $selected_category = count($category) ? $category[0] : -1;
				$selected_category = !empty($selected_category) ? $selected_category : '';
				do_action('wp_ajax_atbdp_custom_fields_listings_front_selected', $p_id, $selected_category); ?>
			</div>
			<?php
		}

		do_action('atbdp_after_general_information', $p_id);
		?>

	</div>
</div>