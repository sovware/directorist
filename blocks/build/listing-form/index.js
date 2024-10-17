/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/@wordpress/icons/build-module/library/close-small.js":
/*!***************************************************************************!*\
  !*** ./node_modules/@wordpress/icons/build-module/library/close-small.js ***!
  \***************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/primitives */ "@wordpress/primitives");
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__);
/**
 * WordPress dependencies
 */


const closeSmall = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24",
  children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__.Path, {
    d: "M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"
  })
});
/* harmony default export */ __webpack_exports__["default"] = (closeSmall);
//# sourceMappingURL=close-small.js.map

/***/ }),

/***/ "./src/controls.js":
/*!*************************!*\
  !*** ./src/controls.js ***!
  \*************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   CategoryControl: function() { return /* binding */ CategoryControl; },
/* harmony export */   ListingControl: function() { return /* binding */ ListingControl; },
/* harmony export */   LocationControl: function() { return /* binding */ LocationControl; },
/* harmony export */   TagsControl: function() { return /* binding */ TagsControl; },
/* harmony export */   TypesControl: function() { return /* binding */ TypesControl; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _functions__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./functions */ "./src/functions.js");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__);
/* harmony import */ var _vendors_token_multiselect_control__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./vendors/token-multiselect-control */ "./src/vendors/token-multiselect-control/index.js");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_13__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_14___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_14__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__);







function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _callSuper(t, o, e) { return o = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(o), (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__["default"])(t, _isNativeReflectConstruct() ? Reflect.construct(o, e || [], (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(t).constructor) : o.apply(t, e)); }
function _isNativeReflectConstruct() { try { var t = !Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); } catch (t) {} return (_isNativeReflectConstruct = function _isNativeReflectConstruct() { return !!t; })(); }







var _directoristBlockConf = directoristBlockConfig,
  CATEGORY_TAX = _directoristBlockConf.categoryTax,
  LOCATION_TAX = _directoristBlockConf.locationTax,
  POST_TYPE = _directoristBlockConf.postType,
  TAG_TAX = _directoristBlockConf.tagTax,
  TYPE_TAX = _directoristBlockConf.typeTax;


var TypesControl = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_7__.withSelect)(function (select) {
  var args = {
    per_page: -1,
    order: 'asc',
    orderby: 'name'
  };
  return {
    items: select('core').getEntityRecords('taxonomy', TYPE_TAX, args)
  };
})(function (props) {
  var choices = [],
    defaultDirChoices = [];
  if (props.items) {
    choices = (props.shouldRender ? (0,_functions__WEBPACK_IMPORTED_MODULE_9__.sortItemsBySelected)(props.items, props.selected, 'slug') : props.items).map(function (item) {
      return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)("li", {
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_14__.CheckboxControl, {
          label: (0,lodash__WEBPACK_IMPORTED_MODULE_10__.truncate)((0,_functions__WEBPACK_IMPORTED_MODULE_9__.decodeHTML)(item.name), {
            length: 30
          }),
          checked: props.selected.includes(item.slug),
          onChange: function onChange(updated) {
            var values = updated ? [].concat((0,_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_6__["default"])(props.selected), [item.slug]) : (0,lodash__WEBPACK_IMPORTED_MODULE_10__.without)(props.selected, item.slug);
            props.onChange(values);
          }
        })
      }, item.id);
    });
    if (props.showDefault) {
      defaultDirChoices = (props.selected.length > 0 ? props.items.filter(function (item) {
        return props.selected.includes(item.slug);
      }) : props.items).map(function (item) {
        return {
          label: (0,lodash__WEBPACK_IMPORTED_MODULE_10__.truncate)((0,_functions__WEBPACK_IMPORTED_MODULE_9__.decodeHTML)(item.name), {
            length: 30
          }),
          value: item.slug
        };
      });
    }
  } else {
    choices = [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)("li", {
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_14__.Spinner, {})
    })];
  }
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsxs)(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__.Fragment, {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_14__.BaseControl, {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__.__)('Directory Types', 'directorist'),
      className: "directorist-gb-cb-list-control",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)("ul", {
        className: "editor-post-taxonomies__hierarchical-terms-list",
        children: choices
      })
    }), defaultDirChoices.length && props.showDefault ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_14__.RadioControl, {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__.__)('Select Default Directory', 'directorist'),
      selected: props.defaultType ? props.defaultType : window.directorist.default_directory_type,
      options: defaultDirChoices,
      onChange: props.onDefaultChange
    }) : '']
  });
});
var TermsMultiSelectComponent = /*#__PURE__*/function (_Component) {
  function TermsMultiSelectComponent(props) {
    var _this;
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__["default"])(this, TermsMultiSelectComponent);
    _this = _callSuper(this, TermsMultiSelectComponent, [props]);
    _this.state = {
      options: [],
      value: _this.props.value,
      isLoading: true
    };
    return _this;
  }
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5__["default"])(TermsMultiSelectComponent, _Component);
  return (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__["default"])(TermsMultiSelectComponent, [{
    key: "render",
    value: function render() {
      var _this2 = this;
      if (this.state.isLoading) {
        return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_14__.BaseControl, {
          label: this.props.label,
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_14__.Spinner, {})
        });
      }
      return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)(_vendors_token_multiselect_control__WEBPACK_IMPORTED_MODULE_12__["default"], {
        maxSuggestions: 10,
        label: this.props.label,
        value: this.state.value,
        options: this.state.options,
        onChange: function onChange(value) {
          _this2.setState({
            value: value,
            isLoading: false
          });
          _this2.props.onChange(value);
        },
        onInputChange: function onInputChange(term) {
          _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_13___default()({
            path: "wp/v2/".concat(_this2.props.taxonomy, "?per_page=10&search=").concat(term)
          }).then(function (terms) {
            var options = (0,_functions__WEBPACK_IMPORTED_MODULE_9__.remapTaxTerms)(terms);
            _this2.setState({
              options: options
            });
          });
        }
      });
    }
  }], [{
    key: "getDerivedStateFromProps",
    value: function getDerivedStateFromProps(props, state) {
      if (!(0,lodash__WEBPACK_IMPORTED_MODULE_10__.isEmpty)(props.options) || !(0,lodash__WEBPACK_IMPORTED_MODULE_10__.isEmpty)(state.options)) {
        return {
          options: (0,lodash__WEBPACK_IMPORTED_MODULE_10__.uniqBy)(state.options.concat(props.options), 'value'),
          isLoading: false
        };
      }
      return null;
    }
  }]);
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__.Component);
var TermsMultiSelectControl = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_7__.withSelect)(function (select, props) {
  var args = {
    per_page: 10,
    order: 'asc',
    orderby: 'name'
  };
  if (!(0,lodash__WEBPACK_IMPORTED_MODULE_10__.isEmpty)(props.value)) {
    args.slug = props.value;
    args.orderby = 'include_slugs';
    args.per_page = props.value.length;
  }
  var terms = select('core').getEntityRecords('taxonomy', props.taxonomy, args);
  return {
    options: !(0,lodash__WEBPACK_IMPORTED_MODULE_10__.isEmpty)(terms) ? (0,_functions__WEBPACK_IMPORTED_MODULE_9__.remapTaxTerms)(terms) : []
  };
})(TermsMultiSelectComponent);
var TagsControl = function TagsControl(props) {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)(TermsMultiSelectControl, _objectSpread(_objectSpread({}, props), {}, {
    taxonomy: TAG_TAX,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__.__)('Select Tags', 'directorist')
  }));
};
var CategoryControl = function CategoryControl(props) {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)(TermsMultiSelectControl, _objectSpread(_objectSpread({}, props), {}, {
    taxonomy: CATEGORY_TAX,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__.__)('Select Categories', 'directorist')
  }));
};
var LocationControl = function LocationControl(props) {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)(TermsMultiSelectControl, _objectSpread(_objectSpread({}, props), {}, {
    taxonomy: LOCATION_TAX,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__.__)('Select Locations', 'directorist')
  }));
};
var PostsMultiSelectComponent = /*#__PURE__*/function (_Component2) {
  function PostsMultiSelectComponent(props) {
    var _this3;
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__["default"])(this, PostsMultiSelectComponent);
    _this3 = _callSuper(this, PostsMultiSelectComponent, [props]);
    _this3.state = {
      options: [],
      value: _this3.props.value,
      isLoading: true
    };
    return _this3;
  }
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5__["default"])(PostsMultiSelectComponent, _Component2);
  return (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__["default"])(PostsMultiSelectComponent, [{
    key: "render",
    value: function render() {
      var _this4 = this;
      if (this.state.isLoading) {
        return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_14__.BaseControl, {
          label: this.props.label,
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_14__.Spinner, {})
        });
      }
      return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)(_vendors_token_multiselect_control__WEBPACK_IMPORTED_MODULE_12__["default"], {
        maxSuggestions: 10,
        label: this.props.label,
        value: this.state.value,
        options: this.state.options,
        onChange: function onChange(value) {
          _this4.setState({
            value: value,
            isLoading: false
          });
          _this4.props.onChange(value);
        },
        onInputChange: function onInputChange(term) {
          _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_13___default()({
            path: "wp/v2/".concat(_this4.props.postType, "?per_page=10&search=").concat(term)
          }).then(function (posts) {
            var options = (0,_functions__WEBPACK_IMPORTED_MODULE_9__.remapPosts)(posts);
            _this4.setState({
              options: options
            });
          });
        }
      });
    }
  }], [{
    key: "getDerivedStateFromProps",
    value: function getDerivedStateFromProps(props, state) {
      if (!(0,lodash__WEBPACK_IMPORTED_MODULE_10__.isEmpty)(props.options) || !(0,lodash__WEBPACK_IMPORTED_MODULE_10__.isEmpty)(state.options)) {
        return {
          options: (0,lodash__WEBPACK_IMPORTED_MODULE_10__.uniqBy)(state.options.concat(props.options), 'value'),
          isLoading: false
        };
      }
      return null;
    }
  }]);
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__.Component);
var PostsMultiSelectControl = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_7__.withSelect)(function (select, props) {
  var args = {
    per_page: 10,
    order: 'desc',
    orderby: 'date'
  };
  if (!(0,lodash__WEBPACK_IMPORTED_MODULE_10__.isEmpty)(props.value)) {
    args.include = props.value;
    args.orderby = 'include';
    args.per_page = props.value.length;
  }
  var posts = select('core').getEntityRecords('postType', props.postType, args);
  return {
    options: !(0,lodash__WEBPACK_IMPORTED_MODULE_10__.isEmpty)(posts) ? (0,_functions__WEBPACK_IMPORTED_MODULE_9__.remapPosts)(posts) : []
  };
})(PostsMultiSelectComponent);
var ListingControl = function ListingControl(props) {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_15__.jsx)(PostsMultiSelectControl, _objectSpread(_objectSpread({}, props), {}, {
    postType: POST_TYPE,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__.__)('Select Listings', 'directorist')
  }));
};

