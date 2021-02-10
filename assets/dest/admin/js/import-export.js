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
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/src/js/admin/import-export.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/admin/import-export.js":
/*!**********************************************!*\
  !*** ./assets/src/js/admin/import-export.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

jQuery(document).ready(function ($) {
  $('#atbdp_ie_download_sample').on('click', function (e) {
    var ie_file = $(this).attr('data-sample-csv');

    if (ie_file) {
      window.location.href = ie_file;
      return false;
    }
  });
  var stepTwo = $('#atbdp_csv_step_two');
  var position = 0;
  var failed = 0;
  var imported = 0;
  $(stepTwo).on('submit', function (e) {
    e.preventDefault();
    $('.atbdp-importer-mapping-table-wrapper').fadeOut(300);
    $('.directorist-importer__importing').fadeIn(300);
    $(this).parent('.csv-fields').fadeOut(300);
    $('.atbdp-mapping-step').removeClass('active').addClass('done');
    $('.atbdp-progress-step').addClass('active');
    var counter = 0;

    var run_import = function run_import() {
      var form_data = new FormData(); // ajax action

      form_data.append('action', 'atbdp_import_listing');
      form_data.append('file', $('input[name="file"]').val());
      form_data.append('delimiter', $('input[name="delimiter"]').val());
      form_data.append('update_existing', $('input[name="update_existing"]').val());
      form_data.append('position', position);
      form_data.append('wpnonce', $('input[name="_wpnonce"]').val());
      $('select.atbdp_map_to').each(function () {
        var name = $(this).attr('name');
        var value = $(this).val();

        if (value == 'title' || value == 'description' || value == '_listing_prv_img') {
          form_data.append(value, name);
        } else if (value == 'category' || value == 'location' || value == 'tag') {
          form_data.append("tax_input[".concat(value, "]"), name);
        } else {
          form_data.append("meta[".concat(value, "]"), name);
        }
      });
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
          $('.importer-details').html("Imported ".concat(response.next_position, " out of ").concat(response.total));
          $('.directorist-importer-progress').val(response.percentage);

          if (response.percentage != '100' && counter < 150) {
            position = response.next_position;
            run_import();
            counter++;
          } else {
            window.location = "".concat(response.url, "&listing-imported=").concat(imported, "&listing-failed=").concat(failed);
          }
        },
        error: function error(response) {
          window.console.log(response);
        }
      });
    };

    run_import();
  });
  /* csv upload */

  $('#upload').change(function (e) {
    var filename = e.target.files[0].name;
    $('.csv-upload .file-name').html(filename);
  });
});

/***/ })

/******/ });
//# sourceMappingURL=import-export.js.map