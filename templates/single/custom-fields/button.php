<?php
/**
 * @author  wpWax
 * @since   8.7
 * @version 8.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$button_value     = is_array( $value ) ? $value : maybe_unserialize( $value );
$button_text      = isset( $button_value['button_text'] ) ? $button_value['button_text'] : '';
$button_url_label = isset( $button_value['button_url_label'] ) ? $button_value['button_url_label'] : '';

if ( ! $button_text || ! $button_url_label ) {
    return;
}

// Get button settings from form_data if available
$form_data    = isset( $data['form_data'] ) ? $data['form_data'] : $data;
$button_style = ! empty( $form_data['button_style'] ) ? $form_data['button_style'] : 'default';
$target       = ! empty( $form_data['open_in_new_tab'] ) ? ' target="_blank" rel="noopener"' : '';

$btn_class = 'directorist-btn directorist-btn-xs';
if ( 'primary' === $button_style ) {
    $btn_class .= ' directorist-btn-primary';
} elseif ( 'secondary' === $button_style ) {
    $btn_class .= ' directorist-btn-secondary';
} else {
    $btn_class .= ' directorist-btn-default';
}

?>

<div class="directorist-single-info directorist-single-info-button">
    <div class="directorist-single-info__label">
        <span class="directorist-single-info__label-icon"><?php directorist_icon( $icon ); ?></span>
        <span class="directorist-single-info__label__text"><?php echo esc_html( $data['label'] ); ?></span>
    </div>

    <div class="directorist-single-info__value">
        <a class="<?php echo esc_attr( $btn_class ); ?>"
           href="<?php echo esc_url( $button_url_label ); ?>"<?php echo esc_attr( $target ); ?>>
            <span class="directorist-btn-text"><?php echo esc_html( $button_text ); ?></span>
            <span class="directorist-icon-arrow-right">
                <?php directorist_icon( 'fas fa-external-link-alt' ); ?>
            </span>
        </a>
    </div>
</div>