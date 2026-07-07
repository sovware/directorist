import InputColorPicker from 'vue-native-color-picker';
import props from './input-field-props.js';

export default {
	mixins: [props],
	components: {
		'v-input-colorpicker': InputColorPicker,
	},
	model: {
		prop: 'value',
		event: 'input',
	},

	created() {
		if (typeof this.value !== 'string') {
			return;
		}

		this.local_value = this.value;
		this.color_value_input = this.value;
	},

	watch: {
		local_value(value) {
			this.color_value_input = value;
			this.$emit('update', value);
		},
	},

	computed: {
		formGroupClass() {
			var validation_classes = this.validationLog.inputErrorClasses
				? this.validationLog.inputErrorClasses
				: {};

			return {
				...validation_classes,
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
			local_value: '#000000',
			color_value_input: '#000000',
			validationLog: {},
		};
	},

	methods: {
		normalizeColorValue(value) {
			let color_value = String(value).trim();

			if (!color_value.length) {
				return '';
			}

			if ('#' !== color_value.charAt(0)) {
				color_value = '#' + color_value;
			}

			if (/^#[0-9a-fA-F]{3}$/.test(color_value)) {
				return (
					'#' +
					color_value
						.slice(1)
						.split('')
						.map((character) => character + character)
						.join('')
						.toLowerCase()
				);
			}

			if (/^#[0-9a-fA-F]{6}$/.test(color_value)) {
				return color_value.toLowerCase();
			}

			return '';
		},

		updateColorValueInput(value) {
			this.color_value_input = value;

			const normalized_value = this.normalizeColorValue(value);

			if (normalized_value) {
				this.local_value = normalized_value;
			}
		},

		resetColorValueInput() {
			this.color_value_input = this.local_value;
		},
	},
};
