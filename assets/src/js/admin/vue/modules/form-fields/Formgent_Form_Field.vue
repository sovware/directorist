<template>
  <div class="cptm-form-group cptm-form-content">
    <template v-if="isFormGentInstalled">
      <template v-if="isFormGentActive">
        <template v-if="isLoadingForms">
          <div class="cptm-form-content-wrapper">
            <span
              class="cptm-form-content-icon cptm-form-content-icon-only la la-spinner la-spin"
            ></span>
            <h3 class="cptm-form-content-title">Loading forms...</h3>
            <p class="cptm-form-content-desc">FormGent forms are appearing</p>
          </div>
        </template>

        <template v-else>
          <div
            class="cptm-form-content-wrapper cptm-form-content-select"
            v-if="forms.length > 0"
          >
            <label class="cptm-form-content-label">{{ label }}</label>

            <select-field
              theme="default"
              :options="formgentFormList"
              v-model="formgent_selected_form"
            />
          </div>

          <div class="cptm-form-content-wrapper" v-else>
            <span
              class="cptm-form-content-icon cptm-form-content-icon-only la la-file-text-o"
            ></span>
            <h3 class="cptm-form-content-title">No forms available.</h3>
            <p class="cptm-form-content-desc">
              You haven't created any FormGent form yet.
            </p>
            <a class="cptm-form-content-btn" :href="createFormButtonData.href">
              <span class="cptm-form-content-btn-icon las la-plus"></span>
              {{ createFormButtonData.label }}
            </a>
          </div>
        </template>
      </template>

      <template v-else>
        <div class="cptm-form-content-wrapper" v-if="canInstallPlugins">
          <span class="cptm-form-content-icon las la-info-circle"></span>
          <h3 class="cptm-form-content-title">Activate FormGent Plugin</h3>
          <p class="cptm-form-content-desc">
            You need the FormGent plugin to use this feature.
          </p>
          <a
            class="cptm-form-content-btn"
            :class="isInstallingPlugin ? 'cptm-btn-disabled' : ''"
            href="#"
            @click.prevent="installPlugin()"
          >
            <span
              v-if="isInstallingPlugin"
              class="cptm-form-content-btn-loader"
            >
              Activating
              <i class="las la-sync la-spin"></i>
            </span>
            <span v-else> Activate</span>
          </a>
        </div>

        <div class="cptm-form-content-wrapper" v-else>
          <h3 class="cptm-form-content-title">
            You need the FormGent plugin to use this feature, ask the site admin
            to activate it.
          </h3>
        </div>
      </template>
    </template>

    <template v-else>
      <div class="cptm-form-content-wrapper" v-if="canInstallPlugins">
        <span class="cptm-form-content-icon las la-info-circle"></span>
        <h3 class="cptm-form-content-title">
          Install & Activate FormGent Plugin
        </h3>
        <p class="cptm-form-content-desc">
          You need the FormGent plugin to use this feature.
        </p>
        <a
          class="cptm-form-content-btn"
          :class="isInstallingPlugin ? 'cptm-btn-disabled' : ''"
          href="#"
          @click.prevent="installPlugin()"
        >
          <span v-if="isInstallingPlugin" class="cptm-form-content-btn-loader">
            Installing
            <i class="las la-sync la-spin"></i>
          </span>
          <span v-else> Install & Activate</span>
        </a>
      </div>

      <div class="" v-else>
        You need the FormGent plugin to use this feature. Ask the site admin to
        install and activate it.
      </div>
    </template>
  </div>
</template>

<script>
import Vue from "vue";
import field_helper from "./../../mixins/form-fields/helper";
import props from "./../../mixins/form-fields/input-field-props";

