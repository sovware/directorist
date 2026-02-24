/**
 * Helper utilities for conditional logic
 */
import { SELECTORS } from './field-mapping.js';

/**
 * Extract labels from Select2 selection container
 * @param {jQuery} $container - Select2 container element
 * @param {jQuery} $ - jQuery instance
 * @returns {string[]} Array of labels
 */
export function getLabelsFromSelect2Container($container, $) {
	if (!$container || !$container.length || !$) {
		return [];
	}
	const labels = [];
	$container.find('.select2-selection__choice').each(function () {
		const $choice = $(this);
		const label =
			$choice.find('.select2-selection__choice__display').text().trim() ||
			$choice.text().trim().replace('×', '').trim();
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
export function parseLabelsString(labelsStr) {
	if (!labelsStr || !labelsStr.trim()) {
		return [];
	}
	return labelsStr
		.split(',')
		.map((label) => label.trim())
		.filter((label) => label.length > 0);
}

/**
 * Parse comma-separated IDs string
 * Accepts both numeric IDs and string slugs (e.g. taxonomy slugs)
 * @param {string} idsStr - Comma-separated IDs
 * @returns {string[]} Array of trimmed, non-empty ID strings
 */
export function parseIdsString(idsStr) {
	if (!idsStr || !idsStr.trim()) {
		return [];
	}
	return idsStr
		.split(',')
		.map((id) => id.trim())
		.filter((id) => id.length > 0);
}

/**
 * Sync data-selected-label and data-selected-id from Select2/DOM to element.
 * Handles Select2 API, DOM fallback, and admin checklist checkboxes.
 * @param {jQuery} $field - The select/field element
 * @param {jQuery} $ - jQuery instance
 * @returns {void}
 */
export function syncSelect2DataAttributes($field, $) {
	if (!$field || !$field.length || !$) return;

	const labels = [];
	const ids = [];
	const isChecklist =
		$field.is(SELECTORS.CATEGORY_CHECKLIST) ||
		$field.closest(SELECTORS.CATEGORY_CHECKLIST).length > 0 ||
		$field.is(SELECTORS.LOCATION_CHECKLIST) ||
		$field.closest(SELECTORS.LOCATION_CHECKLIST).length > 0;

	if (
		!isChecklist &&
		$field.hasClass('select2-hidden-accessible') &&
		typeof $field.select2 === 'function'
	) {
		try {
			const selectedData = $field.select2('data');
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
			const $cb = $(this);
			ids.push(String($cb.val()));
			const labelText = $cb.closest('label').text().trim();
			if (labelText) labels.push(labelText);
		});
	}

	if (labels.length === 0) {
		const $container = $field.next('.select2-container');
		if ($container.length) {
			getLabelsFromSelect2Container($container, $).forEach((l) =>
				labels.push(l)
			);
		}
	}

	const val = $field.val();
	if (val && ids.length === 0) {
		const values = Array.isArray(val) ? val : [val];
		values.forEach((id) => {
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
export function normalizeOperator(op, defaultOp = 'OR') {
	if (op === null || op === undefined || op === '') {
		return defaultOp;
	}
	const normalized = String(op).trim().toUpperCase();
	return normalized || defaultOp;
}
