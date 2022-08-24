<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.3.3
 */

$placeholder = $data['placeholder'] ?? '';
$data_max    = $data['max'] ?? '';
$data_new    = $data['create_new_cat'] ?? '';
$multiple    = $data['type'] === 'multiple' ? 'multiple' : '';
?>

<div class="directorist-form-group directorist-form-categories-field">

	<?php $listing_form->field_label_template( $data );?>

	<select name="admin_category_select[]" id="at_biz_dir-categories" class="directorist-form-element" data-placeholder="<?php echo esc_attr( $placeholder ); ?>" data-max="<?php echo esc_attr( $data_max ); ?>" data-allow_new="<?php echo esc_attr( $data_new ); ?>" <?php echo esc_attr( $multiple ); ?> <?php $listing_form->required( $data ); ?>>

		<?php
		if ($data['type'] !== 'multiple') {
			echo '<option value="">' . esc_attr( $placeholder ) . '</option>';
		}
		echo directorist_kses( $listing_form->add_listing_cat_fields(), 'form_input' );
		?>

	</select>

	<?php $listing_form->field_description_template( $data ); ?>

</div>