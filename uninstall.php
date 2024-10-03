<?php
/**
 * @package Directorist
 */
defined('WP_UNINSTALL_PLUGIN') || exit;

global $wpdb;

include_once("directorist-base.php");

// Clear schedules
wp_clear_scheduled_hook('directorist_hourly_scheduled_events');

function directorist_uninstall() {
    global $wpdb;

    // Delete selected pages
    wp_delete_post(get_directorist_option('add_listing_page'), true);
    wp_delete_post(get_directorist_option('all_listing_page'), true);
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
    wp_delete_post(get_directorist_option('privacy_policy'), true);
    wp_delete_post(get_directorist_option('terms_conditions'), true);

    // Delete posts and data
    $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type IN ('at_biz_dir', 'atbdp_fields', 'atbdp_orders', 'atbdp_listing_review');");

    // Delete all metabox
    $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE post_id NOT IN (SELECT ID FROM {$wpdb->posts});");

    // Delete term relationships
    $wpdb->query("DELETE FROM {$wpdb->term_relationships} WHERE object_id NOT IN (SELECT ID FROM {$wpdb->posts});");

    // Delete all taxonomy
    $wpdb->query("DELETE FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'at_biz_dir-location'");
    $wpdb->query("DELETE FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'at_biz_dir-category'");
    $wpdb->query("DELETE FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'at_biz_dir-tags'");

    // Delete all term meta
    $wpdb->query("DELETE FROM {$wpdb->termmeta} WHERE term_id NOT IN (SELECT term_id FROM {$wpdb->term_taxonomy});");
    $wpdb->query("DELETE FROM {$wpdb->terms} WHERE term_id NOT IN (SELECT term_id FROM {$wpdb->term_taxonomy});");

    // Delete review database
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}atbdp_review");

    // Delete usermeta
    $wpdb->query("DELETE FROM $wpdb->usermeta WHERE meta_key LIKE '%atbdp%';");
    $wpdb->query("DELETE FROM $wpdb->usermeta WHERE meta_key = 'pro_pic';");

    // Delete all the Plugin Options
    $atbdp_settings = array(
        "{$wpdb->prefix}atbdp_review_db_version",
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

if (is_multisite()) {
    $original_blog_id = get_current_blog_id();
    $sites = get_sites();

    foreach ($sites as $site) {
        switch_to_blog($site->blog_id);
        
        if( ! get_directorist_option('enable_uninstall',0) ) {
            continue;
        }

        directorist_uninstall();
        restore_current_blog();
    }

    switch_to_blog($original_blog_id);
} else {
    if( ! get_directorist_option('enable_uninstall',0) ) {
        return;
    }
    
    directorist_uninstall();
}
