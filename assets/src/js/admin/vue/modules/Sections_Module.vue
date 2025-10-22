<template>
  <div class="cptm-tab-content" :class="containerClass">
    <div
      class="cptm-section"
      :class="sectionClass(section)"
      v-for="(section, section_key) in sections"
      :key="section_key"
    >
      <div
        class="cptm-title-area"
        :class="sectionTitleAreaClass(section)"
        v-if="
          ![
            'submission_form_fields',
            'search_form_fields',
            'single_listing_header',
            'single_listings_contents',
            'listings_card_grid_view',
            'listings_card_list_view',
          ].includes(section.fields[0])
        "
      >
        <h3 v-if="section.title" class="cptm-title" v-html="section.title"></h3>
        <div
          v-if="section.description"
          class="cptm-des"
          v-html="section.description"
        ></div>
      </div>

      <div class="cptm-form-fields" v-if="sectionFields(section)">
        <div
          v-for="(field, field_key) in sectionFields(section)"
          v-if="fields[field].group !== 'container'"
          :key="field_key"
        >
          <!-- Render the regular fields -->
          <component
            v-if="fields[field]"
            :is="getFormFieldName(fields[field].type)"
            :field-id="field_key"
            :fieldKey="field"
            :id="menuKey + '__' + section_key + '__' + field"
            :ref="field"
            :class="{ ['highlight-field']: getHighlightState(field) }"
            :cached-data="cached_fields[field]"
            :listing_type_id="listing_type_id"
            :video="video"
            v-bind="fields[field]"
            @update="updateFieldValue(field, $event)"
            @save="$emit('save', $event)"
            @validate="updateFieldValidationState(field, $event)"
            @is-visible="updateFieldData(field, 'isVisible', $event)"
            @do-action="doAction($event, 'sections-module')"
          />

          <div
            v-if="
              field === 'listings_card_grid_view' ||
              field === 'listings_card_list_view'
            "
            class="cptm-preview-notice"
          >
            <div class="cptm-preview-notice-content">
              <svg
                width="16"
                height="16"
                viewBox="0 0 16 16"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g clip-path="url(#clip0_8301_5081)">
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M7.99984 1.99984C4.68613 1.99984 1.99984 4.68613 1.99984 7.99984C1.99984 11.3135 4.68613 13.9998 7.99984 13.9998C11.3135 13.9998 13.9998 11.3135 13.9998 7.99984C13.9998 4.68613 11.3135 1.99984 7.99984 1.99984ZM0.666504 7.99984C0.666504 3.94975 3.94975 0.666504 7.99984 0.666504C12.0499 0.666504 15.3332 3.94975 15.3332 7.99984C15.3332 12.0499 12.0499 15.3332 7.99984 15.3332C3.94975 15.3332 0.666504 12.0499 0.666504 7.99984ZM7.33317 5.33317C7.33317 4.96498 7.63165 4.6665 7.99984 4.6665H8.0065C8.37469 4.6665 8.67317 4.96498 8.67317 5.33317C8.67317 5.70136 8.37469 5.99984 8.0065 5.99984H7.99984C7.63165 5.99984 7.33317 5.70136 7.33317 5.33317ZM7.99984 7.33317C8.36803 7.33317 8.6665 7.63165 8.6665 7.99984V10.6665C8.6665 11.0347 8.36803 11.3332 7.99984 11.3332C7.63165 11.3332 7.33317 11.0347 7.33317 10.6665V7.99984C7.33317 7.63165 7.63165 7.33317 7.99984 7.33317Z"
                    fill="#3E62F5"
                  />
                </g>
                <defs>
                  <clipPath id="clip0_8301_5081">
                    <rect width="16" height="16" fill="white" />
                  </clipPath>
                </defs>
              </svg>
              <p class="cptm-preview-notice-text">
                Want to enable/disable <strong>Grid</strong>,
                <strong>List</strong> or <strong>Map</strong> views for the All
                Listings Page?
              </p>
            </div>
            <div class="cptm-preview-notice-action">
              <a
                href="/wp-admin/edit.php?post_type=at_biz_dir&page=atbdp-settings"
                target="_blank"
                class="cptm-preview-notice-btn"
              >
                Go to settings
                <svg
                  width="14"
                  height="14"
                  viewBox="0 0 14 14"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M6.48424 1.38007C6.769 1.09531 7.23068 1.09531 7.51544 1.38007L12.6196 6.48424C12.9044 6.769 12.9044 7.23068 12.6196 7.51544L7.51544 12.6196C7.23068 12.9044 6.769 12.9044 6.48424 12.6196C6.19948 12.3348 6.19948 11.8732 6.48424 11.5884L10.3436 7.729H1.89567C1.49296 7.729 1.1665 7.40254 1.1665 6.99984C1.1665 6.59713 1.49296 6.27067 1.89567 6.27067H10.3436L6.48424 2.41127C6.19948 2.12651 6.19948 1.66483 6.48424 1.38007Z"
                    fill="#4D5761"
                  />
                </svg>
              </a>
            </div>
          </div>

          <!-- Insert the wrapped container fields right after "way_to_show_preview" -->
          <div
            v-if="
              field === 'way_to_show_preview' &&
              groupedContainerFields.length > 0
            "
            class="cptm-field-group-container"
          >
            <div class="atbdp-row">
              <div class="atbdp-col atbdp-col-4">
                <label class="cptm-field-group-container__label">
                  <span>{{ containerGroupLabel }}</span>
                </label>
              </div>
              <div class="atbdp-col atbdp-col-8">
                <div class="cptm-container-group-fields">
                  <component
                    v-for="(
                      groupedField, groupedFieldKey
                    ) in groupedContainerFields"
                    :is="getFormFieldName(fields[groupedField].type)"
                    :field-id="groupedFieldKey"
                    :id="menuKey + '__' + section_key + '__' + groupedField"
                    :ref="groupedField"
                    :class="{
                      ['highlight-field']: getHighlightState(groupedField),
                    }"
                    :cached-data="cached_fields[groupedField]"
                    v-bind="fields[groupedField]"
                    @update="updateFieldValue(groupedField, $event)"
                    @save="$emit('save', $event)"
                    @validate="updateFieldValidationState(groupedField, $event)"
                    @is-visible="
                      updateFieldData(groupedField, 'isVisible', $event)
                    "
                    @do-action="doAction($event, 'sections-module')"
                    :key="groupedFieldKey"
                  />
                </div>
              </div>
            </div>
          </div>
          <!-- ends: .field-group-container -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import helpers from "./../mixins/helpers";

