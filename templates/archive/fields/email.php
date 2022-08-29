<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-card-email"><?php directorist_icon( $icon );?><span class="directorist-listing-single__info--list__label"><?php $listings->print_label( $label ); ?></span><a target="_top" href="mailto:<?php echo esc_attr( $value );?>"><?php echo esc_html( $value ) ;?></a></div>