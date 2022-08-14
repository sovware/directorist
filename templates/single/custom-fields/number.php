<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-single-info directorist-single-info-number">

	<div class="directorist-single-info__label">
		<span class="directorist-single-info__label-icon"><?php directorist_icon( $icon );?></span>
		<span class="directorist-single-info__label--text"><?php echo esc_html( $data['label'] ); ?></span>
	</div>

	<div class="directorist-single-info__value"><?php echo esc_html( $value ); ?></div>

</div>