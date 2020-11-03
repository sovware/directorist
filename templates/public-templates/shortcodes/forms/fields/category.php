<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group" class="directorist-categories-field">
	<?php $form->add_listing_label_template( $data );?>

	<select name="admin_category_select[]" id="at_biz_dir-categories" class="form-control" <?php echo $data['type'] == 'multiple' ? 'multiple="multiple"' : ''; ?>>
			
		<?php
		if ( ! $display_multiple_cat ) {
			printf( '<option>%s</option>', __( 'Select Category', 'directorist' ) );
		}

		echo $form->add_listing_cat_fields();
		?>
	</select>

</div>
