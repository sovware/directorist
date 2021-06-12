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
/******/ 	return __webpack_require__(__webpack_require__.s = 12);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/global/map-scripts/single-listing/openstreet-map-widget.js":
/*!**********************************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/single-listing/openstreet-map-widget.js ***!
  \**********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function ($) {
  jQuery(document).ready(function () {
    // Localized Data
    var map_container = localized_data_widget.map_container_id ? localized_data_widget.map_container_id : 'gmap';
    var loc_default_latitude = parseFloat(localized_data_widget.default_latitude);
    var loc_default_longitude = parseFloat(localized_data_widget.default_longitude);
    var loc_manual_lat = parseFloat(localized_data_widget.manual_lat);
    var loc_manual_lng = parseFloat(localized_data_widget.manual_lng);
    var loc_map_zoom_level = parseInt(localized_data_widget.map_zoom_level);
    var _localized_data_widge = localized_data_widget,
        display_map_info = _localized_data_widge.display_map_info;
    var _localized_data_widge2 = localized_data_widget,
        cat_icon = _localized_data_widge2.cat_icon;
    var _localized_data_widge3 = localized_data_widget,
        info_content = _localized_data_widge3.info_content;
    loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
    loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;
    $manual_lat = $('#manual_lat');
    $manual_lng = $('#manual_lng');
    saved_lat_lng = {
      lat: loc_manual_lat,
      lng: loc_manual_lng
    };

    function mapLeaflet(lat, lon) {
      var fontAwesomeIcon = L.divIcon({
        html: "<div class=\"atbd_map_shape\"><span class=\"".concat(cat_icon, "\"></span></div>"),
        iconSize: [20, 20],
        className: 'myDivIcon'
      });
      var mymap = L.map(map_container).setView([lat, lon], loc_map_zoom_level);

      if (display_map_info) {
        L.marker([lat, lon], {
          icon: fontAwesomeIcon
        }).addTo(mymap).bindPopup(info_content);
      } else {
        L.marker([lat, lon], {
          icon: fontAwesomeIcon
        }).addTo(mymap);
      }

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(mymap);
    }

    mapLeaflet(loc_manual_lat, loc_manual_lng);
  });
})(jQuery);

/***/ }),

/***/ 12:
/*!****************************************************************************************!*\
  !*** multi ./assets/src/js/global/map-scripts/single-listing/openstreet-map-widget.js ***!
  \****************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/global/map-scripts/single-listing/openstreet-map-widget.js */"./assets/src/js/global/map-scripts/single-listing/openstreet-map-widget.js");


/***/ })

/******/ });
//# sourceMappingURL=public-single-listing-openstreet-map-widget-custom-script.js.map