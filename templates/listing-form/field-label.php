<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( empty( $data['label'] ) ) {
	return;
}
?>

<div class="directorist-form-label"><?php echo esc_html( $data['label'] ); ?>:<?php echo !empty( $data['required'] ) ? '<span class="directorist-form-required"> *</span>' : ''; ?></div>