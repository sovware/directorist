/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/js/global/components/conditional-logic.js":
/*!**************************************************************!*\
  !*** ./assets/src/js/global/components/conditional-logic.js ***!
  \**************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   applyConditionalLogic: function() { return /* reexport safe */ _conditional_logic_init_js__WEBPACK_IMPORTED_MODULE_2__.applyConditionalLogic; },
/* harmony export */   evaluateConditionalLogic: function() { return /* reexport safe */ _conditional_logic_evaluate_js__WEBPACK_IMPORTED_MODULE_1__.evaluateConditionalLogic; },
/* harmony export */   getFieldValue: function() { return /* reexport safe */ _conditional_logic_get_field_value_js__WEBPACK_IMPORTED_MODULE_0__.getFieldValue; },
/* harmony export */   initConditionalLogic: function() { return /* reexport safe */ _conditional_logic_init_js__WEBPACK_IMPORTED_MODULE_2__.initConditionalLogic; },
/* harmony export */   updateCategoryFieldLabel: function() { return /* reexport safe */ _conditional_logic_init_js__WEBPACK_IMPORTED_MODULE_2__.updateCategoryFieldLabel; },
/* harmony export */   watchFieldChanges: function() { return /* reexport safe */ _conditional_logic_init_js__WEBPACK_IMPORTED_MODULE_2__.watchFieldChanges; }
/* harmony export */ });
/* harmony import */ var _conditional_logic_get_field_value_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./conditional-logic/get-field-value.js */ "./assets/src/js/global/components/conditional-logic/get-field-value.js");
/* harmony import */ var _conditional_logic_evaluate_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./conditional-logic/evaluate.js */ "./assets/src/js/global/components/conditional-logic/evaluate.js");
/* harmony import */ var _conditional_logic_init_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./conditional-logic/init.js */ "./assets/src/js/global/components/conditional-logic/init.js");
/**
 * Conditional Logic Evaluation for Frontend Form
 * Combines all submodules and re-exports for consumers
 */





/***/ }),

/***/ "./assets/src/js/global/components/conditional-logic/depends-on-field.js":
/*!*******************************************************************************!*\
  !*** ./assets/src/js/global/components/conditional-logic/depends-on-field.js ***!
  \*******************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   fieldDependsOnChange: function() { return /* binding */ fieldDependsOnChange; }
/* harmony export */ });
/* harmony import */ var _field_mapping_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./field-mapping.js */ "./assets/src/js/global/components/conditional-logic/field-mapping.js");
function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t.return || t.return(); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
/**
 * Check if a field's conditional logic depends on a given changed field.
 * Used to determine which fields need re-evaluation when a value changes.
 *
 * @param {Object} conditionalLogic - Parsed conditional logic config
 * @param {string} fieldKey - Key of the changed field (e.g. 'custom-checkbox', 'category')
 * @param {string} fieldName - Name attribute of the changed field (e.g. 'custom_field[custom-checkbox][]')
 * @param {jQuery} $changedField - The DOM element that changed
 * @param {Function} normalizeConditionFieldKey - Normalizer for field keys
 * @returns {boolean} True if any condition in the config references the changed field
 */

function fieldDependsOnChange(conditionalLogic, fieldKey, fieldName, $changedField, normalizeConditionFieldKey) {
  if (!conditionalLogic.groups || !Array.isArray(conditionalLogic.groups)) {
    return false;
  }
  var hasChangedField = $changedField && typeof $changedField.length !== 'undefined' && $changedField.length > 0;
  var isTaxonomyField = _field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.TAXONOMY_FIELD_KEYS.includes(fieldKey) || _field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.TAXONOMY_FIELD_KEYS.includes(fieldName) || hasChangedField && ($changedField.is(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.CATEGORY) || $changedField.is(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.TAGS) || $changedField.is(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.LOCATION) || $changedField.is(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.IN_LOC) || $changedField.is(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.IN_CAT) || $changedField.is(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.IN_TAG) || $changedField.closest(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.CATEGORY_CHECKLIST).length || $changedField.closest(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.LOCATION_CHECKLIST).length || $changedField.closest(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.TAGS_CHECKLIST).length);
  var _iterator = _createForOfIteratorHelper(conditionalLogic.groups),
    _step;
  try {
    for (_iterator.s(); !(_step = _iterator.n()).done;) {
      var _group = _step.value;
      if (!_group.conditions || !Array.isArray(_group.conditions)) {
        continue;
      }
      var _iterator4 = _createForOfIteratorHelper(_group.conditions),
        _step4;
      try {
        for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
          var _condition = _step4.value;
          var conditionFieldKey = (_condition.field || '').trim();
          var conditionFieldKeyMapped = _field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.WIDGET_KEY_TO_FIELD_KEY[conditionFieldKey] || conditionFieldKey;
          var conditionFieldKeyNormalized = normalizeConditionFieldKey(conditionFieldKey);
          var fieldKeyAsWidgetKey = null;
          if (fieldKey && fieldKey.startsWith('custom-')) {
            fieldKeyAsWidgetKey = fieldKey.replace(/^custom-/, '').replace(/-/g, '_');
          }
          if (fieldName && fieldName.startsWith('custom-')) {
            var fieldNameAsWidgetKey = fieldName.replace(/^custom-/, '');
            if (!fieldKeyAsWidgetKey) {
              fieldKeyAsWidgetKey = fieldNameAsWidgetKey;
            }
          }
          var changedId = hasChangedField ? $changedField.attr('id') : null;
          var changedName = hasChangedField ? $changedField.attr('name') : null;

          // review (builder field) = search_by_rating[] (DOM name)
          var isReviewField = conditionFieldKey === 'review' && (fieldKey === 'search_by_rating' || fieldName === 'search_by_rating[]') || conditionFieldKey === 'search_by_rating' && fieldKey === 'review';
          var matches = isReviewField || conditionFieldKey === fieldKey || conditionFieldKey === fieldName || conditionFieldKey === changedId || conditionFieldKey === changedName || conditionFieldKeyMapped === fieldKey || conditionFieldKeyMapped === fieldName || conditionFieldKeyMapped === changedId || conditionFieldKeyMapped === changedName || conditionFieldKeyNormalized && (conditionFieldKeyNormalized === fieldKey || conditionFieldKeyNormalized === fieldName || conditionFieldKeyNormalized === changedId || conditionFieldKeyNormalized === changedName || "custom_field[".concat(conditionFieldKeyNormalized, "]") === fieldName || "custom_field[".concat(conditionFieldKeyNormalized, "][]") === fieldName) || fieldKeyAsWidgetKey && (conditionFieldKey === fieldKeyAsWidgetKey || conditionFieldKeyMapped === fieldKeyAsWidgetKey);
          if (matches) {
            return true;
          }
        }
      } catch (err) {
        _iterator4.e(err);
      } finally {
        _iterator4.f();
      }
    }
  } catch (err) {
    _iterator.e(err);
  } finally {
    _iterator.f();
  }
  if (isTaxonomyField) {
    var _iterator2 = _createForOfIteratorHelper(conditionalLogic.groups),
      _step2;
    try {
      for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
        var group = _step2.value;
        if (!group.conditions || !Array.isArray(group.conditions)) {
          continue;
        }
        var _iterator3 = _createForOfIteratorHelper(group.conditions),
          _step3;
        try {
          for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
            var condition = _step3.value;
            if (_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.TAXONOMY_FIELD_KEYS.includes(condition.field)) {
              return true;
            }
          }
        } catch (err) {
          _iterator3.e(err);
        } finally {
          _iterator3.f();
        }
      }
    } catch (err) {
      _iterator2.e(err);
    } finally {
      _iterator2.f();
    }
  }
  return false;
}

/***/ }),

/***/ "./assets/src/js/global/components/conditional-logic/evaluate.js":
/*!***********************************************************************!*\
  !*** ./assets/src/js/global/components/conditional-logic/evaluate.js ***!
  \***********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   evaluateArrayCondition: function() { return /* binding */ evaluateArrayCondition; },
/* harmony export */   evaluateCondition: function() { return /* binding */ evaluateCondition; },
/* harmony export */   evaluateConditionalLogic: function() { return /* binding */ evaluateConditionalLogic; },
/* harmony export */   isEmpty: function() { return /* binding */ isEmpty; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var _field_mapping_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./field-mapping.js */ "./assets/src/js/global/components/conditional-logic/field-mapping.js");
/* harmony import */ var _helpers_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./helpers.js */ "./assets/src/js/global/components/conditional-logic/helpers.js");

function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t.return || t.return(); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
/**
 * Condition evaluation logic
 */



/**
 * Check if value is empty (null, undefined, '', or []).
 * @param {*} value
 * @returns {boolean}
 */
function isEmpty(value) {
  if (value === null || value === undefined) {
    return true;
  }
  if (typeof value === 'string' && value.trim() === '') {
    return true;
  }
  if (Array.isArray(value) && value.length === 0) {
    return true;
  }
  return false;
}

/**
 * Evaluate a single condition (operator + value).
 * @param {Object} condition - { field, operator, value }
 * @param {*} fieldValue - Current field value
 * @returns {boolean}
 */
function evaluateCondition(condition, fieldValue) {
  if (!condition.operator) {
    return false;
  }
  var operator = condition.operator.toLowerCase();
  var conditionValue = condition.value || '';

  // Handle file fields with "uploaded" value
  if (conditionValue.toLowerCase() === 'uploaded') {
    if (operator === 'is' || operator === '==' || operator === '=') {
      return fieldValue === 'uploaded' || fieldValue === true;
    }
    if (operator === 'is not' || operator === '!=' || operator === 'not') {
      return fieldValue !== 'uploaded' && fieldValue !== true && isEmpty(fieldValue);
    }
    if (operator === 'empty') {
      return isEmpty(fieldValue) || fieldValue !== 'uploaded';
    }
    if (operator === 'not empty') {
      return !isEmpty(fieldValue) && fieldValue === 'uploaded';
    }
  }
  if (operator === 'empty') {
    return isEmpty(fieldValue);
  }
  if (operator === 'not empty') {
    return !isEmpty(fieldValue);
  }
  if (Array.isArray(fieldValue)) {
    return evaluateArrayCondition(fieldValue, conditionValue, operator);
  }
  var fieldVal = fieldValue;
  var condVal = conditionValue;
  if (typeof fieldVal === 'string') {
    fieldVal = fieldVal.trim().toLowerCase();
  }
  if (typeof condVal === 'string') {
    condVal = condVal.trim().toLowerCase();
  }
  switch (operator) {
    case 'is':
    case '==':
    case '=':
      return String(fieldVal) === String(condVal);
    case 'is not':
    case '!=':
    case 'not':
      return String(fieldVal) !== String(condVal);
    case 'contains':
      if (typeof fieldVal === 'string' && typeof condVal === 'string') {
        return fieldVal.toLowerCase().includes(condVal.toLowerCase());
      }
      return false;
    case 'does not contain':
      if (typeof fieldVal === 'string' && typeof condVal === 'string') {
        return !fieldVal.toLowerCase().includes(condVal.toLowerCase());
      }
      return true;
    case 'greater than':
    case '>':
      return Number(fieldVal) > Number(condVal);
    case 'less than':
    case '<':
      return Number(fieldVal) < Number(condVal);
    case 'greater than or equal':
    case '>=':
      return Number(fieldVal) >= Number(condVal);
    case 'less than or equal':
    case '<=':
      return Number(fieldVal) <= Number(condVal);
    case 'starts with':
      if (typeof fieldVal === 'string' && typeof condVal === 'string') {
        return fieldVal.startsWith(condVal);
      }
      return false;
    case 'ends with':
      if (typeof fieldVal === 'string' && typeof condVal === 'string') {
        return fieldVal.endsWith(condVal);
      }
      return false;
    default:
      return false;
  }
}

/**
 * Evaluate condition when field value is an array (e.g. multi-select).
 * @param {Array} fieldArray
 * @param {*} conditionValue
 * @param {string} operator
 * @returns {boolean}
 */
function evaluateArrayCondition(fieldArray, conditionValue, operator) {
  if (!Array.isArray(fieldArray) || fieldArray.length === 0) {
    if (operator === 'empty' || operator === 'is empty') {
      return true;
    }
    if (operator === 'not empty' || operator === 'is not empty') {
      return false;
    }
    if (operator === 'is' || operator === '==' || operator === '=') {
      return false;
    }
    if (operator === 'is not' || operator === '!=' || operator === 'not') {
      return true;
    }
    return false;
  }
  var condVal = typeof conditionValue === 'string' ? conditionValue.trim().toLowerCase() : conditionValue;
  switch (operator) {
    case 'is':
    case '==':
    case '=':
      {
        var condValStrForIs = String(condVal).toLowerCase().trim();
        var normalizedValues = fieldArray.map(function (val) {
          if (typeof val === 'string') {
            return val.trim().toLowerCase();
          } else if (typeof val === 'number') {
            return String(val).toLowerCase();
          } else if ((0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(val) === 'object' && val !== null) {
            if (val.name) return String(val.name).trim().toLowerCase();
            if (val.label) return String(val.label).trim().toLowerCase();
            if (val.value) return String(val.value).trim().toLowerCase();
            if (val.id) return String(val.id).toLowerCase();
            return String(val).toLowerCase();
          }
          return String(val).toLowerCase();
        });
        // "is" = strict: all values must match (category/location return IDs only)
        var hasMatch = normalizedValues.some(function (val) {
          return val === condValStrForIs;
        });
        if (!hasMatch) return false;
        return normalizedValues.every(function (val) {
          return val === condValStrForIs;
        });
      }
    case 'contains':
      {
        var condValStrContains = String(condVal).toLowerCase();
        return fieldArray.some(function (val) {
          var compareVal = val;
          if (typeof compareVal === 'string') {
            compareVal = compareVal.trim().toLowerCase();
          } else if (typeof compareVal === 'number') {
            compareVal = String(compareVal).toLowerCase();
          } else if ((0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(compareVal) === 'object' && compareVal !== null) {
            if (compareVal.name) compareVal = compareVal.name;else if (compareVal.label) compareVal = compareVal.label;else if (compareVal.value) compareVal = compareVal.value;else if (compareVal.id) compareVal = compareVal.id;else compareVal = String(compareVal);
            if (typeof compareVal === 'string') {
              compareVal = compareVal.trim().toLowerCase();
            }
          }
          var compareValStr = String(compareVal).toLowerCase();
          return compareValStr === condValStrContains || compareValStr.includes(condValStrContains);
        });
      }
    case 'is not':
    case '!=':
    case 'does not contain':
      return !fieldArray.some(function (val) {
        var compareVal = val;
        if (typeof compareVal === 'string') {
          compareVal = compareVal.trim().toLowerCase();
        }
        if ((0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(compareVal) === 'object' && compareVal !== null) {
          if (compareVal.name) compareVal = compareVal.name;else if (compareVal.label) compareVal = compareVal.label;else if (compareVal.value) compareVal = compareVal.value;else if (compareVal.id) compareVal = compareVal.id;else compareVal = String(compareVal);
        }
        return String(compareVal).toLowerCase().includes(String(condVal).toLowerCase()) || String(compareVal).toLowerCase() === String(condVal).toLowerCase();
      });
    case 'empty':
    case 'is empty':
      return fieldArray.length === 0;
    case 'not empty':
    case 'is not empty':
      return fieldArray.length > 0;
    default:
      return false;
  }
}

/**
 * Evaluate full conditional logic (groups, operators, action).
 * @param {Object} conditionalLogic - { enabled, action, globalOperator, groups }
 * @param {Function} getFieldValueFn - (fieldKey) => value
 * @returns {boolean} True if field should show
 */
function evaluateConditionalLogic(conditionalLogic, getFieldValueFn) {
  if (!conditionalLogic) {
    return true;
  }
  var isEnabled = conditionalLogic.enabled === true || conditionalLogic.enabled === 1 || conditionalLogic.enabled === '1' || conditionalLogic.enabled === 'true';
  if (!isEnabled) {
    return true;
  }
  if (!conditionalLogic.groups || !Array.isArray(conditionalLogic.groups) || conditionalLogic.groups.length === 0) {
    return true;
  }
  var groupResults = [];
  var _iterator = _createForOfIteratorHelper(conditionalLogic.groups),
    _step;
  try {
    for (_iterator.s(); !(_step = _iterator.n()).done;) {
      var group = _step.value;
      if (!group.conditions || !Array.isArray(group.conditions) || group.conditions.length === 0) {
        continue;
      }
      var conditionResults = [];
      var _iterator2 = _createForOfIteratorHelper(group.conditions),
        _step2;
      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var condition = _step2.value;
          var rawField = (condition.field || '').trim();
          if (!rawField) {
            continue;
          }
          if (!condition.operator || !condition.operator.trim()) {
            continue;
          }
          var fieldKeyForLookup = (0,_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.normalizeConditionFieldKey)(rawField);
          var fieldValue = getFieldValueFn(fieldKeyForLookup);
          var conditionResult = evaluateCondition(condition, fieldValue);
          conditionResults.push(conditionResult);
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }
      if (conditionResults.length === 0) {
        continue;
      }
      var groupOperator = (0,_helpers_js__WEBPACK_IMPORTED_MODULE_2__.normalizeOperator)(group.operator, 'AND');
      var groupResult = groupOperator === 'OR' ? conditionResults.some(function (result) {
        return result === true;
      }) : conditionResults.every(function (result) {
        return result === true;
      });
      groupResults.push(groupResult);
    }
  } catch (err) {
    _iterator.e(err);
  } finally {
    _iterator.f();
  }
  var globalOperator = (0,_helpers_js__WEBPACK_IMPORTED_MODULE_2__.normalizeOperator)(conditionalLogic.globalOperator, 'OR');
  var result = true;
  if (groupResults.length > 0) {
    result = globalOperator === 'AND' ? groupResults.every(function (groupRes) {
      return groupRes === true;
    }) : groupResults.some(function (groupRes) {
      return groupRes === true;
    });
  }
  if (conditionalLogic.action === 'hide') {
    return !result;
  }
  return result;
}

/***/ }),

/***/ "./assets/src/js/global/components/conditional-logic/event-handlers/file-upload-handlers.js":
/*!**************************************************************************************************!*\
  !*** ./assets/src/js/global/components/conditional-logic/event-handlers/file-upload-handlers.js ***!
  \**************************************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   setupFileUploadHandlers: function() { return /* binding */ setupFileUploadHandlers; }
/* harmony export */ });
/**
 * File upload handlers: plupload, ez-media-uploader, thumb removal.
 * Uses MutationObserver on document.body to detect uploads/deletes.
 * @param {jQuery} $
 * @param {Function} triggerFn - (fieldName, fieldKey, $changedField) => void
 */
function setupFileUploadHandlers($, triggerFn) {
  var fileUploadObserver = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
        var $target = $(mutation.target);
        if ($target.hasClass('ezmu__preview-section') && $target.hasClass('ezmu--show')) {
          var $imageWrapper = $target.closest('.directorist-form-image-upload-field');
          if ($imageWrapper.length) {
            setTimeout(function () {
              triggerFn('listing_img', 'listing_img', $imageWrapper.find('.ez-media-uploader').first());
            }, 200);
          }
        }
        if ($target.hasClass('ezmu__preview-section') && !$target.hasClass('ezmu--show')) {
          var _$imageWrapper = $target.closest('.directorist-form-image-upload-field');
          if (_$imageWrapper.length) {
            setTimeout(function () {
              triggerFn('listing_img', 'listing_img', _$imageWrapper.find('.ez-media-uploader').first());
            }, 200);
          }
        }
      }
      if (mutation.addedNodes.length > 0) {
        mutation.addedNodes.forEach(function (node) {
          if (node.nodeType !== 1) return;
          var $node = $(node);
          if ($node.hasClass('thumb') || $node.closest('.plupload-thumbs').length || $node.find('.thumb').length) {
            var $fileWrapper = $node.closest('.directorist-form-group, .directorist-custom-field-file-upload');
            if ($fileWrapper.length) {
              var fieldKey = resolveFileFieldKey($fileWrapper);
              if (fieldKey) {
                setTimeout(function () {
                  triggerFn(fieldKey, fieldKey, $fileWrapper.find('input[type="hidden"]').first());
                }, 100);
              }
            }
          }
          if ($node.hasClass('ezmu__preview-section') || $node.hasClass('ezmu--show') || $node.closest('.ezmu__preview-section.ezmu--show').length) {
            var _$imageWrapper2 = $node.closest('.directorist-form-image-upload-field');
            if (_$imageWrapper2.length) {
              setTimeout(function () {
                triggerFn('listing_img', 'listing_img', _$imageWrapper2.find('.ez-media-uploader').first());
              }, 200);
            }
          }
        });
      }
      if (mutation.removedNodes.length > 0) {
        mutation.removedNodes.forEach(function (node) {
          if (node.nodeType !== 1) return;
          var $node = $(node);
          if ($node.hasClass('thumb') || $node.closest('.plupload-thumbs').length || $node.find('.thumb').length) {
            var $thumbsContainer = $(mutation.target);
            if ($thumbsContainer.hasClass('plupload-thumbs') || $thumbsContainer.find('.plupload-thumbs').length) {
              var $fileWrapper = $thumbsContainer.closest('.directorist-form-group, .directorist-custom-field-file-upload');
              if ($fileWrapper.length) {
                var fieldKey = resolveFileFieldKey($fileWrapper);
                if (fieldKey) {
                  setTimeout(function () {
                    triggerFn(fieldKey, fieldKey, $fileWrapper.find('input[type="hidden"]').first());
                  }, 300);
                }
              }
            }
          }
          if ($node.hasClass('ezmu__file-item') || $node.hasClass('ezmu__new-file') || $node.closest('.ez-media-uploader').length || $node.hasClass('ezmu__old-files-meta') || $node.find('.ezmu__file-item, .ezmu__new-file').length) {
            var $uploaderContainer = $(mutation.target);
            if ($uploaderContainer.hasClass('ez-media-uploader') || $uploaderContainer.closest('.ez-media-uploader').length) {
              var _$imageWrapper3 = $uploaderContainer.closest('.directorist-form-image-upload-field');
              if (_$imageWrapper3.length) {
                setTimeout(function () {
                  triggerFn('listing_img', 'listing_img', _$imageWrapper3.find('.ez-media-uploader').first());
                }, 300);
              }
            }
          }
        });
      }
    });
  });
  fileUploadObserver.observe(document.body, {
    childList: true,
    subtree: true,
    attributes: true,
    attributeFilter: ['class']
  });
  document.addEventListener('click', function (e) {
    var $target = $(e.target);
    var $removeButton = $target.closest('.thumbremovelink').length ? $target.closest('.thumbremovelink') : $target.hasClass('thumbremovelink') ? $target : null;
    if (!$removeButton || !$removeButton.length) return;
    var $thumb = $removeButton.closest('.thumb');
    if (!$thumb.length) return;
    var $fileWrapper = $thumb.closest('.directorist-form-group, .directorist-custom-field-file-upload');
    if (!$fileWrapper.length) return;
    var fieldKey = resolveFileFieldKey($fileWrapper);
    if (fieldKey) {
      setTimeout(function () {
        var $hiddenInput = $fileWrapper.find("input[type=\"hidden\"][name=\"".concat(fieldKey, "\"], input[type=\"hidden\"][name=\"").concat(fieldKey, "[]\"]")).first();
        triggerFn(fieldKey, fieldKey, $hiddenInput.length ? $hiddenInput : $fileWrapper.find('input[type="hidden"]').first() || $fileWrapper);
      }, 400);
    }
  }, true);
}

