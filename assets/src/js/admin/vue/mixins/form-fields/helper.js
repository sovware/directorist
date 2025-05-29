import { mapState } from 'vuex';
import helper from './../helpers';

export default {
	mixins: [helper],

	computed: {
		...mapState({
			config: 'config',
		}),

		canChange() {
			let is_changeable = false;

			if (this.changeIf) {
				let change_if_condition = this.changeIf;
				let change_if_cond = this.checkChangeIfCondition({
					condition: change_if_condition,
					fieldKey: this.fieldKey,
				});

				is_changeable = change_if_cond.status;
			}

			this.$emit('is-changeable', is_changeable);
			return is_changeable;
		},

		canShow() {
			let is_changeable = true;

			if (this.showIf || this.show_if) {
				let show_if_condition = this.showIf
					? this.showIf
					: this.show_if;
				let show_if_cond = this.checkShowIfCondition({
					condition: show_if_condition,
					root: this.root,
				});

				is_changeable = show_if_cond.status;
			}

			this.$emit('is-changeable', is_changeable);
			return is_changeable;
		},
	},

	methods: {
		getTheTheme(field) {
			var the_theme = 'default';

			if (this.config && this.config.fields_theme) {
				the_theme = this.config.fields_theme;
			}

			if (this.theme && 'default' !== this.theme) {
				the_theme = this.theme;
			}

			return field + '-theme-' + the_theme;
		},
	},
};
