/**
 * Field mapping: escape IDs, map field keys to selectors, normalize field keys
 */

/** @readonly Maps widget/short keys to canonical field keys (e.g. title → listing_title) */
export const WIDGET_KEY_TO_FIELD_KEY = {
	title: 'listing_title',
	description: 'listing_content',
	content: 'listing_content',
};

/** @readonly Taxonomy field keys and names used for condition matching */
export const TAXONOMY_FIELD_KEYS = [
	'category',
	'categories',
	'admin_category_select[]',
	'tax_input[at_biz_dir-category][]',
	'in_cat',
	'tag',
	'tags',
	'in_tag[]',
	'location',
	'locations',
	'tax_input[at_biz_dir-location][]',
	'in_loc',
	'tax_input[at_biz_dir-tags][]',
];

/** @readonly Selectors for taxonomy and special fields */
export const SELECTORS = {
	CATEGORY: '#at_biz_dir-categories',
	TAGS: '#at_biz_dir-tags',
	LOCATION: '#at_biz_dir-location',
	IN_CAT: "select[name='in_cat']",
	IN_LOC: "select[name='in_loc']",
	IN_TAG: "input[name='in_tag[]']",
	CATEGORY_CHECKLIST:
		'#at_biz_dir-categorychecklist, #at_biz_dir-categorychecklist-pop',
	LOCATION_CHECKLIST:
		'#at_biz_dir-locationchecklist, #at_biz_dir-locationchecklist-pop',
	TAGS_CHECKLIST:
		'#at_biz_dir-tagschecklist, #at_biz_dir-tagschecklist-pop, #tagsdiv-at_biz_dir-tags',
	CATEGORY_CHECKLIST_ID: '#at_biz_dir-categorychecklist',
	LOCATION_CHECKLIST_ID: '#at_biz_dir-locationchecklist',
	TAGS_CHECKLIST_ID: '#at_biz_dir-tagschecklist',
	TAG_METABOX: '#tagsdiv-at_biz_dir-tags',
	/** For get-field-value: category/tag/location select or multi-select */
	CATEGORY_SELECT: "#at_biz_dir-categories, select[name='in_cat']",
	TAGS_SELECT: "#at_biz_dir-tags, input[name='in_tag[]']",
	LOCATION_SELECT: "#at_biz_dir-location, select[name='in_loc']",
	CATEGORY_CHECKLIST_CHECKED:
		'#at_biz_dir-categorychecklist input:checked, #at_biz_dir-categorychecklist-pop input:checked',
	TAGS_CHECKLIST_CHECKED:
		'#at_biz_dir-tagschecklist input:checked, #at_biz_dir-tagschecklist-pop input:checked, input[name="tax_input[at_biz_dir-tags][]"]:checked',
	LOCATION_CHECKLIST_CHECKED:
		'#at_biz_dir-locationchecklist input:checked, #at_biz_dir-locationchecklist-pop input:checked, input[name="tax_input[at_biz_dir-location][]"]:checked',
	SEARCH_ADDRESS: ".directorist-search-location input[name='address']",
	TAG_CHECKLIST_ITEMS:
		'#tagsdiv-at_biz_dir-tags .tagchecklist li, #at_biz_dir-tags .tagchecklist li',
	TAG_TEXTAREA:
		'#tagsdiv-at_biz_dir-tags .the-tags, #at_biz_dir-tags .the-tags',
};

/** Sentinel values for extractFieldKeyFromChange: taxonomy field is in search form (use $changedField) */
export const TAXONOMY_SELECTOR_SEARCH_FORM_FIELD = 'search_form_field';
/** Sentinel: taxonomy is search form address input (no Select2 sync, just re-evaluate) */
export const TAXONOMY_SELECTOR_SEARCH_FORM_ADDRESS = 'search_form_address';

/**
 * Check if element is a taxonomy Select2/select field (category, tag, location).
 * @param {jQuery} $field
 * @returns {boolean}
 */
export function isTaxonomySelectField($field) {
	return (
		$field &&
		$field.length &&
		($field.is(SELECTORS.CATEGORY) ||
			$field.is(SELECTORS.TAGS) ||
			$field.is(SELECTORS.LOCATION) ||
			$field.is(SELECTORS.IN_CAT) ||
			$field.is(SELECTORS.IN_LOC))
	);
}

/**
 * Check if fieldKey/fieldName/DOM element refers to a taxonomy field.
 * Used by depends-on-field to determine if a change affects taxonomy conditions.
 * @param {string} fieldKey
 * @param {string} fieldName
 * @param {jQuery} $changedField
 * @returns {boolean}
 */
