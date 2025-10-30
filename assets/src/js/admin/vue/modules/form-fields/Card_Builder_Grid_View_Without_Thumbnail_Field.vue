<template>
  <div class="cptm-builder-section">
    <!-- cptm-preview-area -->
    <div class="cptm-preview-area">
      <div class="cptm-card-preview-widget grid-view-without-thumbnail">
        <div class="cptm-card-preview-widget-content">
          <!-- cptm-listing-card-preview-top -->
          <div class="cptm-card-placeholder-top">
            <div class="cptm-listing-card-author-avatar">
              <card-widget-placeholder
                id="no_thumbnail_body_avatar"
                :containerClass="getAvatarPlaceholderClass"
                :label="local_layout.body.avatar.label"
                :enable_widget="local_layout.body.avatar.enable_widget"
                :availableWidgets="theAvailableWidgets"
                :activeWidgets="active_widgets"
                :acceptedWidgets="local_layout.body.avatar.acceptedWidgets"
                :selectedWidgets="local_layout.body.avatar.selectedWidgets"
                :maxWidget="local_layout.body.avatar.maxWidget"
                :showWidgetsPickerWindow="
                  getActiveInsertWindowStatus('no_thumbnail_body_avatar')
                "
                :widgetOptionsWindow="widgetOptionsWindow"
                @insert-widget="insertWidget($event, local_layout.body.avatar)"
                @edit-widget="editWidget($event)"
                @trash-widget="trashWidget($event, local_layout.body.avatar)"
                @open-widgets-picker-window="
                  toggleInsertWindow('no_thumbnail_body_avatar')
                "
                @open-widgets-option-window="
                  toggleOptionWindow('no_thumbnail_body_avatar')
                "
                @close-widgets-picker-window="closeInsertWindow()"
                @close-widgets-option-window="closeOptionWindow()"
                @toggle-widget-status="
                  toggleWidgetStatus(local_layout.body.avatar)
                "
                @update-option-window="
                  updateWidgetOptionsData($event, widgetOptionsWindow)
                "
                @close-option-window="closeWidgetOptionsWindow()"
                @activate-widget-options="toggleActivateWidgetOptions"
              />
            </div>

            <div class="cptm-listing-card-title">
              <card-widget-placeholder
                id="no_thumbnail_body_title"
                containerClass="cptm-listing-card-preview-title-placeholder"
                :label="local_layout.body.title.label"
                :enable_widget="local_layout.body.title.enable_widget"
                :availableWidgets="theAvailableWidgets"
                :activeWidgets="active_widgets"
                :acceptedWidgets="local_layout.body.title.acceptedWidgets"
                :selectedWidgets="local_layout.body.title.selectedWidgets"
                :maxWidget="local_layout.body.title.maxWidget"
                :widgetOptionsWindow="widgetOptionsWindow"
                :showWidgetsPickerWindow="
                  getActiveInsertWindowStatus('no_thumbnail_body_title')
                "
                :showWidgetsOptionWindow="
                  getActiveOptionWindowStatus('no_thumbnail_body_title')
                "
                @edit-widget="editWidget($event)"
                @insert-widget="insertWidget($event, local_layout.body.title)"
                @trash-widget="trashWidget($event, local_layout.body.title)"
                @toggle-widget-status="
                  toggleWidgetStatus(local_layout.body.title)
                "
                @open-widgets-picker-window="
                  toggleInsertWindow('no_thumbnail_body_title')
                "
                @close-widgets-picker-window="closeInsertWindow()"
              />
            </div>

            <div class="cptm-listing-card-quick-actions">
              <card-widget-placeholder
                id="no_thumbnail_body_quick_actions"
                containerClass="cptm-card-preview-quick-actions-placeholder"
                :label="local_layout.body.quick_actions.label"
                :availableWidgets="theAvailableWidgets"
                :activeWidgets="active_widgets"
                :acceptedWidgets="
                  local_layout.body.quick_actions.acceptedWidgets
                "
                :selectedWidgets="
                  local_layout.body.quick_actions.selectedWidgets
                "
                :maxWidget="local_layout.body.quick_actions.maxWidget"
                :showWidgetsPickerWindow="
                  getActiveInsertWindowStatus('no_thumbnail_body_quick_actions')
                "
                :showWidgetsOptionWindow="
                  getActiveOptionWindowStatus('no_thumbnail_body_quick_actions')
                "
                :widgetOptionsWindow="widgetOptionsWindow"
                :canOpenSettings="true"
                @insert-widget="
                  insertWidget($event, local_layout.body.quick_actions)
                "
                @trash-widget="
                  trashWidget($event, local_layout.body.quick_actions)
                "
                @open-widgets-picker-window="
                  toggleInsertWindow('no_thumbnail_body_quick_actions')
                "
                @open-widgets-option-window="
                  toggleOptionWindow('no_thumbnail_body_quick_actions')
                "
                @close-widgets-picker-window="closeInsertWindow()"
                @close-widgets-option-window="closeOptionWindow()"
                @update="
                  handleUpdateSelectedWidgets(
                    $event,
                    'local_layout.body.quick_actions',
                  )
                "
                @update-active-widget="handleActiveWidgetUpdate"
                @activate-widget-options="toggleActivateWidgetOptions"
                @update-option-window="
                  updateWidgetOptionsData($event, widgetOptionsWindow)
                "
                @close-option-window="closeWidgetOptionsWindow()"
              />
            </div>
          </div>

          <!-- cptm-listing-card-preview-body -->
          <div class="cptm-listing-card-preview-body">
            <card-widget-placeholder
              id="no_thumbnail_body_quick_info"
              containerClass="cptm-card-preview-quick-info-placeholder cptm-card-dark"
              :label="local_layout.body.quick_info.label"
              :availableWidgets="theAvailableWidgets"
              :activeWidgets="active_widgets"
              :acceptedWidgets="local_layout.body.quick_info.acceptedWidgets"
              :selectedWidgets="local_layout.body.quick_info.selectedWidgets"
              :maxWidget="local_layout.body.quick_info.maxWidget"
              :showWidgetsPickerWindow="
                getActiveInsertWindowStatus('no_thumbnail_body_quick_info')
              "
              :showWidgetsOptionWindow="
                getActiveOptionWindowStatus('no_thumbnail_body_quick_info')
              "
              :widgetOptionsWindow="widgetOptionsWindow"
              :canOpenSettings="true"
              @insert-widget="
                insertWidget($event, local_layout.body.quick_info)
              "
              @edit-widget="editWidget($event)"
              @trash-widget="trashWidget($event, local_layout.body.quick_info)"
              @open-widgets-picker-window="
                toggleInsertWindow('no_thumbnail_body_quick_info')
              "
              @open-widgets-option-window="
                toggleOptionWindow('no_thumbnail_body_quick_info')
              "
              @close-widgets-picker-window="closeInsertWindow()"
              @close-widgets-option-window="closeOptionWindow()"
              @update="
                handleUpdateSelectedWidgets(
                  $event,
                  'local_layout.body.quick_info',
                )
              "
              @update-active-widget="handleActiveWidgetUpdate"
              @activate-widget-options="toggleActivateWidgetOptions"
            />

            <card-widget-placeholder
              id="no_thumbnail_body_bottom"
              containerClass="cptm-listing-card-preview-body-placeholder"
              :label="local_layout.body.bottom.label"
              :availableWidgets="theAvailableWidgets"
              :activeWidgets="active_widgets"
              :acceptedWidgets="local_layout.body.bottom.acceptedWidgets"
              :selectedWidgets="local_layout.body.bottom.selectedWidgets"
              :maxWidget="local_layout.body.bottom.maxWidget"
              :showWidgetsPickerWindow="
                getActiveInsertWindowStatus('no_thumbnail_body_bottom')
              "
              :showWidgetsOptionWindow="
                getActiveOptionWindowStatus('no_thumbnail_body_bottom')
              "
              :widgetOptionsWindow="widgetOptionsWindow"
              :canDragAndDrop="true"
              @insert-widget="insertWidget($event, local_layout.body.bottom)"
              @edit-widget="editWidget($event)"
              @trash-widget="trashWidget($event, local_layout.body.bottom)"
              @open-widgets-picker-window="
                toggleInsertWindow('no_thumbnail_body_bottom')
              "
              @open-widgets-option-window="
                toggleOptionWindow('no_thumbnail_body_bottom')
              "
              @close-widgets-picker-window="closeInsertWindow()"
              @close-widgets-option-window="closeOptionWindow()"
              @close-option-window="closeWidgetOptionsWindow()"
              @update="
                handleUpdateSelectedWidgets($event, 'local_layout.body.bottom')
              "
              @update-active-widget="handleActiveWidgetUpdate"
              @activate-widget-options="toggleActivateWidgetOptions"
            />
          </div>

          <!-- cptm-listing-card-preview-footer -->
          <div class="cptm-listing-card-preview-footer">
            <!-- cptm-listing-card-preview-footer-left-placeholder -->
            <div class="cptm-card-preview-footer-left">
              <card-widget-placeholder
                id="no_thumbnail_footer_left"
                containerClass="cptm-listing-card-preview-footer-left-placeholder"
                :label="local_layout.footer.left.label"
                :availableWidgets="theAvailableWidgets"
                :activeWidgets="active_widgets"
                :acceptedWidgets="local_layout.footer.left.acceptedWidgets"
                :selectedWidgets="local_layout.footer.left.selectedWidgets"
                :maxWidget="local_layout.footer.left.maxWidget"
                :showWidgetsPickerWindow="
                  getActiveInsertWindowStatus('no_thumbnail_footer_left')
                "
                :showWidgetsOptionWindow="
                  getActiveOptionWindowStatus('no_thumbnail_footer_left')
                "
                :widgetOptionsWindow="widgetOptionsWindow"
                :canOpenSettings="true"
                @insert-widget="insertWidget($event, local_layout.footer.left)"
                @edit-widget="editWidget($event)"
                @trash-widget="trashWidget($event, local_layout.footer.left)"
                @open-widgets-picker-window="
                  toggleInsertWindow('no_thumbnail_footer_left')
                "
                @open-widgets-option-window="
                  toggleOptionWindow('no_thumbnail_footer_left')
                "
                @close-widgets-picker-window="closeInsertWindow()"
                @close-widgets-option-window="closeOptionWindow()"
                @close-option-window="closeWidgetOptionsWindow()"
                @update="
                  handleUpdateSelectedWidgets(
                    $event,
                    'local_layout.footer.left',
                  )
                "
                @update-active-widget="handleActiveWidgetUpdate"
                @activate-widget-options="toggleActivateWidgetOptions"
              />
            </div>

            <!-- cptm-listing-card-preview-footer-right-placeholder -->
            <div class="cptm-card-preview-footer-right">
              <card-widget-placeholder
                id="no_thumbnail_footer_right"
                containerClass="cptm-listing-card-preview-footer-right-placeholder"
                :label="local_layout.footer.right.label"
                :availableWidgets="theAvailableWidgets"
                :activeWidgets="active_widgets"
                :acceptedWidgets="local_layout.footer.right.acceptedWidgets"
                :selectedWidgets="local_layout.footer.right.selectedWidgets"
                :maxWidget="local_layout.footer.right.maxWidget"
                :showWidgetsPickerWindow="
                  getActiveInsertWindowStatus('no_thumbnail_footer_right')
                "
                :showWidgetsOptionWindow="
                  getActiveOptionWindowStatus('no_thumbnail_footer_right')
                "
                :widgetOptionsWindow="widgetOptionsWindow"
                :canOpenSettings="true"
                @insert-widget="insertWidget($event, local_layout.footer.right)"
                @edit-widget="editWidget($event)"
                @trash-widget="trashWidget($event, local_layout.footer.right)"
                @open-widgets-picker-window="
                  toggleInsertWindow('no_thumbnail_footer_right')
                "
                @open-widgets-option-window="
                  toggleOptionWindow('no_thumbnail_footer_right')
                "
                @close-widgets-picker-window="closeInsertWindow()"
                @close-widgets-option-window="closeOptionWindow()"
                @close-option-window="closeWidgetOptionsWindow()"
                @update="
                  handleUpdateSelectedWidgets(
                    $event,
                    'local_layout.footer.right',
                  )
                "
                @update-active-widget="handleActiveWidgetUpdate"
                @activate-widget-options="toggleActivateWidgetOptions"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import helpers from "../../mixins/helpers";
