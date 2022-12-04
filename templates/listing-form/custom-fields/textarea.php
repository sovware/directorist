<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$maxlength = $data['max'] ?? '';
?>

<div class="directorist-form-group directorist-custom-field-textarea">

	<?php $listing_form->field_label_template( $data );?>

	<textarea name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" rows="<?php echo (int) $data['rows']; ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" maxlength="<?php echo esc_attr( $maxlength ); ?>" <?php $listing_form->required( $data ); ?>><?php echo esc_textarea( $data['value'] ); ?></textarea>

	<?php $listing_form->field_description_template( $data );?>

</div>