<template>
  <div
    class="setting-left-sibebar setting-left-sidebar"
    :class="{ 'setting-left-sidebar--search-open': searchQuery.length }"
  >
    <div class="settings-sidebar-search" ref="searchRoot">
      <svg
        class="settings-sidebar-search__icon"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
        focusable="false"
      >
        <circle cx="11" cy="11" r="7" />
        <path d="m21 21-4.3-4.3" />
      </svg>

      <input
        type="text"
        class="settings-sidebar-search__input"
        placeholder="Search settings..."
        :value="searchQuery"
        @input="$emit('update-search-query', $event.target.value)"
        @keydown.down.prevent="$emit('move-search-result', 1)"
        @keydown.up.prevent="$emit('move-search-result', -1)"
        @keydown.enter.prevent="$emit('submit-search-result')"
        @keydown.esc.prevent="$emit('close-search')"
      />

      <div
        class="settings-command-palette"
        v-if="searchQuery.length && searchResults.length"
        @click.stop
      >
        <div class="settings-command-palette__header">
          <span>{{ searchResultTotal }} RESULTS</span>
          <span class="settings-command-palette__divider">·</span>
          <span class="settings-command-palette__kbd">↑</span>
          <span class="settings-command-palette__kbd">↓</span>
          <span>TO NAVIGATE</span>
          <span class="settings-command-palette__divider">·</span>
          <span class="settings-command-palette__kbd">ESC</span>
          <span>TO CLOSE</span>
        </div>

        <div class="settings-command-palette__list">
          <div
            v-for="(result, index) in searchResults"
            :key="result.fieldKey"
            class="settings-command-palette__result"
            :class="{ 'settings-command-palette__result--active': index === activeSearchIndex }"
            @mouseenter="$emit('set-active-search-index', index)"
          >
            <button
              type="button"
              class="settings-command-palette__target"
              @click="$emit('jump-to-search-result', result)"
            >
              <span
                class="settings-command-palette__label"
                v-html="highlightText(result.label)"
              ></span>

              <span class="settings-command-palette__path">
                <span
                  class="settings-command-palette__path-text"
                  v-html="highlightText(result.pathText)"
                ></span>
                <svg
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  aria-hidden="true"
                  focusable="false"
                >
                  <path d="M7 17 17 7M7 7h10v10" />
                </svg>
              </span>
            </button>

            <div
              class="settings-command-palette__action"
              @click.stop
              @mousedown.stop
            >
              <button
                v-if="result.controlType === 'toggle'"
                type="button"
                class="settings-command-palette__toggle"
                :class="{ 'settings-command-palette__toggle--active': quickBooleanValue(result.value) }"
                :aria-pressed="quickBooleanValue(result.value) ? 'true' : 'false'"
                @click="$emit('quick-update-field', {
                  fieldKey: result.fieldKey,
                  value: !quickBooleanValue(result.value),
                })"
              >
                <span class="settings-command-palette__toggle-knob"></span>
              </button>

              <input
                v-else-if="result.controlType === 'input'"
                class="settings-command-palette__input"
                :type="result.inputType"
                :value="result.value"
                @input="$emit('quick-update-field', {
                  fieldKey: result.fieldKey,
                  value: $event.target.value,
                })"
                @keydown.enter.stop
                @keydown.esc.stop="$emit('close-search')"
              />

              <select
                v-else-if="result.controlType === 'select'"
                class="settings-command-palette__select"
                :value="String(result.value)"
                @change="$emit('quick-update-field', {
                  fieldKey: result.fieldKey,
                  value: $event.target.value,
                })"
                @keydown.esc.stop="$emit('close-search')"
              >
                <option
                  v-if="selectHasUnknownValue(result)"
                  :value="String(result.value)"
                >
                  {{ result.value }}
                </option>
                <option
                  v-for="option in result.options"
                  :key="option.value"
                  :value="option.value"
                >
                  {{ option.label }}
                </option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div
        class="settings-command-palette settings-command-palette--empty"
        v-else-if="searchQuery.length"
        @click.stop
      >
        <div class="settings-command-palette__empty">No settings found.</div>
      </div>
    </div>

    <ul class="settings-nav">
      <li
        class="settings-nav__item"
        :class="{ active: meue_item.active, ['settings-nav__item--' + menu_key]: true }"
        v-for="(meue_item, menu_key) in menu"
        :key="menu_key"
      >
        <a
          href="#"
          class="settings-nav__item__link"
          :class="{ ['nav-has-dropdwon']: meue_item.submenu }"
          @click.prevent="swichToNav({ menu_key }, $event)"
        >
          <span
            class="settings-nav__item__icon"
            v-if="meue_item.icon"
            v-html="meue_item.icon"
          ></span>
          <span class="settings-nav__item__text">{{ meue_item.label }}</span>
          <span class="drop-toggle-caret" v-if="meue_item.submenu"></span>
        </a>
      </li>
    </ul>

    <div class="settings-sidebar-footer">
      <a href="https://directorist.com/documentation/" target="_blank" rel="noopener">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m10 9 5 3-5 3z"/></svg>
        Tutorials
      </a>
      <span class="settings-sidebar-footer__separator"></span>
      <a href="https://directorist.com/documentation/directorist/" target="_blank" rel="noopener">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5z"/></svg>
        Docs
      </a>
      <span class="settings-sidebar-footer__separator"></span>
      <a href="https://directorist.com/contact/" target="_blank" rel="noopener">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.8 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
        Help
      </a>
    </div>
  </div>
</template>

<script>
export default {
  name: "sidebar-navigation",

  props: {
    menu: {
      type: Object,
      default: () => ({}),
    },
    searchQuery: {
      type: String,
      default: "",
    },
    searchResults: {
      type: Array,
      default: () => [],
    },
    searchResultTotal: {
      type: Number,
      default: 0,
    },
    activeSearchIndex: {
      type: Number,
      default: 0,
    },
  },

  mounted() {
    document.addEventListener("click", this.handleOutsideClick);
  },

  beforeDestroy() {
    document.removeEventListener("click", this.handleOutsideClick);
  },

  methods: {
    handleOutsideClick(event) {
      if (!this.searchQuery.length || !this.$refs.searchRoot) {
        return;
      }

      if (!this.$refs.searchRoot.contains(event.target)) {
        this.$emit("close-search");
      }
    },

    swichToNav(args, e) {
      e.preventDefault();
      this.$store.commit("swichToNav", args);
    },

    quickBooleanValue(value) {
      return value === true || value === "true" || value === 1 || value === "1";
    },

    highlightText(value) {
      const text = this.escapeHtml(value);
      const query = this.escapeHtml(this.searchQuery.trim());

      if (!query) {
        return text;
      }

      const escapedQuery = query.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
      const regex = new RegExp(`(${escapedQuery})`, "ig");

      return text.replace(regex, "<mark>$1</mark>");
    },

    escapeHtml(value) {
      return String(value || "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
    },

    selectHasUnknownValue(result) {
      if (!result || !Array.isArray(result.options)) {
        return false;
      }

      return !result.options.some((option) => {
        return String(option.value) === String(result.value);
      });
    },
  },
};
</script>
