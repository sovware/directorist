<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listing_img            = atbdp_get_listing_attachment_ids( $listing_form->get_add_listing_id() );
$maximum                = ! empty( $data['max'] ) ? $data['max'] : $data['max_image_limit'];
$unlimited              = ! empty( $data['unlimited'] ) ? $data['unlimited'] : '';
$limit					= $unlimited ? '0' : $maximum;
$max_file_size          = (float) $data['max_per_image_limit'];
$max_total_file_size    = (float) $data['max_total_image_limit'];
$max_file_size_kb       = $max_file_size * 1024;
$max_total_file_size_kb = $max_total_file_size * 1024;
$required               = (bool) $data['required'];
$select_files_label     = ! empty( $data['select_files_label'] ) ? $data['select_files_label'] : __( 'Select Files', 'directorist' ); 

$accepted_mime_types       = directorist_get_mime_types( 'image', 'extension' );
$accepted_mime_types_upper = array_map( 'strtoupper', $accepted_mime_types ) ;
$accepted_mime_types       = array_merge( $accepted_mime_types, $accepted_mime_types_upper );

$img_upload_data = [
	'type'               => join( ', ', $accepted_mime_types ),
	'max_num_of_img'     => $limit,
	'max_total_img_size' => $max_total_file_size_kb,
	'is_required'        => $required,
	'max_size_per_img'   => $max_file_size_kb,
];

$img_upload_data = json_encode( $img_upload_data );
?>

<div class="directorist-form-group directorist-form-image-upload-field">
	<div class="ez-media-uploader directorist-image-upload" data-uploader="<?php echo esc_attr( $img_upload_data ); ?>">
		<div class="ezmu__loading-section ezmu--show">
			<span class="ezmu__loading-icon"><span class="ezmu__loading-icon-img-bg"></span></span>
		</div>
		<div class="ezmu__old-files">
			<?php
			if ( ! empty( $listing_img ) ) {
				foreach ( $listing_img as $image ) {
					$url  = wp_get_attachment_image_url( $image, 'large' );
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
			<span class="ezmu-dictionary-label-drag-n-drop"><?php esc_html_e( 'Drag and drop an image', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-label-or"><?php esc_html_e( 'or', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-label-select-files"><?php echo esc_html( $select_files_label ); ?></span>
			<span class="ezmu-dictionary-label-to"><?php esc_html_e( 'or drag and drop image here', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-label-add-more"><?php esc_html_e( 'Add More', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-alert-max-file-size"><?php esc_html_e( 'Maximum limit for a file is  __DT__', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-alert-max-total-file-size"><?php esc_html_e( 'Maximum limit for total file size is __DT__', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-alert-min-file-items"><?php esc_html_e( 'Minimum __DT__ file is required', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-alert-max-file-items"><?php esc_html_e( 'Maximum limit for total file is __DT__', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-info-max-file-size"><?php esc_html_e( 'Maximum allowed size per file is __DT__', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-info-max-total-file-size"><?php esc_html_e( 'Maximum total allowed file size is __DT__', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-info-type"></span>
			<span class="ezmu-dictionary-info-min-file-items"><?php esc_html_e( 'Minimum __DT__ file is required', 'directorist' ); ?></span>
			<span class="ezmu-dictionary-info-max-file-items">
				<?php echo !empty($unlimited) ? esc_html__( 'Unlimited images with this plan!', 'directorist' ) : ( ( $limit > 1 ) ? esc_html__('Maximum __DT__ files are allowed', 'directorist') : esc_html__( 'Maximum __DT__ file is allowed', 'directorist' ) ); ?>
			</span>
		</div>
	</div>
</div>
