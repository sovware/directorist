<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

// e_var_dump($data);

$price_label             = get_directorist_option( 'price_label', __( 'Price', 'directorist' ) );
$price_range_label       = get_directorist_option( 'price_range_label', __( 'Price Range', 'directorist' ) );
$price_placeholder       = get_directorist_option( 'price_placeholder', __( 'Price of this listing. Eg. 100', 'directorist' ) );
$price_range_placeholder = get_directorist_option( 'price_range_placeholder', __( 'Price Range', 'directorist' ) );
$allow_decimal           = get_directorist_option( 'allow_decimal', 1 );

return;
?>

<div class="form-group" class="directorist-pricing-field">
	<?php $form->add_listing_label_template( $data ); ?>

	<input type="hidden" id="atbd_listing_pricing" value="<?php echo esc_attr( $data['value'] ); ?>">

	<div class="atbd_pricing_options">
		<?php
		if ( $data['pricing_type'] == 'both' || $data['pricing_type'] == 'price_unit' ) {
			$checked =  ( $atbd_listing_pricing == 'price' || empty($p_id) ) ? ' checked' : '';
			?>
			<label for="price_selected" data-option="price">
				<input type="checkbox" id="price_selected" value="price" name="atbd_listing_pricing"<?php echo $checked; ?>> <?php echo wp_kses_post( $price_label_html );?>
			</label>
			<?php
		}

		if ( $data['pricing_type'] == 'both' || $data['pricing_type'] == 'price_range' ) {
			?>
			<span><?php esc_html_e('Or', 'directorist'); ?></span>

			<label for="price_range_selected" data-option="price_range"><input type="checkbox" id="price_range_selected" value="range" name="atbd_listing_pricing"<?php checked( $atbd_listing_pricing, 'range' ); ?>> <?php echo wp_kses_post( $price_range_label_html );?></label>
			<?php
		}
		?>

		<small><?php esc_html_e('(Optional - Uncheck to hide pricing for this listing)', 'directorist') ?></small>
	</div>


	<?php $form->add_listing_description_template( $data ); ?>
</div>


<div class="form-group" id="atbd_pricing">
	

	<label><?php echo esc_html($pricing_label); ?>:</label>

	<div class="atbd_pricing_options">
		<?php
		if ($plan_price && $display_price) {
			$checked =  ( $atbd_listing_pricing == 'price' || empty($p_id) ) ? ' checked' : '';
			?>
			<label for="price_selected" data-option="price">
				<input type="checkbox" id="price_selected" value="price" name="atbd_listing_pricing"<?php echo $checked; ?>> <?php echo wp_kses_post( $price_label_html );?>
			</label>
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