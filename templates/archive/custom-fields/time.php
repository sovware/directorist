<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.10.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-card-time"><?php directorist_icon( $icon ); ?><?php $listings->print_label( $label ); ?><?php echo esc_html( directorist_format_time( $value ) ); ?></div>