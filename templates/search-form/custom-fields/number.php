<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$conditional_logic_attr = $searchform->get_conditional_logic_attributes( $data );
?>

<div class="directorist-search-field directorist-form-group"<?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?>>
    <?php if ( ! empty( $data['label'] ) ) : ?>
        <label class="directorist-search-field__label"><?php echo esc_attr( $data['label'] ); ?></label>
    <?php endif; ?>
    
    <div class="directorist-form-group__with-prefix">
        <?php if ( ! empty( $data['options']['prepend'] ) ) : ?>
            <span class="directorist-form-group__prefix directorist-form-group__prefix--start"><?php echo esc_html( $data['options']['prepend'] ); ?></span>
        <?php endif; ?>

        <input class="directorist-form-element directorist-search-field__input" type="number" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ?? '' ); ?>" step="<?php echo esc_attr( $data['options']['step'] ?? 1 ); ?>" min="0" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>

        <?php if ( ! empty( $data['options']['append'] ) ) : ?>
            <span class="directorist-form-group__prefix directorist-form-group__prefix--end"><?php echo esc_html( $data['options']['append'] ); ?></span>
        <?php endif; ?>
    </div>

    <div class="directorist-search-field__btn directorist-search-field__btn--clear">
        <?php directorist_icon( 'fas fa-times-circle' ); ?> 
    </div>

</div>