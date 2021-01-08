<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

if ( !empty($data['label']) ): ?>
	<label><?php echo esc_html( $data['label'] ); ?></label>
<?php endif; 
if( !empty( $value ) ) { ?>
	<script> jQuery(document).ready(function ($) { $('.my-color-field').wpColorPicker(); }); </script>
	<?php } else { ?>
		<script> jQuery(document).ready(function ($) { $('.my-color-field').wpColorPicker().empty(); }); </script>
	<?php } ?>

<div class="search-form-field">
	<input class="form-control search_fields my-color-field" id="color_code2" type="text" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo !empty( $data['placeholder'] ) ? esc_attr( $data['placeholder'] ) : ''; ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>
</div>