<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.0.5.1
 */

$placeholder = ! empty( $data['placeholder'] ) ? $data['placeholder'] : '';
?>

<div class="directorist-form-group directorist-form-categories-field">

	<?php $listing_form->field_label_template( $data );?>

	<select name="admin_category_select[]" id="at_biz_dir-categories" class="directorist-form-element" data-placeholder="<?php echo esc_attr( $placeholder ); ?>" <?php echo $data['type'] == 'multiple' ? 'multiple="multiple"' : ''; echo !empty( $data['max'] ) ? 'data-max="'. $data['max'] .'"' : ''; echo !empty( $data['create_new_cat'] ) ? 'data-allow_new="'. $data['create_new_cat'] .'"' : ''; ?>>

		<?php
		if ($data['type'] != 'multiple') {
			echo '<option></option>';
		}
		echo $listing_form->add_listing_cat_fields();
		?>

	</select>

	<?php $listing_form->field_description_template( $data ); ?>

</div>