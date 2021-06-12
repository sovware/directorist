<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.8
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="directorist-quick-login" class="directorist-modal-container">
	<div class="directorist-modal-wrap">
		<div class="directorist-modals">
			<form action="#" id="quick-login-from">
				<div class="directorist-modal-header">
					<div class="directorist-modal-title-area">
						<h3 class="directorist-modal-title"><?php esc_html_e( 'Quick Login', 'directorist' ); ?></h3>
					</div>

					<div class="directorist-modal-actions-area">
						<a href="#" class="directorist-toggle-modal" data-target="#directorist-quick-login"><span class="fas fa-times"></span></a>
					</div>
				</div>

				<div class="directorist-modal-body">
					<div class="directorist-modal-alerts-area directorist-text-center"></div>

					<div class="directorist-form-group directorist-mb-15">
						<input type="text" name="email" placeholder="<?php esc_html_e( 'user@email.com', 'directorist' ); ?>" class="directorist-form-element">
					</div>

					<div class="directorist-form-group directorist-mb-15">
						<input type="password" name="password" placeholder="Password" class="directorist-form-element">
					</div>

					<?php wp_nonce_field('directorist-quick-login-nonce', 'directorist-quick-login-security'); ?>
					<div class="directorist-form-feedback"></div>

					<div class="directorist-form-actions directorist-text-center">
						<button type="button" id="quick-login-from-submit-btn" data-form="#quick-login-from" data-modal="directorist-quick-login-modal" name="login" class="directorist-btn directorist-btn-primary directorist-btn-block">
							<?php esc_html_e( 'Login', 'directorist' ); ?>
						</button>

						<button type="button" data-target="#directorist-quick-login-modal" class="directorist-btn directorist-btn-primary directorist-btn-block directorist-toggle-modal directorist-d-none">
							<?php esc_html_e( 'Continue', 'directorist' ); ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>