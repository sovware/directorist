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

/***/ "./assets/src/js/global/map-scripts/map-view.js":
/*!******************************************************!*\
  !*** ./assets/src/js/global/map-scripts/map-view.js ***!
  \******************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../../lib/helper */ "./assets/src/js/lib/helper.js");
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_lib_helper__WEBPACK_IMPORTED_MODULE_0__);

var atbdp_map = Object(_lib_helper__WEBPACK_IMPORTED_MODULE_0__["get_dom_data"])('atbdp_map'); // Define Marker Shapes

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
} // Apply the inheritance


inherits(Marker, google.maps.Marker); // Custom Marker SetMap

Marker.prototype.setMap = function () {
  google.maps.Marker.prototype.setMap.apply(this, arguments);
  this.MarkerLabel && this.MarkerLabel.setMap.apply(this.MarkerLabel, arguments);
}; // Marker Label Overlay


var MarkerLabel = function MarkerLabel(options) {
  var self = this;
  this.setValues(options); // Create the label container

  this.div = document.createElement('div');
  this.div.className = 'map-icon-label'; // Trigger the marker click handler if clicking on the label

  google.maps.event.addDomListener(this.div, 'click', function (e) {
    e.stopPropagation && e.stopPropagation();
    google.maps.event.trigger(self.marker, 'click');
  });
}; // Create MarkerLabel Object


MarkerLabel.prototype = new google.maps.OverlayView(); // Marker Label onAdd

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
}; // Marker Label onRemove


MarkerLabel.prototype.onRemove = function () {
  this.div.parentNode.removeChild(this.div);

  for (var i = 0, I = this.listeners.length; i < I; ++i) {
    google.maps.event.removeListener(this.listeners[i]);
  }
}; // Implement draw


MarkerLabel.prototype.draw = function () {
  var projection = this.getProjection();
  var position = projection.fromLatLngToDivPixel(this.get('position'));
  var div = this.div;
  this.div.innerHTML = this.get('text').toString();
  div.style.zIndex = this.get('zIndex'); // Allow label to overlay marker

  div.style.position = 'absolute';
  div.style.display = 'block';
  div.style.left = "".concat(position.x - div.offsetWidth / 2, "px");
  div.style.top = "".concat(position.y - div.offsetHeight, "px");
};

