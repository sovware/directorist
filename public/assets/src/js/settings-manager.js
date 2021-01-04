import Vue from 'vue';
import Vuex from 'vuex';
import SlideUpDown from 'vue-slide-up-down';

Vue.use(Vuex);
Vue.component('slide-up-down', SlideUpDown);

Vue.directive('click-outside', {
    priority: 700,
  
    bind () {
        let self  = this;
        this.event = function (event) { 
        console.log('emitting event')
        
            self.vm.$emit( self.expression, event) 
        }
        
        this.el.addEventListener('click', this.stopProp);
        document.body.addEventListener('click',this.event);
    },

    unbind() {
        console.log('unbind');
        
        this.el.removeEventListener('click', this.stopProp);
        document.body.removeEventListener('click',this.event);
    },

    stopProp( event ) { 
        event.stopPropagation()
    }
});

import './global-component';

import store from './vue/store/CPT_Manager_Store';
import settings_manager_component from './vue/components/settings-manager/Settings_Manager.vue';

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
