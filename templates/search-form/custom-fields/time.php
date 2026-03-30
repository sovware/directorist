<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$conditional_logic_attr = $searchform->get_conditional_logic_attributes( $data );
?>

<div class="directorist-search-field directorist-form-group directorist-time"<?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?>>

    <?php if ( ! empty( $data['label'] ) ) : ?>
        <label class="directorist-search-field__label"><?php echo esc_html( $data['label'] ); ?></label>
    <?php endif; ?>

    <div class="directorist-form-group ">
        <input class="directorist-form-element directorist-search-field__input" type="time" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]" value="<?php echo esc_attr( $value ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>
    </div>

    <div class="directorist-search-field__btn directorist-search-field__btn--clear">
        <?php directorist_icon( 'fas fa-times-circle' ); ?> 
    </div>

</div>