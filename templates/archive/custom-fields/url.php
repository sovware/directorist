<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.10.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-card-url"><?php directorist_icon( $icon ); ?><?php $listings->print_label( $label ); ?><a <?php echo ! empty( $data['original_field']['target'] ) ? esc_attr( 'target="_blank"' ) : ''; ?> href="<?php echo esc_url( $value ); ?>"><?php echo esc_html( $value ); ?></a></div>