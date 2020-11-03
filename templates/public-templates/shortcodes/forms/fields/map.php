<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$p_id = $form->add_listing_id;

$address =  get_post_meta( $p_id, '_address', true );
$select_listing_map = get_directorist_option( 'select_listing_map', 'google' );
$manual_lat        = get_post_meta( $p_id, '_manual_lat', true );
$manual_lng        = get_post_meta( $p_id, '_manual_lng', true );
$default_latitude  = get_directorist_option( 'default_latitude', '40.7127753' );
$default_longitude = get_directorist_option( 'default_longitude', '-74.0059728' );
$latitude   = ! empty( $manual_lat ) ? $manual_lat : $default_latitude;
$longitude  = ! empty( $manual_lng ) ? $manual_lng : $default_longitude;
$hide_map = ! empty( get_post_meta( $p_id, '_hide_map', true ) ) ? true : false;
?>

<div class="form-group" class="directorist-map-field">
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