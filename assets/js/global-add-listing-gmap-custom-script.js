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
/******/ 	return __webpack_require__(__webpack_require__.s = 9);
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
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_lib_helper__WEBPACK_IMPORTED_MODULE_0__);


(function ($) {
  $(document).ready(function () {
    var localized_data = Object(_lib_helper__WEBPACK_IMPORTED_MODULE_0__["get_dom_data"])('map_data'); // initialize all vars here to avoid hoisting related misunderstanding.

    var placeSearch;
    var map;
    var autocomplete;
    var address_input;
    var markers;
    var info_window;
    var $manual_lat;
    var $manual_lng;
    var saved_lat_lng;
    var info_content; // Localized Data

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
    }; // default is London city

    info_content = localized_data.info_content, markers = [], // initialize the array to keep track all the marker
    info_window = new google.maps.InfoWindow({
      content: info_content,
      maxWidth: 400
    }); // if(address_input){
    //         address_input = document.getElementById('address');
    //         address_input.addEventListener('focus', geolocate);
    // }

    address_input = document.getElementById('address');

    if (address_input !== null) {
      address_input.addEventListener('focus', geolocate);
    } // this function will work on sites that uses SSL, it applies to Chrome especially, other browsers may allow location sharing without securing.


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
      // location types.
      autocomplete = new google.maps.places.Autocomplete(address_input, {
        types: []
      }); // When the user selects an address from the dropdown, populate the necessary input fields and draw a marker

      autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
      // Get the place details from the autocomplete object.
      var place = autocomplete.getPlace(); // set the value of input field to save them to the database

      $manual_lat.val(place.geometry.location.lat());
      $manual_lng.val(place.geometry.location.lng());
      map.setCenter(place.geometry.location);
      var marker = new google.maps.Marker({
        map: map,
        position: place.geometry.location
      }); // marker.addListener('click', function () {
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
      }); // marker.addListener('click', function () {
      //     info_window.open(map, marker);
      // });
      // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.

      markers.push(marker); // create a Geocode instance

      var geocoder = new google.maps.Geocoder();
      document.getElementById('generate_admin_map').addEventListener('click', function (e) {
        e.preventDefault();
        geocodeAddress(geocoder, map);
      }); // This event listener calls addMarker() when the map is clicked.

      google.maps.event.addListener(map, 'click', function (event) {
        deleteMarker(); // at first remove previous marker and then set new marker;
        // set the value of input field to save them to the database

        $manual_lat.val(event.latLng.lat());
        $manual_lng.val(event.latLng.lng()); // add the marker to the given map.

        addMarker(event.latLng, map);
      }); // This event listener update the lat long field of the form so that we can add the lat long to the database when the MARKER is drag.

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
          }); // marker.addListener('click', function () {
          //     info_window.open(map, marker);
          // });

          deleteMarker(); // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.

          markers.push(marker);
        } else {
          alert(localized_data.geocode_error_msg + status);
        }
      });
    }

    initMap(); // adding features of creating marker manually on the map on add listing page.

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
      }); // marker.addListener('click', function () {
      //     info_window.open(map, marker);
      // });
      // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.

      markers.push(marker);
    } // Delete Marker


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
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/lib/helper.js":
/*!*************************************!*\
  !*** ./assets/src/js/lib/helper.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

throw new Error("Module build failed (from ./node_modules/babel-loader/lib/index.js):\nSyntaxError: /Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/assets/src/js/lib/helper.js: Unexpected token (7:1)\n\n\u001b[0m \u001b[90m  5 |\u001b[39m     \u001b[36mvar\u001b[39m dataElm \u001b[33m=\u001b[39m ( parent ) \u001b[33m?\u001b[39m parent\u001b[33m.\u001b[39mgetElementsByClassName( elmKey ) \u001b[33m:\u001b[39m document\u001b[33m.\u001b[39mgetElementsByClassName( elmKey )\u001b[33m;\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m  6 |\u001b[39m\u001b[0m\n\u001b[0m\u001b[31m\u001b[1m>\u001b[22m\u001b[39m\u001b[90m  7 |\u001b[39m \u001b[33m<<\u001b[39m\u001b[33m<<\u001b[39m\u001b[33m<<\u001b[39m\u001b[33m<\u001b[39m \u001b[33mHEAD\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m    |\u001b[39m  \u001b[31m\u001b[1m^\u001b[22m\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m  8 |\u001b[39m     \u001b[36mif\u001b[39m ( \u001b[33m!\u001b[39m dom_content\u001b[33m.\u001b[39mlength ) { \u001b[36mreturn\u001b[39m \u001b[32m''\u001b[39m\u001b[33m;\u001b[39m }\u001b[0m\n\u001b[0m \u001b[90m  9 |\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 10 |\u001b[39m     \u001b[36mvar\u001b[39m pattern \u001b[33m=\u001b[39m \u001b[36mnew\u001b[39m \u001b[33mRegExp\u001b[39m(\u001b[32m\"(<!-- directorist-dom-data::\"\u001b[39m \u001b[33m+\u001b[39m key \u001b[33m+\u001b[39m \u001b[32m\"\\\\s)(.+)(\\\\s-->)\"\u001b[39m)\u001b[33m;\u001b[39m\u001b[0m\n    at Object._raise (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:775:17)\n    at Object.raiseWithData (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:768:17)\n    at Object.raise (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:736:17)\n    at Object.unexpected (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:9716:16)\n    at Object.jsxParseIdentifier (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:4946:12)\n    at Object.jsxParseNamespacedName (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:4956:23)\n    at Object.jsxParseElementName (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:4967:21)\n    at Object.jsxParseOpeningElementAt (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:5054:22)\n    at Object.jsxParseElementAt (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:5087:33)\n    at Object.jsxParseElement (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:5161:17)\n    at Object.parseExprAtom (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:5168:19)\n    at Object.parseExprSubscripts (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10689:23)\n    at Object.parseUpdate (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10669:21)\n    at Object.parseMaybeUnary (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10647:23)\n    at Object.parseExprOps (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10504:23)\n    at Object.parseMaybeConditional (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10478:23)\n    at Object.parseMaybeAssign (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10441:21)\n    at Object.parseExpressionBase (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10386:23)\n    at /Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10380:39\n    at Object.allowInAnd (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:12079:16)\n    at Object.parseExpression (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10380:17)\n    at Object.parseStatementContent (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:12367:23)\n    at Object.parseStatement (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:12236:17)\n    at Object.parseBlockOrModuleBlockBody (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:12816:25)\n    at Object.parseBlockBody (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:12807:10)\n    at Object.parseBlock (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:12791:10)\n    at Object.parseFunctionBody (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:11758:24)\n    at Object.parseFunctionBodyAndFinish (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:11742:10)\n    at /Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:12949:12\n    at Object.withTopicForbiddingContext (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:12054:14)\n    at Object.parseFunction (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:12948:10)\n    at Object.parseFunctionStatement (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:12584:17)\n    at Object.parseStatementContent (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:12274:21)\n    at Object.parseStatement (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:12236:17)\n    at Object.parseBlockOrModuleBlockBody (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:12816:25)\n    at Object.parseBlockBody (/Users/syedgalib/Local Sites/directorist-app/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:12807:10)");

/***/ }),

/***/ 9:
/*!**************************************************************************!*\
  !*** multi ./assets/src/js/global/map-scripts/add-listing/google-map.js ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/global/map-scripts/add-listing/google-map.js */"./assets/src/js/global/map-scripts/add-listing/google-map.js");


/***/ })

/******/ });
//# sourceMappingURL=global-add-listing-gmap-custom-script.js.map