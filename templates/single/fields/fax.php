<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-single-info directorist-single-info-fax">
	<div class="directorist-single-info-label"><?php directorist_icon( $icon );?><?php echo esc_html( $data['label'] ); ?></div>
	<div class="directorist-single-info-value"><a href="tel:<?php Helper::formatted_tel( $value ); ?>"><?php echo esc_html( $value ); ?></a></div>
</div>