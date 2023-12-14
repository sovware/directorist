<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.8.0
 */

$placeholder = $data['placeholder'] ?? '';
$data_max    = $data['max_location_creation'] ?? '';
$data_new    = $data['create_new_loc'] ?? '';
$multiple    = $data['type'] === 'multiple' ? 'multiple' : '';
$lazy_load   = $data['lazy_load'];

$current_terms = $listing_form->add_listing_terms( ATBDP_LOCATION );

$current_ids = array_map( function( $term ) {
	return $term->term_id;
}, $current_terms );

$current_labels = array_map( function( $term ) {
	return $term->name;
}, $current_terms );

$current_ids_as_string    = implode( ',', $current_ids );
$current_labels_as_string = implode( ',', $current_labels );
?>

<div class="directorist-form-group directorist-form-location-field">

	<?php $listing_form->field_label_template( $data ); ?>

	<select name="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" id="at_biz_dir-location" data-selected-id="<?php echo esc_attr( $current_ids_as_string ) ?>" data-selected-label="<?php echo esc_attr( $current_labels_as_string ) ?>" data-placeholder="<?php echo esc_attr( $placeholder ); ?>" data-max="<?php echo esc_attr( $data_max ); ?>" data-allow_new="<?php echo esc_attr( $data_new ); ?>" <?php echo esc_attr( $multiple ); ?> <?php $listing_form->required( $data ); ?>>

		<?php
		if ($data['type'] !== 'multiple') {
			echo '<option value="">' . esc_attr( $placeholder ) . '</option>';
		}
		if ( ! $lazy_load ) {
			echo directorist_kses( $listing_form->add_listing_location_fields(), 'form_input' );
		}
		?>

	</select>

	<?php $listing_form->field_description_template( $data ); ?>

</div>