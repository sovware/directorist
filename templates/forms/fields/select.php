<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group" id="directorist-select-field">
	<?php $form->add_listing_label_template( $data );?>
	<select name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="form-control" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>
		<?php foreach( $data['options'] as $key => $value ) { ?>
				<option value="<?php echo esc_attr( $value['option_value'] )?>" <?php selected( $value['option_value'], $data['value'] ); ?>><?php echo esc_attr( $value['option_label'] )?></option>
		<?php } ?>
	</select>
	<?php $form->add_listing_description_template( $data ); ?>
</div>
