<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.5.2
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$value = $listing->get_custom_field_value( 'select', $data );
?>

<div class="directorist-single-info directorist-single-info-select">

	<div class="directorist-single-info__label">
		<span class="directorist-single-info__label-icon"><?php directorist_icon( $icon );?></span>
		<span class="directorist-single-info__label--text"><?php echo esc_html( $data['label'] ); ?></span>
	</div>
	
	<div class="directorist-single-info__value"><?php echo esc_html( $value ); ?></div>
	
</div>