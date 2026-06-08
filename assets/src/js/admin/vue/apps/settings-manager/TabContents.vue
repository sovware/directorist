<template>
  <div class="atbdp-cptm-tab-contents">
    <template v-for="(menu, menu_key) in layouts">
      <div
        :id="menu_key"
        class="atbdp-tab-content-item"
        :key="menu_key"
        v-if="menu.active"
      >
        <div class="settings-panel-page-head">
          <div>
            <span class="settings-panel-page-head__eyebrow">Settings area</span>
            <h2>{{ menu.label }}</h2>
          </div>
        </div>

        <template v-if="!menu.submenu">
          <!-- Main Menu Contents -->
          <div class="atbdp-tab-content-body" v-if="menu.sections">
            <sections-module
              v-if="hasSections(menu.sections, false)"
              :menu-key="menu_key"
              :sections="sectionsFor(menu.sections, false)"
              @do-action="doAction($event, 'tab-contents')"
            />

            <div
              class="settings-panel-advanced"
              :data-container-key="containerKey(menu_key)"
              v-if="hasSections(menu.sections, true)"
            >
              <button
                type="button"
                class="settings-panel-advanced__toggle"
                :aria-expanded="isAdvancedOpen(containerKey(menu_key)) ? 'true' : 'false'"
                @click.stop="toggleAdvanced(containerKey(menu_key))"
              >
                <svg
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="settings-panel-advanced__chevron"
                >
                  <polyline points="9 18 15 12 9 6" />
                </svg>
                {{ advancedToggleLabel(menu_key) }}
              </button>

              <div
                class="settings-panel-advanced__body"
                v-show="isAdvancedOpen(containerKey(menu_key))"
              >
                <sections-module
                  :menu-key="menu_key"
                  :sections="sectionsFor(menu.sections, true)"
                  @do-action="doAction($event, 'tab-contents')"
                />
              </div>
            </div>
          </div>
        </template>

        <!-- Submenu Contents -->
        <div class="atbdp-tab-sub-contents" v-if="menu.submenu">
          <nav class="settings-panel-subnav">
            <a
              href="#"
              class="settings-panel-subnav__item"
              :class="{ active: submenu.active }"
              v-for="(submenu, submenu_key) in menu.submenu"
              :key="submenu_key"
              @click.prevent="swichToNav({ menu_key, submenu_key })"
            >
              <span
                class="settings-panel-subnav__icon"
                v-if="submenu.icon"
                v-html="submenu.icon"
              ></span>
              {{ submenu.label }}
            </a>
          </nav>

          <template v-for="(submenu, submenu_key) in menu.submenu">
            <div
              :id="menu_key + '__' + submenu_key"
              class="atbdp-tab-content-item"
              v-if="submenu.active"
              :key="submenu_key"
            >
              <div class="settings-panel-subpage-head">
                <h3>{{ submenu.label }}</h3>
              </div>

              <sections-module
                v-if="hasSections(submenu.sections, false)"
                :menu-key="menu_key + '__' + submenu_key"
                :sections="sectionsFor(submenu.sections, false)"
                @do-action="doAction($event, 'tab-contents')"
              />

              <div
                class="settings-panel-advanced"
                :data-container-key="containerKey(menu_key, submenu_key)"
                v-if="hasSections(submenu.sections, true)"
              >
                <button
                  type="button"
                  class="settings-panel-advanced__toggle"
                  :aria-expanded="isAdvancedOpen(containerKey(menu_key, submenu_key)) ? 'true' : 'false'"
                  @click.stop="toggleAdvanced(containerKey(menu_key, submenu_key))"
                >
                  <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="settings-panel-advanced__chevron"
                  >
                    <polyline points="9 18 15 12 9 6" />
                  </svg>
                  {{ advancedToggleLabel(menu_key, submenu_key) }}
                </button>

                <div
                  class="settings-panel-advanced__body"
                  v-show="isAdvancedOpen(containerKey(menu_key, submenu_key))"
                >
                  <sections-module
                    :menu-key="menu_key + '__' + submenu_key"
                    :sections="sectionsFor(submenu.sections, true)"
                    @do-action="doAction($event, 'tab-contents')"
                  />
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import { mapState } from "vuex";
import helpers from "./../../mixins/helpers";

export default {
  name: "tab-area",
  mixins: [helpers],

  computed: {
    ...mapState({
      layouts: "layouts",
      highlightedFieldKey: "highlighted_field_key",
    }),
  },

  data() {
    return {
      advancedOpen: {},
    };
  },

  watch: {
    highlightedFieldKey() {
      this.openAdvancedForHighlightedField();
    },
  },

  mounted() {
    this.openAdvancedForHighlightedField();
  },

  methods: {
    swichToNav(args) {
      this.$store.commit("swichToNav", args);
    },

    containerKey(menuKey, submenuKey = "") {
      return submenuKey ? `${menuKey}__${submenuKey}` : menuKey;
    },

    isAdvancedOpen(key) {
      return !!this.advancedOpen[key];
    },

    toggleAdvanced(key) {
      this.$set(this.advancedOpen, key, !this.isAdvancedOpen(key));
    },

    advancedToggleLabel(menuKey, submenuKey = "") {
      if (menuKey === "listing_settings" && submenuKey === "single_listing") {
        return "Advanced";
      }

      if (menuKey === "email_settings" && submenuKey === "email_events") {
        return "Schedule & timing";
      }

      return "Show advanced settings";
    },

    sectionIsAdvanced(section) {
      return !!(section && section.advanced);
    },

    sectionsFor(sections, advanced) {
      let filteredSections = {};

      if (!sections) {
        return filteredSections;
      }

      for (let sectionKey in sections) {
        if (this.sectionIsAdvanced(sections[sectionKey]) === advanced) {
          filteredSections[sectionKey] = sections[sectionKey];
        }
      }

      return filteredSections;
    },

    hasSections(sections, advanced) {
      return !!Object.keys(this.sectionsFor(sections, advanced)).length;
    },

    openAdvancedForHighlightedField() {
      if (!this.highlightedFieldKey) {
        return;
      }

      for (let menuKey in this.layouts) {
        const menu = this.layouts[menuKey];

        if (
          this.sectionCollectionHasAdvancedField(
            menu.sections,
            this.highlightedFieldKey
          )
        ) {
          this.$set(this.advancedOpen, this.containerKey(menuKey), true);
        }

        if (!menu.submenu) {
          continue;
        }

        for (let submenuKey in menu.submenu) {
          if (
            this.sectionCollectionHasAdvancedField(
              menu.submenu[submenuKey].sections,
              this.highlightedFieldKey
            )
          ) {
            this.$set(
              this.advancedOpen,
              this.containerKey(menuKey, submenuKey),
              true
            );
          }
        }
      }
    },

    sectionCollectionHasAdvancedField(sections, fieldKey) {
      if (!sections) {
        return false;
      }

      for (let sectionKey in sections) {
        const section = sections[sectionKey];

        if (
          this.sectionIsAdvanced(section) &&
          Array.isArray(section.fields) &&
          section.fields.includes(fieldKey)
        ) {
          return true;
        }
      }

      return false;
    },
  },
};
</script>
