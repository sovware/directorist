<?php
echo $args['before_widget'];
echo '<div class="atbd_widget_title">';
echo $args['before_title'] . esc_html(apply_filters('widget_submit_item_title', $title)) . $args['after_title'];
echo '</div>';
?>
<div class="atbdp atbd_author_info_widget">

    <div class="atbd_avatar_wrapper">
        <?php
        $listing_id = get_the_ID();
        $author_id = get_post_field('post_author', $listing_id);
        $author_name = get_the_author_meta('display_name', $author_id);
        $user_registered = get_the_author_meta('user_registered', $author_id);
        $u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
        $u_pro_pic = !empty($u_pro_pic) ? wp_get_attachment_image_src($u_pro_pic, 'thumbnail') : '';
        $avatar_img = get_avatar($author_id, apply_filters('atbdp_avatar_size', 32));
        ?>
        <div class="atbd_review_avatar"><?php if (empty($u_pro_pic)) {
                echo $avatar_img;
            }
            if (!empty($u_pro_pic)) { ?><img
                src="<?php echo esc_url($u_pro_pic[0]); ?>"
                alt="Avatar Image"><?php } ?>
        </div>
        <div class="atbd_name_time">
            <h4><?php echo esc_html($author_name); ?></h4>
            <span class="review_time"><?php
                printf(__('Member since %s ago', 'directorist'), human_time_diff(strtotime($user_registered), current_time('timestamp'))); ?></span>
        </div>
    </div>

    <div class="atbd_widget_contact_info">
        <ul>
            <?php
            $address = esc_attr(get_user_meta($author_id, 'address', true));
            $phone = esc_attr(get_user_meta($author_id, 'atbdp_phone', true));
            $email = get_the_author_meta('user_email', $author_id);
            $website = get_the_author_meta('user_url', $author_id);;
            $facebook = get_user_meta($author_id, 'atbdp_facebook', true);
            $twitter = get_user_meta($author_id, 'atbdp_twitter', true);
            $linkedIn = get_user_meta($author_id, 'atbdp_linkedin', true);
            $youtube = get_user_meta($author_id, 'atbdp_youtube', true);
            if (!empty($address)) { ?>
                <li>
                    <span class="<?php atbdp_icon_type(true);?>-map-marker"></span>
                    <span class="atbd_info"><?php echo !empty($address) ? esc_html($address) : ''; ?></span>
                </li>
            <?php } ?>

            <?php
            if (isset($phone) && !is_empty_v($phone)) { ?>
                <!-- In Future, We will have to use a loop to print more than 1 number-->
                <li>
                    <span class="<?php atbdp_icon_type(true);?>-phone"></span>
                    <span class="atbd_info"><a href="tel:<?php echo esc_html(stripslashes($phone)); ?>"><?php echo esc_html(stripslashes($phone)); ?></a></span>
                </li>
            <?php } ?>

            <?php

            $email_show = get_directorist_option('display_author_email', 'public');
            if ('public' === $email_show) {
                if (!empty($email)) {
                    ?>
                    <li>
                        <span class="<?php atbdp_icon_type(true);?>-envelope"></span>
                        <span class="atbd_info"><?php echo esc_html($email); ?></span>
                    </li>
                    <?php
                }
            } elseif ('logged_in' === $email_show) {
                if (atbdp_logged_in_user()) {
                    if (!empty($email)) {
                        ?>
                        <li>
                            <span class="<?php atbdp_icon_type(true);?>-envelope"></span>
                            <span class="atbd_info"><?php echo esc_html($email); ?></span>
                        </li>
                        <?php
                    }
                }
            }
            if (!empty($website)) { ?>
                <li>
                    <span class="<?php atbdp_icon_type(true);?>-globe"></span>
                    <a href="<?php echo esc_url($website); ?>"
                        class="atbd_info" <?php echo is_directoria_active() ? 'style="text-transform: none;"' : ''; ?>><?php echo esc_url($website); ?></a>
                </li>
            <?php } ?>

        </ul>
    </div>
    <?php if (!empty($facebook || $twitter || $linkedIn || $youtube )) { ?>
        <div class="atbd_social_wrap">
            <?php
            if ($facebook) {
                printf('<p><a target="_blank" href="%s"><span class="'.atbdp_icon_type().'-facebook"></span></a></p>', $facebook);
            }
            if ($twitter) {
                printf('<p><a target="_blank" href="%s"><span class="'.atbdp_icon_type().'-twitter"></span></a></p>', $twitter);
            }
            if ($linkedIn) {
                printf('<p><a target="_blank" href="%s"><span class="'.atbdp_icon_type().'-linkedin"></span></a></p>', $linkedIn);
            }
            if ($youtube) {
                printf('<p><a target="_blank" href="%s"><span class="'.atbdp_icon_type().'-youtube"></span></a></p>', $youtube);
            }
            ?>
        </div>
    <?php } ?>
    <a href="<?php echo ATBDP_Permalink::get_user_profile_page_link($author_id); ?>"
        class="<?php echo atbdp_directorist_button_classes(); ?>"><?php _e('View Profile', 'directorist'); ?>
    </a>
</div>

<?php echo $args['after_widget']; ?> 
