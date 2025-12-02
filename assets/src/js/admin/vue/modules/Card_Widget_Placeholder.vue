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
      <!-- Placeholder Author Thumb -->
      <span
        class="cptm-placeholder-author-thumb cptm-placeholder-author-avatar-placeholder"
        v-if="canOpenAvatarSettings"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="32"
          height="32"
          viewBox="0 0 32 32"
          fill="none"
        >
          <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M16.0001 5.33268C13.4228 5.33268 11.3334 7.42202 11.3334 9.99935C11.3334 12.5767 13.4228 14.666 16.0001 14.666C18.5774 14.666 20.6668 12.5767 20.6668 9.99935C20.6668 7.42202 18.5774 5.33268 16.0001 5.33268ZM8.66678 9.99935C8.66678 5.94926 11.95 2.66602 16.0001 2.66602C20.0502 2.66602 23.3334 5.94926 23.3334 9.99935C23.3334 14.0494 20.0502 17.3327 16.0001 17.3327C11.95 17.3327 8.66678 14.0494 8.66678 9.99935ZM12.4351 19.3326C12.5112 19.3326 12.5884 19.3327 12.6668 19.3327H19.3334C19.4118 19.3327 19.489 19.3326 19.5651 19.3326C21.2015 19.332 22.3188 19.3316 23.2687 19.6197C25.3994 20.2661 27.0667 21.9334 27.713 24.0641C28.0012 25.014 28.0008 26.1313 28.0002 27.7677C28.0001 27.8438 28.0001 27.921 28.0001 27.9993C28.0001 28.7357 27.4032 29.3327 26.6668 29.3327C25.9304 29.3327 25.3334 28.7357 25.3334 27.9993C25.3334 26.0416 25.319 25.3583 25.1612 24.8382C24.7734 23.5598 23.773 22.5594 22.4946 22.1716C21.9745 22.0138 21.2912 21.9993 19.3334 21.9993H12.6668C10.709 21.9993 10.0257 22.0138 9.50564 22.1716C8.22723 22.5594 7.22682 23.5598 6.83902 24.8382C6.68125 25.3583 6.66678 26.0416 6.66678 27.9993C6.66678 28.7357 6.06982 29.3327 5.33344 29.3327C4.59706 29.3327 4.00011 28.7357 4.00011 27.9993C4.00011 27.921 4.00008 27.8438 4.00005 27.7677C3.99945 26.1313 3.99904 25.014 4.28718 24.0641C4.93351 21.9334 6.60087 20.2661 8.73154 19.6197C9.68141 19.3316 10.7988 19.332 12.4351 19.3326Z"
            fill="#141921"
          />
        </svg>
      </span>

      <!-- Placeholder Label -->
      <p
        class="cptm-placeholder-label"
        :class="{ hide: hasDisplayedWidgets }"
        v-else
      >
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
                (selectedWidgets?.length || canOpenAvatarSettings) &&
                !showWidgetsPickerWindow,
            }"
          >
            <!-- Avatar Settings Window (when canOpenAvatarSettings is true) -->
            <avatar-settings-window
              v-if="canOpenAvatarSettings"
              :id="id"
              :availableWidgets="availableWidgets"
              :selected-widgets="selectedWidgets"
              @update="handleUpdateOptionWindow"
              @update-active-widget="handleActiveWidgetUpdate"
              @insert-widget="$emit('insert-widget', $event)"
              :active="!!(showWidgetsOptionWindow && !showWidgetsPickerWindow)"
              :maxWidgetInfoText="maxWidgetInfoText"
              @trash-widget="$emit('trash-widget', $event)"
              @close="$emit('close-widgets-option-window')"
            />

            <!-- Normal Widgets Option Window -->
            <widgets-option-window
              v-else
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
            <!-- Avatar Settings Button -->
            <a
              v-if="canOpenAvatarSettings"
              href="#"
              class="cptm-widget-action-link"
              @click.prevent="handleAvatarSettingsClick"
            >
              <span class="las la-cog"></span>
            </a>
            <a
              v-if="
                canOpenSettings &&
                selectedWidgets?.length &&
                !canOpenAvatarSettings
              "
              href="#"
              class="cptm-widget-action-link"
              @click.prevent="handleSettingsClick"
            >
              <span class="las la-cog"></span>
            </a>
            <a
              v-if="canAddMore && !canOpenAvatarSettings"
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
      <div
        class="cptm-widget-preview-area"
        v-if="hasDisplayedWidgets && !canOpenAvatarSettings"
      >
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
                :selectedWidgets="selectedWidgets"
                :availableWidgets="availableWidgets"
                @trash="$emit('trash-widget', widget)"
                @insert-widget="$emit('insert-widget', $event)"
                @edit="editWidget($event)"
                @update="handleActiveWidgetUpdate"
              />

              <div
                class="cptm-options-area"
                @click.stop="handleModalClick"
              >
                <options-window
                  :active=isWidgetActive(widget)
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
              :selectedWidgets="selectedWidgets"
              :availableWidgets="availableWidgets"
              @trash="$emit('trash-widget', widget)"
              @insert-widget="$emit('insert-widget', $event)"
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
    canOpenAvatarSettings: { type: Boolean, default: false },
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

    handleAvatarSettingsClick() {
      if (this.showWidgetsPickerWindow) {
        this.$emit("close-widgets-picker-window");
      }
      this.$emit("open-widgets-option-window");
    },

    /**
     * Handle insert button click with modal mutual exclusion
     * Closes settings modal if open, then opens insert modal
     */
    handleInsertClick() {
      // Special case for single accepted widget
      if (this.acceptedWidgets.length === 1) {
        // Don't mutate props directly - emit event to parent instead
        // Create a new array with the new widget added
        const updatedWidgets = [...(this.selectedWidgets || [])];

        // Only add if not already present
        if (!updatedWidgets.includes(this.acceptedWidgets[0])) {
          updatedWidgets.push(this.acceptedWidgets[0]);
        }

        // Emit insert-widget event to parent (same as normal flow)
        this.$emit("insert-widget", {
          key: this.acceptedWidgets[0],
          selected_widgets: updatedWidgets,
        });

        return;
      }

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
