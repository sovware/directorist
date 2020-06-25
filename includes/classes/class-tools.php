<?php
defined('ABSPATH') || die( 'Direct access is not allowed.' );
/**
 * @since 4.7.2
 * @package Directorist
 */
if(!class_exists('ATBDP_Tools')):

    class ATBDP_Tools{

        public function __construct()
        {
            add_action('admin_menu', array($this, 'add_tools_submenu'), 10);

        }

    /**
     * It adds a submenu for showing all the Tools and details support
     */
    public function add_tools_submenu()
    {
        add_submenu_page('edit.php?post_type=at_biz_dir', __('Tools', 'directorist'), __('Tools', 'directorist'), 'manage_options', 'tools', array($this, 'render_tools_submenu_page'));
    }

    public function render_tools_submenu_page(){
        ATBDP()->load_template('tools');
    }
    }

endif;