/***/ }),

/***/ "./src/functions.js":
/*!**************************!*\
  !*** ./src/functions.js ***!
  \**************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
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

"use strict";
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

/***/ "./src/vendors/token-multiselect-control/index.js":
/*!********************************************************!*\
  !*** ./src/vendors/token-multiselect-control/index.js ***!
  \********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! classnames */ "../node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_11__);
/* harmony import */ var _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! @wordpress/keycodes */ "@wordpress/keycodes");
/* harmony import */ var _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_wordpress_keycodes__WEBPACK_IMPORTED_MODULE_12__);
/* harmony import */ var _wordpress_is_shallow_equal__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! @wordpress/is-shallow-equal */ "@wordpress/is-shallow-equal");
/* harmony import */ var _wordpress_is_shallow_equal__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(_wordpress_is_shallow_equal__WEBPACK_IMPORTED_MODULE_13__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_14___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_14__);
/* harmony import */ var _token__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./token */ "./src/vendors/token-multiselect-control/token.js");
/* harmony import */ var _token_input__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./token-input */ "./src/vendors/token-multiselect-control/token-input.js");
/* harmony import */ var _suggestions_list__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./suggestions-list */ "./src/vendors/token-multiselect-control/suggestions-list.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_18___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_18__);







function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _callSuper(t, o, e) { return o = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(o), (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(t, _isNativeReflectConstruct() ? Reflect.construct(o, e || [], (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(t).constructor) : o.apply(t, e)); }
function _isNativeReflectConstruct() { try { var t = !Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); } catch (t) {} return (_isNativeReflectConstruct = function _isNativeReflectConstruct() { return !!t; })(); }
/**
 * External dependencies
 */



/**
 * WordPress dependencies
 */







/**
 * Internal dependencies
 */




var initialState = {
  incompleteTokenValue: '',
  inputOffsetFromEnd: 0,
  isActive: false,
  isExpanded: false,
  selectedSuggestionIndex: -1,
  selectedSuggestionScroll: false
};
var TokenMultiSelectControl = /*#__PURE__*/function (_Component) {
  function TokenMultiSelectControl() {
    var _this;
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2__["default"])(this, TokenMultiSelectControl);
    _this = _callSuper(this, TokenMultiSelectControl, arguments);
    _this.state = initialState;
    _this.onKeyDown = _this.onKeyDown.bind(_this);
    _this.onKeyPress = _this.onKeyPress.bind(_this);
    _this.onFocus = _this.onFocus.bind(_this);
    _this.onClick = _this.onClick.bind(_this);
    _this.onBlur = _this.onBlur.bind(_this);
    _this.deleteTokenBeforeInput = _this.deleteTokenBeforeInput.bind(_this);
    _this.deleteTokenAfterInput = _this.deleteTokenAfterInput.bind(_this);
    _this.addCurrentToken = _this.addCurrentToken.bind(_this);
    _this.onContainerTouched = _this.onContainerTouched.bind(_this);
    _this.renderToken = _this.renderToken.bind(_this);
    _this.onTokenClickRemove = _this.onTokenClickRemove.bind(_this);
    _this.onSuggestionHovered = _this.onSuggestionHovered.bind(_this);
    _this.onSuggestionSelected = _this.onSuggestionSelected.bind(_this);
    _this.onInputChange = _this.onInputChange.bind(_this);
    _this.bindInput = _this.bindInput.bind(_this);
    _this.bindTokensAndInput = _this.bindTokensAndInput.bind(_this);
    _this.updateSuggestions = _this.updateSuggestions.bind(_this);
    _this.addNewTokens = _this.addNewTokens.bind(_this);
    _this.getValueFromLabel = _this.getValueFromLabel.bind(_this);
    _this.getLabelFromValue = _this.getLabelFromValue.bind(_this);
    return _this;
  }
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_6__["default"])(TokenMultiSelectControl, _Component);
  return (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3__["default"])(TokenMultiSelectControl, [{
    key: "componentDidUpdate",
    value: function componentDidUpdate(prevProps) {
      // Make sure to focus the input when the isActive state is true.
      if (this.state.isActive && !this.input.hasFocus()) {
        this.input.focus();
      }
      var _this$props = this.props,
        options = _this$props.options,
        value = _this$props.value;
      var suggestionsDidUpdate = !_wordpress_is_shallow_equal__WEBPACK_IMPORTED_MODULE_13___default()(options, prevProps.options);
      if (suggestionsDidUpdate || value !== prevProps.value) {
        this.updateSuggestions(suggestionsDidUpdate);
      }
    }
  }, {
    key: "bindInput",
    value: function bindInput(ref) {
      this.input = ref;
    }
  }, {
    key: "bindTokensAndInput",
    value: function bindTokensAndInput(ref) {
      this.tokensAndInput = ref;
    }
  }, {
    key: "onFocus",
    value: function onFocus(event) {
      // If focus is on the input or on the container, set the isActive state to true.
      if (this.input.hasFocus() || event.target === this.tokensAndInput) {
        this.setState({
          isActive: true /* , isExpanded: true */
        });
      } else {
        /*
         * Otherwise, focus is on one of the token "remove" buttons and we
         * set the isActive state to false to prevent the input to be
         * re-focused, see componentDidUpdate().
         */
        this.setState({
          isActive: false
        });
      }
      if ('function' === typeof this.props.onFocus) {
        this.props.onFocus(event);
      }
    }
  }, {
    key: "onClick",
    value: function onClick(event) {
      // If focus is on the input or on the container, set the isActive state to true.
      // don't open if we clicked a suggestion
      if (!event.target.classList.contains('components-form-token-field__suggestion')) {
        this.setState({
          isExpanded: true,
          isActive: true
        });
      }
    }
  }, {
    key: "onBlur",
    value: function onBlur() {
      if (this.inputHasValidToken()) {
        this.setState({
          isActive: false,
          isExpanded: false
        });
      } else {
        this.setState(initialState);
      }
    }
  }, {
    key: "onKeyDown",
    value: function onKeyDown(event) {
      var preventDefault = false;
      switch (event.keyCode) {
        case _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_12__.BACKSPACE:
          preventDefault = this.handleDeleteKey(this.deleteTokenBeforeInput);
          break;
        case _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_12__.ENTER:
          preventDefault = this.addCurrentToken();
          break;
        case _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_12__.LEFT:
          preventDefault = this.handleLeftArrowKey();
          break;
        case _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_12__.UP:
          preventDefault = this.handleUpArrowKey();
          break;
        case _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_12__.RIGHT:
          preventDefault = this.handleRightArrowKey();
          break;
        case _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_12__.DOWN:
          preventDefault = this.handleDownArrowKey();
          break;
        case _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_12__.DELETE:
          preventDefault = this.handleDeleteKey(this.deleteTokenAfterInput);
          break;
        case _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_12__.SPACE:
          if (this.props.tokenizeOnSpace) {
            preventDefault = this.addCurrentToken();
          }
          break;
        case _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_12__.ESCAPE:
          preventDefault = this.handleEscapeKey(event);
          event.stopPropagation();
          break;
        default:
          break;
      }
      if (preventDefault) {
        event.preventDefault();
      }
    }
  }, {
    key: "onKeyPress",
    value: function onKeyPress(event) {
      if (!this.state.isExpanded) {
        this.setState({
          isExpanded: true
        });
      }
    }
  }, {
    key: "onContainerTouched",
    value: function onContainerTouched(event) {
      // Prevent clicking/touching the tokensAndInput container from blurring
      // the input and adding the current token.
      if (event.target === this.tokensAndInput && this.state.isActive) {
        event.preventDefault();
      }
      //this.setState( { isExpanded: true } );
    }
  }, {
    key: "onTokenClickRemove",
    value: function onTokenClickRemove(event) {
      this.deleteToken(event.value);
      this.input.focus();
    }
  }, {
    key: "onSuggestionHovered",
    value: function onSuggestionHovered(suggestion) {
      var index = this.getMatchingSuggestions().indexOf(suggestion);
      if (index >= 0) {
        this.setState({
          selectedSuggestionIndex: index,
          selectedSuggestionScroll: false
        });
      }
    }
  }, {
    key: "onSuggestionSelected",
    value: function onSuggestionSelected(suggestion) {
      this.addNewToken(suggestion);
    }
  }, {
    key: "onInputChange",
    value: function onInputChange(event) {
      var tokenValue = event.value;
      this.setState({
        incompleteTokenValue: tokenValue
      }, this.updateSuggestions);
      this.props.onInputChange(tokenValue);
    }
  }, {
    key: "handleDeleteKey",
    value: function handleDeleteKey(deleteToken) {
      var preventDefault = false;
      if (this.input.hasFocus() && this.isInputEmpty()) {
        deleteToken();
        preventDefault = true;
      }
      return preventDefault;
    }
  }, {
    key: "handleLeftArrowKey",
    value: function handleLeftArrowKey() {
      var preventDefault = false;
      if (this.isInputEmpty()) {
        this.moveInputBeforePreviousToken();
        preventDefault = true;
      }
      return preventDefault;
    }
  }, {
    key: "handleRightArrowKey",
    value: function handleRightArrowKey() {
      var preventDefault = false;
      if (this.isInputEmpty()) {
        this.moveInputAfterNextToken();
        preventDefault = true;
      }
      return preventDefault;
    }
  }, {
    key: "getOptionsLabels",
    value: function getOptionsLabels(options) {
      return options.map(function (option) {
        return option.label;
      });
    }
  }, {
    key: "getValueFromLabel",
    value: function getValueFromLabel(optionLabel) {
      var foundOption = this.props.options.find(function (option) {
        return option.label.toLocaleLowerCase() === optionLabel.toLocaleLowerCase();
      });
      if (foundOption) {
        return foundOption.value;
      }
      return null;
    }
  }, {
    key: "getLabelFromValue",
    value: function getLabelFromValue(optionValue) {
      var foundOption = this.props.options.find(function (option) {
        return option.value === optionValue;
      });
      if (foundOption) {
        return foundOption.label;
      }
      return null;
    }
  }, {
    key: "getOptionsValues",
    value: function getOptionsValues(options) {
      return options.map(function (option) {
        return option.value;
      });
    }
  }, {
    key: "handleUpArrowKey",
    value: function handleUpArrowKey() {
      var _this2 = this;
      this.setState(function (state, props) {
        return {
          selectedSuggestionIndex: (state.selectedSuggestionIndex === 0 ? _this2.getMatchingSuggestions(state.incompleteTokenValue, _this2.getOptionsLabels(props.options), props.value, props.maxSuggestions, props.saveTransform).length : state.selectedSuggestionIndex) - 1,
          selectedSuggestionScroll: true
        };
      });
      return true; // preventDefault
    }
  }, {
    key: "handleDownArrowKey",
    value: function handleDownArrowKey() {
      var _this3 = this;
      this.setState(function (state, props) {
        return {
          selectedSuggestionIndex: (state.selectedSuggestionIndex + 1) % _this3.getMatchingSuggestions(state.incompleteTokenValue, _this3.getOptionsLabels(props.options), props.value, props.maxSuggestions, props.saveTransform).length,
          selectedSuggestionScroll: true,
          isExpanded: true
        };
      });
      return true; // preventDefault
    }
  }, {
    key: "handleEscapeKey",
    value: function handleEscapeKey(event) {
      this.setState({
        incompleteTokenValue: event.target.value,
        isExpanded: false,
        selectedSuggestionIndex: -1,
        selectedSuggestionScroll: false
      });
      return true; // preventDefault
    }
  }, {
    key: "moveInputToIndex",
    value: function moveInputToIndex(index) {
      this.setState(function (state, props) {
        return {
          inputOffsetFromEnd: props.value.length - Math.max(index, -1) - 1
        };
      });
    }
  }, {
    key: "moveInputBeforePreviousToken",
    value: function moveInputBeforePreviousToken() {
      this.setState(function (state, props) {
        return {
          inputOffsetFromEnd: Math.min(state.inputOffsetFromEnd + 1, props.value.length)
        };
      });
    }
  }, {
    key: "moveInputAfterNextToken",
    value: function moveInputAfterNextToken() {
      this.setState(function (state) {
        return {
          inputOffsetFromEnd: Math.max(state.inputOffsetFromEnd - 1, 0)
        };
      });
    }
  }, {
    key: "deleteTokenBeforeInput",
    value: function deleteTokenBeforeInput() {
      var index = this.getIndexOfInput() - 1;
      if (index > -1) {
        this.deleteToken(this.props.value[index]);
      }
    }
  }, {
    key: "deleteTokenAfterInput",
    value: function deleteTokenAfterInput() {
      var index = this.getIndexOfInput();
      if (index < this.props.value.length) {
        this.deleteToken(this.props.value[index]);
        // update input offset since it's the offset from the last token
        this.moveInputToIndex(index);
      }
    }
  }, {
    key: "addCurrentToken",
    value: function addCurrentToken() {
      var preventDefault = false;
      var selectedSuggestion = this.getSelectedSuggestion();
      if (selectedSuggestion) {
        this.addNewToken(selectedSuggestion);
        preventDefault = true;
      } else if (this.inputHasValidToken()) {
        this.addNewToken(this.state.incompleteTokenValue);
        preventDefault = true;
      }
      return preventDefault;
    }
  }, {
    key: "addNewTokens",
    value: function addNewTokens(tokens) {
      var _this4 = this;
      var tokensToAdd = (0,lodash__WEBPACK_IMPORTED_MODULE_7__.uniq)(tokens.map(this.props.saveTransform).filter(Boolean).filter(function (token) {
        return !_this4.valueContainsToken(token);
      }));
      if (tokensToAdd.length > 0) {
        var tokenValuesToAdd = tokensToAdd.map(function (tokenLabel) {
          return _this4.getValueFromLabel(tokenLabel);
        });
        var newValue = (0,lodash__WEBPACK_IMPORTED_MODULE_7__.clone)(this.props.value);
        newValue.splice.apply(newValue, [this.getIndexOfInput(), 0].concat(tokenValuesToAdd));
        // now remove duplicates if required
        newValue = (0,_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__["default"])(new Set(newValue));
        this.props.onChange(newValue);
      }
    }
  }, {
    key: "addNewToken",
    value: function addNewToken(token) {
      this.addNewTokens([token]);
      this.props.speak(this.props.messages.added, 'assertive');
      this.setState({
        incompleteTokenValue: '',
        selectedSuggestionIndex: -1,
        selectedSuggestionScroll: false,
        isExpanded: false
      });
      if (this.state.isActive) {
        this.input.focus();
      }
    }
  }, {
    key: "deleteToken",
    value: function deleteToken(token) {
      var _this5 = this;
      var newTokens = this.props.value.filter(function (item) {
        return _this5.getTokenValue(item) !== _this5.getTokenValue(token);
      });
      this.props.onChange(newTokens);
      this.props.speak(this.props.messages.removed, 'assertive');
    }
  }, {
    key: "getTokenValue",
    value: function getTokenValue(token) {
      if (token && token.value) {
        return token.value;
      }
      return token;
    }
  }, {
    key: "getMatchingSuggestions",
    value: function getMatchingSuggestions() {
      var _this6 = this;
      var searchValue = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : this.state.incompleteTokenValue;
      var suggestions = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.getOptionsLabels(this.props.options);
      var value = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : this.props.value;
      var maxSuggestions = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : this.props.maxSuggestions;
      var saveTransform = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : this.props.saveTransform;
      var match = saveTransform(searchValue);
      var startsWithMatch = [];
      var containsMatch = [];
      var activeLabels = value.map(function (optionValue) {
        return _this6.getLabelFromValue(optionValue);
      });
      if (match.length > 0) {
        match = match.toLocaleLowerCase();
        (0,lodash__WEBPACK_IMPORTED_MODULE_7__.each)(suggestions, function (suggestion) {
          var index = suggestion.toLocaleLowerCase().indexOf(match);
          if (value.indexOf(suggestion) === -1) {
            if (index === 0) {
              startsWithMatch.push(suggestion);
            } else if (index > 0) {
              containsMatch.push(suggestion);
            }
          }
        });
        suggestions = startsWithMatch.concat(containsMatch);
      }
      // remove selected labels from suggestions
      suggestions = (0,lodash__WEBPACK_IMPORTED_MODULE_7__.difference)(suggestions, activeLabels);
      return (0,lodash__WEBPACK_IMPORTED_MODULE_7__.take)(suggestions, maxSuggestions);
    }
  }, {
    key: "getSelectedSuggestion",
    value: function getSelectedSuggestion() {
      if (this.state.selectedSuggestionIndex !== -1) {
        return this.getMatchingSuggestions()[this.state.selectedSuggestionIndex];
      }
    }
  }, {
    key: "valueContainsToken",
    value: function valueContainsToken(token) {
      var _this7 = this;
      return (0,lodash__WEBPACK_IMPORTED_MODULE_7__.some)(this.props.value, function (item) {
        return _this7.getTokenValue(token) === _this7.getTokenValue(item);
      });
    }
  }, {
    key: "getIndexOfInput",
    value: function getIndexOfInput() {
      return this.props.value.length - this.state.inputOffsetFromEnd;
    }
  }, {
    key: "isInputEmpty",
    value: function isInputEmpty() {
      return this.state.incompleteTokenValue.length === 0;
    }
  }, {
    key: "inputHasValidToken",
    value: function inputHasValidToken() {
      var incompleteTokenValue = this.state.incompleteTokenValue;
      var foundMatch = false;
      if (incompleteTokenValue && incompleteTokenValue.length > 0) {
        this.props.options.forEach(function (option) {
          if (option.label.trim().toLocaleLowerCase() === incompleteTokenValue.trim().toLocaleLowerCase()) {
            foundMatch = true;
            // return true; //not working?
          }
        });
      }
      return foundMatch;
    }
  }, {
    key: "updateSuggestions",
    value: function updateSuggestions() {
      var resetSelectedSuggestion = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
      var incompleteTokenValue = this.state.incompleteTokenValue;
      var inputHasMinimumChars = true; //incompleteTokenValue.trim().length > 1;
      var matchingSuggestions = this.getMatchingSuggestions(incompleteTokenValue);
      var hasMatchingSuggestions = matchingSuggestions.length > 0;
      var newState = {
        // isExpanded: inputHasMinimumChars && hasMatchingSuggestions,
      };
      if (resetSelectedSuggestion) {
        newState.selectedSuggestionIndex = -1;
        newState.selectedSuggestionScroll = false;
      }
      this.setState(newState);
      if (inputHasMinimumChars) {
        var debouncedSpeak = this.props.debouncedSpeak;
        var message = hasMatchingSuggestions ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__.sprintf)(/* translators: %d: number of results. */
        (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__._n)('%d result found, use up and down arrow keys to navigate.', '%d results found, use up and down arrow keys to navigate.', matchingSuggestions.length), matchingSuggestions.length) : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__.__)('No results.');
        debouncedSpeak(message, 'assertive');
      }
    }
  }, {
    key: "renderTokensAndInput",
    value: function renderTokensAndInput() {
      var components = (0,lodash__WEBPACK_IMPORTED_MODULE_7__.map)(this.props.value, this.renderToken);
      components.splice(this.getIndexOfInput(), 0, this.renderInput());
      return components;
    }
  }, {
    key: "renderToken",
    value: function renderToken(token, index, tokens) {
      var value = this.getTokenValue(token);
      var label = this.getLabelFromValue(value); //todo - optimize
      var status = token.status ? token.status : undefined;
      var termPosition = index + 1;
      var termsCount = tokens.length;
      return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_18__.jsx)(_token__WEBPACK_IMPORTED_MODULE_15__["default"], {
        value: value,
        label: label,
        status: status,
        title: token.title,
        displayTransform: this.props.displayTransform,
        onClickRemove: this.onTokenClickRemove,
        isBorderless: token.isBorderless || this.props.isBorderless,
        onMouseEnter: token.onMouseEnter,
        onMouseLeave: token.onMouseLeave,
        disabled: 'error' !== status && this.props.disabled,
        messages: this.props.messages,
        termsCount: termsCount,
        termPosition: termPosition
      }, 'token-' + value);
    }
  }, {
    key: "renderInput",
    value: function renderInput() {
      var _this$props2 = this.props,
        autoCapitalize = _this$props2.autoCapitalize,
        autoComplete = _this$props2.autoComplete,
        maxLength = _this$props2.maxLength,
        value = _this$props2.value,
        instanceId = _this$props2.instanceId;
      var props = {
        instanceId: instanceId,
        autoCapitalize: autoCapitalize,
        autoComplete: autoComplete,
        ref: this.bindInput,
        key: 'input',
        disabled: this.props.disabled,
        value: this.state.incompleteTokenValue,
        onBlur: this.onBlur,
        isExpanded: this.state.isExpanded,
        selectedSuggestionIndex: this.state.selectedSuggestionIndex
      };
      if (!(maxLength && value.length >= maxLength)) {
        props = _objectSpread(_objectSpread({}, props), {}, {
          onChange: this.onInputChange
        });
      }
      return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_18__.jsx)(_token_input__WEBPACK_IMPORTED_MODULE_16__["default"], _objectSpread({}, props));
    }
  }, {
    key: "render",
    value: function render() {
      var _this$props3 = this.props,
        disabled = _this$props3.disabled,
        _this$props3$label = _this$props3.label,
        label = _this$props3$label === void 0 ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__.__)('Add item') : _this$props3$label,
        instanceId = _this$props3.instanceId,
        className = _this$props3.className;
      var isExpanded = this.state.isExpanded;
      var classes = classnames__WEBPACK_IMPORTED_MODULE_8___default()(className, 'components-form-token-field__input-container', {
        'is-active': this.state.isActive,
        'is-disabled': disabled
      });
      var tokenFieldProps = {
        className: 'components-form-token-field directorist-gb-multiselect',
        tabIndex: '-1'
      };
      var matchingSuggestions = this.getMatchingSuggestions();
      if (!disabled) {
        tokenFieldProps = Object.assign({}, tokenFieldProps, {
          onKeyDown: this.onKeyDown,
          onKeyPress: this.onKeyPress,
          onFocus: this.onFocus,
          onClick: this.onClick
        });
      }

      // Disable reason: There is no appropriate role which describes the
      // input container intended accessible usability.
      // TODO: Refactor click detection to use blur to stop propagation.
      /* eslint-disable jsx-a11y/no-static-element-interactions */
      return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_18__.jsxs)("div", _objectSpread(_objectSpread({}, tokenFieldProps), {}, {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_18__.jsx)("label", {
          htmlFor: "components-form-token-input-".concat(instanceId),
          className: "components-form-token-field__label",
          style: {
            fontSize: '13px'
          },
          children: label
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_18__.jsxs)("div", {
          ref: this.bindTokensAndInput,
          className: classes,
          tabIndex: "-1",
          onMouseDown: this.onContainerTouched,
          onTouchStart: this.onContainerTouched,
          children: [this.renderTokensAndInput(), isExpanded && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_18__.jsx)(_suggestions_list__WEBPACK_IMPORTED_MODULE_17__["default"], {
            instanceId: instanceId,
            match: this.props.saveTransform(this.state.incompleteTokenValue),
            displayTransform: this.props.displayTransform,
            suggestions: matchingSuggestions,
            selectedIndex: this.state.selectedSuggestionIndex,
            scrollIntoView: this.state.selectedSuggestionScroll,
            onHover: this.onSuggestionHovered,
            onSelect: this.onSuggestionSelected
          })]
        })]
      }));
      /* eslint-enable jsx-a11y/no-static-element-interactions */
    }
  }], [{
    key: "getDerivedStateFromProps",
    value: function getDerivedStateFromProps(props, state) {
      if (!props.disabled || !state.isActive) {
        return null;
      }
      return {
        isActive: false,
        incompleteTokenValue: ''
      };
    }
  }]);
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_10__.Component);
TokenMultiSelectControl.defaultProps = {
  options: Object.freeze([]),
  maxSuggestions: 100,
  value: Object.freeze([]),
  displayTransform: lodash__WEBPACK_IMPORTED_MODULE_7__.identity,
  saveTransform: function saveTransform(token) {
    return token ? token.trim() : '';
  },
  onChange: function onChange() {},
  onInputChange: function onInputChange() {},
  isBorderless: false,
  disabled: false,
  tokenizeOnSpace: false,
  messages: {
    added: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__.__)('Item added.'),
    removed: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__.__)('Item removed.'),
    remove: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__.__)('Remove item')
  }
};
/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_components__WEBPACK_IMPORTED_MODULE_14__.withSpokenMessages)((0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_11__.withInstanceId)(TokenMultiSelectControl)));

