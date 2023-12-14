<?php
/**
 * @author  wpWax
 * @since   7.0.0
 * @version 8.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$fav_class  = $listings->loop_is_favourite() ? 'directorist-added-to-favorite' : '';
?>

<div class="directorist-mark-as-favorite">
	<button class="directorist-mark-as-favorite__btn <?php echo esc_attr( $fav_class );  ?> directorist-fav_<?php echo esc_attr( $listings->id ); ?>" data-listing_id="<?php echo esc_attr( $listings->id ); ?>" aria-label="Add to Favorite Button">
		<span class="directorist-favorite-icon"></span>
		<span class="directorist-favorite-tooltip"></span>
	</button>
</div>