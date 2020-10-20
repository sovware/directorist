<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.7
 */

$card_fields = $listings->loop['card_fields'];
?>
<div class="atbd_single_listing atbd_listing_card <?php echo esc_attr( $listings->info_display_in_single_line ); ?>">
	<article class="atbd_single_listing_wrapper <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">
		
		<?php if ( $listings->display_preview_image ): ?>
			<figure class="atbd_listing_thumbnail_area">
				<div class="atbd_listing_image">
					<?php
					$listings->loop_thumb_card_template();
					$listings->render_card_fields($card_fields, 'cover-avatar');
					?>
				</div>
				<div class="directorist-cover-top-left"><?php $listings->render_card_fields($card_fields, 'cover-top-left'); ?></div>
				<div class="directorist-cover-top-right"><?php $listings->render_card_fields($card_fields, 'cover-top-right'); ?></div>
				<div class="directorist-cover-bottom-left"><?php $listings->render_card_fields($card_fields, 'cover-bottom-left'); ?></div>
				<div class="directorist-cover-bottom-right"><?php $listings->render_card_fields($card_fields, 'cover-bottom-right'); ?></div>
			</figure>
		<?php endif; ?>

		<div class="atbd_listing_info">

			<div class="atbd_content_upper">
				<div class="directorist-card-body-top"><?php $listings->render_card_fields($card_fields, 'body-top'); ?></div>
				<div class="atbd_listing_data_list"><ul><?php $listings->render_card_fields($card_fields, 'body-bottom', '<li>', '</li>'); ?></ul></div>				
			</div>

			<div class="atbd_listing_bottom_content">
				<div class="atbd_content_left"><?php $listings->render_card_fields($card_fields, 'footer-left'); ?></div>
				<div class="atbd_content_right"><?php $listings->render_card_fields($card_fields, 'footer-right'); ?></div>
			</div>
		</div>
		
	</article>
</div>