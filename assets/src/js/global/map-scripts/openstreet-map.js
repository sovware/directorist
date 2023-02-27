;
(function () {
    // DOM Mutation observer
    const targetNode = document.querySelector('.directorist-archive-contents');
    if(targetNode){
        function initObserver() {
            const observer = new MutationObserver( initMap );
            targetNode && observer.observe( targetNode, { childList: true } );
        }

        window.addEventListener('DOMContentLoaded', initObserver );
    }
    window.addEventListener('DOMContentLoaded', initMap);
    window.addEventListener('directorist-reload-listings-map-archive', initMap);

    function initMap() {
        var $ = jQuery;
        let mapData;
        $('#map').length ? mapData = JSON.parse($('#map').attr('data-options')) : '';

        function setup_map() {
            bundle1.fillPlaceholders();
            var localVersion = bundle1.getLibVersion('leaflet.featuregroup.subgroup', 'local');

            if (localVersion) {
                localVersion.checkAssetsAvailability(true)
                    .then(function () {
                        mapData !== undefined ? load() : '';
                    })
                    .catch(function () {
                        var version102 = bundle1.getLibVersion('leaflet.featuregroup.subgroup', '1.0.2');
                        if (version102) {
                            version102.defaultVersion = true;
                        }
                        mapData !== undefined ? load() : '';
                    });
            } else {
                mapData !== undefined ? load() : '';
            }
        }

        function load() {
            var url = window.location.href;
            var urlParts = URI.parse(url);
            var queryStringParts = URI.parseQuery(urlParts.query);
            var list = bundle1.getAndSelectVersionsAssetsList(queryStringParts);
            list.push({
                type: 'script',
                path: mapData.openstreet_script,
            });
            loadJsCss.list(list, {
                delayScripts: 500 // Load scripts after stylesheets, delayed by this duration (in ms).
            });
        }
        setup_map();
    }
})();

/* Add listing OSMap */
import './add-listing/openstreet-map';

/* Single listing OSMap */
import './single-listing/openstreet-map';

/* Widget OSMap */
import './single-listing/openstreet-map-widget';