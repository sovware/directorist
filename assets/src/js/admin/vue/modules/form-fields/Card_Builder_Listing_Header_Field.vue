<template>
  <div class="cptm-builder-section">
    <div
      class="cptm-options-area"
      v-if="
        widgetCardOptionsWindowActiveStatus || widgetOptionsWindowActiveStatus
      "
    >
      <options-window
        :active="widgetCardOptionsWindowActiveStatus"
        v-bind="widgetCardOptionsWindow"
        @close="closeCardWidgetOptionsWindow()"
      />

      <options-window
        :active="widgetOptionsWindowActiveStatus"
        v-bind="widgetOptionsWindow"
        @update="updateWidgetOptionsData($event, widgetOptionsWindow)"
        @close="closeWidgetOptionsWindow()"
      />
    </div>

    <!-- cptm-preview-area -->
    <div 
      class="cptm-preview-placeholder"
      :class="!elementsSettingsOpened ? 'cptm-preview-placeholder--settings-closed' : ''"
    >
      <div class="cptm-preview-placeholder__card">
        <!-- Draggable Bottom Widgets -->
        <div
          v-for="(placeholderItem, index) in placeholders"
          :key="index"
          v-if="placeholderItem.type == 'placeholder_group'"
          class="cptm-preview-placeholder__card__item cptm-preview-placeholder__card__item--top" 
        >
          <div
            class="cptm-preview-placeholder__card__content"
          >
            <card-widget-placeholder
              v-for="(
                placeholderSubItem, subIndex
              ) in placeholderItem.placeholders"
              :key="`${index}_${subIndex}`"
              :placeholderKey="placeholderSubItem.placeholderKey"
              :id="`listings_header_${index}_${subIndex}`"
              containerClass="cptm-preview-placeholder__card__box cptm-card-light"
              :label="placeholderSubItem.label"
              :availableWidgets="theAvailableWidgets"
              :activeWidgets="active_widgets"
              :acceptedWidgets="placeholderSubItem.acceptedWidgets"
              :rejectedWidgets="placeholderSubItem.rejectedWidgets"
              :selectedWidgets="placeholderSubItem.selectedWidgets"
              :maxWidget="placeholderSubItem.maxWidget"
              :showWidgetsPickerWindow="
                getActiveInsertWindowStatus(
                  `listings_header_${index}_${subIndex}`
                )
              "
              :widgetDropable="widgetIsDropable(placeholderSubItem)"
              @insert-widget="insertWidget($event, placeholderSubItem)"
              @drag-widget="onDragStartWidget($event, placeholderSubItem)"
              @drop-widget="appendWidget($event, placeholderSubItem)"
              @dragend-widget="onDragEndWidget()"
              @edit-widget="editWidget($event)"
              @trash-widget="trashWidget($event, placeholderSubItem, index)"
              @placeholder-on-drop="
                handleDropOnPlaceholder(placeholderSubItem)
              "
              @open-widgets-picker-window="
                activeInsertWindow(`listings_header_${index}_${subIndex}`)
              "
              @close-widgets-picker-window="closeInsertWindow()"
            />
          </div>
        </div>
        <Container 
          @drop="onDrop" 
          drag-handle-selector=".cptm-drag-element"
          class="cptm-preview-placeholder__card__item cptm-preview-placeholder__card__item--bottom"
        >
          <Draggable
            v-for="(placeholderItem, index) in placeholders"
            :key="index"
            v-if="placeholderItem.type == 'placeholder_item'"
          >

            <div
              class="draggable-item"
            >
              <div
                class="cptm-preview-placeholder__card__content"
              >
                <card-widget-placeholder
                  :placeholderKey="placeholderItem.placeholderKey"
                  :id="'listings_header_' + index"
                  containerClass="cptm-preview-placeholder__card__box cptm-card-light"
                  :label="placeholderItem.label"
                  :availableWidgets="theAvailableWidgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="placeholderItem.acceptedWidgets"
                  :rejectedWidgets="placeholderItem.rejectedWidgets"
                  :selectedWidgets="placeholderItem.selectedWidgets"
                  :maxWidget="placeholderItem.maxWidget"
                  :showWidgetsPickerWindow="
                    getActiveInsertWindowStatus('listings_header_' + index)
                  "
                  :widgetDropable="widgetIsDropable(placeholderItem)"
                  @insert-widget="insertWidget($event, placeholderItem)"
                  @drag-widget="onDragStartWidget($event, placeholderItem)"
                  @drop-widget="appendWidget($event, placeholderItem)"
                  @dragend-widget="onDragEndWidget()"
                  @edit-widget="editWidget($event)"
                  @trash-widget="trashWidget($event, placeholderItem, index)"
                  @placeholder-on-drop="
                    handleDropOnPlaceholder(placeholderItem)
                  "
                  @open-widgets-picker-window="
                    activeInsertWindow('listings_header_' + index)
                  "
                  @close-widgets-picker-window="closeInsertWindow()"
                />
              </div>

              <div class="cptm-drag-element las la-arrows-alt"></div>
            </div>
          </Draggable>
        </Container>
      </div>

      <div class="cptm-placeholder-buttons">
          <template v-for="placeholderKey in Object.keys(placeholdersMap)">
            <div
              :key="placeholderKey"
              class="cptm-preview-placeholder__card__action"
              v-if="canShowAddPlaceholderButton(placeholderKey)"
            >
              <button
                type="button"
                class="cptm-preview-placeholder__card__btn"
                @click="addPlaceholder(placeholderKey)"
              >
                <span class="icon fa fa-plus"></span>
                {{ getAddPlaceholderButtonLabel(placeholderKey) }}
              </button>
            </div>
          </template>
        </div>
    </div>

    <!-- Toggle Button -->
    <button
      type="button"
      class="cptm-elements-settings__toggle"
      v-if="!elementsSettingsOpened"
      @click.prevent="toggleElementsSettings"
    >
      <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M16.0834 10.6668C15.9167 10.5001 15.8334 10.2501 15.8334 10.0001C15.8334 9.75012 15.8334 9.50012 16.0834 9.33346L16.75 8.33346C17.0834 7.91679 17.25 7.41679 17.25 6.91679C17.25 6.41679 17.1667 5.91679 16.9167 5.41679C16.6667 5.00012 16.25 4.58346 15.8334 4.33346C15.3334 4.08346 14.8334 4.00012 14.3334 4.08346L13.1667 4.25012C12.9167 4.25012 12.6667 4.25012 12.4167 4.08346C12.1667 3.91679 12 3.75012 11.9167 3.50012L11.4167 2.41679C11.1667 1.91679 10.8334 1.58346 10.4167 1.25012C9.58336 0.666789 8.33336 0.666789 7.50003 1.25012C7.08336 1.50012 6.75003 1.91679 6.50003 2.41679L6.00003 3.50012C5.9167 3.75012 5.75003 3.91679 5.50003 4.08346C5.25003 4.25012 5.00003 4.25012 4.75003 4.25012L3.58336 4.08346C3.08336 4.08346 2.58336 4.08346 2.08336 4.33346C1.58336 4.58346 1.25003 4.91679 1.00003 5.41679C0.75003 5.83346 0.583364 6.41679 0.666697 6.91679C0.666697 7.41679 0.833364 7.91679 1.1667 8.33346L1.83336 9.33346C2.00003 9.50012 2.08336 9.75012 2.08336 10.0001C2.08336 10.2501 2.08336 10.5001 1.83336 10.6668L1.1667 11.6668C0.833364 12.0835 0.666697 12.5835 0.666697 13.0835C0.666697 13.5835 0.75003 14.0835 1.00003 14.5835C1.25003 15.0001 1.6667 15.4168 2.08336 15.6668C2.58336 15.9168 3.08336 16.0001 3.58336 15.9168L4.75003 15.7501C5.00003 15.7501 5.25003 15.7501 5.50003 15.9168C5.75003 16.0001 5.9167 16.2501 6.00003 16.5001L6.50003 17.5835C6.75003 18.0835 7.08336 18.4168 7.50003 18.7501C7.9167 19.0835 8.4167 19.1668 9.00003 19.1668C9.58337 19.1668 10 19.0001 10.5 18.7501C11 18.5001 11.25 18.0835 11.5 17.5835L12 16.5001C12.0834 16.2501 12.25 16.0835 12.5 15.9168C12.75 15.7501 13 15.7501 13.25 15.7501L14.4167 15.9168C14.9167 15.9168 15.4167 15.9168 15.9167 15.6668C16.4167 15.4168 16.75 15.0835 17 14.5835C17.25 14.1668 17.4167 13.5835 17.3334 13.0835C17.3334 12.5835 17.1667 12.0835 16.8334 11.6668L16.1667 10.6668H16.0834ZM14.75 11.6668L15.4167 12.6668C15.5 12.8335 15.5834 13.0001 15.5834 13.1668C15.5834 13.3335 15.5834 13.5835 15.4167 13.7501C15.3334 13.9168 15.1667 14.0835 15 14.1668C14.8334 14.2501 14.6667 14.2501 14.4167 14.2501L13.25 14.0835C12.6667 14.0835 12.0834 14.0835 11.5 14.4168C11 14.7501 10.5834 15.1668 10.3334 15.7501L9.83337 16.8335C9.83337 17.0001 9.6667 17.1668 9.50003 17.2501C9.1667 17.5001 8.75003 17.5001 8.4167 17.2501C8.25003 17.1668 8.08336 17.0001 8.08336 16.8335L7.58336 15.7501C7.33336 15.1668 6.9167 14.7501 6.4167 14.4168C5.9167 14.0835 5.25003 14.0001 4.6667 14.0835L3.50003 14.2501C3.33336 14.2501 3.08336 14.2501 2.9167 14.1668C2.75003 14.0835 2.58336 13.9168 2.50003 13.7501C2.4167 13.5835 2.33336 13.4168 2.33336 13.1668C2.33336 13.0001 2.33336 12.7501 2.50003 12.6668L3.1667 11.6668C3.50003 11.1668 3.75003 10.5835 3.75003 10.0001C3.75003 9.41679 3.58336 8.83346 3.1667 8.33346L2.50003 7.33346C2.4167 7.16679 2.33336 7.00012 2.33336 6.75012C2.33336 6.58346 2.33336 6.33346 2.50003 6.16679C2.58336 6.00012 2.75003 5.83346 2.9167 5.75012C3.08336 5.66679 3.25003 5.58346 3.50003 5.66679L4.6667 5.83346C5.25003 5.83346 5.83336 5.83346 6.4167 5.50012C6.9167 5.16679 7.33336 4.75012 7.58336 4.16679L8.08336 3.08346C8.08336 2.91679 8.33336 2.75012 8.4167 2.66679C8.75003 2.41679 9.1667 2.41679 9.50003 2.66679C9.6667 2.75012 9.83337 2.91679 9.83337 3.08346L10.3334 4.16679C10.5834 4.75012 11 5.16679 11.5 5.50012C12 5.83346 12.5834 5.91679 13.25 5.83346L14.4167 5.66679C14.5834 5.66679 14.8334 5.66679 15 5.75012C15.1667 5.83346 15.3334 6.00012 15.4167 6.16679C15.5 6.33346 15.5834 6.50012 15.5834 6.75012C15.5834 6.91679 15.5834 7.16679 15.4167 7.33346L14.75 8.33346C14.4167 8.83346 14.1667 9.41679 14.1667 10.0001C14.1667 10.5835 14.3334 11.1668 14.75 11.6668Z" fill="#3E62F5"/>
        <path d="M9.00008 6.66675C7.16675 6.66675 5.66675 8.16675 5.66675 10.0001C5.66675 11.8334 7.16675 13.3334 9.00008 13.3334C10.8334 13.3334 12.3334 11.8334 12.3334 10.0001C12.3334 8.16675 10.8334 6.66675 9.00008 6.66675ZM9.00008 11.6667C8.08341 11.6667 7.33341 10.9167 7.33341 10.0001C7.33341 9.08341 8.08341 8.33341 9.00008 8.33341C9.91675 8.33341 10.6667 9.08341 10.6667 10.0001C10.6667 10.9167 9.91675 11.6667 9.00008 11.6667Z" fill="#3E62F5"/>
      </svg>
    </button>

    <!-- cptm-elements-settings -->
    <div
      class="cptm-elements-settings"
      v-if="elementsSettingsOpened"
    >
      <div class="cptm-elements-settings__header">
        <h4 class="cptm-elements-settings__header__title">Header elements settings</h4>
        <button
          type="button"
          class="cptm-elements-settings__header__close"
          @click.prevent="closeElementsSettings"
        >
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24408 5.24408C5.56951 4.91864 6.09715 4.91864 6.42259 5.24408L10 8.82149L13.5774 5.24408C13.9028 4.91864 14.4305 4.91864 14.7559 5.24408C15.0814 5.56951 15.0814 6.09715 14.7559 6.42259L11.1785 10L14.7559 13.5774C15.0814 13.9028 15.0814 14.4305 14.7559 14.7559C14.4305 15.0814 13.9028 15.0814 13.5774 14.7559L10 11.1785L6.42259 14.7559C6.09715 15.0814 5.56951 15.0814 5.24408 14.7559C4.91864 14.4305 4.91864 13.9028 5.24408 13.5774L8.82149 10L5.24408 6.42259C4.91864 6.09715 4.91864 5.56951 5.24408 5.24408Z" fill="#4D5761"/>
          </svg>
        </button>
      </div>

      <template>
        <div class="cptm-elements-settings__content">
          <div 
            class="cptm-elements-settings__group"
            v-for="(placeholder, placeholder_index) in allPlaceholderItems"
            :key="placeholder_index"
          >
            <span class="cptm-elements-settings__group__title">{{ placeholder.label }}</span>

            <Container
              @drop="onElementsDrop($event, placeholder_index)" 
              group-name="settings-widgets" 
              drag-handle-selector=".drag-handle"
              :get-child-payload="(index) => getSettingsChildPayload(index, placeholder_index)"
            >
              <Draggable
                v-for="(widget_key, widget_index) in placeholder.acceptedWidgets"
                :key="widget_index"
                :data="{ widget_key }" 
              >
                <div class="cptm-elements-settings__group__single">
                  <span class="drag-handle drag-icon uil uil-draggabledots"></span>
                  <span class="cptm-elements-settings__group__single__label">
                    <!-- Display icon only if it exists -->
                    <span v-if="available_widgets[widget_key].icon" :class="available_widgets[widget_key].icon"></span>
                    <span v-if="available_widgets[widget_key]">{{ available_widgets[widget_key].label }}</span>
                    <span v-else>Unknown Widget</span>
                  </span>

                  <!-- Add toggle switch for widget -->
                  <span class="cptm-elements-settings__group__single__switch">
                    <input type="checkbox" :id="`settings-${widget_key}-${placeholder_index}`" />
                    <label :for="`settings-${widget_key}-${placeholder_index}`" />
                  </span>
                </div>
              </Draggable>
            </Container>
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import { Container, Draggable } from "vue-dndrop";
import { applyDrag } from "../../helpers/vue-dndrop";
import helpers from "../../mixins/helpers";
import card_builder from "./../../mixins/form-fields/card-builder";

