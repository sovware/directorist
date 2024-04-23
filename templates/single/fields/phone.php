<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.0.6
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$phone_args = array(
	'number'    => $value,
	'whatsapp'  => $listing->has_whatsapp( $data ),
);

?>
<div class="directorist-single-info directorist-single-info-phone">
	<div class="directorist-single-info__label">
		<span class="directorist-single-info__label-icon"><?php directorist_icon( $icon );?></span>
		<span class="directorist-single-info__label--text"><?php echo esc_html( $data['label'] ); ?></span>
	</div>

	<div class="directorist-single-info__value">
		<a href="<?php echo esc_url( Helper::phone_link( $phone_args ) ); ?>"><?php echo esc_html( $value ); ?></a>
	</div>
</div>