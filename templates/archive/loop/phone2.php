<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-card-phone2"><?php directorist_icon( $icon );?><a href="tel:<?php ATBDP_Helper::sanitize_tel_attr( $value ); ?>"><?php echo esc_html( $value ); ?></a></div>