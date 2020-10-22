<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$select_listing_map = get_directorist_option( 'select_listing_map', 'google' );
$manual_lat         = get_post_meta( get_query_var( 'atbdp_listing_id', 0 ), '_manual_lat', true );
$manual_lng         = get_post_meta( get_query_var( 'atbdp_listing_id', 0 ), '_manual_lng', true );
$latitude           = $manual_lat ? $manual_lat : get_directorist_option( 'default_latitude', '40.7127753' );
$longitude          = $manual_lng ? $manual_lng : get_directorist_option( 'default_longitude', '-74.0059728' );
$hide_map           = ! empty( get_post_meta( get_query_var( 'atbdp_listing_id', 0 ), '_hide_map', true ) ) ? true : false;
?>

<div class="form-group" id="directorist-terms_conditions-field">
	<?php if ( ! empty( $label ) ) : ?>
		<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo esc_html( $label ); ?>:<?php echo ! empty( $required ) ? Directorist_Listing_Forms::instance()->add_listing_required_html() : ''; ?></label>
	<?php endif; ?>

	<input type="text" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" class="form-control" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" <?php echo ! empty( $required ) ? 'required="required"' : ''; ?> >

	<div class="map_wrapper">

		<?php if ( 'google' === $select_listing_map ) { ?>
			<div id="floating-panel">
				<button class="btn btn-danger" id="delete_marker"><?php esc_html_e( 'Delete Marker', 'directorist' ); ?></button>
			</div>
		<?php } ?>

		<div id="osm"><div id="gmap"></div></div>

		<?php if ( 'google' === $select_listing_map ) { ?>
			<small class="map_drag_info"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php esc_html_e( 'You can drag pinpoint to place the correct address manually.', 'directorist' ); ?></small>
		<?php } ?>

		<?php if ( $lat_long ) { ?>
			<div class="cor-wrap">
				<input type="checkbox" name="manual_coordinate" value="1" id="manual_coordinate">
				<label for="manual_coordinate">
					<?php echo esc_attr( $lat_long ); ?>
				</label>
			</div>
		<?php } ?>
	</div>

	<div class="row">
		<div id="hide_if_no_manual_cor" class="clearfix col-sm-12">
			<div class="row">
				<div class="col-md-6 col-sm-12">
					<div class="form-group">
						<label for="manual_lat"> <?php esc_html_e( 'Latitude', 'directorist' ); ?></label>
						<input type="text" name="manual_lat" id="manual_lat" value="<?php echo esc_attr( $latitude ); ?>" class="form-control directory_field" placeholder="<?php esc_attr_e( 'Enter Latitude eg. 24.89904', 'directorist' ); ?>"/>
					</div>
				</div>

				<div class="col-md-6 col-sm-12">
					<div class="form-group">
						<label for="manual_lng"> <?php esc_html_e( 'Longitude', 'directorist' ); ?> </label>
						<input type="text" name="manual_lng" id="manual_lng" value="<?php echo esc_attr( $longitude ); ?>" class="form-control directory_field" placeholder="<?php esc_attr_e( 'Enter Longitude eg. 91.87198', 'directorist' ); ?>"/>
					</div>
				</div>

				<div class="col-md-3 col-sm-12">
					<div class="form-group lat_btn_wrap">
						<button class="btn btn-primary" id="generate_admin_map"><?php esc_html_e( 'Generate on Map', 'directorist' ); ?></button>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-12">
			<div class="form-group hide-map-option">
				<input type="checkbox" name="hide_map" value="1" id="hide_map"<?php checked( $hide_map ); ?>>
				<label for="hide_map"><?php esc_html_e( 'Hide Map', 'directorist' ); ?> </label>
			</div>
		</div>
	</div>
</div>
