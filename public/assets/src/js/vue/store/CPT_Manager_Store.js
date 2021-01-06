import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex)

export default new Vuex.Store({
  // state
  state: {
    active_nav_index: 0,
    fields: {},
    layouts: {},
    options: {},
    config: {},
    metaKeys: {},
    deprecatedMetaKeys: [],
  },
  
  // mutations
  mutations: {
    swichNav: ( state, index ) => {
      state.active_nav_index = index;
    },

    setMetaKey: ( state, payload ) => {
      Vue.set( state.metaKeys, payload.key, payload.value );
    },

    removeMetaKey: ( state, payload ) => {
      Vue.delete( state.metaKeys, payload.key );
    },

    updateOptionsField: ( state, payload ) => {
      state.options[ payload.field ].value = payload.value;
    },

    updateFields: ( state, value ) => {
      state.fields = value;
    },

    updatelayouts: ( state, value ) => {
      state.layouts = value;
    },

    updateOptions: ( state, value ) => {
      state.options = value;
    },

    updateConfig: ( state, value ) => {
      state.config = value;
    },

    updateFormFields: ( state, value ) => {
      state.form_fields = value;
    },

    updateFieldValue: ( state, payload ) => {
      Vue.set( state.fields[ payload.field_key ], 'value' , payload.value );
    },

    updateGeneralSectionData: ( state, payload ) => {
      state.layouts.general.submenu.general.sections[ payload.section_key ].fields[ payload.field_key ].value = payload.value;
    },
  },

  getters: {
    getFieldsValue: state => {
      const maybeJSON = function ( data ) {
          let value = ( typeof data === 'undefined' ) ? '' : data;
          if ( 'object' === typeof value ) {
              value = JSON.stringify( value );
          }

          return value;
      };

      let form_data = {};
      for ( let field in state.fields ) {
          form_data[ field ] = maybeJSON( state.fields[ field ].value )
      }

      return form_data;
    }
  }

});
