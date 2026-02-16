/**
 * Check if a field's conditional logic depends on a given changed field.
 * Used to determine which fields need re-evaluation when a value changes.
 *
 * @param {Object} conditionalLogic - Parsed conditional logic config
 * @param {string} fieldKey - Key of the changed field (e.g. 'custom-checkbox', 'category')
 * @param {string} fieldName - Name attribute of the changed field (e.g. 'custom_field[custom-checkbox][]')
 * @param {jQuery} $changedField - The DOM element that changed
 * @param {Function} normalizeConditionFieldKey - Normalizer for field keys
 * @returns {boolean} True if any condition in the config references the changed field
 */
import {
	WIDGET_KEY_TO_FIELD_KEY,
	TAXONOMY_FIELD_KEYS,
	SELECTORS,
} from './field-mapping.js';

export function fieldDependsOnChange(
	conditionalLogic,
	fieldKey,
	fieldName,
	$changedField,
	normalizeConditionFieldKey
) {
	if (!conditionalLogic.groups || !Array.isArray(conditionalLogic.groups)) {
		return false;
	}

	const hasChangedField =
		$changedField &&
		typeof $changedField.length !== 'undefined' &&
		$changedField.length > 0;
	const isTaxonomyField =
		TAXONOMY_FIELD_KEYS.includes(fieldKey) ||
		TAXONOMY_FIELD_KEYS.includes(fieldName) ||
		(hasChangedField &&
			($changedField.is(SELECTORS.CATEGORY) ||
				$changedField.is(SELECTORS.TAGS) ||
				$changedField.is(SELECTORS.LOCATION) ||
				$changedField.is(SELECTORS.IN_LOC) ||
				$changedField.is(SELECTORS.IN_CAT) ||
				$changedField.is(SELECTORS.IN_TAG) ||
				$changedField.closest(SELECTORS.CATEGORY_CHECKLIST).length ||
				$changedField.closest(SELECTORS.LOCATION_CHECKLIST).length ||
				$changedField.closest(SELECTORS.TAGS_CHECKLIST).length));

	for (const group of conditionalLogic.groups) {
		if (!group.conditions || !Array.isArray(group.conditions)) {
			continue;
		}
		for (const condition of group.conditions) {
			const conditionFieldKey = (condition.field || '').trim();
			const conditionFieldKeyMapped =
				WIDGET_KEY_TO_FIELD_KEY[conditionFieldKey] || conditionFieldKey;
			const conditionFieldKeyNormalized =
				normalizeConditionFieldKey(conditionFieldKey);

			let fieldKeyAsWidgetKey = null;
			if (fieldKey && fieldKey.startsWith('custom-')) {
				fieldKeyAsWidgetKey = fieldKey
					.replace(/^custom-/, '')
					.replace(/-/g, '_');
			}
			if (fieldName && fieldName.startsWith('custom-')) {
				const fieldNameAsWidgetKey = fieldName.replace(/^custom-/, '');
				if (!fieldKeyAsWidgetKey) {
					fieldKeyAsWidgetKey = fieldNameAsWidgetKey;
				}
			}

			const changedId = hasChangedField ? $changedField.attr('id') : null;
			const changedName = hasChangedField
				? $changedField.attr('name')
				: null;

			// review (builder field) = search_by_rating[] (DOM name)
			const isReviewField =
				(conditionFieldKey === 'review' &&
					(fieldKey === 'search_by_rating' || fieldName === 'search_by_rating[]')) ||
				(conditionFieldKey === 'search_by_rating' && fieldKey === 'review');

			const matches =
				isReviewField ||
				conditionFieldKey === fieldKey ||
				conditionFieldKey === fieldName ||
				conditionFieldKey === changedId ||
				conditionFieldKey === changedName ||
				conditionFieldKeyMapped === fieldKey ||
				conditionFieldKeyMapped === fieldName ||
				conditionFieldKeyMapped === changedId ||
				conditionFieldKeyMapped === changedName ||
				(conditionFieldKeyNormalized &&
					(conditionFieldKeyNormalized === fieldKey ||
						conditionFieldKeyNormalized === fieldName ||
						conditionFieldKeyNormalized === changedId ||
						conditionFieldKeyNormalized === changedName ||
						`custom_field[${conditionFieldKeyNormalized}]` ===
							fieldName ||
						`custom_field[${conditionFieldKeyNormalized}][]` ===
							fieldName)) ||
				(fieldKeyAsWidgetKey &&
					(conditionFieldKey === fieldKeyAsWidgetKey ||
						conditionFieldKeyMapped === fieldKeyAsWidgetKey));

			if (matches) {
				return true;
			}
		}
	}

	if (isTaxonomyField) {
		for (const group of conditionalLogic.groups) {
			if (!group.conditions || !Array.isArray(group.conditions)) {
				continue;
			}
			for (const condition of group.conditions) {
				if (TAXONOMY_FIELD_KEYS.includes(condition.field)) {
					return true;
				}
			}
		}
	}

	return false;
}
