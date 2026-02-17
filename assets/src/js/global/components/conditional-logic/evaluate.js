/**
 * Condition evaluation logic
 */
import { normalizeConditionFieldKey } from './field-mapping.js';
import { normalizeOperator } from './helpers.js';

/**
 * Check if value is empty (null, undefined, '', or []).
 * @param {*} value
 * @returns {boolean}
 */
export function isEmpty(value) {
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
 * Evaluate a single condition (operator + value).
 * @param {Object} condition - { field, operator, value }
 * @param {*} fieldValue - Current field value
 * @returns {boolean}
 */
export function evaluateCondition(condition, fieldValue) {
	if (!condition.operator) {
		return false;
	}

	const operator = condition.operator.toLowerCase();
	const conditionValue = condition.value || '';

	// Handle file fields with "uploaded" value
	if (conditionValue.toLowerCase() === 'uploaded') {
		if (operator === 'is' || operator === '==' || operator === '=') {
			return fieldValue === 'uploaded' || fieldValue === true;
		}
		if (operator === 'is not' || operator === '!=' || operator === 'not') {
			return (
				fieldValue !== 'uploaded' &&
				fieldValue !== true &&
				isEmpty(fieldValue)
			);
		}
		if (operator === 'empty') {
			return isEmpty(fieldValue) || fieldValue !== 'uploaded';
		}
		if (operator === 'not empty') {
			return !isEmpty(fieldValue) && fieldValue === 'uploaded';
		}
	}

	if (operator === 'empty') {
		return isEmpty(fieldValue);
	}
	if (operator === 'not empty') {
		return !isEmpty(fieldValue);
	}

	if (Array.isArray(fieldValue)) {
		return evaluateArrayCondition(fieldValue, conditionValue, operator);
	}

	let fieldVal = fieldValue;
	let condVal = conditionValue;

	if (typeof fieldVal === 'string') {
		fieldVal = fieldVal.trim().toLowerCase();
	}
	if (typeof condVal === 'string') {
		condVal = condVal.trim().toLowerCase();
	}

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
				return fieldVal.toLowerCase().includes(condVal.toLowerCase());
			}
			return false;
		case 'does not contain':
			if (typeof fieldVal === 'string' && typeof condVal === 'string') {
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
 * Evaluate condition when field value is an array (e.g. multi-select).
 * @param {Array} fieldArray
 * @param {*} conditionValue
 * @param {string} operator
 * @returns {boolean}
 */
export function evaluateArrayCondition(fieldArray, conditionValue, operator) {
	if (!Array.isArray(fieldArray) || fieldArray.length === 0) {
		if (operator === 'empty' || operator === 'is empty') {
			return true;
		}
		if (operator === 'not empty' || operator === 'is not empty') {
			return false;
		}
		if (operator === 'is' || operator === '==' || operator === '=') {
			return false;
		}
		if (operator === 'is not' || operator === '!=' || operator === 'not') {
			return true;
		}
		return false;
	}

	const condVal =
		typeof conditionValue === 'string'
			? conditionValue.trim().toLowerCase()
			: conditionValue;

	switch (operator) {
		case 'is':
		case '==':
		case '=': {
			const condValStrForIs = String(condVal).toLowerCase().trim();
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
			// "is" = strict: all values must match (category/location return IDs only)
			const hasMatch = normalizedValues.some(
				(val) => val === condValStrForIs
			);
			if (!hasMatch) return false;
			return normalizedValues.every((val) => val === condValStrForIs);
		}
		case 'contains': {
			const condValStrContains = String(condVal).toLowerCase();
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
				const compareValStr = String(compareVal).toLowerCase();
				return (
					compareValStr === condValStrContains ||
					compareValStr.includes(condValStrContains)
				);
			});
		}
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
		case 'empty':
		case 'is empty':
			return fieldArray.length === 0;
		case 'not empty':
		case 'is not empty':
			return fieldArray.length > 0;
		default:
			return false;
	}
}

/**
 * Evaluate full conditional logic (groups, operators, action).
 * @param {Object} conditionalLogic - { enabled, action, globalOperator, groups }
 * @param {Function} getFieldValueFn - (fieldKey) => value
 * @returns {boolean} True if field should show
 */
export function evaluateConditionalLogic(conditionalLogic, getFieldValueFn) {
	if (!conditionalLogic) {
		return true;
	}

	const isEnabled =
		conditionalLogic.enabled === true ||
		conditionalLogic.enabled === 1 ||
		conditionalLogic.enabled === '1' ||
		conditionalLogic.enabled === 'true';

	if (!isEnabled) {
		return true;
	}

	if (
		!conditionalLogic.groups ||
		!Array.isArray(conditionalLogic.groups) ||
		conditionalLogic.groups.length === 0
	) {
		return true;
	}

	const groupResults = [];
	for (let group of conditionalLogic.groups) {
		if (
			!group.conditions ||
			!Array.isArray(group.conditions) ||
			group.conditions.length === 0
		) {
			continue;
		}

		const conditionResults = [];
		for (let condition of group.conditions) {
			const rawField = (condition.field || '').trim();
			if (!rawField) {
				continue;
			}
			if (!condition.operator || !condition.operator.trim()) {
				continue;
			}

			const fieldKeyForLookup = normalizeConditionFieldKey(rawField);
			const fieldValue = getFieldValueFn(fieldKeyForLookup);
			const conditionResult = evaluateCondition(condition, fieldValue);
			conditionResults.push(conditionResult);
		}

		if (conditionResults.length === 0) {
			continue;
		}

		const groupOperator = normalizeOperator(group.operator, 'AND');
		const groupResult =
			groupOperator === 'OR'
				? conditionResults.some((result) => result === true)
				: conditionResults.every((result) => result === true);

		groupResults.push(groupResult);
	}

	const globalOperator = normalizeOperator(
		conditionalLogic.globalOperator,
		'OR'
	);
	let result = true;

	if (groupResults.length > 0) {
		result =
			globalOperator === 'AND'
				? groupResults.every((groupRes) => groupRes === true)
				: groupResults.some((groupRes) => groupRes === true);
	}

	if (conditionalLogic.action === 'hide') {
		return !result;
	}

	return result;
}
