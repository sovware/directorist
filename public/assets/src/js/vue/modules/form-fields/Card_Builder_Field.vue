<template>
  <component 
    :is="cardBiulderType"
    :value="value"
    :widgets="widgets"
    :layout="layout"
    @update="$emit( 'update', $event )"
  >
  </component>
</template>

<script>
import Vue from 'vue';

export default {
  name: "card-builder",
  props: {
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
    view: {
      required: false,
      default: 'grid-view',
    },
  },

  computed: {
      cardBiulderType() {
        const card_biulder_types = {
          'grid-view': 'card-builder-grid-view-field',
          'list-view': 'card-builder-list-view-field',
        };

        if ( typeof card_biulder_types[ this.view ] === 'undefined' ) {
          return 'card-builder-grid-view-field';
        }
        
        return card_biulder_types[ this.view ];
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