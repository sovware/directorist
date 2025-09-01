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

          <div class="cptm-widget-actions-area">
            <a
              v-if="canOpenSettings && selectedWidgets?.length"
              href="#"
              class="cptm-widget-action-link"
              @click.prevent="$emit('open-widgets-option-window')"
            >
              <span class="las la-cog"></span>
            </a>
            <a
              v-if="canAddMore"
              href="#"
              class="cptm-widget-action-link"
              @click.prevent="$emit('open-widgets-picker-window')"
            >
              <span class="las la-plus"></span>
            </a>
          </div>
        </div>
      </div>

      <div class="cptm-widget-preview-area" v-if="hasDisplayedWidgets">
        <Container
          @drop="onWidgetsDrop($event)"
          @drag-start="onDragStart"
          @drag-end="onDragEnd"
          @drag-over="onDragOver"
          :lock-axis="dragAxis"
          :orientation="dragAxis === 'x' ? 'horizontal' : 'vertical'"
          :data-orientation="dragAxis === 'x' ? 'horizontal' : 'vertical'"
          group-name="card-widgets"
          drag-handle-selector=".widget-drag-handle"
          :should-accept-drop="shouldAcceptDrop"
          :get-child-payload="getChildPayload"
          :class="[
            'cptm-widget-preview-container',
            { 'has-non-draggable-widgets': hasNonDraggableWidgets },
          ]"
          v-if="!readOnly"
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
                'is-drag-over': isDragOver(widget),
                'is-drag-end': isDragEnd(widget),
              },
            ]"
          >
            <div
              class="cptm-widget-preview-card"
              :class="{
                active: isWidgetActive(widget),
                [`cptm-widget-preview-card-${widget}`]: true,
                'non-draggable-widget': isNonDraggableWidget(widget),
              }"
              @click.prevent="editWidget(widget)"
            >
              <!-- Drag Handle -->
              <span
                class="cptm-widget-drag-handle widget-drag-handle"
                v-if="
                  canDragAndDrop &&
                  !readOnly &&
                  hasMultipleWidgets &&
                  !isNonDraggableWidget(widget)
                "
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

    hasNonDraggableWidgets() {
      return (
        this.displayedWidgets &&
        this.displayedWidgets.some((widget) =>
          this.isNonDraggableWidget(widget),
        )
      );
    },

    isDragging() {
      return (widget) => this.draggingWidget === widget;
    },

    isDragOver() {
      return (widget) => this.dragOverWidget === widget;
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
        this.widgetOptionsWindow.widget !== "" &&
        widget !== "listing_title"
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
      console.log("@@editWidget", {
        widgetKey,
        widgetOptionsWindow: this.widgetOptionsWindow,
      });

      // Check if click target is inside modal
      if (event?.target?.closest(".cptm-options-area")) {
        console.log("Click inside modal - preventing editWidget");
        return;
      }

      // Check if widget is already active
      if (
        this.widgetOptionsWindow.widget === widgetKey &&
        widgetKey !== "listing_title"
      ) {
        console.log("Widget already active - closing modal");
        this.$emit("close-option-window");
        return;
      }

      // Check if widget is editable
      if (!this.isEditable(widgetKey)) {
        console.log("Widget is not editable:", widgetKey);
        return;
      }

      this.$emit("activate-widget-options", widgetKey);
      this.$emit("edit-widget", widgetKey);
    },

    handleModalClick(event) {
      console.log("Modal clicked - preventing event bubbling");
      event.stopPropagation();
    },

    handleOptionsWindowClose() {
      console.log("Options window close button clicked");
      this.$emit("close-option-window");
    },

    handleUpdateOptionWindow(payload) {
      this.$emit("update", payload.selectedWidgets);
    },

    handleActiveWidgetUpdate({ widgetKey, updatedWidget }) {
      this.$emit("update-active-widget", { widgetKey, updatedWidget });
    },

    // Check if widget is non-draggable and should stay in fixed position
    isNonDraggableWidget(widgetKey) {
      // listing_title widget is not draggable and should always stay at index 0
      return widgetKey === "listing_title";
    },

    // Get child payload for better drag and drop control
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
    onDragStart(dragResult) {
      const { payload } = dragResult;
      console.log("Drag started:", payload);

      // Set the dragging widget
      if (payload && payload.id) {
        this.draggingWidget = payload.id;
      }

      // Add smooth transition class to the dragged item
      if (payload && payload.axis === "x") {
        // For horizontal dragging, ensure smooth movement
        this.$nextTick(() => {
          const draggedElement = document.querySelector(
            `[data-widget="${payload.id}"]`,
          );
          if (draggedElement) {
            draggedElement.style.transition = "transform 0.2s ease";
          }
        });
      }
    },

    // Handle drag over to show drop target
    onDragOver(dragResult) {
      const { payload } = dragResult;
      if (payload && payload.id) {
        this.dragOverWidget = payload.id;
      }
    },

    // Handle drag end to reset drag states
    onDragEnd() {
      console.log("Drag ended, resetting drag states");

      // Set drag end state briefly before clearing
      if (this.draggingWidget) {
        this.dragEndWidget = this.draggingWidget;
      }

      // Clear drag states after a brief delay
      setTimeout(() => {
        this.draggingWidget = null;
        this.dragOverWidget = null;
        this.dragEndWidget = null;
      }, 100);
    },

    // Check if a drop should be accepted at a specific position
    shouldAcceptDrop(dropResult) {
      const { addedIndex } = dropResult;

      // Don't allow dropping at index 0 (where listing_title should always be)
      if (addedIndex === 0) {
        return false;
      }

      // Don't allow dropping on top of listing_title if it's at index 0
      if (addedIndex === 1 && this.displayedWidgets[0] === "listing_title") {
        return false;
      }

      return true;
    },

    // Widget Drop Handler
    onWidgetsDrop(dropResult) {
      console.log("Drop result:", dropResult);
      console.log("Drag axis:", this.dragAxis);

      // Clear all drag states on drop
      this.draggingWidget = null;
      this.dragOverWidget = null;
      this.dragEndWidget = null;

      const { removedIndex, addedIndex } = dropResult;

      if (removedIndex === null || addedIndex === null) return;

      // Only allow reordering if drag and drop is enabled, not in read-only mode, and has multiple widgets
      if (!this.canDragAndDrop || this.readOnly || !this.hasMultipleWidgets)
        return;

      // Get the widget that was moved from the drop result
      const movedWidgetKey = this.displayedWidgets[removedIndex];

      // Prevent dragging of non-draggable widgets
      if (this.isNonDraggableWidget(movedWidgetKey)) {
        console.log("Cannot drag non-draggable widget:", movedWidgetKey);
        return;
      }

      // Clone the array (no mutation)
      const updatedWidgets = [...this.selectedWidgets];

      // Ensure listing_title stays at index 0
      const listingTitleIndex = updatedWidgets.indexOf("listing_title");
      if (listingTitleIndex > 0) {
        // If listing_title is not at index 0, move it back to first position
        const [listingTitle] = updatedWidgets.splice(listingTitleIndex, 1);
        updatedWidgets.unshift(listingTitle);
      }

      // For horizontal dragging, we need to handle the position calculation differently
      if (this.dragAxis === "x") {
        console.log(
          "Horizontal dragging - removing from index:",
          removedIndex,
          "adding at index:",
          addedIndex,
        );
        console.log("Original array:", [...this.selectedWidgets]);

        // For horizontal dragging, we need to ensure the item moves only horizontally
        // and doesn't jump to the top temporarily

        // Remove item from old position
        const [movedItem] = updatedWidgets.splice(removedIndex, 1);
        console.log("Moved item:", movedItem);
        console.log("Array after removal:", [...updatedWidgets]);

        // Calculate the correct horizontal position
        let targetIndex = addedIndex;

        // Ensure we don't place items before listing_title if it exists
        if (updatedWidgets[0] === "listing_title" && targetIndex === 0) {
          targetIndex = 1;
        }

        // For horizontal dragging, ensure the item moves smoothly without jumping
        // Calculate the actual horizontal position based on the drag distance
        if (this.dragAxis === "x") {
          // Prevent the item from jumping to the top
          // The targetIndex should represent the horizontal position, not vertical
          targetIndex = Math.max(
            1,
            Math.min(targetIndex, updatedWidgets.length),
          );
        }

        // Add item at new position
        updatedWidgets.splice(targetIndex, 0, movedItem);
        console.log("Final array:", updatedWidgets);
      } else {
        // Vertical dragging (default behavior)
        console.log(
          "Vertical dragging - removing from index:",
          removedIndex,
          "adding at index:",
          addedIndex,
        );

        // Remove item from old position
        const [movedItem] = updatedWidgets.splice(removedIndex, 1);

        // Add item at new position
        updatedWidgets.splice(addedIndex, 0, movedItem);
      }

      // Final check: ensure listing_title is always at index 0
      const finalListingTitleIndex = updatedWidgets.indexOf("listing_title");
      if (finalListingTitleIndex > 0) {
        const [listingTitle] = updatedWidgets.splice(finalListingTitleIndex, 1);
        updatedWidgets.unshift(listingTitle);
      }

      // Emit to parent to update the selectedWidgets prop
      this.$emit("update", updatedWidgets);

      return;
    },
  },

  watch: {
    output_data() {
      this.$emit("update", this.output_data);
    },
  },
};
</script>
