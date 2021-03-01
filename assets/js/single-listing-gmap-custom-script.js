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
/******/ 	return __webpack_require__(__webpack_require__.s = 6);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/map-scripts/single-listing/google-map.js":
/*!****************************************************************!*\
  !*** ./assets/src/js/map-scripts/single-listing/google-map.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval(";\n\n(function ($) {\n  var MAP_PIN = 'M0-48c-9.8 0-17.7 7.8-17.7 17.4 0 15.5 17.7 30.6 17.7 30.6s17.7-15.4 17.7-30.6c0-9.6-7.9-17.4-17.7-17.4z';\n\n  var inherits = function inherits(childCtor, parentCtor) {\n    /** @constructor */\n    function tempCtor() {}\n\n    tempCtor.prototype = parentCtor.prototype;\n    childCtor.superClass_ = parentCtor.prototype;\n    childCtor.prototype = new tempCtor();\n    childCtor.prototype.constructor = childCtor;\n  };\n\n  function Marker(options) {\n    google.maps.Marker.apply(this, arguments);\n\n    if (options.map_icon_label) {\n      this.MarkerLabel = new MarkerLabel({\n        map: this.map,\n        marker: this,\n        text: options.map_icon_label\n      });\n      this.MarkerLabel.bindTo('position', this, 'position');\n    }\n  } // Apply the inheritance\n\n\n  inherits(Marker, google.maps.Marker); // Custom Marker SetMap\n\n  Marker.prototype.setMap = function () {\n    google.maps.Marker.prototype.setMap.apply(this, arguments);\n    this.MarkerLabel && this.MarkerLabel.setMap.apply(this.MarkerLabel, arguments);\n  }; // Marker Label Overlay\n\n\n  var MarkerLabel = function MarkerLabel(options) {\n    var self = this;\n    this.setValues(options); // Create the label container\n\n    this.div = document.createElement('div');\n    this.div.className = 'map-icon-label'; // Trigger the marker click handler if clicking on the label\n\n    google.maps.event.addDomListener(this.div, 'click', function (e) {\n      e.stopPropagation && e.stopPropagation();\n      google.maps.event.trigger(self.marker, 'click');\n    });\n  }; // Create MarkerLabel Object\n\n\n  MarkerLabel.prototype = new google.maps.OverlayView(); // Marker Label onAdd\n\n  MarkerLabel.prototype.onAdd = function () {\n    var pane = this.getPanes().overlayImage.appendChild(this.div);\n    var self = this;\n    this.listeners = [google.maps.event.addListener(this, 'position_changed', function () {\n      self.draw();\n    }), google.maps.event.addListener(this, 'text_changed', function () {\n      self.draw();\n    }), google.maps.event.addListener(this, 'zindex_changed', function () {\n      self.draw();\n    })];\n  }; // Marker Label onRemove\n\n\n  MarkerLabel.prototype.onRemove = function () {\n    this.div.parentNode.removeChild(this.div);\n\n    for (var i = 0, I = this.listeners.length; i < I; ++i) {\n      google.maps.event.removeListener(this.listeners[i]);\n    }\n  }; // Implement draw\n\n\n  MarkerLabel.prototype.draw = function () {\n    var projection = this.getProjection();\n    var position = projection.fromLatLngToDivPixel(this.get('position'));\n    var div = this.div;\n    this.div.innerHTML = this.get('text').toString();\n    div.style.zIndex = this.get('zIndex'); // Allow label to overlay marker\n\n    div.style.position = 'absolute';\n    div.style.display = 'block';\n    div.style.left = position.x - div.offsetWidth / 2 + 'px';\n    div.style.top = position.y - div.offsetHeight + 'px';\n  };\n\n  $(document).ready(function () {\n    // initialize all vars here to avoid hoisting related misunderstanding.\n    var map, info_window, saved_lat_lng, info_content; // Localized Data\n\n    var loc_default_latitude = parseFloat(localized_data.default_latitude);\n    var loc_default_longitude = parseFloat(localized_data.default_longitude);\n    var loc_manual_lat = parseFloat(localized_data.manual_lat);\n    var loc_manual_lng = parseFloat(localized_data.manual_lng);\n    var loc_map_zoom_level = parseInt(localized_data.map_zoom_level);\n    var display_map_info = localized_data.display_map_info;\n    var cat_icon = localized_data.cat_icon;\n    var info_content = localized_data.info_content;\n    loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;\n    loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;\n    $manual_lat = $('#manual_lat');\n    $manual_lng = $('#manual_lng');\n    saved_lat_lng = {\n      lat: loc_manual_lat,\n      lng: loc_manual_lng\n    }; // create an info window for map\n\n    if (display_map_info) {\n      info_window = new google.maps.InfoWindow({\n        content: info_content,\n        maxWidth: 400\n        /*Add configuration for max width*/\n\n      });\n    }\n\n    function initMap() {\n      /* Create new map instance*/\n      map = new google.maps.Map(document.getElementById('gmap'), {\n        zoom: loc_map_zoom_level,\n        center: saved_lat_lng\n      });\n      /*var marker = new google.maps.Marker({\n          map: map,\n          position: saved_lat_lng\n      });*/\n\n      var marker = new Marker({\n        position: saved_lat_lng,\n        map: map,\n        icon: {\n          path: MAP_PIN,\n          fillColor: 'transparent',\n          fillOpacity: 1,\n          strokeColor: '',\n          strokeWeight: 0\n        },\n        map_icon_label: '<div class=\"atbd_map_shape\"><i class=\"' + cat_icon + '\"></i></div>'\n      });\n\n      if (display_map_info) {\n        marker.addListener('click', function () {\n          info_window.open(map, marker);\n        });\n        google.maps.event.addListener(info_window, 'domready', function () {\n          var closeBtn = $('.iw-close-btn').get();\n          google.maps.event.addDomListener(closeBtn[0], 'click', function () {\n            info_window.close();\n          });\n        });\n      }\n    }\n\n    initMap(); //Convert address tags to google map links -\n\n    $('address').each(function () {\n      var link = \"<a href='http://maps.google.com/maps?q=\" + encodeURIComponent($(this).text()) + \"' target='_blank'>\" + $(this).text() + \"</a>\";\n      $(this).html(link);\n    });\n  });\n})(jQuery);\n\n//# sourceURL=webpack:///./assets/src/js/map-scripts/single-listing/google-map.js?");

/***/ }),

/***/ 6:
/*!**********************************************************************!*\
  !*** multi ./assets/src/js/map-scripts/single-listing/google-map.js ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("module.exports = __webpack_require__(/*! ./assets/src/js/map-scripts/single-listing/google-map.js */\"./assets/src/js/map-scripts/single-listing/google-map.js\");\n\n\n//# sourceURL=webpack:///multi_./assets/src/js/map-scripts/single-listing/google-map.js?");

/***/ })

/******/ });