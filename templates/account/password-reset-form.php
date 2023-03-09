<?php

if (!defined('ABSPATH')) {
	die();
}

do_action('directorist_before_reset_password_form');
?>
	<form method="post" class="directorist-ResetPassword lost_reset_password">
	<p class="directorist-alert directorist-alert-danger password-not-match" style="display:none">
		<?php esc_html_e('Password did not match', 'directorist')?>
	</p>
	<p>
		<?php esc_html_e('Enter a new password below.', 'directorist'); ?>
	</p>
	<p>
		<label for="password_1">
			<?php esc_html_e('New password', 'directorist'); ?> &nbsp;<span class="required">*</span>
		</label>
		<input type="password" class="directorist-Input directorist-Input--text input-text" name="password_1"
			id="password_1" autocomplete="new-password" required/>
	</p>
	<p>
		<label for="password_2">
			<?php esc_html_e('Re-enter new password', 'directorist'); ?>&nbsp;
			<span class="required">*</span>
		</label>
		<input type="password" class="directorist-Input directorist-Input--text input-text" name="password_2"
			id="password_2" autocomplete="new-password" required/>
	</p>

	<div class="clear"></div>

	<?php do_action('directorist_resetpassword_form'); ?>

	<p class="directorist-form-row form-row">
		<input type="hidden" name="directorist_reset_password" value="true" />
		<button type="submit" class="btn btn-primary" value="<?php esc_attr_e('Save', 'directorist'); ?>"><?php esc_html_e('Save', 'directorist'); ?></button>
	</p>

	<?php wp_nonce_field('reset_password', 'directorist-reset-password-nonce'); ?>
</form>

<?php

do_action('directorist_after_reset_password_form');
