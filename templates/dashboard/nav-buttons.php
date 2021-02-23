<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-tab__nav__action">

	<?php if ( $dashboard->user_can_submit() ): ?>
		<a href="<?php echo esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>" class="directorist-btn directorist-btn-dark directorist-btn--add-listing"><?php esc_html_e( 'Submit Listing', 'directorist' ); ?></a>
	<?php endif; ?>

	<?php if ( $dashboard->user_type == 'general' && ! empty( $dashboard->become_author_button)): ?>
		<a href="#" class="directorist-btn directorist-btn-primary directorist-become-author"><?php echo $dashboard->become_author_button_text; ?></a>
		<p id="directorist-become-author-success"></p>

		<div class="directorist-become-author-modal">
            <div class="directorist-become-author-modal__content">
                <!-- <a href="" class="directorist-become-author-modal__close">x</a> -->
                <h3><?php _e( 'Are you sure you want to become an author?', 'directorist' ); ?> <br><?php _e( '(It is subject to approval by the admin)', 'directorist' ); ?> <span class="directorist-become-author__loader"></span></h3>
                <p>
                    <a href="#" class="directorist-become-author-modal__cancel">Cancel</a>
                    <a href="#" class="directorist-become-author-modal__approve" data-nonce="<?php echo wp_create_nonce('atbdp_become_author') ?>" data-userId='<?php echo get_current_user_id(); ?>'>Yes</a>
                </p>
            </div>
        </div>
	<?php endif; ?>

	<a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="directorist-btn directorist-btn-secondary directorist-btn--logout"><?php esc_html_e( 'Log Out', 'directorist' ); ?></a>

</div>