import Vue from 'vue';
import SlideUpDown from 'vue-slide-up-down';
import Vuex from 'vuex';

Vue.use(Vuex);
Vue.component('slide-up-down', SlideUpDown);

import cpt_manager_component from './vue/apps/cpt-manager/CPT_Manager.vue';
import './vue/global-component';
import store from './vue/store/CPT_Manager_Store';

const createBuilderRegistry = () => {
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
		registerBuilderDataMutator(callback) {
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
		applyBuilderDataMutators(builderData, context = {}) {
			return mutators.reduce((currentData, callback) => {
				const nextData = callback(currentData, context);

				return typeof nextData === 'undefined' ? currentData : nextData;
			}, builderData);
		},
		runBeforeMount(builderData, context = {}) {
			beforeMountHooks.forEach((callback) => callback(builderData, context));
		},
		runAfterMount(app, builderData, context = {}) {
			afterMountHooks.forEach((callback) =>
				callback(app, builderData, context)
			);
		},
	};
};

window.DirectoristBuilder =
	window.DirectoristBuilder || createBuilderRegistry();

document.dispatchEvent(
	new CustomEvent('directorist:builder:registry-ready', {
		detail: {
			registry: window.DirectoristBuilder,
			Vue,
		},
	})
);

window.addEventListener('load', () => {
	const cpt_manager_el = document.getElementById('atbdp-cpt-manager');

	if (cpt_manager_el) {
		const encodedBuilderData =
			cpt_manager_el.getAttribute('data-builder-data');
		let builderData = atob(encodedBuilderData);

		try {
			builderData = JSON.parse(builderData);
		} catch (error) {
			builderData = [];
		}

		builderData = window.DirectoristBuilder.applyBuilderDataMutators(
			builderData,
			{
				element: cpt_manager_el,
				Vue,
			}
		);

		window.DirectoristBuilder.runBeforeMount(builderData, {
			element: cpt_manager_el,
			Vue,
		});

		document.dispatchEvent(
			new CustomEvent('directorist:builder:before-mount', {
				detail: {
					builderData,
					element: cpt_manager_el,
					Vue,
				},
			})
		);

		const app = new Vue({
			el: '#atbdp-cpt-manager',
			store,
			components: {
				'cpt-manager': cpt_manager_component,
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
					options:
						typeof builderData.options !== 'undefined'
							? builderData.options
							: { test: 'asas' },
					config:
						typeof builderData.config !== 'undefined'
							? builderData.config
							: {},
				};
			},
		});

		window.DirectoristBuilder.runAfterMount(app, builderData, {
			element: cpt_manager_el,
			Vue,
		});

		document.dispatchEvent(
			new CustomEvent('directorist:builder:mounted', {
				detail: {
					app,
					builderData,
					element: cpt_manager_el,
					Vue,
				},
			})
		);
	}
});
