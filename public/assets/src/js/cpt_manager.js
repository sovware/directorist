import Vue from 'vue';
import Vuex from 'vuex';
import SlideUpDown from 'vue-slide-up-down';

Vue.use(Vuex);
Vue.component('slide-up-down', SlideUpDown);

import './global-component';

import store from './vue/store/CPT_Manager_Store';
import cpt_manager_component from './vue/components/cpt-manager/CPT_Manager.vue';

const cpt_manager_el = document.getElementById( 'atbdp-cpt-manager' );
if ( cpt_manager_el ) {
    new Vue({
        el:'#atbdp-cpt-manager',
        store,
        components: {
            'cpt-manager': cpt_manager_component
        }
    });
}
