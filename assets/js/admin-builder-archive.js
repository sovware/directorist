/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/js/admin/components/delete-directory-modal.js":
/*!******************************************************************!*\
  !*** ./assets/src/js/admin/components/delete-directory-modal.js ***!
  \******************************************************************/
/***/ (function() {

window.addEventListener('load', function () {
  var $ = jQuery;

  // Open Delete Modal
  $('.atbdp-directory-delete-link-action').on('click', function (e) {
    e.preventDefault();
    var delete_link = $(this).data('delete-link');
    $('.atbdp-directory-delete-link').prop('href', delete_link);
  });

  // Delete Action
  $('.atbdp-directory-delete-link').on('click', function (e) {
    // e.preventDefault();
    $(this).prepend('<i class="fas fa-circle-notch fa-spin"></i> ');
    $('.atbdp-directory-delete-cancel-link').removeClass('cptm-modal-toggle');
    $('.atbdp-directory-delete-cancel-link').addClass('atbdp-disabled');
  });
});

/***/ }),

/***/ "./assets/src/js/admin/components/directory-migration-modal.js":
/*!*********************************************************************!*\
  !*** ./assets/src/js/admin/components/directory-migration-modal.js ***!
  \*********************************************************************/
/***/ (function(__unused_webpack_module, __unused_webpack_exports, __webpack_require__) {

window.addEventListener('load', function () {
  var $ = jQuery;
  var axios = (__webpack_require__(/*! axios */ "./node_modules/axios/dist/browser/axios.cjs")["default"]);

  // Migration Link
  $('.atbdp-directory-migration-link').on('click', function (e) {
    e.preventDefault();
    var self = this;
    $('.cptm-directory-migration-form').find('.cptm-comfirmation-text').html('Please wait...');
    $('.atbdp-directory-migration-cencel-link').remove();
    $(this).html('<i class="fas fa-circle-notch fa-spin"></i> Migrating');
    $(this).addClass('atbdp-disabled');
    var form_data = new FormData();
    form_data.append('action', 'directorist_force_migrate');

    // Response Success Callback
    var responseSuccessCallback = function responseSuccessCallback(response) {
      var _response$data;
      // console.log( { response } );

      if (response !== null && response !== void 0 && (_response$data = response.data) !== null && _response$data !== void 0 && _response$data.success) {
        var _response$data$messag, _response$data2;
        var msg = (_response$data$messag = response === null || response === void 0 || (_response$data2 = response.data) === null || _response$data2 === void 0 ? void 0 : _response$data2.message) !== null && _response$data$messag !== void 0 ? _response$data$messag : 'Migration Successful';
        var alert_content = "\n                <div class=\"cptm-section-alert-content\">\n                    <div class=\"cptm-section-alert-icon cptm-alert-success\">\n                        <span class=\"fa fa-check\"></span>\n                    </div>\n\n                    <div class=\"cptm-section-alert-message\">".concat(msg, "</div>\n                </div>\n                ");
        $('.cptm-directory-migration-form').find('.cptm-comfirmation-text').html(alert_content);
        $(self).remove();
        location.reload();
        return;
      }
      responseFaildCallback(response);
    };

    // Response Error Callback
    var responseFaildCallback = function responseFaildCallback(response) {
      var _response$data$messag2, _response$data3;
      // console.log( { response } );

      var msg = (_response$data$messag2 = response === null || response === void 0 || (_response$data3 = response.data) === null || _response$data3 === void 0 ? void 0 : _response$data3.message) !== null && _response$data$messag2 !== void 0 ? _response$data$messag2 : 'Something went wrong please try again';
      var alert_content = "\n            <div class=\"cptm-section-alert-content\">\n                <div class=\"cptm-section-alert-icon cptm-alert-error\">\n                    <span class=\"fa fa-times\"></span>\n                </div>\n\n                <div class=\"cptm-section-alert-message\">".concat(msg, "</div>\n            </div>\n            ");
      $('.cptm-directory-migration-form').find('.cptm-comfirmation-text').html(alert_content);
      $(self).remove();
    };

    // Send Request
    axios.post(directorist_admin.ajax_url, form_data).then(function (response) {
      responseSuccessCallback(response);
    }).catch(function (response) {
      responseFaildCallback(response);
    });
  });
});

/***/ }),

/***/ "./assets/src/js/admin/components/import-directory-modal.js":
/*!******************************************************************!*\
  !*** ./assets/src/js/admin/components/import-directory-modal.js ***!
  \******************************************************************/
/***/ (function(__unused_webpack_module, __unused_webpack_exports, __webpack_require__) {

function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t.return || t.return(); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
window.addEventListener('load', function () {
  var axios = (__webpack_require__(/*! axios */ "./node_modules/axios/dist/browser/axios.cjs")["default"]);
  var $ = jQuery;

  // cptm-import-directory-form
  var term_id = 0;
  $('.cptm-import-directory-form').on('submit', function (e) {
    e.preventDefault();
    var form_feedback = $(this).find('.cptm-form-group-feedback');
    var modal_content = $('.cptm-import-directory-modal').find('.cptm-modal-content');
    var modal_alert = $('.cptm-import-directory-modal-alert');
    var form_data = new FormData();
    form_data.append('action', 'save_imported_post_type_data');
    form_data.append('directorist_nonce', directorist_admin.directorist_nonce);
    if (Number.isInteger(term_id) && term_id > 0) {
      form_data.append('term_id', term_id);
    }
    var form_fields = $(this).find('.cptm-form-field');
    var general_fields = ['text', 'number'];
    $(this).find('button[type=submit] .cptm-loading-icon').removeClass('cptm-d-none');
    var _iterator = _createForOfIteratorHelper(form_fields),
      _step;
    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var field = _step.value;
        if (!field.name.length) {
          continue;
        }

        // General fields
        if (general_fields.includes(field.type)) {
          form_data.append(field.name, $(field).val());
        }

        // Media fields
        if ('file' === field.type) {
          form_data.append(field.name, field.files[0]);
        }
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
    var self = this;
    form_feedback.html('');
    axios.post(directorist_admin.ajax_url, form_data).then(function (response) {
      // console.log( { response } );
      $(self).find('button[type=submit] .cptm-loading-icon').addClass('cptm-d-none');

      // Store term ID if exist
      if (response.data.term_id && Number.isInteger(response.data.term_id) && response.data.term_id > 0) {
        term_id = response.data.term_id;
        // console.log( 'Term ID has been updated' );
      }

      // Show status log
      if (response.data && response.data.status.status_log) {
        var status_log = response.data.status.status_log;
        for (var status in status_log) {
          var alert = '<div class="cptm-form-alert cptm-' + status_log[status].type + '">' + status_log[status].message + '</div>';
          form_feedback.append(alert);
        }
      }

      // Reload the page if success
      if (response.data && response.data.status && response.data.status.success) {
        // console.log( 'reloading...' );

        modal_content.addClass('cptm-d-none');
        modal_alert.removeClass('cptm-d-none');
        $(self).trigger('reset');
        location.reload();
      }
    }).catch(function (error) {
      console.log({
        error: error
      });
      $(self).find('button[type=submit] .cptm-loading-icon').addClass('cptm-d-none');
    });
  });
});

/***/ }),

/***/ "./node_modules/axios/dist/browser/axios.cjs":
/*!***************************************************!*\
  !*** ./node_modules/axios/dist/browser/axios.cjs ***!
  \***************************************************/
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

"use strict";
/*! Axios v1.13.5 Copyright (c) 2026 Matt Zabriskie and contributors */


/**
 * Create a bound version of a function with a specified `this` context
 *
 * @param {Function} fn - The function to bind
 * @param {*} thisArg - The value to be passed as the `this` parameter
 * @returns {Function} A new function that will call the original function with the specified `this` context
 */
function bind(fn, thisArg) {
  return function wrap() {
    return fn.apply(thisArg, arguments);
  };
}

// utils is a library of generic helper functions non-specific to axios

const { toString } = Object.prototype;
const { getPrototypeOf } = Object;
const { iterator, toStringTag } = Symbol;

const kindOf = ((cache) => (thing) => {
  const str = toString.call(thing);
  return cache[str] || (cache[str] = str.slice(8, -1).toLowerCase());
})(Object.create(null));

const kindOfTest = (type) => {
  type = type.toLowerCase();
  return (thing) => kindOf(thing) === type;
};

const typeOfTest = (type) => (thing) => typeof thing === type;

/**
 * Determine if a value is a non-null object
 *
 * @param {Object} val The value to test
 *
 * @returns {boolean} True if value is an Array, otherwise false
 */
const { isArray } = Array;

/**
 * Determine if a value is undefined
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if the value is undefined, otherwise false
 */
const isUndefined = typeOfTest("undefined");

/**
 * Determine if a value is a Buffer
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a Buffer, otherwise false
 */
function isBuffer(val) {
  return (
    val !== null &&
    !isUndefined(val) &&
    val.constructor !== null &&
    !isUndefined(val.constructor) &&
    isFunction$1(val.constructor.isBuffer) &&
    val.constructor.isBuffer(val)
  );
}

/**
 * Determine if a value is an ArrayBuffer
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is an ArrayBuffer, otherwise false
 */
const isArrayBuffer = kindOfTest("ArrayBuffer");

/**
 * Determine if a value is a view on an ArrayBuffer
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a view on an ArrayBuffer, otherwise false
 */
function isArrayBufferView(val) {
  let result;
  if (typeof ArrayBuffer !== "undefined" && ArrayBuffer.isView) {
    result = ArrayBuffer.isView(val);
  } else {
    result = val && val.buffer && isArrayBuffer(val.buffer);
  }
  return result;
}

/**
 * Determine if a value is a String
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a String, otherwise false
 */
const isString = typeOfTest("string");

/**
 * Determine if a value is a Function
 *
 * @param {*} val The value to test
 * @returns {boolean} True if value is a Function, otherwise false
 */
const isFunction$1 = typeOfTest("function");

/**
 * Determine if a value is a Number
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a Number, otherwise false
 */
const isNumber = typeOfTest("number");

/**
 * Determine if a value is an Object
 *
 * @param {*} thing The value to test
 *
 * @returns {boolean} True if value is an Object, otherwise false
 */
const isObject = (thing) => thing !== null && typeof thing === "object";

/**
 * Determine if a value is a Boolean
 *
 * @param {*} thing The value to test
 * @returns {boolean} True if value is a Boolean, otherwise false
 */
const isBoolean = (thing) => thing === true || thing === false;

/**
 * Determine if a value is a plain Object
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a plain Object, otherwise false
 */
const isPlainObject = (val) => {
  if (kindOf(val) !== "object") {
    return false;
  }

  const prototype = getPrototypeOf(val);
  return (
    (prototype === null ||
      prototype === Object.prototype ||
      Object.getPrototypeOf(prototype) === null) &&
    !(toStringTag in val) &&
    !(iterator in val)
  );
};

/**
 * Determine if a value is an empty object (safely handles Buffers)
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is an empty object, otherwise false
 */
const isEmptyObject = (val) => {
  // Early return for non-objects or Buffers to prevent RangeError
  if (!isObject(val) || isBuffer(val)) {
    return false;
  }

  try {
    return (
      Object.keys(val).length === 0 &&
      Object.getPrototypeOf(val) === Object.prototype
    );
  } catch (e) {
    // Fallback for any other objects that might cause RangeError with Object.keys()
    return false;
  }
};

/**
 * Determine if a value is a Date
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a Date, otherwise false
 */
const isDate = kindOfTest("Date");

/**
 * Determine if a value is a File
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a File, otherwise false
 */
const isFile = kindOfTest("File");

/**
 * Determine if a value is a Blob
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a Blob, otherwise false
 */
const isBlob = kindOfTest("Blob");

/**
 * Determine if a value is a FileList
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a File, otherwise false
 */
const isFileList = kindOfTest("FileList");

/**
 * Determine if a value is a Stream
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a Stream, otherwise false
 */
const isStream = (val) => isObject(val) && isFunction$1(val.pipe);

/**
 * Determine if a value is a FormData
 *
 * @param {*} thing The value to test
 *
 * @returns {boolean} True if value is an FormData, otherwise false
 */
const isFormData = (thing) => {
  let kind;
  return (
    thing &&
    ((typeof FormData === "function" && thing instanceof FormData) ||
      (isFunction$1(thing.append) &&
        ((kind = kindOf(thing)) === "formdata" ||
          // detect form-data instance
          (kind === "object" &&
            isFunction$1(thing.toString) &&
            thing.toString() === "[object FormData]"))))
  );
};

/**
 * Determine if a value is a URLSearchParams object
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a URLSearchParams object, otherwise false
 */
const isURLSearchParams = kindOfTest("URLSearchParams");

const [isReadableStream, isRequest, isResponse, isHeaders] = [
  "ReadableStream",
  "Request",
  "Response",
  "Headers",
].map(kindOfTest);

/**
 * Trim excess whitespace off the beginning and end of a string
 *
 * @param {String} str The String to trim
 *
 * @returns {String} The String freed of excess whitespace
 */
const trim = (str) =>
  str.trim ? str.trim() : str.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, "");

/**
 * Iterate over an Array or an Object invoking a function for each item.
 *
 * If `obj` is an Array callback will be called passing
 * the value, index, and complete array for each item.
 *
 * If 'obj' is an Object callback will be called passing
 * the value, key, and complete object for each property.
 *
 * @param {Object|Array<unknown>} obj The object to iterate
 * @param {Function} fn The callback to invoke for each item
 *
 * @param {Object} [options]
 * @param {Boolean} [options.allOwnKeys = false]
 * @returns {any}
 */
function forEach(obj, fn, { allOwnKeys = false } = {}) {
  // Don't bother if no value provided
  if (obj === null || typeof obj === "undefined") {
    return;
  }

  let i;
  let l;

  // Force an array if not already something iterable
  if (typeof obj !== "object") {
    /*eslint no-param-reassign:0*/
    obj = [obj];
  }

  if (isArray(obj)) {
    // Iterate over array values
    for (i = 0, l = obj.length; i < l; i++) {
      fn.call(null, obj[i], i, obj);
    }
  } else {
    // Buffer check
    if (isBuffer(obj)) {
      return;
    }

    // Iterate over object keys
    const keys = allOwnKeys
      ? Object.getOwnPropertyNames(obj)
      : Object.keys(obj);
    const len = keys.length;
    let key;

    for (i = 0; i < len; i++) {
      key = keys[i];
      fn.call(null, obj[key], key, obj);
    }
  }
}

function findKey(obj, key) {
  if (isBuffer(obj)) {
    return null;
  }

  key = key.toLowerCase();
  const keys = Object.keys(obj);
  let i = keys.length;
  let _key;
  while (i-- > 0) {
    _key = keys[i];
    if (key === _key.toLowerCase()) {
      return _key;
    }
  }
  return null;
}

const _global = (() => {
  /*eslint no-undef:0*/
  if (typeof globalThis !== "undefined") return globalThis;
  return typeof self !== "undefined"
    ? self
    : typeof window !== "undefined"
      ? window
      : __webpack_require__.g;
})();

const isContextDefined = (context) =>
  !isUndefined(context) && context !== _global;

/**
 * Accepts varargs expecting each argument to be an object, then
 * immutably merges the properties of each object and returns result.
 *
 * When multiple objects contain the same key the later object in
 * the arguments list will take precedence.
 *
 * Example:
 *
 * ```js
 * const result = merge({foo: 123}, {foo: 456});
 * console.log(result.foo); // outputs 456
 * ```
 *
 * @param {Object} obj1 Object to merge
 *
 * @returns {Object} Result of all merge properties
 */
function merge(/* obj1, obj2, obj3, ... */) {
  const { caseless, skipUndefined } = (isContextDefined(this) && this) || {};
  const result = {};
  const assignValue = (val, key) => {
    // Skip dangerous property names to prevent prototype pollution
    if (key === "__proto__" || key === "constructor" || key === "prototype") {
      return;
    }

    const targetKey = (caseless && findKey(result, key)) || key;
    if (isPlainObject(result[targetKey]) && isPlainObject(val)) {
      result[targetKey] = merge(result[targetKey], val);
    } else if (isPlainObject(val)) {
      result[targetKey] = merge({}, val);
    } else if (isArray(val)) {
      result[targetKey] = val.slice();
    } else if (!skipUndefined || !isUndefined(val)) {
      result[targetKey] = val;
    }
  };

  for (let i = 0, l = arguments.length; i < l; i++) {
    arguments[i] && forEach(arguments[i], assignValue);
  }
  return result;
}

/**
 * Extends object a by mutably adding to it the properties of object b.
 *
 * @param {Object} a The object to be extended
 * @param {Object} b The object to copy properties from
 * @param {Object} thisArg The object to bind function to
 *
 * @param {Object} [options]
 * @param {Boolean} [options.allOwnKeys]
 * @returns {Object} The resulting value of object a
 */
const extend = (a, b, thisArg, { allOwnKeys } = {}) => {
  forEach(
    b,
    (val, key) => {
      if (thisArg && isFunction$1(val)) {
        Object.defineProperty(a, key, {
          value: bind(val, thisArg),
          writable: true,
          enumerable: true,
          configurable: true,
        });
      } else {
        Object.defineProperty(a, key, {
          value: val,
          writable: true,
          enumerable: true,
          configurable: true,
        });
      }
    },
    { allOwnKeys },
  );
  return a;
};

/**
 * Remove byte order marker. This catches EF BB BF (the UTF-8 BOM)
 *
 * @param {string} content with BOM
 *
 * @returns {string} content value without BOM
 */
const stripBOM = (content) => {
  if (content.charCodeAt(0) === 0xfeff) {
    content = content.slice(1);
  }
  return content;
};

/**
 * Inherit the prototype methods from one constructor into another
 * @param {function} constructor
 * @param {function} superConstructor
 * @param {object} [props]
 * @param {object} [descriptors]
 *
 * @returns {void}
 */
const inherits = (constructor, superConstructor, props, descriptors) => {
  constructor.prototype = Object.create(
    superConstructor.prototype,
    descriptors,
  );
  Object.defineProperty(constructor.prototype, "constructor", {
    value: constructor,
    writable: true,
    enumerable: false,
    configurable: true,
  });
  Object.defineProperty(constructor, "super", {
    value: superConstructor.prototype,
  });
  props && Object.assign(constructor.prototype, props);
};

/**
 * Resolve object with deep prototype chain to a flat object
 * @param {Object} sourceObj source object
 * @param {Object} [destObj]
 * @param {Function|Boolean} [filter]
 * @param {Function} [propFilter]
 *
 * @returns {Object}
 */
