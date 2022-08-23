<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.3.3
 */

$placeholder = ! empty( $data['placeholder'] ) ? $data['placeholder'] : '';
$multiple    = $data['type'] == 'multiple' ? 'multiple="multiple" ' : '';
$max         = !empty( $data['max'] ) ? 'data-max="'. $data['max'] .'" ' : '';
$create_new  = !empty( $data['create_new_cat'] ) ? ' data-allow_new="'. $data['create_new_cat'] .'" ' : '';
?>

<div class="directorist-form-group directorist-form-categories-field">

	<?php $listing_form->field_label_template( $data );?>

	<select name="admin_category_select[]" id="at_biz_dir-categories" class="directorist-form-element" data-placeholder="<?php echo esc_attr( $placeholder ); ?>" 
	<?php echo $data['type'] == 'multiple' ? esc_attr( 'multiple' ) : ''; ?>
	data-max="<?php echo ! empty( $data['max'] ) ? esc_attr( $data['max'] ) : ''; ?>"
	data-allow_new="<?php echo ! empty( $data['create_new_cat'] ) ? esc_attr( $data['create_new_cat'] ) : ''; ?>"
	<?php 	
	$listing_form->required( $data ); 
	?>>
		<?php
		if ($data['type'] != 'multiple') {
			echo '<option value="">' . esc_attr( $placeholder ) . '</option>';
		}
		echo directorist_kses( $listing_form->add_listing_cat_fields(), 'form_input' );
		?>

	</select>

	<?php $listing_form->field_description_template( $data ); ?>

</div>