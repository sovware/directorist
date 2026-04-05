/**
 * Form change handlers: input/select/textarea, color picker, clear button
 */
import {
	extractFieldKeyFromChange,
	extractFieldKeyFromName,
	extractFieldFromClearButton,
	TAXONOMY_SELECTOR_SEARCH_FORM_FIELD,
	TAXONOMY_SELECTOR_SEARCH_FORM_ADDRESS,
} from '../field-mapping.js';
import { syncSelect2DataAttributes } from '../helpers.js';

/**
 * @param {Function} getWrapperFn
 * @param {jQuery} $
 * @param {Function} triggerFn - (fieldName, fieldKey, $changedField) => void
 */
export function setupFormHandlers(getWrapperFn, $, triggerFn) {
	// Delegate from document so handlers work after AJAX form replace (e.g. search form tab change)
	const wrapper = getWrapperFn();
	const delegatedSelector = wrapper
		.split(',')
		.map((s) => s.trim())
		.flatMap((s) => [
			s + ' input',
			s + ' select',
			s + ' textarea',
			s + ' .select2-hidden-accessible',
		])
		.join(', ');
	$(document).on(
		'change input select2:select select2:unselect',
		delegatedSelector,
		function () {
			const $changedField = $(this);
			const fieldName =
				$changedField.attr('name') || $changedField.attr('id');
			if (!fieldName) {
				console.warn(
					'Field change detected but no name/id found:',
					$changedField
				);
				return;
			}

			const { fieldKey, taxonomyFieldSelector } =
				extractFieldKeyFromChange(fieldName, $changedField);

			if (taxonomyFieldSelector) {
				// Address input: no Select2, just re-evaluate after DOM settles
				if (
					taxonomyFieldSelector ===
					TAXONOMY_SELECTOR_SEARCH_FORM_ADDRESS
				) {
					setTimeout(function () {
						triggerFn(fieldName, fieldKey, $changedField);
					}, 50);
					return;
				}
				const $fieldToUpdate =
					taxonomyFieldSelector ===
					TAXONOMY_SELECTOR_SEARCH_FORM_FIELD
						? $changedField
						: $(taxonomyFieldSelector);
				// Let Select2 update data-selected-* before re-evaluating
				setTimeout(function () {
					if ($fieldToUpdate.length) {
						syncSelect2DataAttributes($fieldToUpdate, $);
					}
					triggerFn(fieldName, fieldKey, $changedField);
				}, 50);
				return;
			}

			triggerFn(fieldName, fieldKey, $changedField);
		}
	);

	// Clear button
	$(document).on(
		'click',
		'.directorist-search-field__btn--clear',
		function () {
			const $fieldWrap = $(this).closest('.directorist-search-field');
			const { fieldKey, fieldName, $changedField } =
				extractFieldFromClearButton($fieldWrap);
			if (fieldKey) {
				setTimeout(function () {
					triggerFn(fieldName, fieldKey, $changedField);
				}, 50);
			}
		}
	);

	// Document-level fallback for custom fields
	$(document).on(
		'change',
		'.directorist-select select, .directorist-custom-field-select select, select.directorist-form-element, .directorist-custom-field-radio input[type="radio"], .directorist-custom-field-checkbox input[type="checkbox"]',
		function () {
			const $changedField = $(this);
			const fieldName =
				$changedField.attr('name') || $changedField.attr('id');
			if (!fieldName) return;

			const fieldKey = extractFieldKeyFromName(fieldName);
			triggerFn(fieldName, fieldKey, $changedField);
		}
	);

	// Color picker: value updates async, delay before re-evaluating
	function handleColorPickerChange(field) {
		const $changedField = $(field);
		const fieldName =
			$changedField.attr('name') || $changedField.attr('id');
		if (!fieldName) return;

		const fieldKey = extractFieldKeyFromName(fieldName);
		setTimeout(function () {
			triggerFn(fieldName, fieldKey, $changedField);
		}, 50);
	}

	$(document).on(
		'change',
		'.directorist-color-picker, .wp-color-picker, input.wp-color-picker',
		function () {
			handleColorPickerChange(this);
		}
	);
	$(document).on(
		'irischange',
		'.directorist-color-picker, .wp-color-picker, input.wp-color-picker',
		function () {
			handleColorPickerChange(this);
		}
	);

	document.addEventListener(
		'click',
		function (e) {
			if (
				e.target &&
				(e.target.classList.contains('wp-picker-clear') ||
					(e.target.tagName === 'INPUT' &&
						e.target.type === 'button' &&
						e.target.className.includes('wp-picker-clear')))
			) {
				const $clearButton = $(e.target);
				const $colorPickerInput = $clearButton
					.closest('.wp-picker-container')
					.find(
						'.directorist-color-picker, .wp-color-picker, input.wp-color-picker'
					);
				handleColorPickerChange($colorPickerInput);
			}
		},
		true
	);
}
