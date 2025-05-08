<?php
/**
 * @author  wpWax
 * @since   7.2.2
 * @version 8.4.1
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $searchform->have_basic_fields() ) {
	return;
}
?>
<form action="<?php atbdp_search_result_page_link(); ?>" class="directorist-search-form directorist-basic-search">
    <div class="directorist-search-form__box">
		<div class="directorist-search-form-top directorist-flex directorist-align-center directorist-search-form-inline directorist-search-form__top">
			<?php $searchform->render_basic_search_fields(); ?>
		</div>

        <?php if ( ! empty( $listings->display_search_button() ) ) : ?>
            <div class="directorist-search-form-action">
                <div class="directorist-search-form-action__submit">
                    <button type="submit" class="directorist-btn directorist-btn-lg directorist-btn-primary directorist-btn-search">
                        <?php if ( $searchform->has_search_button_icon() ): ?>
                            <?php directorist_icon( 'las la-search' ); ?>
                        <?php endif;?>

                        <?php echo esc_html( $searchform->search_button_text ); ?>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</form>
