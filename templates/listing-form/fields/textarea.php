<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group directorist-textarea-field">
	<?php $listing_form->field_label_template( $data );?>

	<textarea <?php echo !empty( $data['max'] ) ? 'max="'. $data['max'] .'"' : ''; ?> name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="form-control" rows="<?php echo (int) $data['rows']; ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> ><?php echo esc_attr( $data['value'] ); ?></textarea>

	<?php $listing_form->field_description_template( $data );?>
</div>