<?php
/**
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */

if ( is_user_logged_in() || atbdp_is_page( 'login' ) || atbdp_is_page( 'registration' ) ) {
	return;
}

if ( atbdp_is_page( 'add_listing' ) && get_directorist_option( 'guest_listings' ) ) {
	return;
}

$user_type = ! empty( $atts['user_type'] ) ? $atts['user_type'] : '';
$user_type = ! empty( $_REQUEST['user_type'] ) ? $_REQUEST['user_type'] : $user_type;
?>

<div class="directorist-account-block-authentication-modal">

	<div class="modal fade" id="directorist-account-block-login-modal" role="dialog" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered" role="document">

			<div class="modal-content">

				<div class="modal-header">

					<div class="modal-title" id="login_modal_label"><?php esc_html_e( 'Sign In', 'directorist' );?></div>

					<button type="button" class="directorist-account-block-close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>

				</div>

				<div class="modal-body">

					<?php include_once 'login.php';?>

				</div>

			</div>

		</div>

	</div>

	<div class="modal fade" id="directorist-account-block-register-modal" role="dialog" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered">

			<div class="modal-content">

				<div class="modal-header">

					<div class="modal-title"><?php esc_attr_e( 'Registration', 'directorist' );?></div>

					<button type="button" class="directorist-account-block-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span> </button>

				</div>

				<div class="modal-body">

					<?php include_once 'registration.php';?>

				</div>

			</div>

		</div>

	</div>

</div>