<?php
/**
 * @author  wpWax
 * @since   7.3.0
 * @version 7.11.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
$form_id = apply_filters('atbdp_contact_listing_owner_widget_form_id', 'atbdp-contact-form-widget');
?>

<div class="atbdp directorist atbdp-widget-listing-contact">
  <form id="<?php echo esc_attr( $form_id ); ?>" class="form-vertical directorist-contact-owner-form">
    <div class="form-group">
      <input type="text" class="form-control" name="atbdp-contact-name" placeholder="<?php esc_attr_e('Name', 'directorist'); ?>" required />
    </div>

    <div class="form-group">
      <input type="email" class="form-control" name="atbdp-contact-email" placeholder="<?php esc_attr_e('Email', 'directorist'); ?>" required />
    </div>

	<div class="form-group">
		<textarea class="form-control" name="atbdp-contact-message" rows="3" placeholder="<?php esc_attr_e('Message...', 'directorist'); ?>" required ></textarea>
	</div>

    <input type="hidden" name="atbdp-post-id" value="<?php echo esc_attr( get_the_ID() ); ?>" />
    <input type="hidden" name="atbdp-listing-email" value="<?php echo ! empty( $email ) ? esc_attr( $email ) : ''; ?>" />
    
    <?php 
    // Deprecated hook
    do_action_deprecated( 'atbdp_before_contact_form_submit_button', array(), '7.11.0', 'directorist_before_contact_form_submit_button' );
    // New hook
    do_action( 'directorist_before_contact_form_submit_button' );
    ?>
    
    <p class="atbdp-widget-elm directorist-contact-message-display"></p>

    <button type="submit" class="btn btn-primary"><?php esc_html_e('Submit', 'directorist'); ?></button>
  </form>
</div>

