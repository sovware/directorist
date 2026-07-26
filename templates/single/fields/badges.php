<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 8.5
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$badges = $listing->matched_badges( $data );

if ( empty( $badges ) ) {
    return;
}
?>

<div class="directorist-info-item directorist-info-item-badges">
    <?php foreach ( $badges as $badge ) : ?>
        <?php
        $badge_data = Helper::badge_template_data( $badge['key'] );

        if ( empty( $badge_data ) ) {
            continue;
        }

        $badge_style_attr   = ! empty( $badge_data['badge_style_attr'] ) ? $badge_data['badge_style_attr'] : '';
        $badge_display_type = ! empty( $badge_data['badge_display_type'] ) ? $badge_data['badge_display_type'] : 'text_badge';
        $badge_text_class   = ! empty( $badge_data['badge_text_class'] ) ? $badge_data['badge_text_class'] : '';
        $badge_icon         = ! empty( $badge_data['badge_icon_html'] ) ? $badge_data['badge_icon_html'] : '';
        $badge_show_icon    = ! empty( $badge_data['badge_show_icon'] );

        if ( empty( $badge_icon ) && $badge_show_icon && ! empty( $badge_data['icon'] ) ) {
            $badge_icon = Helper::badge_icon_markup( $badge_data['icon'], $badge_data );
        }

        $badge_label        = ! empty( $badge_data['label'] ) ? $badge_data['label'] : '';
        $tooltip_label      = ! empty( $badge_data['tooltip_label'] ) ? $badge_data['tooltip_label'] : $badge_label;
        $badge_class        = ! empty( $badge_data['class'] ) ? $badge_data['class'] : $badge['class'];
        $tooltip_class      = ! empty( $badge_data['tooltip_class'] ) ? $badge_data['tooltip_class'] : '';
        ?>
        <span class="directorist-badge directorist-badge-<?php echo esc_attr( $badge_class ); ?> <?php echo esc_attr( $badge_text_class ); ?>"<?php echo $badge_style_attr ? ' style="' . esc_attr( $badge_style_attr ) . '"' : ''; ?>>
            <?php if ( 'icon_badge' === $badge_display_type ) : ?>
                <?php echo $badge_icon ? wp_kses_post( $badge_icon ) : ''; ?>
                <span class="directorist-badge-tooltip <?php echo esc_attr( $tooltip_class ); ?>"><?php echo esc_html( $tooltip_label ); ?></span>
            <?php else : ?>
                <?php echo $badge_icon ? wp_kses_post( $badge_icon ) : ''; ?>
                <?php echo esc_html( $badge_label ); ?>
            <?php endif; ?>
        </span>
    <?php endforeach; ?>
</div>
