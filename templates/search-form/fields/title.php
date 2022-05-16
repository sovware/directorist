<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.2.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$value = isset( $_GET['q'] ) ? wp_unslash( $_GET['q'] ) : '';
?>

<div class="directorist-search-field directorist-form-group directorist-search-query">
	<input class="directorist-form-element" type="text" name="q" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>
</div>