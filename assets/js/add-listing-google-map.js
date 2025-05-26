/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

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
/*!********************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/add-listing/google-map.js ***!
  \********************************************************************/
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
}();
/******/ })()
;
//# sourceMappingURL=add-listing-google-map.js.map