/***/ }),

/***/ "./src/vendors/token-multiselect-control/suggestions-list.js":
/*!*******************************************************************!*\
  !*** ./src/vendors/token-multiselect-control/suggestions-list.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var dom_scroll_into_view__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! dom-scroll-into-view */ "../node_modules/dom-scroll-into-view/dist-web/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! classnames */ "../node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__);





function _callSuper(t, o, e) { return o = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_3__["default"])(o), (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_2__["default"])(t, _isNativeReflectConstruct() ? Reflect.construct(o, e || [], (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_3__["default"])(t).constructor) : o.apply(t, e)); }
function _isNativeReflectConstruct() { try { var t = !Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); } catch (t) {} return (_isNativeReflectConstruct = function _isNativeReflectConstruct() { return !!t; })(); }
/**
 * External dependencies
 */




/**
 * WordPress dependencies
 */



var SuggestionsList = /*#__PURE__*/function (_Component) {
  function SuggestionsList() {
    var _this;
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, SuggestionsList);
    _this = _callSuper(this, SuggestionsList, arguments);
    _this.handleMouseDown = _this.handleMouseDown.bind(_this);
    _this.bindList = _this.bindList.bind(_this);
    return _this;
  }
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__["default"])(SuggestionsList, _Component);
  return (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(SuggestionsList, [{
    key: "componentDidUpdate",
    value: function componentDidUpdate() {
      var _this2 = this;
      // only have to worry about scrolling selected suggestion into view
      // when already expanded
      if (this.props.selectedIndex > -1 && this.props.scrollIntoView) {
        this.scrollingIntoView = true;
        (0,dom_scroll_into_view__WEBPACK_IMPORTED_MODULE_10__["default"])(this.list.children[this.props.selectedIndex], this.list, {
          onlyScrollIfNeeded: true
        });
        this.props.setTimeout(function () {
          _this2.scrollingIntoView = false;
        }, 100);
      }
    }
  }, {
    key: "bindList",
    value: function bindList(ref) {
      this.list = ref;
    }
  }, {
    key: "handleHover",
    value: function handleHover(suggestion) {
      var _this3 = this;
      return function () {
        if (!_this3.scrollingIntoView) {
          _this3.props.onHover(suggestion);
        }
      };
    }
  }, {
    key: "handleClick",
    value: function handleClick(suggestion) {
      var _this4 = this;
      return function () {
        _this4.props.onSelect(suggestion);
      };
    }
  }, {
    key: "handleMouseDown",
    value: function handleMouseDown(e) {
      // By preventing default here, we will not lose focus of <input> when clicking a suggestion
      e.preventDefault();
    }
  }, {
    key: "computeSuggestionMatch",
    value: function computeSuggestionMatch(suggestion) {
      var match = this.props.displayTransform(this.props.match || '').toLocaleLowerCase();
      if (match.length === 0) {
        return null;
      }
      suggestion = this.props.displayTransform(suggestion);
      var indexOfMatch = suggestion.toLocaleLowerCase().indexOf(match);
      return {
        suggestionBeforeMatch: suggestion.substring(0, indexOfMatch),
        suggestionMatch: suggestion.substring(indexOfMatch, indexOfMatch + match.length),
        suggestionAfterMatch: suggestion.substring(indexOfMatch + match.length)
      };
    }
  }, {
    key: "render",
    value: function render() {
      var _this5 = this;
      // We set `tabIndex` here because otherwise Firefox sets focus on this
      // div when tabbing off of the input in `TokenField` -- not really sure
      // why, since usually a div isn't focusable by default
      // TODO does this still apply now that it's a <ul> and not a <div>?
      return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("ul", {
        ref: this.bindList,
        className: "components-form-token-field__suggestions-list",
        id: "components-form-token-suggestions-".concat(this.props.instanceId),
        role: "listbox",
        children: (0,lodash__WEBPACK_IMPORTED_MODULE_5__.map)(this.props.suggestions, function (suggestion, index) {
          var match = _this5.computeSuggestionMatch(suggestion);
          var classeName = classnames__WEBPACK_IMPORTED_MODULE_6___default()('components-form-token-field__suggestion', {
            'is-selected': index === _this5.props.selectedIndex
          });

          /* eslint-disable jsx-a11y/click-events-have-key-events */
          return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("li", {
            id: "components-form-token-suggestions-".concat(_this5.props.instanceId, "-").concat(index),
            role: "option",
            className: classeName,
            onMouseDown: _this5.handleMouseDown,
            onClick: _this5.handleClick(suggestion),
            onMouseEnter: _this5.handleHover(suggestion),
            "aria-selected": index === _this5.props.selectedIndex,
            children: match ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)("span", {
              "aria-label": _this5.props.displayTransform(suggestion),
              children: [match.suggestionBeforeMatch, /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("strong", {
                className: "components-form-token-field__suggestion-match",
                children: match.suggestionMatch
              }), match.suggestionAfterMatch]
            }) : _this5.props.displayTransform(suggestion)
          }, _this5.props.displayTransform(suggestion));
          /* eslint-enable jsx-a11y/click-events-have-key-events */
        })
      });
    }
  }]);
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__.Component);
SuggestionsList.defaultProps = {
  match: '',
  onHover: function onHover() {},
  onSelect: function onSelect() {},
  suggestions: Object.freeze([])
};
/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_8__.withSafeTimeout)(SuggestionsList));

