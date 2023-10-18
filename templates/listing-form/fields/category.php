<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.8.0
 */

$placeholder = $data['placeholder'] ?? '';
$data_max    = $data['max'] ?? '';
$data_new    = $data['create_new_cat'] ?? '';
$multiple    = $data['type'] === 'multiple' ? 'multiple' : '';
$lazy_load   = $data['lazy_load'];

$current_terms = $listing_form->add_listing_terms( ATBDP_CATEGORY );

$current_ids = array_map( function( $term ) {
	return $term->term_id;
}, $current_terms );

$current_labels = array_map( function( $term ) {
	return $term->name;
}, $current_terms );

$current_ids_as_string    = implode( ',', $current_ids );
$current_labels_as_string = implode( ',', $current_labels );

?>

<div class="directorist-form-group directorist-form-categories-field">

	<?php $listing_form->field_label_template( $data );?>

	<select name="admin_category_select[]" id="at_biz_dir-categories" class="directorist-form-element" data-selected-id="<?php echo esc_attr( $current_ids_as_string ) ?>" data-selected-label="<?php echo esc_attr( $current_labels_as_string ) ?>" data-placeholder="<?php echo esc_attr( $placeholder ); ?>" data-max="<?php echo esc_attr( $data_max ); ?>" data-allow_new="<?php echo esc_attr( $data_new ); ?>" <?php echo esc_attr( $multiple ); ?> <?php $listing_form->required( $data ); ?>>

		<?php
		if ($data['type'] !== 'multiple') {
			echo '<option value="">' . esc_attr( $placeholder ) . '</option>';
		}

		if ( ! $lazy_load ) {
			echo directorist_kses( $listing_form->add_listing_cat_fields(), 'form_input' );
		}
		?>

	</select>

	<?php $listing_form->field_description_template( $data ); ?>

</div>