<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group directorist-date-field">
	<?php $listing_form->field_label_template( $data );?>

	<input type="date" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="form-control" value="<?php echo esc_attr( $data['value'] ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>

	<?php $listing_form->field_description_template( $data );?>
</div>