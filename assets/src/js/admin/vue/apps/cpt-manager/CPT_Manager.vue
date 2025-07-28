<template>
  <div class="directorist-directory-type atbdp-cpt-manager">
    <div class="directorist-directory-type-top">
      <div 
        class="directorist-directory-type-top-left"
      >
        <a
          href="edit.php?post_type=at_biz_dir&page=atbdp-directory-types"
          class="directorist-back-directory"
          v-if="this.enabled_multi_directory"
        >
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
        <div
          class="directorist-row-tooltip"
          data-tooltip="Click here to rename the directory."
          data-flow="bottom"
        >
          <component
            v-if="options.name && options.name.type"
            :is="options.name.type + '-field'"
            v-bind="options.name"
            @update="updateOptionsField({ field: 'name', value: $event })"
          />
        </div>
      </div>
      <div class="directorist-directory-type-top-right">
        <button
          type="button"
          :disabled="footer_actions.save.isDisabled"
          class="cptm-btn cptm-btn-primary"
          @click="saveData()"
        >
          <span
            v-if="footer_actions.save.showLoading"
            class="fa fa-spinner fa-spin"
          ></span>
          {{ footer_actions.save.label }}
        </button>
        <!-- <a href="#" class="directorist-create-directory">Create New Directory</a> -->
      </div>
    </div>
    <div class="atbdp-cptm-status-feedback" v-if="status_messages?.length">
      <div
        class="cptm-alert"
        :class="'cptm-alert-' + status.type"
        v-for="(status, index) in this.status_messages"
        :key="index"
      >
        {{ status.message }}
      </div>
    </div>
    <div class="directorist-directory-type-bottom">
      <headerNavigation />
      <!-- atbdp-cptm-body -->
      <div class="atbdp-cptm-body">
        <tabContents @save="handleSaveData($event)" />
      </div>
    </div>
  </div>
</template>