/***/ }),

/***/ "./src/vendors/token-multiselect-control/token-input.js":
/*!**************************************************************!*\
  !*** ./src/vendors/token-multiselect-control/token-input.js ***!
  \**************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_objectWithoutProperties__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/objectWithoutProperties */ "./node_modules/@babel/runtime/helpers/esm/objectWithoutProperties.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! classnames */ "../node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__);







var _excluded = ["value", "isExpanded", "instanceId", "selectedSuggestionIndex", "className"];
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _callSuper(t, o, e) { return o = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(o), (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__["default"])(t, _isNativeReflectConstruct() ? Reflect.construct(o, e || [], (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__["default"])(t).constructor) : o.apply(t, e)); }
function _isNativeReflectConstruct() { try { var t = !Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); } catch (t) {} return (_isNativeReflectConstruct = function _isNativeReflectConstruct() { return !!t; })(); }
/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */


var TokenInput = /*#__PURE__*/function (_Component) {
  function TokenInput() {
    var _this;
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2__["default"])(this, TokenInput);
    _this = _callSuper(this, TokenInput, arguments);
    _this.onChange = _this.onChange.bind(_this);
    _this.bindInput = _this.bindInput.bind(_this);
    return _this;
  }
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_6__["default"])(TokenInput, _Component);
  return (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3__["default"])(TokenInput, [{
    key: "focus",
    value: function focus() {
      this.input.focus();
    }
  }, {
    key: "hasFocus",
    value: function hasFocus() {
      return this.input === this.input.ownerDocument.activeElement;
    }
  }, {
    key: "bindInput",
    value: function bindInput(ref) {
      this.input = ref;
    }
  }, {
    key: "onChange",
    value: function onChange(event) {
      this.props.onChange({
        value: event.target.value
      });
    }
  }, {
    key: "render",
    value: function render() {
      var _this$props = this.props,
        value = _this$props.value,
        isExpanded = _this$props.isExpanded,
        instanceId = _this$props.instanceId,
        selectedSuggestionIndex = _this$props.selectedSuggestionIndex,
        className = _this$props.className,
        props = (0,_babel_runtime_helpers_objectWithoutProperties__WEBPACK_IMPORTED_MODULE_1__["default"])(_this$props, _excluded);
      var size = value ? value.length + 1 : 0;
      return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("input", _objectSpread(_objectSpread({
        ref: this.bindInput,
        id: "components-form-token-input-".concat(instanceId),
        type: "text"
      }, props), {}, {
        value: value || '',
        onChange: this.onChange,
        size: size,
        className: classnames__WEBPACK_IMPORTED_MODULE_7___default()(className, 'components-form-token-field__input'),
        autoComplete: "off",
        role: "combobox",
        "aria-expanded": isExpanded,
        "aria-autocomplete": "list",
        "aria-owns": isExpanded ? "components-form-token-suggestions-".concat(instanceId) : undefined,
        "aria-activedescendant": selectedSuggestionIndex !== -1 ? "components-form-token-suggestions-".concat(instanceId, "-").concat(selectedSuggestionIndex) : undefined,
        "aria-describedby": "components-form-token-suggestions-howto-".concat(instanceId)
      }));
    }
  }]);
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.Component);
/* harmony default export */ __webpack_exports__["default"] = (TokenInput);

