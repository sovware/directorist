import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import store from './vue/components/cpt-manager/store';
import cpt_manager_component from './vue/components/cpt-manager/CPT_Manager.vue';

const cpt_manager_el = document.getElementById( 'atbdp-cpt-manager' );
if ( cpt_manager_el ) {
    // Contact Form
    new Vue({
        el:' #atbdp-cpt-manager',
        store,
        components: {
            'cpt-manager': cpt_manager_component
        }
    });
}
