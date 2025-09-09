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
        <template
          v-if="displayedWidgets.includes('listing_title') && dragAxis === 'x'"
        >
          <div
            class="cptm-widget-preview-card cptm-widget-preview-card-listing_title"
          >
            <component
              :is="`${availableWidgets['listing_title'].type}-card-widget`"
              :label="getWidgetLabel('listing_title')"
              :icon="getWidgetIcon('listing_title')"
              :widgetKey="'listing_title'"
              :options="getWidgetOptions('listing_title')"
              :activeWidgets="activeWidgets"
              @update="handleActiveWidgetUpdate"
            />
          </div>
        </template>
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
            v-for="(widget, widget_index) in displayedWidgets.filter(
              (w) => w !== 'listing_title',
            )"
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
      if (this.widgetOptionsWindow.widget === widgetKey) {
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

    // Get child payload for better drag and drop control
    getChildPayload(index) {
      // For horizontal drag, we need to account for the filtered display
      let widget;
      if (
        this.dragAxis === "x" &&
        this.displayedWidgets.includes("listing_title")
      ) {
        // Filter out listing_title to match the template's filtered display
        const filteredWidgets = this.displayedWidgets.filter(
          (w) => w !== "listing_title",
        );
        widget = filteredWidgets[index];
      } else {
        widget = this.displayedWidgets[index];
      }

      console.log("@@getChildPayload", {
        widget,
        index,
        dragAxis: this.dragAxis,
        displayedWidgets: this.displayedWidgets,
        hasListingTitle: this.displayedWidgets.includes("listing_title"),
      });
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
      console.log("Drag started:", {
        payload,
        draggingWidget: this.draggingWidget,
      });
    },

    // Handle drag end to reset drag states
    onWidgetDragEnd() {
      console.log("Drag ended, resetting drag states", this.draggingWidget);

      // Set drag end state briefly before clearing
      if (this.draggingWidget) {
        this.dragEndWidget = this.draggingWidget;
      }
    },

    // Widget Drop Handler
    onWidgetsDrop(dropResult) {
      console.log("Drop result:", dropResult);
      console.log("Drag axis:", this.dragAxis);
      console.log("selectedWidgets:", this.selectedWidgets);

      // Clear all drag states
      this.draggingWidget = null;
      this.dragEndWidget = null;

      const { removedIndex, addedIndex } = dropResult;
      if (removedIndex === null || addedIndex === null) return;

      // Only allow reordering if drag-and-drop is enabled, not read-only, and has multiple widgets
      if (!this.canDragAndDrop || this.readOnly || !this.hasMultipleWidgets)
        return;

      // Clone array to avoid mutation
      const widgetsCopy = [...this.selectedWidgets];

      // Determine target index for insertion
      let targetIndex = addedIndex;

      // Horizontal drag adjustments
      if (this.dragAxis === "x") {
        console.log("Horizontal drag - original array:", widgetsCopy);

        // Check if selectedWidgets has listing_title or other special widgets
        const specialWidgets = ["listing_title"];
        const hasSpecialWidgets = specialWidgets.some((widget) =>
          this.selectedWidgets.includes(widget),
        );

        if (hasSpecialWidgets) {
          // For horizontal drag with special widgets, we need to map the drag indices
          // to the original array since the template filters out listing_title

          // Create a mapping of filtered indices to original indices
          const filteredToOriginalMap = [];
          const originalToFilteredMap = [];

          widgetsCopy.forEach((widget, originalIndex) => {
            if (!specialWidgets.includes(widget)) {
              const filteredIndex = filteredToOriginalMap.length;
              filteredToOriginalMap[filteredIndex] = originalIndex;
              originalToFilteredMap[originalIndex] = filteredIndex;
            }
          });

          // Map drag indices to original array indices
          const originalRemovedIndex = filteredToOriginalMap[removedIndex];
          const originalAddedIndex = filteredToOriginalMap[addedIndex];

          console.log("Index mapping:", {
            removedIndex,
            addedIndex,
            originalRemovedIndex,
            originalAddedIndex,
            filteredToOriginalMap,
            widgetsCopy,
          });

          // Perform reordering on the original array using mapped indices
          const [movedItem] = widgetsCopy.splice(originalRemovedIndex, 1);
          widgetsCopy.splice(originalAddedIndex, 0, movedItem);

          // Now ensure listing_title is at the top
          const finalWidgets = [...widgetsCopy];

          // Remove listing_title from its current position
          const listingTitleIndex = finalWidgets.indexOf("listing_title");
          if (listingTitleIndex > -1) {
            finalWidgets.splice(listingTitleIndex, 1);
          }

          // Add listing_title to the top
          finalWidgets.unshift("listing_title");

          console.log(
            "Final array after horizontal drop with special widgets:",
            {
              originalArray: this.selectedWidgets,
              afterReorder: widgetsCopy,
              finalArray: finalWidgets,
              movedItem,
              originalRemovedIndex,
              originalAddedIndex,
            },
          );

          // Emit updated array to parent
          this.$emit("update", finalWidgets);
          return;
        }

        // Clamp targetIndex within array bounds
        targetIndex = Math.max(0, Math.min(targetIndex, widgetsCopy.length));
      } else {
        // Vertical drag adjustments (default)
        console.log("Vertical drag - original array:", widgetsCopy);
        targetIndex = Math.max(0, Math.min(targetIndex, widgetsCopy.length));
      }

      // Remove moved item and insert at new position
      const [movedItem] = widgetsCopy.splice(removedIndex, 1);
      widgetsCopy.splice(targetIndex, 0, movedItem);

      console.log("Final array after drop:", widgetsCopy);

      // Emit updated array to parent
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
