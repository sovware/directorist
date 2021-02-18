<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="nav_button">

    <?php if ( $display_submit_btn && 'general' != $user_type && 'become_author' != $user_type ) { ?>
        <a href="<?php echo esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>" class="<?php echo esc_attr( atbdp_directorist_button_classes() ); ?>"><?php esc_html_e('Submit Listing', 'directorist'); ?></a>
    <?php } ?>

    <?php if ( 'general' == $user_type && ! empty( $become_author_button) ) { ?>
        <a href="" class="<?php echo esc_attr( atbdp_directorist_button_classes() ); ?> directorist-become-author"><?php echo $become_author_button_text; ?></a>
        <p id='directorist-become-author-success'></p>

        <div class="directorist-become-author-modal">
            <div class="directorist-become-author-modal__content">
                <!-- <a href="" class="directorist-become-author-modal__close">x</a> -->
                <h3>Are you sure?</h3>
                <p>
                    <a href="#" class="directorist-become-author-modal__cancel">Cancel</a>
                    <a href="#" class="directorist-become-author-modal__approve" data-nonce="<?php echo wp_create_nonce('atbdp_become_author') ?>" data-userId='<?php echo get_current_user_id(); ?>'>Yes</a>
                </p>
            </div>
        </div>
    <?php } ?>

    <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="<?php echo atbdp_directorist_button_classes('secondary'); ?>"><?php esc_html_e('Log Out', 'directorist'); ?></a>
</div>