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
	<div class="directorist-form-group directorist-mb-15">
		<label for="password_1">
			<?php esc_html_e('New password', 'directorist'); ?> &nbsp;<span class="required">*</span>
		</label>
		<input type="password" class="directorist-form-element" id="password_1" name="password_1"  autocomplete="new-password" required>
	</div>
	<div class="directorist-form-group directorist-mb-15">
		<label for="password_2">
			<?php esc_html_e('Re-enter new password', 'directorist'); ?> &nbsp;<span class="required">*</span>
		</label>
		<input type="password" class="directorist-form-element" id="password_2" name="password_2"  autocomplete="new-password" required>
	</div>

	<div class="clear"></div>

	<?php do_action('directorist_resetpassword_form'); ?>

	<p class="directorist-form-row form-row">
		<input type="hidden" name="directorist_reset_password" value="true" />
		<button type="submit" class="directorist-btn directorist-btn-block directorist-btn-primary directorist-authentication__form__btn" value="<?php esc_attr_e('Save', 'directorist'); ?>"><?php esc_html_e('Save', 'directorist'); ?></button>
	</p>

	<?php wp_nonce_field('reset_password', 'directorist-reset-password-nonce'); ?>
</form>

<?php

do_action('directorist_after_reset_password_form');
