<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="atbd_listing_meta">
    <span class="atbd_meta atbd_listing_rating atbd_listing_transparent">
        <i class="<?php atbdp_icon_type(true);?>-star"></i>
        <i class="<?php atbdp_icon_type(true);?>-star"></i>
        <i class="<?php atbdp_icon_type(true);?>-star"></i>
        <i class="<?php atbdp_icon_type(true);?>-star"></i>
        <i class="<?php atbdp_icon_type(true);?>-star"></i>
        <span class="atbd_listing_avg"><?php echo esc_html( ATBDP()->review->get_average($listings->loop['id']) );?></span>
    </span>
</div>