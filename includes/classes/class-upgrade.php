<?php
// it handles directorist upgrade
class ATBDP_Upgrade{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_upgrade_submenu'), 11);
        add_action('wp_ajax_atbdp_upgrade_old_listings', array($this, 'upgrade_old_listings'));
    }

    /**
     * It adds a submenu for upgrading directorist listing data that are old so that they work fine with new export & import feature
     */
    public function add_upgrade_submenu()
    {
        add_submenu_page('edit.php?post_type=at_biz_dir', __('Upgrade Database', ATBDP_TEXTDOMAIN), __('<span style="color: #ffc733;">Upgrade Database</span>', ATBDP_TEXTDOMAIN), 'manage_options', 'directorist-upgrade', array($this, 'display_upgrade_menu'));
    }

    /**
     * It displays settings page markup
     */
    public function display_upgrade_menu()
    {
        ATBDP()->load_template('upgrade-directorist');
    }

    /**
     * It upgrades old listings and make them compatible with new directorist and it import export
     */
    public function upgrade_old_listings()
    {
        if (!valid_js_nonce()) wp_send_json_error(__('Nonce is invalid', ATBDP_TEXTDOMAIN));
        global $wpdb;
        // get all the ids of old listings that does not have new _listing_img or all listings that has _listing_info meta
        // Grab a snapshot of post IDs, just in case it changes during the export.
        $listing_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} AS p INNER JOIN {$wpdb->postmeta} AS pm ON p.ID=pm.post_id WHERE pm.meta_key='_listing_info'" );
        // Fetch 20 posts at a time rather than loading the entire table into memory.
        while ( $next_listings = array_splice( $listing_ids, 0, 20 ) ) {
            $where = 'WHERE ID IN (' . join(',', $next_listings) . ')';
            $listings = $wpdb->get_results("SELECT * FROM {$wpdb->posts} $where");
            // iterate over the listings and update their data
            foreach ($listings as $listing){
                $infos= aazztech_enc_unserialize(get_post_meta($listing->ID, '_listing_info', true));
                // update old meta to new meta
                foreach ($infos as $meta_key => $meta_value) {
                    if ('attachment_id' == $meta_key){
                        update_post_meta($listing->ID, '_listing_img', $meta_value);
                    }elseif ('phone' == $meta_key){
                        update_post_meta($listing->ID, '_'.$meta_key, @$meta_value[0]);
                    }else{
                        update_post_meta($listing->ID, '_'.$meta_key, $meta_value);
                    }
                }
                // delete the old meta _listing_info as we do not use it in new listing
                delete_post_meta($listing->ID, '_listing_info');
            }
        }
        update_option('directorist_old_listing_upgraded', true);
        wp_send_json_success(__('Congratulations! All old listings Have been updated successfully', ATBDP_TEXTDOMAIN));
    }
}