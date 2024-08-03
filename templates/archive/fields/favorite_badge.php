<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$fav_class  = $listings->loop_is_favourite() ? 'directorist-added-to-favorite' : '';
?>

<button class="directorist-mark-as-favorite__btn <?php echo esc_attr( $fav_class );  ?> directorist-fav_<?php echo esc_attr( $listings->loop['id'] ); ?>" data-listing_id="<?php echo esc_attr( $listings->loop['id'] ); ?>" aria-label="Add to Favorite Button">
	<span class="directorist-favorite-icon"></span>
	<span class="directorist-favorite-tooltip"></span>
</button>