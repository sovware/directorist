<?php
/**
 * @author  wpWax
 * @since   7.3.0
 * @version 7.7.0
 */

if (!defined('ABSPATH')) exit;

$search_form_fields = Directorist\Helper::get_directory_type_term_data(get_the_ID(), 'search_form_fields');
$directoriy_type = get_post_meta(get_the_ID(), '_directory_type', true);
$searchform = new Directorist\Directorist_Listing_Search_Form('listing', $directoriy_type);


if (is_numeric($searchform->listing_type)) {
	$term = get_term_by('id', $searchform->listing_type, ATBDP_TYPE);
	$listing_type = $term->slug;
}
?>
<div class="directorist-card__body">
	<div class="directorist-widget-advanced-search directorist-contents-wrap <?php echo is_singular(ATBDP_POST_TYPE) ? esc_html('directorist_single') : ''; ?>">
		<form action="<?php atbdp_search_result_page_link(); ?>" class="directorist-search-form directorist-advanced-filter__form">
			<input type="hidden" name='directory_type' value='<?php echo !empty($listing_type) ? esc_attr( $listing_type ) : esc_attr(  $searchform->listing_type ); ?>'>
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
			<?php $searchform->buttons_template(); ?>
		</form>
	</div><!-- ends: .default-ad-search -->
</div>