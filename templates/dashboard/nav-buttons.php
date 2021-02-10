<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.6
 */
?>
<div class="nav_button">

    <?php if ( $display_submit_btn && 'general' != $user_type && 'become_author' != $user_type ) { ?>
        <a href="<?php echo esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>" class="<?php echo esc_attr( atbdp_directorist_button_classes() ); ?>"><?php esc_html_e('Submit Listing', 'directorist'); ?></a>
    <?php } ?>

    <?php if ( 'general' == $user_type ) { ?>
        <a href="" class="<?php echo esc_attr( atbdp_directorist_button_classes() ); ?> atbdp-become-author" data-nonce="<?php echo wp_create_nonce('atbdp_become_author') ?>" data-userId='<?php echo get_current_user_id(); ?>'><?php esc_html_e('Become an author', 'directorist'); ?></a>
        <p id='atbdp-become-author-success'></p>
    <?php } ?>

    <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="<?php echo atbdp_directorist_button_classes('secondary'); ?>"><?php esc_html_e('Log Out', 'directorist'); ?></a>
</div>