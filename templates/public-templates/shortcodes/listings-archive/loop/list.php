<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.7
 */

$loop_fields = $listings->loop['list_fields'];
?>
<div class="atbd_single_listing atbd_listing_list">
	<article class="atbd_single_listing_wrapper <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">

		<?php if ( $listings->display_preview_image ): ?>
			<figure class="atbd_listing_thumbnail_area">
				<?php $listings->loop_thumb_card_template(); ?>
				<div class="atbd-list-cover-top-right"><?php $listings->render_loop_fields($loop_fields['thumbnail']['top_right']); ?></div>
			</figure>
		<?php endif; ?>

		<div class="atbd_listing_info">

			<div class="atbd_content_upper">
				<div class="atbd-list-body-top"><?php $listings->render_loop_fields($loop_fields['body']['top']); ?></div>
				<div class="atbd_listing_data_list"><ul><?php $listings->render_loop_fields($loop_fields['body']['bottom'], '<li>', '</li>'); ?></ul></div>
				<div class="excerpt-area"><?php $listings->render_loop_fields($loop_fields['body']['excerpt']); ?></div>
				<div class="atbd-list-body-right"><?php $listings->render_loop_fields($loop_fields['body']['right']); ?></div>
			</div>

			<div class="atbd_listing_bottom_content">
				<div class="atbd_content_left"><?php $listings->render_loop_fields($loop_fields['footer']['left']); ?></div>
				<div class="atbd_content_right"><?php $listings->render_loop_fields($loop_fields['footer']['right']); ?></div>
			</div>
		</div>
		
	</article>
</div>