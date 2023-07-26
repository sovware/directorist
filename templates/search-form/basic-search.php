<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-modal__overlay"></div>
<div class="directorist-search-adv-filter directorist-advanced-filter directorist-search-modal__contents">
	<div class="directorist-advanced-filter__wrapper">
		<span class="directorist-search-modal__minimizer"></span>
		<div class="directorist-search-modal__contents__body">
			<?php foreach ( $searchform->form_data[0]['fields'] as $field ){ ?>
				<div class="directorist-search-modal__input">
					<div class="directorist-search-modal__input__btn directorist-search-modal__input__btn--back">
						<?php directorist_icon( 'fas long-arrow-alt-left' ); ?>
					</div>
					<?php $searchform->field_template( $field ); ?>
					<div class="directorist-search-modal__input__btn directorist-search-modal__input__btn--clear">
						<?php directorist_icon( 'fas fa-times-circle' ); ?>
					</div>
				</div>
			<?php } ?>
			<div class="directorist-search-form-action__modal">
				<button type="submit" class="directorist-btn directorist-btn-light directorist-search-form-action__modal__btn-search">

					<?php directorist_icon( 'las la-search' ); ?>

					<?php echo esc_html( $searchform->search_button_text );?>

				</button>
			</div>
		</div>
	</div>
</div>