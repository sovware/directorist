<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.1.2
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-card-date"><?php directorist_icon( $icon );?><?php $listings->print_label( $label ); ?><?php echo esc_html( date( get_option( 'date_format' ), strtotime( $value ) ) ); ?></div>