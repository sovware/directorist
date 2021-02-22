<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

?>

<div class="directorist-form-group directorist-form-location-field">
	<?php $listing_form->field_label_template( $data ); ?>

	<select name="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" id="at_biz_dir-location"
	 <?php
	 echo $data['type'] == 'multiple' ? 'multiple="multiple"' : '';
	 echo !empty( $data['max'] ) ? 'data-max="'. $data['max'] .'"' : '';
	 echo !empty( $data['allow_new'] ) ? 'allow_new="'. $data['allow_new'] .'"' : '';
	 ?>>

		<?php
		if ($data['type'] != 'multiple') {
			printf('<option>%s</option>', __( 'Select Location', 'directorist' ) );
		}

		echo $listing_form->add_listing_location_fields();
		?>
	</select>

	<?php $listing_form->field_description_template( $data ); ?>
</div>
