<template>
  <div class="cptm-tab-content" :class="containerClass">
    <div
      class="cptm-section"
      :class="sectionClass(section)"
      v-for="(section, section_key) in sections"
      :key="section_key"
    >
      <div class="cptm-title-area" :class="sectionTitleAreaClass(section)">
        <h3 v-if="section.title" class="cptm-title" v-html="section.title"></h3>
        <p v-if="section.description" v-html="section.description"></p>
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
            :id="menuKey + '__' + section_key + '__' + field"
            :ref="field"
            :class="{ ['highlight-field']: getHighlightState(field) }"
            :cached-data="cached_fields[field]"
            v-bind="fields[field]"
            @update="updateFieldValue(field, $event)"
            @validate="updateFieldValidationState(field, $event)"
            @is-visible="updateFieldData(field, 'isVisible', $event)"
            @do-action="doAction($event, 'sections-module')"
          />

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
import helpers from "./../mixins/helpers";
import { mapState } from "vuex";

export default {
  name: "sections-module",
  mixins: [helpers],

  props: {
    sections: {
      type: Object,
    },
    container: {
      type: String,
      default: "",
    },
    menuKey: {
      type: String,
      default: "",
    },
  },

  computed: {
    ...mapState(["metaKeys", "fields", "cached_fields"]),

    containerClass() {
      return {
        "tab-wide": "wide" === this.container ? true : false,
        "tab-full-width": "full-width" === this.container ? true : false,
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
      return {
        "cptm-short-wide": "short-width" === section.container ? true : false,
      };
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