/***/ }),

/***/ "./src/vendors/token-multiselect-control/token.js":
/*!********************************************************!*\
  !*** ./src/vendors/token-multiselect-control/token.js ***!
  \********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ Token; }
/* harmony export */ });
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! classnames */ "../node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/icons */ "./node_modules/@wordpress/icons/build-module/library/close-small.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__);
/**
 * External dependencies
 */



/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */


function Token(_ref) {
  var value = _ref.value,
    label = _ref.label,
    status = _ref.status,
    title = _ref.title,
    displayTransform = _ref.displayTransform,
    _ref$isBorderless = _ref.isBorderless,
    isBorderless = _ref$isBorderless === void 0 ? false : _ref$isBorderless,
    _ref$disabled = _ref.disabled,
    disabled = _ref$disabled === void 0 ? false : _ref$disabled,
    _ref$onClickRemove = _ref.onClickRemove,
    onClickRemove = _ref$onClickRemove === void 0 ? lodash__WEBPACK_IMPORTED_MODULE_1__.noop : _ref$onClickRemove,
    onMouseEnter = _ref.onMouseEnter,
    onMouseLeave = _ref.onMouseLeave,
    messages = _ref.messages,
    termPosition = _ref.termPosition,
    termsCount = _ref.termsCount;
  var instanceId = (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__.useInstanceId)(Token);
  var tokenClasses = classnames__WEBPACK_IMPORTED_MODULE_0___default()('components-form-token-field__token', {
    'is-error': 'error' === status,
    'is-success': 'success' === status,
    'is-validating': 'validating' === status,
    'is-borderless': isBorderless,
    'is-disabled': disabled
  });
  var onClick = function onClick() {
    return onClickRemove({
      value: value
    });
  };
  var transformedValue = displayTransform(label);
  var termPositionAndCount = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.sprintf)(/* translators: 1: term name, 2: term position in a set of terms, 3: total term set count. */
  (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('%1$s (%2$s of %3$s)'), transformedValue, termPosition, termsCount);
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("span", {
    className: tokenClasses,
    onMouseEnter: onMouseEnter,
    onMouseLeave: onMouseLeave,
    title: title,
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("span", {
      className: "components-form-token-field__token-text",
      id: "components-form-token-field__token-text-".concat(instanceId),
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.VisuallyHidden, {
        as: "span",
        children: termPositionAndCount
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("span", {
        "aria-hidden": "true",
        children: transformedValue
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Button, {
      className: "components-form-token-field__remove-token",
      icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_6__["default"],
      onClick: !disabled && onClick,
      label: messages.remove,
      "aria-describedby": "components-form-token-field__token-text-".concat(instanceId)
    })]
  });
}

/***/ }),

/***/ "../node_modules/classnames/index.js":
/*!*******************************************!*\
  !*** ../node_modules/classnames/index.js ***!
  \*******************************************/
/***/ (function(module, exports) {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
	Copyright (c) 2018 Jed Watson.
	Licensed under the MIT License (MIT), see
	http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;
	var nativeCodeString = '[native code]';

	function classNames() {
		var classes = [];

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (!arg) continue;

			var argType = typeof arg;

			if (argType === 'string' || argType === 'number') {
				classes.push(arg);
			} else if (Array.isArray(arg)) {
				if (arg.length) {
					var inner = classNames.apply(null, arg);
					if (inner) {
						classes.push(inner);
					}
				}
			} else if (argType === 'object') {
				if (arg.toString !== Object.prototype.toString && !arg.toString.toString().includes('[native code]')) {
					classes.push(arg.toString());
					continue;
				}

				for (var key in arg) {
					if (hasOwn.call(arg, key) && arg[key]) {
						classes.push(key);
					}
				}
			}
		}

		return classes.join(' ');
	}

	if ( true && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ }),

/***/ "../node_modules/dom-scroll-into-view/dist-web/index.js":
/*!**************************************************************!*\
  !*** ../node_modules/dom-scroll-into-view/dist-web/index.js ***!
  \**************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _typeof(obj) {
  if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
    _typeof = function (obj) {
      return typeof obj;
    };
  } else {
    _typeof = function (obj) {
      return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
    };
  }

  return _typeof(obj);
}

function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

function ownKeys(object, enumerableOnly) {
  var keys = Object.keys(object);

  if (Object.getOwnPropertySymbols) {
    var symbols = Object.getOwnPropertySymbols(object);
    if (enumerableOnly) symbols = symbols.filter(function (sym) {
      return Object.getOwnPropertyDescriptor(object, sym).enumerable;
    });
    keys.push.apply(keys, symbols);
  }

  return keys;
}

function _objectSpread2(target) {
  for (var i = 1; i < arguments.length; i++) {
    var source = arguments[i] != null ? arguments[i] : {};

    if (i % 2) {
      ownKeys(source, true).forEach(function (key) {
        _defineProperty(target, key, source[key]);
      });
    } else if (Object.getOwnPropertyDescriptors) {
      Object.defineProperties(target, Object.getOwnPropertyDescriptors(source));
    } else {
      ownKeys(source).forEach(function (key) {
        Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key));
      });
    }
  }

  return target;
}

var RE_NUM = /[\-+]?(?:\d*\.|)\d+(?:[eE][\-+]?\d+|)/.source;

function getClientPosition(elem) {
  var box;
  var x;
  var y;
  var doc = elem.ownerDocument;
  var body = doc.body;
  var docElem = doc && doc.documentElement; // 根据 GBS 最新数据，A-Grade Browsers 都已支持 getBoundingClientRect 方法，不用再考虑传统的实现方式

  box = elem.getBoundingClientRect(); // 注：jQuery 还考虑减去 docElem.clientLeft/clientTop
  // 但测试发现，这样反而会导致当 html 和 body 有边距/边框样式时，获取的值不正确
  // 此外，ie6 会忽略 html 的 margin 值，幸运地是没有谁会去设置 html 的 margin

  x = box.left;
  y = box.top; // In IE, most of the time, 2 extra pixels are added to the top and left
  // due to the implicit 2-pixel inset border.  In IE6/7 quirks mode and
  // IE6 standards mode, this border can be overridden by setting the
  // document element's border to zero -- thus, we cannot rely on the
  // offset always being 2 pixels.
  // In quirks mode, the offset can be determined by querying the body's
  // clientLeft/clientTop, but in standards mode, it is found by querying
  // the document element's clientLeft/clientTop.  Since we already called
  // getClientBoundingRect we have already forced a reflow, so it is not
  // too expensive just to query them all.
  // ie 下应该减去窗口的边框吧，毕竟默认 absolute 都是相对窗口定位的
  // 窗口边框标准是设 documentElement ,quirks 时设置 body
  // 最好禁止在 body 和 html 上边框 ，但 ie < 9 html 默认有 2px ，减去
  // 但是非 ie 不可能设置窗口边框，body html 也不是窗口 ,ie 可以通过 html,body 设置
  // 标准 ie 下 docElem.clientTop 就是 border-top
  // ie7 html 即窗口边框改变不了。永远为 2
  // 但标准 firefox/chrome/ie9 下 docElem.clientTop 是窗口边框，即使设了 border-top 也为 0

  x -= docElem.clientLeft || body.clientLeft || 0;
  y -= docElem.clientTop || body.clientTop || 0;
  return {
    left: x,
    top: y
  };
}

function getScroll(w, top) {
  var ret = w["page".concat(top ? 'Y' : 'X', "Offset")];
  var method = "scroll".concat(top ? 'Top' : 'Left');

  if (typeof ret !== 'number') {
    var d = w.document; // ie6,7,8 standard mode

    ret = d.documentElement[method];

    if (typeof ret !== 'number') {
      // quirks mode
      ret = d.body[method];
    }
  }

  return ret;
}

function getScrollLeft(w) {
  return getScroll(w);
}

function getScrollTop(w) {
  return getScroll(w, true);
}

function getOffset(el) {
  var pos = getClientPosition(el);
  var doc = el.ownerDocument;
  var w = doc.defaultView || doc.parentWindow;
  pos.left += getScrollLeft(w);
  pos.top += getScrollTop(w);
  return pos;
}

function _getComputedStyle(elem, name, computedStyle_) {
  var val = '';
  var d = elem.ownerDocument;
  var computedStyle = computedStyle_ || d.defaultView.getComputedStyle(elem, null); // https://github.com/kissyteam/kissy/issues/61

  if (computedStyle) {
    val = computedStyle.getPropertyValue(name) || computedStyle[name];
  }

  return val;
}

var _RE_NUM_NO_PX = new RegExp("^(".concat(RE_NUM, ")(?!px)[a-z%]+$"), 'i');

var RE_POS = /^(top|right|bottom|left)$/;
var CURRENT_STYLE = 'currentStyle';
var RUNTIME_STYLE = 'runtimeStyle';
var LEFT = 'left';
var PX = 'px';

function _getComputedStyleIE(elem, name) {
  // currentStyle maybe null
  // http://msdn.microsoft.com/en-us/library/ms535231.aspx
  var ret = elem[CURRENT_STYLE] && elem[CURRENT_STYLE][name]; // 当 width/height 设置为百分比时，通过 pixelLeft 方式转换的 width/height 值
  // 一开始就处理了! CUSTOM_STYLE.height,CUSTOM_STYLE.width ,cssHook 解决@2011-08-19
  // 在 ie 下不对，需要直接用 offset 方式
  // borderWidth 等值也有问题，但考虑到 borderWidth 设为百分比的概率很小，这里就不考虑了
  // From the awesome hack by Dean Edwards
  // http://erik.eae.net/archives/2007/07/27/18.54.15/#comment-102291
  // If we're not dealing with a regular pixel number
  // but a number that has a weird ending, we need to convert it to pixels
  // exclude left right for relativity

  if (_RE_NUM_NO_PX.test(ret) && !RE_POS.test(name)) {
    // Remember the original values
    var style = elem.style;
    var left = style[LEFT];
    var rsLeft = elem[RUNTIME_STYLE][LEFT]; // prevent flashing of content

    elem[RUNTIME_STYLE][LEFT] = elem[CURRENT_STYLE][LEFT]; // Put in the new values to get a computed value out

    style[LEFT] = name === 'fontSize' ? '1em' : ret || 0;
    ret = style.pixelLeft + PX; // Revert the changed values

    style[LEFT] = left;
    elem[RUNTIME_STYLE][LEFT] = rsLeft;
  }

  return ret === '' ? 'auto' : ret;
}

var getComputedStyleX;

if (typeof window !== 'undefined') {
  getComputedStyleX = window.getComputedStyle ? _getComputedStyle : _getComputedStyleIE;
}

function each(arr, fn) {
  for (var i = 0; i < arr.length; i++) {
    fn(arr[i]);
  }
}

function isBorderBoxFn(elem) {
  return getComputedStyleX(elem, 'boxSizing') === 'border-box';
}

var BOX_MODELS = ['margin', 'border', 'padding'];
var CONTENT_INDEX = -1;
var PADDING_INDEX = 2;
var BORDER_INDEX = 1;
var MARGIN_INDEX = 0;

function swap(elem, options, callback) {
  var old = {};
  var style = elem.style;
  var name; // Remember the old values, and insert the new ones

  for (name in options) {
    if (options.hasOwnProperty(name)) {
      old[name] = style[name];
      style[name] = options[name];
    }
  }

  callback.call(elem); // Revert the old values

  for (name in options) {
    if (options.hasOwnProperty(name)) {
      style[name] = old[name];
    }
  }
}

function getPBMWidth(elem, props, which) {
  var value = 0;
  var prop;
  var j;
  var i;

  for (j = 0; j < props.length; j++) {
    prop = props[j];

    if (prop) {
      for (i = 0; i < which.length; i++) {
        var cssProp = void 0;

        if (prop === 'border') {
          cssProp = "".concat(prop + which[i], "Width");
        } else {
          cssProp = prop + which[i];
        }

        value += parseFloat(getComputedStyleX(elem, cssProp)) || 0;
      }
    }
  }

  return value;
}
/**
 * A crude way of determining if an object is a window
 * @member util
 */


function isWindow(obj) {
  // must use == for ie8

  /* eslint eqeqeq:0 */
  return obj != null && obj == obj.window;
}

var domUtils = {};
each(['Width', 'Height'], function (name) {
  domUtils["doc".concat(name)] = function (refWin) {
    var d = refWin.document;
    return Math.max( // firefox chrome documentElement.scrollHeight< body.scrollHeight
    // ie standard mode : documentElement.scrollHeight> body.scrollHeight
    d.documentElement["scroll".concat(name)], // quirks : documentElement.scrollHeight 最大等于可视窗口多一点？
    d.body["scroll".concat(name)], domUtils["viewport".concat(name)](d));
  };

  domUtils["viewport".concat(name)] = function (win) {
    // pc browser includes scrollbar in window.innerWidth
    var prop = "client".concat(name);
    var doc = win.document;
    var body = doc.body;
    var documentElement = doc.documentElement;
    var documentElementProp = documentElement[prop]; // 标准模式取 documentElement
    // backcompat 取 body

    return doc.compatMode === 'CSS1Compat' && documentElementProp || body && body[prop] || documentElementProp;
  };
});
/*
 得到元素的大小信息
 @param elem
 @param name
 @param {String} [extra]  'padding' : (css width) + padding
 'border' : (css width) + padding + border
 'margin' : (css width) + padding + border + margin
 */

function getWH(elem, name, extra) {
  if (isWindow(elem)) {
    return name === 'width' ? domUtils.viewportWidth(elem) : domUtils.viewportHeight(elem);
  } else if (elem.nodeType === 9) {
    return name === 'width' ? domUtils.docWidth(elem) : domUtils.docHeight(elem);
  }

  var which = name === 'width' ? ['Left', 'Right'] : ['Top', 'Bottom'];
  var borderBoxValue = name === 'width' ? elem.offsetWidth : elem.offsetHeight;
  var computedStyle = getComputedStyleX(elem);
  var isBorderBox = isBorderBoxFn(elem);
  var cssBoxValue = 0;

  if (borderBoxValue == null || borderBoxValue <= 0) {
    borderBoxValue = undefined; // Fall back to computed then un computed css if necessary

    cssBoxValue = getComputedStyleX(elem, name);

    if (cssBoxValue == null || Number(cssBoxValue) < 0) {
      cssBoxValue = elem.style[name] || 0;
    } // Normalize '', auto, and prepare for extra


    cssBoxValue = parseFloat(cssBoxValue) || 0;
  }

  if (extra === undefined) {
    extra = isBorderBox ? BORDER_INDEX : CONTENT_INDEX;
  }

  var borderBoxValueOrIsBorderBox = borderBoxValue !== undefined || isBorderBox;
  var val = borderBoxValue || cssBoxValue;

  if (extra === CONTENT_INDEX) {
    if (borderBoxValueOrIsBorderBox) {
      return val - getPBMWidth(elem, ['border', 'padding'], which);
    }

    return cssBoxValue;
  }

  if (borderBoxValueOrIsBorderBox) {
    var padding = extra === PADDING_INDEX ? -getPBMWidth(elem, ['border'], which) : getPBMWidth(elem, ['margin'], which);
    return val + (extra === BORDER_INDEX ? 0 : padding);
  }

  return cssBoxValue + getPBMWidth(elem, BOX_MODELS.slice(extra), which);
}

var cssShow = {
  position: 'absolute',
  visibility: 'hidden',
  display: 'block'
}; // fix #119 : https://github.com/kissyteam/kissy/issues/119

function getWHIgnoreDisplay(elem) {
  var val;
  var args = arguments; // in case elem is window
  // elem.offsetWidth === undefined

  if (elem.offsetWidth !== 0) {
    val = getWH.apply(undefined, args);
  } else {
    swap(elem, cssShow, function () {
      val = getWH.apply(undefined, args);
    });
  }

  return val;
}

function css(el, name, v) {
  var value = v;

  if (_typeof(name) === 'object') {
    for (var i in name) {
      if (name.hasOwnProperty(i)) {
        css(el, i, name[i]);
      }
    }

    return undefined;
  }

  if (typeof value !== 'undefined') {
    if (typeof value === 'number') {
      value += 'px';
    }

    el.style[name] = value;
    return undefined;
  }

  return getComputedStyleX(el, name);
}

each(['width', 'height'], function (name) {
  var first = name.charAt(0).toUpperCase() + name.slice(1);

  domUtils["outer".concat(first)] = function (el, includeMargin) {
    return el && getWHIgnoreDisplay(el, name, includeMargin ? MARGIN_INDEX : BORDER_INDEX);
  };

  var which = name === 'width' ? ['Left', 'Right'] : ['Top', 'Bottom'];

  domUtils[name] = function (elem, val) {
    if (val !== undefined) {
      if (elem) {
        var computedStyle = getComputedStyleX(elem);
        var isBorderBox = isBorderBoxFn(elem);

        if (isBorderBox) {
          val += getPBMWidth(elem, ['padding', 'border'], which);
        }

        return css(elem, name, val);
      }

      return undefined;
    }

    return elem && getWHIgnoreDisplay(elem, name, CONTENT_INDEX);
  };
}); // 设置 elem 相对 elem.ownerDocument 的坐标

function setOffset(elem, offset) {
  // set position first, in-case top/left are set even on static elem
  if (css(elem, 'position') === 'static') {
    elem.style.position = 'relative';
  }

  var old = getOffset(elem);
  var ret = {};
  var current;
  var key;

  for (key in offset) {
    if (offset.hasOwnProperty(key)) {
      current = parseFloat(css(elem, key)) || 0;
      ret[key] = current + offset[key] - old[key];
    }
  }

  css(elem, ret);
}

var util = _objectSpread2({
  getWindow: function getWindow(node) {
    var doc = node.ownerDocument || node;
    return doc.defaultView || doc.parentWindow;
  },
  offset: function offset(el, value) {
    if (typeof value !== 'undefined') {
      setOffset(el, value);
    } else {
      return getOffset(el);
    }
  },
  isWindow: isWindow,
  each: each,
  css: css,
  clone: function clone(obj) {
    var ret = {};

    for (var i in obj) {
      if (obj.hasOwnProperty(i)) {
        ret[i] = obj[i];
      }
    }

    var overflow = obj.overflow;

    if (overflow) {
      for (var _i in obj) {
        if (obj.hasOwnProperty(_i)) {
          ret.overflow[_i] = obj.overflow[_i];
        }
      }
    }

    return ret;
  },
  scrollLeft: function scrollLeft(w, v) {
    if (isWindow(w)) {
      if (v === undefined) {
        return getScrollLeft(w);
      }

      window.scrollTo(v, getScrollTop(w));
    } else {
      if (v === undefined) {
        return w.scrollLeft;
      }

      w.scrollLeft = v;
    }
  },
  scrollTop: function scrollTop(w, v) {
    if (isWindow(w)) {
      if (v === undefined) {
        return getScrollTop(w);
      }

      window.scrollTo(getScrollLeft(w), v);
    } else {
      if (v === undefined) {
        return w.scrollTop;
      }

      w.scrollTop = v;
    }
  },
  viewportWidth: 0,
  viewportHeight: 0
}, domUtils);

function scrollIntoView(elem, container, config) {
  config = config || {}; // document 归一化到 window

  if (container.nodeType === 9) {
    container = util.getWindow(container);
  }

  var allowHorizontalScroll = config.allowHorizontalScroll;
  var onlyScrollIfNeeded = config.onlyScrollIfNeeded;
  var alignWithTop = config.alignWithTop;
  var alignWithLeft = config.alignWithLeft;
  var offsetTop = config.offsetTop || 0;
  var offsetLeft = config.offsetLeft || 0;
  var offsetBottom = config.offsetBottom || 0;
  var offsetRight = config.offsetRight || 0;
  allowHorizontalScroll = allowHorizontalScroll === undefined ? true : allowHorizontalScroll;
  var isWin = util.isWindow(container);
  var elemOffset = util.offset(elem);
  var eh = util.outerHeight(elem);
  var ew = util.outerWidth(elem);
  var containerOffset;
  var ch;
  var cw;
  var containerScroll;
  var diffTop;
  var diffBottom;
  var win;
  var winScroll;
  var ww;
  var wh;

  if (isWin) {
    win = container;
    wh = util.height(win);
    ww = util.width(win);
    winScroll = {
      left: util.scrollLeft(win),
      top: util.scrollTop(win)
    }; // elem 相对 container 可视视窗的距离

    diffTop = {
      left: elemOffset.left - winScroll.left - offsetLeft,
      top: elemOffset.top - winScroll.top - offsetTop
    };
    diffBottom = {
      left: elemOffset.left + ew - (winScroll.left + ww) + offsetRight,
      top: elemOffset.top + eh - (winScroll.top + wh) + offsetBottom
    };
    containerScroll = winScroll;
  } else {
    containerOffset = util.offset(container);
    ch = container.clientHeight;
    cw = container.clientWidth;
    containerScroll = {
      left: container.scrollLeft,
      top: container.scrollTop
    }; // elem 相对 container 可视视窗的距离
    // 注意边框, offset 是边框到根节点

    diffTop = {
      left: elemOffset.left - (containerOffset.left + (parseFloat(util.css(container, 'borderLeftWidth')) || 0)) - offsetLeft,
      top: elemOffset.top - (containerOffset.top + (parseFloat(util.css(container, 'borderTopWidth')) || 0)) - offsetTop
    };
    diffBottom = {
      left: elemOffset.left + ew - (containerOffset.left + cw + (parseFloat(util.css(container, 'borderRightWidth')) || 0)) + offsetRight,
      top: elemOffset.top + eh - (containerOffset.top + ch + (parseFloat(util.css(container, 'borderBottomWidth')) || 0)) + offsetBottom
    };
  }

  if (diffTop.top < 0 || diffBottom.top > 0) {
    // 强制向上
    if (alignWithTop === true) {
      util.scrollTop(container, containerScroll.top + diffTop.top);
    } else if (alignWithTop === false) {
      util.scrollTop(container, containerScroll.top + diffBottom.top);
    } else {
      // 自动调整
      if (diffTop.top < 0) {
        util.scrollTop(container, containerScroll.top + diffTop.top);
      } else {
        util.scrollTop(container, containerScroll.top + diffBottom.top);
      }
    }
  } else {
    if (!onlyScrollIfNeeded) {
      alignWithTop = alignWithTop === undefined ? true : !!alignWithTop;

      if (alignWithTop) {
        util.scrollTop(container, containerScroll.top + diffTop.top);
      } else {
        util.scrollTop(container, containerScroll.top + diffBottom.top);
      }
    }
  }

  if (allowHorizontalScroll) {
    if (diffTop.left < 0 || diffBottom.left > 0) {
      // 强制向上
      if (alignWithLeft === true) {
        util.scrollLeft(container, containerScroll.left + diffTop.left);
      } else if (alignWithLeft === false) {
        util.scrollLeft(container, containerScroll.left + diffBottom.left);
      } else {
        // 自动调整
        if (diffTop.left < 0) {
          util.scrollLeft(container, containerScroll.left + diffTop.left);
        } else {
          util.scrollLeft(container, containerScroll.left + diffBottom.left);
        }
      }
    } else {
      if (!onlyScrollIfNeeded) {
        alignWithLeft = alignWithLeft === undefined ? true : !!alignWithLeft;

        if (alignWithLeft) {
          util.scrollLeft(container, containerScroll.left + diffTop.left);
        } else {
          util.scrollLeft(container, containerScroll.left + diffBottom.left);
        }
      }
    }
  }
}

/* harmony default export */ __webpack_exports__["default"] = (scrollIntoView);
//# sourceMappingURL=index.js.map


/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ (function(module) {

"use strict";
module.exports = window["ReactJSXRuntime"];

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/***/ (function(module) {

"use strict";
module.exports = window["lodash"];

/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/compose":
/*!*********************************!*\
  !*** external ["wp","compose"] ***!
  \*********************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["compose"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/is-shallow-equal":
/*!****************************************!*\
  !*** external ["wp","isShallowEqual"] ***!
  \****************************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["isShallowEqual"];

/***/ }),

/***/ "@wordpress/keycodes":
/*!**********************************!*\
  !*** external ["wp","keycodes"] ***!
  \**********************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["keycodes"];

/***/ }),

/***/ "@wordpress/primitives":
/*!************************************!*\
  !*** external ["wp","primitives"] ***!
  \************************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["primitives"];

/***/ }),

