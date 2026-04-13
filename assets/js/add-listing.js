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

/***/ "./assets/src/js/helper.js":
/*!*********************************!*\
  !*** ./assets/src/js/helper.js ***!
  \*********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   directoristRequestHeaders: function() { return /* binding */ directoristRequestHeaders; },
/* harmony export */   findObjectItem: function() { return /* binding */ findObjectItem; },
/* harmony export */   isObject: function() { return /* binding */ isObject; },
/* harmony export */   toCapitalize: function() { return /* binding */ toCapitalize; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");

function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t.return || t.return(); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
var isObject = function isObject(value) {
  return value && (0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(value) === 'object' && !Array.isArray(value);
};
var toCapitalize = function toCapitalize(value) {
  return value ? value.charAt(0).toUpperCase() + value.slice(1) : '';
};
function findObjectItem(path, data, defaultValue) {
  if (typeof path !== 'string') {
    return defaultValue;
  }
  if (!isObject(data)) {
    return defaultValue;
  }
  var pathItems = path.split('.');
  var targetItem = data;
  var _iterator = _createForOfIteratorHelper(pathItems),
    _step;
  try {
    for (_iterator.s(); !(_step = _iterator.n()).done;) {
      var key = _step.value;
      if (!isObject(targetItem)) {
        return defaultValue;
      }
      if (!targetItem.hasOwnProperty(key)) {
        return defaultValue;
      }
      targetItem = targetItem[key];
    }
  } catch (err) {
    _iterator.e(err);
  } finally {
    _iterator.f();
  }
  return targetItem;
}
function directoristRequestHeaders() {
  if (window.directorist && window.directorist.request_headers && (0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(window.directorist.request_headers) === 'object' && !Array.isArray(window.directorist.request_headers)) {
    var headers = {};
    for (var key in window.directorist.request_headers) {
      headers["Directorist-".concat(key)] = window.directorist.request_headers[key];
    }
    return headers;
  }
  return {};
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
  !*** ./assets/src/js/global/add-listing.js ***!
  \*********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var _global_components_setup_select2__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../global/components/setup-select2 */ "./assets/src/js/global/components/setup-select2.js");
/* harmony import */ var _helper__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../helper */ "./assets/src/js/helper.js");
/* harmony import */ var _public_components_colorPicker__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../public/components/colorPicker */ "./assets/src/js/public/components/colorPicker.js");
/* harmony import */ var _public_components_colorPicker__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_public_components_colorPicker__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _public_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../public/components/directoristDropdown */ "./assets/src/js/public/components/directoristDropdown.js");
/* harmony import */ var _public_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_public_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _public_components_directoristSelect__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../public/components/directoristSelect */ "./assets/src/js/public/components/directoristSelect.js");
/* harmony import */ var _public_components_directoristSelect__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_public_components_directoristSelect__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _components_conditional_logic__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./components/conditional-logic */ "./assets/src/js/global/components/conditional-logic.js");
/* harmony import */ var _components_debounce__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./components/debounce */ "./assets/src/js/global/components/debounce.js");


function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t.return || t.return(); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
// General Components
// import { cacheCategoryCustomFields, getCategoryCustomFieldsCache } from '../global/components/cache-category-custom-fields';
// import loadCategoryCustomFields from '../global/components/load-category-custom-fields';








/* eslint-disable */
var $ = jQuery;
var localized_data = directorist.add_listing_data;
function getWrapper() {
  return localized_data.is_admin ? '#post' : '#directorist-add-listing-form';
}
function initColorField() {
  var $colorField = $('.directorist-color-field-js', getWrapper());
  if ($colorField.length) {
    $colorField.wpColorPicker();
  }
}
function scrollToEl(selector) {
  document.querySelector(selector).scrollIntoView({
    block: 'start',
    behavior: 'smooth'
  });
}

/**
 * Join Query String
 *
 * @param string url
 * @param string queryString
 * @return string
 */
function joinQueryString(url, queryString) {
  return url.match(/[?]/) ? "".concat(url, "&").concat(queryString) : "".concat(url, "?").concat(queryString);
}
function scrollTo(selector) {
  var _document$querySelect;
  (_document$querySelect = document.querySelector(selector)) === null || _document$querySelect === void 0 || _document$querySelect.scrollIntoView({
    block: 'start',
    behavior: 'smooth'
  });
}

