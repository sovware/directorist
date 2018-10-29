<?php
 $attachment_ids = (!empty($args['attachment_ids'])) ? $args['attachment_ids'] : array();
$image_links = []; // define a link placeholder variable
foreach ($attachment_ids as $id){
    $image_links[$id]= wp_get_attachment_image_src($id)[0]; // store the attachment id and url
}
// is multiple image upload extension is active  ?
$active_mi_ext = is_multiple_images_active(); // default is no

?>
    <div class="directorist">
        <div class="row">
            <div class="col-md-12">
                <div class="add_listing_form_wrapper" id="gallery_upload">

                        <div class="form-group">

                            <!-- image container, which can be manipulated with js -->
                            <div class="listing-img-container">
                                <?php if(!empty($image_links)) {
                                    foreach ($image_links as $id => $image_link) {
                                    ?>
                                    <div class="single_attachment"><input class="listing_image_attachment" name="listing[attachment_id][]" type="hidden" value="<?= intval($id); ?>"><img style="width: 100%; height: 100%;" src="<?= esc_url($image_link) ?>" alt="Listing Image"> <span class="remove_image  dashicons dashicons-dismiss" title="<?= __('Remove it',ATBDP_TEXTDOMAIN); ?>"></span></div>
                                <?php }  // ends foreach for looping image
                                } else { ?>
                                <img src="<?= esc_url( ATBDP_ADMIN_ASSETS.'images/no-image.jpg');?>" alt="No Image Found">
                                <?php } //  ends if statement  ?>
                            </div>

                            <!-- A hidden input to set and post the chosen image id -->
<!--                            <input id="listing_image_id" name="listing[attachment_id]" type="hidden" value="">-->


                            <!--  add & remove image links -->
                            <p class="hide-if-no-js">
                                <a href="#" id="listing_image_btn" class="btn btn-default ">
                                    <span class="dashicons dashicons-format-image"></span>
                                    <?php _e('Upload Image', ATBDP_TEXTDOMAIN); ?>
                                </a>
                                <a id="delete-custom-img" class="btn btn-default <?= (!empty($image_links)) ? '': 'hidden' ?>" href="#"> <?php echo ( 1 == $active_mi_ext ) ? esc_html__('Remove Images') : esc_html__('Remove Image'); ?></a>
                            </p>

                        </div>

                </div> <!--ends add_listing_form_wrapper-->

            </div> <!--ends col-md-12 -->
        </div>  <!--ends .row-->
    </div> <!--ends container-fluid-->
