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

	<div class="directorist-form-group">
		<textarea class="directorist-form-element" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]" id="<?php echo esc_attr( $data['field_key'] ); ?>" rows="<?php echo (int) $data['rows']; ?>" placeholder="<?php echo ! empty( $data['placeholder'] ) ? esc_attr( $data['placeholder'] ) : ''; ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> ><?php echo esc_attr( $value ); ?></textarea>
	</div>
	
</div>