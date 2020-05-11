var MAP_PIN = 'M0-48c-9.8 0-17.7 7.8-17.7 17.4 0 15.5 17.7 30.6 17.7 30.6s17.7-15.4 17.7-30.6c0-9.6-7.9-17.4-17.7-17.4z';

var inherits = function (childCtor, parentCtor) {
    /** @constructor */
    function tempCtor() {
    }

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
            text: options.map_icon_label
        });
        this.MarkerLabel.bindTo('position', this, 'position');
    }
}

// Apply the inheritance
inherits(Marker, google.maps.Marker);

// Custom Marker SetMap
Marker.prototype.setMap = function () {
    google.maps.Marker.prototype.setMap.apply(this, arguments);
    (this.MarkerLabel) && this.MarkerLabel.setMap.apply(this.MarkerLabel, arguments);
};

// Marker Label Overlay
var MarkerLabel = function (options) {
    var self = this;
    this.setValues(options);

    // Create the label container
    this.div = document.createElement('div');
    this.div.className = 'map-icon-label';

    // Trigger the marker click handler if clicking on the label
    google.maps.event.addDomListener(this.div, 'click', function (e) {
        (e.stopPropagation) && e.stopPropagation();
        google.maps.event.trigger(self.marker, 'click');
    });
};

// Create MarkerLabel Object
MarkerLabel.prototype = new google.maps.OverlayView;

// Marker Label onAdd
MarkerLabel.prototype.onAdd = function () {
    var pane = this.getPanes().overlayImage.appendChild(this.div);
    var self = this;

    this.listeners = [
        google.maps.event.addListener(this, 'position_changed', function () {
            self.draw();
        }),
        google.maps.event.addListener(this, 'text_changed', function () {
            self.draw();
        }),
        google.maps.event.addListener(this, 'zindex_changed', function () {
            self.draw();
        })
    ];
};

// Marker Label onRemove
MarkerLabel.prototype.onRemove = function () {
    this.div.parentNode.removeChild(this.div);

    for (var i = 0, I = this.listeners.length; i < I; ++i) {
        google.maps.event.removeListener(this.listeners[i]);
    }
};

// Implement draw
MarkerLabel.prototype.draw = function () {
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