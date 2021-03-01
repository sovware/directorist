var loc_data        = get_dom_data( 'loc_data' );
var atbdp_lat_lon   = get_dom_data( 'atbdp_lat_lon' );
var listings_data   = get_dom_data( 'listings_data' );

console.log( { loc_data, atbdp_lat_lon, listings_data } );

function get_dom_data ( key ) {
    var dom_content = document.body.innerHTML;

    if ( ! dom_content.length ) { return ''; }

    var pattern = new RegExp("(<!-- directorist-dom-data::" + key + "\\s)(.+)(\\s-->)");
    var terget_content = pattern.exec( dom_content );

    if ( ! terget_content ) { return ''; }
    if ( typeof terget_content[2] === 'undefined' ) { return ''; }
    
    var dom_data = JSON.parse( terget_content[2] );

    if ( ! dom_data ) { return ''; }

    return dom_data;
}
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
        path: loc_data.script_path
    });
    loadJsCss.list(list, {
        delayScripts: 500 // Load scripts after stylesheets, delayed by this duration (in ms).
    });
}