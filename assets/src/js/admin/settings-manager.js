import Vue from 'vue';
import Vuex from 'vuex';
import SlideUpDown from 'vue-slide-up-down';

Vue.use(Vuex);
Vue.component('slide-up-down', SlideUpDown);


import './vue/global-component';
import store from './vue/store/CPT_Manager_Store'
import settings_manager_component from './vue/apps/settings-manager/Settings_Manager.vue';

const settings_panel_el = document.getElementById( 'atbdp-settings-manager' );
if ( settings_panel_el ) {
    new Vue({
        el:'#atbdp-settings-manager',
        store,
        components: {
            'settings-manager': settings_manager_component
        },

        data() {
            return {
                id: atbdp_settings_manager_data.id,
                fields: atbdp_settings_manager_data.fields,
                layouts: atbdp_settings_manager_data.layouts,
                config: atbdp_settings_manager_data.config,
            }
        }
    });
}
