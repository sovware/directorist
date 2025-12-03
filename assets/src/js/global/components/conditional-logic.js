/**
 * Conditional Logic Evaluation for Frontend Form
 * Handles showing/hiding form fields based on conditional rules
 */

/**
 * Map widget_key/field_key to actual frontend field selector
 */
function mapFieldKeyToSelector(fieldKey) {
	// Map known field keys to their frontend selectors
	const fieldKeyMap = {
		category: '#at_biz_dir-categories',
		categories: '#at_biz_dir-categories',
		description: '[name="description"], #description',
		title: '[name="title"], #title',
		location: '[name="location"], #at_biz_dir-location',
		address: '[name="address"], #address',
		phone: '[name="phone"], #phone',
		email: '[name="email"], #email',
		website: '[name="website"], #website',
		tag: '[name="tag"], #at_biz_dir-tags',
		zip: '[name="zip"], #zip',
		image_upload:
			'[name="listing_img[]"], .directorist-form-image_upload-field',
	};

	if (fieldKeyMap[fieldKey]) {
		return fieldKeyMap[fieldKey];
	}

	return null;
}

/**
 * Get field value from form
 */
function getFieldValue(fieldKey, $) {
	// Special handling for common field keys
	let $field = null;

	// Map field keys to actual field names/selectors
	if (fieldKey === 'category' || fieldKey === 'categories') {
		// Category field uses admin_category_select[] or Select2
		$field = $('#at_biz_dir-categories');
		if (!$field.length) {
			return [];
		}

		// Try data-selected-label attribute first (most reliable for category names)
		let selectedLabels = $field.attr('data-selected-label');
		if (!selectedLabels || !selectedLabels.trim()) {
			// If not in data attribute, try reading from Select2 selection container
			const $select2Container = $field.next('.select2-container');
			if ($select2Container.length) {
				const labels = [];
				$select2Container
					.find('.select2-selection__choice')
					.each(function () {
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
				if (labels.length > 0) {
					selectedLabels = labels.join(',');
					// Update the data attribute for future reads
					$field.attr('data-selected-label', selectedLabels);
				}
			}
		}
		if (selectedLabels && selectedLabels.trim()) {
			const labels = selectedLabels
				.split(',')
				.map(function (label) {
					return label.trim();
				})
				.filter(function (label) {
					return label.length > 0;
				});
			if (labels.length > 0) {
				return labels;
			}
		}

		// Try reading from Select2 selection container (visual tags)
		const $select2Container = $('#at_biz_dir-categories').next(
			'.select2-container'
		);
		if ($select2Container.length) {
			const labelsFromSelect2 = [];
			$select2Container
				.find('.select2-selection__choice')
				.each(function () {
					const $choice = $(this);
					const label =
						$choice
							.find('.select2-selection__choice__display')
							.text()
							.trim() ||
						$choice.text().trim().replace('×', '').trim();
					if (label) {
						labelsFromSelect2.push(label);
					}
				});
			if (labelsFromSelect2.length > 0) {
				return labelsFromSelect2;
			}
		}

		// Try Select2 data
		if (
			$field.hasClass('select2-hidden-accessible') &&
			typeof $field.select2 === 'function'
		) {
			try {
				const selectedData = $field.select2('data');
				if (selectedData && selectedData.length > 0) {
					// Return labels (category names) for comparison
					const labels = selectedData
						.map(function (item) {
							return item.text || item.id || '';
						})
						.filter(function (item) {
							return item.length > 0;
						});
					if (labels.length > 0) {
						return labels;
					}
				}
			} catch (e) {
				// Select2 might not be initialized yet
			}
		}

		// Fallback to select value (may be IDs, not names)
		const val = $field.val();
		if (val) {
			const values = Array.isArray(val) ? val : [val];
			// If we have IDs, try to get labels from selected options
			if (values.length > 0) {
				const labels = [];
				values.forEach(function (id) {
					const $option = $field.find(`option[value="${id}"]`);
					if ($option.length) {
						const label = $option.text().trim();
						if (label) {
							labels.push(label);
						}
					}
				});
				if (labels.length > 0) {
					return labels;
				}
			}
			return values;
		}

		return [];
	}

	// Try mapped selector first
	const mappedSelector = mapFieldKeyToSelector(fieldKey);
	if (mappedSelector) {
		$field = $(mappedSelector).first();
		if ($field.length) {
			// Use the mapped field
		}
	}

	// Try multiple selectors for the field
	if (!$field || !$field.length) {
		const selectors = [
			`[name="${fieldKey}"]`,
			`[name="${fieldKey}[]"]`,
			`#${fieldKey}`,
			`.directorist-form-${fieldKey}-field input`,
			`.directorist-form-${fieldKey}-field select`,
			`.directorist-form-${fieldKey}-field textarea`,
			`input[name*="${fieldKey}"]`,
			`select[name*="${fieldKey}"]`,
			`.directorist-form-group[data-field-key="${fieldKey}"] input`,
			`.directorist-form-group[data-field-key="${fieldKey}"] select`,
			`.directorist-form-group[data-field-key="${fieldKey}"] textarea`,
		];

		for (let selector of selectors) {
			$field = $(selector).first();
			if ($field.length) {
				break;
			}
		}
	}

	if (!$field || !$field.length) {
		return null;
	}

	// Handle checkboxes and radio buttons
	if ($field.is(':checkbox') || $field.is(':radio')) {
		if ($field.is('[name$="[]"]') || $field.attr('name').includes('[]')) {
			// Multiple checkboxes
			const values = [];
			const nameAttr = $field.attr('name');
			$(nameAttr)
				.filter(':checked')
				.each(function () {
					values.push($(this).val());
				});
			return values;
		}
		return $field.is(':checked') ? $field.val() : null;
	}

	// Handle multi-select
	if ($field.is('select[multiple]') || $field.prop('multiple')) {
		const val = $field.val();
		return Array.isArray(val) ? val : val ? [val] : [];
	}

	// Handle Select2 fields
	if ($field.hasClass('select2-hidden-accessible')) {
		const selectedData = $field.select2('data');
		if (selectedData && selectedData.length > 0) {
			return selectedData.map(function (item) {
				return item.text || item.id;
			});
		}
	}

	return $field.val() || null;
}

/**
 * Check if value is empty
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
 * Evaluate a single condition
 */
function evaluateCondition(condition, fieldValue) {
	if (!condition.operator) {
		return false;
	}

	const operator = condition.operator.toLowerCase();
	const conditionValue = condition.value || '';

	// Handle empty/not empty operators
	if (operator === 'empty') {
		return isEmpty(fieldValue);
	}
	if (operator === 'not empty') {
		return !isEmpty(fieldValue);
	}

	// Handle arrays (multi-select fields)
	if (Array.isArray(fieldValue)) {
		return evaluateArrayCondition(fieldValue, conditionValue, operator);
	}

	// Convert values for comparison
	let fieldVal = fieldValue;
	let condVal = conditionValue;

	if (typeof fieldVal === 'string') {
		fieldVal = fieldVal.trim().toLowerCase();
	}
	if (typeof condVal === 'string') {
		condVal = condVal.trim().toLowerCase();
	}

	// Evaluate based on operator
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
				// Case-insensitive contains check
				return fieldVal.toLowerCase().includes(condVal.toLowerCase());
			}
			return false;
		case 'does not contain':
			if (typeof fieldVal === 'string' && typeof condVal === 'string') {
				// Case-insensitive does not contain check
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
 * Evaluate condition for array values
 */
function evaluateArrayCondition(fieldArray, conditionValue, operator) {
	if (!Array.isArray(fieldArray) || fieldArray.length === 0) {
		return operator === 'empty';
	}

	const condVal =
		typeof conditionValue === 'string'
			? conditionValue.trim().toLowerCase()
			: conditionValue;

	switch (operator) {
		case 'is':
		case '==':
		case '=':
		case 'contains':
			return fieldArray.some((val) => {
				let compareVal = val;
				if (typeof compareVal === 'string') {
					compareVal = compareVal.trim().toLowerCase();
				} else if (typeof compareVal === 'number') {
					compareVal = String(compareVal).toLowerCase();
				} else if (
					typeof compareVal === 'object' &&
					compareVal !== null
				) {
					if (compareVal.name) compareVal = compareVal.name;
					else if (compareVal.label) compareVal = compareVal.label;
					else if (compareVal.value) compareVal = compareVal.value;
					else if (compareVal.id) compareVal = compareVal.id;
					else compareVal = String(compareVal);
					if (typeof compareVal === 'string') {
						compareVal = compareVal.trim().toLowerCase();
					}
				}
				const condValStr = String(condVal).toLowerCase();
				const compareValStr = String(compareVal).toLowerCase();
				// Check for exact match or contains match
				return (
					compareValStr === condValStr ||
					compareValStr.includes(condValStr) ||
					condValStr.includes(compareValStr)
				);
			});
		case 'is not':
		case '!=':
		case 'does not contain':
			return !fieldArray.some((val) => {
				let compareVal = val;
				if (typeof compareVal === 'string') {
					compareVal = compareVal.trim().toLowerCase();
				}
				if (typeof compareVal === 'object' && compareVal !== null) {
					if (compareVal.name) compareVal = compareVal.name;
					else if (compareVal.label) compareVal = compareVal.label;
					else if (compareVal.value) compareVal = compareVal.value;
					else if (compareVal.id) compareVal = compareVal.id;
					else compareVal = String(compareVal);
				}
				return (
					String(compareVal)
						.toLowerCase()
						.includes(String(condVal).toLowerCase()) ||
					String(compareVal).toLowerCase() ===
						String(condVal).toLowerCase()
				);
			});
		default:
			return false;
	}
}

/**
 * Evaluate conditional logic rules
 */
function evaluateConditionalLogic(conditionalLogic, getFieldValueFn) {
	if (!conditionalLogic || !conditionalLogic.enabled) {
		return true; // If not enabled, always show
	}

	if (
		!conditionalLogic.groups ||
		!Array.isArray(conditionalLogic.groups) ||
		conditionalLogic.groups.length === 0
	) {
		return true; // If no groups, always show
	}

	// Evaluate each group - groups are combined with OR (if ANY group is true, result is true)
	const groupResults = [];
	for (let group of conditionalLogic.groups) {
		if (
			!group.conditions ||
			!Array.isArray(group.conditions) ||
			group.conditions.length === 0
		) {
			continue;
		}

		// Evaluate conditions in this group
		const conditionResults = [];
		for (let condition of group.conditions) {
			if (!condition.field) {
				continue;
			}

			const fieldValue = getFieldValueFn(condition.field);
			const conditionResult = evaluateCondition(condition, fieldValue);
			conditionResults.push(conditionResult);
		}

		// Combine condition results based on group operator
		let groupResult = false;
		if (conditionResults.length > 0) {
			if (group.operator === 'OR') {
				// Within group: if ANY condition is true, group is true
				groupResult = conditionResults.some(
					(result) => result === true
				);
			} else {
				// Default to AND: ALL conditions must be true
				groupResult = conditionResults.every(
					(result) => result === true
				);
			}
		}
		groupResults.push(groupResult);
	}

	// Groups are combined with OR - if ANY group is true, the result is true
	const result =
		groupResults.length > 0
			? groupResults.some((result) => result === true)
			: true;

	// Apply the action (show/hide)
	if (conditionalLogic.action === 'hide') {
		return !result; // If hide and conditions are met, return false
	}

	// Default to show
	return result;
}

/**
 * Apply conditional logic to a field
 */
function applyConditionalLogic($fieldWrapper, evaluateConditionalLogicFn, $) {
	const conditionalLogicData = $fieldWrapper.attr('data-conditional-logic');
	if (!conditionalLogicData) {
		return;
	}

	try {
		const conditionalLogic = JSON.parse(conditionalLogicData);
		const shouldShow = evaluateConditionalLogicFn(conditionalLogic);

		if (shouldShow) {
			$fieldWrapper.show();
			$fieldWrapper
				.find('input, select, textarea')
				.prop('disabled', false);
			// Enable TinyMCE editor if present
			if (
				$fieldWrapper.find('textarea').length &&
				typeof tinymce !== 'undefined'
			) {
				const editorId = $fieldWrapper.find('textarea').attr('id');
				if (editorId && tinymce.get(editorId)) {
					tinymce.get(editorId).setMode('design');
				}
			}
		} else {
			$fieldWrapper.hide();
			$fieldWrapper
				.find('input, select, textarea')
				.prop('disabled', true);
			// Disable TinyMCE editor if present
			if (
				$fieldWrapper.find('textarea').length &&
				typeof tinymce !== 'undefined'
			) {
				const editorId = $fieldWrapper.find('textarea').attr('id');
				if (editorId && tinymce.get(editorId)) {
					tinymce.get(editorId).setMode('readonly');
				}
			}
		}
	} catch (e) {
		console.error('Error parsing conditional logic:', e);
	}
}

/**
 * Initialize conditional logic for all fields
 */
function initConditionalLogic(
	getWrapperFn,
	getFieldValueFn,
	applyConditionalLogicFn,
	$
) {
	// First, update category field label if needed
	const $categoryField = $('#at_biz_dir-categories');
	if ($categoryField.length) {
		// Ensure data-selected-label is up to date
		if (
			$categoryField.hasClass('select2-hidden-accessible') &&
			typeof $categoryField.select2 === 'function'
		) {
			try {
				const selectedData = $categoryField.select2('data');
				if (selectedData && selectedData.length > 0) {
					const labels = selectedData
						.map(function (item) {
							return item.text || '';
						})
						.filter(function (item) {
							return item.length > 0;
						})
						.join(',');
					$categoryField.attr('data-selected-label', labels);
				}
			} catch (e) {
				// Ignore errors
			}
		}
	}

	// Apply conditional logic to all fields
	// Search both in form wrapper and globally
	const $formWrapper = $(getWrapperFn());
	let $fieldsWithConditionalLogic = $formWrapper.find(
		'.directorist-form-group[data-conditional-logic]'
	);

	// If not found in form wrapper, search globally (for admin or edge cases)
	if ($fieldsWithConditionalLogic.length === 0) {
		$fieldsWithConditionalLogic = $(
			'.directorist-form-group[data-conditional-logic]'
		);
	}

	$fieldsWithConditionalLogic.each(function () {
		const $fieldWrapper = $(this);
		const fieldKey =
			$fieldWrapper.attr('data-field-key') ||
			$fieldWrapper.find('[id]').first().attr('id') ||
			'unknown';

		applyConditionalLogicFn($fieldWrapper);
	});
}

/**
 * Watch for field value changes and re-evaluate conditional logic
 */
function watchFieldChanges(
	getWrapperFn,
	getFieldValueFn,
	applyConditionalLogicFn,
	$
) {
	// Listen to all form field changes
	$(getWrapperFn()).on(
		'change input select2:select select2:unselect',
		'input, select, textarea, .select2-hidden-accessible',
		function () {
			const $changedField = $(this);
			let fieldName =
				$changedField.attr('name') || $changedField.attr('id');

			if (!fieldName) {
				return;
			}

			// Extract field key from name (handle array notation)
			let fieldKey = fieldName;
			if (fieldName.includes('[')) {
				fieldKey = fieldName.split('[')[0];
			}
			if (fieldKey.endsWith('[]')) {
				fieldKey = fieldKey.slice(0, -2);
			}

			// Special handling for category field
			if (
				fieldName === 'admin_category_select[]' ||
				$changedField.is('#at_biz_dir-categories')
			) {
				fieldKey = 'category';
			}

			// Re-evaluate all fields that might depend on this field
			$('.directorist-form-group[data-conditional-logic]').each(
				function () {
					const $fieldWrapper = $(this);
					const conditionalLogicData = $fieldWrapper.attr(
						'data-conditional-logic'
					);

					if (!conditionalLogicData) {
						return;
					}

					try {
						const conditionalLogic =
							JSON.parse(conditionalLogicData);

						// Check if this field's conditional logic depends on the changed field
						let dependsOnField = false;
						if (
							conditionalLogic.groups &&
							Array.isArray(conditionalLogic.groups)
						) {
							for (let group of conditionalLogic.groups) {
								if (
									group.conditions &&
									Array.isArray(group.conditions)
								) {
									for (let condition of group.conditions) {
										// Check multiple possible field key formats
										if (
											condition.field === fieldKey ||
											condition.field === fieldName ||
											(condition.field === 'category' &&
												(fieldKey === 'category' ||
													fieldName.includes(
														'category'
													))) ||
											(condition.field === 'categories' &&
												(fieldKey === 'category' ||
													fieldName.includes(
														'category'
													)))
										) {
											dependsOnField = true;
											break;
										}
									}
								}
								if (dependsOnField) break;
							}
						}

						if (dependsOnField) {
							applyConditionalLogicFn($fieldWrapper);
						}
					} catch (e) {
						console.error('Error in conditional logic:', e);
					}
				}
			);
		}
	);
}

/**
 * Update category field data-selected-label attribute from Select2
 */
function updateCategoryFieldLabel(initConditionalLogicFn, $) {
	const $field = $('#at_biz_dir-categories');
	if (!$field.length) {
		return;
	}

	setTimeout(function () {
		// Get selected labels from Select2
		if (
			$field.hasClass('select2-hidden-accessible') &&
			typeof $field.select2 === 'function'
		) {
			try {
				const selectedData = $field.select2('data');
				if (selectedData && selectedData.length > 0) {
					const labels = selectedData
						.map(function (item) {
							return item.text || '';
						})
						.filter(function (item) {
							return item.length > 0;
						})
						.join(',');
					$field.attr('data-selected-label', labels);
				} else {
					$field.attr('data-selected-label', '');
				}
			} catch (e) {
				// Select2 might not be initialized yet, try reading from DOM
				const $select2Container = $('.select2-selection__choice');
				if ($select2Container.length) {
					const labels = [];
					$select2Container.each(function () {
						const label = $(this)
							.find('.select2-selection__choice__display')
							.text()
							.trim();
						if (label) {
							labels.push(label);
						}
					});
					if (labels.length > 0) {
						$field.attr('data-selected-label', labels.join(','));
					}
				}
			}
		}

		// Re-evaluate conditional logic
		initConditionalLogicFn();
	}, 150);
}

// Export all functions
export {
	applyConditionalLogic,
	evaluateArrayCondition,
	evaluateCondition,
	evaluateConditionalLogic,
	getFieldValue,
	initConditionalLogic,
	isEmpty,
	mapFieldKeyToSelector,
	updateCategoryFieldLabel,
	watchFieldChanges,
};