/* Show and hide manual coordinate input field */
$(window).on('load', function () {
  if ($('input#manual_coordinate').length) {
    $('input#manual_coordinate').each(function (index, element) {
      if (!$(element).is(':checked')) {
        $('#hide_if_no_manual_cor').hide();
        $('.directorist-map-coordinates').hide();
      }
    });
  }

  //initialize color picker
  initColorField();
});
$(function () {
  $('body').on('click', '#manual_coordinate', function (e) {
    if ($('input#manual_coordinate').is(':checked')) {
      $('.directorist-map-coordinates').show();
      $('#hide_if_no_manual_cor').show();
    } else {
      $('.directorist-map-coordinates').hide();
      $('#hide_if_no_manual_cor').hide();
    }
  });

  // SOCIAL SECTION
  // Rearrange the IDS and Add new social field
  $('body').on('click', '#addNewSocial', function (e) {
    var _this = this;
    var social_wrap = $('#social_info_sortable_container'); // cache it
    var currentItems = $('.directorist-form-social-fields').length;
    var ID = "id=".concat(currentItems); // eg. 'id=3'
    var iconBindingElement = jQuery('#addNewSocial');

    // arrange names ID in order before adding new elements
    $('.directorist-form-social-fields').each(function (index, element) {
      var e = $(element);
      e.attr('id', "socialID-".concat(index));
      e.find('select').attr('name', "social[".concat(index, "][id]"));
      e.find('.atbdp_social_input').attr('name', "social[".concat(index, "][url]"));
      e.find('.directorist-form-social-fields__remove').attr('data-id', index);
    });

    // now add the new elements. we could do it here without using ajax but it would require more markup here.
    atbdp_do_ajax(iconBindingElement, 'atbdp_social_info_handler', ID, function (data) {
      social_wrap.append(data);
    });
    setTimeout(function () {
      var socialSelect = _this.parentElement.querySelectorAll('.directorist-form-social-fields select');
      socialSelect.forEach(function (item) {
        if (item.value !== '') {
          item.classList.remove('placeholder-item');
        }
        item.addEventListener('change', function () {
          if (this.value !== '' && this.classList.contains('placeholder-item')) {
            this.classList.remove('placeholder-item');
          } else if (this.value === '') {
            this.classList.add('placeholder-item');
          }
        });
      });
    }, 300);
  });
  document.addEventListener('directorist-reload-plupload', function () {
    initColorField();
  });

  // remove the social field and then reset the ids while maintaining position
  $('body').on('click', '.directorist-form-social-fields__remove', function (e) {
    var id = $(this).data('id');
    var elementToRemove = $("div#socialID-".concat(id));
    /* Act on the event */
    swal({
      title: localized_data.i18n_text.confirmation_text,
      text: localized_data.i18n_text.ask_conf_sl_lnk_del_txt,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: localized_data.i18n_text.confirm_delete,
      closeOnConfirm: false
    }, function (isConfirm) {
      if (isConfirm) {
        // user has confirmed, no remove the item and reset the ids
        elementToRemove.slideUp('fast', function () {
          elementToRemove.remove();
          // reorder the index
          $('.directorist-form-social-fields').each(function (index, element) {
            var e = $(element);
            e.attr('id', "socialID-".concat(index));
            e.find('select').attr('name', "social[".concat(index, "][id]"));
            e.find('.atbdp_social_input').attr('name', "social[".concat(index, "][url]"));
            e.find('.directorist-form-social-fields__remove').attr('data-id', index);
          });
        });

        // show success message
        swal({
          title: localized_data.i18n_text.deleted,
          // text: "Item has been deleted.",
          type: 'success',
          timer: 200,
          showConfirmButton: false
        });
      }
    });
  });

  /* This function handles all ajax request */
  function atbdp_do_ajax(ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler) {
    var data;
    if (ActionName) data = "action=".concat(ActionName);
    if (arg) data = "".concat(arg, "&action=").concat(ActionName);
    if (arg && !ActionName) data = arg;
    // data = data ;

    var n = data.search(localized_data.nonceName);
    if (n < 0) {
      var nonce = typeof directorist !== 'undefined' ? directorist.directorist_nonce : directorist_admin.directorist_nonce;
      data = "".concat(data, "&", 'directorist_nonce', "=").concat(nonce);
    }
    jQuery.ajax({
      type: 'post',
      url: localized_data.ajaxurl,
      data: data,
      beforeSend: function beforeSend() {
        jQuery("<span class='atbdp_ajax_loading'></span>").insertAfter(ElementToShowLoadingIconAfter);
      },
      success: function success(data) {
        jQuery('.atbdp_ajax_loading').remove();
        CallBackHandler(data);
      }
    });
  }

  // Select2 js code
  // if (!localized_data.is_admin) {
  // Location
  // const createLoc = $('#at_biz_dir-location').attr("data-allow_new");
  // let maxLocationLength = $('#at_biz_dir-location').attr("data-max");
  // if (createLoc) {
  //     $("#at_biz_dir-location").select2({
  //         tags: true,
  //         maximumSelectionLength: maxLocationLength,
  //         language: {
  //             maximumSelected: function () {
  //                 return localized_data.i18n_text.max_location_msg;
  //             }
  //         },
  //         tokenSeparators: [","],
  //     });
  // } else {
  //     $("#at_biz_dir-location").select2({
  //         allowClear: true,
  //         tags: false,
  //         maximumSelectionLength: maxLocationLength,
  //         tokenSeparators: [","],
  //     });
  // }

  // // Tags
  // const createTag = $('#at_biz_dir-tags').attr("data-allow_new");
  // let maxTagLength = $('#at_biz_dir-tags').attr("data-max");
  // if (createTag) {
  //     $('#at_biz_dir-tags').select2({
  //         tags: true,
  //         maximumSelectionLength: maxTagLength,
  //         tokenSeparators: [','],
  //     });
  // } else {
  //     $('#at_biz_dir-tags').select2({
  //         allowClear: true,
  //         maximumSelectionLength: maxTagLength,
  //         tokenSeparators: [','],
  //     });
  // }

  // //Category
  // const createCat = $('#at_biz_dir-categories').attr("data-allow_new");
  // let maxCatLength = $('#at_biz_dir-categories').attr("data-max");
  // if (createCat) {
  //     $('#at_biz_dir-categories').select2({
  //         allowClear: true,
  //         tags: true,
  //         maximumSelectionLength: maxCatLength,
  //         tokenSeparators: [','],
  //     });
  // } else {
  //     $('#at_biz_dir-categories').select2({
  //         maximumSelectionLength: maxCatLength,
  //         allowClear: true,
  //     });
  // }
  // }

  /**
   * Price field.
   */
  function getPriceTypeInput(typeId) {
    return $("#".concat($("[for=\"".concat(typeId, "\"]")).data('option')));
  }
  $('.directorist-form-pricing-field__options').on('change', 'input', function () {
    var $otherOptions = $(this).parent().siblings('.directorist-checkbox').find('input');
    $otherOptions.prop('checked', false);
    getPriceTypeInput($otherOptions.attr('id')).hide();
    if (this.checked) {
      getPriceTypeInput(this.id).show();
    } else {
      getPriceTypeInput(this.id).hide();
    }
  });
  if ($('.directorist-form-pricing-field').hasClass('price-type-both')) {
    $('#price_range, #price').hide();
    var $selectedPriceType = $('.directorist-form-pricing-field__options input:checked');
    if ($selectedPriceType.length) {
      getPriceTypeInput($selectedPriceType.attr('id')).show();
    } else {
      $($('.directorist-form-pricing-field__options input').get(0)).prop('checked', true).trigger('change');
    }
  }
  var has_tagline = $('#has_tagline').val();
  var has_excerpt = $('#has_excerpt').val();
  if (has_excerpt && has_tagline) {
    $('.atbd_tagline_moto_field').fadeIn();
  } else {
    $('.atbd_tagline_moto_field').fadeOut();
  }
  $('#atbd_optional_field_check').on('change', function () {
    $(this).is(':checked') ? $('.atbd_tagline_moto_field').fadeIn() : $('.atbd_tagline_moto_field').fadeOut();
  });

  // it shows the hidden term and conditions
  $('#listing_t_c').on('click', function (e) {
    e.preventDefault();
    $('#tc_container').toggleClass('active');
  });

  // Load custom fields of the selected category in the custom post type "atbdp_listings"
  var qs = function (a) {
    if (a == '') return {};
    var b = {};
    for (var i = 0; i < a.length; ++i) {
      var p = a[i].split('=', 2);
      if (p.length == 1) b[p[0]] = '';else b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, ' '));
    }
    return b;
  }(window.location.search.substr(1).split('&'));
  function renderCategoryCustomFields() {
    if ((0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1__["default"])(localized_data === null || localized_data === void 0 ? void 0 : localized_data.category_custom_field_relations) !== 'object') {
      return;
    }
    var categoryIds = [];
    var directoryId = 0;
    var fieldsMap = localized_data.category_custom_field_relations;
    var categoryInputSelector = directorist.is_admin ? '#at_biz_dir-categorychecklist input:checked' : '#at_biz_dir-categories option:selected';
    directoryId = $('select[name="directory_type"]', getWrapper()).val();
    if (!directoryId) {
      directoryId = $('input[name="directory_type"]', getWrapper()).val();
    }
    if (typeof fieldsMap[directoryId] === 'undefined' || fieldsMap[directoryId].length === 0) {
      return;
    }
    var $selectedCategories = $(categoryInputSelector);
    if ($selectedCategories.length) {
      categoryIds = $selectedCategories.toArray().map(function (el) {
        return Number(el.value);
      });
    }
    var $watchableSections = {
      hide: new Set(),
      show: new Set()
    };
    categoryIds = new Set(categoryIds);
    for (var _i = 0, _Object$entries = Object.entries(fieldsMap[directoryId]); _i < _Object$entries.length; _i++) {
      var _Object$entries$_i = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_Object$entries[_i], 2),
        fieldKey = _Object$entries$_i[0],
        categoryId = _Object$entries$_i[1];
      var $input = $(fieldKey.includes('checkbox') ? "[name=\"".concat(fieldKey, "[]\"]") : "[name=\"".concat(fieldKey, "\"]"));
      var $wrapper = $input.closest('.directorist-form-group');
      if (categoryIds.has(categoryId)) {
        $input.removeAttr('disabled');
        $wrapper.show();
        $watchableSections.show.add($wrapper.closest('.directorist-form-section').get(0));
      } else {
        $input.attr('disabled', true);
        $wrapper.hide();
        $watchableSections.hide.add($wrapper.closest('.directorist-form-section').get(0));
      }
    }
    if ($watchableSections.show.size) {
      var _iterator = _createForOfIteratorHelper($watchableSections.show),
        _step;
      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var visible = _step.value;
          var $visible = $(visible);
          $visible.removeAttr('style');
          $visible.find('.directorist-content-module__title').show().addClass('opened');
          $visible.find('.directorist-content-module__contents').show().addClass('active');
          $("a[href=\"#".concat($visible.attr('id'), "\"]")).show();
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }
    }
    if ($watchableSections.hide.size) {
      var _iterator2 = _createForOfIteratorHelper($watchableSections.hide),
        _step2;
      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var hidable = _step2.value;
          var $hidable = $(hidable);
          if ($hidable.find('.directorist-form-group:visible').length) {
            $hidable.removeAttr('style');
            $hidable.find('.directorist-content-module__title').show().addClass('opened');
            $hidable.find('.directorist-content-module__contents').show().addClass('active');
            $("a[href=\"#".concat($hidable.attr('id'), "\"]")).show();
          } else {
            $hidable.css({
              display: 'none',
              height: 0,
              padding: 0,
              margin: 0,
              border: 0,
              overflow: 'hidden'
            });
            $hidable.find('.directorist-content-module__title').hide().removeClass('opened');
            $hidable.find('.directorist-content-module__contents').hide().removeClass('active');
            $("a[href=\"#".concat($hidable.attr('id'), "\"]")).hide();
          }
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }
    }
  }
  window.addEventListener('load', function () {
    renderCategoryCustomFields();
    // cacheCategoryCustomFields();
  });
  window.addEventListener('directorist-type-change', function () {
    renderCategoryCustomFields();
    // cacheCategoryCustomFields();
  });

  // Render category based fields on category change (frontend)
  $('#at_biz_dir-categories').on('change', function () {
    renderCategoryCustomFields();
    // cacheCategoryCustomFields();
  });

  // Render category based fields on category change (backend)
  $('#at_biz_dir-categorychecklist').on('change', function () {
    renderCategoryCustomFields();
    // cacheCategoryCustomFields();
  });

  // Make sure to place the following event trigger after the event bindings.
  if (!directorist.is_admin) {
    if (directorist.lazy_load_taxonomy_fields) {
      $('#at_biz_dir-categories').on('select2:select', function () {
        $('#at_biz_dir-categories').trigger('change');
      });
    } else {
      $('#at_biz_dir-categories').trigger('change');
    }
  }
  function atbdp_element_value(element) {
    var field = $(element);
    if (field.length) {
      return field.val();
    }
  }
  var mediaUploaders = [];
  if (localized_data.media_uploader) {
    var _iterator3 = _createForOfIteratorHelper(localized_data.media_uploader),
      _step3;
    try {
      for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
        var uploader = _step3.value;
        if ($('.' + uploader.element_id).length) {
          var EzUploader = new EzMediaUploader({
            containerClass: uploader.element_id
          });
          mediaUploaders.push({
            media_uploader: EzUploader,
            uploaders_data: uploader
          });
          EzUploader.init();
          // mediaUploaders[i].media_uploader.init();
        }
      }
    } catch (err) {
      _iterator3.e(err);
    } finally {
      _iterator3.f();
    }
  }
  var FORM_ON_PROCESSING = false;
  var has_media = true;
  var quickLoginModalSuccessCallback = null;
  var $notification = $('#listing_notifier');
  var UPLOADED_IMAGES_CACHE = new WeakMap();

  // -----------------------------
  // Submit The Form
  // -----------------------------

  $('body').on('submit', '#directorist-add-listing-form', function (e) {
    e.preventDefault();
    if (FORM_ON_PROCESSING) {
      return;
    }
    var $form = $(e.target);
    var err_log = {};
    var $submitButton = $form.find('.directorist-form-submit__btn');
    var error_count = 0;
    var uploadableImages = [];
    var counter = 0;
    var $directory = $form.find("input[name='directory_type']");
    var directory_id = $directory !== undefined ? $directory.val() : 0;
    directory_id = qs.directory_type ? qs.directory_type : directory_id;
    function disableSubmitButton() {
      FORM_ON_PROCESSING = true;
      $submitButton.addClass('atbd_loading').attr('disabled', true);
    }
    function enableSubmitButton() {
      FORM_ON_PROCESSING = false;
      $submitButton.removeClass('atbd_loading').attr('disabled', false);
    }
    if (mediaUploaders.length) {
      for (var _i2 = 0, _mediaUploaders = mediaUploaders; _i2 < _mediaUploaders.length; _i2++) {
        var uploader = _mediaUploaders[_i2];
        if (!uploader.media_uploader || $(uploader.media_uploader.container).parents('form').get(0) !== $form.get(0)) {
          continue;
        }
        if (!uploader.media_uploader.hasValidFiles()) {
          $submitButton.removeClass('atbd_loading');
          err_log.listing_gallery = {
            msg: uploader.uploaders_data['error_msg']
          };
          error_count++;
          scrollTo('.' + uploader.uploaders_data.element_id);
          break;
        }
        uploader.media_uploader.getTheFiles().forEach(function (file) {
          if (UPLOADED_IMAGES_CACHE.has(file)) {
            return;
          }
          uploadableImages.push({
            field: uploader.uploaders_data.meta_name,
            file: file,
            uploadedFile: ''
          });
        });
      }
    }
    if (uploadableImages.length) {
      function uploadImage() {
        if (UPLOADED_IMAGES_CACHE.has(uploadableImages[counter].file)) {
          return;
        }
        var formData = new FormData();

        // formData.append( 'action', 'directorist_upload_listing_image' );
        // formData.append( 'directorist_nonce', directorist.directorist_nonce );
        // formData.append( 'file', uploadableImages[ counter ] );
        formData.append('file', uploadableImages[counter].file);
        formData.append('field', uploadableImages[counter].field);
        formData.append('directory', directory_id);
        // formData.append( 'field', uploadableImages[ counter ].field );

        $.ajax({
          method: 'POST',
          processData: false,
          contentType: false,
          mimeType: 'multipart/form-data',
          async: true,
          url: directorist.rest_url + 'directorist/v1/temp-media-upload',
          data: formData,
          beforeSend: function beforeSend(xhr) {
            xhr.setRequestHeader('X-WP-Nonce', directorist.rest_nonce);
            disableSubmitButton();
            var totalImages = uploadableImages.length;
            if (totalImages === 1) {
              $notification.show().html('<span class="atbdp_success">'.concat(localized_data.i18n_text.image_uploading_msg, '</span>'));
            } else {
              var completedPercent = Math.ceil((counter === 0 ? 1 : counter) * 100 / totalImages);
              $notification.show().html('<span class="atbdp_success">'.concat(localized_data.i18n_text.image_uploading_msg, ' (').concat(completedPercent, '%)</span>'));
            }
          },
          success: function success(response) {
            var data = JSON.parse(response);
            uploadableImages[counter].uploadedFile = data.file;
            UPLOADED_IMAGES_CACHE.set(uploadableImages[counter].file, true);
            ++counter;
            if (counter < uploadableImages.length) {
              uploadImage();
            } else {
              submitForm($form, uploadableImages);
            }
          },
          error: function error(xhr) {
            var data = JSON.parse(xhr.responseText);
            enableSubmitButton();
            $notification.html('<span class="atbdp_error">'.concat(data.message, '</span>'));
          }
        });
      }
      if (counter < uploadableImages.length) {
        uploadImage();
      } else {
        submitForm($form, uploadableImages);
      }
    } else {
      submitForm($form);
    }
    function submitForm($form) {
      var uploadedImages = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
      var error_count = 0;
      var err_log = {};
      var form_data = new FormData();
      form_data.append('action', 'add_listing_action');
      form_data.append('directorist_nonce', directorist.directorist_nonce);
      disableSubmitButton();
      var fieldValuePairs = $form.serializeArray();

      // Append Form Fields Values
      var _iterator4 = _createForOfIteratorHelper(fieldValuePairs),
        _step4;
      try {
        for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
          var field = _step4.value;
          form_data.append(field.name, field.value);
        }
      } catch (err) {
        _iterator4.e(err);
      } finally {
        _iterator4.f();
      }
      if (mediaUploaders.length) {
        var _loop = function _loop() {
          var uploader = _mediaUploaders2[_i3];
          if (!uploader.media_uploader || $(uploader.media_uploader.container).parents('form').get(0) !== $form.get(0)) {
            return 1; // continue
          }
          if (uploader.media_uploader.hasValidFiles()) {
            uploader.media_uploader.getFilesMeta().forEach(function (file_meta) {
              if (file_meta.attachmentID) {
                form_data.append(''.concat(uploader.uploaders_data.meta_name, '_old[]'), file_meta.attachmentID);
              }
            });
          } else {
            err_log.listing_gallery = {
              msg: uploader.uploaders_data['error_msg']
            };
            error_count++;
            if ($('.' + uploader.uploaders_data.element_id).length) {
              scrollTo('.' + uploader.uploaders_data.element_id);
            }
          }
        };
        for (var _i3 = 0, _mediaUploaders2 = mediaUploaders; _i3 < _mediaUploaders2.length; _i3++) {
          if (_loop()) continue;
        }
      }

      // Upload new image
      if (uploadedImages.length) {
        uploadedImages.forEach(function (image) {
          form_data.append(''.concat(image.field, '[]'), image.uploadedFile);
        });
      }

      // categories
      var categories = $form.find('#at_biz_dir-categories').val();
      if (Array.isArray(categories) && categories.length) {
        for (var key in categories) {
          form_data.append('tax_input[at_biz_dir-category][]', categories[key]);
        }
      }
      if (typeof categories === 'string') {
        form_data.append('tax_input[at_biz_dir-category][]', categories);
      }
      if (form_data.has('admin_category_select[]')) {
        form_data.delete('admin_category_select[]');
      }
      if (form_data.has('directory_type')) {
        form_data.delete('directory_type');
      }
      form_data.append('directory_type', directory_id);
      if (qs.plan) {
        form_data.append('plan_id', qs.plan);
      }
      if (qs.order) {
        form_data.append('order_id', qs.order);
      }
      if (error_count) {
        enableSubmitButton();
        return;
      }
      $.ajax({
        method: 'POST',
        processData: false,
        contentType: false,
        url: localized_data.ajaxurl,
        data: form_data,
        headers: (0,_helper__WEBPACK_IMPORTED_MODULE_3__.directoristRequestHeaders)(),
        beforeSend: function beforeSend() {
          disableSubmitButton();
          $notification.show().html('<span class="atbdp_success">'.concat(localized_data.i18n_text.submission_wait_msg, '</span>'));
        },
        success: function success(response) {
          var redirect_url = response && response.redirect_url ? response.redirect_url : '';
          redirect_url = redirect_url && typeof redirect_url === 'string' ? response.redirect_url.replace(/:\/\//g, '%3A%2F%2F') : '';
          if ((response === null || response === void 0 ? void 0 : response.nonce_expired) === true) {
            updateLocalNonce();
          }
          if (response.error === true) {
            enableSubmitButton();
            $notification.show().html('<span>'.concat(response.error_msg, '</span>'));
            if (response.quick_login_required) {
              var modal = $('#directorist-quick-login');
              var email = response.email;

              // Prepare fields
              modal.find('input[name="email"]').val(email);
              modal.find('input[name="email"]').prop('disabled', true);

              // Show alert
              var alert = '<div class="directorist-alert directorist-alert-info directorist-mb-10 atbd-text-center directorist-mb-10">' + response.error_msg + '</div>';
              modal.find('.directorist-modal-alerts-area').html(alert);

              // Show the modal
              modal.addClass('show');
              quickLoginModalSuccessCallback = function quickLoginModalSuccessCallback($form, $submitButton) {
                $('#guest_user_email').prop('disabled', true);
                $notification.hide().html('');
                $submitButton.remove();
                $form.find('.directorist-form-actions').find('.directorist-toggle-modal').removeClass('directorist-d-none');
              };
            }
          } else {
            // preview on and no need to redirect to payment
            if (response.preview_mode === true && response.need_payment !== true) {
              if (response.edited_listing !== true) {
                $notification.show().html('<span class="atbdp_success">'.concat(response.success_msg, '</span>'));
                window.location.href = joinQueryString(response.preview_url, 'preview=1&redirect='.concat(redirect_url));
              } else {
                $notification.show().html('<span class="atbdp_success">'.concat(response.success_msg, '</span>'));
                if (qs.redirect) {
                  window.location.href = joinQueryString(response.preview_url, 'post_id='.concat(response.id, '&preview=1&payment=1&edited=1&redirect=').concat(qs.redirect));
                } else {
                  window.location.href = joinQueryString(response.preview_url, 'preview=1&edited=1&redirect='.concat(redirect_url));
                }
              }
              // preview mode active and need payment
            } else if (response.preview_mode === true && response.need_payment === true) {
              window.location.href = joinQueryString(response.preview_url, 'preview=1&payment=1&redirect='.concat(redirect_url));
            } else {
              var is_edited = response.edited_listing ? 'listing_id='.concat(response.id, '&edited=1') : '';
              if (response.need_payment === true) {
                $notification.show().html('<span class="atbdp_success">'.concat(response.success_msg, '</span>'));
                window.location.href = decodeURIComponent(redirect_url);
              } else {
                $notification.show().html('<span class="atbdp_success">'.concat(response.success_msg, '</span>'));
                window.location.href = joinQueryString(decodeURIComponent(response.redirect_url), is_edited);
              }
            }
          }
        },
        error: function error(_error) {
          enableSubmitButton();
        }
      });
    }
  });

  // Custom Field Checkbox Button More
  function customFieldSeeMore() {
    if ($('.directorist-custom-field-btn-more').length) {
      $('.directorist-custom-field-btn-more').each(function (index, element) {
        var fieldWrapper = $(element).closest('.directorist-custom-field-checkbox, .directorist-custom-field-radio');
        var customField = $(fieldWrapper).find('.directorist-checkbox, .directorist-radio');
        $(customField).slice(20, customField.length).hide();
        if (customField.length <= 20) {
          $(element).hide();
        }
      });
    }
  }
  $(window).on('load', function () {
    customFieldSeeMore();
  });
  $('body').on('click', '.directorist-custom-field-btn-more', function (event) {
    event.preventDefault();
    var fieldWrapper = $(this).closest('.directorist-custom-field-checkbox, .directorist-custom-field-radio');
    var customField = $(fieldWrapper).find('.directorist-checkbox, .directorist-radio');
    $(customField).slice(20, customField.length).slideUp();
    $(this).toggleClass('active');
    if ($(this).hasClass('active')) {
      $(this).text(localized_data.i18n_text.see_less_text);
      $(customField).slice(20, customField.length).slideDown();
    } else {
      $(this).text(localized_data.i18n_text.see_more_text);
      $(customField).slice(20, customField.length).slideUp();
    }
  });

  // ------------------------------
  // Quick Login
  // ------------------------------
  $('#directorist-quick-login .directorist-toggle-modal').on('click', function (e) {
    e.preventDefault();
    $('#directorist-quick-login').removeClass('show');
  });
  $('#quick-login-from-submit-btn').on('click', function (e) {
    e.preventDefault();
    var $form = $($(this).data('form'));
    var $feedback = $form.find('.directorist-modal-alerts-area');
    $feedback = $feedback.length ? $feedback : $form.find('.directorist-form-feedback');
    var $email = $form.find('input[name="email"]');
    var $password = $form.find('input[name="password"]');
    var $token = $form.find('input[name="directorist-quick-login-security"]');
    var $submit_button = $(this);
    var submit_button_html = $submit_button.html();
    var form_data = {
      action: 'directorist_ajax_quick_login',
      username: $email.val(),
      password: $password.val(),
      rememberme: false,
      token: $token.val()
    };
    $.ajax({
      method: 'POST',
      url: directorist.ajaxurl,
      data: form_data,
      beforeSend: function beforeSend() {
        $feedback.html('');
        $submit_button.prop('disabled', true);
        $submit_button.prepend('<i class="fas fa-circle-notch fa-spin"></i> ');
      },
      success: function success(response) {
        $submit_button.html(submit_button_html);
        if (response.loggedin) {
          $password.prop('disabled', true);
          var message = 'Successfully logged in, please continue to the listing submission';
          var msg = '<div class="directorist-alert directorist-alert-success directorist-text-center directorist-mb-20">' + message + '</div>';
          $feedback.html(msg);
          if (quickLoginModalSuccessCallback) {
            quickLoginModalSuccessCallback($form, $submit_button);
          }
          updateLocalNonce();
        } else {
          var msg = '<div class="directorist-alert directorist-alert-danger directorist-text-center directorist-mb-20">' + response.message + '</div>';
          $feedback.html(msg);
          $submit_button.prop('disabled', false);
        }
      },
      error: function error(_error2) {
        $submit_button.prop('disabled', false);
        $submit_button.html(submit_button_html);
      }
    });
  });
  function addSticky() {
    $(window).scroll((0,_components_debounce__WEBPACK_IMPORTED_MODULE_8__["default"])(function () {
      var windowWidth = $(window).width();
      var sidebarWidth = $('.multistep-wizard__nav').width();
      var sidebarHeight = $('.multistep-wizard__nav').height();
      var multiStepWizardOffset = $('.multistep-wizard').offset() && $('.multistep-wizard').offset().top;
      var multiStepWizardHeight = $('.multistep-wizard').outerHeight();
      if (windowWidth > 991) {
        var scrollPos = $(window).scrollTop();

        // Check if the user has scrolled down to the container position
        if (scrollPos >= multiStepWizardOffset) {
          $('.multistep-wizard__nav').addClass('sticky');
          $('.multistep-wizard__content').css('padding-inline-start', sidebarWidth + 30 + 'px');
          // Check if the user has fully scrolled the container
          if (scrollPos >= multiStepWizardOffset + multiStepWizardHeight - sidebarHeight) {
            $('.multistep-wizard__nav').removeClass('sticky');
            $('.multistep-wizard__content').css('padding-inline-start', '0px');
          } else {
            $('.multistep-wizard__nav').addClass('sticky');
            $('.multistep-wizard__content').css('padding-inline-start', sidebarWidth + 30 + 'px');
          }
        } else {
          $('.multistep-wizard__nav').removeClass('sticky');
          $('.multistep-wizard__content').css('padding-inline-start', '0px');
        }
      } else {
        $('.multistep-wizard__nav').removeClass('sticky');
        $('.multistep-wizard__content').css('padding-inline-start', '0px');
      }
    }, 100));
  }
  addSticky();
  multiStepWizard();
  defaultAddListing();
});

