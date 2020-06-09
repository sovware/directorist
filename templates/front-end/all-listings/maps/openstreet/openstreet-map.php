<?php
wp_enqueue_script('leaflet-subgroup-realworld');
$data = array(
    'zoom'       => !empty($zoom) ? $zoom : 1,
);
wp_localize_script( 'leaflet-subgroup-realworld', 'atbdp_map', $data );
wp_localize_script( 'leaflet-subgroup-realworld', 'atbdp_lat_lon', array(
    'lat'=>40.7128,
    'lon'=>74.0060,
) );
?>
<style>
    .myDivIcon {
        text-align: center !important;
        line-height: 20px !important;
        position: relative;
    }
    .myDivIcon div.atbd_map_shape {
        position: absolute;
        top: -38px;
        left: -15px;
    }
</style>
<script>
    var addressPoints = [
        <?php while( $all_listings->have_posts() ) : $all_listings->the_post();
        global $post;
        $manual_lat                     = get_post_meta($post->ID, '_manual_lat', true);
        $manual_lng                     = get_post_meta($post->ID, '_manual_lng', true);
        $listing_img                    = get_post_meta(get_the_ID(), '_listing_img', true);
        $listing_prv_img                = get_post_meta(get_the_ID(), '_listing_prv_img', true);
        $crop_width                     = get_directorist_option('crop_width', 360);
        $crop_height                    = get_directorist_option('crop_height', 300);
        $address                        = get_post_meta(get_the_ID(), '_address', true);
        $display_map_info               = get_directorist_option('display_map_info', 1);
        $display_image_map              = get_directorist_option('display_image_map', 1);
        $display_title_map              = get_directorist_option('display_title_map', 1);
        $display_address_map            = get_directorist_option('display_address_map', 1);
        $display_direction_map          = get_directorist_option('display_direction_map', 1);

        $disable_single_listing         = get_directorist_option('disable_single_listing', false);
        $disable_single_listing         = ( $disable_single_listing === true || $disable_single_listing === '1' ) ? true : false;

        wp_localize_script( 'leaflet-subgroup-realworld', 'atbdp_lat_lon', array(
                'lat'=>$manual_lat,
                'lon'=>$manual_lng,
        ) );
        if(!empty($listing_prv_img)) {

            $prv_image   = atbdp_get_image_source($listing_prv_img, 'large');

        }
        if(!empty($listing_img[0])) {

            $default_img = atbdp_image_cropping(ATBDP_PUBLIC_ASSETS . 'images/grid.jpg', $crop_width, $crop_height, true, 100)['url'];;
            $gallery_img = atbdp_get_image_source($listing_img[0], 'medium');

        }
        $html = '';
        if(!empty($display_map_info) && (!empty($display_image_map) || !empty($display_title_map) || $display_address_map) || !empty($display_direction_map)) {
            $html .= "<div class='atbdp-body atbdp-map embed-responsive embed-responsive-16by9 atbdp-margin-bottom'>";
            if (!empty($display_image_map)) {
                $html .= "<div class='media-left'>";
                $html .= ( !$disable_single_listing ) ? "<a href='" . get_the_permalink() . "'>" : '';
                $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                if (!empty($listing_prv_img)) {
                    $html .= "<img src='" . esc_url($prv_image) . "' alt='" . esc_html(stripslashes(get_the_title())) . "'>";
                }
                if (!empty($listing_img[0]) && empty($listing_prv_img)) {
                    $html .= "<img src='" . esc_url($gallery_img) . "' alt='" . esc_html(stripslashes(get_the_title())) . "'>";
                }
                if (empty($listing_img[0]) && empty($listing_prv_img)) {
                    $html .= "<img src='" . $default_image . "' alt='" . esc_html(stripslashes(get_the_title())) . "'>";
                }
                $html .= ( !$disable_single_listing ) ? "</a>" : '';
                $html .= "</div>";
            }
            $html .= "<div class='media-body'>";
            if (!empty($display_title_map)) {
                $html .= "<div class='atbdp-listings-title-block'>";
                
                if ( !$disable_single_listing ) {
                    $html .= "<h3 class='atbdp-no-margin'><a href='" . get_the_permalink() . "'>" . get_the_title() . "</a></h3>";
                } else {
                    $html .= "<h3 class='atbdp-no-margin'>" . get_the_title() . "</h3>";
                }
                
                $html .= "</div>";
            }
            if (!empty($address)) {
                if (!empty($display_address_map)) {
                    $html .= "<div class='osm-iw-location'><span class='" . atbdp_icon_type() . "-map-marker'></span> <a href='' class='map-info-link'>" . $address . "</a></div>";
                }
                if (!empty($display_direction_map)) {
                    $html .= "<div class='osm-iw-get-location'><a href='http://www.google.com/maps?daddr=" . $manual_lat . "," . $manual_lng . "' target='_blank'>" . __('Get Direction', 'directorist') . "</a> <span class='" . atbdp_icon_type() . "-arrow-right'></span></div>";
                }
            }
            $html .= "</div>";
            $html .= "</div>";
        }
        if(!empty($manual_lat) && !empty($manual_lat)) {
        ?>
        [<?php echo !empty($manual_lat) ? $manual_lat : '';?>, <?php echo !empty($manual_lng) ? $manual_lng : '';?>, `<?php echo !empty($html) ? $html : '';?>`],
        <?php } endwhile; ?>
    ];
   <?php

   $path = ATBDP_URL . 'templates/front-end/all-listings/maps/openstreet/js/subGroup-markercluster-controlLayers-realworld.388.js';

   if(empty($display_map_info) && (empty($display_image_map) || empty($display_title_map) || empty($display_address_map) || empty($display_direction_map))) {
   ?>
    const setIntForIcon = setInterval(() => {
        if(jQuery('.leaflet-marker-icon').length){

           jQuery('.leaflet-pane.leaflet-popup-pane').hide();
            clearInterval(setIntForIcon)
        }

    },1000);
    <?php } ?>

    bundle1.fillPlaceholders();
    var localVersion = bundle1.getLibVersion('leaflet.featuregroup.subgroup', 'local');
    if (localVersion) {
        localVersion.checkAssetsAvailability(true)
            .then(function () {
                load();
            })
            .catch(function () {
                var version102 = bundle1.getLibVersion('leaflet.featuregroup.subgroup', '1.0.2');
                if (version102) {
                    version102.defaultVersion = true;
                }
                load();
            });
    } else {
        load();
    }
    function load() {
        var url = window.location.href;
        var urlParts = URI.parse(url);
        var queryStringParts = URI.parseQuery(urlParts.query);
        var list = bundle1.getAndSelectVersionsAssetsList(queryStringParts);
        
        console.log( '<?php echo $path; ?>' );

        list.push({
            type: 'script',
            path: '<?php echo $path;?>'
        });
        loadJsCss.list(list, {
            delayScripts: 500 // Load scripts after stylesheets, delayed by this duration (in ms).
        });
    }
    /*setTimeout(() => {
        console.log(jQuery('.leaflet-popup-content'))
    }, 100);*/
</script>
<?php while( $all_listings->have_posts() ) : $all_listings->the_post();
    $cats                           = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
    $font_type = get_directorist_option('font_type','line');
    if(!empty($cats)){
        $cat_icon                       = get_cat_icon($cats[0]->term_id);
    }
    $cat_icon = !empty($cat_icon) ? $cat_icon : 'fa-map-marker';
    $icon_type = substr($cat_icon, 0,2);
    $fa_or_la = ('la' == $icon_type) ? "la " : "fa ";
    $cat_icon = ('none' == $cat_icon) ? 'fa fa-map-marker' : $fa_or_la . $cat_icon ;
?>
<input type="hidden" value="<?php echo !empty($cats) ? $cat_icon : 'fa fa-map-marker';?>" class="openstreet_icon">
<?php endwhile;?>
<div id="map" style="width: 100%; height: <?php echo !empty($listings_map_height)?$listings_map_height:'';?>px;"></div>
