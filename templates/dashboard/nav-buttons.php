<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="nav_button">

	<?php if ( $dashboard->user_can_submit() ): ?>
		<a href="<?php echo esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>" class="btn btn-primary"><?php esc_html_e( 'Submit Listing', 'directorist' ); ?></a>
	<?php endif; ?>

	<?php if ( $dashboard->user_type == 'general' && ! empty( $dashboard->become_author_button)): ?>
		<a href="#" class="btn btn-primary atbdp-become-author" data-nonce="<?php echo wp_create_nonce( 'atbdp_become_author' ); ?>" data-userId="<?php echo get_current_user_id(); ?>"><?php echo $dashboard->become_author_button_text; ?></a>
		<p id="atbdp-become-author-success"></p>
	<?php endif; ?>

	<a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="btn btn-secondary"><?php esc_html_e( 'Log Out', 'directorist' ); ?></a>
	
</div>