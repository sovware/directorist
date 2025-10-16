<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 8.5
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! $listing->has_badge( $data ) ) {
    return;
}
?>

<?php if ( $listing->display_new_badge( $data ) || $listing->display_featured_badge( $data ) || $listing->display_popular_badge( $data ) ) : ?>

    <div class="directorist-info-item directorist-info-item-badges">

        <?php if ( $listing->display_new_badge( $data ) ) : ?>
            <span class="directorist-badge directorist-badge-new"><?php echo esc_html( Helper::new_badge_text() ); ?></span>
        <?php endif; ?>

        <?php if ( $listing->display_featured_badge( $data ) ) : ?>
            <span class="directorist-badge directorist-badge-featured ">
                <?php echo esc_html( Helper::featured_badge_text() ); ?>
            </span>
        <?php endif; ?>

        <?php if ( $listing->display_popular_badge( $data ) ) : ?>
            <span class="directorist-badge directorist-badge-popular"><?php echo esc_html( Helper::popular_badge_text() ); ?></span>
        <?php endif; ?>

    </div>

<?php endif; ?>