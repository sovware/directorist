<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.5
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-form-group directorist-search-field directorist-custom-field-select">

	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>


	<select name='custom-selectbox' <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true" id="<?php echo ! empty( $data['field_key'] ) ? esc_attr( $data['field_key'] ) : 'custom-selectbox'; ?>" class="directorist-form-element custom-select" data-id="select">

		<option value=""><?php esc_html_e( 'Select', 'directorist' )?></option>

		<?php
		foreach ( $data['options'] as $option ) {
			printf( '<option value="%s"%s>%s</option>', esc_attr( $option['option_value'] ), esc_attr( selected(  $value === $option[ 'option_value' ] ) ), esc_html( $option['option_label'] ) );
		}
		?>

	</select>

</div>