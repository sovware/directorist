<?php
/**
 * Archive Action – Custom Button.
 *
 * @author  wpWax
 * @since   8.5.5
 * @version 8.5.5
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$field_key    = ! empty( $original['field_key'] ) ? $original['field_key'] : 'custom-button';
$btn_raw      = get_post_meta( $post_id, '_' . $field_key, true );
$btn_value    = is_array( $btn_raw ) ? $btn_raw : maybe_unserialize( $btn_raw );
$button_text  = $btn_value['button_text'] ?? '';
$button_url   = $btn_value['button_url_label'] ?? '';

if ( ! $button_text || ! $button_url ) {
	return;
}

$button_style = ! empty( $original['button_style'] ) ? $original['button_style'] : 'primary';
$target       = ! empty( $original['open_in_new_tab'] ) ? ' target="_blank" rel="noopener"' : '';
?>
<a class="directorist-btn directorist-btn-xs directorist-btn-outline-<?php echo esc_attr( $button_style ); ?>"
   href="<?php echo esc_url( $button_url ); ?>"<?php echo $target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php directorist_icon( 'las la-link' ); ?>
	<?php echo esc_html( $button_text ); ?>
</a>

