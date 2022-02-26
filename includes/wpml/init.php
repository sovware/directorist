<?php

namespace Directorist\WPML;

class Init {

    public static $instance = null;

    public function __construct() {
        add_action( 'init', [ $this, 'init_wpml_intigration' ] );
    }

     /**
     * Get Instance
     * 
     * @return void
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new Init();
        }

        return self::$instance;
    }

     /**
     * Get Instance
     * 
     * @return void
     */
    public static function init_wpml_intigration() {

        if ( ! is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
            return;
        }

        Filter_Permalinks::get_instance();
    }

}

Init::get_instance();