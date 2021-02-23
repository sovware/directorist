<?php

namespace Directorist;
class Enqueue_Assets {

    public $js_scripts     = [];
    public $css_scripts    = [];
    public $script_version = false;
    /**
     * Constuctor
     */
    function __construct() {
        // Load Assets
        add_action( 'init', [ $this, 'load_assets'] );

        $atbdp_legacy_template = get_directorist_option( 'atbdp_legacy_template', false );
        if ( empty( $atbdp_legacy_template ) ) {
            // Enqueue Public Scripts
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_public_scripts' ] );
        }

        // Enqueue Admin Scripts
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );

        // Enqueue Global Scripts
        add_action( 'init', [ $this, 'enqueue_global_scripts' ] );
    }

    /**
     * Load Assets
     *
     * @return void
     */
    public function load_assets() {

        $this->script_version = apply_filters( 'directorist_script_version', ATBDP_VERSION );

        // Load Vendor Assets
        $this->add_vendor_css_scripts();
        $this->add_vendor_js_scripts();

        // Load Public Assets
        $this->add_public_css_scripts();
        $this->add_public_js_scripts();

        // Load Admin Assets
        $this->add_admin_css_scripts();
        $this->add_admin_js_scripts();
        
        // Load Global Assets
        $this->add_global_css_scripts();
        $this->add_global_js_scripts();

        // Inject Scripts Meta
        $this->inject_scripts_meta();

        // Apply Hook to Scripts
        $this->apply_hook_to_scripts();
    }


    /**
     * Apply Hook to Scripts
     *
     * @return void
     */
    public function apply_hook_to_scripts() {
        $this->css_scripts = apply_filters( 'directorist_css_scripts', $this->css_scripts );
        $this->js_scripts = apply_filters( 'directorist_js_scripts', $this->js_scripts );
    }

    /**
     * Load Vendor CSS Scripts
     *
     * @return void
     */
    public function add_vendor_css_scripts() {
        $scripts = [];

        $atbdp_legacy_template = get_directorist_option( 'atbdp_legacy_template', false );
        $common_asset_group = ( $atbdp_legacy_template ) ? 'admin' : 'global';

        // Global
        // ================================
        $scripts['directorist-openstreet-map'] = [
            'file_name' => 'openstreet-map',
            'base_path' => DIRECTORIST_PUBLIC_CSS,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => $common_asset_group, // public || admin  || global
            'enable'    => Script_Helper::is_enable_map( 'openstreet' ),
        ];

        $scripts['directorist-openstreet-map-leaflet'] = [
            'file_name' => 'leaflet',
            'base_path' => DIRECTORIST_VENDOR_CSS . 'openstreet-map/',
            'ver'       => $this->script_version,
            'group'     => $common_asset_group, // public || admin  || global
            'enable'    => Script_Helper::is_enable_map( 'openstreet' ),
        ];

        $scripts['directorist-openstreet-map-openstreet'] = [
            'file_name' => 'openstreet',
            'base_path' => DIRECTORIST_VENDOR_CSS . 'openstreet-map/',
            'ver'       => $this->script_version,
            'group'     => $common_asset_group, // public || admin  || global
            'enable'    => Script_Helper::is_enable_map( 'openstreet' ),
        ];

        // Admin
        // ================================
        $scripts['directorist-unicons'] = [
            'link'      => '//unicons.iconscout.com/release/v3.0.3/css/line.css',
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'admin', // public || admin  || global
        ];

        // Public
        // ================================
        $scripts['directorist-bootstrap'] = [
            'file_name' => 'bootstrap',
            'base_path' => DIRECTORIST_VENDOR_CSS,
            'has_rtl'   => false,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'enable'    => false
        ];

        $scripts['directorist-font-awesome'] = [
            'file_name' => 'font-awesome.min',
            'base_path' => DIRECTORIST_VENDOR_CSS,
            'has_min'   => false,
            'has_rtl'   => false,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => $common_asset_group, // public || admin  || global
        ];

        $scripts['directorist-line-awesome'] = [
            'file_name' => 'line-awesome.min',
            'base_path' => DIRECTORIST_VENDOR_CSS,
            'has_min'   => false,
            'has_rtl'   => false,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => $common_asset_group, // public || admin  || global
            'enable'    => true
        ];

        $scripts['directorist-ez-media-uploader'] = [
            'file_name' => 'ez-media-uploader',
            'base_path' => DIRECTORIST_VENDOR_CSS,
            'has_min'   => false,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'global', // public || admin  || global
            'enable'    => Script_Helper::is_enable__ez_media_uploader()
        ];

        $scripts['directorist-select2'] = [
            'file_name' => 'select2.min',
            'base_path' => DIRECTORIST_VENDOR_CSS,
            'has_min'   => false,
            'has_rtl'   => false,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => $common_asset_group, // public || admin  || global
            'enable'    => true
        ];

        $scripts['directorist-slick'] = [
            'file_name' => 'slick',
            'base_path' => DIRECTORIST_VENDOR_CSS,
            'has_min'   => false,
            'has_rtl'   => false,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'enable'    => true
        ];

        $scripts['directorist-sweetalert'] = [
            'file_name' => 'sweetalert.min',
            'base_path' => DIRECTORIST_VENDOR_CSS,
            'has_min'   => false,
            'has_rtl'   => false,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => $common_asset_group, // public || admin  || global
            'enable'    => true
        ];

        $scripts = array_merge( $this->css_scripts, $scripts);
        $this->css_scripts = $scripts;
    }

    /**
     * Load Vendor JS Scripts
     *
     * @return void
     */
    public function add_vendor_js_scripts() {
        $scripts = [];

        $atbdp_legacy_template = get_directorist_option( 'atbdp_legacy_template', false );
        $common_asset_group = ( $atbdp_legacy_template ) ? 'admin' : 'global';

        // Global
        // ================================
        $scripts['directorist-no-script'] = [
            'file_name' => 'no-script',
            'base_path' => DIRECTORIST_VENDOR_JS,
            'deps'      => [],
            'has_min'   => false,
            'ver'       => '',
            'group'     => 'global',
            // 'section'   => '',
        ];

        // Openstreet
        $scripts['directorist-openstreet-layers'] = [
            'file_name' => 'openstreetlayers',
            'base_path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/',
            'deps'      => [],
            'has_min'   => false,
            'ver'       => '',
            'group'     => $common_asset_group,
            // 'section'   => '',
            'enable'    => Script_Helper::is_enable_map( 'openstreet' ),
        ];

        $scripts['directorist-openstreet-unpkg'] = [
            'file_name' => 'unpkg-min',
            'base_path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/',
            'deps'      => [],
            'has_min'   => false,
            'ver'       => '',
            'group'     => $common_asset_group,
            // 'section'   => '',
            'enable'    => Script_Helper::is_enable_map( 'openstreet' ),
        ];

        $scripts['directorist-openstreet-unpkg-index'] = [
            'file_name' => 'unpkg-index',
            'base_path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/',
            'deps'      => [],
            'has_min'   => false,
            'ver'       => '',
            'group'     => $common_asset_group,
            // 'section'   => '',
            'enable'    => Script_Helper::is_enable_map( 'openstreet' ),
        ];

        $scripts['directorist-openstreet-unpkg-libs'] = [
            'file_name' => 'unpkg-libs',
            'base_path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/',
            'deps'      => [],
            'has_min'   => false,
            'ver'       => '',
            'group'     => $common_asset_group,
            // 'section'   => '',
            'enable'    => Script_Helper::is_enable_map( 'openstreet' ),
        ];

        $scripts['directorist-openstreet-leaflet-versions'] = [
            'file_name' => 'leaflet-versions',
            'base_path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/',
            'deps'      => [],
            'has_min'   => false,
            'ver'       => '',
            'group'     => $common_asset_group,
            // 'section'   => '',
            'enable'    => Script_Helper::is_enable_map( 'openstreet' ),
        ];

        $scripts['directorist-openstreet-leaflet-markercluster-versions'] = [
            'file_name' => 'leaflet.markercluster-versions',
            'base_path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/',
            'deps'      => [],
            'has_min'   => false,
            'ver'       => '',
            'group'     => $common_asset_group,
            // 'section'   => '',
            'enable'    => Script_Helper::is_enable_map( 'openstreet' ),
        ];

        $scripts['directorist-openstreet-libs-setup'] = [
            'file_name' => 'libs-setup',
            'base_path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/',
            'deps'      => [],
            'has_min'   => false,
            'ver'       => '',
            'group'     => $common_asset_group,
            // 'section'   => '',
            'enable'    => Script_Helper::is_enable_map( 'openstreet' ),
        ];

        $scripts['directorist-openstreet-open-layers'] = [
            'file_name' => 'OpenLayers',
            'base_path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/openlayers/',
            'deps'      => [],
            'has_min'   => false,
            'ver'       => '',
            'group'     => $common_asset_group,
            // 'section'   => '',
            'enable'    => Script_Helper::is_enable_map( 'openstreet' ),
        ];

        $scripts['directorist-openstreet-crosshairs'] = [
            'file_name' => 'Crosshairs',
            'base_path' => DIRECTORIST_VENDOR_JS . 'openstreet-map/openlayers4jgsi/',
            'deps'      => [],
            'has_min'   => false,
            'ver'       => '',
            'group'     => $common_asset_group,
            // 'section'   => '',
            'enable'    => Script_Helper::is_enable_map( 'openstreet' ),
        ];

        $scripts['directorist-openstreet-load-scripts'] = [
            'file_name' => 'load-osm-map',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'deps'      => [],
            'has_min'   => false,
            'ver'       => '',
            'group'     => $common_asset_group,
            'section'   => '__',
            'enable'    => Script_Helper::is_enable_map( 'openstreet' ),
        ];

        // Google Map
        $map_api_key = get_directorist_option( 'map_api_key', 'AIzaSyCwxELCisw4mYqSv_cBfgOahfrPFjjQLLo' );
        $scripts['directorist-google-map'] = [
            'link'      => '//maps.googleapis.com/maps/api/js?key=' . $map_api_key . '&libraries=places',
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => $common_asset_group,
            'section'   => '',
            'enable'    => Script_Helper::is_enable_map( 'google' ),
        ];

        $scripts['directorist-markerclusterer'] = [
            'file_name' => 'markerclusterer',
            'base_path' => DIRECTORIST_VENDOR_JS,
            'deps'      => [],
            'has_min'   => false,
            'ver'       => $this->script_version,
            'group'     => $common_asset_group,
            'section'   => '',
            'enable'    => Script_Helper::is_enable_map( 'google' ),
        ];

        // Other
        $scripts['directorist-select2'] = [
            'file_name' => 'select2.min',
            'base_path' => DIRECTORIST_VENDOR_JS,
            'has_min'   => false,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => $common_asset_group, // public || admin  || global
            'section'   => '',
            'enable'    => true,
        ];

        $scripts['directorist-sweetalert'] = [
            'file_name' => 'sweetalert.min',
            'base_path' => DIRECTORIST_VENDOR_JS,
            'has_min'   => false,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => $common_asset_group, // public || admin  || global
            'section'   => '',
            'enable'    => true,
        ];

        $scripts['directorist-tooltip'] = [
            'file_name' => 'tooltip',
            'base_path' => DIRECTORIST_VENDOR_JS,
            'has_min'   => false,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => $common_asset_group, // public || admin  || global
            'section'   => '',
            'enable'    => true,
        ];

        $scripts['directorist-popper'] = [
            'file_name' => 'popper',
            'base_path' => DIRECTORIST_VENDOR_JS,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => $common_asset_group, // public || admin  || global
            'section'   => '',
            'enable'    => true,
        ];

        $scripts['directorist-range-slider'] = [
            'file_name' => 'range-slider',
            'base_path' => DIRECTORIST_VENDOR_JS,
            'deps'      => [],
            'has_min'   => false,
            'has_rtl'   => true,
            'ver'       => $this->script_version,
            'group'     => $common_asset_group, // public || admin  || global
            'section'   => '__',
        ];

        $scripts['directorist-ez-media-uploader'] = [
            'file_name' => 'ez-media-uploader',
            'base_path' => DIRECTORIST_VENDOR_JS,
            'deps'      => [],
            'has_min'   => false,
            'ver'       => $this->script_version,
            'group'     => 'global', // public || admin  || global
            'section'   => '',
        ];

        
        // Admin
        // ================================

        // Public
        // ================================
        $scripts['directorist-bootstrap'] = [
            'file_name' => 'bootstrap.min',
            'base_path' => DIRECTORIST_VENDOR_JS,
            'has_min'   => false,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'section'   => '',
            'enable'    => false,
        ];

        $scripts['directorist-grid'] = [
            'file_name' => 'grid.min',
            'base_path' => DIRECTORIST_VENDOR_JS,
            'has_min'   => false,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'section'   => '',
            'enable'    => false,
        ];

        $scripts['directorist-jquery-barrating'] = [
            'file_name' => 'jquery.barrating.min',
            'base_path' => DIRECTORIST_VENDOR_JS,
            'has_min'   => false,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'section'   => '',
            'enable'    => true,
        ];

        $scripts['directorist-slick'] = [
            'file_name' => 'slick.min',
            'base_path' => DIRECTORIST_VENDOR_JS,
            'has_min'   => false,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'section'   => '',
            'enable'    => true,
        ];

        $scripts['directorist-plasma-slider'] = [
            'file_name'     => 'plasma-slider',
            'base_path'     => DIRECTORIST_VENDOR_JS,
            'has_min'       => false,
            'ver'           => $this->script_version,
            'group'         => 'public',
        ];

        $scripts['directorist-uikit'] = [
            'file_name' => 'uikit.min',
            'base_path' => DIRECTORIST_VENDOR_JS,
            'has_min'   => false,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'section'   => '',
            'enable'    => false,
        ];

        $scripts['directorist-validator'] = [
            'file_name' => 'validator.min',
            'base_path' => DIRECTORIST_VENDOR_JS,
            'has_min'   => false,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'section'   => '',
            'enable'    => false,
        ];

        $scripts = array_merge( $this->js_scripts, $scripts);
        $this->js_scripts = $scripts;
    }


    /**
     * Load Public CSS Scripts
     *
     * @return void
     */
    public function add_public_css_scripts() {
        $scripts = [];
        
        $scripts['directorist-main-style'] = [
            'file_name' => 'main',
            'base_path' => DIRECTORIST_PUBLIC_CSS,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            // 'section'   => '__',
            'enable'   => true,
        ];

        $scripts['directorist-inline-style'] = [
            'file_name' => 'inline-style',
            'base_path' => DIRECTORIST_ASSETS . 'other/',
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'has_min'   => false,
            'has_rtl'   => false,
        ];
        
        $scripts['directorist-settings-style'] = [
            'file_name' => 'settings-style',
            'base_path' => DIRECTORIST_ASSETS . 'other/',
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'has_min'   => false,
            'has_rtl'   => false,
        ];

        $scripts['directorist-atmodal'] = [
            'file_name' => 'atmodal',
            'base_path' => DIRECTORIST_PUBLIC_CSS,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'section'   => '',
            'shortcode' => [ 'directorist_user_dashboard' ],
        ];

        $scripts['directorist-search-style'] = [
            'file_name' => 'search-style',
            'base_path' => DIRECTORIST_PUBLIC_CSS,
            'deps'      => [ ],
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'section'   => '',
            'enable'   => true,
        ];

        $scripts['directorist-add-listing-public'] = [
            'file_name' => 'add-listing',
            'base_path' => DIRECTORIST_PUBLIC_CSS,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'shortcode' => ['directorist_add_listing'],
        ];

        $scripts['directorist-pure-select-public'] = [
            'file_name' => 'pure-select',
            'base_path' => DIRECTORIST_PUBLIC_CSS,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
        ];

        $scripts = array_merge( $this->css_scripts, $scripts);
        $this->css_scripts = $scripts;

        // var_dump( $this->css_scripts );
    }

    /**
     * Load Public JS Scripts
     *
     * @return void
     */
    public function add_public_js_scripts() {
        $scripts = [];

        $scripts['directorist-main-script'] = [
            'file_name' => 'main',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'section'   => '',
            'enable'    => true,
            'localize_data' => [
                'object_name' => 'atbdp_public_data',
                'data' => Script_Helper::get_main_script_data()
            ],
        ];

        $scripts['directorist-releated-listings-slider'] = [
            'file_name' => 'releated-listings-slider',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'section'   => 'single-listing-page',
            'enable'    => true,
        ];

        $scripts['directorist-atmodal'] = [
            'file_name' => 'atmodal',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'section'   => '',
            'enable'    => true,
            // 'shortcode' => [ 'directorist_user_dashboard' ],
        ];

        $scripts['directorist-geolocation'] = [
            'file_name' => 'geolocation',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'section'   => 'search-home',
        ];

        $scripts['directorist-geolocation-widget'] = [
            'file_name' => 'geolocation-widget',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'section'   => 'search-home',
        ];

        $scripts['directorist-search-listing'] = [
            'file_name' => 'search-listing',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'section'   => 'search-form',
        ];

        $scripts['directorist-search-form-listing'] = [
            'file_name' => 'search-form-listing',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'section'   => 'search-form',
        ];

        $scripts['directorist-single-listing-openstreet-map-custom-script'] = [
            'file_name' => 'single-listing-openstreet-map-custom-script',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'ver'       => $this->script_version,
            'group'     => 'public',                                        // public || admin  || global
            'section'   => 'single-listing-page',                           // public || admin  || global
            'enable'    => Script_Helper::is_enable_map( 'openstreet' ),
        ];

        $scripts['directorist-single-listing-gmap-custom-script'] = [
            'file_name' => 'single-listing-gmap-custom-script',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'section'   => 'single-listing-page', 
            'enable'    => Script_Helper::is_enable_map( 'google' ),
        ];

        $scripts['directorist-add-listing-public'] = [
            'file_name' => 'add-listing',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'shortcode' => ['directorist_add_listing'],
        ];

        $scripts['directorist-add-listing-openstreet-map-custom-script-public'] = [
            'file_name' => 'add-listing-openstreet-map-custom-script',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'shortcode' => ['directorist_add_listing'],
            'enable' => Script_Helper::is_enable_map( 'openstreet' ),
        ];

        $scripts['directorist-add-listing-gmap-custom-script-public'] = [
            'file_name' => 'add-listing-gmap-custom-script',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'ver'       => $this->script_version,
            'group'     => 'public', // public || admin  || global
            'shortcode' => ['directorist_add_listing'],
            'enable' => Script_Helper::is_enable_map( 'google' ),
        ];

        $scripts['directorist-pure-select-public'] = [
            'file_name' => 'pure-select',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'ver'       => $this->script_version,
            'group'     => 'public',                 // public || admin  || global
        ];

        $scripts = array_merge( $this->js_scripts, $scripts);
        $this->js_scripts = $scripts;
    }

    /**
     * Load Admin CSS Scripts
     *
     * @return void
     */
    public function add_admin_css_scripts() {
        $scripts = [];

        $scripts['directorist-admin-style'] = [
            'file_name' => 'admin',
            'base_path' => DIRECTORIST_ADMIN_CSS,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'admin',
            'section'   => '',
            // 'page'      => 'plugins.php',
        ];

        $scripts['directorist-add-listing-admin'] = [
            'file_name' => 'add-listing',
            'base_path' => DIRECTORIST_PUBLIC_CSS,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'admin', // public || admin  || global
            'page'      => [ 'post-new.php', 'post.php' ],
        ];

        $scripts['directorist-plugins-css'] = [
            'file_name' => 'plugins',
            'base_path' => DIRECTORIST_ADMIN_CSS,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'admin',
            'section'   => '',
            // 'page'      => 'plugins.php',
            // 'enable'    => is_admin(),
        ];

        $scripts['directorist-settings-manager'] = [
            'file_name' => 'settings-manager',
            'base_path' => DIRECTORIST_ADMIN_CSS,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'admin',
            'section'   => '',
            'page'      => 'at_biz_dir_page_atbdp-settings',
        ];

        $scripts['directorist-multi-directory-archive'] = [
            'file_name' => 'multi-directory-archive',
            'base_path' => DIRECTORIST_ADMIN_CSS,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'admin',
            'section'   => '',
            'page'      => 'at_biz_dir_page_atbdp-directory-types',
        ];
        
        $scripts['directorist-multi-directory-builder'] = [
            'file_name' => 'multi-directory-builder',
            'base_path' => DIRECTORIST_ADMIN_CSS,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'admin',
            'section'   => '',
            'page'      => [ 
                'at_biz_dir_page_atbdp-layout-builder', 
                'at_biz_dir_page_atbdp-directory-types'
            ],
        ];

        $scripts['directorist-plupload'] = [
            'file_name' => 'directorist-plupload',
            'base_path' => DIRECTORIST_ADMIN_CSS,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'global',
            'section'   => '',
            'page'      => '',
        ];

        $scripts = array_merge( $this->css_scripts, $scripts);
        $this->css_scripts = $scripts;
    }

    /**
     * Load Admin JS Scripts
     *
     * @return void
     */
    public function add_admin_js_scripts() {
        $scripts = [];

        $scripts['directorist-admin-script'] = [
            'file_name'     => 'admin',
            'base_path'     => DIRECTORIST_ADMIN_JS,
            // 'deps'          => Script_Helper::get_admin_script_dependency(),
            'ver'           => $this->script_version,
            'group'         => 'admin',
            'section'       => '',
            'localize_data' => [
                [
                    'object_name' => 'atbdp_admin_data',
                    'data' => Script_Helper::get_admin_script_data()
                ],
                [
                    'object_name' => 'atbdp_public_data',
                    'data' => Script_Helper::get_main_script_data()
                ],
            ],
        ];

        $scripts['directorist-add-listing-admin'] = [
            'file_name' => 'add-listing',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'ver'       => $this->script_version,
            'group'     => 'admin', // public || admin  || global
            'page'      => ['post-new.php', 'post.php'],
        ];

        $scripts['directorist-multi-directory-archive'] = [
            'file_name' => 'multi-directory-archive',
            'base_path' => DIRECTORIST_ADMIN_JS,
            'ver'       => $this->script_version,
            'group'     => 'admin', // public || admin  || global
            'page'      => ['at_biz_dir_page_atbdp-directory-types'],
        ];

        $scripts['directorist-add-listing-openstreet-map-custom-script-admin'] = [
            'file_name' => 'add-listing-openstreet-map-custom-script',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'ver'       => $this->script_version,
            'group'     => 'admin',                                        // public || admin  || global
            'enable'    => Script_Helper::is_enable_map( 'openstreet' ),
            'page'      => ['post-new.php', 'post.php'],
            'section'   => '__',
        ];

        $scripts['directorist-add-listing-gmap-custom-script-admin'] = [
            'file_name' => 'add-listing-gmap-custom-script',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'ver'       => $this->script_version,
            'group'     => 'admin',                                    // public || admin  || global
            'enable'    => Script_Helper::is_enable_map( 'google' ),
            'page'      => ['post-new.php', 'post.php'],
            'section'   => '__',
        ];

    
        $scripts['directorist-multi-directory-builder'] = [
            'file_name'     => 'multi-directory-builder',
            'base_path'     => DIRECTORIST_ADMIN_JS,
            'ver'           => $this->script_version,
            'group'         => 'admin',
            'page'          => [ 
                'at_biz_dir_page_atbdp-layout-builder', 
                'at_biz_dir_page_atbdp-directory-types'
            ],
            'localize_data' => [
                'object_name' => 'ajax_data',
                'data' => [ 'ajax_url' => admin_url('admin-ajax.php') ]
            ],
        ];

        $scripts['directorist-settings-manager'] = [
            'file_name'     => 'settings-manager',
            'base_path'     => DIRECTORIST_ADMIN_JS,
            'ver'           => $this->script_version,
            'group'         => 'admin',
            'page'          => 'at_biz_dir_page_atbdp-settings',
            'localize_data' => [
                'object_name' => 'ajax_data',
                'data' => [ 'ajax_url' => admin_url('admin-ajax.php') ]
            ],
        ];

        $scripts['directorist-plugins'] = [
            'file_name' => 'plugins',
            'base_path' => DIRECTORIST_ADMIN_JS,
            // 'deps'      => ['jquery'],
            'ver'       => $this->script_version,
            'group'     => 'admin',
            'section'   => '',
            'page'      => 'plugins.php',
            'enable'    => is_admin(),
        ];

        $scripts['directorist-plupload'] = [
            'file_name' => 'directorist-plupload',
            'base_path' => DIRECTORIST_ADMIN_JS,
            'ver'       => $this->script_version,
            'group'     => 'global',
            'section'   => '',
        ];

        $scripts['directorist-import-export'] = [
            'file_name'     => 'import-export',
            'base_path'     => DIRECTORIST_ADMIN_JS,
            // 'deps'          => ['jquery'],
            'ver'           => $this->script_version,
            'group'         => 'admin',
            'section'       => '',
            'page'          => 'at_biz_dir_page_tools',
            'enable'        => is_admin(),
            'localize_data' => [
                'object_name' => 'import_export_data',
                'data' => [ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ]
            ],
        ];

        $scripts = array_merge( $this->js_scripts, $scripts);
        $this->js_scripts = $scripts;
    }

    /**
     * Load Global CSS Scripts
     *
     * @return void
     */
    public function add_global_css_scripts() {
        // $scripts = [];

        // $scripts['directorist-admin-style'] = [
        //     'file_name' => 'admin-style',
        //     'base_path' => DIRECTORIST_ADMIN_CSS,
        //     'deps'      => [],
        //     'ver'       => $this->script_version,
        //     'group'     => 'global',
        //     'section'   => '',
        // ];

        // $scripts = array_merge( $this->css_scripts, $scripts);
        // $this->css_scripts = $scripts;
    }

    /**
     * Load Global JS Scripts
     *
     * @return void
     */
    public function add_global_js_scripts() {
        $scripts = [];
        
        $atbdp_legacy_template = get_directorist_option( 'atbdp_legacy_template', false );
        $common_asset_group = ( $atbdp_legacy_template ) ? 'admin' : 'global';

        $scripts['directorist-map-view'] = [
            'file_name' => 'map-view',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => $common_asset_group,
            'section'   => '_',
            'enable'    => Script_Helper::is_enable_map( 'google' )
        ];

        $scripts['directorist-gmap-marker-clusterer'] = [
            'file_name' => 'markerclusterer',
            'base_path' => DIRECTORIST_PUBLIC_JS,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => $common_asset_group,
            'section'   => '__',
            'enable'    => Script_Helper::is_enable_map( 'google' )
        ];

        $scripts = array_merge( $this->js_scripts, $scripts);
        $this->js_scripts = $scripts;
    }


    /**
     * Enqueue Public Scripts
     *
     * @return void
     */
    public function enqueue_public_scripts( $page ) {
        // CSS
        $this->register_css_scripts_by_group( [ 'group' => 'public' ] );
        $this->enqueue_css_scripts_by_group( [ 'group' => 'public', 'page' => $page  ] );

        // Other CSS
        wp_add_inline_style( 'directorist-settings-style', \ATBDP_Stylesheet::style_settings_css() );

        // JS
        $this->register_js_scripts_by_group( [ 'group' => 'public' ] );
        $this->enqueue_js_scripts_by_group( [ 'group' => 'public', 'page' => $page  ] );
    }


    /**
     * Enqueue Admin Scripts
     *
     * @return void
     */
    public function enqueue_admin_scripts( $page ) {
        // CSS
        $this->register_css_scripts_by_group( [ 'group' => 'admin' ] );
        $this->enqueue_css_scripts_by_group( [ 'group' => 'admin', 'page' => $page ] );

        // JS
        $this->register_js_scripts_by_group( [ 'group' => 'admin' ] );
        $this->enqueue_js_scripts_by_group( [ 'group' => 'admin', 'page' => $page ] );

        wp_enqueue_media();
    }

    /**
     * Enqueue Global Scripts
     *
     * @return void
     */
    public function enqueue_global_scripts( $page ) {
        // CSS
        $this->register_css_scripts_by_group( [ 'group' => 'global' ] );
        $this->enqueue_css_scripts_by_group( [ 'group' => 'global', 'page' => $page  ] );

        // JS
        $this->register_js_scripts_by_group( [ 'group' => 'global' ] );
        $this->enqueue_js_scripts_by_group( [ 'group' => 'global', 'page' => $page  ] );

        // Other
        $this->enqueue_custom_color_picker_scripts();

        wp_enqueue_script( 'jquery' );
    }


    /**
     * Register CSS Scripts
     *
     * @return void
     */
    public function register_css_scripts_by_group( array $args = [] ) {
        $default = [ 'scripts' => $this->css_scripts, 'group' => 'public' ];
        $args    = array_merge( $default, $args );

        foreach( $args['scripts'] as $handle => $script_args ) {

            if (  ! ( ! empty( $script_args['group'] ) && $args['group'] === $script_args['group'] ) ) {
                continue;
            }

            $default = [
                'file_name' => $handle,
                'base_path' => DIRECTORIST_PUBLIC_CSS,
                'deps'      => [],
                'ver'       => false,
                'media'     => 'all',
                'link'      => ''
            ];

            $script_args = array_merge( $default, $script_args );
            $src = $script_args['base_path'] . $this->get_script_file_name( $script_args ) . '.css';

            if ( ! empty( $script_args['link'] ) ) {
                $src = $script_args['link'];
            }

            wp_register_style( $handle, $src, $script_args['deps'], $script_args['ver'], $script_args['media']);
        }
    }

    /**
     * Enqueue CSS Scripts
     *
     * @return void
     */
    public function enqueue_css_scripts_by_group( array $args = [] ) {
        $default = [ 'scripts' => $this->css_scripts, 'group' => 'public' ];
        $args    = array_merge( $default, $args );

        foreach( $args['scripts'] as $handle => $script_args ) {

            if ( isset( $script_args['enable'] ) && false === $script_args['enable'] ) {
                continue;
            }

            if ( isset( $args['page'] ) && isset( $script_args[ 'page' ] ) ) {
                if ( is_string( $script_args[ 'page' ] ) && $args['page'] !== $script_args[ 'page' ] ) { continue; }
                if ( is_array( $script_args[ 'page' ] ) && ! in_array( $args['page'], $script_args[ 'page' ] ) ) { continue; }
            }

            if (  ! ( ! empty( $script_args['group'] ) && $args['group'] === $script_args['group'] ) ) {
                continue;
            }

            if (  ! self::script__verify_shortcode( $script_args ) ) { 
                continue;
            }

            if ( ! empty( $script_args['section'] ) ) { continue; }

            wp_enqueue_style( $handle );
        }
    }



    /**
     * Register JS Scripts by Group
     *
     * @param array $args
     * @return void
     */
    public function register_js_scripts_by_group( array $args = [] ) {
        $default = [ 'scripts' => $this->js_scripts, 'group' => 'public' ];
        $args    = array_merge( $default, $args );

        foreach( $args['scripts'] as $handle => $script_args ) {
            if (  ! ( ! empty( $script_args['group'] ) && $args['group'] === $script_args['group'] ) ) {
                continue;
            }

            $default = [
                'file_name' => $handle,
                'base_path' => DIRECTORIST_PUBLIC_JS,
                'link'      => '',
                'deps'      => [],
                'ver'       => false,
                'has_rtl'   => false,
                'in_footer' => true,
            ];

            $script_args = array_merge( $default, $script_args );
            $src = $script_args['base_path'] . $this->get_script_file_name( $script_args ) . '.js';

            if ( ! empty( $script_args['link'] ) ) {
                $src = $script_args['link'];
            }

            wp_register_script( $handle, $src, $script_args['deps'], $script_args['ver'], $script_args['in_footer']);
        }
    }

    /**
     * Enqueue JS Scripts
     *
     * @return void
     */
    public function enqueue_js_scripts_by_group( array $args = [] ) {
        $default = [ 'scripts' => $this->js_scripts, 'group' => 'public' ];
        $args    = array_merge( $default, $args );

        foreach( $args['scripts'] as $handle => $script_args ) {

            if ( isset( $script_args['enable'] ) && false === $script_args['enable'] ) {
                continue;
            }

            if ( isset( $args['page'] ) && isset( $script_args[ 'page' ] ) ) {
                if ( is_string( $script_args[ 'page' ] ) && $args['page'] !== $script_args[ 'page' ] ) { continue; }
                if ( is_array( $script_args[ 'page' ] ) && ! in_array( $args['page'], $script_args[ 'page' ] ) ) { continue; }
            }

            if (  ! ( isset( $script_args['group'] ) && $args['group'] === $script_args['group'] ) ) {
                continue;
            }

            if (  ! self::script__verify_shortcode( $script_args ) ) { 
                continue;
            }

            if ( ! empty( $script_args['section'] ) ) { continue; }

            wp_enqueue_script( $handle );
            $this->add_localize_data_to_script( $handle, $script_args );
        }
    }

    // script__verify_shortcode
    public static function script__verify_shortcode( $script_args ) {
        if ( empty( $script_args['shortcode'] ) ) { 
            return true;
        }

        if ( ! is_array( $script_args['shortcode'] ) ) { 
            return true;
        }

        $match_found = 0;
        foreach ( $script_args['shortcode'] as $_shortcode ) {
            if ( self::has_shortcode( $_shortcode ) ) {
                $match_found++;
            }
        }

        if ( ! $match_found ) { return false; }

        return true;
    }

    // has_shortcode
    public static function has_shortcode( $subject = '' ) {
        global $post;
        $found = false;

        if ( is_a( $post, 'WP_Post' ) ) {
            if ( has_shortcode( $post->post_content, $subject ) ) {
                $found = true;
            }
        }

        return $found;
    }

    /**
     *  Add localize data to script
     *
     * @param string $handle
     * @param array $script_args
     * @return void
     */
    public function add_localize_data_to_script( $handle, $script_args ) {

        if ( ! is_array( $script_args ) ) { return; }
        if ( empty( $script_args['localize_data'] ) ) { return false; }

        if ( self::is_assoc_array( $script_args['localize_data'] ) ) {
            if ( ! self::has_valid_localize_data( $script_args['localize_data'] ) ) {
                return;
            }

            wp_localize_script( $handle, $script_args['localize_data']['object_name'], $script_args['localize_data']['data'] );
            return;
        }
        
        foreach ( $script_args['localize_data'] as $script_args_item ) {

            if ( ! self::has_valid_localize_data( $script_args_item ) ) {
                return;
            }

            wp_localize_script( $handle, $script_args_item['object_name'], $script_args_item['data'] );
        }
    }

    // has_valid_localize_data
    public static function has_valid_localize_data( array $localize_data = [] ) {
        
        if ( empty( $localize_data['object_name'] ) ) { return false; }
        if ( ! is_string( $localize_data['object_name'] ) ) { return false; }
        if ( empty( $localize_data['data'] ) ) { return false; }
        if ( ! is_array(  $localize_data['data'] ) ) { return false; }

        return true;
    }

    // is_assoc_array
    public static function is_assoc_array( array $arr = [] ) {
        if ( array() === $arr ) { return false; }

        return array_keys( $arr) !== range( 0, count($arr) - 1 );
    }


    /**
     * Get Script File Name
     *
     * @param array $args
     * @return $file_name
     */
    public function get_script_file_name( array $args = [] ) {
        $default = [ 'has_min' => true, 'has_rtl' => true ];
        $args    = array_merge( $default, $args );

        $file_name  = ( ! empty( $args['file_name'] ) ) ? $args['file_name'] : '';
        $has_min    = ( ! empty( $args['has_min'] ) ) ? true : false;
        $has_rtl    = ( ! empty( $args['has_rtl'] ) ) ? true : false;

        $load_min = apply_filters( 'directorist_load_min_files', !SCRIPT_DEBUG );
        $is_rtl   =  is_rtl();

        if ( $has_min && $load_min ) {
            $file_name = "{$file_name}.min";
        }

        if ( $has_rtl && $is_rtl ) {
            $file_name = "{$file_name}.rtl";
        }

        return $file_name;
    }

    /**
     * Inject Scripts Meta
     *
     * @return void
     */
    public function inject_scripts_meta() {
        // Add js script meta
        foreach( $this->js_scripts as $handle => $script_args ) {
            // Inject WP dependency meta
            if (  empty( $script_args['file_name'] ) ||  empty( $script_args['group'] ) ) {
                continue;
            }

            $file_name = $this->get_script_file_name( $script_args );
            $asset_path = ATBDP_DIR . "assets/dest/{$script_args['group']}/js/{$file_name}.asset.php";

            if ( ! file_exists( $asset_path ) ) { continue; }
            $asset_source = require( $asset_path );

            $deps = ( isset( $script_args['deps'] ) && is_array( $script_args['deps'] ) ) ? $script_args['deps'] : [];
            $ver  = ( isset( $script_args['ver'] ) && is_string( $script_args['ver'] ) ) ? $script_args['ver'] : '';

            if ( isset( $asset_source['dependencies'] ) ) {
                $deps = array_unique( array_merge( $deps, $asset_source['dependencies'] ) );
            }

            if ( isset( $asset_source['version'] ) ) {
                $ver =  $asset_source['version'];
            }

            $this->js_scripts[ $handle ][ 'deps' ] = $deps;
            $this->js_scripts[ $handle ][ 'ver' ]  = $ver;
        }
    }

    /**
     * Enqueue Custom Color Picker Scripts
     *
     * @return void
     */
    public function enqueue_custom_color_picker_scripts() {
        wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );
        wp_enqueue_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), array( 'iris', 'wp-i18n' ), false, 1 );

        $colorpicker_l10n = array(
            'clear'         => __( 'Clear' ),
            'defaultString' => __( 'Default' ),
            'pick'          => __( 'Select Color' ),
            'current'       => __( 'Current Color' ),
        );

        wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n );

    }
}