// MultiStep Wizard
function multiStepWizard() {
  var defaultAddListing = document.querySelector('.multistep-wizard.default-add-listing');
  if (!defaultAddListing) {
    var totalStep = document.querySelectorAll('.multistep-wizard .multistep-wizard__nav__btn');
    var totalWizard = document.querySelectorAll('.multistep-wizard .multistep-wizard__single');
    var totalWizardCount = document.querySelector('.multistep-wizard .multistep-wizard__count__total');
    var currentWizardCount = document.querySelector('.multistep-wizard .multistep-wizard__count__current');
    var progressWidth = document.querySelector('.multistep-wizard .multistep-wizard__progressbar__width');
    var stepCount = 1;
    var progressPerStep = 100 / totalWizard.length;

    // Initialize Wizard Count & Progressbar
    if (currentWizardCount) {
      currentWizardCount.innerHTML = stepCount;
    }
    if (totalWizardCount) {
      totalWizardCount.innerHTML = totalWizard.length;
    }
    if (progressWidth) {
      progressWidth.style.width = progressPerStep + '%';
    }

    // Set data-id on Wizards
    totalWizard.forEach(function (item, index) {
      item.setAttribute('data-id', index);
      item.style.display = 'none';
      if (index === 0) {
        item.style.display = 'block';
        item.classList.add('active');
      }
    });

    // Set data-step on Nav Items
    totalStep.forEach(function (item, index) {
      item.setAttribute('data-step', index);
      if (index === 0) {
        item.classList.add('active');
      }
    });

    // Go Previous Step
    $('.multistep-wizard__btn--prev').on('click', function (e) {
      e.preventDefault();
      if (stepCount > 1) {
        stepCount--;
        activeWizard(stepCount);
        if (stepCount <= 1) {
          this.setAttribute('disabled', true);
        }
      }
    });

    // Go Next Step
    $('.multistep-wizard__btn--next').on('click', function (e) {
      e.preventDefault();
      if (stepCount < totalWizard.length) {
        stepCount++;
        activeWizard(stepCount);
      }
    });

    // Go Random Step
    $('.multistep-wizard__nav__btn').on('click', function (e) {
      e.preventDefault();
      if (this.classList.contains('completed')) {
        var currentStep = Number(this.attributes[3].value) + 1;
        stepCount = currentStep;
        activeWizard(stepCount);
      }
      if (stepCount <= 1) {
        $('.multistep-wizard__btn--prev').attr('disabled', true);
      }
    });

    // Active Wizard
    function activeWizard(value) {
      // Add Active Class
      totalWizard.forEach(function (item, index) {
        if (item.classList.contains('active')) {
          item.classList.remove('active');
          item.style.display = 'none';
        } else if (value - 1 === index) {
          item.classList.add('active');
          item.style.display = 'block';
        }
      });

      // Add Completed Class
      totalStep.forEach(function (item, index) {
        if (index + 1 < value) {
          item.classList.add('completed');
        } else {
          item.classList.remove('completed');
        }
        if (item.classList.contains('active')) {
          item.classList.remove('active');
        } else if (value - 1 === index) {
          item.classList.add('active');
        }
      });

      // Enable Previous Button
      if (value > 1) {
        $('.multistep-wizard__btn--prev').removeAttr('disabled');
      }

      // Change Button Text on Last Step
      var nextBtn = document.querySelector('.multistep-wizard__btn--next');
      var previewBtn = document.querySelector('.multistep-wizard__btn--save-preview');
      var submitBtn = document.querySelector('.multistep-wizard__btn--skip-preview');
      if (value === totalWizard.length) {
        nextBtn.style.cssText = 'display:none; width: 0; height: 0; opacity: 0; visibility: hidden;';
        previewBtn.style.cssText = 'height: 54px; flex: unset; opacity: 1; visibility: visible;';
        submitBtn.style.cssText = 'height: 54px; opacity: 1; visibility: visible;';
      } else {
        nextBtn.style.cssText = 'display:inline-flex; width: 200px; height: 54px; opacity: 1; visibility: visible;';
        previewBtn.style.cssText = 'height: 0; flex: 0 0 100%; opacity: 0; visibility: hidden;';
        submitBtn.style.cssText = 'height: 0; opacity: 0; visibility: hidden;';
      }

      // Update Wizard Count & Progressbar
      currentWizardCount.innerHTML = value;
      progressWidth.style.width = progressPerStep * value + '%';
      progressWidth.style.transition = '0.5s ease';
    }
  }
}

