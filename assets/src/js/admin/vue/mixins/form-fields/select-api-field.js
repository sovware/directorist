import { mapState } from 'vuex';
import helpers from './../helpers';
import props from './input-field-props.js';

export default {
	mixins: [props, helpers],
	model: {
		prop: 'value',
		event: 'update',
	},

	props: {
		apiPath: {
			type: String,
			required: true,
			default: '',
		},
		apiMethod: {
			type: String,
			default: 'GET',
		},
		apiParams: {
			type: Object,
			default: () => ({}),
		},
		resyncLabel: {
			type: String,
			default: 'Reload',
		},
		showResyncButton: {
			type: Boolean,
			default: true,
		},
	},

	created() {
		this.setup();
		this.fetchOptions();
	},

	computed: {
		...mapState({
			fields: 'fields',
		}),

		theDefaultOption() {
			if (this.defaultOption && typeof this.defaultOption === 'object') {
				return this.defaultOption;
			}

			return { value: '', label: 'Select...' };
		},

		theCurrentOptionLabel() {
			if (this.isLoading) {
				return 'Loading...';
			}

			if (this.hasError) {
				return 'Error loading options';
			}

			if (!this.optionsInObject) {
				return '';
			}
			if (typeof this.optionsInObject[this.value] === 'undefined') {
				return this.theDefaultOption.value == this.value &&
					this.theDefaultOption.label
					? this.theDefaultOption.label
					: '';
			}

			return this.optionsInObject[this.value];
		},

		theOptions() {
			if (
				!this.fetchedOptions ||
				typeof this.fetchedOptions !== 'object'
			) {
				return this.defaultOption ? [this.defaultOption] : [];
			}

			return this.parseOptions(this.fetchedOptions);
		},

		formGroupClass() {
			const validation_classes = this.validationLog.inputErrorClasses
				? this.validationLog.inputErrorClasses
				: {};

			return {
				...validation_classes,
				['--loading']: this.isLoading,
				['--error']: this.hasError,
			};
		},
	},

	data() {
		return {
			local_value_ms: [],
			optionsInObject: {},
			show_option_modal: false,
			clickEvent: null,
			validationLog: {},
			fetchedOptions: [],
			isLoading: false,
			hasError: false,
			errorMessage: '',
		};
	},

	methods: {
		setup() {
			if (this.defaultOption || typeof this.defaultOption === 'object') {
				this.default_option = this.defaultOption;
			}

			const self = this;
			document.addEventListener('click', function () {
				self.show_option_modal = false;
			});
		},

		async fetchOptions() {
			if (!this.apiPath) {
				console.error('API path is required for select-api-field');
				return;
			}

			this.isLoading = true;
			this.hasError = false;
			this.errorMessage = '';

			try {
				const response = await this.makeApiRequest();

				if (response) {
					const parsedOptions = this.parseApiResponse(response);
					this.fetchedOptions = parsedOptions;
					this.optionsInObject = this.convertOptionsToObject();

					if (!this.valueIsValid(this.value)) {
						this.$emit('update', '');
					}
				} else {
					throw new Error('Invalid response format');
				}
			} catch (error) {
				this.hasError = true;
				this.errorMessage = error.message || 'Failed to fetch options';
				console.error('Error fetching options:', error);
			} finally {
				this.isLoading = false;
			}
		},

		async makeApiRequest() {
			const options = {
				method: this.apiMethod,
				headers: {
					'Content-Type': 'application/json',
				},
			};

			const params = { ...this.apiParams };

			// Add params for POST requests
			if (this.apiMethod === 'POST' && Object.keys(params).length > 0) {
				options.body = JSON.stringify(params);
			}

			// Remove trailing slash from URL
			let url = this.apiPath.replace(/\/$/, '');

			// Add params to URL for GET requests
			if (this.apiMethod === 'GET' && Object.keys(params).length > 0) {
				const urlParams = new URLSearchParams(params);
				url = `${url}?${urlParams.toString()}`;
			}

			const response = await fetch(url, options);

			if (!response.ok) {
				throw new Error(`HTTP error! status: ${response.status}`);
			}

			const data = await response.json();
			return data;
		},

		parseApiResponse(response) {
			const data = response;

			// Handle different API response formats
			// WordPress REST API, custom APIs, etc.
			if (Array.isArray(data)) {
				return data.map((item) => {
					// Determine the value (prefer 'value', then 'id')
					const value =
						item.value !== undefined
							? item.value
							: item.id !== undefined
								? item.id
								: '';

					// Determine the label with priority:
					// 1. Direct 'label' property
					// 2. WordPress 'title.rendered' (for posts/pages)
					// 3. Direct 'name' property (for categories/tags)
					// 4. Fallback to value or id
					let label = '';

					if (item.label !== undefined) {
						label = item.label;
					} else if (
						item.title &&
						typeof item.title === 'object' &&
						item.title.rendered
					) {
						// WordPress REST API format (posts, pages, custom post types)
						label = item.title.rendered;
					} else if (item.name !== undefined) {
						// WordPress taxonomies (categories, tags) or simple name property
						label = item.name;
					} else {
						// Fallback
						label = item.value || item.id || '';
					}

					return {
						value: String(value),
						label: String(label),
					};
				});
			}

			// If data is an object (key-value pairs), convert to array
			if (typeof data === 'object' && data !== null) {
				return Object.keys(data).map((key) => ({
					value: String(key),
					label: String(data[key]),
				}));
			}

			return [];
		},

		handleResync() {
			this.fetchOptions();
		},

		update_value(value) {
			this.$emit('update', value);
		},

		updateOption(value) {
			this.update_value(value);
			this.show_option_modal = false;
		},

		toggleTheOptionModal() {
			if (this.isLoading || this.hasError) {
				return;
			}

			let self = this;

			if (this.show_option_modal) {
				this.show_option_modal = false;
			} else {
				this.show_option_modal = true;

				setTimeout(function () {
					self.show_option_modal = true;
				}, 0);
			}
		},

		valueIsValid(value) {
			return this.theOptions
				.map((item) => item.value)
				.includes(`${value}`);
		},

		parseOptions(options) {
			return options.map((item) => ({
				...item,
				value: typeof item.value !== 'undefined' ? `${item.value}` : '',
			}));
		},

		convertOptionsToObject() {
			if (!(this.theOptions && Array.isArray(this.theOptions))) {
				return null;
			}

			let option_object = {};
			for (let option in this.theOptions) {
				if (typeof this.theOptions[option].value === 'undefined') {
					continue;
				}

				let label = this.theOptions[option].label
					? this.theOptions[option].label
					: '';
				option_object[this.theOptions[option].value] = label;
			}

			return option_object;
		},
	},
};
