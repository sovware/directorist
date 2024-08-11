<?php
/**
 * @author  wpWax
 * @since   7.3.0
 * @version 7.10.4
 */

if ( ! defined( 'ABSPATH' ) ) exit;
$form_id = apply_filters('atbdp_contact_listing_owner_widget_form_id', 'directorist-contact-owner-form');
?>

<div class="directorist-card__body directorist-widget__listing-contact">
  <form id="<?php echo esc_attr( $form_id ); ?>" class="directorist-contact-owner-form">
    <div class="directorist-form-group">
      <input type="text" class="directorist-form-element" name="atbdp-contact-name" placeholder="<?php esc_attr_e('Name', 'directorist'); ?>" required />
    </div>

    <div class="directorist-form-group">
      <input type="email" class="directorist-form-element" name="atbdp-contact-email" placeholder="<?php esc_attr_e('Email', 'directorist'); ?>" required />
    </div>

	<div class="directorist-form-group">
		<textarea class="directorist-form-element" name="atbdp-contact-message" rows="3" placeholder="<?php esc_attr_e('Message...', 'directorist'); ?>" required ></textarea>
	</div>

    <input type="hidden" name="atbdp-post-id" value="<?php echo esc_attr( get_the_ID() ); ?>" />
    <input type="hidden" name="atbdp-listing-email" value="<?php echo ! empty( $email ) ? esc_attr( $email ) : ''; ?>" />
    
    <?php 
    // Deprecated hook
    do_action_deprecated( 'atbdp_before_contact_form_submit_button', array(), '7.10.4', 'directorist_before_contact_form_submit_button' );
    // New hook
    do_action( 'directorist_before_contact_form_submit_button' );
    ?>
    
    <p class="atbdp-widget-elm directorist-contact-message-display"></p>

    <button type="submit" class="directorist-btn directorist-btn-light directorist-btn-md"><?php esc_html_e('Submit Now', 'directorist'); ?></button>
  </form>
</div>

