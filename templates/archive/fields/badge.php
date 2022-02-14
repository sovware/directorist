<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;
?>

<span class="directorist-badge directorist-info-item directorist-badge-<?php echo esc_attr( $listings->badge_class() )?>"><?php echo esc_html( $listings->badge_text() );?></span>