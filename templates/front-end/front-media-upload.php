<?php
$display_prv_image                   = get_directorist_option('dsiplay_prv_single_page',1);
$display_slider_image                = get_directorist_option('dsiplay_slider_single_page',1);
$display_preview_image               = get_directorist_option('display_preview_image',1);
$listing_imgs                        = (!empty($args['listing_img'])) ? $args['listing_img'] : array();
$listing_prv_img_id                  = (!empty($args['listing_prv_img'])) ? $args['listing_prv_img'] : '';
$plan_slider                         = (!empty($args['plan_slider'])) ? $args['plan_slider'] : '';
$listing_prv_img                     = wp_get_attachment_image_src($listing_prv_img_id);
$image_links                         = []; // define a link placeholder variable
foreach ($listing_imgs as $id) {
    $image_links[$id] = wp_get_attachment_image_src($id)[0]; // store the attachment id and url
}
// is multiple image upload extension is active  ?
$active_mi_ext = is_multiple_images_active(); // default is no
?>
<?php if( !empty($display_prv_image) || !empty($display_preview_image) || !empty($display_slider_image)) {?>
<div class="add_listing_form_wrapper" id="gallery_upload">
    <?php if( !empty($display_prv_image) || !empty($display_preview_image)) {?>
    <div class="form-group">
        <!-- image container, which can be manipulated with js -->
        <div class="listing-prv-img-container">
            <div class="single_prv_attachment">
                <input class="listing_prv_img" name="listing_prv_img" type="hidden"
                       value="<?php echo $listing_prv_img_id; ?>">
                <img style="max-height: 150px;max-width: 150px" class="change_listing_prv_img"
                     src="<?php echo esc_url($listing_prv_img[0]) ?>">
            </div>
            <div class="default_img">
            </div>
        </div>
        <!--  add & remove image links -->
        <p class="hide-if-no-js">
            <a href="#" class="upload-header btn btn-secondary"><?php esc_attr_e('Upload Preview Image', ATBDP_TEXTDOMAIN); ?></a> <br />
            <?php echo get_directorist_option('require_preview_img')?'<span class="atbdp_make_str_red">(Field is required)</span>':''; ?>
        </p>
    </div>
    <?php }
    ?>
    <?php if( !empty($display_slider_image) && $plan_slider ) {?>
    <div class="form-group">
        <!-- image container, which can be manipulated with js -->
        <div class="listing-img-container">
            <?php if (!empty($image_links)) {
                foreach ($image_links as $id => $image_link) { ?>
                    <div class="single_attachment">
                        <input class="listing_image_attachment" name="listing_img[]" type="hidden"
                               value="<?= intval($id); ?>">
                        <img style="width: 100%; height: 100%;"
                             src="<?= esc_url($image_link) ?>"
                             alt="<?php esc_attr_e('Listing Image', ATBDP_TEXTDOMAIN); ?>">
                        <span class="remove_image  dashicons dashicons-dismiss"
                              title="<?= __('Remove it', ATBDP_TEXTDOMAIN); ?>"></span>
                    </div>
                <?php }  // ends foreach for looping image
            } else { ?>
                <img src="<?= esc_url(ATBDP_ADMIN_ASSETS . 'images/no-image.png'); ?>"
                     alt="<?php esc_attr_e('No Image Found', ATBDP_TEXTDOMAIN); ?>">
                <p>No Images</p>
            <?php } //  ends if statement  ?>
        </div>
        <?php
        /* A hidden input to set and post the chosen image id
        <input id="listing_image_id" name="listing[listing_img]" type="hidden" value="">*/
        ?>
        <!--  add & remove image links -->
        <p class="hide-if-no-js">
            <a href="#" id="listing_image_btn" class="btn btn-primary">
                <span class="dashicons dashicons-format-image"></span>
                <?php _e('Upload Slider Images', ATBDP_TEXTDOMAIN); ?>
            </a>
            <a id="delete-custom-img" class="btn btn-danger <?= (!empty($image_links)) ? '' : 'hidden' ?>"
               href="#"> <?php echo (1 == $active_mi_ext) ? esc_html__('Remove Images') : esc_html__('Remove Image'); ?></a><br />
            <?php echo get_directorist_option('require_gallery_img')?'<span class="atbdp_make_str_red">(Field is required)</span>':''; ?>
        </p>
    </div>
    <?php } ?>
</div> <!--ends add_listing_form_wrapper-->
<?php } ?>

