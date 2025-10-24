<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-block-wrap cptm-widget-title-card-wrap"
  >
    <div
      class="cptm-widget-card cptm-widget-title-card cptm-has-widget-control cptm-widget-actions-tools-wrap"
    >
      <div class="cptm-widget-title-block">
        {{ label }}
      </div>

      <span class="cptm-widget-card-disabled-badge" v-if="disabled">
        Disable
      </span>
    </div>

    <div class="cptm-widget-card-options-area" v-if="hasOptions">
      <div
        v-for="(field, field_key) in localOptions.fields"
        :key="field_key"
        class="cptm-field-item"
      >
        <component
          v-if="field?.type"
          :is="`${field.type}-field`"
          v-bind="field"
          @update="updateFieldData($event, field_key)"
        />
      </div>
    </div>

    <span
      class="cptm-widget-badge-trash"
      @click.stop="$emit('trash')"
      v-if="!readOnly"
    >
      <span class="las la-trash-alt"></span>
    </span>
  </div>
</template>

<script>
export default {
  name: "title-card-widget",

  props: {
    label: {
      type: String,
      default: "",
    },
    widgetKey: {
      type: String,
      default: "",
    },
    options: {
      type: Object,
      default: () => ({}),
    },
    activeWidgets: {
      type: Object,
      default: () => ({}),
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    readOnly: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      localOptions: {},
    };
  },

  computed: {
    hasOptions() {
      const { fields } = this.localOptions;
      return (
        fields && typeof fields === "object" && Object.keys(fields).length > 0
      );
    },

    currentActiveWidget() {
      return this.activeWidgets[this.widgetKey];
    },

    currentWidgetFields() {
      return this.currentActiveWidget?.options?.fields;
    },
  },

  watch: {
    options: {
      handler(newOptions) {
        if (newOptions) {
          this.localOptions = { ...newOptions };
        }
      },
      immediate: true,
      deep: true,
    },
  },

  methods: {
    updateFieldData(value, field_key) {
      const currentFields = this.currentWidgetFields;

      if (currentFields?.[field_key]) {
        // Update the field value
        currentFields[field_key].value = value;

        // Emit update event
        this.$emit("update", {
          widgetKey: this.widgetKey,
          updatedWidget: this.currentActiveWidget,
        });
      }
    },
  },
};
</script>
