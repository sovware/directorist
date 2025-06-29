<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$done = str_replace( '|||', '', $value );
$name_arr = explode( '/', $done );
$filename = end( $name_arr );
?>

<div class="directorist-single-info directorist-single-info-file">

	<div class="directorist-single-info__label">
		<span class="directorist-single-info__label-icon"><?php directorist_icon( $icon );?></span>
		<span class="directorist-single-info__label__text"><?php echo esc_html( $data['label'] ); ?></span>
	</div>

	<div class="directorist-single-info__value"><?php printf('<a href="%s" target="_blank" download>%s</a>', esc_url( $done ), esc_html( $filename ) ); ?></div>

</div>