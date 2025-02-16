<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0.11
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<span class="directorist-badge directorist-info-item directorist-badge-<?php echo esc_attr( $class )?> <?php echo esc_attr( $class === 'featured' ? $featured_badge_class : '' ); ?>">

    <?php if ( $class == 'featured' ) : ?>

        <?php if ( $featured_badge_type == 'icon_badge' ) : ?>

            <?php directorist_icon( 'fas fa-star' ); ?>
            <span class="directorist-badge-tooltip directorist-badge-tooltip__featured"><?php echo esc_html( ! empty( $label ) ? $label : 'Featured' ); ?></span>

        <?php else: ?>

            <?php echo esc_html( ! empty( $label ) ? $label : 'Featured' ); ?>

        <?php endif; ?>

    <?php elseif ( $class == 'new' ) : ?>

        <?php directorist_icon( 'fas fa-bolt' ); ?>
        <span class="directorist-badge-tooltip directorist-badge-tooltip__new"><?php echo esc_html( ! empty( $label ) ? $label : 'New' ); ?></span>

    <?php elseif ( $class == 'popular' ) : ?>

        <?php directorist_icon( 'fas fa-fire' ); ?>
        <span class="directorist-badge-tooltip directorist-badge-tooltip__popular"><?php echo esc_html( ! empty( $label ) ? $label : 'Popular' ); ?></span>

    <?php endif;?>

</span>