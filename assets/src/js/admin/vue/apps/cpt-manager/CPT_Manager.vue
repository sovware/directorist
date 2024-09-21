<template>
  <div class="directorist-directory-type atbdp-cpt-manager">
    <div class="directorist-directory-type-top">
      <div class="directorist-directory-type-top-left">
        <a href="#" class="directorist-back-directory">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="14"
            height="14"
            viewBox="0 0 14 14"
            fill="none"
          >
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M7.51556 1.38019C7.80032 1.66495 7.80032 2.12663 7.51556 2.41139L3.65616 6.27079H12.1041C12.5068 6.27079 12.8333 6.59725 12.8333 6.99996C12.8333 7.40267 12.5068 7.72913 12.1041 7.72913H3.65616L7.51556 11.5885C7.80032 11.8733 7.80032 12.335 7.51556 12.6197C7.2308 12.9045 6.76912 12.9045 6.48436 12.6197L1.38019 7.51556C1.09544 7.2308 1.09544 6.76912 1.38019 6.48436L6.48436 1.38019C6.76912 1.09544 7.2308 1.09544 7.51556 1.38019Z"
              fill="currentColor"
            />
          </svg>
          All Directories
        </a>
        <!-- atbdp-cptm-header -->
        <div class="directorist-row-tooltip" data-tooltip="Click here to rename the directory." data-flow="bottom">
          <component
            v-if="options.name && options.name.type"
            :is="options.name.type + '-field'"
            v-bind="options.name"
            @update="updateOptionsField({ field: 'name', value: $event })"
          />
        </div>
      </div>
      <div class="directorist-directory-type-top-right">
        <a href="#" class="directorist-create-directory"> Create Directory </a>
      </div>
    </div>
    <div class="directorist-directory-type-bottom">
      <headerNavigation />
      <!-- atbdp-cptm-body -->
      <div class="atbdp-cptm-body">
        <tabContents />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import headerNavigation from "./Header_Navigation.vue";
import tabContents from "./TabContents.vue";

export default {
  name: "cpt-manager",

  components: {
    headerNavigation,
    tabContents,
  },

  computed: {
    ...mapState({
      options: "options",
    }),
  },

  created() {
    if (this.$root.fields) {
      this.$store.commit("updateFields", this.$root.fields);
    }

    if (this.$root.layouts) {
      this.$store.commit("updatelayouts", this.$root.layouts);
    }

    if (this.$root.options) {
      this.$store.commit("updateOptions", this.$root.options);
    }

    if (this.$root.config) {
      this.$store.commit("updateConfig", this.$root.config);
    }
  },

  data() {
    return {
      listing_type_id: null,
    };
  },

  methods: {
    ...mapGetters(["getFieldsValue"]),

    updateOptionsField(payload) {
      if (!payload.field) {
        return;
      }
      if (typeof payload.value === "undefined") {
        return;
      }

      this.$store.commit("updateOptionsField", payload);
    },

    saveData() {
      let options = this.$store.state.options;

      // Get Options Fields Data
      let options_field_list = [];
      for (let field in options) {
        let value = this.maybeJSON(options[field].value);

        form_data.append(field, value);
        options_field_list.push(field);
      }
    },
  },
};
</script>
