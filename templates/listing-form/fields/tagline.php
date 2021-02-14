<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group directorist-tagline-field">
	<?php $listing_form->field_label_template( $data ); ?>

	<input type="text" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="form-control" value="<?php echo esc_attr( $data['value'] ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> >

	<?php $listing_form->field_description_template( $data ); ?>
</div>
