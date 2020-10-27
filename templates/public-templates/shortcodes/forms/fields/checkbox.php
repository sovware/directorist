<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

?>

<div class="form-group" id="directorist-checkbox-field">
	<?php $form->add_listing_label_template( $data );?>

	<div class="form-control">
		<input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
		<label for="vehicle1"> I have a bike</label><br>
		<input type="checkbox" id="vehicle2" name="vehicle2" value="Car">
		<label for="vehicle2"> I have a car</label><br>
		<input type="checkbox" id="vehicle3" name="vehicle3" value="Boat">
		<label for="vehicle3"> I have a boat</label><br><br>
	</div>

	<?php $form->add_listing_description_template( $data ); ?>
</div>
