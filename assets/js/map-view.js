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
/******/ 	return __webpack_require__(__webpack_require__.s = 16);
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

/***/ "./assets/src/js/map-scripts/map-view.js":
/*!***********************************************!*\
  !*** ./assets/src/js/map-scripts/map-view.js ***!
  \***********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../lib/helper */ \"./assets/src/js/lib/helper.js\");\n\nvar atbdp_map = Object(_lib_helper__WEBPACK_IMPORTED_MODULE_0__[\"get_dom_data\"])('atbdp_map'); // Define Marker Shapes\n\nvar MAP_PIN = 'M0-48c-9.8 0-17.7 7.8-17.7 17.4 0 15.5 17.7 30.6 17.7 30.6s17.7-15.4 17.7-30.6c0-9.6-7.9-17.4-17.7-17.4z';\n\nvar inherits = function inherits(childCtor, parentCtor) {\n  /** @constructor */\n  function tempCtor() {}\n\n  tempCtor.prototype = parentCtor.prototype;\n  childCtor.superClass_ = parentCtor.prototype;\n  childCtor.prototype = new tempCtor();\n  childCtor.prototype.constructor = childCtor;\n};\n\nfunction Marker(options) {\n  google.maps.Marker.apply(this, arguments);\n\n  if (options.map_icon_label) {\n    this.MarkerLabel = new MarkerLabel({\n      map: this.map,\n      marker: this,\n      text: options.map_icon_label\n    });\n    this.MarkerLabel.bindTo('position', this, 'position');\n  }\n} // Apply the inheritance\n\n\ninherits(Marker, google.maps.Marker); // Custom Marker SetMap\n\nMarker.prototype.setMap = function () {\n  google.maps.Marker.prototype.setMap.apply(this, arguments);\n  this.MarkerLabel && this.MarkerLabel.setMap.apply(this.MarkerLabel, arguments);\n}; // Marker Label Overlay\n\n\nvar MarkerLabel = function MarkerLabel(options) {\n  var self = this;\n  this.setValues(options); // Create the label container\n\n  this.div = document.createElement('div');\n  this.div.className = 'map-icon-label'; // Trigger the marker click handler if clicking on the label\n\n  google.maps.event.addDomListener(this.div, 'click', function (e) {\n    e.stopPropagation && e.stopPropagation();\n    google.maps.event.trigger(self.marker, 'click');\n  });\n}; // Create MarkerLabel Object\n\n\nMarkerLabel.prototype = new google.maps.OverlayView(); // Marker Label onAdd\n\nMarkerLabel.prototype.onAdd = function () {\n  var pane = this.getPanes().overlayImage.appendChild(this.div);\n  var self = this;\n  this.listeners = [google.maps.event.addListener(this, 'position_changed', function () {\n    self.draw();\n  }), google.maps.event.addListener(this, 'text_changed', function () {\n    self.draw();\n  }), google.maps.event.addListener(this, 'zindex_changed', function () {\n    self.draw();\n  })];\n}; // Marker Label onRemove\n\n\nMarkerLabel.prototype.onRemove = function () {\n  this.div.parentNode.removeChild(this.div);\n\n  for (var i = 0, I = this.listeners.length; i < I; ++i) {\n    google.maps.event.removeListener(this.listeners[i]);\n  }\n}; // Implement draw\n\n\nMarkerLabel.prototype.draw = function () {\n  var projection = this.getProjection();\n  var position = projection.fromLatLngToDivPixel(this.get('position'));\n  var div = this.div;\n  this.div.innerHTML = this.get('text').toString();\n  div.style.zIndex = this.get('zIndex'); // Allow label to overlay marker\n\n  div.style.position = 'absolute';\n  div.style.display = 'block';\n  div.style.left = \"\".concat(position.x - div.offsetWidth / 2, \"px\");\n  div.style.top = \"\".concat(position.y - div.offsetHeight, \"px\");\n};\n\n(function ($) {\n  // map view\n\n  /**\n   *  Render a Google Map onto the selected jQuery element.\n   *\n   *  @since    5.0.0\n   */\n  var at_icon = [];\n\n  function atbdp_rander_map($el) {\n    $el.addClass('atbdp-map-loaded'); // var\n\n    var $markers = $el.find('.marker'); // vars\n\n    var args = {\n      zoom: parseInt(atbdp_map.zoom),\n      center: new google.maps.LatLng(0, 0),\n      mapTypeId: google.maps.MapTypeId.ROADMAP,\n      zoomControl: true,\n      scrollwheel: false,\n      gestureHandling: 'cooperative',\n      averageCenter: true,\n      scrollWheelZoom: 'center'\n    }; // create map\n\n    var map = new google.maps.Map($el[0], args); // add a markers reference\n\n    map.markers = []; // set map type\n\n    map.type = $el.data('type');\n    var infowindow = new google.maps.InfoWindow({\n      content: ''\n    }); // add markers\n\n    $markers.each(function () {\n      atbdp_add_marker($(this), map, infowindow);\n    }); // center map\n\n    atbdp_center_map(map); // update map when contact details fields are updated in the custom post type 'acadp_listings'\n\n    var mcOptions = {\n      // imagePath: atbdp_map.plugin_url+'public/assets/images/m',\n      cssClass: 'marker-cluster-shape'\n    };\n\n    if (map.type === 'markerclusterer') {\n      var markerCluster = new MarkerClusterer(map, map.markers, mcOptions);\n    }\n  }\n  /**\n   *  Add a marker to the selected Google Map.\n   *\n   *  @since    1.0.0\n   */\n\n\n  function atbdp_add_marker($marker, map, infowindow) {\n    // var\n    var latlng = new google.maps.LatLng($marker.data('latitude'), $marker.data('longitude')); // check to see if any of the existing markers match the latlng of the new marker\n\n    if (map.markers.length) {\n      for (var i = 0; i < map.markers.length; i++) {\n        var existing_marker = map.markers[i];\n        var pos = existing_marker.getPosition(); // if a marker already exists in the same position as this marker\n\n        if (latlng.equals(pos)) {\n          // update the position of the coincident marker by applying a small multipler to its coordinates\n          var latitude = latlng.lat() + (Math.random() - 0.5) / 1500; // * (Math.random() * (max - min) + min);\n\n          var longitude = latlng.lng() + (Math.random() - 0.5) / 1500; // * (Math.random() * (max - min) + min);\n\n          latlng = new google.maps.LatLng(latitude, longitude);\n        }\n      }\n    }\n\n    var icon = $marker.data('icon');\n    var marker = new Marker({\n      position: latlng,\n      map: map,\n      icon: {\n        path: MAP_PIN,\n        fillColor: 'transparent',\n        fillOpacity: 1,\n        strokeColor: '',\n        strokeWeight: 0\n      },\n      map_icon_label: icon !== undefined && \"<div class=\\\"atbd_map_shape\\\"><i class=\\\"\".concat(icon, \"\\\"></i></div>\")\n    }); // add to array\n\n    map.markers.push(marker); // if marker contains HTML, add it to an infoWindow\n\n    if ($marker.html()) {\n      // map info window close button\n      google.maps.event.addListener(infowindow, 'domready', function () {\n        var closeBtn = $('.iw-close-btn').get();\n        google.maps.event.addDomListener(closeBtn[0], 'click', function () {\n          infowindow.close();\n        });\n      }); // show info window when marker is clicked\n\n      google.maps.event.addListener(marker, 'click', function () {\n        if (atbdp_map.disable_info_window === 'no') {\n          infowindow.setContent($marker.html());\n          infowindow.open(map, marker);\n        }\n      });\n    }\n  }\n  /**\n   *  Center the map, showing all markers attached to this map.\n   *\n   *  @since    1.0.0\n   */\n\n\n  function atbdp_center_map(map) {\n    // vars\n    var bounds = new google.maps.LatLngBounds(); // loop through all markers and create bounds\n\n    $.each(map.markers, function (i, marker) {\n      var latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());\n      bounds.extend(latlng);\n    }); // only 1 marker?\n\n    if (map.markers.length !== 1) {\n      // set center of map\n      map.setCenter(bounds.getCenter());\n      map.setZoom(parseInt(atbdp_map.zoom));\n    } else {\n      // fit to bounds\n      map.fitBounds(bounds);\n    }\n  }\n\n  function setup_info_window() {\n    var abc = document.querySelectorAll('div');\n    abc.forEach(function (el, index) {\n      if (el.innerText === 'atgm_marker') {\n        // console.log(at_icon)\n        el.innerText = ' ';\n        el.innerHTML = \"<i class=\\\"la \".concat(at_icon, \" atbd_map_marker_icon\\\"></i>\");\n      } // ${$marker.data('icon')}\n\n    });\n    document.querySelectorAll('div').forEach(function (el1, index) {\n      if (el1.style.backgroundImage.split('/').pop() === 'm1.png\")') {\n        el1.addEventListener('click', function () {\n          setInterval(function () {\n            var abc = document.querySelectorAll('div');\n            abc.forEach(function (el, index) {\n              if (el.innerText === 'atgm_marker') {\n                el.innerText = ' ';\n                el.innerHTML = \"<i class=\\\"la \".concat(at_icon, \" atbd_map_marker_icon\\\"></i>\");\n              }\n            });\n          }, 100);\n        });\n      }\n    });\n  }\n\n  function setup_map() {\n    // render map in the custom post\n    $('.atbdp-map').each(function () {\n      atbdp_rander_map($(this));\n    });\n  }\n\n  window.addEventListener('load', setup_map);\n  window.addEventListener('load', setup_info_window);\n  window.addEventListener('directorist-reload-listings-map-archive', setup_map);\n  window.addEventListener('directorist-reload-listings-map-archive', setup_info_window);\n})(jQuery);\n\n//# sourceURL=webpack:///./assets/src/js/map-scripts/map-view.js?");

/***/ }),

/***/ 16:
/*!*****************************************************!*\
  !*** multi ./assets/src/js/map-scripts/map-view.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("module.exports = __webpack_require__(/*! ./assets/src/js/map-scripts/map-view.js */\"./assets/src/js/map-scripts/map-view.js\");\n\n\n//# sourceURL=webpack:///multi_./assets/src/js/map-scripts/map-view.js?");

/***/ })

/******/ });