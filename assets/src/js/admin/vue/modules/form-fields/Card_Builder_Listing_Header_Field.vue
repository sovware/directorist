<template>
  <div class="cptm-builder-section">
    <!-- cptm-elements-settings -->
    <div class="cptm-elements-settings">
      <div class="cptm-elements-settings__header">
        <h4 class="cptm-elements-settings__header__title">Listing Header</h4>
        <a
          href="#"
          class="directorist-row-tooltip cptm-form-builder-action-btn"
          :data-tooltip="video?.description"
          data-flow="bottom-right"
          @click.prevent="openModal()"
          v-if="video"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="14"
            height="14"
            viewBox="0 0 14 14"
            fill="none"
          >
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M3.94256 2.33333H7.14074C7.6103 2.33332 7.99785 2.33331 8.31355 2.35911C8.64143 2.3859 8.94285 2.44339 9.22596 2.58765C9.665 2.81135 10.022 3.16831 10.2457 3.60735C10.3899 3.89046 10.4474 4.19187 10.4742 4.51976C10.4981 4.81257 10.4999 5.16718 10.5 5.59171L11.6396 4.45212C11.7511 4.34058 11.8607 4.23096 11.9567 4.15052C12.0424 4.07876 12.223 3.93485 12.473 3.91517C12.7522 3.8932 13.0251 4.00622 13.207 4.21921C13.3699 4.40993 13.3958 4.63932 13.4056 4.75068C13.4167 4.87549 13.4167 5.03051 13.4166 5.18822V8.81177C13.4167 8.96948 13.4167 9.1245 13.4056 9.24931C13.3958 9.36067 13.3699 9.59006 13.207 9.78078C13.0251 9.99377 12.7522 10.1068 12.473 10.0848C12.223 10.0651 12.0424 9.92123 11.9567 9.84947C11.8607 9.76904 11.7511 9.65941 11.6396 9.54787L10.5 8.40828C10.4999 8.83281 10.4981 9.18742 10.4742 9.48023C10.4474 9.80812 10.3899 10.1095 10.2457 10.3926C10.022 10.8317 9.665 11.1886 9.22596 11.4123C8.94285 11.5566 8.64144 11.6141 8.31355 11.6409C7.99784 11.6667 7.6103 11.6667 7.14072 11.6667H3.94257C3.473 11.6667 3.08545 11.6667 2.76975 11.6409C2.44186 11.6141 2.14045 11.5566 1.85734 11.4123C1.41829 11.1886 1.06134 10.8317 0.837632 10.3926C0.693379 10.1095 0.635883 9.80812 0.609093 9.48023C0.5833 9.16453 0.583306 8.77699 0.583313 8.30742V5.69257C0.583306 5.22301 0.5833 4.83546 0.609093 4.51976C0.635883 4.19187 0.693379 3.89046 0.837632 3.60735C1.06134 3.16831 1.41829 2.81135 1.85734 2.58765C2.14045 2.44339 2.44186 2.3859 2.76975 2.35911C3.08545 2.33331 3.47299 2.33332 3.94256 2.33333ZM9.33331 5.71666C9.33331 5.21699 9.33286 4.87732 9.31141 4.61477C9.29051 4.35903 9.25264 4.22824 9.20615 4.13701C9.0943 3.91748 8.91582 3.73901 8.6963 3.62715C8.60507 3.58067 8.47428 3.5428 8.21854 3.5219C7.95599 3.50045 7.61632 3.5 7.11665 3.5H3.96665C3.46698 3.5 3.1273 3.50045 2.86475 3.5219C2.60901 3.5428 2.47822 3.58067 2.38699 3.62715C2.16747 3.73901 1.98899 3.91748 1.87714 4.13701C1.83065 4.22824 1.79278 4.35903 1.77189 4.61477C1.75043 4.87732 1.74998 5.21699 1.74998 5.71666V8.28333C1.74998 8.783 1.75043 9.12267 1.77189 9.38522C1.79278 9.64097 1.83065 9.77175 1.87714 9.86298C1.98899 10.0825 2.16747 10.261 2.38699 10.3728C2.47822 10.4193 2.60901 10.4572 2.86475 10.4781C3.1273 10.4995 3.46698 10.5 3.96665 10.5H7.11665C7.61632 10.5 7.95599 10.4995 8.21854 10.4781C8.47428 10.4572 8.60507 10.4193 8.6963 10.3728C8.91582 10.261 9.0943 10.0825 9.20615 9.86298C9.25264 9.77175 9.29051 9.64097 9.31141 9.38522C9.33286 9.12267 9.33331 8.783 9.33331 8.28333V5.71666ZM10.7416 7L12.25 8.50837V5.49162L10.7416 7Z"
              fill="currentColor"
            />
          </svg>
          Learn
        </a>
      </div>

      <template>
        <div class="cptm-elements-settings__content">
          <div
            class="cptm-elements-settings__group"
            v-for="(placeholder, placeholder_index) in allPlaceholderItems"
            :key="placeholder_index"
          >
            <span
              class="cptm-elements-settings__group__title"
              v-if="
                placeholder.label &&
                placeholder?.placeholderKey !== 'listing-title-placeholder' &&
                placeholder?.placeholderKey !== 'slider-placeholder'
              "
            >
              {{ placeholder.label }}
            </span>

            <Container
              @drop="onElementsDrop($event, placeholder_index)"
              group-name="settings-widgets"
              drag-handle-selector=".drag-handle"
              :get-child-payload="
                (index) => getSettingsChildPayload(index, placeholder_index)
              "
            >
              <Draggable
                v-for="(
                  widget_key, widget_index
                ) in placeholder.acceptedWidgets"
                :key="widget_index"
                :data="{ widget_key }"
              >
                <div
                  class="cptm-elements-settings__group__single"
                  :class="{
                    'cptm-elements-settings__group__single--disabled':
                      placeholder.maxWidget > 0 &&
                      placeholder.selectedWidgets?.length >=
                        placeholder.maxWidget &&
                      !placeholder.selectedWidgets.some(
                        (widget) => widget.widget_key === widget_key,
                      ),
                  }"
                >
                  <span
                    class="drag-handle drag-icon uil uil-draggabledots"
                    v-if="placeholder.acceptedWidgets?.length > 1"
                  ></span>
                  <span class="cptm-elements-settings__group__single__label">
                    <!-- Display icon only if it exists -->
                    <span
                      v-if="available_widgets[widget_key].icon"
                      :class="available_widgets[widget_key].icon"
                    ></span>
                    <span v-if="available_widgets[widget_key]">{{
                      available_widgets[widget_key].label
                    }}</span>
                    <span v-else>Unknown Widget</span>
                  </span>
                  <div class="cptm-elements-settings__group__single__action">
                    <!-- Add edit button for widget -->
                    <span
                      class="cptm-elements-settings__group__single__edit"
                      :class="{
                        'cptm-elements-settings__group__single__edit--disabled':
                          !active_widgets[widget_key],
                      }"
                      @click.prevent="editWidget(widget_key)"
                      v-if="available_widgets[widget_key].options"
                    >
                      <span
                        class="cptm-elements-settings__group__single__edit__icon uil uil-cog"
                      ></span>
                    </span>

                    <!-- Add toggle switch for widget -->
                    <span class="cptm-elements-settings__group__single__switch">
                      <input
                        type="checkbox"
                        :id="`settings-${widget_key}-${placeholder_index}`"
                        :checked="
                          placeholder.selectedWidgets &&
                          placeholder.selectedWidgets.some(
                            (widget) => widget.widget_key === widget_key,
                          )
                        "
                        @click="
                          handleWidgetSwitch(
                            $event,
                            widget_key,
                            placeholder_index,
                          )
                        "
                      />
                      <label
                        :for="`settings-${widget_key}-${placeholder_index}`"
                      />
                    </span>
                  </div>
                </div>

                <!-- Widget Options -->
                <div
                  class="cptm-elements-settings__group__options"
                  v-if="widgetOptionsWindowActiveStatus(widget_key)"
                >
                  <options-window
                    :active="widgetOptionsWindowActiveStatus(widget_key)"
                    v-bind="widgetOptionsWindow"
                    @update="
                      updateWidgetOptionsData($event, widgetOptionsWindow)
                    "
                    @close="closeWidgetOptionsWindow"
                  />
                </div>
              </Draggable>
            </Container>
          </div>
        </div>
      </template>
    </div>

    <!-- cptm-preview-area -->
    <div class="cptm-preview-placeholder">
      <div class="cptm-preview-placeholder__card">
        <!-- Draggable Bottom Widgets -->
        <div
          v-for="(placeholderItem, index) in placeholders"
          :key="index"
          v-if="placeholderItem.type == 'placeholder_group'"
          class="cptm-preview-placeholder__card__item cptm-preview-placeholder__card__item--top"
        >
          <div class="cptm-preview-placeholder__card__content">
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
              :selectedWidgets="placeholderSubItem.selectedWidgetList"
              :maxWidget="placeholderSubItem.maxWidget"
              :readOnly="true"
            />
          </div>
        </div>
        <Container
          @drop="onDrop"
          drag-handle-selector=".cptm-drag-element"
          class="cptm-preview-placeholder__card__item cptm-preview-placeholder__card__item--bottom"
          :get-child-payload="(index) => getChildPayload(index)"
        >
          <Draggable
            v-for="(placeholderItem, index) in placeholders"
            :key="index"
            v-if="placeholderItem.type == 'placeholder_item'"
          >
            <div class="draggable-item">
              <div class="cptm-drag-element uil uil-draggabledots"></div>
              <div class="cptm-preview-placeholder__card__content">
                <card-widget-placeholder
                  :placeholderKey="placeholderItem.placeholderKey"
                  :id="'listings_header_' + index"
                  containerClass="cptm-preview-placeholder__card__box cptm-card-light"
                  :label="placeholderItem.label"
                  :availableWidgets="theAvailableWidgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="placeholderItem.acceptedWidgets"
                  :rejectedWidgets="placeholderItem.rejectedWidgets"
                  :selectedWidgets="placeholderItem.selectedWidgetList"
                  :maxWidget="placeholderItem.maxWidget"
                  :showWidgetsPickerWindow="
                    getActiveInsertWindowStatus('listings_header_' + index)
                  "
                  @edit-widget="editWidget($event)"
                  :readOnly="true"
                />
              </div>
            </div>
          </Draggable>
        </Container>
      </div>
    </div>

    <!-- Video Popup Modal -->
    <form-builder-widget-modal-component
      v-if="modalContent"
      :modalOpened="showModal"
      :content="modalContent"
      :type="modalContent.type"
      @close-modal="closeModal"
    />
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
    video: {
      type: Object,
    },
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
    // output_data
    output_data() {
      let output = [];
      let placeholders = this.placeholders;

      // Get Widget Data
      const getWidgetData = (placeholderData) => {
        if (typeof placeholderData !== "object") {
          return null;
        }

        let data = [];

        let acceptedWidgets = placeholderData.acceptedWidgets || [];
        let selectedWidgets = placeholderData.selectedWidgets || [];
        let selectedWidgetList = placeholderData.selectedWidgetList || [];

        // Sort the selectedWidgetList & selectedWidgets based on acceptedWidgets order
        selectedWidgetList.sort(
          (a, b) => acceptedWidgets.indexOf(a) - acceptedWidgets.indexOf(b),
        );
        selectedWidgets.sort(
          (a, b) =>
            acceptedWidgets.indexOf(a.widget_key) -
            acceptedWidgets.indexOf(b.widget_key),
        );

        // Filter out invalid widgets (without widget_key)
        let validWidgets = selectedWidgets
          .map((widget, index) => {
            if (widget.widget_key) return widget;

            // Fallback: Use `selectedWidgetList` if available
            let widget_name = selectedWidgetList?.[index];
            return widget_name ? { widget_key: widget_name, ...widget } : null;
          })
          .filter((widget) => widget && widget.widget_key); // Remove invalid items

        for (let widget of validWidgets) {
          const widget_name = widget.widget_key;

          if (
            !this.active_widgets[widget_name] ||
            typeof this.active_widgets[widget_name] !== "object"
          ) {
            continue;
          }

          let widget_data = {};

          // Extract widget data, excluding unnecessary keys
          for (let key in this.active_widgets[widget_name]) {
            widget_data[key] = this.active_widgets[widget_name][key];
          }

          // Process widget options if available
          if (
            typeof this.active_widgets[widget_name].options === "object" &&
            typeof this.active_widgets[widget_name].options.fields === "object"
          ) {
            let widget_options =
              this.active_widgets[widget_name].options.fields;

            for (let option in widget_options) {
              if (option === "icon" && widget_data.icon) {
                // update widget data
                widget_data.icon = widget_options[option]?.value;
                // update icon on available widgets
                this.available_widgets[widget_name].icon =
                  widget_options[option]?.value;
              }

              if (widget_data?.options?.fields) {
                // update widget data fields
                widget_data.options.fields[option] = widget_options[option];
                // update fields on available widgets
                this.available_widgets[widget_name].options.fields[option] =
                  widget_options[option];
              }
            }
          }

          data.push(widget_data);
        }

        return data;
      };

      // Parse Layout
      for (const placeholder of placeholders) {
        if ("placeholder_item" === placeholder.type) {
          const data = getWidgetData(placeholder);

          output.push({
            type: placeholder.type,
            placeholderKey: placeholder.placeholderKey,
            label: placeholder.label,
            selectedWidgets: data,
            acceptedWidgets: placeholder.acceptedWidgets,
            selectedWidgetList: placeholder.selectedWidgetList,
          });
          continue;
        }

        if ("placeholder_group" === placeholder.type) {
          let subGroupsData = [];

          for (const subPlaceholder of placeholder.placeholders) {
            const data = getWidgetData(subPlaceholder);

            subGroupsData.push({
              type: placeholder.type ? placeholder.type : "placeholder_item",
              placeholderKey: subPlaceholder.placeholderKey,
              label: subPlaceholder.label,
              selectedWidgets: data,
              acceptedWidgets: subPlaceholder.acceptedWidgets,
              selectedWidgetList: subPlaceholder.selectedWidgetList,
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

      this.placeholders = output;

      return output;
    },

    // available_widgets
    theAvailableWidgets() {
      let available_widgets = JSON.parse(
        JSON.stringify(this.available_widgets),
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

    // widget options window active status
    widgetOptionsWindowActiveStatus() {
      return (widgetKey) => {
        if (
          !widgetKey ||
          this.widgetOptionsWindow.widget === "" ||
          this.widgetOptionsWindow.widget !== widgetKey ||
          typeof this.active_widgets[widgetKey] === "undefined"
        ) {
          return false;
        }
        return true;
      };
    },

    // widget card options window active status
    widgetCardOptionsWindowActiveStatus() {
      if (!this.isObject(this.widgetCardOptionsWindow.widget)) {
        return false;
      }

      return true;
    },

    // video modal content
    modalContent() {
      return this.video;
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
      placeholders: [],
      showModal: false,
    };
  },

  methods: {
    init() {
      this.importWidgets();
      this.importCardOptions();
      this.importPlaceholders();
      this.importOldData();
    },

    // getChildPayload
    getChildPayload(index) {
      return this.placeholders[index];
    },

    // Handle the drop event
    onDrop(dropResult) {
      const draggablePlaceholders = this.placeholders.filter(
        (placeholder) => placeholder.type === "placeholder_item",
      );

      // Update only the filtered placeholders
      const updatedPlaceholders = applyDrag(draggablePlaceholders, dropResult);

      // Map the updated placeholders back to their original positions in the full array
      this.placeholders = this.placeholders.map((placeholder) => {
        if (placeholder.type === "placeholder_item") {
          return updatedPlaceholders.shift(); // Replace with the updated item
        }
        return placeholder; // Keep other placeholders unchanged
      });

      // Sync allPlaceholderItems with the updated placeholders
      let newAllPlaceholderItems = [];

      // Iterate over placeholders to update the newAllPlaceholderItems array
      this.placeholders.forEach((placeholder) => {
        if (placeholder.type === "placeholder_item") {
          // Find the matching item from allPlaceholderItems
          const matchedItem = this.allPlaceholderItems.find(
            (item) => item.placeholderKey === placeholder.placeholderKey,
          );

          // If a matched item is found, push it to newAllPlaceholderItems
          if (matchedItem) {
            newAllPlaceholderItems.push(matchedItem); // Push only the matchedItem
          }
        } else if (placeholder.type === "placeholder_group") {
          // Iterate over subPlaceholders for a group
          placeholder.placeholders.forEach((subPlaceholder) => {
            const matchedItem = this.allPlaceholderItems.find(
              (item) => item.placeholderKey === subPlaceholder.placeholderKey,
            );

            // If a matched item is found, push it to newAllPlaceholderItems
            if (matchedItem) {
              newAllPlaceholderItems.push(matchedItem); // Push only the matchedItem
            }
          });
        }
      });

      // Update allPlaceholderItems with the new array
      this.allPlaceholderItems = newAllPlaceholderItems;
    },

    // Get the payload for the settings child
    getSettingsChildPayload(draggedItemIndex, placeholderIndex) {
      // Return the payload containing both pieces of data
      return {
        draggedItemIndex: draggedItemIndex,
        placeholderIndex: placeholderIndex,
      };
    },

    // Handle the drop event on elements
    onElementsDrop(dropResult, placeholder_index) {
      const { removedIndex, addedIndex, payload } = dropResult;
      const { draggedItemIndex, placeholderIndex } = payload;

      if (removedIndex !== null || addedIndex !== null) {
        let destinationItemIndex;
        let destinationPlaceholderIndex;
        const sourceItemIndex = draggedItemIndex;
        const sourcePlaceholderIndex = placeholderIndex;

        if (addedIndex !== null) {
          destinationItemIndex = addedIndex;
          destinationPlaceholderIndex = placeholder_index;
        } else {
          destinationItemIndex = null;
          destinationPlaceholderIndex = null;
        }

        // Get the widget key from the source placeholder
        const widgetKey =
          this.allPlaceholderItems[sourcePlaceholderIndex]?.acceptedWidgets[
            draggedItemIndex
          ];

        if (widgetKey !== undefined) {
          if (sourcePlaceholderIndex === destinationPlaceholderIndex) {
            // Moving within the same placeholder
            const widgets =
              this.allPlaceholderItems[sourcePlaceholderIndex].acceptedWidgets;
            const selectedWidgets =
              this.allPlaceholderItems[sourcePlaceholderIndex].selectedWidgets;
            const selectedWidgetList =
              this.allPlaceholderItems[sourcePlaceholderIndex]
                .selectedWidgetList;

            // Remove the widget from the source position
            const [movedWidget] = widgets.splice(sourceItemIndex, 1);

            // Insert the widget at the destination position
            widgets.splice(destinationItemIndex, 0, movedWidget);

            // Update selectedWidgetList position based on acceptedWidgets
            const selectedWidgetIndex =
              selectedWidgetList && selectedWidgetList.indexOf(movedWidget);
            if (selectedWidgetIndex && selectedWidgetIndex !== -1) {
              // Remove the widget from the selected position
              selectedWidgetList.splice(selectedWidgetIndex, 1);

              // Insert the widget at the new position
              const newSelectedIndex = widgets.indexOf(movedWidget);
              selectedWidgetList.splice(newSelectedIndex, 0, movedWidget);
            }

            // Reorder `selectedWidgets` based on `selectedWidgetList`
            selectedWidgets &&
              selectedWidgets.sort((a, b) => {
                return (
                  selectedWidgetList.indexOf(a.widget_key) -
                  selectedWidgetList.indexOf(b.widget_key)
                );
              });

            // Update Placeholders
            const updatedPlaceholders =
              this.syncPlaceholdersWithAllPlaceholderItems(
                this.allPlaceholderItems,
                this.placeholders || [],
              );

            this.placeholders = updatedPlaceholders;
          } else if (destinationPlaceholderIndex !== null) {
            // Moving between different placeholders
            // this.allPlaceholderItems[destinationPlaceholderIndex].selectedWidgetList.splice(destinationItemIndex, 0, widgetKey);
            // this.allPlaceholderItems[sourcePlaceholderIndex].selectedWidgetList.splice(sourceItemIndex, 1);
          }
        }
      } else {
        return;
      }
    },

    // Check if an object is truthy
    isTruthyObject(obj) {
      if (!obj && typeof obj !== "object" && !Array.isArray(obj)) {
        return false;
      }

      return true;
    },

    // Check if a string is a valid JSON
    isJSON(string) {
      try {
        JSON.parse(string);
      } catch (e) {
        return false;
      }

      return true;
    },

    // Import Old Data
    importOldData() {
      let value = JSON.parse(JSON.stringify(this.value));

      if (!Array.isArray(value)) {
        return;
      }

      let newPlaceholders = [];
      let newAllPlaceholders = [];

      // Import Layout
      // -------------------------
      const addActiveWidget = (widget) => {
        // Ensure that the widget exists in the available widgets
        if (!this.theAvailableWidgets[widget.widget_name]) {
          console.error(
            `Widget ${widget.widget_name} not found in available widgets.`,
          );
          return; // Exit if widget is not available
        }

        let widgets_template = {
          ...this.theAvailableWidgets[widget.widget_name],
        };

        let has_widget_options = false;

        if (widgets_template.options && widgets_template.options.fields) {
          has_widget_options = true;
        }

        // Iterate over the properties of widgets_template and copy values from widget
        for (let root_option in widgets_template) {
          if ("options" === root_option) {
            continue;
          }

          // Ensure that the value exists in the widget and is not undefined
          if (typeof widget[root_option] === "undefined") {
            continue;
          }

          widgets_template[root_option] = widget[root_option];
        }

        // Handle widget options fields
        if (has_widget_options) {
          for (let option_key in widgets_template.options.fields) {
            if (typeof widget.options?.fields[option_key] === "undefined") {
              continue;
            }

            widgets_template.options.fields[option_key] =
              widget.options.fields[option_key];
          }
        }

        // Set the widget data in the active_widgets object
        Vue.set(this.active_widgets, widget.widget_name, widgets_template);
      };

      const importWidgets = (placeholder, destination) => {
        if (!this.placeholdersMap.hasOwnProperty(placeholder.placeholderKey)) {
          return;
        }

        let newPlaceholder = JSON.parse(
          JSON.stringify(this.placeholdersMap[placeholder.placeholderKey]),
        );

        if (placeholder.acceptedWidgets) {
          newPlaceholder.acceptedWidgets = placeholder.acceptedWidgets;
        }

        if (placeholder.selectedWidgets) {
          newPlaceholder.selectedWidgets = placeholder.selectedWidgets;
          newPlaceholder.selectedWidgetList = placeholder.selectedWidgets.map(
            (widget) => widget.widget_name,
          );
        }

        newPlaceholder.maxWidget =
          typeof newPlaceholder.maxWidget !== "undefined"
            ? parseInt(newPlaceholder.maxWidget)
            : 0;

        newAllPlaceholders.push(newPlaceholder);
        let targetPlaceholderIndex = destination.length;

        destination.splice(targetPlaceholderIndex, 0, newPlaceholder);

        // Add active widgets based on selectedWidgets
        placeholder.selectedWidgets.forEach((widget) => {
          if (
            typeof widget !== "undefined" &&
            typeof this.available_widgets[widget.widget_name] !== "undefined"
          ) {
            addActiveWidget(widget);
          }
        });
      };

      value.forEach((placeholder, index) => {
        if (!this.isTruthyObject(placeholder)) {
          return;
        }

        if ("placeholder_item" === placeholder.type) {
          // if (!Array.isArray(placeholder.selectedWidgets)) {
          //   return;
          // }

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
            JSON.stringify(this.placeholdersMap[placeholder.placeholderKey]),
          );
          newPlaceholder.placeholders = [];
          let targetPlaceholderIndex = this.placeholders.length;

          newPlaceholders.splice(targetPlaceholderIndex, 0, newPlaceholder);

          placeholder.placeholders.forEach((subPlaceholder) => {
            // if (!Array.isArray(subPlaceholder.selectedWidgets)) {
            //   return;
            // }

            importWidgets(subPlaceholder, newPlaceholders[index].placeholders);
          });
        }
      });

      this.placeholders = newPlaceholders;
      this.allPlaceholderItems = newAllPlaceholders;
    },

    // Import Widgets
    importWidgets() {
      if (!this.isTruthyObject(this.widgets)) {
        return;
      }

      this.available_widgets = this.widgets;
    },

    // Import Card Options
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
          JSON.parse(JSON.stringify(this.cardOptions[section])),
        );
      }
    },

    // Import Placeholders
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

        if (typeof placeholder.label === "undefined") {
          placeholder.label = "";
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
          placeholderItem,
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
                  placeholderSubItem.placeholderKey,
                )
              ) {
                placeholderItem.placeholders.splice(subPlaceholderIndex, 1);
                return;
              }

              Vue.set(
                this.placeholdersMap,
                placeholderSubItem.placeholderKey,
                placeholderSubItem,
              );

              const placeholderItemData =
                sanitizePlaceholderData(placeholderSubItem);

              if (placeholderItemData) {
                placeholderItem.placeholders.splice(
                  subPlaceholderIndex,
                  1,
                  placeholderItemData,
                );
                this.allPlaceholderItems.push(placeholderItemData);
              }
            },
          );

          if (placeholderItem.placeholders.length) {
            sanitizedPlaceholders.push(placeholderItem);
          }
        }
      }

      this.placeholders = sanitizedPlaceholders;
    },

    // Handle widget toggle from UI
    handleWidgetSwitch(event, widget_key, placeholder_index) {
      const placeholder = this.allPlaceholderItems[placeholder_index];

      // Return if placeholder is not found
      if (!placeholder) {
        return;
      }

      // Prevent selecting more than maxWidget
      if (
        event.target.checked &&
        placeholder.maxWidget > 0 &&
        placeholder.selectedWidgets?.length >= placeholder.maxWidget
      ) {
        event.preventDefault(); // Prevent the checkbox from being checked
        return;
      }

      const isChecked = event.target.checked;

      // Toggle widget in selectedWidgets
      this.toggleWidgetInSelectedWidgets(
        widget_key,
        placeholder_index,
        isChecked,
      );

      // Sync selectedWidgets between allPlaceholderItems and placeholders
      this.placeholders = this.syncSelectedWidgets(
        this.allPlaceholderItems,
        this.placeholders,
      );
    },

    // Add/remove widget from selectedWidgets & active_widgets
    toggleWidgetInSelectedWidgets(widget_key, placeholder_index, isChecked) {
      const placeholder = this.allPlaceholderItems[placeholder_index];
      const acceptedWidgets = placeholder.acceptedWidgets || [];
      let selectedWidgets = placeholder.selectedWidgets || [];
      let selectedWidgetList = placeholder.selectedWidgetList || [];

      if (!Array.isArray(selectedWidgets)) {
        selectedWidgets = Object.values(selectedWidgets); // Convert object to array if needed
      }

      if (isChecked) {
        // Add widget if it does not exist
        if (
          !selectedWidgets.some((widget) => widget.widget_key === widget_key)
        ) {
          const widgetIndex = acceptedWidgets.indexOf(widget_key);
          if (widgetIndex !== -1) {
            selectedWidgetList.push(widget_key);
            selectedWidgets.push(this.theAvailableWidgets[widget_key]);
          }
        }
      } else {
        // Remove widget if unchecked
        selectedWidgets = selectedWidgets.filter(
          (widget) => widget.widget_key !== widget_key,
        );
        selectedWidgetList = selectedWidgetList.filter(
          (widget) => widget !== widget_key,
        );
      }

      // Sort the selectedWidgetList and selectedWidgets based on acceptedWidgets order
      selectedWidgetList.sort(
        (a, b) => acceptedWidgets.indexOf(a) - acceptedWidgets.indexOf(b),
      );
      selectedWidgets.sort(
        (a, b) =>
          acceptedWidgets.indexOf(a.widget_key) -
          acceptedWidgets.indexOf(b.widget_key),
      );

      // Update selectedWidgets array
      this.$set(
        this.allPlaceholderItems[placeholder_index],
        "selectedWidgets",
        selectedWidgets,
      );
      this.$set(
        this.allPlaceholderItems[placeholder_index],
        "selectedWidgetList",
        selectedWidgetList,
      );

      // Update active_widgets separately
      if (isChecked) {
        this.$set(
          this.active_widgets,
          widget_key,
          this.theAvailableWidgets[widget_key],
        );
      } else {
        this.$delete(this.active_widgets, widget_key);
      }
    },

    // Sync selectedWidgets across placeholders
    syncSelectedWidgets(allPlaceholderItems, placeholders) {
      const allItemsMap = allPlaceholderItems.reduce((acc, item) => {
        acc[item.placeholderKey] = item;
        return acc;
      }, {});

      const updatePlaceholders = (placeholders) => {
        return placeholders.map((placeholder) => {
          if (allItemsMap[placeholder.placeholderKey]) {
            let selectedWidgets =
              allItemsMap[placeholder.placeholderKey].selectedWidgets || [];
            let selectedWidgetList =
              allItemsMap[placeholder.placeholderKey].selectedWidgetList || [];

            if (!Array.isArray(selectedWidgetList)) {
              selectedWidgetList = Object.values(selectedWidgetList);
            }
            Vue.set(placeholder, "selectedWidgets", selectedWidgets);
            Vue.set(placeholder, "selectedWidgetList", selectedWidgetList);
          }

          if (
            placeholder.type === "placeholder_group" &&
            placeholder.placeholders
          ) {
            Vue.set(
              placeholder,
              "placeholders",
              updatePlaceholders(placeholder.placeholders),
            );
          }

          return placeholder;
        });
      };

      return updatePlaceholders(placeholders);
    },

    // Sync placeholders with allPlaceholderItems
    syncPlaceholdersWithAllPlaceholderItems(allPlaceholderItems, placeholders) {
      const updatePlaceholderItem = (placeholder, allPlaceholderItem) => {
        if (placeholder.placeholderKey === allPlaceholderItem.placeholderKey) {
          placeholder.acceptedWidgets = [...allPlaceholderItem.acceptedWidgets];
          let selectedWidgets = allPlaceholderItem.selectedWidgets || [];
          let selectedWidgetList = allPlaceholderItem.selectedWidgetList || [];

          placeholder.selectedWidgets = [...selectedWidgets];
          placeholder.selectedWidgetList = [...selectedWidgetList];
        }
      };

      const updatePlaceholders = (placeholders) => {
        placeholders &&
          placeholders.forEach((placeholder) => {
            if (placeholder.type === "placeholder_group") {
              updatePlaceholders(placeholder.placeholders);
            } else if (placeholder.type === "placeholder_item") {
              const matchingItem = allPlaceholderItems.find(
                (item) => item.placeholderKey === placeholder.placeholderKey,
              );

              if (matchingItem) {
                updatePlaceholderItem(placeholder, matchingItem);
              }
            }
          });
      };

      updatePlaceholders(placeholders);
      return placeholders;
    },

    // Edit Widget
    editWidget(key) {
      if (key === this.widgetOptionsWindow.widget) {
        this.closeWidgetOptionsWindow();
        return;
      }
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

    // Update Widget Options
    updateWidgetOptionsData(data, options_window) {
      return;
    },

    // Close Widget Options Window
    closeWidgetOptionsWindow() {
      this.widgetOptionsWindow = this.widgetOptionsWindowDefault;
    },

    // Get Active Insert Window Status
    getActiveInsertWindowStatus(current_item_key) {
      if (current_item_key === this.active_insert_widget_key) {
        return true;
      }

      return false;
    },

    // Open the modal
    openModal() {
      this.showModal = true;
    },

    // Close the modal
    closeModal() {
      this.showModal = false;
    },
  },
};
</script>
