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
/******/ 	return __webpack_require__(__webpack_require__.s = 5);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/map-scripts/single-listing/openstreet-map.js":
/*!********************************************************************!*\
  !*** ./assets/src/js/map-scripts/single-listing/openstreet-map.js ***!
  \********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function ($) {\n  jQuery(document).ready(function () {\n    // Localized Data\n    var loc_default_latitude = parseFloat(localized_data.default_latitude);\n    var loc_default_longitude = parseFloat(localized_data.default_longitude);\n    var loc_manual_lat = parseFloat(localized_data.manual_lat);\n    var loc_manual_lng = parseFloat(localized_data.manual_lng);\n    var loc_map_zoom_level = parseInt(localized_data.map_zoom_level);\n    var _localized_data = localized_data,\n        display_map_info = _localized_data.display_map_info;\n    var _localized_data2 = localized_data,\n        cat_icon = _localized_data2.cat_icon;\n    var _localized_data3 = localized_data,\n        info_content = _localized_data3.info_content;\n    loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;\n    loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;\n    $manual_lat = $('#manual_lat');\n    $manual_lng = $('#manual_lng');\n    saved_lat_lng = {\n      lat: loc_manual_lat,\n      lng: loc_manual_lng\n    };\n\n    function mapLeaflet(lat, lon) {\n      var fontAwesomeIcon = L.divIcon({\n        html: \"<div class=\\\"atbd_map_shape\\\"><span class=\\\"\".concat(cat_icon, \"\\\"></span></div>\"),\n        iconSize: [20, 20],\n        className: 'myDivIcon'\n      });\n      var mymap = L.map('gmap').setView([lat, lon], loc_map_zoom_level);\n\n      if (display_map_info) {\n        L.marker([lat, lon], {\n          icon: fontAwesomeIcon\n        }).addTo(mymap).bindPopup(info_content);\n      } else {\n        L.marker([lat, lon], {\n          icon: fontAwesomeIcon\n        }).addTo(mymap);\n      }\n\n      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {\n        attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'\n      }).addTo(mymap);\n    }\n\n    mapLeaflet(loc_manual_lat, loc_manual_lng);\n  });\n})(jQuery);\n\n//# sourceURL=webpack:///./assets/src/js/map-scripts/single-listing/openstreet-map.js?");

/***/ }),

/***/ 5:
/*!**************************************************************************!*\
  !*** multi ./assets/src/js/map-scripts/single-listing/openstreet-map.js ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("module.exports = __webpack_require__(/*! ./assets/src/js/map-scripts/single-listing/openstreet-map.js */\"./assets/src/js/map-scripts/single-listing/openstreet-map.js\");\n\n\n//# sourceURL=webpack:///multi_./assets/src/js/map-scripts/single-listing/openstreet-map.js?");

/***/ })

/******/ });