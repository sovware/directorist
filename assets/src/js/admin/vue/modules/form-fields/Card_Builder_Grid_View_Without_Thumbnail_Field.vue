<template>
  <div class="cptm-builder-section">
    <!-- cptm-preview-area -->
    <div class="cptm-preview-area">
      <div class="cptm-card-preview-widget grid-view-without-thumbnail">
        <div class="cptm-card-preview-widget-content">
          <!-- cptm-listing-card-preview-body -->
          <div class="cptm-listing-card-preview-body">
            <div class="cptm-card-placeholder-top">
              <!-- cptm-placeholder-left-col -->
              <div class="cptm-card-placeholder-top-left">
                <card-widget-placeholder
                  id="top_quick_actions"
                  containerClass="cptm-listing-card-quick-actions-placeholder cptm-card-light cptm-mb-20"
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
                    activeInsertWindow('top_quick_actions')
                  "
                  @open-widgets-option-window="
                    activeOptionWindow('top_quick_actions')
                  "
                  @close-widgets-picker-window="closeInsertWindow()"
                  @close-widgets-option-window="closeOptionWindow()"
                  @update="
                    handleUpdateSelectedWidgets(
                      $event,
                      'local_layout.top.quick_actions'
                    )
                  "
                  @update-active-widget="handleActiveWidgetUpdate"
                  editOnClick
                />
              </div>

              <!-- cptm-placeholder-right-col -->
              <div class="cptm-card-placeholder-top-right">
                <card-widget-placeholder
                  id="top_quick_info"
                  containerClass="cptm-listing-card-quick-info-placeholder cptm-card-light cptm-mb-20 cptm-text-right"
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
                    activeInsertWindow('top_quick_info')
                  "
                  @open-widgets-option-window="
                    activeOptionWindow('top_quick_info')
                  "
                  @close-widgets-picker-window="closeInsertWindow()"
                  @close-widgets-option-window="closeOptionWindow()"
                  @update="
                    handleUpdateSelectedWidgets(
                      $event,
                      'local_layout.top.quick_info'
                    )
                  "
                  @update-active-widget="handleActiveWidgetUpdate"
                  editOnClick
                />
              </div>
            </div>

            <card-widget-placeholder
              id="body_avatar"
              :containerClass="getAvatarPlaceholderClass"
              :label="local_layout.body.avatar.label"
              :enable_widget="local_layout.body.avatar.enable_widget"
              :availableWidgets="theAvailableWidgets"
              :activeWidgets="active_widgets"
              :acceptedWidgets="local_layout.body.avatar.acceptedWidgets"
              :selectedWidgets="local_layout.body.avatar.selectedWidgets"
              :maxWidget="local_layout.body.avatar.maxWidget"
              :showWidgetsPickerWindow="
                getActiveInsertWindowStatus('body_avatar')
              "
              :widgetOptionsWindow="widgetOptionsWindow"
              @insert-widget="insertWidget($event, local_layout.body.avatar)"
              @edit-widget="editWidget($event)"
              @trash-widget="trashWidget($event, local_layout.body.avatar)"
              @open-widgets-picker-window="activeInsertWindow('body_avatar')"
              @open-widgets-option-window="activeOptionWindow('body_avatar')"
              @close-widgets-picker-window="closeInsertWindow()"
              @close-widgets-option-window="closeOptionWindow()"
              @toggle-widget-status="
                toggleWidgetStatus(local_layout.body.avatar)
              "
              @update-option-window="
                updateWidgetOptionsData($event, widgetOptionsWindow)
              "
              @close-option-window="closeWidgetOptionsWindow()"
              editOnClick
            />

            <card-widget-placeholder
              id="body_title"
              containerClass="cptm-listing-card-preview-top-placeholder cptm-card-light cptm-mb-20 cptm-align-left"
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
              editOnClick
            />

            <card-widget-placeholder
              id="body_tagline"
              containerClass="cptm-listing-card-preview-tagline-placeholder cptm-card-light cptm-mb-20 cptm-align-left"
              :label="local_layout.body.tagline.label"
              :enable_widget="local_layout.body.tagline.enable_widget"
              :availableWidgets="theAvailableWidgets"
              :activeWidgets="active_widgets"
              :acceptedWidgets="local_layout.body.tagline.acceptedWidgets"
              :selectedWidgets="local_layout.body.tagline.selectedWidgets"
              :maxWidget="local_layout.body.tagline.maxWidget"
              :widgetOptionsWindow="widgetOptionsWindow"
              :hasDisableButton="true"
              @edit-widget="editWidget($event)"
              @trash-widget="trashWidget($event, local_layout.body.tagline)"
              @toggle-widget-status="
                toggleWidgetStatus(local_layout.body.tagline)
              "
              editOnClick
            />

            <card-widget-placeholder
              id="thumbnail_body_badge"
              containerClass="cptm-listing-card-preview-badges-placeholder cptm-card-light cptm-mb-20 cptm-align-left"
              :label="local_layout.body.badges.label"
              :availableWidgets="theAvailableWidgets"
              :activeWidgets="active_widgets"
              :acceptedWidgets="local_layout.body.badges.acceptedWidgets"
              :selectedWidgets="local_layout.body.badges.selectedWidgets"
              :maxWidget="local_layout.body.badges.maxWidget"
              :showWidgetsPickerWindow="
                getActiveInsertWindowStatus('thumbnail_body_badge')
              "
              :showWidgetsOptionWindow="
                getActiveOptionWindowStatus('thumbnail_body_badge')
              "
              :widgetOptionsWindow="widgetOptionsWindow"
              @insert-widget="insertWidget($event, local_layout.body.badges)"
              @edit-widget="editWidget($event)"
              @trash-widget="trashWidget($event, local_layout.body.badges)"
              @open-widgets-picker-window="
                activeInsertWindow('thumbnail_body_badge')
              "
              @open-widgets-option-window="
                activeOptionWindow('thumbnail_body_badge')
              "
              @close-widgets-picker-window="closeInsertWindow()"
              @close-widgets-option-window="closeOptionWindow()"
              @update="
                handleUpdateSelectedWidgets($event, 'local_layout.body.badges')
              "
              @update-active-widget="handleActiveWidgetUpdate"
              editOnClick
            />

            <card-widget-placeholder
              id="thumbnail_body_bottom"
              containerClass="cptm-listing-card-preview-body-placeholder cptm-card-light"
              :label="local_layout.body.bottom.label"
              :availableWidgets="theAvailableWidgets"
              :activeWidgets="active_widgets"
              :acceptedWidgets="local_layout.body.bottom.acceptedWidgets"
              :selectedWidgets="local_layout.body.bottom.selectedWidgets"
              :maxWidget="local_layout.body.bottom.maxWidget"
              :showWidgetsPickerWindow="
                getActiveInsertWindowStatus('thumbnail_body_bottom')
              "
              :showWidgetsOptionWindow="
                getActiveOptionWindowStatus('thumbnail_body_bottom')
              "
              :widgetOptionsWindow="widgetOptionsWindow"
              @insert-widget="insertWidget($event, local_layout.body.bottom)"
              @edit-widget="editWidget($event)"
              @trash-widget="trashWidget($event, local_layout.body.bottom)"
              @open-widgets-picker-window="
                activeInsertWindow('thumbnail_body_bottom')
              "
              @open-widgets-option-window="
                activeOptionWindow('thumbnail_body_bottom')
              "
              @close-widgets-picker-window="closeInsertWindow()"
              @close-widgets-option-window="closeOptionWindow()"
              @update="
                handleUpdateSelectedWidgets($event, 'local_layout.body.bottom')
              "
              @update-active-widget="handleActiveWidgetUpdate"
              editOnClick
            />
          </div>

          <!-- cptm-listing-card-preview-footer -->
          <div class="cptm-listing-card-preview-footer">
            <!-- cptm-listing-card-preview-footer-left-placeholder -->
            <div class="cptm-card-preview-footer-left">
              <card-widget-placeholder
                id="thumbnail_footer_left"
                containerClass="cptm-listing-card-preview-footer-left-placeholder cptm-card-light"
                :label="local_layout.footer.left.label"
                :availableWidgets="theAvailableWidgets"
                :activeWidgets="active_widgets"
                :acceptedWidgets="local_layout.footer.left.acceptedWidgets"
                :selectedWidgets="local_layout.footer.left.selectedWidgets"
                :maxWidget="local_layout.footer.left.maxWidget"
                :showWidgetsPickerWindow="
                  getActiveInsertWindowStatus('thumbnail_footer_left')
                "
                :showWidgetsOptionWindow="
                  getActiveOptionWindowStatus('thumbnail_footer_left')
                "
                :widgetOptionsWindow="widgetOptionsWindow"
                @insert-widget="insertWidget($event, local_layout.footer.left)"
                @edit-widget="editWidget($event)"
                @trash-widget="trashWidget($event, local_layout.footer.left)"
                @open-widgets-picker-window="
                  activeInsertWindow('thumbnail_footer_left')
                "
                @open-widgets-option-window="
                  activeOptionWindow('thumbnail_footer_left')
                "
                @close-widgets-picker-window="closeInsertWindow()"
                @close-widgets-option-window="closeOptionWindow()"
                @update="
                  handleUpdateSelectedWidgets(
                    $event,
                    'local_layout.footer.left'
                  )
                "
                @update-active-widget="handleActiveWidgetUpdate"
                editOnClick
              />
            </div>

            <!-- cptm-listing-card-preview-footer-right-placeholder -->
            <div class="cptm-card-preview-footer-right">
              <card-widget-placeholder
                id="thumbnail_footer_right"
                containerClass="cptm-listing-card-preview-footer-right-placeholder cptm-card-light"
                :label="local_layout.footer.right.label"
                :availableWidgets="theAvailableWidgets"
                :activeWidgets="active_widgets"
                :acceptedWidgets="local_layout.footer.right.acceptedWidgets"
                :selectedWidgets="local_layout.footer.right.selectedWidgets"
                :maxWidget="local_layout.footer.right.maxWidget"
                :showWidgetsPickerWindow="
                  getActiveInsertWindowStatus('thumbnail_footer_right')
                "
                :showWidgetsOptionWindow="
                  getActiveOptionWindowStatus('thumbnail_footer_right')
                "
                :widgetOptionsWindow="widgetOptionsWindow"
                @insert-widget="insertWidget($event, local_layout.footer.right)"
                @edit-widget="editWidget($event)"
                @trash-widget="trashWidget($event, local_layout.footer.right)"
                @open-widgets-picker-window="
                  activeInsertWindow('thumbnail_footer_right')
                "
                @open-widgets-option-window="
                  activeOptionWindow('thumbnail_footer_right')
                "
                @close-widgets-picker-window="closeInsertWindow()"
                @close-widgets-option-window="closeOptionWindow()"
                @update="
                  handleUpdateSelectedWidgets(
                    $event,
                    'local_layout.footer.right'
                  )
                "
                @update-active-widget="handleActiveWidgetUpdate"
                editOnClick
              />
            </div>
          </div>
        </div>
      </div>

      <div class="cptm-preview-notice">
        <div class="cptm-preview-notice-content">
          <svg
            width="16"
            height="16"
            viewBox="0 0 16 16"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <g clip-path="url(#clip0_8301_5081)">
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M7.99984 1.99984C4.68613 1.99984 1.99984 4.68613 1.99984 7.99984C1.99984 11.3135 4.68613 13.9998 7.99984 13.9998C11.3135 13.9998 13.9998 11.3135 13.9998 7.99984C13.9998 4.68613 11.3135 1.99984 7.99984 1.99984ZM0.666504 7.99984C0.666504 3.94975 3.94975 0.666504 7.99984 0.666504C12.0499 0.666504 15.3332 3.94975 15.3332 7.99984C15.3332 12.0499 12.0499 15.3332 7.99984 15.3332C3.94975 15.3332 0.666504 12.0499 0.666504 7.99984ZM7.33317 5.33317C7.33317 4.96498 7.63165 4.6665 7.99984 4.6665H8.0065C8.37469 4.6665 8.67317 4.96498 8.67317 5.33317C8.67317 5.70136 8.37469 5.99984 8.0065 5.99984H7.99984C7.63165 5.99984 7.33317 5.70136 7.33317 5.33317ZM7.99984 7.33317C8.36803 7.33317 8.6665 7.63165 8.6665 7.99984V10.6665C8.6665 11.0347 8.36803 11.3332 7.99984 11.3332C7.63165 11.3332 7.33317 11.0347 7.33317 10.6665V7.99984C7.33317 7.63165 7.63165 7.33317 7.99984 7.33317Z"
                fill="#3E62F5"
              />
            </g>
            <defs>
              <clipPath id="clip0_8301_5081">
                <rect width="16" height="16" fill="white" />
              </clipPath>
            </defs>
          </svg>
          <p class="cptm-preview-notice-text">
            Want to enable/disable <strong>Grid</strong>,
            <strong>List</strong> or <strong>Map</strong> views for the All
            Listings Page?
          </p>
        </div>
        <div class="cptm-preview-notice-action">
          <a
            href="/wp-admin/edit.php?post_type=at_biz_dir&page=atbdp-settings"
            target="_blank"
            class="cptm-preview-notice-btn"
          >
            Go to settings
            <svg
              width="14"
              height="14"
              viewBox="0 0 14 14"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M6.48424 1.38007C6.769 1.09531 7.23068 1.09531 7.51544 1.38007L12.6196 6.48424C12.9044 6.769 12.9044 7.23068 12.6196 7.51544L7.51544 12.6196C7.23068 12.9044 6.769 12.9044 6.48424 12.6196C6.19948 12.3348 6.19948 11.8732 6.48424 11.5884L10.3436 7.729H1.89567C1.49296 7.729 1.1665 7.40254 1.1665 6.99984C1.1665 6.59713 1.49296 6.27067 1.89567 6.27067H10.3436L6.48424 2.41127C6.19948 2.12651 6.19948 1.66483 6.48424 1.38007Z"
                fill="#4D5761"
              />
            </svg>
          </a>
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

              widget_data[root_option] = this.active_widgets[widget_name][
                root_option
              ];
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

            let widget_options = this.active_widgets[widget_name].options
              .fields;

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
        "cptm-listing-card-author-avatar-placeholder cptm-card-light cptm-mb-20": true,
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

      // Available Widgets
      available_widgets: {},

      // Active Widgets
      active_widgets: {},

      // Layout
      local_layout: {
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
          avatar: {
            label: "Avatar",
            selectedWidgets: [],
          },
          title: {
            label: "Title",
            selectedWidgets: [],
          },
          tagline: {
            label: "Tagline",
            selectedWidgets: [],
          },
          badges: {
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
      }

      // Load Selected Widgets Data
      for (let item of selectedWidgets) {
        let length = this.local_layout[item.section][item.area].selectedWidgets
          .length;
        this.local_layout[item.section][item.area].selectedWidgets.splice(
          length,
          0,
          item.widget
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
            this.layout[section][area]
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

      let opt = this.active_widgets[key].options;
      this.widgetOptionsWindow = this.widgetOptionsWindowDefault;

      const self = this;

      setTimeout(() => {
        self.widgetOptionsWindow = {
          ...self.widgetOptionsWindowDefault,
          ...opt,
        };
        self.widgetOptionsWindow.widget = key;
      }, 0);
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
            layout
          );
        });
      }
    },

    // Active Insert Window
    activeInsertWindow(current_item_key) {
      if (this.active_insert_widget_key === current_item_key) {
        this.active_insert_widget_key = "";
        return;
      }

      this.active_insert_widget_key = current_item_key;
    },

    // Active Option Window
    activeOptionWindow(current_item_key) {
      if (this.active_option_widget_key === current_item_key) {
        this.active_option_widget_key = "";
        return;
      }

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
    closeInsertWindow(widget_insert_window) {
      this.active_insert_widget_key = "";
    },

    // Close Option Window
    closeOptionWindow(widget_option_window) {
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
