<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="directorist-contact-fields atbdp_info_module">
	<div class="atbdp_info_module">
		<?php
		/**
		 * It fires after the google map preview area
		 * @param string $type Page type.
		 * @param array $listing_info Information of the current listing
		 * @since 4.4.7
		 **/
		do_action('atbdp_edit_before_video_field', 'add_listing_page_frontend', $listing_info, $p_id);
		?>
	</div>
</div>

<?php if ($plan_video || $plan_slider) { ?>
	<div class="atbd_content_module" id="atbdp_front_media_wrap">
		<div class="atbd_content_module_title_area">
			<div class="atbd_area_title">
				<h4><?php echo esc_html($title);?></h4>
			</div>
		</div>

		<div class="atbdb_content_module_contents atbdp_video_field">
			<?php if ($plan_slider) { ?>
				<div id="_listing_gallery" class="ez-media-uploader" data-type="jpg, jpeg, png, gif" data-max-file-items="<?php echo esc_attr( $max_file_items ); ?>" data-min-file-items="<?php echo esc_attr( $min_file_items ); ?>" data-max-file-size="<?php echo esc_attr( $max_file_size_kb ); ?>" data-max-total-file-size="<?php echo esc_attr( $max_total_file_size_kb ); ?>" data-show-alerts="0">

					<div class="ezmu__loading-section ezmu--show">
						<span class="ezmu__loading-icon"><span class="ezmu__loading-icon-img-bg"></span></span>
					</div>

					<div class="ezmu__old-files">
						<?php
						if (!empty($listing_img)) {
							foreach ($listing_img as $image) {
								$url = wp_get_attachment_image_url($image, 'full');
								$size = filesize(get_attached_file($image));
								?>
								<span class="ezmu__old-files-meta" data-attachment-id="<?php echo esc_attr($image); ?>" data-url="<?php echo esc_url($url); ?>" data-size="<?php echo esc_attr($size / 1024); ?>" data-type="image"></span>
								<?php
							}
						}
						?>
					</div>

					<div class="ezmu-dictionary">
						<span class="ezmu-dictionary-label-drop-here"><?php esc_html_e('Drop Here', 'directorist') ?></span>
						<span class="ezmu-dictionary-label-featured"><?php esc_html_e('Preview', 'directorist') ?></span>
						<span class="ezmu-dictionary-label-drag-n-drop"><?php esc_html_e('Drag & Drop', 'directorist') ?></span>
						<span class="ezmu-dictionary-label-or"><?php esc_html_e('or', 'directorist') ?></span>
						<span class="ezmu-dictionary-label-select-files"><?php echo esc_html($gallery_label); ?></span>
						<span class="ezmu-dictionary-label-add-more"><?php esc_html_e('Add More', 'directorist') ?></span>

						<span class="ezmu-dictionary-alert-max-file-size"><?php esc_html_e('Maximum limit for a file is  __DT__', 'directorist') ?></span>
						<span class="ezmu-dictionary-alert-max-total-file-size"><?php esc_html_e('Maximum limit for total file size is __DT__', 'directorist') ?></span>
						<span class="ezmu-dictionary-alert-min-file-items"><?php esc_html_e('Minimum __DT__ file is required', 'directorist') ?></span>
						<span class="ezmu-dictionary-alert-max-file-items"><?php esc_html_e('Maximum limit for total file is __DT__', 'directorist') ?></span>

						<span class="ezmu-dictionary-info-max-file-size"><?php esc_html_e('Maximum allowed size per file is __DT__', 'directorist') ?></span>
						<span class="ezmu-dictionary-info-max-total-file-size"><?php esc_html_e('Maximum total allowed file size is __DT__', 'directorist') ?></span>

						<span class="ezmu-dictionary-info-type" data-show='0'></span>

						<span class="ezmu-dictionary-info-min-file-items"><?php esc_html_e('Minimum __DT__ file is required', 'directorist') ?></span>

						<span class="ezmu-dictionary-info-max-file-items" data-featured="<?php echo !empty($slider_unl) ? '1' : ''; ?>"><?php echo !empty($slider_unl) ? __('Unlimited images with this plan!', 'directorist') : __('Maximum __DT__ file is allowed', 'directorist'); ?></span>
					</div>
				</div>
				<?php
			}

            /**
             * @since 4.7.1
             * It fires after the tag field
             */
            do_action('atbdp_add_listing_after_listing_slider', 'add_listing_page_frontend', $listing_info);

            if ($plan_video) { ?>
            	<div class="form-group">
            		<label for="videourl"><?php echo wp_kses_post( $video_label_html ); ?></label>
            		<input type="text" id="videourl" name="videourl" value="<?php echo esc_attr( $videourl ); ?>" class="form-control directory_field" placeholder="<?php echo esc_attr($video_placeholder); ?>"/>
            	</div>
            	
            	<?php
            	do_action('atbdp_video_field', $p_id);
            }
            ?>
        </div>
    </div>
    <?php
}