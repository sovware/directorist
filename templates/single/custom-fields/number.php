<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.0.5.2
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-single-info directorist-single-info-number">

	<div class="directorist-single-info__label">
		<span class="directorist-single-info__label-icon"><?php directorist_icon( $icon );?></span>
		<span class="directorist-single-info__label__text"><?php echo esc_html( $data['label'] ); ?></span>
	</div>

	<div class="directorist-form-group__with-prefix">
		<?php if( ! empty( $data['form_data']['prepend'] ) ) : ?>
			<span class="directorist-form-group__prefix directorist-form-group__prefix--start"><?php echo esc_html( $data['form_data']['prepend'] ); ?></span>
		<?php endif; ?>

		<div class="directorist-single-info__value"><?php echo esc_html( $value ); ?></div>

		<?php if( ! empty( $data['form_data']['append'] ) ) : ?>
			<span class="directorist-form-group__prefix directorist-form-group__prefix--end"><?php echo esc_html( $data['form_data']['append'] ); ?></span>
		<?php endif; ?>
	</div>

</div>