<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 7.0.3.2
 */
?>

<div class="form-group directorist-categories-field">
	<?php $form->add_listing_label_template( $data );?>

	<select name="admin_category_select[]" id="at_biz_dir-categories" class="form-control" <?php echo $data['type'] == 'multiple' ? 'multiple="multiple"' : ''; echo !empty( $data['max'] ) ? 'max="'. $data['max'] .'"' : ''; echo !empty( $data['create_new_cat'] ) ? 'data-allow_new="'. $data['create_new_cat'] .'"' : ''; ?>>
			
		<?php
		if ( $data['type'] != 'multiple' ) {
			printf( '<option>%s</option>', __( 'Select Category', 'directorist' ) );
		}

		echo $form->add_listing_cat_fields();
		?>
	</select>

</div>
<div class="form-group atbdp_category_custom_fields"></div>