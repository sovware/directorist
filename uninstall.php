<?php
/**
 * @package Directorist
 */
if ( ! defined ('WP_UNINSTALL_PLUGIN')) {
    die;
}
// Access the database via SQL
global $wpdb;
include_once("directorist-base.php");
$enable_uninstall = get_directorist_option('enable_uninstall',0);
// Clear schedules
wp_clear_scheduled_hook('atbdp_custom_cron');
wp_clear_scheduled_hook('directorist_hourly_scheduled_events');
if(!empty($enable_uninstall)) {
    // Delete selected pages.
    wp_delete_post(get_directorist_option('add_listing_page'), true);
    wp_delete_post(get_directorist_option('all_listing_page'), true);
    wp_delete_post(get_directorist_option('single_listing_page'), true);
    wp_delete_post(get_directorist_option('user_dashboard'), true);
    wp_delete_post(get_directorist_option('author_profile_page'), true);
    wp_delete_post(get_directorist_option('all_categories_page'), true);
    wp_delete_post(get_directorist_option('single_category_page'), true);
    wp_delete_post(get_directorist_option('all_locations_page'), true);
    wp_delete_post(get_directorist_option('single_location_page'), true);
    wp_delete_post(get_directorist_option('single_tag_page'), true);
    wp_delete_post(get_directorist_option('custom_registration'), true);
    wp_delete_post(get_directorist_option('user_login'), true);
    wp_delete_post(get_directorist_option('search_listing'), true);
    wp_delete_post(get_directorist_option('search_result_page'), true);
    wp_delete_post(get_directorist_option('checkout_page'), true);
    wp_delete_post(get_directorist_option('payment_receipt_page'), true);
    wp_delete_post(get_directorist_option('transaction_failure_page'), true);
    //Delete all custom post
    $wpdb->query("DELETE FROM wp_posts WHERE post_type = 'at_biz_dir'"); // delete all listing
    $wpdb->query("DELETE FROM wp_posts WHERE post_type = 'atbdp_fields'"); // delete all custom field
    $wpdb->query("DELETE FROM wp_posts WHERE post_type = 'atbdp_orders'"); // delete all order
    $wpdb->query("DELETE FROM wp_posts WHERE post_type = 'atbdp_listing_review'"); // delete all approval review
    //Delete all metabox
    $wpdb->query("DELETE FROM wp_postmeta WHERE post_id Not IN  (SELECT id FROM wp_posts)");
    //Delete term relationships
    $wpdb->query("DELETE FROM wp_term_relationships WHERE object_id Not IN  (SELECT id FROM wp_posts)");

    //Delete all taxonomy
    $wpdb->query("DELETE FROM wp_term_taxonomy WHERE taxonomy = 'at_biz_dir-location'");
    $wpdb->query("DELETE FROM wp_term_taxonomy WHERE taxonomy = 'at_biz_dir-category'");
    $wpdb->query("DELETE FROM wp_term_taxonomy WHERE taxonomy = 'at_biz_dir-tags'");
    //Delete all term meta
    $wpdb->query("DELETE FROM wp_termmeta WHERE term_id Not IN  (SELECT term_id FROM wp_term_taxonomy)");
    $wpdb->query("DELETE FROM wp_terms WHERE term_id Not IN  (SELECT term_id FROM wp_term_taxonomy)");
    //Delete raview database
    $wpdb->query("DROP TABLE IF EXISTS wp_atbdp_review");
    //Delete usermeta.
    $wpdb->query("DELETE FROM $wpdb->usermeta WHERE meta_key LIKE '%atbdp%';");
    $wpdb->query("DELETE FROM $wpdb->usermeta WHERE meta_key = 'pro_pic';");

    // Delete all the Plugin Options
    $atbdp_settings = array(
        'wp_atbdp_review_db_version',
        'atbdp_option',
        'widget_bdpl_widget',
        'widget_bdvd_widget',
        'widget_bdco_widget',
        'widget_bdsb_widget',
        'widget_bdlf_widget',
        'widget_bdcw_widget',
        'widget_bdlw_widget',
        'widget_bdtw_widget',
        'widget_bdsw_widget',
        'widget_bdmw_widget',
        'widget_bdamw_widget',
        'widget_bdsl_widget',
        'widget_bdsi_widget',
        'widget_bdfl_widget',
        'atbdp_meta_version',
        'atbdp_pages_version',
        'atbdp_roles_mapped',
        'atbdp_roles_version',
        'at_biz_dir-location_children',
        'at_biz_dir-category_children',
    );
    foreach ($atbdp_settings as $settings) {
        delete_option($settings);
    }
}