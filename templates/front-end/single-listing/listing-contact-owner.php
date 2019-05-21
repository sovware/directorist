<?php
global $post;
$listing_id = $post->ID;
$fm_plan = get_post_meta($listing_id, '_fm_plans', true);
$plan_permission = true;
$listing_info['hide_contact_owner'] = get_post_meta($post->ID, '_hide_contact_owner', true);
$disable_contact_owner = get_directorist_option('disable_contact_owner', 1);
$contact_listing_owner = get_directorist_option('contact_listing_owner', __('Contact Listing Owner', ATBDP_TEXTDOMAIN));
$main_col_size          = is_active_sidebar('right-sidebar-listing') ? 'col-lg-8' : 'col-lg-12';
extract($listing_info);
if (is_fee_manager_active()) {
    $plan_permission = is_plan_allowed_owner_contact_widget($fm_plan);
}
if ($plan_permission && !$hide_contact_owner && empty($disable_contact_owner)) { ?>

                <div class="atbd_content_module atbd_contact_information_module">
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4>
                                <span class="fa fa-paper-plane"></span><?php _e($contact_listing_owner, ATBDP_TEXTDOMAIN); ?>
                            </h4>
                        </div>
                    </div>
                    <form id="atbdp-contact-form" class="form-vertical contact_listing_owner" role="form">
                        <div class="form-group">
                            <input type="text" class="form-control" id="atbdp-contact-name"
                                   placeholder="<?php _e('Name', ATBDP_TEXTDOMAIN); ?>" required/>
                        </div>

                        <div class="form-group">
                            <input type="email" class="form-control" id="atbdp-contact-email"
                                   placeholder="<?php _e('Email', ATBDP_TEXTDOMAIN); ?>" required/>
                        </div>

                        <div class="form-group">
                                        <textarea class="form-control" id="atbdp-contact-message" rows="3"
                                                  placeholder="<?php _e('Message', ATBDP_TEXTDOMAIN); ?>..." required></textarea>
                        </div>
                        <?php
                        /**
                         * It fires before contact form in the widget area
                         * @since 4.4.0
                         */

                        do_action('atbdp_before_submit_contact_form_inWidget');
                        ?>
                        <p id="atbdp-contact-message-display" style="margin-bottom: 10px"></p>

                        <button type="submit" class="btn btn-primary"><?php _e('Submit', ATBDP_TEXTDOMAIN); ?></button>
                    </form>
                </div>

<?php } ?>