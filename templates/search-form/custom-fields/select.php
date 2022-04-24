<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.1.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$field_id = uniqid( 'directorist-input-' );
?>

<div class="directorist-search-field">
	<?php if ( ! empty( $data['label'] ) ) : ?>
		<label for="<?php echo $field_id; ?>"><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>

	<div class="directorist-select">
		<select id="<?php echo $field_id; ?>" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true">
			<option value=""><?php esc_html_e( 'Select', 'directorist' )?></option>

			<?php foreach ( $data['options'] as $option ) : ?>
				<option value="<?php echo esc_attr( $option['option_value'] ); ?>" <?php selected( $value, $option['option_value'] ); ?>><?php echo esc_html( $option['option_label'] ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>