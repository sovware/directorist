/**
 * Init, apply, and event binding for conditional logic.
 * Orchestrates evaluation and delegates to event-handlers for change detection.
 */
import { fieldDependsOnChange } from './depends-on-field.js';
import { normalizeConditionFieldKey, SELECTORS } from './field-mapping.js';
import { syncSelect2DataAttributes } from './helpers.js';
import { setupTaxonomyHandlers } from './event-handlers/taxonomy-handlers.js';
import { setupFileUploadHandlers } from './event-handlers/file-upload-handlers.js';
import { setupFormHandlers } from './event-handlers/form-handlers.js';
import { setupTinyMCEHandlers } from './event-handlers/tinymce-handlers.js';

/**
 * Apply show/hide and disabled state based on evaluated conditional logic.
 * @param {jQuery} $fieldWrapper - Wrapper with data-conditional-logic
 * @param {Function} evaluateConditionalLogicFn - (conditionalLogic) => boolean
 * @param {jQuery} $
 */
export function applyConditionalLogic(
	$fieldWrapper,
	evaluateConditionalLogicFn,
	$
) {
	const conditionalLogicData = $fieldWrapper.attr('data-conditional-logic');
	if (!conditionalLogicData) {
		return;
	}

	try {
		let decodedData = conditionalLogicData;
		// Decode HTML entities (e.g. &quot;) before JSON.parse
		if (typeof decodedData === 'string') {
			const textarea = document.createElement('textarea');
			textarea.innerHTML = decodedData;
			decodedData = textarea.value;
		}
		const conditionalLogic = JSON.parse(decodedData);
		const shouldShow = evaluateConditionalLogicFn(conditionalLogic);

		if (shouldShow) {
			$fieldWrapper.show();
			$fieldWrapper
				.find('input, select, textarea')
				.prop('disabled', false);
			const $modalInput = $fieldWrapper.closest(
				'.directorist-search-modal__input'
			);
			if ($modalInput.length) $modalInput.show();
			const $advancedElement = $fieldWrapper.closest(
				'.directorist-advanced-filter__advanced__element'
			);
			if ($advancedElement.length) $advancedElement.show();
			setTinyMCEMode($fieldWrapper, 'design');
		} else {
			$fieldWrapper.hide();
			$fieldWrapper
				.find('input, select, textarea')
				.prop('disabled', true);
			const $modalInput = $fieldWrapper.closest(
				'.directorist-search-modal__input'
			);
			if ($modalInput.length) $modalInput.hide();
			const $advancedElement = $fieldWrapper.closest(
				'.directorist-advanced-filter__advanced__element'
			);
			if ($advancedElement.length) $advancedElement.hide();
			setTinyMCEMode($fieldWrapper, 'readonly');
		}
	} catch (e) {
		console.error('Error parsing conditional logic:', e, {
			conditionalLogicData,
		});
	}
}

/** Set TinyMCE design/readonly mode when field visibility changes. */
function setTinyMCEMode($fieldWrapper, mode) {
	if (
		!$fieldWrapper.find('textarea').length ||
		typeof tinymce === 'undefined'
	)
		return;
	try {
		const editorId = $fieldWrapper.find('textarea').attr('id');
		if (editorId) {
			const editor = tinymce.get(editorId);
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
export function initConditionalLogic(
	getWrapperFn,
	getFieldValueFn,
	applyConditionalLogicFn,
	$,
	adminTargets = []
) {
	const $categoryField = $(SELECTORS.CATEGORY);
	if (
		$categoryField.length &&
		$categoryField.hasClass('select2-hidden-accessible') &&
		$categoryField.is('select') &&
		typeof $categoryField.select2 === 'function'
	) {
		try {
			const selectedData = $categoryField.select2('data');
			if (selectedData && selectedData.length > 0) {
				$categoryField.attr(
					'data-selected-label',
					selectedData
						.map((item) => item.text || '')
						.filter((item) => item.length > 0)
						.join(',')
				);
			}
		} catch (e) {}
	}

	const $formWrapper = $(getWrapperFn());
	let $fieldsWithConditionalLogic = $formWrapper.find(
		'.directorist-form-group[data-conditional-logic]'
	);
	if ($fieldsWithConditionalLogic.length === 0) {
		$fieldsWithConditionalLogic = $(
			'.directorist-form-group[data-conditional-logic]'
		);
	}

	$fieldsWithConditionalLogic.each(function () {
		applyConditionalLogicFn($(this));
	});

	if (
		adminTargets &&
		Array.isArray(adminTargets) &&
		adminTargets.length > 0
	) {
		adminTargets.forEach(function (target) {
			const $el = $(target.selector);
			if ($el.length && target.conditionalLogic) {
				$el.addClass('directorist-conditional-logic-target');
				$el.attr(
					'data-conditional-logic',
					typeof target.conditionalLogic === 'string'
						? target.conditionalLogic
						: JSON.stringify(target.conditionalLogic)
				);
				if (target.fieldKey)
					$el.attr('data-field-key', target.fieldKey);
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
export function watchFieldChanges(
	getWrapperFn,
	getFieldValueFn,
	applyConditionalLogicFn,
	$
) {
	function triggerConditionalLogicEvaluation(
		fieldName,
		fieldKey,
		$changedField
	) {
		const $fieldsWithLogic = $(
			'.directorist-form-group[data-conditional-logic], .directorist-conditional-logic-target[data-conditional-logic]'
		);

		$fieldsWithLogic.each(function () {
			const $fieldWrapper = $(this);
			const conditionalLogicData = $fieldWrapper.attr(
				'data-conditional-logic'
			);
			if (!conditionalLogicData) return;

			try {
				let decodedData = conditionalLogicData;
				if (typeof decodedData === 'string') {
					const textarea = document.createElement('textarea');
					textarea.innerHTML = decodedData;
					decodedData = textarea.value;
				}
				const conditionalLogic = JSON.parse(decodedData);
				const dependsOnField = fieldDependsOnChange(
					conditionalLogic,
					fieldKey,
					fieldName,
					$changedField,
					normalizeConditionFieldKey
				);
				if (dependsOnField) {
					applyConditionalLogicFn($fieldWrapper);
				}
			} catch (e) {
				console.error('Error in conditional logic evaluation:', e);
			}
		});
	}

	setupTaxonomyHandlers($, triggerConditionalLogicEvaluation);
	setupFormHandlers(getWrapperFn, $, triggerConditionalLogicEvaluation);
	setupFileUploadHandlers($, triggerConditionalLogicEvaluation);
	setupTinyMCEHandlers($, triggerConditionalLogicEvaluation);
}

/**
 * Update category Select2 data-selected-label and re-run init.
 * Called after category field is changed externally (e.g. AJAX).
 * @param {Function} initConditionalLogicFn
 * @param {jQuery} $
 */
export function updateCategoryFieldLabel(initConditionalLogicFn, $) {
	const $field = $(SELECTORS.CATEGORY);
	if (!$field.length) return;

	setTimeout(function () {
		if ($field.is('select')) {
			syncSelect2DataAttributes($field, $);
		}
		initConditionalLogicFn();
	}, 150);
}
