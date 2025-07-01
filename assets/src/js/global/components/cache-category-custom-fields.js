let cache = {};

export function cacheCategoryCustomFields() {
	const customFields = document.querySelectorAll(
		'.atbdp_category_custom_fields .directorist-form-element'
	);
	const checksField = document.querySelectorAll(
		'.atbdp_category_custom_fields .directorist-form-checks'
	);

	if (customFields.length) {
		customFields.forEach(
			(el) => (cache[el.getAttribute('data-id')] = el.value)
		);
	}

	if (checksField.length) {
		checksField.forEach(
			(el) => (cache[el.getAttribute('data-id')] = el.checked)
		);
	}
}

export function getCategoryCustomFieldsCache() {
	return cache;
}
