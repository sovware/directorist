<template>
  <div class="cptm-placeholder-block-wrapper">
    <div
      class="cptm-placeholder-block"
      :class="[
        getContainerClass,
        {
          'cptm-widget-picker-open':
            showWidgetsPickerWindow || showWidgetsOptionWindow,
          enabled: hasSelectedWidgets,
          disabled: !hasSelectedWidgets,
        },
      ]"
    >
      <p class="cptm-placeholder-label" :class="{ hide: hasDisplayedWidgets }">
        {{ label }}
      </p>

      <div class="cptm-widget-actions-area" v-if="!readOnly" @click.stop>
        <div class="cptm-widget-actions-wrap">
          <!-- Widgets Option Window -->
          <div
            class="cptm-widget-action-modal-container cptm-widget-option-modal-container"
            :class="{
              active:
                showWidgetsOptionWindow &&
                selectedWidgets?.length &&
                !showWidgetsPickerWindow,
            }"
          >
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
          </div>

          <!-- Widgets Picker Window -->
          <div
            class="cptm-widget-action-modal-container cptm-widget-insert-modal-container"
            :class="{
              active:
                showWidgetsPickerWindow &&
                selectedWidgets?.length &&
                !showWidgetsOptionWindow,
            }"
          >
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

          <!-- Widgets Actions -->
          <div class="cptm-widget-actions">
            <a
              v-if="canOpenSettings && selectedWidgets?.length"
              href="#"
              class="cptm-widget-action-link"
              @click.prevent="handleSettingsClick"
            >
              <span class="las la-cog"></span>
            </a>
            <a
              v-if="canAddMore"
              href="#"
              class="cptm-widget-action-link"
              @click.prevent="handleInsertClick"
            >
              <span class="las la-plus"></span>
            </a>
          </div>
        </div>
      </div>

      <!-- Widgets Preview Area -->
      <div class="cptm-widget-preview-area" v-if="hasDisplayedWidgets">
        <!-- With Drag and Drop Preview -->
        <Container
          @drop="onWidgetsDrop($event)"
          @drag-start="onWidgetDragStart($event)"
          @drag-end="onWidgetDragEnd()"
          :lock-axis="dragAxis"
          :orientation="dragAxis === 'x' ? 'horizontal' : 'vertical'"
          :data-orientation="dragAxis === 'x' ? 'horizontal' : 'vertical'"
          group-name="card-widgets"
          drag-handle-selector=".widget-drag-handle"
          :get-child-payload="getChildPayload"
          :class="['cptm-widget-preview-container']"
          v-if="!readOnly && canDragAndDrop"
        >
          <Draggable
            v-for="(widget, widget_index) in displayedWidgets"
            :key="widget_index"
            v-if="hasValidWidget(widget)"
            :data="{ widget, index: widget_index }"
            :data-widget="widget"
            :class="[
              `dndrop-draggable-wrapper dndrop-draggable-wrapper-${widget}`,
              {
                'is-dragging': isDragging(widget),
                'is-drag-end': isDragEnd(widget),
              },
            ]"
          >
            <div
              class="cptm-widget-preview-card"
              :class="{
                active: isWidgetActive(widget),
                [`cptm-widget-preview-card-${widget}`]: true,
              }"
              @click.prevent="editWidget(widget)"
            >
              <!-- Drag Handle -->
              <span
                class="cptm-widget-drag-handle widget-drag-handle"
                v-if="canDragAndDrop && !readOnly && hasMultipleWidgets"
              >
                <span class="uil uil-draggabledots"></span>
              </span>

              <component
                :is="`${availableWidgets[widget].type}-card-widget`"
                :class="{
                  'cptm-widget-card-disabled':
                    readOnly && !isWidgetSelected(widget),
                }"
                :label="getWidgetLabel(widget)"
                :icon="getWidgetIcon(widget)"
                :widgetKey="widget"
                :options="getWidgetOptions(widget)"
                :fields="getWidgetFields(widget)"
                :disabled="readOnly && !isWidgetSelected(widget)"
                :readOnly="readOnly"
                :activeWidgets="activeWidgets"
                @trash="$emit('trash-widget', widget)"
                @edit="editWidget($event)"
                @update="handleActiveWidgetUpdate"
              />

              <div
                v-if="shouldShowOptionsArea(widget)"
                class="cptm-options-area"
                @click.stop="handleModalClick"
              >
                <options-window
                  :active="true"
                  v-bind="widgetOptionsWindow"
                  @close="handleOptionsWindowClose"
                />
              </div>
            </div>
          </Draggable>
        </Container>

        <!-- Without Drag and Drop Preview -->
        <div
          class="cptm-widget-preview-container"
          v-if="!canDragAndDrop && !readOnly"
        >
          <div
            v-for="(widget, widget_index) in displayedWidgets"
            :key="widget_index"
            class="cptm-widget-preview-card no-dndrop"
            :class="`cptm-widget-preview-card-${widget}`"
            v-if="hasValidWidget(widget)"
          >
            <component
              :is="`${availableWidgets[widget].type}-card-widget`"
              :class="{
                'cptm-widget-card-disabled':
                  readOnly && !isWidgetSelected(widget),
              }"
              :label="getWidgetLabel(widget)"
              :icon="getWidgetIcon(widget)"
              :widgetKey="widget"
              :options="getWidgetOptions(widget)"
              :fields="getWidgetFields(widget)"
              :disabled="readOnly && !isWidgetSelected(widget)"
              :readOnly="readOnly"
              :activeWidgets="activeWidgets"
              @trash="$emit('trash-widget', widget)"
              @edit="editWidget($event)"
              @update="handleActiveWidgetUpdate"
            />
          </div>
        </div>

        <!-- Read Only Preview -->
        <div
          class="cptm-widget-preview-card"
          v-for="(widget, widget_index) in displayedWidgets"
          v-if="readOnly && hasValidWidget(widget)"
        >
          <component
            :is="`${availableWidgets[widget].type}-card-widget`"
            :class="{
              'cptm-widget-card-disabled':
                readOnly && !isWidgetSelected(widget),
            }"
            :label="getWidgetLabel(widget)"
            :icon="getWidgetIcon(widget)"
            :widgetKey="widget"
            :disabled="readOnly && !isWidgetSelected(widget)"
            :readOnly="readOnly"
          />
        </div>
      </div>
    </div>

    <span
      v-if="enable_widget"
      class="cptm-widget-card-status"
      :class="hasSelectedWidgets ? 'enabled' : 'disabled'"
      :style="{ cursor: hasAcceptedWidgets ? 'pointer' : 'not-allowed' }"
      @click="$emit('toggle-widget-status')"
    >
      <span :class="hasSelectedWidgets ? 'fa fa-eye' : 'fa fa-eye-slash'" />
    </span>
  </div>
