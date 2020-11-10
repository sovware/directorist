<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$hide_contact_owner = get_post_meta($listing->id, '_hide_contact_owner', true);
$hide_contact_owner = false;
$email = get_post_meta($listing->id, '_email', true);

if (!$hide_contact_owner) { ?>
	<form action="atbdp_public_send_contact_email" id="atbdp-contact-form" class="atbdp-form form-vertical contact-listing-owner-form" data-form-id="atbdp_stcode_contact_email">

		<div class="form-group">
			<input type="text" class="form-control atbdp-form-field" id="atbdp-contact-name" name="name" placeholder="<?php esc_attr_e('Name', 'directorist'); ?>" required />
		</div>

		<div class="form-group">
			<input type="email" class="form-control atbdp-form-field" id="atbdp-contact-email" name="email" placeholder="<?php esc_attr_e('Email', 'directorist'); ?>" required />
		</div>

		<div class="form-group">
			<textarea class="form-control atbdp-form-field" id="atbdp-contact-message" name="message" rows="3" placeholder="<?php esc_attr_e('Message', 'directorist'); ?>..." required></textarea>
		</div>

		<input type="hidden" name="post_id" class="atbdp-form-field" value="<?php echo esc_attr($listing->id); ?>" />
		<input type="hidden" name="listing_email" class="atbdp-form-field" value="<?php echo esc_attr($email); ?>" />
		
		<?php
        /**
         * It fires before contact form in the widget area
         * @since 4.4.0
         */

        do_action('atbdp_before_contact_form_submit_button');
        ?>
		<p class="atbdp-contact-message-display"></p> 
        <button type="submit" class="btn btn-primary"><?php esc_html_e('Submit', 'directorist'); ?></button>
    </form>
    <?php
}