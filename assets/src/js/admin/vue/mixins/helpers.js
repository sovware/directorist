import { mapState } from 'vuex';

export default {
	computed: {
		...mapState({
			fields: 'fields',
			cached_fields: 'cached_fields',
			highlighted_field_key: 'highlighted_field_key',
		}),
	},

	methods: {
		doAction(payload, component_key) {
			if (!payload.action) {
				return;
			}
			if (this[payload.component] !== component_key) {
				this.$emit('do-action', payload);
				return;
			}

			if (typeof this[payload.action] !== 'function') {
				return;
			}

			this[payload.action](payload.args);
		},

		maybeJSON(data) {
			try {
				JSON.parse(data);
			} catch (e) {
				return data;
			}

			return JSON.parse(data);
		},

		isObject(the_var) {
			if (typeof the_var === 'undefined') {
				return false;
			}
			if (the_var === null) {
				return false;
			}
			if (typeof the_var !== 'object') {
				return false;
			}
			if (Array.isArray(the_var)) {
				return false;
			}

			return the_var;
		},

		getHighlightState(field_key) {
			return this.highlighted_field_key === field_key;
		},

		getOptionID(option, field_index, section_index) {
			let option_id = '';

			if (section_index) {
				option_id = section_index;
			}

			if (this.fieldId) {
				option_id = option_id + '_' + this.fieldId;
			}

			if (typeof option.id !== 'undefined') {
				option_id = option_id + '_' + option.id;
			}

			if (typeof field_index !== 'undefined') {
				option_id = option_id + '_' + field_index;
			}

			return option_id;
		},

		mapDataByMap(data, map) {
			const flatten_data = JSON.parse(JSON.stringify(data));
			const flatten_map = JSON.parse(JSON.stringify(map));

			let mapped_data = flatten_data.map((element) => {
				let item = {};

				for (let key in flatten_map) {
					if (typeof element[key] !== 'undefined') {
						item[key] = element[flatten_map[key]];
					}
				}

				return item;
			});

			return mapped_data;
		},

		filterDataByValue(data, value) {
			let value_is_array =
				value && typeof value === 'object' ? true : false;
			let value_is_text =
				typeof value === 'string' || typeof value === 'number'
					? true
					: false;
			let flatten_data = JSON.parse(JSON.stringify(data));

			return flatten_data.filter((item) => {
				if (value_is_text && value === item.value) {
					// console.log( 'value_is_text', item.value, value );
					return item;
				}

				if (value_is_array && value.includes(item.value)) {
					// console.log( 'value_is_array', item.value, value );
					return item;
				}

				if (!value_is_text && !value_is_array) {
					// console.log( 'no filter', item.value, value );
					return item;
				}
			});
		},

		checkChangeIfCondition(payload) {
			let root = this.fields;
			let isChangeable = false;

			// Extract from payload
			const { condition, fieldKey } = payload;

			let currentField = root[fieldKey];
			let conditionField = root[condition.where];

			// Loop through the conditions to check if they match
			for (let item of condition.conditions) {
				if (item.key === 'value' && item.compare === '=') {
					// Compare the value
					if (conditionField && conditionField.value === item.value) {
						isChangeable = true;
						break;
					}
				}
			}

			// If the isChangeable is true, apply all effects
			if (isChangeable) {
				for (let effect of condition.effects) {
					currentField[effect.key] = effect.value; // Apply the effect value
				}
			} else {
				// Reset to default values for all effects if not changeable
				for (let effect of condition.effects) {
					if (effect.default_value !== undefined) {
						currentField[effect.key] = effect.default_value;
					}
				}
			}

			return isChangeable;
		},

		checkShowIfCondition(payload) {
			// Handle both single and multiple conditions
			if (payload.condition && Array.isArray(payload.condition)) {
				// This is a multiple condition case
				let result = {
					status: false,
					failed_conditions: 0,
					succeed_conditions: 0,
					matched_data: [],
				};

				for (let condition of payload.condition) {
					let state = this.checkSingleCondition({
						condition: condition,
					});

					if (state.status) {
						result.succeed_conditions += 1;
						result.matched_data.push(condition);
					} else {
						result.failed_conditions += 1;
					}
				}

				result.status = result.failed_conditions === 0;
				return result;
			} else {
				// This is a single condition case
				return this.checkSingleCondition(payload);
			}
		},

		checkSingleCondition(payload) {
			let args = { condition: null };
			Object.assign(args, payload);

			let condition = args.condition;

			let root = this.fields;
			if (this.isObject(args.root)) {
				root = args.root;
			}

			let failed_cond_count = 0;
			let success_cond_count = 0;
			let accepted_comparison = ['and', 'or'];
			let compare = 'and';
			let matched_data = [];

			let state = {
				status: false,
				failed_conditions: failed_cond_count,
				succeed_conditions: success_cond_count,
				matched_data: matched_data,
			};

			let target_field = this.getTergetFields({
				root: root,
				path: condition.where,
			});

			if (
				!(
					condition.conditions &&
					Array.isArray(condition.conditions) &&
					condition.conditions.length
				)
			) {
				return state;
			}
			if (!this.isObject(target_field)) {
				return state;
			}

			if (
				typeof condition.compare === 'string' &&
				accepted_comparison.indexOf(condition.compare)
			) {
				compare = condition.compare;
			}

			for (let sub_condition of condition.conditions) {
				if (typeof sub_condition.key !== 'string') {
					continue;
				}

				let sub_condition_field_path = sub_condition.key.split('.');
				let sub_condition_field = null;
				let sub_condition_error = 0;
				let sub_compare =
					typeof sub_condition.compare === 'string'
						? sub_condition.compare
						: '=';

				if (!sub_condition_field_path.length) {
					continue;
				}

				// ---
				if (sub_condition_field_path[0] !== '_any') {
					sub_condition_field =
						target_field[sub_condition_field_path[0]];
					let is_hidden =
						typeof target_field.hidden !== 'undefined'
							? target_field.hidden
							: false;

					if (
						sub_condition_field_path.length > 1 &&
						!this.isObject(sub_condition_field)
					) {
						sub_condition_error++;
					}

					if (
						sub_condition_field_path.length > 1 &&
						!sub_condition_error
					) {
						sub_condition_field =
							target_field[sub_condition_field_path[0]][
								sub_condition_field_path[1]
							];
						is_hidden =
							typeof target_field[sub_condition_field_path[0]]
								.hidden !== 'undefined'
								? target_field[sub_condition_field_path[0]]
										.hidden
								: false;
					}

					if (is_hidden) {
						sub_condition_error++;
					}

					if (typeof sub_condition_field === 'undefined') {
						sub_condition_error++;
					}

					if (sub_condition_error) {
						failed_cond_count++;
						continue;
					}

					if (
						!this.checkComparison({
							data_a: sub_condition_field,
							data_b: sub_condition.value,
							compare: sub_compare,
						})
					) {
						failed_cond_count++;
						continue;
					}

					matched_data.push(
						target_field[sub_condition_field_path[0]]
					);
					success_cond_count++;
					continue;
				}

				// Check if has _any condition
				if (sub_condition_field_path[0] === '_any') {
					let failed_any_cond_count = 0;
					let success_any_cond_count = 0;

					for (let field in target_field) {
						let any_cond_error = 0;

						sub_condition_field = target_field[field];

						if (
							sub_condition_field_path.length > 1 &&
							!this.isObject(sub_condition_field)
						) {
							any_cond_error++;
						}

						if (
							sub_condition_field_path.length > 1 &&
							!any_cond_error
						) {
							sub_condition_field =
								sub_condition_field[
									sub_condition_field_path[1]
								];
						}

						if (typeof sub_condition_field === 'undefined') {
							any_cond_error++;
						}

						if (any_cond_error) {
							failed_any_cond_count++;
							continue;
						}

						if (
							!this.checkComparison({
								data_a: sub_condition_field,
								data_b: sub_condition.value,
								compare: sub_compare,
							})
						) {
							failed_any_cond_count++;
							continue;
						}

						matched_data.push(target_field[field]);
						success_any_cond_count++;
					}

					if (!success_any_cond_count) {
						failed_cond_count++;
					} else {
						success_cond_count++;
					}
				}
			}

			// Get Status
			let status = false;
			switch (compare) {
				case 'and':
					status = failed_cond_count ? false : true;
					break;
				case 'or':
					status = success_cond_count ? true : false;
					break;
			}

			state = {
				status: status,
				failed_conditions: failed_cond_count,
				succeed_conditions: success_cond_count,
				matched_data: matched_data,
			};

			return state;
		},

		checkComparison(payload) {
			let args = { data_a: '', data_b: '', compare: '=' };
			Object.assign(args, payload);

			let status = false;

			switch (args.compare) {
				case '=':
					status = args.data_a == args.data_b ? true : false;
					break;
				case '==':
					status = args.data_a === args.data_b ? true : false;
					break;
				case '!=':
					status = args.data_a !== args.data_b ? true : false;
					break;
				case 'not':
					status = args.data_a !== args.data_b ? true : false;
					break;
				case '>':
					status = args.data_a > args.data_b ? true : false;
					break;
				case '<':
					status = args.data_a < args.data_b ? true : false;
					break;
				case '>=':
					status = args.data_a >= args.data_b ? true : false;
					break;
				case '<=':
					status = args.data_a <= args.data_b ? true : false;
					break;
			}

			return status;
		},

		getFormFieldName(field_type) {
			return field_type + '-field';
		},

		updateFieldValue(field_key, value) {
			this.$store.commit('updateFieldValue', { field_key, value });
		},

		updateFieldValidationState(field_key, value) {
			this.$store.commit('updateFieldData', {
				field_key,
				option_key: 'validationState',
				value,
			});
		},

		updateFieldData(field_key, option_key, value) {
			this.$store.commit('updateFieldData', {
				field_key,
				option_key,
				value,
			});
		},

		getActiveClass(item_index, active_index) {
			return item_index === active_index ? 'active' : '';
		},

		getTergetFields(payload) {
			let args = { root: this.fields, path: '' };

			if (this.isObject(payload)) {
				Object.assign(args, payload);
			}

			if (typeof args.path !== 'string') {
				return null;
			}
			let terget_field = null;

			let terget_fields = args.path.split('.');
			let terget_missmatched = false;

			if (terget_fields && typeof terget_fields === 'object') {
				terget_field = this.fields;

				for (let key of terget_fields) {
					if (!key.length) {
						continue;
					}

					if ('self' === key) {
						terget_field = args.root;
						continue;
					}

					if (typeof terget_field[key] === 'undefined') {
						terget_missmatched = true;
						break;
					}

					if (
						typeof terget_field[key].isVisible !== 'undefined' &&
						!terget_field[key].isVisible
					) {
						terget_missmatched = true;
						break;
					}

					terget_field =
						terget_field !== null
							? terget_field[key]
							: args.root[key];
				}
			}

			if (terget_missmatched) {
				return false;
			}

			return JSON.parse(JSON.stringify(terget_field));
		},

		getSanitizedProps(props) {
			if (props && typeof props === 'object') {
				let _props = JSON.parse(JSON.stringify(props));
				delete _props.value;

				return _props;
			}

			return props;
		},
	},

	/**
	 * Evaluate conditional logic rules in the new format.
	 * @param {Object} conditionalLogic - Conditional logic configuration
	 * @param {Object} rootFields - Root fields object containing all field values
	 * @returns {Boolean} - True if conditions are met
	 */
	/**
	 * Evaluate conditional logic rules for admin form builder preview
	 *
	 * This function is used in the ADMIN FORM BUILDER to show/hide fields in the preview
	 * based on conditional logic rules configured by the admin user.
	 *
	 * @param {Object} conditionalLogic - Conditional logic configuration
	 * @param {Object} rootFields - All field values from the form builder (rootFields object)
	 * @returns {boolean} - true if field should be shown, false if hidden
	 *
	 * Usage: Called from Field_List_Component.vue (line 167) to filter visible fields
	 * in the admin form builder preview as the admin configures conditional logic rules.
	 */
	evaluateConditionalLogic(conditionalLogic, rootFields) {
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
		let groupResults = [];
		for (let group of conditionalLogic.groups) {
			if (
				!group.conditions ||
				!Array.isArray(group.conditions) ||
				group.conditions.length === 0
			) {
				continue;
			}

			// Evaluate conditions in this group - combined with AND/OR based on group.operator
			let conditionResults = [];
			for (let condition of group.conditions) {
				// Skip conditions without field (incomplete conditions)
				if (!condition.field || !condition.field.trim()) {
					continue;
				}

				// Skip conditions without operator (incomplete conditions)
				if (!condition.operator || !condition.operator.trim()) {
					continue;
				}

				// Get the field value from rootFields
				let fieldValue = this.getFieldValueForCondition(
					rootFields,
					condition.field
				);
				let conditionResult = this.evaluateCondition(
					condition,
					fieldValue
				);
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
			if (!groupOperator || typeof groupOperator !== 'string') {
				groupOperator = 'AND'; // Default to AND
			}
			groupOperator = groupOperator.toString().trim().toUpperCase();

			// Evaluate group result based on operator
			let groupResult = false;
			if (groupOperator === 'OR') {
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
	},

	/**
	 * Get field value from root fields for condition evaluation.
	 * @param {Object} rootFields - Root fields object
	 * @param {String} fieldKey - Field key to get value for
	 * @returns {*} - Field value
	 */
	getFieldValueForCondition(rootFields, fieldKey) {
		if (!rootFields || !fieldKey) {
			return null;
		}

		// Try to get the field value
		if (typeof rootFields[fieldKey] !== 'undefined') {
			let field = rootFields[fieldKey];

			// If field is an object with a value property, use that
			if (this.isObject(field) && typeof field.value !== 'undefined') {
				return field.value;
			}

			// Otherwise use the field itself if it's a primitive value
			if (typeof field !== 'object') {
				return field;
			}
		}

		return null;
	},

	/**
	 * Evaluate a single condition.
	 * @param {Object} condition - Condition object with field, operator, value
	 * @param {*} fieldValue - Current value of the field being checked
	 * @returns {Boolean} - True if condition is met
	 */
	evaluateCondition(condition, fieldValue) {
		if (!condition.operator) {
			return false;
		}

		const operator = condition.operator.toLowerCase();
		const conditionValue = condition.value;

		// Handle empty/not empty operators first (they don't need a value)
		if (operator === 'empty') {
			return this.isEmpty(fieldValue);
		}
		if (operator === 'not empty') {
			return !this.isEmpty(fieldValue);
		}

		// Convert fieldValue and conditionValue to comparable types
		let fieldVal = fieldValue;
		let condVal = conditionValue;

		// Handle arrays (for multi-select fields like category)
		if (Array.isArray(fieldVal)) {
			return this.evaluateArrayCondition(fieldVal, condVal, operator);
		}

		// Handle strings
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
				return fieldVal == condVal;
			case 'is not':
			case '!=':
			case 'not':
				return fieldVal != condVal;
			case 'contains':
				if (
					typeof fieldVal === 'string' &&
					typeof condVal === 'string'
				) {
					return fieldVal.includes(condVal);
				}
				return false;
			case 'does not contain':
				if (
					typeof fieldVal === 'string' &&
					typeof condVal === 'string'
				) {
					return !fieldVal.includes(condVal);
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
				if (
					typeof fieldVal === 'string' &&
					typeof condVal === 'string'
				) {
					return fieldVal.startsWith(condVal);
				}
				return false;
			case 'ends with':
				if (
					typeof fieldVal === 'string' &&
					typeof condVal === 'string'
				) {
					return fieldVal.endsWith(condVal);
				}
				return false;
			default:
				return false;
		}
	},

	/**
	 * Evaluate condition for array values (multi-select fields).
	 * @param {Array} fieldArray - Array of field values
	 * @param {*} conditionValue - Value to compare against
	 * @param {String} operator - Comparison operator
	 * @returns {Boolean}
	 */
	evaluateArrayCondition(fieldArray, conditionValue, operator) {
		if (!Array.isArray(fieldArray) || fieldArray.length === 0) {
			return operator === 'empty';
		}

		// Convert condition value to comparable format
		let condVal = conditionValue;
		if (typeof condVal === 'string') {
			condVal = condVal.trim().toLowerCase();
		}

		// Check if any item in the array matches
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
						if (val.name)
							return String(val.name).trim().toLowerCase();
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
					}
					if (typeof compareVal === 'object' && compareVal !== null) {
						// Handle objects (e.g., category objects)
						if (compareVal.name) compareVal = compareVal.name;
						else if (compareVal.label)
							compareVal = compareVal.label;
						else if (compareVal.value)
							compareVal = compareVal.value;
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
						else if (compareVal.label)
							compareVal = compareVal.label;
						else if (compareVal.value)
							compareVal = compareVal.value;
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
	},

	/**
	 * Check if a value is empty.
	 * @param {*} value - Value to check
	 * @returns {Boolean}
	 */
	isEmpty(value) {
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
	},

	data() {
		return {
			default_option: { value: '', label: 'Select...' },
		};
	},
};
