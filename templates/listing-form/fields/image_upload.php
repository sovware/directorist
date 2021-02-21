<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$p_id                	= $listing_form->get_add_listing_id();
$listing_img            = atbdp_get_listing_attachment_ids( $p_id );


$limit                  = !empty( $data['max'] ) ? $data['max'] : $data['max_image_limit'];
$unlimited              = !empty( $data['unlimited'] ) ? $data['unlimited'] : '';
$max_file_size          = $data['max_per_image_limit'];
$max_total_file_size    = $data['max_total_image_limit'];
$max_file_size_kb       = (float) $max_file_size * 1024;//
$max_total_file_size_kb = (float) $max_total_file_size * 1024;//
$required               = $data['required'] ? '1' : '';
?>

<div class="directorist-form-group directorist-form-image-upload-field">

	<div id="_listing_gallery" 
		class="ez-media-uploader" 
		data-type="jpg, jpeg, png, gif" 
		data-max-file-items="<?php echo $limit; ?>"
        data-max-total-file-size="<?php echo $max_total_file_size_kb; ?>"
        data-min-file-items="<?php echo esc_attr( $required ); ?>"
        data-max-file-size="<?php echo esc_attr( $max_file_size_kb ); ?>"
		data-show-alerts="0">

		<div class="ezmu__loading-section ezmu--show">
			<span class="ezmu__loading-icon"><span class="ezmu__loading-icon-img-bg"></span></span>
		</div>

		<div class="ezmu__old-files">

			<?php
			if ( !empty( $listing_img ) ) {
				foreach ( $listing_img as $image ) {
					$url = wp_get_attachment_image_url( $image, 'full' );
					$size = filesize(get_attached_file( $image ) );
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
			<span class="ezmu-dictionary-label-select-files"><?php echo esc_html( $data['select_files_label'] ); ?></span>
			<span class="ezmu-dictionary-label-add-more"><?php esc_html_e( 'Add More', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-alert-max-file-size"><?php esc_html_e( 'Maximum limit for a file is  __DT__', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-alert-max-total-file-size"><?php esc_html_e( 'Maximum limit for total file size is __DT__', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-alert-min-file-items"><?php esc_html_e( 'Minimum __DT__ file is required', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-alert-max-file-items"><?php esc_html_e( 'Maximum limit for total file is __DT__', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-info-max-file-size"><?php esc_html_e( 'Maximum allowed size per file is __DT__', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-info-max-total-file-size"><?php esc_html_e( 'Maximum total allowed file size is __DT__', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-info-type" data-show='0'></span>
			<span class="ezmu-dictionary-info-min-file-items"><?php esc_html_e( 'Minimum __DT__ file is required', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-info-max-file-items" data-featured="<?php echo !empty($unlimited) ? '1' : ''; ?>">
			<?php echo !empty($unlimited) ? esc_html__( 'Unlimited images with this plan!', 'directorist' ) : ( ( $limit > 1 ) ? esc_html__('Maximum __DT__ files are allowed', 'directorist') : esc_html__( 'Maximum __DT__ file is allowed', 'directorist' ) ); ?></span>
		</div>

	</div>

</div>