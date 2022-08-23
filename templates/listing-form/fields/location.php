<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.3.3
 */

$placeholder = ! empty( $data['placeholder'] ) ? $data['placeholder'] : '';
?>

<div class="directorist-form-group directorist-form-location-field">

	<?php $listing_form->field_label_template( $data ); ?>

	<select name="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" id="at_biz_dir-location" data-placeholder="<?php echo esc_attr( $placeholder ); ?>" <?php echo $data['type'] == 'multiple' ? esc_attr( 'multiple' ) : ''; ?> data-max="<?php echo ! empty( $data['max_location_creation'] ) ? esc_attr( $data['max_location_creation'] ) : ''; ?>" data-allow_new="<?php echo ! empty( $data['create_new_loc'] ) ? esc_attr( $data['create_new_loc'] ) : ''; ?>"
	<?php 	
	$listing_form->required( $data ); 
	?>>
		<?php
		if ($data['type'] != 'multiple') {
			echo '<option value="">' . esc_attr( $placeholder ) . '</option>';
		}
		echo directorist_kses( $listing_form->add_listing_location_fields(), 'form_input' );
		?>

	</select>

	<?php $listing_form->field_description_template( $data ); ?>

</div>