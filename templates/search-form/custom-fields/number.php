<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-field directorist-form-group">

	<div class="directorist-form-group__with-prefix">
		<?php if( ! empty( $data['options']['prepend'] ) ) : ?>
			<span class="directorist-form-group__prefix directorist-form-group__prefix--start"><?php echo esc_html( $data['options']['prepend'] ); ?></span>
		<?php endif; ?>

		<input class="directorist-form-element" type="number" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ?? '' ); ?>" step="any" min="0" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>

		<?php if( ! empty( $data['options']['append'] ) ) : ?>
			<span class="directorist-form-group__prefix directorist-form-group__prefix--end"><?php echo esc_html( $data['options']['append'] ); ?></span>
		<?php endif; ?>
	</div>

	<div class="directorist-search-field__btn directorist-search-field__btn--clear">
		<i class="directorist-icon-mask" aria-hidden="true" style="--directorist-icon: url(https://revamp.local/wp-content/plugins/directorist/assets/icons/font-awesome/svgs/solid/times-circle.svg)"></i>	
	</div>

</div>