import card_builder from "./../../mixins/form-fields/card-builder";

export default {
  name: "card-builder-grid-view-without-thumbnail-field",
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
    // Output Data
    output_data() {
      let output = {};
      let layout = this.local_layout;

      for (let section in layout) {
        output[section] = {};

        if (typeof layout[section] !== "object") {
          continue;
        }

        for (let section_area in layout[section]) {
          output[section][section_area] = [];

          if (typeof layout[section][section_area] !== "object") {
            continue;
          }
          if (
            typeof layout[section][section_area].selectedWidgets !== "object"
          ) {
            continue;
          }

          for (let widget in layout[section][section_area].selectedWidgets) {
            const widget_name =
              layout[section][section_area].selectedWidgets[widget];

            if (
              !this.active_widgets[widget_name] &&
              typeof this.active_widgets[widget_name] !== "object"
            ) {
              continue;
            }

            let widget_data = {};
            for (let root_option in this.active_widgets[widget_name]) {
              if ("show_if" === root_option) {
                continue;
              }

              widget_data[root_option] =
                this.active_widgets[widget_name][root_option];
            }

            if (typeof this.active_widgets[widget_name].options !== "object") {
              output[section][section_area].push(widget_data);
              continue;
            }

            if (
              typeof this.active_widgets[widget_name].options.fields !==
              "object"
            ) {
              output[section][section_area].push(widget_data);
              continue;
            }

            let widget_options =
              this.active_widgets[widget_name].options.fields;

            for (let option in widget_options) {
              widget_data[option] = widget_options[option].value;
            }

            output[section][section_area].push(widget_data);
          }
        }
      }

      return output;
    },

    // Available Widgets
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
                _main_widget.original_widget_key = matched_field.widget_key;
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

    // Widget Options Window Active Status
    widgetOptionsWindowActiveStatus() {
      if (
        !this.widgetOptionsWindow.widget ||
        this.widgetOptionsWindow.widget.length === 0
      ) {
        return false;
      }
      if (
        typeof this.active_widgets[this.widgetOptionsWindow.widget] ===
        "undefined"
      ) {
        return false;
      }

      return true;
    },

    // Get Avatar Placeholder Class
    getAvatarPlaceholderClass() {
      let accepted_align_options = ["right", "center", "left"];
      let align_option = "";
      let active_widgets = JSON.parse(JSON.stringify(this.active_widgets));

      let has_option = false;

      if (this.isObject(active_widgets)) {
        has_option = true;
      }
      if (has_option && !active_widgets.user_avatar) {
        has_option = false;
      }
      if (has_option && !active_widgets.user_avatar.options) {
        has_option = false;
      }
      if (has_option && !active_widgets.user_avatar.options.fields) {
        has_option = false;
      }
      if (has_option && !active_widgets.user_avatar.options.fields.align) {
        has_option = false;
      }

      if (
        has_option &&
        !(
          typeof active_widgets.user_avatar.options.fields.align.value ===
          "string"
        )
      ) {
        has_option = false;
      }

      if (has_option) {
        align_option = active_widgets.user_avatar.options.fields.align.value;
      }

      if (!accepted_align_options.includes(align_option)) {
        align_option = "center";
      }

      return {
        "cptm-listing-card-author-avatar-placeholder cptm-card-dark-light cptm-mb-20": true,
        "cptm-text-right": "right" === align_option ? true : false,
        "cptm-text-center": "center" === align_option ? true : false,
        "cptm-text-left": "left" === align_option ? true : false,
      };
    },
  },

  data() {
    return {
      active_insert_widget_key: "",
      active_option_widget_key: "",

      // Widget Options Window
      widgetOptionsWindowDefault: {
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

      // Layout
      local_layout: {
        body: {
          avatar: {
            label: "Add Avatar",
            selectedWidgets: [],
          },
          title: {
            label: "Title",
            selectedWidgets: [],
          },
          quick_actions: {
            label: "Top Right",
            selectedWidgets: [],
          },
          quick_info: {
            label: "Quick Info",
            selectedWidgets: [],
          },
          bottom: {
            label: "Add Elements",
            selectedWidgets: [],
          },
        },

        footer: {
          right: {
            label: "Footer Right",
            selectedWidgets: [],
          },
          left: {
            label: "Footer Left",
            selectedWidgets: [],
          },
        },
      },
    };
  },

  methods: {
    init() {
      this.importWidgets();
      this.importLayout();
      this.importOldData();
    },

    // isTruthyObject check
    isTruthyObject(obj) {
      if (!obj && typeof obj !== "object") {
        return false;
      }

      return true;
    },

    // Import Old Data
    importOldData() {
      let value = JSON.parse(JSON.stringify(this.value));

      if (!this.isTruthyObject(value)) {
        return;
      }
      let selectedWidgets = [];

      // Get Active Widgets Data
      let active_widgets_data = {};
      for (let section in value) {
        if (!value[section] && typeof value[section] !== "object") {
          continue;
        }

        for (let area in value[section]) {
          if (
            !value[section][area] &&
            typeof value[section][area] !== "object"
          ) {
            continue;
          }

          for (let widget of value[section][area]) {
            if (typeof widget.widget_name === "undefined") {
              continue;
            }
            if (typeof widget.widget_key === "undefined") {
              continue;
            }
            if (
              typeof this.available_widgets[widget.widget_name] === "undefined"
            ) {
              continue;
            }
            if (typeof this.local_layout[section] === "undefined") {
              continue;
            }
            if (typeof this.local_layout[section][area] === "undefined") {
              continue;
            }

            active_widgets_data[widget.widget_key] = widget;
            selectedWidgets.push({
              section: section,
              area: area,
              widget: widget.widget_key,
            });
          }
        }
      }

      // Load Active Widgets
      for (let widget_key in active_widgets_data) {
        if (typeof this.theAvailableWidgets[widget_key] === "undefined") {
          continue;
        }

        let widgets_template = { ...this.theAvailableWidgets[widget_key] };
        // let widget_options = ( ! active_widgets_data[widget_key].options && typeof active_widgets_data[widget_key].options !== "object" ) ? false : active_widgets_data[widget_key].options;

        for (let root_option in widgets_template) {
          // if ("options" === root_option) {
          //   continue;
          // }
          if (
            typeof active_widgets_data[widget_key][root_option] === "undefined"
          ) {
            continue;
          }

          widgets_template[root_option] =
            active_widgets_data[widget_key][root_option];
        }

        let has_widget_options = false;
        if (widgets_template.options && widgets_template.options.fields) {
          has_widget_options = true;
        }

        if (has_widget_options) {
          for (let option_key in widgets_template.options.fields) {
            if (
              typeof active_widgets_data[widget_key][option_key] === "undefined"
            ) {
              continue;
            }
            widgets_template.options.fields[option_key].value =
              active_widgets_data[widget_key][option_key];
          }
        }

        Vue.set(this.active_widgets, widget_key, widgets_template);
        Vue.set(this.available_widgets, widget_key, widgets_template);
      }

      // Load Selected Widgets Data
      for (let item of selectedWidgets) {
        const currentWidgets =
          this.local_layout[item.section][item.area].selectedWidgets;

        // Check if widget already exists to prevent duplicates
        if (!currentWidgets.includes(item.widget)) {
          // If it's listing_title, add as first item
          if (item.widget === "listing_title") {
            currentWidgets.unshift(item.widget);
          } else {
            // For other widgets, add to the end
            currentWidgets.push(item.widget);
          }
        }
      }
    },

    // Import Widgets
    importWidgets() {
      if (!this.isTruthyObject(this.widgets)) {
        return;
      }

      this.available_widgets = this.widgets;
    },

    // Import Layout
    importLayout() {
      if (!this.isTruthyObject(this.layout)) {
        return;
      }

      for (let section in this.local_layout) {
        if (!this.isTruthyObject(this.layout[section])) {
          continue;
        }

        for (let area in this.local_layout[section]) {
          if (!this.isTruthyObject(this.layout[section][area])) {
            continue;
          }

          Object.assign(
            this.local_layout[section][area],
            this.layout[section][area],
          );
        }
      }
    },

    // Edit Widget
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

      let opt = this.active_widgets[key].options;
      // Force Vue reactivity by using Vue.set or restructuring
      this.$set(this, "widgetOptionsWindow", {
        ...this.widgetOptionsWindowDefault,
        ...opt,
        widget: key,
      });

      // Also update the active_option_widget_key for consistency
      this.active_option_widget_key = key;
    },

    // Update Widget Options Data
    updateWidgetOptionsData(data, widget) {
      return;
    },

    // Close Widget Options Window
    closeWidgetOptionsWindow() {
      this.widgetOptionsWindow = this.widgetOptionsWindowDefault;
      // Also clear the active_option_widget_key for consistency
      this.active_option_widget_key = "";
    },

    // Trash Widget
    trashWidget(key, where) {
      if (!where.selectedWidgets.includes(key)) {
        return;
      }

      let index = where.selectedWidgets.indexOf(key);
      Vue.delete(where.selectedWidgets, index);

      if (typeof this.active_widgets[key] === "undefined") {
        return;
      }
      Vue.delete(this.active_widgets, key);

      if (key === this.widgetOptionsWindow.widget) {
        this.closeWidgetOptionsWindow();
      }

      // Also clear active_option_widget_key if this widget was active
      if (this.active_option_widget_key === key) {
        this.active_option_widget_key = "";
      }
    },

    // Toggle Widget Status
    toggleWidgetStatus(layout) {
      if (layout.selectedWidgets.length > 0) {
        layout.selectedWidgets?.map((widget) => {
          this.trashWidget(widget, layout);
        });
      } else {
        layout.acceptedWidgets?.map((widget) => {
          this.insertWidget(
            { key: widget, selected_widgets: [widget] },
            layout,
          );
        });
      }
    },

    // Toggle Insert Window
    toggleInsertWindow(current_item_key) {
      if (this.active_insert_widget_key === current_item_key) {
        this.active_insert_widget_key = "";
        this.active_option_widget_key = "";
        return;
      }

      // Close all other modals before opening insert window
      this.active_option_widget_key = "";
      this.closeWidgetOptionsWindow();

      // Open the insert window
      this.active_insert_widget_key = current_item_key;
    },

    // Toggle Option Window
    toggleOptionWindow(current_item_key) {
      if (this.active_option_widget_key === current_item_key) {
        this.active_option_widget_key = "";
        return;
      }

      // Close all other modals before opening option window
      this.active_insert_widget_key = "";
      this.closeWidgetOptionsWindow();

      // Open the option window
      this.active_option_widget_key = current_item_key;
    },

    // Insert Widget
    insertWidget(payload, where) {
      if (!this.isTruthyObject(this.theAvailableWidgets[payload.key])) {
        return;
      }

      Vue.set(this.active_widgets, payload.key, {
        ...this.theAvailableWidgets[payload.key],
      });
      Vue.set(where, "selectedWidgets", payload.selected_widgets);
    },

    // Close Insert Window
    closeInsertWindow() {
      this.active_insert_widget_key = "";
    },

    // Close Option Window
    closeOptionWindow() {
      this.active_option_widget_key = "";
    },

    // Close Widget Options Window
    closeWidgetOptionsWindow() {
      this.active_option_widget_key = "";
      this.$set(this.widgetOptionsWindow, "widget", "");
    },

    // Get Active Insert Window Status
    getActiveInsertWindowStatus(current_item_key) {
      if (current_item_key === this.active_insert_widget_key) {
        return true;
      }

      return false;
    },

    // Get Active Option Window Status
    getActiveOptionWindowStatus(current_item_key) {
      if (current_item_key === this.active_option_widget_key) {
        return true;
      }

      return false;
    },

    // Is Placeholder Active
    placeholderIsActive(layout) {
      if (!this.isObject(layout.show_if)) {
        return true;
      }

      let check_condition = this.checkShowIfCondition({
        condition: layout.show_if,
      });
      return check_condition.status;
    },

    // Handle Update Selected Widgets
    handleUpdateSelectedWidgets(updatedWidgets, path) {
      // Split the path into keys
      const pathKeys = path.split(".");

      // Navigate through the object dynamically
      let obj = this;
      for (let i = 0; i < pathKeys.length - 1; i++) {
        obj = obj[pathKeys[i]]; // Navigate deeper into the object
      }

      // Update the selectedWidgets at the correct path
      obj[pathKeys[pathKeys.length - 1]].selectedWidgets = updatedWidgets;
    },

    // Handle Update Selected Widgets
    handleActiveWidgetUpdate({ widgetKey, updatedWidget }) {
      this.$set(this.active_widgets, widgetKey, updatedWidget);
      this.$set(this.available_widgets, widgetKey, updatedWidget);
    },

    // Activate Widget Options
    toggleActivateWidgetOptions(widgetKey) {
      // Always activate the widget options
      this.$set(this.widgetOptionsWindow, "widget", widgetKey);
      this.active_option_widget_key = widgetKey;
    },
  },
};
</script>
