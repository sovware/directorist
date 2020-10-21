<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<p><span class="<?php atbdp_icon_type(true); ?>-phone"></span><a href="tel:<?php ATBDP_Helper::sanitize_tel_attr( $listings->loop['phone_number'] ); ?>"><?php ATBDP_Helper::sanitize_html( $listings->loop['phone_number'] ); ?></a></p>