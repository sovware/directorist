import { get_dom_data } from './../lib/helper';
const atbdp_map = get_dom_data('atbdp_map');

// Define Marker Shapes
const MAP_PIN =
    'M0-48c-9.8 0-17.7 7.8-17.7 17.4 0 15.5 17.7 30.6 17.7 30.6s17.7-15.4 17.7-30.6c0-9.6-7.9-17.4-17.7-17.4z';

const inherits = function (childCtor, parentCtor) {
    /** @constructor */
    function tempCtor() { }

    tempCtor.prototype = parentCtor.prototype;
    childCtor.superClass_ = parentCtor.prototype;
    childCtor.prototype = new tempCtor();
    childCtor.prototype.constructor = childCtor;
};

function Marker(options) {
    google.maps.Marker.apply(this, arguments);

    if (options.map_icon_label) {
        this.MarkerLabel = new MarkerLabel({
            map: this.map,
            marker: this,
            text: options.map_icon_label,
        });
        this.MarkerLabel.bindTo('position', this, 'position');
    }
}

// Apply the inheritance
inherits(Marker, google.maps.Marker);

// Custom Marker SetMap
Marker.prototype.setMap = function () {
    google.maps.Marker.prototype.setMap.apply(this, arguments);
    this.MarkerLabel && this.MarkerLabel.setMap.apply(this.MarkerLabel, arguments);
};

// Marker Label Overlay
var MarkerLabel = function (options) {
    const self = this;
    this.setValues(options);

    // Create the label container
    this.div = document.createElement('div');
    this.div.className = 'map-icon-label';

    // Trigger the marker click handler if clicking on the label
    google.maps.event.addDomListener(this.div, 'click', function (e) {
        e.stopPropagation && e.stopPropagation();
        google.maps.event.trigger(self.marker, 'click');
    });
};

// Create MarkerLabel Object
MarkerLabel.prototype = new google.maps.OverlayView();

// Marker Label onAdd
MarkerLabel.prototype.onAdd = function () {
    const pane = this.getPanes().overlayImage.appendChild(this.div);
    const self = this;

    this.listeners = [
        google.maps.event.addListener(this, 'position_changed', function () {
            self.draw();
        }),
        google.maps.event.addListener(this, 'text_changed', function () {
            self.draw();
        }),
        google.maps.event.addListener(this, 'zindex_changed', function () {
            self.draw();
        }),
    ];
};

// Marker Label onRemove
MarkerLabel.prototype.onRemove = function () {
    this.div.parentNode.removeChild(this.div);

    for (let i = 0, I = this.listeners.length; i < I; ++i) {
        google.maps.event.removeListener(this.listeners[i]);
    }
};

// Implement draw
MarkerLabel.prototype.draw = function () {
    const projection = this.getProjection();
    const position = projection.fromLatLngToDivPixel(this.get('position'));
    const { div } = this;

    this.div.innerHTML = this.get('text').toString();

    div.style.zIndex = this.get('zIndex'); // Allow label to overlay marker
    div.style.position = 'absolute';
    div.style.display = 'block';
    div.style.left = `${position.x - div.offsetWidth / 2}px`;
    div.style.top = `${position.y - div.offsetHeight}px`;
};

