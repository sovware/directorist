<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-card-select"><?php directorist_icon( $icon ); ?><?php $listings->print_label( $label ); ?><?php echo esc_html( $value ); ?></div>