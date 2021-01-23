<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */

if ($plan_permission && !$hide_contact_owner && empty($disable_contact_owner)) { ?>
	<div class="atbd_content_module atbd_contact_information_module">

		<div class="atbd_content_module_title_area">
			<div class="atbd_area_title">
				<h4>
					<span class="fa fa-paper-plane"></span><?php echo esc_html($section_title); ?>
				</h4>
			</div>
		</div>

		<form action="atbdp_public_send_contact_email" id="atbdp-contact-form" class="atbdp-form form-vertical contact_listing_owner" data-form-id="atbdp_stcode_contact_email">

			<div class="form-group">
				<input type="text" class="form-control atbdp-form-field" id="atbdp-contact-name" name="name" placeholder="<?php esc_attr_e('Name', 'directorist'); ?>" required />
			</div>

			<div class="form-group">
				<input type="email" class="form-control atbdp-form-field" id="atbdp-contact-email" name="email" placeholder="<?php esc_attr_e('Email', 'directorist'); ?>" required />
			</div>

			<div class="form-group">
				<textarea class="form-control atbdp-form-field" id="atbdp-contact-message" name="message" rows="3" placeholder="<?php esc_attr_e('Message', 'directorist'); ?>..." required></textarea>
			</div>

			<input type="hidden" name="post_id" class="atbdp-form-field" value="<?php echo esc_attr($listing_id); ?>" />
			<input type="hidden" name="listing_email" class="atbdp-form-field" value="<?php echo esc_attr($email); ?>" />
			
			<?php
            /**
             * It fires before contact form in the widget area
             * @since 4.4.0
             */

            // do_action('atbdp_before_contact_form_submit_button');
            ?>
            <!-- <div class="atbdp-form-alert" style="margin-bottom: 10px"></div> -->
			<p class="atbdp-contact-message-display" style="margin-bottom: 10px"></p> 
            <button type="submit" class="btn btn-primary"><?php _e('Submit', 'directorist'); ?></button>
        </form>
	</div>
	
	<?php
	/**
	 * @since 5.0.5
	 */
	do_action('atbdp_after_contact_listing_owner_section', $listing_id);
}