/** Get field key from data-field-key or hidden input name. */
function resolveFileFieldKey($fileWrapper) {
  var fieldKey = $fileWrapper.attr('data-field-key') || $fileWrapper.find('[data-field-key]').first().attr('data-field-key');
  if (!fieldKey) {
    var $hiddenInput = $fileWrapper.find('input[type="hidden"]').first();
    if ($hiddenInput.length) {
      var inputName = $hiddenInput.attr('name');
      if (inputName) {
        fieldKey = inputName.includes('[') ? inputName.split('[')[0] : inputName;
      }
    }
  }
  return fieldKey;
}

/***/ }),

/***/ "./assets/src/js/global/components/conditional-logic/event-handlers/form-handlers.js":
/*!*******************************************************************************************!*\
  !*** ./assets/src/js/global/components/conditional-logic/event-handlers/form-handlers.js ***!
  \*******************************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   setupFormHandlers: function() { return /* binding */ setupFormHandlers; }
/* harmony export */ });
/* harmony import */ var _field_mapping_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../field-mapping.js */ "./assets/src/js/global/components/conditional-logic/field-mapping.js");
/* harmony import */ var _helpers_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../helpers.js */ "./assets/src/js/global/components/conditional-logic/helpers.js");
/**
 * Form change handlers: input/select/textarea, color picker, clear button
 */



/**
 * @param {Function} getWrapperFn
 * @param {jQuery} $
 * @param {Function} triggerFn - (fieldName, fieldKey, $changedField) => void
 */
function setupFormHandlers(getWrapperFn, $, triggerFn) {
  // Delegate from document so handlers work after AJAX form replace (e.g. search form tab change)
  var wrapper = getWrapperFn();
  var delegatedSelector = wrapper.split(',').map(function (s) {
    return s.trim();
  }).flatMap(function (s) {
    return [s + ' input', s + ' select', s + ' textarea', s + ' .select2-hidden-accessible'];
  }).join(', ');
  $(document).on('change input select2:select select2:unselect', delegatedSelector, function () {
    var $changedField = $(this);
    var fieldName = $changedField.attr('name') || $changedField.attr('id');
    if (!fieldName) {
      console.warn('Field change detected but no name/id found:', $changedField);
      return;
    }
    var _extractFieldKeyFromC = (0,_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.extractFieldKeyFromChange)(fieldName, $changedField),
      fieldKey = _extractFieldKeyFromC.fieldKey,
      taxonomyFieldSelector = _extractFieldKeyFromC.taxonomyFieldSelector;
    if (taxonomyFieldSelector) {
      // Address input: no Select2, just re-evaluate after DOM settles
      if (taxonomyFieldSelector === _field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.TAXONOMY_SELECTOR_SEARCH_FORM_ADDRESS) {
        setTimeout(function () {
          triggerFn(fieldName, fieldKey, $changedField);
        }, 50);
        return;
      }
      var $fieldToUpdate = taxonomyFieldSelector === _field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.TAXONOMY_SELECTOR_SEARCH_FORM_FIELD ? $changedField : $(taxonomyFieldSelector);
      // Let Select2 update data-selected-* before re-evaluating
      setTimeout(function () {
        if ($fieldToUpdate.length) {
          (0,_helpers_js__WEBPACK_IMPORTED_MODULE_1__.syncSelect2DataAttributes)($fieldToUpdate, $);
        }
        triggerFn(fieldName, fieldKey, $changedField);
      }, 50);
      return;
    }
    triggerFn(fieldName, fieldKey, $changedField);
  });

  // Clear button
  $(document).on('click', '.directorist-search-field__btn--clear', function () {
    var $fieldWrap = $(this).closest('.directorist-search-field');
    var _extractFieldFromClea = (0,_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.extractFieldFromClearButton)($fieldWrap),
      fieldKey = _extractFieldFromClea.fieldKey,
      fieldName = _extractFieldFromClea.fieldName,
      $changedField = _extractFieldFromClea.$changedField;
    if (fieldKey) {
      setTimeout(function () {
        triggerFn(fieldName, fieldKey, $changedField);
      }, 50);
    }
  });

  // Document-level fallback for custom fields
  $(document).on('change', '.directorist-select select, .directorist-custom-field-select select, select.directorist-form-element, .directorist-custom-field-radio input[type="radio"], .directorist-custom-field-checkbox input[type="checkbox"]', function () {
    var $changedField = $(this);
    var fieldName = $changedField.attr('name') || $changedField.attr('id');
    if (!fieldName) return;
    var fieldKey = (0,_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.extractFieldKeyFromName)(fieldName);
    triggerFn(fieldName, fieldKey, $changedField);
  });

  // Color picker: value updates async, delay before re-evaluating
  function handleColorPickerChange(field) {
    var $changedField = $(field);
    var fieldName = $changedField.attr('name') || $changedField.attr('id');
    if (!fieldName) return;
    var fieldKey = (0,_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.extractFieldKeyFromName)(fieldName);
    setTimeout(function () {
      triggerFn(fieldName, fieldKey, $changedField);
    }, 50);
  }
  $(document).on('change', '.directorist-color-picker, .wp-color-picker, input.wp-color-picker', function () {
    handleColorPickerChange(this);
  });
  $(document).on('irischange', '.directorist-color-picker, .wp-color-picker, input.wp-color-picker', function () {
    handleColorPickerChange(this);
  });
  document.addEventListener('click', function (e) {
    if (e.target && (e.target.classList.contains('wp-picker-clear') || e.target.tagName === 'INPUT' && e.target.type === 'button' && e.target.className.includes('wp-picker-clear'))) {
      var $clearButton = $(e.target);
      var $colorPickerInput = $clearButton.closest('.wp-picker-container').find('.directorist-color-picker, .wp-color-picker, input.wp-color-picker');
      handleColorPickerChange($colorPickerInput);
    }
  }, true);
}

/***/ }),

/***/ "./assets/src/js/global/components/conditional-logic/event-handlers/taxonomy-handlers.js":
/*!***********************************************************************************************!*\
  !*** ./assets/src/js/global/components/conditional-logic/event-handlers/taxonomy-handlers.js ***!
  \***********************************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   setupTaxonomyHandlers: function() { return /* binding */ setupTaxonomyHandlers; }
/* harmony export */ });
/* harmony import */ var _field_mapping_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../field-mapping.js */ "./assets/src/js/global/components/conditional-logic/field-mapping.js");
/* harmony import */ var _helpers_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../helpers.js */ "./assets/src/js/global/components/conditional-logic/helpers.js");
/**
 * Taxonomy field handlers: Select2, tag metabox, clear button
 */



/**
 * @param {jQuery} $
 * @param {Function} triggerFn - (fieldName, fieldKey, $changedField) => void
 */
function setupTaxonomyHandlers($, triggerFn) {
  var taxonomyFieldSelectors = "".concat(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.CATEGORY, ", ").concat(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.TAGS, ", ").concat(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.LOCATION, ", ").concat(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.IN_LOC, ", ").concat(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.IN_CAT);

  // Select2: delay so DOM is updated before we read data-selected-*
  // 'change' needed for search form in_cat/in_loc (Select2 may not fire select2:select in some setups)
  $(document).on('change select2:select select2:unselect select2:clear', taxonomyFieldSelectors, function () {
    setTimeout(function () {
      var $field = $(this);
      if (!$field.length) return;
      var fieldKey = 'category';
      var fieldName = 'admin_category_select[]';
      if ($field.is(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.TAGS)) {
        fieldKey = 'tag';
        fieldName = $field.attr('name') || 'tag';
      } else if ($field.is(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.LOCATION) || $field.is(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.IN_LOC)) {
        fieldKey = 'location';
        fieldName = $field.attr('name') || 'tax_input[at_biz_dir-location][]';
      } else if ($field.is(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.IN_CAT)) {
        fieldKey = 'category';
        fieldName = $field.attr('name') || 'in_cat';
      }
      (0,_helpers_js__WEBPACK_IMPORTED_MODULE_1__.syncSelect2DataAttributes)($field, $);
      triggerFn(fieldName, fieldKey, $field);
    }.bind(this), 50);
  });

  // Tag metabox: add/remove tags
  $(document).on('click', "".concat(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.TAG_METABOX, " .ntdelbutton, ").concat(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.TAG_METABOX, " input.tagadd, ").concat(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.TAG_METABOX, " .button"), function () {
    setTimeout(function () {
      triggerFn('tax_input[at_biz_dir-tags][]', 'tax_input[at_biz_dir-tags][]', $("".concat(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.TAG_METABOX, " .tagchecklist")));
    }, 100);
  });
  $(document).on('keypress', "".concat(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.TAG_METABOX, " input.newtag, ").concat(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.TAGS, " input.newtag"), function (e) {
    if (e.which === 13) {
      setTimeout(function () {
        triggerFn('tax_input[at_biz_dir-tags][]', 'tax_input[at_biz_dir-tags][]', $("".concat(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.TAG_METABOX, " .tagchecklist, ").concat(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.TAGS, " .tagchecklist")));
      }, 50);
    }
  });

  // MutationObserver: tagchecklist may load via AJAX
  function observeTagchecklist() {
    var $tagchecklist = $("".concat(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.TAG_METABOX, " .tagchecklist"));
    if ($tagchecklist.length && typeof MutationObserver !== 'undefined') {
      var tagObserver = new MutationObserver(function () {
        triggerFn('tax_input[at_biz_dir-tags][]', 'tax_input[at_biz_dir-tags][]', $tagchecklist);
      });
      tagObserver.observe($tagchecklist[0], {
        childList: true,
        subtree: true
      });
    }
  }
  observeTagchecklist();
  setTimeout(observeTagchecklist, 1000);
}

/***/ }),

/***/ "./assets/src/js/global/components/conditional-logic/event-handlers/tinymce-handlers.js":
/*!**********************************************************************************************!*\
  !*** ./assets/src/js/global/components/conditional-logic/event-handlers/tinymce-handlers.js ***!
  \**********************************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   setupTinyMCEHandlers: function() { return /* binding */ setupTinyMCEHandlers; }
/* harmony export */ });
/* harmony import */ var _field_mapping_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../field-mapping.js */ "./assets/src/js/global/components/conditional-logic/field-mapping.js");
/**
 * TinyMCE editor handlers for conditional logic
 */


/**
 * @param {jQuery} $
 * @param {Function} triggerFn - (fieldName, fieldKey, $changedField) => void
 */
function setupTinyMCEHandlers($, triggerFn) {
  if (typeof tinymce === 'undefined') return;
  function attachTinyMCEEvents(editor) {
    if (!editor || !editor.id) return;
    var editorId = editor.id;
    var $editorTextarea = $('#' + editorId);
    if (!$editorTextarea.length) return;
    var $formGroup = $editorTextarea.closest('.directorist-form-group');
    var isWordPressContentEditor = editorId === 'content' && $editorTextarea.closest('#postdivrich, #wp-content-wrap').length;
    if (!$formGroup.length && !isWordPressContentEditor) return;
    var fieldName = $editorTextarea.attr('name') || editorId;
    var fieldKey = _field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.WIDGET_KEY_TO_FIELD_KEY[fieldName] || fieldName;

    // Do not remove all TinyMCE listeners; that can break core UI
    // behaviors like link popover visibility/positioning.
    if (editor.directoristConditionalLogicBound) {
      return;
    }
    var conditionalLogicHandler = function conditionalLogicHandler() {
      triggerFn(fieldName, fieldKey, $editorTextarea);
    };
    editor.on('input keyup change NodeChange', conditionalLogicHandler);
    editor.directoristConditionalLogicBound = true;
  }
  $(document).ready(function () {
    try {
      if (typeof tinymce.on === 'function') {
        tinymce.on('AddEditor', function (e) {
          if (e && e.editor) attachTinyMCEEvents(e.editor);
        });
      }
    } catch (e) {}
    function initExistingEditors() {
      try {
        if (typeof tinymce !== 'undefined' && tinymce.editors && typeof tinymce.editors.forEach === 'function') {
          tinymce.editors.forEach(function (editor) {
            if (editor) attachTinyMCEEvents(editor);
          });
        }
      } catch (e) {}
    }
    initExistingEditors();
    setTimeout(initExistingEditors, 500);
    setTimeout(initExistingEditors, 1000);
    setTimeout(initExistingEditors, 2000);
  });
  $(document).on('tinymce-editor-init', function (e, editor) {
    if (editor) attachTinyMCEEvents(editor);
  });
}

/***/ }),

/***/ "./assets/src/js/global/components/conditional-logic/field-mapping.js":
/*!****************************************************************************!*\
  !*** ./assets/src/js/global/components/conditional-logic/field-mapping.js ***!
  \****************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   SELECTORS: function() { return /* binding */ SELECTORS; },
/* harmony export */   TAXONOMY_FIELD_KEYS: function() { return /* binding */ TAXONOMY_FIELD_KEYS; },
/* harmony export */   TAXONOMY_SELECTOR_SEARCH_FORM_ADDRESS: function() { return /* binding */ TAXONOMY_SELECTOR_SEARCH_FORM_ADDRESS; },
/* harmony export */   TAXONOMY_SELECTOR_SEARCH_FORM_FIELD: function() { return /* binding */ TAXONOMY_SELECTOR_SEARCH_FORM_FIELD; },
/* harmony export */   WIDGET_KEY_TO_FIELD_KEY: function() { return /* binding */ WIDGET_KEY_TO_FIELD_KEY; },
/* harmony export */   escapeCssId: function() { return /* binding */ escapeCssId; },
/* harmony export */   extractFieldFromClearButton: function() { return /* binding */ extractFieldFromClearButton; },
/* harmony export */   extractFieldKeyFromChange: function() { return /* binding */ extractFieldKeyFromChange; },
/* harmony export */   extractFieldKeyFromName: function() { return /* binding */ extractFieldKeyFromName; },
/* harmony export */   isTaxonomyFieldKeyOrElement: function() { return /* binding */ isTaxonomyFieldKeyOrElement; },
/* harmony export */   isTaxonomySelectField: function() { return /* binding */ isTaxonomySelectField; },
/* harmony export */   mapFieldKeyToSelector: function() { return /* binding */ mapFieldKeyToSelector; },
/* harmony export */   normalizeConditionFieldKey: function() { return /* binding */ normalizeConditionFieldKey; }
/* harmony export */ });
/**
 * Field mapping: escape IDs, map field keys to selectors, normalize field keys
 */

/** @readonly Maps widget/short keys to canonical field keys (e.g. title → listing_title) */
var WIDGET_KEY_TO_FIELD_KEY = {
  title: 'listing_title',
  description: 'listing_content',
  content: 'listing_content'
};

/** @readonly Taxonomy field keys and names used for condition matching */
var TAXONOMY_FIELD_KEYS = ['category', 'categories', 'admin_category_select[]', 'tax_input[at_biz_dir-category][]', 'in_cat', 'tag', 'tags', 'in_tag[]', 'location', 'locations', 'tax_input[at_biz_dir-location][]', 'in_loc', 'tax_input[at_biz_dir-tags][]'];

/** @readonly Selectors for taxonomy and special fields */
var SELECTORS = {
  CATEGORY: '#at_biz_dir-categories',
  TAGS: '#at_biz_dir-tags',
  LOCATION: '#at_biz_dir-location',
  IN_CAT: "select[name='in_cat']",
  IN_LOC: "select[name='in_loc']",
  IN_TAG: "input[name='in_tag[]']",
  CATEGORY_CHECKLIST: '#at_biz_dir-categorychecklist, #at_biz_dir-categorychecklist-pop',
  LOCATION_CHECKLIST: '#at_biz_dir-locationchecklist, #at_biz_dir-locationchecklist-pop',
  TAGS_CHECKLIST: '#at_biz_dir-tagschecklist, #at_biz_dir-tagschecklist-pop, #tagsdiv-at_biz_dir-tags',
  CATEGORY_CHECKLIST_ID: '#at_biz_dir-categorychecklist',
  LOCATION_CHECKLIST_ID: '#at_biz_dir-locationchecklist',
  TAGS_CHECKLIST_ID: '#at_biz_dir-tagschecklist',
  TAG_METABOX: '#tagsdiv-at_biz_dir-tags',
  /** For get-field-value: category/tag/location select or multi-select */
  CATEGORY_SELECT: "#at_biz_dir-categories, select[name='in_cat']",
  TAGS_SELECT: "#at_biz_dir-tags, input[name='in_tag[]']",
  LOCATION_SELECT: "#at_biz_dir-location, select[name='in_loc']",
  CATEGORY_CHECKLIST_CHECKED: '#at_biz_dir-categorychecklist input:checked, #at_biz_dir-categorychecklist-pop input:checked',
  TAGS_CHECKLIST_CHECKED: '#at_biz_dir-tagschecklist input:checked, #at_biz_dir-tagschecklist-pop input:checked, input[name="tax_input[at_biz_dir-tags][]"]:checked',
  LOCATION_CHECKLIST_CHECKED: '#at_biz_dir-locationchecklist input:checked, #at_biz_dir-locationchecklist-pop input:checked, input[name="tax_input[at_biz_dir-location][]"]:checked',
  SEARCH_ADDRESS: ".directorist-search-location input[name='address']",
  TAG_CHECKLIST_ITEMS: '#tagsdiv-at_biz_dir-tags .tagchecklist li, #at_biz_dir-tags .tagchecklist li',
  TAG_TEXTAREA: '#tagsdiv-at_biz_dir-tags .the-tags, #at_biz_dir-tags .the-tags'
};

/** Sentinel values for extractFieldKeyFromChange: taxonomy field is in search form (use $changedField) */
var TAXONOMY_SELECTOR_SEARCH_FORM_FIELD = 'search_form_field';
/** Sentinel: taxonomy is search form address input (no Select2 sync, just re-evaluate) */
var TAXONOMY_SELECTOR_SEARCH_FORM_ADDRESS = 'search_form_address';

/**
 * Check if element is a taxonomy Select2/select field (category, tag, location).
 * @param {jQuery} $field
 * @returns {boolean}
 */
function isTaxonomySelectField($field) {
  return $field && $field.length && ($field.is(SELECTORS.CATEGORY) || $field.is(SELECTORS.TAGS) || $field.is(SELECTORS.LOCATION) || $field.is(SELECTORS.IN_CAT) || $field.is(SELECTORS.IN_LOC));
}

/**
 * Check if fieldKey/fieldName/DOM element refers to a taxonomy field.
 * Used by depends-on-field to determine if a change affects taxonomy conditions.
 * @param {string} fieldKey
 * @param {string} fieldName
 * @param {jQuery} $changedField
 * @returns {boolean}
 */
function isTaxonomyFieldKeyOrElement(fieldKey, fieldName, $changedField) {
  if (TAXONOMY_FIELD_KEYS.includes(fieldKey) || TAXONOMY_FIELD_KEYS.includes(fieldName)) {
    return true;
  }
  var hasField = $changedField && typeof $changedField.length !== 'undefined' && $changedField.length > 0;
  if (!hasField) return false;
  return $changedField.is(SELECTORS.CATEGORY) || $changedField.is(SELECTORS.TAGS) || $changedField.is(SELECTORS.LOCATION) || $changedField.is(SELECTORS.IN_LOC) || $changedField.is(SELECTORS.IN_CAT) || $changedField.is(SELECTORS.IN_TAG) || $changedField.closest(SELECTORS.CATEGORY_CHECKLIST).length > 0 || $changedField.closest(SELECTORS.LOCATION_CHECKLIST).length > 0 || $changedField.closest(SELECTORS.TAGS_CHECKLIST).length > 0;
}

/**
 * Escape a string for use in CSS ID/class selectors.
 * Characters like [ ] in field keys (e.g. admin_category_select[]) break jQuery selectors.
 * @param {string} str - Raw string
 * @returns {string} Escaped string
 */
