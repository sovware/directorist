<?php
/**
 * @author  wpWax
 * @since   7.2.0
 * @version 7.2.0
 */

use Directorist\Directorist_Listings;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist">
    <a href="<?php echo (is_fee_manager_active())?esc_url(ATBDP_Permalink::get_fee_plan_page_link()):esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>"
        class="<?php echo atbdp_directorist_button_classes(); ?>"><?php _e('Submit New Listing', 'directorist'); ?></a>
</div>

