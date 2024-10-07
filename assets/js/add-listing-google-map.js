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
/******/ 	return __webpack_require__(__webpack_require__.s = 17);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/global/map-scripts/add-listing/google-map.js":
/*!********************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/add-listing/google-map.js ***!
  \********************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../../../lib/helper */ "./assets/src/js/lib/helper.js");
/* Add listing google map */


(function ($) {
  $(document).ready(function () {
    if ($('#gmap').length) {
      var localized_data = Object(_lib_helper__WEBPACK_IMPORTED_MODULE_0__["get_dom_data"])('map_data');

      // initialize all vars here to avoid hoisting related misunderstanding.
      var placeSearch;
      var map;
      var autocomplete;
      var address_input;
      var markers;
      var info_window;
      var $manual_lat;
      var $manual_lng;
      var saved_lat_lng;
      var info_content;

      // Localized Data
      var loc_default_latitude = parseFloat(localized_data.default_latitude);
      var loc_default_longitude = parseFloat(localized_data.default_longitude);
      var loc_manual_lat = parseFloat(localized_data.manual_lat);
      var loc_manual_lng = parseFloat(localized_data.manual_lng);
      var loc_map_zoom_level = parseInt(localized_data.map_zoom_level);
      loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
      loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;
      $manual_lat = $('#manual_lat');
      $manual_lng = $('#manual_lng');
      saved_lat_lng = {
        lat: loc_manual_lat,
        lng: loc_manual_lng
      };

      // default is London city
      info_content = localized_data.info_content, markers = [],
      // initialize the array to keep track all the marker
      info_window = new google.maps.InfoWindow({
        content: info_content,
        maxWidth: 400
      });

      // if(address_input){
      //         address_input = document.getElementById('address');
      //         address_input.addEventListener('focus', geolocate);
      // }

      address_input = document.getElementById('address');
      if (address_input !== null) {
        address_input.addEventListener('focus', geolocate);
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
        var marker = new google.maps.Marker({
          map: map,
          position: place.geometry.location
        });

        // marker.addListener('click', function () {
        //     info_window.open(map, marker);
        // });

        // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
        markers.push(marker);
      }
      initAutocomplete(); // start google map place auto complete API call

      function initMap() {
        /* Create new map instance */
        map = new google.maps.Map(document.getElementById('gmap'), {
          zoom: loc_map_zoom_level,
          center: saved_lat_lng
        });
        var marker = new google.maps.Marker({
          map: map,
          position: saved_lat_lng,
          draggable: true,
          title: localized_data.marker_title
        });
        // marker.addListener('click', function () {
        //     info_window.open(map, marker);
        // });
        // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
        markers.push(marker);

        // create a Geocode instance
        var geocoder = new google.maps.Geocoder();
        document.getElementById('generate_admin_map').addEventListener('click', function (e) {
          e.preventDefault();
          geocodeAddress(geocoder, map);
        });

        // This event listener calls addMarker() when the map is clicked.
        google.maps.event.addListener(map, 'click', function (event) {
          deleteMarker(); // at first remove previous marker and then set new marker;
          // set the value of input field to save them to the database
          $manual_lat.val(event.latLng.lat());
          $manual_lng.val(event.latLng.lng());
          // add the marker to the given map.
          addMarker(event.latLng, map);
        });
        // This event listener update the lat long field of the form so that we can add the lat long to the database when the MARKER is drag.
        google.maps.event.addListener(marker, 'dragend', function (event) {
          // set the value of input field to save them to the database
          $manual_lat.val(event.latLng.lat());
          $manual_lng.val(event.latLng.lng());
        });
      }

      /*
       * Geocode and address using google map javascript api and then populate the input fields for storing lat and long
       * */

      function geocodeAddress(geocoder, resultsMap) {
        var address = address_input.value;
        var lat = parseFloat(document.getElementById('manual_lat').value);
        var lng = parseFloat(document.getElementById('manual_lng').value);
        var latLng = new google.maps.LatLng(lat, lng);
        var opt = {
          location: latLng,
          address: address
        };
        geocoder.geocode(opt, function (results, status) {
          if (status === 'OK') {
            // set the value of input field to save them to the database
            $manual_lat.val(results[0].geometry.location.lat());
            $manual_lng.val(results[0].geometry.location.lng());
            resultsMap.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
              map: resultsMap,
              position: results[0].geometry.location
            });

            // marker.addListener('click', function () {
            //     info_window.open(map, marker);
            // });

            deleteMarker();
            // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
            markers.push(marker);
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
        // Add the marker at the clicked location, and add the next-available label
        // from the array of alphabetical characters.
        var marker = new google.maps.Marker({
          position: location,
          /* label: labels[labelIndex++ % labels.length], */
          draggable: true,
          title: localized_data.marker_title,
          map: map
        });
        // marker.addListener('click', function () {
        //     info_window.open(map, marker);
        // });
        // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
        markers.push(marker);
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
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/lib/helper.js":
/*!*************************************!*\
  !*** ./assets/src/js/lib/helper.js ***!
  \*************************************/
/*! exports provided: get_dom_data, convertToSelect2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "get_dom_data", function() { return get_dom_data; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "convertToSelect2", function() { return convertToSelect2; });
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/typeof.js");
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "./node_modules/@babel/runtime/helpers/toConsumableArray.js");
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__);


var $ = jQuery;
function get_dom_data(key, parent) {
  // var elmKey = 'directorist-dom-data-' + key;
  var elmKey = 'directorist-dom-data-' + key;
  var dataElm = parent ? parent.getElementsByClassName(elmKey) : document.getElementsByClassName(elmKey);
  if (!dataElm) {
    return '';
  }
  var is_script_debugging = directorist && directorist.script_debugging && directorist.script_debugging == '1' ? true : false;
  try {
    var dataValue = atob(dataElm[0].dataset.value);
    dataValue = JSON.parse(dataValue);
    return dataValue;
  } catch (error) {
    if (is_script_debugging) {
      console.warn({
        key: key,
        dataElm: dataElm,
        error: error
      });
    }
    return '';
  }
}
function convertToSelect2(field) {
  if (!field) {
    return;
  }
  if (!field.elm) {
    return;
  }
  if (!field.elm.length) {
    return;
  }
  _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1___default()(field.elm).forEach(function (item) {
    var default_args = {
      allowClear: true,
      width: '100%',
      templateResult: function templateResult(data) {
        // We only really care if there is an field to pull classes from
        if (!data.field) {
          return data.text;
        }
        var $field = $(data.field);
        var $wrapper = $('<span></span>');
        $wrapper.addClass($field[0].className);
        $wrapper.text(data.text);
        return $wrapper;
      }
    };
    var args = field.args && _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(field.args) === 'object' ? Object.assign(default_args, field.args) : default_args;
    var options = $(item).find('option');
    var placeholder = options.length ? options[0].innerHTML : '';
    if (placeholder.length) {
      args.placeholder = placeholder;
    }
    $(item).select2(args);
  });
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayLikeToArray.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;
  for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i];
  return arr2;
}
module.exports = _arrayLikeToArray, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayLikeToArray = __webpack_require__(/*! ./arrayLikeToArray.js */ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js");
function _arrayWithoutHoles(arr) {
  if (Array.isArray(arr)) return arrayLikeToArray(arr);
}
module.exports = _arrayWithoutHoles, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/iterableToArray.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/iterableToArray.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _iterableToArray(iter) {
  if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter);
}
module.exports = _iterableToArray, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/nonIterableSpread.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/nonIterableSpread.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}
module.exports = _nonIterableSpread, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/toConsumableArray.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/toConsumableArray.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayWithoutHoles = __webpack_require__(/*! ./arrayWithoutHoles.js */ "./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js");
var iterableToArray = __webpack_require__(/*! ./iterableToArray.js */ "./node_modules/@babel/runtime/helpers/iterableToArray.js");
var unsupportedIterableToArray = __webpack_require__(/*! ./unsupportedIterableToArray.js */ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js");
var nonIterableSpread = __webpack_require__(/*! ./nonIterableSpread.js */ "./node_modules/@babel/runtime/helpers/nonIterableSpread.js");
function _toConsumableArray(arr) {
  return arrayWithoutHoles(arr) || iterableToArray(arr) || unsupportedIterableToArray(arr) || nonIterableSpread();
}
module.exports = _toConsumableArray, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/typeof.js":
/*!*******************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/typeof.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(o) {
  "@babel/helpers - typeof";

  return (module.exports = _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) {
    return typeof o;
  } : function (o) {
    return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o;
  }, module.exports.__esModule = true, module.exports["default"] = module.exports), _typeof(o);
}
module.exports = _typeof, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js":
/*!***************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js ***!
  \***************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayLikeToArray = __webpack_require__(/*! ./arrayLikeToArray.js */ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js");
function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return arrayLikeToArray(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(o);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return arrayLikeToArray(o, minLen);
}
module.exports = _unsupportedIterableToArray, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ 17:
/*!**************************************************************************!*\
  !*** multi ./assets/src/js/global/map-scripts/add-listing/google-map.js ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/global/map-scripts/add-listing/google-map.js */"./assets/src/js/global/map-scripts/add-listing/google-map.js");


/***/ })

/******/ });
//# sourceMappingURL=add-listing-google-map.js.map