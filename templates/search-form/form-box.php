<?php
/**
 * @author  wpWax
 * @since   7.2.2
 * @version 8.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-form__box">

	<div class="directorist-search-form-top directorist-flex directorist-align-center directorist-search-form-inline directorist-search-form__top directorist-search-modal directorist-search-modal--basic">
		<?php $searchform->advanced_search_form_basic_fields_template();?>
	</div>
	<?php
		$searchform->more_buttons_template();
	?>

</div>

<div class="directorist-search-form-action__modal">
	<a href="#" class="directorist-btn directorist-btn-light directorist-search-form-action__modal__btn-search directorist-modal-btn directorist-modal-btn--basic">

		<?php directorist_icon( 'las la-search' ); ?>

		<?php echo esc_html( $searchform->search_button_text );?>

	</a>

	<?php if ( $searchform->has_more_filters_button ): ?>
		<a href="#" class="directorist-search-form-action__modal__btn-advanced directorist-modal-btn directorist-modal-btn--advanced">

			<?php directorist_icon( 'fas fa-sliders-h' ); ?>

		</a>
	<?php endif ?>
</div>

<div class="directorist-search-modal directorist-search-modal--advanced">
	<?php $searchform->advanced_search_form_fields_template(); ?>
</div>