<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-sl-action directorist-action-report directorist-tooltip" data-target="atbdp-report-abuse-modal" aria-label="<?php esc_html_e( 'Report', 'directorist' ); ?>">

	<?php if ( atbdp_logged_in_user() ): ?>
		<a href="#"><?php directorist_icon( $icon );?></a>
	<?php else: ?>
		<a href="javascript:void(0)" class="directorist-btn-modal directorist-btn-modal-js atbdp-require-login" data-directorist_target="directorist-modal-js"><?php directorist_icon( $icon );?><?php esc_html_e( 'Report', 'directorist' ); ?></a>
	<?php endif; ?>

	<input type="hidden" id="atbdp-post-id" value="<?php echo esc_attr( $listing->id ); ?>"/>

</div>
 
<div class="directorist-modal directorist-modal-js directorist-fade">
	<div class="directorist-modal__dialog">
		<div class="directorist-modal__content">

			

			<form id="atbdp-report-abuse-form" class="form-vertical tst">

				<div class="directorist-modal__header">
					<h3 class="modal-title" id="atbdp-report-abuse-modal-label"><?php esc_html_e('Report Abuse', 'directorist'); ?></h3>
					<a href="" class="directorist-modal-close directorist-modal-close-js"><span aria-hidden="true">&times;</span></a>
				</div>

				<div class="directorist-modal__body">
					<div class="directorist-form-group">
						<label for="directorist-report-message"><?php esc_html_e( 'Your Complain', 'directorist' ); ?><span class="directorist-report-star">*</span></label>
						<textarea class="directorist-form-element" id="directorist-report-message" rows="3" placeholder="<?php esc_attr_e( 'Message...', 'directorist' ); ?>" required></textarea>
					</div>
					<div id="directorist-report-abuse-g-recaptcha"></div>
					<div id="directorist-report-abuse-message-display"></div>
				</div>

				<div class="directorist-modal__footer">
					<button type="submit" class="directorist-btn directorist-btn-primary directorist-btn-sm"><?php esc_html_e( 'Submit', 'directorist' ); ?></button>
				</div>

			</form>

		</div>
	</div>
</div>