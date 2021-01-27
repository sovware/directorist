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

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$loop_fields = $listings->loop['card_fields']['template_data']['grid_view_with_thumbnail'];
?>

<div class="directorist-card directorist-card-has-thumb <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">

	<figure class="directorist-card-thumb-area">
		<div class="atbd_listing_image">
			<?php
			$listings->loop_thumb_card_template();
			$listings->render_loop_fields($loop_fields['thumbnail']['avatar']);
			?>
		</div>
		<div class="directorist-card-cover-top-left"><?php $listings->render_loop_fields($loop_fields['thumbnail']['top_left']); ?></div>
		<div class="directorist-card-cover-top-right"><?php $listings->render_loop_fields($loop_fields['thumbnail']['top_right']); ?></div>
		<div class="directorist-card-cover-bottom-left"><?php $listings->render_loop_fields($loop_fields['thumbnail']['bottom_left']); ?></div>
		<div class="directorist-card-cover-bottom-right"><?php $listings->render_loop_fields($loop_fields['thumbnail']['bottom_right']); ?></div>
	</figure>

	<div class="atbd_listing_info">

		<div class="atbd_content_upper">
			<div class="directorist-card-body-top"><?php $listings->render_loop_fields($loop_fields['body']['top']); ?></div>
			<div class="atbd_listing_data_list"><ul><?php $listings->render_loop_fields($loop_fields['body']['bottom'], '<li>', '</li>'); ?></ul></div>
			<div class="excerpt-area"><?php $listings->render_loop_fields($loop_fields['body']['excerpt']); ?></div>
		</div>

		<div class="atbd_listing_bottom_content">
			<div class="atbd_content_left"><?php $listings->render_loop_fields($loop_fields['footer']['left']); ?></div>
			<div class="atbd_content_right"><?php $listings->render_loop_fields($loop_fields['footer']['right']); ?></div>
		</div>

	</div>
</div>