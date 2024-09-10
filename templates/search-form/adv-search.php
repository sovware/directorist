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
	<div class="directorist-search-modal__contents__header">
		<h3 class="directorist-search-modal__contents__title">More Filters</h3>
		<button class="directorist-search-modal__contents__btn directorist-search-modal__contents__btn--close">
			<?php directorist_icon( 'fas fa-times' ); ?>
		</button>
		<span class="directorist-search-modal__minimizer"></span>
	</div>
	<div class="directorist-search-modal__contents__body">

		<?php if ( isset( $searchform->form_data[1]['fields'] ) && is_array( $searchform->form_data[1]['fields'] ) ) : ?>
			
			<?php foreach ( $searchform->form_data[1]['fields'] as $field ): ?>

				<?php if (  ! in_array( $field['field_key'], $searchform->assign_to_category()['custom_field_key'] ) ) { ?>

					<div class="directorist-advanced-filter__advanced__element directorist-search-field-<?php echo esc_attr( $field['widget_name'] )?>"><?php $searchform->field_template( $field ); ?></div>

				<?php } ?>

			<?php endforeach; ?>

		<?php endif; ?>
	</div>
	<div class="directorist-search-modal__contents__footer">
		<?php $searchform->buttons_template(); ?>
	</div>
</div>