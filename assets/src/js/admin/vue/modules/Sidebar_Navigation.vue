<template>
  <div class="setting-left-sibebar setting-left-sidebar">
    <div class="settings-sidebar-search">
      <svg class="settings-sidebar-search__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
      <input
        type="text"
        class="setting-search-field__input"
        placeholder="Search settings..."
        :value="searchQuery"
        @input="$emit('update-search-query', $event.target.value)"
      />

      <div class="setting-search-suggestions" v-if="searchSuggestions">
        <ul class="search-suggestions-list">
          <li
            class="search-suggestions-list--list-item"
            v-for="(field_key, field_index) in Object.keys(searchSuggestions)"
            :key="field_index"
          >
            <a
              href="#"
              class="search-suggestions-list--link"
              @click.prevent="$emit('jump-to-search-result', searchSuggestions[field_key])"
            >
              {{ searchSuggestions[field_key].label }}
            </a>
          </li>
        </ul>
      </div>
    </div>

    <ul class="settings-nav">
      <li class="settings-nav__item" :class="{ active: meue_item.active, ['settings-nav__item--' + menu_key]: true }" v-for="( meue_item, menu_key ) in menu" :key="menu_key">
        
        <a href="#" class="settings-nav__item__link" :class="{ ['nav-has-dropdwon']: meue_item.submenu }" @click.prevent="swichToNav({ menu_key }, $event)">
          <span class="settings-nav__item__icon" v-if="meue_item.icon" v-html="meue_item.icon"></span> 
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
    searchSuggestions: {
      type: [Object, Boolean],
      default: false,
    },
  },

  // computed
  computed: {

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
