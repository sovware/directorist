<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */
?>
<div class="directorist-listing-card-phone"><?php directorist_icon( $icon );?><a href="tel:<?php ATBDP_Helper::sanitize_tel_attr( $value ); ?>"><?php ATBDP_Helper::sanitize_html( $value ); ?></a></div>