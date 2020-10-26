<template>
  <component 
    :is="cardBiulderTemplate"
    :field-id="fieldId"
    :value="value"
    :widgets="widgets"
    :layout="layout"
    :card-options="cardOptions"
    @update="$emit( 'update', $event )"
  >
  </component>
</template>

<script>
import Vue from 'vue';

export default {
  name: "card-builder",
  props: {
    fieldId: {
      required: false,
      default: '',
    },
    value: {
      required: false,
      default: null,
    },
    widgets: {
      required: false,
      default: null,
    },
    layout: {
      required: false,
      default: null,
    },
    cardOptions: {
      required: false,
      default: null,
    },
    template: {
      required: false,
      default: 'grid-view',
    },
  },

  computed: {
      cardBiulderTemplate() {
        const card_biulder_templates = {
          'grid-view': 'card-builder-grid-view-field',
          'list-view': 'card-builder-list-view-field',
          'listing-header': 'card-builder-listing-header-field',
        };

        if ( typeof card_biulder_templates[ this.template ] === 'undefined' ) {
          return 'card-builder-grid-view-field';
        }
        
        return card_biulder_templates[ this.template ];
      }
  },

  data() {
    return {
      
    }
  },

  methods: {

  },
};
</script>