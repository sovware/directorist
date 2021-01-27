<?php
/**
 * @template_description1
 *
 * This template can be overridden by copying it to yourtheme/directorist/ @template_description2
 *
 * @author  wpWax
 * @since   6.6
 * @version 6.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$loop_fields = $listings->loop['list_fields']['template_data']['list_view_without_thumbnail'];
?>

<div class="directorist-list directorist-list-no-thumb <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">
	
	<div class="directorist-loop-content-area">

		<div class="directorist-loop-contents">
			<div class="directorist-loop-body-top"><?php $listings->render_loop_fields($loop_fields['body']['top']); ?></div>
			<div class="directorist-loop-info"><ul><?php $listings->render_loop_fields($loop_fields['body']['bottom'], '<li>', '</li>'); ?></ul></div>
			<div class="directorist-loop-excerpt"><?php $listings->render_loop_fields($loop_fields['body']['excerpt']); ?></div>
			<div class="directorist-loop-body-right"><?php $listings->render_loop_fields($loop_fields['body']['right']); ?></div>
		</div>

		<div class="directorist-loop-footer">
			<div class="directorist-loop-footer-left"><?php $listings->render_loop_fields($loop_fields['footer']['left']); ?></div>
			<div class="directorist-loop-footer-right"><?php $listings->render_loop_fields($loop_fields['footer']['right']); ?></div>
		</div>

	</div>

</div>