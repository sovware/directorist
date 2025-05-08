<?php
/**
 * @author  wpWax
 * @since   8.0
 * @version 8.4.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $searchform->have_advance_fields() ) {
	return;
}

$title              = $listings->options['sidebar_filter_text'] ?? __( 'Filters', 'directorist' );
$reset_button_label = $searchform->options['reset_sidebar_filters_text'] ?? esc_html__( 'Clear All', 'directorist' );
?>
<form action="<?php atbdp_search_result_page_link(); ?>" class="directorist-search-form directorist-advanced-search">
	<div class="directorist-search-form__box">
		<div class="directorist-advanced-filter__top">
			<h2 class="directorist-advanced-filter__title"><?php echo esc_html( $title ); ?></h2>
			<button class="directorist-search-modal__contents__btn directorist-advanced-filter__close" type="button"  aria-label="<?php esc_attr_e( 'Sidebar filter close button', 'directorist' ); ?>">
				<?php directorist_icon( 'fas fa-times' ); ?>
			</button>
		</div>
		<div class="directorist-advanced-filter__advanced">
			<input type="hidden" name='directory_type' value='<?php echo esc_attr( $listings->get_directory_type_slug() ); ?>'>

			<?php $searchform->render_advance_search_fields(); ?>

			<div class="directorist-advanced-filter__action directorist-advanced-filter__action--ajax">
				<button class="directorist-btn-reset-js directorist-btn-reset-ajax"><?php echo esc_html( $reset_button_label ); ?></button>
			</div>
		</div>

		<?php if ( ! empty( $listings->display_search_button() ) ) : ?>

			<?php $searchform->buttons_template(); ?>

		<?php endif; ?>
	</div>
</form>
