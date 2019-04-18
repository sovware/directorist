(function ($) {
//map view
/**
 *  Render a Google Map onto the selected jQuery element.
 *
 *  @since    5.0.0
 */
function atbdp_rander_map( $el ) {

    $el.addClass( 'atbdp-map-loaded' );

    // var
    var $markers = $el.find('.marker');

    // vars
    var args = {
        zoom	    : parseInt( atbdp_map.zoom ),
        center	    : new google.maps.LatLng( 0, 0 ),
        mapTypeId   : google.maps.MapTypeId.ROADMAP,
        zoomControl : true,
        scrollwheel : false
    };

    // create map
    var map = new google.maps.Map( $el[0], args );

    // add a markers reference
    map.markers = [];

    // set map type
    map.type = $el.data('type');

    // add markers
    $markers.each(function() {
        atbdp_add_marker( $( this ), map );
    });

    // center map
    atbdp_center_map( map );

    // update map when contact details fields are updated in the custom post type 'acadp_listings'
    if( map.type == 'markerclusterer' ) {
        var markerCluster = new MarkerClusterer( map, map.markers, { imagePath: atbdp_map.plugin_url+'public/assets/images/m' } );

    }

};

/**
 *  Add a marker to the selected Google Map.
 *
 *  @since    1.0.0
 */
function atbdp_add_marker( $marker, map ) {

    // var
    var latlng = new google.maps.LatLng( $marker.data( 'latitude' ), $marker.data( 'longitude' ) );

    // check to see if any of the existing markers match the latlng of the new marker
    if( map.markers.length ) {
        for( var i = 0; i < map.markers.length; i++ ) {
            var existing_marker = map.markers[i];
            var pos = existing_marker.getPosition();

            // if a marker already exists in the same position as this marker
            if( latlng.equals( pos ) ) {
                // update the position of the coincident marker by applying a small multipler to its coordinates
                var latitude  = latlng.lat() + ( Math.random() - .5 ) / 1500; // * (Math.random() * (max - min) + min);
                var longitude = latlng.lng() + ( Math.random() - .5 ) / 1500; // * (Math.random() * (max - min) + min);
                latlng = new google.maps.LatLng( latitude, longitude );
            }
        }
    }

    // create marker
    var marker = new google.maps.Marker({
        position  : latlng,
        map		  : map
    });

    // add to array
    map.markers.push( marker );

    // if marker contains HTML, add it to an infoWindow
    if( $marker.html() ) {
        // create info window
        var infowindow = new google.maps.InfoWindow({
            content	: $marker.html()
        });

        // show info window when marker is clicked
        google.maps.event.addListener(marker, 'click', function() {

            infowindow.open( map, marker );

        });
    };


};

/**
 *  Center the map, showing all markers attached to this map.
 *
 *  @since    1.0.0
 */
function atbdp_center_map( map ) {

    // vars
    var bounds = new google.maps.LatLngBounds();

    // loop through all markers and create bounds
    $.each( map.markers, function( i, marker ){

        var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );

        bounds.extend( latlng );

    });

    // only 1 marker?
    if( map.markers.length == 1 ) {

        // set center of map
        map.setCenter( bounds.getCenter() );
        map.setZoom( parseInt( atbdp_map.zoom ) );

    } else {

        // fit to bounds
        map.fitBounds( bounds );

    };

};
// render map in the custom post
$( '.atbdp-map' ).each(function() {
    atbdp_rander_map( $( this ) );
});


})(jQuery);