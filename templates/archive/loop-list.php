<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;
?>

<div class="directorist-listing-single directorist-listing-list directorist-listing-has-thumb <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">

	<figure class="directorist-listing-single__thumb">

		<?php $listings->loop_thumbnail_template(); ?>

		<div class="directorist-thumb-top-right"><?php $listings->render_fields( 'thumb-top-left' ); ?></div>

	</figure>

	<div class="directorist-listing-single__content">

		<div class="directorist-listing-single__info">

			<div class="directorist-listing-single__info--top"><?php $listings->render_fields( 'body-top' ); ?></div>

			<div class="directorist-listing-single__info--right"><?php $listings->render_fields( 'body-right' ); ?></div>

			<div class="directorist-listing-single__info--list">
				<ul>
					<?php $listings->render_fields( 'body-bottom', '<li>', '</li>' ); ?>
				</ul>
			</div>

			<div class="directorist-listing-single__info--excerpt"><?php $listings->render_fields( 'body-excerpt' ); ?></div>

		</div>

		<div class="directorist-listing-single__meta">

			<div class="directorist-listing-single__meta--left"><?php $listings->render_fields( 'footer-left' ); ?></div>

			<div class="directorist-listing-single__meta--right"><?php $listings->render_fields( 'footer-right' ); ?></div>

		</div>

	</div>

</div>