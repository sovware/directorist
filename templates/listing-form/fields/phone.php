<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group directorist-phone-field">
	<?php $form->field_label_template( $data );?>

	<input type="tel" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="form-control" value="<?php echo esc_attr( $data['value'] ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> >

	<?php $form->field_description_template( $data ); ?>
</div>