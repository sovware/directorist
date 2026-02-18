<?php
/**
 * @author  wpWax
 * @since   8.5.5
 * @version 8.5.5
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$button_value     = is_array( $value ) ? $value : maybe_unserialize( $value );
$button_text      = isset( $button_value['button_text'] ) ? $button_value['button_text'] : '';
$button_url_label = isset( $button_value['button_url_label'] ) ? $button_value['button_url_label'] : '';

// Get button settings from form_data if available
$form_data    = isset( $data['form_data'] ) ? $data['form_data'] : $data;
$button_style = ! empty( $form_data['button_style'] ) ? $form_data['button_style'] : 'primary';
$target       = ! empty( $form_data['open_in_new_tab'] ) ? ' target="_blank" rel="noopener"' : '';

?>

<div class="directorist-single-info directorist-single-info-button">
    <div class="directorist-single-info__label">
        <span class="directorist-single-info__label-icon"><?php directorist_icon( $icon ); ?></span>
        <span class="directorist-single-info__label__text"><?php echo esc_html( $data['label'] ); ?></span>
    </div>

    <?php if ( $button_text && $button_url_label ) : ?>
        <div class="directorist-single-info__value">
            <a class="directorist-btn directorist-btn-<?php echo esc_attr( $button_style ); ?>"
               href="<?php echo esc_url( $button_url_label ); ?>"<?php echo $target; ?>>
                <?php echo esc_html( $button_text ); ?>
            </a>
        </div>
    <?php endif; ?>
</div>