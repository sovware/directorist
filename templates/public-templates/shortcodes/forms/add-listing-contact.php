<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */

$listing_info = get_defined_vars();

if ((empty($display_fax_for) || empty($display_phone2_for) || empty($display_phone_for) || empty($display_address_for) || empty($display_email_for) || empty($display_website_for) || empty($display_zip_for) || empty($display_social_info_for)) && (!empty($display_address_field) || !empty($display_phone_field) || !empty($display_phone2_field) || !empty($display_fax_field) || !empty($display_email_field) || !empty($display_website_field) || !empty($display_zip_field) || !empty($display_social_info_field))) { ?>
    <div class="atbd_content_module atbd_contact_information">
        <div class="atbd_content_module_title_area">
            <div class="atbd_area_title">
                <h4><?php esc_html_e('Contact Information', 'directorist') ?></h4>
            </div>
        </div>

        <div class="atbdb_content_module_contents">
            <?php if (!empty($display_contact_hide)) { ?>
                <div class="form-check">
                    <input type="checkbox" name="hide_contact_info" class="form-check-input" id="hide_contact_info" value="1" <?php if (!empty($hide_contact_info)) { checked($hide_contact_info);} ?> >
                    <label class="form-check-label" for="hide_contact_info"><?php echo !empty($contact_hide_label) ? $contact_hide_label : __('Check it to hide Contact Information for this listing', 'directorist'); ?></label>
                </div>
            <?php } ?>
            <?php if (!$disable_contact_owner) { ?>
                <div class="form-check">
                    <input type="checkbox" name="hide_contact_owner" class="form-check-input" id="hide_contact_owner" value="1" <?php if (!empty($hide_contact_owner)) {checked($hide_contact_owner);} ?> >
                    <label class="form-check-label" for="hide_contact_owner"><?php esc_html_e('Check it to hide Contact owner', 'directorist'); ?></label>
                </div>
            <?php }

            /**
             * It fires after the google map preview area
             * @param string $type Page type.
             * @param array $listing_info Information of the current listing
             * @since 1.1.1
             **/
            do_action('atbdp_edit_after_googlemap_preview', 'add_listing_page_frontend', $listing_info, $p_id); // @dev
            if (empty($display_address_for) && !empty($display_address_field) && (!empty($display_map_for) || empty($display_map_field))) {
                ?>
                <div class="form-group" id="atbdp_address">
                    <label for="address"><?php esc_html_e($address_label . ':', 'directorist'); echo $require_address ? '<span class="atbdp_make_str_red">*</span>' : ''; ?></label>
                    <input type="text" name="address" autocomplete="off" id="address" value="<?php echo !empty($address) ? esc_attr($address) : ''; ?>" class="form-control directory_field" placeholder="<?php echo esc_attr($address_placeholder); ?>"/>
                </div>
            <?php }

            if (empty($display_zip_for) && !empty($display_zip_field)) {
                ?>
                <div class="form-group" id="atbdp_zip_code">
                    <label for="atbdp_zip"><?php esc_html_e($zip_label . ':', 'directorist'); echo $require_zip ? '<span class="atbdp_make_str_red">*</span>' : ''; ?></label>
                    <input type="text" id="atbdp_zip" name="zip" value="<?php echo !empty($zip) ? esc_attr($zip) : ''; ?>" class="form-control directory_field" placeholder="<?php echo esc_attr($zip_placeholder); ?>"/>
                </div>
            <?php }

            if ($plan_phone && empty($display_phone_for) && !empty($display_phone_field)) {
                ?>
                <div class="form-group" id="atbdp_phone">
                    <label for="atbdp_phone_number"><?php esc_html_e($phone_label . ':', 'directorist'); echo $require_phone_number ? '<span class="atbdp_make_str_red">*</span>' : ''; ?></label>
                    <input type="tel" name="phone" id="atbdp_phone_number" value="<?php echo !empty($phone) ? esc_attr($phone) : ''; ?>" class="form-control directory_field" placeholder="<?php echo esc_attr($phone_placeholder); ?>"/>
                </div>
            <?php }

            if ($plan_phone && empty($display_phone2_for) && !empty($display_phone2_field)) {
                ?>
                <div class="form-group" id="atbdp_phone2">
                    <label for="atbdp_phone_number2"><?php esc_html_e($phone_label2 . ':', 'directorist'); echo $require_phone_number2 ? '<span class="atbdp_make_str_red">*</span>' : ''; ?></label>
                    <input type="tel" name="phone2" id="atbdp_phone_number2" value="<?php echo !empty($phone2) ? esc_attr($phone2) : ''; ?>" class="form-control directory_field" placeholder="<?php echo esc_attr($phone_placeholder2); ?>"/>
                </div>
            <?php }

            if (empty($display_fax_for) && !empty($display_fax_field)) {
                ?>
                <div class="form-group" id="atbdp_fax_number">
                	<label for="atbdp_fax"><?php esc_html_e($fax_label . ':', 'directorist'); echo $require_fax ? '<span class="atbdp_make_str_red">*</span>' : ''; ?></label>
                    <input type="tel" name="fax" id="atbdp_fax" value="<?php echo !empty($fax) ? esc_attr($fax) : ''; ?>" class="form-control directory_field" placeholder="<?php echo esc_attr($fax_placeholder); ?>"/>
                </div>
            <?php }

            if ($plan_email && empty($display_email_for) && !empty($display_email_field)) {
                ?>
                <div class="form-group" id="atbdp_emails">
                    <label for="atbdp_email"><?php esc_html_e($email_label . ':', 'directorist'); echo $require_email ? '<span class="atbdp_make_str_red">*</span>' : ''; ?></label>
                    <input type="email" name="email" id="atbdp_email" value="<?php echo !empty($email) ? esc_attr($email) : ''; ?>" class="form-control directory_field" placeholder="<?php echo esc_attr($email_placeholder); ?>"/>
                </div>
            <?php }

            if ($plan_webLink && empty($display_website_for) && !empty($display_website_field)) {
                ?>
                <div class="form-group" id="atbdp_webs">
                    <label for="atbdp_website"><?php esc_html_e($website_label . ':', 'directorist'); echo $require_website ? '<span class="atbdp_make_str_red">*</span>' : ''; ?></label>
                    <input type="text" id="atbdp_website" name="website" value="<?php echo !empty($website) ? esc_url($website) : ''; ?>" class="form-control directory_field" placeholder="<?php echo esc_attr($website_placeholder); ?>"/>
                </div>
            <?php }
            ?>

            <div class="form-group" id="atbdp_socialInFo">
                <?php
                /**
                 * It fires before social information fields
                 * @param string $type Page type.
                 * @param array $listing_info Information of the current listing
                 * @since 1.1.1
                 **/
                do_action('atbdp_edit_before_social_info_fields', 'add_listing_page_frontend', $listing_info); // @dev
                if ($plan_social_networks && empty($display_social_info_for) && !empty($display_social_info_field)) {
                    ATBDP()->load_template('meta-partials/social', array('social_info' => $social_info));
                }
                /**
                 * It fires after social information fields
                 * @param string $type Page type.
                 * @param array $listing_info Information of the current listing
                 * @since 1.1.1
                 **/
                do_action('atbdp_edit_after_social_info_fields', 'add_listing_page_frontend', $listing_info); // @dev

                ?>
            </div>
        </div>
    </div>

    <?php
    if (is_business_hour_active() && $plan_hours) {
        ?>
        <div class="atbd_content_module atbd_business_hour_module">
            <div class="atbd_content_module_title_area">
                <div class="atbd_area_title">
                    <h4><?php _e('Opening/Business Hour Information', 'directorist'); ?></h4>
                </div>
            </div>

            <div class="atbdb_content_module_contents">
                <?php
                /**
                 * It fires before social information fields
                 * @param string $type Page type.
                 * @param array $listing_info Information of the current listing
                 * @since 1.1.1
                 **/
                apply_filters('atbdp_edit_after_contact_info_fields', 'add_listing_page_frontend', $listing_info); // @dev
                ?>
            </div>
        </div>
        <?php
    }
    /**
     * It fires before map
     * @param string $type Page type.
     * @param array $listing_info Information of the current listing
     * @since 4.0
     **/
    do_action('atbdp_edit_after_business_hour_fields', 'add_listing_page_frontend', $listing_info); // @dev
}

do_action('atbdp_after_contact_info_section', 'add_listing_page_frontend', $listing_info, $p_id);