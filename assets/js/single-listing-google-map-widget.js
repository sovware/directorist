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
/******/ 	return __webpack_require__(__webpack_require__.s = 22);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/global/map-scripts/single-listing/google-map-widget.js":
/*!******************************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/single-listing/google-map-widget.js ***!
  \******************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* Widget google map */

window.addEventListener('DOMContentLoaded', function () {
  ;
  (function ($) {
    if ($('#gmap-widget').length) {
      var MAP_PIN = 'M0-48c-9.8 0-17.7 7.8-17.7 17.4 0 15.5 17.7 30.6 17.7 30.6s17.7-15.4 17.7-30.6c0-9.6-7.9-17.4-17.7-17.4z';
      var inherits = function inherits(childCtor, parentCtor) {
        /** @constructor */
        function tempCtor() {}
        tempCtor.prototype = parentCtor.prototype;
        childCtor.superClass_ = parentCtor.prototype;
        childCtor.prototype = new tempCtor();
        childCtor.prototype.constructor = childCtor;
      };
      function Marker(options) {
        google.maps.Marker.apply(this, arguments);
        if (options.map_icon_label) {
          this.MarkerLabel = new MarkerLabel({
            map: this.map,
            marker: this,
            text: options.map_icon_label
          });
          this.MarkerLabel.bindTo('position', this, 'position');
        }
      }

      // Apply the inheritance
      inherits(Marker, google.maps.Marker);

      // Custom Marker SetMap
      Marker.prototype.setMap = function () {
        google.maps.Marker.prototype.setMap.apply(this, arguments);
        this.MarkerLabel && this.MarkerLabel.setMap.apply(this.MarkerLabel, arguments);
      };

      // Marker Label Overlay
      var MarkerLabel = function MarkerLabel(options) {
        var self = this;
        this.setValues(options);

        // Create the label container
        this.div = document.createElement('div');
        this.div.className = 'map-icon-label';

        // Trigger the marker click handler if clicking on the label
        google.maps.event.addDomListener(this.div, 'click', function (e) {
          e.stopPropagation && e.stopPropagation();
          google.maps.event.trigger(self.marker, 'click');
        });
      };

      // Create MarkerLabel Object
      MarkerLabel.prototype = new google.maps.OverlayView();

      // Marker Label onAdd
      MarkerLabel.prototype.onAdd = function () {
        var pane = this.getPanes().overlayImage.appendChild(this.div);
        var self = this;
        this.listeners = [google.maps.event.addListener(this, 'position_changed', function () {
          self.draw();
        }), google.maps.event.addListener(this, 'text_changed', function () {
          self.draw();
        }), google.maps.event.addListener(this, 'zindex_changed', function () {
          self.draw();
        })];
      };

      // Marker Label onRemove
      MarkerLabel.prototype.onRemove = function () {
        this.div.parentNode.removeChild(this.div);
        for (var i = 0, I = this.listeners.length; i < I; ++i) {
          google.maps.event.removeListener(this.listeners[i]);
        }
      };

      // Implement draw
      MarkerLabel.prototype.draw = function () {
        var projection = this.getProjection();
        var position = projection.fromLatLngToDivPixel(this.get('position'));
        var div = this.div;
        this.div.innerHTML = this.get('text').toString();
        div.style.zIndex = this.get('zIndex'); // Allow label to overlay marker
        div.style.position = 'absolute';
        div.style.display = 'block';
        div.style.left = position.x - div.offsetWidth / 2 + 'px';
        div.style.top = position.y - div.offsetHeight + 'px';
      };
      $(document).ready(function () {
        // initialize all vars here to avoid hoisting related misunderstanding.
        var map, info_window, saved_lat_lng, info_content;

        // Localized Data
        var map_container = localized_data_widget.map_container_id ? localized_data_widget.map_container_id : 'gmap';
        var loc_default_latitude = parseFloat(localized_data_widget.default_latitude);
        var loc_default_longitude = parseFloat(localized_data_widget.default_longitude);
        var loc_manual_lat = parseFloat(localized_data_widget.manual_lat);
        var loc_manual_lng = parseFloat(localized_data_widget.manual_lng);
        var loc_map_zoom_level = parseInt(localized_data_widget.map_zoom_level);
        var display_map_info = localized_data_widget.display_map_info;
        var cat_icon = localized_data_widget.cat_icon;
        var info_content = localized_data_widget.info_content;
        loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
        loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;
        $manual_lat = $('#manual_lat');
        $manual_lng = $('#manual_lng');
        saved_lat_lng = {
          lat: loc_manual_lat,
          lng: loc_manual_lng
        };

        // create an info window for map
        if (display_map_info) {
          info_window = new google.maps.InfoWindow({
            content: info_content,
            maxWidth: 400 /*Add configuration for max width*/
          });
        }

        function initMap() {
          /* Create new map instance*/
          map = new google.maps.Map(document.getElementById(map_container), {
            zoom: loc_map_zoom_level,
            center: saved_lat_lng
          });
          /*var marker = new google.maps.Marker({
              map: map,
              position: saved_lat_lng
          });*/
          var marker = new Marker({
            position: saved_lat_lng,
            map: map,
            icon: {
              path: MAP_PIN,
              fillColor: 'transparent',
              fillOpacity: 1,
              strokeColor: '',
              strokeWeight: 0
            },
            map_icon_label: '<div class="atbd_map_shape">' + cat_icon + '</div>'
          });
          if (display_map_info) {
            marker.addListener('click', function () {
              info_window.open(map, marker);
            });
            google.maps.event.addListener(info_window, 'domready', function () {
              var closeBtn = $('.iw-close-btn').get();
              google.maps.event.addDomListener(closeBtn[0], 'click', function () {
                info_window.close();
              });
            });
          }
        }
        initMap();
        //Convert address tags to google map links -
        $('address').each(function () {
          var link = "<a href='http://maps.google.com/maps?q=" + encodeURIComponent($(this).text()) + "' target='_blank'>" + $(this).text() + "</a>";
          $(this).html(link);
        });
      });
    }
  })(jQuery);
});

/***/ }),

/***/ 22:
/*!************************************************************************************!*\
  !*** multi ./assets/src/js/global/map-scripts/single-listing/google-map-widget.js ***!
  \************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/global/map-scripts/single-listing/google-map-widget.js */"./assets/src/js/global/map-scripts/single-listing/google-map-widget.js");


/***/ })

/******/ });
//# sourceMappingURL=single-listing-google-map-widget.js.map