<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="atbd_content_module">

	<div class="atbd_content_module_title_area">

		<?php if ( $section_title ) : ?>
			<h4><?php directorist_icon( $section_icon );?><?php echo esc_html( $section_title ); ?></h4>
		<?php endif; ?>

		<?php $listing->quick_actions_template(); ?>
	</div>

	<div class="atbdb_content_module_contents">

		<?php $listing->slider_template(); ?>

		<div>

			<?php $listing->quick_info_template(); ?>

			<?php if ( $display_title ): ?>
				<h2><?php echo esc_html( $listing->get_title() ); ?></h2>
			<?php endif; ?>

			<?php if ( $display_tagline && $listing->get_tagline() ): ?>
				<p class="atbd_single_listing_tagline"><?php echo esc_html( $listing->get_tagline() ); ?></p>
			<?php endif; ?>
 
			<?php if ( $display_content && $listing->get_contents() ): ?>
				<p class="about_detail"><?php echo wp_kses_post( $listing->get_contents() ); ?></p>
			<?php endif; ?>	

		</div>

	</div>

</div>