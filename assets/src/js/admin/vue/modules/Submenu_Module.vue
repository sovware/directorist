<template>
  <div>
    <div class="cptm-tab-content-header">
      <sub-navigation :navLists="navList" v-model="active_sub_nav" />
    </div>

    <div class="cptm-tab-content-body">
      <template v-for="(sub_tab, sub_tab_index) in subNavigation">
        <div
          class="cptm-tab-sub-content-item"
          :key="sub_tab_index"
          v-if="active_sub_nav === sub_tab_index ? true : false"
          :class="{ active: active_sub_nav === sub_tab_index ? true : false }"
        >
          <sections-module v-bind="sub_tab" />
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import helpers from "../mixins/helpers";

export default {
  name: "submenu-module",
  mixins: [helpers],

  props: {
    submenu: {
      type: Object,
    },
    menuKey: {
      type: String,
      default: "",
    },
  },

  // computed
  computed: {
    subNavigation() {
      if (!this.submenu && typeof this.submenu !== "object") {
        return [];
      }

      let sub_navigation = [];

      for (let submenu_key in this.submenu) {
        let submenu = this.submenu[submenu_key];

        if (typeof submenu.label !== "string") {
          continue;
        }

        if (!submenu.sections && typeof submenu.sections !== "object") {
          continue;
        }

        if (Array.isArray(submenu.sections)) {
          continue;
        }

        sub_navigation.push(submenu);
      }

      return sub_navigation;
    },

    navList() {
      if (!this.subNavigation && typeof this.subNavigation !== "object") {
        return [];
      }

      return [...this.subNavigation].map((item) => {
        return item;
      });
    },

    activeSubMenu() {
      return this.subNavigation[this.active_sub_nav] || {};
    },
  },

  data() {
    return {
      active_sub_nav: 0,
    };
  },

  mounted() {
    this.active_sub_nav = this.getInitialSubNavIndex();
  },

  watch: {
    active_sub_nav(index) {
      try {
        window.localStorage.setItem(this.getSubNavStorageKey(), String(index));
      } catch (error) {}
    },
  },

  methods: {
    getSubNavStorageKey() {
      const typeId = this.$root.id || 0;
      return this.menuKey
        ? `directorist_cptm_active_sub_tab_${this.menuKey}_${typeId}`
        : `directorist_cptm_active_sub_tab_${typeId}`;
    },

    getInitialSubNavIndex() {
      if (!this.subNavigation.length) {
        return 0;
      }

      let fallbackIndex = this.subNavigation.findIndex(
        (submenu) => submenu.active === true,
      );

      if (fallbackIndex < 0) {
        fallbackIndex = 0;
      }

      try {
        const storedValue = window.localStorage.getItem(
          this.getSubNavStorageKey(),
        );
        const parsedValue = Number.parseInt(storedValue, 10);

        if (
          !Number.isNaN(parsedValue) &&
          parsedValue >= 0 &&
          parsedValue < this.subNavigation.length
        ) {
          return parsedValue;
        }
      } catch (error) {}

      return fallbackIndex;
    },
  },
};
</script>
