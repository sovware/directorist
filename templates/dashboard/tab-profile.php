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

		<div class="<?php Helper::directorist_column(3); ?>">

			<?php $dashboard->profile_pic_template(); ?>

		</div>

		<div class="<?php Helper::directorist_column(9); ?>">

			<div class="atbd_user_profile_edit">

				<h4><?php esc_html_e( 'My Profile', 'directorist' ); ?></h4>

				<div class="user_info_wrap">

					<input type="hidden" name="ID" value="<?php echo get_current_user_id(); ?>">

					<div class="row_fu_name <?php Helper::directorist_row(); ?>">

						<div class="<?php Helper::directorist_column(6); ?>">
							<div class="form-group">
								<label for="full_name"><?php esc_html_e( 'Full Name', 'directorist' ); ?></label>
								<input class="form-control" type="text" id="full_name" name="user[full_name]" value="<?php echo esc_attr( $dashboard->user_info( 'display_name' ) ); ?>" placeholder="<?php esc_html_e( 'Enter your full name', 'directorist' ); ?>">
							</div>
						</div>

						<div class="<?php Helper::directorist_column(6); ?>">
							<div class="form-group">
								<label for="user_name"><?php esc_html_e('User Name', 'directorist'); ?></label>
								<input class="form-control" id="user_name" type="text" disabled="disabled" name="user[user_name]" value="<?php echo esc_attr( $dashboard->user_info( 'username' ) ); ?>"> <?php esc_html_e( '(username can not be changed)', 'directorist' ); ?>
							</div>
						</div>

					</div>

					<div class="row_fl_name <?php Helper::directorist_row(); ?>">

						<div class="<?php Helper::directorist_column(6); ?>">
							<div class="form-group">
								<label for="first_name"><?php esc_html_e( 'First Name', 'directorist' ); ?></label>
								<input class="form-control" id="first_name" type="text" name="user[first_name]" value="<?php echo esc_attr( $dashboard->user_info( 'first_name' ) ); ?>">
							</div>
						</div>

						<div class="<?php Helper::directorist_column(6); ?>">
							<div class="form-group">
								<label for="last_name"><?php esc_html_e( 'Last Name', 'directorist' ); ?></label>
								<input class="form-control" id="last_name" type="text" name="user[last_name]" value="<?php echo esc_attr( $dashboard->user_info( 'last_name' ) ); ?>">
							</div>
						</div>

					</div>

					<div class="row_email_cell <?php Helper::directorist_row(); ?>">

						<div class="<?php Helper::directorist_column(6); ?>">
							<div class="form-group">
								<label for="req_email"><?php esc_html_e( 'Email (required)', 'directorist' ); ?></label>
								<input class="form-control" id="req_email" type="text" name="user[user_email]" value="<?php echo esc_attr( $dashboard->user_info( 'email' ) ); ?>" required>
							</div>
						</div>

						<div class="<?php Helper::directorist_column(6); ?>">
							<div class="form-group">
								<label for="phone"><?php esc_html_e( 'Phone', 'directorist' ); ?></label>
								<input class="form-control" type="tel" id="phone" name="user[phone]" value="<?php echo esc_attr( $dashboard->user_info( 'phone' ) ); ?>" placeholder="<?php esc_attr_e( 'Enter your phone number', 'directorist' ); ?>">
							</div>
						</div>

					</div>

					<div class="row_site_addr <?php Helper::directorist_row(); ?>">

						<div class="<?php Helper::directorist_column(6); ?>">
							<div class="form-group">
								<label for="website"><?php esc_html_e( 'Website', 'directorist' ); ?></label>
								<input class="form-control" id="website" type="text" name="user[website]" value="<?php echo esc_attr( $dashboard->user_info( 'website' ) ); ?>">
							</div>
						</div>

						<div class="<?php Helper::directorist_column(6); ?>">
							<div class="form-group">
								<label for="address"><?php esc_html_e( 'Address', 'directorist' ); ?></label>
								<input class="form-control" id="address" type="text" name="user[address]" value="<?php echo esc_attr( $dashboard->user_info( 'address' ) ); ?>">
							</div>
						</div>

					</div>

					<div class="row_password <?php Helper::directorist_row(); ?>">

						<div class="<?php Helper::directorist_column(6); ?>">
							<div class="form-group">
								<label for="new_pass"><?php esc_html_e( 'New Password', 'directorist' ); ?></label>
								<input id="new_pass" class="form-control" type="password" name="user[new_pass]" placeholder="<?php esc_attr_e( 'Enter a new password', 'directorist' ); ?>">
							</div>
						</div>

						<div class="<?php Helper::directorist_column(6); ?>">
							<div class="form-group">
								<label for="confirm_pass"><?php esc_html_e( 'Confirm New Password', 'directorist' ); ?></label>
								<input id="confirm_pass" class="form-control" type="password" name="user[confirm_pass]" placeholder="<?php esc_attr_e( 'Confirm your new password', 'directorist' ); ?>">
							</div>
						</div>

					</div>

					<div class="row_socials <?php Helper::directorist_row(); ?>">

						<div class="<?php Helper::directorist_column(6); ?>">
							<div class="form-group">
								<label for="facebook"><?php esc_html_e( 'Facebook', 'directorist' ); ?></label>
								<p><?php esc_html_e( 'Leave it empty to hide', 'directorist' ) ?></p>
								<input id="facebook" class="form-control" type="url" name="user[facebook]" value="<?php echo esc_attr( $dashboard->user_info( 'facebook' ) ); ?>" placeholder="<?php esc_attr_e( 'Enter your facebook url', 'directorist' ); ?>">
							</div>
						</div>

						<div class="<?php Helper::directorist_column(6); ?>">
							<div class="form-group">
								<label for="twitter"><?php esc_html_e( 'Twitter', 'directorist' ); ?></label>
								<p><?php esc_html_e( 'Leave it empty to hide', 'directorist' ) ?></p>
								<input id="twitter" class="form-control" type="url" name="user[twitter]" value="<?php echo esc_attr( $dashboard->user_info( 'twitter' ) ); ?>" placeholder="<?php esc_attr_e( 'Enter your twitter url', 'directorist' ); ?>">
							</div>
						</div>

						<div class="<?php Helper::directorist_column(6); ?>">
							<div class="form-group">
								<label for="linkedIn"><?php esc_html_e( 'LinkedIn', 'directorist' ); ?></label>
								<p><?php esc_html_e( 'Leave it empty to hide', 'directorist' ) ?></p>
								<input id="linkedIn" class="form-control" type="url" name="user[linkedIn]" value="<?php echo esc_attr( $dashboard->user_info( 'linkedin' ) ); ?>" placeholder="<?php esc_attr_e( 'Enter linkedIn url', 'directorist' ); ?>">
							</div>
						</div>

						<div class="<?php Helper::directorist_column(6); ?>">
							<div class="form-group">
								<label for="youtube"><?php esc_html_e( 'Youtube', 'directorist' ); ?></label>
								<p><?php esc_html_e( 'Leave it empty to hide', 'directorist' ) ?></p>
								<input id="youtube" class="form-control" type="url" name="user[youtube]" value="<?php echo esc_attr( $dashboard->user_info( 'youtube' ) ); ?>" placeholder="<?php esc_attr_e( 'Enter youtube url', 'directorist' ); ?>">
							</div>
						</div>

						<div class="<?php Helper::directorist_column(12); ?>">
							<div class="form-group">
								<label for="bio"><?php esc_html_e( 'About Author', 'directorist' ); ?></label>
								<textarea class="wp-editor-area form-control" style="height: 200px" autocomplete="off" cols="40" name="user[bio]" id="bio"><?php echo esc_html( $dashboard->user_info( 'bio' ) ); ?></textarea>
							</div>
						</div>

					</div>

					<button type="submit" class="btn btn-primary" id="update_user_profile"><?php esc_html_e( 'Save Changes', 'directorist' ); ?></button>

					<div id="pro_notice"></div>

				</div>
			</div>

		</div>
	</div>

</form>