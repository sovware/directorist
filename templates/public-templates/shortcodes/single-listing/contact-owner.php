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
                    <form id="atbdp-contact-form" class="form-vertical contact_listing_owner" role="form">
                        <div class="form-group">
                            <input type="text" class="form-control" id="atbdp-contact-name"
                                   placeholder="<?php _e('Name', 'directorist'); ?>" required/>
                        </div>

                        <div class="form-group">
                            <input type="email" class="form-control" id="atbdp-contact-email"
                                   placeholder="<?php _e('Email', 'directorist'); ?>" required/>
                        </div>

                        <div class="form-group">
                                        <textarea class="form-control" id="atbdp-contact-message" rows="3"
                                                  placeholder="<?php _e('Message', 'directorist'); ?>..." required></textarea>
                        </div>
                        <?php
                        /**
                         * It fires before contact form in the widget area
                         * @since 4.4.0
                         */

                        do_action('atbdp_before_contact_form_submit_button');
                        ?>
                        <p id="atbdp-contact-message-display" style="margin-bottom: 10px"></p>

                        <button type="submit" class="btn btn-primary"><?php _e('Submit', 'directorist'); ?></button>
                    </form>
                </div>
                <input type="hidden" id="atbdp-post-id" value="<?php echo $post->ID; ?>" />
                <input type="hidden" id="atbdp-listing-email" value="<?php echo !empty($email) ? sanitize_email($email) : ''; ?>" />
<?php } ?>