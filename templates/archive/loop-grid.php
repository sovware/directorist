<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$loop_fields = $listings->loop['card_fields']['template_data']['grid_view_with_thumbnail'];
?>

<div class="directorist-listing-card directorist-listing-card-has-thumb <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">

	<figure class="directorist-listing-card__thumb">
		<?php
		$listings->loop_thumb_card_template();
		$listings->render_loop_fields($loop_fields['thumbnail']['avatar']);
		?>
		<div class="directorist-thumb-top-left"><?php $listings->render_loop_fields($loop_fields['thumbnail']['top_left']); ?></div>
		<div class="directorist-thumb-top-right"><?php $listings->render_loop_fields($loop_fields['thumbnail']['top_right']); ?></div>
		<div class="directorist-thumb-bottom-left"><?php $listings->render_loop_fields($loop_fields['thumbnail']['bottom_left']); ?></div>
		<div class="directorist-thumb-bottom-right"><?php $listings->render_loop_fields($loop_fields['thumbnail']['bottom_right']); ?></div>
	</figure>

	<div class="directorist-listing-card__content">

		<div class="directorist-listing-card__info">
			<div class="directorist-listing-card__info--top"><?php $listings->render_loop_fields($loop_fields['body']['top']); ?></div>
			<div class="directorist-listing-card__info--list"><ul><?php $listings->render_loop_fields($loop_fields['body']['bottom'], '<li>', '</li>'); ?></ul></div>
			<div class="directorist-listing-card__info--excerpt"><?php $listings->render_loop_fields($loop_fields['body']['excerpt']); ?></div>
		</div>

		<div class="directorist-listing-card__meta">
			<div class="directorist-listing-card__meta--left"><?php $listings->render_loop_fields($loop_fields['footer']['left']); ?></div>
			<div class="directorist-listing-card__meta--right"><?php $listings->render_loop_fields($loop_fields['footer']['right']); ?></div>
		</div>

	</div>

</div>