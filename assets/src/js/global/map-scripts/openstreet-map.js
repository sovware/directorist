var $ = jQuery;
const mapData = JSON.parse($('#map').attr('data-options'));

window.addEventListener('load', setup_map);
window.addEventListener('directorist-reload-listings-map-archive', setup_map);

function setup_map() {
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