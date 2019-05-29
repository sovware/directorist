<?php
global $post;
$listing_id = $post->ID;
$fm_plan = get_post_meta($listing_id, '_fm_plans', true);
/*store all data in an array so that we can pass it to filters for extension to get this value*/
$listing_info['address'] = get_post_meta($post->ID, '_address', true);
$listing_info['phone'] = get_post_meta($post->ID, '_phone', true);
$listing_info['email'] = get_post_meta($post->ID, '_email', true);
$listing_info['website'] = get_post_meta($post->ID, '_website', true);
$listing_info['zip'] = get_post_meta($post->ID, '_zip', true);
$listing_info['social'] = get_post_meta($post->ID, '_social', true);
$listing_info['hide_contact_info'] = get_post_meta($post->ID, '_hide_contact_info', true);
extract($listing_info);
/*Prepare Listing Image links*/
$listing_imgs = (!empty($listing_img) && !empty($display_slider_image)) ? $listing_img : array();
$image_links = array(); // define a link placeholder variable
foreach ($listing_imgs as $id) {

    if (!empty($gallery_cropping)) {
        $image_links[$id] = atbdp_image_cropping($id, $custom_gl_width, $custom_gl_height, true, 100)['url'];
    } else {
        $image_links[$id] = wp_get_attachment_image_src($id, 'large')[0];
    }

    $image_links_thumbnails[$id] = wp_get_attachment_image_src($id, 'thumbnail')[0]; // store the attachment id and url

    //@todo; instead of getting a full size image, define a an image size and then fetch that size and let the user change that image size via a hook.
}

/*END INFO WINDOW CONTENT*/

$contact_info_text = get_directorist_option('contact_info_text', __('Contact Information', ATBDP_TEXTDOMAIN));
$display_address_field = get_directorist_option('display_address_field', 1);
$display_phone_field = get_directorist_option('display_phone_field', 1);
$display_email_field = get_directorist_option('display_email_field', 1);
$display_website_field = get_directorist_option('display_website_field', 1);
$display_zip_field = get_directorist_option('display_zip_field', 1);
$display_social_info_field = get_directorist_option('display_social_info_field', 1);
$display_social_info_for = get_directorist_option('display_social_info_for', 'admin_users');
$disable_contact_info = get_directorist_option('disable_contact_info', 0);
// make main column size 12 when sidebar or submit widget is active @todo; later make the listing submit widget as real widget instead of hard code
$main_col_size = is_active_sidebar('right-sidebar-listing') ? 'col-lg-8' : 'col-lg-12';
if ((!$hide_contact_info) && !empty($address || $phone || $email || $website || $zip || $social) && empty($disable_contact_info)) { ?>

    <div class="atbd_content_module atbd_contact_information_module">
        <div class="atbd_content_module__tittle_area">
            <div class="atbd_area_title">
                <h4>
                    <span class="fa fa-envelope-o"></span><?php _e($contact_info_text, ATBDP_TEXTDOMAIN); ?>
                </h4>
            </div>
        </div>

        <div class="atbdb_content_module_contents">
            <div class="atbd_contact_info">
                <ul>
                    <?php if (!empty($address) && !empty($display_address_field)) { ?>
                        <li>
                            <div class="atbd_info_title"><span
                                        class="fa fa-map-marker"></span><?php _e('Address', ATBDP_TEXTDOMAIN); ?>
                            </div>
                            <div class="atbd_info"><?= esc_html($address); ?></div>
                        </li>
                    <?php } ?>

                    <?php
                    if (isset($phone) && !is_empty_v($phone) && !empty($display_phone_field)) { ?>
                        <!-- In Future, We will have to use a loop to print more than 1 number-->
                        <li>
                            <div class="atbd_info_title"><span
                                        class="fa fa-phone"></span><?php _e('Phone', ATBDP_TEXTDOMAIN); ?>
                            </div>
                            <div class="atbd_info"><?= esc_html($phone); ?></div>
                        </li>
                    <?php } ?>

                    <?php if (!empty($email) && !empty($display_email_field)) { ?>
                        <li>
                            <div class="atbd_info_title"><span
                                        class="fa fa-envelope"></span><?php _e('Email', ATBDP_TEXTDOMAIN); ?>
                            </div>
                            <span class="atbd_info"><a target="_top"
                                                       href="mailto:<?= esc_html($email); ?>"><?= esc_html($email); ?></a></span>
                        </li>
                    <?php } ?>

                    <?php if (!empty($website) && !empty($display_website_field)) { ?>
                        <li>
                            <div class="atbd_info_title"><span
                                        class="fa fa-globe"></span><?php _e('Website', ATBDP_TEXTDOMAIN); ?>
                            </div>
                            <a target="_blank" href="<?= esc_url($website); ?>"
                               class="atbd_info" <?php echo !empty($use_nofollow) ? 'rel="nofollow"' : ''; ?>><?= esc_html($website); ?></a>
                        </li>
                    <?php } ?>
                    <?php
                    if (isset($zip) && !is_empty_v($zip) && !empty($display_zip_field)) { ?>
                        <!-- In Future, We will have to use a loop to print more than 1 number-->
                        <li>
                            <div class="atbd_info_title"><span
                                        class="fa fa-address-card"></span><?php _e('Zip/Post Code', ATBDP_TEXTDOMAIN); ?>
                            </div>
                            <div class="atbd_info"><?= esc_html($zip); ?></div>
                        </li>
                    <?php } ?>

                </ul>
            </div>
            <?php if (!empty($social) && is_array($social) && !empty($display_social_info_field)) { ?>
                <div class="atbd_director_social_wrap">
                    <?php foreach ($social as $link) {
                        $n = esc_attr($link['id']);
                        $l = esc_url($link['url']);
                        ?>
                        <a target='_blank' href="<?php echo $l; ?>"><span
                                    class="fa fa-<?php echo $n; ?>"></span></a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div><!-- end .atbd_custom_fields_contents -->

<?php } ?>
