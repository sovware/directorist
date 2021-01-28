<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$loop_fields = $listings->loop['card_fields']['template_data']['grid_view_without_thumbnail'];
?>

<div class="directorist-card directorist-card-no-thumb <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">

	<div class="directorist-loop-nothumb-area">
		<div class="directorist-nothumb-area-eft">
			<div class="directorist-nothumb-1"><?php $listings->render_loop_fields($loop_fields['body']['avatar']); ?></div>
			<div class="directorist-nothumb-2"><?php $listings->render_loop_fields($loop_fields['body']['title']); ?></div>
		</div>
		<div class="directorist-nothumb-area-right">
			<div class="directorist-nothumb-3"><?php $listings->render_loop_fields($loop_fields['body']['quick_actions']); ?></div>
		</div>
	</div>

	<div class="directorist-nothumb-info"><?php $listings->render_loop_fields($loop_fields['body']['quick_info']); ?></div>

	<div class="directorist-loop-content-area">

		<div class="directorist-loop-contents">
			<div class="directorist-loop-info"><ul><?php $listings->render_loop_fields($loop_fields['body']['bottom'], '<li>', '</li>'); ?></ul></div>
			<div class="directorist-loop-excerpt"><?php $listings->render_loop_fields($loop_fields['body']['excerpt']); ?></div>
		</div>

		<div class="directorist-loop-footer">
			<div class="directorist-loop-footer-left"><?php $listings->render_loop_fields($loop_fields['footer']['left']); ?></div>
			<div class="directorist-loop-footer-right"><?php $listings->render_loop_fields($loop_fields['footer']['right']); ?></div>
		</div>

	</div>

</div>