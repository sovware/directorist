<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-add-listing-form__action">

	<div class="directorist-add-listing-form__publish">
		<div class="directorist-add-listing-form__publish__icon">
			<?php directorist_icon( 'fas fa-paper-plane' ); ?>
		</div>
		<h2 class="directorist-add-listing-form__publish__title">
			<?php esc_html_e( 'You are about to publish', 'directorist' ); ?>
		</h2>
		<p class="directorist-add-listing-form__publish__subtitle">
			<?php esc_html_e( 'Are you sure you want to publish this listing?', 'directorist' ); ?>
		</p>
	</div>

	<?php if ( $display_guest_listings && !is_user_logged_in() ): ?>
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

	<?php if ( $display_privacy ): ?>

	<div class="directorist-form-privacy directorist-form-terms directorist-checkbox">

		<input id="directorist_submit_privacy_policy" type="checkbox" name="privacy_policy" <?php checked( $privacy_checked ); ?> <?php echo $privacy_is_required ? 'required="required"' : ''; ?>>

		<label for="directorist_submit_privacy_policy" class="directorist-checkbox__label"><?php echo wp_kses_post( $listing_form->privacy_label() ); ?></label>

		<?php if ( $privacy_is_required ): ?>
			<span class="directorist-form-required"> *</span>
		<?php endif; ?>

	</div>

	<?php endif; ?>

	<div id="listing_notifier"></div>

	<?php do_action( 'directorist_after_submit_listing_frontend' ); ?>

</div>