/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/functions.js":
/*!**************************!*\
  !*** ./src/functions.js ***!
  \**************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   decodeHTML: function() { return /* binding */ decodeHTML; },
/* harmony export */   getAttsForTransform: function() { return /* binding */ getAttsForTransform; },
/* harmony export */   getPlaceholder: function() { return /* binding */ getPlaceholder; },
/* harmony export */   getPreview: function() { return /* binding */ getPreview; },
/* harmony export */   getWithSharedAttributes: function() { return /* binding */ getWithSharedAttributes; },
/* harmony export */   isMultiDirectoryEnabled: function() { return /* binding */ isMultiDirectoryEnabled; },
/* harmony export */   remapPosts: function() { return /* binding */ remapPosts; },
/* harmony export */   remapTaxTerms: function() { return /* binding */ remapTaxTerms; },
/* harmony export */   sortItemsBySelected: function() { return /* binding */ sortItemsBySelected; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _logo__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./logo */ "./src/logo.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__);





function getAttsForTransform() {
  var attributes = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  var _atts = {};
  var _loop = function _loop() {
    var _Object$entries$_i = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_Object$entries[_i], 2),
      key = _Object$entries$_i[0],
      value = _Object$entries$_i[1];
    _atts[key] = {
      type: value.type,
      shortcode: function shortcode(_ref) {
        var named = _ref.named;
        if (typeof named[key] === 'undefined') {
          return value.default;
        }
        if (value.type === 'string') {
          return String(named[key]);
        }
        if (value.type === 'number') {
          return Number(named[key]);
        }
        if (value.type === 'boolen') {
          return Boolean(named[key]);
        }
      }
    };
  };
  for (var _i = 0, _Object$entries = Object.entries(attributes); _i < _Object$entries.length; _i++) {
    _loop();
  }
  return _atts;
}
function sortItemsBySelected() {
  var items = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
  var selected = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
  var key = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'id';
  var isSelectedItem = function isSelectedItem(item) {
    return selected.includes(item[key]);
  };
  var itemsSlected = function itemsSlected(itemA, itemB) {
    var itemASelected = isSelectedItem(itemA);
    var itemBSelected = isSelectedItem(itemB);
    if (itemASelected === itemBSelected) {
      return 0;
    }
    if (itemASelected && !itemBSelected) {
      return -1;
    }
    if (!itemASelected && itemBSelected) {
      return 1;
    }
    return 0;
  };
  items.sort(itemsSlected);
  return items;
}
function isMultiDirectoryEnabled() {
  return !!directoristBlockConfig.multiDirectoryEnabled;
}
function getWithSharedAttributes() {
  var attributes = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  attributes.isPreview = {
    type: 'boolen',
    default: false
  };
  attributes.query_type = {
    type: 'string',
    default: 'regular'
  };
  return attributes;
}
function getPreview(name) {
  var isPreviewPopup = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.Fragment, {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("img", {
      style: {
        display: 'block',
        width: '100%',
        height: 'auto'
      },
      className: "directorist-block-preview",
      src: "".concat(directoristBlockConfig.previewUrl, "preview/").concat(name, ".svg"),
      alt: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Preview', 'directorist')
    }), !isPreviewPopup && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
      style: {
        textAlign: 'center',
        fontSize: '12px',
        marginTop: '5px'
      },
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("em", {
        children: "It's a placeholder. Please check the preview on frontend."
      })
    })]
  });
}
function getPlaceholder(name) {
  var showLogo = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
    className: "directorist-container",
    children: showLogo ? (0,_logo__WEBPACK_IMPORTED_MODULE_3__["default"])() : /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("img", {
      style: {
        display: 'block',
        width: '100%',
        height: 'auto'
      },
      className: "directorist-block-preview",
      src: "".concat(directoristBlockConfig.previewUrl, "preview/").concat(name, ".svg"),
      alt: "Placeholder for ".concat(name)
    })
  });
}
function remapTaxTerms(terms) {
  var ignorables = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
  return terms.map(function (term) {
    return {
      value: term.slug,
      label: (0,lodash__WEBPACK_IMPORTED_MODULE_2__.truncate)(decodeHTML(term.name), {
        length: 30
      })
    };
  });
}
function remapPosts(posts) {
  var ignorables = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
  return posts.map(function (post) {
    return {
      value: post.id,
      label: (0,lodash__WEBPACK_IMPORTED_MODULE_2__.truncate)(decodeHTML(post.title.rendered), {
        length: 30
      })
    };
  });
}
function decodeHTML(text) {
  var textarea = document.createElement('textarea');
  textarea.innerHTML = text;
  return textarea.textContent;
}

