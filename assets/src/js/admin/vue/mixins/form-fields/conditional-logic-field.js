import { mapState } from 'vuex';
import helpers from '../helpers';
import props from './input-field-props.js';

export default {
	mixins: [props, helpers],
	model: {
		prop: 'value',
		event: 'input',
	},

	created() {
		this.initValue();
		this.$emit('update', this.localValue);
		this.setup();
	},

	watch: {
		value(newVal) {
			if (JSON.stringify(newVal) !== JSON.stringify(this.localValue)) {
				this.initValue();
			}
		},
	},

	computed: {
		...mapState({
			fields: 'fields',
		}),

		toggleClass() {
			return {
				active: this.localValue.enabled,
			};
		},

		formGroupClass() {
			var validation_classes = this.validationLog.inputErrorClasses
				? this.validationLog.inputErrorClasses
				: {};

			return {
				...validation_classes,
			};
		},

		/**
		 * Get available fields for conditional logic.
		 * Fields that can be used in conditions.
		 */
		availableFields() {
			// Get all form fields from the submission form fields
			// This will be populated from the form builder context
			let fields = [];

			// Try to get fields from root context (form builder)
			if (this.root && typeof this.root === 'object') {
				// Access submission_form_fields from the builder
				// This is a placeholder - actual implementation will depend on form builder structure
				return this.getFieldsFromRoot();
			}

			// Fallback: return empty array for now
			// This will be properly implemented when we connect to form builder
			return fields;
		},
	},

	data() {
		return {
			localValue: {
				enabled: false,
				action: 'show',
				groups: [],
			},
			validationLog: {},
		};
	},

	methods: {
		setup() {
			// Setup initialization
		},

		initValue() {
			const defaultValue = {
				enabled: false,
				action: 'show',
				groups: [],
			};

			if (!this.value || typeof this.value !== 'object') {
				this.localValue = JSON.parse(JSON.stringify(defaultValue));
				return;
			}

			this.localValue = {
				enabled:
					typeof this.value.enabled !== 'undefined'
						? this.value.enabled
						: false,
				action: this.value.action || 'show',
				groups: Array.isArray(this.value.groups)
					? JSON.parse(JSON.stringify(this.value.groups))
					: [],
			};

			// Ensure groups have proper structure
			if (!this.localValue.groups.length) {
				this.localValue.groups = [this.createEmptyGroup()];
			} else {
				// Validate and fix group structure
				this.localValue.groups = this.localValue.groups.map((group) => {
					if (!Array.isArray(group.conditions)) {
						group.conditions = [];
					}
					if (!group.operator) {
						group.operator = 'AND';
					}
					if (!group.conditions.length) {
						group.conditions = [this.createEmptyCondition()];
					}
					return group;
				});
			}
		},

		toggleEnabled() {
			this.localValue.enabled = !this.localValue.enabled;
			this.updateValue();
		},

		updateValue() {
			// Deep clone to ensure reactivity
			const valueToEmit = JSON.parse(JSON.stringify(this.localValue));
			this.$emit('update', valueToEmit);
		},

		createEmptyGroup() {
			return {
				operator: 'AND',
				conditions: [this.createEmptyCondition()],
			};
		},

		createEmptyCondition() {
			return {
				field: '',
				operator: 'is',
				value: '',
			};
		},

		addCondition(groupIndex) {
			if (!this.localValue.groups[groupIndex]) {
				return;
			}
			this.localValue.groups[groupIndex].conditions.push(
				this.createEmptyCondition()
			);
			this.updateValue();
		},

		removeCondition(groupIndex, conditionIndex) {
			if (!this.localValue.groups[groupIndex]) {
				return;
			}
			if (this.localValue.groups[groupIndex].conditions.length <= 1) {
				// Don't remove the last condition, just reset it
				this.localValue.groups[groupIndex].conditions[conditionIndex] =
					this.createEmptyCondition();
			} else {
				this.localValue.groups[groupIndex].conditions.splice(
					conditionIndex,
					1
				);
			}
			this.updateValue();
		},

		addGroup() {
			this.localValue.groups.push(this.createEmptyGroup());
			this.updateValue();
		},

		removeGroup(groupIndex) {
			if (this.localValue.groups.length <= 1) {
				// Don't remove the last group, just reset it
				this.localValue.groups[groupIndex] = this.createEmptyGroup();
			} else {
				this.localValue.groups.splice(groupIndex, 1);
			}
			this.updateValue();
		},

		onFieldChange(condition) {
			// When field changes, reset value and potentially update operators
			condition.value = '';
			this.updateValue();
		},

		onConditionValueUpdate(condition, value) {
			condition.value = value;
			this.updateValue();
		},

		/**
		 * Get fields from root context (form builder).
		 * This method will extract available fields from the submission form fields.
		 */
		getFieldsFromRoot() {
			// Try multiple methods to find the form builder component

			// Method 1: Traverse up the component tree to find form-builder
			let parent = this.$parent;
			while (parent) {
				// Check by component name
				if (
					parent.$options &&
					parent.$options.name === 'form-builder'
				) {
					if (parent.active_widget_fields) {
						return this.formatFieldsForDropdown(
							parent.active_widget_fields
						);
					}
				}

				// Check for active_widget_fields property directly (form builder might have it)
				if (
					parent.active_widget_fields &&
					typeof parent.active_widget_fields === 'object'
				) {
					return this.formatFieldsForDropdown(
						parent.active_widget_fields
					);
				}

				parent = parent.$parent;
			}

			// Method 2: Search in root's children
			if (this.$root && this.$root.$children) {
				const findInChildren = (children) => {
					for (let child of children) {
						if (child && child.$options) {
							if (
								child.$options.name === 'form-builder' &&
								child.active_widget_fields
							) {
								return child.active_widget_fields;
							}
						}
						// Recursively search nested children
						if (
							child &&
							child.$children &&
							child.$children.length > 0
						) {
							const found = findInChildren(child.$children);
							if (found) return found;
						}
					}
					return null;
				};

				const foundFields = findInChildren(this.$root.$children);
				if (foundFields) {
					return this.formatFieldsForDropdown(foundFields);
				}
			}

			// Method 3: Check root component itself
			if (this.$root && this.$root.active_widget_fields) {
				return this.formatFieldsForDropdown(
					this.$root.active_widget_fields
				);
			}

			// Method 4: Try accessing through provide/inject if available
			// (Not implemented yet, but could be added if needed)

			return [];
		},

		/**
		 * Format fields from form builder for dropdown options.
		 * @param {Object} activeWidgetFields - Object with widget_key as keys and field data as values
		 * @returns {Array} Array of field options for dropdown
		 */
		formatFieldsForDropdown(activeWidgetFields) {
			if (!activeWidgetFields || typeof activeWidgetFields !== 'object') {
				return [];
			}

			const fields = [];
			const currentFieldKey = this.fieldKey || this.getCurrentFieldKey();

			// Iterate through all active widget fields
			for (let widgetKey in activeWidgetFields) {
				const widget = activeWidgetFields[widgetKey];

				// Skip the current field being edited to avoid circular references
				if (widgetKey === currentFieldKey) {
					continue;
				}

				// Get field label (prefer label, fallback to widget_key)
				const label =
					widget.label ||
					widget.name ||
					widget.placeholder ||
					widgetKey ||
					'Unnamed Field';

				// Get field type
				const type = widget.type || widget.field_type || 'text';

				// Only include fields that can be used in conditions
				// Exclude fields like conditional-logic itself and non-comparable types
				const excludeTypes = [
					'conditional-logic',
					'button',
					'submit',
					'section',
				];
				if (excludeTypes.includes(type)) {
					continue;
				}

				fields.push({
					value: widgetKey,
					label: label,
					type: type,
				});
			}

			// Sort fields alphabetically by label
			fields.sort((a, b) => {
				return a.label.localeCompare(b.label);
			});

			return fields;
		},

		/**
		 * Get the current field key being edited.
		 * @returns {String|null} Current field key or null
		 */
		getCurrentFieldKey() {
			// Try to get from props
			if (this.fieldKey) {
				return this.fieldKey;
			}

			// Try to get from parent component (Options_Window context)
			let parent = this.$parent;
			let depth = 0; // Prevent infinite loops
			while (parent && depth < 10) {
				// Check if parent is Options_Window and has activeWidget
				if (
					parent.$options &&
					parent.$options.name === 'options-window'
				) {
					if (parent.activeWidget && parent.activeWidget.widget_key) {
						return parent.activeWidget.widget_key;
					}
					if (parent.widget) {
						return parent.widget;
					}
				}

				// Check if parent has widget key or field key directly
				if (parent.widget) {
					return parent.widget;
				}
				if (parent.activeWidget && parent.activeWidget.widget_key) {
					return parent.activeWidget.widget_key;
				}

				parent = parent.$parent;
				depth++;
			}

			return null;
		},

		/**
		 * Get value input component based on selected field type.
		 */
		getValueInputComponent(condition) {
			if (!condition.field) {
				return 'text-field';
			}

			// TODO: Determine field type and return appropriate component
			// For now, return text field
			return 'text-field';
		},

		/**
		 * Check if value input should be hidden based on operator.
		 */
		isValueHidden(operator) {
			const hiddenOperators = ['empty', 'not empty'];
			return hiddenOperators.includes(operator);
		},

		// Translation helper
		__(text, domain) {
			if (
				typeof window.directorist_admin !== 'undefined' &&
				window.directorist_admin.i18n
			) {
				// Use WordPress i18n if available
				return window.directorist_admin.i18n[text] || text;
			}
			return text;
		},
	},
};
