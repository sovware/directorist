import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex)

export default new Vuex.Store({
  // state
  state: {
    active_nav_index: 0,
    header_navigation: [
      {
        label: 'General',
        icon_class: 'fa fa-map-marker',
      },
      {
        label: 'Submission Form',
        icon_class: 'fa fa-bars',
      },
      {
        label: 'Single Page Layout',
        icon_class: 'fa fa-wpforms',
      },
      {
        label: 'Listings Page Layout',
        icon_class: 'fa fa-picture-o',
      },
      {
        label: 'Search Forms',
        icon_class: 'fa fa-search',
      },
    ]
  },
  
  // mutations
  mutations: {
    swichNav: ( state, index ) => {
      state.active_nav_index = index;
    }
  }
})
