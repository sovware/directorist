<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */

if (!$display_map_for && $display_map_field) { ?>
	<div class="atbd_content_module">
		<div class="atbd_content_module_title_area">
			<div class="atbd_area_title">
				<h4><?php esc_html_e('Map', 'directorist') ?></h4>
			</div>
		</div>

		<div class="atbdb_content_module_contents">

			<?php if (!$display_address_for && $display_address_field) { ?>
				<div class="form-group" id="atbdp_address">
					<label for="address"><?php echo wp_kses_post( $address_label_html ); ?></label>
					<input type="text" name="address" id="address" autocomplete="off" value="<?php echo esc_attr($address); ?>" class="form-control directory_field" placeholder="<?php echo esc_attr($address_placeholder); ?>"/>
					<div id="result"><ul></ul></div>
				</div>
				<?php
			}
			else { ?>
				<input type="hidden" id="address">
				<?php
			}

			if (!$display_map_for && $display_map_field) { ?>
				<div class="form-group">
					<div class="map_wrapper">

						<?php if ('google' == $select_listing_map) { ?>
							<div id="floating-panel">
								<button class="btn btn-danger" id="delete_marker"><?php esc_html_e('Delete Marker', 'directorist'); ?></button>
							</div>
						<?php } ?>

						<div id="osm"><div id="gmap"></div></div>

						<?php if ('google' == $select_listing_map) { ?>
							<small class="map_drag_info"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php esc_html_e('You can drag pinpoint to place the correct address manually.', 'directorist'); ?></small>
						<?php } ?>
						
						<div class="cor-wrap">
							<input type="checkbox" name="manual_coordinate" value="1" id="manual_coordinate">
							<label for="manual_coordinate"><?php esc_html_e('Or Enter Coordinates (latitude and longitude) Manually', 'directorist');?></label>
						</div>

					</div>

					<div class="row">
						<div id="hide_if_no_manual_cor" class="clearfix col-sm-12">
							
							<div class="row">
								<div class="col-md-6 col-sm-12">
									<div class="form-group">
										<label for="manual_lat"> <?php esc_html_e('Latitude', 'directorist'); ?></label>
										<input type="text" name="manual_lat" id="manual_lat" value="<?php echo esc_attr($latitude); ?>" class="form-control directory_field" placeholder="<?php esc_attr_e('Enter Latitude eg. 24.89904', 'directorist'); ?>"/>
									</div>
								</div>

								<div class="col-md-6 col-sm-12">
									<div class="form-group">
										<label for="manual_lng"> <?php _e('Longitude', 'directorist'); ?> </label>
										<input type="text" name="manual_lng" id="manual_lng" value="<?php echo esc_attr($longitude); ?>" class="form-control directory_field" placeholder="<?php esc_attr_e('Enter Longitude eg. 91.87198', 'directorist'); ?>"/>
									</div>
								</div>

								<div class="col-md-3 col-sm-12">
									<div class="form-group lat_btn_wrap">
										<button class="btn btn-primary" id="generate_admin_map"><?php esc_html_e('Generate on Map', 'directorist'); ?></button>
									</div>
								</div>
							</div>

						</div>

						<div class="col-sm-12">
							<div class="form-group hide-map-option">
								<input type="checkbox" name="hide_map" value="1" id="hide_map"<?php checked( $hide_map ); ?>>
								<label for="hide_map"><?php esc_html_e('Hide Map', 'directorist'); ?> </label>
							</div>
						</div>
					</div>
				</div>
				<?php
			}

            /**
             * It fires after the google map preview area
             * @param string $type Page type.
             * @param array $listing_info Information of the current listing
             * @since 1.1.1
             **/
            do_action('atbdp_edit_after_googlemap_preview', 'add_listing_page_frontend', $listing_info, $p_id);
            ?>
        </div>

    </div>
    <?php
}