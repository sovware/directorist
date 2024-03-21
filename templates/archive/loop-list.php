<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$loop_fields = $listings->loop['list_fields']['template_data']['list_view_with_thumbnail'];
?>

<article class="directorist-listing-single directorist-listing-single--bg directorist-listing-list directorist-listing-has-thumb <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">

	<div class="directorist-listing-single__thumb">
		<?php $listings->loop_thumb_card_template(); ?>
		<div class="directorist-thumb-top-right"><?php $listings->render_loop_fields($loop_fields['thumbnail']['top_right']); ?></div>
	</div>

	<section class="directorist-listing-single__content">

		<div class="directorist-listing-single__info">
			<div class="directorist-listing-single__info__top-right">
				<header class="directorist-listing-single__info__top">
					<?php $listings->render_loop_fields( $loop_fields['body']['top'], 'div', 'div' ); ?>
				</header>
				<div class="directorist-listing-single__info__right">
					<div class="directorist-listing-single__action">
						<?php $listings->render_loop_fields($loop_fields['body']['right']); ?>
					</div>
				</div>
			</div>
			<ul class="directorist-listing-single__info__list">
				<?php $listings->render_loop_fields($loop_fields['body']['bottom'], '', ''); ?>
			</ul>

			<?php if ( ! empty( $loop_fields['body']['excerpt'] ) ) : ?>
				<?php $listings->render_loop_fields( $loop_fields['body']['excerpt'] ) ?>
			<?php endif; ?>
		</div>

		<footer class="directorist-listing-single__meta">
			<div class="directorist-listing-single__meta__left"><?php $listings->render_loop_fields($loop_fields['footer']['left']); ?></div>
			<div class="directorist-listing-single__meta__right"><?php $listings->render_loop_fields($loop_fields['footer']['right']); ?></div>
		</footer>

	</section>
	<footer class="directorist-listing-single__mobile-view-meta">
		<div class="directorist-listing-single__meta">
			<div class="directorist-listing-single__meta__left"><?php $listings->render_loop_fields($loop_fields['footer']['left']); ?></div>
			<div class="directorist-listing-single__meta__right"><?php $listings->render_loop_fields($loop_fields['footer']['right']); ?></div>
		</div>
	</footer>

</article>