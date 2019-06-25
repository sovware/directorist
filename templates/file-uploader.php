<?php
/**
 * Directorist file uploader
 *
 */
wp_enqueue_script('atbdp-plupload-min');
wp_enqueue_script('atbdp-plupload');
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