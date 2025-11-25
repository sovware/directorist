<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.5.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-form-group directorist-form-button-field">

    <?php $listing_form->field_label_template( $data ); ?>

    <?php
    $button_value = is_array( $data['value'] ) ? $data['value'] : [];
    $button_text = isset( $button_value['button_text'] ) ? $button_value['button_text'] : '';
    $button_link = isset( $button_value['button_link'] ) ? $button_value['button_link'] : '';
    ?>

    <div class="directorist-form-button-field__inputs">
        <div class="directorist-form-button-field__text">
            <label for="<?php echo esc_attr( $data['field_key'] . '_text' ); ?>">
                <?php echo esc_html( ! empty( $data['button_text'] ) ? $data['button_text'] : __( 'Button Text', 'directorist' ) ); ?>
            </label>
            <input type="text" autocomplete="off" name="<?php echo esc_attr( $data['field_key'] . '[button_text]' ); ?>" id="<?php echo esc_attr( $data['field_key'] . '_text' ); ?>" class="directorist-form-element" value="<?php echo esc_attr( $button_text ); ?>" placeholder="<?php echo esc_attr( __( 'Enter button text', 'directorist' ) ); ?>" <?php $listing_form->required( $data ); ?>>
        </div>

        <div class="directorist-form-button-field__link">
            <label for="<?php echo esc_attr( $data['field_key'] . '_link' ); ?>">
                <?php echo esc_html( ! empty( $data['button_link'] ) ? $data['button_link'] : __( 'Website URL', 'directorist' ) ); ?>
            </label>
            <input type="url" autocomplete="off" name="<?php echo esc_attr( $data['field_key'] . '[button_link]' ); ?>" id="<?php echo esc_attr( $data['field_key'] . '_link' ); ?>" class="directorist-form-element" value="<?php echo esc_attr( $button_link ); ?>" placeholder="<?php echo esc_attr( __( 'https://example.com', 'directorist' ) ); ?>" <?php $listing_form->required( $data ); ?>>
        </div>
    </div>

    <?php $listing_form->field_description_template( $data ); ?>

</div>