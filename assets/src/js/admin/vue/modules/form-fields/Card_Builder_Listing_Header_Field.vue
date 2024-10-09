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
    <div class="cptm-preview-placeholder">
      <div class="cptm-preview-placeholder__card">
        <!-- Draggable Bottom Widgets -->
        <Container @drop="onDrop" drag-handle-selector=".cptm-drag-element">
          <Draggable
            v-for="(placeholderItem, index) in placeholders"
            :key="index"
          >
            <div
              v-if="placeholderItem.type == 'placeholder_group'"
              class="draggable-item"
            >
              <div
                class="cptm-preview-placeholder__card__item cptm-preview-placeholder__card__item--top"
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

              <div class="cptm-drag-element las la-arrows-alt"></div>
            </div>

            <div
              v-if="placeholderItem.type == 'placeholder_item'"
              class="draggable-item"
            >
              <div
                class="cptm-preview-placeholder__card__item cptm-preview-placeholder__card__item--bottom"
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
              }
            }
          );

          if (placeholderItem.placeholders.length) {
            sanitizedPlaceholders.push(placeholderItem);
          }
        }
      }

      this.placeholders = sanitizedPlaceholders;
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
  },
};
</script>