/***/ "@wordpress/server-side-render":
/*!******************************************!*\
  !*** external ["wp","serverSideRender"] ***!
  \******************************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["serverSideRender"];

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js ***!
  \*********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
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

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _arrayWithHoles; }
/* harmony export */ });
function _arrayWithHoles(r) {
  if (Array.isArray(r)) return r;
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js ***!
  \**********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _arrayWithoutHoles; }
/* harmony export */ });
/* harmony import */ var _arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayLikeToArray.js */ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js");

function _arrayWithoutHoles(r) {
  if (Array.isArray(r)) return (0,_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(r);
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js":
/*!**************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js ***!
  \**************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _assertThisInitialized; }
/* harmony export */ });
function _assertThisInitialized(e) {
  if (void 0 === e) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
  return e;
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/classCallCheck.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _classCallCheck; }
/* harmony export */ });
function _classCallCheck(a, n) {
  if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function");
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/createClass.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/createClass.js ***!
  \****************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _createClass; }
/* harmony export */ });
/* harmony import */ var _toPropertyKey_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./toPropertyKey.js */ "./node_modules/@babel/runtime/helpers/esm/toPropertyKey.js");

function _defineProperties(e, r) {
  for (var t = 0; t < r.length; t++) {
    var o = r[t];
    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, (0,_toPropertyKey_js__WEBPACK_IMPORTED_MODULE_0__["default"])(o.key), o);
  }
}
function _createClass(e, r, t) {
  return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", {
    writable: !1
  }), e;
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/defineProperty.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
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

