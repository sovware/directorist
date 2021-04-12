<template>
  <div class="">
    <template v-if="card_templates">
      <div class="cptm-card-top-area cptm-text-center cptm-mb-20">
        <select-field theme="default" :options="theCardBiulderTemplateOptionList" v-model="template_id" />
      </div>

      <component
        :is="theCardBiulderTemplate"
        :field-id="fieldId"
        v-bind="theCurrentTemplateModel"
        :value="theCardBiulderValue"
        @update="updateValue($event)"
      >
      </component>
    </template>

    <template v-else>
      <component
        :is="cardBiulderTemplate"
        :field-id="fieldId"
        :value="value"
        :widgets="widgets"
        :layout="layout"
        :card-options="cardOptions"
        @update="$emit('update', $event)"
      >
      </component>
    </template>
  </div>
</template>

<script>
import Vue from "vue";
import { mapState } from "vuex";

export default {
  name: "card-builder",
  props: {
    fieldId: {
      required: false,
      default: "",
    },
    value: {
      required: false,
      default: null,
    },
    widgets: {
      required: false,
      default: null,
    },
    layout: {
      required: false,
      default: null,
    },
    cardOptions: {
      required: false,
      default: null,
    },
    template: {
      required: false,
      default: "grid-view",
    },
    card_templates: {
      required: false,
    },
  },

  created() {
    this.init();
  },

  computed: {
    ...mapState({
      fields: "fields",
    }),

    theCardBiulderTemplateOptionList() {
      var options = [];
      if (!this.card_templates) {
        return options;
      }

      for (let option in this.card_templates) {
        let label = this.card_templates[option].label;
        options.push({ value: option, label: label ? label : "" });
      }

      return options;
    },

    theCardBiulderTemplate() {
      if (!this.theCurrentTemplateModel) {
        return "";
      }

      return "card-builder-" + this.theCurrentTemplateModel.template + "-field";
    },

    theCardBiulderValue() {
      if (!this.card_templates) {
        return "";
      }
      if (!this.value) {
        return "";
      }
      if (!this.value.template_data) {
        return "";
      }
      if (!this.value.template_data[this.template_id]) {
        return "";
      }

      return this.value.template_data[this.template_id];
    },

    theCurrentTemplateModel() {
      if (!this.card_templates) {
        return false;
      }
      if (!this.template_id) {
        return false;
      }
      if (!this.card_templates[this.template_id]) {
        return false;
      }
      if (!this.card_templates[this.template_id].template) {
        return false;
      }

      return this.card_templates[this.template_id];
    },

    cardBiulderTemplate() {
      const card_biulder_templates = {
        "grid-view": "card-builder-grid-view-field",
        "list-view": "card-builder-list-view-field",
        "listing-header": "card-builder-listing-header-field",
      };

      if (typeof card_biulder_templates[this.template] === "undefined") {
        return "card-builder-grid-view-field";
      }

      return card_biulder_templates[this.template];
    },
  },

  data() {
    return {
      template_id: "",
    };
  },

  methods: {
    init() {
      this.syncTemplateSelectOption();
    },

    syncTemplateSelectOption() {
      var current_option = "";

      let card_template_keys = [];
      if (this.card_templates && typeof this.card_templates === "object") {
        card_template_keys = Object.keys(this.card_templates);
        current_option = card_template_keys[0];
      }

      if (this.value && this.value.active_template) {
        var template_key = "card-builder-grid-view-with-thumbnail-field";
        current_option =
          card_template_keys &&
          card_template_keys.indexOf(this.value.active_template) < 0
            ? current_option
            : this.value.active_template;
      }

      this.template_id = current_option;
    },

    getCurrentTemplate(prop) {
      if (!this.theCurrentTemplateModel) {
        return "";
      }
      if (typeof this.theCurrentTemplateModel[prop] === "undefined") {
        return "";
      }

      return this.theCurrentTemplateModel[prop];
    },

    updateValue(value) {
      var old_value = this.value;

      // If has no old value
      if (!old_value) {
        old_value = {};
      }

      // Update Active Template ID
      old_value.active_template = this.template_id;

      // Update Template Data
      if (!old_value.template_data) {
        old_value.template_data = {};
      }

      old_value.template_data[this.template_id] = value;
      this.$emit("update", old_value);
    },
  },
};
</script>