<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<li class="directorist-listing-card-posted-on"><?php directorist_icon( $icon );?><span class="directorist-listing-single__info__list__label"></span><?php echo esc_html( $listings->loop_get_published_date( $data ) );?></li>