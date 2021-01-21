<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.7
 */

$loop_fields = $listings->loop['card_fields']['template_data']['grid_view_without_thumbnail'];
?>

<div class="atbdp_column atbdp-col-<?php echo esc_attr( $listings->columns ); ?>">
	<div class="atbd_single_listing directorist-card-no-thumbnail atbd_listing_card <?php echo esc_attr( $listings->info_display_in_single_line ); ?>">
		<article class="atbd_single_listing_wrapper <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">
			<div class="directorist-card-nothumb-area">
				<div class="directorist-card-nothumb-area__left">
					<div class="directorist-card-nothumb-1"><?php $listings->render_loop_fields($loop_fields['body']['avatar']); ?></div>
					<div class="directorist-card-nothumb-2"><?php $listings->render_loop_fields($loop_fields['body']['title']); ?></div>
				</div>
				<div class="directorist-card-nothumb-area__right">
					<div class="directorist-card-nothumb-3"><?php $listings->render_loop_fields($loop_fields['body']['quick_actions']); ?></div>
				</div>
			</div>

			<div class="directorist-card-nothumb-info"><?php $listings->render_loop_fields($loop_fields['body']['quick_info']); ?></div>

			<div class="atbd_listing_info">

				<div class="atbd_content_upper">
					<div class="atbd_listing_data_list"><ul><?php $listings->render_loop_fields($loop_fields['body']['bottom'], '<li>', '</li>'); ?></ul></div>
					<div class="excerpt-area"><?php $listings->render_loop_fields($loop_fields['body']['excerpt']); ?></div>
				</div>

				<div class="atbd_listing_bottom_content">
					<div class="atbd_content_left"><?php $listings->render_loop_fields($loop_fields['footer']['left']); ?></div>
					<div class="atbd_content_right"><?php $listings->render_loop_fields($loop_fields['footer']['right']); ?></div>
				</div>
				
			</div>
			
		</article>
	</div>
</div>