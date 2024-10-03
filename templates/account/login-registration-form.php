<?php
/**
 * @author  wpWax
 * @since   7.8.3
 * @version 7.12.4
 */

use \Directorist\Helper;

$user_email = isset( $_GET['user'] ) ? sanitize_email( wp_unslash( base64_decode( $_GET['user'] ) ) ) : '';
$key        = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '';
?>
<div class="directorist-login-wrapper directorist-authentication active directorist-w-100">
    <div class="<?php Helper::directorist_container_fluid(); ?>">
        <div class="<?php Helper::directorist_row(); ?>">
            <div class="directorist-col-md-6 directorist-offset-md-3">
				<div class="atbdp_login_form_shortcode directorist-authentication__form">
					<?php if ( directorist_is_email_verification_enabled() && ! empty( $_GET['verification'] ) && is_email( $user_email ) ) : ?>
						<p class="directorist-alert directorist-alert-success"><span>
							<?php
							$send_confirm_mail_url = add_query_arg( array(
								'action'            => 'directorist_send_confirmation_email',
								'user'              => $user_email,
								'directorist_nonce' => wp_create_nonce( 'directorist_nonce' ),
							), admin_url( 'admin-ajax.php' ) );

							echo wp_kses( sprintf( __( "Thank you for signing up! To complete the registration, please verify your email address by clicking on the link we have sent to your email.<br><br>If you didn't find the verification email, please check your spam folder. If you still can't find it, click on the <a href='%s'>Resend confirmation email</a> to have a new email sent to you.", 'directorist' ), esc_url( $send_confirm_mail_url ) ), array( 'a' => array( 'href' => array() ), 'br' => array() ) );
							?>
						</span></p>
					<?php endif; ?>

					<?php if ( directorist_is_email_verification_enabled() && ! empty( $_GET['send_verification_email'] ) ) : ?>
						<p class="directorist-alert directorist-alert-success"><span>
							<?php echo wp_kses( __( "Thank you for requesting a new verification email. Please check your inbox and verify to complete the registration.<br><br>If you still can't find it, please check your spam folder. And please contact if you are still having trouble.", 'directorist' ), array( 'br' => array() ) ); ?>
						</span></p>
					<?php endif; ?>

					<?php
					// start recovery stuff
					if ( is_email( $user_email ) && ! empty( $key ) ) {
						$user = get_user_by( 'email', $user_email );

						if ( ! $user ) { ?>
							<p class="directorist-alert directorist-alert-danger">
								<?php esc_html_e( 'Sorry! user not found', 'directorist' ); ?>
							</p>
						<?php } else {
							$is_valid_password_reset_key = check_password_reset_key( $key, $user->user_login );

							if ( is_wp_error( $is_valid_password_reset_key ) ) {
								?><p class="directorist-alert directorist-alert-danger">
									<?php echo $is_valid_password_reset_key->get_error_message(); ?>
								</p><?php
							} else {
								if ( ! empty( $_POST['directorist_reset_password'] ) && directorist_verify_nonce( 'directorist-reset-password-nonce', 'reset_password' ) ) :
									// Ignore password sanitization
									$password_1 = isset( $_POST['password_1'] ) ? $_POST['password_1'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
									$password_2 = isset( $_POST['password_2'] ) ? $_POST['password_2'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

									if ( empty( $password_1 ) || empty( $password_2 ) ) : ?>
										<p class="atbd_reset_warning directorist-alert directorist-alert-danger"><?php echo esc_html__( 'Passwords cannot be empty.', 'directorist' ); ?></p>
									<?php elseif ( $password_1 !== $password_2 ) : ?>
										<p class="atbd_reset_error directorist-alert directorist-alert-danger"><?php echo esc_html__( 'Passwords do not match!', 'directorist' ); ?></p>
									<?php else :
										wp_set_password( $password_2, $user->ID );
										// Since password reset is handled through email, so we can consider it verified!
										delete_user_meta( $user->ID, 'directorist_user_email_unverified' );
										?>
										<p class="atbd_reset_success directorist-alert directorist-alert-success"><?php echo wp_kses( sprintf(
											__( 'Password changed successfully. Please <a href="%s">click here to login</a>.', 'directorist' ),
											esc_url( ATBDP_Permalink::get_dashboard_page_link() )
										), array( 'a' => array( 'href' => array() ) ) ); ?></p>
									<?php endif;
								endif;

								if ( ! empty( $_GET['password_reset'] ) ) {
									include ATBDP_DIR . 'templates/account/password-reset-form.php';
								}

								if ( ! empty( $_GET['confirm_mail'] ) ) {
									/**
									 * Verify user and send registration confirmation mail
									 */
									delete_user_meta( $user->ID, 'directorist_user_email_unverified' );
									ATBDP()->email->custom_wp_new_user_notification_email( $user->ID );
									?>
									<div class="directorist-alert directorist-alert-success">
										<?php echo wp_kses( sprintf(
											__( 'Email verification successful. Please <a href="%s">click here to login</a>.', 'directorist' ),
											esc_url( ATBDP_Permalink::get_dashboard_page_link() )
										), array( 'a' => array( 'href' => array() ) ) ); ?>
									</div>
									<?php
								}
							}
						}
					} else {
						$log_username        = get_directorist_option( 'log_username', __( 'Username or Email Address', 'directorist' ) );
						$log_password        = get_directorist_option( 'log_password', __( 'Password', 'directorist' ) );
						$display_rememberMe  = get_directorist_option( 'display_rememberme', 1 );
						$log_rememberMe      = get_directorist_option( 'log_rememberme', __( 'Remember Me', 'directorist' ) );
						$log_button          = get_directorist_option( 'log_button', __( 'Log In', 'directorist' ) );
						$display_recpass     = get_directorist_option( 'display_recpass', 1 );
						$recpass_text        = get_directorist_option( 'recpass_text', __( 'Forgot Password?', 'directorist' ) );
						$recpass_desc        = get_directorist_option( 'recpass_desc', __( 'Lost your password? Please enter your email address. You will receive a link to create a new password via email.', 'directorist' ) );
						$recpass_username    = get_directorist_option( 'recpass_username', __( 'E-mail:', 'directorist' ) );
						$recpass_placeholder = get_directorist_option( 'recpass_placeholder', __( 'eg. mail@example.com', 'directorist' ) );
						$recpass_button      = get_directorist_option( 'recpass_button', __( 'Get New Password', 'directorist' ) );
						$reg_text            = get_directorist_option( 'reg_text', __( "Don't have an account?", 'directorist' ) );
						$reg_url             = ATBDP_Permalink::get_registration_page_link();
						$reg_linktxt         = get_directorist_option( 'reg_linktxt', __( 'Sign Up', 'directorist' ) );
						$display_signup      = get_directorist_option( 'display_signup', 1 );

						?>
						<form action="#" id="login" method="POST">
							<p class="status"></p>
							<div class="directorist-form-group directorist-mb-15">
								<label for="directorist__authentication__signin__username"><?php echo esc_html( $log_username ); ?></label>
								<input type="text" class="directorist-form-element" id="username" name="username" />
							</div>

							<div class="directorist-form-group">
								<label for="directorist__authentication__signin__password"><?php echo esc_html( $log_password ); ?></label>
								<input type="password" id="password" autocomplete="off" name="password" class="directorist-form-element" />
							</div>

							<div class="directorist-authentication__form__actions">
								<div class="keep_signed directorist-checkbox">
									<?php if ( $display_rememberMe ) : ?>
										<input type="checkbox" id="directorist_login_keep_signed_in" value="1" name="keep_signed_in" checked />
										<label for="directorist_login_keep_signed_in" class="directorist-checkbox__label not_empty">
											<?php echo esc_html( $log_rememberMe ); ?>
										</label>
									<?php endif; ?>
								</div>

								<?php if ( $display_recpass ) :
									$output = sprintf( "<a href='' class='atbdp_recovery_pass'> " . $recpass_text . '</a>' );
									echo wp_kses_post( $output );
								endif; ?>
							</div>

							<div class="directorist-form-group atbd_login_btn_wrapper directorist-mb-15 directorist-authentication__form__btn-wrapper">
								<button class="directorist-btn directorist-btn-block directorist-btn-primary directorist-authentication__form__btn" type="submit" value="<?php echo esc_attr( $log_button ); ?>" name="submit" aria-label="Signin Button"><?php echo esc_html( $log_button ); ?></button>
								<?php wp_nonce_field( 'ajax-login-nonce', 'security' );?>
							</div>
						</form>

						<div class="atbd_social_login">
							<?php do_action( 'atbdp_before_login_form_end' );?>
						</div>

						<?php if ( ! empty( $display_signup ) && $new_user_registration ) : ?>
							<div class="directorist-authentication__form__toggle-area">
								<?php echo esc_html( $reg_text ); ?>
								<button class="directorist-authentication__btn directorist-authentication__btn--signup" aria-label="Signup Button"><?php echo esc_html( $reg_linktxt ); ?></button>
							</div>
						<?php endif; ?>

						<?php
						//stuff to recover password start
						$error = '';
						$success = '';
						// check if we're in reset form
						if ( isset( $_POST['action'] ) && 'reset' === $_POST['action'] && directorist_verify_nonce() ) :

							$email = isset( $_POST['user_login'] ) ? sanitize_email( wp_unslash( $_POST['user_login'] ) ) : '';

							if ( empty( $email ) ) {
								$error = __( 'Email address cannot be empty.', 'directorist' );
							} else if ( ! is_email( $email ) ) {
								$error = __( 'Invalid e-mail address.', 'directorist' );
							} else if ( ! email_exists( $email ) ) {
								$error = __( 'There is no user registered with that email address.', 'directorist' );
							} else {
								$user      = get_user_by( 'email', $email );
								/* translators: %s: site name */
								$subject   = esc_html( sprintf( __( '[%s] Reset Your Password', 'directorist' ), get_option( 'blogname', 'display' )  ));
								$title     = esc_html__( 'Password Reset Request', 'directorist' );
								$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

								/* translators: %1$s: site name, %1$s: user name, %3$s: password reset link */
								$message = sprintf( __( 'Someone has requested a password reset for the following account:
									<strong>Site name:</strong> %1$s
									<strong>User name:</strong> %2$s
									To reset your password, please click on the <a href="%3$s">Reset Password</a>.<br>
									If this was a mistake, just ignore this email and nothing will happen.'
									),
									$site_name,
									$user->user_login,
									esc_url( directorist_password_reset_url( $user, true ) )
								);

								$message = wp_kses( $message, array(
									'br' => array(),
									'strong' => array(),
									'a' => array(
										'href' => array()
									)
								) );

								$message = atbdp_email_html( $title, nl2br( $message ) );

								$headers[] = 'Content-Type: text/html; charset=UTF-8';
								$mail      = wp_mail( $email, $subject, $message, $headers );
								if ( $mail ) {
									$success = __( 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox.', 'directorist' );
								} else {
									$error = __( 'Something went wrong, unable to send the password reset email. If the issue persists please contact with the site administrator.', 'directorist' );
								}

							}

							if ( ! empty( $error ) ) {
								echo '<div class="message"><p class="error directorist-alert directorist-alert-danger">' . wp_kses( sprintf( __( '<strong>ERROR: </strong> %s', 'directorist' ), esc_html( $error ) ), array( 'strong' => array() ) ) . '</p></div>';
							}

							if ( ! empty( $success ) ) {
								echo '<div class="error_login"><p class="success directorist-alert directorist-alert-success">' . esc_html( $success ) . '</p></div>';
							}

						endif; ?>

						<div id="recover-pass-modal" class="directorist-mt-15 directorist-authentication__form__recover-pass-modal">
							<form method="post">
								<fieldset class="directorist-form-group">
									<p><?php echo esc_html( $recpass_desc ); ?></p>
									<label for="reset_user_login"><?php echo esc_html( $recpass_username ); ?></label>
									<input type="text" class="directorist-mb-15 directorist-form-element" name="user_login" id="reset_user_login" value="<?php echo isset( $_POST['user_login'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['user_login'] ) ) ) : ''; ?>" placeholder="<?php echo esc_attr( $recpass_placeholder ); ?>" required="required" />
									<div class="directorist-authentication__form__btn-wrapper">
										<input type="hidden" name="action" value="reset" />
										<button type="submit" class="directorist-btn directorist-btn-primary directorist-authentication__form__btn" id="directorist__authentication__submit" aria-label="Recover Password Button"><?php echo esc_html( $recpass_button ); ?></button>
										<input type="hidden" value="<?php echo esc_attr( wp_create_nonce( directorist_get_nonce_key() ) ); ?>" name="directorist_nonce">
									</div>
								</fieldset>
							</form>
						</div>
					<?php }; ?>
				</div><!-- /.atbdp_login_form_shortcode -->
			</div>
		</div>
	</div>
</div>


<div class="directorist-registration-wrapper directorist-authentication directorist-w-100">
	<div class="<?php Helper::directorist_container_fluid(); ?>">
		<div class="<?php Helper::directorist_row(); ?>">
			<div class="directorist-col-md-6 directorist-offset-md-3">
				<div class="directory_register_form_wrap directorist-authentication__form">
					<div class="add_listing_title atbd_success_mesage directorist-authentication__message">
						<?php
						if ( ! empty( $_GET['registration_status'] ) && true == $_GET['registration_status'] ) {
							if ( empty( $display_password_reg ) ) {
								?>
								<p style="padding: 20px" class="alert-success directorist-alert directorist-alert-success"><span><?php directorist_icon( 'las la-check' ); ?> <?php esc_html_e('Go to your inbox or spam/junk and get your password.', 'directorist'); ?>
									<?php
									$output = sprintf( __( 'Click %s to login.', 'directorist' ), '<a href="' . ATBDP_Permalink::get_login_page_link() . '"><i style="color: red">' . __( 'Here', 'directorist' ) . '</i></a>' );
									echo wp_kses_post( $output );
									?>
								</span></p>
							<?php } else { ?>
								<!--registration succeeded, so show notification -->
								<p style="padding: 20px" class="alert-success directorist-alert directorist-alert-success"><span><?php directorist_icon( 'las la-check' ); ?> <?php esc_html_e('Registration completed. Please check your email for confirmation.', 'directorist'); ?>
									<?php
									$output = sprintf( __('Or click %s to login.', 'directorist' ), '<a href="' . ATBDP_Permalink::get_login_page_link() . '"><span style="color: red">' . __( 'Here', 'directorist' ) . '</span></a>' );
									echo wp_kses_post( $output );
									?>
								</span></p>
							<?php
							}
						}
						?>
						<!--Registration failed, so show notification.-->
						<p style="padding: 20px; display:none;" class="alert-danger directorist-register-error"><?php directorist_icon( 'las la-exclamation-triangle' ); ?></p>
					</div>
					<form action="<?php the_permalink(); ?>" method="post" class="directorist__authentication__signup">
						<div class="directorist-form-group directorist-mb-35">
							<label for="directorist__authentication__signup__username"><?php echo esc_html( $username ); ?> <strong class="directorist-form-required">*</strong></label>
							<input id="directorist__authentication__signup__username" class="directorist-form-element" type="text" name="username" value="<?php echo isset( $_REQUEST['username'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['username'] ) ) ) : ''; ?>" required>
						</div>
						<div class="directorist-form-group directorist-mb-35">
							<label for="directorist__authentication__signup__email"><?php echo esc_html( $email ); ?> <strong class="directorist-form-required">*</strong></label>
							<input id="directorist__authentication__signup__email" class="directorist-form-element" type="text" name="email" value="<?php echo isset( $_REQUEST['email'] ) ? esc_attr( sanitize_email( wp_unslash( $_REQUEST['email'] ) ) ) : ''; ?>" required>
						</div>
						<?php if ( ! empty( $display_password_reg ) ) { ?>
							<div class="directorist-form-group directorist-mb-35">
								<label for="directorist__authentication__signup__password"><?php
									echo esc_html( $password );
									echo ( ! empty( $require_password ) ? '<strong class="directorist-form-required">*</strong>' : '' );
								?></label>
								<input id="directorist__authentication__signup__password" class="directorist-form-element" type="password" name="password" value="" <?php echo ( ! empty( $require_password ) ? 'required' : '' ); ?>>
							</div>
						<?php } ?>
						<?php if ( ! empty( $display_fname ) ) { ?>
						<div class="directorist-form-group directorist-mb-35">
							<label for="directorist__authentication__signup__fname"><?php
								echo esc_html( $first_name );
								echo ( ! empty( $require_fname ) ? '<strong class="directorist-form-required">*</strong>' : '' );
							?></label>
							<input id="directorist__authentication__signup__fname" class="directorist-form-element" type="text" name="fname" value="<?php echo isset( $_REQUEST['fname']) ? esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['fname'] ) ) ) : ''; ?>" <?php echo ( ! empty( $require_fname ) ? 'required' : '' ); ?>>
						</div>
						<?php } ?>
						<?php if ( ! empty( $display_lname ) ) { ?>
						<div class="directorist-form-group directorist-mb-35">
							<label for="directorist__authentication__signup__lname"><?php
								echo esc_html( $last_name );
								echo ( ! empty( $require_lname ) ? '<strong class="directorist-form-required">*</strong>' : '' );
							?></label>
							<input class="directorist-form-element" id="directorist__authentication__signup__lname" type="text" name="lname" value="<?php echo isset( $_REQUEST['lname']) ? esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['lname'] ) ) ) : ''; ?>" <?php echo ( ! empty( $require_lname ) ? 'required' : '' ); ?>>
						</div>
						<?php } ?>
						<?php if ( ! empty( $display_website ) ) {  ?>
							<div class="directorist-form-group directorist-mb-35">
								<label for="directorist__authentication__signup__website"><?php
									echo esc_html( $website );
									echo ( ! empty( $require_website ) ? '<strong class="directorist-form-required">*</strong>' : '' );
								?></label>
								<input id="directorist__authentication__signup__website" class="directorist-form-element" type="text" name="website" value="<?php echo isset( $_REQUEST['website']) ? esc_url( sanitize_text_field( wp_unslash( $_REQUEST['website'] ) ) ) : ''; ?>" <?php echo ( ! empty( $require_website ) ? 'required' : '' ); ?>>
							</div>
						<?php } ?>
						<?php if ( ! empty( $display_bio ) ) { ?>
						<div class="directorist-form-group directorist-mb-35">
							<label for="directorist__authentication__signup__bio"><?php
								echo esc_html( $bio );
								echo ( ! empty( $require_bio ) ? '<strong class="directorist-form-required">*</strong>' : '' );
							?></label>
							<textarea id="directorist__authentication__signup__bio" class="directorist-form-element" name="bio" rows="10" placeholder="<?php echo esc_html( $bio ); ?>" <?php echo ( ! empty( $require_bio ) ? 'required' : '' ); ?>><?php echo isset( $_REQUEST['bio']) ? esc_textarea( sanitize_text_field( wp_unslash( $_REQUEST['bio'] ) ) ) : ''; ?></textarea>
						</div>
						<?php } ?>
						<?php if ( ! empty( get_directorist_option( 'display_user_type' ) ) ) {
							if ( empty( $user_type ) || 'author' === $user_type ) {
							?>
							<div class="atbd_user_type_area directory_regi_btn directorist-radio directorist-radio-circle directorist-mb-35">
								<input id="author_type" type="radio" name="user_type" value="author" <?php echo esc_attr( $author_checked ); ?>><label for="author_type" class="directorist-radio__label"><?php esc_html_e( 'I am an author', 'directorist' ); ?>
							</div>
							<?php } ?>
							<?php if ( empty( $user_type ) || 'general' == $user_type ) { ?>
							<div class="atbd_user_type_area directory_regi_btn directorist-radio directorist-radio-circle directorist-mb-35">
								<input id="general_type" type="radio" name="user_type" value="general" <?php echo esc_attr( $general_checked ); ?>><label for="general_type" class="directorist-radio__label"><?php esc_html_e( 'I am a user', 'directorist' ); ?>
							</div>
							<?php } ?>
						<?php } ?>
						
						<?php if ( ! empty( get_directorist_option( 'registration_privacy', 1 ) ) ) { ?>
							<div class="atbd_privacy_policy_area directory_regi_btn directorist-checkbox directorist-mb-20">
								<input id="directorist__authentication__signup__privacy_policy" type="checkbox" name="privacy_policy" <?php echo( ( isset( $privacy_policy ) && 'on' === $privacy_policy ) ? 'checked="checked"' : '' ); ?>>
								<label for="directorist__authentication__signup__privacy_policy" class="directorist-checkbox__label"><?php echo esc_html( $privacy_label ); ?> <a style="color: red" target="_blank" href="<?php echo esc_url( $privacy_page_link ); ?>"><?php echo esc_html( $privacy_label_link ); ?></a> <span class="directorist-form-required">*</span></label>
							</div>
						<?php } ?>
						<?php if ( ! empty( get_directorist_option('regi_terms_condition', 1 ) ) ) { ?>
							<div class="atbd_term_and_condition_area directory_regi_btn directorist-checkbox directorist-mb-30">
								<input id="directorist__authentication__signup__listing_t" type="checkbox" name="t_c_check" <?php echo( ( isset( $t_c_check ) && 'on' === $t_c_check ) ? 'checked="checked"' : '' ); ?>>
								<label for="directorist__authentication__signup__listing_t" class="directorist-checkbox__label"><?php echo esc_attr($terms_label); ?>
									<a style="color: red" target="_blank" href="<?php echo esc_url($t_C_page_link)?>"><?php echo esc_attr($terms_label_link); ?></a> <span class="directorist-form-required">*</span></label>
							</div>
						<?php } ?>

						<?php
						/*
						 * @since 4.4.0
						 */
						do_action( 'atbdp_before_user_registration_submit' );
						?>
						<div class="directory_regi_btn directorist-mb-15">
							<?php if ( get_directorist_option( 'redirection_after_reg' ) === 'previous_page' ) { ?>
							<input type="hidden" name='previous_page' value='<?php echo esc_url( wp_get_referer() ); ?>'>
							<?php } ?>
							<input type="hidden" value="<?php echo esc_attr( wp_create_nonce( directorist_get_nonce_key() ) ); ?>" name="directorist_nonce">
							<button type="submit" class="directorist-btn directorist-btn-primary directorist-authentication__form__btn" name="atbdp_user_submit"><?php echo esc_html( $reg_signup ); ?></button>
						</div>
						<?php if ( ! empty( $display_login ) ) { ?>
							<div class="directory_regi_btn directorist-authentication__form__toggle-area">
								<p><?php echo esc_html( $login_text ); ?> <button class="directorist-authentication__btn directorist-authentication__btn--signin"><?php echo esc_html( $log_linkingmsg ); ?></button></p>
							</div>
						<?php } ?>
					</form>
				</div>
			</div>
		</div> <!--ends .row-->
	</div>
</div>