// Default Add Listing
function defaultAddListing() {
  var navLinks = document.querySelectorAll('.default-add-listing .multistep-wizard__nav .multistep-wizard__nav__btn');

  // Add 'active' class to the first navigation item on page load
  window.addEventListener('load', function () {
    if (navLinks.length > 0) {
      navLinks[0].classList.add('active');
    }
  });

  // Function to determine which section is currently in view
  function getCurrentSectionInView() {
    var currentSection = null;
    var sections = document.querySelectorAll('.default-add-listing .multistep-wizard__content .multistep-wizard__single');
    if (sections) {
      sections.forEach(function (section) {
        var rect = section.getBoundingClientRect();
        if (rect.top <= 50 && rect.bottom >= 50) {
          currentSection = section.getAttribute('id');
        }
      });
    }
    return currentSection;
  }

  // Function to update active class on navigation items
  function updateActiveNav() {
    var currentSection = getCurrentSectionInView();
    if (currentSection == null) {
      navLinks[0].classList.add('active');
    } else {
      if (navLinks[0].classList.contains('active')) {
        navLinks[0].classList.remove('active');
      }
      navLinks.forEach(function (link) {
        if (link.getAttribute('href') === "#".concat(currentSection)) {
          link.classList.add('active');
        } else {
          link.classList.remove('active');
        }
      });
    }
  }

  // Function to scroll smoothly to the target section
  function smoothScroll(targetSection) {
    var scrollDuration = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1000;
    var targetElement = document.getElementById(targetSection);
    if (!targetElement) return;
    var targetPosition = targetElement.getBoundingClientRect().top + window.scrollY;
    var startPosition = window.scrollY;
    var scrollDistance = targetPosition - startPosition;
    var startTime = null;
    function scrollAnimation(currentTime) {
      if (startTime === null) startTime = currentTime;
      var timeElapsed = currentTime - startTime;
      var run = easeInOutQuad(timeElapsed, startPosition, scrollDistance, scrollDuration);
      window.scrollTo(0, run);
      if (timeElapsed < scrollDuration) {
        requestAnimationFrame(scrollAnimation); // Continue the scrollAnimation
      }
    }
    function easeInOutQuad(t, b, c, d) {
      t /= d / 2;
      if (t < 1) return c / 2 * t * t + b;
      t--;
      return -c / 2 * (t * (t - 2) - 1) + b;
    }
    requestAnimationFrame(scrollAnimation); // Start the scrollAnimation
  }

  // Initial update and update on scroll
  if (navLinks.length > 0) {
    updateActiveNav();
    window.addEventListener('scroll', updateActiveNav);
  }

  // Add smooth scroll to navigation links
  navLinks.forEach(function (link) {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      var targetSection = this.getAttribute('href').substring(1);
      smoothScroll(targetSection, 1250);
    });
  });
}

