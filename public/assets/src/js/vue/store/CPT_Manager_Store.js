import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex)

export default new Vuex.Store({
  // state
  state: {
    active_nav_index: 0,
    settings: {},
    fields: {},
    form_fields: {},

    submenu: {
      general: [
        'General',
        'Packages',
        'Other',
      ],
    },
    
  },
  
  // mutations
  mutations: {
    swichNav: ( state, index ) => {
      state.active_nav_index = index;
    },

    updateSettings: ( state, value ) => {
      state.settings = value;
    },

    updateFields: ( state, value ) => {
      state.fields = value;
    },

    updateFormFields: ( state, value ) => {
      state.form_fields = value;
    },

    reorderActiveFieldsItems: ( state, payload ) => {
      const origin_value = state.fields.submission_form_fields.value.groups[ payload.group_index ].fields[ payload.origin_field_index ];

      state.fields.submission_form_fields.value.groups[ payload.group_index ].fields.splice( payload.origin_field_index, 1 );
      const des_ind = ( payload.origin_field_index === 0 ) ? payload.destination_field_index : payload.destination_field_index + 1 ;
      state.fields.submission_form_fields.value.groups[ payload.group_index ].fields.splice( des_ind, 0, origin_value );
    },

    trashActiveFieldItem: ( state, payload ) => {
      const field_key = state.fields.submission_form_fields.value.groups[ payload.group_index ].fields[ payload.field_index ];

      state.fields.submission_form_fields.value.groups[ payload.group_index ].fields.splice( payload.field_index, 1 );
      delete state.fields.submission_form_fields.value.active_fields[ field_key ];
    },

    appendActiveFieldsItem: ( state, payload ) => {
      const field_data = state.form_fields[ payload.appending_from ][ payload.appending_field_key ];

      state.fields.submission_form_fields.value.active_fields[payload.appending_field_key] = { ...field_data };
      state.fields.submission_form_fields.value.groups[ payload.group_index ].fields.splice( payload.field_index + 1, 0 , payload.appending_field_key );
    },

    updateFieldValue: ( state, payload ) => {
      state.fields[ payload.field_key ].value = payload.value;
    },

    updateGeneralSectionData: ( state, payload ) => {
      state.settings.general.submenu.general.sections[ payload.section_key ].fields[ payload.field_key ].value = payload.value;
    },
  },

  getters: {

  }

});
