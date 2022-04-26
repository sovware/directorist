<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.2.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-field">

	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>

	<div class="directorist-form-group directorist-zipcode-search">
		<input class="<?php echo esc_attr( $searchform->zip_code_class() ); ?>" type="text" name="<?php echo esc_attr( $data['field_key'] ); ?>" value="<?php echo esc_attr( $value ); ?>" autocomplete="off" placeholder="<?php echo !empty( $data['placeholder'] ) ? esc_attr( $data['placeholder'] ) : ''; ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>

		<div class="directorist-country directorist-search-country" style="display: none"></div>
		<input type="hidden" class="zip-cityLat" name="zip-cityLat" value="" />
		<input type="hidden" class="zip-cityLng" name="zip-cityLng" value="" />
	</div>
	
</div>