<template>
  <div class="cptm-placeholder-block-wrapper">
    <!-- <div class="cptm-widget-option-modal-container">
      <widgets-option-window
        :id="id"
        :availableWidgets="availableWidgets"
        :selected-widgets="selectedWidgets"
        @update="handleUpdateOptionWindow"
        @update-active-widget="handleActiveWidgetUpdate"
        :active="
          !!(
            showWidgetsOptionWindow &&
            selectedWidgets?.length &&
            !showWidgetsPickerWindow
          )
        "
        :maxWidgetInfoText="maxWidgetInfoText"
        @trash-widget="$emit('trash-widget', $event)"
        @close="$emit('close-widgets-option-window')"
      />
    </div> -->

    <div
      class="cptm-placeholder-block"
      :class="[
        getContainerClass,
        {
          'cptm-widget-picker-open': showWidgetsPickerWindow,
          enabled: selectedWidgets?.length > 0,
          disabled: selectedWidgets?.length === 0,
        },
      ]"
    >
      <p
        class="cptm-placeholder-label"
        :class="{ hide: displayedWidgets && displayedWidgets?.length }"
      >
        {{ label }}
      </p>

      <div class="cptm-widget-insert-area" v-if="!readOnly" @click.stop>
        <div class="cptm-widget-insert-wrap">
          <div class="cptm-widget-insert-modal-container">
            <widgets-window
              :id="id"
              :availableWidgets="availableWidgets"
              :acceptedWidgets="acceptedWidgets"
              :rejectedWidgets="rejectedWidgets"
              :activeWidgets="activeWidgets"
              :selectedWidgets="selectedWidgets"
              :active="showWidgetsPickerWindow"
              :maxWidget="maxWidget"
              :maxWidgetInfoText="maxWidgetInfoText"
              :bottomAchhor="true"
              @widget-selection="$emit('insert-widget', $event)"
              @close="$emit('close-widgets-picker-window')"
            />
          </div>

          <a
            v-if="canAddMore"
            href="#"
            class="cptm-widget-insert-link"
            @click.prevent="$emit('open-widgets-picker-window')"
          >
            <span class="las la-plus"></span>
          </a>
        </div>
      </div>

      <div class="cptm-widget-preview-area" v-if="displayedWidgets?.length > 0">
        <template v-for="(widget, widget_index) in displayedWidgets">
          <template v-if="hasValidWidget(widget)">
            <div
              class="cptm-widget-preview-card"
              :class="isWidgetActive(widget) ? 'active' : ''"
              @click.prevent="editWidget(widget)"
            >
              <component
                :is="availableWidgets[widget].type + '-card-widget'"
                :class="{
                  'cptm-widget-card-disabled':
                    readOnly && !selectedWidgets?.includes(widget),
                }"
                :key="widget_index"
                :label="
                  typeof availableWidgets[widget] !== 'undefined'
                    ? availableWidgets[widget].label
                    : 'Not Available'
                "
                :icon="
                  typeof availableWidgets[widget].icon === 'string'
                    ? availableWidgets[widget].icon
                    : ''
                "
                :widgetKey="widget"
                :options="getWidgetOptions(widget)"
                :fields="getWidgetFields(widget)"
                :disabled="readOnly && !selectedWidgets?.includes(widget)"
                :readOnly="readOnly"
                @trash="$emit('trash-widget', widget)"
                @edit="editWidget($event)"
              >
              </component>
              <div
                class="cptm-options-area"
                v-if="
                  widgetOptionsWindow.widget === widget &&
                  widgetOptionsWindow.widget !== ''
                "
                @click.stop="handleModalClick"
              >
                <options-window
                  :active="true"
                  v-bind="widgetOptionsWindow"
                  @update="$emit('update-option-window', $event)"
                  @close="handleOptionsWindowClose"
                />
              </div>
            </div>
          </template>
        </template>
      </div>
    </div>

    <span
      class="cptm-widget-card-status"
      :class="this.selectedWidgets?.length > 0 ? 'enabled' : 'disabled'"
      :style="{
        cursor: acceptedWidgets?.length > 0 ? 'pointer' : 'not-allowed',
      }"
      v-if="enable_widget"
      @click="$emit('toggle-widget-status')"
    >
      <span
        :class="
          this.selectedWidgets?.length > 0 ? 'fa fa-eye' : 'fa fa-eye-slash'
        "
      ></span>
    </span>
  </div>
</template>

