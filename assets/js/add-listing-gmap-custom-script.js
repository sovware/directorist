/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 20);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/lib/helper.js":
/*!*************************************!*\
  !*** ./assets/src/js/lib/helper.js ***!
  \*************************************/
/*! exports provided: get_dom_data */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"get_dom_data\", function() { return get_dom_data; });\nfunction get_dom_data(key) {\n  var dom_content = document.body.innerHTML;\n\n  if (!dom_content.length) {\n    return '';\n  }\n\n  var pattern = new RegExp(\"(<!-- directorist-dom-data::\" + key + \"\\\\s)(.+)(\\\\s-->)\");\n  var terget_content = pattern.exec(dom_content);\n\n  if (!terget_content) {\n    return '';\n  }\n\n  if (typeof terget_content[2] === 'undefined') {\n    return '';\n  }\n\n  var dom_data = JSON.parse(terget_content[2]);\n\n  if (!dom_data) {\n    return '';\n  }\n\n  return dom_data;\n}\n\n\n\n//# sourceURL=webpack:///./assets/src/js/lib/helper.js?");

/***/ }),

/***/ "./assets/src/js/map-scripts/add-listing/google-map.js":
/*!*************************************************************!*\
  !*** ./assets/src/js/map-scripts/add-listing/google-map.js ***!
  \*************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../lib/helper */ \"./assets/src/js/lib/helper.js\");\n\n\n(function ($) {\n  $(document).ready(function () {\n    var localized_data = Object(_lib_helper__WEBPACK_IMPORTED_MODULE_0__[\"get_dom_data\"])('map_data'); // initialize all vars here to avoid hoisting related misunderstanding.\n\n    var placeSearch;\n    var map;\n    var autocomplete;\n    var address_input;\n    var markers;\n    var info_window;\n    var $manual_lat;\n    var $manual_lng;\n    var saved_lat_lng;\n    var info_content; // Localized Data\n\n    var loc_default_latitude = parseFloat(localized_data.default_latitude);\n    var loc_default_longitude = parseFloat(localized_data.default_longitude);\n    var loc_manual_lat = parseFloat(localized_data.manual_lat);\n    var loc_manual_lng = parseFloat(localized_data.manual_lng);\n    var loc_map_zoom_level = parseInt(localized_data.map_zoom_level);\n    loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;\n    loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;\n    $manual_lat = $('#manual_lat');\n    $manual_lng = $('#manual_lng');\n    saved_lat_lng = {\n      lat: loc_manual_lat,\n      lng: loc_manual_lng\n    }; // default is London city\n\n    info_content = localized_data.info_content, markers = [], // initialize the array to keep track all the marker\n    info_window = new google.maps.InfoWindow({\n      content: info_content,\n      maxWidth: 400\n    });\n    address_input = document.getElementById('address');\n    address_input.addEventListener('focus', geolocate); // this function will work on sites that uses SSL, it applies to Chrome especially, other browsers may allow location sharing without securing.\n\n    function geolocate() {\n      if (navigator.geolocation) {\n        navigator.geolocation.getCurrentPosition(function (position) {\n          var geolocation = {\n            lat: position.coords.latitude,\n            lng: position.coords.longitude\n          };\n          var circle = new google.maps.Circle({\n            center: geolocation,\n            radius: position.coords.accuracy\n          });\n          autocomplete.setBounds(circle.getBounds());\n        });\n      }\n    }\n\n    function initAutocomplete() {\n      // Create the autocomplete object, restricting the search to geographical\n      // location types.\n      autocomplete = new google.maps.places.Autocomplete(address_input, {\n        types: []\n      }); // When the user selects an address from the dropdown, populate the necessary input fields and draw a marker\n\n      autocomplete.addListener('place_changed', fillInAddress);\n    }\n\n    function fillInAddress() {\n      // Get the place details from the autocomplete object.\n      var place = autocomplete.getPlace(); // set the value of input field to save them to the database\n\n      $manual_lat.val(place.geometry.location.lat());\n      $manual_lng.val(place.geometry.location.lng());\n      map.setCenter(place.geometry.location);\n      var marker = new google.maps.Marker({\n        map: map,\n        position: place.geometry.location\n      }); // marker.addListener('click', function () {\n      //     info_window.open(map, marker);\n      // });\n      // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.\n\n      markers.push(marker);\n    }\n\n    initAutocomplete(); // start google map place auto complete API call\n\n    function initMap() {\n      /* Create new map instance */\n      map = new google.maps.Map(document.getElementById('gmap'), {\n        zoom: loc_map_zoom_level,\n        center: saved_lat_lng\n      });\n      var marker = new google.maps.Marker({\n        map: map,\n        position: saved_lat_lng,\n        draggable: true,\n        title: localized_data.marker_title\n      }); // marker.addListener('click', function () {\n      //     info_window.open(map, marker);\n      // });\n      // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.\n\n      markers.push(marker); // create a Geocode instance\n\n      var geocoder = new google.maps.Geocoder();\n      document.getElementById('generate_admin_map').addEventListener('click', function (e) {\n        e.preventDefault();\n        geocodeAddress(geocoder, map);\n      }); // This event listener calls addMarker() when the map is clicked.\n\n      google.maps.event.addListener(map, 'click', function (event) {\n        deleteMarker(); // at first remove previous marker and then set new marker;\n        // set the value of input field to save them to the database\n\n        $manual_lat.val(event.latLng.lat());\n        $manual_lng.val(event.latLng.lng()); // add the marker to the given map.\n\n        addMarker(event.latLng, map);\n      }); // This event listener update the lat long field of the form so that we can add the lat long to the database when the MARKER is drag.\n\n      google.maps.event.addListener(marker, 'dragend', function (event) {\n        // set the value of input field to save them to the database\n        $manual_lat.val(event.latLng.lat());\n        $manual_lng.val(event.latLng.lng());\n      });\n    }\n    /*\n     * Geocode and address using google map javascript api and then populate the input fields for storing lat and long\n     * */\n\n\n    function geocodeAddress(geocoder, resultsMap) {\n      var address = address_input.value;\n      var lat = document.getElementById('manual_lat').value;\n      var lng = document.getElementById('manual_lng').value;\n      var latLng = new google.maps.LatLng(lat, lng);\n      var opt = {\n        location: latLng,\n        address: address\n      };\n      geocoder.geocode(opt, function (results, status) {\n        if (status === 'OK') {\n          // set the value of input field to save them to the database\n          $manual_lat.val(results[0].geometry.location.lat());\n          $manual_lng.val(results[0].geometry.location.lng());\n          resultsMap.setCenter(results[0].geometry.location);\n          var marker = new google.maps.Marker({\n            map: resultsMap,\n            position: results[0].geometry.location\n          }); // marker.addListener('click', function () {\n          //     info_window.open(map, marker);\n          // });\n\n          deleteMarker(); // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.\n\n          markers.push(marker);\n        } else {\n          alert(localized_data.geocode_error_msg + status);\n        }\n      });\n    }\n\n    initMap(); // adding features of creating marker manually on the map on add listing page.\n\n    /* var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';\n    var labelIndex = 0; */\n    // Adds a marker to the map.\n\n    function addMarker(location, map) {\n      // Add the marker at the clicked location, and add the next-available label\n      // from the array of alphabetical characters.\n      var marker = new google.maps.Marker({\n        position: location,\n\n        /* label: labels[labelIndex++ % labels.length], */\n        draggable: true,\n        title: localized_data.marker_title,\n        map: map\n      }); // marker.addListener('click', function () {\n      //     info_window.open(map, marker);\n      // });\n      // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.\n\n      markers.push(marker);\n    } // Delete Marker\n\n\n    $('#delete_marker').on('click', function (e) {\n      e.preventDefault();\n      deleteMarker();\n    });\n\n    function deleteMarker() {\n      for (var i = 0; i < markers.length; i++) {\n        markers[i].setMap(null);\n      }\n\n      markers = [];\n    }\n  });\n})(jQuery);\n\n//# sourceURL=webpack:///./assets/src/js/map-scripts/add-listing/google-map.js?");

/***/ }),

/***/ 20:
/*!*******************************************************************!*\
  !*** multi ./assets/src/js/map-scripts/add-listing/google-map.js ***!
  \*******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("module.exports = __webpack_require__(/*! ./assets/src/js/map-scripts/add-listing/google-map.js */\"./assets/src/js/map-scripts/add-listing/google-map.js\");\n\n\n//# sourceURL=webpack:///multi_./assets/src/js/map-scripts/add-listing/google-map.js?");

/***/ })

/******/ });