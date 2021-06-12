<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$post_id = ! empty( $data['field_key'] ) ? $data['field_key'] : rand();
// wp_enqueue_style( 'atbdp-pluploadcss' );
// wp_enqueue_script( 'atbdp-plupload-min' );
// wp_enqueue_script( 'atbdp-plupload' );
// wp_enqueue_script( 'atbdp-plupload' );
$file_types        = ! empty( $data['file_type'] ) ? $data['file_type'] : 'all_types';
$file_size         = ! empty( $data['file_size'] ) ? $data['file_size'] : '2mb';
$allowed_img_types = array( 'jpg', 'jpeg', 'jpe', 'gif', 'png' );
// place js config array for plupload
$plupload_init = array(
	'runtimes'            => 'html5,silverlight,html4',
	'browse_button'       => 'plupload-browse-button', // will be adjusted per uploader
	'container'           => 'plupload-upload-ui', // will be adjusted per uploader
	// 'drop_element' => 'dropbox', // will be adjusted per uploader
	'file_data_name'      => 'async-upload', // will be adjusted per uploader
	'multiple_queues'     => true,
	// 'max_file_size' => $file_size,
	'url'                 => admin_url( 'admin-ajax.php' ),
	'flash_swf_url'       => includes_url( 'js/plupload/plupload.flash.swf' ),
	'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
	'filters'             => array(
		array(
			'title'      => __( 'Allowed Files', 'directorist' ),
			'extensions' => '*',
		),
	),
	'multipart'           => true,
	'urlstream_upload'    => true,
	'multi_selection'     => false, // will be added per uploader
	// additional post data to send to our ajax hook
	'multipart_params'    => array(
		'_ajax_nonce' => wp_create_nonce( 'atbdp_attachment_upload' ), // will be added per uploader
		'action'      => 'atbdp_post_attachment_upload', // the ajax action name
		'imgid'       => 0, // will be added per uploader
	),
);

$text_value    = array(
	'atbdp_allowed_img_types' => ! empty( $allowed_img_types ) ? implode( ',', $allowed_img_types ) : '',
	'txt_all_files'           => __( 'Allowed files', 'directorist' ),
	'err_max_file_size'       => __( 'File size error : You tried to upload a file over %s', 'directorist' ),
	'err_file_type'           => __( 'File type error. Allowed file types: %s', 'directorist' ),
	'err_file_upload_limit'   => __( 'You have reached your upload limit of %s files.', 'directorist' ),
	'err_pkg_upload_limit'    => __( 'You may only upload %s files with this package, please try again.', 'directorist' ),
	'action_remove'           => __( 'Remove', 'directorist' ),
	'button_set'              => __( 'Set', 'directorist' ),
);
$thumb_img_arr = array();

if ( isset( $_REQUEST['pid'] ) && $_REQUEST['pid'] != '' ) {
	$thumb_img_arr = atbdp_get_images( $_REQUEST['pid'] );
}

$totImg      = '';
$image_limit = '';
if ( ! empty( $thumb_img_arr ) ) {
	$totImg = count( $thumb_img_arr );
}
$base_plupload_config = json_encode( $plupload_init );
$gd_plupload_init     = array(
	'base_plupload_config' => $base_plupload_config,
	'totalImg'             => 0,
	'image_limit'          => 0,
	// 'upload_img_size' => $file_size
);

wp_localize_script( 'directorist-plupload-public', 'atbdp_plupload_params', $gd_plupload_init );
wp_localize_script( 'directorist-plupload-public', 'atbdp_params', $text_value );
wp_localize_script( 'directorist-plupload-admin', 'atbdp_plupload_params', $gd_plupload_init );
wp_localize_script( 'directorist-plupload-admin', 'atbdp_params', $text_value );

Directorist\Helper::add_hidden_data_to_dom( 'atbdp_plupload_params', $gd_plupload_init );
Directorist\Helper::add_hidden_data_to_dom( 'atbdp_params', $text_value );

 wp_localize_script( 'atbdp-plupload', 'atbdp_plupload_params', $gd_plupload_init );
 wp_localize_script( 'atbdp-plupload-min', 'atbdp_plupload_params', $gd_plupload_init );
 wp_localize_script( 'atbdp-plupload', 'atbdp_params', $text_value );
 wp_localize_script( 'atbdp-plupload-min', 'atbdp_params', $text_value );