/***/ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _getPrototypeOf; }
/* harmony export */ });
function _getPrototypeOf(t) {
  return _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function (t) {
    return t.__proto__ || Object.getPrototypeOf(t);
  }, _getPrototypeOf(t);
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/inherits.js":
/*!*************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/inherits.js ***!
  \*************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _inherits; }
/* harmony export */ });
/* harmony import */ var _setPrototypeOf_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./setPrototypeOf.js */ "./node_modules/@babel/runtime/helpers/esm/setPrototypeOf.js");

function _inherits(t, e) {
  if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function");
  t.prototype = Object.create(e && e.prototype, {
    constructor: {
      value: t,
      writable: !0,
      configurable: !0
    }
  }), Object.defineProperty(t, "prototype", {
    writable: !1
  }), e && (0,_setPrototypeOf_js__WEBPACK_IMPORTED_MODULE_0__["default"])(t, e);
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/iterableToArray.js":
/*!********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/iterableToArray.js ***!
  \********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _iterableToArray; }
/* harmony export */ });
function _iterableToArray(r) {
  if ("undefined" != typeof Symbol && null != r[Symbol.iterator] || null != r["@@iterator"]) return Array.from(r);
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js ***!
  \*************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
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

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _nonIterableRest; }
/* harmony export */ });
function _nonIterableRest() {
  throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js ***!
  \**********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _nonIterableSpread; }
