<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-add-listing-form__action">

	<?php if ( $display_guest_listings && !is_user_logged_in() ): ?>
		<div class="directorist-add-listing-form__publish">
			<div class="directorist-add-listing-form__publish__icon">
				<?php directorist_icon( 'fas fa-paper-plane' ); ?>
			</div>
			<h2 class="directorist-add-listing-form__publish__title">
				You are about to publish
			</h2>
			<p class="directorist-add-listing-form__publish__subtitle">
				Are you sure you want to publish this listing?
			</p>
		</div>

		<div class="directorist-form-group">

			<label for="guest_user_email"><?php echo esc_html( $guest_email_label ); ?>:<span class="directorist-form-required"> *</span></label>

			<input type="text" id="guest_user_email" name="guest_user_email" class="directorist-form-element directory_field" placeholder="<?php echo esc_attr( $guest_email_placeholder ); ?>"/>
		</div>

	<?php endif;
	/**
	 * @since 7.0
	 */
	do_action( 'atbdp_before_terms_and_conditions_font' )
	?>

	<?php if ( $display_privacy && $display_terms ): ?>

	<div class="directorist-form-privacy directorist-form-terms directorist-checkbox">

		<input id="privacy_policy_terms" type="checkbox" name="privacy_policy_terms" <?php checked( $privacy_checked ); ?> >

		<label for="privacy_policy_terms" class="directorist-checkbox__label">I agree to the <a href="#">Privacy & Policy</a> and <a href="#">Terms & Condition</a></label>

	</div>

	<?php endif; ?>

	<div id="listing_notifier"></div>

	<?php do_action( 'directorist_after_submit_listing_frontend' ); ?>

</div>