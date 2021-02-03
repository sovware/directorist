<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

$value = isset( $_GET['q'] ) ? $_GET['q'] : '';
?>
<div class="directorist-form-group directorist-search-query">
	<input class="directorist-form-element directorist-search-fields" type="text" name="q" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>
</div>