<script>
export default {
  name: "card-widget-placeholder",
  props: {
    id: {
      type: String,
      default: "",
    },
    containerClass: {
      // type: String,
      default: "",
    },
    placeholderKey: {
      default: "",
    },
    enable_widget: {
      type: Object,
    },
    label: {
      type: String,
      default: "",
    },
    availableWidgets: {
      type: Object,
    },
    activeWidgets: {
      type: Object,
    },
    acceptedWidgets: {
      type: Array,
    },
    rejectedWidgets: {
      type: Array,
    },
    selectedWidgets: {
      type: Array,
    },
    showWidgetsPickerWindow: {
      type: Boolean,
      default: false,
    },
    showWidgetsOptionWindow: {
      type: Boolean,
      default: false,
    },
    widgetDropable: {
      type: Boolean,
      default: false,
    },
    hasDisableButton: {
      type: Boolean,
      default: false,
    },
    maxWidget: {
      type: Number,
      default: 0,
    },
    maxWidgetInfoText: {
      type: String,
      default: "Up to __DATA__ item{s} can be added",
    },
    readOnly: {
      type: Boolean,
      default: false,
    },
    widgetOptionsWindow: {
      type: Object,
      default: () => ({}),
    },
  },

  computed: {
    canAddMore() {
      if (this.enable_widget) {
        return false;
      }

      if (this.maxWidget < 1) {
        return true;
      }

      return this.selectedWidgets?.length < this.maxWidget;
    },

    getContainerClass() {
      let classNames = {
        "drag-enter": this.placeholderDragEnter,
      };

      if (this.placeholderKey) {
        classNames[this.placeholderKey] = true;
      }

      if (typeof this.containerClass === "string") {
        classNames[this.containerClass] = true;
      }

      if (
        this.containerClass &&
        typeof this.containerClass === "object" &&
        !Array.isArray(this.containerClass)
      ) {
        classNames = {
          ...classNames,
          ...this.containerClass,
        };
      }

      return classNames;
    },

    displayedWidgets() {
      return this.readOnly ? this.acceptedWidgets : this.selectedWidgets;
    },

    // Check if a specific widget is currently active
    isWidgetActive() {
      return (widgetKey) => {
        const isActive =
          this.widgetOptionsWindow.widget === widgetKey &&
          this.widgetOptionsWindow.widget !== "" &&
          this.isEditable(widgetKey);

        console.log(`isWidgetActive(${widgetKey}):`, {
          widgetOptionsWindow: this.widgetOptionsWindow,
          widget: this.widgetOptionsWindow.widget,
          widgetKey,
          isEditable: this.isEditable(widgetKey),
          isActive,
        });

        return isActive;
      };
    },
  },

  methods: {
    hasValidWidget(widget_key) {
      if (
        !this.availableWidgets[widget_key] &&
        typeof this.availableWidgets[widget_key] !== "object"
      ) {
        return false;
      }
      if (typeof this.availableWidgets[widget_key].type !== "string") {
        return false;
      }
      return true;
    },

    // Check if a widget is editable (has options)
    isEditable(widgetKey) {
      const widget = this.availableWidgets[widgetKey];
      if (!widget || !widget.options) {
        return false;
      }

      // Check if options is an object or array, not a string
      if (typeof widget.options === "string") {
        return false;
      }

      // Check if options has actual content
      if (Array.isArray(widget.options) && widget.options.length === 0) {
        return false;
      }

      if (
        typeof widget.options === "object" &&
        Object.keys(widget.options).length === 0
      ) {
        return false;
      }

      return true;
    },

    editWidget(widgetKey) {
      console.log("@@editWidget", {
        widgetKey,
        widgetOptionsWindow: this.widgetOptionsWindow,
        currentActiveWidget: this.widgetOptionsWindow.widget,
        isMatched: this.widgetOptionsWindow.widget === widgetKey,
        activeWidget: this.availableWidgets[widgetKey],
      });

      // Check if the click target is inside the modal - if so, don't edit
      if (event && event.target) {
        const modalContainer = event.target.closest(".cptm-options-area");
        if (modalContainer) {
          console.log("Click inside modal - preventing editWidget");
          return;
        }
      }

      // Check if widget is already active - if so, close modal instead of editing
      if (this.widgetOptionsWindow.widget === widgetKey) {
        console.log("Widget already active - closing modal");
        this.$emit("close-option-window");
        return;
      }

      // Check if widget is editable before proceeding
      if (!this.isEditable(widgetKey)) {
        console.log("Widget is not editable:", widgetKey);
        return;
      }

      // Always activate widget options
      console.log("Activating widget options for:", widgetKey);
      // Emit event to parent to activate this widget options
      this.$emit("activate-widget-options", widgetKey);

      this.$emit("edit-widget", widgetKey);
    },

    // Handle clicks inside the modal to prevent event bubbling
    handleModalClick(event) {
      console.log("Modal clicked - preventing event bubbling");
      event.stopPropagation();
    },

    // Handle close button click from options-window child component
    handleOptionsWindowClose(event) {
      console.log("Options window close button clicked");
      // Emit event to parent to close the widget options
      this.$emit("close-option-window");
    },

    // Emit the updated selectedWidgets to the parent component
    handleUpdateOptionWindow(payload) {
      // Emit the updated selectedWidgets to the parent component
      this.$emit("update-option-window", payload);
    },

    // Emit the updated active widget to the parent component
    handleActiveWidgetUpdate({ widgetKey, updatedWidget }) {
      // Emit the updated widget to the parent component
      this.$emit("update-active-widget", { widgetKey, updatedWidget });
    },

    // Get widget options with safety check
    getWidgetOptions(widgetKey) {
      const widget = this.availableWidgets[widgetKey];
      if (!widget || !widget.options) {
        return {};
      }

      // Ensure options is an object or array, not a string
      if (typeof widget.options === "string") {
        return {};
      }

      return widget.options;
    },

    // Get widget fields with safety check
    getWidgetFields(widgetKey) {
      const widget = this.availableWidgets[widgetKey];
      if (!widget || !widget.fields) {
        return {};
      }

      // Ensure fields is an object or array, not a string
      if (typeof widget.fields === "string") {
        return {};
      }

      return widget.fields;
    },
  },

  watch: {
    output_data() {
      this.$emit("update", this.output_data);
    },

    // Removed watch for activeWidgetKey since it's now managed by parent
  },
};
</script>
