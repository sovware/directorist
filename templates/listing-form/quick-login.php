<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="directorist-quick-login" class="directorist-modal-container">
	<div class="directorist-modal-wrap">
		<div class="directorist-modals">
			<div class="directorist-modal-header">
				<div class="directorist-modal-title-area">
					<h3 class="directorist-modal-title"><?php esc_html_e( 'Quick Login', 'directorist' ); ?></h3>
				</div>

				<div class="directorist-modal-actions-area">
					<a href="#" class="directorist-toggle-modal" data-target="#directorist-quick-login"><span class="fas fa-times"></span></a>
				</div>
			</div>

			<div class="directorist-modal-body">
				<div class="directorist-modal-alerts-area"></div>

				<p class="directorist-form-label directorist-email-label directorist-mb-10"><?php esc_html_e( 'user@email.com', 'directorist' ); ?></p>

				<div class="directorist-form-group directorist-mb-15">
					<input type="password" name="password" placeholder="Password" class="directorist-form-element">
				</div>

				<button type="button" name="login" class="directorist-btn directorist-btn-primary directorist-btn-block"><?php esc_html_e( 'Login', 'directorist' ); ?></button>
			</div>
		</div>
	</div>
</div>