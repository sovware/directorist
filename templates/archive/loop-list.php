<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;

$card_view = $listings->card_view_data( 'list', true );
?>

<div class="directorist-listing-single directorist-listing-list directorist-listing-has-thumb <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">

	<figure class="directorist-listing-single__thumb">

		<?php $listings->loop_thumbnail_template(); ?>

		<div class="directorist-thumb-top-right"><?php $listings->render_card_view($card_view['thumbnail']['top_right']); ?></div>

	</figure>

	<div class="directorist-listing-single__content">

		<div class="directorist-listing-single__info">
			<div class="directorist-listing-single__info--top"><?php $listings->render_card_view($card_view['body']['top']); ?></div>
			<div class="directorist-listing-single__info--list"><ul><?php $listings->render_card_view($card_view['body']['bottom'], '<li>', '</li>'); ?></ul></div>
			<div class="directorist-listing-single__info--excerpt"><?php $listings->render_card_view($card_view['body']['excerpt']); ?></div>
			<div class="directorist-listing-single__info--right"><?php $listings->render_card_view($card_view['body']['right']); ?></div>
		</div>

		<div class="directorist-listing-single__meta">
			<div class="directorist-listing-single__meta--left"><?php $listings->render_card_view($card_view['footer']['left']); ?></div>
			<div class="directorist-listing-single__meta--right"><?php $listings->render_card_view($card_view['footer']['right']); ?></div>
		</div>

	</div>

</div>