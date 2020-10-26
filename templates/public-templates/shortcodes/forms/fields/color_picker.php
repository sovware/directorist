<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group" class="directorist-color-field">
	<?php $form->add_listing_label_template( $data );?>
	
	<script>
		jQuery(document).ready(function ($) {
			$('.my-color-field2').wpColorPicker().empty();
		});
	</script>

	<input type="color" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="form-control my-color-field2" value="<?php echo esc_attr( $data['value'] ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> >

	<?php $form->add_listing_description_template( $data );?>
</div>