<script>
const axios = require("axios").default;

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
      fields: "fields",
      cached_fields: "cached_fields",
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

    if (this.$root.cachedOptions) {
      this.$store.commit("updateCachedOptions", this.$store.options);
    }

    if (this.$root.config) {
      this.$store.commit("updateConfig", this.$root.config);
    }

    if (this.$root.id && !isNaN(this.$root.id)) {
      const id = parseInt(this.$root.id);

      if (id > 0) {
        this.listing_type_id = id;
        this.footer_actions.save.label = "Update";
      }
    }

    this.$store.commit("updateCachedFields");
    this.setupClosingWarning();
    this.setupSaveOnKeyboardInput();

    this.enabled_multi_directory = directorist_admin.enabled_multi_directory === "1";
  },

  data() {
    return {
      listing_type_id: null,
      status_messages: [],
      footer_actions: {
        save: {
          show: true,
          label: "Create Directory",
          showLoading: false,
          isDisabled: false,
        },
      },
      enabled_multi_directory: null,
    };
  },

  methods: {
    ...mapGetters(["getFieldsValue"]),

    setupSaveOnKeyboardInput() {
      addEventListener("keydown", (event) => {
        if ( ( event.metaKey || event.ctrlKey ) && 's' === event.key ) {
          event.preventDefault();
          this.saveData();
        }
      });
    },

    setupClosingWarning() {
      window.addEventListener("beforeunload", this.handleBeforeUnload);
    },

    getFieldsValue(fields) {
      const values = {};

      for (const key of Object.keys(fields)) {
        values[key] = fields[key].value;
      }

      return values;
    },

    parseJSONString(jsonString) {
      jsonString = jsonString.replace(/true/g, '"1"');
      jsonString = jsonString.replace(/false/g, '""');

      return jsonString;
    },

    handleBeforeUnload(event) {
      try {
        const fieldsValues = this.getFieldsValue(this.fields);
        const cachedFieldsValues = this.getFieldsValue(this.cached_fields);

        const dataA = this.parseJSONString(JSON.stringify(fieldsValues));
        const dataB = this.parseJSONString(JSON.stringify(cachedFieldsValues));

        if (btoa(dataA) !== btoa(dataB)) {
          event.preventDefault();
          event.returnValue = "";
        }
      } catch (error) {
        console.log({ error });
      }
    },

    updateOptionsField(payload) {
      if (!payload.field) {
        return;
      }
      if (typeof payload.value === "undefined") {
        return;
      }

      this.$store.commit("updateOptionsField", payload);
    },

    updateData() {
      let fields = this.getFieldsValue();

      let submission_url = this.$store.state.config.submission.url;
      let submission_with = this.$store.state.config.submission.with;

      let form_data = new FormData();

      if (submission_with && typeof submission_with === "object") {
        for (let data_key in submission_with) {
          form_data.append(data_key, submission_with[data_key]);
        }
      }

      if (this.listing_type_id) {
        form_data.append("listing_type_id", this.listing_type_id);
        this.footer_actions.save.label = "Update";
      }

      for (let field_key in fields) {
        let value = this.maybeJSON(fields[data_key]);
        form_data.append(data_key, value);
      }

      console.log({ submission_url, submission_with });
    },

    async handleSaveData(callback) {
      await this.saveData();

      if (typeof callback === "function") {
        callback(this.$store.state);
      }

      // Get Add Listing URL from Object
      const addListingURL = directorist_admin.add_listing_url;
      // Append the listing_type_id to the URL as a query parameter
      const urlWithListingType = `${addListingURL}?directory_type=${this.listing_type_id}`;
      // Open the URL with the listing_type_id parameter
      window.open(urlWithListingType, "_blank");
    },

    saveData() {
      let options = this.$store.state.options;
      let fields = this.$store.state.fields;

      let submission_url = this.$store.state.config.submission.url;
      let submission_with = this.$store.state.config.submission.with;

      let form_data = new FormData();

      if (submission_with && typeof submission_with === "object") {
        for (let data_key in submission_with) {
          form_data.append(data_key, submission_with[data_key]);
        }
      }

      if (this.listing_type_id) {
        form_data.append("listing_type_id", this.listing_type_id);
        this.footer_actions.save.label = "Update";
      }

      // Get Options Fields Data
      let options_field_list = [];
      for (let field in options) {
        let value = this.maybeJSON(options[field].value);

        form_data.append(field, value);
        options_field_list.push(field);
      }

      form_data.append("field_list", JSON.stringify(field_list));

      // Get Form Fields Data
      let field_list = [];
      for (let field in fields) {
        let value = this.maybeJSON([fields[field].value]);

        form_data.append(field, value);
        field_list.push(field);
      }

      form_data.append("field_list", this.maybeJSON(field_list));

      this.status_messages = [];
      this.footer_actions.save.showLoading = true;
      this.footer_actions.save.isDisabled = true;
      const self = this;

      this.$store.commit("updateIsSaving", true);

      axios
        .post(submission_url, form_data)
        .then((response) => {
          self.$store.commit("updateIsSaving", false);
          self.$store.commit("updateCachedFields");

          self.footer_actions.save.showLoading = false;
          self.footer_actions.save.isDisabled = false;

          // console.log( response );
          // return;

          if (response.data.term_id && !isNaN(response.data.term_id)) {
            self.listing_type_id = response.data.term_id;
            self.footer_actions.save.label = "Update";
            self.listing_type_id = response.data.term_id;

            if (response.data.redirect_url) {
              window.location = response.data.redirect_url;
            }
          }

          if (response.data.status && response.data.status.status_log) {
            for (let status_key in response.data.status.status_log) {
              self.status_messages.push({
                type: response.data.status.status_log[status_key].type,
                message: response.data.status.status_log[status_key].message,
              });
            }

            setTimeout(function () {
              self.status_messages = [];
            }, 5000);
          }

          // console.log( response );
        })
        .catch((error) => {
          self.footer_actions.save.showLoading = false;
          self.footer_actions.save.isDisabled = false;
          self.$store.commit("updateIsSaving", false);
          console.log(error);
        });
    },

    maybeJSON(data) {
      let value = typeof data === "undefined" ? "" : data;

      if (
        ("object" === typeof value && Object.keys(value)) ||
        Array.isArray(value)
      ) {
        let json_encoded_value = JSON.stringify(value);
        let base64_encoded_value = this.encodeUnicodedToBase64(
          json_encoded_value
        );
        value = base64_encoded_value;
      }

      return value;
    },

    encodeUnicodedToBase64(str) {
      // first we use encodeURIComponent to get percent-encoded UTF-8,
      // then we convert the percent encodings into raw bytes which
      // can be fed into btoa.
      return btoa(
        encodeURIComponent(str).replace(
          /%([0-9A-F]{2})/g,
          function toSolidBytes(match, p1) {
            return String.fromCharCode("0x" + p1);
          }
        )
      );
    },
  },
};
</script>
