/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
/*!******************************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/single-listing/google-map-widget.js ***!
  \******************************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initSingleMapWidget: function() { return /* binding */ initSingleMapWidget; }
/* harmony export */ });
/* Widget google map */
var $ = jQuery;

// Single Listing Map Initialize
function initSingleMapWidget() {
  if (typeof google === "undefined" || !google.maps || !google.maps.Marker || !google.maps.OverlayView) {
    return;
  }
  if ($('#gmap-widget').length) {
    var searchIcon = "<i class=\"directorist-icon-mask\"></i>";
    var markerShape = document.createElement("div");
    markerShape.className = "atbd_map_shape";
    markerShape.innerHTML = searchIcon;
    var inherits = function inherits(childCtor, parentCtor) {
      /** @constructor */
      function tempCtor() {}
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
      this.MarkerLabel && this.MarkerLabel.setMap.apply(this.MarkerLabel, arguments);
    };

    // Marker Label Overlay
    var MarkerLabel = function MarkerLabel(options) {
      var self = this;
      this.setValues(options);

      // Create the label container
      this.div = document.createElement('div');
      this.div.className = 'map-icon-label';

      // Trigger the marker click handler if clicking on the label
      google.maps.event.addListener(this.div, 'click', function (e) {
        e.stopPropagation && e.stopPropagation();
        google.maps.event.trigger(self.marker, 'click');
      });
    };

    // Create MarkerLabel Object
    MarkerLabel.prototype = new google.maps.OverlayView();

    // Marker Label onAdd
    MarkerLabel.prototype.onAdd = function () {
      var pane = this.getPanes().overlayImage.appendChild(this.div);
      var self = this;
      this.listeners = [google.maps.event.addListener(this, 'position_changed', function () {
        self.draw();
      }), google.maps.event.addListener(this, 'text_changed', function () {
        self.draw();
      }), google.maps.event.addListener(this, 'zindex_changed', function () {
        self.draw();
      })];
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
      div.style.left = position.x - div.offsetWidth / 2 + 'px';
      div.style.top = position.y - div.offsetHeight + 'px';
    };

    // initialize all vars here to avoid hoisting related misunderstanding.
    var map, info_window, saved_lat_lng;

    // Localized Data
    var map_container = localized_data_widget.map_container_id ? localized_data_widget.map_container_id : 'gmap';
    var loc_default_latitude = parseFloat(localized_data_widget.default_latitude);
    var loc_default_longitude = parseFloat(localized_data_widget.default_longitude);
    var loc_manual_lat = parseFloat(localized_data_widget.manual_lat);
    var loc_manual_lng = parseFloat(localized_data_widget.manual_lng);
    var loc_map_zoom_level = parseInt(localized_data_widget.map_zoom_level);
    var display_map_info = localized_data_widget.display_map_info;
    var info_content = mapData.info_content;
    loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
    loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;
    $manual_lat = $('#manual_lat');
    $manual_lng = $('#manual_lng');
    saved_lat_lng = {
      lat: loc_manual_lat,
      lng: loc_manual_lng
    };

    // create an info window for map
    if (display_map_info) {
      info_window = new google.maps.InfoWindow({
        content: info_content,
        maxWidth: 400 /*Add configuration for max width*/
      });
    }
    var marker = new google.maps.marker.AdvancedMarkerElement({
      map: map,
      position: saved_lat_lng,
      content: markerShape
    });

    // create an info window for map
    marker.addListener('click', function () {
      if (display_map_info) {
        display_map_info = false;
      } else {
        info_window.close();
        display_map_info = true;
      }
    });
    function initMap() {
      /* Create new map instance*/
      map = new google.maps.Map(document.getElementById(map_container), {
        zoom: loc_map_zoom_level,
        center: saved_lat_lng,
        mapId: "single_listing_map_widget"
      });
      var marker = new google.maps.marker.AdvancedMarkerElement({
        map: map,
        position: saved_lat_lng,
        content: markerShape
      });
      if (display_map_info) {
        marker.addListener('click', function () {
          if (info_window.getMap()) {
            info_window.close(); // If already open, close it
          } else {
            info_window.open(map, marker); // Otherwise, open it
          }
        });
      }
    }
    $(document).ready(function () {
      initMap();
      //Convert address tags to google map links -
      $('address').each(function () {
        var link = "<a href='http://maps.google.com/maps?q=" + encodeURIComponent($(this).text()) + "' target='_blank'>" + $(this).text() + "</a>";
        $(this).html(link);
      });
    });
  }
}
$(document).ready(function () {
  initSingleMapWidget();
});

// Single Listing Map on Elementor EditMode
$(window).on('elementor/frontend/init', function () {
  setTimeout(function () {
    if ($('body').hasClass('elementor-editor-active')) {
      initSingleMapWidget();
    }
  }, 3000);
});
$('body').on('click', function (e) {
  if ($('body').hasClass('elementor-editor-active') && e.target.nodeName !== 'A' && e.target.nodeName !== 'BUTTON') {
    initSingleMapWidget();
  }
});
/******/ })()
;
//# sourceMappingURL=single-listing-google-map-widget.js.map