function escapeCssId(str) {
  if (typeof str !== 'string') return str;
  try {
    if (typeof CSS !== 'undefined' && typeof CSS.escape === 'function') {
      return CSS.escape(str);
    }
  } catch (e) {}
  // Fallback: escape [ ] and other chars that break ID/class selectors
  return str.replace(/([!"#$%&'()*+,./:;<=>?@[\]^`{|}~\\])/g, '\\$1');
}

/**
 * Map widget_key/field_key to actual frontend field selector.
 * @param {string} fieldKey - Field key (e.g. 'category', 'custom-select')
 * @returns {string|null} jQuery selector string or null
 */
function mapFieldKeyToSelector(fieldKey) {
  var fieldKeyMap = {
    category: "#at_biz_dir-categories, select[name='in_cat'], .directorist-search-category select",
    categories: "#at_biz_dir-categories, select[name='in_cat'], .directorist-search-category select",
    'admin_category_select[]': "#at_biz_dir-categories, select[name='in_cat'], .directorist-search-category select",
    in_cat: "select[name='in_cat'], .directorist-search-category select",
    description: '[name="listing_content"], #listing_content, [name="description"], #description, #content, [name="content"]',
    listing_content: '[name="listing_content"], #listing_content, [name="description"], #description, #content, [name="content"]',
    title: '.directorist-search-query input, .directorist-search-form-wrap input[name="q"], .directorist-search-form input[name="q"], input[name="q"], [name="listing_title"], #listing_title, [name="title"], #title, [name="post_title"]',
    listing_title: '.directorist-search-query input, .directorist-search-form-wrap input[name="q"], .directorist-search-form input[name="q"], input[name="q"], [name="listing_title"], #listing_title, [name="title"], #title, [name="post_title"]',
    q: '.directorist-search-query input, input[name="q"]',
    location: '[name="location"], #at_biz_dir-location, select[name="in_loc"], .directorist-search-location select',
    in_loc: 'select[name="in_loc"], .directorist-search-location select',
    address: '[name="address"], #address',
    phone: '[name="phone"], #phone',
    email: '[name="email"], #email',
    website: '[name="website"], #website',
    tag: '[name="tag"], #at_biz_dir-tags, [name="in_tag[]"]',
    'in_tag[]': '[name="in_tag[]"]',
    'tax_input[at_biz_dir-tags][]': "#at_biz_dir-tags, [name='in_tag[]']",
    'tax_input[at_biz_dir-location][]': "#at_biz_dir-location, select[name='in_loc']",
    zip: '[name="zip"], #zip',
    miles: '[name="miles"], .directorist-custom-range-slider__range',
    search_by_rating: '[name="search_by_rating[]"]',
    review: '[name="search_by_rating[]"]',
    image_upload: '[name="listing_img[]"], .directorist-form-image_upload-field'
  };
  if (fieldKeyMap[fieldKey]) {
    return fieldKeyMap[fieldKey];
  }

  // Search form custom fields: name="custom_field[field_key]"
  if (fieldKey && (fieldKey.startsWith('custom-') || ['select', 'radio', 'checkbox'].some(function (t) {
    return fieldKey === t || fieldKey.startsWith(t + '_');
  }))) {
    var fk = fieldKey.startsWith('custom-') ? fieldKey : "custom-".concat(fieldKey.replace(/_/g, '-'));
    return ["select[name=\"custom_field[".concat(fk, "]\"]"), "input[name=\"custom_field[".concat(fk, "]\"]"), "input[name=\"custom_field[".concat(fk, "][]\"]"), ".directorist-advanced-filter__advanced__element.directorist-search-field-select select[name=\"custom_field[".concat(fk, "]\"]"), ".directorist-advanced-filter__advanced__element.directorist-search-field-radio input[name=\"custom_field[".concat(fk, "]\"]"), ".directorist-advanced-filter__advanced__element.directorist-search-field-checkbox input[name=\"custom_field[".concat(fk, "][]\"]"), ".directorist-search-field select[name=\"custom_field[".concat(fk, "]\"]"), ".directorist-search-field input[name=\"custom_field[".concat(fk, "]\"]"), ".directorist-search-field input[name=\"custom_field[".concat(fk, "][]\"]")].join(', ');
  }
  return null;
}

/**
 * Extract field key from name attribute (handles array notation).
 * @param {string} fieldName - Raw name (e.g. 'custom_field[custom-checkbox][]')
 * @returns {string} Base field key (e.g. 'custom_field[custom-checkbox]')
 */
function extractFieldKeyFromName(fieldName) {
  if (!fieldName || typeof fieldName !== 'string') return '';
  var key = fieldName;
  if (fieldName.includes('[')) {
    key = fieldName.split('[')[0];
  }
  if (key.endsWith('[]')) {
    key = key.slice(0, -2);
  }
  return key;
}

/**
 * Extract fieldKey and fieldName from a changed field element.
 * Handles WordPress admin, search form, and custom field mappings.
 * @param {string} fieldName - Raw name from element
 * @param {jQuery} $changedField - The DOM element that changed
 * @param {Object} [selectors] - SELECTORS (default)
 * @returns {{ fieldKey: string, fieldName: string, taxonomyFieldSelector: string|null }}
 */
function extractFieldKeyFromChange(fieldName, $changedField) {
  var selectors = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : SELECTORS;
  var fieldKey = extractFieldKeyFromName(fieldName);
  var taxonomyFieldSelector = null;
  if (fieldName === 'post_title' || $changedField.attr('id') === 'title') {
    return {
      fieldKey: 'listing_title',
      fieldName: fieldName,
      taxonomyFieldSelector: null
    };
  }
  if (fieldName === 'q' || $changedField.attr('name') === 'q' && $changedField.closest('.directorist-search-query, .directorist-search-form-wrap, .directorist-search-form').length) {
    return {
      fieldKey: 'title',
      fieldName: fieldName,
      taxonomyFieldSelector: null
    };
  }
  if (fieldName === 'content' || $changedField.attr('id') === 'content') {
    return {
      fieldKey: 'listing_content',
      fieldName: fieldName,
      taxonomyFieldSelector: null
    };
  }
  if (fieldName === 'admin_category_select[]' || $changedField.is(selectors.CATEGORY)) {
    return {
      fieldKey: 'category',
      fieldName: fieldName,
      taxonomyFieldSelector: selectors.CATEGORY
    };
  }
  if (fieldName === 'tax_input[at_biz_dir-category][]' || $changedField.closest(selectors.CATEGORY_CHECKLIST).length) {
    return {
      fieldKey: 'admin_category_select[]',
      fieldName: fieldName,
      taxonomyFieldSelector: selectors.CATEGORY_CHECKLIST_ID
    };
  }
  if (fieldName === 'tax_input[at_biz_dir-location][]' || $changedField.closest(selectors.LOCATION_CHECKLIST).length) {
    return {
      fieldKey: 'tax_input[at_biz_dir-location][]',
      fieldName: fieldName,
      taxonomyFieldSelector: selectors.LOCATION_CHECKLIST_ID
    };
  }
  if (fieldName === 'tax_input[at_biz_dir-tags][]' || $changedField.closest(selectors.TAGS_CHECKLIST).length) {
    return {
      fieldKey: 'tax_input[at_biz_dir-tags][]',
      fieldName: fieldName,
      taxonomyFieldSelector: selectors.TAGS_CHECKLIST_ID
    };
  }
  if ($changedField.is(selectors.TAGS)) {
    return {
      fieldKey: 'tag',
      fieldName: fieldName,
      taxonomyFieldSelector: selectors.TAGS
    };
  }
  if ($changedField.is(selectors.LOCATION)) {
    return {
      fieldKey: 'location',
      fieldName: fieldName,
      taxonomyFieldSelector: selectors.LOCATION
    };
  }
  if (fieldName === 'in_loc' || $changedField.is(selectors.IN_LOC)) {
    return {
      fieldKey: 'location',
      fieldName: fieldName,
      taxonomyFieldSelector: TAXONOMY_SELECTOR_SEARCH_FORM_FIELD
    };
  }
  if ((fieldName === 'address' || $changedField.is("input[name='address']")) && $changedField.closest('.directorist-search-location').length) {
    return {
      fieldKey: 'location',
      fieldName: fieldName,
      taxonomyFieldSelector: TAXONOMY_SELECTOR_SEARCH_FORM_ADDRESS
    };
  }
  if (fieldName === 'in_cat' || $changedField.is(selectors.IN_CAT)) {
    return {
      fieldKey: 'category',
      fieldName: fieldName,
      taxonomyFieldSelector: TAXONOMY_SELECTOR_SEARCH_FORM_FIELD
    };
  }
  if (fieldName === 'in_tag[]' || $changedField.is(selectors.IN_TAG)) {
    return {
      fieldKey: 'tag',
      fieldName: fieldName,
      taxonomyFieldSelector: TAXONOMY_SELECTOR_SEARCH_FORM_FIELD
    };
  }
  return {
    fieldKey: fieldKey,
    fieldName: fieldName,
    taxonomyFieldSelector: null
  };
}

/**
 * Extract field key/name from clear button's parent search field wrapper.
 * @param {jQuery} $fieldWrap - .directorist-search-field wrapper
 * @returns {{ fieldKey: string|null, fieldName: string|null, $changedField: jQuery|null }}
 */
function extractFieldFromClearButton($fieldWrap) {
  if (!$fieldWrap || !$fieldWrap.length) return {
    fieldKey: null,
    fieldName: null,
    $changedField: null
  };
  var checks = [{
    sel: 'input[name="in_tag[]"]',
    fieldKey: 'tag',
    fieldName: 'in_tag[]'
  }, {
    sel: "select[name='in_cat']",
    fieldKey: 'category',
    fieldName: 'in_cat'
  }, {
    sel: "select[name='in_loc']",
    fieldKey: 'location',
    fieldName: 'in_loc'
  }, {
    sel: 'input[name="search_by_rating[]"]',
    fieldKey: 'search_by_rating',
    fieldName: 'search_by_rating[]'
  }, {
    sel: 'input[name="email"]',
    fieldKey: 'email',
    fieldName: 'email'
  }, {
    sel: 'input[name="phone"]',
    fieldKey: 'phone',
    fieldName: 'phone'
  }, {
    sel: 'input[name="phone2"]',
    fieldKey: 'phone2',
    fieldName: 'phone2'
  }, {
    sel: 'input[name="fax"]',
    fieldKey: 'fax',
    fieldName: 'fax'
  }, {
    sel: 'input[name="website"]',
    fieldKey: 'website',
    fieldName: 'website'
  }, {
    sel: 'input[name="zip"]',
    fieldKey: 'zip',
    fieldName: 'zip'
  }];
  for (var _i = 0, _checks = checks; _i < _checks.length; _i++) {
    var c = _checks[_i];
    var $el = $fieldWrap.find(c.sel).first();
    if ($el.length) return {
      fieldKey: c.fieldKey,
      fieldName: c.fieldName,
      $changedField: $el
    };
  }
  if ($fieldWrap.find('input[name="address"]').length && $fieldWrap.hasClass('directorist-search-location')) {
    return {
      fieldKey: 'location',
      fieldName: 'address',
      $changedField: $fieldWrap.find('input[name="address"]').first()
    };
  }
  if ($fieldWrap.find('input[name="q"]').length || $fieldWrap.hasClass('directorist-search-query')) {
    return {
      fieldKey: 'title',
      fieldName: 'q',
      $changedField: $fieldWrap.find('input[name="q"]').first()
    };
  }
  var $customInput = $fieldWrap.find('select[name^="custom_field["], input[name^="custom_field["]').first();
  if ($customInput.length) {
    var fieldName = $customInput.attr('name');
    var match = fieldName && fieldName.match(/^custom_field\[([^\]]+)\]/);
    if (match) return {
      fieldKey: match[1],
      fieldName: fieldName,
      $changedField: $customInput
    };
  }
  return {
    fieldKey: null,
    fieldName: null,
    $changedField: null
  };
}

/**
 * Normalize condition.field to match actual field name in DOM.
 * Search form custom fields use name="custom_field[custom-select]" etc.
 * Conditions may be stored as "select", "select_2" from builder.
 * @param {string} fieldKey - Raw field key from condition
 * @returns {string} Normalized key (e.g. 'select_2' → 'custom-select-2')
 */
function normalizeConditionFieldKey(fieldKey) {
  if (!fieldKey || typeof fieldKey !== 'string') {
    return fieldKey;
  }
  var key = String(fieldKey).trim();
  if (key.startsWith('custom-')) {
    return key;
  }
  var m = key.match(/^(select|radio|checkbox)(?:_(\d+))?$/i);
  if (m) {
    var suffix = m[2] ? '-' + m[2] : '';
    return 'custom-' + m[1].toLowerCase() + suffix;
  }
  return key;
}

/***/ }),

/***/ "./assets/src/js/global/components/conditional-logic/get-field-value.js":
/*!******************************************************************************!*\
  !*** ./assets/src/js/global/components/conditional-logic/get-field-value.js ***!
  \******************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   getFieldValue: function() { return /* binding */ getFieldValue; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js");
/* harmony import */ var _field_mapping_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./field-mapping.js */ "./assets/src/js/global/components/conditional-logic/field-mapping.js");
/* harmony import */ var _helpers_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./helpers.js */ "./assets/src/js/global/components/conditional-logic/helpers.js");

function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t.return || t.return(); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
/**
 * Extract field values from form elements
 */



/**
 * Get field value from form by field key.
 * @param {string} fieldKey - Field key (e.g. 'category', 'custom-select')
 * @param {jQuery} $ - jQuery
 * @returns {*} Value, array, 'uploaded', or null
 */
function getFieldValue(fieldKey, $) {
  // Special handling for privacy_policy field (checkbox field)
  if (fieldKey === 'privacy_policy') {
    var $privacyCheckbox = $('input[name="privacy_policy"], #directorist_submit_privacy_policy');
    if ($privacyCheckbox.length) {
      return $privacyCheckbox.is(':checked') ? 'checked' : '';
    }
    return '';
  }

  // Special handling for listing_img field (image upload field)
  if (fieldKey === 'listing_img' || fieldKey === 'image_upload') {
    var $imageUploadWrapper = $('.directorist-form-image-upload-field');
    if ($imageUploadWrapper.length) {
      var $previewSection = $imageUploadWrapper.find('.ezmu__preview-section.ezmu--show');
      if ($previewSection.length > 0) {
        return 'uploaded';
      }
    }
    return null;
  }
  var $field = null;

  // Handle category, tag, and location fields
  if (fieldKey === 'category' || fieldKey === 'categories' || fieldKey === 'admin_category_select[]' || fieldKey === 'in_cat') {
    $field = $(_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.SELECTORS.CATEGORY_SELECT).first();
    if (!$field.length) {
      var $checkboxes = $(_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.SELECTORS.CATEGORY_CHECKLIST_CHECKED);
      if ($checkboxes.length) {
        return $checkboxes.map(function () {
          return $(this).val();
        }).get();
      }
      return [];
    }
  } else if (fieldKey === 'tag' || fieldKey === 'tags' || fieldKey === 'tax_input[at_biz_dir-tags][]' || fieldKey === 'in_tag[]') {
    $field = $(_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.SELECTORS.TAGS_SELECT).first();
    if (!$field.length) {
      var _$checkboxes = $(_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.SELECTORS.TAGS_CHECKLIST_CHECKED);
      if (_$checkboxes.length) {
        return _$checkboxes.map(function () {
          return $(this).val();
        }).get();
      }
      return [];
    }
    if ($field.is('div') && !$field.is('select')) {
      var $tagItems = $(_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.SELECTORS.TAG_CHECKLIST_ITEMS);
      if ($tagItems.length) {
        return $tagItems.map(function () {
          return $(this).clone().children().remove().end().text().trim();
        }).get().filter(Boolean);
      }
      var $textarea = $(_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.SELECTORS.TAG_TEXTAREA);
      if ($textarea.length && $textarea.val()) {
        var raw = String($textarea.val()).trim();
        if (raw) {
          return raw.split(/[,\u00A0]+/).map(function (s) {
            return s.trim();
          }).filter(Boolean);
        }
      }
      return [];
    }
  } else if (fieldKey === 'location' || fieldKey === 'locations' || fieldKey === 'tax_input[at_biz_dir-location][]' || fieldKey === 'in_loc' || fieldKey === 'address') {
    $field = $(_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.SELECTORS.LOCATION_SELECT).first();
    if (!$field.length) {
      var $addressInput = $(_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.SELECTORS.SEARCH_ADDRESS);
      if ($addressInput.length) {
        var val = $addressInput.val();
        return val && val.trim() ? [val.trim()] : [];
      }
      var _$checkboxes2 = $(_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.SELECTORS.LOCATION_CHECKLIST_CHECKED);
      if (_$checkboxes2.length) {
        return _$checkboxes2.map(function () {
          return $(this).val();
        }).get();
      }
      return [];
    }
  }

  // Search form: in_tag[] checkboxes (1 value per selected = label or ID)
  if ((fieldKey === 'in_tag[]' || fieldKey === 'tag' || fieldKey === 'tags') && $field && $field.is(_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.SELECTORS.IN_TAG)) {
    var _$checkboxes3 = $("".concat(_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.SELECTORS.IN_TAG, ":checked"));
    if (_$checkboxes3.length) {
      var values = [];
      _$checkboxes3.each(function () {
        var $cb = $(this);
        var $label = $cb.siblings('label').first();
        if (!$label.length && $cb.attr('id')) {
          $label = $("label[for=\"".concat($cb.attr('id'), "\"]"));
        }
        var label = $label.length ? $label.text().trim() : '';
        if (label) {
          values.push(label);
        } else {
          var id = $cb.val();
          if (id) values.push(String(id));
        }
      });
      return values;
    }
    return [];
  }
  var isTaxonomyField = (0,_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.isTaxonomySelectField)($field);
  if (isTaxonomyField) {
    // Strategy 1: data-selected-label AND data-selected-id
    var cachedLabels = $field.attr('data-selected-label');
    var cachedIds = $field.attr('data-selected-id');
    var isTagField = $field.is(_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.SELECTORS.TAGS);
    if (cachedLabels && cachedLabels.trim()) {
      var parsedLabels = (0,_helpers_js__WEBPACK_IMPORTED_MODULE_2__.parseLabelsString)(cachedLabels);
      var parsedIds = cachedIds ? (0,_helpers_js__WEBPACK_IMPORTED_MODULE_2__.parseIdsString)(cachedIds) : [];
      var combined = [];
      if (isTagField) {
        parsedLabels.forEach(function (label) {
          if (label) combined.push(label);
        });
        parsedIds.forEach(function (id) {
          if (id && !parsedLabels.includes(id)) {
            combined.push(id);
          }
        });
      } else {
        // Category/location: return IDs only so "is" = strict match (not "contains")
        parsedIds.forEach(function (id) {
          if (id) combined.push(id);
        });
      }
      if (combined.length > 0) {
        return combined;
      }
    }

    // Strategy 2: Select2 API
    if ($field.hasClass('select2-hidden-accessible') && typeof $field.select2 === 'function') {
      try {
        var selectedData = $field.select2('data');
        if (selectedData && selectedData.length > 0) {
          var _combined = [];
          var _isTagField = $field.is(_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.SELECTORS.TAGS);
          selectedData.forEach(function (item) {
            if (_isTagField) {
              if (item.text) _combined.push(item.text);
              if (item.id && item.id !== item.text) {
                _combined.push(String(item.id));
              }
            } else {
              // Category/location: IDs only for strict "is" match
              if (item.id) _combined.push(String(item.id));
            }
          });
          if (_combined.length > 0) {
            $field.attr('data-selected-label', selectedData.map(function (item) {
              return item.text || '';
            }).filter(function (t) {
              return t;
            }).join(','));
            $field.attr('data-selected-id', selectedData.map(function (item) {
              return item.id || '';
            }).filter(function (id) {
              return id;
            }).join(','));
            return _combined;
          }
        }
      } catch (e) {
        // Select2 might not be initialized yet
      }
    }

    // Strategy 3: Select2 DOM container
    var $select2Container = $field.next('.select2-container');
    if ($select2Container.length) {
      var labels = (0,_helpers_js__WEBPACK_IMPORTED_MODULE_2__.getLabelsFromSelect2Container)($select2Container, $);
      if (labels.length > 0) {
        var _val = $field.val();
        var ids = Array.isArray(_val) ? _val : _val ? [_val] : [];
        var _combined2 = [];
        if (isTagField) {
          labels.forEach(function (label) {
            if (label) _combined2.push(label);
          });
        }
        ids.forEach(function (id) {
          if (id) _combined2.push(String(id));
        });
        if (_combined2.length > 0) {
          $field.attr('data-selected-label', labels.join(','));
          $field.attr('data-selected-id', ids.join(','));
          return _combined2;
        }
      }
    }

    // Strategy 4: Fallback to select option text and values
    var _val2 = $field.val();
    if (_val2) {
      var _values = Array.isArray(_val2) ? _val2 : [_val2];
      if (_values.length > 0) {
        var _combined3 = [];
        var _labels = [];
        var _ids = [];
        var _isTagField2 = $field.is(_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.SELECTORS.TAGS);
        _values.forEach(function (val) {
          if (_isTagField2) {
            var tagName = String(val).trim();
            if (tagName) {
              _labels.push(tagName);
              _combined3.push(tagName);
            }
          } else {
            var $option = $field.find("option[value=\"".concat(val, "\"]"));
            if ($option.length) {
              var label = $option.text().trim();
              if (label) _labels.push(label);
              _ids.push(String(val));
              _combined3.push(String(val));
            } else {
              _combined3.push(String(val));
            }
          }
        });
        if (_combined3.length > 0) {
          if (_labels.length > 0) {
            $field.attr('data-selected-label', _labels.join(','));
          }
          if (_ids.length > 0) {
            $field.attr('data-selected-id', _ids.join(','));
          } else if (_isTagField2 && _combined3.length > 0) {
            $field.attr('data-selected-id', _combined3.join(','));
          }
          return _combined3;
        }
      }
    }
    return [];
  }

  // Reset $field for regular fields (not taxonomy selects)
  if ($field && !(0,_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.isTaxonomySelectField)($field)) {
    $field = null;
  }
  var mappedSelector = (0,_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.mapFieldKeyToSelector)(fieldKey);
  if (mappedSelector) {
    $field = $(mappedSelector).first();
  }
  if (!$field || !$field.length) {
    var potentialFieldKey = _field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.WIDGET_KEY_TO_FIELD_KEY[fieldKey] || fieldKey;
    if (!fieldKey.startsWith('custom-') && !potentialFieldKey.startsWith('custom-')) {
      var customFieldKey = "custom-".concat(fieldKey);
      var customFieldIdEscaped = (0,_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.escapeCssId)(customFieldKey);
      var $customField = $("[name=\"".concat(customFieldKey, "\"], #").concat(customFieldIdEscaped, ", .directorist-form-group[data-field-key=\"").concat(customFieldKey, "\"] select, .directorist-form-group[data-field-key=\"").concat(customFieldKey, "\"] input[type=\"checkbox\"], .directorist-form-group[data-field-key=\"").concat(customFieldKey, "\"] input[type=\"radio\"]")).first();
      if ($customField.length) {
        potentialFieldKey = customFieldKey;
      }
    }
    var fieldKeyEscaped = (0,_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.escapeCssId)(fieldKey);
    var potentialFieldKeyEscaped = (0,_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.escapeCssId)(potentialFieldKey);
    var selectors = ["[name=\"".concat(fieldKey, "\"]"), "[name=\"".concat(fieldKey, "[]\"]"), "#".concat(fieldKeyEscaped), "[name=\"".concat(potentialFieldKey, "\"]"), "[name=\"".concat(potentialFieldKey, "[]\"]"), "#".concat(potentialFieldKeyEscaped), ".directorist-form-".concat(fieldKeyEscaped, "-field input"), ".directorist-form-".concat(fieldKeyEscaped, "-field select"), ".directorist-form-".concat(fieldKeyEscaped, "-field textarea"), ".directorist-form-".concat(fieldKeyEscaped, "-field input[type=\"file\"]"), ".directorist-form-".concat(potentialFieldKeyEscaped, "-field input"), ".directorist-form-".concat(potentialFieldKeyEscaped, "-field select"), ".directorist-form-".concat(potentialFieldKeyEscaped, "-field textarea"), ".directorist-form-".concat(potentialFieldKeyEscaped, "-field input[type=\"file\"]"), "input[name*=\"".concat(fieldKey, "\"]"), "select[name*=\"".concat(fieldKey, "\"]"), "input[type=\"file\"][name*=\"".concat(fieldKey, "\"]"), "input[name*=\"".concat(potentialFieldKey, "\"]"), "select[name*=\"".concat(potentialFieldKey, "\"]"), "input[type=\"file\"][name*=\"".concat(potentialFieldKey, "\"]"), ".directorist-form-group[data-field-key=\"".concat(fieldKey, "\"] input"), ".directorist-form-group[data-field-key=\"".concat(fieldKey, "\"] select"), ".directorist-form-group[data-field-key=\"".concat(fieldKey, "\"] textarea"), ".directorist-form-group[data-field-key=\"".concat(fieldKey, "\"] input[type=\"file\"]"), ".directorist-form-group[data-field-key=\"".concat(potentialFieldKey, "\"] input"), ".directorist-form-group[data-field-key=\"".concat(potentialFieldKey, "\"] select"), ".directorist-form-group[data-field-key=\"".concat(potentialFieldKey, "\"] textarea"), ".directorist-form-group[data-field-key=\"".concat(potentialFieldKey, "\"] input[type=\"file\"]"), ".directorist-custom-field-select select[name=\"".concat(fieldKey, "\"]"), ".directorist-custom-field-select select#".concat(fieldKeyEscaped), ".directorist-custom-field-select select[name=\"".concat(potentialFieldKey, "\"]"), ".directorist-custom-field-select select#".concat(potentialFieldKeyEscaped), ".directorist-form-group.directorist-custom-field-select select[name=\"".concat(fieldKey, "\"]"), ".directorist-form-group.directorist-custom-field-select select#".concat(fieldKeyEscaped), ".directorist-form-group.directorist-custom-field-select select[name=\"".concat(potentialFieldKey, "\"]"), ".directorist-form-group.directorist-custom-field-select select#".concat(potentialFieldKeyEscaped), "select[name=\"custom_field[".concat(fieldKey, "]\"]"), "input[name=\"custom_field[".concat(fieldKey, "]\"]"), "input[name=\"custom_field[".concat(fieldKey, "][]\"]"), "select[name=\"custom_field[".concat(potentialFieldKey, "]\"]"), "input[name=\"custom_field[".concat(potentialFieldKey, "]\"]"), "input[name=\"custom_field[".concat(potentialFieldKey, "][]\"]"), ".directorist-search-field select[name=\"custom_field[".concat(fieldKey, "]\"]"), ".directorist-search-field input[name=\"custom_field[".concat(fieldKey, "]\"]"), ".directorist-search-field select[name=\"custom_field[".concat(potentialFieldKey, "]\"]"), ".directorist-search-field input[name=\"custom_field[".concat(potentialFieldKey, "]\"]")].concat((0,_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__["default"])(fieldKey && !fieldKey.startsWith('custom-') ? ["[name=\"custom-".concat(fieldKey, "\"]"), "[name=\"custom-".concat(fieldKey.replace(/_/g, '-'), "\"]"), "select[name=\"custom_field[custom-".concat(fieldKey.replace(/_/g, '-'), "]\"]"), "input[name=\"custom_field[custom-".concat(fieldKey.replace(/_/g, '-'), "]\"]"), "#".concat((0,_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.escapeCssId)('custom-' + fieldKey)), ".directorist-form-group[data-field-key=\"custom-".concat(fieldKey, "\"] select"), ".directorist-form-group[data-field-key=\"custom-".concat(fieldKey, "\"] input"), ".directorist-custom-field-select select[name=\"custom-".concat(fieldKey, "\"]"), ".directorist-custom-field-select select#".concat((0,_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.escapeCssId)('custom-' + fieldKey))] : []));
    var _iterator = _createForOfIteratorHelper(selectors),
      _step;
    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var selector = _step.value;
        $field = $(selector).first();
        if ($field.length) {
          break;
        }
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
  }
  if (!$field || !$field.length) {
    return null;
  }

  // Checkboxes and radio buttons
  if ($field.is(':checkbox') || $field.is(':radio')) {
    if ($field.is('[name$="[]"]') || $field.attr('name') && $field.attr('name').includes('[]')) {
      var _values2 = [];
      var nameAttr = $field.attr('name');
      $("[name=\"".concat(nameAttr, "\"]")).filter(':checked').each(function () {
        _values2.push($(this).val());
      });
      return _values2;
    }
    if ($field.is(':radio')) {
      var _nameAttr = $field.attr('name');
      var $checkedRadio = $("[name=\"".concat(_nameAttr, "\"]:checked"));
      return $checkedRadio.length ? $checkedRadio.val() : null;
    }
    return $field.is(':checked') ? $field.val() : null;
  }

  // Multi-select
  if ($field.is('select[multiple]') || $field.prop('multiple')) {
    var _val3 = $field.val();
    return Array.isArray(_val3) ? _val3 : _val3 ? [_val3] : [];
  }

  // Select2 fields
  if ($field.hasClass('select2-hidden-accessible')) {
    try {
      var _selectedData = $field.select2('data');
      if (_selectedData && _selectedData.length > 0) {
        return _selectedData.map(function (item) {
          return item.text || item.id;
        });
      }
    } catch (e) {}
  }

  // TinyMCE editor
  if (typeof tinymce !== 'undefined' && $field.length) {
    try {
      var editorId = $field.attr('id');
      if (editorId) {
        var editor = tinymce.get(editorId);
        if (editor && typeof editor.isHidden === 'function' && !editor.isHidden()) {
          var content = typeof editor.getContent === 'function' ? editor.getContent() : '';
          if (content) {
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = content;
            return tempDiv.textContent || tempDiv.innerText || '';
          }
        }
      }
    } catch (e) {
      // TinyMCE may not be ready or editor not found
    }
  }

  // File upload fields
  var $fileWrapper = $field.closest('.directorist-form-group, .directorist-custom-field-file, .directorist-custom-field-file-upload');
  if (!$fileWrapper.length) {
    $fileWrapper = $field.closest('.directorist-form-group, .directorist-custom-field-file, .directorist-custom-field-file-upload');
  }
  var isFileUploadField = $field.is('input[type="file"]') || $field.closest('.directorist-custom-field-file').length || $field.closest('.directorist-custom-field-file-upload').length || $fileWrapper.length && ($fileWrapper.hasClass('directorist-custom-field-file') || $fileWrapper.hasClass('directorist-custom-field-file-upload') || $fileWrapper.find('.plupload-upload-ui, .plupload-thumbs').length > 0);
  if (isFileUploadField) {
    if ($field.is('input[type="file"]') && $field[0] && $field[0].files && $field[0].files.length > 0) {
      return 'uploaded';
    }
    if ($fileWrapper.length) {
      var $thumbsContainer = $fileWrapper.find('.plupload-thumbs');
      if ($thumbsContainer.length && $thumbsContainer.find('.thumb').length > 0) {
        return 'uploaded';
      }
    }
    if ($fileWrapper.length) {
      var fieldKeyFromWrapper = $fileWrapper.attr('data-field-key') || $fileWrapper.find('[data-field-key]').first().attr('data-field-key');
      if (fieldKeyFromWrapper) {
        var $hiddenInput = $fileWrapper.find("input[type=\"hidden\"][name=\"".concat(fieldKeyFromWrapper, "\"]"));
        if ($hiddenInput.length && $hiddenInput.val() && $hiddenInput.val().trim() !== '' && $hiddenInput.val() !== 'null') {
          return 'uploaded';
        }
      }
    }
    if ($fileWrapper.length) {
      var hasUploadedFiles = $fileWrapper.find('.directorist-file-list-item, .directorist-uploaded-file, .directorist-file-item, [data-file-id], .thumb').length > 0 || $fileWrapper.find('input[type="hidden"][name*="_file_id"], input[type="hidden"][name*="_file_url"]').filter(function () {
        return $(this).val() && $(this).val().trim() !== '';
      }).length > 0;
      if (hasUploadedFiles) {
        return 'uploaded';
      }
    }
    return null;
  }
  return $field.val() || null;
}

