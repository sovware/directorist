<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

$favourites = (array) get_user_meta( get_current_user_id(), 'atbdp_favourites', true );
$fav_class  = in_array( $listings->loop['id'] , $favourites ) ? 'directorist-added-to-favorite' : '';
?>

<div class="directorist-mark-as-favorite">
	<a class="directorist-mark-as-favorite__btn <?php echo esc_attr( $fav_class ); ?>" id="directorist-fav_<?php echo esc_attr( $listings->loop['id'] ); ?>" data-listing_id="<?php echo esc_attr( $listings->loop['id'] ); ?>" href="">
		<span class="directorist-favorite-icon"></span>
		<span class="directorist-favorite-tooltip"></span>
	</a>
</div>