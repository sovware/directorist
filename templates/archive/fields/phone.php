<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.6
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$phone_args = array(
	'number'    => $value,
	'whatsapp'  => $listings->has_whatsapp( $data ),
);
?>

<div class="directorist-listing-card-phone"><?php directorist_icon( $icon ); ?><?php $listings->print_label( $label ); ?><a href="<?php echo esc_url( Helper::phone_link( $phone_args ) ); ?>"><?php echo esc_html( $value ); ?></a></div>