/***/ }),

/***/ "./src/logo.js":
/*!*********************!*\
  !*** ./src/logo.js ***!
  \*********************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ getLogo; }
/* harmony export */ });
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__);

function getLogo() {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
    xmlns: "http://www.w3.org/2000/svg",
    viewBox: "0 0 222 221.7",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("linearGradient", {
      id: "SVGID_1_111111",
      gradientUnits: "userSpaceOnUse",
      x1: "81.4946",
      y1: "2852.0237",
      x2: "188.5188",
      y2: "2660.0842",
      gradientTransform: "translate(0 -2658.8872)",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("stop", {
        offset: "0",
        "stop-color": "#2ae498"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("stop", {
        offset: ".01117462",
        "stop-color": "#2ae299"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("stop", {
        offset: ".4845",
        "stop-color": "#359dca"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("stop", {
        offset: ".8263",
        "stop-color": "#3b72e9"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("stop", {
        offset: "1",
        "stop-color": "#3e62f5"
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M171.4 5c-6.1 0-11.1 5-11.1 11.1v52.1C147.4 56 130.1 48.5 111 48.5c-39.5 0-71.5 32-71.5 71.5s32 71.5 71.5 71.5c19.1 0 36.4-7.5 49.2-19.7v4.4c0 6.1 5 11.1 11.1 11.1s11.1-5 11.1-11.1V16.1c0-6.1-5-11.1-11-11.1z",
      fill: "url(#SVGID_1_111111)"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M160.3 135.6v3.7c0 9.4-4 20.6-11.5 33-4 6.6-9 13.5-14.9 20.5-8.8 10.5-17.6 19.1-22.8 23.9-5.2-4.8-14-13.3-22.7-23.7-3.5-4.1-6.6-8.1-9.4-12.1-.3-.4-.6-.8-.8-1.1-3.5-4.9-6.4-9.7-8.8-14.3l-.3-.6c-4.8-9.4-7.2-17.9-7.2-25.4v-3.7c0-14.5 6-27.8 15.6-37.1C86.3 90.2 98 84.9 111 84.9s24.9 5.2 33.6 13.8c.9.9 1.8 1.9 2.7 2.9.4.3.6.7.9 1 .2.2.4.5.6.7 7.1 8.8 11.3 20.1 11.5 32.3z",
      opacity: ".12"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      fill: "#fff",
      d: "M160.3 121.2v3.7c0 9.4-4 20.6-11.5 33-4 6.6-9 13.5-14.9 20.5-8.8 10.5-17.6 19.1-22.8 23.9-5.2-4.8-14-13.3-22.7-23.7-3.5-4.1-6.6-8.1-9.4-12.1-.3-.4-.6-.8-.8-1.1-3.5-4.9-6.4-9.7-8.8-14.3l-.3-.6c-4.8-9.4-7.2-17.9-7.2-25.4v-3.7c0-14.5 6-27.8 15.6-37.1C86.3 75.8 98 70.5 111 70.5s24.9 5.2 33.6 13.8c.9.9 1.8 1.9 2.7 2.9.4.3.6.7.9 1 .2.2.4.5.6.7 7.1 8.8 11.3 20.1 11.5 32.3z"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M110.9 91.8c-15.6 0-28.2 12.6-28.2 28.2 0 5 1.3 9.8 3.6 13.9l-17.1 17.2c2.3 4.6 5.3 9.3 8.8 14.3l20.1-20.1c3.8 2 8.2 3.1 12.8 3.1 15.6 0 28.2-12.6 28.2-28.2s-12.6-28.4-28.2-28.4z",
      fill: "#3e62f5"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      fill: "#fff",
      d: "M102.5 100.3c-3.7 1.6-6.6 4.2-8.5 7.3-.9 1.5-.1 3.6 1.6 3.9.1 0 .2 0 .3.1 1.1.2 2.1-.3 2.7-1.3 1.4-2.2 3.4-4 6-5.1 2.8-1.2 5.7-1.3 8.4-.6 1 .3 2.1 0 2.7-.9.1-.1.1-.2.2-.3 1-1.4.3-3.5-1.4-3.9-3.8-1.1-8.1-.9-12 .8z"
    })]
  });
}

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ (function(module) {

module.exports = window["ReactJSXRuntime"];

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/***/ (function(module) {

module.exports = window["lodash"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ (function(module) {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ (function(module) {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/server-side-render":
/*!******************************************!*\
  !*** external ["wp","serverSideRender"] ***!
  \******************************************/
/***/ (function(module) {

module.exports = window["wp"]["serverSideRender"];

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js ***!
  \*********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _arrayLikeToArray; }
/* harmony export */ });
function _arrayLikeToArray(r, a) {
  (null == a || a > r.length) && (a = r.length);
  for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e];
  return n;
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _arrayWithHoles; }
/* harmony export */ });
function _arrayWithHoles(r) {
  if (Array.isArray(r)) return r;
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/defineProperty.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _defineProperty; }
/* harmony export */ });
/* harmony import */ var _toPropertyKey_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./toPropertyKey.js */ "./node_modules/@babel/runtime/helpers/esm/toPropertyKey.js");

function _defineProperty(e, r, t) {
  return (r = (0,_toPropertyKey_js__WEBPACK_IMPORTED_MODULE_0__["default"])(r)) in e ? Object.defineProperty(e, r, {
    value: t,
    enumerable: !0,
    configurable: !0,
    writable: !0
  }) : e[r] = t, e;
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js ***!
  \*************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _iterableToArrayLimit; }
/* harmony export */ });
function _iterableToArrayLimit(r, l) {
  var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"];
  if (null != t) {
    var e,
      n,
      i,
      u,
      a = [],
      f = !0,
      o = !1;
    try {
      if (i = (t = t.call(r)).next, 0 === l) {
        if (Object(t) !== t) return;
        f = !1;
      } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0);
    } catch (r) {
      o = !0, n = r;
    } finally {
      try {
        if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return;
      } finally {
        if (o) throw n;
      }
    }
    return a;
  }
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js":
/*!********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js ***!
  \********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _nonIterableRest; }
/* harmony export */ });
function _nonIterableRest() {
  throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/slicedToArray.js ***!
  \******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _slicedToArray; }
/* harmony export */ });
/* harmony import */ var _arrayWithHoles_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayWithHoles.js */ "./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js");
/* harmony import */ var _iterableToArrayLimit_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./iterableToArrayLimit.js */ "./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js");
/* harmony import */ var _unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./unsupportedIterableToArray.js */ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js");
/* harmony import */ var _nonIterableRest_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./nonIterableRest.js */ "./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js");




