<?php
/**
 * @author  wpWax
 * @since   7.0
 * @version 7.8.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}
?>
<div class="directorist-registration-wrapper directorist-w-100">
	<div class="<?php Helper::directorist_container_fluid(); ?>">
		<div class="<?php Helper::directorist_row(); ?>">
			<div class="directorist-col-md-6 directorist-offset-md-3">
				<div class="add_listing_title atbd_success_mesage">
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
					<?php if ( isset( $_GET['errors'] ) ) { ?>
						<p style="padding: 20px" class="alert-danger"><?php directorist_icon( 'las la-exclamation-triangle' ); ?> <?php echo wp_kses_post( directorist_get_registration_error_message( sanitize_text_field( wp_unslash( $_GET['errors'] ) ) ) ); ?></p>
					<?php } ?>
				</div>
			</div>
			<div class="directorist-col-md-6 directorist-offset-md-3">
				<div class="directory_register_form_wrap">
					<form action="<?php the_permalink(); ?>" method="post">
						<div class="directorist-form-group directorist-mb-15">
							<label for="username"><?php echo esc_html( $username ); ?> <strong class="directorist-form-required">*</strong></label>
							<input id="username" class="directorist-form-element" type="text" name="username" value="<?php echo isset( $_REQUEST['username'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['username'] ) ) ) : ''; ?>" required>
						</div>
						<div class="directorist-form-group directorist-mb-15">
							<label for="email"><?php echo esc_html( $email ); ?> <strong class="directorist-form-required">*</strong></label>
							<input id="email" class="directorist-form-element" type="text" name="email" value="<?php echo isset( $_REQUEST['email'] ) ? esc_attr( sanitize_email( wp_unslash( $_REQUEST['email'] ) ) ) : ''; ?>" required>
						</div>
						<?php if ( ! empty( $display_password_reg ) ) { ?>
							<div class="directorist-form-group directorist-mb-15">
								<label for="password"><?php
									echo esc_html( $password );
									echo ( ! empty( $require_password ) ? '<strong class="directorist-form-required">*</strong>' : '' );
								?></label>
								<input id="password" class="directorist-form-element" type="password" name="password" value="" <?php echo ( ! empty( $require_password ) ? 'required' : '' ); ?>>
							</div>
						<?php } ?>
						<?php if ( ! empty( $display_fname ) ) { ?>
						<div class="directorist-form-group directorist-mb-15">
							<label for="fname"><?php
								echo esc_html( $first_name );
								echo ( ! empty( $require_fname ) ? '<strong class="directorist-form-required">*</strong>' : '' );
							?></label>
							<input id="fname" class="directorist-form-element" type="text" name="fname" value="<?php echo isset( $_REQUEST['fname']) ? esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['fname'] ) ) ) : ''; ?>" <?php echo ( ! empty( $require_fname ) ? 'required' : '' ); ?>>
						</div>
						<?php } ?>
						<?php if ( ! empty( $display_lname ) ) { ?>
						<div class="directorist-form-group directorist-mb-15">
							<label for="lname"><?php
								echo esc_html( $last_name );
								echo ( ! empty( $require_lname ) ? '<strong class="directorist-form-required">*</strong>' : '' );
							?></label>
							<input class="directorist-form-element" id="lname" type="text" name="lname" value="<?php echo isset( $_REQUEST['lname']) ? esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['lname'] ) ) ) : ''; ?>" <?php echo ( ! empty( $require_lname ) ? 'required' : '' ); ?>>
						</div>
						<?php } ?>
						<?php if ( ! empty( $display_website ) ) {  ?>
							<div class="directorist-form-group directorist-mb-15">
								<label for="website"><?php
									echo esc_html( $website );
									echo ( ! empty( $require_website ) ? '<strong class="directorist-form-required">*</strong>' : '' );
								?></label>
								<input id="website" class="directorist-form-element" type="text" name="website" value="<?php echo isset( $_REQUEST['website']) ? esc_url( sanitize_text_field( wp_unslash( $_REQUEST['website'] ) ) ) : ''; ?>" <?php echo ( ! empty( $require_website ) ? 'required' : '' ); ?>>
							</div>
						<?php } ?>
						<?php if ( ! empty( $display_bio ) ) { ?>
						<div class="directorist-form-group directorist-mb-15">
							<label for="bio"><?php
								echo esc_html( $bio );
								echo ( ! empty( $require_bio ) ? '<strong class="directorist-form-required">*</strong>' : '' );
							?></label>
							<textarea id="bio" class="directorist-form-element" name="bio" rows="10" <?php echo ( ! empty( $require_bio ) ? 'required' : '' ); ?>><?php echo isset( $_REQUEST['bio']) ? esc_textarea( sanitize_text_field( wp_unslash( $_REQUEST['bio'] ) ) ) : ''; ?></textarea>
						</div>
						<?php } ?>
						<?php if ( ! empty( get_directorist_option( 'display_user_type' ) ) ) {
							if ( empty( $user_type ) || 'author' === $user_type ) {
							?>
							<div class="atbd_user_type_area directory_regi_btn directorist-radio directorist-radio-circle directorist-mb-15">
								<input id="author_type" type="radio" name="user_type" value="author" <?php echo esc_attr( $author_checked ); ?>><label for="author_type" class="directorist-radio__label"><?php esc_html_e( 'I am an author', 'directorist' ); ?>
							</div>
							<?php } ?>
							<?php if ( empty( $user_type ) || 'general' == $user_type ) { ?>
							<div class="atbd_user_type_area directory_regi_btn directorist-radio directorist-radio-circle directorist-mb-15">
								<input id="general_type" type="radio" name="user_type" value="general" <?php echo esc_attr( $general_checked ); ?>><label for="general_type" class="directorist-radio__label"><?php esc_html_e( 'I am a user', 'directorist' ); ?>
							</div>
							<?php } ?>
						<?php } ?>

						<?php if ( ! empty( get_directorist_option( 'registration_privacy', 1 ) ) ) { ?>
							<div class="atbd_privacy_policy_area directory_regi_btn directorist-checkbox directorist-mb-15">
								<input id="directorist_registration-privacy_policy" type="checkbox" name="privacy_policy" <?php echo( ( isset( $privacy_policy ) && 'on' === $privacy_policy ) ? 'checked="checked"' : '' ); ?>>
								<label for="directorist_registration-privacy_policy" class="directorist-checkbox__label"><?php echo esc_html( $privacy_label ); ?> <a style="color: red" target="_blank" href="<?php echo esc_url( $privacy_page_link ); ?>"><?php echo esc_html( $privacy_label_link ); ?></a> <span class="directorist-form-required">*</span></label>
							</div>
						<?php } ?>
						<?php if ( ! empty( get_directorist_option('regi_terms_condition', 1 ) ) ) { ?>
							<div class="atbd_term_and_condition_area directory_regi_btn directorist-checkbox directorist-mb-15">
								<input id="directorist_registration-listing_t" type="checkbox" name="t_c_check" <?php echo( ( isset( $t_c_check ) && 'on' === $t_c_check ) ? 'checked="checked"' : '' ); ?>>
								<label for="directorist_registration-listing_t" class="directorist-checkbox__label"><?php echo esc_attr($terms_label); ?>
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
							<button type="submit" class="directorist-btn directorist-btn-primary" name="atbdp_user_submit"><?php echo esc_html( $reg_signup ); ?></button>
							<input type="hidden" value="<?php echo esc_attr( wp_create_nonce( directorist_get_nonce_key() ) ); ?>" name="directorist_nonce">
						</div>
						<?php if ( ! empty( $display_login ) ) { ?>
							<div class="directory_regi_btn">
								<p><?php echo esc_html( $login_text ); ?> <a href="<?php echo esc_url( $login_url ); ?>"><?php echo esc_html( $log_linkingmsg ); ?></a></p>
							</div>
						<?php } ?>
					</form>
				</div>
			</div>
		</div> <!--ends .row-->
	</div>
</div>
