<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.10.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-single-info directorist-single-info-url">

	<div class="directorist-single-info__label">
		<span class="directorist-single-info__label-icon"><?php directorist_icon( $icon );?></span>
		<span class="directorist-single-info__label--text"><?php echo esc_html( $data['label'] ); ?></span>
	</div>

	<div class="directorist-single-info__value"><a <?php echo ! empty( $data['form_data']['target'] ) ? esc_attr( 'target="_blank"' ) : ''; ?> href="<?php echo esc_url( $value ); ?>" <?php echo !empty( $data['use_nofollow'] ) ? 'rel="nofollow"' : ''; ?>><?php echo esc_html( $value ); ?></a></div>

</div>