<?php
// it handles directorist Help and Support Page
class ATBDP_Help_Support{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_help_and_support_submenu'), 11);
    }

    /**
     * It adds a submenu for showing all the documentation and details support
     */
    public function add_help_and_support_submenu()
    {
        add_submenu_page('edit.php?post_type=at_biz_dir', __('Documentation', ATBDP_TEXTDOMAIN), __('Documentation', ATBDP_TEXTDOMAIN), 'manage_options', 'directorist-help-and-support', array($this, 'display_help_and_support_menu'));
    }

    /**
     * It displays settings page markup
     */
    public function display_help_and_support_menu()
    {
        ATBDP()->load_template('help-and-support');
    }

}