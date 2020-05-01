<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */

$submit_listing_button = get_directorist_option('submit_listing_button', 1);
?>
<div class="nav_button">
    <?php if (!empty($submit_listing_button)) { ?>
        <a href="<?php echo esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>"
           class="<?php echo atbdp_directorist_button_classes(); ?>"><?php _e('Submit Listing', 'directorist'); ?></a>
    <?php } ?>
    <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>"
       class="<?php echo atbdp_directorist_button_classes('secondary'); ?>"><?php _e('Log Out', 'directorist'); ?></a>
</div>