<?php
$listing_imgs = (!empty($args['listing_img'])) ? $args['listing_img'] : array();
$listing_prv_img_id = (!empty($args['listing_prv_img'])) ? $args['listing_prv_img'] : '';
$listing_prv_img = !empty($listing_prv_img_id) ? atbdp_get_image_source($listing_prv_img_id) : '';
$display_prv_field = get_directorist_option('display_prv_field', 1);
$display_gallery_field = get_directorist_option('display_gallery_field', 1);
$image_links = []; // define a link placeholder variable
foreach ($listing_imgs as $id) {
    $image_links[$id] = atbdp_get_image_source($id); // store the attachment id and url
}
// is multiple image upload extension is active  ?
$active_mi_ext = is_multiple_images_active(); // default is no
?>
<div class="add_listing_form_wrapper" id="gallery_upload">
    <?php if (!empty($display_prv_field)) { ?>
        <div class="form-group">
            <!-- image container, which can be manipulated with js -->
            <div class="listing-prv-img-container">
                <div class="single_prv_attachment">
                    <input class="listing_prv_img" name="listing_prv_img" type="hidden"
                           value="<?php echo $listing_prv_img_id; ?>">
                    <div>
                        <img style="max-height: 150px;max-width: 150px" class="change_listing_prv_img"
                             src="<?php echo $listing_prv_img ? esc_url($listing_prv_img) : ''; ?>">
                        <a href="" class="remove_prev_img"><span class="fa fa-times" title="Remove it"></span></a>
                    </div>
                </div>
                <div class="default_img">
                </div>
            </div>
            <!--  add & remove image links -->
            <p class="hide-if-no-js">
                <a href="#"
                   class="upload-header btn btn-primary"><?php $preview_label = get_directorist_option('preview_label', __('Upload Preview Image', 'directorist'));
                    esc_html_e($preview_label, 'directorist'); ?></a>
            </p>
        </div>
    <?php } ?>
    <?php if (!empty($display_gallery_field)) { ?>
        <div class="form-group">
            <!-- image container, which can be manipulated with js -->
            <div class="listing-img-container">
                <?php if (!empty($image_links)) {
                    foreach ($image_links as $id => $image_link) { ?>
                        <div class="single_attachment">
                            <input class="listing_image_attachment" name="listing_img[]" type="hidden"
                                   value="<?php echo intval($id); ?>">
                            <img style="width: 100%; height: 100%;"
                                 src="<?php echo esc_url($image_link) ?>"
                                 alt="<?php esc_attr_e('Listing Image', 'directorist'); ?>">
                            <span class="remove_image  dashicons dashicons-dismiss"
                                  title="<?php echo __('Remove it', 'directorist'); ?>"></span>
                        </div>
                    <?php }  // ends foreach for looping image
                } else { ?>
                    <img src="<?php echo esc_url(ATBDP_ADMIN_ASSETS . 'images/no-image.png'); ?>"
                         alt="<?php esc_attr_e('No Image Found', 'directorist'); ?>">
                    <p><?php esc_attr_e('No Images', 'directorist'); ?></p>
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
                    <?php $gallery_label = get_directorist_option('gallery_label', __('Upload Slider Images', 'directorist'));
                    esc_html_e($gallery_label, 'directorist'); ?>
                </a>
                <a id="delete-custom-img" class="btn btn-danger <?php echo (!empty($image_links)) ? '' : 'hidden' ?>"
                   href="#"> <?php echo (1 == $active_mi_ext) ? esc_html__('Remove Images', 'directorist') : esc_html__('Remove Image', 'directorist'); ?></a>
            </p>
        </div>
    <?php } ?>
</div> <!--ends add_listing_form_wrapper-->


