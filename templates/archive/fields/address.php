<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-card-address"><?php directorist_icon( $icon ); ?><span class="directorist-listing-single__info--list__label"><?php $listings->print_label( $label ); ?></span><?php echo esc_html( $value ); ?></div>