function _slicedToArray(r, e) {
  return (0,_arrayWithHoles_js__WEBPACK_IMPORTED_MODULE_0__["default"])(r) || (0,_iterableToArrayLimit_js__WEBPACK_IMPORTED_MODULE_1__["default"])(r, e) || (0,_unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__["default"])(r, e) || (0,_nonIterableRest_js__WEBPACK_IMPORTED_MODULE_3__["default"])();
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/toPrimitive.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/toPrimitive.js ***!
  \****************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ toPrimitive; }
/* harmony export */ });
/* harmony import */ var _typeof_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./typeof.js */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");

function toPrimitive(t, r) {
  if ("object" != (0,_typeof_js__WEBPACK_IMPORTED_MODULE_0__["default"])(t) || !t) return t;
  var e = t[Symbol.toPrimitive];
  if (void 0 !== e) {
    var i = e.call(t, r || "default");
    if ("object" != (0,_typeof_js__WEBPACK_IMPORTED_MODULE_0__["default"])(i)) return i;
    throw new TypeError("@@toPrimitive must return a primitive value.");
  }
  return ("string" === r ? String : Number)(t);
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/toPropertyKey.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/toPropertyKey.js ***!
  \******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ toPropertyKey; }
/* harmony export */ });
/* harmony import */ var _typeof_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./typeof.js */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var _toPrimitive_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./toPrimitive.js */ "./node_modules/@babel/runtime/helpers/esm/toPrimitive.js");


