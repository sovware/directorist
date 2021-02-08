<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-field">

	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>

	<?php if( !empty( $value ) ): ?>
		<script> jQuery(document).ready(function ($) { $('.directorist-color-picker').wpColorPicker(); }); </script>
	<?php else: ?>
		<script> jQuery(document).ready(function ($) { $('.directorist-color-picker').wpColorPicker().empty(); }); </script>
	<?php endif; ?>

	<div class="directorist-form-group directorist-color-picker-wrap">
		<input class="directorist-form-element directorist-color-picker" type="text" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo !empty( $data['placeholder'] ) ? esc_attr( $data['placeholder'] ) : ''; ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>
	</div>
	
</div>