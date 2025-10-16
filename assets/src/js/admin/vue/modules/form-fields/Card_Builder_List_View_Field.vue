<template>
  <div class="cptm-builder-section">
    <!-- cptm-preview-area -->
    <div class="cptm-preview-area">
      <div
        class="cptm-card-preview-widget cptm-card-list-view list-view-with-thumbnail"
      >
        <!-- cptm-listing-card-preview-header -->
        <div class="cptm-listing-card-preview-header">
          <div class="cptm-card-preview-thumbnail">
            <div class="cptm-card-preview-thumbnail-overlay">
              <!-- cptm-card-preview-bottom-left -->
              <div class="cptm-card-preview-top-right">
                <card-widget-placeholder
                  id="thumbnail_top_right"
                  containerClass="cptm-listing-card-quick-info-placeholder cptm-card-dark cptm-text-right"
                  :label="local_layout.thumbnail.top_right.label"
                  :availableWidgets="theAvailableWidgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="
                    local_layout.thumbnail.top_right.acceptedWidgets
                  "
                  :selectedWidgets="
                    local_layout.thumbnail.top_right.selectedWidgets
                  "
                  :maxWidget="local_layout.thumbnail.top_right.maxWidget"
                  :showWidgetsPickerWindow="
                    getActiveInsertWindowStatus('top_top_right')
                  "
                  :showWidgetsOptionWindow="
                    getActiveOptionWindowStatus('top_top_right')
                  "
                  :widgetOptionsWindow="widgetOptionsWindow"
                  @insert-widget="
                    insertWidget($event, local_layout.thumbnail.top_right)
                  "
                  @edit-widget="editWidget($event)"
                  @trash-widget="
                    trashWidget($event, local_layout.thumbnail.top_right)
                  "
                  @open-widgets-picker-window="
                    toggleInsertWindow('top_top_right')
                  "
                  @open-widgets-option-window="
                    toggleOptionWindow('top_top_right')
                  "
                  @close-widgets-picker-window="closeInsertWindow()"
                  @close-widgets-option-window="closeOptionWindow()"
                  @update="
                    handleUpdateSelectedWidgets(
                      $event,
                      'local_layout.thumbnail.top_right',
                    )
                  "
                  @update-active-widget="handleActiveWidgetUpdate"
                />
              </div>

              <div class="cptm-card-preview-thumbnail-bg">
                <svg
                  width="100"
                  height="80"
                  viewBox="0 0 100 80"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <g opacity="0.2" clip-path="url(#clip0_9916_95736)">
                    <path
                      d="M89.9951 0H9.99512C4.48012 0 -0.00488281 4.485 -0.00488281 10V70C-0.00488281 75.515 4.48012 80 9.99512 80H89.9951C95.5101 80 99.9951 75.515 99.9951 70V10C99.9951 4.485 95.5101 0 89.9951 0ZM22.4951 15C24.4842 15 26.3919 15.7902 27.7984 17.1967C29.2049 18.6032 29.9951 20.5109 29.9951 22.5C29.9951 24.4891 29.2049 26.3968 27.7984 27.8033C26.3919 29.2098 24.4842 30 22.4951 30C20.506 30 18.5983 29.2098 17.1918 27.8033C15.7853 26.3968 14.9951 24.4891 14.9951 22.5C14.9951 20.5109 15.7853 18.6032 17.1918 17.1967C18.5983 15.7902 20.506 15 22.4951 15ZM49.9951 65H14.9951L34.9951 40L42.4951 50L57.4951 30L84.9951 65H49.9951Z"
                      fill="#4D5761"
                    />
                  </g>
                  <defs>
                    <clipPath id="clip0_9916_95736">
                      <rect width="100" height="80" fill="white" />
                    </clipPath>
                  </defs>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <div class="cptm-listing-card-content">
          <!-- cptm-listing-card-preview-body -->
          <div class="cptm-listing-card-preview-body">
            <div class="cptm-card-placeholder-top">
              <!-- cptm-placeholder-left-col -->
              <div class="cptm-card-placeholder-top-left">
                <card-widget-placeholder
                  id="top_quick_actions"
                  containerClass="cptm-listing-card-quick-actions-placeholder cptm-mb-20"
                  :label="local_layout.top.quick_actions.label"
                  :availableWidgets="theAvailableWidgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="
                    local_layout.top.quick_actions.acceptedWidgets
                  "
                  :selectedWidgets="
                    local_layout.top.quick_actions.selectedWidgets
                  "
                  :maxWidget="local_layout.top.quick_actions.maxWidget"
                  :showWidgetsPickerWindow="
                    getActiveInsertWindowStatus('top_quick_actions')
                  "
                  :showWidgetsOptionWindow="
                    getActiveOptionWindowStatus('top_quick_actions')
                  "
                  :widgetOptionsWindow="widgetOptionsWindow"
                  @insert-widget="
                    insertWidget($event, local_layout.top.quick_actions)
                  "
                  @edit-widget="editWidget($event)"
                  @trash-widget="
                    trashWidget($event, local_layout.top.quick_actions)
                  "
                  @open-widgets-picker-window="
                    toggleInsertWindow('top_quick_actions')
                  "
                  @open-widgets-option-window="
                    toggleOptionWindow('top_quick_actions')
                  "
                  @close-widgets-picker-window="closeInsertWindow()"
                  @close-widgets-option-window="closeOptionWindow()"
                  @update="
                    handleUpdateSelectedWidgets(
                      $event,
                      'local_layout.top.quick_actions',
                    )
                  "
                  @update-active-widget="handleActiveWidgetUpdate"
                />
              </div>

              <!-- cptm-placeholder-right-col -->
              <div class="cptm-card-placeholder-top-right">
                <card-widget-placeholder
                  id="top_quick_info"
                  containerClass="cptm-listing-card-quick-info-placeholder cptm-mb-20 cptm-text-right"
                  :label="local_layout.top.quick_info.label"
                  :availableWidgets="theAvailableWidgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="local_layout.top.quick_info.acceptedWidgets"
                  :selectedWidgets="local_layout.top.quick_info.selectedWidgets"
                  :maxWidget="local_layout.top.quick_info.maxWidget"
                  :showWidgetsPickerWindow="
                    getActiveInsertWindowStatus('top_quick_info')
                  "
                  :showWidgetsOptionWindow="
                    getActiveOptionWindowStatus('top_quick_info')
                  "
                  :widgetOptionsWindow="widgetOptionsWindow"
                  @insert-widget="
                    insertWidget($event, local_layout.top.quick_info)
                  "
                  @edit-widget="editWidget($event)"
                  @trash-widget="
                    trashWidget($event, local_layout.top.quick_info)
                  "
                  @open-widgets-picker-window="
                    toggleInsertWindow('top_quick_info')
                  "
                  @open-widgets-option-window="
                    toggleOptionWindow('top_quick_info')
                  "
                  @close-widgets-picker-window="closeInsertWindow()"
                  @close-widgets-option-window="closeOptionWindow()"
                  @update="
                    handleUpdateSelectedWidgets(
                      $event,
                      'local_layout.top.quick_info',
                    )
                  "
                  @update-active-widget="handleActiveWidgetUpdate"
                />
              </div>
            </div>

            <card-widget-placeholder
              id="body_title"
              containerClass="cptm-listing-card-preview-top-placeholder cptm-mb-12 cptm-align-left"
              :label="local_layout.body.title.label"
              :enable_widget="local_layout.body.title.enable_widget"
              :availableWidgets="theAvailableWidgets"
              :activeWidgets="active_widgets"
              :acceptedWidgets="local_layout.body.title.acceptedWidgets"
              :selectedWidgets="local_layout.body.title.selectedWidgets"
              :maxWidget="local_layout.body.title.maxWidget"
              :widgetOptionsWindow="widgetOptionsWindow"
              @edit-widget="editWidget($event)"
              @trash-widget="trashWidget($event, local_layout.body.title)"
              @toggle-widget-status="
                toggleWidgetStatus(local_layout.body.title)
              "
            />

            <card-widget-placeholder
              id="body_bottom"
              containerClass="cptm-listing-card-preview-body-placeholder"
              :label="local_layout.body.bottom.label"
              :availableWidgets="theAvailableWidgets"
              :activeWidgets="active_widgets"
              :acceptedWidgets="local_layout.body.bottom.acceptedWidgets"
              :selectedWidgets="local_layout.body.bottom.selectedWidgets"
              :maxWidget="local_layout.body.bottom.maxWidget"
              :showWidgetsPickerWindow="
                getActiveInsertWindowStatus('body_bottom')
              "
              :showWidgetsOptionWindow="
                getActiveOptionWindowStatus('body_bottom')
              "
              :widgetOptionsWindow="widgetOptionsWindow"
              @insert-widget="insertWidget($event, local_layout.body.bottom)"
              @edit-widget="editWidget($event)"
              @trash-widget="trashWidget($event, local_layout.body.bottom)"
              @open-widgets-picker-window="toggleInsertWindow('body_bottom')"
              @open-widgets-option-window="toggleOptionWindow('body_bottom')"
              @close-widgets-picker-window="closeInsertWindow()"
              @close-widgets-option-window="closeOptionWindow()"
              @update="
                handleUpdateSelectedWidgets($event, 'local_layout.body.bottom')
              "
              @update-active-widget="handleActiveWidgetUpdate"
            />
          </div>

          <!-- cptm-listing-card-preview-footer -->
          <div class="cptm-listing-card-preview-footer">
            <!-- cptm-listing-card-preview-footer-left-placeholder -->
            <div class="cptm-card-preview-footer-left">
              <card-widget-placeholder
                id="footer_left"
                containerClass="cptm-listing-card-preview-footer-left-placeholder"
                :label="local_layout.footer.left.label"
                :availableWidgets="theAvailableWidgets"
                :activeWidgets="active_widgets"
                :acceptedWidgets="local_layout.footer.left.acceptedWidgets"
                :selectedWidgets="local_layout.footer.left.selectedWidgets"
                :maxWidget="local_layout.footer.left.maxWidget"
                :showWidgetsPickerWindow="
                  getActiveInsertWindowStatus('footer_left')
                "
                :showWidgetsOptionWindow="
                  getActiveOptionWindowStatus('footer_left')
                "
                :widgetOptionsWindow="widgetOptionsWindow"
                @insert-widget="insertWidget($event, local_layout.footer.left)"
                @edit-widget="editWidget($event)"
                @trash-widget="trashWidget($event, local_layout.footer.left)"
                @open-widgets-picker-window="toggleInsertWindow('footer_left')"
                @open-widgets-option-window="toggleOptionWindow('footer_left')"
                @close-widgets-picker-window="closeInsertWindow()"
                @close-widgets-option-window="closeOptionWindow()"
                @update="
                  handleUpdateSelectedWidgets(
                    $event,
                    'local_layout.footer.left',
                  )
                "
                @update-active-widget="handleActiveWidgetUpdate"
              />
            </div>

            <!-- cptm-listing-card-preview-footer-right-placeholder -->
            <div class="cptm-card-preview-footer-right">
              <card-widget-placeholder
                id="footer_right"
                containerClass="cptm-listing-card-preview-footer-right-placeholder"
                :label="local_layout.footer.right.label"
                :availableWidgets="theAvailableWidgets"
                :activeWidgets="active_widgets"
                :acceptedWidgets="local_layout.footer.right.acceptedWidgets"
                :selectedWidgets="local_layout.footer.right.selectedWidgets"
                :maxWidget="local_layout.footer.right.maxWidget"
                :showWidgetsPickerWindow="
                  getActiveInsertWindowStatus('footer_right')
                "
                :showWidgetsOptionWindow="
                  getActiveOptionWindowStatus('footer_right')
                "
                :widgetOptionsWindow="widgetOptionsWindow"
                @insert-widget="insertWidget($event, local_layout.footer.right)"
                @edit-widget="editWidget($event)"
                @trash-widget="trashWidget($event, local_layout.footer.right)"
                @open-widgets-picker-window="toggleInsertWindow('footer_right')"
                @open-widgets-option-window="toggleOptionWindow('footer_right')"
                @close-widgets-picker-window="closeInsertWindow()"
                @close-widgets-option-window="closeOptionWindow()"
                @update="
                  handleUpdateSelectedWidgets(
                    $event,
                    'local_layout.footer.right',
                  )
                "
                @update-active-widget="handleActiveWidgetUpdate"
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
  name: "card-builder-list-view-field",
  mixins: [card_builder, helpers],
  props: {
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
      if (!this.widgetOptionsWindow.widget.length) {
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
        thumbnail: {
          top_right: {
            label: "Bottom Left",
            selectedWidgets: [],
          },
        },

        top: {
          quick_actions: {
            label: "Quick Actions",
            selectedWidgets: [],
          },
          quick_info: {
            label: "Quick Info",
            selectedWidgets: [],
          },
        },

        body: {
          title: {
            label: "Title",
            selectedWidgets: [],
          },
          tagline: {
            label: "Tagline",
            selectedWidgets: [],
          },
          rating: {
            label: "Add Elements",
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
        let widget_options =
          !active_widgets_data[widget_key].options &&
          typeof active_widgets_data[widget_key].options !== "object"
            ? false
            : active_widgets_data[widget_key].options;

        for (let root_option in widgets_template) {
          if ("options" === root_option) {
            continue;
          }
          if (active_widgets_data[widget_key][root_option] === "undefined") {
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
        let length =
          this.local_layout[item.section][item.area].selectedWidgets.length;
        this.local_layout[item.section][item.area].selectedWidgets.splice(
          length,
          0,
          item.widget,
        );
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
      if (
        typeof this.active_widgets[key] === "undefined" ||
        this.widgetOptionsWindowActiveStatus
      ) {
        return;
      }

      if (
        !this.active_widgets[key].options &&
        typeof this.active_widgets[key].options !== "object"
      ) {
        return;
      }

      this.widgetOptionsWindow = this.widgetOptionsWindowDefault;
    },

    // Update Widget Options Data
    updateWidgetOptionsData(data, widget) {
      return;
    },

    // Close Widget Options Window
    closeWidgetOptionsWindow() {
      this.widgetOptionsWindow = this.widgetOptionsWindowDefault;
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
  },
};
</script>
