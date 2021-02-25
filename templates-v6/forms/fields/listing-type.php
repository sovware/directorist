<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
if( is_admin() || $data['value'] ) {
     return;
}
?>
<div class="atbd_listing_type">

    <?php $form->add_listing_label_template( $data );?>
    <div class="atbdp_input_group --atbdp_inline">
        <input id="general" type="radio" class="atbdp_radio_input" name="listing_type" value="general" checked>
        <label for="general" class="general_listing_type_select">
            <?php echo esc_attr( $data['general_label'] ); ?>
        </label>
    </div>
    <div class="atbdp_input_group --atbdp_inline">
        <input id="featured" type="radio" class="atbdp_radio_input" name="listing_type" value="featured">
        <label for="featured" class="featured_listing_type_select">
            <?php echo esc_attr( $data['featured_label'] ); ?>
            <small class="atbdp_make_str_green"><?php
            echo esc_attr( $form->featured_listing_description() ) ;?>
            </small>
        </label>
    </div>
</div>