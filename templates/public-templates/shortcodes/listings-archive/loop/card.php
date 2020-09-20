<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */

$card_data = $listings->loop['directorist_options']['card_fields'];
?>
<!-- listings-archive > loop > card -->
<div class="atbd_single_listing atbd_listing_card <?php echo esc_attr( $listings->info_display_in_single_line ); ?>">
	<article class="atbd_single_listing_wrapper <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">
		
		<?php if ( $listings->display_preview_image ): ?>
			<figure class="atbd_listing_thumbnail_area">
				<div class="atbd-cover-top-left"></div>
				<div class="atbd-cover-top-right"></div>
				<div class="atbd-cover-bottom-left"></div>
				<div class="atbd-cover-bottom-right"></div>
			</figure>
		<?php endif; ?>

		<div class="atbd_listing_info">
			<?php
			$listings->loop_top_content_template();
			$listings->loop_grid_bottom_content_template();
			?>
		</div>
		
	</article>
</div>