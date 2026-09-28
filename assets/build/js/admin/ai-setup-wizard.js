/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

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
/******/ 	/* webpack/runtime/define property getters */
/******/ 	// define getter/value functions for harmony exports
/******/ 	__webpack_require__.d = function(exports, definition) {
/******/ 		for(var key in definition) {
/******/ 			if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 				Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 			}
/******/ 		}
/******/ 	};
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); };
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
!function() {
/*!************************************************!*\
  !*** ./assets/src/js/admin/ai-setup-wizard.js ***!
  \************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");

(function ($) {
  'use strict';

  var config = window.directoristAiSetup || {};
  var i18n = config.i18n || {};
  var maxRegenerations = 3;
  var state = {
    prompt: '',
    setup: null,
    selected: [],
    regenerateCount: 0
  };
  var presets = {
    restaurant: 'Create a restaurant directory for local dining spots with cuisine, price range, location, contact, menu link, photos, and booking information.',
    job: 'Create a job board for tech roles with job title, company, salary range, location, job type, skills, application link, and deadline.',
    realestate: 'Create a real estate directory for apartments and homes with price, property type, bedrooms, bathrooms, area, address, map, photos, and contact details.',
    classified: 'Create a classifieds marketplace for local buy and sell listings with price, condition, category, location, seller contact, photos, and product details.',
    service: 'Create a service marketplace for local professionals with service type, pricing, service area, experience, portfolio link, contact, and photos.',
    local: 'Create a local business directory with categories, address, phone, email, website, map, photos, social links, and short business details.',
    automotive: 'Create a car listings directory with make, model, year, mileage, price, condition, location, seller contact, photos, and video.',
    hotel: 'Create a hotel directory with room type, price range, amenities, location, address, phone, website, photos, and booking link.',
    medical: 'Create a medical directory for clinics and doctors with specialty, location, address, phone, website, appointment link, insurance options, and photos.',
    legal: 'Create a legal directory for lawyers and law firms with practice area, location, consultation type, phone, email, website, and profile details.'
  };
  var typeLabels = {
    image_upload: 'Images',
    social_info: 'Social Info',
    terms_privacy: 'Terms',
    color_picker: 'Color',
    phone2: 'Phone 2',
    html: 'HTML',
    url: 'URL'
  };
  var presetTypes = {
    title: true,
    description: true,
    tagline: true,
    pricing: true,
    excerpt: true,
    location: true,
    tag: true,
    category: true,
    map: true,
    address: true,
    zip: true,
    phone: true,
    phone2: true,
    fax: true,
    email: true,
    website: true,
    social_info: true,
    image_upload: true,
    video: true,
    terms_privacy: true
  };
  var typeAliases = {
    dropdown: 'select',
    long_text: 'textarea',
    multi_line: 'textarea',
    wp_editor: 'html',
    image: 'image_upload',
    images: 'image_upload',
    photos: 'image_upload',
    photo: 'image_upload',
    file_upload: 'file',
    postal_code: 'zip',
    postcode: 'zip',
    locations: 'location',
    categories: 'category',
    tags: 'tag',
    phone_number: 'phone',
    socialinfo: 'social_info',
    business_hours: '',
    opening_hours: '',
    hours: ''
  };
  function t(key, fallback) {
    return i18n[key] || fallback;
  }
  function sprintf(template, value) {
    return (template || '').replace('%d', value);
  }
  function getMessage(response, fallback) {
    var data = response && response.data ? response.data : response;
    if (data && data.message) {
      return data.message;
    }
    if (data && data.data && data.data.message) {
      return data.data.message;
    }
    if (response && response.responseJSON) {
      return getMessage(response.responseJSON, fallback);
    }
    return fallback;
  }
  function fieldTypeLabel(type) {
    type = type || 'text';
    if (typeLabels[type]) {
      return typeLabels[type];
    }
    return type.replace(/_/g, ' ').replace(/\b\w/g, function (letter) {
      return letter.toUpperCase();
    });
  }
  function isLockedField(field) {
    return field && (field.type === 'title' || field.type === 'description');
  }
  function isSecondaryPhoneLabel(label) {
    return /\b(phone\s*2|phone2|alternative\s+phone|alternate\s+phone|secondary\s+phone|second\s+phone|additional\s+phone|other\s+phone|backup\s+phone|alternative\s+contact(?:\s+number)?|alternate\s+contact(?:\s+number)?|secondary\s+contact(?:\s+number)?)\b/i.test(label || '');
  }
  function primaryPhoneLabel(label) {
    return isSecondaryPhoneLabel(label) ? 'Phone' : label;
  }
  function normalizeFieldType(type, label) {
    var rawType = $.trim(type || 'text').toLowerCase().replace(/[\s-]+/g, '_');
    var labelText = $.trim(label || '');
    var labelKey = labelText.toLowerCase();
    var socialPattern = /\b(social|facebook|twitter|x profile|instagram|linkedin|youtube|tiktok|pinterest|snapchat)\b/i;
    rawType = Object.prototype.hasOwnProperty.call(typeAliases, rawType) ? typeAliases[rawType] : rawType;
    if (socialPattern.test(labelText + ' ' + rawType)) {
      return 'social_info';
    }
    if (/\b(image|images|photo|photos|gallery|logo)\b/i.test(labelKey)) {
      return 'image_upload';
    }
    if (/\b(website|site url|business url|company url)\b/i.test(labelKey)) {
      return 'website';
    }
    if (/\b(email|e-mail)\b/i.test(labelKey)) {
      return 'email';
    }
    if (isSecondaryPhoneLabel(labelText + ' ' + rawType)) {
      return 'phone2';
    }
    if (/\b(phone|telephone|contact number|mobile)\b/i.test(labelKey)) {
      return 'phone';
    }
    return rawType;
  }
  function fieldsHaveType(fields, type) {
    return (fields || []).some(function (field) {
      return field && field.type === type;
    });
  }
  function countFieldsOfType(fields, type) {
    var count = 0;
    (fields || []).forEach(function (field) {
      if (!field || (0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(field) !== 'object') {
        return;
      }
      if (normalizeFieldType(field.type || '', field.label || '') === type) {
        count++;
      }
    });
    return count;
  }
  function normalizeDependentPresetFields(fields) {
    var hasPhone = fieldsHaveType(fields, 'phone');
    return (fields || []).map(function (field) {
      var nextField = $.extend({}, field);
      if (nextField.type === 'phone2' && !hasPhone) {
        nextField.type = 'phone';
        nextField.label = 'Phone';
        hasPhone = true;
        return nextField;
      }
      if (nextField.type === 'phone' && isSecondaryPhoneLabel(nextField.label)) {
        nextField.label = 'Phone';
      }
      return nextField;
    });
  }
  function findPhoneLikeFieldIndex(fields) {
    var result = null;
    (fields || []).some(function (field, index) {
      var type;
      if (!field || (0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(field) !== 'object') {
        return false;
      }
      type = normalizeFieldType(field.type || '', field.label || '');
      if (type === 'phone' || type === 'phone2' || /\b(phone|telephone|contact number|mobile)\b/i.test(field.label || '')) {
        result = index;
        return true;
      }
      return false;
    });
    return result;
  }
  function preserveRegeneratedSelectedFieldContext(newFields, existingFields, selectedFields) {
    var selectedField;
    var selectedType;
    var existingPhoneCount;
    var selectedPhoneCount;
    var hasUnselectedPhone;
    var targetType;
    var targetLabel;
    var targetIndex;
    if (!Array.isArray(selectedFields) || selectedFields.length !== 1 || !newFields.length) {
      return newFields;
    }
    selectedField = selectedFields[0];
    selectedType = normalizeFieldType(selectedField.type || '', selectedField.label || '');
    if (selectedType !== 'phone' && selectedType !== 'phone2') {
      return newFields;
    }
    existingPhoneCount = countFieldsOfType(existingFields, 'phone');
    selectedPhoneCount = countFieldsOfType(selectedFields, 'phone');
    hasUnselectedPhone = existingPhoneCount > selectedPhoneCount;
    targetType = selectedType === 'phone2' && hasUnselectedPhone ? 'phone2' : 'phone';
    targetLabel = targetType === 'phone2' ? selectedField.label || 'Alternative Phone' : primaryPhoneLabel(selectedField.label || 'Phone');
    targetIndex = findPhoneLikeFieldIndex(newFields);
    if (targetIndex === null) {
      targetIndex = 0;
    }
    newFields[targetIndex] = $.extend({}, newFields[targetIndex], {
      type: targetType,
      label: targetLabel
    });
    return normalizeDependentPresetFields(newFields);
  }
  function normalizeFields(fields) {
    var normalized = [];
    var seenLocked = {};
    if (!Array.isArray(fields)) {
      return normalized;
    }
    fields.forEach(function (field) {
      var label;
      var type;
      if (!field || (0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(field) !== 'object') {
        return;
      }
      label = $.trim(field.label || field.name || '');
      type = normalizeFieldType(field.type || 'text', label);
      if (!label || /hours/i.test(label) || /hours/i.test(type)) {
        return;
      }
      if (presetTypes[type] && seenLocked[type]) {
        return;
      }
      if (presetTypes[type]) {
        seenLocked[type] = true;
      }
      normalized.push({
        label: label,
        type: type,
        group: $.trim(field.group || field.group_name || 'General Information'),
        options: Array.isArray(field.options) ? field.options : []
      });
    });
    return normalizeDependentPresetFields(normalized);
  }
  function dedupeLockedPresetFields(fields) {
    var seenLocked = {};
    return normalizeDependentPresetFields((fields || []).filter(function (field) {
      var type = field && field.type;
      if (!presetTypes[type]) {
        return true;
      }
      if (seenLocked[type]) {
        return false;
      }
      seenLocked[type] = true;
      return true;
    }));
  }
  function normalizeSetup(setup) {
    setup = setup && (0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(setup) === 'object' ? setup : {};
    var fields = normalizeFields(setup.fields || []);
    var categories = Array.isArray(setup.categories) ? setup.categories : [];
    categories = categories.map(function (category) {
      return $.trim(category || '');
    }).filter(Boolean);
    if (!categories.length) {
      categories = ['General'];
    }
    return {
      directory_name: $.trim(setup.directory_name || setup.name || setup.type || 'Business Directory'),
      categories: categories,
      default_address: $.trim(setup.default_address || setup.default_location || setup.location || ''),
      fields: fields,
      monetization: !!setup.monetization || !!setup.payment,
      data_sharing: typeof setup.data_sharing === 'undefined' ? true : !!setup.data_sharing,
      demo_content: typeof setup.demo_content === 'undefined' ? true : !!setup.demo_content
    };
  }
  function ajax(action, data) {
    data = data || {};
    data.action = action;
    data.directorist_nonce = config.nonce || '';
    return $.ajax({
      url: config.ajaxUrl,
      method: 'POST',
      data: data
    });
  }
  function setProgress(width) {
    $('#directorist-ai-setup-progress').css('width', width + '%');
  }
  function showScreen(screen) {
    $('#directorist-ai-setup-screen-prompt, #directorist-ai-setup-loading, #directorist-ai-setup-screen-summary, #directorist-ai-setup-done').addClass('directorist-ai-setup__hidden');
    $('.directorist-ai-setup__notice').remove();
    if (screen === 'prompt') {
      $('#directorist-ai-setup-screen-prompt').removeClass('directorist-ai-setup__hidden');
      setProgress(33);
    }
    if (screen === 'loading') {
      $('#directorist-ai-setup-loading').removeClass('directorist-ai-setup__hidden');
      setProgress(50);
    }
    if (screen === 'summary') {
      $('#directorist-ai-setup-screen-summary').removeClass('directorist-ai-setup__hidden');
      setProgress(75);
    }
    if (screen === 'done') {
      $('#directorist-ai-setup-done').removeClass('directorist-ai-setup__hidden');
      setProgress(100);
    }
  }
  function showNotice(message) {
    var $screen = $('.directorist-ai-setup__screen:not(.directorist-ai-setup__hidden)');
    $('.directorist-ai-setup__notice').remove();
    if (!$screen.length) {
      $screen = $('.directorist-ai-setup__page');
    }
    $('<div />', {
      class: 'directorist-ai-setup__notice',
      role: 'alert',
      text: message
    }).prependTo($screen);
  }
  function setButtonLoading($button, loading, label) {
    if (!$button.length) {
      return;
    }
    if (loading) {
      $button.data('label', $.trim($button.text()));
      $button.prop('disabled', true).addClass('is-loading');
      $button.contents().filter(function () {
        return this.nodeType === 3;
      }).first().replaceWith(label + ' ');
      return;
    }
    $button.prop('disabled', false).removeClass('is-loading');
    $button.contents().filter(function () {
      return this.nodeType === 3;
    }).first().replaceWith(($button.data('label') || '') + ' ');
  }
  function updateGenerateState() {
    var prompt = $.trim($('#directorist-ai-setup-prompt').val() || '');
    $('#directorist-ai-setup-generate').prop('disabled', prompt.length < 5);
  }
  function updateRegenerateState() {
    var remaining = maxRegenerations - state.regenerateCount;
    var canRegenerate = state.selected.length > 0 && remaining > 0;
    $('#directorist-ai-setup-regenerate').prop('disabled', !canRegenerate);
    $('#directorist-ai-setup-regenerate-label').text(t('regenerate', 'Regenerate'));
    $('#directorist-ai-setup-regenerate-note').text(remaining > 0 ? sprintf(t('regenerateNote', 'You can regenerate fields %d more times.'), remaining) : t('regenerateDone', 'Regeneration limit reached.'));
  }
  function renderCategories() {
    var $wrap = $('#directorist-ai-setup-categories');
    $wrap.empty();
    state.setup.categories.forEach(function (category, index) {
      var $tag = $('<span />', {
        class: 'directorist-ai-setup__tag'
      });
      $('<span />', {
        text: category
      }).appendTo($tag);
      $('<button />', {
        type: 'button',
        class: 'directorist-ai-setup__tag-remove',
        'aria-label': 'Remove ' + category,
        text: 'x'
      }).data('index', index).appendTo($tag);
      $tag.appendTo($wrap);
    });
    $('<button />', {
      type: 'button',
      class: 'directorist-ai-setup__tag-add',
      text: t('addCategory', '+ Add category')
    }).appendTo($wrap);
  }
  function renderFieldsSummary() {
    var fields = state.setup.fields || [];
    var names = fields.map(function (field) {
      return field.label;
    });
    $('#directorist-ai-setup-fields-count').text(fields.length ? sprintf(t('fieldCount', '%d fields selected'), fields.length) : t('noFields', 'No fields selected yet.'));
    $('#directorist-ai-setup-fields-preview').text(names.slice(0, 8).join(', '));
  }
  function renderFields() {
    var $wrap = $('#directorist-ai-setup-fields');
    $wrap.empty();
    (state.setup.fields || []).forEach(function (field, index) {
      var selected = state.selected.indexOf(index) !== -1;
      var locked = isLockedField(field);
      var $row = $('<div />', {
        class: 'directorist-ai-setup__field-row' + (selected ? ' selected' : '')
      }).attr('data-index', index);
      $('<input />', {
        type: 'checkbox',
        class: 'directorist-ai-setup__field-select',
        checked: selected,
        disabled: locked
      }).appendTo($row);
      $('<input />', {
        type: 'text',
        class: 'directorist-ai-setup__field-name',
        value: field.label,
        'aria-label': field.label
      }).appendTo($row);
      $('<span />', {
        class: 'directorist-ai-setup__field-type',
        text: fieldTypeLabel(field.type)
      }).appendTo($row);
      $('<button />', {
        type: 'button',
        class: 'directorist-ai-setup__field-remove',
        'aria-label': 'Remove ' + field.label,
        text: 'x',
        disabled: locked
      }).appendTo($row);
      $row.appendTo($wrap);
    });
    updateRegenerateState();
  }
  function renderSummary() {
    $('#directorist-ai-setup-name').val(state.setup.directory_name);
    $('#directorist-ai-setup-location').val(state.setup.default_address);
    $('#directorist-ai-setup-money').prop('checked', !!state.setup.monetization);
    $('#directorist-ai-setup-data-sharing').prop('checked', !!state.setup.data_sharing);
    $('#directorist-ai-setup-demo-content').prop('checked', !!state.setup.demo_content);
    renderCategories();
    renderFieldsSummary();
    renderFields();
  }
  function syncSetupFromForm() {
    state.setup.directory_name = $.trim($('#directorist-ai-setup-name').val() || '');
    state.setup.default_address = $.trim($('#directorist-ai-setup-location').val() || '');
    state.setup.monetization = $('#directorist-ai-setup-money').is(':checked');
    state.setup.data_sharing = $('#directorist-ai-setup-data-sharing').is(':checked');
    state.setup.demo_content = $('#directorist-ai-setup-demo-content').is(':checked');
  }
  function extractSetupPayload(response) {
    var data = response && response.data ? response.data : response || {};
    var payload = data.setup || data.response || data;
    if (payload && payload.response) {
      payload = payload.response;
    }
    return payload;
  }
  function extractFieldsPayload(response) {
    var data = response && response.data ? response.data : response || {};
    var payload = data.fields || data.response || data;
    if (payload && payload.fields) {
      payload = payload.fields;
    }
    return payload;
  }
  function generateSetup() {
    var prompt = $.trim($('#directorist-ai-setup-prompt').val() || '');
    var $button = $('#directorist-ai-setup-generate');
    if (prompt.length < 5) {
      return;
    }
    state.prompt = prompt;
    setButtonLoading($button, true, 'Generating...');
    showScreen('loading');
    ajax(config.actions.generate, {
      prompt: prompt
    }).done(function (response) {
      if (!response || !response.success) {
        showScreen('prompt');
        showNotice(getMessage(response, t('generateError', 'Could not generate setup data. Please try again.')));
        return;
      }
      state.setup = normalizeSetup(extractSetupPayload(response));
      state.selected = [];
      state.regenerateCount = 0;
      renderSummary();
      showScreen('summary');
      if (response.data && response.data.fallback) {
        showNotice(getMessage(response, t('fallbackNotice', 'AI is taking longer than expected, so we prepared a starter setup. You can edit it before launch.')));
      }
    }).fail(function (response) {
      showScreen('prompt');
      showNotice(getMessage(response, t('generateError', 'Could not generate setup data. Please try again.')));
    }).always(function () {
      setButtonLoading($button, false);
      updateGenerateState();
    });
  }
  function regenerateFields() {
    var indexes = state.selected.slice().sort(function (a, b) {
      return a - b;
    });
    var selectedFields = indexes.map(function (index) {
      return state.setup.fields[index];
    });
    var $button = $('#directorist-ai-setup-regenerate');
    if (!indexes.length || state.regenerateCount >= maxRegenerations) {
      return;
    }
    $button.prop('disabled', true);
    $('#directorist-ai-setup-regenerate-label').text(t('regenerating', 'Regenerating...'));
    ajax(config.actions.regenerate, {
      prompt: state.prompt,
      fields: JSON.stringify(state.setup.fields),
      selected: JSON.stringify(selectedFields)
    }).done(function (response) {
      var newFields;
      var firstIndex;
      var indexMap = {};
      var nextFields = [];
      if (!response || !response.success) {
        showNotice(getMessage(response, t('generateError', 'Could not generate setup data. Please try again.')));
        return;
      }
      newFields = normalizeFields(extractFieldsPayload(response));
      newFields = preserveRegeneratedSelectedFieldContext(newFields, state.setup.fields, selectedFields);
      if (!newFields.length) {
        showNotice(t('generateError', 'Could not generate setup data. Please try again.'));
        return;
      }
      firstIndex = indexes[0];
      indexes.forEach(function (index) {
        indexMap[index] = true;
      });
      state.setup.fields.forEach(function (field, index) {
        if (index === firstIndex) {
          nextFields = nextFields.concat(newFields);
        }
        if (!indexMap[index]) {
          nextFields.push(field);
        }
      });
      state.setup.fields = dedupeLockedPresetFields(nextFields);
      state.selected = [];
      state.regenerateCount++;
      renderFieldsSummary();
      renderFields();
    }).fail(function (response) {
      showNotice(getMessage(response, t('generateError', 'Could not generate setup data. Please try again.')));
    }).always(function () {
      updateRegenerateState();
    });
  }
  function launchDirectory() {
    var $button = $('#directorist-ai-setup-launch');
    syncSetupFromForm();
    if (!state.setup.directory_name) {
      showNotice('Please add a directory name.');
      return;
    }
    setButtonLoading($button, true, t('launching', 'Launching...'));
    ajax(config.actions.launch, {
      setup: JSON.stringify(state.setup)
    }).done(function (response) {
      var url;
      if (!response || !response.success) {
        showNotice(getMessage(response, t('launchError', 'Could not launch your directory. Please try again.')));
        return;
      }
      url = response.data && response.data.url ? response.data.url : config.dashboard;
      showScreen('done');
      window.setTimeout(function () {
        window.location.href = url;
      }, 900);
    }).fail(function (response) {
      showNotice(getMessage(response, t('launchError', 'Could not launch your directory. Please try again.')));
    }).always(function () {
      setButtonLoading($button, false);
      $button.contents().filter(function () {
        return this.nodeType === 3;
      }).first().replaceWith(t('launch', 'Launch my directory') + ' ');
    });
  }
  function exitSetup() {
    if (window.confirm(t('exitConfirm', 'Exit setup and go to the dashboard? Your progress will not be saved.'))) {
      window.location.href = config.dashboard || window.ajaxurl || '/wp-admin/';
    }
  }
  $(function () {
    var $prompt = $('#directorist-ai-setup-prompt');
    updateGenerateState();
    showScreen('prompt');
    $prompt.on('input', updateGenerateState);
    $('#directorist-ai-setup-generate').on('click', generateSetup);
    $('#directorist-ai-setup-back').on('click', function () {
      showScreen('prompt');
    });
    $('#directorist-ai-setup-close, #directorist-ai-setup-exit').on('click', exitSetup);
    $('#directorist-ai-setup-chips').on('click', '.directorist-ai-setup__chip', function () {
      var preset = $(this).data('preset');
      $prompt.val(presets[preset] || $(this).text()).trigger('input').focus();
    });
    $('#directorist-ai-setup-categories').on('click', '.directorist-ai-setup__tag-remove', function () {
      var index = $(this).data('index');
      state.setup.categories.splice(index, 1);
      renderCategories();
    }).on('click', '.directorist-ai-setup__tag-add', function () {
      var category = window.prompt(t('addCategoryPrompt', 'Category name'), '');
      category = $.trim(category || '');
      if (category) {
        state.setup.categories.push(category);
        renderCategories();
      }
    });
    $('#directorist-ai-setup-fields-edit').on('click', function () {
      $('#directorist-ai-setup-fields-editor').addClass('open');
    });
    $('#directorist-ai-setup-fields-done').on('click', function () {
      $('#directorist-ai-setup-fields-editor').removeClass('open');
      renderFieldsSummary();
    });
    $('#directorist-ai-setup-regenerate').on('click', regenerateFields);
    $('#directorist-ai-setup-launch').on('click', launchDirectory);
    $('#directorist-ai-setup-name, #directorist-ai-setup-location').on('input', syncSetupFromForm);
    $('#directorist-ai-setup-money, #directorist-ai-setup-data-sharing, #directorist-ai-setup-demo-content').on('change', syncSetupFromForm);
    $('#directorist-ai-setup-fields').on('change', '.directorist-ai-setup__field-select', function () {
      var $row = $(this).closest('.directorist-ai-setup__field-row');
      var index = parseInt($row.attr('data-index'), 10);
      var selectedIndex = state.selected.indexOf(index);
      if ($(this).is(':checked') && selectedIndex === -1) {
        state.selected.push(index);
      }
      if (!$(this).is(':checked') && selectedIndex !== -1) {
        state.selected.splice(selectedIndex, 1);
      }
      $row.toggleClass('selected', $(this).is(':checked'));
      updateRegenerateState();
    }).on('input', '.directorist-ai-setup__field-name', function () {
      var index = parseInt($(this).closest('.directorist-ai-setup__field-row').attr('data-index'), 10);
      if (state.setup && state.setup.fields[index]) {
        state.setup.fields[index].label = $.trim($(this).val() || '');
        renderFieldsSummary();
      }
    }).on('click', '.directorist-ai-setup__field-remove', function () {
      var index;
      if ($(this).prop('disabled')) {
        return;
      }
      index = parseInt($(this).closest('.directorist-ai-setup__field-row').attr('data-index'), 10);
      state.setup.fields.splice(index, 1);
      state.selected = [];
      renderFieldsSummary();
      renderFields();
    });
  });
})(jQuery);
}();
/******/ })()
;
//# sourceMappingURL=ai-setup-wizard.js.map