const toFlatObject = (sourceObj, destObj, filter, propFilter) => {
  let props;
  let i;
  let prop;
  const merged = {};

  destObj = destObj || {};
  // eslint-disable-next-line no-eq-null,eqeqeq
  if (sourceObj == null) return destObj;

  do {
    props = Object.getOwnPropertyNames(sourceObj);
    i = props.length;
    while (i-- > 0) {
      prop = props[i];
      if (
        (!propFilter || propFilter(prop, sourceObj, destObj)) &&
        !merged[prop]
      ) {
        destObj[prop] = sourceObj[prop];
        merged[prop] = true;
      }
    }
    sourceObj = filter !== false && getPrototypeOf(sourceObj);
  } while (
    sourceObj &&
    (!filter || filter(sourceObj, destObj)) &&
    sourceObj !== Object.prototype
  );

  return destObj;
};

/**
 * Determines whether a string ends with the characters of a specified string
 *
 * @param {String} str
 * @param {String} searchString
 * @param {Number} [position= 0]
 *
 * @returns {boolean}
 */
const endsWith = (str, searchString, position) => {
  str = String(str);
  if (position === undefined || position > str.length) {
    position = str.length;
  }
  position -= searchString.length;
  const lastIndex = str.indexOf(searchString, position);
  return lastIndex !== -1 && lastIndex === position;
};

/**
 * Returns new array from array like object or null if failed
 *
 * @param {*} [thing]
 *
 * @returns {?Array}
 */
const toArray = (thing) => {
  if (!thing) return null;
  if (isArray(thing)) return thing;
  let i = thing.length;
  if (!isNumber(i)) return null;
  const arr = new Array(i);
  while (i-- > 0) {
    arr[i] = thing[i];
  }
  return arr;
};

/**
 * Checking if the Uint8Array exists and if it does, it returns a function that checks if the
 * thing passed in is an instance of Uint8Array
 *
 * @param {TypedArray}
 *
 * @returns {Array}
 */
// eslint-disable-next-line func-names
const isTypedArray = ((TypedArray) => {
  // eslint-disable-next-line func-names
  return (thing) => {
    return TypedArray && thing instanceof TypedArray;
  };
})(typeof Uint8Array !== "undefined" && getPrototypeOf(Uint8Array));

/**
 * For each entry in the object, call the function with the key and value.
 *
 * @param {Object<any, any>} obj - The object to iterate over.
 * @param {Function} fn - The function to call for each entry.
 *
 * @returns {void}
 */
const forEachEntry = (obj, fn) => {
  const generator = obj && obj[iterator];

  const _iterator = generator.call(obj);

  let result;

  while ((result = _iterator.next()) && !result.done) {
    const pair = result.value;
    fn.call(obj, pair[0], pair[1]);
  }
};

/**
 * It takes a regular expression and a string, and returns an array of all the matches
 *
 * @param {string} regExp - The regular expression to match against.
 * @param {string} str - The string to search.
 *
 * @returns {Array<boolean>}
 */
const matchAll = (regExp, str) => {
  let matches;
  const arr = [];

  while ((matches = regExp.exec(str)) !== null) {
    arr.push(matches);
  }

  return arr;
};

/* Checking if the kindOfTest function returns true when passed an HTMLFormElement. */
const isHTMLForm = kindOfTest("HTMLFormElement");

const toCamelCase = (str) => {
  return str
    .toLowerCase()
    .replace(/[-_\s]([a-z\d])(\w*)/g, function replacer(m, p1, p2) {
      return p1.toUpperCase() + p2;
    });
};

/* Creating a function that will check if an object has a property. */
const hasOwnProperty = (
  ({ hasOwnProperty }) =>
  (obj, prop) =>
    hasOwnProperty.call(obj, prop)
)(Object.prototype);

/**
 * Determine if a value is a RegExp object
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a RegExp object, otherwise false
 */
const isRegExp = kindOfTest("RegExp");

const reduceDescriptors = (obj, reducer) => {
  const descriptors = Object.getOwnPropertyDescriptors(obj);
  const reducedDescriptors = {};

  forEach(descriptors, (descriptor, name) => {
    let ret;
    if ((ret = reducer(descriptor, name, obj)) !== false) {
      reducedDescriptors[name] = ret || descriptor;
    }
  });

  Object.defineProperties(obj, reducedDescriptors);
};

/**
 * Makes all methods read-only
 * @param {Object} obj
 */

const freezeMethods = (obj) => {
  reduceDescriptors(obj, (descriptor, name) => {
    // skip restricted props in strict mode
    if (
      isFunction$1(obj) &&
      ["arguments", "caller", "callee"].indexOf(name) !== -1
    ) {
      return false;
    }

    const value = obj[name];

    if (!isFunction$1(value)) return;

    descriptor.enumerable = false;

    if ("writable" in descriptor) {
      descriptor.writable = false;
      return;
    }

    if (!descriptor.set) {
      descriptor.set = () => {
        throw Error("Can not rewrite read-only method '" + name + "'");
      };
    }
  });
};

const toObjectSet = (arrayOrString, delimiter) => {
  const obj = {};

  const define = (arr) => {
    arr.forEach((value) => {
      obj[value] = true;
    });
  };

  isArray(arrayOrString)
    ? define(arrayOrString)
    : define(String(arrayOrString).split(delimiter));

  return obj;
};

const noop = () => {};

const toFiniteNumber = (value, defaultValue) => {
  return value != null && Number.isFinite((value = +value))
    ? value
    : defaultValue;
};

/**
 * If the thing is a FormData object, return true, otherwise return false.
 *
 * @param {unknown} thing - The thing to check.
 *
 * @returns {boolean}
 */
function isSpecCompliantForm(thing) {
  return !!(
    thing &&
    isFunction$1(thing.append) &&
    thing[toStringTag] === "FormData" &&
    thing[iterator]
  );
}

const toJSONObject = (obj) => {
  const stack = new Array(10);

  const visit = (source, i) => {
    if (isObject(source)) {
      if (stack.indexOf(source) >= 0) {
        return;
      }

      //Buffer check
      if (isBuffer(source)) {
        return source;
      }

      if (!("toJSON" in source)) {
        stack[i] = source;
        const target = isArray(source) ? [] : {};

        forEach(source, (value, key) => {
          const reducedValue = visit(value, i + 1);
          !isUndefined(reducedValue) && (target[key] = reducedValue);
        });

        stack[i] = undefined;

        return target;
      }
    }

    return source;
  };

  return visit(obj, 0);
};

const isAsyncFn = kindOfTest("AsyncFunction");

const isThenable = (thing) =>
  thing &&
  (isObject(thing) || isFunction$1(thing)) &&
  isFunction$1(thing.then) &&
  isFunction$1(thing.catch);

// original code
// https://github.com/DigitalBrainJS/AxiosPromise/blob/16deab13710ec09779922131f3fa5954320f83ab/lib/utils.js#L11-L34

const _setImmediate = ((setImmediateSupported, postMessageSupported) => {
  if (setImmediateSupported) {
    return setImmediate;
  }

  return postMessageSupported
    ? ((token, callbacks) => {
        _global.addEventListener(
          "message",
          ({ source, data }) => {
            if (source === _global && data === token) {
              callbacks.length && callbacks.shift()();
            }
          },
          false,
        );

        return (cb) => {
          callbacks.push(cb);
          _global.postMessage(token, "*");
        };
      })(`axios@${Math.random()}`, [])
    : (cb) => setTimeout(cb);
})(typeof setImmediate === "function", isFunction$1(_global.postMessage));

const asap =
  typeof queueMicrotask !== "undefined"
    ? queueMicrotask.bind(_global)
    : (typeof process !== "undefined" && process.nextTick) || _setImmediate;

// *********************

const isIterable = (thing) => thing != null && isFunction$1(thing[iterator]);

var utils$1 = {
  isArray,
  isArrayBuffer,
  isBuffer,
  isFormData,
  isArrayBufferView,
  isString,
  isNumber,
  isBoolean,
  isObject,
  isPlainObject,
  isEmptyObject,
  isReadableStream,
  isRequest,
  isResponse,
  isHeaders,
  isUndefined,
  isDate,
  isFile,
  isBlob,
  isRegExp,
  isFunction: isFunction$1,
  isStream,
  isURLSearchParams,
  isTypedArray,
  isFileList,
  forEach,
  merge,
  extend,
  trim,
  stripBOM,
  inherits,
  toFlatObject,
  kindOf,
  kindOfTest,
  endsWith,
  toArray,
  forEachEntry,
  matchAll,
  isHTMLForm,
  hasOwnProperty,
  hasOwnProp: hasOwnProperty, // an alias to avoid ESLint no-prototype-builtins detection
  reduceDescriptors,
  freezeMethods,
  toObjectSet,
  toCamelCase,
  noop,
  toFiniteNumber,
  findKey,
  global: _global,
  isContextDefined,
  isSpecCompliantForm,
  toJSONObject,
  isAsyncFn,
  isThenable,
  setImmediate: _setImmediate,
  asap,
  isIterable,
};

class AxiosError extends Error {
    static from(error, code, config, request, response, customProps) {
        const axiosError = new AxiosError(error.message, code || error.code, config, request, response);
        axiosError.cause = error;
        axiosError.name = error.name;
        customProps && Object.assign(axiosError, customProps);
        return axiosError;
    }

    /**
     * Create an Error with the specified message, config, error code, request and response.
     *
     * @param {string} message The error message.
     * @param {string} [code] The error code (for example, 'ECONNABORTED').
     * @param {Object} [config] The config.
     * @param {Object} [request] The request.
     * @param {Object} [response] The response.
     *
     * @returns {Error} The created error.
     */
    constructor(message, code, config, request, response) {
        super(message);
        this.name = 'AxiosError';
        this.isAxiosError = true;
        code && (this.code = code);
        config && (this.config = config);
        request && (this.request = request);
        if (response) {
            this.response = response;
            this.status = response.status;
        }
    }

    toJSON() {
        return {
            // Standard
            message: this.message,
            name: this.name,
            // Microsoft
            description: this.description,
            number: this.number,
            // Mozilla
            fileName: this.fileName,
            lineNumber: this.lineNumber,
            columnNumber: this.columnNumber,
            stack: this.stack,
            // Axios
            config: utils$1.toJSONObject(this.config),
            code: this.code,
            status: this.status,
        };
    }
}

// This can be changed to static properties as soon as the parser options in .eslint.cjs are updated.
AxiosError.ERR_BAD_OPTION_VALUE = 'ERR_BAD_OPTION_VALUE';
AxiosError.ERR_BAD_OPTION = 'ERR_BAD_OPTION';
AxiosError.ECONNABORTED = 'ECONNABORTED';
AxiosError.ETIMEDOUT = 'ETIMEDOUT';
AxiosError.ERR_NETWORK = 'ERR_NETWORK';
AxiosError.ERR_FR_TOO_MANY_REDIRECTS = 'ERR_FR_TOO_MANY_REDIRECTS';
AxiosError.ERR_DEPRECATED = 'ERR_DEPRECATED';
AxiosError.ERR_BAD_RESPONSE = 'ERR_BAD_RESPONSE';
AxiosError.ERR_BAD_REQUEST = 'ERR_BAD_REQUEST';
AxiosError.ERR_CANCELED = 'ERR_CANCELED';
AxiosError.ERR_NOT_SUPPORT = 'ERR_NOT_SUPPORT';
AxiosError.ERR_INVALID_URL = 'ERR_INVALID_URL';

var AxiosError$1 = AxiosError;

// eslint-disable-next-line strict
var httpAdapter = null;

/**
 * Determines if the given thing is a array or js object.
 *
 * @param {string} thing - The object or array to be visited.
 *
 * @returns {boolean}
 */
function isVisitable(thing) {
  return utils$1.isPlainObject(thing) || utils$1.isArray(thing);
}

/**
 * It removes the brackets from the end of a string
 *
 * @param {string} key - The key of the parameter.
 *
 * @returns {string} the key without the brackets.
 */
function removeBrackets(key) {
  return utils$1.endsWith(key, '[]') ? key.slice(0, -2) : key;
}

/**
 * It takes a path, a key, and a boolean, and returns a string
 *
 * @param {string} path - The path to the current key.
 * @param {string} key - The key of the current object being iterated over.
 * @param {string} dots - If true, the key will be rendered with dots instead of brackets.
 *
 * @returns {string} The path to the current key.
 */
function renderKey(path, key, dots) {
  if (!path) return key;
  return path.concat(key).map(function each(token, i) {
    // eslint-disable-next-line no-param-reassign
    token = removeBrackets(token);
    return !dots && i ? '[' + token + ']' : token;
  }).join(dots ? '.' : '');
}

/**
 * If the array is an array and none of its elements are visitable, then it's a flat array.
 *
 * @param {Array<any>} arr - The array to check
 *
 * @returns {boolean}
 */
function isFlatArray(arr) {
  return utils$1.isArray(arr) && !arr.some(isVisitable);
}

const predicates = utils$1.toFlatObject(utils$1, {}, null, function filter(prop) {
  return /^is[A-Z]/.test(prop);
});

/**
 * Convert a data object to FormData
 *
 * @param {Object} obj
 * @param {?Object} [formData]
 * @param {?Object} [options]
 * @param {Function} [options.visitor]
 * @param {Boolean} [options.metaTokens = true]
 * @param {Boolean} [options.dots = false]
 * @param {?Boolean} [options.indexes = false]
 *
 * @returns {Object}
 **/

/**
 * It converts an object into a FormData object
 *
 * @param {Object<any, any>} obj - The object to convert to form data.
 * @param {string} formData - The FormData object to append to.
 * @param {Object<string, any>} options
 *
 * @returns
 */
function toFormData(obj, formData, options) {
  if (!utils$1.isObject(obj)) {
    throw new TypeError('target must be an object');
  }

  // eslint-disable-next-line no-param-reassign
  formData = formData || new (FormData)();

  // eslint-disable-next-line no-param-reassign
  options = utils$1.toFlatObject(options, {
    metaTokens: true,
    dots: false,
    indexes: false
  }, false, function defined(option, source) {
    // eslint-disable-next-line no-eq-null,eqeqeq
    return !utils$1.isUndefined(source[option]);
  });

  const metaTokens = options.metaTokens;
  // eslint-disable-next-line no-use-before-define
  const visitor = options.visitor || defaultVisitor;
  const dots = options.dots;
  const indexes = options.indexes;
  const _Blob = options.Blob || typeof Blob !== 'undefined' && Blob;
  const useBlob = _Blob && utils$1.isSpecCompliantForm(formData);

  if (!utils$1.isFunction(visitor)) {
    throw new TypeError('visitor must be a function');
  }

  function convertValue(value) {
    if (value === null) return '';

    if (utils$1.isDate(value)) {
      return value.toISOString();
    }

    if (utils$1.isBoolean(value)) {
      return value.toString();
    }

    if (!useBlob && utils$1.isBlob(value)) {
      throw new AxiosError$1('Blob is not supported. Use a Buffer instead.');
    }

    if (utils$1.isArrayBuffer(value) || utils$1.isTypedArray(value)) {
      return useBlob && typeof Blob === 'function' ? new Blob([value]) : Buffer.from(value);
    }

    return value;
  }

  /**
   * Default visitor.
   *
   * @param {*} value
   * @param {String|Number} key
   * @param {Array<String|Number>} path
   * @this {FormData}
   *
   * @returns {boolean} return true to visit the each prop of the value recursively
   */
  function defaultVisitor(value, key, path) {
    let arr = value;

    if (value && !path && typeof value === 'object') {
      if (utils$1.endsWith(key, '{}')) {
        // eslint-disable-next-line no-param-reassign
        key = metaTokens ? key : key.slice(0, -2);
        // eslint-disable-next-line no-param-reassign
        value = JSON.stringify(value);
      } else if (
        (utils$1.isArray(value) && isFlatArray(value)) ||
        ((utils$1.isFileList(value) || utils$1.endsWith(key, '[]')) && (arr = utils$1.toArray(value))
        )) {
        // eslint-disable-next-line no-param-reassign
        key = removeBrackets(key);

        arr.forEach(function each(el, index) {
          !(utils$1.isUndefined(el) || el === null) && formData.append(
            // eslint-disable-next-line no-nested-ternary
            indexes === true ? renderKey([key], index, dots) : (indexes === null ? key : key + '[]'),
            convertValue(el)
          );
        });
        return false;
      }
    }

    if (isVisitable(value)) {
      return true;
    }

    formData.append(renderKey(path, key, dots), convertValue(value));

    return false;
  }

  const stack = [];

  const exposedHelpers = Object.assign(predicates, {
    defaultVisitor,
    convertValue,
    isVisitable
  });

  function build(value, path) {
    if (utils$1.isUndefined(value)) return;

    if (stack.indexOf(value) !== -1) {
      throw Error('Circular reference detected in ' + path.join('.'));
    }

    stack.push(value);

    utils$1.forEach(value, function each(el, key) {
      const result = !(utils$1.isUndefined(el) || el === null) && visitor.call(
        formData, el, utils$1.isString(key) ? key.trim() : key, path, exposedHelpers
      );

      if (result === true) {
        build(el, path ? path.concat(key) : [key]);
      }
    });

    stack.pop();
  }

  if (!utils$1.isObject(obj)) {
    throw new TypeError('data must be an object');
  }

  build(obj);

  return formData;
}

/**
 * It encodes a string by replacing all characters that are not in the unreserved set with
 * their percent-encoded equivalents
 *
 * @param {string} str - The string to encode.
 *
 * @returns {string} The encoded string.
 */
function encode$1(str) {
  const charMap = {
    '!': '%21',
    "'": '%27',
    '(': '%28',
    ')': '%29',
    '~': '%7E',
    '%20': '+',
    '%00': '\x00'
  };
  return encodeURIComponent(str).replace(/[!'()~]|%20|%00/g, function replacer(match) {
    return charMap[match];
  });
}

/**
 * It takes a params object and converts it to a FormData object
 *
 * @param {Object<string, any>} params - The parameters to be converted to a FormData object.
 * @param {Object<string, any>} options - The options object passed to the Axios constructor.
 *
 * @returns {void}
 */
function AxiosURLSearchParams(params, options) {
  this._pairs = [];

  params && toFormData(params, this, options);
}

const prototype = AxiosURLSearchParams.prototype;

prototype.append = function append(name, value) {
  this._pairs.push([name, value]);
};