$id                 = $post_id;
$is_required        = 0;
$image_limit        = 0;
$total_files        = 0;
$allowed_file_types = ( 'all_types' == $file_types || 'all' == $file_types ) ? '*' : $file_types;
$display_file_types = ( 'all_types' == $file_types || 'all' == $file_types ) ? '.*' : $file_types;
$multiple           = false;
?>
<div class="directorist-form-group directorist-custom-field-file-upload">

	<?php $listing_form->field_label_template( $data );?>

	<div class="directorist-custom-field-file-upload__wrapper">
		<div class="" id="<?php echo $id; ?>dropbox">
			<input type="hidden" name="<?php echo $data['field_key']; ?>" id="<?php echo $post_id; ?>" value="<?php echo !empty( $data['value'] ) ? $data['value'] : '' ; ?>"
			/>
			<input type="hidden" name="<?php echo $id; ?>image_limit" id="<?php echo $id; ?>image_limit"
				   value="<?php echo $image_limit; ?>"/>
			<input type="hidden" name="<?php echo $id; ?>totImg" id="<?php echo $id; ?>totImg"
				   value="<?php echo $total_files; ?>"/>
			<?php if ( $allowed_file_types != '' ) { ?>
				<input type="hidden" name="<?php echo $id; ?>_allowed_types" id="<?php echo $id; ?>_allowed_types"
					   value="<?php echo esc_attr( $allowed_file_types ); ?>"
					   data-exts="<?php echo esc_attr( $display_file_types ); ?>"/>
			<?php } ?>

			<?php if ( ! empty( $file_size ) ) { ?>
				<input type="hidden" name="<?php echo $id; ?>_file_size" id="<?php echo $id; ?>_file_size"
					   value="<?php echo esc_attr( $file_size ); ?>"/>
			<?php } ?>

			<div class="plupload-upload-uic hide-if-no-js
			<?php
			if ( $multiple ) {
				echo 'plupload-upload-uic-multiple';
			}
			?>
			" id="<?php echo $id; ?>plupload-upload-ui">
				<div class="directorist-dropbox-title"><?php _e( 'Drop files here <small>or</small>', 'directorist' ); ?></div>
				<input id="<?php echo $id; ?>plupload-browse-button" type="button"
					   value="<?php esc_attr_e( 'Select Files', 'directorist' ); ?>" class="directorist-btn directorist-btn-primary"/>
				<div class="directorist-dropbox-file-types"><?php echo( $display_file_types != '' ? __( 'Allowed file types:', 'directorist' ) . ' ' . $display_file_types : '' ); ?></div>
				<div class="directorist-dropbox-file-limit">
					<?php
					if ( $image_limit == 1 ) {
						echo '(' . __( 'You can upload', 'directorist' ) . ' ' . $image_limit . ' ' . __( 'file', 'directorist' ) . ')';
					}
					?>
					<?php
					if ( $image_limit > 1 ) {
						echo '(' . __( 'You can upload', 'directorist' ) . ' ' . $image_limit . ' ' . __( 'files', 'directorist' ) . ')';
					}
					?>
				</div>
				<span class="ajaxnonceplu" id="ajaxnonceplu<?php echo wp_create_nonce( $id . 'pluploadan' ); ?>"></span>
				<div class="filelist"></div>
			</div>

			<div class="plupload-thumbs
			<?php
			if ( $multiple ) {
				echo 'plupload-thumbs-multiple';
			}
			?>
			 clearfix" id="<?php echo $id; ?>plupload-thumbs"></div>
			<span id="<?php echo $id; ?>upload-error" style="display:none"></span>
			<span style="display: none" id="atbdp-image-meta-input" class="lity-hide lity-show"></span>
		</div>
	</div>

	<?php $listing_form->field_description_template( $data ); ?>

</div>
