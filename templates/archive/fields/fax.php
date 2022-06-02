<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.8
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-card-fax"><?php directorist_icon( $icon ); ?><span class="directorist-listing-single__info--list__label"><?php $listings->print_label( $label ); ?></span><a href="tel:<?php Helper::formatted_tel( $value ); ?>"><?php echo esc_html( $value ); ?></a></div>