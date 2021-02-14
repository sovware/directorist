<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-card-email"><?php directorist_icon( $icon );?><?php $listings->print_label( $label ); ?><a target="_top" href="mailto:<?php echo esc_attr( $value );?>"><?php echo esc_html( $value ) ;?></a></div>