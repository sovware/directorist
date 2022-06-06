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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/public/search-listing.js":
/*!************************************************!*\
  !*** ./assets/src/js/public/search-listing.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function ($) {
  //ad search js

  /* var showMore = atbdp_search_listing.i18n_text.show_more;
  var showLess = atbdp_search_listing.i18n_text.show_less;
  var checkbox = $(".bads-tags .custom-control");
  checkbox.slice(4).hide();
  var show_more = $(".more-less");
  show_more.on("click", function (e) {
      e.preventDefault();
      var txt = checkbox.slice(4).is(":visible") ? showMore : showLess;
      $(this).text(txt);
      checkbox.slice(4).slideToggle(200);
      $(this).toggleClass("ad");
  });
  if (checkbox.length <= 4) {
      show_more.remove();
  }
      var item = $('.custom-control').closest('.bads-custom-checks');
  item.each(function (index, el) {
      var count = 0;
      var abc = $(el)[0];
      var abc2 = $(abc).children('.custom-control');
      if(abc2.length <= 4){
          $(abc2).closest('.bads-custom-checks').next('a.more-or-less').hide();
      }
      $(abc2).slice(4, abc2.length).hide();
    });
  
      $(".bads-custom-checks").parent(".form-group").addClass("ads-filter-tags"); */
  function defaultTags() {
    $('.directorist-btn-ml').each(function (index, element) {
      var item = $(element).siblings('.atbdp_cf_checkbox, .direcorist-search-field-tag, .directorist-search-tags');
      var abc2 = $(item).find('.directorist-checkbox');
      $(abc2).slice(4, abc2.length).fadeOut();
    });
  }

  $(window).on('load', defaultTags);
  window.addEventListener('triggerSlice', defaultTags);
  $('body').on('click', '.directorist-btn-ml', function (event) {
    event.preventDefault();
    var item = $(this).siblings('.atbdp_cf_checkbox, .direcorist-search-field-tag, .directorist-search-tags');
    var abc2 = $(item).find('.directorist-checkbox ');
    $(abc2).slice(4, abc2.length).fadeOut();
    $(this).toggleClass('active');

    if ($(this).hasClass('active')) {
      $(this).text(atbdp_search_listing.i18n_text.show_less);
      $(abc2).slice(4, abc2.length).fadeIn();
    } else {
      $(this).text(atbdp_search_listing.i18n_text.show_more);
      $(abc2).slice(4, abc2.length).fadeOut();
    }
  });
  /* Advanced search */

  var ad = $(".directorist-search-float .directorist-advanced-filter");
  ad.css({
    visibility: 'hidden',
    height: '0'
  });

  var adsFilterHeight = function adsFilterHeight() {
    return $('.directorist-advanced-filter .directorist-advanced-filter__action').innerHeight();
  };

  console.log(adsFilterHeight());
  var adsItemsHeight;

  function getItemsHeight(selector) {
    var advElmHeight;
    var basicElmHeight;

    var adsAdvItemHeight = function adsAdvItemHeight() {
      return $(selector).closest('.directorist-search-form-box, .directorist-archive-contents, .directorist-search-form').find('.directorist-advanced-filter__advanced--element');
    };

    var adsBasicItemHeight = function adsBasicItemHeight() {
      return $(selector).closest('.directorist-search-form-box, .directorist-archive-contents').find('.directorist-advanced-filter__basic');
    };

    for (var i = 0; i <= adsAdvItemHeight().length; i++) {
      adsAdvItemHeight().length <= 1 ? advElmHeight = adsAdvItemHeight().innerHeight() : advElmHeight = adsAdvItemHeight().innerHeight() * i;
    }

    if (isNaN(advElmHeight)) {
      advElmHeight = 0;
    }

    var basicElmHeights = adsBasicItemHeight().innerHeight();
    basicElmHeights === undefined ? basicElmHeight = 0 : basicElmHeight = basicElmHeights;
    return adsItemsHeight = advElmHeight + basicElmHeight;
  }

  getItemsHeight('.directorist-filter-btn');
  var count = 0;
  $('body').on('click', '.directorist-listing-type-selection .search_listing_types, .directorist-type-nav .directorist-type-nav__link', function () {
    count = 0;
  });
  /* Toggle overlapped advanced filter wrapper */

  $('body').on("click", '.directorist-filter-btn', function (e) {
    count++;
    e.preventDefault();

    var _this = $(this);

    setTimeout(function () {
      getItemsHeight(_this);
    }, 500);

    _this.toggleClass('directorist-filter-btn--active');

    var currentPos = e.clientY,
        displayPos = window.innerHeight,
        height = displayPos - currentPos;
    var advFilterWrap = $(e.currentTarget).closest('.directorist-search-form, .directorist-archive-contents').find('.directorist-search-float').find('.directorist-advanced-filter');

    if (count % 2 === 0) {
      $(advFilterWrap).css({
        visibility: 'hidden',
        opacity: '0',
        height: '0',
        transition: '.3s ease'
      });
    } else {
      $(advFilterWrap).css({
        visibility: 'visible',
        height: adsItemsHeight + adsFilterHeight() + 50 + 'px',
        transition: '0.3s ease',
        opacity: '1',
        display: 'block'
      });
    }
  });
  /* Hide overlapped advanced filter */

  var directoristAdvFilter = function directoristAdvFilter() {
    return $('.directorist-search-float .directorist-advanced-filter');
  };

  $(document).on('click', function (e) {
    if (!e.target.closest('.directorist-search-form-top, .directorist-listings-header, .directorist-search-form') && !e.target.closest('.directorist-search-float .directorist-advanced-filter')) {
      count = 0;
      directoristAdvFilter().css({
        visibility: 'hidden',
        opacity: '0',
        height: '0',
        transition: '.3s ease'
      });
    }
  });
  $('body').on('click', '.directorist-sortby-dropdown > a, .directorist-viewas-dropdown > a', function () {
    count = 0;
    directoristAdvFilter().css({
      visibility: 'hidden',
      opacity: '0',
      height: '0',
      transition: '.3s ease'
    });
  });
  var ad_slide = $(".directorist-search-slide .directorist-advanced-filter");
  ad_slide.hide().slideUp();
  $('body').on("click", '.directorist-filter-btn', function (e) {
    e.preventDefault();
    var miles = parseInt($('.atbdrs-value').val());
    var default_args = {
      maxValue: 1000,
      minValue: miles,
      maxWidth: '100%',
      barColor: '#d4d5d9',
      barBorder: 'none',
      pointerColor: '#fff',
      pointerBorder: '4px solid #444752'
    };
    var config = default_args;
    $(this).closest('.directorist-search-form, .directorist-archive-contents').find('.directorist-search-slide').find('.directorist-advanced-filter').slideToggle().show();
    $(this).closest('.directorist-search-form, .directorist-archive-contents').find('.directorist-search-slide').find('.directorist-advanced-filter').toggleClass("directorist-advanced-filter--show");
    atbd_callingSlider();
    atbd_slider('.atbdp-range-slider', config);
  });
  $(".directorist-advanced-filter").parents("div").css("overflow", "visible"); //remove preload after window load

  $(window).on('load', function () {
    $("body").removeClass("directorist-preload");
    $('.button.wp-color-result').attr('style', ' ');
  });
  $('body').on("click", '.directorist-mark-as-favorite__btn', function (event) {
    event.preventDefault();
    var data = {
      'action': 'atbdp-favourites-all-listing',
      'post_id': $(this).data('listing_id')
    };
    var fav_tooltip_success = '<span>' + atbdp_search_listing.i18n_text.added_favourite + '</span>';
    var fav_tooltip_warning = '<span>' + atbdp_search_listing.i18n_text.please_login + '</span>';
    $(".directorist-favorite-tooltip").hide();
    $.post(atbdp_search_listing.ajax_url, data, function (response) {
      var post_id = data['post_id'].toString();
      var staElement = $('.directorist-fav_' + post_id);
      var data_id = staElement.attr('data-listing_id');

      if (response === "login_required") {
        staElement.children(".directorist-favorite-tooltip").append(fav_tooltip_warning);
        staElement.children(".directorist-favorite-tooltip").fadeIn();
        setTimeout(function () {
          staElement.children(".directorist-favorite-tooltip").children("span").remove();
        }, 3000);
      } else if ('false' === response) {
        staElement.removeClass('directorist-added-to-favorite');
        $(".directorist-favorite-tooltip span").remove();
      } else {
        if (data_id === post_id) {
          staElement.addClass('directorist-added-to-favorite');
          staElement.children(".directorist-favorite-tooltip").append(fav_tooltip_success);
          staElement.children(".directorist-favorite-tooltip").fadeIn();
          setTimeout(function () {
            staElement.children(".directorist-favorite-tooltip").children("span").remove();
          }, 3000);
        }
      }
    });
  }); //reset fields

  function resetFields() {
    var inputArray = document.querySelectorAll('.search-area input');
    inputArray.forEach(function (input) {
      if (input.getAttribute("type") !== "hidden" || input.getAttribute("id") === "atbd_rs_value") {
        input.value = "";
      }
    });
    var textAreaArray = document.querySelectorAll('.search-area textArea');
    textAreaArray.forEach(function (textArea) {
      textArea.innerHTML = "";
    });
    var range = document.querySelector(".atbdpr-range .ui-slider-horizontal .ui-slider-range");
    var rangePos = document.querySelector(".atbdpr-range .ui-slider-horizontal .ui-slider-handle");
    var rangeAmount = document.querySelector(".atbdpr_amount");

    if (range) {
      range.setAttribute("style", "width: 0;");
    }

    if (rangePos) {
      rangePos.setAttribute("style", "left: 0;");
    }

    if (rangeAmount) {
      rangeAmount.innerText = "0 Mile";
    }

    var checkBoxes = document.querySelectorAll('.directorist-advanced-filter input[type="checkbox"]');
    checkBoxes.forEach(function (el, ind) {
      el.checked = false;
    });
    var radios = document.querySelectorAll('.directorist-advanced-filter input[type="radio"]');
    radios.forEach(function (el, ind) {
      el.checked = false;
    });
    $('.search-area select').prop('selectedIndex', 0);
    $(".bdas-location-search, .bdas-category-search").val('').trigger('change');
  }

  $("body").on("click", ".atbd_widget .directorist-advanced-filter #atbdp_reset", function (e) {
    e.preventDefault();
    resetFields();
  });
  /* advanced search form reset */

  function adsFormReset(searchForm) {
    searchForm.querySelectorAll("input[type='text']").forEach(function (el) {
      el.value = "";
    });
    searchForm.querySelectorAll("input[type='date']").forEach(function (el) {
      el.value = "";
    });
    searchForm.querySelectorAll("input[type='time']").forEach(function (el) {
      el.value = "";
    });
    searchForm.querySelectorAll("input[type='url']").forEach(function (el) {
      el.value = "";
    });
    searchForm.querySelectorAll("input[type='number']").forEach(function (el) {
      el.value = "";
    });
    searchForm.querySelectorAll("input[type='radio']").forEach(function (el) {
      el.checked = false;
    });
    searchForm.querySelectorAll("input[type='checkbox']").forEach(function (el) {
      el.checked = false;
    });
    searchForm.querySelectorAll("select").forEach(function (el) {
      el.selectedIndex = 0;
      $('.directorist-select2-dropdown-close').click();
      $(el).val(null).trigger('change');
    });
    var irisPicker = searchForm.querySelector("input.wp-picker-clear");

    if (irisPicker !== null) {
      irisPicker.click();
    }

    var rangeValue = searchForm.querySelector(".atbd-current-value span");

    if (rangeValue !== null) {
      rangeValue.innerHTML = "0";
    }
  }
  /* Advance Search Filter For Search Home Short Code */


  if ($(".directorist-search-form .directorist-btn-reset-js") !== null) {
    $("body").on("click", ".directorist-search-form .directorist-btn-reset-js", function (e) {
      e.preventDefault();

      if (this.closest('.directorist-search-contents')) {
        var searchForm = this.closest('.directorist-search-contents').querySelector('.directorist-search-form');

        if (searchForm) {
          adsFormReset(searchForm);
        }
      }

      atbd_callingSlider(0);
    });
  }
  /* All Listing Advance Filter */


  if ($(".directorist-advanced-filter__form .directorist-btn-reset-js") !== null) {
    $("body").on("click", ".directorist-advanced-filter__form .directorist-btn-reset-js", function (e) {
      e.preventDefault();

      if (this.closest('.directorist-advanced-filter')) {
        var searchForm = this.closest('.directorist-advanced-filter').querySelector('.directorist-advanced-filter__form');

        if (searchForm) {
          adsFormReset(searchForm);
        }
      }

      atbd_callingSlider(0);
    });
  }

  if ($("#bdlm-search-area #atbdp_reset") !== null) {
    $("body").on("click", "#bdlm-search-area #atbdp_reset", function (e) {
      e.preventDefault();

      if (this.closest('.directorist-search-contents')) {
        var searchForm = this.closest('.directorist-search-contents').querySelector('.directorist-search-form');

        if (searchForm) {
          adsFormReset(searchForm);
        }
      }

      if (this.closest('.directorist-advanced-filter')) {
        var _searchForm = this.closest('.directorist-advanced-filter').querySelector('.directorist-advanced-filter__form');

        if (_searchForm) {
          adsFormReset(_searchForm);
        }
      }

      atbd_callingSlider(0);
    });
  }
  /* Map Listing Search Form */


  if ($("#directorist-search-area .directorist-btn-reset-js") !== null) {
    $("body").on("click", "#directorist-search-area .directorist-btn-reset-js", function (e) {
      e.preventDefault();

      if (this.closest('#directorist-search-area')) {
        var searchForm = this.closest('#directorist-search-area').querySelector('#directorist-search-area-form');

        if (searchForm) {
          adsFormReset(searchForm);
        }
      }

      atbd_callingSlider(0);
    });
  }
  /* Single Listing widget Form */


  if ($(".atbd_widget .search-area .directorist-btn-reset-js") !== null) {
    $("body").on("click", ".atbd_widget .search-area .directorist-btn-reset-js", function (e) {
      e.preventDefault();

      if (this.closest('.search-area')) {
        var searchForm = this.closest('.search-area').querySelector('.directorist-advanced-filter__form');

        if (searchForm) {
          adsFormReset(searchForm);
        }
      }

      atbd_callingSlider(0);
    });
  }
})(jQuery);

/***/ }),

/***/ 2:
/*!******************************************************!*\
  !*** multi ./assets/src/js/public/search-listing.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/public/search-listing.js */"./assets/src/js/public/search-listing.js");


/***/ })

/******/ });
//# sourceMappingURL=public-search-listing.js.map