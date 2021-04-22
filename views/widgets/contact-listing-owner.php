<?php
echo $args['before_widget'];
echo '<div class="atbd_widget_title">';
echo $args['before_title'] . esc_html(apply_filters('widget_contact_form_title', $title)) . $args['after_title'];
echo '</div>';
$form_id = apply_filters('atbdp_contact_listing_owner_widget_form_id', 'atbdp-contact-form-widget');
?>
<div class="atbdp directorist atbdp-widget-listing-contact">
  <form id="<?php echo $form_id; ?>" class="form-vertical directorist-contact-owner-form">
    <div class="form-group">
      <input type="text" class="form-control" name="atbdp-contact-name" placeholder="<?php _e('Name', 'directorist'); ?>" required />
    </div>

    <div class="form-group">
      <input type="email" class="form-control" name="atbdp-contact-email" placeholder="<?php _e('Email', 'directorist'); ?>" required />
    </div>

    <?php
    $msg_html = '<div class="form-group">';
    $msg_html .= '<textarea class="form-control" name="atbdp-contact-message" rows="3" placeholder="' . __('Message', 'directorist') . '..." required ></textarea>';
    $msg_html .= '</div>';
    
    /**
     * @since 5.10.0
     */
    echo apply_filters('atbdp_widget_contact_form_message_field', $msg_html);
    ?>

    <input type="hidden" name="atbdp-post-id" value="<?php echo $post->ID; ?>" />
    <input type="hidden" name="atbdp-listing-email" value="<?php echo ! empty($email) ? sanitize_email($email) : ''; ?>" />
    
    <?php
    /**
     * It fires before contact form in the widget area
     * @since 4.4.0
     */

    do_action('atbdp_before_contact_form_submit_button');
    ?>
    <p class="atbdp-widget-elm directorist-contact-message-display"></p>

    <button type="submit" class="btn btn-primary"><?php _e('Submit', 'directorist'); ?></button>
  </form>
</div>

<?php
echo $args['after_widget'];