/* harmony export */ });
function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/objectWithoutProperties.js":
/*!****************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/objectWithoutProperties.js ***!
  \****************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _objectWithoutProperties; }
/* harmony export */ });
/* harmony import */ var _objectWithoutPropertiesLoose_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./objectWithoutPropertiesLoose.js */ "./node_modules/@babel/runtime/helpers/esm/objectWithoutPropertiesLoose.js");

function _objectWithoutProperties(e, t) {
  if (null == e) return {};
  var o,
    r,
    i = (0,_objectWithoutPropertiesLoose_js__WEBPACK_IMPORTED_MODULE_0__["default"])(e, t);
  if (Object.getOwnPropertySymbols) {
    var s = Object.getOwnPropertySymbols(e);
    for (r = 0; r < s.length; r++) o = s[r], t.includes(o) || {}.propertyIsEnumerable.call(e, o) && (i[o] = e[o]);
  }
  return i;
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/objectWithoutPropertiesLoose.js":
/*!*********************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/objectWithoutPropertiesLoose.js ***!
  \*********************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _objectWithoutPropertiesLoose; }
/* harmony export */ });
function _objectWithoutPropertiesLoose(r, e) {
  if (null == r) return {};
  var t = {};
  for (var n in r) if ({}.hasOwnProperty.call(r, n)) {
    if (e.includes(n)) continue;
    t[n] = r[n];
  }
  return t;
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js":
/*!******************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js ***!
  \******************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _possibleConstructorReturn; }
/* harmony export */ });
/* harmony import */ var _typeof_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./typeof.js */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var _assertThisInitialized_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./assertThisInitialized.js */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");


function _possibleConstructorReturn(t, e) {
  if (e && ("object" == (0,_typeof_js__WEBPACK_IMPORTED_MODULE_0__["default"])(e) || "function" == typeof e)) return e;
  if (void 0 !== e) throw new TypeError("Derived constructors may only return object or undefined");
  return (0,_assertThisInitialized_js__WEBPACK_IMPORTED_MODULE_1__["default"])(t);
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/setPrototypeOf.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/setPrototypeOf.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _setPrototypeOf; }
/* harmony export */ });
function _setPrototypeOf(t, e) {
  return _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function (t, e) {
    return t.__proto__ = e, t;
  }, _setPrototypeOf(t, e);
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/slicedToArray.js ***!
  \******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
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

/***/ "./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js ***!
  \**********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _toConsumableArray; }
/* harmony export */ });
/* harmony import */ var _arrayWithoutHoles_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayWithoutHoles.js */ "./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js");
/* harmony import */ var _iterableToArray_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./iterableToArray.js */ "./node_modules/@babel/runtime/helpers/esm/iterableToArray.js");
/* harmony import */ var _unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./unsupportedIterableToArray.js */ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js");
/* harmony import */ var _nonIterableSpread_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./nonIterableSpread.js */ "./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js");




function _toConsumableArray(r) {
  return (0,_arrayWithoutHoles_js__WEBPACK_IMPORTED_MODULE_0__["default"])(r) || (0,_iterableToArray_js__WEBPACK_IMPORTED_MODULE_1__["default"])(r) || (0,_unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__["default"])(r) || (0,_nonIterableSpread_js__WEBPACK_IMPORTED_MODULE_3__["default"])();
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/toPrimitive.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/toPrimitive.js ***!
  \****************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
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

"use strict";
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

"use strict";
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

"use strict";
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

/***/ "./src/listing-form/block.json":
/*!*************************************!*\
  !*** ./src/listing-form/block.json ***!
  \*************************************/
/***/ (function(module) {

"use strict";
module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"category":"directorist-blocks-collection","version":"1.0.0","name":"directorist/add-listing","title":"Add Listing Form","description":"Create a listing entry form.","keywords":["add listing","create listing","listing form"],"attributes":{"directory_type":{"type":"string","default":""}},"editorScript":["file:./index.js","directorist-select2-script","directorist-add-listing"]}');

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
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
!function() {
"use strict";
/*!***********************************!*\
  !*** ./src/listing-form/index.js ***!
  \***********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_server_side_render__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/server-side-render */ "@wordpress/server-side-render");
/* harmony import */ var _wordpress_server_side_render__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_server_side_render__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _controls__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./../controls */ "./src/controls.js");
/* harmony import */ var _functions__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./../functions */ "./src/functions.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./block.json */ "./src/listing-form/block.json");
/* harmony import */ var _logo__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./../logo */ "./src/logo.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__);


function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }











var Placeholder = function Placeholder() {
  return (0,_functions__WEBPACK_IMPORTED_MODULE_9__.getPlaceholder)('add-listing');
};
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_2__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_10__.name, {
  icon: (0,_logo__WEBPACK_IMPORTED_MODULE_11__["default"])(),
  transforms: {
    from: [{
      type: 'shortcode',
      tag: 'directorist_add_listing',
      attributes: {}
    }]
  },
  edit: function edit(_ref) {
    var attributes = _ref.attributes,
      setAttributes = _ref.setAttributes;
    var _useState = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.useState)(true),
      _useState2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1__["default"])(_useState, 2),
      shouldRender = _useState2[0],
      setShouldRender = _useState2[1];
    var oldTypes = attributes.directory_type ? attributes.directory_type.split(',') : [];
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsxs)(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__.Fragment, {
      children: [(0,_functions__WEBPACK_IMPORTED_MODULE_9__.isMultiDirectoryEnabled)() && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.InspectorControls, {
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__.PanelBody, {
          title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('General', 'directorist'),
          initialOpen: true,
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_controls__WEBPACK_IMPORTED_MODULE_8__.TypesControl, {
            shouldRender: shouldRender,
            selected: oldTypes,
            showDefault: false,
            onChange: function onChange(types) {
              setAttributes({
                directory_type: types.join(',')
              });
              setShouldRender(false);
            }
          })
        })
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)("div", _objectSpread(_objectSpread({}, (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.useBlockProps)({
        className: 'directorist-content-active directorist-w-100'
      })), {}, {
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)((_wordpress_server_side_render__WEBPACK_IMPORTED_MODULE_4___default()), {
          block: _block_json__WEBPACK_IMPORTED_MODULE_10__.name,
          attributes: attributes,
          LoadingResponsePlaceholder: Placeholder
        })
      }))]
    });
  }
});
}();
/******/ })()
;
//# sourceMappingURL=index.js.map