/***/ }),

/***/ "./assets/src/js/global/components/conditional-logic/helpers.js":
/*!**********************************************************************!*\
  !*** ./assets/src/js/global/components/conditional-logic/helpers.js ***!
  \**********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   getLabelsFromSelect2Container: function() { return /* binding */ getLabelsFromSelect2Container; },
/* harmony export */   normalizeOperator: function() { return /* binding */ normalizeOperator; },
/* harmony export */   parseIdsString: function() { return /* binding */ parseIdsString; },
/* harmony export */   parseLabelsString: function() { return /* binding */ parseLabelsString; },
/* harmony export */   syncSelect2DataAttributes: function() { return /* binding */ syncSelect2DataAttributes; }
/* harmony export */ });
/* harmony import */ var _field_mapping_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./field-mapping.js */ "./assets/src/js/global/components/conditional-logic/field-mapping.js");
/**
 * Helper utilities for conditional logic
 */


/**
 * Extract labels from Select2 selection container
 * @param {jQuery} $container - Select2 container element
 * @param {jQuery} $ - jQuery instance
 * @returns {string[]} Array of labels
 */
function getLabelsFromSelect2Container($container, $) {
  if (!$container || !$container.length || !$) {
    return [];
  }
  var labels = [];
  $container.find('.select2-selection__choice').each(function () {
    var $choice = $(this);
    var label = $choice.find('.select2-selection__choice__display').text().trim() || $choice.text().trim().replace('×', '').trim();
    if (label) {
      labels.push(label);
    }
  });
  return labels;
}

/**
 * Parse comma-separated labels string
 * @param {string} labelsStr - Comma-separated labels
 * @returns {string[]} Array of trimmed, non-empty labels
 */
function parseLabelsString(labelsStr) {
  if (!labelsStr || !labelsStr.trim()) {
    return [];
  }
  return labelsStr.split(',').map(function (label) {
    return label.trim();
  }).filter(function (label) {
    return label.length > 0;
  });
}

/**
 * Parse comma-separated IDs string
 * Accepts both numeric IDs and string slugs (e.g. taxonomy slugs)
 * @param {string} idsStr - Comma-separated IDs
 * @returns {string[]} Array of trimmed, non-empty ID strings
 */
function parseIdsString(idsStr) {
  if (!idsStr || !idsStr.trim()) {
    return [];
  }
  return idsStr.split(',').map(function (id) {
    return id.trim();
  }).filter(function (id) {
    return id.length > 0;
  });
}

/**
 * Sync data-selected-label and data-selected-id from Select2/DOM to element.
 * Handles Select2 API, DOM fallback, and admin checklist checkboxes.
 * @param {jQuery} $field - The select/field element
 * @param {jQuery} $ - jQuery instance
 * @returns {void}
 */
function syncSelect2DataAttributes($field, $) {
  if (!$field || !$field.length || !$) return;
  var labels = [];
  var ids = [];
  var isChecklist = $field.is(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.CATEGORY_CHECKLIST) || $field.closest(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.CATEGORY_CHECKLIST).length > 0 || $field.is(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.LOCATION_CHECKLIST) || $field.closest(_field_mapping_js__WEBPACK_IMPORTED_MODULE_0__.SELECTORS.LOCATION_CHECKLIST).length > 0;
  if (!isChecklist && $field.hasClass('select2-hidden-accessible') && typeof $field.select2 === 'function') {
    try {
      var selectedData = $field.select2('data');
      if (selectedData && selectedData.length > 0) {
        selectedData.forEach(function (item) {
          if (item.text) labels.push(item.text);
          if (item.id) ids.push(String(item.id));
        });
      }
    } catch (e) {
      // Select2 might throw if not initialized
    }
  }
  if (isChecklist && labels.length === 0 && ids.length === 0) {
    $field.find('input:checked').each(function () {
      var $cb = $(this);
      ids.push(String($cb.val()));
      var labelText = $cb.closest('label').text().trim();
      if (labelText) labels.push(labelText);
    });
  }
  if (labels.length === 0) {
    var $container = $field.next('.select2-container');
    if ($container.length) {
      getLabelsFromSelect2Container($container, $).forEach(function (l) {
        return labels.push(l);
      });
    }
  }
  var val = $field.val();
  if (val && ids.length === 0) {
    var values = Array.isArray(val) ? val : [val];
    values.forEach(function (id) {
      if (id) ids.push(String(id));
    });
  }
  $field.attr('data-selected-label', labels.join(','));
  $field.attr('data-selected-id', ids.join(','));
}

/**
 * Normalize AND/OR operator - handle null, empty, case variations
 * @param {*} op - Operator value
 * @param {string} defaultOp - Default when invalid (e.g. 'AND' or 'OR')
 * @returns {string} 'AND' or 'OR'
 */
function normalizeOperator(op) {
  var defaultOp = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'OR';
  if (op === null || op === undefined || op === '') {
    return defaultOp;
  }
  var normalized = String(op).trim().toUpperCase();
  return normalized || defaultOp;
}

/***/ }),

/***/ "./assets/src/js/global/components/conditional-logic/init.js":
/*!*******************************************************************!*\
  !*** ./assets/src/js/global/components/conditional-logic/init.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   applyConditionalLogic: function() { return /* binding */ applyConditionalLogic; },
/* harmony export */   initConditionalLogic: function() { return /* binding */ initConditionalLogic; },
/* harmony export */   updateCategoryFieldLabel: function() { return /* binding */ updateCategoryFieldLabel; },
/* harmony export */   watchFieldChanges: function() { return /* binding */ watchFieldChanges; }
/* harmony export */ });
/* harmony import */ var _depends_on_field_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./depends-on-field.js */ "./assets/src/js/global/components/conditional-logic/depends-on-field.js");
/* harmony import */ var _field_mapping_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./field-mapping.js */ "./assets/src/js/global/components/conditional-logic/field-mapping.js");
/* harmony import */ var _helpers_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./helpers.js */ "./assets/src/js/global/components/conditional-logic/helpers.js");
/* harmony import */ var _event_handlers_taxonomy_handlers_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./event-handlers/taxonomy-handlers.js */ "./assets/src/js/global/components/conditional-logic/event-handlers/taxonomy-handlers.js");
/* harmony import */ var _event_handlers_file_upload_handlers_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./event-handlers/file-upload-handlers.js */ "./assets/src/js/global/components/conditional-logic/event-handlers/file-upload-handlers.js");
/* harmony import */ var _event_handlers_form_handlers_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./event-handlers/form-handlers.js */ "./assets/src/js/global/components/conditional-logic/event-handlers/form-handlers.js");
/* harmony import */ var _event_handlers_tinymce_handlers_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./event-handlers/tinymce-handlers.js */ "./assets/src/js/global/components/conditional-logic/event-handlers/tinymce-handlers.js");
/**
 * Init, apply, and event binding for conditional logic.
 * Orchestrates evaluation and delegates to event-handlers for change detection.
 */








/**
 * Apply show/hide and disabled state based on evaluated conditional logic.
 * @param {jQuery} $fieldWrapper - Wrapper with data-conditional-logic
 * @param {Function} evaluateConditionalLogicFn - (conditionalLogic) => boolean
 * @param {jQuery} $
 */
function applyConditionalLogic($fieldWrapper, evaluateConditionalLogicFn, $) {
  var conditionalLogicData = $fieldWrapper.attr('data-conditional-logic');
  if (!conditionalLogicData) {
    return;
  }
  try {
    var decodedData = conditionalLogicData;
    // Decode HTML entities (e.g. &quot;) before JSON.parse
    if (typeof decodedData === 'string') {
      var textarea = document.createElement('textarea');
      textarea.innerHTML = decodedData;
      decodedData = textarea.value;
    }
    var conditionalLogic = JSON.parse(decodedData);
    var shouldShow = evaluateConditionalLogicFn(conditionalLogic);
    if (shouldShow) {
      $fieldWrapper.show();
      $fieldWrapper.find('input, select, textarea').prop('disabled', false);
      var $modalInput = $fieldWrapper.closest('.directorist-search-modal__input');
      if ($modalInput.length) $modalInput.show();
      var $advancedElement = $fieldWrapper.closest('.directorist-advanced-filter__advanced__element');
      if ($advancedElement.length) $advancedElement.show();
      setTinyMCEMode($fieldWrapper, 'design');
    } else {
      $fieldWrapper.hide();
      $fieldWrapper.find('input, select, textarea').prop('disabled', true);
      var _$modalInput = $fieldWrapper.closest('.directorist-search-modal__input');
      if (_$modalInput.length) _$modalInput.hide();
      var _$advancedElement = $fieldWrapper.closest('.directorist-advanced-filter__advanced__element');
      if (_$advancedElement.length) _$advancedElement.hide();
      setTinyMCEMode($fieldWrapper, 'readonly');
    }
  } catch (e) {
    console.error('Error parsing conditional logic:', e, {
      conditionalLogicData: conditionalLogicData
    });
  }
}

/** Set TinyMCE design/readonly mode when field visibility changes. */
function setTinyMCEMode($fieldWrapper, mode) {
  if (!$fieldWrapper.find('textarea').length || typeof tinymce === 'undefined') return;
  try {
    var editorId = $fieldWrapper.find('textarea').attr('id');
    if (editorId) {
      var editor = tinymce.get(editorId);
      if (editor && typeof editor.setMode === 'function') {
        editor.setMode(mode);
      }
    }
  } catch (e) {}
}

/**
 * Initialize conditional logic for all fields
 * @param {Function} getWrapperFn
 * @param {Function} getFieldValueFn
 * @param {Function} applyConditionalLogicFn
 * @param {jQuery} $
 * @param {Array} [adminTargets] - Optional. For admin: [{selector, fieldKey, conditionalLogic}]
 */
function initConditionalLogic(getWrapperFn, getFieldValueFn, applyConditionalLogicFn, $) {
  var adminTargets = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : [];
  var $categoryField = $(_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.SELECTORS.CATEGORY);
  if ($categoryField.length && $categoryField.hasClass('select2-hidden-accessible') && $categoryField.is('select') && typeof $categoryField.select2 === 'function') {
    try {
      var selectedData = $categoryField.select2('data');
      if (selectedData && selectedData.length > 0) {
        $categoryField.attr('data-selected-label', selectedData.map(function (item) {
          return item.text || '';
        }).filter(function (item) {
          return item.length > 0;
        }).join(','));
      }
    } catch (e) {}
  }
  var $formWrapper = $(getWrapperFn());
  var $fieldsWithConditionalLogic = $formWrapper.find('.directorist-form-group[data-conditional-logic]');
  if ($fieldsWithConditionalLogic.length === 0) {
    $fieldsWithConditionalLogic = $('.directorist-form-group[data-conditional-logic]');
  }
  $fieldsWithConditionalLogic.each(function () {
    applyConditionalLogicFn($(this));
  });
  if (adminTargets && Array.isArray(adminTargets) && adminTargets.length > 0) {
    adminTargets.forEach(function (target) {
      var $el = $(target.selector);
      if ($el.length && target.conditionalLogic) {
        $el.addClass('directorist-conditional-logic-target');
        $el.attr('data-conditional-logic', typeof target.conditionalLogic === 'string' ? target.conditionalLogic : JSON.stringify(target.conditionalLogic));
        if (target.fieldKey) $el.attr('data-field-key', target.fieldKey);
        applyConditionalLogicFn($el);
      }
    });
  }
}

/**
 * Watch for field value changes and re-evaluate conditional logic.
 * Sets up taxonomy, form, file-upload, and TinyMCE handlers.
 * @param {Function} getWrapperFn - () => form selector
 * @param {Function} getFieldValueFn - (fieldKey) => value
 * @param {Function} applyConditionalLogicFn - ($fieldWrapper) => void
 * @param {jQuery} $
 */
function watchFieldChanges(getWrapperFn, getFieldValueFn, applyConditionalLogicFn, $) {
  function triggerConditionalLogicEvaluation(fieldName, fieldKey, $changedField) {
    var $fieldsWithLogic = $('.directorist-form-group[data-conditional-logic], .directorist-conditional-logic-target[data-conditional-logic]');
    $fieldsWithLogic.each(function () {
      var $fieldWrapper = $(this);
      var conditionalLogicData = $fieldWrapper.attr('data-conditional-logic');
      if (!conditionalLogicData) return;
      try {
        var decodedData = conditionalLogicData;
        if (typeof decodedData === 'string') {
          var textarea = document.createElement('textarea');
          textarea.innerHTML = decodedData;
          decodedData = textarea.value;
        }
        var conditionalLogic = JSON.parse(decodedData);
        var dependsOnField = (0,_depends_on_field_js__WEBPACK_IMPORTED_MODULE_0__.fieldDependsOnChange)(conditionalLogic, fieldKey, fieldName, $changedField, _field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.normalizeConditionFieldKey);
        if (dependsOnField) {
          applyConditionalLogicFn($fieldWrapper);
        }
      } catch (e) {
        console.error('Error in conditional logic evaluation:', e);
      }
    });
  }
  (0,_event_handlers_taxonomy_handlers_js__WEBPACK_IMPORTED_MODULE_3__.setupTaxonomyHandlers)($, triggerConditionalLogicEvaluation);
  (0,_event_handlers_form_handlers_js__WEBPACK_IMPORTED_MODULE_5__.setupFormHandlers)(getWrapperFn, $, triggerConditionalLogicEvaluation);
  (0,_event_handlers_file_upload_handlers_js__WEBPACK_IMPORTED_MODULE_4__.setupFileUploadHandlers)($, triggerConditionalLogicEvaluation);
  (0,_event_handlers_tinymce_handlers_js__WEBPACK_IMPORTED_MODULE_6__.setupTinyMCEHandlers)($, triggerConditionalLogicEvaluation);
}

/**
 * Update category Select2 data-selected-label and re-run init.
 * Called after category field is changed externally (e.g. AJAX).
 * @param {Function} initConditionalLogicFn
 * @param {jQuery} $
 */
function updateCategoryFieldLabel(initConditionalLogicFn, $) {
  var $field = $(_field_mapping_js__WEBPACK_IMPORTED_MODULE_1__.SELECTORS.CATEGORY);
  if (!$field.length) return;
  setTimeout(function () {
    if ($field.is('select')) {
      (0,_helpers_js__WEBPACK_IMPORTED_MODULE_2__.syncSelect2DataAttributes)($field, $);
    }
    initConditionalLogicFn();
  }, 150);
}

/***/ }),

/***/ "./assets/src/js/global/components/debounce.js":
/*!*****************************************************!*\
  !*** ./assets/src/js/global/components/debounce.js ***!
  \*****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ debounce; }
/* harmony export */ });
function debounce(func, wait, immediate) {
  var timeout;
  return function () {
    var context = this,
      args = arguments;
    var later = function later() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
}

/***/ }),

/***/ "./assets/src/js/global/components/select2-custom-control.js":
/*!*******************************************************************!*\
  !*** ./assets/src/js/global/components/select2-custom-control.js ***!
  \*******************************************************************/
/***/ (function() {

function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t.return || t.return(); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
var $ = jQuery;
window.addEventListener('load', waitAndInit);
window.addEventListener('directorist-search-form-nav-tab-reloaded', waitAndInit);
window.addEventListener('directorist-type-change', waitAndInit);
window.addEventListener('directorist-instant-search-reloaded', waitAndInit);
function waitAndInit() {
  setTimeout(init, 0);
}

// Initialize
function init() {
  // Add custom dropdown toggle button
  selec2_add_custom_dropdown_toggle_button();

  // Add custom close button where needed
  selec2_add_custom_close_button_if_needed();

  // Add custom close button if field contains value on change
  $('.select2-hidden-accessible').on('change', function (e) {
    var value = $(this).children('option:selected').val();
    if (!value) {
      return;
    }
    selec2_add_custom_close_button($(this));
    var selectItems = this.parentElement.querySelectorAll('.select2-selection__choice');
    selectItems.forEach(function (item) {
      item.childNodes && item.childNodes.forEach(function (node) {
        if (node.nodeType && node.nodeType === Node.TEXT_NODE) {
          var originalString = node.textContent;
          var modifiedString = originalString.replace(/^[\s\xa0]+/, '');
          node.textContent = modifiedString;
          item.title = modifiedString;
        }
      });
    });
    var customSelectItem = this.parentElement.querySelector('.select2-selection__rendered');
    customSelectItem.childNodes && customSelectItem.childNodes.forEach(function (node) {
      if (node.nodeType && node.nodeType === Node.TEXT_NODE) {
        var originalString = node.textContent;
        var modifiedString = originalString.replace(/^[\s\xa0]+/, '');
        node.textContent = modifiedString;
      }
    });
  });
}
function selec2_add_custom_dropdown_toggle_button() {
  // Remove Default
  $('.select2-selection__arrow').css({
    display: 'none'
  });
  var addon_container = selec2_get_addon_container('.select2-hidden-accessible');
  if (!addon_container) {
    return;
  }
  var dropdown = addon_container.find('.directorist-select2-dropdown-toggle');
  if (!dropdown.length) {
    // Add Dropdown Toggle Button
    var iconURL = directorist.assets_url + 'icons/font-awesome/svgs/solid/chevron-down.svg';
    var iconHTML = directorist.icon_markup.replace('##URL##', iconURL).replace('##CLASS##', '');
    var dropdownHTML = "<span class=\"directorist-select2-addon directorist-select2-dropdown-toggle\">".concat(iconHTML, "</span>");
    addon_container.append(dropdownHTML);
  }
  var selec2_custom_dropdown = addon_container.find('.directorist-select2-dropdown-toggle');

  // Toggle --is-open class
  $('.select2-hidden-accessible').on('select2:open', function (e) {
    var dropdown_btn = $(this).next().find('.directorist-select2-dropdown-toggle');
    dropdown_btn.addClass('--is-open');
  });
  $('.select2-hidden-accessible').on('select2:close', function (e) {
    var dropdown_btn = $(this).next().find('.directorist-select2-dropdown-toggle');
    dropdown_btn.removeClass('--is-open');
    var dropdownParent = $(this).closest('.directorist-search-field');
    var renderTitle = $(this).next().find('.select2-selection__rendered').attr('title');

    // Check if renderTitle is empty and remove the focus class if so
    if (!renderTitle) {
      dropdownParent.removeClass('input-is-focused');
    } else {
      dropdownParent.addClass('input-has-value');
    }
  });

  // Toggle Dropdown
  selec2_custom_dropdown.on('click', function (e) {
    var isOpen = $(this).hasClass('--is-open');
    var field = $(this).closest('.select2-container').siblings('select:enabled');
    if (isOpen) {
      field.select2('close');
    } else {
      field.select2('open');
    }
  });

  // Adjust space for addons
  selec2_adjust_space_for_addons();
}
function selec2_add_custom_close_button_if_needed() {
  var select2_fields = $('.select2-hidden-accessible');
  if (!select2_fields && !select2_fields.length) {
    return;
  }
  var _iterator = _createForOfIteratorHelper(select2_fields),
    _step;
  try {
    for (_iterator.s(); !(_step = _iterator.n()).done;) {
      var field = _step.value;
      var value = $(field).children('option:selected').val();
      if (!value) {
        continue;
      }
      selec2_add_custom_close_button(field);
    }
  } catch (err) {
    _iterator.e(err);
  } finally {
    _iterator.f();
  }
}
function selec2_add_custom_close_button(field) {
  // Remove Default
  $('.select2-selection__clear').css({
    display: 'none'
  });
  var addon_container = selec2_get_addon_container(field);
  if (!(addon_container && addon_container.length)) {
    return;
  }

  // Remove if already exists
  addon_container.find('.directorist-select2-dropdown-close').remove();

  // Add
  var iconURL = directorist.assets_url + 'icons/font-awesome/svgs/solid/times.svg';
  var iconHTML = directorist.icon_markup.replace('##URL##', iconURL).replace('##CLASS##', '');
  addon_container.prepend("<span class=\"directorist-select2-addon directorist-select2-dropdown-close\">".concat(iconHTML, "</span>"));
  var selec2_custom_close = addon_container.find('.directorist-select2-dropdown-close');
  selec2_custom_close.on('click', function (e) {
    var field = $(this).closest('.select2-container').siblings('select:enabled');
    field.val(null).trigger('change');
    addon_container.find('.directorist-select2-dropdown-close').remove();
    selec2_adjust_space_for_addons();
  });

  // Adjust space for addons
  selec2_adjust_space_for_addons();
}
function selec2_remove_custom_close_button(field) {
  var addon_container = selec2_get_addon_container(field);
  if (!(addon_container && addon_container.length)) {
    return;
  }

  // Remove
  addon_container.find('.directorist-select2-dropdown-close').remove();

  // Adjust space for addons
  selec2_adjust_space_for_addons();
}
function selec2_get_addon_container(field) {
  var container = field ? $(field).next('.select2-container') : $('.select2-container');
  container = $(container).find('.directorist-select2-addons-area');
  if (!container.length) {
    $('.select2-container').append('<span class="directorist-select2-addons-area"></span>');
    container = $('.select2-container').find('.directorist-select2-addons-area');
  }
  var container = field ? $(field).next('.select2-container') : null;
  if (!container) {
    return null;
  }
  var addonsArea = $(container).find('.directorist-select2-addons-area');
  if (!addonsArea.length) {
    container.append('<span class="directorist-select2-addons-area"></span>');
    return container.find('.directorist-select2-addons-area');
  }
  return addonsArea;
}
function selec2_adjust_space_for_addons() {
  var container = $('.select2-container').find('.directorist-select2-addons-area');
  if (!container.length) {
    return;
  }
  var width = container.outerWidth();
  $('.select2-container').find('.select2-selection__rendered').css({
    'padding-right': width + 'px'
  });
}

/***/ }),