export default {
  name: "sections-module",
  mixins: [helpers],

  props: {
    sections: {
      type: Object,
    },
    tabKey: {
      type: String,
      default: "",
    },
    container: {
      type: String,
      default: "",
    },
    menuKey: {
      type: String,
      default: "",
    },
    listing_type_id: {
      type: String,
      default: "",
    },
    video: {
      type: Object,
    },
  },

  computed: {
    ...mapState(["metaKeys", "fields", "cached_fields"]),
    ...mapState({
      layout: (state) => state.layouts,
      fields: (state) => state.fields,
    }),

    containerClass() {
      return {
        "tab-wide": this.container === "wide",
        "tab-short-wide": this.container === "short-wide",
        "tab-full-width": this.container === "full-width",
        [`cptm-tab-content-${this.tabKey}`]: !!this.tabKey,
      };
    },

    // Get the grouped container fields
    groupedContainerFields() {
      return this.groupFieldsByContainer().container || [];
    },

    // Get the label for the container group
    containerGroupLabel() {
      const firstContainerField = this.groupedContainerFields[0];
      return firstContainerField
        ? this.fields[firstContainerField].group_label
        : "";
    },
  },

  methods: {
    sectionFields(section) {
      if (!this.isObject(section)) {
        return false;
      }
      if (!Array.isArray(section.fields)) {
        return false;
      }

      return section.fields;
    },

    // Group fields by their group value, focusing on the container group
    groupFieldsByContainer() {
      let groupedFields = {
        container: [],
      };

      Object.keys(this.fields).forEach((field) => {
        if (this.fields[field].group === "container") {
          groupedFields.container.push(field);
        }
      });

      return groupedFields;
    },

    sectionClass(section) {
      const isDisabled =
        this.fields[section.fields[0]]?.type === "toggle" &&
        this.fields[section.fields[0]].value !== true;
      const sectionClass = `${isDisabled ? "cptm-section--disabled" : ""} ${
        section.fields[0]
      }`.trim();

      return sectionClass;
    },

    sectionTitleAreaClass(section) {
      return {
        "directorist-no-header": !section.title && !section.description,
        "cptm-text-center": "center" === section.title_align ? true : false,
      };
    },

    fieldWrapperClass(field_key, field) {
      let type_class =
        field && field.type
          ? "cptm-field-wraper-type-" + field.type
          : "cptm-field-wraper";
      let key_class = "cptm-field-wraper-key-" + field_key;

      return {
        [type_class]: true,
        [key_class]: true,
      };
    },

    fieldWrapperID(field) {
      let type_id = "";
      if (field && field.editor !== undefined) {
        type_id = field.editor === "wp_editor" ? "cptm-field_wp_editor" : "";
      }
      return type_id;
    },
  },
};
</script>