export default {
  name: "card-builder-listing-header-field",
  components: {
    Container,
    Draggable,
  },
  mixins: [card_builder, helpers],
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
    cardOptions: {
      required: false,
      default: null,
    },
    layout: {
      required: false,
      default: null,
    },
  },

  mounted() {
    const self = this;
    document.addEventListener("click", function (e) {
      self.closeInsertWindow();
    });
  },

  created() {
    this.init();
    this.$emit("update", this.output_data);
  },

  watch: {
    output_data() {
      this.$emit("update", this.output_data);
    },
  },

  computed: {
    output_data() {
      let output = [];
      let placeholders = this.placeholders;

      const getWidgetData = (placeholderData) => {
        if (typeof placeholderData !== "object") {
          return null;
        }

        if (typeof placeholderData.selectedWidgets !== "object") {
          return null;
        }

        let data = [];

        for (let widgetIndex in placeholderData.selectedWidgets) {
          const widget_name = placeholderData.selectedWidgets[widgetIndex];

          if (
            !this.active_widgets[widget_name] &&
            typeof this.active_widgets[widget_name] !== "object"
          ) {
            continue;
          }

          let widget_data = {};

          for (let root_option in this.active_widgets[widget_name]) {
            if ("options" === root_option) {
              continue;
            }
            if ("icon" === root_option) {
              continue;
            }
            if ("show_if" === root_option) {
              continue;
            }
            if ("fields" === root_option) {
              continue;
            }

            widget_data[root_option] = this.active_widgets[widget_name][
              root_option
            ];
          }

          if (typeof this.active_widgets[widget_name].options !== "object") {
            data.push(widget_data);
            continue;
          }

          if (
            typeof this.active_widgets[widget_name].options.fields !== "object"
          ) {
            data.push(widget_data);
            continue;
          }

          let widget_options = this.active_widgets[widget_name].options.fields;

          for (let option in widget_options) {
            widget_data[option] = widget_options[option].value;
          }

          data.push(widget_data);
        }

        return data;
      };

      // Parse Layout
      for (const placeholder of placeholders) {
        if ("placeholder_item" === placeholder.type) {
          const data = getWidgetData(placeholder);

          if (!data) {
            continue;
          }

          output.push({
            type: placeholder.type,
            placeholderKey: placeholder.placeholderKey,
            selectedWidgets: data,
          });
          continue;
        }

        if ("placeholder_group" === placeholder.type) {
          let subGroupsData = [];

          for (const subPlaceholder of placeholder.placeholders) {
            const data = getWidgetData(subPlaceholder);
            if (!data) {
              continue;
            }

            subGroupsData.push({
              type: placeholder.type ? placeholder.type : "placeholder_item",
              placeholderKey: subPlaceholder.placeholderKey,
              selectedWidgets: data,
            });
            continue;
          }

          output.push({
            type: placeholder.type,
            placeholderKey: placeholder.placeholderKey,
            placeholders: subGroupsData,
          });

          continue;
        }
      }

      return output;
    },

    theAvailableWidgets() {
      let available_widgets = JSON.parse(
        JSON.stringify(this.available_widgets)
      );

      for (let widget in available_widgets) {
        available_widgets[widget].widget_name = widget;
        available_widgets[widget].widget_key = widget;

        // Check show if condition
        let show_if_cond_state = null;

        if (this.isObject(available_widgets[widget].show_if)) {
          show_if_cond_state = this.checkShowIfCondition({
            condition: available_widgets[widget].show_if,
          });
          let main_widget = available_widgets[widget];

          delete available_widgets[widget];

          if (show_if_cond_state.status) {
            let widget_keys = [];
            for (let matched_field of show_if_cond_state.matched_data) {
              // console.log( {matched_field} );
              let _main_widget = JSON.parse(JSON.stringify(main_widget));
              let current_key = widget_keys.includes(widget)
                ? widget + "_" + (widget_keys.length + 1)
                : widget;
              _main_widget.widget_key = current_key;

              if (matched_field.widget_key) {
                _main_widget.widget_key = matched_field.widget_key;
              }

              if (
                typeof matched_field.label === "string" &&
                matched_field.label.length
              ) {
                _main_widget.label = matched_field.label;
              }

              available_widgets[current_key] = _main_widget;
              widget_keys.push(current_key);
            }
          }
        }
      }

      return available_widgets;
    },

    widgetOptionsWindowActiveStatus() {
      if (!this.widgetOptionsWindow.widget.length) {
        return false;
      }
      if (
        typeof this.active_widgets[this.widgetOptionsWindow.widget] ===
        "undedined"
      ) {
        return false;
      }

      return true;
    },

    widgetCardOptionsWindowActiveStatus() {
      if (!this.isObject(this.widgetCardOptionsWindow.widget)) {
        return false;
      }

      return true;
    },

    _currentDraggingWidget() {
      return this.currentDraggingWidget;
    },
  },

  data() {
    return {
      active_insert_widget_key: "",

      // Widget Options Window
      widgetOptionsWindowDefault: {
        animation: "cptm-animation-flip",
        widget: "",
      },

      widgetCardOptionsWindow: {
        animation: "cptm-animation-flip",
        widget: "",
      },

      widgetOptionsWindow: {
        animation: "cptm-animation-flip",
        widget: "",
      },

      currentDraggingWidget: { origin: {}, key: "" },

      // Available Widgets
      available_widgets: {},

      // Active Widgets
      active_widgets: {},

      // Card Options
      card_options: {
        general: {},
        content_settings: {},
      },

      placeholdersMap: {},

      placeholders: [
        {
          type: "placeholder_group",
          placeholders: [
            {
              label: "Quick Info",
              selectedWidgets: [],
            },
            {
              label: "Quick Action",
              selectedWidgets: [],
            },
          ],
        },
        {
          type: "placeholder_item",
          label: "Listing Title",
          acceptedWidgets: ["title"],
          rejectedWidgets: ["slider"],
          selectedWidgets: [],
        },
        {
          type: "placeholder_item",
          label: "More Widgets",
          rejectedWidgets: ["slider"],
          selectedWidgets: [],
        },
      ],

      elementsSettingsOpened: false,
    };
  },

  methods: {
    init() {
      this.importWidgets();
      this.importCardOptions();
      this.importPlaceholders();
      this.importOldData();
    },

    onDrop(dropResult) {
      this.placeholders = applyDrag(this.placeholders, dropResult);
    },

    getSettingsChildPayload(draggedItemIndex, placeholderIndex) {
      // Log for debugging
      console.log('Dragged Item Index:', draggedItemIndex);
      console.log('Placeholder Index:', placeholderIndex);

      // Return the payload containing both pieces of data
      return {
        draggedItemIndex: draggedItemIndex,
        placeholderIndex: placeholderIndex,
        // Add any other data you want to include in the payload
      };
    },

    onElementsDrop(dropResult, placeholder_index) {
      const { removedIndex, addedIndex, payload } = dropResult;
      const { draggedItemIndex, placeholderIndex } = payload; 

      // If no changes, return
      if (removedIndex === null && addedIndex === null) return; 

      const destinationPlaceholderIndex = placeholder_index;
      const sourcePlaceholderIndex = placeholderIndex;

      // Get the widget key from the source placeholder
      const widgetKey = this.allPlaceholderItems[sourcePlaceholderIndex]?.acceptedWidgets[draggedItemIndex];

      if (widgetKey !== undefined) {
        console.log('#chk', { 
          draggedItemIndex, widgetKey, sourcePlaceholderIndex, destinationPlaceholderIndex, 
          source: this.allPlaceholderItems[sourcePlaceholderIndex].acceptedWidgets,
          destination: this.allPlaceholderItems[destinationPlaceholderIndex].acceptedWidgets
        });

        // Remove the widget from the source
        if (removedIndex !== null) {
          this.allPlaceholderItems[sourcePlaceholderIndex].acceptedWidgets.splice(removedIndex, 1);
        }

        // Add the widget to the destination at the correct position
        if (addedIndex !== null) {
          this.allPlaceholderItems[destinationPlaceholderIndex].acceptedWidgets.splice(addedIndex, 0, widgetKey);
        }

        console.log('Source, Destination updated', {
          source: this.allPlaceholderItems[sourcePlaceholderIndex].acceptedWidgets,
          destination: this.allPlaceholderItems[destinationPlaceholderIndex].acceptedWidgets
        });
      } else {
        console.error('Widget key not found', { sourcePlaceholderIndex, draggedItemIndex });
      }
    },


    getGhostParent() {
      return document.body;
    },
    getChildPayload(index) {
      return this.items[index];
    },

    canShowAddPlaceholderButton(placeholderKey) {
      const placeholder = this.placeholdersMap[placeholderKey];

      if (!placeholder.insertByButton) {
        return false;
      }

      const findPlaceholder = (placeholderKey, placeholders) => {
        for (const placeholder of placeholders) {
          if ("placeholder_item" === placeholder.type) {
            if (placeholderKey === placeholder.placeholderKey) {
              return placeholder;
            }
            continue;
          }

          if ("placeholder_group" === placeholder.type) {
            const targetPlaceholder = findPlaceholder(
              placeholderKey,
              placeholder.placeholders
            );

            if (targetPlaceholder) {
              return targetPlaceholder;
            }

            continue;
          }
        }

        return null;
      };

      const targetPlaceholder = findPlaceholder(
        placeholderKey,
        this.placeholders
      );
      return targetPlaceholder ? false : true;
    },

    addPlaceholder(placeholderKey) {
      let placeholder = JSON.parse( JSON.stringify( this.placeholdersMap[placeholderKey] ) );

      if ( ! Array.isArray( placeholder.selectedWidgets ) ) {
        placeholder.selectedWidgets = [];
      }

      if (placeholder.selectedWidgets.length) {
        for (const widgetKey of placeholder.selectedWidgets) {

          if (!this.isTruthyObject(this.theAvailableWidgets[widgetKey])) {
            continue;
          }

          Vue.set(this.active_widgets, widgetKey, {
            ...this.theAvailableWidgets[widgetKey],
          });
        }
      }

      this.placeholders.splice(this.placeholders.length, 0, placeholder);
    },

    getAddPlaceholderButtonLabel(placeholderKey) {
      const placeholder = this.placeholdersMap[placeholderKey];
      const defaultLabel = "Add new placeholder";

      if (!this.isTruthyObject(placeholder.insertButton)) {
        return defaultLabel;
      }

      if (!placeholder.insertButton.label) {
        return defaultLabel;
      }

      return placeholder.insertButton.label;
    },

    isTruthyObject(obj) {
      if (!obj && typeof obj !== "object" && !Array.isArray(obj)) {
        return false;
      }

      return true;
    },

    isJSON(string) {
      try {
        JSON.parse(string);
      } catch (e) {
        return false;
      }

      return true;
    },

    importOldData() {
      let value = JSON.parse(JSON.stringify(this.value));

      if (!Array.isArray(value)) {
        return;
      }

      let newPlaceholders = [];

      // Import Layout
      // -------------------------
      const addActiveWidget = (widget) => {
        let widgets_template = {
          ...this.theAvailableWidgets[widget.widget_key],
        };

        let has_widget_options = false;

        if (widgets_template.options && widgets_template.options.fields) {
          has_widget_options = true;
        }

        for (let root_option in widgets_template) {
          if ("options" === root_option) {
            continue;
          }

          if (widget[root_option] === "undefined") {
            continue;
          }

          widgets_template[root_option] = widget[root_option];
        }

        if (has_widget_options) {
          for (let option_key in widgets_template.options.fields) {
            if (typeof widget[option_key] === "undefined") {
              continue;
            }

            widgets_template.options.fields[option_key].value =
              widget[option_key];
          }
        }

        Vue.set(this.active_widgets, widget.widget_key, widgets_template);
      };

      const importWidgets = (placeholder, destination) => {
        if (!this.placeholdersMap.hasOwnProperty(placeholder.placeholderKey)) {
          return;
        }

        let newPlaceholder = JSON.parse(
          JSON.stringify(this.placeholdersMap[placeholder.placeholderKey])
        );

        newPlaceholder.selectedWidgets = [];
        newPlaceholder.maxWidget =
          typeof newPlaceholder.maxWidget !== "undefined"
            ? parseInt(newPlaceholder.maxWidget)
            : 0;

        let targetPlaceholderIndex = destination.length;

        destination.splice(targetPlaceholderIndex, 0, newPlaceholder);

        let widgetIndex = 0;

        for (let widget of placeholder.selectedWidgets) {
          if (typeof widget.widget_key === "undefined") {
            continue;
          }
          if (typeof widget.widget_name === "undefined") {
            continue;
          }

          if (
            typeof this.available_widgets[widget.widget_name] === "undefined"
          ) {
            continue;
          }

          addActiveWidget(widget);

          destination[targetPlaceholderIndex].selectedWidgets.splice(
            widgetIndex,
            0,
            widget.widget_key
          );
          widgetIndex++;
        }
      };

      value.forEach((placeholder, index) => {
        if (!this.isTruthyObject(placeholder)) {
          return;
        }

        if ("placeholder_item" === placeholder.type) {
          if (!Array.isArray(placeholder.selectedWidgets)) {
            return;
          }

          importWidgets(placeholder, newPlaceholders);
          return;
        }

        if ("placeholder_group" === placeholder.type) {
          if (
            !this.placeholdersMap.hasOwnProperty(placeholder.placeholderKey)
          ) {
            return;
          }

          let newPlaceholder = JSON.parse(
            JSON.stringify(this.placeholdersMap[placeholder.placeholderKey])
          );
          newPlaceholder.placeholders = [];
          let targetPlaceholderIndex = this.placeholders.length;

          newPlaceholders.splice(targetPlaceholderIndex, 0, newPlaceholder);

          placeholder.placeholders.forEach((subPlaceholder) => {
            if (!Array.isArray(subPlaceholder.selectedWidgets)) {
              return;
            }

            importWidgets(subPlaceholder, newPlaceholders[index].placeholders);
          });
        }
      });

      this.placeholders = newPlaceholders;
    },

    importWidgets() {
      if (!this.isTruthyObject(this.widgets)) {
        return;
      }

      this.available_widgets = this.widgets;

      console.log({ available_widgets: this.available_widgets });
    },

    importCardOptions() {
      if (!this.isTruthyObject(this.cardOptions)) {
        return;
      }

      for (let section in this.card_options) {
        if (!this.isTruthyObject(this.cardOptions[section])) {
          return;
        }
        Vue.set(
          this.card_options,
          section,
          JSON.parse(JSON.stringify(this.cardOptions[section]))
        );
      }
    },

    importPlaceholders() {
      this.allPlaceholderItems = [];

      if (!Array.isArray(this.layout)) {
        return;
      }

      if (!this.layout.length) {
        return;
      }

      const sanitizePlaceholderData = (placeholder) => {
        if (!this.isTruthyObject(placeholder)) {
          placeholder = {};
        }

        if (placeholder.insertByButton) {
          return null;
        }

        placeholder.selectedWidgets = [];

        if (typeof placeholder.label === "undefined") {
          placeholder.label = "Widgets";
        }

        return placeholder;
      };

      let sanitizedPlaceholders = [];

      for (const placeholder of this.layout) {
        if (!this.isTruthyObject(placeholder)) {
          continue;
        }

        let placeholderItem = placeholder;

        if (typeof placeholderItem.type === "undefined") {
          placeholderItem.type = "placeholder_item";
        }

        if (typeof placeholderItem.placeholderKey === "undefined") {
          continue;
        }

        if (
          this.placeholdersMap.hasOwnProperty(placeholderItem.placeholderKey)
        ) {
          continue;
        }

        Vue.set(
          this.placeholdersMap,
          placeholderItem.placeholderKey,
          placeholderItem
        );

        if (placeholderItem.type === "placeholder_item") {
          const placeholderItemData = sanitizePlaceholderData(placeholderItem);
          if (placeholderItemData) {
            sanitizedPlaceholders.push(placeholderItemData);
            this.allPlaceholderItems.push(placeholderItemData);
          }

          continue;
        }

        if (placeholderItem.type === "placeholder_group") {
          if (typeof placeholderItem.placeholders === "undefined") {
            continue;
          }

          if (!Array.isArray(placeholderItem.placeholders)) {
            continue;
          }

          if (!placeholderItem.placeholders.length) {
            continue;
          }

          placeholderItem.placeholders.forEach(
            (placeholderSubItem, subPlaceholderIndex) => {
              if (
                this.placeholdersMap.hasOwnProperty(
                  placeholderSubItem.placeholderKey
                )
              ) {
                placeholderItem.placeholders.splice(subPlaceholderIndex, 1);
                return;
              }

              Vue.set(
                this.placeholdersMap,
                placeholderSubItem.placeholderKey,
                placeholderSubItem
              );

              const placeholderItemData = sanitizePlaceholderData(
                placeholderSubItem
              );

              if (placeholderItemData) {
                placeholderItem.placeholders.splice(
                  subPlaceholderIndex,
                  1,
                  placeholderItemData
                );
                this.allPlaceholderItems.push(placeholderItemData);
              }
            }
          );

          if (placeholderItem.placeholders.length) {
            sanitizedPlaceholders.push(placeholderItem);
          }
        }
      }

      this.placeholders = sanitizedPlaceholders;

      console.log({ placeholders: this.placeholders });
      console.log({ allPlaceholderItems: this.allPlaceholderItems });
    },

    onDragStartWidget(key, origin) {
      this.currentDraggingWidget.key = key;
      this.currentDraggingWidget.origin = origin;
    },

    onDragEndWidget() {
      this.currentDraggingWidget.key = "";
      this.currentDraggingWidget.origin = "";
    },

    maxWidgetLimitIsReached(path) {
      if (!path.maxWidget) {
        return false;
      }
      if (path.selectedWidgets.length >= path.maxWidget) {
        return true;
      }

      return false;
    },

    widgetIsAccepted(path, key) {
      if (!path.acceptedWidgets) {
        return true;
      }
      if (!this.isTruthyObject(path.acceptedWidgets)) {
        return true;
      }

      if (
        path.acceptedWidgets.includes(this.theAvailableWidgets[key].widget_name)
      ) {
        return true;
      }

      return false;
    },

    widgetIsNotAccepted(path, key) {
      if (!path.rejectedWidgets) {
        return false;
      }

      if (!this.isTruthyObject(path.rejectedWidgets)) {
        return false;
      }

      if (
        path.rejectedWidgets.includes(this.theAvailableWidgets[key].widget_name)
      ) {
        return true;
      }

      return false;
    },

    widgetIsDropable(path) {
      if (!this._currentDraggingWidget.key.length) {
        return false;
      }

      if (!this.isTruthyObject(this._currentDraggingWidget.origin)) {
        return false;
      }

      if (path.selectedWidgets.includes(this._currentDraggingWidget.key)) {
        return true;
      }

      if (this.maxWidgetLimitIsReached(path)) {
        return false;
      }

      if (this.widgetIsNotAccepted(path, this._currentDraggingWidget.key)) {
        return false;
      }

      if (!this.widgetIsAccepted(path, this._currentDraggingWidget.key)) {
        return false;
      }

      return true;
    },

    appendWidget(dest_key, dest_path) {
      const key = this.currentDraggingWidget.key;
      const from = this.currentDraggingWidget.origin.selectedWidgets;
      const origin_index = from.indexOf(key);
      let dest_index = dest_path.selectedWidgets.indexOf(dest_key) + 1;

      if (dest_path.selectedWidgets.includes(key) && 0 === origin_index) {
        dest_index--;
      }

      Vue.delete(from, from.indexOf(key));
      dest_path.selectedWidgets.splice(
        dest_index,
        0,
        this.currentDraggingWidget.key
      );

      this.onDragEndWidget();
    },

    handleDropOnPlaceholder(dest) {
      // return;
      const key = this.currentDraggingWidget.key;
      const from = this.currentDraggingWidget.origin.selectedWidgets;
      const to = dest.selectedWidgets;

      if (!this.isTruthyObject(from)) {
        return;
      }
      if (!this.isTruthyObject(to)) {
        return;
      }
      if (this.maxWidgetLimitIsReached(dest)) {
        return;
      }
      if (!this.widgetIsAccepted(dest, key)) {
        return;
      }

      if (!to.includes(key)) {
        Vue.delete(from, from.indexOf(key));
        Vue.set(to, to.length, key);
      }

      this.onDragEndWidget();
    },

    handleDragEnterOnPlaceholder(where) {
      // console.log( 'handleDragEnterOnPlaceholder', where );
    },

    handleDragOverOnPlaceholder(where) {
      // console.log( 'handleDragOverOnPlaceholder', where );
    },

    handleDragleaveOnPlaceholder(where) {
      // console.log( 'handleDragleaveOnPlaceholder', where );
    },

    editWidget(key) {
      if (typeof this.active_widgets[key] === "undefined") {
        return;
      }

      if (
        !this.active_widgets[key].options &&
        typeof this.active_widgets[key].options !== "object"
      ) {
        return;
      }

      this.widgetOptionsWindow = {
        ...this.widgetOptionsWindowDefault,
        ...this.active_widgets[key].options,
      };
      this.widgetOptionsWindow.widget = key;

      this.active_insert_widget_key = "";
    },

    editOption(widget_path, widget_key) {
      if (!this.isObject(widget_path[widget_key].options)) {
        return;
      }

      let widget_options = widget_path[widget_key].options;
      // let window_default = JSON.parse( JSON.stringify( this.widgetOptionsWindowDefault ) );
      let window_default = this.widgetOptionsWindowDefault;

      this.widgetCardOptionsWindow = { ...window_default, ...widget_options };
      this.widgetCardOptionsWindow.widget = { path: widget_path, widget_key };
    },

    updateCardWidgetOptionsData(data, options_window) {
      return;

      if (
        typeof this.card_option_widgets[options_window.widget] === "undefined"
      ) {
        return;
      }

      if (
        typeof this.card_option_widgets[options_window.widget].options ===
        "undefined"
      ) {
        return;
      }

      Vue.set(
        this.card_option_widgets[options_window.widget].options,
        "fields",
        data
      );
    },

    updateWidgetOptionsData(data, options_window) {
      return;

      if (typeof this.active_widgets[widget.widget] === "undefined") {
        return;
      }

      if (typeof this.active_widgets[widget.widget].options === "undefined") {
        return;
      }

      Vue.set(this.active_widgets[widget.widget].options, "fields", data);
    },

    closeCardWidgetOptionsWindow() {
      this.widgetCardOptionsWindow = this.widgetOptionsWindowDefault;
    },

    closeWidgetOptionsWindow() {
      this.widgetOptionsWindow = this.widgetOptionsWindowDefault;
    },

    trashWidget(key, where, placeholderIndex) {
      if (!where.selectedWidgets.includes(key)) {
        return;
      }

      let widgetIndex = where.selectedWidgets.indexOf(key);
      Vue.delete(where.selectedWidgets, widgetIndex);

      if (typeof this.active_widgets[key] === "undefined") {
        return;
      }

      Vue.delete(this.active_widgets, key);

      if (key === this.widgetOptionsWindow.widget) {
        this.closeWidgetOptionsWindow();
      }

      if (!where.canDelete) {
        return;
      }

      if (where.selectedWidgets.length) {
        return;
      }

      if (typeof this.placeholders[placeholderIndex] === "undefined") {
        return;
      }

      Vue.delete(this.placeholders, placeholderIndex);
    },

    activeInsertWindow(current_item_key) {
      let self = this;

      setTimeout(function () {
        if (self.active_insert_widget_key === current_item_key) {
          self.active_insert_widget_key = "";
          return;
        }

        self.active_insert_widget_key = current_item_key;
      }, 0);
    },

    insertWidget(payload, where) {
      if (!this.isTruthyObject(this.theAvailableWidgets[payload.key])) {
        return;
      }

      Vue.set(this.active_widgets, payload.key, {
        ...this.theAvailableWidgets[payload.key],
      });

      Vue.set(where, "selectedWidgets", payload.selected_widgets);

      this.editWidget(payload.key);
    },

    closeInsertWindow(widget_insert_window) {
      this.active_insert_widget_key = "";
    },

    getWidgetLabel(widget) {
      let label = "";

      if (typeof widget.label === "string") {
        label = widget.label;
      }

      if (
        this.isObject(widget.options) &&
        widget.options.fields &&
        widget.options.fields.label &&
        widget.options.fields.type === "text" &&
        widget.options.fields.label.value &&
        widget.options.fields.label.value.length
      ) {
        label = widget.options.fields.label.value;
      }

      return label;
    },

    getActiveInsertWindowStatus(current_item_key) {
      if (current_item_key === this.active_insert_widget_key) {
        return true;
      }

      return false;
    },

    closeElementsSettings() {
      this.elementsSettingsOpened = false;
    },
    toggleElementsSettings() {
      this.elementsSettingsOpened = !this.elementsSettingsOpened;
    },
  },
};
</script>
