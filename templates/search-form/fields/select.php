<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-field directorist-flex directorist-align-center">

	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>
	<div class="directorist-select" id="directorist-search-select-js">
		<select name='custom_field[<?php echo esc_html( $data['field_key'] ); ?>]' <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true">

			<?php
			if( $original_field['fields']['select']['field_key'] === $data['field_key'] ) {
				$options = $original_field['fields']['select']['options'];
				
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