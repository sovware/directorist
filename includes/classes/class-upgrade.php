<?php
// it handles directorist upgrade
class ATBDP_Upgrade{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_upgrade_submenu'), 11);
        add_action('wp_ajax_atbdp_upgrade_old_pages', array($this, 'upgrade_old_pages'));
        add_action('admin_init', array( $this, 'check_need_to_upgrade_database' ), 100 );
        add_action('admin_notices', array( $this, 'upgrade_notice' ), 100 );
    }

    public function upgrade_notice() {
        $user_id = get_current_user_id();
        if (!current_user_can('administrator')) return false;
        $update_link = admin_url().'/edit.php?post_type=at_biz_dir&page=directorist-upgrade';

        //check the version of Directorist
        $directorist_header = get_plugins( '/' . explode( '/', plugin_basename( __FILE__ ) )[0] );
        $current_version = '';
        foreach ($directorist_header as $key => $val){
            $current_version = $val['Version'];
        }

        if ('true' == get_user_meta( $user_id, '_atbdp_shortcode_regenerate_notice',true)){
            $link_regen = '<a href="'.$update_link.'">Generate Pages</a>';
            //update notice for single category and location page.
            if ( true != get_user_meta( $user_id, '_atbdp_location_category_page',true )){
                echo '<div id="message" class="notice notice-info" style="display: flex; background: #f7bdc7;  justify-content: space-between;"><p>';
                printf(__('Directorist plugin requires two new pages with the [directorist_category] and [directorist_location] shortcodes to function single category and location pages properly. You can create these pages yourself or let the plugin do this for you.<br> %s', ATBDP_TEXTDOMAIN), $link_regen);
                echo '</p><p><a href="?location-category-page">Hide</a></p></div>';
            }
        }

        if (class_exists('BD_Business_Hour')){
            $businessH_version = BDBH_VERSION;
            if (empty(get_user_meta($user_id, '_atbdp_bh_notice', true)) && ($businessH_version<'2.0.1')){
                $BHlink = 'https://aazztech.com/product/directorist-business-hours/';
                $BHextension = sprintf('<a target="_blank" href="%s">%s</a>', $BHlink, __('Business Hours', ATBDP_TEXTDOMAIN));
                echo '<div id="message" class="notice notice-info" style="display: flex; background: #ffc733;  justify-content: space-between;"><p>';
                printf(__('Please update %s extension as we have made a major update (otherwise it may create some issues).', ATBDP_TEXTDOMAIN), $BHextension);
                echo '</p><p><a href="?bh-update-notice">Hide</a></p></div>';
            }
        }
        $link = '<a href="'.$update_link.'">please replace</a>';
        $is_generated_pages = get_user_meta( $user_id, '_atbdp_shortcode_regenerate_notice',true );
        if (empty($is_generated_pages)){
            echo '<div id="message" class="notice notice-info" style="display: flex; background: #ffc733;  justify-content: space-between;"><p>';
            printf(__('If you are an old user of the %s plugin, %s your shortcodes as we have restructured our shortcodes.', ATBDP_TEXTDOMAIN), ATBDP_NAME, $link);
            echo '</p><p><a href="?my-plugin-dismissed">Hide</a></p></div>';
        }
    }

    public function check_need_to_upgrade_database( ){
        $user_id = get_current_user_id();
        if ( isset( $_GET['bh-update-notice'] ) ){
            update_user_meta( $user_id, '_atbdp_bh_notice', 1);
        }
        if ( isset( $_GET['my-plugin-dismissed'] ) ){
            update_user_meta( $user_id, '_atbdp_shortcode_regenerate_notice', 'new_true' );
        }if(isset( $_POST['shortcode-updated'] )){
            update_option('atbdp_pages_version', 0);
        }
        if ( isset( $_GET['location-category-page'] ) ){
            update_user_meta( $user_id, '_atbdp_location_category_page', 'true' );
        }
    }

    /**
     * It adds a submenu for upgrading directorist listing data that are old so that they work fine with new export & import feature
     */
    public function add_upgrade_submenu()
    {
        add_submenu_page('edit.php?post_type=at_biz_dir', __('Database Upgrade', ATBDP_TEXTDOMAIN), __('<span>Database Upgrade</span>', ATBDP_TEXTDOMAIN), 'manage_options', 'directorist-upgrade', array($this, 'display_upgrade_menu'));
    }

    /**
     * It displays settings page markup
     */
    public function display_upgrade_menu()
    {
        ATBDP()->load_template('upgrade-directorist');
    }


    /**
     * It upgrades old pages and make them compatible with new shortcodes
     */
    public function upgrade_old_pages()
    {
        //if (!valid_js_nonce()) wp_send_json_error(__('Nonce is invalid', ATBDP_TEXTDOMAIN));

        update_option('atbdp_pages_version', 0);
        wp_send_json_success(__('Congratulations! All old pages have been updated successfully', ATBDP_TEXTDOMAIN));
    }
}