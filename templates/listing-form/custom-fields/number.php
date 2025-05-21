<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
$data_min  = $data['min_value'] ?? '';
$data_max  = $data['max_value'] ?? '';
$data_step = max( 0, floatval( $data['step'] ?? 1 ) );
?>

<div class="directorist-form-group directorist-custom-field-number">

	<?php $listing_form->field_label_template( $data );?>

	<div class="directorist-form-group__with-prefix">

		<?php if ( ! empty( $data['prepend'] ) ) : ?>
			<span class="directorist-form-group__prefix directorist-form-group__prefix--start"><?php echo esc_html( $data['prepend'] ); ?></span>
		<?php endif; ?>

		<input type="number" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" value="<?php echo esc_attr( $data['value'] ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" step="<?php echo esc_attr( $data_step ); ?>" min="<?php echo esc_attr( $data_min ); ?>" max="<?php echo esc_attr( $data_max ); ?>" <?php $listing_form->required( $data ); ?>>

		<?php if ( ! empty( $data['append'] ) ) : ?>
			<span class="directorist-form-group__prefix directorist-form-group__prefix--end"><?php echo esc_html( $data['append'] ); ?></span>
		<?php endif; ?>

	</div>

	<?php $listing_form->field_description_template( $data ); ?>

</div>