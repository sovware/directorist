<?php
if ($plan_permission && !$hide_contact_owner && empty($disable_contact_owner)) { ?>
    <div class="atbd_content_module atbd_contact_information_module">
        <div class="atbd_content_module_title_area">
            <div class="atbd_area_title">
                <h4>
                    <span class="fa fa-paper-plane"></span><?php _e($contact_listing_owner, 'directorist'); ?>
                </h4>
            </div>
        </div>

        <form action="atbdp_public_send_contact_email" 
            class="atbdp-form form-vertical contact_listing_owner"
            data-form-id="atbdp_stcode_contact_email">
            <div class="form-group">
                <input 
                    type="text" 
                    class="form-control atbdp-form-field"
                    name="name"
                    placeholder="<?php _e('Name', 'directorist'); ?>" required
                />
            </div>

            <div class="form-group">
                <input 
                    type="email" 
                    class="form-control atbdp-form-field"
                    name="email"
                    placeholder="<?php _e('Email', 'directorist'); ?>" 
                    required
                />
            </div>

            <div class="form-group">
                <textarea 
                    class="form-control atbdp-form-field" 
                    name="message"
                    rows="3"
                    placeholder="<?php _e('Message', 'directorist'); ?>..." required></textarea>
            </div>

            <input type="hidden" name="post_id" class="atbdp-form-field" value="<?php echo $post->ID; ?>" />
            <input type="hidden" name="listing_email" class="atbdp-form-field" value="<?php echo !empty($email) ? sanitize_email($email) : ''; ?>" />
            <?php
            /**
             * It fires before contact form in the widget area
             * @since 4.4.0
             */

            do_action('atbdp_before_contact_form_submit_button');
            ?>
            <div class="atbdp-form-alert" style="margin-bottom: 10px"></div>

            <button type="submit" class="btn btn-primary"><?php _e('Submit', 'directorist'); ?></button>
        </form>
    </div>
<?php } ?>