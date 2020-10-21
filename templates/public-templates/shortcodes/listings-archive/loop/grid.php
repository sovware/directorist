<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.7
 */

$loop_fields = $listings->loop['card_fields'];
?>
<div class="atbdp_column atbdp-col-<?php echo esc_attr( $listings->columns ); ?>">
	<div class="atbd_single_listing atbd_listing_card <?php echo esc_attr( $listings->info_display_in_single_line ); ?>">
		<article class="atbd_single_listing_wrapper <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">
			
			<?php if ( $listings->display_preview_image ): ?>
				<figure class="atbd_listing_thumbnail_area">
					<div class="atbd_listing_image">
						<?php
						$listings->loop_thumb_card_template();
						$listings->render_loop_fields($loop_fields, 'card-cover-avatar');
						?>
					</div>
					<div class="atbd-card-cover-top-left"><?php $listings->render_loop_fields($loop_fields, 'card-cover-tl'); ?></div>
					<div class="atbd-card-cover-top-right"><?php $listings->render_loop_fields($loop_fields, 'card-cover-tr'); ?></div>
					<div class="atbd-card-cover-bottom-left"><?php $listings->render_loop_fields($loop_fields, 'card-cover-bl'); ?></div>
					<div class="atbd-card-cover-bottom-right"><?php $listings->render_loop_fields($loop_fields, 'card-cover-br'); ?></div>
				</figure>
			<?php endif; ?>

			<div class="atbd_listing_info">

				<div class="atbd_content_upper">
					<div class="atbd-card-body-top"><?php $listings->render_loop_fields($loop_fields, 'card-b-top'); ?></div>
					<div class="atbd_listing_data_list"><ul><?php $listings->render_loop_fields($loop_fields, 'card-b-bottom', '<li>', '</li>'); ?></ul></div>				
				</div>

				<div class="atbd_listing_bottom_content">
					<div class="atbd_content_left"><?php $listings->render_loop_fields($loop_fields, 'card-f-left'); ?></div>
					<div class="atbd_content_right"><?php $listings->render_loop_fields($loop_fields, 'card-f-right'); ?></div>
				</div>
				
			</div>
			
		</article>
	</div>
</div>