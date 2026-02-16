/**
 * Field mapping: escape IDs, map field keys to selectors, normalize field keys
 */

/**
 * Escape a string for use in CSS ID/class selectors.
 * Characters like [ ] in field keys (e.g. admin_category_select[]) break jQuery selectors.
 */
export function escapeCssId(str) {
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
 * Map widget_key/field_key to actual frontend field selector
 */
export function mapFieldKeyToSelector(fieldKey) {
	const fieldKeyMap = {
		category:
			"#at_biz_dir-categories, select[name='in_cat'], .directorist-search-category select",
		categories:
			"#at_biz_dir-categories, select[name='in_cat'], .directorist-search-category select",
		'admin_category_select[]':
			"#at_biz_dir-categories, select[name='in_cat'], .directorist-search-category select",
		in_cat: "select[name='in_cat'], .directorist-search-category select",
		description:
			'[name="listing_content"], #listing_content, [name="description"], #description, #content, [name="content"]',
		listing_content:
			'[name="listing_content"], #listing_content, [name="description"], #description, #content, [name="content"]',
		title: '.directorist-search-query input, .directorist-search-form-wrap input[name="q"], .directorist-search-form input[name="q"], input[name="q"], [name="listing_title"], #listing_title, [name="title"], #title, [name="post_title"]',
		listing_title:
			'.directorist-search-query input, .directorist-search-form-wrap input[name="q"], .directorist-search-form input[name="q"], input[name="q"], [name="listing_title"], #listing_title, [name="title"], #title, [name="post_title"]',
		q: '.directorist-search-query input, input[name="q"]',
		location:
			'[name="location"], #at_biz_dir-location, select[name="in_loc"], .directorist-search-location select',
		in_loc: 'select[name="in_loc"], .directorist-search-location select',
		address: '[name="address"], #address',
		phone: '[name="phone"], #phone',
		email: '[name="email"], #email',
		website: '[name="website"], #website',
		tag: '[name="tag"], #at_biz_dir-tags, [name="in_tag[]"]',
		'in_tag[]': '[name="in_tag[]"]',
		'tax_input[at_biz_dir-tags][]': "#at_biz_dir-tags, [name='in_tag[]']",
		'tax_input[at_biz_dir-location][]':
			"#at_biz_dir-location, select[name='in_loc']",
		zip: '[name="zip"], #zip',
		miles: '[name="miles"], .directorist-custom-range-slider__range',
		search_by_rating: '[name="search_by_rating[]"]',
		image_upload:
			'[name="listing_img[]"], .directorist-form-image_upload-field',
	};

	if (fieldKeyMap[fieldKey]) {
		return fieldKeyMap[fieldKey];
	}

	// Search form custom fields: name="custom_field[field_key]"
	if (
		fieldKey &&
		(fieldKey.startsWith('custom-') ||
			['select', 'radio', 'checkbox'].some(
				(t) => fieldKey === t || fieldKey.startsWith(t + '_')
			))
	) {
		const fk = fieldKey.startsWith('custom-')
			? fieldKey
			: `custom-${fieldKey.replace(/_/g, '-')}`;
		return [
			`select[name="custom_field[${fk}]"]`,
			`input[name="custom_field[${fk}]"]`,
			`input[name="custom_field[${fk}][]"]`,
			`.directorist-advanced-filter__advanced__element.directorist-search-field-select select[name="custom_field[${fk}]"]`,
			`.directorist-advanced-filter__advanced__element.directorist-search-field-radio input[name="custom_field[${fk}]"]`,
			`.directorist-advanced-filter__advanced__element.directorist-search-field-checkbox input[name="custom_field[${fk}][]"]`,
			`.directorist-search-field select[name="custom_field[${fk}]"]`,
			`.directorist-search-field input[name="custom_field[${fk}]"]`,
			`.directorist-search-field input[name="custom_field[${fk}][]"]`,
		].join(', ');
	}

	return null;
}

/**
 * Normalize condition.field to match actual field name in DOM.
 * Search form custom fields use name="custom_field[custom-select]" etc.
 * Conditions may be stored as "select", "select_2" from builder.
 */
export function normalizeConditionFieldKey(fieldKey) {
	if (!fieldKey || typeof fieldKey !== 'string') {
		return fieldKey;
	}
	const key = String(fieldKey).trim();
	if (key.startsWith('custom-')) {
		return key;
	}
	const m = key.match(/^(select|radio|checkbox)(?:_(\d+))?$/i);
	if (m) {
		const suffix = m[2] ? '-' + m[2] : '';
		return 'custom-' + m[1].toLowerCase() + suffix;
	}
	return key;
}
