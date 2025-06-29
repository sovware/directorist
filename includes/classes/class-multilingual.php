<?php

defined( 'ABSPATH' ) || die( 'Direct access is not allowed.' );

if ( ! class_exists( 'Directorist_Multilingual' ) ) :

class Directorist_Multilingual {
    public function __construct() {
        add_action( 'plugins_loaded', [ self::class, 'init' ], 20 );
    }

    public static function init() {
        new Directorist_Multilingual_Polylang();
    }
}

endif;