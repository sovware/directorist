<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$p_id                    = $listing_form->get_add_listing_id();
$price                   = get_post_meta( $p_id, '_price', true );
$price_range             = get_post_meta( $p_id, '_price_range', true );
$atbd_listing_pricing    = get_post_meta( $p_id, '_atbd_listing_pricing', true );
$price_placeholder       = get_directorist_option( 'price_placeholder', __( 'Price of this listing. Eg. 100', 'directorist' ) );
$price_range_placeholder = get_directorist_option( 'price_range_placeholder', __( 'Price Range', 'directorist' ) );
$allow_decimal           = get_directorist_option( 'allow_decimal', 1 );
$currency                = get_directorist_option( 'g_currency', 'USD' );
$c_symbol                = atbdp_currency_symbol( $currency );
$current_price_type      = '';
?>

<div class="directorist-form-group directorist-form-pricing-field">

	<?php $listing_form->field_label_template( $data ); ?>

	<input type="hidden" id="atbd_listing_pricing" value="<?php echo esc_attr( $atbd_listing_pricing ); ?>">

	<div class="directorist-form-pricing-field__options">
		<?php
		if ( $data['pricing_type'] == 'both' || $data['pricing_type'] == 'price_unit' ) {
			$checked =  ( $atbd_listing_pricing == 'price' || empty($p_id) ) ? ' checked' : '';
			$current_price_type = ( ! empty( $checked ) ) ? 'price_unit' : $current_price_type;

			ob_start(); ?>
			<div class="directorist-checkbox directorist_pricing_options">
				<input type="checkbox" id="price_selected" value="price" name="atbd_listing_pricing"<?php echo $checked; ?>>
				<label for="price_selected" class="directorist-checkbox__label" data-option="price"><?php echo esc_html( $data['price_unit_field_label'] );?></label>
			</div>

			<?php

			$price_unit_checkbox =  apply_filters( 'directorist_submission_field_module', ob_get_clean(), [
				'field'       => 'pricing',
				'module'      => 'price_unit',
				'section_key' => 'price_selected_input',
				'data'        => $data,
			]);

			echo $price_unit_checkbox;
		}


		if ( $data['pricing_type'] == 'both' || $data['pricing_type'] == 'price_range' ) {
			ob_start();

			$current_price_type = ( checked( $atbd_listing_pricing, 'range', false ) ) ? 'price_range' : $current_price_type;

			if ( ! empty( $price_unit_checkbox ) ) : ?>
				<span class="directorist-form-pricing-field__options__divider"><?php esc_html_e('Or', 'directorist'); ?></span>
			<?php endif; ?>
			<div class="directorist-checkbox directorist_pricing_options">
				<input type="checkbox" id="price_range_selected" value="range" name="atbd_listing_pricing"<?php checked( $atbd_listing_pricing, 'range' ); ?>>
				<label for="price_range_selected" class="directorist-checkbox__label" data-option="price_range"><?php echo esc_html( $data['price_range_label'] );?></label>
			</div>

			<?php
			$price_range_checkbox = apply_filters( 'directorist_submission_field_module', ob_get_clean(), [
				'field'       => 'pricing',
				'module'      => 'price_range',
				'section_key' => 'price_range_selected_input',
				'data'        => $data,
			]);

			echo $price_range_checkbox;
		}

		if ( ! empty( $price_unit_checkbox ) || ! empty( $price_range_checkbox ) ) { ?>
			<small class="directorist-form-pricing-field__options__info"><?php esc_html_e('(Optional - Uncheck to hide pricing for this listing)', 'directorist') ?></small>
		<?php } ?>
	</div>

	<?php
	if ( $data['pricing_type'] == 'both' || $data['pricing_type'] == 'price_unit' ) {
		ob_start();
		$step = $allow_decimal ? ' step="any"' : '';
		?>

		<input type="<?php echo $data['price_unit_field_type']; ?>"<?php echo $step; ?> id="price" name="price" value="<?php echo esc_attr($price); ?>" class="directorist-form-element directory_field directory_pricing_field" placeholder="<?php echo esc_attr($price_placeholder); ?>"/>
		<?php

		echo apply_filters( 'directorist_submission_field_module', ob_get_clean(), [
			'field'       => 'pricing',
			'module'      => 'price_unit',
			'section_key' => 'price_unit_field_type_input',
			'data'        => $data,
		]);
	}

	if ( $data['pricing_type'] == 'both' || $data['pricing_type'] == 'price_range' ) {
		ob_start();
		?>
		<select class="directorist-form-element directory_field directory_pricing_field" id="price_range" name="price_range">
			<option value=""><?php echo esc_html($price_range_placeholder); ?></option>

			<option value="skimming"<?php selected($price_range, 'skimming'); ?>><?php printf( '%s (%s)', esc_html__('Ultra High', 'directorist'), str_repeat($c_symbol, 4) );?></option>

			<option value="moderate" <?php selected($price_range, 'moderate'); ?>><?php printf( '%s (%s)', esc_html__('Expensive ', 'directorist'), str_repeat($c_symbol, 3) );?></option>

			<option value="economy" <?php selected($price_range, 'economy'); ?>><?php printf( '%s (%s)', esc_html__('Moderate ', 'directorist'), str_repeat($c_symbol, 2) );?></option>

			<option value="bellow_economy" <?php selected($price_range, 'bellow_economy'); ?>><?php printf( '%s (%s)', esc_html__('Cheap', 'directorist'), str_repeat($c_symbol, 1) );?></option>
		</select>
		<?php

		echo apply_filters( 'directorist_submission_field_module', ob_get_clean(), [
			'field'       => 'pricing',
			'module'      => 'price_range',
			'section_key' => 'price_range_field_type_input',
			'data'        => $data,
		]);
	}
	?>

</div>