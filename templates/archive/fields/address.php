<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-card-address"><?php directorist_icon( $icon ); ?><?php $listings->print_label( $label, true ); ?><?php echo esc_html( $value ); ?></div>