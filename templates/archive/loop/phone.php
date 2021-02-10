<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-card-phone"><?php directorist_icon( $icon ); ?><?php $listings->print_label( $label ); ?><a href="tel:<?php Helper::formatted_tel( $value ); ?>"><?php echo esc_html( $value ); ?></a></div>