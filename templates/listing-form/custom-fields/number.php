<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$data_min = $data['min_value'] ?? '';
$data_max = $data['max_value'] ?? '';
?>

<div class="directorist-form-group directorist-custom-field-number">

	<?php $listing_form->field_label_template( $data );?>

	<div class="directorist-form-group__with-prefix">
		<span class="directorist-form-group__prefix directorist-form-group__prefix--start">$</span>
		<input type="number" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" value="<?php echo esc_attr( $data['value'] ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" step="any" min="<?php echo esc_attr( $data_min ); ?>" max="<?php echo esc_attr( $data_max ); ?>" <?php $listing_form->required( $data ); ?>>
		<span class="directorist-form-group__prefix directorist-form-group__prefix--end">Per Hour</span>
	</div>

	<?php $listing_form->field_description_template( $data ); ?>

</div>