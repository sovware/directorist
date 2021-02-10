<template>
  <div class="setting-left-sibebar">
    <ul class="settings-nav">
      <li class="settings-nav__item" :class="{ active: meue_item.active }" v-for="( meue_item, menu_key ) in menu" :key="menu_key">
        
        <a href="#" class="settings-nav__item__link" :class="{ ['nav-has-dropdwon']: meue_item.submenu }" @click.prevent="swichToNav({ menu_key }, $event)">
          <span class="settings-nav__item__icon" v-if="meue_item.icon" v-html="meue_item.icon"></span> 
          {{ meue_item.label }} <span class="drop-toggle-caret" v-if="meue_item.submenu"></span>
        </a>
        
        <ul v-if="meue_item.submenu">
            <li v-for="( submeue_item, submenu_key ) in meue_item.submenu" :key="submenu_key">
              <a href="#" :class="{ active: submeue_item.active }" @click.prevent="swichToNav({ menu_key, submenu_key }, $event)">
                <span class="settings-nav__item__icon" v-if="submeue_item.icon" v-html="submeue_item.icon"></span> 
                {{ submeue_item.label }}
              </a>
            </li>
        </ul>
      </li> 
    </ul>
  </div>
</template>

<script>
export default {
  name: "sidebar-navigation",
  props: [ 'menu' ],

  // computed
  computed: {

  },

  data() {
    return {
      navigation: {
        general: {
          label: 'General',
          icon: 'fa fa-home',
          link: '#',
          active: true,
          submenu: {
            general_settings: {
              label: 'General Setttings',
              icon: 'fa fa-home',
              active: true,
            }
          }
        },
        users: {
          label: 'Users',
          icon: 'fa fa-home',
          link: '#',
          active: true,
          submenu: {
            users_settings: {
              label: 'Users Setttings',
              icon: 'fa fa-home',
              link: '#',
              active: true,
            }
          }
        },
      }
    }
  },

  // methods
  methods: {
    swichToNav( args, e ) {
      e.preventDefault();
      this.$store.commit( 'swichToNav', args );
    }
  },
};
</script>