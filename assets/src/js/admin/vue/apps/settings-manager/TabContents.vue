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
          <div
            class="settings-panel-subnav-shell"
            :class="subnavShellClasses(menu_key)"
          >
            <button
              type="button"
              class="settings-panel-subnav-scroll settings-panel-subnav-scroll--left"
              aria-label="Scroll settings tabs left"
              v-if="subnavCanScrollLeft(menu_key)"
              @click="scrollSubnav(menu_key, -1)"
            >
              <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
              >
                <polyline points="15 18 9 12 15 6" />
              </svg>
            </button>

            <nav
              class="settings-panel-subnav"
              aria-label="Settings tabs"
              :ref="subnavRefName(menu_key)"
              @scroll.passive="handleSubnavScroll(menu_key)"
              @wheel="handleSubnavWheel($event, menu_key)"
            >
              <a
                href="#"
                class="settings-panel-subnav__item"
                :class="{ active: submenu.active }"
                :aria-current="submenu.active ? 'page' : null"
                :data-submenu-key="submenu_key"
                v-for="(submenu, submenu_key) in menu.submenu"
                :key="submenu_key"
                @click.prevent="swichToNav({ menu_key, submenu_key })"
                @keydown.left.prevent="focusAdjacentSubnav(menu_key, submenu_key, -1)"
                @keydown.right.prevent="focusAdjacentSubnav(menu_key, submenu_key, 1)"
              >
                <span
                  class="settings-panel-subnav__icon"
                  v-if="submenu.icon"
                  v-html="submenu.icon"
                ></span>
                {{ submenu.label }}
              </a>
            </nav>

            <button
              type="button"
              class="settings-panel-subnav-scroll settings-panel-subnav-scroll--right"
              aria-label="Scroll settings tabs right"
              v-if="subnavCanScrollRight(menu_key)"
              @click="scrollSubnav(menu_key, 1)"
            >
              <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
              >
                <polyline points="9 18 15 12 9 6" />
              </svg>
            </button>
          </div>

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

    activeSubnavSignature() {
      for (let menuKey in this.layouts) {
        const menu = this.layouts[menuKey];

        if (!menu || !menu.active || !menu.submenu) {
          continue;
        }

        for (let submenuKey in menu.submenu) {
          if (menu.submenu[submenuKey].active) {
            return `${menuKey}__${submenuKey}`;
          }
        }
      }

      return "";
    },
  },

  data() {
    return {
      advancedOpen: {},
      subnavScrollState: {},
      subnavFrame: null,
      resizeHandler: null,
    };
  },

  watch: {
    highlightedFieldKey() {
      this.openAdvancedForHighlightedField();
      this.queueActiveSubnavIntoView();
    },

    activeSubnavSignature() {
      this.queueActiveSubnavIntoView();
    },
  },

  mounted() {
    this.openAdvancedForHighlightedField();
    this.resizeHandler = () => this.queueSubnavStateSync();
    window.addEventListener("resize", this.resizeHandler);
    this.queueActiveSubnavIntoView();
  },

  beforeDestroy() {
    if (this.resizeHandler) {
      window.removeEventListener("resize", this.resizeHandler);
    }

    this.cancelSubnavFrame();
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

    subnavRefName(menuKey) {
      return `settings-panel-subnav-${menuKey}`;
    },

    subnavState(menuKey) {
      return this.subnavScrollState[menuKey] || {};
    },

    subnavCanScrollLeft(menuKey) {
      return !!this.subnavState(menuKey).canScrollLeft;
    },

    subnavCanScrollRight(menuKey) {
      return !!this.subnavState(menuKey).canScrollRight;
    },

    subnavShellClasses(menuKey) {
      const state = this.subnavState(menuKey);

      return {
        "settings-panel-subnav-shell--scrollable": !!state.isScrollable,
        "settings-panel-subnav-shell--can-scroll-left": !!state.canScrollLeft,
        "settings-panel-subnav-shell--can-scroll-right": !!state.canScrollRight,
      };
    },

    getSubnavEl(menuKey) {
      const ref = this.$refs[this.subnavRefName(menuKey)];

      return Array.isArray(ref) ? ref[0] : ref;
    },

    handleSubnavScroll(menuKey) {
      this.syncSubnavState(menuKey);
    },

    handleSubnavWheel(event, menuKey) {
      const rail = this.getSubnavEl(menuKey);

      if (!rail || rail.scrollWidth <= rail.clientWidth) {
        return;
      }

      const delta =
        Math.abs(event.deltaX) > Math.abs(event.deltaY)
          ? event.deltaX
          : event.deltaY;

      if (!delta) {
        return;
      }

      const maxScrollLeft = this.maxSubnavScrollLeft(rail);
      const nextScrollLeft = this.clamp(
        rail.scrollLeft + delta,
        0,
        maxScrollLeft
      );

      if (nextScrollLeft === rail.scrollLeft) {
        return;
      }

      event.preventDefault();
      rail.scrollLeft = nextScrollLeft;
      this.syncSubnavState(menuKey);
    },

    scrollSubnav(menuKey, direction) {
      const rail = this.getSubnavEl(menuKey);

      if (!rail) {
        return;
      }

      const distance = Math.max(Math.round(rail.clientWidth * 0.7), 180);
      const nextScrollLeft = this.clamp(
        rail.scrollLeft + distance * direction,
        0,
        this.maxSubnavScrollLeft(rail)
      );

      rail.scrollTo({
        left: nextScrollLeft,
        behavior: "smooth",
      });

      this.queueSubnavStateSync();
    },

    focusAdjacentSubnav(menuKey, submenuKey, direction) {
      const menu = this.layouts[menuKey];
      const submenuKeys = Object.keys((menu && menu.submenu) || {});
      const currentIndex = submenuKeys.indexOf(submenuKey);

      if (currentIndex < 0) {
        return;
      }

      const nextIndex = this.clamp(
        currentIndex + direction,
        0,
        submenuKeys.length - 1
      );

      if (nextIndex === currentIndex) {
        return;
      }

      const nextSubmenuKey = submenuKeys[nextIndex];

      this.swichToNav({
        menu_key: menuKey,
        submenu_key: nextSubmenuKey,
      });

      this.$nextTick(() => {
        const rail = this.getSubnavEl(menuKey);
        const items = rail
          ? rail.querySelectorAll(".settings-panel-subnav__item")
          : [];
        const nextItem = items[nextIndex];

        if (nextItem && typeof nextItem.focus === "function") {
          nextItem.focus({ preventScroll: true });
        }
      });
    },

    queueActiveSubnavIntoView() {
      this.$nextTick(() => {
        this.cancelSubnavFrame();
        this.subnavFrame = window.requestAnimationFrame(() => {
          this.subnavFrame = null;
          this.scrollActiveSubnavIntoView();
          this.syncAllSubnavStates();
        });
      });
    },

    queueSubnavStateSync() {
      this.$nextTick(() => {
        if (this.subnavFrame) {
          return;
        }

        this.subnavFrame = window.requestAnimationFrame(() => {
          this.subnavFrame = null;
          this.syncAllSubnavStates();
        });
      });
    },

    cancelSubnavFrame() {
      if (!this.subnavFrame) {
        return;
      }

      window.cancelAnimationFrame(this.subnavFrame);
      this.subnavFrame = null;
    },

    scrollActiveSubnavIntoView() {
      const active = this.activeSubnavSignature.split("__");
      const menuKey = active[0];

      if (!menuKey) {
        return;
      }

      const rail = this.getSubnavEl(menuKey);
      const activeItem = rail
        ? rail.querySelector(".settings-panel-subnav__item.active")
        : null;

      if (!rail || !activeItem || rail.scrollWidth <= rail.clientWidth) {
        return;
      }

      const railRect = rail.getBoundingClientRect();
      const activeRect = activeItem.getBoundingClientRect();
      const safePadding = 44;
      let nextScrollLeft = rail.scrollLeft;

      if (activeRect.left < railRect.left + safePadding) {
        nextScrollLeft -= railRect.left + safePadding - activeRect.left;
      } else if (activeRect.right > railRect.right - safePadding) {
        nextScrollLeft += activeRect.right - (railRect.right - safePadding);
      }

      nextScrollLeft = this.clamp(
        nextScrollLeft,
        0,
        this.maxSubnavScrollLeft(rail)
      );

      if (Math.abs(nextScrollLeft - rail.scrollLeft) < 1) {
        return;
      }

      rail.scrollTo({
        left: nextScrollLeft,
        behavior: "smooth",
      });
    },

    syncAllSubnavStates() {
      for (let menuKey in this.layouts) {
        const menu = this.layouts[menuKey];

        if (menu && menu.submenu) {
          this.syncSubnavState(menuKey);
        }
      }
    },

    syncSubnavState(menuKey) {
      const rail = this.getSubnavEl(menuKey);

      if (!rail) {
        return;
      }

      const maxScrollLeft = this.maxSubnavScrollLeft(rail);

      this.$set(this.subnavScrollState, menuKey, {
        isScrollable: maxScrollLeft > 1,
        canScrollLeft: rail.scrollLeft > 1,
        canScrollRight: rail.scrollLeft < maxScrollLeft - 1,
      });
    },

    maxSubnavScrollLeft(rail) {
      return Math.max(rail.scrollWidth - rail.clientWidth, 0);
    },

    clamp(value, min, max) {
      return Math.min(Math.max(value, min), max);
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
