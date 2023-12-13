<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$loop_fields = $listings->loop['card_fields']['template_data']['grid_view_with_thumbnail'];
?>

<div class="directorist-listing-single directorist-listing-single--bg directorist-listing-card directorist-listing-has-thumb <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">

	<figure class="directorist-listing-single__thumb">

		<?php
		$listings->loop_thumb_card_template();
		$listings->render_loop_fields($loop_fields['thumbnail']['avatar']);
		?>

		<div class="directorist-thumb-top-left"><?php $listings->render_loop_fields($loop_fields['thumbnail']['top_left']); ?></div>
		<div class="directorist-thumb-top-right"><?php $listings->render_loop_fields($loop_fields['thumbnail']['top_right']); ?></div>
		<div class="directorist-thumb-bottom-left"><?php $listings->render_loop_fields($loop_fields['thumbnail']['bottom_left']); ?></div>
		<div class="directorist-thumb-bottom-right"><?php $listings->render_loop_fields($loop_fields['thumbnail']['bottom_right']); ?></div>

	</figure>

	<div class="directorist-listing-single__content">

		<div class="directorist-listing-single__info">
			<div class="directorist-listing-single__info--top">
				<?php $listings->render_loop_fields($loop_fields['body']['top']); ?>
			</div>

			<ul class="directorist-listing-single__info__list">
				<?php $listings->render_loop_fields($loop_fields['body']['bottom'], '', ''); ?>
			</ul>

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