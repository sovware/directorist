<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group directorist-color-field">
	<?php
	$form->add_listing_label_template( $data );
	
	if( !empty( $data['value'] ) ) {
		?>
		<script> jQuery(document).ready(function ($) { $('.my-color-field').wpColorPicker(); }); </script>
		<?php
	}
	else {
		?>
		<script> jQuery(document).ready(function ($) { $('.my-color-field').wpColorPicker().empty(); }); </script>
	<?php } ?>
	
	<input type="text" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="color_code2 <?php echo esc_attr( $data['field_key'] ); ?>" class="my-color-field" value="<?php echo esc_attr( $data['value'] ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> >

	<?php $form->add_listing_description_template( $data );?>
</div>