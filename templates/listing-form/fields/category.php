<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<div class="directorist-form-group directorist-form-categories-field">
	<?php $listing_form->field_label_template( $data );?>

	<select name="admin_category_select[]" id="at_biz_dir-categories" class="directorist-form-element" <?php echo $data['type'] == 'multiple' ? 'multiple="multiple"' : ''; echo !empty( $data['max'] ) ? 'data-max="'. $data['max'] .'"' : ''; ?>>

		<?php
		if ( $data['type'] != 'multiple' ) {
			printf( '<option>%s</option>', __( 'Select Category', 'directorist' ) );
		}

		echo $listing_form->add_listing_cat_fields();
		?>
	</select>

</div>
<div class="form-group atbdp_category_custom_fields"></div>