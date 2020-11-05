<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

// e_var_dump($data)

?>

<div class="form-group" id="directorist-select-field">
	<?php $form->add_listing_label_template( $data );?>

	<select name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="form-control" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>
		<option value="volvo">Volvo</option>
		<option value="saab">Saab</option>
		<option value="mercedes">Mercedes</option>
		<option value="audi">Audi</option>
	</select>

	<?php $form->add_listing_description_template( $data ); ?>
</div>
