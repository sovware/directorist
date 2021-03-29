<?php
/**
 * @author  wpWax
 * @since   7.0
 * @version 7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$announcements = $dashboard->get_announcements();
?>
<div class="atbd_tab_inner" id="announcement">
    <div class="atbd_announcement_wrapper">
        <?php if ( !empty( $announcements ) ) : ?>
	        <div class="atbdp-accordion">
	            <?php foreach ( $announcements as $announcement_id => $announcement ): ?>
		            <div class="atbdp-announcement update-announcement-status announcement-item announcement-id-<?php echo esc_attr( $announcement_id ); ?>" data-post-id="<?php echo esc_attr( $announcement_id ); ?>">
						<div class="atbdp-announcement__date">
							<span class="atbdp-date-card-part-1"><?php echo get_the_date( 'd', $announcement_id ) ?></span>
							<span class="atbdp-date-card-part-2"><?php echo get_the_date( 'M', $announcement_id ) ?></span>
							<span class="atbdp-date-card-part-3"><?php echo get_the_date( 'Y', $announcement_id ) ?></span>
						</div>

						<div class="atbdp-announcement__content">
							<h3 class="atbdp-announcement__title"><?php echo esc_html( $announcement['title'] ); ?></h3>
							<p><?php echo esc_html( $announcement['content'] ); ?></p>
						</div>

						<div class="atbdp-announcement__close">
							<button class="close-announcement"><i class="<?php atbdp_icon_type( true ); ?>-times"></i></button>
						</div>
		            </div>
	            <?php endforeach; ?>
	        </div>
        <?php else: ?>
            <div class="directorist_not-found"><p><?php _e( 'No announcements found', 'directorist' ) ?></p></div>
        <?php endif; ?>
    </div>
</div>