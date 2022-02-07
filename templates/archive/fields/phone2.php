<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.8
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$phone_args = array(
	'number'    => $value,
	'whatsapp'  => $listings->has_whatsapp( $data ),
);
?>

<div class="directorist-listing-card-phone2"><?php directorist_icon( $icon ); ?><span class="directorist-listing-single__info--list__label"><?php $listings->print_label( $label ); ?></span><a href="<?php echo esc_url( Helper::phone_link( $phone_args ) ); ?>"><?php echo esc_html( $value ); ?></a></div>