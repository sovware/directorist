<?php
global $post;
$listing_id = $post->ID;
$fm_plan = get_post_meta($listing_id, '_fm_plans', true);
/*store all data in an array so that we can pass it to filters for extension to get this value*/
$listing_info['address'] = get_post_meta($post->ID, '_address', true);
$listing_info['phone'] = get_post_meta($post->ID, '_phone', true);
$listing_info['phone2'] = get_post_meta($post->ID, '_phone2', true);
$listing_info['fax'] = get_post_meta($post->ID, '_fax', true);
$listing_info['email'] = get_post_meta($post->ID, '_email', true);
$listing_info['website'] = get_post_meta($post->ID, '_website', true);
$listing_info['zip'] = get_post_meta($post->ID, '_zip', true);
$listing_info['social'] = get_post_meta($post->ID, '_social', true);
$listing_info['hide_contact_info'] = get_post_meta($post->ID, '_hide_contact_info', true);
extract($listing_info);
/*Prepare Listing Image links*/
$listing_imgs = (!empty($listing_img) && !empty($display_slider_image)) ? $listing_img : array();


/*END INFO WINDOW CONTENT*/
$contact_info_text = get_directorist_option('contact_info_text', __('Contact Information', 'directorist'));
$display_address_field = get_directorist_option('display_address_field', 1);
$address_label = get_directorist_option('address_label', __('Address', 'directorist'));
$display_phone_field = get_directorist_option('display_phone_field', 1);
$phone_label = get_directorist_option('phone_label', __('Phone', 'directorist'));
$display_phone2_field = get_directorist_option('display_phone_field2', 1);
$phone_label2 = get_directorist_option('phone_label2', __('Phone Number 2', 'directorist'));
$display_fax_field = get_directorist_option('display_fax', 1);
$fax_label = get_directorist_option('fax_label', __('Fax', 'directorist'));
$display_email_field = get_directorist_option('display_email_field', 1);
$email_label = get_directorist_option('email_label', __('Email', 'directorist'));
$display_website_field = get_directorist_option('display_website_field', 1);
$website_label = get_directorist_option('website_label', __('Website', 'directorist'));
$display_zip_field = get_directorist_option('display_zip_field', 1);
$zip_label = get_directorist_option('zip_label', __('Zip/Post Code', 'directorist'));
$display_social_info_field = get_directorist_option('display_social_info_field', 1);
$display_social_info_for = get_directorist_option('display_social_info_for', 'admin_users');
$is_info = get_directorist_option('disable_contact_info', 0);
$use_nofollow = get_directorist_option('use_nofollow');
$address_map_link = get_directorist_option('address_map_link', 0);
$disable_contact_info = apply_filters('atbdp_single_listing_contact_info', $is_info);
// make main column size 12 when sidebar or submit widget is active @todo; later make the listing submit widget as real widget instead of hard code
$main_col_size = is_active_sidebar('right-sidebar-listing') ? 'col-lg-8' : 'col-lg-12';
if ((!$hide_contact_info) && !empty($address || $phone ||$phone2 ||$fax || $email || $website || $zip || $social) && empty($disable_contact_info)) { ?>
    <div class="atbd_content_module atbd_contact_information_module">
        <div class="atbd_content_module__tittle_area">
            <div class="atbd_area_title">
                <h4>
                    <span class="<?php atbdp_icon_type(true);?>-envelope-o"></span><?php _e($contact_info_text, 'directorist'); ?>
                </h4>
            </div>
        </div>
        <div class="atbdb_content_module_contents">
            <div class="atbd_contact_info">
                <ul>
                    <?php
                    $address_text = !empty($address_map_link)?'<a target="google_map" href="https://www.google.de/maps/search/�'.esc_html($address).'">'.esc_html($address).'</a>': esc_html($address);
                    if (!empty($address) && !empty($display_address_field)) { ?>
                        <li>
                            <div class="atbd_info_title"><span
                                        class="<?php atbdp_icon_type(true);?>-map-marker"></span><?php _e($address_label, 'directorist'); ?>
                            </div>
                            <div class="atbd_info"><?php echo $address_text; ?></div>
                        </li>
                    <?php } ?>
                    <?php
                    $plan_phone = true;
                    if (is_fee_manager_active()) {
                        $plan_phone = is_plan_allowed_listing_phone($fm_plan);
                    }
                    if (isset($phone) && !is_empty_v($phone) && !empty($display_phone_field) && $plan_phone) { ?>
                        <!-- In Future, We will have to use a loop to print more than 1 number-->
                        <li>
                            <div class="atbd_info_title"><span
                                        class="<?php atbdp_icon_type(true);?>-phone"></span><?php _e($phone_label, 'directorist'); ?>
                            </div>
                            <div class="atbd_info"><a
                                        href="tel:<?php echo esc_html(stripslashes($phone)); ?>"><?php echo esc_html(stripslashes($phone)); ?></a>
                            </div>
                        </li>
                    <?php } ?>
                    <?php
                    if (isset($phone2) && !is_empty_v($phone2) && !empty($display_phone2_field)) { ?>
                        <!-- In Future, We will have to use a loop to print more than 1 number-->
                        <li>
                            <div class="atbd_info_title"><span
                                        class="<?php atbdp_icon_type(true);?>-phone"></span><?php echo $phone_label2; ?>
                            </div>
                            <div class="atbd_info"><a href="tel:<?php echo esc_html(stripslashes($phone2)); ?>"><?php echo esc_html(stripslashes($phone2)); ?></a>
                            </div>
                        </li>
                    <?php } ?>
                    <?php
                    if (isset($fax) && !is_empty_v($fax) && !empty($display_fax_field)) { ?>
                        <!-- In Future, We will have to use a loop to print more than 1 number-->
                        <li>
                            <div class="atbd_info_title"><span
                                        class="<?php atbdp_icon_type(true);?>-fax"></span><?php echo $fax_label; ?>
                            </div>
                            <div class="atbd_info"><a href="tel:<?php echo esc_html(stripslashes($fax)); ?>"><?php echo esc_html(stripslashes($fax)); ?></a>
                            </div>
                        </li>
                    <?php } ?>
                    <?php
                    $plan_email = true;
                    if (is_fee_manager_active()) {
                        $plan_email = is_plan_allowed_listing_email($fm_plan);
                    }
                    if (!empty($email) && !empty($display_email_field) && $plan_email) { ?>
                        <li>
                            <div class="atbd_info_title"><span
                                        class="<?php atbdp_icon_type(true);?>-envelope"></span><?php _e($email_label, 'directorist'); ?>
                            </div>
                            <span class="atbd_info"><a target="_top"
                                                       href="mailto:<?php echo esc_html($email); ?>"><?php echo esc_html($email); ?></a></span>
                        </li>
                    <?php } ?>
                    <?php
                    $plan_webLink = true;
                    if (is_fee_manager_active()) {
                        $plan_webLink = is_plan_allowed_listing_webLink($fm_plan);
                    }
                    if (!empty($website) && !empty($display_website_field) && $plan_webLink) { ?>
                        <li>
                            <div class="atbd_info_title"><span
                                        class="<?php atbdp_icon_type(true);?>-globe"></span><?php _e($website_label, 'directorist'); ?>
                            </div>
                            <a target="_blank" href="<?php echo esc_url($website); ?>"
                               class="atbd_info" <?php echo !empty($use_nofollow) ? 'rel="nofollow"' : ''; ?>><?php echo esc_html($website); ?></a>
                        </li>
                    <?php } ?>
                    <?php
                    if (isset($zip) && !is_empty_v($zip) && !empty($display_zip_field)) { ?>
                        <!-- In Future, We will have to use a loop to print more than 1 number-->
                        <li>
                            <div class="atbd_info_title"><span
                                        class="<?php atbdp_icon_type(true);?>-at"></span><?php _e($zip_label, 'directorist'); ?>
                            </div>
                            <div class="atbd_info"><?php echo esc_html($zip); ?></div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <?php
            $plan_social_networks = true;
            if (is_fee_manager_active()) {
                $plan_social_networks = is_plan_allowed_listing_social_networks($fm_plan);
            }
            if (!empty($social) && is_array($social) && !empty($display_social_info_field) && $plan_social_networks) { ?>
                <div class="atbd_director_social_wrap">
                    <?php foreach ($social as $link) {
                        $link_id = $link['id'];
                        $link_url = $link['url'];

                        $n = esc_attr($link_id);
                        $l = esc_url($link_url);
                        ?>
                        <a target='_blank' href="<?php echo $l; ?>" class="<?php echo $n; ?>">
                            <span class="fa fa-<?php echo $n; ?>"></span>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div><!-- end .atbd_custom_fields_contents -->
<?php }