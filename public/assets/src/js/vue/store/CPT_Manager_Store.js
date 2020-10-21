import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex)

export default new Vuex.Store({
  // state
  state: {
    active_nav_index: 2,
    fields: {},
    layouts: {},
    config: {},
  },
  
  // mutations
  mutations: {
    swichNav: ( state, index ) => {
      state.active_nav_index = index;
    },

    updatelayouts: ( state, value ) => {
      state.layouts = value;
    },

    updateConfig: ( state, value ) => {
      state.config = value;
    },

    updateFields: ( state, value ) => {
      state.fields = value;
    },

    updateFormFields: ( state, value ) => {
      state.form_fields = value;
    },

    updateFieldValue: ( state, payload ) => {
      state.fields[ payload.field_key ].value = payload.value;
    },

    updateGeneralSectionData: ( state, payload ) => {
      state.layouts.general.submenu.general.sections[ payload.section_key ].fields[ payload.field_key ].value = payload.value;
    },
  },

  getters: {

  }

});
