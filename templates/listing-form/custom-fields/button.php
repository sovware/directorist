<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Get conditional logic attributes using centralized method
$conditional_logic_attr = $listing_form->get_conditional_logic_attributes( $data );
?>

<div class="directorist-form-group directorist-form-button-field" <?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?>>

    <?php
    $button_value = is_array( $data['value'] ) ? $data['value'] : [];
    $button_text = isset( $button_value['button_text'] ) ? $button_value['button_text'] : '';
    $button_url_label = isset( $button_value['button_url_label'] ) ? $button_value['button_url_label'] : '';
    $button_text_placeholder = isset( $data['button_text_placeholder'] ) ? $data['button_text_placeholder'] : '';
    $button_url_placeholder = isset( $data['button_url_placeholder'] ) ? $data['button_url_placeholder'] : '';
    ?>

        <div class="directorist-form-button-field__text">

        <label class="directorist-form-label" for="<?php echo esc_attr( $data['field_key'] . '_text' ); ?>">
            <?php echo esc_html( ! empty( $data['label'] ) ? $data['label'] : __( 'Button Text', 'directorist' ) ); ?>:<?php echo ! empty( $data['required'] ) ? '<span class="directorist-form-required"> *</span>' : ''; ?>
        </label>

        <input type="text" autocomplete="off" name="<?php echo esc_attr( $data['field_key'] . '[button_text]' ); ?>" id="<?php echo esc_attr( $data['field_key'] . '_text' ); ?>" class="directorist-form-element" value="<?php echo esc_attr( $button_text ); ?>" placeholder="<?php echo esc_attr( $button_text_placeholder ); ?>" <?php $listing_form->required( $data ); ?>>

        <?php if ( ! empty( $data['button_text_description'] ) ) : ?>
            <div class="directorist-form-description"><?php echo esc_html( $data['button_text_description'] ); ?></div>
        <?php endif; ?>

        </div>

        <div class="directorist-form-button-field__link">

        <label class="directorist-form-label" for="<?php echo esc_attr( $data['field_key'] . '_link' ); ?>">
            <?php echo esc_html( ! empty( $data['button_url_label'] ) ? $data['button_url_label'] : __( 'Website URL', 'directorist' ) ); ?>:<?php echo ! empty( $data['required'] ) ? '<span class="directorist-form-required"> *</span>' : ''; ?>
        </label>

        <input type="url" autocomplete="off" name="<?php echo esc_attr( $data['field_key'] . '[button_url_label]' ); ?>" id="<?php echo esc_attr( $data['field_key'] . '_link' ); ?>" class="directorist-form-element" value="<?php echo esc_attr( $button_url_label ); ?>" placeholder="<?php echo esc_attr( $button_url_placeholder ); ?>" <?php $listing_form->required( $data ); ?>>

        <?php if ( ! empty( $data['button_url_description'] ) ) : ?>
            <div class="directorist-form-description"><?php echo esc_html( $data['button_url_description'] ); ?></div>
        <?php endif; ?>

        </div>

</div>