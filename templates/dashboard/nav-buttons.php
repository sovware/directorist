<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-tab__nav__action">

	<?php if ( $dashboard->user_can_submit() ): ?>
		<a href="<?php echo esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>" class="directorist-btn directorist-btn--add-listing"><?php esc_html_e( 'Submit Listing', 'directorist' ); ?></a>
	<?php endif; ?>

	<?php if ( $dashboard->user_type == 'general' && ! empty( $dashboard->become_author_button)): ?>
		<a href="#" class="directorist-btn directorist-become-author"><?php echo esc_html( $dashboard->become_author_button_text ); ?></a>
		<p id="directorist-become-author-success"></p>

		<div class="directorist-become-author-modal">
            <div class="directorist-become-author-modal__content">
                <h3><?php esc_html_e( 'Are you sure you want to become an author?', 'directorist' ); ?> <br><?php esc_html_e( '(It is subject to approval by the admin)', 'directorist' ); ?> <span class="directorist-become-author__loader"></span></h3>
                <p>
                    <a href="#" class="directorist-become-author-modal__cancel">Cancel</a>
                    <a href="#" class="directorist-become-author-modal__approve" data-nonce="<?php echo esc_attr( wp_create_nonce('atbdp_become_author' ) ); ?>" data-userId='<?php echo esc_attr( get_current_user_id() ); ?>'>Yes</a>
                </p>
            </div>
        </div>
	<?php endif; ?>

	<a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="directorist-btn directorist-btn-secondary directorist-btn--logout"><?php esc_html_e( 'Sign Out', 'directorist' ); ?></a>

</div>