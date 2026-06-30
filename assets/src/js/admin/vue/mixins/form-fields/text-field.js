import props from './input-field-props.js';

export default {
	mixins: [props],
	model: {
		prop: 'value',
		event: 'update',
	},

	computed: {
		filteredValue() {
			return this.decodeEntity(this.value);
		},

		input_type() {
			const supported_types = {
				'text-field': 'text',
				'number-field': 'number',
				'password-field': 'password',
				'date-field': 'date',
				'hidden-field': 'hidden',
				text: 'text',
				number: 'number',
				password: 'password',
				date: 'date',
				hidden: 'hidden',
			};

			if (typeof supported_types[this.type] !== 'undefined') {
				return supported_types[this.type];
			}

			return 'text';
		},

		formGroupClass() {
			var validation_classes = this.validationLog?.inputErrorClasses
				? this.validationLog.inputErrorClasses
				: {};
			var safe_field_key = String(this.fieldKey).replace(/[^a-zA-Z0-9_-]/g, '-');
			var field_key_class = this.fieldKey
				? `cptm-form-group--${safe_field_key}`
				: '';

			return {
				...validation_classes,
				[field_key_class]: !!field_key_class,
				'cptm-mb-0': 'hidden' === this.input_type ? true : false,
			};
		},

		formControlClass() {
			let class_names = {};

			if (this.input_style && this.input_style.class_names) {
				class_names[this.input_style.class_names] = true;
			}

			return class_names;
		},
	},

	data() {
		return {
			validationLog: {},
		};
	},

	methods: {
		decodeEntity: function (inputStr) {
			var textarea = document.createElement('textarea');
			textarea.innerHTML = inputStr;
			return textarea.value;
		},
	},
};
