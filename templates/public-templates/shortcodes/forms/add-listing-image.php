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
            <!--Image Uploader-->
            <?php if ($plan_slider) {
                $plan_image = get_directorist_option('max_gallery_image_limit', 5);
                $slider_unl = '';
                if (is_fee_manager_active()) {
                    $selected_plan = selected_plan_id();
                    $planID = !empty($selected_plan) ? $selected_plan : $fm_plan;
                    $allow_slider = is_plan_allowed_slider($planID);
                    $slider_unl = is_plan_slider_unlimited($planID);
                    if (!empty($allow_slider) && empty($slider_unl)) {
                        $plan_image = is_plan_slider_limit($planID);
                    }
                }

                $max_file_size = get_directorist_option('max_gallery_upload_size_per_file', 0);
                $max_file_size_kb = (float)$max_file_size * 1024;
                
                $max_total_file_size = get_directorist_option('max_gallery_upload_size', 4);
                $max_total_file_size_kb = (float)$max_total_file_size * 1024;

                $req_gallery_image = get_directorist_option('require_gallery_img');
                $gallery_label = get_directorist_option('gallery_label', __('Select Files', 'directorist'));
                ?>
                <div id="_listing_gallery" class="ez-media-uploader"
                     data-max-file-items="<?php echo !empty($slider_unl) ? '999' : $plan_image; ?>"
                     data-min-file-items="<?php echo !empty($req_gallery_image) ? '1' : ''; ?>"
                     data-max-file-size="<?php echo $max_file_size_kb; ?>"
                     data-max-total-file-size="<?php echo $max_total_file_size_kb; ?>"
                     data-show-alerts="0">
                    <div class="ezmu__loading-section ezmu--show">
                        <span class="ezmu__loading-icon">
                          <span class="ezmu__loading-icon-img-bg"></span>
                        </span>
                    </div>
                    <!--old files-->
                    <div class="ezmu__old-files">
                        <?php
                        if (!empty($listing_img)) {
                            foreach ($listing_img as $image) {
                                $url = wp_get_attachment_image_url($image, 'full');
                                $size = filesize(get_attached_file($image));
                                ?>
                                <span
                                        class="ezmu__old-files-meta"
                                        data-attachment-id="<?php echo esc_attr($image); ?>"
                                        data-url="<?php echo esc_url($url); ?>"
                                        data-size="<?php echo esc_attr($size / 1024); ?>"
                                        data-type="image"
                                ></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <!-- translatable string-->
                    <div class="ezmu-dictionary">
                        <!-- Label Texts -->
                        <span class="ezmu-dictionary-label-drop-here"><?php echo __('Drop Here', 'directorist') ?></span>
                        <span class="ezmu-dictionary-label-featured"><?php echo __('Preview', 'directorist') ?></span>
                        <span class="ezmu-dictionary-label-drag-n-drop"><?php echo __('Drag & Drop', 'directorist') ?></span>
                        <span class="ezmu-dictionary-label-or"><?php echo __('or', 'directorist') ?></span>
                        <span class="ezmu-dictionary-label-select-files"><?php echo $gallery_label ? $gallery_label : __('Select Files', 'directorist'); ?></span>
                        <span class="ezmu-dictionary-label-add-more"><?php echo __('Add More', 'directorist') ?></span>
                        <!-- Alert Texts -->
                        <span class="ezmu-dictionary-alert-max-file-size">
                            <?php echo __('Maximum limit for a file is  __DT__', 'directorist') ?>
                        </span>
                        <span class="ezmu-dictionary-alert-max-total-file-size">
                            <?php echo __('Maximum limit for total file size is __DT__', 'directorist') ?>
                        </span>
                        <span class="ezmu-dictionary-alert-min-file-items">
                            <?php echo __('Minimum __DT__ file is required', 'directorist') ?>
                        </span>
                        <span class="ezmu-dictionary-alert-max-file-items">
                            <?php echo __('Maximum limit for total file is __DT__', 'directorist') ?>
                        </span>

                        <!-- Info Text -->
                        <span class="ezmu-dictionary-info-max-file-size"><?php echo __('Maximum allowed size per file is __DT__', 'directorist') ?></span>
                        <span class="ezmu-dictionary-info-max-total-file-size"><?php echo __('Maximum total allowed file size is __DT__', 'directorist') ?></span>

                        <span class="ezmu-dictionary-info-type"
                              data-show='0'></span>

                        <span class="ezmu-dictionary-info-min-file-items">
<?php echo __('Minimum __DT__ file is required', 'directorist') ?></span>

                        <span class="ezmu-dictionary-info-max-file-items"
                              data-featured="<?php echo !empty($slider_unl) ? '1' : ''; ?>">
                            <?php echo !empty($slider_unl) ? __('Unlimited images with this plan!', 'directorist') : __('Maximum __DT__ file is allowed', 'directorist'); ?></span>
                    </div>
                </div>
            <?php } ?>
            <?php
            /**
             * @since 4.7.1
             * It fires after the tag field
             */
            do_action('atbdp_add_listing_after_listing_slider', 'add_listing_page_frontend', $listing_info);
            ?>
            <?php
            if ($plan_video) {
                ?>
                <div class="form-group">
                    <label for="videourl"><?php esc_html_e($video_label . ':', 'directorist'); echo $require_video ? '<span class="atbdp_make_str_red">*</span>' : ''; ?></label>
                    <input type="text" id="videourl" name="videourl" value="<?php echo !empty($videourl) ? esc_url($videourl) : ''; ?>" class="form-control directory_field" placeholder="<?php echo esc_attr($video_placeholder); ?>"/>
                </div>
                <?php do_action('atbdp_video_field', $p_id);
            } ?>
        </div>
    </div>
    <?php
}