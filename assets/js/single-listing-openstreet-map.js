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

/***/ "./assets/src/js/global/map-scripts/single-listing/openstreet-map.js":
/*!***************************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/single-listing/openstreet-map.js ***!
  \***************************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../../../lib/helper */ "./assets/src/js/lib/helper.js");


(function ($) {
  jQuery(document).ready(function () {
    var localized_data = Object(_lib_helper__WEBPACK_IMPORTED_MODULE_0__["get_dom_data"])('map_data'); // Localized Data

    var map_container = localized_data.map_container_id ? localized_data.map_container_id : 'gmap';
    var loc_default_latitude = parseFloat(localized_data.default_latitude);
    var loc_default_longitude = parseFloat(localized_data.default_longitude);
    var loc_manual_lat = parseFloat(localized_data.manual_lat);
    var loc_manual_lng = parseFloat(localized_data.manual_lng);
    var loc_map_zoom_level = parseInt(localized_data.map_zoom_level);
    var display_map_info = localized_data.display_map_info;
    var cat_icon = localized_data.cat_icon;
    var info_content = localized_data.info_content;
    loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
    loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;
    $('#manual_lat').length ? $manual_lat = $('#manual_lat') : '';
    $('#manual_lng').length ? $manual_lng = $('#manual_lng') : '';
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

var $ = jQuery;

function get_dom_data(key, parent) {
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
      console.log({
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
  var options = field.elm.find('option');
  var placeholder = options.length ? options[0].innerHTML : '';

  if (placeholder.length) {
    args.placeholder = placeholder;
  }

  field.elm.select2(args);
}



/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/typeof.js":
/*!*******************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/typeof.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) {
  "@babel/helpers - typeof";

  if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
    module.exports = _typeof = function _typeof(obj) {
      return typeof obj;
    };

    module.exports["default"] = module.exports, module.exports.__esModule = true;
  } else {
    module.exports = _typeof = function _typeof(obj) {
      return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
    };

    module.exports["default"] = module.exports, module.exports.__esModule = true;
  }

  return _typeof(obj);
}

module.exports = _typeof;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ 17:
/*!*********************************************************************************!*\
  !*** multi ./assets/src/js/global/map-scripts/single-listing/openstreet-map.js ***!
  \*********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/global/map-scripts/single-listing/openstreet-map.js */"./assets/src/js/global/map-scripts/single-listing/openstreet-map.js");


/***/ })

/******/ });
//# sourceMappingURL=single-listing-openstreet-map.js.map