import Vue from 'vue';
import Vuex from 'vuex';
import SlideUpDown from 'vue-slide-up-down';

Vue.use(Vuex);
Vue.component('slide-up-down', SlideUpDown);

import './vue/global-component';
import store from './vue/store/CPT_Manager_Store'
import cpt_manager_component from './vue/apps/cpt-manager/CPT_Manager.vue';

const cpt_manager_el = document.getElementById('atbdp-cpt-manager');
if (cpt_manager_el) {
    const encodedBuilderData = cpt_manager_el.getAttribute('data-builder-data');
    let builderData = atob(encodedBuilderData);

    try {
        builderData = JSON.parse(builderData);
    } catch (error) {
        builderData = [];
    }

    new Vue({
        el: '#atbdp-cpt-manager',
        store,
        components: {
            'cpt-manager': cpt_manager_component
        },

        data() {
            return {
                id: (typeof builderData.id !== 'undefined') ? builderData.id : 0,
                fields: (typeof builderData.fields !== 'undefined') ? builderData.fields : [],
                layouts: (typeof builderData.layouts !== 'undefined') ? builderData.layouts : [],
                options: (typeof builderData.options !== 'undefined') ? builderData.options : {
                    test: 'asas'
                },
                config: (typeof builderData.config !== 'undefined') ? builderData.config : {},
            }
        }
    });
}

$('img.test').each(function () {
    var $img = $(this),
        imgURL = $img.attr('src'),
        imgID = $img.attr('id');
    $.get(imgURL, function (data) {
        // Get the SVG tag, ignore the rest
        var $svg = $(data).find('svg');
        // Add replaced image's ID to the new SVG
        if (typeof imgID !== 'undefined') {
            $svg = $svg.attr('id', imgID);
        }

        $svg = $svg.removeAttr('xmlns:a');
        $img.replaceWith($svg);
    }, 'xml');
});