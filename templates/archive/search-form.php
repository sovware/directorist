<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */
?>

<?php
if (is_numeric($searchform->listing_type)) {
	$term = get_term_by('id', $searchform->listing_type, ATBDP_TYPE);
	$listing_type = $term->slug;
}
?>
<div class="directorist-search-modal directorist-search-modal--full">
	<div class="directorist-search-modal__overlay"></div>
	<div class="directorist-search-modal__contents directorist-archive-adv-filter directorist-advanced-filter">
		<form action="<?php atbdp_search_result_page_link(); ?>" class="directorist-advanced-filter__form">
			<div class="directorist-search-modal__contents__header">
				<h3 class="directorist-search-modal__contents__title"><?php esc_html_e( 'More Filters', 'directorist' ) ?></h3>
				<button class="directorist-search-modal__contents__btn directorist-search-modal__contents__btn--close">
					<?php directorist_icon( 'fas fa-times' ); ?>
				</button>
				<span class="directorist-search-modal__minimizer"></span>
			</div>

			<div class="directorist-search-modal__contents__body">
				<input type="hidden" name='directory_type' value='<?php echo !empty($listing_type) ? esc_attr( $listing_type ) : esc_attr( $searchform->listing_type ); ?>'>
				<div class="directorist-advanced-filter__basic">
					<?php foreach ($searchform->form_data[0]['fields'] as $field) : ?>
						<div class="directorist-advanced-filter__basic__element"><?php $searchform->field_template($field); ?></div>
					<?php endforeach; ?>
				</div>

				<div class="directorist-advanced-filter__advanced">
					<?php foreach ($searchform->form_data[1]['fields'] as $field) : ?>
						<div class="directorist-advanced-filter__advanced__element directorist-search-field-<?php echo esc_attr($field['widget_name']) ?>"><?php $searchform->field_template($field); ?></div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="directorist-search-modal__contents__footer">
				<?php $searchform->buttons_template(); ?>
			</div>
		</form>
	</div>
</div>