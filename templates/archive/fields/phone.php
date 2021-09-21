<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.6
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-card-phone"><?php directorist_icon( $icon ); ?><?php $listings->print_label( $label ); ?><a href="<?php Helper::phone_linked_with( $args ) . Helper::formatted_tel( $value ); ?>"><?php echo esc_html( $value ); ?></a></div>