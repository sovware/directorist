<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$fav_class  = $listings->loop_is_favourite() ? 'directorist-added-to-favorite' : '';
?>

<div class="directorist-mark-as-favorite">
	<a class="directorist-mark-as-favorite__btn <?php echo esc_attr( $fav_class ); ?>" id="directorist-fav_<?php echo esc_attr( $listings->loop['id'] ); ?>" data-listing_id="<?php echo esc_attr( $listings->loop['id'] ); ?>" href="">
		<span class="directorist-favorite-icon"></span>
		<span class="directorist-favorite-tooltip"></span>
	</a>
</div>