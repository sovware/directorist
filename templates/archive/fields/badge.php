<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<span class="directorist-badge directorist-info-item directorist-badge-<?php echo esc_attr( $class )?>">
    <?php if ( $class == 'featured' ) : ?>
        <?php directorist_icon( 'fas fa-star' ); ?>
    <?php elseif ( $class == 'new' ) : ?>
        <?php directorist_icon( 'fas fa-bolt' ); ?>
    <?php elseif ( $class == 'popular' ) : ?>
        <?php directorist_icon( 'fas fa-fire' ); ?>
    <?php endif;?>
</span>