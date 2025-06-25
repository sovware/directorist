<template>
  <div class="cptm-form-group cptm-form-content">
    <template v-if="isFormGentInstalled">
      <template v-if="isFormGentActive">
        <template v-if="isLoadingForms">
          <div class="cptm-form-content-wrapper">
            <span class="cptm-form-content-icon la-spin">
              <svg
                width="32"
                height="32"
                viewBox="0 0 32 32"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g clip-path="url(#clip0_10032_121490)">
                  <path
                    d="M16.0001 2.66797V8.0013M16.0001 24.0013V29.3346M6.57341 6.57464L10.3467 10.348M21.6534 21.6546L25.4267 25.428M2.66675 16.0013H8.00008M24.0001 16.0013H29.3334M6.57341 25.428L10.3467 21.6546M21.6534 10.348L25.4267 6.57464"
                    stroke="#1E1E1E"
                    stroke-width="4"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </g>
                <defs>
                  <clipPath id="clip0_10032_121490">
                    <rect width="32" height="32" fill="white" />
                  </clipPath>
                </defs>
              </svg>
            </span>
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
              :value="value"
              @update="updateValue"
            />
          </div>

          <div class="cptm-form-content-wrapper" v-else>
            <span class="cptm-form-content-icon">
              <svg
                width="40"
                height="40"
                viewBox="0 0 40 40"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M7.5 3.75V36.25H32.5V11.9995L32.124 11.626L24.624 4.12598L24.2505 3.75H7.5ZM10 6.25H22.5V13.75H30V33.75H10V6.25ZM25 8.00049L28.2495 11.25H25V8.00049ZM12.5 16.25V18.75H27.5V16.25H12.5ZM12.5 22.5V25H21.25V22.5H12.5ZM23.75 22.5V25H27.5V22.5H23.75ZM12.5 27.5V30H21.25V27.5H12.5ZM23.75 27.5V30H27.5V27.5H23.75Z"
                  fill="#747C89"
                />
              </svg>
            </span>
            <h3 class="cptm-form-content-title">
              Get started with your first form
            </h3>
            <a
              class="cptm-btn cptm-btn-secondery"
              target="_blank"
              :href="createFormButtonData.href"
            >
              {{ createFormButtonData.label }}
            </a>
            <button
              class="cptm-form-content-btn cptm-form-loader"
              @click="loadForms"
            >
              <span class="cptm-form-content-btn-icon las la-redo-alt"></span>
              Check for new forms
            </button>
          </div>
        </template>
      </template>

      <template v-else>
        <div class="cptm-form-content-wrapper" v-if="canInstallPlugins">
          <span class="cptm-form-content-icon">
            <svg
              width="40"
              height="38"
              viewBox="0 0 40 38"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <mask id="path-1-inside-1_10032_119817" fill="white">
                <path d="M0 0H40V37.5H0V0Z" />
              </mask>
              <path d="M0 0H40V37.5H0V0Z" fill="#141921" />
              <path
                d="M40 37.5V36.875H0V37.5V38.125H40V37.5Z"
                fill="#2C3239"
                mask="url(#path-1-inside-1_10032_119817)"
              />
              <path
                d="M20.217 9.66406H13.7814C13.0737 9.66406 12.5 10.2594 12.5 10.9939V13.683C12.5 14.4174 13.0737 15.0128 13.7814 15.0128H20.217C20.9247 15.0128 21.4985 14.4174 21.4985 13.683V10.9939C21.4985 10.2594 20.9247 9.66406 20.217 9.66406Z"
                fill="white"
              />
              <path
                d="M13.7814 28.9921C13.0695 28.9921 12.5 28.4011 12.5 27.6623V19.654C12.5 18.9152 13.0695 18.3242 13.7814 18.3242H25.9977C26.7096 18.3242 27.2791 18.9152 27.2791 19.654V22.3136C27.2791 23.0524 26.7096 23.6434 25.9977 23.6434H17.6542V27.6328C17.6542 28.3715 17.0847 28.9626 16.3728 28.9626H13.7814V28.9921Z"
                fill="white"
              />
              <path
                d="M24.4022 10.9922L24.8863 12.4993L26.3386 13.0017L24.8863 13.504L24.4022 15.0111L23.9181 13.504L22.4658 13.0017L23.9181 12.4993L24.4022 10.9922Z"
                fill="white"
              />
              <path
                d="M26.4809 9.66406L26.6803 10.2846L27.2783 10.4915L26.6803 10.6983L26.4809 11.3189L26.2816 10.6983L25.6836 10.4915L26.2816 10.2846L26.4809 9.66406Z"
                fill="white"
              />
            </svg>
          </span>
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
        <span class="cptm-form-content-icon">
          <svg
            width="40"
            height="38"
            viewBox="0 0 40 38"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <mask id="path-1-inside-1_10032_119817" fill="white">
              <path d="M0 0H40V37.5H0V0Z" />
            </mask>
            <path d="M0 0H40V37.5H0V0Z" fill="#141921" />
            <path
              d="M40 37.5V36.875H0V37.5V38.125H40V37.5Z"
              fill="#2C3239"
              mask="url(#path-1-inside-1_10032_119817)"
            />
            <path
              d="M20.217 9.66406H13.7814C13.0737 9.66406 12.5 10.2594 12.5 10.9939V13.683C12.5 14.4174 13.0737 15.0128 13.7814 15.0128H20.217C20.9247 15.0128 21.4985 14.4174 21.4985 13.683V10.9939C21.4985 10.2594 20.9247 9.66406 20.217 9.66406Z"
              fill="white"
            />
            <path
              d="M13.7814 28.9921C13.0695 28.9921 12.5 28.4011 12.5 27.6623V19.654C12.5 18.9152 13.0695 18.3242 13.7814 18.3242H25.9977C26.7096 18.3242 27.2791 18.9152 27.2791 19.654V22.3136C27.2791 23.0524 26.7096 23.6434 25.9977 23.6434H17.6542V27.6328C17.6542 28.3715 17.0847 28.9626 16.3728 28.9626H13.7814V28.9921Z"
              fill="white"
            />
            <path
              d="M24.4022 10.9922L24.8863 12.4993L26.3386 13.0017L24.8863 13.504L24.4022 15.0111L23.9181 13.504L22.4658 13.0017L23.9181 12.4993L24.4022 10.9922Z"
              fill="white"
            />
            <path
              d="M26.4809 9.66406L26.6803 10.2846L27.2783 10.4915L26.6803 10.6983L26.4809 11.3189L26.2816 10.6983L25.6836 10.4915L26.2816 10.2846L26.4809 9.66406Z"
              fill="white"
            />
          </svg>
        </span>
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
        value: form.value,
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
    };
  },

  methods: {
    updateValue(value) {
      this.$emit("update", value);
    },

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
        this.updateNoFormSelectedAlert();
      } catch (error) {
        console.log(error);
      }

      this.isLoadingForms = false;
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
  },
};
</script>