export function isTaxonomyFieldKeyOrElement(
	fieldKey,
	fieldName,
	$changedField
) {
	if (
		TAXONOMY_FIELD_KEYS.includes(fieldKey) ||
		TAXONOMY_FIELD_KEYS.includes(fieldName)
	) {
		return true;
	}
	const hasField =
		$changedField &&
		typeof $changedField.length !== 'undefined' &&
		$changedField.length > 0;
	if (!hasField) return false;
	return (
		$changedField.is(SELECTORS.CATEGORY) ||
		$changedField.is(SELECTORS.TAGS) ||
		$changedField.is(SELECTORS.LOCATION) ||
		$changedField.is(SELECTORS.IN_LOC) ||
		$changedField.is(SELECTORS.IN_CAT) ||
		$changedField.is(SELECTORS.IN_TAG) ||
		$changedField.closest(SELECTORS.CATEGORY_CHECKLIST).length > 0 ||
		$changedField.closest(SELECTORS.LOCATION_CHECKLIST).length > 0 ||
		$changedField.closest(SELECTORS.TAGS_CHECKLIST).length > 0
	);
}

/**
 * Escape a string for use in CSS ID/class selectors.
 * Characters like [ ] in field keys (e.g. admin_category_select[]) break jQuery selectors.
 * @param {string} str - Raw string
 * @returns {string} Escaped string
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
 * Map widget_key/field_key to actual frontend field selector.
 * @param {string} fieldKey - Field key (e.g. 'category', 'custom-select')
 * @returns {string|null} jQuery selector string or null
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
		review: '[name="search_by_rating[]"]',
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
 * Extract field key from name attribute (handles array notation).
 * @param {string} fieldName - Raw name (e.g. 'custom_field[custom-checkbox][]')
 * @returns {string} Base field key (e.g. 'custom_field[custom-checkbox]')
 */
