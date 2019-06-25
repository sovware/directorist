<?php
/**
 * Directorist file uploader
 *
 */
wp_enqueue_style('atbdp-pluploadcss');
wp_enqueue_script('atbdp-plupload-min');
wp_enqueue_script('atbdp-plupload');
$allowed_img_types =  array( 'jpg', 'jpeg', 'jpe', 'gif', 'png' );
// place js config array for plupload
$plupload_init = array(
    'runtimes' => 'html5,silverlight,html4',
    'browse_button' => 'plupload-browse-button', // will be adjusted per uploader
    'container' => 'plupload-upload-ui', // will be adjusted per uploader
    //'drop_element' => 'dropbox', // will be adjusted per uploader
    'file_data_name' => 'async-upload', // will be adjusted per uploader
    'multiple_queues' => true,
    'max_file_size' => '2mb',
    'url' => admin_url('admin-ajax.php'),
    'flash_swf_url' => includes_url('js/plupload/plupload.flash.swf'),
    'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
    'filters' => array(array('title' => __('Allowed Files', 'geodirectory'), 'extensions' => '*')),
    'multipart' => true,
    'urlstream_upload' => true,
    'multi_selection' => false, // will be added per uploader
    // additional post data to send to our ajax hook
    'multipart_params' => array(
        '_ajax_nonce' => wp_create_nonce( "geodir_attachment_upload" ), // will be added per uploader
        'action' => 'geodir_post_attachment_upload', // the ajax action name
        'imgid' => 0 // will be added per uploader
    )
);

$text_value = array(
    'gd_allowed_img_types'                         => ! empty( $allowed_img_types ) ? implode( ',', $allowed_img_types ) : '',
    'txt_all_files'                                => __( 'Allowed files', 'geodirectory' ),
    'err_max_file_size'                            => __( 'File size error : You tried to upload a file over %s', 'geodirectory' ),
    'err_file_type'                                => __( 'File type error. Allowed file types: %s', 'geodirectory' ),
    'err_file_upload_limit'                        => __( 'You have reached your upload limit of %s files.', 'geodirectory' ),
    'err_pkg_upload_limit'                         => __( 'You may only upload %s files with this package, please try again.', 'geodirectory' ),
    'action_remove'                                => __( 'Remove', 'geodirectory' ),
    'button_set'                                   => __( 'Set', 'geodirectory' ),
);
$thumb_img_arr = array();

if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '')
    $thumb_img_arr = geodir_get_images($_REQUEST['pid']);

$totImg = '';
$image_limit = '';
if (!empty($thumb_img_arr)) {
    $totImg = count($thumb_img_arr);
}
$base_plupload_config = json_encode($plupload_init);
$gd_plupload_init = array('base_plupload_config' => $base_plupload_config,
    'totalImg' => 0,
    'image_limit' => 0,
    'upload_img_size' => 2);

wp_localize_script('atbdp-plupload', 'geodir_plupload_params', $gd_plupload_init);
wp_localize_script('atbdp-plupload-min', 'geodir_plupload_params', $gd_plupload_init);
wp_localize_script('atbdp-plupload', 'geodir_params', $text_value);
wp_localize_script('atbdp-plupload-min', 'geodir_params', $text_value);
$id= $post_id;
$is_required = 0;
$image_limit         = 0;
$total_files         = 0;
$allowed_file_types  = "*";
$display_file_types  = ".*";
$multiple            = true;
?>
<div class="geodir-add-files">
    <div class="geodir_form_row clearfix geodir-files-dropbox" id="<?php echo $id; ?>dropbox">
        <input type="hidden" name="custom_field[<?php echo $post_id; ?>]" id="<?php echo $post_id; ?>" value="<?php echo $value;?>"
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

        <div class="plupload-upload-uic hide-if-no-js <?php if ( $multiple ) {
            echo "plupload-upload-uic-multiple";
        } ?>" id="<?php echo $id; ?>plupload-upload-ui">
            <div class="geodir-dropbox-title"><?php _e( 'Drop files here <small>or</small>', 'geodirectory' ); ?></div>
            <input id="<?php echo $id; ?>plupload-browse-button" type="button"
                   value="<?php esc_attr_e( 'Select Files', 'geodirectory' ); ?>" class="geodir_button button "/>
            <div
                class="geodir-dropbox-file-types"><?php echo( $display_file_types != '' ? __( 'Allowed file types:', 'geodirectory' ) . ' ' . $display_file_types : '' ); ?></div>
            <div class="geodir-dropbox-file-limit">
                <?php if ( $image_limit == 1 ) {
                    echo '(' . __( 'You can upload', 'geodirectory' ) . ' ' . $image_limit . ' ' . __( 'file', 'geodirectory' ) . ')';
                } ?>
                <?php if ( $image_limit > 1 ) {
                    echo '(' . __( 'You can upload', 'geodirectory' ) . ' ' . $image_limit . ' ' . __( 'files', 'geodirectory' ) . ')';
                } ?>
                <?php if ( $image_limit == '' ) {
                    echo '(' . __( 'You can upload unlimited files with this package', 'geodirectory' ) . ')';
                } ?>
            </div>
            <span class="ajaxnonceplu" id="ajaxnonceplu<?php echo wp_create_nonce( $id . 'pluploadan' ); ?>"></span>
            <div class="filelist"></div>
        </div>

        <div class="plupload-thumbs <?php if ( $multiple ) {
            echo "plupload-thumbs-multiple";
        } ?> clearfix" id="<?php echo $id; ?>plupload-thumbs"></div>
        <span
            id="upload-msg"><?php _e( 'Please drag &amp; drop the files to rearrange the order', 'geodirectory' ); ?></span>
        <span id="<?php echo $id; ?>upload-error" style="display:none"></span>
        <span style="display: none" id="gd-image-meta-input" class="lity-hide lity-show"></span>
    </div>
</div>