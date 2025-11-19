<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-inline-wrap cptm-widget-badge-card-wrap"
  >
    <div
      class="cptm-widget-card cptm-has-widget-control cptm-widget-actions-tools-wrap"
    >
      <div class="cptm-placeholder-author-thumb">
        <svg
          width="40"
          height="40"
          viewBox="0 0 40 40"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M35.1667 20.8327L37.5 23.1827L26.6167 34.166L20.8333 28.3327L23.1667 25.9827L26.6167 29.4493L35.1667 20.8327ZM16.6667 28.3327L21.6667 33.3327H5V29.9993C5 26.316 10.9667 23.3327 18.3333 23.3327L21.4833 23.516L16.6667 28.3327ZM18.3333 6.66602C20.1014 6.66602 21.7971 7.36839 23.0474 8.61864C24.2976 9.86888 25 11.5646 25 13.3327C25 15.1008 24.2976 16.7965 23.0474 18.0467C21.7971 19.297 20.1014 19.9993 18.3333 19.9993C16.5652 19.9993 14.8695 19.297 13.6193 18.0467C12.369 16.7965 11.6667 15.1008 11.6667 13.3327C11.6667 11.5646 12.369 9.86888 13.6193 8.61864C14.8695 7.36839 16.5652 6.66602 18.3333 6.66602Z"
            fill="#141921"
          />
        </svg>
        <a
          href="#"
          class="cptm-widget-action-link cptm-placeholder-author-thumb-options"
          @click.stop="toggleOptions"
          v-if="isAvailableOptions"
        >
          <span class="las la-cog"></span>
        </a>
        <a
          href="#"
          class="cptm-placeholder-author-thumb-trash"
          @click.stop="$emit('trash')"
          v-else
        >
          <span class="las la-trash-alt"></span>
        </a>
      </div>
    </div>

    <!-- Collapsible Options Section -->
    <div class="cptm-widget-action-modal-container" v-if="showOptions">
      <div
        class="cptm-option-card cptm-animation-slide-up"
        :class="{ active: showOptions }"
      >
        <div class="cptm-option-card-header">
          <div class="cptm-option-card-header-title-section">
            <h3 class="cptm-option-card-header-title">Edit Element</h3>
            <div class="cptm-header-action-area">
              <a
                href="#"
                class="cptm-header-action-link cptm-header-action-close"
                @click.stop="toggleOptions"
              >
                <span class="fa fa-times"></span>
              </a>
            </div>
          </div>
        </div>

        <div class="cptm-option-card-body">
          <!-- Avatar Toggle -->
          <div class="cptm-input-toggle-wrap">
            <div class="cptm-input-toggle-content">
              <label>
                <span>Avatar</span>
              </label>
            </div>
            <div class="directorist_vertical-align-m cptm-input-toggle-btn">
              <div class="directorist_item">
                <label
                  class="cptm-input-toggle"
                  :for="`avatar-toggle-${widgetKey}`"
                  :class="{ active: isEnabled }"
                ></label>
                <input
                  type="checkbox"
                  :id="`avatar-toggle-${widgetKey}`"
                  :name="`avatar-toggle-${widgetKey}`"
                  class="cptm-toggle-input"
                  @change="handleToggleChange"
                  v-model="isEnabled"
                />
              </div>
            </div>
          </div>

          <!-- Position Options -->
          <div
            class="cptm-option-card-body-item"
            v-if="isAvailableOptions && hasPositionField"
          >
            <label class="cptm-option-card-body-item-label">Position</label>
            <div class="cptm-option-card-body-item-options">
              <component
                v-for="(field, field_key) in optionFields"
                :key="field_key"
                :is="field.type + '-field'"
                v-bind="field"
                @update="updateFieldData($event, field_key)"
                v-if="
                  field_key === 'position' ||
                  field_key === 'align' ||
                  field.label === 'Position' ||
                  field.label === 'Align'
                "
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "avatar-card-widget",
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

    readOnly: {
      type: Boolean,
      default: false,
    },

    // Add activeWidget prop to get the complete widget data
    activeWidgets: {
      type: Object,
    },

    // Add selectedWidgets to check if widget is selected
    selectedWidgets: {
      type: Array,
      default: () => [],
    },

    // Add availableWidgets to access widget data
    availableWidgets: {
      type: Object,
      default: () => ({}),
    },
  },

  data() {
    return {
      localOptions: null,
      showOptions: false,
      isEnabled: true,
    };
  },

  created() {
    this.init();
    this.checkWidgetStatus();
  },

  watch: {
    options: {
      handler(newOptions) {
        if (newOptions) {
          this.localOptions = JSON.parse(JSON.stringify(newOptions));
        }
      },
      deep: true,
    },

    selectedWidgets: {
      handler() {
        this.checkWidgetStatus();
      },
      deep: true,
    },
  },

  computed: {
    // Check if options has value and contains fields
    isAvailableOptions() {
      if (!this.localOptions || typeof this.localOptions !== "object") {
        return false;
      }

      if (
        !this.localOptions.fields ||
        typeof this.localOptions.fields !== "object"
      ) {
        return false;
      }

      // Check if fields object has at least one property
      return Object.keys(this.localOptions.fields).length > 0;
    },

    // Get the fields from options
    optionFields() {
      if (!this.isAvailableOptions) {
        return {};
      }

      return this.localOptions.fields;
    },

    // Check if position/align field exists
    hasPositionField() {
      if (!this.isAvailableOptions) {
        return false;
      }

      const fields = this.localOptions.fields;
      return (
        fields.position ||
        fields.align ||
        Object.keys(fields).some(
          (key) =>
            fields[key].label === "Position" ||
            fields[key].label === "Align" ||
            key.toLowerCase().includes("position") ||
            key.toLowerCase().includes("align"),
        )
      );
    },
  },

  methods: {
    init() {
      if (this.options) {
        this.localOptions = JSON.parse(JSON.stringify(this.options));
      }
    },

    // Check if widget is currently selected/enabled
    checkWidgetStatus() {
      if (this.selectedWidgets && Array.isArray(this.selectedWidgets)) {
        this.isEnabled = this.selectedWidgets.includes(this.widgetKey);
      } else if (this.activeWidgets) {
        this.isEnabled =
          typeof this.activeWidgets[this.widgetKey] !== "undefined";
      }
    },

    // Toggle Options section visibility
    toggleOptions() {
      this.showOptions = !this.showOptions;
    },

    // Handle toggle change for enable/disable widget
    handleToggleChange() {
      if (this.isEnabled) {
        // Widget is enabled - add to selectedWidgets
        this.$emit("insert-widget", {
          key: this.widgetKey,
          selected_widgets: [this.widgetKey],
        });
      } else {
        // Widget is disabled - emit trash to remove
        this.$emit("trash");
      }
    },

    // Update field data when field value changes
    updateFieldData(value, field_key) {
      // Update the local field value
      if (this.localOptions && this.localOptions.fields) {
        this.localOptions.fields[field_key].value = value;
      }

      // Get the current widget from activeWidgets
      const currentWidget = this.activeWidgets[this.widgetKey];

      // Deep clone to avoid mutations
      const updatedWidget = JSON.parse(JSON.stringify(currentWidget));

      // Update the specific field value in the cloned widget
      if (updatedWidget.options && updatedWidget.options.fields) {
        if (!updatedWidget.options.fields[field_key]) {
          updatedWidget.options.fields[field_key] = {};
        }
        updatedWidget.options.fields[field_key].value = value;
      }

      console.log('updateFieldData', {value, field_key, updatedWidget});

      // Emit the updated widget data to parent with correct structure
      this.$emit("update", {
        widgetKey: this.widgetKey,
        updatedWidget: updatedWidget,
      });
    },
  },
};
</script>
