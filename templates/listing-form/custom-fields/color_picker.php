<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.5.3
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listing_form->load_color_picker_script( $data );
?>

<div class="directorist-form-group directorist-custom-field-color">

	<?php $listing_form->field_label_template( $data ); ?>

	<input type="text" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-color-field-js directorist-form-element" value="<?php echo esc_attr( $data['value'] ); ?>" <?php $listing_form->required( $data ); ?>>

	<?php $listing_form->field_description_template( $data );?>

</div>