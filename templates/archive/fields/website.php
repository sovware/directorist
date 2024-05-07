<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<li class="directorist-listing-card-website"><?php directorist_icon( $icon ); ?><span class="directorist-listing-card-info-label"><?php $listings->print_label( $label ); ?></span><a target="_blank" rel="noopener" href="<?php echo esc_url( $value ); ?>"><?php echo esc_html( $value ); ?></a></li>