/***/ "./assets/src/js/global/components/setup-select2.js":
/*!**********************************************************!*\
  !*** ./assets/src/js/global/components/setup-select2.js ***!
  \**********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _lib_helper__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./../../lib/helper */ "./assets/src/js/lib/helper.js");
/* harmony import */ var _select2_custom_control__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./select2-custom-control */ "./assets/src/js/global/components/select2-custom-control.js");
/* harmony import */ var _select2_custom_control__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_select2_custom_control__WEBPACK_IMPORTED_MODULE_2__);

function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }


var $ = jQuery;
window.addEventListener('load', initSelect2);
document.body.addEventListener('directorist-search-form-nav-tab-reloaded', initSelect2);
document.body.addEventListener('directorist-reload-select2-fields', initSelect2);
window.addEventListener('directorist-instant-search-reloaded', initSelect2);

// Init Static Select 2 Fields
function initSelect2() {
  var selectors = ['.directorist-select select', '#directorist-select-js',
  // Not found in any template
  '#directorist-search-category-js',
  // Not found in any template
  // '#directorist-select-st-s-js',
  // '#directorist-select-sn-s-js',
  // '#directorist-select-mn-e-js',
  // '#directorist-select-tu-e-js',
  // '#directorist-select-wd-s-js',
  // '#directorist-select-wd-e-js',
  // '#directorist-select-th-e-js',
  // '#directorist-select-fr-s-js',
  // '#directorist-select-fr-e-js',
  '.select-basic',
  // Not found in any template
  '#loc-type', '#cat-type', '#at_biz_dir-category', '.bdas-location-search',
  // Not found in any template
  '.bdas-category-search' // Not found in any template
  ];
  selectors.forEach(function (selector) {
    return (0,_lib_helper__WEBPACK_IMPORTED_MODULE_1__.convertToSelect2)(selector);
  });
  initMaybeLazyLoadedTaxonomySelect2();
}

// Init Select2 Ajax Fields
function initMaybeLazyLoadedTaxonomySelect2() {
  var restBase = "".concat(directorist.rest_url, "directorist/v1");
  maybeLazyLoadCategories({
    selector: '.directorist-search-category select',
    url: "".concat(restBase, "/listings/categories")
  });
  maybeLazyLoadCategories({
    selector: '.directorist-form-categories-field select',
    url: "".concat(restBase, "/listings/categories")
  });
  maybeLazyLoadLocations({
    selector: '.directorist-search-location select',
    url: "".concat(restBase, "/listings/locations")
  });
  maybeLazyLoadLocations({
    selector: '.directorist-form-location-field select',
    url: "".concat(restBase, "/listings/locations")
  });
  maybeLazyLoadTags({
    selector: '.directorist-form-tag-field select',
    url: "".concat(restBase, "/listings/tags")
  });
}
function maybeLazyLoadCategories(args) {
  maybeLazyLoadTaxonomyTermsSelect2(_objectSpread(_objectSpread({}, {
    taxonomy: 'categories'
  }), args));
}
function maybeLazyLoadLocations(args) {
  maybeLazyLoadTaxonomyTermsSelect2(_objectSpread(_objectSpread({}, {
    taxonomy: 'locations'
  }), args));
}
function maybeLazyLoadTags(args) {
  maybeLazyLoadTaxonomyTermsSelect2(_objectSpread(_objectSpread({}, {
    taxonomy: 'tags'
  }), args));
}

// maybeLazyLoadTaxonomyTermsSelect2
function maybeLazyLoadTaxonomyTermsSelect2(args) {
  var defaults = {
    selector: '',
    url: '',
    taxonomy: 'tags'
  };
  args = _objectSpread(_objectSpread({}, defaults), args);
  if (!args.selector) {
    return;
  }
  var $el = $(args.selector);
  var $addListing = $el.closest('.directorist-add-listing-form');
  var canCreate = $el.data('allow_new');
  var maxLength = $el.data('max');
  var directoryId = 0;
  if (args.taxonomy !== 'tags') {
    var $searchForm = $el.closest('.directorist-search-form');
    var $archivePage = $el.closest('.directorist-archive-contents');
    var $directory = $addListing.find('input[name="directory_type"]');
    var $navListItem = null;

    // If search page
    if ($searchForm.length) {
      $navListItem = $searchForm.find('.directorist-listing-type-selection__link--current');
    }
    if ($archivePage.length) {
      $navListItem = $archivePage.find('.directorist-type-nav__list li.directorist-type-nav__list__current .directorist-type-nav__link');
    }
    if ($navListItem && $navListItem.length) {
      directoryId = Number($navListItem.data('listing_type_id'));
    }
    if ($directory.length) {
      directoryId = $directory.val();
    }
    if (directoryId) {
      directoryId = Number(directoryId);
    }
  }
  var currentPage = 1;
  var select2Options = {
    allowClear: true,
    tags: canCreate,
    maximumSelectionLength: maxLength,
    width: '100%',
    escapeMarkup: function escapeMarkup(text) {
      return text;
    },
    templateResult: function templateResult(data) {
      if (!data.id) {
        return data.text;
      }

      // Fetch the data-icon attribute
      var iconURI = $(data.element).attr('data-icon');

      // Get the original text
      var originalText = data.text;

      // Match and count leading spaces
      var leadingSpaces = originalText.match(/^\s+/);
      var spaceCount = leadingSpaces ? leadingSpaces[0].length : 0;

      // Trim leading spaces from the original text
      originalText = originalText.trim();

      // Construct the icon element
      var iconElm = iconURI ? "<i class=\"directorist-icon-mask\" aria-hidden=\"true\" style=\"--directorist-icon: url('".concat(iconURI, "')\"></i>") : '';

      // Prepare the combined text (icon + text)
      var combinedText = iconElm + originalText;

      // Create the state container
      var $state = $('<div class="directorist-select2-contents"></div>');

      // Determine the level based on space count
      var level = Math.floor(spaceCount / 8) + 1; // 8 spaces = level 2, 16 spaces = level 3, etc.
      if (level > 1) {
        $state.addClass('item-level-' + level); // Add class for the level (e.g., level-1, level-2, etc.)
      }
      $state.html(combinedText); // Set the combined content (icon + text)

      return $state;
    }
  };
  if (directorist.lazy_load_taxonomy_fields) {
    select2Options.ajax = {
      url: args.url,
      dataType: 'json',
      cache: true,
      delay: 250,
      data: function data(params) {
        currentPage = params.page || 1;
        var query = {
          page: currentPage,
          per_page: args.perPage,
          hide_empty: true
        };

        // Load empty terms on add listings.
        if ($addListing.length) {
          query.hide_empty = false;
        }
        if (params.term) {
          query.search = params.term;
          query.hide_empty = false;
        }
        if (directoryId) {
          query.directory = directoryId;
        }
        return query;
      },
      processResults: function processResults(data) {
        return {
          results: data.items,
          pagination: {
            more: data.paginationMore
          }
        };
      },
      transport: function transport(params, success, failure) {
        var $request = $.ajax(params);
        $request.then(function (data, textStatus, jqXHR) {
          var totalPage = Number(jqXHR.getResponseHeader('x-wp-totalpages'));
          var paginationMore = currentPage < totalPage;
          var items = data.map(function (item) {
            var text = item.name;
            if (!$addListing.length && params.data.search) {
              text = "".concat(item.name, " (").concat(item.count, ")");
            }
            return {
              id: item.id,
              text: text
            };
          });
          return {
            items: items,
            paginationMore: paginationMore
          };
        }).then(success);
        $request.fail(failure);
        return $request;
      }
    };
  }
  $el.length && $el.select2(select2Options);
  if (directorist.lazy_load_taxonomy_fields) {
    function setupSelectedItems($el, selectedId, selectedLabel) {
      if (!$el.length || !selectedId) {
        return;
      }
      var selectedIds = "".concat(selectedId).split(',');
      var selectedLabels = selectedLabel ? "".concat(selectedLabel).split(',') : [];
      selectedIds.forEach(function (id, index) {
        var label = selectedLabels.length >= index + 1 ? selectedLabels[index] : '';
        var option = new Option(label, id, true, true);
        $el.append(option);
        $el.trigger({
          type: 'select2:select',
          params: {
            data: {
              id: id,
              text: label
            }
          }
        });
      });
    }
    setupSelectedItems($el, $el.data('selected-id'), $el.data('selected-label'));
  }
}

/***/ }),

/***/ "./assets/src/js/lib/helper.js":
/*!*************************************!*\
  !*** ./assets/src/js/lib/helper.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   convertToSelect2: function() { return /* binding */ convertToSelect2; },
/* harmony export */   get_dom_data: function() { return /* binding */ get_dom_data; }
/* harmony export */ });
var $ = jQuery;
function get_dom_data(selector, parent) {
  selector = '.directorist-dom-data-' + selector;
  if (!parent) {
    parent = document;
  }
  var el = parent.querySelector(selector);
  if (!el || !el.dataset.value) {
    return {};
  }
  var IS_SCRIPT_DEBUGGING = directorist && directorist.script_debugging && directorist.script_debugging == '1';
  try {
    var value = atob(el.dataset.value);
    return JSON.parse(value);
  } catch (error) {
    if (IS_SCRIPT_DEBUGGING) {
      console.log(el, error);
    }
    return {};
  }
}
function convertToSelect2(selector) {
  var $selector = $(selector);
  var args = {
    allowClear: true,
    width: '100%',
    templateResult: function templateResult(data) {
      if (!data.id) {
        return data.text;
      }
      var iconURI = $(data.element).data('icon');
      var iconElm = "<i class=\"directorist-icon-mask\" aria-hidden=\"true\" style=\"--directorist-icon: url(".concat(iconURI, ")\"></i>");
      var originalText = data.text;
      var modifiedText = originalText.replace(/^(\s*)/, '$1' + iconElm);
      var $state = $("<div class=\"directorist-select2-contents\">".concat(typeof iconURI !== 'undefined' && iconURI !== '' ? modifiedText : originalText, "</div>"));
      return $state;
    }
  };
  var options = $selector.find('option');
  if (options.length && options[0].textContent.length) {
    args.placeholder = options[0].textContent;
  }
  $selector.length && $selector.select2(args);
}


/***/ }),

/***/ "./assets/src/js/public/components/category-custom-fields.js":
/*!*******************************************************************!*\
  !*** ./assets/src/js/public/components/category-custom-fields.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ initSearchCategoryCustomFields; }
/* harmony export */ });
/**
 * @deprecated This file is deprecated. The assign_to feature has been removed.
 * Use conditional_logic instead for field visibility.
 *
 * This file is kept as a no-op for backward compatibility.
 * Since directorist_get_category_custom_field_relations() now returns empty array,
 * this function will have no effect.
 */
function hideAllCustomFieldsExceptSelected(relations, categories, $container) {
  // Deprecated: assign_to feature removed - use conditional_logic instead
  // Early return since relations will always be empty now
  if (!relations || Object.keys(relations).length === 0) {
    return;
  }
  var fields = Object.keys(relations);
  var wrappers = ['.directorist-advanced-filter__advanced__element', '.directorist-search-modal__input', '.directorist-search-field'];
  if (!fields.length) {
    return;
  }

  // Convert categories to array if it's not already
  var categoryArray = Array.isArray(categories) ? categories : [categories];
  fields.forEach(function (field) {
    var fieldCategory = relations[field];

    // Try multiple selectors to find the field
    var $field = null;
    var selectors = ["[name=\"custom_field[".concat(field, "]\"]"), "[name=\"custom_field[".concat(field, "][]\"]"), "[name*=\"".concat(field, "\"]"), "[data-field-key=\"".concat(field, "\"]"), "[id*=\"".concat(field, "\"]")];
    for (var _i = 0, _selectors = selectors; _i < _selectors.length; _i++) {
      var selector = _selectors[_i];
      $field = $container.find(selector);
      if ($field.length > 0) {
        break;
      }
    }
    if (!$field || !$field.length) {
      return;
    }

    // Check if the field category matches any of the selected categories
    var shouldShow = categoryArray.some(function (category) {
      return Number(category) === Number(fieldCategory);
    });
    if (shouldShow) {
      $field.prop('disabled', false);
      wrappers.forEach(function (wrapper) {
        var $wrapper = $field.closest(wrapper);
        if ($wrapper.length) {
          $wrapper.show();
        }
      });
    } else {
      $field.prop('disabled', true);
      wrappers.forEach(function (wrapper) {
        var $wrapper = $field.closest(wrapper);
        if ($wrapper.length) {
          $wrapper.hide();
        }
      });
    }
  });
}

/**
 * @deprecated This function is deprecated. The assign_to feature has been removed.
 * Use conditional_logic instead for field visibility.
 *
 * This function is kept for backward compatibility but will have no effect
 * since category_custom_fields_relations will always be empty.
 */
function initSearchCategoryCustomFields($) {
  // Deprecated: assign_to feature removed - use conditional_logic instead
  // This function is kept as a no-op for backward compatibility

  // Handle multiple search forms and containers
  var containers = ['.directorist-search-contents', '.directorist-archive-contents', '.directorist-search-form', '.directorist-add-listing-form'];
  containers.forEach(function (containerSelector) {
    var $container = $(containerSelector);
    if ($container.length) {
      // Bind events to all category selects within this container
      $container.on('change', '.directorist-category-select, .directorist-search-category select, .bdas-category-search', function (event) {
        var $this = $(this);
        var $form = $this.parents('form');
        var categories = $this.val();
        var attributes = $form.data('atts');

        // If form doesn't have attributes, try container
        if (!attributes) {
          attributes = $container.data('atts');
        }

        // If still no attributes, try document body
        if (!attributes) {
          attributes = $(document.body).data('atts');
        }
        if (!attributes || !attributes.category_custom_fields_relations) {
          return;
        }

        // Handle both single and multiple category selections
        if (categories) {
          // Convert to array if it's a single value
          if (!Array.isArray(categories)) {
            categories = [categories];
          }
          // Convert string values to numbers and filter out empty values
          categories = categories.map(function (cat) {
            return Number(cat);
          }).filter(function (cat) {
            return cat > 0;
          }); // Filter out 0, null, undefined, etc.
        } else {
          categories = [];
        }

        // Use the specific container for field search to avoid conflicts
        hideAllCustomFieldsExceptSelected(attributes.category_custom_fields_relations, categories, $container);
      });

      // Trigger change event on page load for all category selects in this container
      $container.find('.directorist-category-select, .directorist-search-category select, .bdas-category-search').each(function () {
        $(this).trigger('change');
      });
    }
  });

  // Also handle global category selects that might not be in specific containers
  var globalSelectors = '.directorist-category-select, .directorist-search-category select, .bdas-category-search';
  $(document).on('change', globalSelectors, function (event) {
    var $this = $(this);

    // Only handle if not already handled by container-specific handlers
    if (!event.isDefaultPrevented()) {
      var $form = $this.parents('form');
      var categories = $this.val();
      var attributes = $form.data('atts');
      if (!attributes) {
        attributes = $(document.body).data('atts');
      }
      if (!attributes || !attributes.category_custom_fields_relations) {
        return;
      }

      // Handle both single and multiple category selections
      if (categories) {
        if (!Array.isArray(categories)) {
          categories = [categories];
        }
        categories = categories.map(function (cat) {
          return Number(cat);
        }).filter(function (cat) {
          return cat > 0;
        });
      } else {
        categories = [];
      }
      hideAllCustomFieldsExceptSelected(attributes.category_custom_fields_relations, categories, $(document.body));
    }
  });
}

/***/ }),

/***/ "./assets/src/js/public/components/colorPicker.js":
/*!********************************************************!*\
  !*** ./assets/src/js/public/components/colorPicker.js ***!
  \********************************************************/
