import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex)

export default new Vuex.Store({
  // state
  state: {
    active_nav_index: 0,
    settings: {},

    submenu: {
      general: [
        'General',
        'Packages',
        'Other',
      ],
    },

    fotter_actions: {
      prev: { show: true },
      save: { show: true },
      next: { show: true },
    }
  },
  
  // mutations
  mutations: {
    swichNav: ( state, index ) => {
      state.active_nav_index = index;
    },

    updateSettings: ( state, value ) => {
      state.settings = value;
    },

    updateGeneralSectionData: ( state, payload ) => {
      state.settings.general.submenu.general.sections[ payload.section_key ].fields[ payload.field_key ].value = payload.value;
    },
    updatePackagesSectionData: ( state, payload ) => {
      state.settings.general.submenu.packages.sections[ payload.section_key ].fields[ payload.field_key ].value = payload.value;
    },
    updateOtherSectionData: ( state, payload ) => {
      state.settings.general.submenu.other.sections[ payload.section_key ].fields[ payload.field_key ].value = payload.value;
    },
  },

  getters: {
    
  }

});
