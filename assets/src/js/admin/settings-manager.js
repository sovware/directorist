import Vue from 'vue';
import SlideUpDown from 'vue-slide-up-down';
import Vuex from 'vuex';

Vue.use(Vuex);
Vue.component('slide-up-down', SlideUpDown);

import settings_manager_component from './vue/apps/settings-manager/Settings_Manager.vue';
import './vue/global-component';
import store from './vue/store/CPT_Manager_Store';

const createSettingsPanelRegistry = () => {
	const mutators = [];
	const beforeMountHooks = [];
	const afterMountHooks = [];
	const components = {};

	return {
		registerComponent(name, component) {
			if (typeof name !== 'string' || !name.length || !component) {
				return;
			}

			components[name] = component;
			Vue.component(name, component);
		},
		getComponent(name) {
			return components[name] || Vue.options.components[name] || null;
		},
		getVue() {
			return Vue;
		},
		registerSettingsDataMutator(callback) {
			if (typeof callback === 'function') {
				mutators.push(callback);
			}
		},
		registerBeforeMount(callback) {
			if (typeof callback === 'function') {
				beforeMountHooks.push(callback);
			}
		},
		registerAfterMount(callback) {
			if (typeof callback === 'function') {
				afterMountHooks.push(callback);
			}
		},
		applySettingsDataMutators(settingsData, context = {}) {
			return mutators.reduce((currentData, callback) => {
				const nextData = callback(currentData, context);

				return typeof nextData === 'undefined' ? currentData : nextData;
			}, settingsData);
		},
		runBeforeMount(settingsData, context = {}) {
			beforeMountHooks.forEach((callback) =>
				callback(settingsData, context)
			);
		},
		runAfterMount(app, settingsData, context = {}) {
			afterMountHooks.forEach((callback) =>
				callback(app, settingsData, context)
			);
		},
	};
};

window.DirectoristSettingsPanel =
	window.DirectoristSettingsPanel || createSettingsPanelRegistry();

document.dispatchEvent(
	new CustomEvent('directorist:settings-panel:registry-ready', {
		detail: {
			registry: window.DirectoristSettingsPanel,
			Vue,
		},
	})
);

window.addEventListener('load', () => {
	const settings_panel_el = document.getElementById('atbdp-settings-manager');

	if (settings_panel_el) {
		const encodedBuilderData =
			settings_panel_el.getAttribute('data-builder-data');
		let builderData = atob(encodedBuilderData);

		try {
			builderData = JSON.parse(builderData);
		} catch (error) {
			builderData = [];
		}

		builderData =
			window.DirectoristSettingsPanel.applySettingsDataMutators(
				builderData,
				{
					element: settings_panel_el,
					Vue,
				}
			);

		window.DirectoristSettingsPanel.runBeforeMount(builderData, {
			element: settings_panel_el,
			Vue,
		});

		document.dispatchEvent(
			new CustomEvent('directorist:settings-panel:before-mount', {
				detail: {
					settingsData: builderData,
					element: settings_panel_el,
					Vue,
				},
			})
		);

		const app = new Vue({
			el: '#atbdp-settings-manager',
			store,
			components: {
				'settings-manager': settings_manager_component,
			},

			data() {
				return {
					id:
						typeof builderData.id !== 'undefined'
							? builderData.id
							: 0,
					fields:
						typeof builderData.fields !== 'undefined'
							? builderData.fields
							: [],
					layouts:
						typeof builderData.layouts !== 'undefined'
							? builderData.layouts
							: [],
					config:
						typeof builderData.config !== 'undefined'
							? builderData.config
							: {},
				};
			},
		});

		window.DirectoristSettingsPanel.runAfterMount(app, builderData, {
			element: settings_panel_el,
			Vue,
		});

		document.dispatchEvent(
			new CustomEvent('directorist:settings-panel:mounted', {
				detail: {
					app,
					settingsData: builderData,
					element: settings_panel_el,
					Vue,
				},
			})
		);
	}

	/* Copy shortcodes on click */
	var $ = jQuery;
	$('body').on('click', '.atbdp_shortcodes', function () {
		const $this = $(this);
		const $temp = $('<input>');
		$('body').append($temp);
		$temp.val($(this).text()).select();
		document.execCommand('copy');
		$temp.remove();
		// Check if '.copy-notify' already exists next to the clicked element
		if (!$this.siblings('.copy-notify').length) {
			$this.after(
				"<p class='copy-notify' style='color: #32cc6f; margin-top: 5px;'>Copied to clipboard!</p>"
			);

			let timeout = setTimeout(function () {
				$this.siblings('.copy-notify').fadeOut(300, function () {
					$(this).remove();
				});

				clearTimeout(timeout);
			}, 3000);
		}
	});
});
