<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group" class="directorist-textarea-field">
	<?php $form->add_listing_label_template( $data );?>

	<textarea name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="form-control" rows="<?php echo (int) esc_attr( $rows ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php echo ! empty( $required ) ? 'required="required"' : ''; ?> ><?php echo esc_attr( $data['value'] ); ?></textarea>

	<?php $form->add_listing_description_template( $data );?>
</div>