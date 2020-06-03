<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="atbdp_column atbdp-col-<?php echo esc_attr( $listings->columns ); ?>">
	<div class="atbd_single_listing atbd_listing_card">
		<article class="atbd_single_listing_wrapper <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">
			<figure class="atbd_listing_thumbnail_area">
				<?php $listings->loop_grid_thumbnail_template();?>
			</figure>

			<div class="atbd_listing_info">

				<?php
				$listings->loop_grid_top_content_template();
				$listings->loop_grid_bottom_content_template();
				
				/**
				 * @param mixed $footer_html
				 * @since
				 * @package Directorist
				 */
				apply_filters('atbdp_listings_footer_content', '');
				?>
				
			</div>
		</article>
	</div>
</div>