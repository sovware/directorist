<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$loop_fields = $listings->loop['card_fields']['template_data']['grid_view_without_thumbnail'];
?>

<div class="directorist-listing-single directorist-listing-single--bg directorist-listing-card directorist-listing-no-thumb <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">

	<div class="directorist-listing-single__header">

		<div class="directorist-listing-single__header__left">
			<?php $listings->render_loop_fields($loop_fields['body']['avatar']); ?>
		</div>

		<div class="directorist-listing-single__header__title">
			<?php $listings->render_loop_fields($loop_fields['body']['title']); ?>
		</div>

		<div class="directorist-listing-single__header__right">
			<div class="directorist-listing-single__action"><?php $listings->render_loop_fields($loop_fields['body']['quick_actions']); ?></div>
		</div>

	</div>

	<div class="directorist-listing-single__info"><?php $listings->render_loop_fields($loop_fields['body']['quick_info']); ?></div>

	<div class="directorist-listing-single__content">

		<div class="directorist-listing-single__content__body">
			<ul class="directorist-listing-single__info__list"><?php $listings->render_loop_fields($loop_fields['body']['bottom'], '', ''); ?></ul>

			<?php if ( ! empty( $loop_fields['body']['excerpt'] ) ) : ?>
				<div class="directorist-listing-single__info__excerpt">
					<?php $listings->render_loop_fields( $loop_fields['body']['excerpt'] ) ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="directorist-listing-single__meta">
			<div class="directorist-listing-single__meta--left"><?php $listings->render_loop_fields($loop_fields['footer']['left']); ?></div>
			<div class="directorist-listing-single__meta--right"><?php $listings->render_loop_fields($loop_fields['footer']['right']); ?></div>
		</div>

	</div>

</div>