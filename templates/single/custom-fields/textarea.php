<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-single-info directorist-single-info-textarea">
	<?php if ( ! empty( $data['label'] ) ) : ?>
	<div class="directorist-single-info__label">
		<span class="directorist-single-info__label-icon"><?php directorist_icon( $icon );?></span>
		<span class="directorist-single-info__label__text"><?php echo esc_html( $data['label'] ); ?></span>
	</div>
	<?php endif; ?>
	<div class="directorist-single-info__value"><?php echo nl2br( esc_textarea( $value ) ); ?></div>

</div>