// Add Listing Accordion
function addListingAccordion() {
  // Set default state to open for all content modules
  $('.directorist-add-listing-form .directorist-content-module__title').addClass('opened');
  $('.directorist-add-listing-form .directorist-content-module__contents').addClass('active');
  $('body').on('click', '.directorist-add-listing-form .directorist-content-module__title', function (e) {
    e.preventDefault();
    var windowScreen = window.innerWidth;
    if (windowScreen <= 991) {
      $(this).toggleClass('opened');
      $(this).next('.directorist-content-module__contents').toggleClass('active');
    }
  });
}
addListingAccordion();

// Multistep Add Listing on Elementor EditMode
$(window).on('elementor/frontend/init', function () {
  setTimeout(function () {
    if ($('body').hasClass('elementor-editor-active')) {
      multiStepWizard();
    }
  }, 3000);
});
$('body').on('click', function (e) {
  if ($('body').hasClass('elementor-editor-active') && e.target.nodeName !== 'A' && e.target.nodeName !== 'BUTTON') {
    multiStepWizard();
  }
});
function updateLocalNonce() {
  $.ajax({
    type: 'POST',
    url: localized_data.ajaxurl,
    data: {
      action: 'directorist_generate_nonce'
    },
    success: function success(response) {
      if (response.success) {
        window.directorist.directorist_nonce = response.data.directorist_nonce;
      }
    }
  });
}

