<?php
/**
 * @author  wpWax
 * @since   7.3.0
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-card__body directorist-widget__submit-listing">
    <a href="<?php echo is_fee_manager_active() ? esc_url(ATBDP_Permalink::get_fee_plan_page_link()): esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>"
        class="<?php echo esc_attr( atbdp_directorist_button_classes() ); ?>"><?php esc_html_e('Submit New Listing', 'directorist'); ?></a>
</div>
