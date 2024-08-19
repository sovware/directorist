<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<<?php echo esc_attr( $before ? $before : 'div' ); ?>  class="directorist-listing-card-checkbox"><?php directorist_icon( $icon ); ?><?php $listings->print_label( $label ); ?><?php echo esc_html( $value ); ?><<?php echo esc_attr( $after ? $after : 'div' ); ?>/>