<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.2
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$badge_style_attr = ! empty( $badge_style_attr ) ? $badge_style_attr : '';
$badge_icon       = ! empty( $badge_icon_html ) ? $badge_icon_html : '';
$badge_show_icon  = ! empty( $badge_show_icon );
$tooltip_label    = ! empty( $tooltip_label ) ? $tooltip_label : $label;

if ( empty( $badge_icon ) && $badge_show_icon && ! empty( $icon ) ) {
    $badge_icon = \Directorist\Helper::badge_icon_markup(
        $icon,
        [
            'badge_icon_color' => ! empty( $badge_icon_color ) ? $badge_icon_color : '',
        ]
    );
}
?>

<span class="directorist-badge directorist-info-item directorist-badge-<?php echo esc_attr( $class ); ?> <?php echo esc_attr( $badge_text_class ); ?>"<?php echo $badge_style_attr ? ' style="' . esc_attr( $badge_style_attr ) . '"' : ''; ?>>
    <?php if ( $badge_display_type === 'icon_badge' ) : ?>
        <?php echo $badge_icon ? wp_kses_post( $badge_icon ) : ''; ?>
        <span class="directorist-badge-tooltip <?php echo esc_attr( $tooltip_class ); ?>"><?php echo esc_html( $tooltip_label ); ?></span>
    <?php else : ?>
        <?php echo $badge_icon ? wp_kses_post( $badge_icon ) : ''; ?>
        <?php echo esc_html( $label ); ?>
    <?php endif; ?>
</span>
