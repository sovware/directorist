<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.2
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listing_id              = $listing_form->get_add_listing_id();
$price                   = get_post_meta( $listing_id, '_price', true );
$price_range             = get_post_meta( $listing_id, '_price_range', true );
$price_type              = get_post_meta( $listing_id, '_atbd_listing_pricing', true );
$allow_decimal           = get_directorist_option( 'allow_decimal', 1 );
$currency_symbol         = atbdp_currency_symbol( directorist_get_currency() );

?>
<div class="directorist-form-group directorist-form-pricing-field price-type-<?php echo esc_attr( $data['pricing_type'] ); ?>">
	<?php $listing_form->field_label_template( $data ); ?>

	<?php if ( $data['pricing_type'] === 'both' ) { ?>
		<div class="directorist-form-pricing-field__options">
			<div class="directorist-checkbox directorist_pricing_options">
				<input type="checkbox" id="price_selected" value="price" name="atbd_listing_pricing" <?php checked( $price_type, 'price' ); ?>>
				<label for="price_selected" class="directorist-checkbox__label" data-option="price"><?php echo esc_html( $data['price_unit_field_label'] );?></label>
			</div>

			<?php if ( ! empty( $price_unit_checkbox ) ) : ?>
				<span class="directorist-form-pricing-field__options__divider"><?php esc_html_e( 'Or', 'directorist' ); ?></span>
			<?php endif; ?>

			<div class="directorist-checkbox directorist_pricing_options">
				<input type="checkbox" id="price_range_selected" value="range" name="atbd_listing_pricing" <?php checked( $price_type, 'range' ); ?>>
				<label for="price_range_selected" class="directorist-checkbox__label" data-option="price_range"><?php echo esc_html( $data['price_range_label'] ); ?></label>
			</div>
		</div>
	<?php } ?>

	<?php if ( $data['pricing_type'] === 'both' || $data['pricing_type'] === 'price_unit' ) { ?>
		<input class="directorist-form-element directory_field directory_pricing_field" id="price" type="<?php echo esc_attr( $data['price_unit_field_type'] ); ?>" name="price" step="<?php echo esc_attr( $allow_decimal ? 'any' : 1 ); ?>" value="<?php echo esc_attr( $price ); ?>" placeholder="<?php echo esc_attr( $data['price_unit_field_placeholder'] ); ?>" />
	<?php } ?>

	<?php if ( $data['pricing_type'] === 'both' || $data['pricing_type'] === 'price_range' ) { ?>
		<select class="directorist-form-element directory_field directory_pricing_field" id="price_range" name="price_range">
			<option value=""><?php echo esc_html( $data['price_range_placeholder'] ); ?></option>
			<option value="skimming"<?php selected( $price_range, 'skimming' ); ?>><?php echo esc_html( sprintf( __( 'Ultra High (%s)', 'directorist' ), str_repeat( $currency_symbol, 4 ) ) );?></option>
			<option value="moderate" <?php selected( $price_range, 'moderate' ); ?>><?php echo esc_html( sprintf( __( 'Expensive (%s)', 'directorist' ), str_repeat( $currency_symbol, 3 ) ) );?></option>
			<option value="economy" <?php selected( $price_range, 'economy' ); ?>><?php echo esc_html( sprintf( __( 'Moderate (%s)', 'directorist' ), str_repeat( $currency_symbol, 2 ) ) ); ?></option>
			<option value="bellow_economy" <?php selected( $price_range, 'bellow_economy' ); ?>><?php echo esc_html( sprintf( __( 'Cheap (%s)', 'directorist' ), str_repeat( $currency_symbol, 1 ) ) ); ?></option>
		</select>
	<?php } ?>
</div>
