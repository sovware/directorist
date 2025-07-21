<?php
/**
 * @author  wpWax
 * @since   1.0
 * @version 8.5
 */

// Check if the user is logged in
$is_logged_in = is_user_logged_in();

// Check if current page is login, registration, or dashboard
$is_login_page        = atbdp_is_page( 'signin_signup' );
$is_registration_page = atbdp_is_page( 'registration' );
$is_dashboard_page    = atbdp_is_page( 'dashboard' );

// Check if current page is add_listing and guest listings are enabled
$is_guest_listing = atbdp_is_page( 'add_listing' ) && get_directorist_option( 'guest_listings' );

// If any of the above conditions are true, exit early
if ( $is_logged_in ||
    $is_login_page ||
    $is_registration_page ||
    $is_dashboard_page ||
    $is_guest_listing
) {
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

<div class="directorist-account-block-logout-mode">
    <?php echo wp_kses_post( $content ); ?>
</div>