<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div>

	<div>

		<?php if (!empty($listing_details_text)) : ?>
			<div class="atbd_area_title">
				<h4><span class="<?php atbdp_icon_type(true); ?>-file-text atbd_area_icon"></span><?php echo esc_html($listing_details_text); ?></h4>
			</div>
		<?php endif; ?>

		<?php $listing->header_actions_template(); ?>
	</div>

	<div class="atbdb_content_module_contents">

		<?php if (apply_filters('atbdp_single_listing_slider', true)) {
			$listing->the_slider();
		} ?>
		<div class="atbd_listing_detail">

			<?php $listing->header_meta_template(); ?>

			<div class="<?php echo apply_filters('atbdp_single_listing_title_class', 'atbd_listing_title'); ?>">
				<?php
				/**
				 * @since 5.0.5
				 */
				echo apply_filters('atbdp_listing_title', sprintf('<h2>%s</h2>', $p_title));

				do_action('atbdp_single_listing_after_title', $listing_id);
				?>
			</div>

			<?php
			if (!empty($tagline) && !empty($display_tagline_field)) {
				/**
				 * @since 5.0.5
				 */
				echo apply_filters('atbdp_listing_tagline', sprintf('<p class="atbd_single_listing_tagline">%s</p>', $tagline));
			}

			echo apply_filters('atbdp_listing_content', sprintf('<div class="about_detail">%s</div>', $content));
			?>
		</div>
	</div>
</div>