function toPropertyKey(t) {
  var i = (0,_toPrimitive_js__WEBPACK_IMPORTED_MODULE_1__["default"])(t, "string");
  return "symbol" == (0,_typeof_js__WEBPACK_IMPORTED_MODULE_0__["default"])(i) ? i : i + "";
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/typeof.js":
/*!***********************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/typeof.js ***!
  \***********************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _typeof; }
/* harmony export */ });
function _typeof(o) {
  "@babel/helpers - typeof";

  return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) {
    return typeof o;
  } : function (o) {
    return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o;
  }, _typeof(o);
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js":
/*!*******************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js ***!
  \*******************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _unsupportedIterableToArray; }
/* harmony export */ });
/* harmony import */ var _arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayLikeToArray.js */ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js");

function _unsupportedIterableToArray(r, a) {
  if (r) {
    if ("string" == typeof r) return (0,_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(r, a);
    var t = {}.toString.call(r).slice(8, -1);
    return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? (0,_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(r, a) : void 0;
  }
}


/***/ }),

/***/ "./src/signin-signup/block.json":
/*!**************************************!*\
  !*** ./src/signin-signup/block.json ***!
  \**************************************/
/***/ (function(module) {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"category":"directorist-blocks-collection","version":"1.0.0","title":"Sign In & Sign Up","name":"directorist/signin-signup","description":"Create user sign in and sign up form.","keywords":["login","registration","sign in","sign up"],"supports":{"html":false},"editorScript":"file:./index.js","attributes":{"user_role":{"type":"boolean","default":false},"author_role_label":{"type":"string","default":"I am an author"},"user_role_label":{"type":"string","default":"I am a user"},"registration":{"type":"boolean","default":true},"username_label":{"type":"string","default":"Username"},"password":{"type":"boolean","default":true},"password_label":{"type":"string","default":"Password"},"email_label":{"type":"string","default":"Email"},"website":{"type":"boolean","default":false},"website_label":{"type":"string","default":"Website"},"website_required":{"type":"boolean","default":false},"firstname":{"type":"boolean","default":false},"firstname_label":{"type":"string","default":"First Name"},"firstname_required":{"type":"boolean","default":false},"lastname":{"type":"boolean","default":false},"lastname_label":{"type":"string","default":"Last Name"},"lastname_required":{"type":"boolean","default":false},"bio":{"type":"boolean","default":false},"bio_label":{"type":"string","default":"About/bio"},"bio_required":{"type":"boolean","default":false},"privacy":{"type":"boolean","default":true},"privacy_label":{"type":"string","default":"I agree to the"},"privacy_linking_text":{"type":"string","default":"Privacy & Policy"},"terms":{"type":"boolean","default":true},"terms_label":{"type":"string","default":"I agree with all"},"terms_linking_text":{"type":"string","default":"terms & conditions"},"signup_button_label":{"type":"string","default":"Sign Up"},"signin_message":{"type":"string","default":"Already have an account? Please Sign in"},"signin_linking_text":{"type":"string","default":"Here"},"signin_after_signup":{"type":"boolean","default":false},"signup_redirect_url":{"type":"string","default":""},"signin_username_label":{"type":"string","default":"Username or Email Address"},"signin_button_label":{"type":"string","default":"Sign In"},"signup_label":{"type":"string","default":"Don\'t have an account?"},"signup_linking_text":{"type":"string","default":"Sign Up"},"recovery_password_label":{"type":"string","default":"Forgot Password?"},"recovery_password_description":{"type":"string","default":"Lost your password? Please enter your email address. You will receive a link to create a new password via email."},"recovery_password_email_label":{"type":"string","default":"E-mail:"},"recovery_password_email_placeholder":{"type":"string","default":"eg. mail@example.com"},"recovery_password_button_label":{"type":"string","default":"Get New Password"},"user_type":{"type":"string","default":""}}}');

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
/*!************************************!*\
  !*** ./src/signin-signup/index.js ***!
  \************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_server_side_render__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/server-side-render */ "@wordpress/server-side-render");
/* harmony import */ var _wordpress_server_side_render__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_server_side_render__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _functions__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./../functions */ "./src/functions.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./block.json */ "./src/signin-signup/block.json");
/* harmony import */ var _logo__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./../logo */ "./src/logo.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__);

