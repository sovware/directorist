<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="atbdp-quick-login" class="atbdp-modal-container">
	<div class="atbdp-modal-wrap">
		<div class="atbdp-modal">
			<div class="atbdp-modal-header">
				<div class="atbdp-modal-title-area">
					<h3 class="atbdp-modal-title">Quick Login</h3>
				</div>

				<div class="atbdp-modal-actions-area">
					<a href="#" class="atbdp-toggle-modal" data-target="#atbdp-quick-login"><span class="fas fa-times"></span></a>
				</div>
			</div>

			<div class="atbdp-modal-body">
				<div class="atbdp-modal-alerts-area"></div>

				<p class="atbdp-form-label atbdp-email-label">user@email.com</p>

				<div class="atbdp-form-group">
					<input type="password" name="password" placeholder="Password" class="atbdp-form-control">
				</div>

				<button type="button" name="login" class="atbdp-btn atbdp-btn-primary atbdp-btn-block">
					Login
				</button>
			</div>
		</div>
	</div>
</div>