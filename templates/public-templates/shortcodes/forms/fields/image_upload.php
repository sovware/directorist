<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$plan_slider = true;
global $wp;
global $pagenow;
$current_url = home_url( add_query_arg( array(), $wp->request ) );
$fm_plans    = '';
$listing_id  = '';

if ( ( strpos( $current_url, '/edit/' ) !== false ) && ( $pagenow = 'at_biz_dir' ) ) {
	$listing_id = substr( $current_url, strpos( $current_url, '/edit/' ) + 6 );
	$fm_plans   = get_post_meta( $listing_id, '_fm_plans', true );
}

if ( is_fee_manager_active() ) {
	$plan_slider = is_plan_allowed_slider( $fm_plans );
}
?>

<div class="form-group" id="directorist-image_upload-field">
	<?php $form->add_listing_label_template( $data );?>

	<div class="atbdb_content_module_contents atbdp_video_field">
		<?php 
		if ( $plan_slider ) {
			$min_file_items = !empty(get_directorist_option('require_gallery_img')) ? '1' : '';
			?>
			<div id="_listing_gallery" class="ez-media-uploader" data-type="jpg, jpeg, png, gif" data-max-file-items="<?php echo esc_attr( $max_image_limit ); ?>" data-min-file-items="<?php echo esc_attr( $min_file_items ); ?>" data-max-file-size="<?php echo esc_attr( $max_per_image_limit ); ?>" data-max-total-file-size="<?php echo esc_attr( $max_total_image_limit ); ?>" data-show-alerts="0">

				<div class="ezmu__loading-section ezmu--show">
					<span class="ezmu__loading-icon"><span class="ezmu__loading-icon-img-bg"></span></span>
				</div>

				<div class="ezmu__old-files">
					<?php
					$listing_img = atbdp_get_listing_attachment_ids(get_query_var('atbdp_listing_id', 0));
					if ( ! empty( $listing_img ) ) {
						foreach ( $listing_img as $image ) {
							$url  = wp_get_attachment_image_url( $image, 'full' );
							$size = filesize( get_attached_file( $image ) );
							?>
							<span class="ezmu__old-files-meta" data-attachment-id="<?php echo esc_attr( $image ); ?>" data-url="<?php echo esc_url( $url ); ?>" data-size="<?php echo esc_attr( $size / 1024 ); ?>" data-type="image"></span>
							<?php
						}
					}
					?>
				</div>

				<div class="ezmu-dictionary">
					<span class="ezmu-dictionary-label-drop-here"><?php esc_html_e( 'Drop Here', 'directorist' ); ?></span>
					<span class="ezmu-dictionary-label-featured"><?php esc_html_e( 'Preview', 'directorist' ); ?></span>
					<span class="ezmu-dictionary-label-drag-n-drop"><?php esc_html_e( 'Drag & Drop', 'directorist' ); ?></span>
					<span class="ezmu-dictionary-label-or"><?php esc_html_e( 'or', 'directorist' ); ?></span>
					<span class="ezmu-dictionary-label-select-files"><?php echo esc_html( $label ); ?></span>
					<span class="ezmu-dictionary-label-add-more"><?php esc_html_e( 'Add More', 'directorist' ); ?></span>

					<span class="ezmu-dictionary-alert-max-file-size">
						<?php esc_html_e( 'Maximum limit for a file is  __DT__', 'directorist' ); ?>
					</span>
					<span class="ezmu-dictionary-alert-max-total-file-size">
						<?php esc_html_e( 'Maximum limit for total file size is __DT__', 'directorist' ); ?>
					</span>
					<span class="ezmu-dictionary-alert-min-file-items"><?php esc_html_e( 'Minimum __DT__ file is required', 'directorist' ); ?></span>
					<span class="ezmu-dictionary-alert-max-file-items">
						<?php esc_html_e( 'Maximum limit for total file is __DT__', 'directorist' ); ?>
					</span>

					<span class="ezmu-dictionary-info-max-file-size">
						<?php esc_html_e( 'Maximum allowed size per file is __DT__', 'directorist' ); ?>
					</span>
					<span class="ezmu-dictionary-info-max-total-file-size">
						<?php esc_html_e( 'Maximum total allowed file size is __DT__', 'directorist' ); ?>
					</span>

					<span class="ezmu-dictionary-info-type" data-show='0'></span>

					<span class="ezmu-dictionary-info-min-file-items"><?php esc_html_e( 'Minimum __DT__ file is required', 'directorist' ); ?></span>

					<span class="ezmu-dictionary-info-max-file-items" data-featured="<?php echo ! empty( $slider_unl ) ? '1' : ''; ?>">
						<?php echo ! empty( $slider_unl ) ? __( 'Unlimited images with this plan!', 'directorist' ) : __( 'Maximum __DT__ file is allowed', 'directorist' ); ?>
					</span>
				</div>
			</div>
			<?php
		}

		do_action( 'atbdp_add_listing_after_listing_slider', 'add_listing_page_frontend', $listing_info );
		?>
	</div>
</div>