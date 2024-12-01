<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0.8.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$location_source = !empty($data['location_source']) && $data['location_source'] == 'from_map_api' ? 'map' : 'listing';

if ( $location_source == 'listing' ) {
	$selected_item = $searchform::get_selected_location_option_data();
	?>

	<div class="directorist-search-field">
		<div class="directorist-select directorist-search-location directorist-search-field__input">

			<?php if ( ! empty( $data['label'] ) ) : ?>
				<label class="directorist-search-field__label"><?php echo esc_attr( $data['label'] ); ?></label>
			<?php endif; ?>

			<select name="in_loc" class="<?php echo esc_attr($searchform->location_class); ?>" data-placeholder="<?php echo esc_attr($data['placeholder']); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true" data-selected-id="<?php echo esc_attr( $selected_item['id'] ); ?>" data-selected-label="<?php echo esc_attr( $selected_item['label'] ); ?>">
				<?php
				echo '<option value="">' . esc_html__( 'Select Location', 'directorist' ) . '</option>';

				if ( empty( $data['lazy_load'] ) ) {
					echo directorist_kses( $searchform->locations_fields, 'form_input' );
				}

				?>
			</select>
		</div>
		<div class="directorist-search-field__btn directorist-search-field__btn--clear">
			<?php directorist_icon( 'fas fa-times-circle' ); ?>	
		</div>
	</div>

	<?php
}

elseif ( $location_source == 'map' ) {
	$cityLat = isset( $_GET['cityLat'] ) ? sanitize_text_field( wp_unslash( $_GET['cityLat'] ) ) : '';
	$cityLng = isset( $_GET['cityLng'] ) ? sanitize_text_field( wp_unslash( $_GET['cityLng'] ) ) : '';
	$value   = isset( $_GET['address'] ) ? sanitize_text_field( wp_unslash( $_GET['address'] ) ) : '';
	?>

	<div class="directorist-search-field directorist-form-group directorist-search-location directorist-icon-right">
		<?php if ( ! empty( $data['label'] ) ) : ?>
			<label class="directorist-search-field__label" for="addressId"><?php echo esc_attr( $data['label'] ); ?></label>
		<?php endif; ?>
		<span class="directorist-input-icon directorist-filter-location-icon"><?php directorist_icon( 'fas fa-crosshairs' ); ?></span>
		<input type="text" name="address" id="addressId" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" autocomplete="off" class="directorist-form-element directorist-location-js location-name directorist-search-field__input" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>

		<div class="address_result location-names" style="display: none"></div>
		<input type="hidden" id="cityLat" name="cityLat" value="<?php echo esc_attr($cityLat); ?>" />
		<input type="hidden" id="cityLng" name="cityLng" value="<?php echo esc_attr($cityLng); ?>" />

		<div class="directorist-search-field__btn directorist-search-field__btn--clear">
			<?php directorist_icon( 'fas fa-times-circle' ); ?>	
		</div>
	</div>

	<?php
}