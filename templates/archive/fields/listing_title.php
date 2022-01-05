<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.4
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;

$title = !$listings->disable_single_listing() ? sprintf( '<a href="%s">%s</a>', $listings->loop_get_permalink(), $listings->loop_get_title() ) : $listings->loop_get_title();
?>

<h4 class="directorist-listing-title"><?php echo wp_kses_post( $title );?></h4>

<?php if( !empty( $data['show_tagline'] ) && !empty( $listings->loop_get_tagline() ) ): ?>
    
	<p class="directorist-listing-tagline"><?php echo wp_kses_post( $listings->loop_get_tagline() );?></p>

<?php endif; ?>