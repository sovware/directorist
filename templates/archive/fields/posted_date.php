<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-card-posted-on"><?php directorist_icon( $icon );?><span class="directorist-listing-single__info--list__label"></span><?php echo esc_html( $listings->loop_get_published_date( $data ) );?></div>