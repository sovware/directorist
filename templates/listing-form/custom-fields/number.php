<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.3
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$data_max = $data['max'] ?? '';
?>

<div class="directorist-form-group directorist-custom-field-number">

	<?php $listing_form->field_label_template( $data );?>

	<input type="number" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" value="<?php echo esc_attr( $data['value'] ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" step="any" max="<?php echo esc_attr( $data_max ); ?>" <?php $listing_form->required( $data ); ?>>

	<?php $listing_form->field_description_template( $data ); ?>

</div>