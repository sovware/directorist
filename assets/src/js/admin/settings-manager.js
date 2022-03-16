import Vue from 'vue';
import Vuex from 'vuex';
import SlideUpDown from 'vue-slide-up-down';

Vue.use(Vuex);
Vue.component('slide-up-down', SlideUpDown);

import './vue/global-component';
import store from './vue/store/CPT_Manager_Store'
import settings_manager_component from './vue/apps/settings-manager/Settings_Manager.vue';

window.addEventListener('DOMContentLoaded', () => {
    const settings_panel_el = document.getElementById( 'atbdp-settings-manager' );
    
    if ( settings_panel_el ) {
        const encodedBuilderData = settings_panel_el.getAttribute( 'data-builder-data' );
        let builderData = atob( encodedBuilderData );

        try {
            builderData = JSON.parse( builderData );
        } catch ( error ) {
            builderData = [];
        }

        new Vue({
            el:'#atbdp-settings-manager',
            store,
            components: {
                'settings-manager': settings_manager_component
            },

            data() {
                return {
                    id: builderData.id,
                    fields: builderData.fields,
                    layouts: builderData.layouts,
                    config: builderData.config,
                }
            }
        });
    }
});

