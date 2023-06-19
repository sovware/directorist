<?php
/**
 * @author  wpWax
 * @since   7.2.2
 * @version 7.7.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<form action="<?php atbdp_search_result_page_link(); ?>" class="directorist-search-form">
	<div class="directorist-search-form-box-wrap directorist-search-form__box-wrap">
		<div class="directorist-search-form-box directorist-search-form__box">
			<div class="directorist-advanced-filter__top">
				<h2 class="directorist-advanced-filter__title">Filters</h2>
				<button class="directorist-search-modal__contents__btn directorist-advanced-filter__close">
					<?php directorist_icon( 'fas fa-times' ); ?>
				</button>
			</div>
			<div class="directorist-advanced-filter__advanced">
				<?php foreach ($searchform->form_data[1]['fields'] as $field) : ?>
					<div class="directorist-form-group directorist-advanced-filter__advanced--element directorist-search-field-<?php echo esc_attr($field['widget_name']) ?>"><?php $searchform->field_template($field); ?></div>
				<?php endforeach; ?>
			</div>
			<?php $searchform->buttons_template(); ?>
		</div>
	</div>
</form>