function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }










var Placeholder = function Placeholder() {
  return (0,_functions__WEBPACK_IMPORTED_MODULE_7__.getPlaceholder)('signin');
};
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_8__.name, {
  icon: (0,_logo__WEBPACK_IMPORTED_MODULE_9__["default"])(),
  transforms: {
    from: [{
      type: 'shortcode',
      tag: 'directorist_user_login',
      attributes: {}
    }, {
      type: 'shortcode',
      tag: 'directorist_custom_registration',
      attributes: {}
    }]
  },
  edit: function edit(_ref) {
    var attributes = _ref.attributes,
      setAttributes = _ref.setAttributes;
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.Fragment, {
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.InspectorControls, {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.PanelBody, {
          title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Sign-In Settings', 'directorist'),
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Username Label', 'directorist'),
            value: attributes.signin_username_label,
            onChange: function onChange(value) {
              return setAttributes({
                signin_username_label: value
              });
            }
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Password Label', 'directorist'),
            value: attributes.password_label,
            onChange: function onChange(value) {
              return setAttributes({
                password_label: value
              });
            }
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Button Label', 'directorist'),
            value: attributes.signin_button_label,
            onChange: function onChange(value) {
              return setAttributes({
                signin_button_label: value
              });
            }
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Sign Up Linking Text', 'directorist'),
            value: attributes.signup_linking_text,
            onChange: function onChange(value) {
              return setAttributes({
                signup_linking_text: value
              });
            }
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Recovery Password Label', 'directorist'),
            value: attributes.recovery_password_label,
            onChange: function onChange(value) {
              return setAttributes({
                recovery_password_label: value
              });
            }
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.PanelBody, {
          isOpen: false,
          title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Sign-Up Settings', 'directorist'),
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.ToggleControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Enable Sign-Up', 'directorist'),
            checked: attributes.registration,
            onChange: function onChange(value) {
              return setAttributes({
                registration: value
              });
            }
          }), attributes.registration ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.Fragment, {
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Username Label', 'directorist'),
              value: attributes.username_label,
              onChange: function onChange(value) {
                return setAttributes({
                  username_label: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.ToggleControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Show Password Field', 'directorist'),
              checked: attributes.password,
              onChange: function onChange(value) {
                return setAttributes({
                  password: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Password Label', 'directorist'),
              value: attributes.password_label,
              onChange: function onChange(value) {
                return setAttributes({
                  password_label: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Email Label', 'directorist'),
              value: attributes.email_label,
              onChange: function onChange(value) {
                return setAttributes({
                  email_label: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Button Label', 'directorist'),
              value: attributes.signup_button_label,
              onChange: function onChange(value) {
                return setAttributes({
                  signup_button_label: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.ToggleControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Auto Sign In After Sign Up', 'directorist'),
              checked: attributes.signin_after_signup,
              onChange: function onChange(value) {
                return setAttributes({
                  signin_after_signup: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Sign Up Redirect URL', 'directorist'),
              value: attributes.signup_redirect_url,
              onChange: function onChange(value) {
                return setAttributes({
                  signup_redirect_url: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Sign Up Label', 'directorist'),
              value: attributes.signup_label,
              onChange: function onChange(value) {
                return setAttributes({
                  signup_label: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Sign In Message', 'directorist'),
              value: attributes.signin_message,
              onChange: function onChange(value) {
                return setAttributes({
                  signin_message: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Sign In Linking Text', 'directorist'),
              value: attributes.signin_linking_text,
              onChange: function onChange(value) {
                return setAttributes({
                  signin_linking_text: value
                });
              }
            })]
          }) : null]
        }), attributes.registration ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__.Fragment, {
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.PanelBody, {
            title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('User Role Settings', 'directorist'),
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.ToggleControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('User Role', 'directorist'),
              checked: attributes.user_role,
              onChange: function onChange(value) {
                return setAttributes({
                  user_role: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Author Role Label', 'directorist'),
              value: attributes.author_role_label,
              onChange: function onChange(value) {
                return setAttributes({
                  author_role_label: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('User Role Label', 'directorist'),
              value: attributes.user_role_label,
              onChange: function onChange(value) {
                return setAttributes({
                  user_role_label: value
                });
              }
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.PanelBody, {
            title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Website Settings', 'directorist'),
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.ToggleControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Show Website Field', 'directorist'),
              checked: attributes.website,
              onChange: function onChange(value) {
                return setAttributes({
                  website: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Website Label', 'directorist'),
              value: attributes.website_label,
              onChange: function onChange(value) {
                return setAttributes({
                  website_label: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.ToggleControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Require Website', 'directorist'),
              checked: attributes.website_required,
              onChange: function onChange(value) {
                return setAttributes({
                  website_required: value
                });
              }
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.PanelBody, {
            title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Name Fields', 'directorist'),
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.ToggleControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Show First Name Field', 'directorist'),
              checked: attributes.firstname,
              onChange: function onChange(value) {
                return setAttributes({
                  firstname: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('First Name Label', 'directorist'),
              value: attributes.firstname_label,
              onChange: function onChange(value) {
                return setAttributes({
                  firstname_label: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.ToggleControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Require First Name', 'directorist'),
              checked: attributes.firstname_required,
              onChange: function onChange(value) {
                return setAttributes({
                  firstname_required: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.ToggleControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Show Last Name Field', 'directorist'),
              checked: attributes.lastname,
              onChange: function onChange(value) {
                return setAttributes({
                  lastname: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Last Name Label', 'directorist'),
              value: attributes.lastname_label,
              onChange: function onChange(value) {
                return setAttributes({
                  lastname_label: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.ToggleControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Require Last Name', 'directorist'),
              checked: attributes.lastname_required,
              onChange: function onChange(value) {
                return setAttributes({
                  lastname_required: value
                });
              }
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.PanelBody, {
            title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Bio Settings', 'directorist'),
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.ToggleControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Show Bio Field', 'directorist'),
              checked: attributes.bio,
              onChange: function onChange(value) {
                return setAttributes({
                  bio: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Bio Label', 'directorist'),
              value: attributes.bio_label,
              onChange: function onChange(value) {
                return setAttributes({
                  bio_label: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.ToggleControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Require Bio', 'directorist'),
              checked: attributes.bio_required,
              onChange: function onChange(value) {
                return setAttributes({
                  bio_required: value
                });
              }
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.PanelBody, {
            title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Privacy & Terms', 'directorist'),
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.ToggleControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Enable Privacy Agreement', 'directorist'),
              checked: attributes.privacy,
              onChange: function onChange(value) {
                return setAttributes({
                  privacy: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Privacy Label', 'directorist'),
              value: attributes.privacy_label,
              onChange: function onChange(value) {
                return setAttributes({
                  privacy_label: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Privacy Linking Text', 'directorist'),
              value: attributes.privacy_linking_text,
              onChange: function onChange(value) {
                return setAttributes({
                  privacy_linking_text: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.ToggleControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Enable Terms Agreement', 'directorist'),
              checked: attributes.terms,
              onChange: function onChange(value) {
                return setAttributes({
                  terms: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Terms Label', 'directorist'),
              value: attributes.terms_label,
              onChange: function onChange(value) {
                return setAttributes({
                  terms_label: value
                });
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Terms Linking Text', 'directorist'),
              value: attributes.terms_linking_text,
              onChange: function onChange(value) {
                return setAttributes({
                  terms_linking_text: value
                });
              }
            })]
          })]
        }) : null, /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.PanelBody, {
          title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Recovery Password Settings', 'directorist'),
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.TextControl, {
            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Recovery Password Linking Text', 'directorist'),
            value: attributes.recovery_password_linking_text,
            onChange: function onChange(value) {
              return setAttributes({
                recovery_password_linking_text: value
              });
            }
          })
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)("div", _objectSpread(_objectSpread({}, (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.useBlockProps)({
        className: 'directorist-content-active directorist-w-100'
      })), {}, {
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)((_wordpress_server_side_render__WEBPACK_IMPORTED_MODULE_3___default()), {
          block: _block_json__WEBPACK_IMPORTED_MODULE_8__.name,
          attributes: attributes,
          LoadingResponsePlaceholder: Placeholder
        })
      }))]
    });
  }
});
/******/ })()
;
//# sourceMappingURL=index.js.map