/***/ (function() {

/* Initialize wpColorPicker */
(function ($) {
  // Make sure the codes in this file runs only once, even if enqueued twice
  if (typeof window.directorist_colorPicker_executed === 'undefined') {
    window.directorist_colorPicker_executed = true;
  } else {
    return;
  }
  window.addEventListener('load', function () {
    /* Initialize wp color picker */
    function colorPickerInit() {
      var wpColorPickers = document.querySelectorAll('.directorist-color-picker-wrap');
      wpColorPickers.forEach(function (wrap) {
        var $pickerInput = $(wrap).find('.directorist-color-picker');
        if ($pickerInput) {
          if ($.fn.wpColorPicker) {
            $pickerInput.wpColorPicker({
              change: function change(event, ui) {
                var color = ui.color.toString();

                // Dispatch custom event
                var colorChangeEvent = new CustomEvent('directorist-color-changed', {
                  detail: {
                    color: color,
                    input: event.target,
                    form: event.target.closest('form')
                  }
                });
                window.dispatchEvent(colorChangeEvent);
              }
            });
          } else {
            console.warn('wpColorPicker is NOT available!');
          }
        }
      });
    }
    colorPickerInit();

    /* Initialize on Directory type change */
    window.addEventListener('directorist-instant-search-reloaded', colorPickerInit);
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/directoristDropdown.js":
/*!****************************************************************!*\
  !*** ./assets/src/js/public/components/directoristDropdown.js ***!
  \****************************************************************/
/***/ (function() {

(function ($) {
  // Make sure the codes in this file runs only once, even if enqueued twice
  if (typeof window.directorist_dropdown_executed === 'undefined') {
    window.directorist_dropdown_executed = true;
  } else {
    return;
  }
  window.addEventListener('load', function () {
    /* custom dropdown */
    var atbdDropdown = document.querySelectorAll('.directorist-dropdown-select');

    // toggle dropdown
    var clickCount = 0;
    if (atbdDropdown !== null) {
      atbdDropdown.forEach(function (el) {
        el.querySelector('.directorist-dropdown-select-toggle').addEventListener('click', function (e) {
          e.preventDefault();
          clickCount++;
          if (clickCount % 2 === 1) {
            document.querySelectorAll('.directorist-dropdown-select-items').forEach(function (elem) {
              elem.classList.remove('directorist-dropdown-select-show');
            });
            el.querySelector('.directorist-dropdown-select-items').classList.add('directorist-dropdown-select-show');
          } else {
            document.querySelectorAll('.directorist-dropdown-select-items').forEach(function (elem) {
              elem.classList.remove('directorist-dropdown-select-show');
            });
          }
        });
      });
    }

    // remvoe toggle when click outside
    document.body.addEventListener('click', function (e) {
      if (e.target.getAttribute('data-drop-toggle') !== 'directorist-dropdown-select-toggle') {
        clickCount = 0;
        document.querySelectorAll('.directorist-dropdown-select-items').forEach(function (el) {
          el.classList.remove('directorist-dropdown-select-show');
        });
      }
    });

    //custom select
    var atbdSelect = document.querySelectorAll('.atbd-drop-select');
    if (atbdSelect !== null) {
      atbdSelect.forEach(function (el) {
        el.querySelectorAll('.directorist-dropdown-select-items').forEach(function (item) {
          item.addEventListener('click', function (e) {
            e.preventDefault();
            el.querySelector('.directorist-dropdown-select-toggle').textContent = e.target.textContent;
            el.querySelectorAll('.directorist-dropdown-select-items').forEach(function (elm) {
              elm.classList.remove('atbd-active');
            });
            item.classList.add('atbd-active');
          });
        });
      });
    }

    // Dropdown
    $('body').on('click', '.directorist-dropdown .directorist-dropdown-toggle', function (e) {
      e.preventDefault();
      $(this).siblings('.directorist-dropdown-option').toggle();
    });

    // Select Option after click
    $('body').on('click', '.directorist-dropdown .directorist-dropdown-option ul li a', function (e) {
      e.preventDefault();
      var optionText = $(this).html();
      $(this).children('.directorist-dropdown-toggle__text').html(optionText);
      $(this).closest('.directorist-dropdown-option').siblings('.directorist-dropdown-toggle').children('.directorist-dropdown-toggle__text').html(optionText);
      $('.directorist-dropdown-option').hide();
    });

    // Hide Clicked Anywhere
    $(document).bind('click', function (e) {
      var clickedDOM = $(e.target);
      if (!clickedDOM.parents().hasClass('directorist-dropdown')) $('.directorist-dropdown-option').hide();
    });

    //atbd_dropdown
    $(document).on('click', '.atbd_dropdown', function (e) {
      if ($(this).attr('class') === 'atbd_dropdown') {
        e.preventDefault();
        $(this).siblings('.atbd_dropdown').removeClass('atbd_drop--active');
        $(this).toggleClass('atbd_drop--active');
        e.stopPropagation();
      }
    });
    $(document).on('click', function (e) {
      if ($(e.target).is('.atbd_dropdown, .atbd_drop--active') === false) {
        $('.atbd_dropdown').removeClass('atbd_drop--active');
      }
    });
    $('body').on('click', '.atbd_dropdown-toggle', function (e) {
      e.preventDefault();
    });

    // Directorist Dropdown
    $('body').on('click', '.directorist-dropdown-js .directorist-dropdown__toggle-js', function (e) {
      e.preventDefault();
      if (!$(this).siblings('.directorist-dropdown__links-js').is(':visible')) {
        $('.directorist-dropdown__links').hide();
      }
      $(this).siblings('.directorist-dropdown__links-js').toggle();
    });
    $('body').on('click', function (e) {
      if (!e.target.closest('.directorist-dropdown-js')) {
        $('.directorist-dropdown__links-js').hide();
      }
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/directoristSelect.js":
/*!**************************************************************!*\
  !*** ./assets/src/js/public/components/directoristSelect.js ***!
  \**************************************************************/
/***/ (function() {

window.addEventListener('load', function () {
  // Make sure the codes in this file runs only once, even if enqueued twice
  if (typeof window.directorist_select_executed === 'undefined') {
    window.directorist_select_executed = true;
  } else {
    return;
  }
  //custom select
  var atbdSelect = document.querySelectorAll('.atbd-drop-select');
  if (atbdSelect !== null) {
    atbdSelect.forEach(function (el) {
      el.querySelectorAll('.atbd-dropdown-item').forEach(function (item) {
        item.addEventListener('click', function (e) {
          e.preventDefault();
          el.querySelector('.atbd-dropdown-toggle').textContent = item.textContent;
          el.querySelectorAll('.atbd-dropdown-item').forEach(function (elm) {
            elm.classList.remove('atbd-active');
          });
          item.classList.add('atbd-active');
        });
      });
    });
  }

  // select data-status
  var atbdSelectData = document.querySelectorAll('.atbd-drop-select.with-sort');
  atbdSelectData.forEach(function (el) {
    el.querySelectorAll('.atbd-dropdown-item').forEach(function (item) {
      var atbd_dropdown = el.querySelector('.atbd-dropdown-toggle');
      var dropdown_item = item.getAttribute('data-status');
      item.addEventListener('click', function (e) {
        atbd_dropdown.setAttribute('data-status', "".concat(dropdown_item));
      });
    });
  });
});

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
/******/ 		if (!(moduleId in __webpack_modules__)) {
/******/ 			delete __webpack_module_cache__[moduleId];
/******/ 			var e = new Error("Cannot find module '" + moduleId + "'");
/******/ 			e.code = 'MODULE_NOT_FOUND';
/******/ 			throw e;
/******/ 		}
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
// This entry needs to be wrapped in an IIFE because it needs to be in strict mode.
!function() {
"use strict";
/*!*********************************************!*\
  !*** ./assets/src/js/public/search-form.js ***!
  \*********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _global_components_conditional_logic__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../global/components/conditional-logic */ "./assets/src/js/global/components/conditional-logic.js");
/* harmony import */ var _global_components_debounce__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../global/components/debounce */ "./assets/src/js/global/components/debounce.js");
/* harmony import */ var _global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./../global/components/select2-custom-control */ "./assets/src/js/global/components/select2-custom-control.js");
/* harmony import */ var _global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_global_components_select2_custom_control__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _global_components_setup_select2__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./../global/components/setup-select2 */ "./assets/src/js/global/components/setup-select2.js");
/* harmony import */ var _components_category_custom_fields__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./components/category-custom-fields */ "./assets/src/js/public/components/category-custom-fields.js");
/* harmony import */ var _components_colorPicker__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./components/colorPicker */ "./assets/src/js/public/components/colorPicker.js");
/* harmony import */ var _components_colorPicker__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_components_colorPicker__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var _components_directoristDropdown__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./components/directoristDropdown */ "./assets/src/js/public/components/directoristDropdown.js");
/* harmony import */ var _components_directoristDropdown__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var _components_directoristSelect__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./components/directoristSelect */ "./assets/src/js/public/components/directoristSelect.js");
/* harmony import */ var _components_directoristSelect__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_components_directoristSelect__WEBPACK_IMPORTED_MODULE_11__);




function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t.return || t.return(); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }








var ViewportAwareDropdown = /*#__PURE__*/function () {
  function ViewportAwareDropdown() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2__["default"])(this, ViewportAwareDropdown);
    this.options = _objectSpread({
      dropdownClass: '.directorist-search-basic-dropdown-content',
      triggerClass: '.directorist-search-basic-dropdown-label',
      activeClass: 'dropdown-content-show',
      upwardClass: 'dropdown-upward',
      offset: 8,
      positioningDelay: 10,
      mutationDelay: 50,
      animationDelay: 300
    }, options);
    this.observer = null;
    this.isInitialized = false;
    this.init();
  }
  return (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3__["default"])(ViewportAwareDropdown, [{
    key: "init",
    value: function init() {
      if (this.isInitialized) return;
      this.bindEvents();
      this.setupMutationObserver();
      this.isInitialized = true;
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var _this2 = this;
      var debouncedResize = (0,_global_components_debounce__WEBPACK_IMPORTED_MODULE_5__["default"])(function () {
        return _this2.updateVisibleDropdowns();
      }, 100);
      var debouncedScroll = (0,_global_components_debounce__WEBPACK_IMPORTED_MODULE_5__["default"])(function () {
        return _this2.updateVisibleDropdowns();
      }, 50);
      window.addEventListener('resize', debouncedResize);
      window.addEventListener('scroll', debouncedScroll);
    }
  }, {
    key: "positionDropdown",
    value: function positionDropdown(trigger) {
      var dropdown = trigger.parentElement.querySelector(this.options.dropdownClass);
      if (!dropdown) return;
      dropdown.classList.remove(this.options.upwardClass);
      var triggerRect = trigger.getBoundingClientRect();
      var dropdownHeight = dropdown.offsetHeight;
      var dropdownWidth = dropdown.offsetWidth;
      var viewportHeight = window.innerHeight;
      var viewportWidth = window.innerWidth;
      var spaceBelow = viewportHeight - triggerRect.bottom;
      var spaceAbove = triggerRect.top;
      var spaceRight = viewportWidth - triggerRect.left;
      var spaceLeft = triggerRect.right;
      var needsUpward = spaceBelow < dropdownHeight + this.options.offset && spaceAbove > spaceBelow;
      var needsLeft = spaceRight < dropdownWidth && spaceLeft > spaceRight;
      if (needsUpward) {
        dropdown.classList.add(this.options.upwardClass);
      }
      this.setDropdownPosition(dropdown, needsUpward, needsLeft);
    }
  }, {
    key: "setDropdownPosition",
    value: function setDropdownPosition(dropdown, upward, left) {
      var isRTL = document.dir === 'rtl' || document.documentElement.dir === 'rtl';
      Object.assign(dropdown.style, (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])({
        position: 'absolute',
        top: upward ? '' : '100%',
        bottom: upward ? '100%' : '',
        left: left && !isRTL || !left && isRTL ? 'auto' : '0',
        right: left && !isRTL || !left && isRTL ? '0' : 'auto',
        transform: ''
      }, upward ? 'marginBottom' : 'marginTop', "".concat(this.options.offset, "px")));
    }
  }, {
    key: "updateVisibleDropdowns",
    value: function updateVisibleDropdowns() {
      var _this3 = this;
      var visibleDropdowns = document.querySelectorAll("".concat(this.options.dropdownClass, ".").concat(this.options.activeClass));
      visibleDropdowns.forEach(function (dropdown) {
        var trigger = dropdown.parentElement.querySelector(_this3.options.triggerClass);
        if (trigger) {
          _this3.positionDropdown(trigger);
        }
      });
    }
  }, {
    key: "setupMutationObserver",
    value: function setupMutationObserver() {
      var _this4 = this;
      if (this.observer) return;
      this.observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
          if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
            var target = mutation.target;
            if (target.classList.contains(_this4.options.dropdownClass) && target.classList.contains(_this4.options.activeClass)) {
              var trigger = target.parentElement.querySelector(_this4.options.triggerClass);
              if (trigger) {
                setTimeout(function () {
                  return _this4.positionDropdown(trigger);
                }, _this4.options.mutationDelay);
              }
            }
          }
          if (mutation.type === 'childList') {
            mutation.addedNodes.forEach(function (node) {
              if (node.nodeType === Node.ELEMENT_NODE) {
                var dropdowns = node.querySelectorAll ? node.querySelectorAll(_this4.options.dropdownClass) : node.matches && node.matches(_this4.options.dropdownClass) ? [node] : [];
                dropdowns.forEach(function (dropdown) {
                  var trigger = dropdown.parentElement.querySelector(_this4.options.triggerClass);
                  if (trigger) {
                    _this4.attachDropdownEvents(trigger);
                  }
                });
              }
            });
          }
        });
      });
      this.observer.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['class']
      });
    }
  }, {
    key: "attachDropdownEvents",
    value: function attachDropdownEvents(trigger) {
      var _this5 = this;
      if (trigger.dataset.viewportDropdownAttached) return;
      trigger.addEventListener('click', function (e) {
        setTimeout(function () {
          return _this5.positionDropdown(e.target);
        }, _this5.options.positioningDelay);
      });
      trigger.dataset.viewportDropdownAttached = 'true';
    }
  }, {
    key: "initializeAllDropdowns",
    value: function initializeAllDropdowns() {
      var _this6 = this;
      var allTriggers = document.querySelectorAll(this.options.triggerClass);
      allTriggers.forEach(function (trigger) {
        _this6.attachDropdownEvents(trigger);
      });
    }
  }, {
    key: "position",
    value: function position(trigger) {
      var element = typeof trigger === 'string' ? document.querySelector(trigger) : trigger;
      if (element) this.positionDropdown(element);
    }
  }, {
    key: "updateOptions",
    value: function updateOptions(newOptions) {
      Object.assign(this.options, newOptions);
    }
  }, {
    key: "destroy",
    value: function destroy() {
      if (this.observer) {
        this.observer.disconnect();
        this.observer = null;
      }
      this.isInitialized = false;
    }
  }]);
}();
var viewportDropdown = new ViewportAwareDropdown();

