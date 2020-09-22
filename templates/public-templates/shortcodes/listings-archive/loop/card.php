<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */

$card_data = $listings->loop['directorist_options']['card_fields'];
?>
<div class="atbd_single_listing atbd_listing_card <?php echo esc_attr( $listings->info_display_in_single_line ); ?>">
	<article class="atbd_single_listing_wrapper <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">
		
		<?php if ( $listings->display_preview_image ): ?>
			<figure class="atbd_listing_thumbnail_area">
				<div class="directorist-cover-top-left"></div>
				<div class="directorist-cover-top-right"></div>
				<div class="directorist-cover-bottom-left"></div>
				<div class="directorist-cover-bottom-right"></div>
			</figure>
		<?php endif; ?>

		<div class="atbd_listing_info">
			<div class="directorist-card-content-top"></div>
			<div class="directorist-card-content-after-top"></div>
			<div class="directorist-card-content-meta">
				<ul>
					<li></li>
				</ul>
			</div>
			<div class="directorist-card-footer">
				<div class="directorist-card-footer-left"></div>
				<div class="directorist-card-footer-right"></div>
			</div>
		</div>
		
	</article>
</div>