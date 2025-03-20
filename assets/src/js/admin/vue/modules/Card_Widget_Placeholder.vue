<template>
  <div class="cptm-placeholder-block-wrapper">
    <div class="cptm-widget-option-modal-container">
      <widgets-option-window
        :id="id"
        :availableWidgets="availableWidgets"
        :selectedWidgets="selectedWidgets"
        :active="
          !!(
            showWidgetsOptionWindow &&
            selectedWidgets.length &&
            !showWidgetsPickerWindow
          )
        "
        :maxWidgetInfoText="maxWidgetInfoText"
        @trash-widget="$emit('trash-widget', $event)"
        @close="$emit('close-widgets-option-window')"
      />
    </div>
    <div
      class="cptm-placeholder-block"
      :class="[
        getContainerClass,
        { 'cptm-widget-picker-open': showWidgetsPickerWindow },
      ]"
      @drop.prevent="placeholderOnDrop()"
      @dragover.prevent="$emit('placeholder-dragover-on')"
      @dragenter="placeholderOnDragEnter()"
      @dragleave="placeholderOnDragLeave()"
      @click.prevent="$emit('open-widgets-option-window')"
    >
      <p
        class="cptm-placeholder-label"
        :class="{ hide: displayedWidgets && displayedWidgets.length }"
      >
        {{ label }}
      </p>

      <div class="cptm-widget-insert-area" v-if="!readOnly">
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
            <span class="fa fa-plus"></span>
          </a>
        </div>
      </div>

      <div class="cptm-widget-preview-area" v-if="displayedWidgets.length > 0">
        <template v-for="(widget, widget_index) in displayedWidgets">
          <template v-if="hasValidWidget(widget)">
            <div
              class="cptm-widget-preview-card"
              @click.prevent="setActiveWidget(widget)"
            >
              <component
                :is="availableWidgets[widget].type + '-card-widget'"
                :class="{
                  'cptm-widget-card-disabled':
                    readOnly && !selectedWidgets.includes(widget),
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
                :options="availableWidgets[widget].options"
                :widgetDropable="widgetDropable"
                :hasDisableButton="hasDisableButton"
                :canMove="
                  activeWidgets[widget] &&
                  typeof activeWidgets[widget].can_move !== undefined
                    ? activeWidgets[widget].can_move
                    : true
                "
                :canEdit="
                  activeWidgets[widget] &&
                  widgetHasOptions(activeWidgets[widget])
                "
                @drag="$emit('drag-widget', widget)"
                @drop="$emit('drop-widget', widget)"
                @dragend="$emit('dragend-widget', widget)"
                @edit="$emit('edit-widget', widget)"
                @trash="$emit('trash-widget', widget)"
                :disabled="readOnly && !selectedWidgets.includes(widget)"
                :readOnly="readOnly"
                :editOnClick="editOnClick"
              >
              </component>
            </div>
          </template>
        </template>
      </div>

      <div class="cptm-options-area" v-if="optionWidgetKey === activeWidgetKey">
        <options-window
          :active="optionWidgetKey.length !== 0"
          v-bind="widgetOptionsWindow"
          @update="$emit('update-option-window')"
          @close="$emit('close-option-window')"
        />
      </div>
    </div>
    <span
      class="cptm-widget-card-status"
      :class="this.selectedWidgets.length > 0 ? 'enabled' : 'disabled'"
      v-if="enable_widget"
      @click="$emit('toggle-widget-status')"
    >
      <span
        :class="
          this.selectedWidgets.length > 0 ? 'fa fa-eye' : 'fa fa-eye-slash'
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
    editOnClick: {
      type: Boolean,
      default: false,
    },
    widgetOptionsWindowActiveStatus: {
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

      return this.selectedWidgets.length < this.maxWidget;
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

    optionWidgetKey() {
      return this.widgetOptionsWindow?.widget || null;
    },
  },

  data() {
    return {
      placeholderDragEnter: false,
      activeWidgetKey: "",
    };
  },

  methods: {
    widgetHasOptions(active_widget) {
      if (!active_widget.options && typeof active_widget.options !== "object") {
        return false;
      }
      if (
        !active_widget.options.fields &&
        typeof active_widget.options.fields !== "object"
      ) {
        return false;
      }
      return true;
    },
    placeholderOnDrop() {
      this.placeholderDragEnter = false;
      this.$emit("placeholder-on-drop");
    },
    placeholderOnDragEnter() {
      this.placeholderDragEnter = true;
      this.$emit("placeholder-on-dragenter");
    },
    placeholderOnDragLeave() {
      this.placeholderDragEnter = false;
      this.$emit("placeholder-on-dragleave");
    },
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
    setActiveWidget(widgetKey) {
      if (!this.editOnClick) {
        return;
      }

      this.activeWidgetKey = widgetKey;
    },
  },
};
</script>
