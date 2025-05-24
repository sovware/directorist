/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/js/global/map-scripts/add-listing/google-map.js":
/*!********************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/add-listing/google-map.js ***!
  \********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initAddListingMap: function() { return /* binding */ initAddListingMap; }
/* harmony export */ });
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../../../lib/helper */ "./assets/src/js/lib/helper.js");
/* Add listing google map */


var $ = jQuery;

// Add Listing Map Initialize
function initAddListingMap() {
  if (typeof google === "undefined" || !google.maps || !google.maps.Geocoder) {
    return;
  }
  if ($('#gmap').length) {
    var localized_data = (0,_lib_helper__WEBPACK_IMPORTED_MODULE_0__.get_dom_data)('map_data');

    // initialize all vars here to avoid hoisting related misunderstanding.
    var map;
    var autocomplete;
    var address_input;
    var markers;
    var $manual_lat;
    var $manual_lng;
    var saved_lat_lng;

    // Localized Data
    var loc_default_latitude = parseFloat(localized_data.default_latitude);
    var loc_default_longitude = parseFloat(localized_data.default_longitude);
    var loc_manual_lat = parseFloat(localized_data.manual_lat);
    var loc_manual_lng = parseFloat(localized_data.manual_lng);
    var loc_map_zoom_level = parseInt(localized_data.map_zoom_level);
    var searchIcon = "<i class=\"directorist-icon-mask\"></i>";
    var markerShape = document.createElement("div");
    markerShape.className = "atbd_map_shape";
    markerShape.innerHTML = searchIcon;
    loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
    loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;
    $manual_lat = $('#manual_lat');
    $manual_lng = $('#manual_lng');
    saved_lat_lng = {
      lat: loc_manual_lat,
      lng: loc_manual_lng
    };

    // default is London city
    markers = [],
    // initialize the array to keep track all the marker

    address_input = document.getElementById('address');
    if (address_input !== null) {
      address_input.addEventListener('focus', geolocate);
    }
    var geocoder = new google.maps.Geocoder();

    // This function will help to get the current location of the user
    function markerDragInit(marker) {
      marker.addListener('dragend', function (event) {
        // set the value of input field to save them to the database
        $manual_lat.val(event.latLng.lat());
        $manual_lng.val(event.latLng.lng());

        // Regenerate Address
        geocodeAddress(geocoder, map);
      });
    }

    // this function will work on sites that uses SSL, it applies to Chrome especially, other browsers may allow location sharing without securing.
    function geolocate() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
          var geolocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          };
          var circle = new google.maps.Circle({
            center: geolocation,
            radius: position.coords.accuracy
          });
          autocomplete.setBounds(circle.getBounds());
        });
      }
    }
    function initAutocomplete() {
      // Create the autocomplete object, restricting the search to geographical
      var opt = {
        types: ['geocode'],
        componentRestrictions: {
          country: directorist.restricted_countries
        }
      };
      var options = directorist.countryRestriction ? opt : {
        types: []
      };

      // location types.
      autocomplete = new google.maps.places.Autocomplete(address_input, options);

      // When the user selects an address from the dropdown, populate the necessary input fields and draw a marker
      autocomplete.addListener('place_changed', fillInAddress);
    }
    function fillInAddress() {
      // Get the place details from the autocomplete object.
      var place = autocomplete.getPlace();

      // set the value of input field to save them to the database
      $manual_lat.val(place.geometry.location.lat());
      $manual_lng.val(place.geometry.location.lng());
      map.setCenter(place.geometry.location);
      var marker = new google.maps.marker.AdvancedMarkerElement({
        map: map,
        position: place.geometry.location,
        gmpDraggable: true,
        content: markerShape,
        title: localized_data.marker_title
      });

      // Delete Previous Marker
      deleteMarker();

      // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
      markers.push(marker);
      markerDragInit(marker);
    }
    initAutocomplete(); // start google map place auto complete API call

    // Map Initialize
    function initMap() {
      /* Create new map instance */
      map = new google.maps.Map(document.getElementById('gmap'), {
        zoom: loc_map_zoom_level,
        center: saved_lat_lng,
        mapId: "add_listing_map"
      });
      var marker = new google.maps.marker.AdvancedMarkerElement({
        map: map,
        position: saved_lat_lng,
        gmpDraggable: true,
        content: markerShape,
        title: localized_data.marker_title
      });
      markers.push(marker);
      document.getElementById('generate_admin_map').addEventListener('click', function (e) {
        e.preventDefault();
        geocodeAddress(geocoder, map);
      });

      // This event listener calls addMarker() when the map is clicked.
      marker.addListener('click', function (event) {
        deleteMarker(); // at first remove previous marker and then set new marker;
        // set the value of input field to save them to the database
        $manual_lat.val(event.latLng.lat());
        $manual_lng.val(event.latLng.lng());

        // add the marker to the given map.
        addMarker(event.latLng, map);
      });
      markerDragInit(marker);
    }

    /*
        * Geocode and address using google map javascript api and then populate the input fields for storing lat and long
        * */

    function geocodeAddress(geocoder, resultsMap) {
      var lat = parseFloat(document.getElementById('manual_lat').value);
      var lng = parseFloat(document.getElementById('manual_lng').value);
      var latLng = new google.maps.LatLng(lat, lng);
      var opt = {
        location: latLng
      };
      geocoder.geocode(opt, function (results, status) {
        if (status === 'OK') {
          // set the value of input field to save them to the database
          $manual_lat.val(results[0].geometry.location.lat());
          $manual_lng.val(results[0].geometry.location.lng());
          resultsMap.setCenter(results[0].geometry.location);
          var marker = new google.maps.marker.AdvancedMarkerElement({
            map: resultsMap,
            position: results[0].geometry.location,
            gmpDraggable: true,
            content: markerShape,
            title: localized_data.marker_title
          });
          deleteMarker();
          // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
          markers.push(marker);
          address_input.value = results[0].formatted_address;
          markerDragInit(marker);
        } else {
          alert(localized_data.geocode_error_msg + status);
        }
      });
    }
    initMap();

    // adding features of creating marker manually on the map on add listing page.
    /* var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var labelIndex = 0; */

    // Adds a marker to the map.
    function addMarker(location, map) {
      // Add the marker at the clicked location, and add the next-available label;

      // from the array of alphabetical characters.
      var marker = new google.maps.marker.AdvancedMarkerElement({
        map: map,
        position: location,
        gmpDraggable: true,
        content: markerShape,
        title: localized_data.marker_title
      });

      // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
      markers.push(marker);
      markerDragInit(marker);
    }

    // Delete Marker
    $('#delete_marker').on('click', function (e) {
      e.preventDefault();
      deleteMarker();
    });
    function deleteMarker() {
      for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
      }
      markers = [];
    }
  }
}
$(document).ready(function () {
  initAddListingMap();
});

