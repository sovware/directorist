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
/******/ 	return __webpack_require__(__webpack_require__.s = 8);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/global/map-scripts/add-listing/openstreet-map.js":
/*!************************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/add-listing/openstreet-map.js ***!
  \************************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../../../lib/helper */ "./assets/src/js/lib/helper.js");
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_lib_helper__WEBPACK_IMPORTED_MODULE_0__);

;

(function ($) {
  $(document).ready(function () {
    var localized_data = Object(_lib_helper__WEBPACK_IMPORTED_MODULE_0__["get_dom_data"])('map_data'); // Localized Data

    var loc_default_latitude = parseFloat(localized_data.default_latitude);
    var loc_default_longitude = parseFloat(localized_data.default_longitude);
    var loc_manual_lat = parseFloat(localized_data.manual_lat);
    var loc_manual_lng = parseFloat(localized_data.manual_lng);
    var loc_map_zoom_level = parseInt(localized_data.map_zoom_level);
    var loc_map_icon = localized_data.map_icon;
    loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
    loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;

    function mapLeaflet(lat, lon) {
      // @todo @kowsar / remove later. fix js error
      if ($("#gmap").length == 0) {
        return;
      }

      var fontAwesomeIcon = L.icon({
        iconUrl: loc_map_icon,
        iconSize: [20, 25]
      });
      var mymap = L.map('gmap').setView([lat, lon], loc_map_zoom_level);
      L.marker([lat, lon], {
        icon: fontAwesomeIcon,
        draggable: true
      }).addTo(mymap).addTo(mymap).on("drag", function (e) {
        var marker = e.target;
        var position = marker.getLatLng();
        $('#manual_lat').val(position.lat);
        $('#manual_lng').val(position.lng);
        $.ajax({
          url: "https://nominatim.openstreetmap.org/reverse?format=json&lon=".concat(position.lng, "&lat=").concat(position.lat),
          type: 'POST',
          data: {},
          success: function success(data) {
            $('.directorist-location-js').val(data.display_name);
          }
        });
      });
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(mymap);
    }

    $('.directorist-location-js').each(function (id, elm) {
      $(elm).on('keyup', function (event) {
        event.preventDefault();

        if (event.keyCode !== 40 && event.keyCode !== 38) {
          var search = $(elm).val();
          $(elm).siblings('.address_result').css({
            'display': 'block'
          });

          if (search === "") {
            $(elm).siblings('.address_result').css({
              'display': 'none'
            });
          }

          var res = "";
          $.ajax({
            url: "https://nominatim.openstreetmap.org/?q=%27+".concat(search, "+%27&format=json"),
            type: 'POST',
            data: {},
            success: function success(data) {
              //console.log(data);
              for (var i = 0; i < data.length; i++) {
                res += "<li><a href=\"#\" data-lat=".concat(data[i].lat, " data-lon=").concat(data[i].lon, ">").concat(data[i].display_name, "</a></li>");
              }

              $(elm).siblings('.address_result').find('ul').html(res);
            }
          });
        }
      });
    });
    var lat = loc_manual_lat,
        lon = loc_manual_lng;
    mapLeaflet(lat, lon);
    $('body').on('click', '.directorist-form-address-field .address_result ul li a', function (event) {
      document.getElementById('osm').innerHTML = "<div id='gmap'></div>";
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
    }); // Popup controller by keyboard

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
    }); // $('#post').on('submit', function (event) {
    //     event.preventDefault();
    //     return false;
    // });
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

/***/ 8:
/*!******************************************************************************!*\
  !*** multi ./assets/src/js/global/map-scripts/add-listing/openstreet-map.js ***!
  \******************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/global/map-scripts/add-listing/openstreet-map.js */"./assets/src/js/global/map-scripts/add-listing/openstreet-map.js");


/***/ })

/******/ });
//# sourceMappingURL=global-add-listing-openstreet-map-custom-script.js.map