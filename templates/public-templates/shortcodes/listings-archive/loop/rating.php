<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="atbd_listing_meta"><span class="atbd_meta atbd_listing_rating"><?php echo esc_html( ATBDP()->review->get_average($listings->loop['id']) );?><i class="<?php atbdp_icon_type(true);?>-star"></i></span></div>