(function ($) {
    // map view
    /**
     *  Render a Google Map onto the selected jQuery element.
     *
     *  @since    5.0.0
     */
    const at_icon = [];

    function atbdp_rander_map($el) {
        $el.addClass('atbdp-map-loaded');

        // var
        const $markers = $el.find('.marker');

        // vars
        const args = {
            zoom: parseInt(atbdp_map.zoom),
            center: new google.maps.LatLng(0, 0),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoomControl: true,
            scrollwheel: false,
            gestureHandling: 'cooperative',
            averageCenter: true,
            scrollWheelZoom: 'center',
        };

        // create map
        const map = new google.maps.Map($el[0], args);

        // add a markers reference
        map.markers = [];

        // set map type
        map.type = $el.data('type');

        const infowindow = new google.maps.InfoWindow({ content: '' });
        // add markers
        $markers.each(function () {
            atbdp_add_marker($(this), map, infowindow);
        });

        // center map
        atbdp_center_map(map);

        // update map when contact details fields are updated in the custom post type 'acadp_listings'
        /* const mcOptions = {
            imagePath: atbdp_map.plugin_url+'assets/images/m',
            //cssClass: 'marker-cluster-shape',
            styles:[{
                url: atbdp_map.plugin_url+'assets/images/m1.png',
                width: 53,
                height:53,
                textColor:"#ffffff",
            }]
        }; */
        var mcOptions = new MarkerClusterer(map, [], {
            imagePath: atbdp_map.plugin_url+'assets/images/m'
        });
        mcOptions.setStyles(mcOptions.getStyles().map(function (style) {
            style.textColor = '#fff';
            return style;
        }));

        if (map.type === 'markerclusterer') {
            //const markerCluster = new MarkerClusterer(map, map.markers, mcOptions);
            mcOptions.addMarkers(map.markers);
        }
    }

    /**
     *  Add a marker to the selected Google Map.
     *
     *  @since    1.0.0
     */
    function atbdp_add_marker($marker, map, infowindow) {
        // var
        let latlng = new google.maps.LatLng($marker.data('latitude'), $marker.data('longitude'));
        // check to see if any of the existing markers match the latlng of the new marker
        if (map.markers.length) {
            for (let i = 0; i < map.markers.length; i++) {
                const existing_marker = map.markers[i];
                const pos = existing_marker.getPosition();

                // if a marker already exists in the same position as this marker
                if (latlng.equals(pos)) {
                    // update the position of the coincident marker by applying a small multipler to its coordinates
                    const latitude = latlng.lat() + (Math.random() - 0.5) / 1500; // * (Math.random() * (max - min) + min);
                    const longitude = latlng.lng() + (Math.random() - 0.5) / 1500; // * (Math.random() * (max - min) + min);
                    latlng = new google.maps.LatLng(latitude, longitude);
                }
            }
        }

        const icon = $marker.data('icon');
        const marker = new Marker({
            position: latlng,
            map,
            icon: {
                path: MAP_PIN,
                fillColor: 'transparent',
                fillOpacity: 1,
                strokeColor: '',
                strokeWeight: 0,
            },
            map_icon_label:
                icon !== undefined && `<div class="atbd_map_shape"><i class="${icon}"></i></div>`,
        });

        // add to array
        map.markers.push(marker);
        // if marker contains HTML, add it to an infoWindow
        if ($marker.html()) {
            // map info window close button
            google.maps.event.addListener(infowindow, 'domready', function () {
                const closeBtn = $('.iw-close-btn').get();
                google.maps.event.addDomListener(closeBtn[0], 'click', function () {
                    infowindow.close();
                });
            });

            // show info window when marker is clicked
            google.maps.event.addListener(marker, 'click', function () {
                if (atbdp_map.disable_info_window === 'no') {
                    infowindow.setContent($marker.html());
                    infowindow.open(map, marker);
                }
            });
        }
    }

    /**
     *  Center the map, showing all markers attached to this map.
     *
     *  @since    1.0.0
     */
    function atbdp_center_map(map) {
        // vars
        const bounds = new google.maps.LatLngBounds();

        // loop through all markers and create bounds
        $.each(map.markers, function (i, marker) {
            const latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());

            bounds.extend(latlng);
        });

        // only 1 marker?
        if (map.markers.length !== 1) {
            // set center of map
            map.setCenter(bounds.getCenter());
            map.setZoom(parseInt(atbdp_map.zoom));
        } else {
            // fit to bounds
            map.fitBounds(bounds);
        }
    }

    function setup_info_window() {
        const abc = document.querySelectorAll('div');

        abc.forEach(function (el, index) {
            if (el.innerText === 'atgm_marker') {
                // console.log(at_icon)
                el.innerText = ' ';
                el.innerHTML = `<i class="la ${at_icon} atbd_map_marker_icon"></i>`;
            }
            // ${$marker.data('icon')}
        });

        document.querySelectorAll('div').forEach((el1, index) => {
            if (el1.style.backgroundImage.split('/').pop() === 'm1.png")') {
                el1.addEventListener('click', () => {
                    setInterval(() => {
                        const abc = document.querySelectorAll('div');
                        abc.forEach(function (el, index) {
                            if (el.innerText === 'atgm_marker') {
                                el.innerText = ' ';
                                el.innerHTML = `<i class="la ${at_icon} atbd_map_marker_icon"></i>`;
                            }
                        });
                    }, 100);
                });
            }
        });
    }


    function setup_map() {
        // render map in the custom post
        $('.atbdp-map').each(function () {
            atbdp_rander_map( $( this ) );
        });
    }

    window.addEventListener( 'load', setup_map );
    window.addEventListener( 'load', setup_info_window );

    window.addEventListener( 'directorist-reload-listings-map-archive', setup_map );
    window.addEventListener( 'directorist-reload-listings-map-archive', setup_info_window );


})(jQuery);