export function extractFieldKeyFromName(fieldName) {
	if (!fieldName || typeof fieldName !== 'string') return '';
	let key = fieldName;
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
export function extractFieldKeyFromChange(
	fieldName,
	$changedField,
	selectors = SELECTORS
) {
	let fieldKey = extractFieldKeyFromName(fieldName);
	let taxonomyFieldSelector = null;

	if (fieldName === 'post_title' || $changedField.attr('id') === 'title') {
		return {
			fieldKey: 'listing_title',
			fieldName,
			taxonomyFieldSelector: null,
		};
	}
	if (
		fieldName === 'q' ||
		($changedField.attr('name') === 'q' &&
			$changedField.closest(
				'.directorist-search-query, .directorist-search-form-wrap, .directorist-search-form'
			).length)
	) {
		return { fieldKey: 'title', fieldName, taxonomyFieldSelector: null };
	}
	if (fieldName === 'content' || $changedField.attr('id') === 'content') {
		return {
			fieldKey: 'listing_content',
			fieldName,
			taxonomyFieldSelector: null,
		};
	}
	if (
		fieldName === 'admin_category_select[]' ||
		$changedField.is(selectors.CATEGORY)
	) {
		return {
			fieldKey: 'category',
			fieldName,
			taxonomyFieldSelector: selectors.CATEGORY,
		};
	}
	if (
		fieldName === 'tax_input[at_biz_dir-category][]' ||
		$changedField.closest(selectors.CATEGORY_CHECKLIST).length
	) {
		return {
			fieldKey: 'admin_category_select[]',
			fieldName,
			taxonomyFieldSelector: selectors.CATEGORY_CHECKLIST_ID,
		};
	}
	if (
		fieldName === 'tax_input[at_biz_dir-location][]' ||
		$changedField.closest(selectors.LOCATION_CHECKLIST).length
	) {
		return {
			fieldKey: 'tax_input[at_biz_dir-location][]',
			fieldName,
			taxonomyFieldSelector: selectors.LOCATION_CHECKLIST_ID,
		};
	}
	if (
		fieldName === 'tax_input[at_biz_dir-tags][]' ||
		$changedField.closest(selectors.TAGS_CHECKLIST).length
	) {
		return {
			fieldKey: 'tax_input[at_biz_dir-tags][]',
			fieldName,
			taxonomyFieldSelector: selectors.TAGS_CHECKLIST_ID,
		};
	}
	if ($changedField.is(selectors.TAGS)) {
		return {
			fieldKey: 'tag',
			fieldName,
			taxonomyFieldSelector: selectors.TAGS,
		};
	}
	if ($changedField.is(selectors.LOCATION)) {
		return {
			fieldKey: 'location',
			fieldName,
			taxonomyFieldSelector: selectors.LOCATION,
		};
	}
	if (fieldName === 'in_loc' || $changedField.is(selectors.IN_LOC)) {
		return {
			fieldKey: 'location',
			fieldName,
			taxonomyFieldSelector: TAXONOMY_SELECTOR_SEARCH_FORM_FIELD,
		};
	}
	if (
		(fieldName === 'address' ||
			$changedField.is("input[name='address']")) &&
		$changedField.closest('.directorist-search-location').length
	) {
		return {
			fieldKey: 'location',
			fieldName,
			taxonomyFieldSelector: TAXONOMY_SELECTOR_SEARCH_FORM_ADDRESS,
		};
	}
	if (fieldName === 'in_cat' || $changedField.is(selectors.IN_CAT)) {
		return {
			fieldKey: 'category',
			fieldName,
			taxonomyFieldSelector: TAXONOMY_SELECTOR_SEARCH_FORM_FIELD,
		};
	}
	if (fieldName === 'in_tag[]' || $changedField.is(selectors.IN_TAG)) {
		return {
			fieldKey: 'tag',
			fieldName,
			taxonomyFieldSelector: TAXONOMY_SELECTOR_SEARCH_FORM_FIELD,
		};
	}

	return { fieldKey, fieldName, taxonomyFieldSelector: null };
}

/**
 * Extract field key/name from clear button's parent search field wrapper.
 * @param {jQuery} $fieldWrap - .directorist-search-field wrapper
 * @returns {{ fieldKey: string|null, fieldName: string|null, $changedField: jQuery|null }}
 */
export function extractFieldFromClearButton($fieldWrap) {
	if (!$fieldWrap || !$fieldWrap.length)
		return { fieldKey: null, fieldName: null, $changedField: null };

	const checks = [
		{
			sel: 'input[name="in_tag[]"]',
			fieldKey: 'tag',
			fieldName: 'in_tag[]',
		},
		{
			sel: "select[name='in_cat']",
			fieldKey: 'category',
			fieldName: 'in_cat',
		},
		{
			sel: "select[name='in_loc']",
			fieldKey: 'location',
			fieldName: 'in_loc',
		},
		{
			sel: 'input[name="search_by_rating[]"]',
			fieldKey: 'search_by_rating',
			fieldName: 'search_by_rating[]',
		},
		{
			sel: 'input[name="email"]',
			fieldKey: 'email',
			fieldName: 'email',
		},
		{
			sel: 'input[name="phone"]',
			fieldKey: 'phone',
			fieldName: 'phone',
		},
		{
			sel: 'input[name="phone2"]',
			fieldKey: 'phone2',
			fieldName: 'phone2',
		},
		{
			sel: 'input[name="fax"]',
			fieldKey: 'fax',
			fieldName: 'fax',
		},
		{
			sel: 'input[name="website"]',
			fieldKey: 'website',
			fieldName: 'website',
		},
		{
			sel: 'input[name="zip"]',
			fieldKey: 'zip',
			fieldName: 'zip',
		},
	];
	for (const c of checks) {
		const $el = $fieldWrap.find(c.sel).first();
		if ($el.length)
			return {
				fieldKey: c.fieldKey,
				fieldName: c.fieldName,
				$changedField: $el,
			};
	}

	if (
		$fieldWrap.find('input[name="address"]').length &&
		$fieldWrap.hasClass('directorist-search-location')
	) {
		return {
			fieldKey: 'location',
			fieldName: 'address',
			$changedField: $fieldWrap.find('input[name="address"]').first(),
		};
	}
	if (
		$fieldWrap.find('input[name="q"]').length ||
		$fieldWrap.hasClass('directorist-search-query')
	) {
		return {
			fieldKey: 'title',
			fieldName: 'q',
			$changedField: $fieldWrap.find('input[name="q"]').first(),
		};
	}

	const $customInput = $fieldWrap
		.find('select[name^="custom_field["], input[name^="custom_field["]')
		.first();
	if ($customInput.length) {
		const fieldName = $customInput.attr('name');
		const match = fieldName && fieldName.match(/^custom_field\[([^\]]+)\]/);
		if (match)
			return {
				fieldKey: match[1],
				fieldName,
				$changedField: $customInput,
			};
	}

	return { fieldKey: null, fieldName: null, $changedField: null };
}

/**
 * Normalize condition.field to match actual field name in DOM.
 * Search form custom fields use name="custom_field[custom-select]" etc.
 * Conditions may be stored as "select", "select_2" from builder.
 * @param {string} fieldKey - Raw field key from condition
 * @returns {string} Normalized key (e.g. 'select_2' → 'custom-select-2')
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
