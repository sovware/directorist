<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-form-action">

	<?php if ( $searchform->has_more_filters_button ): ?>

		<div class="directorist-search-form-action__filter">
			<a href="#" class="directorist-btn directorist-btn-lg directorist-filter-btn directorist-modal-btn__advanced">
				<?php if ( $searchform->has_more_filters_icon() ): ?>
					<?php directorist_icon( 'fas fa-sliders-h' ); ?>
				<?php endif;?>

				<?php echo esc_html( $searchform->more_filters_text );?>
			</a>
		</div>

	<?php endif ?>

	<?php if ( $searchform->has_search_button ): ?>

		<div class="directorist-search-form-action__submit">
			<button type="submit" class="directorist-btn directorist-btn-lg directorist-btn-dark directorist-btn-search">

				<?php if ( $searchform->has_search_button_icon() ): ?>
					<?php directorist_icon( 'las la-search' ); ?>
				<?php endif;?>

				<?php echo esc_html( $searchform->search_button_text );?>

			</button>
		</div>

	<?php endif; ?>

	<div class="directorist-search-form-action__modal">
		<a href="#" class="directorist-btn directorist-btn-light directorist-search-form-action__modal__btn-search directorist-modal-btn__basic">

			<?php directorist_icon( 'las la-search' ); ?>

			<?php echo esc_html( $searchform->search_button_text );?>

		</a>
	</div>

</div>