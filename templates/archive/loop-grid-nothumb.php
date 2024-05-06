<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$loop_fields = $listings->loop['card_fields']['template_data']['grid_view_without_thumbnail'];
?>

<article class="directorist-listing-single directorist-listing-single--bg directorist-listing-card directorist-listing-no-thumb <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">

	<section class="directorist-listing-single__header">

		<figure class="directorist-listing-single__header__left">
			<?php $listings->render_loop_fields($loop_fields['body']['avatar']); ?>
		</figure>

		<header class="directorist-listing-single__header__title">
			<?php $listings->render_loop_fields($loop_fields['body']['title']); ?>
		</header>

		<div class="directorist-listing-single__header__right">
			<div class="directorist-listing-single__action">
				<?php $listings->render_loop_fields($loop_fields['body']['quick_actions']); ?>
			</div>
		</div>

	</section>

	<?php if ( ! empty( $loop_fields['body']['quick_info'] ) ) : ?>
		<div class="directorist-listing-single__info">
			<?php $listings->render_loop_fields( $loop_fields['body']['quick_info'] ); ?>
		</div>
	<?php endif; ?>

	<section class="directorist-listing-single__content">
		<ul class="directorist-listing-single__info__list"><?php $listings->render_loop_fields($loop_fields['body']['bottom'], '', ''); ?></ul>

		<?php if ( ! empty( $loop_fields['body']['excerpt'] ) ) : ?>
			<?php $listings->render_loop_fields( $loop_fields['body']['excerpt'] ) ?>
		<?php endif; ?>
	</section>

	<footer class="directorist-listing-single__meta">
		<div class="directorist-listing-single__meta__left"><?php $listings->render_loop_fields($loop_fields['footer']['left']); ?></div>
		<div class="directorist-listing-single__meta__right"><?php $listings->render_loop_fields($loop_fields['footer']['right']); ?></div>
	</footer>

</article>