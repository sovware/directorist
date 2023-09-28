<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<span class="directorist-badge directorist-info-item directorist-badge-<?php echo esc_attr( $class )?>">
    <?php if ( $class == 'featured' ) : ?>
        <?php directorist_icon( 'fas fa-star' ); ?>
        <span class="directorist-badge-tooltip directorist-badge-tooltip__featured">Featured</span>
    <?php elseif ( $class == 'new' ) : ?>
        <?php directorist_icon( 'fas fa-bolt' ); ?>
        <span class="directorist-badge-tooltip directorist-badge-tooltip__new">New</span>
    <?php elseif ( $class == 'popular' ) : ?>
        <?php directorist_icon( 'fas fa-fire' ); ?>
        <span class="directorist-badge-tooltip directorist-badge-tooltip__popular">Popular</span>
    <?php endif;?>
</span>