// Add Listing Map on Elementor EditMode 
$(window).on('elementor/frontend/init', function () {
  setTimeout(function () {
    if ($('body').hasClass('elementor-editor-active')) {
      initAddListingMap();
    }
  }, 3000);
});
$('body').on('click', function (e) {
  if ($('body').hasClass('elementor-editor-active') && e.target.nodeName !== 'A' && e.target.nodeName !== 'BUTTON') {
    initAddListingMap();
  }
});

/***/ }),

/***/ "./assets/src/js/global/map-scripts/single-listing/google-map-widget.js":
/*!******************************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/single-listing/google-map-widget.js ***!
  \******************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

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

/***/ }),

/***/ "./assets/src/js/global/map-scripts/single-listing/google-map.js":
/*!***********************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/single-listing/google-map.js ***!
  \***********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initSingleMap: function() { return /* binding */ initSingleMap; }
/* harmony export */ });
/* Single listing google map */
var $ = jQuery;

// Single Listing Map Initialize
function initSingleMap() {
  if (typeof google === "undefined" || !google.maps || !google.maps.Marker || !google.maps.OverlayView || !google.maps.marker.AdvancedMarkerElement) {
    return;
  }
  if ($('.directorist-single-map').length) {
    document.querySelectorAll('.directorist-single-map').forEach(function (mapElm) {
      var searchIcon = "<i class=\"directorist-icon-mask\"></i>";
      var markerShape = document.createElement("div");
      markerShape.className = "atbd_map_shape";
      markerShape.innerHTML = searchIcon;
      function Marker(options) {
        google.maps.Marker.apply(this, arguments); // Properly call parent constructor

        if (options.map_icon_label) {
          this.MarkerLabel = new MarkerLabel({
            map: this.getMap(),
            marker: this,
            text: options.map_icon_label
          });
          this.MarkerLabel.bindTo('position', this, 'position');
        }
      }

      // Ensure Marker extends google.maps.Marker
      Marker.prototype = Object.create(google.maps.Marker.prototype);
      Marker.prototype.constructor = Marker;

      // Custom Marker setMap method
      Marker.prototype.setMap = function (map) {
        google.maps.Marker.prototype.setMap.call(this, map);
        if (this.MarkerLabel) {
          this.MarkerLabel.setMap(map);
        }
      };

      // Marker Label Overlay
      function MarkerLabel(options) {
        this.setValues(options);
        this.div = document.createElement('div');
        this.div.className = 'map-icon-label';

        // Ensure marker click event works
        var self = this;
        google.maps.event.addDomListener(this.div, 'click', function (e) {
          if (e.stopPropagation) e.stopPropagation();
          google.maps.event.trigger(self.marker, 'click');
        });
      }

      // Ensure Google Maps API is loaded before extending OverlayView
      MarkerLabel.prototype = Object.create(google.maps.OverlayView.prototype);
      MarkerLabel.prototype.constructor = MarkerLabel;

      // onAdd method
      MarkerLabel.prototype.onAdd = function () {
        var pane = this.getPanes();
        if (pane) {
          pane.overlayImage.appendChild(this.div);
        }
        var self = this;
        this.listeners = [google.maps.event.addListener(this, 'position_changed', function () {
          self.draw();
        }), google.maps.event.addListener(this, 'text_changed', function () {
          self.draw();
        }), google.maps.event.addListener(this, 'zindex_changed', function () {
          self.draw();
        })];
      };

      // onRemove method
      MarkerLabel.prototype.onRemove = function () {
        if (this.div.parentNode) {
          this.div.parentNode.removeChild(this.div);
        }
        for (var i = 0; i < this.listeners.length; i++) {
          google.maps.event.removeListener(this.listeners[i]);
        }
      };

      // draw method
      MarkerLabel.prototype.draw = function () {
        var projection = this.getProjection();
        if (!projection) return; // Ensure projection is available

        var position = projection.fromLatLngToDivPixel(this.get('position'));
        if (!position) return;
        var div = this.div;
        div.innerHTML = this.get('text') || "";
        div.style.zIndex = this.get('zIndex') || "0";
        div.style.position = 'absolute';
        div.style.display = 'block';
        div.style.left = position.x - div.offsetWidth / 2 + 'px';
        div.style.top = position.y - div.offsetHeight + 'px';
      };

      // initialize all vars here to avoid hoisting related misunderstanding.
      var map, info_window, saved_lat_lng;

      // Localized Data
      var mapData = JSON.parse(mapElm.getAttribute('data-map'));
      var loc_default_latitude = parseFloat(mapData.default_latitude);
      var loc_default_longitude = parseFloat(mapData.default_longitude);
      var loc_manual_lat = parseFloat(mapData.manual_lat);
      var loc_manual_lng = parseFloat(mapData.manual_lng);
      var loc_map_zoom_level = parseInt(mapData.map_zoom_level);
      var display_map_info = mapData.display_map_info;
      var info_content = mapData.info_content;
      loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
      loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;
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
        map = new google.maps.Map(mapElm, {
          zoom: loc_map_zoom_level,
          center: saved_lat_lng,
          mapId: "single_listing_map"
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
  initSingleMap();
});

// Single Listing Map on Elementor EditMode
$(window).on('elementor/frontend/init', function () {
  setTimeout(function () {
    if ($('body').hasClass('elementor-editor-active')) {
      initSingleMap();
    }
  }, 3000);
});
$('body').on('click', function (e) {
  if ($('body').hasClass('elementor-editor-active') && e.target.nodeName !== 'A' && e.target.nodeName !== 'BUTTON') {
    initSingleMap();
  }
});

/***/ }),

/***/ "./assets/src/js/lib/helper.js":
/*!*************************************!*\
  !*** ./assets/src/js/lib/helper.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   convertToSelect2: function() { return /* binding */ convertToSelect2; },
/* harmony export */   get_dom_data: function() { return /* binding */ get_dom_data; }
/* harmony export */ });
var $ = jQuery;
function get_dom_data(selector, parent) {
  selector = '.directorist-dom-data-' + selector;
  if (!parent) {
    parent = document;
  }
  var el = parent.querySelector(selector);
  if (!el || !el.dataset.value) {
    return {};
  }
  var IS_SCRIPT_DEBUGGING = directorist && directorist.script_debugging && directorist.script_debugging == '1';
  try {
    var value = atob(el.dataset.value);
    return JSON.parse(value);
  } catch (error) {
    if (IS_SCRIPT_DEBUGGING) {
      console.log(el, error);
    }
    return {};
  }
}
function convertToSelect2(selector) {
  var $selector = $(selector);
  var args = {
    allowClear: true,
    width: '100%',
    templateResult: function templateResult(data) {
      if (!data.id) {
        return data.text;
      }
      var iconURI = $(data.element).data('icon');
      var iconElm = "<i class=\"directorist-icon-mask\" aria-hidden=\"true\" style=\"--directorist-icon: url(".concat(iconURI, ")\"></i>");
      var originalText = data.text;
      var modifiedText = originalText.replace(/^(\s*)/, "$1" + iconElm);
      var $state = $("<div class=\"directorist-select2-contents\">".concat(typeof iconURI !== 'undefined' && iconURI !== '' ? modifiedText : originalText, "</div>"));
      return $state;
    }
  };
  var options = $selector.find('option');
  if (options.length && options[0].textContent.length) {
    args.placeholder = options[0].textContent;
  }
  $selector.length && $selector.select2(args);
}


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
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
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
!function() {
/*!******************************************************!*\
  !*** ./assets/src/js/global/map-scripts/map-view.js ***!
  \******************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../../lib/helper */ "./assets/src/js/lib/helper.js");
