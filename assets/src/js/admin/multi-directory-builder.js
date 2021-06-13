import Vue from 'vue';
import Vuex from 'vuex';
import SlideUpDown from 'vue-slide-up-down';

Vue.use(Vuex);
Vue.component('slide-up-down', SlideUpDown);

import './vue/global-component';
import store from './vue/store/CPT_Manager_Store'
import cpt_manager_component from './vue/apps/cpt-manager/CPT_Manager.vue';

const cpt_manager_el = document.getElementById( 'atbdp-cpt-manager' );
if ( cpt_manager_el ) {
    new Vue({
        el:'#atbdp-cpt-manager',
        store,
        components: {
            'cpt-manager': cpt_manager_component
        },

        data() {
            return {
                id: ( typeof cptm_data.id !== 'undefined' ) ? cptm_data.id : 0,
                fields: ( typeof cptm_data.fields !== 'undefined' ) ? cptm_data.fields : [],
                layouts: ( typeof cptm_data.layouts !== 'undefined' ) ? cptm_data.layouts : [],
                options: ( typeof cptm_data.options !== 'undefined' ) ? cptm_data.options : { test: 'asas' },
                config: ( typeof cptm_data.config !== 'undefined' ) ? cptm_data.config : {},
            }
        }
    });
}
