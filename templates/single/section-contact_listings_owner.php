<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 8.4.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if( $listing->contact_owner_form_disabled() ) {
	return;
}

$fields = $listing->contact_owner_fields( $section_data['fields'] );
?>

<section class="directorist-card directorist-card-contact-owner <?php echo esc_attr( $class );?>" <?php $listing->section_id( $id ); ?>>

	<header class="directorist-card__header">

		<h3 class="directorist-card__header__title">
			<?php if ( ! empty( $icon ) ) : ?>
				<span class="directorist-card__header-icon"><?php directorist_icon( $icon );?></span>
			<?php endif; ?>
			<span class="directorist-card__header-text"><?php echo esc_html( $label );?></span>
		</h3>

	</header>

	<div class="directorist-card__body">

		<form action="atbdp_public_send_contact_email" class="directorist-contact-owner-form" data-form-id="atbdp_stcode_contact_email">

			<div class="directorist-contact-owner-form-inner">

				<?php if( ! empty( $fields['name']['enable'] ) ) : ?>
					<div class="directorist-form-group">
						<input type="text" class="directorist-form-element" name="atbdp-contact-name" placeholder="<?php echo esc_attr( $fields['name']['placeholder'] ); ?>" required />
					</div>
				<?php endif; ?>

				<div class="directorist-form-group">
					<input type="email" class="directorist-form-element" name="atbdp-contact-email" placeholder="<?php echo esc_attr( $fields['email']['placeholder'] ); ?>" required />
				</div>

				<div class="directorist-form-group">
					<textarea class="directorist-form-element" name="atbdp-contact-message" rows="3" placeholder="<?php echo esc_attr( $fields['message']['placeholder'] ); ?>" required></textarea>
				</div>

				<input type="hidden" name="atbdp-post-id" value="<?php echo esc_attr( $listing->id ); ?>" />
				<input type="hidden" name="atbdp-listing-email" value="<?php echo esc_attr( $listing->contact_owner_email() ); ?>" />

				<?php do_action( 'directorist_before_contact_form_submit_button' ); ?>

				<p class="directorist-contact-message-display"></p>

				<button type="submit" class="directorist-btn directorist-btn-light directorist-btn-md directorist-btn-submit <?php echo esc_attr( ! empty( $_GET['preview'] ) ? 'directorist-btn-disabled' : '' ) ?>"><?php esc_html_e( 'Submit now', 'directorist' ); ?></button>

			</div>

		</form>

	</div>

</section>