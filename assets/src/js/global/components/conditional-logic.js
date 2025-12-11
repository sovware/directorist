/**
 * Conditional Logic Evaluation for Frontend Form
 * Handles showing/hiding form fields based on conditional rules
 */

/**
 * Map widget_key/field_key to actual frontend field selector
 */
function mapFieldKeyToSelector(fieldKey) {
	// Map widget_keys and field_keys to their frontend selectors
	// Note: widget_key (e.g., "title") may differ from field_key (e.g., "listing_title")
	const fieldKeyMap = {
		category: '#at_biz_dir-categories',
		categories: '#at_biz_dir-categories',
		description:
			'[name="listing_content"], #listing_content, [name="description"], #description',
		title: '[name="listing_title"], #listing_title, [name="title"], #title',
		listing_title: '[name="listing_title"], #listing_title',
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
	// Handle category, tag, and location fields - all use Select2 with similar structure
	if (fieldKey === 'category' || fieldKey === 'categories') {
		// Category field uses admin_category_select[] or Select2
		$field = $('#at_biz_dir-categories');
		if (!$field.length) {
			return [];
		}
	} else if (fieldKey === 'tag' || fieldKey === 'tags') {
		// Tag field uses Select2
		$field = $('#at_biz_dir-tags');
		if (!$field.length) {
			return [];
		}
	} else if (fieldKey === 'location' || fieldKey === 'locations') {
		// Location field uses Select2
		$field = $('#at_biz_dir-location');
		if (!$field.length) {
			return [];
		}
	}

	// If we matched a taxonomy field (category, tag, location), process it
	if (
		$field &&
		($field.is('#at_biz_dir-categories') ||
			$field.is('#at_biz_dir-tags') ||
			$field.is('#at_biz_dir-location'))
	) {
		/**
		 * Helper function to extract labels from Select2 selection container
		 * @param {jQuery} $container - Select2 container element
		 * @returns {string[]} Array of category labels
		 */
		function getLabelsFromSelect2Container($container) {
			if (!$container || !$container.length) {
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
		 * Helper function to parse comma-separated labels string
		 * @param {string} labelsStr - Comma-separated labels
		 * @returns {string[]} Array of trimmed, non-empty labels
		 */
		function parseLabelsString(labelsStr) {
			if (!labelsStr || !labelsStr.trim()) {
				return [];
			}
			return labelsStr
				.split(',')
				.map((label) => label.trim())
				.filter((label) => label.length > 0);
		}

		/**
		 * Helper function to parse comma-separated IDs string
		 */
		function parseIdsString(idsStr) {
			if (!idsStr || !idsStr.trim()) {
				return [];
			}
			return idsStr
				.split(',')
				.map((id) => id.trim())
				.filter((id) => id.length > 0 && !isNaN(id));
		}

		// Strategy 1: Try data-selected-label AND data-selected-id (return both for comparison)
		const cachedLabels = $field.attr('data-selected-label');
		const cachedIds = $field.attr('data-selected-id');
		const isTagField = $field.is('#at_biz_dir-tags');

		if (cachedLabels && cachedLabels.trim()) {
			const parsedLabels = parseLabelsString(cachedLabels);
			const parsedIds = cachedIds ? parseIdsString(cachedIds) : [];

			// Return combined array: both IDs and labels for flexible matching
			// This allows condition to match either by ID (from builder dropdown) or label
			const combined = [];

			// For tag field, prioritize labels (names) since that's what's stored in form
			if (isTagField) {
				parsedLabels.forEach((label) => {
					if (label) combined.push(label);
				});
				// For tags, also add IDs if they exist (though form uses names)
				parsedIds.forEach((id) => {
					if (id && !parsedLabels.includes(id)) {
						combined.push(id);
					}
				});
			} else {
				// For category and location, add both labels and IDs
				parsedLabels.forEach((label) => {
					if (label) combined.push(label);
				});
				parsedIds.forEach((id) => {
					if (id) combined.push(id);
				});
			}

			if (combined.length > 0) {
				return combined;
			}
		}

		// Strategy 2: Try Select2 API (most accurate if available)
		if (
			$field.hasClass('select2-hidden-accessible') &&
			typeof $field.select2 === 'function'
		) {
			try {
				const selectedData = $field.select2('data');
				if (selectedData && selectedData.length > 0) {
					const combined = [];
					const isTagField = $field.is('#at_biz_dir-tags');

					selectedData.forEach((item) => {
						// For tag field, Select2 stores tag name as id (since option value is name)
						// So prioritize text (name) for tags
						if (isTagField) {
							// For tags, the id in Select2 is actually the tag name (from option value)
							// So we use both id and text, but text is more reliable
							if (item.text) {
								combined.push(item.text); // Tag name
							}
							if (item.id && item.id !== item.text) {
								combined.push(String(item.id)); // Also add id if different
							}
						} else {
							// For category and location, add both ID and label for flexible matching
							if (item.id) combined.push(String(item.id));
							if (item.text) combined.push(item.text);
						}
					});
					if (combined.length > 0) {
						// Cache for future reads
						$field.attr(
							'data-selected-label',
							selectedData
								.map((item) => item.text || '')
								.filter((t) => t)
								.join(',')
						);
						$field.attr(
							'data-selected-id',
							selectedData
								.map((item) => item.id || '')
								.filter((id) => id)
								.join(',')
						);
						return combined;
					}
				}
			} catch (e) {
				// Select2 might not be initialized yet, continue to next strategy
			}
		}

		// Strategy 3: Try reading from Select2 DOM container (visual tags)
		const $select2Container = $field.next('.select2-container');
		if ($select2Container.length) {
			const labels = getLabelsFromSelect2Container($select2Container);
			if (labels.length > 0) {
				// Also get IDs from the actual select field
				const val = $field.val();
				const ids = Array.isArray(val) ? val : val ? [val] : [];

				const combined = [];
				labels.forEach((label) => {
					if (label) combined.push(label);
				});
				ids.forEach((id) => {
					if (id) combined.push(String(id));
				});

				if (combined.length > 0) {
					// Cache for future reads
					$field.attr('data-selected-label', labels.join(','));
					$field.attr('data-selected-id', ids.join(','));
					return combined;
				}
			}
		}

		// Strategy 4: Fallback to select option text and values
		const val = $field.val();
		if (val) {
			const values = Array.isArray(val) ? val : [val];
			if (values.length > 0) {
				const combined = [];
				const labels = [];
				const ids = [];

				// Special handling for tag field - values are stored as names, not IDs
				const isTagField = $field.is('#at_biz_dir-tags');

				values.forEach((val) => {
					// For tags, the option value IS the tag name, so use it directly
					if (isTagField) {
						const tagName = String(val).trim();
						if (tagName) {
							// For tags, the value is the name, so add it to both labels and combined
							labels.push(tagName);
							combined.push(tagName);
							// Also try to find the ID from data-selected-id if available
							const cachedIds = $field.attr('data-selected-id');
							if (cachedIds) {
								// Tag IDs might be in the cache, but the actual value is the name
								// We'll rely on name matching for tags
							}
						}
					} else {
						// For category and location, try to find option to get both ID and label
						const $option = $field.find(`option[value="${val}"]`);
						if ($option.length) {
							const label = $option.text().trim();
							if (label) {
								labels.push(label);
								combined.push(label);
							}
							ids.push(String(val));
							combined.push(String(val));
						} else {
							// If option not found, treat value as-is (could be ID or label)
							combined.push(String(val));
						}
					}
				});

				if (combined.length > 0) {
					// Cache for future reads
					if (labels.length > 0) {
						$field.attr('data-selected-label', labels.join(','));
					}
					if (ids.length > 0) {
						$field.attr('data-selected-id', ids.join(','));
					} else if (isTagField && combined.length > 0) {
						// For tags, also cache the names as selected-id for consistency
						// (even though they're names, not IDs)
						$field.attr('data-selected-id', combined.join(','));
					}
					return combined;
				}
			}
		}

		return [];
	}

	// Reset $field if it was set for taxonomy fields above, now continue with regular fields
	if (
		$field &&
		!(
			$field.is('#at_biz_dir-categories') ||
			$field.is('#at_biz_dir-tags') ||
			$field.is('#at_biz_dir-location')
		)
	) {
		$field = null;
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
		// Map widget_key to field_key for common fields
		const widgetKeyToFieldKeyMap = {
			title: 'listing_title',
			description: 'listing_content',
		};

		// Get both widget_key and potential field_key
		let potentialFieldKey = widgetKeyToFieldKeyMap[fieldKey] || fieldKey;

		// For custom fields: widget_key might be like "custom-select" or just "select"
		// Try to find field_key by checking if widget_key starts with "custom-"
		// If not, try prepending "custom-" to match field_key format
		if (
			!fieldKey.startsWith('custom-') &&
			!potentialFieldKey.startsWith('custom-')
		) {
			// Try custom field format: "custom-{type}" or "custom-{type}-{suffix}"
			const customFieldKey = `custom-${fieldKey}`;
			// Check if this custom field exists in the form (select, checkbox, or radio)
			const $customField = $(
				`[name="${customFieldKey}"], #${customFieldKey}, .directorist-form-group[data-field-key="${customFieldKey}"] select, .directorist-form-group[data-field-key="${customFieldKey}"] input[type="checkbox"], .directorist-form-group[data-field-key="${customFieldKey}"] input[type="radio"]`
			).first();
			if ($customField.length) {
				potentialFieldKey = customFieldKey;
			}
		}

		const selectors = [
			`[name="${fieldKey}"]`,
			`[name="${fieldKey}[]"]`,
			`#${fieldKey}`,
			`[name="${potentialFieldKey}"]`,
			`[name="${potentialFieldKey}[]"]`,
			`#${potentialFieldKey}`,
			`.directorist-form-${fieldKey}-field input`,
			`.directorist-form-${fieldKey}-field select`,
			`.directorist-form-${fieldKey}-field textarea`,
			`.directorist-form-${potentialFieldKey}-field input`,
			`.directorist-form-${potentialFieldKey}-field select`,
			`.directorist-form-${potentialFieldKey}-field textarea`,
			`input[name*="${fieldKey}"]`,
			`select[name*="${fieldKey}"]`,
			`input[name*="${potentialFieldKey}"]`,
			`select[name*="${potentialFieldKey}"]`,
			`.directorist-form-group[data-field-key="${fieldKey}"] input`,
			`.directorist-form-group[data-field-key="${fieldKey}"] select`,
			`.directorist-form-group[data-field-key="${fieldKey}"] textarea`,
			`.directorist-form-group[data-field-key="${potentialFieldKey}"] input`,
			`.directorist-form-group[data-field-key="${potentialFieldKey}"] select`,
			`.directorist-form-group[data-field-key="${potentialFieldKey}"] textarea`,
			// Additional selectors for custom fields (try both widget_key and field_key formats)
			`.directorist-custom-field-select select[name="${fieldKey}"]`,
			`.directorist-custom-field-select select#${fieldKey}`,
			`.directorist-custom-field-select select[name="${potentialFieldKey}"]`,
			`.directorist-custom-field-select select#${potentialFieldKey}`,
			`.directorist-form-group.directorist-custom-field-select select[name="${fieldKey}"]`,
			`.directorist-form-group.directorist-custom-field-select select#${fieldKey}`,
			`.directorist-form-group.directorist-custom-field-select select[name="${potentialFieldKey}"]`,
			`.directorist-form-group.directorist-custom-field-select select#${potentialFieldKey}`,
			// Try custom field format if fieldKey doesn't start with "custom-"
			...(fieldKey && !fieldKey.startsWith('custom-')
				? [
						`[name="custom-${fieldKey}"]`,
						`#custom-${fieldKey}`,
						`.directorist-form-group[data-field-key="custom-${fieldKey}"] select`,
						`.directorist-form-group[data-field-key="custom-${fieldKey}"] input`,
						`.directorist-custom-field-select select[name="custom-${fieldKey}"]`,
						`.directorist-custom-field-select select#custom-${fieldKey}`,
					]
				: []),
		];

		for (let selector of selectors) {
			$field = $(selector).first();
			if ($field.length) {
				break;
			}
		}
	}

	if (!$field || !$field.length) {
		// Debug: Log when field is not found (especially for custom fields)
		if (
			fieldKey &&
			(fieldKey.includes('select') || fieldKey.startsWith('custom-'))
		) {
			console.warn('Conditional logic: Field not found', {
				fieldKey: fieldKey,
				potentialFieldKey: potentialFieldKey,
				selectorsTried: selectors ? selectors.length : 0,
			});
		}
		return null;
	}

	// Handle checkboxes and radio buttons
	if ($field.is(':checkbox') || $field.is(':radio')) {
		// For checkboxes with [] in name (multiple checkboxes with same name)
		if (
			$field.is('[name$="[]"]') ||
			($field.attr('name') && $field.attr('name').includes('[]'))
		) {
			// Multiple checkboxes - use attribute selector to find all with same name
			const values = [];
			const nameAttr = $field.attr('name');

			// CRITICAL FIX: Use attribute selector [name="..."] instead of passing name directly to $()
			// This prevents jQuery syntax error when nameAttr contains brackets like "custom-checkbox[]"
			$(`[name="${nameAttr}"]`)
				.filter(':checked')
				.each(function () {
					values.push($(this).val());
				});
			return values;
		}
		// For radio buttons or single checkboxes (no [] in name)
		// Radio buttons share the same name, so find the checked one with that name
		if ($field.is(':radio')) {
			const nameAttr = $field.attr('name');
			const $checkedRadio = $(`[name="${nameAttr}"]:checked`);
			return $checkedRadio.length ? $checkedRadio.val() : null;
		}
		// Single checkbox
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

	// Handle TinyMCE editor (wp_editor)
	if (typeof tinymce !== 'undefined' && $field.length) {
		const editorId = $field.attr('id');
		if (editorId && tinymce.get(editorId)) {
			const editor = tinymce.get(editorId);
			if (editor && !editor.isHidden()) {
				// Get content from TinyMCE editor
				const content = editor.getContent();
				// Return text content (strip HTML tags) for comparison
				// You can also return raw HTML if needed: return content;
				const tempDiv = document.createElement('div');
				tempDiv.innerHTML = content;
				return tempDiv.textContent || tempDiv.innerText || '';
			}
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
	// Handle empty array
	if (!Array.isArray(fieldArray) || fieldArray.length === 0) {
		// If array is empty, check what operator expects
		if (operator === 'empty' || operator === 'is empty') {
			return true;
		}
		if (operator === 'not empty' || operator === 'is not empty') {
			return false;
		}
		// For "is" operator with empty array, return false (no match)
		// For "is not" operator with empty array, return true (condition not met = true)
		if (operator === 'is' || operator === '==' || operator === '=') {
			return false; // Empty array never matches "is X"
		}
		if (operator === 'is not' || operator === '!=' || operator === 'not') {
			return true; // Empty array always matches "is not X"
		}
		return false; // Default: empty array doesn't match
	}

	const condVal =
		typeof conditionValue === 'string'
			? conditionValue.trim().toLowerCase()
			: conditionValue;

	switch (operator) {
		case 'is':
		case '==':
		case '=':
			// For "is" operator: must be exactly one selection AND that value must match exactly
			// Note: fieldArray may contain both IDs and labels (e.g., ["Food", "5"] for one selection)
			// So we need to check if there's exactly one unique selection, not array length

			// Normalize condition value for comparison
			const condValStrForIs = String(condVal).toLowerCase().trim();

			// Normalize all array values to strings for comparison
			const normalizedValues = fieldArray.map((val) => {
				if (typeof val === 'string') {
					return val.trim().toLowerCase();
				} else if (typeof val === 'number') {
					return String(val).toLowerCase();
				} else if (typeof val === 'object' && val !== null) {
					if (val.name) return String(val.name).trim().toLowerCase();
					if (val.label)
						return String(val.label).trim().toLowerCase();
					if (val.value)
						return String(val.value).trim().toLowerCase();
					if (val.id) return String(val.id).toLowerCase();
					return String(val).toLowerCase();
				}
				return String(val).toLowerCase();
			});

			// Check if condition value matches any value in the array
			const hasMatch = normalizedValues.some(
				(val) => val === condValStrForIs
			);

			if (!hasMatch) {
				return false; // Condition value not found
			}

			// For "is" operator: array must represent exactly ONE selection
			// Category/tag/location fields return ID+label pairs:
			// - Single selection: ["Food", "5"] → 2 items (ID + label for same selection)
			// - Multiple selections: ["Food", "5", "Travel", "10"] → 4 items (2 selections)
			// So: if array.length <= 2, it's a single selection; if > 2, it's multiple

			// Check if this is the ONLY selection
			if (fieldArray.length > 2) {
				return false; // Multiple selections (3+ items means at least 2 selections)
			}

			// Array has 1-2 items, meaning single selection
			// Condition value must match
			return hasMatch;

		case 'contains':
			// For "contains" operator: value can be one of many (current behavior)
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
	// Normalize enabled flag - handle string "1", boolean true, etc.
	if (!conditionalLogic) {
		return true;
	}

	// Check if enabled (handle string "1", boolean true, etc.)
	const isEnabled =
		conditionalLogic.enabled === true ||
		conditionalLogic.enabled === 1 ||
		conditionalLogic.enabled === '1' ||
		conditionalLogic.enabled === 'true';

	if (!isEnabled) {
		return true; // If not enabled, always show
	}

	if (
		!conditionalLogic.groups ||
		!Array.isArray(conditionalLogic.groups) ||
		conditionalLogic.groups.length === 0
	) {
		return true; // If no groups, always show
	}

	// Evaluate each group
	const groupResults = [];
	for (let group of conditionalLogic.groups) {
		if (
			!group.conditions ||
			!Array.isArray(group.conditions) ||
			group.conditions.length === 0
		) {
			continue; // Skip empty groups
		}

		// Evaluate conditions in this group
		const conditionResults = [];
		for (let condition of group.conditions) {
			// Skip conditions without field (incomplete conditions)
			if (!condition.field || !condition.field.trim()) {
				continue;
			}

			// Skip conditions without operator (incomplete conditions)
			if (!condition.operator || !condition.operator.trim()) {
				continue;
			}

			const fieldValue = getFieldValueFn(condition.field);

			// Debug logging for custom select fields
			if (
				condition.field &&
				condition.field.includes('select') &&
				!condition.field.includes('category') &&
				!condition.field.includes('tag') &&
				!condition.field.includes('location')
			) {
				// Custom select field evaluation
			}

			const conditionResult = evaluateCondition(condition, fieldValue);
			conditionResults.push(conditionResult);
		}

		// Only process group if it has valid conditions
		// If no valid conditions, skip this group (don't add false result)
		if (conditionResults.length === 0) {
			continue;
		}

		// Combine condition results based on group operator
		// Normalize operator to handle case variations and empty values
		let groupOperator = group.operator;

		// Handle various data types and empty values
		if (
			groupOperator === null ||
			groupOperator === undefined ||
			groupOperator === ''
		) {
			groupOperator = 'AND'; // Default to AND
		} else {
			// Convert to string and normalize
			groupOperator = String(groupOperator).trim().toUpperCase();
			// If after trimming it's empty, default to AND
			if (!groupOperator) {
				groupOperator = 'AND';
			}
		}

		// Evaluate group result based on operator
		let groupResult = false;
		if (groupOperator === 'OR') {
			// Within group: if ANY condition is true, group is true
			groupResult = conditionResults.some((result) => result === true);
		} else {
			// Default to AND: ALL conditions must be true
			groupResult = conditionResults.every((result) => result === true);
		}

		// Only push result if group had valid conditions
		groupResults.push(groupResult);
	}

	// Combine group results based on globalOperator (AND/OR)
	// Default to OR if globalOperator is not specified (backward compatibility)
	// Normalize operator to handle case variations
	let globalOperator = conditionalLogic.globalOperator;
	if (
		globalOperator === null ||
		globalOperator === undefined ||
		globalOperator === ''
	) {
		globalOperator = 'OR'; // Default to OR
	} else {
		globalOperator = String(globalOperator).trim().toUpperCase();
		if (!globalOperator) {
			globalOperator = 'OR';
		}
	}

	let result = true;

	if (groupResults.length > 0) {
		if (globalOperator === 'AND') {
			// ALL groups must be true
			result = groupResults.every((groupRes) => groupRes === true);
		} else {
			// OR: ANY group is true
			result = groupResults.some((groupRes) => groupRes === true);
		}
	}

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
		// Decode HTML entities before parsing JSON
		let decodedData = conditionalLogicData;
		if (typeof decodedData === 'string') {
			// Handle HTML entity encoding (e.g., &quot; -> ")
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
		console.error('Error parsing conditional logic:', e, {
			conditionalLogicData,
		});
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
	// Helper function to trigger conditional logic re-evaluation
	function triggerConditionalLogicEvaluation(
		fieldName,
		fieldKey,
		$changedField
	) {
		// Re-evaluate all fields that might depend on this field
		const $fieldsWithLogic = $(
			'.directorist-form-group[data-conditional-logic]'
		);

		$fieldsWithLogic.each(function () {
			const $fieldWrapper = $(this);
			const conditionalLogicData = $fieldWrapper.attr(
				'data-conditional-logic'
			);

			if (!conditionalLogicData) {
				return;
			}

			try {
				// Decode HTML entities before parsing JSON
				let decodedData = conditionalLogicData;
				if (typeof decodedData === 'string') {
					// Handle HTML entity encoding (e.g., &quot; -> ")
					const textarea = document.createElement('textarea');
					textarea.innerHTML = decodedData;
					decodedData = textarea.value;
				}
				const conditionalLogic = JSON.parse(decodedData);

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
								// Map widget_key to field_key for matching
								const widgetKeyToFieldKeyMap = {
									title: 'listing_title',
									description: 'listing_content',
								};

								const conditionFieldKey = condition.field;
								const conditionFieldKeyMapped =
									widgetKeyToFieldKeyMap[conditionFieldKey] ||
									conditionFieldKey;

								// For custom fields: handle widget_key (e.g., "select") vs field_key (e.g., "custom-select")
								// If condition.field is a widget_key (like "select"), try matching with "custom-{type}"
								// If changed field is "custom-{type}", try matching with just the type (widget_key)
								let conditionFieldKeyAsCustom = null;
								let fieldKeyAsWidgetKey = null;

								// If condition field doesn't start with "custom-", try "custom-{field}" format
								if (
									conditionFieldKey &&
									!conditionFieldKey.startsWith('custom-')
								) {
									conditionFieldKeyAsCustom = `custom-${conditionFieldKey}`;
								}

								// If changed field starts with "custom-", extract the widget_key part
								if (
									fieldKey &&
									fieldKey.startsWith('custom-')
								) {
									fieldKeyAsWidgetKey = fieldKey.replace(
										/^custom-/,
										''
									);
								}
								if (
									fieldName &&
									fieldName.startsWith('custom-')
								) {
									const fieldNameAsWidgetKey =
										fieldName.replace(/^custom-/, '');
									if (!fieldKeyAsWidgetKey) {
										fieldKeyAsWidgetKey =
											fieldNameAsWidgetKey;
									}
								}

								// Check multiple possible field key formats
								// Match by exact field key, field name, or id
								if (
									conditionFieldKey === fieldKey ||
									conditionFieldKey === fieldName ||
									conditionFieldKey ===
										$changedField.attr('id') ||
									conditionFieldKey ===
										$changedField.attr('name') ||
									conditionFieldKeyMapped === fieldKey ||
									conditionFieldKeyMapped === fieldName ||
									conditionFieldKeyMapped ===
										$changedField.attr('id') ||
									conditionFieldKeyMapped ===
										$changedField.attr('name') ||
									// Custom field mapping: condition "select" matches changed field "custom-select"
									(conditionFieldKeyAsCustom &&
										(conditionFieldKeyAsCustom ===
											fieldKey ||
											conditionFieldKeyAsCustom ===
												fieldName ||
											conditionFieldKeyAsCustom ===
												$changedField.attr('id') ||
											conditionFieldKeyAsCustom ===
												$changedField.attr('name'))) ||
									// Custom field mapping: changed field "custom-select" matches condition "select"
									(fieldKeyAsWidgetKey &&
										(conditionFieldKey ===
											fieldKeyAsWidgetKey ||
											conditionFieldKeyMapped ===
												fieldKeyAsWidgetKey))
								) {
									dependsOnField = true;
									break;
								}
							}
							if (dependsOnField) {
								break;
							}
						}
					}
				}

				// Special handling for category, tag, and location fields
				const isTaxonomyField =
					fieldKey === 'category' ||
					fieldKey === 'categories' ||
					fieldKey === 'tag' ||
					fieldKey === 'tags' ||
					fieldKey === 'location' ||
					fieldKey === 'locations' ||
					fieldName === 'admin_category_select[]' ||
					$changedField.is('#at_biz_dir-categories') ||
					$changedField.is('#at_biz_dir-tags') ||
					$changedField.is('#at_biz_dir-location');

				if (isTaxonomyField) {
					// Check if any condition references category, tag, or location
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
									if (
										condition.field === 'category' ||
										condition.field === 'categories' ||
										condition.field === 'tag' ||
										condition.field === 'tags' ||
										condition.field === 'location' ||
										condition.field === 'locations'
									) {
										dependsOnField = true;
										break;
									}
								}
								if (dependsOnField) {
									break;
								}
							}
						}
					}
				}

				// If this field depends on the changed field, re-evaluate
				if (dependsOnField) {
					applyConditionalLogicFn($fieldWrapper);
				}
			} catch (e) {
				console.error('Error in conditional logic evaluation:', e);
			}
		});
	}

	// Special handling for category, tag, and location field Select2 events
	// Listen on document to catch events even if field is added dynamically
	const taxonomyFieldSelectors =
		'#at_biz_dir-categories, #at_biz_dir-tags, #at_biz_dir-location';

	$(document).on(
		'select2:select select2:unselect select2:clear',
		taxonomyFieldSelectors,
		function (e) {
			// Update data attributes immediately when taxonomy field changes
			setTimeout(
				function () {
					const $field = $(this); // The field that triggered the event
					if ($field.length) {
						const labels = [];
						const ids = [];

						// Determine field key based on which field was changed
						let fieldKey = 'category';
						let fieldName = 'admin_category_select[]';

						if ($field.is('#at_biz_dir-tags')) {
							fieldKey = 'tag';
							fieldName = $field.attr('name') || 'tag';
						} else if ($field.is('#at_biz_dir-location')) {
							fieldKey = 'location';
							fieldName = $field.attr('name') || 'location';
						}

						// Try to get data from Select2 API
						if (typeof $field.select2 === 'function') {
							try {
								const selectedData = $field.select2('data');
								if (selectedData && selectedData.length > 0) {
									selectedData.forEach(function (item) {
										if (item.text) labels.push(item.text);
										if (item.id) ids.push(String(item.id));
									});
								}
							} catch (e) {
								// Select2 might throw error, continue with DOM reading
							}
						}

						// Fallback: Read from DOM if Select2 API fails
						if (labels.length === 0 && ids.length === 0) {
							// Try to read from Select2 container
							const $container =
								$field.next('.select2-container');
							if ($container.length) {
								$container
									.find('.select2-selection__choice')
									.each(function () {
										const $choice = $(this);
										const label =
											$choice
												.find(
													'.select2-selection__choice__display'
												)
												.text()
												.trim() ||
											$choice
												.text()
												.trim()
												.replace('×', '')
												.trim();
										if (label) labels.push(label);
									});
							}

							// Get IDs from actual select field value
							const val = $field.val();
							if (val) {
								const values = Array.isArray(val) ? val : [val];
								values.forEach(function (id) {
									if (id) ids.push(String(id));
								});
							}
						}

						// Update data attributes (empty string if no selections)
						$field.attr('data-selected-label', labels.join(','));
						$field.attr('data-selected-id', ids.join(','));

						// Trigger re-evaluation after attributes are updated
						triggerConditionalLogicEvaluation(
							fieldName,
							fieldKey,
							$field
						);
					}
				}.bind(this),
				50
			); // Small delay to ensure Select2 has updated
		}
	);

	// Listen to all form field changes
	$(getWrapperFn()).on(
		'change input select2:select select2:unselect',
		'input, select, textarea, .select2-hidden-accessible',
		function () {
			const $changedField = $(this);
			let fieldName =
				$changedField.attr('name') || $changedField.attr('id');

			if (!fieldName) {
				console.warn(
					'Field change detected but no name/id found:',
					$changedField
				);
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

			// Special handling for category, tag, and location fields
			let taxonomyFieldSelector = null;
			if (
				fieldName === 'admin_category_select[]' ||
				$changedField.is('#at_biz_dir-categories')
			) {
				fieldKey = 'category';
				taxonomyFieldSelector = '#at_biz_dir-categories';
			} else if ($changedField.is('#at_biz_dir-tags')) {
				fieldKey = 'tag';
				taxonomyFieldSelector = '#at_biz_dir-tags';
			} else if ($changedField.is('#at_biz_dir-location')) {
				fieldKey = 'location';
				taxonomyFieldSelector = '#at_biz_dir-location';
			}

			if (taxonomyFieldSelector) {
				// Update taxonomy field data attributes when it changes
				setTimeout(function () {
					const $taxField = $(taxonomyFieldSelector);
					if ($taxField.length) {
						const labels = [];
						const ids = [];

						// Try Select2 API
						if (typeof $taxField.select2 === 'function') {
							try {
								const selectedData = $taxField.select2('data');
								if (selectedData && selectedData.length > 0) {
									selectedData.forEach(function (item) {
										if (item.text) labels.push(item.text);
										if (item.id) ids.push(String(item.id));
									});
								}
							} catch (e) {
								// Continue with DOM reading
							}
						}

						// Fallback to DOM
						if (labels.length === 0) {
							const $container =
								$taxField.next('.select2-container');
							if ($container.length) {
								$container
									.find('.select2-selection__choice')
									.each(function () {
										const $choice = $(this);
										const label =
											$choice
												.find(
													'.select2-selection__choice__display'
												)
												.text()
												.trim() ||
											$choice
												.text()
												.trim()
												.replace('×', '')
												.trim();
										if (label) labels.push(label);
									});
							}
						}

						// Get IDs
						const val = $taxField.val();
						if (val) {
							const values = Array.isArray(val) ? val : [val];
							values.forEach(function (id) {
								if (id) ids.push(String(id));
							});
						}

						// Update attributes
						$taxField.attr('data-selected-label', labels.join(','));
						$taxField.attr('data-selected-id', ids.join(','));
					}

					// Trigger evaluation after attributes are updated
					triggerConditionalLogicEvaluation(
						fieldName,
						fieldKey,
						$changedField
					);
				}, 50);
				return; // Don't trigger twice
			}

			triggerConditionalLogicEvaluation(
				fieldName,
				fieldKey,
				$changedField
			);
		}
	);

	// Also listen on document level as fallback for custom fields that might be outside the form wrapper
	$(document).on(
		'change',
		'.directorist-custom-field-select select, select.directorist-form-element, .directorist-custom-field-radio input[type="radio"], .directorist-custom-field-checkbox input[type="checkbox"]',
		function () {
			const $changedField = $(this);
			let fieldName =
				$changedField.attr('name') || $changedField.attr('id');

			if (!fieldName) {
				return;
			}

			// Extract field key from name
			let fieldKey = fieldName;
			if (fieldName.includes('[')) {
				fieldKey = fieldName.split('[')[0];
			}
			if (fieldKey.endsWith('[]')) {
				fieldKey = fieldKey.slice(0, -2);
			}

			triggerConditionalLogicEvaluation(
				fieldName,
				fieldKey,
				$changedField
			);
		}
	);

	/**
	 * Handle color picker field change for conditional logic
	 * Extracts field name/key and triggers conditional logic evaluation with a delay
	 * to ensure the input value is updated in the DOM
	 *
	 * @param {jQuery|HTMLElement} field - The color picker input field (jQuery object or DOM element)
	 */
	function handleColorPickerChange(field) {
		const $changedField = $(field);
		let fieldName = $changedField.attr('name') || $changedField.attr('id');

		if (!fieldName) {
			return;
		}

		// Extract field key from name
		let fieldKey = fieldName;
		if (fieldName.includes('[')) {
			fieldKey = fieldName.split('[')[0];
		}
		if (fieldKey.endsWith('[]')) {
			fieldKey = fieldKey.slice(0, -2);
		}

		// Use setTimeout to ensure the input value is updated after color change
		// The color picker updates the value asynchronously, so we need a small delay
		setTimeout(function () {
			triggerConditionalLogicEvaluation(
				fieldName,
				fieldKey,
				$changedField
			);
		}, 50);
	}

	// Also listen for wpColorPicker's change event directly on the input
	// This catches cases where the custom event might not fire
	$(document).on(
		'change',
		'.directorist-color-picker, .wp-color-picker, input.wp-color-picker',
		function () {
			handleColorPickerChange(this);
		}
	);

	// Also listen for iris color change events (fired by wpColorPicker internally)
	// This is a more direct way to catch color picker changes
	// Note: irischange fires during color selection, but the value might not be set yet
	$(document).on(
		'irischange',
		'.directorist-color-picker, .wp-color-picker, input.wp-color-picker',
		function () {
			handleColorPickerChange(this);
		}
	);

	// Listen for color picker clear button click
	// Note: The button is dynamically added to DOM when color picker is opened
	// We use native addEventListener with capture phase to catch the event
	// before other handlers that might stop propagation
	// This is necessary because the button is created dynamically by wpColorPicker
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
				// Find the associated color picker input
				// e.target is a DOM element, so we need to wrap it in jQuery
				const $clearButton = $(e.target);
				const $colorPickerInput = $clearButton
					.closest('.wp-picker-container')
					.find(
						'.directorist-color-picker, .wp-color-picker, input.wp-color-picker'
					);
				// Trigger conditional logic evaluation
				handleColorPickerChange($colorPickerInput);
			}
		},
		true
	);

	// Listen to TinyMCE editor changes
	// Helper function to attach TinyMCE event listeners
	function attachTinyMCEEvents(editor) {
		if (!editor || !editor.id) {
			return;
		}

		const editorId = editor.id;
		// Check if this editor is within a form group that might be used for conditional logic
		// We'll check for common description/content field IDs
		const $editorTextarea = $('#' + editorId);
		if (!$editorTextarea.length) {
			return;
		}

		// Check if this textarea is inside a directorist-form-group
		const $formGroup = $editorTextarea.closest('.directorist-form-group');
		if (!$formGroup.length) {
			return;
		}

		// Get the field key from the textarea name or id
		const fieldName = $editorTextarea.attr('name') || editorId;
		let fieldKey = fieldName;

		// Map widget_key to field_key
		const widgetKeyToFieldKeyMap = {
			title: 'listing_title',
			description: 'listing_content',
		};
		fieldKey = widgetKeyToFieldKeyMap[fieldKey] || fieldKey;

		// Remove existing listeners to avoid duplicates
		editor.off('input keyup change NodeChange');

		// Listen to editor content changes
		// Use NodeChange for better compatibility with TinyMCE
		editor.on('input keyup change NodeChange', function () {
			const $changedField = $editorTextarea;
			triggerConditionalLogicEvaluation(
				fieldName,
				fieldKey,
				$changedField
			);
		});
	}

	// Set up TinyMCE listeners when available
	if (typeof tinymce !== 'undefined') {
		// Wait for TinyMCE to be ready
		$(document).ready(function () {
			// Use TinyMCE's AddEditor event to attach listeners to new editors
			if (tinymce.on) {
				tinymce.on('AddEditor', function (e) {
					attachTinyMCEEvents(e.editor);
				});
			}

			// Handle editors that are already initialized
			function initExistingEditors() {
				if (typeof tinymce !== 'undefined' && tinymce.editors) {
					tinymce.editors.forEach(function (editor) {
						attachTinyMCEEvents(editor);
					});
				}
			}

			// Try immediately
			initExistingEditors();

			// Also try after a delay to catch late-loading editors
			setTimeout(initExistingEditors, 500);
			setTimeout(initExistingEditors, 1000);
			setTimeout(initExistingEditors, 2000);
		});

		// Listen to WordPress TinyMCE setup events
		$(document).on('tinymce-editor-init', function (e, editor) {
			attachTinyMCEEvents(editor);
		});
	}
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
