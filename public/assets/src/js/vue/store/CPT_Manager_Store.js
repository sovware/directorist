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
        'Preview Image',
        'Packages',
        'Other',
      ],
    },

    active_fields_ref: {}
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

    moveActiveFieldsItems: ( state, payload ) => {
      const origin_value = state.fields.submission_form_fields.value.groups[ payload.origin_group_index ].fields[ payload.origin_field_index ];

      // Remove from origin group
      state.fields.submission_form_fields.value.groups[ payload.origin_group_index ].fields.splice( payload.origin_field_index, 1 );

      // Insert to destination group
      // const des_ind = ( payload.origin_field_index === 0 ) ? payload.destination_field_index : payload.destination_field_index + 1 ;
      const des_ind = payload.destination_field_index + 1 ;
      state.fields.submission_form_fields.value.groups[ payload.destination_group_index ].fields.splice( des_ind, 0, origin_value );
    },

    trashActiveFieldItem: ( state, payload ) => {
      const field_key = state.fields.submission_form_fields.value.groups[ payload.group_index ].fields[ payload.field_index ];

      state.fields.submission_form_fields.value.groups[ payload.group_index ].fields.splice( payload.field_index, 1 );
      delete state.fields.submission_form_fields.value.active_fields[ field_key ];
    },

    updateActiveFieldsOptionData( state, payload ) {
      state.fields.submission_form_fields.value.active_fields[ payload.field_key ].options[ payload.option_key ].value = payload.value;
    },

    insertActiveFieldsItem: ( state, payload ) => {
      let inserting_field_key = payload.inserting_field_key;

      if ( typeof state.active_fields_ref[ payload.inserting_field_key ] === 'undefined' ) {
        state.active_fields_ref[ payload.inserting_field_key ] = [];
      }

      if ( ! state.active_fields_ref[ payload.inserting_field_key ].length ) {
        state.active_fields_ref[ payload.inserting_field_key ].push( payload.inserting_field_key );
      } else {
        let new_key = payload.inserting_field_key + '_' + ( state.active_fields_ref[ payload.inserting_field_key ].length + 1 );
        state.active_fields_ref[ inserting_field_key ].push( new_key );
        inserting_field_key = new_key;
      }

      const field_data = state.form_fields[ payload.inserting_from ][ payload.inserting_field_key ];

      state.fields.submission_form_fields.value.active_fields[ inserting_field_key ] = { ...field_data };
      
      if ( typeof payload.destination_field_index !== 'undefined' ) {
        state.fields.submission_form_fields.value.groups[ payload.destination_group_index ].fields.splice( payload.destination_field_index + 1, 0 , inserting_field_key );
      } else {
        state.fields.submission_form_fields.value.groups[ payload.destination_group_index ].fields.push( inserting_field_key );
      }
    },

    addNewActiveFieldSection: state => {
      state.fields.submission_form_fields.value.groups.push( { label: 'New Section', fields: [] } );
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
