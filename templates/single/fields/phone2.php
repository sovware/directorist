<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.0.6
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-single-info directorist-single-info-phone2">

	<div class="directorist-single-info__label">
		<span class="directorist-single-info__label-icon"><?php directorist_icon( $icon );?></span>
		<span class="directorist-single-info__label--text"><?php echo esc_html( $data['label'] ); ?></span>
	</div>
	
	<div class="directorist-single-info__value">
		<a href="<?php Helper::phone_linked_with( $data ) . Helper::formatted_tel( $value ); ?>"><?php echo esc_html( $value ); ?></a>
	</div>
	
</div>