<?php

namespace Directorist;

class Map_iFrame {

    public function __construct() {
        add_action( 'template_redirect', [ $this, 'map_iframe' ] );
        add_shortcode( 'directorist-map', [ $this, 'render_listings_archive_map' ] );
    } 

    // map_iframe
    public function map_iframe() {
        global $wp;

        if ( 'directorist-listings-archive-map-iframe' !== $wp->request ) { return; } 
    
            $this->get_osm_iframe();

        die;
    }

    public function render_listings_archive_map() {
        $url  = home_url( 'directorist-listings-archive-map-iframe' );
        $data = [ 'paged' => 1 ];
        $url  = add_query_arg( $data, $url );

        ob_start(); ?>

        <iframe class="directorist-iframe" src="<?php echo $url; ?>" title="Map" width="100%" height="400"></iframe>
        
        <?php return ob_get_clean();
    }

    // get_osm_iframe
    public function get_osm_iframe() {

        $atts = $_GET;
        $listings = new Directorist_Listings( $atts );
        $main_style = DIRECTORIST_PUBLIC_CSS . 'main.css';

        $osm_js_scripts = [
            'jquery'           => 'https://code.jquery.com/jquery-3.5.1.min.js',
            'openstreetlayers' => DIRECTORIST_VENDOR_JS . 'openstreet-map/openstreetlayers.js',
            'unpkg-min'        => DIRECTORIST_VENDOR_JS . 'openstreet-map/unpkg-min.js',
            'unpkg-index'      => DIRECTORIST_VENDOR_JS . 'openstreet-map/unpkg-index.js',
            'unpkg-libs'       => DIRECTORIST_VENDOR_JS . 'openstreet-map/unpkg-libs.js',
            'leaflet-versions' => DIRECTORIST_VENDOR_JS . 'openstreet-map/leaflet-versions.js',
            'markercluster'    => DIRECTORIST_VENDOR_JS . 'openstreet-map/leaflet.markercluster-versions.js',
            'libs-setup'       => DIRECTORIST_VENDOR_JS . 'openstreet-map/libs-setup.js',
            'open-layers'      => DIRECTORIST_VENDOR_JS . 'openstreet-map/openlayers/OpenLayers.js',
            'crosshairs'       => DIRECTORIST_VENDOR_JS . 'openstreet-map/openlayers4jgsi/Crosshairs.js',
            'load-scripts'     => DIRECTORIST_VENDOR_JS . 'openstreet-map/load-scripts.js',
        ]; ?>

        <!DOCTYPE html>
        <head>
            <link rel="stylesheet" type="text/css" href="<?php echo $main_style; ?>"></link>
        </head>
        <body>
            <?php echo $listings->load_openstreet_map(); ?>

            <?php foreach( $osm_js_scripts as $script_key => $script_src ) : ?>
            <script id="<?php echo $script_key ?>" src="<?php echo $script_src ?>"></script>
            <?php endforeach; ?>
        </body>
        </html>

        <?php
    }

}