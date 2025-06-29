<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$loop_fields = $listings->loop['card_fields']['template_data']['grid_view_without_thumbnail'];

// Capture output for avatar
ob_start();
$listings->render_loop_fields($loop_fields['body']['avatar']);
$listing_avatar = ob_get_clean();

// Capture output for title
ob_start();
$listings->render_loop_fields($loop_fields['body']['title']);
$listing_title = ob_get_clean();

// Capture output for quick actions
ob_start();
$listings->render_loop_fields($loop_fields['body']['quick_actions']);
$quick_actions_field = ob_get_clean();

// Capture output for quick_info
ob_start();
$listings->render_loop_fields($loop_fields['body']['quick_info']);
$quick_info_field = ob_get_clean();
?>

<article class="directorist-listing-single directorist-listing-single--bg directorist-listing-card directorist-listing-no-thumb <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">

	<section class="directorist-listing-single__header">

		<?php if ( ! empty( $listing_avatar ) ) : ?>
			<figure class="directorist-listing-single__header__left">
				<?php echo $listing_avatar; ?>
			</figure>
		<?php endif; ?>
		
		<?php if ( ! empty( $listing_title ) ) : ?>
			<header class="directorist-listing-single__header__title">
				<?php echo $listing_title; ?>
			</header>
		<?php endif; ?>
		
		<?php if ( ! empty( $quick_actions_field ) ) : ?>
			<div class="directorist-listing-single__header__right">
				<div class="directorist-listing-single__action">
					<?php echo $quick_actions_field; ?>
				</div>
			</div>
		<?php endif; ?>

	</section>

	<?php if ( ! empty( $quick_info_field ) ) : ?>
		<div class="directorist-listing-single__info">
			<?php echo $quick_info_field; ?>
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