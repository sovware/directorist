
    // Define Marker Shapes
    var MAP_PIN = 'M0-48c-9.8 0-17.7 7.8-17.7 17.4 0 15.5 17.7 30.6 17.7 30.6s17.7-15.4 17.7-30.6c0-9.6-7.9-17.4-17.7-17.4z';
    var SQUARE_PIN = 'M22-48h-44v43h16l6 5 6-5h16z';
    var SHIELD = 'M18.8-31.8c.3-3.4 1.3-6.6 3.2-9.5l-7-6.7c-2.2 1.8-4.8 2.8-7.6 3-2.6.2-5.1-.2-7.5-1.4-2.4 1.1-4.9 1.6-7.5 1.4-2.7-.2-5.1-1.1-7.3-2.7l-7.1 6.7c1.7 2.9 2.7 6 2.9 9.2.1 1.5-.3 3.5-1.3 6.1-.5 1.5-.9 2.7-1.2 3.8-.2 1-.4 1.9-.5 2.5 0 2.8.8 5.3 2.5 7.5 1.3 1.6 3.5 3.4 6.5 5.4 3.3 1.6 5.8 2.6 7.6 3.1.5.2 1 .4 1.5.7l1.5.6c1.2.7 2 1.4 2.4 2.1.5-.8 1.3-1.5 2.4-2.1.7-.3 1.3-.5 1.9-.8.5-.2.9-.4 1.1-.5.4-.1.9-.3 1.5-.6.6-.2 1.3-.5 2.2-.8 1.7-.6 3-1.1 3.8-1.6 2.9-2 5.1-3.8 6.4-5.3 1.7-2.2 2.6-4.8 2.5-7.6-.1-1.3-.7-3.3-1.7-6.1-.9-2.8-1.3-4.9-1.2-6.4z';
    var ROUTE = 'M24-28.3c-.2-13.3-7.9-18.5-8.3-18.7l-1.2-.8-1.2.8c-2 1.4-4.1 2-6.1 2-3.4 0-5.8-1.9-5.9-1.9l-1.3-1.1-1.3 1.1c-.1.1-2.5 1.9-5.9 1.9-2.1 0-4.1-.7-6.1-2l-1.2-.8-1.2.8c-.8.6-8 5.9-8.2 18.7-.2 1.1 2.9 22.2 23.9 28.3 22.9-6.7 24.1-26.9 24-28.3z';
    var SQUARE = 'M-24-48h48v48h-48z';
    var SQUARE_ROUNDED = 'M24-8c0 4.4-3.6 8-8 8h-32c-4.4 0-8-3.6-8-8v-32c0-4.4 3.6-8 8-8h32c4.4 0 8 3.6 8 8v32z';

    var inherits = function(childCtor, parentCtor) {
        /** @constructor */
        function tempCtor() {};
        tempCtor.prototype = parentCtor.prototype;
        childCtor.superClass_ = parentCtor.prototype;
        childCtor.prototype = new tempCtor();
        childCtor.prototype.constructor = childCtor;
    };

    function Marker(options){
        google.maps.Marker.apply(this, arguments);

        if (options.map_icon_label) {
            this.MarkerLabel = new MarkerLabel({
                map: this.map,
                marker: this,
                text: options.map_icon_label
            });
            this.MarkerLabel.bindTo('position', this, 'position');
        }
    }

// Apply the inheritance
    inherits(Marker, google.maps.Marker);

// Custom Marker SetMap
    Marker.prototype.setMap = function() {
        google.maps.Marker.prototype.setMap.apply(this, arguments);
        (this.MarkerLabel) && this.MarkerLabel.setMap.apply(this.MarkerLabel, arguments);
    };

// Marker Label Overlay
    var MarkerLabel = function(options) {
        var self = this;
        this.setValues(options);

        // Create the label container
        this.div = document.createElement('div');
        this.div.className = 'map-icon-label';

        // Trigger the marker click handler if clicking on the label
        google.maps.event.addDomListener(this.div, 'click', function(e){
            (e.stopPropagation) && e.stopPropagation();
            google.maps.event.trigger(self.marker, 'click');
        });
    };

// Create MarkerLabel Object
    MarkerLabel.prototype = new google.maps.OverlayView;

// Marker Label onAdd
    MarkerLabel.prototype.onAdd = function() {
        var pane = this.getPanes().overlayImage.appendChild(this.div);
        var self = this;

        this.listeners = [
            google.maps.event.addListener(this, 'position_changed', function() { self.draw(); }),
            google.maps.event.addListener(this, 'text_changed', function() { self.draw(); }),
            google.maps.event.addListener(this, 'zindex_changed', function() { self.draw(); })
        ];
    };

// Marker Label onRemove
    MarkerLabel.prototype.onRemove = function() {
        this.div.parentNode.removeChild(this.div);

        for (var i = 0, I = this.listeners.length; i < I; ++i) {
            google.maps.event.removeListener(this.listeners[i]);
        }
    };

// Implement draw
    MarkerLabel.prototype.draw = function() {
        var projection = this.getProjection();
        var position = projection.fromLatLngToDivPixel(this.get('position'));
        var div = this.div;

        this.div.innerHTML = this.get('text').toString();

        div.style.zIndex = this.get('zIndex'); // Allow label to overlay marker
        div.style.position = 'absolute';
        div.style.display = 'block';
        div.style.left = (position.x - (div.offsetWidth / 2)) + 'px';
        div.style.top = (position.y - div.offsetHeight) + 'px';
    };


(function ($) {
//map view
/**
 *  Render a Google Map onto the selected jQuery element.
 *
 *  @since    5.0.0
 */
var at_icon = [];
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

    //console.log($marker.data( 'icon' ));

    /*var ico = $marker.data( 'icon' );
    var marker = new google.maps.Marker({
        position  : latlng,
        map		  : map,
        icon: ' ',
        label: {
            fontFamily: "FontAwesome",
            text: eval("'\\u"+'f024'+"'")
        }
    });*/
    var icon = $marker.data( 'icon' );
    console.log(icon);
    var marker = new Marker({
        position  : latlng,
        map		  : map,
        icon: {
            path: MAP_PIN,
            fillColor: '#9C27B0',
            fillOpacity: 1,
            strokeColor: '',
            strokeWeight: 0
        },
        map_icon_label: '<i class="fa '+icon+'"></i>'
    });


    // create marker



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
            if (atbdp_map.disable_info_window === 'no'){
                infowindow.open( map, marker );
            }
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
    if( map.markers.length !== 1 ) {

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
    window.addEventListener("load", () => {

            var abc = document.querySelectorAll('div');
            abc.forEach(function (el, index) {

                if(el.innerText === "atgm_marker"){
                    console.log(at_icon)
                    el.innerText = " ";
                    el.innerHTML = `<i class="la ${at_icon} atbd_map_marker_icon"></i>`;
                }
                //${$marker.data('icon')}
            });

            document.querySelectorAll('div').forEach((el1, index) => {
                if(el1.style.backgroundImage.split("/").pop() === 'm1.png")'){

                    el1.addEventListener('click', () => {
                        setInterval(() => {
                            var abc = document.querySelectorAll('div');
                            abc.forEach(function (el, index) {
                                if(el.innerText === "atgm_marker"){
                                    el.innerText = " ";
                                    el.innerHTML = `<i class="la ${at_icon} atbd_map_marker_icon"></i>`;
                                }
                            })
                        }, 100)

                    });
                }
            });


    })



})(jQuery);