(function ($) {
  // map view

  /**
   *  Render a Google Map onto the selected jQuery element.
   *
   *  @since    5.0.0
   */
  var at_icon = [];

  function atbdp_rander_map($el) {
    $el.addClass('atbdp-map-loaded'); // var

    var $markers = $el.find('.marker'); // vars

    var args = {
      zoom: parseInt(atbdp_map.zoom),
      center: new google.maps.LatLng(0, 0),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      zoomControl: true,
      scrollwheel: false,
      gestureHandling: 'cooperative',
      averageCenter: true,
      scrollWheelZoom: 'center'
    }; // create map

    var map = new google.maps.Map($el[0], args); // add a markers reference

    map.markers = []; // set map type

    map.type = $el.data('type');
    var infowindow = new google.maps.InfoWindow({
      content: ''
    }); // add markers

    $markers.each(function () {
      atbdp_add_marker($(this), map, infowindow);
    });
    var cord = {
      lat: Number(atbdp_map.default_latitude) ? Number(atbdp_map.default_latitude) : 40.7127753,
      lng: Number(atbdp_map.default_longitude) ? Number(atbdp_map.default_longitude) : -74.0059728
    };

    if ($markers.length) {
      cord.lat = Number($markers[0].getAttribute('data-latitude'));
      cord.lng = Number($markers[0].getAttribute('data-longitude'));
    } // center map


    atbdp_center_map(map, cord);
    var mcOptions = new MarkerClusterer(map, [], {
      imagePath: atbdp_map.plugin_url + 'assets/images/m'
    });
    mcOptions.setStyles(mcOptions.getStyles().map(function (style) {
      style.textColor = '#fff';
      return style;
    }));

    if (map.type === 'markerclusterer') {
      //const markerCluster = new MarkerClusterer(map, map.markers, mcOptions);
      mcOptions.addMarkers(map.markers);
    }
  }
  /**
   *  Add a marker to the selected Google Map.
   *
   *  @since    1.0.0
   */


  function atbdp_add_marker($marker, map, infowindow) {
    // var
    var latlng = new google.maps.LatLng($marker.data('latitude'), $marker.data('longitude')); // check to see if any of the existing markers match the latlng of the new marker

    if (map.markers.length) {
      for (var i = 0; i < map.markers.length; i++) {
        var existing_marker = map.markers[i];
        var pos = existing_marker.getPosition(); // if a marker already exists in the same position as this marker

        if (latlng.equals(pos)) {
          // update the position of the coincident marker by applying a small multipler to its coordinates
          var latitude = latlng.lat() + (Math.random() - 0.5) / 1500; // * (Math.random() * (max - min) + min);

          var longitude = latlng.lng() + (Math.random() - 0.5) / 1500; // * (Math.random() * (max - min) + min);

          latlng = new google.maps.LatLng(latitude, longitude);
        }
      }
    }

    var icon = $marker.data('icon');
    var marker = new Marker({
      position: latlng,
      map: map,
      icon: {
        path: MAP_PIN,
        fillColor: 'transparent',
        fillOpacity: 1,
        strokeColor: '',
        strokeWeight: 0
      },
      map_icon_label: icon !== undefined && "<div class=\"atbd_map_shape\"><i class=\"".concat(icon, "\"></i></div>")
    }); // add to array

    map.markers.push(marker); // if marker contains HTML, add it to an infoWindow

    if ($marker.html()) {
      // map info window close button
      google.maps.event.addListener(infowindow, 'domready', function () {
        var closeBtn = $('.iw-close-btn').get();
        google.maps.event.addDomListener(closeBtn[0], 'click', function () {
          infowindow.close();
        });
      }); // show info window when marker is clicked

      google.maps.event.addListener(marker, 'click', function () {
        if (atbdp_map.disable_info_window === 'no') {
          var marker_childrens = $($marker).children();

          if (marker_childrens.length) {
            var marker_content = marker_childrens[0];
            $(marker_content).addClass('map-info-wrapper--show');
          }

          infowindow.setContent($marker.html());
          infowindow.open(map, marker);
        }
      });
    }
  }
  /**
   *  Center the map, showing all markers attached to this map.
   *
   *  @since    1.0.0
   */


  function atbdp_center_map(map, cord) {
    map.setCenter(cord);
    map.setZoom(parseInt(atbdp_map.zoom));
  }

  function setup_info_window() {
    var abc = document.querySelectorAll('div');
    abc.forEach(function (el, index) {
      if (el.innerText === 'atgm_marker') {
        // console.log(at_icon)
        el.innerText = ' ';
        el.innerHTML = "<i class=\"la ".concat(at_icon, " atbd_map_marker_icon\"></i>");
      } // ${$marker.data('icon')}

    });
    document.querySelectorAll('div').forEach(function (el1, index) {
      if (el1.style.backgroundImage.split('/').pop() === 'm1.png")') {
        el1.addEventListener('click', function () {
          setInterval(function () {
            var abc = document.querySelectorAll('div');
            abc.forEach(function (el, index) {
              if (el.innerText === 'atgm_marker') {
                el.innerText = ' ';
                el.innerHTML = "<i class=\"la ".concat(at_icon, " atbd_map_marker_icon\"></i>");
              }
            });
          }, 100);
        });
      }
    });
  }

  function setup_map() {
    // render map in the custom post
    $('.atbdp-map').each(function () {
      atbdp_rander_map($(this));
    });
  }

  window.addEventListener('load', setup_map);
  window.addEventListener('load', setup_info_window);
  window.addEventListener('directorist-reload-listings-map-archive', setup_map);
  window.addEventListener('directorist-reload-listings-map-archive', setup_info_window);
  $(document).ready(function () {
    $('body').find('.map-info-wrapper').addClass('map-info-wrapper--show');
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

/***/ 17:
/*!************************************************************!*\
  !*** multi ./assets/src/js/global/map-scripts/map-view.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/global/map-scripts/map-view.js */"./assets/src/js/global/map-scripts/map-view.js");


/***/ })

/******/ });
//# sourceMappingURL=global-map-view.js.map