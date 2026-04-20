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

if ( ! $button_text || ! $button_url_label ) {
    return;
}

// Get button settings from the original submission form field
$original  = ! empty( $data['original_field'] ) ? $data['original_field'] : [];
$btn_style = ! empty( $original['button_style'] ) ? $original['button_style'] : 'default';
$target    = ! empty( $original['open_in_new_tab'] ) ? ' target="_blank" rel="noopener"' : '';

$btn_class = 'directorist-btn directorist-btn-xs';
if ( 'primary' === $btn_style ) {
    $btn_class .= ' directorist-btn-primary';
} elseif ( 'secondary' === $btn_style ) {
    $btn_class .= ' directorist-btn-secondary';
} else {
    $btn_class .= ' directorist-btn-default';
}
?>

<<?php echo tag_escape( $before ? $before : 'div' ); ?> class="directorist-listing-card-button">
    <?php $listings->print_label( $label ); ?>
    <a class="<?php echo esc_attr( $btn_class ); ?>"
       href="<?php echo esc_url( $button_url_label ); ?>"<?php echo $target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
        <span class="directorist-btn-text"><?php echo esc_html( $button_text ); ?></span>
        <span class="directorist-icon-arrow-right">
            <?php directorist_icon( 'fas fa-external-link-alt' ); ?>
        </span>
    </a>
</<?php echo tag_escape( $after ? $after : 'div' ); ?>>

