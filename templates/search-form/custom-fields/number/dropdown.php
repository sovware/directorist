<?php
/**
 * @author  wpWax
 * @since   8.0
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$options = directorist_calculate_number_options( $data );
?>

<div class="directorist-search-field directorist-form-group">

	<div class="directorist-select directorist-search-field__input">

		<?php if ( ! empty( $data['label'] ) ) : ?>
			<label class="directorist-search-field__label"><?php echo esc_attr( $data['label'] ); ?></label>
		<?php endif; ?>

		<select name='custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]' <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true" data-placeholder="<?php echo esc_attr( $data['placeholder'] ?? '' ); ?>">

			<option value=""><?php echo esc_html( ! empty( $data['placeholder'] ) ? $data['placeholder'] : __( 'Select', 'directorist' ) )?></option>

			<?php
			if( $options ) {
				foreach ( $options['select'] as $option ) {
					printf( '<option value="%s"%s>%s</option>', esc_attr( $option ), esc_attr( selected( (int) $value === $option ) ), esc_html( $option ) );
				}
			}
			?>

		</select>

	</div>

    <div class="directorist-search-field__btn directorist-search-field__btn--clear">
		<?php directorist_icon( 'fas fa-times-circle' ); ?>	
	</div>

</div>