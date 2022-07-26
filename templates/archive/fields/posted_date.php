<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-card-posted-on"><?php directorist_icon( $icon );?><?php echo esc_html( $listings->loop_get_published_date( $data ) ); ?></div>