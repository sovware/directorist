<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<form action="#" id="user_profile_form" method="post">

	<div class="<?php Helper::directorist_row(); ?>">

		<div class="<?php Helper::directorist_column('lg-3'); ?>">

			<?php $dashboard->profile_pic_template(); ?>

		</div>

		<div class="<?php Helper::directorist_column('lg-9'); ?>">

			<div class="directorist-user-profile-edit">

				<div class="directorist-card directorist-user-profile-box">

					<div class="directorist-card__header">

						<h4 class="directorist-card__header--title"><?php esc_html_e( 'My Profile', 'directorist' ); ?></h4>

					</div>

					<div class="directorist-card__body">

						<div class="directorist-user-info-wrap">

							<input type="hidden" name="ID" value="<?php echo get_current_user_id(); ?>">

							<div class="directorist-user-full-name">

								<div class="directorist-form-group">

									<label for="full_name"><?php esc_html_e( 'Full Name', 'directorist' ); ?></label>
									
									<input class="directorist-form-element" type="text" id="full_name" name="user[full_name]" value="<?php echo esc_attr( $dashboard->user_info( 'display_name' ) ); ?>" placeholder="<?php esc_html_e( 'Enter your full name', 'directorist' ); ?>">
								
								</div>
							
								<div class="directorist-form-group">

									<label for="user_name"><?php esc_html_e('User Name', 'directorist'); ?></label>
									
									<input class="directorist-form-element" id="user_name" type="text" disabled="disabled" name="user[user_name]" value="<?php echo esc_attr( $dashboard->user_info( 'username' ) ); ?>"> <span class="directorist-input-extra-info"><?php esc_html_e( '(username can not be changed)', 'directorist' ); ?></span>
								
								</div>
								
							</div>

							<div class="directorist-user-first-name">
								
								<div class="directorist-form-group">

									<label for="first_name"><?php esc_html_e( 'First Name', 'directorist' ); ?></label>
									
									<input class="directorist-form-element" id="first_name" type="text" name="user[first_name]" value="<?php echo esc_attr( $dashboard->user_info( 'first_name' ) ); ?>">
								
								</div>
							
								<div class="directorist-form-group">

									<label for="last_name"><?php esc_html_e( 'Last Name', 'directorist' ); ?></label>
									
									<input class="directorist-form-element" id="last_name" type="text" name="user[last_name]" value="<?php echo esc_attr( $dashboard->user_info( 'last_name' ) ); ?>">
								
								</div>								

							</div>

							<div class="directorist-user-email">

								<div class="directorist-form-group">

									<label for="req_email"><?php esc_html_e( 'Email (required)', 'directorist' ); ?></label>
									
									<input class="directorist-form-element" id="req_email" type="text" name="user[user_email]" value="<?php echo esc_attr( $dashboard->user_info( 'email' ) ); ?>" required>
								
								</div>
							
								<div class="directorist-form-group">

									<label for="phone"><?php esc_html_e( 'Phone', 'directorist' ); ?></label>
									
									<input class="directorist-form-element" type="tel" id="phone" name="user[phone]" value="<?php echo esc_attr( $dashboard->user_info( 'phone' ) ); ?>" placeholder="<?php esc_attr_e( 'Enter your phone number', 'directorist' ); ?>">
								
								</div>
								
							</div>

							<div class="directorist-user-site-url">

								<div class="directorist-form-group">

									<label for="website"><?php esc_html_e( 'Website', 'directorist' ); ?></label>

									<input class="directorist-form-element" id="website" type="text" name="user[website]" value="<?php echo esc_attr( $dashboard->user_info( 'website' ) ); ?>">
								
								</div>
							
								<div class="directorist-form-group">
									
									<label for="address"><?php esc_html_e( 'Address', 'directorist' ); ?></label>
									
									<input class="directorist-form-element" id="address" type="text" name="user[address]" value="<?php echo esc_attr( $dashboard->user_info( 'address' ) ); ?>">
								
								</div>
								
							</div>

							<div class="directorist-user-password">

								<div class="directorist-form-group">

									<label for="new_pass"><?php esc_html_e( 'New Password', 'directorist' ); ?></label>

									<input id="new_pass" class="directorist-form-element" type="password" name="user[new_pass]" placeholder="<?php esc_attr_e( 'Enter a new password', 'directorist' ); ?>">
								</div>
							
								<div class="directorist-form-group">

									<label for="confirm_pass"><?php esc_html_e( 'Confirm New Password', 'directorist' ); ?></label>

									<input id="confirm_pass" class="directorist-form-element" type="password" name="user[confirm_pass]" placeholder="<?php esc_attr_e( 'Confirm your new password', 'directorist' ); ?>">
								</div>

								<div class="directorist-form-group">

									<label for="bio"><?php esc_html_e( 'About Author', 'directorist' ); ?></label>

									<textarea class="wp-editor-area directorist-form-element" style="height: 200px" autocomplete="off" cols="40" name="user[bio]" id="bio"><?php echo esc_html( $dashboard->user_info( 'bio' ) ); ?></textarea>
								</div>

							</div>

							<div class="directorist-user-socials">

								<h4 class="directorist-user-social-label"><?php esc_html_e( 'Social Profiles', 'directorist' ); ?></h4>

								<div class="directorist-form-group">

									<label for="facebook"><span class="directorist-social-icon"><i class="<?php atbdp_icon_type( true ); ?>-facebook"></i></span> <?php esc_html_e( 'Facebook', 'directorist' ); ?></label>
									
									<input id="facebook" class="directorist-form-element" type="url" name="user[facebook]" value="<?php echo esc_attr( $dashboard->user_info( 'facebook' ) ); ?>" placeholder="<?php esc_attr_e( 'Enter your facebook url', 'directorist' ); ?>">
									
									<span class="directorist-input-extra-info"><?php esc_html_e( 'Leave it empty to hide', 'directorist' ) ?></span>

								</div>
							
								<div class="directorist-form-group">

									<label for="twitter"><span class="directorist-social-icon"><i class="<?php atbdp_icon_type( true ); ?>-twitter"></i></span><?php esc_html_e( 'Twitter', 'directorist' ); ?></label>
									
									<input id="twitter" class="directorist-form-element" type="url" name="user[twitter]" value="<?php echo esc_attr( $dashboard->user_info( 'twitter' ) ); ?>" placeholder="<?php esc_attr_e( 'Enter your twitter url', 'directorist' ); ?>">
									
									<span class="directorist-input-extra-info"><?php esc_html_e( 'Leave it empty to hide', 'directorist' ) ?></span>

								</div>
							
								<div class="directorist-form-group">

									<label for="linkedIn"><span class="directorist-social-icon"><i class="<?php atbdp_icon_type( true ); ?>-linkedin"></i></span><?php esc_html_e( 'LinkedIn', 'directorist' ); ?></label>
									
									<input id="linkedIn" class="directorist-form-element" type="url" name="user[linkedIn]" value="<?php echo esc_attr( $dashboard->user_info( 'linkedin' ) ); ?>" placeholder="<?php esc_attr_e( 'Enter linkedIn url', 'directorist' ); ?>">
									
									<span class="directorist-input-extra-info"><?php esc_html_e( 'Leave it empty to hide', 'directorist' ) ?></span>

								</div>
							
								<div class="directorist-form-group">

									<label for="youtube"><span class="directorist-social-icon"><i class="<?php atbdp_icon_type( true ); ?>-youtube"></i></span><?php esc_html_e( 'Youtube', 'directorist' ); ?></label>
									
									<input id="youtube" class="directorist-form-element" type="url" name="user[youtube]" value="<?php echo esc_attr( $dashboard->user_info( 'youtube' ) ); ?>" placeholder="<?php esc_attr_e( 'Enter youtube url', 'directorist' ); ?>">
									
									<span class="directorist-input-extra-info"><?php esc_html_e( 'Leave it empty to hide', 'directorist' ) ?></span>
									
								</div>
								
							</div>

							<button type="submit" class="directorist-btn directorist-btn-lg directorist-btn-dark directorist-btn-profile-save" id="update_user_profile"><?php esc_html_e( 'Save Changes', 'directorist' ); ?></button>

							<div id="directorist-prifile-notice"></div>

						</div>

					</div>

				</div>

			</div>

		</div>
	</div>

</form>