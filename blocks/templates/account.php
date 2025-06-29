<?php
/**
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */

if ( is_user_logged_in() ) {
	return;
}

if ( atbdp_is_page( 'add_listing' ) && get_directorist_option( 'guest_listings' ) ) {
	return;
}
?>

<div class="directorist-account-block-authentication-modal">

	<div class="modal fade" id="directorist-account-block-login-modal" role="dialog" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered" role="document">

			<div class="modal-content">

				<div class="modal-header">

					<div class="modal-title" id="login_modal_label"><?php esc_html_e( 'Account', 'directorist' );?></div>

					<button type="button" class="directorist-account-block-close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>

				</div>

				<div class="modal-body">

					<?php echo do_shortcode( '[directorist_user_login]' ); ?>

				</div>

			</div>

		</div>

	</div>

</div>