// Initialize all dropdowns when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
  viewportDropdown.initializeAllDropdowns();
});
(function ($) {
  window.addEventListener('load', function () {
    //Remove Preload after Window Load
    $('body').removeClass('directorist-preload');
    $('.button.wp-color-result').attr('style', ' ');

    // Escape text for safe HTML insertion (XSS prevention)
    function escapeHtml(text) {
      var div = document.createElement('div');
      div.textContent = text == null ? '' : String(text);
      return div.innerHTML;
    }

    /* ----------------
          Search Form
          ------------------ */

    // Default Tags Slice
    function defaultTags() {
      $('.directorist-btn-ml').each(function (index, element) {
        var item = $(element).siblings('.atbdp_cf_checkbox, .directorist-search-field-tag, .directorist-search-tags');
        var item_checkbox = $(item).find('.directorist-checkbox');
        $(item_checkbox).slice(4, item_checkbox.length).fadeOut();
        if (item_checkbox.length <= 4) {
          $(element).css('display', 'none');
        }
      });
    }
    defaultTags();
    window.addEventListener('triggerSlice', defaultTags);

    // See More Tags Button
    $('body').on('click', '.directorist-btn-ml', function (event) {
      event.preventDefault();
      var item = $(this).siblings('.directorist-search-tags');
      var item_checkbox = $(item).find('.directorist-checkbox');
      $(item_checkbox).slice(4, item_checkbox.length).fadeOut();
      $(this).toggleClass('active');
      if ($(this).hasClass('active')) {
        $(this).text(directorist.i18n_text.show_less);
        $(item_checkbox).slice(4, item_checkbox.length).fadeIn();
      } else {
        $(this).text(directorist.i18n_text.show_more);
        $(item_checkbox).slice(4, item_checkbox.length).fadeOut();
      }
    });

    /*** Search Form ***/

    // Count Checkbox Selected Items
    function selectedItemCount(item) {
      var dropdownParent = $(item).closest('.directorist-search-field');
      var dropDownContent = $(item).closest('.directorist-search-basic-dropdown-content');
      var selectedItemCount = dropDownContent.find('.directorist-checkbox input[type="checkbox"]:checked');
      var selectedPrefix = dropDownContent.siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-prefix');
      var selectedCounter = dropDownContent.siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-count');
      if (selectedItemCount.length > 0) {
        selectedCounter.text(selectedItemCount.length);
        selectedPrefix.text('Selected');
        dropdownParent.addClass('input-has-value');
      } else {
        // If no items are checked, clear the text
        selectedCounter.text('');
        selectedPrefix.text('');
        dropdownParent.removeClass('input-has-value');
      }
    }

    // Radio Selected Items
    function selectedRadioItem(item) {
      var dropdownParent = $(item).closest('.directorist-search-field');
      var dropDownLabel = dropdownParent.find('.directorist-search-basic-dropdown-selected-item');
      var selectedItem = dropdownParent.find('.directorist-radio input[type="radio"]:checked');
      var selectedItemLabel = selectedItem.siblings('.directorist-radio__label').text();
      if (selectedItem) {
        dropDownLabel.text(' - ' + selectedItemLabel);
        dropdownParent.addClass('input-has-value');
      } else {
        // If no items are checked, clear the text
        selectedItem.text('');
        dropdownParent.removeClass('input-has-value');
      }
    }

    // Checkbox Field Check
    $('body').on('change', '.directorist-search-form__top .directorist-search-basic-dropdown input[type="checkbox"], .directorist-search-modal .directorist-search-basic-dropdown input[type="checkbox"]', function (e) {
      e.preventDefault();
      selectedItemCount(this);
    });

    // Radio Field Check
    $('body').on('change', '.directorist-search-form__top .directorist-search-basic-dropdown input[type="radio"], .directorist-search-modal .directorist-search-basic-dropdown input[type="radio"]', function (e) {
      e.preventDefault();
      selectedRadioItem(this);
    });

    // Initialize selected item count for checkboxes that are already checked on page load
    // Process each dropdown that has checked checkboxes to avoid redundant calls
    $('.directorist-search-form__top .directorist-search-basic-dropdown-content, .directorist-search-modal .directorist-search-basic-dropdown-content').each(function () {
      var checkedCheckbox = $(this).find('input[type="checkbox"]:checked');
      if (checkedCheckbox.length > 0) {
        // Call once per dropdown with any checked checkbox
        selectedItemCount(checkedCheckbox.first());
      }
    });

    // Initialize selected radio items that are already checked on page load
    $('.directorist-search-form__top .directorist-search-basic-dropdown input[type="radio"]:checked, .directorist-search-modal .directorist-search-basic-dropdown input[type="radio"]:checked').each(function () {
      selectedRadioItem(this);
    });

    // Initialize all input fields that have values on page load
    $('.directorist-search-form__top .directorist-search-field__input:not(.directorist-search-basic-dropdown), .directorist-search-modal .directorist-search-field__input:not(.directorist-search-basic-dropdown)').each(function () {
      var inputField = $(this);
      var inputValue = inputField.val();
      var searchField = inputField.closest('.directorist-search-field');

      // Check if it's a select field
      if (inputField.hasClass('directorist-select')) {
        var selectElement = inputField.find('select');
        if (selectElement.length) {
          inputValue = selectElement.val() || selectElement.data('selected-id');
        }
      }

      // If field has a value, add appropriate classes
      if (inputValue && inputValue !== '' && inputValue !== '0') {
        searchField.addClass('input-has-value');
        if (!searchField.hasClass('input-is-focused')) {
          searchField.addClass('input-is-focused');
        }
      }
    });

    // Initialize color picker background colors on page load
    $('.wp-color-picker, .directorist-color-picker').each(function () {
      var colorValue = $(this).val();
      if (colorValue && colorValue !== '') {
        var colorButton = $(this).closest('.directorist-search-field').find('.wp-color-result');
        if (colorButton.length) {
          colorButton.css('background-color', colorValue);
        }
      }
    });

    // Basic Search Dropdown Toggle
    $('body').on('click', '.directorist-search-form__top .directorist-search-basic-dropdown-label, .directorist-search-modal .directorist-search-basic-dropdown-label', function (e) {
      var _this7 = this;
      e.preventDefault();
      var dropDownParent = $(this).closest('.directorist-search-field');
      var dropDownContent = $(this).siblings('.directorist-search-basic-dropdown-content');
      dropDownContent.toggleClass('dropdown-content-show');
      if (dropDownContent.hasClass('dropdown-content-show')) {
        dropDownParent.addClass('input-is-focused');
        dropDownContent.slideDown();
        setTimeout(function () {
          viewportDropdown.position(_this7);
        }, viewportDropdown.options.animationDelay);
      } else {
        dropDownParent.removeClass('input-is-focused');
        dropDownContent.slideUp();
      }
      // Hide all other open contents
      $('.directorist-search-basic-dropdown-content.dropdown-content-show').not(dropDownContent).each(function () {
        $(this).removeClass('dropdown-content-show dropdown-upward').slideUp();
      });
    });

    // Dropdown Content Hide on Outside Click
    $('body').on('click', function (e) {
      var dropDownRoot = $(e.target).closest('.directorist-search-form-dropdown');
      var dropDownParent = $('.directorist-search-form-dropdown.input-is-focused');
      var dropDownContent = $('.directorist-search-basic-dropdown-content.dropdown-content-show');
      if (!dropDownRoot.length) {
        dropDownParent.each(function () {
          $(this).removeClass('input-is-focused');
        });
        dropDownContent.each(function () {
          $(this).removeClass('dropdown-content-show dropdown-upward').slideUp();
        });
      }
    });

    // Check Empty Search Fields on Search Modal
    function initSearchFields() {
      var searchFields = document.querySelectorAll('.directorist-search-field__input:not(.directorist-search-basic-dropdown)');
      searchFields.forEach(function (searchField) {
        var wrapper = searchField.closest('.directorist-search-field');
        if (!wrapper) {
          return;
        }
        var inputFieldValue = searchField.value;
        if (searchField.classList.contains('directorist-select')) {
          inputFieldValue = searchField.querySelector('select').dataset.selectedId;
        }
        if (inputFieldValue !== '') {
          wrapper.classList.add('input-has-value');
          if (!wrapper.classList.contains('input-is-focused')) {
            wrapper.classList.add('input-is-focused');
          }
        } else {
          inputFieldValue = '';
          if (wrapper.classList.contains('input-has-value')) {
            wrapper.classList.remove('input-has-value');
          }
        }
      });
    }
    initSearchFields();

    // Search Form Reset Button Initialize
    function initForm(searchForm) {
      var value = false;

      // Check all input fields which are not checkbox, radio & hidden
      searchForm.querySelectorAll("input:not([type='checkbox']):not([type='radio']):not([type='hidden']):not(.wp-picker-clear):not(.directorist-custom-range-slider__value__min):not(.directorist-custom-range-slider__value__max)").forEach(function (el) {
        if (el.value !== '') {
          value = true;
        }
      });

      // Check all checkbox, radio field
      searchForm.querySelectorAll("input[type='checkbox'], input[type='radio']").forEach(function (el) {
        if (el.checked) {
          value = true;
        }
      });

      // Check all select field
      searchForm.querySelectorAll('select').forEach(function (el) {
        if (el.value || el.selectedIndex !== 0) {
          value = true;
        }
      });

      // Check all custom number range field
      searchForm.querySelectorAll('.directorist-search-field-text_range .directorist-custom-range-slider__range').forEach(function (el) {
        if (el.value === '0-0') {
          value = false;
        }
      });

      // Check all range slider field
      searchForm.querySelectorAll('.directorist-custom-range-slider__value input').forEach(function (el) {
        if (el.value > 0) {
          value = true;
        }
      });

      // Disable Reset Button based on value
      if (!value) {
        // Find Reset Button in current form
        var resetButtonWrapper = searchForm.querySelector('.directorist-advanced-filter__action');
        if (resetButtonWrapper) {
          resetButtonWrapper.classList.add('reset-btn-disabled');
        } else {
          var _searchForm$closest;
          // Find Reset Button in whole listing-with-sidebar
          resetButtonWrapper = (_searchForm$closest = searchForm.closest('.listing-with-sidebar')) === null || _searchForm$closest === void 0 ? void 0 : _searchForm$closest.querySelector('.directorist-advanced-filter__action');
          if (resetButtonWrapper) {
            resetButtonWrapper.classList.add('reset-btn-disabled');
          }
        }
      } else {
        setTimeout(function () {
          enableResetButton(searchForm);
        }, 100);
      }
    }

    // Enable Reset Button
    function enableResetButton(searchForm) {
      var $resetButtonWrapper = $(searchForm).find('.directorist-advanced-filter__action');
      if (!$resetButtonWrapper.length) {
        $resetButtonWrapper = $(searchForm).closest('.directorist-instant-search').find('.directorist-advanced-filter__action');
      }
      if ($resetButtonWrapper.length) {
        $resetButtonWrapper.removeClass('reset-btn-disabled');
      }
    }

    // Initialize Form Reset Button
    var searchForm = document.querySelectorAll('.directorist-contents-wrap form');
    searchForm.forEach(function (form) {
      setTimeout(function () {
        initForm(form);
      }, 100);
    });

    // Input Field Check
    $('body').on('keyup', '.directorist-contents-wrap form input:not([type="checkbox"]):not([type="radio"])', function (e) {
      var searchForm = this.closest('form');
      if (this.value && this.value !== 0 && this.value !== undefined) {
        enableResetButton(searchForm);
      } else {
        setTimeout(function () {
          initForm(searchForm);
        }, 100);
      }
    });
    $('body').on('change', '.directorist-contents-wrap form input[type="checkbox"], .directorist-contents-wrap form input[type="radio"]', function (e) {
      var searchForm = this.closest('form');
      if (this.checked) {
        enableResetButton(searchForm);
      } else {
        setTimeout(function () {
          initForm(searchForm);
        }, 100);
      }
    });
    $('body').on('change', '.directorist-contents-wrap form select', function (e) {
      var searchForm = this.closest('form');
      if (this.value !== undefined && this.value !== '') {
        enableResetButton(searchForm);
      } else {
        setTimeout(function () {
          initForm(searchForm);
        }, 100);
      }
    });

    // Color Field Open Button Click
    $('.directorist-contents-wrap form .wp-color-result').on('click', function (e) {
      e.preventDefault();
      var $parentElement = $(this).closest('.directorist-search-field');
      if ($parentElement.hasClass('input-has-value') || $parentElement.hasClass('input-is-focused')) {
        $parentElement.removeClass('input-has-value input-is-focused');
      } else {
        $parentElement.addClass('input-has-value input-is-focused');
      }
    });

    // Color Field Clear Button Click
    $('.directorist-contents-wrap form .wp-picker-clear').on('click', function (e) {
      e.preventDefault();
      var $parentElement = $(this).closest('.directorist-search-field');
      if ($parentElement.hasClass('input-has-value') || $parentElement.hasClass('input-is-focused')) {
        $parentElement.removeClass('input-has-value input-is-focused');
      }
      var color = '';
      var input = $parentElement.find('.wp-color-picker')[0]; // get raw DOM element
      var form = $parentElement.closest('form')[0];
      if (!input || !form) return;

      // Dispatch custom event
      var colorChangeEvent = new CustomEvent('directorist-color-changed', {
        detail: {
          color: color,
          input: input,
          form: form
        }
      });
      window.dispatchEvent(colorChangeEvent);
    });

    // Color Change Event
    window.addEventListener('directorist-color-changed', function (e) {
      var _e$detail = e.detail,
        color = _e$detail.color,
        input = _e$detail.input,
        form = _e$detail.form;
      if (color && color !== '') {
        enableResetButton(form);
        var $parentElement = $(input).closest('.directorist-search-field');
        if (!$parentElement.hasClass('input-has-value') && !$parentElement.hasClass('input-is-focused')) {
          $parentElement.addClass('input-has-value input-is-focused');
        }
      } else {
        setTimeout(function () {
          initForm(form);
        }, 100);
      }
    });

    // Searchform Reset
    function adsFormReset(searchForm) {
      searchForm.querySelectorAll("input[type='text']:not(.wp-picker-clear)").forEach(function (el) {
        el.value = '';
        if (el.parentElement.classList.contains('input-has-value') || el.parentElement.classList.contains('input-is-focused')) {
          el.parentElement.classList.remove('input-has-value', 'input-is-focused');
        }
      });
      searchForm.querySelectorAll("input[type='date']").forEach(function (el) {
        el.value = '';
      });
      searchForm.querySelectorAll("input[type='time']").forEach(function (el) {
        el.value = '';
      });
      searchForm.querySelectorAll("input[type='url']").forEach(function (el) {
        el.value = '';
        if (el.parentElement.classList.contains('input-has-value') || el.parentElement.classList.contains('input-is-focused')) {
          el.parentElement.classList.remove('input-has-value', 'input-is-focused');
        }
      });
      searchForm.querySelectorAll("input[type='number']").forEach(function (el) {
        el.value = '';
        if (el.parentElement.classList.contains('input-has-value') || el.parentElement.classList.contains('input-is-focused')) {
          el.parentElement.classList.remove('input-has-value', 'input-is-focused');
        }
      });
      searchForm.querySelectorAll("input[type='hidden']:not(.listing_type)").forEach(function (el) {
        if (el.getAttribute('name') === 'directory_type' || el.getAttribute('name') === 'radius-search-based-on') return;
        el.value = '';
      });
      searchForm.querySelectorAll("input[type='radio']").forEach(function (el) {
        el.checked = false;
      });
      searchForm.querySelectorAll("input[type='checkbox']").forEach(function (el) {
        el.checked = false;
      });
      searchForm.querySelectorAll('select').forEach(function (el) {
        el.selectedIndex = 0;
        $('.directorist-select2-dropdown-close').click();
        var parentElem = el.closest('.directorist-search-field');
        if (parentElem.classList.contains('input-has-value') || parentElem.classList.contains('input-is-focused')) {
          setTimeout(function () {
            parentElem.classList.remove('input-has-value', 'input-is-focused');
          }, 100);
        }
      });
      var customRangeSliders = document.querySelectorAll('.directorist-custom-range-slider');
      customRangeSliders.forEach(function (sliderItem) {
        resetCustomRangeSlider(sliderItem);
      });
      searchForm.querySelectorAll('.directorist-search-basic-dropdown-content').forEach(function (dropdown) {
        var dropDownParent = dropdown.closest('.directorist-search-field');
        $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-count').text('');
        $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-prefix').text('');
        if (dropDownParent.classList.contains('input-has-value') || dropDownParent.classList.contains('input-is-focused')) {
          dropDownParent.classList.remove('input-has-value', 'input-is-focused');
        }
      });
      var irisPicker = searchForm.querySelector('input.wp-picker-clear');
      if (irisPicker !== null) {
        irisPicker.click();
      }
      handleRadiusVisibility();
      initForm(searchForm);
    }

    // Searchform Reset Trigger
    if ($('.directorist-btn-reset-js') !== null) {
      $('body').on('click', '.directorist-btn-reset-js', function (e) {
        var _this8 = this;
        e.preventDefault();
        setTimeout(function () {
          // Clear URL params on modal form reset
          var baseUrl = window.location.origin + window.location.pathname;

          // Update the URL in the address bar
          window.history.replaceState(null, '', baseUrl);
          if (_this8.closest('.directorist-search-modal')) {
            // Clear only the query parameters
            var _baseUrl = window.location.origin + window.location.pathname;

            // Update the URL in the address bar
            window.history.replaceState(null, '', _baseUrl);
          }
        }, 300);

        // Reset search form values
        if (this.closest('.directorist-contents-wrap')) {
          var _searchForm = this.closest('.directorist-contents-wrap').querySelector('.directorist-search-form');
          if (_searchForm) {
            adsFormReset(_searchForm);
          }
          var advanceSearchForm = this.closest('.directorist-contents-wrap').querySelector('.directorist-advanced-filter__form');
          if (advanceSearchForm) {
            adsFormReset(advanceSearchForm);
          }
          var advanceSearchFilter = this.closest('.directorist-contents-wrap').querySelector('.directorist-advanced-filter__advanced');
          if (advanceSearchFilter) {
            adsFormReset(advanceSearchFilter);
          }
        }
      });
    }

    // Search Modal Open
    function searchModalOpen(searchModalParent) {
      // Modal Overlay
      var modalOverlay = searchModalParent.querySelector('.directorist-search-modal__overlay');
      // Modal Content
      var modalContent = searchModalParent.querySelector('.directorist-search-modal__contents');

      // Modal Overlay Style
      modalOverlay.style.cssText = 'opacity: 1; visibility: visible; transition: 0.3s ease;';

      // Modal Content Style
      modalContent.style.cssText = 'opacity: 1; visibility: visible; bottom: 50%; transform: translate(-50%, 50%)';

      // Check if container width is less than 576px
      var containerWidth = document.body.offsetWidth;
      if (containerWidth < 576) {
        // Check if backdrop is added to body
        var bodyElement = document.body;
        var bodyStyles = getComputedStyle(bodyElement);
        var bodyBackdropStyle = (bodyStyles === null || bodyStyles === void 0 ? void 0 : bodyStyles.backdropFilter) || '';
        if (bodyBackdropStyle !== 'none' && bodyBackdropStyle !== '') {
          // If backdrop is added to body, set bottom to 50%
          modalContent.style.cssText += 'bottom: 50%; transform: translate(-50%, 50%)';
        } else {
          // If backdrop is not added to body, set bottom to 0
          modalContent.style.cssText += 'bottom: 0; transform: translate(-50%, 0)';
        }
      }
    }

    // Search Modal Close
    function searchModalClose(searchModalParent) {
      var modalOverlay = searchModalParent.querySelector('.directorist-search-modal__overlay');
      var modalContent = searchModalParent.querySelector('.directorist-search-modal__contents');

      // Overlay Style
      if (modalOverlay) {
        modalOverlay.style.cssText = 'opacity: 0; visibility: hidden';
      }

      // Modal Content Style
      if (modalContent) {
        modalContent.style.cssText = 'opacity: 0; visibility: hidden; bottom: -200px;';
      }
    }

    // Search Modal Minimizer
    function searchModalMinimize(searchModalParent) {
      var modalContent = searchModalParent.querySelector('.directorist-search-modal__contents');
      var modalMinimizer = searchModalParent.querySelector('.directorist-search-modal__minimizer');
      if (modalMinimizer.classList.contains('minimized')) {
        modalMinimizer.classList.remove('minimized');
        modalContent.style.bottom = '0';
      } else {
        modalMinimizer.classList.add('minimized');
        modalContent.style.bottom = '-50%';
      }
    }

    // Search Modal Open Trigger
    $('body').on('click', '.directorist-modal-btn', function (e) {
      e.preventDefault();
      // added overlay class on body
      document.querySelector('.directorist-content-active').classList.add('directorist-overlay-active');
      var parentElement = this.closest('.directorist-contents-wrap');
      if (this.classList.contains('directorist-modal-btn--basic')) {
        var searchModalElement = parentElement.querySelector('.directorist-search-modal--basic');
        searchModalOpen(searchModalElement);
      }
      if (this.classList.contains('directorist-modal-btn--advanced')) {
        var _searchModalElement = parentElement.querySelector('.directorist-search-modal--advanced');
        searchModalOpen(_searchModalElement);
      }
      if (this.classList.contains('directorist-modal-btn--full')) {
        var _searchModalElement2 = parentElement.querySelector('.directorist-search-modal--full');
        searchModalOpen(_searchModalElement2);
      }
    });

    // Search Modal Close Trigger
    $('body').on('click', '.directorist-search-modal__contents__btn--close, .directorist-search-modal__overlay', function (e) {
      e.preventDefault();
      // removed overlay class from body
      document.querySelector('.directorist-content-active').classList.remove('directorist-overlay-active');
      var searchModalElement = this.closest('.directorist-search-modal');
      searchModalClose(searchModalElement);
    });

    // Search Modal Minimizer Trigger
    $('body').on('click', '.directorist-search-modal__minimizer', function (e) {
      e.preventDefault();
      var searchModalElement = this.closest('.directorist-search-modal');
      searchModalMinimize(searchModalElement);
    });

    // Search Field Input Value Check
    function inputValueCheck(searchField) {
      searchField = searchField[0];
      var inputBox = searchField.querySelector('.directorist-search-field__input:not(.directorist-search-basic-dropdown)');
      var inputFieldValue = inputBox && inputBox.value;
      if (inputFieldValue) {
        searchField.classList.add('input-has-value');
        if (!searchField.classList.contains('input-is-focused')) {
          searchField.classList.add('input-is-focused');
        }
      } else {
        inputFieldValue = '';
        if (searchField.classList.contains('input-has-value')) {
          searchField.classList.remove('input-has-value');
        }
        if (searchField.classList.contains('input-is-focused')) {
          searchField.classList.remove('input-is-focused');
        }
      }
    }

    // Search Field Input Event Check
    function inputEventCheck(e, searchField) {
      searchField = searchField[0];
      var inputBox = searchField.querySelector('.directorist-search-field__input:not(.directorist-search-basic-dropdown)');
      var inputFieldValue = inputBox.value;
      if (e.type === 'focusin') {
        searchField.classList.add('input-is-focused');
      } else if (e.type === 'focusout') {
        if (inputBox.classList.contains('directorist-select')) {
          selectFocusOutCheck(searchField, inputBox);
        } else {
          if (inputFieldValue) {
            searchField.classList.add('input-has-value');
            if (!searchField.classList.contains('input-is-focused')) {
              searchField.classList.add('input-is-focused');
            }
          } else {
            searchField.classList.remove('input-is-focused');
          }
        }
      }
    }

    // Search Field Input Focusout Event Check
    function selectFocusOutCheck(searchField, inputBox) {
      searchField.classList.add('input-is-focused');
      var inputFieldValue = inputBox.querySelector('select').value;
      $('body').one('click', function (e) {
        inputFieldValue = inputBox.querySelector('select').value;
        var parentWithClass = e.target.closest('.directorist-search-field__input:not(.directorist-search-basic-dropdown)');
        if (!parentWithClass) {
          if (inputFieldValue) {
            searchField.classList.add('input-has-value');
            if (!searchField.classList.contains('input-is-focused')) {
              searchField.classList.add('input-is-focused');
            }
          } else {
            searchField.classList.remove('input-is-focused');
          }
        }
      });
    }

    // Search Form Select Field Init
    function initSelectFields() {
      var selectFields = document.querySelectorAll('.directorist-select.directorist-search-field__input:not(.directorist-search-basic-dropdown');
      selectFields.forEach(function (selectField) {
        var searchField = $(selectField).closest('.directorist-search-field');
        inputValueCheck(searchField);
      });
    }
    initSelectFields();

    // Search Form Input Field Check Trigger
    $('body').on('input keyup change', '.directorist-search-field__input:not(.directorist-search-basic-dropdown)', function (e) {
      var searchField = $(this).closest('.directorist-search-field');
      inputValueCheck(searchField);
    });
    $('body').on('focus blur', '.directorist-search-field__input:not(.directorist-search-basic-dropdown)', function (e) {
      var searchField = $(this).closest('.directorist-search-field');
      inputEventCheck(e, searchField);
    });

    // Search Form Input Clear Button
    $('body').on('click', '.directorist-search-field__btn--clear', function (e) {
      var inputFields = this.parentElement.querySelectorAll('.directorist-form-element');
      var selectboxField = this.parentElement.querySelector('.directorist-select select');
      var basicDropdown = this.parentElement.querySelectorAll('.directorist-search-basic-dropdown-content');
      var radioFields = this.parentElement.querySelectorAll('input[type="radio"]');
      var checkboxFields = this.parentElement.querySelectorAll('input[type="checkbox"]');
      if (selectboxField) {
        selectboxField.selectedIndex = 0;
        selectboxField.dispatchEvent(new Event('change'));
        $(selectboxField).trigger('change');
      }
      if (inputFields) {
        inputFields.forEach(function (inputField) {
          inputField.value = '';
        });
      }
      if (radioFields) {
        radioFields.forEach(function (element) {
          element.checked = false;
        });
      }
      if (checkboxFields) {
        checkboxFields.forEach(function (element) {
          element.checked = false;
        });
      }
      if (basicDropdown) {
        basicDropdown.forEach(function (dropdown) {
          $(dropdown).slideUp();
          $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-count').text('');
          $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-prefix').text('');
          $(dropdown).siblings('.directorist-search-basic-dropdown-label').find('.directorist-search-basic-dropdown-selected-item').text('');
        });
      }
      if (this.parentElement.classList.contains('input-has-value') || this.parentElement.classList.contains('input-is-focused')) {
        var _this$parentElement$q;
        this.parentElement.classList.remove('input-has-value', 'input-is-focused');
        (_this$parentElement$q = this.parentElement.querySelector('.directorist-search-basic-dropdown-content.dropdown-content-show')) === null || _this$parentElement$q === void 0 || _this$parentElement$q.classList.remove('dropdown-content-show');
      }
      handleRadiusVisibility();

      // Reset Button Disable
      var searchform = this.closest('form');
      var inputValue = $(this).parent('.directorist-search-field').find('.directorist-search-field__input:not(.directorist-search-basic-dropdown)').val();
      var selectValue = $(this).parent('.directorist-search-field').find('.directorist-search-field__input select:not(.directorist-search-basic-dropdown)').val();
      if (inputValue && inputValue !== 0 && inputValue !== undefined || selectValue && selectValue.selectedIndex === 0 || selectValue && selectValue.selectedIndex !== undefined) {
        enableResetButton(searchform);
      } else {
        setTimeout(function () {
          initForm(searchform);
        }, 100);
      }
    });

    // Search Form Input Field Back Button
    $('body').on('click', '.directorist-search-field__label:not(.directorist-search-basic-dropdown-label)', function (e) {
      var windowScreen = window.innerWidth;
      var parentField = this.closest('.directorist-search-field');
      if (windowScreen <= 575) {
        if (parentField.classList.contains('input-is-focused')) {
          parentField.classList.remove('input-is-focused');
        }
      }
    });

    // Listing Type Change
    $('body').on('click', '.search_listing_types, .directorist-type-nav__link', function (event) {
      event.preventDefault();
      var parent = $(this).closest('.directorist-search-contents');
      var listing_type = $(this).attr('data-listing_type');
      var type_current = parent.find('.directorist-listing-type-selection__link--current');
      if (type_current.length) {
        type_current.removeClass('directorist-listing-type-selection__link--current');
        $(this).addClass('directorist-listing-type-selection__link--current');
      }
      parent.find('.listing_type').val(listing_type);
      var form_data = new FormData();
      form_data.append('action', 'atbdp_listing_types_form');
      form_data.append('nonce', directorist.directorist_nonce);
      form_data.append('listing_type', listing_type);
      var atts = parent.attr('data-atts');
      var atts_decoded = btoa(atts);
      form_data.append('atts', atts_decoded);
      parent.find('.directorist-search-form-box').addClass('atbdp-form-fade');
      $.ajax({
        method: 'POST',
        processData: false,
        contentType: false,
        url: directorist.ajax_url,
        data: form_data,
        success: function success(response) {
          if (response) {
            // Add Temp Element
            var new_inserted_elm = '<div class="directorist_search_temp"><div>';
            parent.before(new_inserted_elm);

            // Remove Old Parent
            parent.remove();

            // Insert New Parent
            $('.directorist_search_temp').after(response['search_form']);
            var newParent = $('.directorist_search_temp').next();

            // Toggle Active Class
            newParent.find('.directorist-listing-type-selection__link--current').removeClass('directorist-listing-type-selection__link--current');
            newParent.find("[data-listing_type='" + listing_type + "']").addClass('directorist-listing-type-selection__link--current');

            // Remove Temp Element
            $('.directorist_search_temp').remove();
            var events = [new CustomEvent('directorist-search-form-nav-tab-reloaded'), new CustomEvent('directorist-reload-select2-fields'), new CustomEvent('directorist-reload-map-api-field'), new CustomEvent('triggerSlice')];
            events.forEach(function (event) {
              document.body.dispatchEvent(event);
              window.dispatchEvent(event);
            });
            // So conditional logic re-runs (listens via jQuery)
            $(document).trigger('directorist-search-form-nav-tab-reloaded');
            handleRadiusVisibility();
            directorist_custom_range_slider();
            initSearchFields();
            (0,_components_category_custom_fields__WEBPACK_IMPORTED_MODULE_8__["default"])($);
          }
          var parentAfterAjax = $(this).closest('.directorist-search-contents');
          parentAfterAjax.find('.directorist-search-form-box').removeClass('atbdp-form-fade');
          if (parentAfterAjax.find('.directorist-search-form-box').find('.directorist-search-field-radius_search').length) {
            handleRadiusVisibility();
            directorist_custom_range_slider();
          }
        },
        error: function error(_error) {
          // console.log(error);
        }
      });
    });
    (0,_components_category_custom_fields__WEBPACK_IMPORTED_MODULE_8__["default"])($);

    // Back Button to go back to the previous page
    $('body').on('click', '.directorist-btn__back', function (e) {
      e.preventDefault();
      window.history.back();
    });

    // Radius Search Field Hide on Empty Location Field
    function handleRadiusVisibility() {
      // Add class to mark the radius search field
      $('.directorist-range-slider-wrap').closest('.directorist-search-field').addClass('directorist-search-field-radius_search');
      var radius_search_item_selector = null;
      var radius_search_based_on = $('.directorist-radius_search_based_on').val();

      // Determine which search item selector to use
      if (radius_search_based_on === 'address') {
        radius_search_item_selector = '.directorist-location-js';
      } else if (radius_search_based_on === 'zip') {
        radius_search_item_selector = '.directorist-zipcode-search .zip-radius-search';
      } else {
        // Default fallback
        radius_search_item_selector = '.directorist-location-js';
      }

      // Check if radius search item selector elements exist
      var $radiusSearchItems = $(radius_search_item_selector);
      if ($radiusSearchItems.length === 0) {
        // If no elements found, hide all radius search containers
        $('.directorist-search-field-radius_search, .directorist-radius-search').css({
          display: 'none'
        });
      } else {
        // Loop through the elements
        $radiusSearchItems.each(function (index, locationDOM) {
          var $location = $(locationDOM);
          var isEmpty = $location.val() === '';
          var $container = $location.closest('.directorist-contents-wrap').find('.directorist-search-field-radius_search, .directorist-radius-search');
          $container.css({
            display: isEmpty ? 'none' : 'block'
          });
        });
      }
    }

    // handleRadiusVisibility Trigger
    $('body').on('keyup keydown input change focus', '.directorist-location-js, .zip-radius-search', function (e) {
      handleRadiusVisibility();
    });

    // rangeSlider, defaultTags Trigger on directory type | page change
    $('body').on('click', '.directorist-type-nav__link, .directorist-pagination .page-numbers, .directorist-viewas .directorist-viewas__item', function (e) {
      setTimeout(function () {
        handleRadiusVisibility();
        directorist_custom_range_slider();
        defaultTags();
      }, 600);
    });

    // directorist-instant-search-reloaded event
    window.addEventListener('directorist-instant-search-reloaded', function () {
      handleRadiusVisibility();
      directorist_custom_range_slider();
      defaultTags();
    });

    // active class add on view as button
    $('body').on('click', '.directorist-viewas .directorist-viewas__item', function (e) {
      $(this).addClass('active').siblings().removeClass('active');
    });

    // Hide Country Result Click on Outside of Zipcode Field
    $(document).on('click', function (e) {
      if (!$(e.target).closest('.directorist-zip-code').length) {
        $('.directorist-country').hide();
      }
    });
    $('body').on('click', '.directorist-country ul li a', function (event) {
      event.preventDefault();
      var zipcode_search = $(this).closest('.directorist-zipcode-search');
      var lat = $(this).data('lat');
      var lon = $(this).data('lon');
      zipcode_search.find('.zip-cityLat').val(lat);
      zipcode_search.find('.zip-cityLng').val(lon);
      $('.directorist-country').hide();
    });
    $('.address_result').hide();

    // Init Location
    init_map_api_field();
    document.body.addEventListener('directorist-reload-map-api-field', init_map_api_field);
    function init_map_api_field() {
      if (directorist.i18n_text.select_listing_map === 'google') {
        function initialize() {
          var opt = {
            types: ['geocode'],
            componentRestrictions: {
              country: directorist.restricted_countries
            }
          };
          var options = directorist.countryRestriction ? opt : '';
          var input_fields = [{
            input_class: '.directorist-location-js',
            lat_id: 'cityLat',
            lng_id: 'cityLng',
            options: options
          }, {
            input_id: 'address_widget',
            lat_id: 'cityLat',
            lng_id: 'cityLng',
            options: options
          }];
          var setupAutocomplete = function setupAutocomplete(field) {
            var input = document.querySelectorAll(field.input_class);
            input.forEach(function (elm) {
              if (!elm) {
                return;
              }
              var autocomplete = new google.maps.places.Autocomplete(elm, field.options);
              google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                elm.closest('.directorist-search-field').querySelector("#".concat(field.lat_id)).value = place.geometry.location.lat();
                elm.closest('.directorist-search-field').querySelector("#".concat(field.lng_id)).value = place.geometry.location.lng();
              });
            });
          };
          input_fields.forEach(function (field) {
            setupAutocomplete(field);
          });
        }
        initialize();
      } else if (directorist.i18n_text.select_listing_map === 'openstreet') {
        var getResultContainer = function getResultContainer(context, field) {
          return $(context).next(field.search_result_elm);
        };
        var getWidgetResultContainer = function getWidgetResultContainer(context, field) {
          return $(context).parent().next(field.search_result_elm);
        };
        var input_fields = [{
          input_elm: '.directorist-location-js',
          search_result_elm: '.address_result',
          getResultContainer: getResultContainer
        }, {
          input_elm: '#q_addressss',
          search_result_elm: '.address_result',
          getResultContainer: getResultContainer
        }, {
          input_elm: '.atbdp-search-address',
          search_result_elm: '.address_result',
          getResultContainer: getResultContainer
        }, {
          input_elm: '#address_widget',
          search_result_elm: '#address_widget_result',
          getResultContainer: getWidgetResultContainer
        }];
        input_fields.forEach(function (field) {
          $('body').off('keyup.directoristOpenstreet', field.input_elm).on('keyup.directoristOpenstreet', field.input_elm, (0,_global_components_debounce__WEBPACK_IMPORTED_MODULE_5__["default"])(function (event) {
            event.preventDefault();
            var blockedKeyCodes = [16, 17, 18, 19, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 91, 93, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 144, 145];

            // Return early when blocked key is pressed.
            if (blockedKeyCodes.includes(event.keyCode)) {
              return;
            }
            var locationAddressField = $(this).parent('.directorist-search-field');
            var result_container = field.getResultContainer(this, field);
            var search = $(this).val();
            if (search.length < 3) {
              result_container.css({
                display: 'none'
              });
            } else {
              locationAddressField.addClass('atbdp-form-fade');
              result_container.css({
                display: 'block'
              });
              $.ajax({
                url: 'https://nominatim.openstreetmap.org/?q=' + encodeURIComponent(search) + '&format=json&limit=5',
                type: 'GET',
                data: {},
                success: function success(data) {
                  var res = '';
                  var currentIconURL = directorist.assets_url + 'icons/font-awesome/svgs/solid/paper-plane.svg';
                  var currentIconHTML = directorist.icon_markup.replace('##URL##', currentIconURL).replace('##CLASS##', '');
                  var currentLocationIconHTML = "<span class='location-icon'>" + currentIconHTML + '</span>';
                  var currentLocationAddressHTML = "<span class='location-address'></span>";
                  var iconURL = directorist.assets_url + 'icons/font-awesome/svgs/solid/map-marker-alt.svg';
                  var iconHTML = directorist.icon_markup.replace('##URL##', iconURL).replace('##CLASS##', '');
                  var locationIconHTML = "<span class='location-icon'>" + iconHTML + '</span>';
                  for (var i = 0, len = data.length > 5 ? 5 : data.length; i < len; i++) {
                    res += '<li><a href="#" data-lat="' + escapeHtml(String(data[i].lat)) + '" data-lon="' + escapeHtml(String(data[i].lon)) + '">' + locationIconHTML + "<span class='location-address'>" + escapeHtml(String(data[i].display_name || '')) + '</span></a></li>';
                  }
                  function displayLocation(position, event) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    $.ajax({
                      url: 'https://nominatim.openstreetmap.org/reverse?format=json&lon=' + lng + '&lat=' + lat,
                      type: 'GET',
                      data: {},
                      success: function success(data) {
                        $('.directorist-location-js, .atbdp-search-address').val(data.display_name);
                        $('.directorist-location-js, .atbdp-search-address').attr('data-value', data.display_name);
                        $('#cityLat').val(lat);
                        $('#cityLng').val(lng);
                        var locationSearch = $('.directorist-search-location');
                        if (locationSearch.length) {
                          locationSearch.trigger('change');
                        }
                      }
                    });
                  }
                  result_container.html('<ul>' + "<li><a href='#' class='current-location'>" + currentLocationIconHTML + currentLocationAddressHTML + '</a></li>' + res + '</ul>');
                  if (res.length) {
                    result_container.show();
                  } else {
                    result_container.hide();
                  }
                  locationAddressField.removeClass('atbdp-form-fade');
                  $('body').off('click', '.address_result .current-location').on('click', '.address_result .current-location', function (e) {
                    e.preventDefault();
                    navigator.geolocation.getCurrentPosition(function (position) {
                      return displayLocation(position, e);
                    });
                  });
                },
                error: function error(_error3) {
                  console.log({
                    error: _error3
                  });
                  locationAddressField.removeClass('atbdp-form-fade');
                }
              });
            }
          }, 750));
        });

        // hide address result when click outside the input field
        $(document).on('click', function (e) {
          if (!$(e.target).closest('.directorist-location-js, #q_addressss, .atbdp-search-address, .current-location').length) {
            var locationSearch = $(e.target).closest('.directorist-search-location');
            var zipCodeSearch = $(e.target).closest('.directorist-zipcode-search');
            if (locationSearch.length) {
              locationSearch.trigger('change');
            }
            if (zipCodeSearch.length) {
              zipCodeSearch.trigger('change');
            }
            $('.address_result').hide();
          }
        });
        var syncLatLngData = function syncLatLngData(context, event, args) {
          event.preventDefault();
          var text = $(context).text();
          var lat = $(context).data('lat');
          var lon = $(context).data('lon');
          var _this = event.target;
          $(_this).closest('.address_result').siblings('input[name="cityLat"]').val(lat);
          $(_this).closest('.address_result').siblings('input[name="cityLng"]').val(lon);
          var inp = $(context).closest(args.result_list_container).parent().find('.directorist-location-js, #address_widget, #q_addressss, .atbdp-search-address');
          inp.val(text);
          $(args.result_list_container).hide();
        };
        $('body').on('click', '.address_result ul li a', function (event) {
          syncLatLngData(this, event, {
            result_list_container: '.address_result'
          });
        });
        $('body').on('click', '#address_widget_result ul li a', function (event) {
          syncLatLngData(this, event, {
            result_list_container: '#address_widget_result'
          });
        });
      }
      if ($('.directorist-location-js, #q_addressss, .atbdp-search-address').val() === '') {
        $(this).parent().next('.address_result').css({
          display: 'none'
        });
      }
    }
    $('.directorist-search-contents').each(function () {
      if ($(this).next().length === 0) {
        $(this).find('.directorist-search-country').css('max-height', '175px');
        $(this).find('.directorist-search-field .address_result').css('max-height', '175px');
      }
    });

    // Custom Range Slider
    function directorist_custom_range_slider() {
      var sliders = document.querySelectorAll('.directorist-custom-range-slider');
      sliders.forEach(function (sliderItem) {
        var _slider$directoristCu, _slider$directoristCu2, _slider$directoristCu3;
        var slider = sliderItem.querySelector('.directorist-custom-range-slider__slide');

        // Skip if already initialized
        if (!slider || slider.directoristCustomRangeSlider) return;
        var sliderStep = parseInt(slider.getAttribute('step')) || 1;
        var sliderMinValue = parseInt(slider.getAttribute('min-value')) || 0;
        var sliderMaxValue = parseInt(slider.getAttribute('max-value')) || 100;
        var sliderDefaultValue = parseInt(slider.getAttribute('default-value'));
        var minInput = sliderItem.querySelector('.directorist-custom-range-slider__value__min');
        var maxInput = sliderItem.querySelector('.directorist-custom-range-slider__value__max');
        var sliderRange = sliderItem.querySelector('.directorist-custom-range-slider__range');
        var sliderRangeShow = sliderItem.querySelector('.directorist-custom-range-slider__range__show');
        var sliderRangeValue = sliderItem.querySelector('.directorist-custom-range-slider__wrap .directorist-custom-range-slider__range');
        var minInputName = (minInput === null || minInput === void 0 ? void 0 : minInput.getAttribute('name')) || '';
        var maxInputName = (maxInput === null || maxInput === void 0 ? void 0 : maxInput.getAttribute('name')) || '';
        var isRTL = document.dir === 'rtl';

        // Flags
        var rangeInitLoad = true;
        var sliderActivated = false;
        var sliderRadiusActive = false;

        // Parse the URL parameters
        var urlParams = new URLSearchParams(window.location.search);
        var customNumberParams = urlParams.get('custom-number');
        var rangeFieldName = (sliderRange === null || sliderRange === void 0 ? void 0 : sliderRange.getAttribute('name')) || '';
        var fieldRangeValueParam = rangeFieldName ? urlParams.get(rangeFieldName) : null;
        var specificRangeMinParam = minInputName ? urlParams.get(minInputName) : null;
        var specificRangeMaxParam = maxInputName ? urlParams.get(maxInputName) : null;
        var globalRangeMinParam = urlParams.get('directorist-custom-range-slider__value__min');
        var globalRangeMaxParam = urlParams.get('directorist-custom-range-slider__value__max');
        var effectiveRangeMinParam = specificRangeMinParam !== null && specificRangeMinParam !== void 0 ? specificRangeMinParam : globalRangeMinParam;
        var effectiveRangeMaxParam = specificRangeMaxParam !== null && specificRangeMaxParam !== void 0 ? specificRangeMaxParam : globalRangeMaxParam;
        var locationDistanceParams = urlParams.get('miles');
        var milesParams = urlParams.has('miles');
        if (rangeFieldName === 'miles' && locationDistanceParams !== '0-0' && sliderDefaultValue >= 0) {
          sliderRadiusActive = true;
        }

        // if already have custom values, then slider is activated
        if (fieldRangeValueParam && fieldRangeValueParam !== '0-0') {
          sliderActivated = true;
        } else if (customNumberParams && customNumberParams !== '0-0') {
          sliderActivated = true;
        } else if (effectiveRangeMaxParam && effectiveRangeMaxParam !== '0') {
          sliderActivated = true;
        }
        if (typeof directoristCustomRangeSlider === 'undefined') return;
        if (sliderRadiusActive) {
          var _directoristCustomRan;
          // Radius Search Range Slider
          (_directoristCustomRan = directoristCustomRangeSlider) === null || _directoristCustomRan === void 0 || _directoristCustomRan.create(slider, {
            start: [minInput.value, !milesParams ? sliderDefaultValue : maxInput.value],
            connect: true,
            direction: isRTL ? 'rtl' : 'ltr',
            step: sliderStep ? sliderStep : 1,
            range: {
              min: Number(sliderMinValue || 0),
              max: Number(sliderMaxValue || 100)
            }
          });
        } else if (sliderActivated) {
          var _directoristCustomRan2;
          // Custom Number Range Slider
          var minValue = minInput.value;
          var maxValue = maxInput.value;

          // Assign min-max values from custom-range-slider params
          if (fieldRangeValueParam && fieldRangeValueParam !== '0-0') {
            var _fieldRangeValueParam = fieldRangeValueParam.split('-').map(Number),
              _fieldRangeValueParam2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_fieldRangeValueParam, 2),
              min = _fieldRangeValueParam2[0],
              max = _fieldRangeValueParam2[1];
            minValue = min;
            maxValue = max;
          } else if (customNumberParams && customNumberParams !== '0-0') {
            var _customNumberParams$s = customNumberParams.split('-').map(Number),
              _customNumberParams$s2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_customNumberParams$s, 2),
              _min = _customNumberParams$s2[0],
              _max = _customNumberParams$s2[1];

            // Use the split values as min-max
            minValue = _min;
            maxValue = _max;
          } else if (effectiveRangeMinParam && effectiveRangeMaxParam) {
            // Modal Search Form
            minValue = effectiveRangeMinParam;
            maxValue = effectiveRangeMaxParam;
          }

          // Initial with [min, max] value
          (_directoristCustomRan2 = directoristCustomRangeSlider) === null || _directoristCustomRan2 === void 0 || _directoristCustomRan2.create(slider, {
            start: [minValue, !milesParams ? sliderDefaultValue || maxValue : maxValue],
            connect: true,
            direction: isRTL ? 'rtl' : 'ltr',
            step: sliderStep ? sliderStep : 1,
            range: {
              min: Number(sliderMinValue || 0),
              max: Number(sliderMaxValue || 100)
            }
          });
        } else {
          var _directoristCustomRan3;
          // Initialize with [0, 0] and temp min/max
          (_directoristCustomRan3 = directoristCustomRangeSlider) === null || _directoristCustomRan3 === void 0 || _directoristCustomRan3.create(slider, {
            start: [0, 0],
            connect: true,
            direction: isRTL ? 'rtl' : 'ltr',
            step: 1,
            range: {
              min: 0,
              max: 1
            }
          });
        }

        // Handle first interaction
        (_slider$directoristCu = slider.directoristCustomRangeSlider) === null || _slider$directoristCu === void 0 || _slider$directoristCu.on('start', function () {
          if (sliderActivated || sliderRadiusActive) return;
          sliderActivated = true;

          // Range slider options update
          slider.directoristCustomRangeSlider.updateOptions({
            start: [sliderMinValue, sliderMinValue],
            step: sliderStep,
            range: {
              min: sliderMinValue,
              max: sliderMaxValue
            }
          });

          // Trigger range slider observer
          rangeSliderObserver();
        });

        // Update slider config - update values but don't trigger change during drag
        (_slider$directoristCu2 = slider.directoristCustomRangeSlider) === null || _slider$directoristCu2 === void 0 || _slider$directoristCu2.on('update', function (values, handle) {
          var value = Math.round(values[handle]);
          // Assign min-max value based on handler
          if (handle === 0) {
            minInput.value = value;
          } else {
            maxInput.value = value;
          }
          var rangeValue = "".concat(minInput.value, "-").concat(maxInput.value);
          if (sliderRange) sliderRange.value = rangeValue;
          if (sliderRangeShow) sliderRangeShow.innerHTML = rangeValue;
          if (sliderRangeValue) {
            sliderRangeValue.setAttribute('value', rangeValue);
          }
        });

        // Trigger change only when dragging ends (mouse/touch released)
        (_slider$directoristCu3 = slider.directoristCustomRangeSlider) === null || _slider$directoristCu3 === void 0 || _slider$directoristCu3.on('end', function () {
          if (sliderRangeValue && !rangeInitLoad) {
            $(sliderRangeValue).trigger('change');
          }
        });

        // Mark init complete
        rangeInitLoad = false;

        // 🔁 Manual input update logic (on change/keyup)
        function updateSliderFromInputs() {
          var minValue = Math.round(parseInt(minInput.value || 0, 10) / sliderStep) * sliderStep;
          var maxValue = Math.round(parseInt(maxInput.value || 0, 10) / sliderStep) * sliderStep;
          if (isNaN(minValue)) minValue = 0;
          if (isNaN(maxValue)) maxValue = 0;
          if (!sliderActivated) {
            sliderActivated = true;
            slider.directoristCustomRangeSlider.updateOptions({
              range: {
                min: sliderMinValue,
                max: sliderMaxValue
              },
              step: sliderStep,
              start: [sliderMinValue, sliderMaxValue]
            });
          }

          // Fix invalid ranges
          if (minValue > maxValue) {
            minInput.value = maxValue;
            minValue = maxValue;
          }
          if (maxValue < minValue) {
            maxInput.value = minValue;
            maxValue = minValue;
          }
          slider.directoristCustomRangeSlider.set([minValue, maxValue]);
        }

        // Debounce keyup to allow typing multi-digit values before validation
        var debouncedUpdate = (0,_global_components_debounce__WEBPACK_IMPORTED_MODULE_5__["default"])(updateSliderFromInputs, 500);
        minInput.addEventListener('change', updateSliderFromInputs);
        maxInput.addEventListener('change', updateSliderFromInputs);
        minInput.addEventListener('keyup', debouncedUpdate);
        maxInput.addEventListener('keyup', debouncedUpdate);
      });
    }
    directorist_custom_range_slider();

    // Reset Custom Range Slider
    function resetCustomRangeSlider(sliderItem) {
      var slider = sliderItem.querySelector('.directorist-custom-range-slider__slide');
      var minInput = sliderItem.querySelector('.directorist-custom-range-slider__value__min');
      var maxInput = sliderItem.querySelector('.directorist-custom-range-slider__value__max');
      var rangeValue = sliderItem.querySelector('.directorist-custom-range-slider__range');
      var radiusSearch = sliderItem.closest('.directorist-search-field-radius_search');
      var defaultValue = slider.getAttribute('default-value') || '0';
      if (radiusSearch) {
        var _slider$directoristCu4;
        minInput.value = '0';
        maxInput.value = defaultValue;
        slider === null || slider === void 0 || (_slider$directoristCu4 = slider.directoristCustomRangeSlider) === null || _slider$directoristCu4 === void 0 || _slider$directoristCu4.set([0, defaultValue]); // Set initial values
      } else {
        var _slider$directoristCu5;
        // Reset values to their initial state
        slider === null || slider === void 0 || (_slider$directoristCu5 = slider.directoristCustomRangeSlider) === null || _slider$directoristCu5 === void 0 || _slider$directoristCu5.set([0, 0]); // Set initial values
        minInput.value = '0'; // Set initial min value
        maxInput.value = '0'; // Set initial max value
        rangeValue.value = '0-0';
      }
      var sidebarRangeSlider = slider.closest('.listing-with-sidebar');
      if (sidebarRangeSlider && slider !== null && slider !== void 0 && slider.directoristCustomRangeSlider) {
        // Destroy the custom range slider instance
        slider.directoristCustomRangeSlider.destroy();
        delete slider.directoristCustomRangeSlider;
      }
    }

    // DOM Mutation Observer on Location Field
    function locationObserver() {
      var targetNode = document.querySelector('.directorist-location-js');
      if (targetNode) {
        var observer = new MutationObserver(handleRadiusVisibility);
        observer.observe(targetNode, {
          attributes: true
        });
      }
    }
    locationObserver();
    handleRadiusVisibility();
    $('body').on('keyup', '.zip-radius-search', directorist_debounce(function () {
      var zipcode = $(this).val();
      var zipcode_search = $(this).closest('.directorist-zipcode-search');
      var country_suggest = zipcode_search.find('.directorist-country');
      var zipcode_search = $(this).closest('.directorist-zipcode-search');
      if (zipcode) {
        zipcode_search.addClass('dir_loading');
      }
      if (directorist.i18n_text.select_listing_map === 'google') {
        var url = directorist.ajax_url;
      } else {
        url = "https://nominatim.openstreetmap.org/?postalcode=".concat(encodeURIComponent(zipcode), "&format=json&addressdetails=1");
        $('.directorist-country').css({
          display: 'block'
        });
        if (zipcode === '') {
          $('.directorist-country').css({
            display: 'none'
          });
        }
      }
      var res = '';
      var google_data = {
        nonce: directorist.directorist_nonce,
        action: 'directorist_zipcode_search',
        zipcode: zipcode
      };
      $.ajax({
        url: url,
        method: 'POST',
        data: directorist.i18n_text.select_listing_map === 'google' ? google_data : '',
        success: function success(data) {
          if (data.data && data.data.error_message) {
            zipcode_search.find('.error_message').remove();
            zipcode_search.find('.zip-cityLat').val('');
            zipcode_search.find('.zip-cityLng').val('');
            zipcode_search.append(data.data.error_message);
          }
          zipcode_search.removeClass('dir_loading');
          if (directorist.i18n_text.select_listing_map === 'google' && typeof data.lat !== 'undefined' && typeof data.lng !== 'undefined') {
            zipcode_search.find('.error_message').remove();
            zipcode_search.find('.zip-cityLat').val(data.lat);
            zipcode_search.find('.zip-cityLng').val(data.lng);
          } else {
            if (data.length === 1) {
              var lat = data[0].lat;
              var lon = data[0].lon;
              zipcode_search.find('.zip-cityLat').val(lat);
              zipcode_search.find('.zip-cityLng').val(lon);
            } else {
              for (var i = 0; i < data.length; i++) {
                var country = data[i] && data[i].address && data[i].address.country ? data[i].address.country : '';
                res += '<li><a href="#" data-lat="' + escapeHtml(String(data[i].lat)) + '" data-lon="' + escapeHtml(String(data[i].lon)) + '">' + escapeHtml(country) + '</a></li>';
              }
            }
            $(country_suggest).html("<ul>".concat(res, "</ul>"));
            if (res.length) {
              $('.directorist-country').show();
            } else {
              $('.directorist-country').hide();
            }
          }
        }
      });
    }, 250));

    // Returns a function, that, as long as it continues to be invoked, will not
    // be triggered. The function will be called after it stops being called for
    // N milliseconds. If `immediate` is passed, trigger the function on the
    // leading edge, instead of the trailing.
    function directorist_debounce(func, wait, immediate) {
      var timeout;
      return function () {
        var context = this,
          args = arguments;
        var later = function later() {
          timeout = null;
          if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
      };
    }

    // Custom Range Slider Value Check on Change
    function sliderValueCheck(searchForm, targetNode, value) {
      if (value > 0) {
        enableResetButton(searchForm);
        var rangeSlider = targetNode.closest('.directorist-custom-range-slider');
        if (!rangeSlider) return;
        var customSliderMin = rangeSlider.querySelector('.directorist-custom-range-slider__value__min');
        var customSliderRange = rangeSlider.querySelector('.directorist-custom-range-slider__range');
        customSliderMin.value = customSliderMin.value ? customSliderMin.value : 0;
        customSliderRange.value = customSliderMin.value + '-' + value;
      } else {
        initForm(searchForm);
      }
    }

    // DOM Mutation Observer on Custom Range Slider
    function rangeSliderObserver() {
      var targetNodes = document.querySelectorAll('.directorist-search-field:not(.directorist-search-field-radius_search) .directorist-custom-range-slider-handle-upper');
      targetNodes.forEach(function (targetNode) {
        if (targetNode) {
          var _searchForm2 = targetNode.closest('form');
          var observerCallback = function observerCallback(mutationList, observer) {
            var _iterator = _createForOfIteratorHelper(mutationList),
              _step;
            try {
              for (_iterator.s(); !(_step = _iterator.n()).done;) {
                var mutation = _step.value;
                if (targetNode.classList.contains('directorist-custom-range-slider-handle-upper')) {
                  sliderValueCheck(_searchForm2, targetNode, parseInt(targetNode.ariaValueNow));
                }
              }
            } catch (err) {
              _iterator.e(err);
            } finally {
              _iterator.f();
            }
          };
          var sliderObserver = new MutationObserver(observerCallback);
          sliderObserver.observe(targetNode, {
            attributes: true
          });
        }
      });
    }
    rangeSliderObserver();

    // Conditional logic for search form (Search Bar & Search Filter)
    (function initSearchFormConditionalLogic() {
      function getSearchFormWrapper() {
        return '.directorist-search-form-wrap, .directorist-search-form, .directorist-search-modal, .directorist-search-adv-filter';
      }
      var getFieldValueFn = function getFieldValueFn(fieldKey) {
        return (0,_global_components_conditional_logic__WEBPACK_IMPORTED_MODULE_4__.getFieldValue)(fieldKey, jQuery);
      };
      var evaluateConditionalLogicFn = function evaluateConditionalLogicFn(conditionalLogic) {
        return (0,_global_components_conditional_logic__WEBPACK_IMPORTED_MODULE_4__.evaluateConditionalLogic)(conditionalLogic, getFieldValueFn);
      };
      var applyConditionalLogicFn = function applyConditionalLogicFn($fieldWrapper) {
        return (0,_global_components_conditional_logic__WEBPACK_IMPORTED_MODULE_4__.applyConditionalLogic)($fieldWrapper, evaluateConditionalLogicFn, jQuery);
      };
      (0,_global_components_conditional_logic__WEBPACK_IMPORTED_MODULE_4__.watchFieldChanges)(getSearchFormWrapper, getFieldValueFn, applyConditionalLogicFn, jQuery);
      function runSearchFormConditionalLogic() {
        (0,_global_components_conditional_logic__WEBPACK_IMPORTED_MODULE_4__.initConditionalLogic)(getSearchFormWrapper, getFieldValueFn, applyConditionalLogicFn, jQuery, []);
      }

      // On load
      runSearchFormConditionalLogic();
      setTimeout(runSearchFormConditionalLogic, 300);

      // Re-run when triggerSlice fires
      window.addEventListener('triggerSlice', function () {
        setTimeout(runSearchFormConditionalLogic, 100);
      });

      // Re-run when Select2 loads for search form
      jQuery(document).on('select2-loaded', function () {
        setTimeout(runSearchFormConditionalLogic, 200);
      });

      // Re-run when advanced search modal opens
      jQuery('body').on('click', '.directorist-modal-btn--advanced, .directorist-search-form-action__modal__btn-advanced', function () {
        setTimeout(runSearchFormConditionalLogic, 300);
      });

      // Re-run when search form nav tab reloads
      jQuery(document).on('directorist-search-form-nav-tab-reloaded', function () {
        setTimeout(runSearchFormConditionalLogic, 300);
      });
    })();
  });
})(jQuery);
}();
/******/ })()
;
//# sourceMappingURL=search-form.js.map