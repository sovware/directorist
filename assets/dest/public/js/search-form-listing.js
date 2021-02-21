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
/******/ 	return __webpack_require__(__webpack_require__.s = 4);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/components/search-form-listing.js":
/*!*********************************************************!*\
  !*** ./assets/src/js/components/search-form-listing.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function ($) {
  $('body').on('click', '.search_listing_types', function (event) {
    event.preventDefault();
    var listing_type = $(this).attr('data-listing_type');
    var type_current = $('.directorist-listing-type-selection__link--current');

    if (type_current.length) {
      type_current.removeClass('directorist-listing-type-selection__link--current');
    }

    $('#listing_type').val(listing_type);
    $(this).addClass('directorist-listing-type-selection__link--current');
    var form_data = new FormData();
    form_data.append('action', 'atbdp_listing_types_form');
    form_data.append('listing_type', listing_type);
    $('.directorist-search-form-box').addClass('atbdp-form-fade');
    $.ajax({
      method: 'POST',
      processData: false,
      contentType: false,
      url: atbdp_search.ajax_url,
      data: form_data,
      success: function success(response) {
        console.log(response);
        console.log("not response");

        if (response) {
          $('.directorist-search-form-box').empty().html(response);
        }

        $('.directorist-search-form-box').removeClass('atbdp-form-fade');
      },
      error: function error(_error) {
        console.log(_error);
      }
    });
  }); // Advance search
  // Populate atbdp child terms dropdown

  $('.bdas-terms').on('change', 'select', function (e) {
    e.preventDefault();
    var $this = $(this);
    var taxonomy = $this.data('taxonomy');
    var parent = $this.data('parent');
    var value = $this.val();
    var classes = $this.attr('class');
    $this.closest('.bdas-terms').find('input.bdas-term-hidden').val(value);
    $this.parent().find('div:first').remove();

    if (parent != value) {
      $this.parent().append('<div class="bdas-spinner"></div>');
      var data = {
        action: 'bdas_public_dropdown_terms',
        taxonomy: taxonomy,
        parent: value,
        class: classes,
        security: atbdp_search.ajaxnonce
      };
      $.post(atbdp_search.ajax_url, data, function (response) {
        $this.parent().find('div:first').remove();
        $this.parent().append(response);
      });
    }
  }); // load custom fields of the selected category in the search form

  $('body').on('change', '.bdas-category-search, #at_biz_dir-category', function () {
    var $search_elem = $(this).closest('form').find('.atbdp-custom-fields-search');

    if ($search_elem.length) {
      $search_elem.html('<div class="atbdp-spinner"></div>');
      var data = {
        action: 'atbdp_custom_fields_search',
        term_id: $(this).val(),
        security: atbdp_search.ajaxnonce
      };
      $.post(atbdp_search.ajax_url, data, function (response) {
        $search_elem.html(response);
        var item = $('.custom-control').closest('.bads-custom-checks');
        item.each(function (index, el) {
          var count = 0;
          var abc = $(el)[0];
          var abc2 = $(abc).children('.custom-control');

          if (abc2.length <= 4) {
            $(abc2).closest('.bads-custom-checks').next('a.more-or-less').hide();
          }

          $(abc2).slice(4, abc2.length).hide();
        });
      });
    }
  });
  $('.address_result').hide();

  if (atbdp_search_listing.i18n_text.select_listing_map === 'google') {
    function initialize() {
      var options = atbdp_search_listing.countryRestriction ? {
        types: ['geocode'],
        componentRestrictions: {
          country: atbdp_search_listing.restricted_countries
        }
      } : '';
      var input = document.getElementById('address');
      var autocomplete = new google.maps.places.Autocomplete(input, options);
      google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var place = autocomplete.getPlace();
        document.getElementById('cityLat').value = place.geometry.location.lat();
        document.getElementById('cityLng').value = place.geometry.location.lng();
      });
    }

    google.maps.event.addDomListener(window, 'load', initialize);
  } else if (atbdp_search_listing.i18n_text.select_listing_map === 'openstreet') {
    $('#address, #q_addressss,.atbdp-search-address').on('keyup', function (event) {
      event.preventDefault();
      var search = $(this).val();
      $(this).parent().next('.address_result').css({
        display: 'block'
      });

      if (search === '') {
        $(this).parent().next('.address_result').css({
          display: 'none'
        });
      }

      var res = '';
      $.ajax({
        url: "https://nominatim.openstreetmap.org/?q=%27+".concat(search, "+%27&format=json"),
        type: 'POST',
        data: {},
        success: function success(data) {
          for (var i = 0; i < data.length; i++) {
            res += "<li><a href=\"#\" data-lat=".concat(data[i].lat, " data-lon=").concat(data[i].lon, ">").concat(data[i].display_name, "</a></li>");
          }

          $(event.target).parent().next('.address_result').html("<ul>".concat(res, "</ul>"));
        },
        error: function error(_error2) {
          console.log({
            error: _error2
          });
        }
      });
    }); // hide address result when click outside the input field

    $(document).on('click', function (e) {
      if (!$(e.target).closest('#address, #q_addressss,.atbdp-search-address').length) {
        $('.address_result').hide();
      }
    });
    $('body').on('click', '.address_result ul li a', function (event) {
      event.preventDefault();
      var text = $(this).text();
      var lat = $(this).data('lat');
      var lon = $(this).data('lon');
      $('#cityLat').val(lat);
      $('#cityLng').val(lon);
      $(this).closest('.address_result').parent().find('#address, #q_addressss,.atbdp-search-address').val(text);
      $('.address_result').hide();
    });
  }

  if ($('#address, #q_addressss,.atbdp-search-address').val() === '') {
    $(this).parent().next('.address_result').css({
      display: 'none'
    });
  }
})(jQuery);

/***/ }),

/***/ 4:
/*!***************************************************************!*\
  !*** multi ./assets/src/js/components/search-form-listing.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/components/search-form-listing.js */"./assets/src/js/components/search-form-listing.js");


/***/ })

/******/ });
//# sourceMappingURL=search-form-listing.js.map