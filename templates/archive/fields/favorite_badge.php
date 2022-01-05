<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.5.4
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;

$fav_class  = $listings->loop_is_favourite() ? 'directorist-added-to-favorite' : '';
?>

<div class="directorist-mark-as-favorite">
	<a class="directorist-mark-as-favorite__btn <?php echo esc_attr( $fav_class );  ?> directorist-fav_<?php echo esc_attr( get_the_ID() ); ?>" data-listing_id="<?php echo esc_attr( get_the_ID() ); ?>" href="">
		<span class="directorist-favorite-icon"></span>
		<span class="directorist-favorite-tooltip"></span>
	</a>
</div>