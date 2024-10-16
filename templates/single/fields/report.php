<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<?php if ( is_user_logged_in() ): ?>
	<button class="directorist-single-listing-action directorist-btn directorist-btn-sm directorist-btn-light directorist-action-report directorist-action-report-loggedin directorist-btn-modal directorist-btn-modal-js" href="#" data-directorist_target="directorist-report-abuse-modal" aria-label="Report Open Modal">
		<?php directorist_icon( $icon );?><span class="directorist-single-listing-action__text"><?php esc_html_e( 'Report', 'directorist'); ?></span> 
	</button>
<?php else: ?>
	<button class="directorist-single-listing-action directorist-btn directorist-btn-sm directorist-btn-light directorist-action-report directorist-action-report-not-loggedin directorist-btn-modal directorist-btn-modal-js"><?php directorist_icon( $icon );?> <span class="directorist-single-listing-action__text" aria-label="Report Modal"> <?php esc_html_e( 'Report', 'directorist'); ?></span></button>
<?php endif; ?>

<section class="directorist-modal directorist-modal-js directorist-fade directorist-report-abuse-modal">

	<div class="directorist-modal__dialog">

		<div class="directorist-modal__content">

			<form id="directorist-report-abuse-form">

				<header class="directorist-modal__header">

					<h2 class="directorist-modal-title" id="directorist-report-abuse-modal__label"><?php esc_html_e('Report Abuse', 'directorist'); ?></h2>

					<button class="directorist-modal-close directorist-modal-close-js" aria-label="Report Modal Close">
						<span aria-hidden="true">&times;</span>
					</button>

				</header>

				<div class="directorist-modal__body">

					<div class="directorist-form-group">

						<label for="directorist-report-message"><?php esc_html_e( 'Your Complaint', 'directorist' ); ?><span class="directorist-report-star">*</span></label>

						<textarea class="directorist-form-element" id="directorist-report-message" rows="3" placeholder="<?php esc_attr_e( 'Message...', 'directorist' ); ?>" required></textarea>
						<input type="hidden" name="atbdp-post-id" id="atbdp-post-id" value="<?php echo esc_attr( get_the_id() ); ?>" />
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

</section>