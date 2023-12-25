<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<li class="directorist-listing-card-email"><?php directorist_icon( $icon );?><span class="directorist-listing-card-info-label"><?php $listings->print_label( $label ); ?></span><a target="_top" href="mailto:<?php echo esc_attr( $value );?>"><?php echo esc_html( $value ) ;?></a></li>