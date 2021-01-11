<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
if( is_admin() ) return;
$description = get_directorist_option('featured_listing_desc');
?>
<div class="atbd_listing_type">
    <?php $listing_type = !empty($listing_info['listing_type']) ? $listing_info['listing_type'] : ''; ?>

    <h4 class="atbdp_option_title"><?php _e('Choose Listing Type', 'directorist-pricing-plans') ?><span class="atbdp_make_str_red"> *</span></h4>
    <div class="atbdp_input_group --atbdp_inline">
        <input id="general" type="radio" class="atbdp_radio_input" <?php echo ($listing_type == 'general') ? 'checked' : ''; ?> name="listing_type" value="general">
        <label for="general" class="general_listing_type_select">
            <?php echo esc_attr( $data['general_label'] ); ?>
        </label>
    </div>
    <div class="atbdp_input_group --atbdp_inline">
        <input id="featured" type="radio" class="atbdp_radio_input" <?php echo ($listing_type == 'featured') ? 'checked' : ''; ?> name="listing_type" value="featured">
        <label for="featured" class="featured_listing_type_select">
            <?php echo esc_attr( $data['featured_label'] ); ?>
            <small class="atbdp_make_str_green"><?php
            echo esc_attr( $description ) ;?>
            </small>
        </label>
    </div>
</div>