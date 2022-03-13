<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

$search_form = directorist()->search_form;

if(  is_numeric( $search_form->listing_type ) ) {
	$term = get_term_by( 'id', $search_form->listing_type, ATBDP_TYPE );
	$listing_type = $term->slug;
}
?>

<div class="directorist-archive-adv-filter directorist-advanced-filter">

	<form action="<?php atbdp_search_result_page_link(); ?>" class="directorist-advanced-filter__form">

		<input type="hidden" name='directory_type' value='<?php echo ! empty( $listing_type ) ? $listing_type : $search_form->listing_type; ?>'>

		<div class="directorist-advanced-filter__basic">

			<?php foreach ( $search_form->form_data[0]['fields'] as $field ): ?>

				<div class="directorist-advanced-filter__basic--element"><?php $search_form->field_template( $field ); ?></div>

			<?php endforeach; ?>

		</div>

		<div class="directorist-advanced-filter__advanced">

			<?php foreach ( $search_form->form_data[1]['fields'] as $field ): ?>

				<div class="directorist-form-group directorist-advanced-filter__advanced--element directorist-advanced-filter__advanced--<?php echo esc_attr( $field['widget_name'] )?>"><?php $search_form->field_template( $field ); ?></div>

			<?php endforeach; ?>

		</div>

		<?php $search_form->buttons_template(); ?>

	</form>

</div>