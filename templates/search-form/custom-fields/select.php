<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-field">

	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>

	<div class="directorist-select">

		<select name='custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]' <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true">

			<option value=""><?php esc_html_e( 'Select', 'directorist' )?></option>

			<?php
			foreach ( $data['options'] as $option ) {
				printf('<option value="%s"%s>%s</option>', esc_attr( $option['option_value'] ), selected(  $value === $option[ 'option_value' ] ), esc_attr( $option['option_label'] ) );
			}
			?>

		</select>

	</div>

</div>