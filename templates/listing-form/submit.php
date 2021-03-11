<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-add-listing-form__action">

	<?php if ( $display_guest_listings && !atbdp_logged_in_user() ): ?>

		<div class="directorist-form-group directorist-mb-15">

			<label for="guest_user"><?php echo esc_html( $guest_email_label ); ?>:<span class="directorist-form-required"> *</span></label>

			<input type="text" id="guest_user_email" name="guest_user_email" class="directorist-form-element directory_field" placeholder="<?php echo esc_attr( $guest_email_placeholder ); ?>"/>
		</div>

	<?php endif;
	/**
	 * @since 7.0
	 */
	do_action( 'atbdp_before_terms_and_conditions_font' )
	?>

	<?php if ( $display_privacy ): ?>

		<div class="directorist-form-privacy">

			<input id="privacy_policy" type="checkbox" name="privacy_policy" <?php checked( $privacy_checked ); ?> <?php echo $privacy_is_required ? 'required="required"' : ''; ?>>

			<label for="privacy_policy"><?php echo wp_kses_post( $listing_form->privacy_label() ); ?></label>

			<?php if ( $privacy_is_required ): ?>
				<span class="directorist-form-required"> *</span>
			<?php endif; ?>

		</div>

	<?php endif; ?>

	<?php if ($display_terms): ?>

		<div class="directorist-form-terms">

			<input id="listing_t" type="checkbox" name="t_c_check" <?php checked( $terms_checked ); ?> <?php echo $terms_is_required ? 'required="required"' : ''; ?>>

			<label for="listing_t"><?php echo wp_kses_post( $listing_form->terms_label() ); ?></label>

			<?php if ($terms_is_required): ?>
				<span class="directorist-form-required"> *</span>
			<?php endif; ?>

		</div>

	<?php endif; ?>

	<div id="listing_notifier"></div>

	<div class="directorist-form-submit">
		<button type="submit" class="directorist-btn directorist-btn-primary directorist-btn-lg directorist-form-submit__btn"><?php echo esc_html( $listing_form->submit_label() ); ?></button>
	</div>

	<?php do_action( 'directorist_after_submit_listing_frontend' ); ?>

</div>