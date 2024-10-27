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

/***/ "./assets/src/js/global/components/debounce.js":
/*!*****************************************************!*\
  !*** ./assets/src/js/global/components/debounce.js ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return debounce; });
function debounce(func, wait, immediate) {
  var timeout;
  return function () {
    var context = this,
      args = arguments;
    var later = function later() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
}
;

/***/ }),

/***/ "./assets/src/js/global/map-scripts/add-listing/openstreet-map.js":
/*!************************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/add-listing/openstreet-map.js ***!
  \************************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_debounce__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../components/debounce */ "./assets/src/js/global/components/debounce.js");
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./../../../lib/helper */ "./assets/src/js/lib/helper.js");
/* Add listing OSMap */



(function ($) {
  // Add focus class to the parent field of .directorist-location-js
  function addFocusClass(location) {
    // Get the parent field of .directorist-location-js
    var parentField = location.closest('.directorist-search-field');

    // Add the 'input-is-focused' class if not already present
    if (parentField && !parentField.hasClass('input-is-focused')) {
      parentField.addClass('input-is-focused');
    }
  }

  // Add Listing Map Initialize
  function initAddListingMap() {
    var mapData = Object(_lib_helper__WEBPACK_IMPORTED_MODULE_1__["get_dom_data"])('map_data');

    // Localized Data
    var loc_default_latitude = parseFloat(mapData.default_latitude);
    var loc_default_longitude = parseFloat(mapData.default_longitude);
    var loc_manual_lat = parseFloat(mapData.manual_lat);
    var loc_manual_lng = parseFloat(mapData.manual_lng);
    var loc_map_zoom_level = parseInt(mapData.map_zoom_level);
    var loc_map_icon = mapData.map_icon;
    loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
    loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;
    function mapLeaflet(lat, lon) {
      // @todo @kowsar / remove later. fix js error
      if ($("#gmap").length == 0) {
        return;
      }
      var fontAwesomeIcon = L.divIcon({
        html: "<div class=\"atbd_map_shape\">".concat(loc_map_icon, "</div>"),
        iconSize: [20, 20],
        className: 'myDivIcon'
      });
      var mymap = L.map('gmap').setView([lat, lon], loc_map_zoom_level);

      // Create draggable marker
      var marker = L.marker([lat, lon], {
        icon: fontAwesomeIcon,
        draggable: true
      }).addTo(mymap);

      // Trigger AJAX request when marker is dropped
      marker.on("dragend", function (e) {
        var position = marker.getLatLng();
        $('#manual_lat').val(position.lat);
        $('#manual_lng').val(position.lng);

        // Make AJAX request after the drag ends (marker drop)
        $.ajax({
          url: "https://nominatim.openstreetmap.org/reverse?format=json&lon=".concat(position.lng, "&lat=").concat(position.lat),
          type: 'GET',
          data: {},
          success: function success(data) {
            $('.directorist-location-js').val(data.display_name);
            addFocusClass($('.directorist-location-js'));
          },
          error: function error() {
            $('.directorist-location-js').val('Location not found');
            addFocusClass($('.directorist-location-js'));
          }
        });
      });
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(mymap);
      function toggleFullscreen() {
        var mapContainer = document.getElementById('gmap');
        var fullScreenEnable = document.querySelector('#gmap_full_screen_button .fullscreen-enable');
        var fullScreenDisable = document.querySelector('#gmap_full_screen_button .fullscreen-disable');
        if (!document.fullscreenElement && !document.webkitFullscreenElement) {
          if (mapContainer.requestFullscreen) {
            mapContainer.requestFullscreen();
            fullScreenEnable.style.display = "none";
            fullScreenDisable.style.display = "block";
          } else if (mapContainer.webkitRequestFullscreen) {
            mapContainer.webkitRequestFullscreen();
          }
        } else {
          if (document.exitFullscreen) {
            document.exitFullscreen();
            fullScreenDisable.style.display = "none";
            fullScreenEnable.style.display = "block";
          } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
          }
        }
      }
      $('body').on('click', '#gmap_full_screen_button', function (event) {
        event.preventDefault();
        toggleFullscreen();
      });
    }
    $('.directorist-location-js').each(function (id, elm) {
      var result_container = $(elm).siblings('.address_result');
      $(elm).on('keyup', Object(_components_debounce__WEBPACK_IMPORTED_MODULE_0__["default"])(function (event) {
        event.preventDefault();
        var blockedKeyCodes = [16, 17, 18, 19, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 91, 93, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 144, 145];

        // Return early when blocked key is pressed.
        if (blockedKeyCodes.includes(event.keyCode)) {
          return;
        }
        var locationAddressField = $(this).parent('.directorist-form-address-field');
        var search = $(elm).val();
        if (search.length < 3) {
          result_container.css({
            'display': 'none'
          });
        } else {
          locationAddressField.addClass('atbdp-form-fade');
          result_container.css({
            'display': 'block'
          });
          $.ajax({
            url: "https://nominatim.openstreetmap.org/?q=%27+".concat(search, "+%27&format=json"),
            type: 'GET',
            data: {},
            success: function success(data) {
              var res = '';
              for (var i = 0; i < data.length; i++) {
                res += "<li><a href=\"#\" data-lat=".concat(data[i].lat, " data-lon=").concat(data[i].lon, ">").concat(data[i].display_name, "</a></li>");
              }
              result_container.find('ul').html(res);
              if (res.length) {
                result_container.show();
              } else {
                result_container.hide();
              }
              locationAddressField.removeClass('atbdp-form-fade');
            }
          });
        }
      }, 750));
    });
    var lat = loc_manual_lat,
      lon = loc_manual_lng;
    mapLeaflet(lat, lon);

    // Add Map on Add Listing Multistep
    $('body').on('click', '.multistep-wizard__btn', function (event) {
      if (document.getElementById('osm')) {
        document.getElementById('osm').innerHTML = "<div id='gmap'></div>";
        mapLeaflet(lat, lon);
      }
    });
    $('body').on('click', '.directorist-form-address-field .address_result ul li a', function (event) {
      if (document.getElementById('osm')) {
        document.getElementById('osm').innerHTML = "<div id='gmap'></div>";
      }
      event.preventDefault();
      var text = $(this).text(),
        lat = $(this).data('lat'),
        lon = $(this).data('lon');
      $('#manual_lat').val(lat);
      $('#manual_lng').val(lon);
      $(this).closest('.address_result').siblings('.directorist-location-js').val(text);
      $('.address_result').css({
        'display': 'none'
      });
      mapLeaflet(lat, lon);
    });
    $('body').on('click', '.location-names ul li a', function (event) {
      event.preventDefault();
      var text = $(this).text();
      $(this).closest('.address_result').siblings('.directorist-location-js').val(text);
      $('.address_result').css({
        'display': 'none'
      });
    });
    $('body').on('click', '#generate_admin_map', function (event) {
      event.preventDefault();
      document.getElementById('osm').innerHTML = "<div id='gmap'></div>";
      mapLeaflet($('#manual_lat').val(), $('#manual_lng').val());
    });

    // Popup controller by keyboard
    var index = 0;
    $('.directorist-location-js').on('keyup', function (event) {
      event.preventDefault();
      var length = $('#directorist.atbd_wrapper .address_result ul li a').length;
      if (event.keyCode === 40) {
        index++;
        if (index > length) {
          index = 0;
        }
      } else if (event.keyCode === 38) {
        index--;
        if (index < 0) {
          index = length;
        }
        ;
      }
      if ($('#directorist.atbd_wrapper .address_result ul li a').length > 0) {
        $('#directorist.atbd_wrapper .address_result ul li a').removeClass('active');
        $($('#directorist.atbd_wrapper .address_result ul li a')[index]).addClass('active');
        if (event.keyCode === 13) {
          $($('#directorist.atbd_wrapper .address_result ul li a')[index]).click();
          event.preventDefault();
          index = 0;
          return false;
        }
      }
      ;
    });
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
})(jQuery);

/***/ }),

/***/ "./assets/src/js/lib/helper.js":
/*!*************************************!*\
  !*** ./assets/src/js/lib/helper.js ***!
  \*************************************/
/*! exports provided: convertToSelect2, get_dom_data */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "convertToSelect2", function() { return convertToSelect2; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "get_dom_data", function() { return get_dom_data; });
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


/***/ }),

/***/ 17:
/*!******************************************************************************!*\
  !*** multi ./assets/src/js/global/map-scripts/add-listing/openstreet-map.js ***!
  \******************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/global/map-scripts/add-listing/openstreet-map.js */"./assets/src/js/global/map-scripts/add-listing/openstreet-map.js");


/***/ })

/******/ });
//# sourceMappingURL=add-listing-openstreet-map.js.map