prototype.toString = function toString(encoder) {
  const _encode = encoder ? function(value) {
    return encoder.call(this, value, encode$1);
  } : encode$1;

  return this._pairs.map(function each(pair) {
    return _encode(pair[0]) + '=' + _encode(pair[1]);
  }, '').join('&');
};

/**
 * It replaces all instances of the characters `:`, `$`, `,`, `+`, `[`, and `]` with their
 * URI encoded counterparts
 *
 * @param {string} val The value to be encoded.
 *
 * @returns {string} The encoded value.
 */
function encode(val) {
  return encodeURIComponent(val).
    replace(/%3A/gi, ':').
    replace(/%24/g, '$').
    replace(/%2C/gi, ',').
    replace(/%20/g, '+');
}

/**
 * Build a URL by appending params to the end
 *
 * @param {string} url The base of the url (e.g., http://www.google.com)
 * @param {object} [params] The params to be appended
 * @param {?(object|Function)} options
 *
 * @returns {string} The formatted url
 */
function buildURL(url, params, options) {
  if (!params) {
    return url;
  }

  const _encode = options && options.encode || encode;

  const _options = utils$1.isFunction(options) ? {
    serialize: options
  } : options;

  const serializeFn = _options && _options.serialize;

  let serializedParams;

  if (serializeFn) {
    serializedParams = serializeFn(params, _options);
  } else {
    serializedParams = utils$1.isURLSearchParams(params) ?
      params.toString() :
      new AxiosURLSearchParams(params, _options).toString(_encode);
  }

  if (serializedParams) {
    const hashmarkIndex = url.indexOf("#");

    if (hashmarkIndex !== -1) {
      url = url.slice(0, hashmarkIndex);
    }
    url += (url.indexOf('?') === -1 ? '?' : '&') + serializedParams;
  }

  return url;
}

class InterceptorManager {
  constructor() {
    this.handlers = [];
  }

  /**
   * Add a new interceptor to the stack
   *
   * @param {Function} fulfilled The function to handle `then` for a `Promise`
   * @param {Function} rejected The function to handle `reject` for a `Promise`
   * @param {Object} options The options for the interceptor, synchronous and runWhen
   *
   * @return {Number} An ID used to remove interceptor later
   */
  use(fulfilled, rejected, options) {
    this.handlers.push({
      fulfilled,
      rejected,
      synchronous: options ? options.synchronous : false,
      runWhen: options ? options.runWhen : null
    });
    return this.handlers.length - 1;
  }

  /**
   * Remove an interceptor from the stack
   *
   * @param {Number} id The ID that was returned by `use`
   *
   * @returns {void}
   */
  eject(id) {
    if (this.handlers[id]) {
      this.handlers[id] = null;
    }
  }

  /**
   * Clear all interceptors from the stack
   *
   * @returns {void}
   */
  clear() {
    if (this.handlers) {
      this.handlers = [];
    }
  }

  /**
   * Iterate over all the registered interceptors
   *
   * This method is particularly useful for skipping over any
   * interceptors that may have become `null` calling `eject`.
   *
   * @param {Function} fn The function to call for each interceptor
   *
   * @returns {void}
   */
  forEach(fn) {
    utils$1.forEach(this.handlers, function forEachHandler(h) {
      if (h !== null) {
        fn(h);
      }
    });
  }
}

var InterceptorManager$1 = InterceptorManager;

var transitionalDefaults = {
  silentJSONParsing: true,
  forcedJSONParsing: true,
  clarifyTimeoutError: false,
  legacyInterceptorReqResOrdering: true
};

var URLSearchParams$1 = typeof URLSearchParams !== 'undefined' ? URLSearchParams : AxiosURLSearchParams;

var FormData$1 = typeof FormData !== 'undefined' ? FormData : null;

var Blob$1 = typeof Blob !== 'undefined' ? Blob : null;

var platform$1 = {
  isBrowser: true,
  classes: {
    URLSearchParams: URLSearchParams$1,
    FormData: FormData$1,
    Blob: Blob$1
  },
  protocols: ['http', 'https', 'file', 'blob', 'url', 'data']
};

const hasBrowserEnv = typeof window !== 'undefined' && typeof document !== 'undefined';

const _navigator = typeof navigator === 'object' && navigator || undefined;

/**
 * Determine if we're running in a standard browser environment
 *
 * This allows axios to run in a web worker, and react-native.
 * Both environments support XMLHttpRequest, but not fully standard globals.
 *
 * web workers:
 *  typeof window -> undefined
 *  typeof document -> undefined
 *
 * react-native:
 *  navigator.product -> 'ReactNative'
 * nativescript
 *  navigator.product -> 'NativeScript' or 'NS'
 *
 * @returns {boolean}
 */
const hasStandardBrowserEnv = hasBrowserEnv &&
  (!_navigator || ['ReactNative', 'NativeScript', 'NS'].indexOf(_navigator.product) < 0);

/**
 * Determine if we're running in a standard browser webWorker environment
 *
 * Although the `isStandardBrowserEnv` method indicates that
 * `allows axios to run in a web worker`, the WebWorker will still be
 * filtered out due to its judgment standard
 * `typeof window !== 'undefined' && typeof document !== 'undefined'`.
 * This leads to a problem when axios post `FormData` in webWorker
 */
const hasStandardBrowserWebWorkerEnv = (() => {
  return (
    typeof WorkerGlobalScope !== 'undefined' &&
    // eslint-disable-next-line no-undef
    self instanceof WorkerGlobalScope &&
    typeof self.importScripts === 'function'
  );
})();

const origin = hasBrowserEnv && window.location.href || 'http://localhost';

var utils = /*#__PURE__*/Object.freeze({
  __proto__: null,
  hasBrowserEnv: hasBrowserEnv,
  hasStandardBrowserWebWorkerEnv: hasStandardBrowserWebWorkerEnv,
  hasStandardBrowserEnv: hasStandardBrowserEnv,
  navigator: _navigator,
  origin: origin
});

var platform = {
  ...utils,
  ...platform$1
};

function toURLEncodedForm(data, options) {
  return toFormData(data, new platform.classes.URLSearchParams(), {
    visitor: function(value, key, path, helpers) {
      if (platform.isNode && utils$1.isBuffer(value)) {
        this.append(key, value.toString('base64'));
        return false;
      }

      return helpers.defaultVisitor.apply(this, arguments);
    },
    ...options
  });
}

/**
 * It takes a string like `foo[x][y][z]` and returns an array like `['foo', 'x', 'y', 'z']
 *
 * @param {string} name - The name of the property to get.
 *
 * @returns An array of strings.
 */
function parsePropPath(name) {
  // foo[x][y][z]
  // foo.x.y.z
  // foo-x-y-z
  // foo x y z
  return utils$1.matchAll(/\w+|\[(\w*)]/g, name).map(match => {
    return match[0] === '[]' ? '' : match[1] || match[0];
  });
}

/**
 * Convert an array to an object.
 *
 * @param {Array<any>} arr - The array to convert to an object.
 *
 * @returns An object with the same keys and values as the array.
 */
function arrayToObject(arr) {
  const obj = {};
  const keys = Object.keys(arr);
  let i;
  const len = keys.length;
  let key;
  for (i = 0; i < len; i++) {
    key = keys[i];
    obj[key] = arr[key];
  }
  return obj;
}

/**
 * It takes a FormData object and returns a JavaScript object
 *
 * @param {string} formData The FormData object to convert to JSON.
 *
 * @returns {Object<string, any> | null} The converted object.
 */
function formDataToJSON(formData) {
  function buildPath(path, value, target, index) {
    let name = path[index++];

    if (name === '__proto__') return true;

    const isNumericKey = Number.isFinite(+name);
    const isLast = index >= path.length;
    name = !name && utils$1.isArray(target) ? target.length : name;

    if (isLast) {
      if (utils$1.hasOwnProp(target, name)) {
        target[name] = [target[name], value];
      } else {
        target[name] = value;
      }

      return !isNumericKey;
    }

    if (!target[name] || !utils$1.isObject(target[name])) {
      target[name] = [];
    }

    const result = buildPath(path, value, target[name], index);

    if (result && utils$1.isArray(target[name])) {
      target[name] = arrayToObject(target[name]);
    }

    return !isNumericKey;
  }

  if (utils$1.isFormData(formData) && utils$1.isFunction(formData.entries)) {
    const obj = {};

    utils$1.forEachEntry(formData, (name, value) => {
      buildPath(parsePropPath(name), value, obj, 0);
    });

    return obj;
  }

  return null;
}

/**
 * It takes a string, tries to parse it, and if it fails, it returns the stringified version
 * of the input
 *
 * @param {any} rawValue - The value to be stringified.
 * @param {Function} parser - A function that parses a string into a JavaScript object.
 * @param {Function} encoder - A function that takes a value and returns a string.
 *
 * @returns {string} A stringified version of the rawValue.
 */
function stringifySafely(rawValue, parser, encoder) {
  if (utils$1.isString(rawValue)) {
    try {
      (parser || JSON.parse)(rawValue);
      return utils$1.trim(rawValue);
    } catch (e) {
      if (e.name !== 'SyntaxError') {
        throw e;
      }
    }
  }

  return (encoder || JSON.stringify)(rawValue);
}

const defaults = {

  transitional: transitionalDefaults,

  adapter: ['xhr', 'http', 'fetch'],

  transformRequest: [function transformRequest(data, headers) {
    const contentType = headers.getContentType() || '';
    const hasJSONContentType = contentType.indexOf('application/json') > -1;
    const isObjectPayload = utils$1.isObject(data);

    if (isObjectPayload && utils$1.isHTMLForm(data)) {
      data = new FormData(data);
    }

    const isFormData = utils$1.isFormData(data);

    if (isFormData) {
      return hasJSONContentType ? JSON.stringify(formDataToJSON(data)) : data;
    }

    if (utils$1.isArrayBuffer(data) ||
      utils$1.isBuffer(data) ||
      utils$1.isStream(data) ||
      utils$1.isFile(data) ||
      utils$1.isBlob(data) ||
      utils$1.isReadableStream(data)
    ) {
      return data;
    }
    if (utils$1.isArrayBufferView(data)) {
      return data.buffer;
    }
    if (utils$1.isURLSearchParams(data)) {
      headers.setContentType('application/x-www-form-urlencoded;charset=utf-8', false);
      return data.toString();
    }

    let isFileList;

    if (isObjectPayload) {
      if (contentType.indexOf('application/x-www-form-urlencoded') > -1) {
        return toURLEncodedForm(data, this.formSerializer).toString();
      }

      if ((isFileList = utils$1.isFileList(data)) || contentType.indexOf('multipart/form-data') > -1) {
        const _FormData = this.env && this.env.FormData;

        return toFormData(
          isFileList ? {'files[]': data} : data,
          _FormData && new _FormData(),
          this.formSerializer
        );
      }
    }

    if (isObjectPayload || hasJSONContentType ) {
      headers.setContentType('application/json', false);
      return stringifySafely(data);
    }

    return data;
  }],

  transformResponse: [function transformResponse(data) {
    const transitional = this.transitional || defaults.transitional;
    const forcedJSONParsing = transitional && transitional.forcedJSONParsing;
    const JSONRequested = this.responseType === 'json';

    if (utils$1.isResponse(data) || utils$1.isReadableStream(data)) {
      return data;
    }

    if (data && utils$1.isString(data) && ((forcedJSONParsing && !this.responseType) || JSONRequested)) {
      const silentJSONParsing = transitional && transitional.silentJSONParsing;
      const strictJSONParsing = !silentJSONParsing && JSONRequested;

      try {
        return JSON.parse(data, this.parseReviver);
      } catch (e) {
        if (strictJSONParsing) {
          if (e.name === 'SyntaxError') {
            throw AxiosError$1.from(e, AxiosError$1.ERR_BAD_RESPONSE, this, null, this.response);
          }
          throw e;
        }
      }
    }

    return data;
  }],

  /**
   * A timeout in milliseconds to abort a request. If set to 0 (default) a
   * timeout is not created.
   */
  timeout: 0,

  xsrfCookieName: 'XSRF-TOKEN',
  xsrfHeaderName: 'X-XSRF-TOKEN',

  maxContentLength: -1,
  maxBodyLength: -1,

  env: {
    FormData: platform.classes.FormData,
    Blob: platform.classes.Blob
  },

  validateStatus: function validateStatus(status) {
    return status >= 200 && status < 300;
  },

  headers: {
    common: {
      'Accept': 'application/json, text/plain, */*',
      'Content-Type': undefined
    }
  }
};

utils$1.forEach(['delete', 'get', 'head', 'post', 'put', 'patch'], (method) => {
  defaults.headers[method] = {};
});

var defaults$1 = defaults;

// RawAxiosHeaders whose duplicates are ignored by node
// c.f. https://nodejs.org/api/http.html#http_message_headers
const ignoreDuplicateOf = utils$1.toObjectSet([
  'age', 'authorization', 'content-length', 'content-type', 'etag',
  'expires', 'from', 'host', 'if-modified-since', 'if-unmodified-since',
  'last-modified', 'location', 'max-forwards', 'proxy-authorization',
  'referer', 'retry-after', 'user-agent'
]);

/**
 * Parse headers into an object
 *
 * ```
 * Date: Wed, 27 Aug 2014 08:58:49 GMT
 * Content-Type: application/json
 * Connection: keep-alive
 * Transfer-Encoding: chunked
 * ```
 *
 * @param {String} rawHeaders Headers needing to be parsed
 *
 * @returns {Object} Headers parsed into an object
 */
var parseHeaders = rawHeaders => {
  const parsed = {};
  let key;
  let val;
  let i;

  rawHeaders && rawHeaders.split('\n').forEach(function parser(line) {
    i = line.indexOf(':');
    key = line.substring(0, i).trim().toLowerCase();
    val = line.substring(i + 1).trim();

    if (!key || (parsed[key] && ignoreDuplicateOf[key])) {
      return;
    }

    if (key === 'set-cookie') {
      if (parsed[key]) {
        parsed[key].push(val);
      } else {
        parsed[key] = [val];
      }
    } else {
      parsed[key] = parsed[key] ? parsed[key] + ', ' + val : val;
    }
  });

  return parsed;
};

const $internals = Symbol('internals');

function normalizeHeader(header) {
  return header && String(header).trim().toLowerCase();
}

function normalizeValue(value) {
  if (value === false || value == null) {
    return value;
  }

  return utils$1.isArray(value) ? value.map(normalizeValue) : String(value);
}

function parseTokens(str) {
  const tokens = Object.create(null);
  const tokensRE = /([^\s,;=]+)\s*(?:=\s*([^,;]+))?/g;
  let match;

  while ((match = tokensRE.exec(str))) {
    tokens[match[1]] = match[2];
  }

  return tokens;
}

