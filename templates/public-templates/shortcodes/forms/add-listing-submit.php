<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>

<div class="directorist-contact-fields atbdp_info_module">

    <?php
    /*
     * @since 4.1.0
     */
    do_action('atbdp_before_terms_and_conditions_font');

    if ( $guest_listings && !atbdp_logged_in_user() ) {
        ?>
        <div class="atbd_content_module" id="atbdp_front_media_wrap">
            <div class="atbdb_content_module_contents atbdp_video_field">
                <div class="form-group">
                    <label for="guest_user"><?php esc_html_e($guest_email_label . ':', 'directorist'); echo '<span class="atbdp_make_str_red">*</span>'; ?></label>
                    <input type="text" id="guest_user_email" name="guest_user_email" value="<?php echo !empty($guest_user_email) ? esc_url($guest_user_email) : ''; ?>" class="form-control directory_field" placeholder="<?php echo esc_attr($guest_email_placeholder); ?>"/>
                </div>
            </div>
        </div>
        <?php
    }

    if (!empty($listing_privacy)) {
        ?>
        <div class="atbd_privacy_policy_area">
            <?php if ($require_privacy == 1) { ?>
                <span class="atbdp_make_str_red"> *</span>
            	<?php
            }
            ?>
            <input id="privacy_policy" type="checkbox" name="privacy_policy" <?php if (!empty($privacy_policy)) if ('on' == $privacy_policy) {echo 'checked';} ?>>
            <label for="privacy_policy"><?php echo esc_attr($privacy_label); ?><a style="color: red" target="_blank" href="<?php echo esc_url($privacy_page_link) ?>"><?php echo esc_attr($privacy_label_link); ?></a></label>
        </div>
        <?php
    }

    if (!empty($listing_terms_condition)) {
        ?>
        <div class="atbd_term_and_condition_area">
            <?php if ($require_terms_conditions == 1) { ?>
                <span class="atbdp_make_str_red"> *</span>
                <?php
            }
            ?>
            <input id="listing_t" type="checkbox" name="t_c_check" <?php if (!empty($t_c_check)) if ('on' == $t_c_check) {echo 'checked';} ?>>
            <label for="listing_t"><?php echo esc_attr($terms_label); ?>
                <a style="color: red" target="_blank" href="<?php echo esc_url($t_C_page_link) ?>"><?php echo esc_attr($terms_label_link); ?></a>
            </label>
        </div>
        <?php
    }

    /**
     * It fires before rendering submit listing button on the front end.
     */
    do_action('atbdp_before_submit_listing_frontend', $p_id);
    ?>

    <div id="listing_notifier"></div>
    
    <div class="btn_wrap list_submit">
        <button type="submit" class="btn btn-primary btn-lg listing_submit_btn"><?php echo !empty($p_id) ? __('Preview Changes', 'directorist') : __($submit_label, 'directorist'); ?></button>
    </div>

    <div class="clearfix"></div>
</div>