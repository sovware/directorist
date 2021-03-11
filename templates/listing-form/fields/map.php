<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$p_id               = $listing_form->add_listing_id;
$address            = get_post_meta( $p_id, '_address', true );
$select_listing_map = get_directorist_option( 'select_listing_map', 'google' );
$manual_lat         = get_post_meta( $p_id, '_manual_lat', true );
$manual_lng         = get_post_meta( $p_id, '_manual_lng', true );
$default_latitude   = get_directorist_option( 'default_latitude', '40.7127753' );
$default_longitude  = get_directorist_option( 'default_longitude', '-74.0059728' );
$latitude           = !empty( $manual_lat ) ? $manual_lat : $default_latitude;
$longitude          = !empty( $manual_lng ) ? $manual_lng : $default_longitude;
$hide_map           = !empty( get_post_meta( $p_id, '_hide_map', true ) ) ? true : false;

$map_data = $listing_form->get_map_data();
Directorist\Helper::add_hidden_data_to_dom( 'map_data', $map_data );
?>

<div class="directorist-form-group directorist-form-map-field">

	<div class="directorist-form-map-field__wrapper">

		<?php if ( 'google' == $select_listing_map ): ?>

			<div id="directorist-map-floating-panel">
				<button class="directorist-btn directorist-btn-xs directorist-btn-danger" id="delete_marker"><?php esc_html_e( 'Delete Marker', 'directorist' ); ?></button>
			</div>

		<?php endif; ?>

		<div class="directorist-form-map-field__maps"><div id="osm"><div id="gmap"></div></div></div>

		<?php if ('google' == $select_listing_map): ?>

			<small class="map_drag_info"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php esc_html_e('You can drag pinpoint to place the correct address manually.', 'directorist'); ?></small>

		<?php endif; ?>

		<div class="directorist-map-coordinate directorist-checkbox">

			<input type="checkbox" name="manual_coordinate" value="1" id="manual_coordinate">

			<label for="manual_coordinate" class="directorist-checkbox__label"><?php echo esc_attr( $data['lat_long'] );?></label>

		</div>

	</div>


	<div class="directorist-map-coordinates">
		<div class="directorist-form-group">
			<label for="manual_lat"> <?php esc_html_e('Latitude', 'directorist'); ?></label>
			<input type="text" name="manual_lat" id="manual_lat" value="<?php echo esc_attr( $latitude ); ?>" class="directorist-form-element directory_field" placeholder="<?php esc_attr_e('Enter Latitude eg. 24.89904', 'directorist'); ?>"/>
		</div>

		<div class="directorist-form-group">
			<label for="manual_lng"> <?php esc_html_e( 'Longitude', 'directorist' ); ?> </label>
			<input type="text" name="manual_lng" id="manual_lng" value="<?php echo esc_attr( $longitude ); ?>" class="directorist-form-element directory_field" placeholder="<?php esc_attr_e( 'Enter Longitude eg. 91.87198', 'directorist' ); ?>"/>
		</div>

		<div class="directorist-form-group directorist-map-coordinates__generate">
			<button class="directorist-btn directorist-btn-sm directorist-btn-primary" id="generate_admin_map" type="button"><?php esc_html_e( 'Generate on Map', 'directorist' ); ?></button>
		</div>

	</div>

	<div class="directorist-checkbox directorist-hide-map-option">
		<input type="checkbox" name="hide_map" value="1" id="hide_map"<?php checked( $hide_map ); ?>>
		<label for="hide_map" class="directorist-checkbox__label"><?php esc_html_e(' Hide Map', 'directorist' ); ?> </label>
	</div>

</div>