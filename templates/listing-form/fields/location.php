<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.3.3
 */

$placeholder = $data['placeholder'] ?? '';
$data_max    = $data['max_location_creation'] ?? '';
$data_new    = $data['create_new_loc'] ?? '';
$multiple    = $data['type'] === 'multiple' ? 'multiple' : '';
?>

<div class="directorist-form-group directorist-form-location-field">

	<?php $listing_form->field_label_template( $data ); ?>

	<select name="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" id="at_biz_dir-location" data-placeholder="<?php echo esc_attr( $placeholder ); ?>" data-max="<?php echo esc_attr( $data_max ); ?>" data-allow_new="<?php echo esc_attr( $data_new ); ?>" <?php echo esc_attr( $multiple ); ?> <?php $listing_form->required( $data ); ?>>

		<?php
		if ($data['type'] !== 'multiple') {
			echo '<option value="">' . esc_attr( $placeholder ) . '</option>';
		}
		echo directorist_kses( $listing_form->add_listing_location_fields(), 'form_input' );
		?>

	</select>

	<?php $listing_form->field_description_template( $data ); ?>

</div>