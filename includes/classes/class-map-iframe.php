<?php

namespace Directorist;

class Map_iFrame {

    public function __construct() {
        add_action( 'template_redirect', [ $this, 'map_iframe' ] );
        add_shortcode( 'directorist-map', [ $this, 'render_map' ] );
    } 


    // map_iframe
    public function map_iframe() {
        global $wp;

        if ( 'directorist-map-iframe' !== $wp->request ) { return; } 
            $listings = new Directorist_Listings();
            $main_style = DIRECTORIST_PUBLIC_CSS . 'main.css';
            ?>
            <!DOCTYPE html>
            <head>
                <link rel="stylesheet" type="text/css" href="<?php echo $main_style; ?>"></link>
            </head>
            <body>
                <?php echo $listings->render_shortcode(); ?>
            </body>
            </html>
            <?php
        die;
    }

    public function render_map() {
        $url = home_url( 'directorist-map-iframe' );
        ob_start(); 
        ?>
        <iframe id="custom-map" src="<?php echo $url; ?>" title="Map" width="100%" height="300"></iframe>
        
        <?php return ob_get_clean();
    }

}