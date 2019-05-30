<div id="map" style="width: 100%"></div>

<?php


?>
<script>

    var addressPoints = [
        <?php while( $all_listings->have_posts() ) : $all_listings->the_post();
        global $post;
        $manual_lat                     = get_post_meta($post->ID, '_manual_lat', true);
        $manual_lng                     = get_post_meta($post->ID, '_manual_lng', true);

        ?>
        [<?php echo !empty($manual_lat) ? $manual_lat : '';?>, <?php echo !empty($manual_lng) ? $manual_lng : '';?>, "address"],
        <?php endwhile;?>
    ];

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
        list.push({
            type: 'script',
            path: '<?php echo ATBDP_URL . 'templates/front-end/all-listings/maps/openstreet/js/subGroup-markercluster-controlLayers-realworld.388.js';?>'
        });
        loadJsCss.list(list, {
            delayScripts: 500 // Load scripts after stylesheets, delayed by this duration (in ms).
        });
    }
</script>