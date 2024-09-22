<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<form action="#" id="user_preferences" method="post">

	<div class="<?php Helper::directorist_row(); ?>">

		<div class="<?php Helper::directorist_column('lg-9'); ?>">

			<div class="directorist-user-profile-edit">

				<div class="directorist-card directorist-user-profile-box">

					<div class="directorist-card__header">

						<h3 class="directorist-card__header__title"><?php esc_html_e( 'Preferences', 'directorist' ); ?></h3>

					</div>

					<div class="directorist-card__body">

						<div class="directorist-user-info-wrap">

							<input type="hidden" name="ID" value="<?php echo esc_attr( get_current_user_id() ); ?>">

							<div class="directorist-user-full-name">

								<div class="directorist-form-group">

									<label for="full_name"><?php esc_html_e( 'Hide contact form in my listings', 'directorist' ); ?></label>

									<input type="checkbox" id="hide_contact_form" name="hide_contact_form" value="1" <?php echo esc_attr( ($dashboard->user_info( 'hide_contact_form' ) == '1' ) ? 'checked' : '' ); ?>>

								</div>

							</div>

							<div class="directorist-user-full-name">

								<div class="directorist-form-group">

									<label for="display_author_email"><?php esc_html_e( 'Display Email on Author Page', 'directorist' ); ?></label>

									<select name="display_author_email" id="display_author_email">
										<option value="public" ><?php esc_html_e('Display to Everyone', 'directorist') ?></option>
										<option value="logged_in" ><?php esc_html_e('Display to Logged in Users Only', 'directorist') ?></option>
										<option value="none_to_display" ><?php esc_html_e('Don’t Display', 'directorist') ?></option>
									</select>

								</div>

							</div>

							<button type="submit" class="directorist-btn directorist-btn-lg directorist-btn-dark directorist-btn-profile-save" id="update_user_preferences"><?php esc_html_e( 'Save Changes', 'directorist' ); ?></button>

							<div id="directorist-preference-notice"></div>

						</div>

					</div>

				</div>

			</div>

		</div>
	</div>

</form>