const isValidHeaderName = (str) => /^[-_a-zA-Z0-9^`|~,!#$%&'*+.]+$/.test(str.trim());

function matchHeaderValue(context, value, header, filter, isHeaderNameFilter) {
  if (utils$1.isFunction(filter)) {
    return filter.call(this, value, header);
  }

  if (isHeaderNameFilter) {
    value = header;
  }

  if (!utils$1.isString(value)) return;

  if (utils$1.isString(filter)) {
    return value.indexOf(filter) !== -1;
  }

  if (utils$1.isRegExp(filter)) {
    return filter.test(value);
  }
}

function formatHeader(header) {
  return header.trim()
    .toLowerCase().replace(/([a-z\d])(\w*)/g, (w, char, str) => {
      return char.toUpperCase() + str;
    });
}

function buildAccessors(obj, header) {
  const accessorName = utils$1.toCamelCase(' ' + header);

  ['get', 'set', 'has'].forEach(methodName => {
    Object.defineProperty(obj, methodName + accessorName, {
      value: function(arg1, arg2, arg3) {
        return this[methodName].call(this, header, arg1, arg2, arg3);
      },
      configurable: true
    });
  });
}

class AxiosHeaders {
  constructor(headers) {
    headers && this.set(headers);
  }

  set(header, valueOrRewrite, rewrite) {
    const self = this;

    function setHeader(_value, _header, _rewrite) {
      const lHeader = normalizeHeader(_header);

      if (!lHeader) {
        throw new Error('header name must be a non-empty string');
      }

      const key = utils$1.findKey(self, lHeader);

      if(!key || self[key] === undefined || _rewrite === true || (_rewrite === undefined && self[key] !== false)) {
        self[key || _header] = normalizeValue(_value);
      }
    }

    const setHeaders = (headers, _rewrite) =>
      utils$1.forEach(headers, (_value, _header) => setHeader(_value, _header, _rewrite));

    if (utils$1.isPlainObject(header) || header instanceof this.constructor) {
      setHeaders(header, valueOrRewrite);
    } else if(utils$1.isString(header) && (header = header.trim()) && !isValidHeaderName(header)) {
      setHeaders(parseHeaders(header), valueOrRewrite);
    } else if (utils$1.isObject(header) && utils$1.isIterable(header)) {
      let obj = {}, dest, key;
      for (const entry of header) {
        if (!utils$1.isArray(entry)) {
          throw TypeError('Object iterator must return a key-value pair');
        }

        obj[key = entry[0]] = (dest = obj[key]) ?
          (utils$1.isArray(dest) ? [...dest, entry[1]] : [dest, entry[1]]) : entry[1];
      }

      setHeaders(obj, valueOrRewrite);
    } else {
      header != null && setHeader(valueOrRewrite, header, rewrite);
    }

    return this;
  }

  get(header, parser) {
    header = normalizeHeader(header);

    if (header) {
      const key = utils$1.findKey(this, header);

      if (key) {
        const value = this[key];

        if (!parser) {
          return value;
        }

        if (parser === true) {
          return parseTokens(value);
        }

        if (utils$1.isFunction(parser)) {
          return parser.call(this, value, key);
        }

        if (utils$1.isRegExp(parser)) {
          return parser.exec(value);
        }

        throw new TypeError('parser must be boolean|regexp|function');
      }
    }
  }

  has(header, matcher) {
    header = normalizeHeader(header);

    if (header) {
      const key = utils$1.findKey(this, header);

      return !!(key && this[key] !== undefined && (!matcher || matchHeaderValue(this, this[key], key, matcher)));
    }

    return false;
  }

  delete(header, matcher) {
    const self = this;
    let deleted = false;

    function deleteHeader(_header) {
      _header = normalizeHeader(_header);

      if (_header) {
        const key = utils$1.findKey(self, _header);

        if (key && (!matcher || matchHeaderValue(self, self[key], key, matcher))) {
          delete self[key];

          deleted = true;
        }
      }
    }

    if (utils$1.isArray(header)) {
      header.forEach(deleteHeader);
    } else {
      deleteHeader(header);
    }

    return deleted;
  }

  clear(matcher) {
    const keys = Object.keys(this);
    let i = keys.length;
    let deleted = false;

    while (i--) {
      const key = keys[i];
      if(!matcher || matchHeaderValue(this, this[key], key, matcher, true)) {
        delete this[key];
        deleted = true;
      }
    }

    return deleted;
  }

  normalize(format) {
    const self = this;
    const headers = {};

    utils$1.forEach(this, (value, header) => {
      const key = utils$1.findKey(headers, header);

      if (key) {
        self[key] = normalizeValue(value);
        delete self[header];
        return;
      }

      const normalized = format ? formatHeader(header) : String(header).trim();

      if (normalized !== header) {
        delete self[header];
      }

      self[normalized] = normalizeValue(value);

      headers[normalized] = true;
    });

    return this;
  }

  concat(...targets) {
    return this.constructor.concat(this, ...targets);
  }

  toJSON(asStrings) {
    const obj = Object.create(null);

    utils$1.forEach(this, (value, header) => {
      value != null && value !== false && (obj[header] = asStrings && utils$1.isArray(value) ? value.join(', ') : value);
    });

    return obj;
  }

  [Symbol.iterator]() {
    return Object.entries(this.toJSON())[Symbol.iterator]();
  }

  toString() {
    return Object.entries(this.toJSON()).map(([header, value]) => header + ': ' + value).join('\n');
  }

  getSetCookie() {
    return this.get("set-cookie") || [];
  }

  get [Symbol.toStringTag]() {
    return 'AxiosHeaders';
  }

  static from(thing) {
    return thing instanceof this ? thing : new this(thing);
  }

  static concat(first, ...targets) {
    const computed = new this(first);

    targets.forEach((target) => computed.set(target));

    return computed;
  }

  static accessor(header) {
    const internals = this[$internals] = (this[$internals] = {
      accessors: {}
    });

    const accessors = internals.accessors;
    const prototype = this.prototype;

    function defineAccessor(_header) {
      const lHeader = normalizeHeader(_header);

      if (!accessors[lHeader]) {
        buildAccessors(prototype, _header);
        accessors[lHeader] = true;
      }
    }

    utils$1.isArray(header) ? header.forEach(defineAccessor) : defineAccessor(header);

    return this;
  }
}

AxiosHeaders.accessor(['Content-Type', 'Content-Length', 'Accept', 'Accept-Encoding', 'User-Agent', 'Authorization']);

// reserved names hotfix
utils$1.reduceDescriptors(AxiosHeaders.prototype, ({value}, key) => {
  let mapped = key[0].toUpperCase() + key.slice(1); // map `set` => `Set`
  return {
    get: () => value,
    set(headerValue) {
      this[mapped] = headerValue;
    }
  }
});

utils$1.freezeMethods(AxiosHeaders);

var AxiosHeaders$1 = AxiosHeaders;

/**
 * Transform the data for a request or a response
 *
 * @param {Array|Function} fns A single function or Array of functions
 * @param {?Object} response The response object
 *
 * @returns {*} The resulting transformed data
 */
function transformData(fns, response) {
  const config = this || defaults$1;
  const context = response || config;
  const headers = AxiosHeaders$1.from(context.headers);
  let data = context.data;

  utils$1.forEach(fns, function transform(fn) {
    data = fn.call(config, data, headers.normalize(), response ? response.status : undefined);
  });

  headers.normalize();

  return data;
}

function isCancel(value) {
  return !!(value && value.__CANCEL__);
}

class CanceledError extends AxiosError$1 {
  /**
   * A `CanceledError` is an object that is thrown when an operation is canceled.
   *
   * @param {string=} message The message.
   * @param {Object=} config The config.
   * @param {Object=} request The request.
   *
   * @returns {CanceledError} The created error.
   */
  constructor(message, config, request) {
    super(message == null ? 'canceled' : message, AxiosError$1.ERR_CANCELED, config, request);
    this.name = 'CanceledError';
    this.__CANCEL__ = true;
  }
}

var CanceledError$1 = CanceledError;

/**
 * Resolve or reject a Promise based on response status.
 *
 * @param {Function} resolve A function that resolves the promise.
 * @param {Function} reject A function that rejects the promise.
 * @param {object} response The response.
 *
 * @returns {object} The response.
 */
function settle(resolve, reject, response) {
  const validateStatus = response.config.validateStatus;
  if (!response.status || !validateStatus || validateStatus(response.status)) {
    resolve(response);
  } else {
    reject(new AxiosError$1(
      'Request failed with status code ' + response.status,
      [AxiosError$1.ERR_BAD_REQUEST, AxiosError$1.ERR_BAD_RESPONSE][Math.floor(response.status / 100) - 4],
      response.config,
      response.request,
      response
    ));
  }
}

function parseProtocol(url) {
  const match = /^([-+\w]{1,25})(:?\/\/|:)/.exec(url);
  return match && match[1] || '';
}

/**
 * Calculate data maxRate
 * @param {Number} [samplesCount= 10]
 * @param {Number} [min= 1000]
 * @returns {Function}
 */
function speedometer(samplesCount, min) {
  samplesCount = samplesCount || 10;
  const bytes = new Array(samplesCount);
  const timestamps = new Array(samplesCount);
  let head = 0;
  let tail = 0;
  let firstSampleTS;

  min = min !== undefined ? min : 1000;

  return function push(chunkLength) {
    const now = Date.now();

    const startedAt = timestamps[tail];

    if (!firstSampleTS) {
      firstSampleTS = now;
    }

    bytes[head] = chunkLength;
    timestamps[head] = now;

    let i = tail;
    let bytesCount = 0;

    while (i !== head) {
      bytesCount += bytes[i++];
      i = i % samplesCount;
    }

    head = (head + 1) % samplesCount;

    if (head === tail) {
      tail = (tail + 1) % samplesCount;
    }

    if (now - firstSampleTS < min) {
      return;
    }

    const passed = startedAt && now - startedAt;

    return passed ? Math.round(bytesCount * 1000 / passed) : undefined;
  };
}

/**
 * Throttle decorator
 * @param {Function} fn
 * @param {Number} freq
 * @return {Function}
 */
function throttle(fn, freq) {
  let timestamp = 0;
  let threshold = 1000 / freq;
  let lastArgs;
  let timer;

  const invoke = (args, now = Date.now()) => {
    timestamp = now;
    lastArgs = null;
    if (timer) {
      clearTimeout(timer);
      timer = null;
    }
    fn(...args);
  };

  const throttled = (...args) => {
    const now = Date.now();
    const passed = now - timestamp;
    if ( passed >= threshold) {
      invoke(args, now);
    } else {
      lastArgs = args;
      if (!timer) {
        timer = setTimeout(() => {
          timer = null;
          invoke(lastArgs);
        }, threshold - passed);
      }
    }
  };

  const flush = () => lastArgs && invoke(lastArgs);

  return [throttled, flush];
}

const progressEventReducer = (listener, isDownloadStream, freq = 3) => {
  let bytesNotified = 0;
  const _speedometer = speedometer(50, 250);

  return throttle(e => {
    const loaded = e.loaded;
    const total = e.lengthComputable ? e.total : undefined;
    const progressBytes = loaded - bytesNotified;
    const rate = _speedometer(progressBytes);
    const inRange = loaded <= total;

    bytesNotified = loaded;

    const data = {
      loaded,
      total,
      progress: total ? (loaded / total) : undefined,
      bytes: progressBytes,
      rate: rate ? rate : undefined,
      estimated: rate && total && inRange ? (total - loaded) / rate : undefined,
      event: e,
      lengthComputable: total != null,
      [isDownloadStream ? 'download' : 'upload']: true
    };

    listener(data);
  }, freq);
};

const progressEventDecorator = (total, throttled) => {
  const lengthComputable = total != null;

  return [(loaded) => throttled[0]({
    lengthComputable,
    total,
    loaded
  }), throttled[1]];
};

const asyncDecorator = (fn) => (...args) => utils$1.asap(() => fn(...args));

var isURLSameOrigin = platform.hasStandardBrowserEnv ? ((origin, isMSIE) => (url) => {
  url = new URL(url, platform.origin);

  return (
    origin.protocol === url.protocol &&
    origin.host === url.host &&
    (isMSIE || origin.port === url.port)
  );
})(
  new URL(platform.origin),
  platform.navigator && /(msie|trident)/i.test(platform.navigator.userAgent)
) : () => true;

var cookies = platform.hasStandardBrowserEnv ?

  // Standard browser envs support document.cookie
  {
    write(name, value, expires, path, domain, secure, sameSite) {
      if (typeof document === 'undefined') return;

      const cookie = [`${name}=${encodeURIComponent(value)}`];

      if (utils$1.isNumber(expires)) {
        cookie.push(`expires=${new Date(expires).toUTCString()}`);
      }
      if (utils$1.isString(path)) {
        cookie.push(`path=${path}`);
      }
      if (utils$1.isString(domain)) {
        cookie.push(`domain=${domain}`);
      }
      if (secure === true) {
        cookie.push('secure');
      }
      if (utils$1.isString(sameSite)) {
        cookie.push(`SameSite=${sameSite}`);
      }

      document.cookie = cookie.join('; ');
    },

    read(name) {
      if (typeof document === 'undefined') return null;
      const match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
      return match ? decodeURIComponent(match[1]) : null;
    },

    remove(name) {
      this.write(name, '', Date.now() - 86400000, '/');
    }
  }

  :

  // Non-standard browser env (web workers, react-native) lack needed support.
  {
    write() {},
    read() {
      return null;
    },
    remove() {}
  };

/**
 * Determines whether the specified URL is absolute
 *
 * @param {string} url The URL to test
 *
 * @returns {boolean} True if the specified URL is absolute, otherwise false
 */
function isAbsoluteURL(url) {
  // A URL is considered absolute if it begins with "<scheme>://" or "//" (protocol-relative URL).
  // RFC 3986 defines scheme name as a sequence of characters beginning with a letter and followed
  // by any combination of letters, digits, plus, period, or hyphen.
  if (typeof url !== 'string') {
    return false;
  }

  return /^([a-z][a-z\d+\-.]*:)?\/\//i.test(url);
}

/**
 * Creates a new URL by combining the specified URLs
 *
 * @param {string} baseURL The base URL
 * @param {string} relativeURL The relative URL
 *
 * @returns {string} The combined URL
 */
function combineURLs(baseURL, relativeURL) {
  return relativeURL
    ? baseURL.replace(/\/?\/$/, '') + '/' + relativeURL.replace(/^\/+/, '')
    : baseURL;
}

/**
 * Creates a new URL by combining the baseURL with the requestedURL,
 * only when the requestedURL is not already an absolute URL.
 * If the requestURL is absolute, this function returns the requestedURL untouched.
 *
 * @param {string} baseURL The base URL
 * @param {string} requestedURL Absolute or relative URL to combine
 *
 * @returns {string} The combined full path
 */
function buildFullPath(baseURL, requestedURL, allowAbsoluteUrls) {
  let isRelativeUrl = !isAbsoluteURL(requestedURL);
  if (baseURL && (isRelativeUrl || allowAbsoluteUrls == false)) {
    return combineURLs(baseURL, requestedURL);
  }
  return requestedURL;
}

const headersToObject = (thing) =>
  thing instanceof AxiosHeaders$1 ? { ...thing } : thing;

/**
 * Config-specific merge-function which creates a new config-object
 * by merging two configuration objects together.
 *
 * @param {Object} config1
 * @param {Object} config2
 *
 * @returns {Object} New object resulting from merging config2 to config1
 */
function mergeConfig(config1, config2) {
  // eslint-disable-next-line no-param-reassign
  config2 = config2 || {};
  const config = {};

  function getMergedValue(target, source, prop, caseless) {
    if (utils$1.isPlainObject(target) && utils$1.isPlainObject(source)) {
      return utils$1.merge.call({ caseless }, target, source);
    } else if (utils$1.isPlainObject(source)) {
      return utils$1.merge({}, source);
    } else if (utils$1.isArray(source)) {
      return source.slice();
    }
    return source;
  }

  function mergeDeepProperties(a, b, prop, caseless) {
    if (!utils$1.isUndefined(b)) {
      return getMergedValue(a, b, prop, caseless);
    } else if (!utils$1.isUndefined(a)) {
      return getMergedValue(undefined, a, prop, caseless);
    }
  }

  // eslint-disable-next-line consistent-return
  function valueFromConfig2(a, b) {
    if (!utils$1.isUndefined(b)) {
      return getMergedValue(undefined, b);
    }
  }

  // eslint-disable-next-line consistent-return
  function defaultToConfig2(a, b) {
    if (!utils$1.isUndefined(b)) {
      return getMergedValue(undefined, b);
    } else if (!utils$1.isUndefined(a)) {
      return getMergedValue(undefined, a);
    }
  }

  // eslint-disable-next-line consistent-return
  function mergeDirectKeys(a, b, prop) {
    if (prop in config2) {
      return getMergedValue(a, b);
    } else if (prop in config1) {
      return getMergedValue(undefined, a);
    }
  }

  const mergeMap = {
    url: valueFromConfig2,
    method: valueFromConfig2,
    data: valueFromConfig2,
    baseURL: defaultToConfig2,
    transformRequest: defaultToConfig2,
    transformResponse: defaultToConfig2,
    paramsSerializer: defaultToConfig2,
    timeout: defaultToConfig2,
    timeoutMessage: defaultToConfig2,
    withCredentials: defaultToConfig2,
    withXSRFToken: defaultToConfig2,
    adapter: defaultToConfig2,
    responseType: defaultToConfig2,
    xsrfCookieName: defaultToConfig2,
    xsrfHeaderName: defaultToConfig2,
    onUploadProgress: defaultToConfig2,
    onDownloadProgress: defaultToConfig2,
    decompress: defaultToConfig2,
    maxContentLength: defaultToConfig2,
    maxBodyLength: defaultToConfig2,
    beforeRedirect: defaultToConfig2,
    transport: defaultToConfig2,
    httpAgent: defaultToConfig2,
    httpsAgent: defaultToConfig2,
    cancelToken: defaultToConfig2,
    socketPath: defaultToConfig2,
    responseEncoding: defaultToConfig2,
    validateStatus: mergeDirectKeys,
    headers: (a, b, prop) =>
      mergeDeepProperties(headersToObject(a), headersToObject(b), prop, true),
  };

  utils$1.forEach(
    Object.keys({ ...config1, ...config2 }),
    function computeConfigValue(prop) {
      if (
        prop === "__proto__" ||
        prop === "constructor" ||
        prop === "prototype"
      )
        return;
      const merge = utils$1.hasOwnProp(mergeMap, prop)
        ? mergeMap[prop]
        : mergeDeepProperties;
      const configValue = merge(config1[prop], config2[prop], prop);
      (utils$1.isUndefined(configValue) && merge !== mergeDirectKeys) ||
        (config[prop] = configValue);
    },
  );

  return config;
}

var resolveConfig = (config) => {
  const newConfig = mergeConfig({}, config);

  let { data, withXSRFToken, xsrfHeaderName, xsrfCookieName, headers, auth } = newConfig;

  newConfig.headers = headers = AxiosHeaders$1.from(headers);

  newConfig.url = buildURL(buildFullPath(newConfig.baseURL, newConfig.url, newConfig.allowAbsoluteUrls), config.params, config.paramsSerializer);

  // HTTP basic authentication
  if (auth) {
    headers.set('Authorization', 'Basic ' +
      btoa((auth.username || '') + ':' + (auth.password ? unescape(encodeURIComponent(auth.password)) : ''))
    );
  }

  if (utils$1.isFormData(data)) {
    if (platform.hasStandardBrowserEnv || platform.hasStandardBrowserWebWorkerEnv) {
      headers.setContentType(undefined); // browser handles it
    } else if (utils$1.isFunction(data.getHeaders)) {
      // Node.js FormData (like form-data package)
      const formHeaders = data.getHeaders();
      // Only set safe headers to avoid overwriting security headers
      const allowedHeaders = ['content-type', 'content-length'];
      Object.entries(formHeaders).forEach(([key, val]) => {
        if (allowedHeaders.includes(key.toLowerCase())) {
          headers.set(key, val);
        }
      });
    }
  }  

  // Add xsrf header
  // This is only done if running in a standard browser environment.
  // Specifically not if we're in a web worker, or react-native.

  if (platform.hasStandardBrowserEnv) {
    withXSRFToken && utils$1.isFunction(withXSRFToken) && (withXSRFToken = withXSRFToken(newConfig));

    if (withXSRFToken || (withXSRFToken !== false && isURLSameOrigin(newConfig.url))) {
      // Add xsrf header
      const xsrfValue = xsrfHeaderName && xsrfCookieName && cookies.read(xsrfCookieName);

      if (xsrfValue) {
        headers.set(xsrfHeaderName, xsrfValue);
      }
    }
  }

  return newConfig;
};

const isXHRAdapterSupported = typeof XMLHttpRequest !== 'undefined';

var xhrAdapter = isXHRAdapterSupported && function (config) {
  return new Promise(function dispatchXhrRequest(resolve, reject) {
    const _config = resolveConfig(config);
    let requestData = _config.data;
    const requestHeaders = AxiosHeaders$1.from(_config.headers).normalize();
    let {responseType, onUploadProgress, onDownloadProgress} = _config;
    let onCanceled;
    let uploadThrottled, downloadThrottled;
    let flushUpload, flushDownload;

    function done() {
      flushUpload && flushUpload(); // flush events
      flushDownload && flushDownload(); // flush events

      _config.cancelToken && _config.cancelToken.unsubscribe(onCanceled);

      _config.signal && _config.signal.removeEventListener('abort', onCanceled);
    }

    let request = new XMLHttpRequest();

    request.open(_config.method.toUpperCase(), _config.url, true);

    // Set the request timeout in MS
    request.timeout = _config.timeout;

    function onloadend() {
      if (!request) {
        return;
      }
      // Prepare the response
      const responseHeaders = AxiosHeaders$1.from(
        'getAllResponseHeaders' in request && request.getAllResponseHeaders()
      );
      const responseData = !responseType || responseType === 'text' || responseType === 'json' ?
        request.responseText : request.response;
      const response = {
        data: responseData,
        status: request.status,
        statusText: request.statusText,
        headers: responseHeaders,
        config,
        request
      };

      settle(function _resolve(value) {
        resolve(value);
        done();
      }, function _reject(err) {
        reject(err);
        done();
      }, response);

      // Clean up request
      request = null;
    }

    if ('onloadend' in request) {
      // Use onloadend if available
      request.onloadend = onloadend;
    } else {
      // Listen for ready state to emulate onloadend
      request.onreadystatechange = function handleLoad() {
        if (!request || request.readyState !== 4) {
          return;
        }

        // The request errored out and we didn't get a response, this will be
        // handled by onerror instead
        // With one exception: request that using file: protocol, most browsers
        // will return status as 0 even though it's a successful request
        if (request.status === 0 && !(request.responseURL && request.responseURL.indexOf('file:') === 0)) {
          return;
        }
        // readystate handler is calling before onerror or ontimeout handlers,
        // so we should call onloadend on the next 'tick'
        setTimeout(onloadend);
      };
    }

    // Handle browser request cancellation (as opposed to a manual cancellation)
    request.onabort = function handleAbort() {
      if (!request) {
        return;
      }

      reject(new AxiosError$1('Request aborted', AxiosError$1.ECONNABORTED, config, request));

      // Clean up request
      request = null;
    };

    // Handle low level network errors
  request.onerror = function handleError(event) {
       // Browsers deliver a ProgressEvent in XHR onerror
       // (message may be empty; when present, surface it)
       // See https://developer.mozilla.org/docs/Web/API/XMLHttpRequest/error_event
       const msg = event && event.message ? event.message : 'Network Error';
       const err = new AxiosError$1(msg, AxiosError$1.ERR_NETWORK, config, request);
       // attach the underlying event for consumers who want details
       err.event = event || null;
       reject(err);
       request = null;
    };
    
    // Handle timeout
    request.ontimeout = function handleTimeout() {
      let timeoutErrorMessage = _config.timeout ? 'timeout of ' + _config.timeout + 'ms exceeded' : 'timeout exceeded';
      const transitional = _config.transitional || transitionalDefaults;
      if (_config.timeoutErrorMessage) {
        timeoutErrorMessage = _config.timeoutErrorMessage;
      }
      reject(new AxiosError$1(
        timeoutErrorMessage,
        transitional.clarifyTimeoutError ? AxiosError$1.ETIMEDOUT : AxiosError$1.ECONNABORTED,
        config,
        request));

      // Clean up request
      request = null;
    };

    // Remove Content-Type if data is undefined
    requestData === undefined && requestHeaders.setContentType(null);

    // Add headers to the request
    if ('setRequestHeader' in request) {
      utils$1.forEach(requestHeaders.toJSON(), function setRequestHeader(val, key) {
        request.setRequestHeader(key, val);
      });
    }

    // Add withCredentials to request if needed
    if (!utils$1.isUndefined(_config.withCredentials)) {
      request.withCredentials = !!_config.withCredentials;
    }

    // Add responseType to request if needed
    if (responseType && responseType !== 'json') {
      request.responseType = _config.responseType;
    }

    // Handle progress if needed
    if (onDownloadProgress) {
      ([downloadThrottled, flushDownload] = progressEventReducer(onDownloadProgress, true));
      request.addEventListener('progress', downloadThrottled);
    }

    // Not all browsers support upload events
    if (onUploadProgress && request.upload) {
      ([uploadThrottled, flushUpload] = progressEventReducer(onUploadProgress));

      request.upload.addEventListener('progress', uploadThrottled);

      request.upload.addEventListener('loadend', flushUpload);
    }

    if (_config.cancelToken || _config.signal) {
      // Handle cancellation
      // eslint-disable-next-line func-names
      onCanceled = cancel => {
        if (!request) {
          return;
        }
        reject(!cancel || cancel.type ? new CanceledError$1(null, config, request) : cancel);
        request.abort();
        request = null;
      };

      _config.cancelToken && _config.cancelToken.subscribe(onCanceled);
      if (_config.signal) {
        _config.signal.aborted ? onCanceled() : _config.signal.addEventListener('abort', onCanceled);
      }
    }

    const protocol = parseProtocol(_config.url);

    if (protocol && platform.protocols.indexOf(protocol) === -1) {
      reject(new AxiosError$1('Unsupported protocol ' + protocol + ':', AxiosError$1.ERR_BAD_REQUEST, config));
      return;
    }


    // Send the request
    request.send(requestData || null);
  });
};

const composeSignals = (signals, timeout) => {
  const {length} = (signals = signals ? signals.filter(Boolean) : []);

  if (timeout || length) {
    let controller = new AbortController();

    let aborted;

    const onabort = function (reason) {
      if (!aborted) {
        aborted = true;
        unsubscribe();
        const err = reason instanceof Error ? reason : this.reason;
        controller.abort(err instanceof AxiosError$1 ? err : new CanceledError$1(err instanceof Error ? err.message : err));
      }
    };

    let timer = timeout && setTimeout(() => {
      timer = null;
      onabort(new AxiosError$1(`timeout of ${timeout}ms exceeded`, AxiosError$1.ETIMEDOUT));
    }, timeout);

    const unsubscribe = () => {
      if (signals) {
        timer && clearTimeout(timer);
        timer = null;
        signals.forEach(signal => {
          signal.unsubscribe ? signal.unsubscribe(onabort) : signal.removeEventListener('abort', onabort);
        });
        signals = null;
      }
    };

    signals.forEach((signal) => signal.addEventListener('abort', onabort));

    const {signal} = controller;

    signal.unsubscribe = () => utils$1.asap(unsubscribe);

    return signal;
  }
};

var composeSignals$1 = composeSignals;

const streamChunk = function* (chunk, chunkSize) {
  let len = chunk.byteLength;

  if (!chunkSize || len < chunkSize) {
    yield chunk;
    return;
  }

  let pos = 0;
  let end;

  while (pos < len) {
    end = pos + chunkSize;
    yield chunk.slice(pos, end);
    pos = end;
  }
};

const readBytes = async function* (iterable, chunkSize) {
  for await (const chunk of readStream(iterable)) {
    yield* streamChunk(chunk, chunkSize);
  }
};

const readStream = async function* (stream) {
  if (stream[Symbol.asyncIterator]) {
    yield* stream;
    return;
  }

  const reader = stream.getReader();
  try {
    for (;;) {
      const {done, value} = await reader.read();
      if (done) {
        break;
      }
      yield value;
    }
  } finally {
    await reader.cancel();
  }
};

const trackStream = (stream, chunkSize, onProgress, onFinish) => {
  const iterator = readBytes(stream, chunkSize);

  let bytes = 0;
  let done;
  let _onFinish = (e) => {
    if (!done) {
      done = true;
      onFinish && onFinish(e);
    }
  };

  return new ReadableStream({
    async pull(controller) {
      try {
        const {done, value} = await iterator.next();

        if (done) {
         _onFinish();
          controller.close();
          return;
        }

        let len = value.byteLength;
        if (onProgress) {
          let loadedBytes = bytes += len;
          onProgress(loadedBytes);
        }
        controller.enqueue(new Uint8Array(value));
      } catch (err) {
        _onFinish(err);
        throw err;
      }
    },
    cancel(reason) {
      _onFinish(reason);
      return iterator.return();
    }
  }, {
    highWaterMark: 2
  })
};

const DEFAULT_CHUNK_SIZE = 64 * 1024;

const {isFunction} = utils$1;

const globalFetchAPI = (({Request, Response}) => ({
  Request, Response
}))(utils$1.global);

const {
  ReadableStream: ReadableStream$1, TextEncoder
} = utils$1.global;


const test = (fn, ...args) => {
  try {
    return !!fn(...args);
  } catch (e) {
    return false
  }
};

const factory = (env) => {
  env = utils$1.merge.call({
    skipUndefined: true
  }, globalFetchAPI, env);

  const {fetch: envFetch, Request, Response} = env;
  const isFetchSupported = envFetch ? isFunction(envFetch) : typeof fetch === 'function';
  const isRequestSupported = isFunction(Request);
  const isResponseSupported = isFunction(Response);

  if (!isFetchSupported) {
    return false;
  }

  const isReadableStreamSupported = isFetchSupported && isFunction(ReadableStream$1);

  const encodeText = isFetchSupported && (typeof TextEncoder === 'function' ?
      ((encoder) => (str) => encoder.encode(str))(new TextEncoder()) :
      async (str) => new Uint8Array(await new Request(str).arrayBuffer())
  );

  const supportsRequestStream = isRequestSupported && isReadableStreamSupported && test(() => {
    let duplexAccessed = false;

    const hasContentType = new Request(platform.origin, {
      body: new ReadableStream$1(),
      method: 'POST',
      get duplex() {
        duplexAccessed = true;
        return 'half';
      },
    }).headers.has('Content-Type');

    return duplexAccessed && !hasContentType;
  });

  const supportsResponseStream = isResponseSupported && isReadableStreamSupported &&
    test(() => utils$1.isReadableStream(new Response('').body));

  const resolvers = {
    stream: supportsResponseStream && ((res) => res.body)
  };

  isFetchSupported && ((() => {
    ['text', 'arrayBuffer', 'blob', 'formData', 'stream'].forEach(type => {
      !resolvers[type] && (resolvers[type] = (res, config) => {
        let method = res && res[type];

        if (method) {
          return method.call(res);
        }

        throw new AxiosError$1(`Response type '${type}' is not supported`, AxiosError$1.ERR_NOT_SUPPORT, config);
      });
    });
  })());

  const getBodyLength = async (body) => {
    if (body == null) {
      return 0;
    }

    if (utils$1.isBlob(body)) {
      return body.size;
    }

    if (utils$1.isSpecCompliantForm(body)) {
      const _request = new Request(platform.origin, {
        method: 'POST',
        body,
      });
      return (await _request.arrayBuffer()).byteLength;
    }

    if (utils$1.isArrayBufferView(body) || utils$1.isArrayBuffer(body)) {
      return body.byteLength;
    }

    if (utils$1.isURLSearchParams(body)) {
      body = body + '';
    }

    if (utils$1.isString(body)) {
      return (await encodeText(body)).byteLength;
    }
  };

  const resolveBodyLength = async (headers, body) => {
    const length = utils$1.toFiniteNumber(headers.getContentLength());

    return length == null ? getBodyLength(body) : length;
  };

  return async (config) => {
    let {
      url,
      method,
      data,
      signal,
      cancelToken,
      timeout,
      onDownloadProgress,
      onUploadProgress,
      responseType,
      headers,
      withCredentials = 'same-origin',
      fetchOptions
    } = resolveConfig(config);

    let _fetch = envFetch || fetch;

    responseType = responseType ? (responseType + '').toLowerCase() : 'text';

    let composedSignal = composeSignals$1([signal, cancelToken && cancelToken.toAbortSignal()], timeout);

    let request = null;

    const unsubscribe = composedSignal && composedSignal.unsubscribe && (() => {
      composedSignal.unsubscribe();
    });

    let requestContentLength;

    try {
      if (
        onUploadProgress && supportsRequestStream && method !== 'get' && method !== 'head' &&
        (requestContentLength = await resolveBodyLength(headers, data)) !== 0
      ) {
        let _request = new Request(url, {
          method: 'POST',
          body: data,
          duplex: "half"
        });

        let contentTypeHeader;

        if (utils$1.isFormData(data) && (contentTypeHeader = _request.headers.get('content-type'))) {
          headers.setContentType(contentTypeHeader);
        }

        if (_request.body) {
          const [onProgress, flush] = progressEventDecorator(
            requestContentLength,
            progressEventReducer(asyncDecorator(onUploadProgress))
          );

          data = trackStream(_request.body, DEFAULT_CHUNK_SIZE, onProgress, flush);
        }
      }

      if (!utils$1.isString(withCredentials)) {
        withCredentials = withCredentials ? 'include' : 'omit';
      }

      // Cloudflare Workers throws when credentials are defined
      // see https://github.com/cloudflare/workerd/issues/902
      const isCredentialsSupported = isRequestSupported && "credentials" in Request.prototype;

      const resolvedOptions = {
        ...fetchOptions,
        signal: composedSignal,
        method: method.toUpperCase(),
        headers: headers.normalize().toJSON(),
        body: data,
        duplex: "half",
        credentials: isCredentialsSupported ? withCredentials : undefined
      };

      request = isRequestSupported && new Request(url, resolvedOptions);

      let response = await (isRequestSupported ? _fetch(request, fetchOptions) : _fetch(url, resolvedOptions));

      const isStreamResponse = supportsResponseStream && (responseType === 'stream' || responseType === 'response');

      if (supportsResponseStream && (onDownloadProgress || (isStreamResponse && unsubscribe))) {
        const options = {};

        ['status', 'statusText', 'headers'].forEach(prop => {
          options[prop] = response[prop];
        });

        const responseContentLength = utils$1.toFiniteNumber(response.headers.get('content-length'));

        const [onProgress, flush] = onDownloadProgress && progressEventDecorator(
          responseContentLength,
          progressEventReducer(asyncDecorator(onDownloadProgress), true)
        ) || [];

        response = new Response(
          trackStream(response.body, DEFAULT_CHUNK_SIZE, onProgress, () => {
            flush && flush();
            unsubscribe && unsubscribe();
          }),
          options
        );
      }

      responseType = responseType || 'text';

      let responseData = await resolvers[utils$1.findKey(resolvers, responseType) || 'text'](response, config);

      !isStreamResponse && unsubscribe && unsubscribe();

      return await new Promise((resolve, reject) => {
        settle(resolve, reject, {
          data: responseData,
          headers: AxiosHeaders$1.from(response.headers),
          status: response.status,
          statusText: response.statusText,
          config,
          request
        });
      })
    } catch (err) {
      unsubscribe && unsubscribe();

      if (err && err.name === 'TypeError' && /Load failed|fetch/i.test(err.message)) {
        throw Object.assign(
          new AxiosError$1('Network Error', AxiosError$1.ERR_NETWORK, config, request, err && err.response),
          {
            cause: err.cause || err
          }
        )
      }

      throw AxiosError$1.from(err, err && err.code, config, request, err && err.response);
    }
  }
};

const seedCache = new Map();

const getFetch = (config) => {
  let env = (config && config.env) || {};
  const {fetch, Request, Response} = env;
  const seeds = [
    Request, Response, fetch
  ];

  let len = seeds.length, i = len,
    seed, target, map = seedCache;

  while (i--) {
    seed = seeds[i];
    target = map.get(seed);

    target === undefined && map.set(seed, target = (i ? new Map() : factory(env)));

    map = target;
  }

  return target;
};

getFetch();

/**
 * Known adapters mapping.
 * Provides environment-specific adapters for Axios:
 * - `http` for Node.js
 * - `xhr` for browsers
 * - `fetch` for fetch API-based requests
 * 
 * @type {Object<string, Function|Object>}
 */
const knownAdapters = {
  http: httpAdapter,
  xhr: xhrAdapter,
  fetch: {
    get: getFetch,
  }
};

// Assign adapter names for easier debugging and identification
utils$1.forEach(knownAdapters, (fn, value) => {
  if (fn) {
    try {
      Object.defineProperty(fn, 'name', { value });
    } catch (e) {
      // eslint-disable-next-line no-empty
    }
    Object.defineProperty(fn, 'adapterName', { value });
  }
});

/**
 * Render a rejection reason string for unknown or unsupported adapters
 * 
 * @param {string} reason
 * @returns {string}
 */
const renderReason = (reason) => `- ${reason}`;

/**
 * Check if the adapter is resolved (function, null, or false)
 * 
 * @param {Function|null|false} adapter
 * @returns {boolean}
 */
const isResolvedHandle = (adapter) => utils$1.isFunction(adapter) || adapter === null || adapter === false;

/**
 * Get the first suitable adapter from the provided list.
 * Tries each adapter in order until a supported one is found.
 * Throws an AxiosError if no adapter is suitable.
 * 
 * @param {Array<string|Function>|string|Function} adapters - Adapter(s) by name or function.
 * @param {Object} config - Axios request configuration
 * @throws {AxiosError} If no suitable adapter is available
 * @returns {Function} The resolved adapter function
 */
function getAdapter(adapters, config) {
  adapters = utils$1.isArray(adapters) ? adapters : [adapters];

  const { length } = adapters;
  let nameOrAdapter;
  let adapter;

  const rejectedReasons = {};

  for (let i = 0; i < length; i++) {
    nameOrAdapter = adapters[i];
    let id;

    adapter = nameOrAdapter;

    if (!isResolvedHandle(nameOrAdapter)) {
      adapter = knownAdapters[(id = String(nameOrAdapter)).toLowerCase()];

      if (adapter === undefined) {
        throw new AxiosError$1(`Unknown adapter '${id}'`);
      }
    }

    if (adapter && (utils$1.isFunction(adapter) || (adapter = adapter.get(config)))) {
      break;
    }

    rejectedReasons[id || '#' + i] = adapter;
  }

  if (!adapter) {
    const reasons = Object.entries(rejectedReasons)
      .map(([id, state]) => `adapter ${id} ` +
        (state === false ? 'is not supported by the environment' : 'is not available in the build')
      );

    let s = length ?
      (reasons.length > 1 ? 'since :\n' + reasons.map(renderReason).join('\n') : ' ' + renderReason(reasons[0])) :
      'as no adapter specified';

    throw new AxiosError$1(
      `There is no suitable adapter to dispatch the request ` + s,
      'ERR_NOT_SUPPORT'
    );
  }

  return adapter;
}

/**
 * Exports Axios adapters and utility to resolve an adapter
 */
var adapters = {
  /**
   * Resolve an adapter from a list of adapter names or functions.
   * @type {Function}
   */
  getAdapter,

  /**
   * Exposes all known adapters
   * @type {Object<string, Function|Object>}
   */
  adapters: knownAdapters
};

/**
 * Throws a `CanceledError` if cancellation has been requested.
 *
 * @param {Object} config The config that is to be used for the request
 *
 * @returns {void}
 */
function throwIfCancellationRequested(config) {
  if (config.cancelToken) {
    config.cancelToken.throwIfRequested();
  }

  if (config.signal && config.signal.aborted) {
    throw new CanceledError$1(null, config);
  }
}

/**
 * Dispatch a request to the server using the configured adapter.
 *
 * @param {object} config The config that is to be used for the request
 *
 * @returns {Promise} The Promise to be fulfilled
 */
function dispatchRequest(config) {
  throwIfCancellationRequested(config);

  config.headers = AxiosHeaders$1.from(config.headers);

  // Transform request data
  config.data = transformData.call(
    config,
    config.transformRequest
  );

  if (['post', 'put', 'patch'].indexOf(config.method) !== -1) {
    config.headers.setContentType('application/x-www-form-urlencoded', false);
  }

  const adapter = adapters.getAdapter(config.adapter || defaults$1.adapter, config);

  return adapter(config).then(function onAdapterResolution(response) {
    throwIfCancellationRequested(config);

    // Transform response data
    response.data = transformData.call(
      config,
      config.transformResponse,
      response
    );

    response.headers = AxiosHeaders$1.from(response.headers);

    return response;
  }, function onAdapterRejection(reason) {
    if (!isCancel(reason)) {
      throwIfCancellationRequested(config);

      // Transform response data
      if (reason && reason.response) {
        reason.response.data = transformData.call(
          config,
          config.transformResponse,
          reason.response
        );
        reason.response.headers = AxiosHeaders$1.from(reason.response.headers);
      }
    }

    return Promise.reject(reason);
  });
}

const VERSION = "1.13.5";

const validators$1 = {};

// eslint-disable-next-line func-names
['object', 'boolean', 'number', 'function', 'string', 'symbol'].forEach((type, i) => {
  validators$1[type] = function validator(thing) {
    return typeof thing === type || 'a' + (i < 1 ? 'n ' : ' ') + type;
  };
});

const deprecatedWarnings = {};

/**
 * Transitional option validator
 *
 * @param {function|boolean?} validator - set to false if the transitional option has been removed
 * @param {string?} version - deprecated version / removed since version
 * @param {string?} message - some message with additional info
 *
 * @returns {function}
 */
validators$1.transitional = function transitional(validator, version, message) {
  function formatMessage(opt, desc) {
    return '[Axios v' + VERSION + '] Transitional option \'' + opt + '\'' + desc + (message ? '. ' + message : '');
  }

  // eslint-disable-next-line func-names
  return (value, opt, opts) => {
    if (validator === false) {
      throw new AxiosError$1(
        formatMessage(opt, ' has been removed' + (version ? ' in ' + version : '')),
        AxiosError$1.ERR_DEPRECATED
      );
    }

    if (version && !deprecatedWarnings[opt]) {
      deprecatedWarnings[opt] = true;
      // eslint-disable-next-line no-console
      console.warn(
        formatMessage(
          opt,
          ' has been deprecated since v' + version + ' and will be removed in the near future'
        )
      );
    }

    return validator ? validator(value, opt, opts) : true;
  };
};

validators$1.spelling = function spelling(correctSpelling) {
  return (value, opt) => {
    // eslint-disable-next-line no-console
    console.warn(`${opt} is likely a misspelling of ${correctSpelling}`);
    return true;
  }
};

/**
 * Assert object's properties type
 *
 * @param {object} options
 * @param {object} schema
 * @param {boolean?} allowUnknown
 *
 * @returns {object}
 */

function assertOptions(options, schema, allowUnknown) {
  if (typeof options !== 'object') {
    throw new AxiosError$1('options must be an object', AxiosError$1.ERR_BAD_OPTION_VALUE);
  }
  const keys = Object.keys(options);
  let i = keys.length;
  while (i-- > 0) {
    const opt = keys[i];
    const validator = schema[opt];
    if (validator) {
      const value = options[opt];
      const result = value === undefined || validator(value, opt, options);
      if (result !== true) {
        throw new AxiosError$1('option ' + opt + ' must be ' + result, AxiosError$1.ERR_BAD_OPTION_VALUE);
      }
      continue;
    }
    if (allowUnknown !== true) {
      throw new AxiosError$1('Unknown option ' + opt, AxiosError$1.ERR_BAD_OPTION);
    }
  }
}

var validator = {
  assertOptions,
  validators: validators$1
};

const validators = validator.validators;

/**
 * Create a new instance of Axios
 *
 * @param {Object} instanceConfig The default config for the instance
 *
 * @return {Axios} A new instance of Axios
 */
class Axios {
  constructor(instanceConfig) {
    this.defaults = instanceConfig || {};
    this.interceptors = {
      request: new InterceptorManager$1(),
      response: new InterceptorManager$1()
    };
  }

  /**
   * Dispatch a request
   *
   * @param {String|Object} configOrUrl The config specific for this request (merged with this.defaults)
   * @param {?Object} config
   *
   * @returns {Promise} The Promise to be fulfilled
   */
  async request(configOrUrl, config) {
    try {
      return await this._request(configOrUrl, config);
    } catch (err) {
      if (err instanceof Error) {
        let dummy = {};

        Error.captureStackTrace ? Error.captureStackTrace(dummy) : (dummy = new Error());

        // slice off the Error: ... line
        const stack = dummy.stack ? dummy.stack.replace(/^.+\n/, '') : '';
        try {
          if (!err.stack) {
            err.stack = stack;
            // match without the 2 top stack lines
          } else if (stack && !String(err.stack).endsWith(stack.replace(/^.+\n.+\n/, ''))) {
            err.stack += '\n' + stack;
          }
        } catch (e) {
          // ignore the case where "stack" is an un-writable property
        }
      }

      throw err;
    }
  }

  _request(configOrUrl, config) {
    /*eslint no-param-reassign:0*/
    // Allow for axios('example/url'[, config]) a la fetch API
    if (typeof configOrUrl === 'string') {
      config = config || {};
      config.url = configOrUrl;
    } else {
      config = configOrUrl || {};
    }

    config = mergeConfig(this.defaults, config);

    const {transitional, paramsSerializer, headers} = config;

    if (transitional !== undefined) {
      validator.assertOptions(transitional, {
        silentJSONParsing: validators.transitional(validators.boolean),
        forcedJSONParsing: validators.transitional(validators.boolean),
        clarifyTimeoutError: validators.transitional(validators.boolean),
        legacyInterceptorReqResOrdering: validators.transitional(validators.boolean)
      }, false);
    }

    if (paramsSerializer != null) {
      if (utils$1.isFunction(paramsSerializer)) {
        config.paramsSerializer = {
          serialize: paramsSerializer
        };
      } else {
        validator.assertOptions(paramsSerializer, {
          encode: validators.function,
          serialize: validators.function
        }, true);
      }
    }

    // Set config.allowAbsoluteUrls
    if (config.allowAbsoluteUrls !== undefined) ; else if (this.defaults.allowAbsoluteUrls !== undefined) {
      config.allowAbsoluteUrls = this.defaults.allowAbsoluteUrls;
    } else {
      config.allowAbsoluteUrls = true;
    }

    validator.assertOptions(config, {
      baseUrl: validators.spelling('baseURL'),
      withXsrfToken: validators.spelling('withXSRFToken')
    }, true);

    // Set config.method
    config.method = (config.method || this.defaults.method || 'get').toLowerCase();

    // Flatten headers
    let contextHeaders = headers && utils$1.merge(
      headers.common,
      headers[config.method]
    );

    headers && utils$1.forEach(
      ['delete', 'get', 'head', 'post', 'put', 'patch', 'common'],
      (method) => {
        delete headers[method];
      }
    );

    config.headers = AxiosHeaders$1.concat(contextHeaders, headers);

    // filter out skipped interceptors
    const requestInterceptorChain = [];
    let synchronousRequestInterceptors = true;
    this.interceptors.request.forEach(function unshiftRequestInterceptors(interceptor) {
      if (typeof interceptor.runWhen === 'function' && interceptor.runWhen(config) === false) {
        return;
      }

      synchronousRequestInterceptors = synchronousRequestInterceptors && interceptor.synchronous;

      const transitional = config.transitional || transitionalDefaults;
      const legacyInterceptorReqResOrdering = transitional && transitional.legacyInterceptorReqResOrdering;

      if (legacyInterceptorReqResOrdering) {
        requestInterceptorChain.unshift(interceptor.fulfilled, interceptor.rejected);
      } else {
        requestInterceptorChain.push(interceptor.fulfilled, interceptor.rejected);
      }
    });

    const responseInterceptorChain = [];
    this.interceptors.response.forEach(function pushResponseInterceptors(interceptor) {
      responseInterceptorChain.push(interceptor.fulfilled, interceptor.rejected);
    });

    let promise;
    let i = 0;
    let len;

    if (!synchronousRequestInterceptors) {
      const chain = [dispatchRequest.bind(this), undefined];
      chain.unshift(...requestInterceptorChain);
      chain.push(...responseInterceptorChain);
      len = chain.length;

      promise = Promise.resolve(config);

      while (i < len) {
        promise = promise.then(chain[i++], chain[i++]);
      }

      return promise;
    }

    len = requestInterceptorChain.length;

    let newConfig = config;

    while (i < len) {
      const onFulfilled = requestInterceptorChain[i++];
      const onRejected = requestInterceptorChain[i++];
      try {
        newConfig = onFulfilled(newConfig);
      } catch (error) {
        onRejected.call(this, error);
        break;
      }
    }

    try {
      promise = dispatchRequest.call(this, newConfig);
    } catch (error) {
      return Promise.reject(error);
    }

    i = 0;
    len = responseInterceptorChain.length;

    while (i < len) {
      promise = promise.then(responseInterceptorChain[i++], responseInterceptorChain[i++]);
    }

    return promise;
  }

  getUri(config) {
    config = mergeConfig(this.defaults, config);
    const fullPath = buildFullPath(config.baseURL, config.url, config.allowAbsoluteUrls);
    return buildURL(fullPath, config.params, config.paramsSerializer);
  }
}

// Provide aliases for supported request methods
utils$1.forEach(['delete', 'get', 'head', 'options'], function forEachMethodNoData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, config) {
    return this.request(mergeConfig(config || {}, {
      method,
      url,
      data: (config || {}).data
    }));
  };
});

utils$1.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  /*eslint func-names:0*/

  function generateHTTPMethod(isForm) {
    return function httpMethod(url, data, config) {
      return this.request(mergeConfig(config || {}, {
        method,
        headers: isForm ? {
          'Content-Type': 'multipart/form-data'
        } : {},
        url,
        data
      }));
    };
  }

  Axios.prototype[method] = generateHTTPMethod();

  Axios.prototype[method + 'Form'] = generateHTTPMethod(true);
});

var Axios$1 = Axios;

/**
 * A `CancelToken` is an object that can be used to request cancellation of an operation.
 *
 * @param {Function} executor The executor function.
 *
 * @returns {CancelToken}
 */
class CancelToken {
  constructor(executor) {
    if (typeof executor !== 'function') {
      throw new TypeError('executor must be a function.');
    }

    let resolvePromise;

    this.promise = new Promise(function promiseExecutor(resolve) {
      resolvePromise = resolve;
    });

    const token = this;

    // eslint-disable-next-line func-names
    this.promise.then(cancel => {
      if (!token._listeners) return;

      let i = token._listeners.length;

      while (i-- > 0) {
        token._listeners[i](cancel);
      }
      token._listeners = null;
    });

    // eslint-disable-next-line func-names
    this.promise.then = onfulfilled => {
      let _resolve;
      // eslint-disable-next-line func-names
      const promise = new Promise(resolve => {
        token.subscribe(resolve);
        _resolve = resolve;
      }).then(onfulfilled);

      promise.cancel = function reject() {
        token.unsubscribe(_resolve);
      };

      return promise;
    };

    executor(function cancel(message, config, request) {
      if (token.reason) {
        // Cancellation has already been requested
        return;
      }

      token.reason = new CanceledError$1(message, config, request);
      resolvePromise(token.reason);
    });
  }

  /**
   * Throws a `CanceledError` if cancellation has been requested.
   */
  throwIfRequested() {
    if (this.reason) {
      throw this.reason;
    }
  }

  /**
   * Subscribe to the cancel signal
   */

  subscribe(listener) {
    if (this.reason) {
      listener(this.reason);
      return;
    }

    if (this._listeners) {
      this._listeners.push(listener);
    } else {
      this._listeners = [listener];
    }
  }

  /**
   * Unsubscribe from the cancel signal
   */

  unsubscribe(listener) {
    if (!this._listeners) {
      return;
    }
    const index = this._listeners.indexOf(listener);
    if (index !== -1) {
      this._listeners.splice(index, 1);
    }
  }

  toAbortSignal() {
    const controller = new AbortController();

    const abort = (err) => {
      controller.abort(err);
    };

    this.subscribe(abort);

    controller.signal.unsubscribe = () => this.unsubscribe(abort);

    return controller.signal;
  }

  /**
   * Returns an object that contains a new `CancelToken` and a function that, when called,
   * cancels the `CancelToken`.
   */
  static source() {
    let cancel;
    const token = new CancelToken(function executor(c) {
      cancel = c;
    });
    return {
      token,
      cancel
    };
  }
}

var CancelToken$1 = CancelToken;

/**
 * Syntactic sugar for invoking a function and expanding an array for arguments.
 *
 * Common use case would be to use `Function.prototype.apply`.
 *
 *  ```js
 *  function f(x, y, z) {}
 *  const args = [1, 2, 3];
 *  f.apply(null, args);
 *  ```
 *
 * With `spread` this example can be re-written.
 *
 *  ```js
 *  spread(function(x, y, z) {})([1, 2, 3]);
 *  ```
 *
 * @param {Function} callback
 *
 * @returns {Function}
 */
function spread(callback) {
  return function wrap(arr) {
    return callback.apply(null, arr);
  };
}

/**
 * Determines whether the payload is an error thrown by Axios
 *
 * @param {*} payload The value to test
 *
 * @returns {boolean} True if the payload is an error thrown by Axios, otherwise false
 */
function isAxiosError(payload) {
  return utils$1.isObject(payload) && (payload.isAxiosError === true);
}

const HttpStatusCode = {
  Continue: 100,
  SwitchingProtocols: 101,
  Processing: 102,
  EarlyHints: 103,
  Ok: 200,
  Created: 201,
  Accepted: 202,
  NonAuthoritativeInformation: 203,
  NoContent: 204,
  ResetContent: 205,
  PartialContent: 206,
  MultiStatus: 207,
  AlreadyReported: 208,
  ImUsed: 226,
  MultipleChoices: 300,
  MovedPermanently: 301,
  Found: 302,
  SeeOther: 303,
  NotModified: 304,
  UseProxy: 305,
  Unused: 306,
  TemporaryRedirect: 307,
  PermanentRedirect: 308,
  BadRequest: 400,
  Unauthorized: 401,
  PaymentRequired: 402,
  Forbidden: 403,
  NotFound: 404,
  MethodNotAllowed: 405,
  NotAcceptable: 406,
  ProxyAuthenticationRequired: 407,
  RequestTimeout: 408,
  Conflict: 409,
  Gone: 410,
  LengthRequired: 411,
  PreconditionFailed: 412,
  PayloadTooLarge: 413,
  UriTooLong: 414,
  UnsupportedMediaType: 415,
  RangeNotSatisfiable: 416,
  ExpectationFailed: 417,
  ImATeapot: 418,
  MisdirectedRequest: 421,
  UnprocessableEntity: 422,
  Locked: 423,
  FailedDependency: 424,
  TooEarly: 425,
  UpgradeRequired: 426,
  PreconditionRequired: 428,
  TooManyRequests: 429,
  RequestHeaderFieldsTooLarge: 431,
  UnavailableForLegalReasons: 451,
  InternalServerError: 500,
  NotImplemented: 501,
  BadGateway: 502,
  ServiceUnavailable: 503,
  GatewayTimeout: 504,
  HttpVersionNotSupported: 505,
  VariantAlsoNegotiates: 506,
  InsufficientStorage: 507,
  LoopDetected: 508,
  NotExtended: 510,
  NetworkAuthenticationRequired: 511,
  WebServerIsDown: 521,
  ConnectionTimedOut: 522,
  OriginIsUnreachable: 523,
  TimeoutOccurred: 524,
  SslHandshakeFailed: 525,
  InvalidSslCertificate: 526,
};

Object.entries(HttpStatusCode).forEach(([key, value]) => {
  HttpStatusCode[value] = key;
});

var HttpStatusCode$1 = HttpStatusCode;

/**
 * Create an instance of Axios
 *
 * @param {Object} defaultConfig The default config for the instance
 *
 * @returns {Axios} A new instance of Axios
 */
function createInstance(defaultConfig) {
  const context = new Axios$1(defaultConfig);
  const instance = bind(Axios$1.prototype.request, context);

  // Copy axios.prototype to instance
  utils$1.extend(instance, Axios$1.prototype, context, {allOwnKeys: true});

  // Copy context to instance
  utils$1.extend(instance, context, null, {allOwnKeys: true});

  // Factory for creating new instances
  instance.create = function create(instanceConfig) {
    return createInstance(mergeConfig(defaultConfig, instanceConfig));
  };

  return instance;
}

// Create the default instance to be exported
const axios = createInstance(defaults$1);

// Expose Axios class to allow class inheritance
axios.Axios = Axios$1;

// Expose Cancel & CancelToken
axios.CanceledError = CanceledError$1;
axios.CancelToken = CancelToken$1;
axios.isCancel = isCancel;
axios.VERSION = VERSION;
axios.toFormData = toFormData;

// Expose AxiosError class
axios.AxiosError = AxiosError$1;

// alias for CanceledError for backward compatibility
axios.Cancel = axios.CanceledError;

// Expose all/spread
axios.all = function all(promises) {
  return Promise.all(promises);
};

axios.spread = spread;

// Expose isAxiosError
axios.isAxiosError = isAxiosError;

// Expose mergeConfig
axios.mergeConfig = mergeConfig;

axios.AxiosHeaders = AxiosHeaders$1;

axios.formToJSON = thing => formDataToJSON(utils$1.isHTMLForm(thing) ? new FormData(thing) : thing);

axios.getAdapter = adapters.getAdapter;

axios.HttpStatusCode = HttpStatusCode$1;

axios.default = axios;

module.exports = axios;
//# sourceMappingURL=axios.cjs.map


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Check if module exists (development only)
/******/ 		if (__webpack_modules__[moduleId] === undefined) {
/******/ 			var e = new Error("Cannot find module '" + moduleId + "'");
/******/ 			e.code = 'MODULE_NOT_FOUND';
/******/ 			throw e;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/global */
/******/ 	!function() {
/******/ 		__webpack_require__.g = (function() {
/******/ 			if (typeof globalThis === 'object') return globalThis;
/******/ 			try {
/******/ 				return this || new Function('return this')();
/******/ 			} catch (e) {
/******/ 				if (typeof window === 'object') return window;
/******/ 			}
/******/ 		})();
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be in strict mode.
!function() {
"use strict";
/*!********************************************************!*\
  !*** ./assets/src/js/admin/multi-directory-archive.js ***!
  \********************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_delete_directory_modal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/delete-directory-modal */ "./assets/src/js/admin/components/delete-directory-modal.js");
/* harmony import */ var _components_delete_directory_modal__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_components_delete_directory_modal__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_directory_migration_modal__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/directory-migration-modal */ "./assets/src/js/admin/components/directory-migration-modal.js");
/* harmony import */ var _components_directory_migration_modal__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_components_directory_migration_modal__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _components_import_directory_modal__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/import-directory-modal */ "./assets/src/js/admin/components/import-directory-modal.js");
/* harmony import */ var _components_import_directory_modal__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_components_import_directory_modal__WEBPACK_IMPORTED_MODULE_2__);
// Scrips



var $ = jQuery;
var axios = (__webpack_require__(/*! axios */ "./node_modules/axios/dist/browser/axios.cjs")["default"]);
window.addEventListener('load', function () {
  // Migration Link
  $('.directorist_directory_template_library').on('click', function (e) {
    e.preventDefault();
    var self = this;
    // Add 'disabled' class to all siblings with the specific class and also to self
    $(self).siblings('.cptm-create-directory-modal__action__single').addBack().addClass('disabled');
    $('.cptm-create-directory-modal__action').after("<span class='directorist_template_notice'>Installing Templatiq, Please wait..</span>");
    var form_data = new FormData();
    form_data.append('action', 'directorist_directory_type_library');
    form_data.append('directorist_nonce', directorist_admin.directorist_nonce);

    // Response Success Callback
    var responseSuccessCallback = function responseSuccessCallback(response) {
      var _response$data;
      if (response !== null && response !== void 0 && (_response$data = response.data) !== null && _response$data !== void 0 && _response$data.success) {
        var _response$data$messag, _response$data2;
        var msg = (_response$data$messag = response === null || response === void 0 || (_response$data2 = response.data) === null || _response$data2 === void 0 ? void 0 : _response$data2.message) !== null && _response$data$messag !== void 0 ? _response$data$messag : 'Imported successfully!';
        $('.directorist_template_notice').addClass('cptm-section-alert-success').text(msg);
        location.reload();
        return;
      }
      responseFieldCallback(response);
    };

    // Response Error Callback
    var responseFieldCallback = function responseFieldCallback(response) {
      var _response$data$messag2, _response$data3;
      // Remove 'disabled' class from all siblings and self in case of failure
      $(self).siblings('.cptm-create-directory-modal__action__single').addBack().removeClass('disabled');
      var msg = (_response$data$messag2 = response === null || response === void 0 || (_response$data3 = response.data) === null || _response$data3 === void 0 ? void 0 : _response$data3.message) !== null && _response$data$messag2 !== void 0 ? _response$data$messag2 : 'Something went wrong please try again';
      var alert_content = "\n            <div class=\"cptm-section-alert-content\">\n                <div class=\"cptm-section-alert-icon cptm-alert-error\">\n                    <span class=\"fa fa-times\"></span>\n                </div>\n\n                <div class=\"cptm-section-alert-message\">".concat(msg, "</div>\n            </div>\n            ");
      $('.cptm-directory-migration-form').find('.cptm-comfirmation-text').html(alert_content);
      $(self).remove();
    };

    // Send Request
    axios.post(directorist_admin.ajax_url, form_data).then(function (response) {
      responseSuccessCallback(response);
    }).catch(function (response) {
      responseFieldCallback(response);
    });
  });

  // Show the form when the '.directorist-ai-directory-creation' element is clicked
  $('.directorist-ai-directory-creation').on('click', function (e) {
    e.preventDefault();

    // Prepare form data for the request
    var form_data = new FormData();
    form_data.append('action', 'directorist_ai_directory_form');

    // Send the request using Axios
    axios.post(directorist_admin.ajax_url, form_data).then(function (response) {
      var _response$data4;
      if (response !== null && response !== void 0 && (_response$data4 = response.data) !== null && _response$data4 !== void 0 && _response$data4.success) {
        var _response$data5;
        // Replace the content inside '#wpbody' with the response HTML
        $('#wpbody').empty().html(response === null || response === void 0 || (_response$data5 = response.data) === null || _response$data5 === void 0 || (_response$data5 = _response$data5.data) === null || _response$data5 === void 0 ? void 0 : _response$data5.form);

        // Initialize Step Contents
        initialStepContents();
      } else {
        console.log(response.data);
      }
    }).catch(function (response) {
      console.log(response.data);
    });
  });
});
var totalStep = 3;
var currentStep = 1;
var directoryTitle = '';
var directoryLocation = '';
var directoryType = '';
var directoryPrompt = 'I want to create a car directory';
var maxPromptLength = 200;
var directoryKeywords = [];
var directoryFields = [];
var directoryPinnedFields = [];
var creationCompleted = false;

// Update Step Title
function updateStepTitle(title) {
  $('.directorist-create-directory__info__title').html(title);
}

// Update Step Description
function updateStepDescription(desc) {
  $('.directorist-create-directory__info__desc').html(desc);
}

// Update Button Text
function updateButtonText(text) {
  $('.directorist_generate_ai_directory .directorist_generate_ai_directory__text').html(text);
}

// Update Directory Prompt
function updatePrompt() {
  directoryPrompt = "I want to create a ".concat(directoryType, " directory").concat(directoryLocation ? " in ".concat(directoryLocation) : '');
  $('#directorist-ai-prompt').val(directoryPrompt);
  $('#directorist-ai-prompt').siblings('.character-count').find('.current-count').text(directoryPrompt.length);
  if (directoryType) {
    handleCreateButtonEnable();
  } else {
    handleCreateButtonDisable();
  }
}

// Function to initialize Keyword Selected
function initializeKeyword() {
  var tagList = []; // Internal list for selected keywords
  var maxFreeTags = 5; // Max item limit for all users

  var tagListElem = document.getElementById('directorist-box__tagList');
  var newTagElem = document.getElementById('directorist-box__newTag');
  var recommendedTagsElem = document.getElementById('directorist-recommendedTags');
  var recommendedTags = Array.from(recommendedTagsElem.getElementsByTagName('li'));
  var tagLimitMsgElem = document.getElementById('directorist-tagLimitMsg');
  var tagCountElem = document.getElementById('directorist-tagCount');
  var canAddMoreTags = function canAddMoreTags() {
    return tagList.length < maxFreeTags;
  };

  // Update the global keywords list
  var updateDirectoryKeywords = function updateDirectoryKeywords() {
    directoryKeywords = [].concat(tagList); // Sync global keywords
  };

  // Update the tag count and recommended tags state
  var updateTagCount = function updateTagCount() {
    tagCountElem.textContent = "".concat(tagList.length, "/").concat(maxFreeTags);
    tagLimitMsgElem.style.display = 'flex';
    recommendedTagsElem.classList.toggle('recommend-disable', !canAddMoreTags());
  };

  // Update the recommended tags state based on the selected tags
  var updateRecommendedTagsState = function updateRecommendedTagsState() {
    recommendedTags.forEach(function (tagElem) {
      var tagText = tagElem.textContent.trim();
      tagElem.classList.toggle('disabled', tagList.includes(tagText));
    });
  };

  // Render the tag list
  var renderTagList = function renderTagList() {
    tagListElem.innerHTML = tagList.map(function (tag) {
      return "<li>".concat(tag, " <span class=\"directorist-rmTag\" style=\"cursor:pointer;\">&times;</span></li>");
    }).join('');
    tagListElem.appendChild(newTagElem.parentNode || document.createElement('li').appendChild(newTagElem));
    updateRecommendedTagsState();
    updateTagCount();
    updateDirectoryKeywords();
  };

  // Add a new tag to the list
  var addTag = function addTag(tag) {
    if (tag && !tagList.includes(tag) && canAddMoreTags()) {
      tagList.push(tag);
      renderTagList();
    }
  };

  // Remove a tag from the list
  var removeTag = function removeTag(index) {
    if (index !== -1) {
      tagList.splice(index, 1);
      renderTagList();
    }
  };

  // Event listener for adding tags via input
  newTagElem.addEventListener('keyup', function (e) {
    if (e.key === 'Enter') {
      var newTag = newTagElem.value.trim();
      addTag(newTag);
      newTagElem.value = '';
    }
  });

  // Event delegation for removing tags
  tagListElem.addEventListener('click', function (e) {
    if (e.target.classList.contains('directorist-rmTag')) {
      var index = Array.from(tagListElem.children).indexOf(e.target.parentElement);
      removeTag(index);
    }
  });

  // Event listener for adding recommended tags
  recommendedTagsElem.addEventListener('click', function (e) {
    if (e.target.tagName === 'LI' && !e.target.classList.contains('disabled')) {
      addTag(e.target.textContent.trim());
    }
  });

  // Initialize the tag management interface
  renderTagList();
}

// Function to initialize Progress bar
function initializeProgressBar(finalProgress) {
  if (finalProgress) {
    $('#directorist-create-directory__generating .directory-img #directory-img__generating').hide();
    $('#directorist-create-directory__generating .directory-img #directory-img__building').show();
    $('#directory-generate-btn__content__text').html('Generating directory...');
  } else {
    $('#directorist-create-directory__generating .directory-img #directory-img__generating').show();
    $('#directorist-create-directory__generating .directory-img #directory-img__building').hide();
  }
  var generateBtnWrapper = document.querySelector('.directory-generate-btn__wrapper');
  var btnPercentage = document.querySelector('.directory-generate-btn__percentage');
  var progressBar = document.querySelector('.directory-generate-btn--bg');
  if (generateBtnWrapper) {
    var finalWidth = generateBtnWrapper.getAttribute('data-width');
    var currentWidth = 0;
    var intervalDuration = 20; // Interval time in milliseconds
    var increment = finalWidth / (2000 / intervalDuration);

    // Update the progress bar width
    var updateProgress = function updateProgress() {
      if (creationCompleted) {
        progressBar.style.width = "".concat(finalWidth, "%");
        btnPercentage.textContent = '';
        $('#directory-generate-btn__content__text').html('Generated Successfully');
        if (typeof updateProgressList === 'function') {
          updateProgressList(finalWidth);
        }
        clearInterval(progressInterval);
        return;
      } else if (currentWidth <= finalWidth) {
        btnPercentage.textContent = "".concat(currentWidth, "%");
        progressBar.style.width = "".concat(currentWidth, "%");
        if (typeof updateProgressList === 'function') {
          updateProgressList(currentWidth);
        }
        currentWidth += increment;
      } else {
        if (!finalProgress) {
          setTimeout(function () {
            progressBar.style.width = '0';
          }, 3000);
        }
        clearInterval(progressInterval);
      }
    };
    var progressInterval = setInterval(updateProgress, intervalDuration);
  }
  var steps = document.querySelectorAll('.directory-generate-progress-list li');

  // Update the progress list based on the current progress
  var updateProgressList = function updateProgressList(progress) {
    if (steps.length > 0) {
      steps.forEach(function (step, index) {
        var stepNumber = index + 1;
        var stepThreshold = stepNumber * (100 / steps.length);
        if (progress >= stepThreshold) {
          step.setAttribute('data-type', 'completed');
          step.querySelector('.completed-icon').style.display = 'block';
          step.querySelector('.progress-icon').style.display = 'none';
          step.querySelector('.default-icon').style.display = 'none';
        } else if (progress < stepThreshold && progress >= stepThreshold - 100 / steps.length) {
          step.setAttribute('data-type', 'progress');
          step.querySelector('.completed-icon').style.display = 'none';
          step.querySelector('.progress-icon').style.display = 'block';
          step.querySelector('.default-icon').style.display = 'none';
        } else {
          step.setAttribute('data-type', 'default');
          step.querySelector('.completed-icon').style.display = 'none';
          step.querySelector('.progress-icon').style.display = 'none';
          step.querySelector('.default-icon').style.display = 'block';
        }
      });
    }
  };
}

//Function to initialize Dropdown
function initializeDropdownField() {
  var dropdowns = document.querySelectorAll('.directorist-ai-generate-dropdown');
  var accordion = true;
  $('#directorist-create-directory__ai-fields .fields-count').html(dropdowns.length);
  var pinnedIconSVG = "\n        <svg width=\"20\" height=\"20\" viewBox=\"0 0 20 20\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n            <path d=\"M10.9189 3.03837C11.1788 2.43195 11.3088 2.12874 11.5188 1.99101C11.7024 1.87057 11.9262 1.82748 12.1414 1.87111C12.3875 1.921 12.6208 2.15426 13.0873 2.62078L17.3732 6.90673C17.8398 7.37325 18.073 7.60651 18.1229 7.85263C18.1665 8.06786 18.1234 8.29161 18.003 8.47524C17.8653 8.68523 17.5621 8.81517 16.9556 9.07507L14.877 9.96591C14.7888 10.0037 14.7447 10.0226 14.7034 10.0462C14.6667 10.0672 14.6316 10.0909 14.5985 10.1173C14.5612 10.1469 14.5273 10.1808 14.4594 10.2486L13.1587 11.5494C13.0526 11.6555 12.9995 11.7085 12.9574 11.769C12.92 11.8226 12.889 11.8805 12.8651 11.9414C12.8382 12.01 12.8235 12.0835 12.7941 12.2307L12.1833 15.2844C12.0246 16.078 11.9452 16.4748 11.736 16.6604C11.5538 16.8221 11.3099 16.896 11.0685 16.8625C10.7915 16.8241 10.5053 16.538 9.93307 15.9657L4.02829 10.0609C3.45602 9.48868 3.16989 9.20255 3.13148 8.9255C3.09802 8.68415 3.17187 8.44024 3.33359 8.25798C3.51923 8.04877 3.91602 7.96941 4.70961 7.8107L7.76333 7.19995C7.91047 7.17052 7.98403 7.15581 8.05264 7.1289C8.11353 7.10502 8.1714 7.07405 8.22505 7.03663C8.28549 6.99447 8.33854 6.94142 8.44465 6.83532L9.74539 5.53458C9.81322 5.46674 9.84714 5.43282 9.87676 5.39554C9.90307 5.36242 9.92681 5.32734 9.9478 5.29061C9.97142 5.24927 9.99031 5.20518 10.0281 5.117L10.9189 3.03837Z\" fill=\"#141921\"/>\n            <path d=\"M6.98065 13.0133L2.2666 17.7274M9.74539 5.53458L8.44465 6.83532C8.33854 6.94142 8.28549 6.99447 8.22505 7.03663C8.1714 7.07405 8.11353 7.10502 8.05264 7.1289C7.98403 7.15581 7.91047 7.17052 7.76333 7.19995L4.70961 7.8107C3.91602 7.96941 3.51923 8.04877 3.33359 8.25798C3.17187 8.44024 3.09802 8.68415 3.13148 8.9255C3.16989 9.20255 3.45602 9.48868 4.02829 10.0609L9.93307 15.9657C10.5053 16.538 10.7915 16.8241 11.0685 16.8625C11.3099 16.896 11.5538 16.8221 11.736 16.6604C11.9452 16.4748 12.0246 16.078 12.1833 15.2844L12.7941 12.2307C12.8235 12.0835 12.8382 12.01 12.8651 11.9414C12.889 11.8805 12.92 11.8226 12.9574 11.769C12.9995 11.7085 13.0526 11.6555 13.1587 11.5494L14.4594 10.2486C14.5273 10.1808 14.5612 10.1469 14.5985 10.1173C14.6316 10.0909 14.6667 10.0672 14.7034 10.0462C14.7447 10.0226 14.7888 10.0037 14.877 9.96591L16.9556 9.07507C17.5621 8.81517 17.8653 8.68523 18.003 8.47524C18.1234 8.29161 18.1665 8.06786 18.1229 7.85263C18.073 7.60651 17.8398 7.37325 17.3732 6.90673L13.0873 2.62078C12.6208 2.15426 12.3875 1.921 12.1414 1.87111C11.9262 1.82748 11.7024 1.87057 11.5188 1.99101C11.3088 2.12874 11.1788 2.43195 10.9189 3.03837L10.0281 5.117C9.99031 5.20518 9.97142 5.24927 9.9478 5.29061C9.92681 5.32734 9.90307 5.36242 9.87676 5.39554C9.84714 5.43282 9.81322 5.46674 9.74539 5.53458Z\" stroke=\"#141921\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/>\n        </svg>\n    ";
  var unpinnedIconSVG = "\n        <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"20\" height=\"20\" viewBox=\"0 0 20 20\" fill=\"none\">\n            <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M11.0616 1.29452C11.4288 1.05364 11.8763 0.967454 12.3068 1.05472C12.6318 1.12059 12.8801 1.29569 13.0651 1.44993C13.2419 1.59735 13.4399 1.79537 13.653 2.00849L17.9857 6.34116C18.1988 6.55424 18.3968 6.75223 18.5442 6.92908C18.6985 7.11412 18.8736 7.36242 18.9395 7.6874C19.0267 8.11785 18.9405 8.56535 18.6997 8.93261C18.5178 9.20988 18.263 9.3754 18.0511 9.48992C17.8485 9.59937 17.5911 9.70966 17.3141 9.82836L15.2051 10.7322C15.1578 10.7525 15.1347 10.7624 15.118 10.77C15.1176 10.7702 15.1173 10.7704 15.1169 10.7705C15.1166 10.7708 15.1163 10.7711 15.116 10.7714C15.1028 10.7841 15.0849 10.8018 15.0485 10.8382L13.7478 12.1389C13.6909 12.1959 13.6629 12.224 13.6432 12.2451C13.6427 12.2456 13.6423 12.2461 13.6418 12.2466C13.6417 12.2472 13.6415 12.2479 13.6414 12.2486C13.6347 12.2767 13.6268 12.3155 13.611 12.3944L12.9932 15.4835C12.92 15.8499 12.8541 16.1794 12.7773 16.438C12.7004 16.6969 12.5739 17.0312 12.289 17.2841C11.9244 17.6075 11.4366 17.7552 10.9539 17.6883C10.5765 17.636 10.2858 17.4279 10.0783 17.2552C9.87091 17.0826 9.63334 16.845 9.36915 16.5808L6.98049 14.1922L2.85569 18.317C2.53026 18.6424 2.00262 18.6424 1.67718 18.317C1.35175 17.9915 1.35175 17.4639 1.67718 17.1384L5.80198 13.0136L3.41338 10.625C3.14915 10.3608 2.91154 10.1233 2.73896 9.91588C2.56626 9.70833 2.3582 9.41765 2.30588 9.04027C2.23896 8.55756 2.38666 8.06974 2.7101 7.70522C2.96297 7.42025 3.29732 7.29379 3.55615 7.2169C3.81479 7.14007 4.14427 7.0742 4.51068 7.00094L7.59973 6.38313C7.67866 6.36735 7.71751 6.35946 7.7456 6.35282C7.74629 6.35266 7.74696 6.3525 7.7476 6.35234C7.74808 6.3519 7.74858 6.35143 7.7491 6.35095C7.7702 6.33126 7.79832 6.3033 7.85523 6.24639L9.15597 4.94565C9.19239 4.90923 9.21013 4.89143 9.22278 4.87819C9.22308 4.87787 9.22336 4.87757 9.22364 4.87729C9.22381 4.87692 9.22398 4.87654 9.22416 4.87615C9.23175 4.85949 9.24169 4.83641 9.26199 4.78906L10.1658 2.68009C10.2845 2.40306 10.3948 2.14567 10.5043 1.94311C10.6188 1.73117 10.7843 1.47638 11.0616 1.29452ZM10.5222 15.3768C10.82 15.6746 11.003 15.8565 11.1444 15.9741C11.1535 15.9817 11.162 15.9886 11.1699 15.995C11.173 15.9853 11.1762 15.9748 11.1796 15.9634C11.232 15.7871 11.2834 15.5343 11.366 15.1213L11.9767 12.0676C11.9787 12.0577 11.9808 12.0475 11.9828 12.037C12.0055 11.9222 12.0342 11.7776 12.0892 11.6374C12.1369 11.5156 12.1989 11.3999 12.2737 11.2926C12.3599 11.169 12.4643 11.065 12.5472 10.9825C12.5547 10.9749 12.5621 10.9676 12.5693 10.9604L13.87 9.6597C13.8746 9.65514 13.8793 9.65044 13.884 9.64564C13.9371 9.59249 14.0038 9.52556 14.08 9.46504C14.1462 9.41243 14.2164 9.36493 14.2899 9.32297C14.3743 9.2747 14.4613 9.23757 14.5303 9.20809C14.5366 9.20543 14.5427 9.20282 14.5486 9.20028L16.6272 8.30944C16.9451 8.17321 17.1311 8.09262 17.2588 8.02362C17.2656 8.01993 17.272 8.01642 17.2779 8.0131C17.2736 8.00783 17.269 8.00221 17.264 7.99624C17.1711 7.88475 17.0283 7.74085 16.7838 7.49631L12.4979 3.21037C12.2533 2.96583 12.1094 2.82309 11.9979 2.73014C11.992 2.72517 11.9864 2.72057 11.9811 2.71631C11.9778 2.72222 11.9743 2.72858 11.9706 2.73541C11.9016 2.86312 11.821 3.04909 11.6847 3.36696L10.7939 5.44559C10.7914 5.45153 10.7887 5.45762 10.7861 5.46387C10.7566 5.53289 10.7195 5.61984 10.6712 5.70432C10.6292 5.77777 10.5817 5.84793 10.5291 5.91417C10.4686 5.99036 10.4017 6.05713 10.3485 6.11013C10.3437 6.11492 10.339 6.1196 10.3345 6.12417L9.03374 7.4249C9.02658 7.43206 9.01924 7.43943 9.01172 7.44698C8.92915 7.52989 8.82513 7.63432 8.70159 7.72048C8.59429 7.79531 8.47855 7.85725 8.35677 7.90502C8.21655 7.96002 8.07196 7.98864 7.95717 8.01136C7.94673 8.01343 7.93652 8.01544 7.92659 8.01743L4.87287 8.62817C4.45985 8.71078 4.20704 8.7622 4.03076 8.81456C4.0194 8.81794 4.00891 8.82117 3.99923 8.82425C4.00557 8.83219 4.01251 8.84071 4.02009 8.84981C4.13771 8.99116 4.31954 9.17418 4.61738 9.47202L10.5222 15.3768Z\" fill=\"currentColor\"></path>\n        </svg>\n    ";

  // Initialize each dropdown
  dropdowns.forEach(function (dropdown) {
    var header = dropdown.querySelector('.directorist-ai-generate-dropdown__header.has-options');
    var content = dropdown.querySelector('.directorist-ai-generate-dropdown__content');
    var icon = dropdown.querySelector('.directorist-ai-generate-dropdown__header-icon');
    var pinIcon = dropdown.querySelector('.directorist-ai-generate-dropdown__pin-icon');
    var dropdownItem = dropdown.closest('.directorist-ai-generate-box__item');

    // Pin Field
    pinIcon.addEventListener('click', function (event) {
      event.stopPropagation();
      if (dropdownItem.classList.contains('pinned')) {
        dropdownItem.classList.remove('pinned');
        dropdownItem.classList.add('unpinned');

        // Change to pinned SVG
        pinIcon.innerHTML = unpinnedIconSVG;
      } else {
        dropdownItem.classList.remove('unpinned');
        dropdownItem.classList.add('pinned');

        // Change to pinned SVG
        pinIcon.innerHTML = pinnedIconSVG;
      }

      // Find all pinned items
      directoryPinnedFields = findAllPinnedItems();
    });

    // Toggle the dropdown content
    header && header.addEventListener('click', function (event) {
      if (event.target === pinIcon || pinIcon.contains(event.target)) {
        return;
      }
      var isExpanded = content && content.classList.toggle('directorist-ai-generate-dropdown__content--expanded');
      dropdown.setAttribute('aria-expanded', isExpanded);
      content.setAttribute('aria-expanded', isExpanded);
      icon.classList.toggle('rotate', isExpanded);
      if (accordion) {
        dropdowns.forEach(function (otherDropdown) {
          if (otherDropdown !== dropdown) {
            var otherContent = otherDropdown.querySelector('.directorist-ai-generate-dropdown__content');
            var otherIcon = otherDropdown.querySelector('.directorist-ai-generate-dropdown__header-icon');
            otherDropdown.setAttribute('aria-expanded', false);
            if (otherContent) {
              otherContent.classList.remove('directorist-ai-generate-dropdown__content--expanded');
              otherContent.setAttribute('aria-expanded', false);
            }
            if (otherIcon) {
              otherIcon.classList.remove('rotate');
            }
          }
        });
      }
    });
  });

  // Function to find all pinned items
  function findAllPinnedItems() {
    var pinnedElements = document.querySelectorAll('.directorist-ai-generate-box__item.pinned');
    if (pinnedElements.length > 0) {
      var titles = Array.from(pinnedElements).flatMap(function (pinnedElement) {
        return Array.from(pinnedElement.querySelectorAll('.directorist-ai-generate-dropdown__title-main h6')).map(function (item) {
          return item.innerText;
        });
      });
      return titles; // Return the array of titles
    }
    return [];
  }
}

// Function to handle back button
function handleBackButton() {
  currentStep = 1;
  // Back to initial step
  initialStepContents();
}

// handle back btn
$('body').on('click', '.directorist-create-directory__back__btn', function (e) {
  e.preventDefault();
  handleBackButton();
});

// Enable Submit Button
function handleCreateButtonEnable() {
  $('.directorist_generate_ai_directory').removeClass('disabled');
}

// Disable Submit Button
function handleCreateButtonDisable() {
  $('.directorist_generate_ai_directory').addClass('disabled');
}

// Initial Step Contents
function initialStepContents() {
  // Hide all steps except the first one initially
  $('#directorist-create-directory__creating').hide();
  $('#directorist-create-directory__ai-fields').hide();
  $('#directorist-create-directory__generating').hide();
  $('.directorist-create-directory__content__items').hide();
  $('.directorist-create-directory__back__btn').addClass('disabled');
  $('.directorist-create-directory__content__items[data-step="1"]').show();
  $('.directorist-create-directory__step .step-count .total-step').html(totalStep);
  $('.directorist-create-directory__step .step-count .current-step').html(1);
  $('#directorist-ai-prompt').siblings('.character-count').find('.max-count').text(maxPromptLength);
  var $directoryName = $('.directorist-create-directory__content__input[name="directory-name"]');
  var $directoryLocation = $('.directorist-create-directory__content__input[name="directory-location"]');
  if (!$directoryName.val()) {
    handleCreateButtonDisable();
    directoryTitle = '';
  }
  if (!$directoryLocation.val()) {
    directoryLocation = '';
  }

  // Directory Title Input Listener
  $directoryName.on('input', function (e) {
    directoryTitle = $(this).val();
    if (directoryTitle) {
      handleCreateButtonEnable();
      updatePrompt();
    } else {
      handleCreateButtonDisable();
    }
  });

  // Directory Location Input Listener
  $directoryLocation.on('input', function (e) {
    directoryLocation = $(this).val();
    updatePrompt();
  });

  // Directory Prompt Input Listener
  $('body').on('input keyup', '#directorist-ai-prompt', function (e) {
    $('#directorist-ai-prompt').siblings('.character-count').find('.current-count').text(directoryPrompt.length);
    if (e.target.value.length > maxPromptLength) {
      // Limit to maxPromptLength characters by preventing additional input
      e.target.value = e.target.value.substring(0, maxPromptLength);

      // Add a class to indicate the maximum character limit reached
      $(e.target).addClass('max-char-reached');
    } else {
      // Remove the class if below the maximum character limit
      $(e.target).removeClass('max-char-reached');
    }
    if (!e.target.value) {
      directoryPrompt = '';
      handleCreateButtonDisable();
    } else {
      directoryPrompt = e.target.value;
      handleCreateButtonEnable();
    }
  });

  // Other Directory Type Input Listener
  function checkOtherDirectoryType(type) {
    updatePrompt();
    if (type === '') {
      handleCreateButtonDisable();
      $('#new-directory-type').addClass('empty');
    } else {
      handleCreateButtonEnable();
      $('#new-directory-type').removeClass('empty');
    }
  }

  // Check if any item is initially checked
  $('[name="directory_type[]"]').each(function () {
    if ($(this).is(':checked')) {
      directoryType = $(this).val();
    }
  });

  // Directory Type Input Listener
  $('body').on('change', '[name="directory_type[]"]', function (e) {
    directoryType = e.target.value;
    // Show or hide the input based on the selected value
    if (directoryType === 'others') {
      directoryType = $('#new-directory-type').val();
      $('#directorist-create-directory__checkbox__others').show();
      checkOtherDirectoryType(directoryType);
      $('#new-directory-type').focus();
      $('body').on('input', '[name="new-directory-type"]', function (e) {
        directoryType = e.target.value;
        checkOtherDirectoryType(directoryType);
      });
    } else {
      $('#directorist-create-directory__checkbox__others').hide();
      updatePrompt();
    }
  });
}

// Handle Prompt Step
function handlePromptStep(response) {
  $('.directorist-create-directory__content__items[data-step="2"]').hide();
  $('.directorist-create-directory__content__items[data-step="3"]').show();
  $('.directorist-create-directory__back__btn').hide();
  $('#directorist-recommendedTags').empty().html(response);
  initializeKeyword();
  updateStepTitle('Select relevant keywords to <br /> optimize AI-generated content');
  updateStepDescription('Keywords helps AI to generate relevant categories and fields');
  updateButtonText('Generate Directory');
  currentStep = 3;
}

// Handle Keyword Step
function handleKeywordStep() {
  $('#directorist-create-directory__generating').show();
  $('.directorist-create-directory__top').hide();
  $('.directorist-create-directory__content__items').hide();
  $('.directorist-create-directory__header').hide();
  $('.directorist-create-directory__content__footer').hide();
  $('.directorist-create-directory__content').toggleClass('full-width');
  updateButtonText('Build Directory');
  initializeProgressBar();
}

// Handle Generated Fields
function handleGenerateFields(response) {
  var _response$data6;
  $('#directorist-create-directory__ai-fields').show();
  $('.directorist-create-directory__header').show();
  $('.directorist_regenerate_fields').show();
  $('#directorist-create-directory__generating').hide();
  $('.directorist-create-directory__content__footer').show();
  $('.directorist-create-directory__content').removeClass('full-width');
  $('#directorist-ai-generated-fields-array').val(JSON.stringify(response === null || response === void 0 || (_response$data6 = response.data) === null || _response$data6 === void 0 ? void 0 : _response$data6.fields));
  $('#directorist_ai_generated_fields').empty().html(response);
  initializeDropdownField();
  currentStep = 4;
}

// Handle Create Directory
function handleCreateDirectory(redirect_url) {
  $('#directorist-create-directory__preview-btn').removeClass('disabled');
  $('#directorist-create-directory__preview-btn').attr('href', redirect_url);
  $('#directorist-create-directory__generating .directory-title').html('Your directory is ready to use');
  creationCompleted = true;
}

// Response Success Callback
function handleAIFormResponse(response) {
  var _response$data7;
  if (response !== null && response !== void 0 && (_response$data7 = response.data) !== null && _response$data7 !== void 0 && _response$data7.success) {
    var nextStep = currentStep + 1;
    $('.directorist-create-directory__content__items[data-step="' + currentStep + '"]').hide();
    $('.directorist-create-directory__step .step-count .current-step').html(nextStep);
    $(".directorist-create-directory__step .atbdp-setup-steps li:nth-child(".concat(nextStep, ")")).addClass('active');
    if ($('.directorist-create-directory__content__items[data-step="' + nextStep + '"]').length) {
      $('.directorist-create-directory__content__items[data-step="' + nextStep + '"]').show();
    }
    if (currentStep == 2) {
      var _response$data8;
      handlePromptStep(response === null || response === void 0 || (_response$data8 = response.data) === null || _response$data8 === void 0 || (_response$data8 = _response$data8.data) === null || _response$data8 === void 0 ? void 0 : _response$data8.html);
    } else if (currentStep == 3) {
      var _response$data0;
      setTimeout(function () {
        var _response$data9;
        handleGenerateFields(response === null || response === void 0 || (_response$data9 = response.data) === null || _response$data9 === void 0 || (_response$data9 = _response$data9.data) === null || _response$data9 === void 0 ? void 0 : _response$data9.html);
      }, 1000);
      directoryFields = JSON.stringify(response === null || response === void 0 || (_response$data0 = response.data) === null || _response$data0 === void 0 || (_response$data0 = _response$data0.data) === null || _response$data0 === void 0 ? void 0 : _response$data0.fields);
    } else if (currentStep == 4) {
      var _response$data1;
      handleCreateDirectory(response === null || response === void 0 || (_response$data1 = response.data) === null || _response$data1 === void 0 || (_response$data1 = _response$data1.data) === null || _response$data1 === void 0 ? void 0 : _response$data1.url);
    }
  } else {
    console.error(response === null || response === void 0 ? void 0 : response.data);
  }
}

// Generate AI Directory Form Submission Handler
$('body').on('click', '.directorist_generate_ai_directory', function (e) {
  e.preventDefault();
  if (currentStep == 1) {
    $('.directorist-create-directory__back__btn').removeClass('disabled');
    $('.directorist-create-directory__content__items[data-step="1"]').hide();
    $('.directorist-create-directory__content__items[data-step="2"]').show();
    $('.directorist-create-directory__step .step-count .current-step').html(2);
    $(".directorist-create-directory__step .atbdp-setup-steps li:nth-child(2)").addClass('active');
    updateStepTitle('Describe your business in plain language');
    currentStep = 2;
    return;
  } else if (currentStep == 3) {
    handleKeywordStep();
  } else if (currentStep == 4) {
    $('#directorist-create-directory__generating').show();
    $('#directorist-create-directory__creating').show();
    $('#directorist-create-directory__ai-fields').hide();
    $('.directorist_regenerate_fields').hide();
    $('.directorist-create-directory__top').hide();
    $('.directorist-create-directory__content__items').hide();
    $('.directorist-create-directory__header').hide();
    $('.directorist-create-directory__content__footer').hide();
    $('.directorist-create-directory__content').addClass('full-width');
    $('#directorist-create-directory__preview-btn').addClass('disabled');
    $('#directorist-create-directory__generating .directory-title').html('Directory AI is Building your directory... ');
    $('#directorist-create-directory__generating .directory-description').html("We're using your infomation to finalize your directory fields.");
    initializeProgressBar('finalProgress');
  }
  handleCreateButtonDisable();
  var form_data = new FormData();
  form_data.append('action', 'directorist_ai_directory_creation');
  form_data.append('name', directoryTitle);
  form_data.append('prompt', directoryPrompt);
  form_data.append('keywords', directoryKeywords);
  form_data.append('fields', directoryFields);
  form_data.append('step', currentStep - 1);

  // Handle Axios Request
  axios.post(directorist_admin.ajax_url, form_data).then(function (response) {
    handleCreateButtonEnable();
    handleAIFormResponse(response);
  }).catch(function (error) {
    var _error$response$data, _error$response$data2;
    if (((_error$response$data = error.response.data) === null || _error$response$data === void 0 ? void 0 : _error$response$data.success) === false && ((_error$response$data2 = error.response.data) === null || _error$response$data2 === void 0 || (_error$response$data2 = _error$response$data2.data) === null || _error$response$data2 === void 0 ? void 0 : _error$response$data2.code) === 'limit_exceeded') {
      alert("🙌 You've exceeded the request/site beta limit.");
    }
    handleCreateButtonEnable();
    console.error(error.response.data);
  });
});

// Regenerate Fields
$('body').on('click', '.directorist_regenerate_fields', function (e) {
  var _this = this;
  e.preventDefault();
  $(this).addClass('loading');
  var form_data = new FormData();
  form_data.append('action', 'directorist_ai_directory_creation');
  form_data.append('name', directoryTitle);
  form_data.append('prompt', directoryPrompt);
  form_data.append('keywords', directoryKeywords);
  form_data.append('pinned', directoryPinnedFields);
  form_data.append('step', 2);

  // Handle Axios Request
  axios.post(directorist_admin.ajax_url, form_data).then(function (response) {
    var _response$data10;
    $(_this).removeClass('loading');
    handleGenerateFields(response === null || response === void 0 || (_response$data10 = response.data) === null || _response$data10 === void 0 || (_response$data10 = _response$data10.data) === null || _response$data10 === void 0 ? void 0 : _response$data10.html);
    $('.directorist_regenerate_fields').hide();
    directoryFields = JSON.stringify(response.data.data.fields);
  }).catch(function (error) {
    var _error$response$data3, _error$response$data4;
    if (((_error$response$data3 = error.response.data) === null || _error$response$data3 === void 0 ? void 0 : _error$response$data3.success) === false && ((_error$response$data4 = error.response.data) === null || _error$response$data4 === void 0 || (_error$response$data4 = _error$response$data4.data) === null || _error$response$data4 === void 0 ? void 0 : _error$response$data4.code) === 'limit_exceeded') {
      alert("🙌 You've exceeded the request/site beta limit.");
    }
    $(_this).removeClass('loading');
    console.error(error.response.data);
  });
});
}();
/******/ })()
;
//# sourceMappingURL=admin-builder-archive.js.map