/* harmony import */ var _add_listing_google_map__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./add-listing/google-map */ "./assets/src/js/global/map-scripts/add-listing/google-map.js");
/* harmony import */ var _single_listing_google_map__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./single-listing/google-map */ "./assets/src/js/global/map-scripts/single-listing/google-map.js");
/* harmony import */ var _single_listing_google_map_widget__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./single-listing/google-map-widget */ "./assets/src/js/global/map-scripts/single-listing/google-map-widget.js");




(function () {
  window.addEventListener('load', initMap);
  window.addEventListener('directorist-reload-listings-map-archive', initMap);
  function initMap() {
    var mapData = (0,_lib_helper__WEBPACK_IMPORTED_MODULE_0__.get_dom_data)('atbdp_map');

    // Define Marker Shapes
    var MAP_PIN = 'M0-48c-9.8 0-17.7 7.8-17.7 17.4 0 15.5 17.7 30.6 17.7 30.6s17.7-15.4 17.7-30.6c0-9.6-7.9-17.4-17.7-17.4z';
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
      div.style.left = "".concat(position.x - div.offsetWidth / 2, "px");
      div.style.top = "".concat(position.y - div.offsetHeight, "px");
    };
    (function ($) {
      // map view
      /**
       *  Render a Google Map onto the selected jQuery element.
       *
       *  @since    5.0.0
       */
      var at_icon = [];

      /* Use Default lat/lng in listings map view */
      var defCordEnabled = mapData.use_def_lat_long;
      function atbdp_rander_map($el) {
        $el.addClass('atbdp-map-loaded');

        // var
        var $markers = $el.find('.marker');

        // vars
        var args = {
          zoom: parseInt(mapData.zoom),
          center: new google.maps.LatLng(0, 0),
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          zoomControl: true,
          scrollwheel: false,
          gestureHandling: 'cooperative',
          averageCenter: true,
          scrollWheelZoom: 'center'
        };

        // create map
        var map = new google.maps.Map($el[0], args);

        // add a markers reference
        map.markers = [];

        // set map type
        map.type = $el.data('type');
        var infowindow = new google.maps.InfoWindow({
          content: ''
        });
        // add markers
        $markers.each(function () {
          atbdp_add_marker($(this), map, infowindow);
        });
        var cord = {
          lat: Number(mapData.default_latitude) ? Number(mapData.default_latitude) :  true ? defCordEnabled : 0,
          lng: Number(mapData.default_longitude) ? Number(mapData.default_longitude) :  true ? defCordEnabled : 0
        };
        if ($markers.length) {
          cord.lat = defCordEnabled ? Number(mapData.default_latitude) : Number($markers[0].getAttribute('data-latitude'));
          cord.lng = defCordEnabled ? Number(mapData.default_longitude) : Number($markers[0].getAttribute('data-longitude'));
        }

        // center map
        atbdp_center_map(map, cord);
        var mcOptions = new MarkerClusterer(map, [], {
          imagePath: mapData.plugin_url + 'assets/images/m'
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
        var latlng = new google.maps.LatLng($marker.data('latitude'), $marker.data('longitude'));
        // check to see if any of the existing markers match the latlng of the new marker
        if (map.markers.length) {
          for (var i = 0; i < map.markers.length; i++) {
            var existing_marker = map.markers[i];
            var pos = existing_marker.getPosition();

            // if a marker already exists in the same position as this marker
            if (latlng.equals(pos)) {
              // update the position of the coincident marker by applying a small multipler to its coordinates
              var latitude = latlng.lat() + (Math.random() - 0.5) / 1500; // * (Math.random() * (max - min) + min);
              var longitude = latlng.lng() + (Math.random() - 0.5) / 1500; // * (Math.random() * (max - min) + min);
              latlng = new google.maps.LatLng(latitude, longitude);
            }
          }
        }
        var icon = JSON.parse($marker.data('icon'));
        var marker = new Marker({
          position: latlng,
          map: map,
          icon: {
            path: MAP_PIN,
            fillColor: 'transparent',
            fillOpacity: 1,
            strokeColor: '',
            strokeWeight: 0
          },
          map_icon_label: icon !== undefined && "<div class=\"atbd_map_shape\">".concat(icon, "</div>")
        });

        // add to array
        map.markers.push(marker);
        // if marker contains HTML, add it to an infoWindow
        if ($marker.html()) {
          // show info window when marker is clicked
          google.maps.event.addListener(marker, 'click', function () {
            if (mapData.disable_info_window === 'no') {
              var marker_childrens = $($marker).children();
              if (marker_childrens.length) {
                var marker_content = marker_childrens[0];
                $(marker_content).toggleClass('map-info-wrapper--show');
              }
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

      function atbdp_center_map(map, cord) {
        map.setCenter(cord);
        map.setZoom(parseInt(mapData.zoom));
      }
      function setup_info_window() {
        var abc = document.querySelectorAll('div');
        abc.forEach(function (el, index) {
          if (el.innerText === 'atgm_marker') {
            el.innerText = ' ';
            el.innerHTML = "<i class=\"atbd_map_marker_icon\">".concat(at_icon, "</i>");
          }
          // ${$marker.data('icon')}
        });
        document.querySelectorAll('div').forEach(function (el1, index) {
          if (el1.style.backgroundImage.split('/').pop() === 'm1.png")') {
            el1.addEventListener('click', function () {
              setInterval(function () {
                var abc = document.querySelectorAll('div');
                abc.forEach(function (el, index) {
                  if (el.innerText === 'atgm_marker') {
                    el.innerText = ' ';
                    el.innerHTML = "<i class=\"atbd_map_marker_icon\">".concat(at_icon, "</i>");
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
          atbdp_rander_map($(this));
        });
      }
      setup_map();
      setup_info_window();
      $(document).ready(function () {
        $('body').find('.map-info-wrapper').addClass('map-info-wrapper--show');
      });
    })(jQuery);
  }
  var $ = jQuery;

  /* Elementor Edit Mode */
  $(window).on('elementor/frontend/init', function () {
    setTimeout(function () {
      if ($('body').hasClass('elementor-editor-active')) {
        initMap();
      }
    }, 3000);
  });

  // Elementor EditMode
  $('body').on('click', function (e) {
    if ($('body').hasClass('elementor-editor-active') && e.target.nodeName !== 'A' && e.target.nodeName !== 'BUTTON') {
      initMap();
    }
  });
})();
window.directoristLoadGoogleMap = function () {
  if (typeof google === "undefined" || !google.maps || !google.maps.Map) {
    return;
  } else {
    (0,_single_listing_google_map__WEBPACK_IMPORTED_MODULE_2__.initSingleMap)();
    (0,_add_listing_google_map__WEBPACK_IMPORTED_MODULE_1__.initAddListingMap)();
    (0,_single_listing_google_map_widget__WEBPACK_IMPORTED_MODULE_3__.initSingleMapWidget)();
  }
};
}();
/******/ })()
;
//# sourceMappingURL=google-map.js.map