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
/******/ 	return __webpack_require__(__webpack_require__.s = 13);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/map-scripts/geolocation-widget.js":
/*!*********************************************************!*\
  !*** ./assets/src/js/map-scripts/geolocation-widget.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function ($) {\n  /*// Price Range Slider\n  var slider_range = $(\".atbd_slider-range\");\n  var miles = atbdp_search_listing.i18n_text.Miles;\n  var dvalue = $(\"#atbd_rs_value\").val();\n  slider_range.each(function () {\n      $(this).slider({\n          range: \"min\",\n          min: 0,\n          max: 1000,\n          value: dvalue,\n          slide: function (event, ui) {\n              $(\".atbdpr_amount\").text(ui.value + miles);\n              $(\"#atbd_rs_value\").val(ui.value);\n          }\n      });\n  });\n  $(\".atbdpr_amount\").text(slider_range.slider(\"value\") + miles);\n  $(\"#atbd_rs_value\").val(slider_range.slider(\"value\"));*/\n\n  /*\n  get current location\n  */\n  if ('google' === adbdp_geolocation.select_listing_map) {\n    (function () {\n      var x = document.querySelector(\".widget-location-name\");\n      var get_lat = document.querySelector(\"#cityLat\");\n      var get_lng = document.querySelector(\"#cityLng\");\n\n      function getLocation() {\n        if (navigator.geolocation) {\n          navigator.geolocation.getCurrentPosition(showPosition, showError);\n        } else {\n          x.value = \"Geolocation is not supported by this browser.\";\n        }\n      }\n\n      function showPosition(position) {\n        lat = position.coords.latitude;\n        lon = position.coords.longitude;\n        displayLocation(lat, lon);\n        get_lat.value = lat;\n        get_lng.value = lon;\n      }\n\n      function showError(error) {\n        switch (error.code) {\n          case error.PERMISSION_DENIED:\n            x.value = \"User denied the request for Geolocation.\";\n            break;\n\n          case error.POSITION_UNAVAILABLE:\n            x.value = \"Location information is unavailable.\";\n            break;\n\n          case error.TIMEOUT:\n            x.value = \"The request to get user location timed out.\";\n            break;\n\n          case error.UNKNOWN_ERROR:\n            x.value = \"An unknown error occurred.\";\n            break;\n        }\n      }\n\n      function displayLocation(latitude, longitude) {\n        var geocoder;\n        geocoder = new google.maps.Geocoder();\n        var latlng = new google.maps.LatLng(latitude, longitude);\n        geocoder.geocode({\n          'latLng': latlng\n        }, function (results, status) {\n          if (status == google.maps.GeocoderStatus.OK) {\n            if (results[0]) {\n              var add = results[0].formatted_address;\n              var value = add.split(\",\");\n              count = value.length;\n              country = value[count - 1];\n              state = value[count - 2];\n              city = value[count - 3];\n              x.value = city;\n            } else {\n              x.value = \"address not found\";\n            }\n          } else {\n            x.value = \"Geocoder failed due to: \" + status;\n          }\n        });\n      }\n\n      var get_loc_btn = document.querySelector(\".atbd_get_loc_wid\");\n      get_loc_btn.addEventListener(\"click\", function () {\n        getLocation();\n      });\n\n      if (atbdp_search_listing.i18n_text.select_listing_map === 'google') {\n        function initialize() {\n          var input = document.getElementById('address_widget');\n          var options = atbdp_search_listing.countryRestriction ? {\n            types: ['geocode'],\n            componentRestrictions: {\n              country: atbdp_search_listing.restricted_countries\n            }\n          } : '';\n          var autocomplete = new google.maps.places.Autocomplete(input, options);\n          google.maps.event.addListener(autocomplete, 'place_changed', function () {\n            var place = autocomplete.getPlace();\n            document.getElementById('cityLat').value = place.geometry.location.lat();\n            document.getElementById('cityLng').value = place.geometry.location.lng();\n          });\n        }\n\n        google.maps.event.addDomListener(window, 'load', initialize);\n      }\n    })();\n  } else if (atbdp_search_listing.i18n_text.select_listing_map === 'openstreet') {\n    $('#address_widget').on('keyup', function (event) {\n      event.preventDefault();\n      var search = $('#address_widget').val();\n      $('#address_widget_result').css({\n        'display': 'block'\n      });\n\n      if (search === \"\") {\n        $('#address_widget_result').css({\n          'display': 'none'\n        });\n      }\n\n      var res = \"\";\n      $.ajax({\n        url: \"https://nominatim.openstreetmap.org/?q=%27+\".concat(search, \"+%27&format=json\"),\n        type: 'POST',\n        data: {},\n        success: function success(data) {\n          //console.log(data);\n          for (var i = 0; i < data.length; i++) {\n            res += '<li><a href=\"#\" data-lat=' + data[i].lat + ' data-lon=' + data[i].lon + '>' + data[i].display_name + '</a></li>';\n          }\n\n          $('#address_widget_result').html('<ul>' + res + '</ul>');\n        }\n      });\n    });\n    $('body').on('click', '#address_widget_result ul li a', function (event) {\n      event.preventDefault();\n      var text = $(this).text(),\n          lat = $(this).data('lat'),\n          lon = $(this).data('lon');\n      $('#cityLat').val(lat);\n      $('#cityLng').val(lon);\n      $('#address_widget').val(text);\n      $('#address_widget_result').hide();\n    });\n\n    function displayLocation(position) {\n      var lat = position.coords.latitude;\n      var lng = position.coords.longitude;\n      $.ajax({\n        url: \"https://nominatim.openstreetmap.org/reverse?format=json&lon=\".concat(lng, \"&lat=\").concat(lat),\n        type: 'POST',\n        data: {},\n        success: function success(data) {\n          $('#address_widget').val(data.display_name);\n          $('#cityLat').val(lat);\n          $('#cityLng').val(lng);\n        }\n      });\n    }\n\n    $(\".atbd_get_loc_wid\").on('click', function () {\n      navigator.geolocation.getCurrentPosition(displayLocation);\n    });\n  }\n\n  if ($('#address_widget').val() === \"\") {\n    $('#address_widget_result').css({\n      'display': 'none'\n    });\n  }\n})(jQuery);\n\n//# sourceURL=webpack:///./assets/src/js/map-scripts/geolocation-widget.js?");

/***/ }),

/***/ 13:
/*!***************************************************************!*\
  !*** multi ./assets/src/js/map-scripts/geolocation-widget.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("module.exports = __webpack_require__(/*! ./assets/src/js/map-scripts/geolocation-widget.js */\"./assets/src/js/map-scripts/geolocation-widget.js\");\n\n\n//# sourceURL=webpack:///multi_./assets/src/js/map-scripts/geolocation-widget.js?");

/***/ })

/******/ });