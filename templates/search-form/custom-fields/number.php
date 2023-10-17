<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-field directorist-form-group">

	<!-- <label class="directorist-search-field__label"><?php echo esc_attr( $data['placeholder'] ); ?></label> -->

	<div class="directorist-form-group__with-prefix">
		<span class="directorist-form-group__prefix directorist-form-group__prefix--start">$</span>
		<input class="directorist-form-element directorist-search-field__input" type="number" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" step="any" min="0" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>
		<span class="directorist-form-group__prefix directorist-form-group__prefix--end">Per Hour</span>
	</div>

	<div class="directorist-search-field__btn directorist-search-field__btn--clear">
		<i class="directorist-icon-mask" aria-hidden="true" style="--directorist-icon: url(https://revamp.local/wp-content/plugins/directorist/assets/icons/font-awesome/svgs/solid/times-circle.svg)"></i>	
	</div>

</div>