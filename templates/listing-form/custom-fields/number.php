<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$max = !empty( $data['max'] ) ? 'max="'. esc_attr( $data['max'] ) .'"' : '';
?>

<div class="directorist-form-group directorist-custom-field-number">

	<?php $listing_form->field_label_template( $data );?>

	<input type="number" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" value="<?php echo esc_attr( $data['value'] ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" step="any" <?php $listing_form->required( $data ); ?> <?php echo $max; ?>>

	<?php $listing_form->field_description_template( $data ); ?>

</div>