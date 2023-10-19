<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$value = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
?>

<div class="directorist-search-field directorist-form-group directorist-search-query">
	<label class="directorist-search-field__label"><?php echo esc_attr( $data['placeholder'] ); ?></label>
	<input class="directorist-form-element directorist-search-field__input" type="text" name="q" value="<?php echo esc_attr( $value ); ?>" placeholder="" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>
	<div class="directorist-search-field__btn directorist-search-field__btn--clear">
		<?php directorist_icon( 'fas fa-times-circle' ); ?>
	</div>
</div>