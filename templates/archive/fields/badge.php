<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.2
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<span class="directorist-badge directorist-info-item directorist-badge-<?php echo esc_attr( $class ); ?> <?php echo esc_attr( $badge_text_class ); ?>">
    <?php if ( $badge_display_type === 'icon_badge' ) : ?>
        <?php directorist_icon( $icon ); ?>
        <span class="directorist-badge-tooltip <?php echo esc_attr( $tooltip_class ); ?>"><?php echo esc_html( $label ); ?></span>
    <?php else : ?>
        <?php echo esc_html( $label ); ?>
    <?php endif; ?>
</span>