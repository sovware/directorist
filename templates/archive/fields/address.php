<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<li class="directorist-listing-card-address"><?php directorist_icon( $icon ); ?><span class="directorist-listing-card-info-label"><?php $listings->print_label( $label ); ?></span><?php echo esc_html( $value ); ?></li>