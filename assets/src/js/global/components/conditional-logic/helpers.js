/**
 * Helper utilities for conditional logic
 */

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
			$choice
				.find('.select2-selection__choice__display')
				.text()
				.trim() ||
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
 * @param {string} idsStr - Comma-separated IDs
 * @returns {string[]} Array of trimmed, valid ID strings
 */
export function parseIdsString(idsStr) {
	if (!idsStr || !idsStr.trim()) {
		return [];
	}
	return idsStr
		.split(',')
		.map((id) => id.trim())
		.filter((id) => id.length > 0 && !isNaN(id));
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
