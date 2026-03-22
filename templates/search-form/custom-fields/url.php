<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$conditional_logic_attr = $searchform->get_conditional_logic_attributes( $data );
?>

<div class="directorist-search-field single_search_field directorist-form-group search-form-field"<?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?>>

    <?php if ( ! empty( $data['label'] ) ) : ?>
        <label class="directorist-search-field__label"><?php echo esc_attr( $data['label'] ); ?></label>
    <?php endif; ?>

    <input class="search_fields directorist-form-element directorist-search-field__input" type="url" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ?? '' ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>

    <div class="directorist-search-field__btn directorist-search-field__btn--clear">
        <?php directorist_icon( 'fas fa-times-circle' ); ?> 
    </div>

</div>