export default {
  name: "formgent-form-field",
  mixins: [props, field_helper],

  created() {
    this.init();
  },

  computed: {
    formgentFormList() {
      return this.forms.map((form) => ({
        label: form.label,
        value: this.getShortcode(form.value),
      }));
    },
  },

  watch: {
    alerts() {
      this.$emit(
        "alert",
        Object.keys(this.alerts).length ? { ...this.alerts } : null,
      );
    },

    value() {
      this.updateNoFormSelectedAlert();
    },
  },

  data() {
    return {
      alerts: {},
      isLoadingForms: false,
      isInstallingPlugin: false,
      forms: [],
      isFormGentInstalled: false,
      isFormGentActive: false,
      canInstallPlugins: false,
      createFormButtonData: {
        href: "#",
        label: "Create a new Form",
      },
      formgent_selected_form: "",
    };
  },

  methods: {
    init() {
      this.loadPropsData();
      this.loadLocalizeData();
      this.updateMissingDependencyAlert();

      if (this.isFormGentActive) {
        this.loadForms();
      }
    },

    loadPropsData() {
      if (
        this.createFormButton &&
        typeof this.createFormButton === "object" &&
        !Array.isArray(this.createFormButton)
      ) {
        this.createFormButtonData = {
          ...this.createFormButtonData,
          ...this.createFormButton,
        };
      }
    },

    loadLocalizeData() {
      if (typeof directorist_admin === "undefined") {
        return;
      }

      if (
        directorist_admin.capabilities &&
        directorist_admin.capabilities.install_plugins
      ) {
        this.canInstallPlugins = true;
      }

      if (typeof directorist_admin.formgent !== "undefined") {
        this.isFormGentInstalled = directorist_admin.formgent.is_installed;
        this.isFormGentActive = directorist_admin.formgent.is_active;
      }
    },

    async loadForms() {
      this.isLoadingForms = true;

      try {
        const response = await wp.apiFetch({
          path: "/formgent/admin/forms/select",
        });

        this.forms = response.forms;
        this.validateValue();
        this.updateNoFormSelectedAlert();
      } catch (error) {
        console.log(error);
      }

      this.isLoadingForms = false;
      this.formgent_selected_form = this.parseValue(this.value);
    },

    validateValue() {
      const parsedValue = this.parseValue(this.value);

      if (parsedValue !== this.value) {
        this.$emit("update", parsedValue);
      }
    },

    parseValue(value) {
      if (value === "") {
        return "";
      }

      const match = value.match(/\[formgent id="(\d+)"\]/);

      if (!match) {
        return "";
      }

      if (this.forms.map((item) => `${item.value}`).includes(`${match[1]}`)) {
        return value;
      }

      return "";
    },

    async installPlugin() {
      if (this.isInstallingPlugin) {
        return;
      }

      this.isInstallingPlugin = true;

      try {
        const response = await wp.apiFetch({
          path: "/directorist/v1/admin/install-plugin",
          method: "POST",
          data: {
            slug: "formgent",
            activate: "1",
          },
        });

        this.isFormGentInstalled = true;
        this.isFormGentActive = true;

        this.updateMissingDependencyAlert();

        this.updateLocalizeData({
          formgent: {
            is_installed: true,
            is_active: true,
          },
        });

        this.loadForms();
      } catch (error) {
        console.log(error);
      }

      this.isInstallingPlugin = false;
    },

    updateLocalizeData(data) {
      if (typeof window.directorist_admin === "undefined") {
        window.directorist_admin = {};
      }

      window.directorist_admin = {
        ...window.directorist_admin,
        ...data,
      };
    },

    updateNoFormSelectedAlert() {
      if (this.value === "") {
        this.alerts = {
          ...this.alerts,
          noFormSelected: {
            type: "warning",
            message: "Please select a form.",
          },
        };
      } else {
        this.removeAlert("noFormSelected");
      }
    },

    updateMissingDependencyAlert() {
      if (!this.isFormGentInstalled) {
        this.alerts = {
          ...this.alerts,
          missingDependency: {
            type: "warning",
            message: "Please install and activate the FormGent plugin.",
          },
        };
      } else if (!this.isFormGentActive) {
        this.alerts = {
          ...this.alerts,
          missingDependency: {
            type: "warning",
            message: "Please activate the FormGent plugin.",
          },
        };
      } else {
        this.removeAlert("missingDependency");
      }
    },

    removeAlert(key) {
      Vue.delete(this.alerts, key);
    },

    getShortcode(id) {
      return '[formgent id="' + id + '"]';
    },
  },
};
</script>
