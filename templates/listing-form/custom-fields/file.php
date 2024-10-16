<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.3
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$post_id    = ! empty( $data['field_key'] ) ? $data['field_key'] : rand();
$file_types = 'all_types';

if ( ! empty( $data['file_type'] ) ) {
	$groups = directorist_get_supported_file_types_groups();

	if ( isset( $groups[ $data['file_type'] ] ) ) {
		$file_types = implode( ',', $groups[ $data['file_type'] ] );
	} else {
		$file_types = $data['file_type'];
	}
}

$file_size         = ! empty( $data['file_size'] ) ? $data['file_size'] : '2mb';
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
		'_ajax_nonce' => wp_create_nonce( 'atbdp_attachment_upload' ),   // will be added per uploader
		'action'      => 'atbdp_post_attachment_upload',                 // the ajax action name
		// Do not delete or modify 'imgid' we are running backend validation based on this id.
		'imgid'       => 0,                                              // will be added per uploader
		'directory'   => $data['form']->current_listing_type,
	),
);

$text_value    = array(
	'atbdp_allowed_img_types' => implode( ',', directorist_get_supported_file_types_groups( 'image' ) ),
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
	$thumb_img_arr = atbdp_get_images( sanitize_text_field( wp_unslash( $_REQUEST['pid'] ) ) );
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


Directorist\Helper::add_hidden_data_to_dom( 'atbdp_plupload_params', $gd_plupload_init );
Directorist\Helper::add_hidden_data_to_dom( 'atbdp_params', $text_value );

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
		<div class="" id="<?php echo esc_attr( $id ); ?>dropbox">
			<input type="hidden" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $post_id ); ?>" value="<?php echo !empty( $data['value'] ) ? esc_attr( $data['value'] ) : '' ; ?>"
			/>
			<input type="hidden" name="<?php echo esc_attr( $id ); ?>image_limit" id="<?php echo esc_attr( $id ); ?>image_limit"
				   value="<?php echo esc_attr( $image_limit ); ?>"/>
			<input type="hidden" name="<?php echo esc_attr( $id ); ?>totImg" id="<?php echo esc_attr( $id ); ?>totImg"
				   value="<?php echo esc_attr( $total_files ); ?>"/>
			<?php if ( $allowed_file_types != '' ) { ?>
				<input type="hidden" name="<?php echo esc_attr( $id ); ?>_allowed_types" id="<?php echo esc_attr( $id ); ?>_allowed_types"
					   value="<?php echo esc_attr( $allowed_file_types ); ?>"
					   data-exts="<?php echo esc_attr( $display_file_types ); ?>"/>
			<?php } ?>

			<?php if ( ! empty( $file_size ) ) { ?>
				<input type="hidden" name="<?php echo esc_attr( $id ); ?>_file_size" id="<?php echo esc_attr( $id ); ?>_file_size"
					   value="<?php echo esc_attr( $file_size ); ?>"/>
			<?php } ?>

			<input type="hidden" name="<?php echo esc_attr( $id ); ?>_directory" id="<?php echo esc_attr( $id ); ?>_directory" value="general"/>

			<div class="plupload-upload-uic hide-if-no-js
			<?php
			if ( $multiple ) {
				echo 'plupload-upload-uic-multiple';
			}
			?>
			" id="<?php echo esc_attr( $id ); ?>plupload-upload-ui">
				<input id="<?php echo esc_attr( $id ); ?>plupload-browse-button" type="button"
					   value="<?php esc_attr_e( 'Select Files', 'directorist' ); ?>" class="directorist-btn"/>
				<label for="<?php echo esc_attr( $id ); ?>plupload-browse-button" class="plupload-browse-button-label"><?php directorist_icon( 'far fa-image' ); ?></label>
				<span class="plupload-browse-img-size">1600×1200 or larger</span>
			</div>

			<div class="plupload-thumbs
			<?php
			if ( $multiple ) {
				echo 'plupload-thumbs-multiple';
			}
			?>
			 clearfix" id="<?php echo esc_attr( $id ); ?>plupload-thumbs"></div>
			<span id="<?php echo esc_attr( $id ); ?>upload-error" style="display:none"></span>
			<span style="display: none" id="atbdp-image-meta-input" class="lity-hide lity-show"></span>
		</div>
	</div>

	<?php $listing_form->field_description_template( $data ); ?>

</div>
<?php
/**
 * @since 7.4.0
 * Add additional field in file upload
 */

do_action( 'directorist_after_file_upload_form_field', $data );