<?php
/**
 * @author  wpWax
 * @since   7.0
 * @version 7.0.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$widget_name = 'select';
if ( strpos( $data['widget_name'], '_') ) {
	$widget_name = $data['widget_name'];
}
?>

<div class="directorist-search-field">

	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>
	<div class="directorist-select" id="directorist-search-select-js">
		<select name='custom_field[<?php echo esc_html( $data['field_key'] ); ?>]' <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true">

			<?php
			if( $original_field['fields'][$widget_name]['field_key'] === $data['field_key'] ) {
				$options = $original_field['fields'][$widget_name]['options'];
				
				printf( '<option value="">%s</option>', __( 'Select', 'directorist' ) );

				if( $options ) {
					foreach ( $options as $option ) {
						printf('<option value="%s"%s>%s</option>', $option['option_value'], selected(  $value === $option[ 'option_value' ] ), $option['option_label']);
					}
				}
			}
			?>

		</select>
	</div>
</div>