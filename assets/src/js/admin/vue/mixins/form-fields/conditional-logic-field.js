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

		/**
		 * Check if only one rule/group exists (cannot delete)
		 */
		canDeleteRule() {
			return this.localValue.groups.length > 1;
		},

		/**
		 * Check if a specific group can be deleted
		 */
		canDeleteGroup(groupIndex) {
			// Can delete if there's more than one group, or if this group has multiple conditions
			if (this.localValue.groups.length > 1) {
				return true;
			}
			// If only one group exists, can only delete if it has multiple conditions
			const group = this.localValue.groups[groupIndex];
			return group && group.conditions && group.conditions.length > 1;
		},
	},

	data() {
		return {
			localValue: {
				enabled: false,
				action: 'show',
				globalOperator: 'AND',
				groups: [],
			},
			validationLog: {},
			// Stores the field key of the widget that owns this conditional logic,
			// so we can exclude it from the "Select a field" dropdown.
			currentFieldKeyForExclusion: null,
			// Cache for category options
			cachedCategoryOptions: null,
			// Cache for tag options
			cachedTagOptions: null,
			// Cache for location options
			cachedLocationOptions: null,
		};
	},

	methods: {
		setup() {
			// Setup initialization
		},

		/**
		 * Decode HTML entities in a string
		 * @param {string} str - String potentially containing HTML entities
		 * @returns {string} - Decoded string
		 */
		decodeHtmlEntities(str) {
			if (!str || typeof str !== 'string') {
				return str;
			}
			const textarea = document.createElement('textarea');
			textarea.innerHTML = str;
			return textarea.value;
		},

		initValue() {
			const defaultValue = {
				enabled: false,
				action: 'show',
				globalOperator: 'AND',
				groups: [],
			};

			if (!this.value || typeof this.value !== 'object') {
				this.localValue = JSON.parse(JSON.stringify(defaultValue));
				// Store field key when initializing if enabled
				if (this.localValue.enabled) {
					this.storeCurrentFieldKey();
				}
				return;
			}

			this.localValue = {
				enabled:
					typeof this.value.enabled !== 'undefined'
						? this.value.enabled
						: false,
				action: this.value.action || 'show',
				globalOperator: this.value.globalOperator || 'AND',
				groups: Array.isArray(this.value.groups)
					? JSON.parse(JSON.stringify(this.value.groups))
					: [],
			};

			// Validate and fix group structure
			this.localValue.groups = this.localValue.groups.map((group) => {
				if (!Array.isArray(group.conditions)) {
					group.conditions = [];
				}
				if (!group.operator) {
					group.operator = 'AND';
				}
				// Set isGroup flag if not set (for backward compatibility)
				if (typeof group.isGroup === 'undefined') {
					// If has multiple conditions, it's a group; otherwise it's a single rule
					group.isGroup = group.conditions.length > 1;
				}
				// Ensure groups have at least one condition
				if (!group.conditions.length) {
					group.conditions = [this.createEmptyCondition()];
				}
				return group;
			});

			// Auto-add first rule if enabled and no groups exist
			if (
				this.localValue.enabled &&
				this.localValue.groups.length === 0
			) {
				this.localValue.groups.push({
					operator: 'AND',
					conditions: [this.createEmptyCondition()],
					isGroup: false, // Single rule, not a group
				});
			}

			// Store field key when initializing if enabled
			if (this.localValue.enabled && !this.currentFieldKeyForExclusion) {
				this.storeCurrentFieldKey();
			}
		},

		toggleEnabled() {
			this.localValue.enabled = !this.localValue.enabled;
			// Auto-add first rule when enabling conditional logic
			if (
				this.localValue.enabled &&
				this.localValue.groups.length === 0
			) {
				this.addRule();
				// Store the current field key when enabling conditional logic
				this.storeCurrentFieldKey();
			}
			this.updateValue();
		},

		/**
		 * Store the current field key for exclusion from available fields dropdown
		 * This is called when conditional logic is enabled
		 */
		storeCurrentFieldKey() {
			const fieldKey = this.findCurrentFieldKey();
			if (fieldKey) {
				this.currentFieldKeyForExclusion = fieldKey;
			}
		},

		/**
		 * Find the current field key - SIMPLIFIED APPROACH
		 * Extract from fieldId or match activeWidget with availableFields
		 */
		findCurrentFieldKey() {
			const skipKeys = [
				'logic',
				'conditional_logic',
				'conditional-logic',
				'conditionalLogic',
				'submission_form_fields',
				'widgets',
				'fields',
			];

			const shouldSkip = (key) => {
				if (!key) return true;
				const normalized = key.toString().trim().toLowerCase();
				return skipKeys.includes(normalized);
			};

			// PRIORITY 1: Extract from fieldId (format: "section_fieldKey_optionKey" or "fieldKey_optionKey")
			if (this.fieldId && this.fieldId.toString().includes('_')) {
				const parts = this.fieldId.toString().split('_');
				// Try parts from end to beginning (last parts are usually the field key)
				for (let i = parts.length - 2; i >= 0; i--) {
					const possibleKey = parts[i].trim();
					if (possibleKey && !shouldSkip(possibleKey)) {
						// Verify it's in availableFields
						const availableFields = this.availableFields || [];
						const availableFieldKeys = availableFields.map(
							(f) => f.value
						);
						if (availableFieldKeys.includes(possibleKey)) {
							return possibleKey;
						}
					}
				}
			}

			// PRIORITY 2: Get from Options_Window widget prop or activeWidget
			let parent = this.$parent;
			let depth = 0;
			while (parent && depth < 25) {
				if (parent.$options) {
					const componentName = parent.$options.name || '';
					if (
						componentName === 'options-window' ||
						componentName === 'Options_Window'
					) {
						// Try widget prop
						if (parent.widget && !shouldSkip(parent.widget)) {
							const widgetKey = parent.widget.toString().trim();
							const availableFields = this.availableFields || [];
							const availableFieldKeys = availableFields.map(
								(f) => f.value
							);
							if (availableFieldKeys.includes(widgetKey)) {
								return widgetKey;
							}
						}

						// Try activeWidget.widget_key or activeWidget.key
						if (parent.activeWidget) {
							const keysToCheck = [
								parent.activeWidget.widget_key,
								parent.activeWidget.key,
								parent.activeWidget.widget_name,
								parent.activeWidget.name,
							];
							for (let key of keysToCheck) {
								if (key && !shouldSkip(key)) {
									const keyStr = key.toString().trim();
									const availableFields =
										this.availableFields || [];
									const availableFieldKeys =
										availableFields.map((f) => f.value);
									if (availableFieldKeys.includes(keyStr)) {
										return keyStr;
									}
								}
							}
						}
						break;
					}
				}
				parent = parent.$parent;
				depth++;
			}

			return null;
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
				isGroup: false, // false = single rule, true = group container
			};
		},

		createEmptyGroupContainer() {
			return {
				operator: 'AND',
				conditions: [this.createEmptyCondition()],
				isGroup: true, // This is a group container
			};
		},

		createEmptyCondition() {
			return {
				field: '',
				operator: 'is',
				value: '',
			};
		},

		addRule() {
			// Add a single standalone rule (not a group container)
			this.localValue.groups.push({
				operator: 'AND',
				conditions: [this.createEmptyCondition()],
				isGroup: false, // Single rule, not a group
			});
			this.updateValue();
		},

		removeRule(groupIndex) {
			// Cannot remove if only one rule exists
			if (!this.canDeleteRule) {
				return;
			}
			// Remove a single rule (single-condition group)
			if (
				!this.localValue.groups ||
				!this.localValue.groups[groupIndex]
			) {
				return;
			}
			this.localValue.groups.splice(groupIndex, 1);
			this.updateValue();
		},

		addCondition(groupIndex) {
			// Add a condition to an existing group
			if (!this.localValue.groups[groupIndex]) {
				return;
			}
			// Mark as group when adding second condition
			if (
				this.localValue.groups[groupIndex].conditions.length === 1 &&
				!this.localValue.groups[groupIndex].isGroup
			) {
				this.localValue.groups[groupIndex].isGroup = true;
			}
			this.localValue.groups[groupIndex].conditions.push(
				this.createEmptyCondition()
			);
			this.updateValue();
		},

		removeCondition(groupIndex, conditionIndex) {
			if (
				!this.localValue.groups ||
				!this.localValue.groups[groupIndex]
			) {
				return;
			}
			const group = this.localValue.groups[groupIndex];

			// Cannot remove if it's the only rule/group and only condition
			if (group.conditions.length === 1 && !this.canDeleteRule) {
				return;
			}

			// If removing the last condition in a group
			if (group.conditions.length <= 1) {
				// Remove the entire group/rule
				this.localValue.groups.splice(groupIndex, 1);
			} else {
				// Remove the condition
				this.localValue.groups[groupIndex].conditions.splice(
					conditionIndex,
					1
				);
				// If it becomes a single condition, keep it as a group.
				// Once a group, it should always remain rendered as a group container.
			}
			this.updateValue();
		},

		addGroup() {
			// Add a new group container (starts with one condition but is a group)
			this.localValue.groups.push({
				operator: 'AND',
				conditions: [this.createEmptyCondition()],
				isGroup: true, // This is a group container
			});
			this.updateValue();
		},

		removeGroup(groupIndex) {
			// Cannot remove if only one group exists
			if (!this.canDeleteRule) {
				return;
			}
			if (
				!this.localValue.groups ||
				!this.localValue.groups[groupIndex]
			) {
				return;
			}
			this.localValue.groups.splice(groupIndex, 1);
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

			// Iterate through all active widget fields
			// Note: Filtering is now done in the template via filteredAvailableFields computed property
			for (let widgetKey in activeWidgetFields) {
				const widget = activeWidgetFields[widgetKey];

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
				// Note: date, time, and file fields are now included (they use specialized inputs)
				const excludeTypes = [
					'conditional-logic',
					'button',
					'submit',
					'section',
				];
				if (excludeTypes.includes(type)) {
					continue;
				}

				// For custom fields, prefer field_key over widget_key if available
				// This ensures we use the actual field_key used in HTML (e.g., "custom-select")
				// instead of just the widget_key
				const fieldValue = widget.field_key || widgetKey;

				fields.push({
					value: fieldValue, // Use field_key if available, otherwise widget_key
					label: label,
					type: type,
					widget: widget, // Store full widget data for accessing options
				});
			}

			// Sort fields alphabetically by label
			fields.sort((a, b) => {
				return a.label.localeCompare(b.label);
			});

			return fields;
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

		/**
		 * Get field data by field key from availableFields
		 */
		getFieldData(fieldKey) {
			if (!fieldKey || !this.availableFields) {
				return null;
			}
			return (
				this.availableFields.find((f) => f.value === fieldKey) || null
			);
		},

		/**
		 * Get value options for a condition based on selected field type
		 * Returns options array or null if field doesn't need a select dropdown
		 */
		getValueOptions(condition) {
			if (!condition || !condition.field) {
				return null;
			}

			const fieldData = this.getFieldData(condition.field);
			if (!fieldData) {
				return null;
			}

			const fieldType = fieldData.type;
			const widget = fieldData.widget;

			// Handle category field - needs special handling via AJAX or passed data
			if (
				fieldType === 'category' ||
				condition.field === 'category' ||
				condition.field === 'categories'
			) {
				// Return placeholder - will be loaded via AJAX or from passed data
				return this.getCategoryOptions();
			}

			// Handle tag field - needs special handling via AJAX or passed data
			if (
				fieldType === 'tag' ||
				fieldType === 'tags' ||
				condition.field === 'tag' ||
				condition.field === 'tags'
			) {
				return this.getTagOptions();
			}

			// Handle location field - needs special handling via AJAX or passed data
			if (
				fieldType === 'location' ||
				fieldType === 'locations' ||
				condition.field === 'location' ||
				condition.field === 'locations'
			) {
				return this.getLocationOptions();
			}

			// Handle file fields - return "uploaded" option for boolean check
			if (this.isFileField(condition)) {
				return [
					{
						value: 'uploaded',
						label: 'Uploaded',
					},
				];
			}

			// Handle select/radio/checkbox fields - get options from widget
			if (['select', 'radio', 'checkbox'].includes(fieldType) && widget) {
				const options = [];

				// Priority 1: Check widget.value.options (saved field value - actual options data)
				if (
					widget.value &&
					widget.value.options &&
					Array.isArray(widget.value.options)
				) {
					widget.value.options.forEach((option) => {
						if (typeof option === 'object') {
							// Format: { option_value: 'val', option_label: 'Label' } (from multi-fields)
							if (option.option_value !== undefined) {
								options.push({
									value: String(option.option_value || ''),
									label: this.decodeHtmlEntities(
										option.option_label ||
											option.option_value ||
											''
									),
								});
							}
							// Format: { value: 'val', label: 'Label' }
							else if (option.value !== undefined) {
								options.push({
									value: String(option.value || ''),
									label: this.decodeHtmlEntities(
										option.label || option.value || ''
									),
								});
							}
						}
					});
					if (options.length > 0) {
						return options;
					}
				}

				// Priority 2: Check widget.options.value.options (nested in field definition)
				if (
					widget.options &&
					widget.options.options &&
					widget.options.options.value
				) {
					const savedOptions = widget.options.options.value;
					if (Array.isArray(savedOptions)) {
						savedOptions.forEach((option) => {
							if (typeof option === 'object') {
								if (option.option_value !== undefined) {
									options.push({
										value: String(
											option.option_value || ''
										),
										label: this.decodeHtmlEntities(
											option.option_label ||
												option.option_value ||
												''
										),
									});
								} else if (option.value !== undefined) {
									options.push({
										value: String(option.value || ''),
										label: this.decodeHtmlEntities(
											option.label || option.value || ''
										),
									});
								}
							}
						});
						if (options.length > 0) {
							return options;
						}
					}
				}

				// Priority 3: Check widget.options (direct array - less common)
				if (widget.options && Array.isArray(widget.options)) {
					widget.options.forEach((option) => {
						if (typeof option === 'object') {
							if (option.option_value !== undefined) {
								options.push({
									value: String(option.option_value || ''),
									label: this.decodeHtmlEntities(
										option.option_label ||
											option.option_value ||
											''
									),
								});
							} else if (option.value !== undefined) {
								options.push({
									value: String(option.value || ''),
									label: this.decodeHtmlEntities(
										option.label || option.value || ''
									),
								});
							}
						} else if (typeof option === 'string') {
							options.push({
								value: option,
								label: this.decodeHtmlEntities(option),
							});
						}
					});
					if (options.length > 0) {
						return options;
					}
				}

				// No options found
				return null;
			}

			// For other field types, return null to show text input
			return null;
		},

		/**
		 * Check if condition needs a select dropdown (has options)
		 */
		needsSelectInput(condition) {
			// File fields need a selectbox with "uploaded" option
			if (this.isFileField(condition)) {
				return true;
			}
			return this.getValueOptions(condition) !== null;
		},

		/**
		 * Check if field is a date type
		 */
		isDateField(condition) {
			if (!condition || !condition.field) {
				return false;
			}
			const fieldData = this.getFieldData(condition.field);
			if (!fieldData) {
				return false;
			}
			const fieldType = (fieldData.type || '')
				.toString()
				.trim()
				.toLowerCase();
			return fieldType === 'date';
		},

		/**
		 * Check if field is a time type
		 */
		isTimeField(condition) {
			if (!condition || !condition.field) {
				return false;
			}
			const fieldData = this.getFieldData(condition.field);
			if (!fieldData) {
				return false;
			}
			const fieldType = (fieldData.type || '')
				.toString()
				.trim()
				.toLowerCase();
			return fieldType === 'time';
		},

		/**
		 * Check if field is a file type
		 */
		isFileField(condition) {
			if (!condition || !condition.field) {
				return false;
			}
			const fieldValue = (condition.field || '')
				.toString()
				.trim()
				.toLowerCase();

			// Check by field key (listing_img, image_upload)
			if (fieldValue === 'listing_img' || fieldValue === 'image_upload') {
				return true;
			}

			const fieldData = this.getFieldData(condition.field);
			if (!fieldData) {
				return false;
			}
			const fieldType = (fieldData.type || '')
				.toString()
				.trim()
				.toLowerCase();
			return fieldType === 'file' || fieldType === 'file_upload';
		},

		/**
		 * Get listing type ID from Vue context
		 */
		getListingTypeId() {
			// Try to get from URL parameter
			const urlParams = new URLSearchParams(window.location.search);
			const listingTypeId = urlParams.get('listing_type_id');
			if (listingTypeId) {
				return listingTypeId;
			}

			// Try to get from parent components
			let parent = this.$parent;
			let depth = 0;
			while (parent && depth < 25) {
				if (parent.listing_type_id) {
					return parent.listing_type_id;
				}
				parent = parent.$parent;
				depth++;
			}

			// Try to get from root
			if (this.$root && this.$root.listing_type_id) {
				return this.$root.listing_type_id;
			}

			return null;
		},

		/**
		 * Get category options for the current directory type
		 * This will be populated from available data or needs AJAX call
		 */
		getCategoryOptions() {
			// Return cached options if available
			if (this.cachedCategoryOptions) {
				return this.cachedCategoryOptions;
			}

			const options = [];

			// Method 1: Try to get from availableFields if category field exists
			const categoryField = this.availableFields.find(
				(f) => f.value === 'category' || f.value === 'categories'
			);
			if (
				categoryField &&
				categoryField.widget &&
				categoryField.widget.options
			) {
				// If category field has options stored
				if (Array.isArray(categoryField.widget.options)) {
					categoryField.widget.options.forEach((option) => {
						if (typeof option === 'object') {
							options.push({
								value:
									option.value ||
									option.id ||
									option.term_id ||
									'',
								label: this.decodeHtmlEntities(
									option.label ||
										option.name ||
										option.text ||
										''
								),
							});
						}
					});
				}
				if (options.length > 0) {
					this.cachedCategoryOptions = options;
					return options;
				}
			}

			// Method 2: Try to get from form builder context (categories might be passed)
			// This would need to be implemented based on how categories are stored
			if (this.$store && this.$store.state.categories) {
				const categories = this.$store.state.categories;
				if (Array.isArray(categories)) {
					categories.forEach((cat) => {
						options.push({
							value: cat.id || cat.term_id || cat.value || '',
							label: cat.name || cat.label || cat.text || '',
						});
					});
					if (options.length > 0) {
						this.cachedCategoryOptions = options;
						return options;
					}
				}
			}

			// Method 3: Make AJAX call to fetch categories
			// This will fetch categories for the current directory type
			const listingTypeId = this.getListingTypeId();
			if (
				listingTypeId &&
				typeof jQuery !== 'undefined' &&
				!this.cachedCategoryOptions
			) {
				// Fetch categories via AJAX (only if not cached)
				const self = this;
				jQuery.ajax({
					url:
						typeof directorist !== 'undefined' &&
						directorist.ajaxurl
							? directorist.ajaxurl
							: window.ajaxurl || '',
					type: 'POST',
					data: {
						action: 'directorist_get_category_options',
						listing_type_id: listingTypeId,
						directorist_nonce:
							typeof directorist !== 'undefined' &&
							directorist.directorist_nonce
								? directorist.directorist_nonce
								: '',
					},
					success: function (response) {
						if (
							response.success &&
							response.data &&
							Array.isArray(response.data)
						) {
							const fetchedOptions = response.data.map((cat) => ({
								value: String(
									cat.id || cat.term_id || cat.value || ''
								),
								label: self.decodeHtmlEntities(
									cat.name || cat.label || cat.text || ''
								),
							}));
							self.cachedCategoryOptions = fetchedOptions;
							// Force Vue update
							self.$forceUpdate();
						}
					},
					error: function () {
						console.warn(
							'Failed to fetch category options for conditional logic'
						);
					},
				});
			}

			// Return empty array for now - will be populated via AJAX if needed
			return [];
		},

		/**
		 * Get tag options for the current directory type
		 * Similar to getCategoryOptions() but for tags
		 */
		getTagOptions() {
			// Return cached options if available
			if (this.cachedTagOptions) {
				return this.cachedTagOptions;
			}

			const options = [];

			// Method 1: Try to get from availableFields if tag field exists
			const tagField = this.availableFields.find(
				(f) => f.value === 'tag' || f.value === 'tags'
			);
			if (tagField && tagField.widget && tagField.widget.options) {
				if (Array.isArray(tagField.widget.options)) {
					tagField.widget.options.forEach((option) => {
						if (typeof option === 'object') {
							options.push({
								value:
									option.value ||
									option.id ||
									option.term_id ||
									'',
								label: this.decodeHtmlEntities(
									option.label ||
										option.name ||
										option.text ||
										''
								),
							});
						}
					});
				}
				if (options.length > 0) {
					this.cachedTagOptions = options;
					return options;
				}
			}

			// Method 2: Try to get from Vuex store
			if (this.$store && this.$store.state.tags) {
				const tags = this.$store.state.tags;
				if (Array.isArray(tags)) {
					tags.forEach((tag) => {
						options.push({
							value: tag.id || tag.term_id || tag.value || '',
							label: this.decodeHtmlEntities(
								tag.name || tag.label || tag.text || ''
							),
						});
					});
					if (options.length > 0) {
						this.cachedTagOptions = options;
						return options;
					}
				}
			}

			// Method 3: Make AJAX call to fetch tags
			const listingTypeId = this.getListingTypeId();
			if (
				listingTypeId &&
				typeof jQuery !== 'undefined' &&
				!this.cachedTagOptions
			) {
				const self = this;
				jQuery.ajax({
					url:
						typeof directorist !== 'undefined' &&
						directorist.ajaxurl
							? directorist.ajaxurl
							: window.ajaxurl || '',
					type: 'POST',
					data: {
						action: 'directorist_get_tag_options',
						listing_type_id: listingTypeId,
						directorist_nonce:
							typeof directorist !== 'undefined' &&
							directorist.directorist_nonce
								? directorist.directorist_nonce
								: '',
					},
					success: function (response) {
						if (
							response.success &&
							response.data &&
							Array.isArray(response.data)
						) {
							// For tags, use name as value since tag field stores names as option values
							const fetchedOptions = response.data.map((tag) => ({
								value: String(
									tag.name ||
										tag.label ||
										tag.text ||
										tag.id ||
										tag.term_id ||
										''
								), // Use name as value for tags
								label: tag.name || tag.label || tag.text || '',
							}));
							self.cachedTagOptions = fetchedOptions;
							self.$forceUpdate();
						}
					},
					error: function () {
						console.warn(
							'Failed to fetch tag options for conditional logic'
						);
					},
				});
			}

			return [];
		},

		/**
		 * Get location options for the current directory type
		 * Similar to getCategoryOptions() but for locations
		 */
		getLocationOptions() {
			// Return cached options if available
			if (this.cachedLocationOptions) {
				return this.cachedLocationOptions;
			}

			const options = [];

			// Method 1: Try to get from availableFields if location field exists
			const locationField = this.availableFields.find(
				(f) => f.value === 'location' || f.value === 'locations'
			);
			if (
				locationField &&
				locationField.widget &&
				locationField.widget.options
			) {
				if (Array.isArray(locationField.widget.options)) {
					locationField.widget.options.forEach((option) => {
						if (typeof option === 'object') {
							options.push({
								value:
									option.value ||
									option.id ||
									option.term_id ||
									'',
								label: this.decodeHtmlEntities(
									option.label ||
										option.name ||
										option.text ||
										''
								),
							});
						}
					});
				}
				if (options.length > 0) {
					this.cachedLocationOptions = options;
					return options;
				}
			}

			// Method 2: Try to get from Vuex store
			if (this.$store && this.$store.state.locations) {
				const locations = this.$store.state.locations;
				if (Array.isArray(locations)) {
					locations.forEach((location) => {
						options.push({
							value:
								location.id ||
								location.term_id ||
								location.value ||
								'',
							label: this.decodeHtmlEntities(
								location.name ||
									location.label ||
									location.text ||
									''
							),
						});
					});
					if (options.length > 0) {
						this.cachedLocationOptions = options;
						return options;
					}
				}
			}

			// Method 3: Make AJAX call to fetch locations
			const listingTypeId = this.getListingTypeId();
			if (
				listingTypeId &&
				typeof jQuery !== 'undefined' &&
				!this.cachedLocationOptions
			) {
				const self = this;
				jQuery.ajax({
					url:
						typeof directorist !== 'undefined' &&
						directorist.ajaxurl
							? directorist.ajaxurl
							: window.ajaxurl || '',
					type: 'POST',
					data: {
						action: 'directorist_get_location_options',
						listing_type_id: listingTypeId,
						directorist_nonce:
							typeof directorist !== 'undefined' &&
							directorist.directorist_nonce
								? directorist.directorist_nonce
								: '',
					},
					success: function (response) {
						if (
							response.success &&
							response.data &&
							Array.isArray(response.data)
						) {
							const fetchedOptions = response.data.map(
								(location) => ({
									value: String(
										location.id ||
											location.term_id ||
											location.value ||
											''
									),
									label: self.decodeHtmlEntities(
										location.name ||
											location.label ||
											location.text ||
											''
									),
								})
							);
							self.cachedLocationOptions = fetchedOptions;
							self.$forceUpdate();
						}
					},
					error: function () {
						console.warn(
							'Failed to fetch location options for conditional logic'
						);
					},
				});
			}

			return [];
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
