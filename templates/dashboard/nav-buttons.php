<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="nav_button">

    <?php if ($display_submit_btn) { ?>
        <a href="<?php echo esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>" class="<?php echo esc_attr( atbdp_directorist_button_classes() ); ?>"><?php esc_html_e('Submit Listing', 'directorist'); ?></a>
    <?php } ?>

    <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="<?php echo atbdp_directorist_button_classes('secondary'); ?>"><?php esc_html_e('Log Out', 'directorist'); ?></a>
</div>