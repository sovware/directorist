<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<li class="directorist-listing-card-fax"><?php directorist_icon( $icon ); ?><span class="directorist-listing-card-info-label"><?php $listings->print_label( $label ); ?></span><a href="tel:<?php Helper::formatted_tel( $value ); ?>"><?php echo esc_html( $value ); ?></a></li>