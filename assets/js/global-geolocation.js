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
/******/ 	return __webpack_require__(__webpack_require__.s = 10);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/global/map-scripts/geolocation.js":
/*!*********************************************************!*\
  !*** ./assets/src/js/global/map-scripts/geolocation.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function ($) {
  /* $("button[type='reset']").on("click", function (){
  $("#atbd_rs_value").val(0);
  $(".atbdpr_amount").text(0 + miles);
  slider_range.each(function () {
      $(this).slider({
          range: "min",
          min: 0,
          max: 1000,
          value: 0,
          slide: function (event, ui) {
              $(".atbdpr_amount").text(ui.value + miles);
              $("#atbd_rs_value").val(ui.value);
          }
      });
  });
  $("#at_biz_dir-location, #at_biz_dir-category").val('').trigger('change');
  }); */

  /* get current location */
  setTimeout(function () {
    if (adbdp_geolocation.select_listing_map === 'google') {
      (function () {
        var x = document.querySelector('.location-name');
        var get_lat = document.querySelector('#cityLat');
        var get_lng = document.querySelector('#cityLng');

        function getLocation() {
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
          } else {
            x.value = 'Geolocation is not supported by this browser.';
          }
        }

        function showPosition(position) {
          lat = position.coords.latitude;
          lon = position.coords.longitude;
          displayCurrentLocation(lat, lon);
          get_lat.value = lat;
          get_lng.value = lon;
        }

        function showError(error) {
          switch (error.code) {
            case error.PERMISSION_DENIED:
              x.value = 'User denied the request for Geolocation.';
              break;

            case error.POSITION_UNAVAILABLE:
              x.value = 'Location information is unavailable.';
              break;

            case error.TIMEOUT:
              x.value = 'The request to get user location timed out.';
              break;

            case error.UNKNOWN_ERROR:
              x.value = 'An unknown error occurred.';
              break;
          }
        }

        function displayLocation(latitude, longitude) {
          var geocoder;
          geocoder = new google.maps.Geocoder();
          var latlng = new google.maps.LatLng(latitude, longitude);
          geocoder.geocode({
            latLng: latlng,
            componentRestrictions: {
              country: 'GB'
            }
          }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              if (results[0]) {
                var add = results[0].formatted_address;
                var value = add.split(',');
                count = value.length;
                country = value[count - 1];
                state = value[count - 2];
                city = value[count - 3];
                x.value = city;
              } else {
                x.value = 'address not found';
              }
            } else {
              x.value = "Geocoder failed due to: ".concat(status);
            }
          });
        }

        function displayCurrentLocation(latitude, longitude) {
          var geocoder;
          geocoder = new google.maps.Geocoder();
          var latlng = new google.maps.LatLng(latitude, longitude);
          geocoder.geocode({
            latLng: latlng
          }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              if (results[0]) {
                var add = results[0].formatted_address;
                var value = add.split(',');
                count = value.length;
                country = value[count - 1];
                state = value[count - 2];
                city = value[count - 3];
                x.value = city;
              } else {
                x.value = 'address not found';
              }
            } else {
              x.value = "Geocoder failed due to: ".concat(status);
            }
          });
        }

        var get_loc_btn = document.querySelector('.directorist-filter-location-icon');
        get_loc_btn.addEventListener('click', function () {
          getLocation();
        });
      })();
    } else if (adbdp_geolocation.select_listing_map === 'openstreet') {
      function displayLocation(position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;
        $.ajax({
          url: "https://nominatim.openstreetmap.org/reverse?format=json&lon=".concat(lng, "&lat=").concat(lat),
          type: 'POST',
          data: {},
          success: function success(data) {
            $('#address,.atbdp-search-address').val(data.display_name);
            $('#cityLat').val(lat);
            $('#cityLng').val(lng);
          }
        });
      }

      $('.directorist-filter-location-icon').on('click', function () {
        navigator.geolocation.getCurrentPosition(displayLocation);
      });
    }
  }, 1000);
})(jQuery);

/***/ }),

/***/ 10:
/*!***************************************************************!*\
  !*** multi ./assets/src/js/global/map-scripts/geolocation.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/global/map-scripts/geolocation.js */"./assets/src/js/global/map-scripts/geolocation.js");


/***/ })

/******/ });
//# sourceMappingURL=global-geolocation.js.map