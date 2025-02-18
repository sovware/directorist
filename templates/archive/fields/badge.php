<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0.11
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Define badge configurations
$badge_configs = [
    'featured' => [
        'icon'          => 'fas fa-star',
        'tooltip_class' => 'directorist-badge-tooltip__featured',
        'default_label' => 'Featured',
        'badge_class'   => $featured_badge_class ?? '',
        'badge_type'    => $featured_badge_type ?? '',
    ],
    'new' => [
        'icon'          => 'fas fa-bolt',
        'tooltip_class' => 'directorist-badge-tooltip__new',
        'default_label' => 'New',
        'badge_class'   => $new_badge_class ?? '',
        'badge_type'    => $new_badge_type ?? '',
    ],
    'popular' => [
        'icon'          => 'fas fa-fire',
        'tooltip_class' => 'directorist-badge-tooltip__popular',
        'default_label' => 'Popular',
        'badge_class'   => $popular_badge_class ?? '',
        'badge_type'    => $popular_badge_type ?? '',
    ],
];

// Get the current badge configuration
$badge_config = $badge_configs[$class] ?? null;

if ( $badge_config ) {
    $badge_label = ! empty( $label ) ? $label : $badge_config['default_label'];
    ?>
    <span class="directorist-badge directorist-info-item directorist-badge-<?php echo esc_attr( $class ); ?> <?php echo esc_attr( $badge_config['badge_class'] ); ?>">
        <?php if ( $badge_config['badge_type'] === 'icon_badge' ) : ?>
            <?php directorist_icon( $badge_config['icon'] ); ?>
            <span class="directorist-badge-tooltip <?php echo esc_attr( $badge_config['tooltip_class'] ); ?>"><?php echo esc_html( $badge_label ); ?></span>
        <?php else : ?>
            <?php echo esc_html( $badge_label ); ?>
        <?php endif; ?>
    </span>
    <?php
}
?>