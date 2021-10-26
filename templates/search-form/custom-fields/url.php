<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.5.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="single_search_field ">

	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>

	<div class="search-form-field directorist-form-group">
		<input class="search_fields directorist-form-element" type="url" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo !empty( $data['placeholder'] ) ? esc_attr( $data['placeholder'] ) : ''; ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>
	</div>

</div>