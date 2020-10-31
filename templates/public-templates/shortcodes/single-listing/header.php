<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<div class="atbd_content_module">

	<div class="atbd_content_module_title_area">

		<?php if ( $section_title ) : ?>
			<div class="atbd_area_title">
				<h4><span class="<?php atbdp_icon_type(true); ?>-file-text atbd_area_icon"></span><?php echo esc_html( $section_title ); ?></h4>
			</div>
		<?php endif; ?>

		<?php $listing->quick_actions_template(); ?>
	</div>

	<div class="atbdb_content_module_contents">

		<?php $listing->slider_template(); ?>

		<div class="atbd_listing_detail">

			<?php $listing->quick_info_template(); ?>


			<div class="<?php echo apply_filters('atbdp_single_listing_title_class', 'atbd_listing_title'); ?>">
				<?php
				/**
				 * @since 5.0.5
				 */
				echo apply_filters( 'atbdp_listing_title', sprintf( '<h2>%s</h2>', $listing->title ) );

				do_action( 'atbdp_single_listing_after_title', $listing->id );
				?>
			</div>

			<?php
			if ( !empty($listing->tagline) ) {
				/**
				 * @since 5.0.5
				 */
				echo apply_filters('atbdp_listing_tagline', sprintf( '<p class="atbd_single_listing_tagline">%s</p>', $listing->tagline ));
			}

			/**
			 * Fires after the title and sub title of the listing is rendered on the single listing page
			 *
			 * @since 1.0.0
			 */
			do_action('atbdp_after_listing_tagline');

			echo apply_filters('atbdp_listing_content', sprintf( '<div class="about_detail">%s</div>', $listing->get_contents() ));
			?>
		</div>
	</div>
</div>