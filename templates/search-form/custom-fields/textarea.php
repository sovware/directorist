<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-field directorist-form-group">

	<?php if ( ! empty( $data['label'] ) ) : ?>
		<label class="directorist-search-field__label"><?php echo esc_attr( $data['label'] ); ?></label>
	<?php endif; ?>

	<textarea class="directorist-form-element directorist-search-field__input" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]" id="<?php echo esc_attr( $data['field_key'] ); ?>" rows="<?php echo (int) $data['rows']; ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ?? '' ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> ><?php echo esc_attr( $value ); ?></textarea>
	
	<div class="directorist-search-field__btn directorist-search-field__btn--clear">
		<?php directorist_icon( 'fas fa-times-circle' ); ?>	
	</div>

</div>