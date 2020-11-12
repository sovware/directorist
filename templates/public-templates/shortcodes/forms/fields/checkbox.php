<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
// e_var_dump($data);

return;
?>

<div class="form-group directorist-checkbox-field">
	<?php $form->add_listing_label_template( $data );?>

	<div class="form-control">

	<div class="form-control">
		<?php foreach ( $data['options'] as $option ): ?>
			<?php $uniqid = $option['option_value'] . '-' .wp_rand();  ?>
			<input type="checkbox" id="<?php echo esc_attr( $uniqid ); ?>" name="<?php echo esc_attr( $data['field_key'] ); ?>" value="<?php echo esc_attr( $option['option_value'] ); ?>" <?php checked( $option['option_value'], $data['value'] ); ?>><label for="<?php echo esc_attr( $uniqid ); ?>"><?php echo esc_html( $option['option_label'] ); ?></label><br>
		<?php endforeach; ?>
	</div>


		<input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
		<label for="vehicle1"> I have a bike</label><br>
		<input type="checkbox" id="vehicle2" name="vehicle2" value="Car">
		<label for="vehicle2"> I have a car</label><br>
		<input type="checkbox" id="vehicle3" name="vehicle3" value="Boat">
		<label for="vehicle3"> I have a boat</label><br> 
	</div>

	<?php $form->add_listing_description_template( $data ); ?>
</div>
