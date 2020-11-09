<template>
  <div class="atbdp-sidebar-nav-area">
    <ul class="atbdp-sidebar-nav">
      <!-- atbdp-sidebar-nav-item -->
      <li class="atbdp-sidebar-nav-item active atbdp-nav-item-general"
        v-for="( nav, nav_key ) in navigation" :key="nav_key">
        <a href="#" class="atbdp-nav-link">
          <span class="atbdp-nav-icon">
            <span :class="nav.icon"></span>
          </span>
          <span class="atbdp-nav-label">{{ nav.label }}</span>
        </a>

        <!-- atbdp-sidebar-subnav -->
        <ul class="atbdp-sidebar-subnav" v-if="nav.submenu">
          <!-- atbdp-sidebar-subnav-item -->
          <li class="atbdp-sidebar-subnav-item active" 
            v-for="( subnav, subnav_key ) in nav.submenu"
            :key="subnav_key"
          >
            <a href="#" class="atbdp-nav-link">
              <span class="atbdp-nav-icon">
                <span :class="subnav.icon"></span>
              </span>
              <span class="atbdp-nav-label">{{ subnav.label }}</span>
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
    
  },
};
</script>