<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-card directorist-single-listing-header">

	<div class="directorist-card__header directorist-flex directorist-align-center directorist-justify-content-between">

		<?php if ( $section_title ) : ?>
			<h4 class="directorist-card__header--title"><?php directorist_icon( $section_icon );?><?php echo esc_html( $section_title ); ?></h4>
		<?php endif; ?>

		<?php $listing->quick_actions_template(); ?>

	</div>

	<div class="directorist-card__body">

		<?php $listing->slider_template(); ?>

		<div class="directorist-listing-details">

			<?php $listing->quick_info_template(); ?>

			<?php if ( $display_title ): ?>
				<h2 class="directorist-listing-details__listing-title"><?php echo esc_html( $listing->get_title() ); ?></h2>
			<?php endif; ?>
			
			<?php do_action( 'directorist_single_listing_after_title', $listing->id ); ?>

			<?php if ( $display_tagline && $listing->get_tagline() ): ?>
				<p class="directorist-listing-details-tagline"><?php echo esc_html( $listing->get_tagline() ); ?></p>
			<?php endif; ?>
 
			<?php if ( $display_content && $listing->get_contents() ): ?>
				<div class="directorist-listing-details__text">
					<p><?php echo wp_kses_post( $listing->get_contents() ); ?></p>
				</div>
			<?php endif; ?>	

		</div>

	</div>

</div>