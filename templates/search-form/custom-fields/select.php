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

		<?php if ( ! empty( $data['label'] ) ) : ?>
			<label class="directorist-search-field__label"><?php echo esc_attr( $data['label'] ); ?></label>
		<?php endif; ?>

		<select name='custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]' <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true" data-placeholder="<?php echo esc_html( ! empty( $data['placeholder'] ) ? $data['placeholder'] : __( 'Select', 'directorist' ) )?>">

			<option value=""><?php echo esc_html( ! empty( $data['placeholder'] ) ? $data['placeholder'] : __( 'Select', 'directorist' ) )?></option>

			<?php
			if( $data['options']['options'] ) {
				foreach ( $data['options']['options'] as $option ) {
					printf( '<option value="%s"%s>%s</option>', esc_attr( $option['option_value'] ), esc_attr( selected(  $value === $option[ 'option_value' ] ) ), esc_html( $option['option_label'] ) );
				}
			}
			?>

		</select>

	</div>

	<div class="directorist-search-field__btn directorist-search-field__btn--clear">
		<?php directorist_icon( 'fas fa-times-circle' ); ?>	
	</div>

</div>