</template>

<script>
import { Container, Draggable } from "vue-dndrop";

export default {
  name: "card-widget-placeholder",

  components: {
    Container,
    Draggable,
  },

  data() {
    return {
      draggingWidget: null,
      dragOverWidget: null,
      dragEndWidget: null,
    };
  },

  props: {
    id: { type: String, default: "" },
    containerClass: { default: "" },
    placeholderKey: { default: "" },
    enable_widget: { type: Object },
    label: { type: String, default: "" },
    availableWidgets: { type: Object },
    activeWidgets: { type: Object },
    acceptedWidgets: { type: Array },
    rejectedWidgets: { type: Array },
    selectedWidgets: { type: Array },
    showWidgetsPickerWindow: { type: Boolean, default: false },
    showWidgetsOptionWindow: { type: Boolean, default: false },
    canOpenSettings: { type: Boolean, default: false },
    maxWidget: { type: Number, default: 0 },
    maxWidgetInfoText: {
      type: String,
      default: "Up to __DATA__ item{s} can be added",
    },
    readOnly: { type: Boolean, default: false },
    canDragAndDrop: { type: Boolean, default: false },
    dragAxis: {
      type: String,
      default: "y",
      validator: (value) => ["x", "y", "xy"].includes(value),
    },
    widgetOptionsWindow: { type: Object, default: () => ({}) },
  },

  computed: {
    hasSelectedWidgets() {
      return this.selectedWidgets?.length > 0;
    },

    hasDisplayedWidgets() {
      return this.displayedWidgets?.length > 0;
    },

    hasAcceptedWidgets() {
      return this.acceptedWidgets?.length > 0;
    },

    canAddMore() {
      if (this.enable_widget) return false;
      if (this.maxWidget < 1) return true;
      return this.selectedWidgets?.length < this.maxWidget;
    },

    getContainerClass() {
      const classNames = { "drag-enter": this.placeholderDragEnter };

      if (this.placeholderKey) {
        classNames[this.placeholderKey] = true;
      }

      if (typeof this.containerClass === "string") {
        classNames[this.containerClass] = true;
      } else if (
        this.containerClass &&
        typeof this.containerClass === "object" &&
        !Array.isArray(this.containerClass)
      ) {
        Object.assign(classNames, this.containerClass);
      }

      return classNames;
    },

    displayedWidgets() {
      return this.readOnly ? this.acceptedWidgets : this.selectedWidgets;
    },

    hasMultipleWidgets() {
      return this.selectedWidgets && this.selectedWidgets.length > 1;
    },

    isDragging() {
      return (widget) => this.draggingWidget === widget;
    },

    isDragEnd() {
      return (widget) => this.dragEndWidget === widget;
    },
  },

  methods: {
    hasValidWidget(widget_key) {
      const widget = this.availableWidgets[widget_key];
      return (
        widget && typeof widget === "object" && typeof widget.type === "string"
      );
    },

    isWidgetSelected(widget) {
      return this.selectedWidgets?.includes(widget);
    },

    isWidgetActive(widgetKey) {
      return (
        this.widgetOptionsWindow.widget === widgetKey &&
        this.widgetOptionsWindow.widget !== "" &&
        this.isEditable(widgetKey)
      );
    },

    isEditable(widgetKey) {
      const widget = this.availableWidgets[widgetKey];
      if (!widget?.options) return false;

      const { options } = widget;
      if (typeof options === "string") return false;
      if (Array.isArray(options) && options.length === 0) return false;
      if (typeof options === "object" && Object.keys(options).length === 0)
        return false;

      return true;
    },

    shouldShowOptionsArea(widget) {
      return (
        this.widgetOptionsWindow.widget === widget &&
        this.widgetOptionsWindow.widget !== ""
      );
    },

    getWidgetLabel(widget) {
      return this.availableWidgets[widget]?.label || "Not Available";
    },

    getWidgetIcon(widget) {
      const icon = this.availableWidgets[widget]?.icon;
      return typeof icon === "string" ? icon : "";
    },

    getWidgetOptions(widgetKey) {
      const widget = this.availableWidgets[widgetKey];
      if (!widget?.options || typeof widget.options === "string") return {};
      return widget.options;
    },

    getWidgetFields(widgetKey) {
      const widget = this.availableWidgets[widgetKey];
      if (!widget?.fields || typeof widget.fields === "string") return {};
      return widget.fields;
    },

    editWidget(widgetKey) {
      // Check if click target is inside modal
      if (event?.target?.closest(".cptm-options-area")) {
        return;
      }

      // Check if widget is already active
      if (this.widgetOptionsWindow.widget === widgetKey) {
        this.$emit("close-option-window");
        return;
      }

      // Check if widget is editable
      if (!this.isEditable(widgetKey)) {
        return;
      }

      this.$emit("activate-widget-options", widgetKey);
      this.$emit("edit-widget", widgetKey);
    },

    handleModalClick(event) {
      event.stopPropagation();
    },

    handleOptionsWindowClose() {
      this.$emit("close-option-window");
    },

    handleUpdateOptionWindow(payload) {
      this.$emit("update", payload.selectedWidgets);
    },

    handleActiveWidgetUpdate({ widgetKey, updatedWidget }) {
      this.$emit("update-active-widget", { widgetKey, updatedWidget });
    },

    /**
     * Handle settings button click with modal mutual exclusion
     * Closes insert modal if open, then opens settings modal
     */
    handleSettingsClick() {
      // Close insert modal if it's open
      if (this.showWidgetsPickerWindow) {
        this.$emit("close-widgets-picker-window");
      }
      // Open settings modal
      this.$emit("open-widgets-option-window");
    },

    /**
     * Handle insert button click with modal mutual exclusion
     * Closes settings modal if open, then opens insert modal
     */
    handleInsertClick() {
      // Close settings modal if it's open
      if (this.showWidgetsOptionWindow) {
        this.$emit("close-widgets-option-window");
      }
      // Open insert modal
      this.$emit("open-widgets-picker-window");
    },

    /**
     * Get child payload for drag and drop operations
     */
    getChildPayload(index) {
      const widget = this.displayedWidgets[index];

      return {
        id: widget,
        index: index,
        type: "widget",
        axis: this.dragAxis,
      };
    },

    // Handle drag start for smooth transitions
    onWidgetDragStart(dragResult) {
      const { payload } = dragResult;

      // Set the dragging widget
      if (payload && payload.id) {
        this.draggingWidget = payload.id;
      }
    },

    // Handle drag end to reset drag states
    onWidgetDragEnd() {
      // Set drag end state briefly before clearing
      if (this.draggingWidget) {
        this.dragEndWidget = this.draggingWidget;
        this.draggingWidget = null;
      }
    },

    /**
     * Handle widget drop operations with optimized performance and maintainability
     */
    onWidgetsDrop(dropResult) {
      // Clear drag states immediately
      this.draggingWidget = null;
      this.dragEndWidget = null;

      const { removedIndex, addedIndex } = dropResult;

      // Validate drop operation
      if (removedIndex === null || addedIndex === null) return;
      if (!this.canDragAndDrop || this.readOnly || !this.hasMultipleWidgets)
        return;

      // Handle standard drag operations
      this.handleStandardDrop(dropResult);
    },

    /**
     * Handle standard drop operations (vertical or horizontal without special widgets)
     * Optimized for simplicity and performance
     */
    handleStandardDrop(dropResult) {
      const { removedIndex, addedIndex } = dropResult;
      const widgetsCopy = [...this.selectedWidgets];

      // Clamp indices within array bounds
      const targetIndex = Math.max(0, Math.min(addedIndex, widgetsCopy.length));

      // Perform reordering
      const [movedItem] = widgetsCopy.splice(removedIndex, 1);
      widgetsCopy.splice(targetIndex, 0, movedItem);

      this.$emit("update", widgetsCopy);
    },
  },

  watch: {
    output_data() {
      this.$emit("update", this.output_data);
    },
  },
};
</script>
