<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.5.4
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-field directorist-form-group">

	<div class="directorist-select directorist-search-field__input">
		<label class="directorist-search-field__label"><?php echo esc_attr( $data['placeholder'] ); ?></label>

		<select name='custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]' <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true">

			<option value=""><?php echo esc_html( ! empty( $data['placeholder'] ) ? $data['placeholder'] : __( 'Select', 'directorist' ) )?></option>

			<?php
			foreach ( $data['options'] as $option ) {
				printf( '<option value="%s"%s>%s</option>', esc_attr( $option['option_value'] ), esc_attr( selected(  $value === $option[ 'option_value' ] ) ), esc_html( $option['option_label'] ) );
			}
			?>

		</select>

	</div>

	<div class="directorist-search-field__btn directorist-search-field__btn--clear">
		<i class="directorist-icon-mask" aria-hidden="true" style="--directorist-icon: url(https://revamp.local/wp-content/plugins/directorist/assets/icons/font-awesome/svgs/solid/times-circle.svg)"></i>	
	</div>

</div>