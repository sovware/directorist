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
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/src/js/admin/setup-wizard.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/admin/setup-wizard.js":
/*!*********************************************!*\
  !*** ./assets/src/js/admin/setup-wizard.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* eslint-disable */
jQuery(document).ready(function ($) {
  var import_dummy = $('#atbdp_dummy_form');
  var position = 0;
  var failed = 0;
  var imported = 0;
  var redirect_url = '';
  $(import_dummy).on('submit', function (e) {
    e.preventDefault();
    $('.atbdp_dummy_body').fadeOut(300);
    $('.atbdp-c-footer').fadeOut(300);
    $('.directorist-importer__importing').fadeIn(300);
    $(this).parent('.csv-fields').fadeOut(300);
    $('.atbdp-mapping-step').removeClass('active').addClass('done');
    $('.atbdp-progress-step').addClass('active');
    var counter = 0;

    var run_import = function run_import() {
      var form_data = new FormData(); // ajax action

      form_data.append('action', 'atbdp_dummy_data_import');
      form_data.append('file', $('#dummy_csv_file').val());
      form_data.append('limit', $('#atbdp-listings-to-import').val());
      form_data.append('image', $('#atbdp-import-image').is(':checked') ? 1 : '');
      form_data.append('delimiter', ',');
      form_data.append('update_existing', '');
      form_data.append('position', position);
      form_data.append('wpnonce', $('input[name="_wpnonce"]').val());
      form_data.append('pre_mapped', true);
      $.ajax({
        method: 'POST',
        processData: false,
        contentType: false,
        // async: false,
        url: import_export_data.ajaxurl,
        data: form_data,
        success: function success(response) {
          imported += response.imported;
          failed += response.failed;
          redirect_url = response.url;
          $('.importer-details').html("Imported ".concat(response.next_position, " out of ").concat(response.total));
          $('.directorist-importer-progress').val(response.percentage);

          if (response.percentage != '100' && counter < 150) {
            position = response.next_position;
            run_import();
            counter++;
          } else {
            window.location = response.url;
          }

          $('input[name="save_step"]').addClass("btn-hide");
          $('.directorist-importer-length').css('width', response.percentage + '%');
        },
        error: function error(response) {
          window.location = redirect_url;
        }
      });
    };

    run_import();
  }); //options

  $('.atbdp-sw-gmap-key').hide();
  $('#select_map').on('change', function (e) {
    if ($(this).val() === 'google') {
      $('.atbdp-sw-gmap-key').show();
    } else {
      $('.atbdp-sw-gmap-key').hide();
    }
  });

  if ($('#select_map').val() === 'google') {
    $('.atbdp-sw-gmap-key').show();
  } else {
    $('.atbdp-sw-gmap-key').hide();
  }

  $('.atbdp-sw-featured-listing').hide();
  $('#enable_monetization').on('change', function () {
    if ($(this).prop("checked") === true) {
      $('.atbdp-sw-featured-listing').show();
    } else {
      $('.atbdp-sw-featured-listing').hide();
    }
  });

  if ($('#enable_monetization').prop("checked") === true) {
    $('.atbdp-sw-featured-listing').show();
  } else {
    $('.atbdp-sw-featured-listing').hide();
  }

  $('.atbdp-sw-listing-price').hide();
  $('#enable_featured_listing').on('change', function () {
    if ($(this).prop("checked") === true) {
      $('.atbdp-sw-listing-price').show();
    } else {
      $('.atbdp-sw-listing-price').hide();
    }
  });

  if ($('#enable_monetization').prop("checked") === true) {
    $('.atbdp-sw-listing-price').show();
  } else {
    $('.atbdp-sw-listing-price').hide();
  }
  /* custom select */


  $('#select_map').select2({
    minimumResultsForSearch: -1
  });
  $('#atbdp-listings-to-import').select2({
    minimumResultsForSearch: -1
  });
});

/***/ })

/******/ });
//# sourceMappingURL=admin-setup-wizard.js.map