<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$value = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
?>

<div class="directorist-search-field directorist-form-group directorist-search-query <?php echo esc_attr( $empty_label ); ?>">

	<?php if ( ! empty( $data['label'] ) ) : ?>
		<label class="directorist-search-field__label" for="<?php echo esc_attr( $data['field_key'] ?? '' ); ?>"><?php echo esc_attr( $data['label'] ); ?></label>
	<?php endif; ?>

	<input class="directorist-form-element directorist-search-field__input" id="<?php echo esc_attr( $data['field_key'] ?? '' ); ?>" type="text" name="q" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ?? '' ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>

	<div class="directorist-search-field__btn directorist-search-field__btn--clear">
		<?php directorist_icon( 'fas fa-times-circle' ); ?>
	</div>

</div>