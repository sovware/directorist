<?php
/**
 * @author  wpWax
 * @since   8.0
 * @version 8.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<form action="<?php atbdp_search_result_page_link(); ?>" class="directorist-search-form directorist-advanced-search">
	<div class="directorist-search-form__box">
		<div class="directorist-advanced-filter__top">
			<h2 class="directorist-advanced-filter__title"><?php esc_html_e( 'Filters', 'directorist' ); ?></h2>
			<button class="directorist-search-modal__contents__btn directorist-advanced-filter__close" type="button"  aria-label="Sidebar Filter Close Button">
				<?php directorist_icon( 'fas fa-times' ); ?>
			</button>
		</div>
		<div class="directorist-advanced-filter__advanced">
		<input type="hidden" name='directory_type' value='<?php echo esc_attr( $listings->get_directory_type_slug() ); ?>'>
			<?php foreach ( $searchform->form_data[1]['fields'] as $field ) : ?>
				<div class="directorist-advanced-filter__advanced__element directorist-search-field-<?php echo esc_attr( $field['widget_name'] ) ?>"><?php $searchform->field_template( $field ); ?></div>
			<?php endforeach; ?>
		</div>

		<?php if ( ! empty( $listings->display_search_button() ) ) : ?>
			<?php $searchform->buttons_template(); ?>
		<?php endif; ?>
	</div>
</form>