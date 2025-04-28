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
			<div class="directorist-listing-single__info__top">
				<div class="directorist-listing-single__info__top__left">
					<?php $listings->render_loop_fields($loop_fields['top']['quick_actions']); ?>
				</div>
				<div class="directorist-listing-single__info__top__right">
					<?php $listings->render_loop_fields($loop_fields['top']['quick_info']); ?>
				</div>
			</div>

			<?php if ( ! empty( $loop_fields['body']['title'] ) ) : ?>
				<header class="directorist-listing-single__info__title">
					<?php $listings->render_loop_fields( $loop_fields['body']['title'] ); ?>
				</header>
			<?php endif; ?>

			<?php if ( ! empty( $loop_fields['body']['tagline'] ) ) : ?>
				<div class="directorist-listing-single__info__tagline">
					<?php $listings->render_loop_fields( $loop_fields['body']['tagline'] ); ?>
				</div>
			<?php endif; ?>	

			<?php if ( ! empty( $loop_fields['body']['rating'] ) ) : ?>
				<div class="directorist-listing-single__info__rating">
					<?php $listings->render_loop_fields( $loop_fields['body']['rating'] ); ?>
				</div>
			<?php endif; ?>
			<ul class="directorist-listing-single__info__list">
				<?php $listings->render_loop_fields($loop_fields['body']['bottom'], '', ''); ?>
			</ul>
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