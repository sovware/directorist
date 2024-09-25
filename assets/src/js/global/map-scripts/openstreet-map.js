(function () {
    // DOM Mutation observer
    const targetNode = document.querySelector('.directorist-archive-contents');
    if(targetNode){
        function initObserver() {
            const observer = new MutationObserver( initMap );
            targetNode && observer.observe( targetNode, { childList: true } );
        }

        window.addEventListener('load', initObserver );
    }
    window.addEventListener('load', initMap);
    window.addEventListener('directorist-reload-listings-map-archive', initMap);

    // Map Initialize 
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

            function toggleFullscreen() {
                var mapContainer = document.getElementById('map');
                var fullScreenEnable = document.querySelector('#gmap_full_screen_button .fullscreen-enable');
                var fullScreenDisable = document.querySelector('#gmap_full_screen_button .fullscreen-disable');
                
                if (!document.fullscreenElement && !document.webkitFullscreenElement) {
                    if (mapContainer.requestFullscreen) {
                        mapContainer.requestFullscreen();
                        
                        fullScreenEnable.style.display="none";
                        fullScreenDisable.style.display="block";
                    } else if (mapContainer.webkitRequestFullscreen) {
                        mapContainer.webkitRequestFullscreen();
                    }
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                        
                        fullScreenDisable.style.display="none";
                        fullScreenEnable.style.display="block";
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    }
                }
            }

            $('body').on('click', '#gmap_full_screen_button', function (event) {
                event.preventDefault();
                toggleFullscreen();
            });
            
        }
        setup_map();
    }

    const $ = jQuery;

    // Map on Elementor Edit Mode
    $(window).on('elementor/frontend/init', function () {
        setTimeout(function() {
            if ($('body').hasClass('elementor-editor-active')) {
                initMap();
            }
        }, 3000);

    });

    $('body').on('click', function (e) {
        if ($('body').hasClass('elementor-editor-active')  && (e.target.nodeName !== 'A' && e.target.nodeName !== 'BUTTON')) {
            initMap();
        }
    });

})();

/* Add listing OSMap */
import './add-listing/openstreet-map';

/* Single listing OSMap */
import './single-listing/openstreet-map';

/* Widget OSMap */
import './single-listing/openstreet-map-widget';