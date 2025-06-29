<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$loop_fields = $listings->loop['card_fields']['template_data']['grid_view_with_thumbnail'];
?>

<article class="directorist-listing-single directorist-listing-single--bg directorist-listing-card directorist-listing-has-thumb <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">

	<div class="directorist-listing-single__thumb">

		<?php
		$listings->loop_thumb_card_template();
		$listings->render_loop_fields($loop_fields['thumbnail']['avatar']);
		?>

		<div class="directorist-thumb-top-left"><?php $listings->render_loop_fields($loop_fields['thumbnail']['top_left']); ?></div>
		<div class="directorist-thumb-top-right"><?php $listings->render_loop_fields($loop_fields['thumbnail']['top_right']); ?></div>
		<div class="directorist-thumb-bottom-left"><?php $listings->render_loop_fields($loop_fields['thumbnail']['bottom_left']); ?></div>
		<div class="directorist-thumb-bottom-right"><?php $listings->render_loop_fields($loop_fields['thumbnail']['bottom_right']); ?></div>

	</div>

	<div class="directorist-listing-single__content">
		<section class="directorist-listing-single__info">
			<header class="directorist-listing-single__info__top">
				<?php $listings->render_loop_fields( $loop_fields['body']['top'], 'div', 'div' ); ?>
			</header>

			<ul class="directorist-listing-single__info__list">
				<?php $listings->render_loop_fields( $loop_fields['body']['bottom'], 'li', 'li' ); ?>
			</ul>

			<?php if ( ! empty( $loop_fields['body']['excerpt'] ) ) : ?>
				<?php $listings->render_loop_fields( $loop_fields['body']['excerpt'] ) ?>
			<?php endif; ?>
		</section>

		<footer class="directorist-listing-single__meta">
			<div class="directorist-listing-single__meta__left"><?php $listings->render_loop_fields($loop_fields['footer']['left']); ?></div>
			<div class="directorist-listing-single__meta__right"><?php $listings->render_loop_fields($loop_fields['footer']['right']); ?></div>
		</footer>
	</div>

</article>