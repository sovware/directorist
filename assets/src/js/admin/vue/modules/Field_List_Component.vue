<template>
  <div
    class="directorist-form-fields-area"
    v-if="field_list && typeof field_list === 'object'"
  >
    <component
      v-if="field.type"
      v-for="(field, field_key) in visibleFields"
      :key="field_key"
      :is="field.type + '-field'"
      :section-id="sectionId"
      :field-id="`${sectionId}_${field_key}`"
      :root="field_list"
      v-bind="excludeShowIfCondition(field)"
      @update="update({ key: field_key, value: $event })"
    />
    <button 
      class="cptm-form-builder-group-options__advanced-toggle"
      @click="toggleAdvanced"
      v-if="hasAdvancedFields"
    >
      {{ showAdvanced ? "Basic" : "Advanced" }}
    </button>
  </div>
</template>

<script>
import helpers from "./../mixins/helpers";

export default {
  name: "field-list-components",
  mixins: [helpers],
  props: {
    root: {
      default: "",
    },
    sectionId: {
      default: "",
    },
    fieldList: {
      default: "",
    },
    value: {
      default: "",
    },
  },

  created() {
    this.filterFieldList();
  },

  watch: {
    fieldList() {
      this.filterFieldList();
    },
    value() {
      this.filterFieldList();
    },
  },

  computed: {
    rootFields() {
      if (!this.root) {
        return this.value;
      }
      if (typeof this.root !== "object") {
        return this.value;
      }

      return this.root;
    },

    visibleFields() {
      const basicFields = {};
      const advancedFields = {};

      // Separate basic and advanced fields
      Object.keys(this.field_list).forEach((key) => {
        if (key !== "isAdvanced") {
          const field = this.field_list[key];
          if (field.field_type === "advanced") {
            advancedFields[key] = field;
          } else {
            basicFields[key] = field;
          }
        }
      });

      // Show basic fields or advanced fields based on the toggle state
      return this.showAdvanced ? { ...basicFields, ...advancedFields } : basicFields;
    },

    hasAdvancedFields() {
      // Check if there are any advanced fields
      return Object.values(this.field_list).some(
        (field) => field.field_type === "advanced"
      );
    },
  },

  data() {
    return {
      field_list: null,
      showAdvanced: false,
    };
  },

  methods: {
    filterFieldList() {
      this.field_list = this.getFilteredFieldList(this.fieldList);
    },
    
    toggleAdvanced() {
      this.showAdvanced = !this.showAdvanced;
    },

    excludeShowIfCondition(field) {
      if (!field) {
        return field;
      }

      if (typeof field !== "object") {
        return field;
      }

      if (field.showIf) {
        delete field["showIf"];
      }

      if (field.show_if) {
        delete field["show_if"];
      }

      return field;
    },

    getFilteredFieldList(field_list) {
      if (!field_list) {
        return field_list;
      }

      let new_fields = JSON.parse(JSON.stringify(this.fieldList));

      for (let field_key in new_fields) {
        if (
          this.value &&
          typeof this.value === "object" &&
          typeof this.value[field_key] !== "undefined"
        ) {
          new_fields[field_key].value = this.value[field_key];
        }
      }

      for (let field_key in new_fields) {
        if (!(new_fields[field_key].showIf || new_fields[field_key].show_if)) {
          continue;
        }

        let show_if_condition = new_fields[field_key].showIf
          ? new_fields[field_key].showIf
          : new_fields[field_key].show_if;

        let checkShowIfCondition = this.checkShowIfCondition({
          root: new_fields,
          condition: show_if_condition,
        });

        if (!checkShowIfCondition.status) {
          delete new_fields[field_key];
        }
      }

      return new_fields;
    },

    update(payload) {
      this.$emit("update", payload);
      this.filterFieldList();
    },
  },
};
</script>
