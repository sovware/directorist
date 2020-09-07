import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex)

export default new Vuex.Store({
  // state
  state: {
    active_nav_index: 0,

    general: {
      general: {
        icon: 'fa fa-home',
        singular_name: 'places',
        plural_name: 'place',
        permalink: 'places',
      },
      packages: {
        listing_packages: {
          enable: true,
          packages: [
            {
              id: 1,
              label: 'Basic',
              description: '',
              featured: false,
            },

            {
              id: 2,
              label: 'Advanced',
              description: '',
              featured: true,
            }
          ],
        }
      },

      review: {
        gallery_upload: true,
        star_ratings: true,
        stars_mode: 'full_stars',
        rating_categories: [
          { label: '', key: '', },
          { label: '', key: '', },
          { label: '', key: '', }
        ]
      },

      scheema_markup: {
        '@context': '',
        '@type': '',
        '@id': '',
        'name': '',
        'legalName': '',
      },

      other: {
        expiration_rules: {},
        global_listing_type: false
      },
    },

    submission_form: {
      fields: [
        {
          group: 'General',
          pricing_plans: [
            { id: 1 }
          ],
          fields: [
            {
              label: 'Title',
              key: 'title',
              placeholder: '',
              description: '',
              required: true,
              pricing_plans: [
                { id: 1 }
              ]
            },

            {
              label: 'Description',
              key: 'description',
              placeholder: '',
              description: '',
              required: true,
              pricing_plans: [
                { id: 1 }
              ]
            }
          ]
        }
      ]
    },

    single_page_layout: {},
    listings_page_layout: {},
    search_forms: {},
  },
  
  // mutations
  mutations: {
    swichNav: ( state, index ) => {
      state.active_nav_index = index;
    }
  },

  getters: {
    getPostMetaFields: state => {
      const meta_fields = {
        // general: JSON.stringify( state.general.general ),
        general: JSON.stringify( { data: 'value' } ),
        // has_listing_packages: state.general.packages.listing_packages.enable.toString(),
        // listing_packages: JSON.stringify( state.general.packages.listing_packages.packages ),
        // review_stars_mode: state.general.review.stars_mode,
      };

      return meta_fields;
    }
  }

});