/**
 * Conditional Logic Evaluation for Frontend Form
 */
(function ($) {
  'use strict';

  // Set up conditional logic functions with dependencies
  var getFieldValueFn = function getFieldValueFn(fieldKey) {
    return (0,_components_conditional_logic__WEBPACK_IMPORTED_MODULE_7__.getFieldValue)(fieldKey, $);
  };
  var evaluateConditionalLogicFn = function evaluateConditionalLogicFn(conditionalLogic) {
    return (0,_components_conditional_logic__WEBPACK_IMPORTED_MODULE_7__.evaluateConditionalLogic)(conditionalLogic, getFieldValueFn);
  };
  var applyConditionalLogicFn = function applyConditionalLogicFn($fieldWrapper) {
    return (0,_components_conditional_logic__WEBPACK_IMPORTED_MODULE_7__.applyConditionalLogic)($fieldWrapper, evaluateConditionalLogicFn, $);
  };
  var initConditionalLogicFn = function initConditionalLogicFn() {
    return (0,_components_conditional_logic__WEBPACK_IMPORTED_MODULE_7__.initConditionalLogic)(getWrapper, getFieldValueFn, applyConditionalLogicFn, $, localized_data.admin_conditional_logic_targets || []);
  };
  var watchFieldChangesFn = function watchFieldChangesFn() {
    return (0,_components_conditional_logic__WEBPACK_IMPORTED_MODULE_7__.watchFieldChanges)(getWrapper, getFieldValueFn, applyConditionalLogicFn, $);
  };
  var updateCategoryFieldLabelFn = function updateCategoryFieldLabelFn() {
    return (0,_components_conditional_logic__WEBPACK_IMPORTED_MODULE_7__.updateCategoryFieldLabel)(initConditionalLogicFn, $);
  };

  // Initialize on page load
  $(document).ready(function () {
    watchFieldChangesFn();
    // Wait a bit longer to ensure Select2 and all fields are initialized
    setTimeout(function () {
      initConditionalLogicFn();
    }, 800);

    // Also try after a longer delay to catch any late-loading fields
    setTimeout(function () {
      initConditionalLogicFn();
    }, 2000);
  });

  // Re-initialize when form is reloaded (e.g., after directory type change)
  $(window).on('directorist-type-change', function () {
    setTimeout(function () {
      initConditionalLogicFn();
    }, 500);
  });

  // Re-initialize after category custom fields are rendered
  $(window).on('load', function () {
    setTimeout(function () {
      initConditionalLogicFn();
    }, 1000);
  });

  // Re-initialize after Select2 is initialized
  $(document).on('select2-loaded', function () {
    setTimeout(function () {
      initConditionalLogicFn();
    }, 200);
  });

  // Watch for Select2 changes on category field
  $(document).on('select2:select select2:unselect select2:clear', '#at_biz_dir-categories', function () {
    updateCategoryFieldLabelFn();
  });

  // Also watch for changes on the category field
  $(document).on('change', '#at_biz_dir-categories', function () {
    updateCategoryFieldLabelFn();
  });

  // Watch for custom category field change events
  $(document).on('directorist-category-changed', function () {
    updateCategoryFieldLabelFn();
  });

  // Also trigger after category custom fields are rendered (they might update category field)
  $(window).on('load', function () {
    setTimeout(function () {
      updateCategoryFieldLabelFn();
    }, 1500);
  });
})(jQuery);
}();
/******/ })()
;
//# sourceMappingURL=add-listing.js.map