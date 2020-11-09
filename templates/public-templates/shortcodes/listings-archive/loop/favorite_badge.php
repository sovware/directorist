<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$favourites = (array) get_user_meta( get_current_user_id(), 'atbdp_favourites', true );
$fav_class  = in_array( $listings->loop['id'] , $favourites ) ? 'atbdp_fav_isActive' : '';
?>

<div class="atbdp_add_to_fav_listings">
	<a class="atbdp_mark_as_fav <?php echo esc_attr( $fav_class ); ?>" id="atbdp-fav_<?php echo esc_attr( $listings->loop['id'] ); ?>" data-listing_id="<?php echo esc_attr( $listings->loop['id'] ); ?>" href=""><?php directorist_icon( $icon );?><span class="atbd_fav_tooltip"></span></a>
</div>