<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<div class="directorist-single-info directorist-single-info-phone2">
	<div class="directorist-single-info-label"><?php directorist_icon( $icon );?><?php echo esc_html( $data['label'] ); ?></div>
	<div class="directorist-single-info-value"><a href="tel:<?php ATBDP_Helper::sanitize_tel_attr( $value ); ?>"><?php echo esc_html( $value ); ?></a></div>
</div>