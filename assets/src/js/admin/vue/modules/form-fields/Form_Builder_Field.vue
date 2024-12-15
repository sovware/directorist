<template>
  <div class="cptm-form-builder cptm-row">
    <div class="cptm-col-5 cptm-col-sticky">
      <template v-for="(widget_group, widget_group_key) in widgets">
        <form-builder-widget-list-section-component
          :field-id="fieldId"
          v-bind="widget_group"
          :widget-group="widget_group_key"
          :selected-widgets="active_widget_fields"
          :active-widget-groups="active_widget_groups"
          @update-widget-list="updateWidgetList"
          @drag-start="handleWidgetListItemDragStart(widget_group_key, $event)"
          @drag-end="handleWidgetListItemDragEnd(widget_group_key, $event)"
        />
      </template>
    </div>
    <div class="cptm-col-7">
      <div class="cptm-form-builder-active-fields">
        <div class="cptm-form-builder-active-fields-container cptm-col-sticky">
          <draggable-list-item-wrapper
            list-id="widget-group"
            :is-dragging-self="
              currentDraggingGroup &&
              widget_group_key === currentDraggingGroup.widget_group_key
            "
            :droppable="currentDraggingGroup"
            v-for="(widget_group, widget_group_key) in active_widget_groups"
            :key="widget_group_key"
            @drop="handleGroupDrop(widget_group_key, $event)"
          >
            <form-builder-widget-group-component
              :group-key="widget_group_key"
              :field-id="fieldId"
              :active-widgets="active_widget_fields"
              :avilable-widgets="avilable_widgets"
              :group-data="widget_group"
              :group-settings="groupSettingsProp"
              :group-fields="groupFields"
              :widget-is-dragging="widgetIsDragging"
              :current-dragging-group="currentDraggingGroup"
              :current-dragging-widget="currentDraggingWidget"
              :is-enabled-group-dragging="isEnabledGroupDragging"
              @update-group-field="updateGroupField(widget_group_key, $event)"
              @update-widget-field="updateWidgetField"
              @trash-widget="trashWidget(widget_group_key, $event)"
              @trash-group="trashGroup(widget_group_key)"
              @widget-drag-start="
                handleWidgetDragStart(widget_group_key, $event)
              "
              @widget-drag-end="handleWidgetDragEnd()"
              @drop-widget="handleWidgetDrop(widget_group_key, $event)"
              @group-drag-start="handleGroupDragStart(widget_group_key)"
              @group-drag-end="handleGroupDragEnd()"
              @append-widget="handleAppendWidget(widget_group_key)"
            />
          </draggable-list-item-wrapper>

          <div
            class="cptm-form-builder-active-fields-footer"
            v-if="showAddNewGroupButton"
          >
            <button
              type="button"
              class="cptm-btn cptm-btn-secondery"
              @click="addNewGroup()"
              v-html="addNewGroupButtonLabel"
            ></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { findObjectItem, isObject } from "../../../../helper";
import helpers from "../../mixins/helpers";

export default {
  name: "form-builder",
  mixins: [helpers],
  props: {
    fieldId: {
      type: [String, Number],
      required: false,
      default: "",
    },
    widgets: {
      default: false,
    },
    generalSettings: {
      default: false,
    },
    groupSettings: {
      default: false,
    },
    groupFields: {
      default: false,
    },
    value: {
      default: "",
    },
  },

  created() {
    this.setupActiveWidgetFields();

    if (this.$root.fields) {
      this.$store.commit("updateFields", this.$root.fields);
    }

    if (this.$root.layouts) {
      this.$store.commit("updatelayouts", this.$root.layouts);
    }

    if (this.$root.options) {
      this.$store.commit("updateOptions", this.$root.options);
    }

    if (this.$root.config) {
      this.$store.commit("updateConfig", this.$root.config);
    }
  },

  mounted() {
    this.setupActiveWidgetGroups();
  },

  watch: {
    finalValue() {
      this.$emit("update", this.finalValue);
    },
  },

  computed: {
    finalValue() {
      return {
        fields: this.active_widget_fields,
        groups: this.active_widget_groups,
      };
    },

    widgetIsDragging() {
      return this.currentDraggingWidget ? true : false;
    },

    groupSettingsProp() {
      if (!this.generalSettings) {
        return this.groupSettings;
      }
      if (typeof this.generalSettings.minGroup === "undefined") {
        return this.groupSettings;
      }

      if (this.active_widget_groups.length <= this.groupSettings.minGroup) {
        this.groupSettings.canTrash = false;
      }

      return this.groupSettings;
    },

    showGroupDragToggleButton() {
      let show_button = true;

      if (!this.active_widget_groups) {
        show_button = false;
      }

      if (
        this.groupSettings &&
        typeof this.groupSettings.draggable !== "undefined" &&
        !this.groupSettings.draggable
      ) {
        show_button = false;
      }

      return show_button;
    },

    showAddNewGroupButton() {
      let show_button = true;

      if (
        this.generalSettings &&
        typeof this.generalSettings.allowAddNewGroup !== "undefined" &&
        !this.generalSettings.allowAddNewGroup
      ) {
        show_button = false;
      }

      return show_button;
    },

    addNewGroupButtonLabel() {
      let button_label = "Add New";

      let button_icon = '<span aria-hidden="true" class="la la-plus"></span>';

      if (this.generalSettings && this.generalSettings.addNewGroupButtonLabel) {
        button_label = this.generalSettings.addNewGroupButtonLabel;
      }

      return button_icon + button_label;
    },

    buttonText() {
      return this.$store.state.is_saving
        ? "Saving"
        : 'Save & Preview <span class="la la-pen"></span>';
    },

    ...mapState({
      options: "options",
    }),
  },

  data() {
    return {
      local_value: {},

      active_widget_fields: {},
      active_widget_groups: [],
      avilable_widgets: {},
      isDataChanged: false,

      default_group: [
        {
          type: "general_group",
          label:
            this.groupSettings && this.groupSettings.defaultGroupLabel
              ? this.groupSettings.defaultGroupLabel
              : "Section",
          fields: [],
        },
      ],

      forceExpandStateTo: "", // expand | 'collapse'
      isEnabledGroupDragging: true,

      currentDraggingGroup: null,
      currentDraggingWidget: null,

      listing_type_id: null,
    };
  },

  methods: {
    setup() {
      this.setupActiveWidgetFields();
      this.setupActiveWidgetGroups();
    },

    // setupActiveWidgetFields
    setupActiveWidgetFields() {
      if (!this.value) {
        return;
      }

      this.active_widget_fields = this.sanitizeActiveWidgetFields(
        findObjectItem("fields", this.value, {})
      );

      this.$emit("updated-state");
      this.$emit("active-widgets-updated");
    },

    // sanitizeActiveWidgetFields
    sanitizeActiveWidgetFields(activeWidgetFields) {
      if (!isObject(activeWidgetFields)) {
        return {};
      }

      if (activeWidgetFields.hasOwnProperty("field_key")) {
        delete activeWidgetFields.field_key;
      }

      for (let widget_key in activeWidgetFields) {
        if (!isObject(activeWidgetFields[widget_key])) {
          delete activeWidgetFields[widget_key];
          continue;
        }

        activeWidgetFields[widget_key].widget_key = widget_key;
      }

      return activeWidgetFields;
    },

    // setupActiveWidgetGroups
    setupActiveWidgetGroups() {
      if (!this.value) return;
      if (typeof this.value !== "object") return;

      if (Array.isArray(this.value.groups)) {
        this.active_widget_groups = this.sanitizeActiveWidgetGroups(
          this.value.groups
        );
      }

      this.$emit("active-group-updated");
    },

    // sanitizeActiveWidgetGroups
    sanitizeActiveWidgetGroups(_active_widget_groups) {
      let active_widget_groups = _active_widget_groups;
      if (!Array.isArray(active_widget_groups)) {
        active_widget_groups = [];
      }

      let group_index = 0;
      for (let widget_group of active_widget_groups) {
        if (typeof widget_group.label === "undefined") {
          active_widget_groups[group_index].label = "";
        }

        if (
          typeof widget_group.fields === "undefined" ||
          !Array.isArray(widget_group.fields)
        ) {
          active_widget_groups[group_index].fields = [];
        }

        let field_index = 0;
        for (let field of widget_group.fields) {
          if (typeof this.active_widget_fields[field] === "undefined") {
            delete active_widget_groups[group_index].fields[field_index];
          }

          field_index++;
        }

        group_index++;
      }

      return active_widget_groups;
    },

    // updateWidgetList
    updateWidgetList(widget_list) {
      if (!widget_list) {
        return;
      }
      if (typeof widget_list !== "object") {
        return;
      }
      if (typeof widget_list.widget_group === "undefined") {
        return;
      }
      if (typeof widget_list.base_widget_list === "undefined") {
        return;
      }

      Vue.set(
        this.avilable_widgets,
        widget_list.widget_group,
        widget_list.base_widget_list
      );
    },

    updateGroupField(widget_group_key, payload) {
      Vue.set(
        this.active_widget_groups[widget_group_key],
        payload.key,
        payload.value
      );

      this.$emit("update", this.finalValue);
      this.$emit("updated-state");
      this.$emit("group-field-updated");
    },

    updateWidgetField(props) {
      this.isDataChanged = true;
      let activeWidget = this.active_widget_fields[props.widget_key];
      let updatedValue = props.payload.value;

      if (props.payload.key === "placeholder" && !props.payload.value) {
        if (!activeWidget.label) {
          updatedValue = directorist_admin.search_form_default_placeholder;
        }
      } else if (props.payload.key === "label" && !props.payload.value) {
        if (!activeWidget.placeholder) {
          updatedValue = directorist_admin.search_form_default_label;
        }
      }
        
      Vue.set(
        this.active_widget_fields[props.widget_key],
        props.payload.key,
        updatedValue
      );

      this.$emit("update", this.finalValue);
      this.$emit("updated-state");
      this.$emit("widget-field-updated");
    },

    // Widget Tasks
    handleAppendWidget(widget_group_key) {
      if (!this.currentDraggingWidget) {
        return;
      }

      let payload = {
        widget_index:
          this.active_widget_groups[widget_group_key].fields.length - 1,
      };
      this.handleWidgetDrop(widget_group_key, payload);
    },

    handleWidgetDragStart(widget_group_key, payload) {
      this.currentDraggingWidget = {
        from: "active_widgets",
        widget_group_key,
        widget_index: payload.widget_index,
        widget_key: payload.widget_key,
      };
    },

    handleWidgetDragEnd() {
      this.currentDraggingWidget = null;
    },

    isAcceptedSectionWidget(widgetKey, destinationSection) {
      const widgetPath = `${destinationSection.widget_group}.widgets.${destinationSection.widget_name}`;
      const widget = findObjectItem(widgetPath, this.widgets, {});

      if (!widget.hasOwnProperty("accepted_widgets")) {
        return true;
      }

      if (!Array.isArray(widget.accepted_widgets)) {
        return true;
      }

      if (!widget.accepted_widgets.length) {
        return true;
      }

      const droppedWidget = this.active_widget_fields[widgetKey];

      let hasMissMatchWidget = false;

      for (const acceptedWidget of widget.accepted_widgets) {
        for (const acceptedWidgetKey of Object.keys(acceptedWidget)) {
          if (
            droppedWidget[acceptedWidgetKey] !==
            acceptedWidget[acceptedWidgetKey]
          ) {
            hasMissMatchWidget = true;
            break;
          }
        }
      }

      if (hasMissMatchWidget) {
        return false;
      }
    },

    handleWidgetDrop(widget_group_key, payload) {
      const dropped_in = {
        widget_group_key,
        widget_key: payload.widget_key,
        widget_index: payload.widget_index,
        drop_direction: payload.drop_direction,
      };

      const activeGroup = this.active_widget_groups[widget_group_key];

      if (
        "section" === activeGroup.type &&
        !this.isAcceptedSectionWidget(payload.widget_key, activeGroup)
      ) {
        return false;
      }

      // handleWidgetReorderFromActiveWidgets
      if ("active_widgets" === this.currentDraggingWidget.from) {
        this.handleWidgetReorderFromActiveWidgets(
          this.currentDraggingWidget,
          dropped_in
        );
        this.currentDraggingWidget = null;
        return;
      }

      // handleWidgetInsertFromAvailableWidgets
      if ("available_widgets" === this.currentDraggingWidget.from) {
        this.handleWidgetInsertFromAvailableWidgets(
          this.currentDraggingWidget,
          dropped_in
        );
        this.currentDraggingWidget = null;
      }
    },

    handleWidgetReorderFromActiveWidgets(from, to) {
      let from_fields = this.active_widget_groups[from.widget_group_key].fields;
      let to_fields = this.active_widget_groups[to.widget_group_key].fields;

      // If Reordering in same group
      if (from.widget_group_key === to.widget_group_key) {
        let origin_data = from_fields[from.widget_index];
        let dest_index =
          from.widget_index < to.widget_index
            ? to.widget_index - 1
            : to.widget_index;

        dest_index =
          "after" === to.drop_direction ? dest_index + 1 : dest_index;

        this.active_widget_groups[from.widget_group_key].fields.splice(
          from.widget_index,
          1
        );
        this.active_widget_groups[to.widget_group_key].fields.splice(
          dest_index,
          0,
          origin_data
        );

        return;
      }

      // If Reordering to diffrent group
      let origin_data = from_fields[from.widget_index];
      let dest_index =
        "before" === to.drop_direction ? to.widget_index - 1 : to.widget_index;
      dest_index =
        "after" === to.drop_direction ? to.widget_index + 1 : to.widget_index;
      dest_index = dest_index < 0 ? 0 : dest_index;
      dest_index =
        dest_index >= to_fields.length ? to_fields.length : dest_index;

      this.active_widget_groups[from.widget_group_key].fields.splice(
        from.widget_index,
        1
      );
      this.active_widget_groups[to.widget_group_key].fields.splice(
        dest_index,
        0,
        origin_data
      );

      this.$emit("updated-state");
      this.$emit("active-widgets-updated");
    },

    handleWidgetInsertFromAvailableWidgets(from, to) {
      const field_data_options = this.getOptionDataFromWidget(from.widget);

      field_data_options.widget_key = this.genarateWidgetKeyForActiveWidgets(
        from.widget_key
      );

      if (field_data_options.field_key) {
        field_data_options.field_key = this.genarateFieldKeyForActiveWidgets(
          field_data_options
        );
      }

      if (!isObject(this.active_widget_fields)) {
        this.active_widget_fields = {};
      }

      Vue.set(
        this.active_widget_fields,
        field_data_options.widget_key,
        field_data_options
      );

      let to_fields = this.active_widget_groups[to.widget_group_key].fields;
      let dest_index =
        "before" === to.drop_direction ? to.widget_index - 1 : to.widget_index;

      dest_index =
        "after" === to.drop_direction ? to.widget_index + 1 : to.widget_index;
      dest_index = dest_index < 0 ? 0 : dest_index;
      dest_index =
        dest_index >= to_fields.length ? to_fields.length : dest_index;

      this.active_widget_groups[to.widget_group_key].fields.splice(
        dest_index,
        0,
        field_data_options.widget_key
      );

      this.$emit("updated-state");
      this.$emit("active-widgets-updated");
    },

    handleWidgetListItemDragStart(widget_group_key, payload) {
      if (
        payload.widget &&
        typeof payload.widget.type !== "undefined" &&
        "section" === payload.widget.type
      ) {
        this.currentDraggingGroup = {
          from: "available_widgets",
          widget_group_key,
          widget_key: payload.widget_key,
          widget: payload.widget,
        };

        this.forceExpandStateTo = "collapse";
        this.isEnabledGroupDragging = true;

        return;
      }

      this.currentDraggingWidget = {
        from: "available_widgets",
        widget_group_key,
        widget_key: payload.widget_key,
        widget: payload.widget,
      };
    },

    handleWidgetListItemDragEnd() {
      this.currentDraggingWidget = null;
      this.currentDraggingGroup = null;
    },

    trashWidget(widget_group_key, payload) {
      let index = this.active_widget_groups[widget_group_key].fields.indexOf(
        payload.widget_key
      );

      this.active_widget_groups[widget_group_key].fields.splice(index, 1);

      Vue.delete(this.active_widget_fields, payload.widget_key);

      this.$emit("updated-state");
      this.$emit("widget-field-trashed");
      this.$emit("active-widgets-updated");
    },

    getOptionDataFromWidget(widget) {
      const widgetOptions = findObjectItem("options", widget);

      if (!isObject(widgetOptions)) {
        return {};
      }

      const fieldDataOptions = {};

      for (let option_key in widgetOptions) {
        fieldDataOptions[option_key] =
          typeof widgetOptions[option_key].value !== "undefined"
            ? widgetOptions[option_key].value
            : "";
      }

      return fieldDataOptions;
    },

    genarateWidgetKeyForActiveWidgets(widget_key) {
      if (typeof this.active_widget_fields[widget_key] !== "undefined") {
        let matched_keys = Object.keys(this.active_widget_fields);

        const getUniqueKey = function (current_key, new_key) {
          if (matched_keys.includes(new_key)) {
            let field_id = new_key.match(/[_](\d+)$/);
            field_id = field_id ? parseInt(field_id[1]) : 1;

            const new_field_key = current_key + "_" + (field_id + 1);

            return getUniqueKey(current_key, new_field_key);
          }

          return new_key;
        };

        return getUniqueKey(widget_key, widget_key);
      }

      return widget_key;
    },

    genarateFieldKeyForActiveWidgets(field_data_options) {
      if (!field_data_options.field_key) {
        return "";
      }
      const current_field_key = field_data_options.field_key;

      let field_keys = [];

      for (let key in this.active_widget_fields) {
        if (!this.active_widget_fields[key].field_key) {
          continue;
        }

        field_keys.push(this.active_widget_fields[key].field_key);
      }

      const getUniqueKey = function (field_key) {
        if (field_keys.includes(field_key)) {
          let field_id = field_key.match(/[-](\d+)$/);
          field_id = field_id ? parseInt(field_id[1]) : 1;

          const new_field_key = current_field_key + "-" + (field_id + 1);

          return getUniqueKey(new_field_key);
        }

        return field_key;
      };

      const unique_field_key = getUniqueKey(current_field_key);

      return unique_field_key;
    },

    handleGroupDragStart(widget_group_key) {
      this.currentDraggingGroup = {
        from: "active_widgets",
        widget_group_key,
      };
      this.isEnabledGroupDragging = false;
    },

    handleGroupDragEnd() {
      this.currentDraggingGroup = null;
      this.isEnabledGroupDragging = true;
    },

    handleGroupDrop(widget_group_key, payload) {
      let dropped_in = {
        widget_group_key,
        drop_direction: payload.drop_direction,
      };

      if ("active_widgets" === this.currentDraggingGroup.from) {
        this.handleGroupReorderFromActiveWidgets(
          this.currentDraggingGroup,
          dropped_in
        );
      }

      if ("available_widgets" === this.currentDraggingGroup.from) {
        this.handleGroupInsertFromAvailableWidgets(
          this.currentDraggingGroup,
          dropped_in
        );
      }

      this.currentDraggingGroup = null;
    },

    addNewGroup() {
      let group = JSON.parse(JSON.stringify(this.default_group[0]));

      if (this.groupSettings) {
        Object.assign(group, this.groupSettings);
      }

      if (this.groupFields && this.groupFields.section_id) {
        group.section_id = this.getUniqueSectionID();
      }

      let dest_index = this.active_widget_groups.length;
      this.active_widget_groups.splice(dest_index, 0, group);

      this.$emit("updated-state");
    },

    getUniqueSectionID() {
      let existing_ids = [];

      if (!Array.isArray(this.active_widget_groups)) {
        return 1;
      }

      for (let group of this.active_widget_groups) {
        if (typeof group.section_id !== undefined && !isNaN(group.section_id)) {
          existing_ids.push(parseInt(group.section_id));
        }
      }

      if (existing_ids.length) {
        return Math.max(...existing_ids) + 1;
      }

      return 1;
    },

    handleGroupReorderFromActiveWidgets(from, to) {
      let origin_data = this.active_widget_groups[from.widget_group_key];

      let dest_index =
        from.widget_group_key < to.widget_group_key
          ? to.widget_group_key - 1
          : to.widget_group_key;
      dest_index = "after" === to.drop_direction ? dest_index + 1 : dest_index;

      this.active_widget_groups.splice(from.widget_group_key, 1);
      this.active_widget_groups.splice(dest_index, 0, origin_data);

      this.$emit("updated-state");
      this.$emit("group-reordered");
    },

    handleGroupInsertFromAvailableWidgets(from, to) {
      let group = JSON.parse(JSON.stringify(this.default_group[0]));

      if (this.groupSettings) {
        Object.assign(group, this.groupSettings);
      }

      if (this.groupFields && this.groupFields.section_id) {
        group.section_id = this.getUniqueSectionID();
      }

      let widget = from.widget;
      let option_data = this.getOptionDataFromWidget(widget);

      group.fields = this.insertWidgetFromAvailableSectionWidgets(
        widget.widgets
      );

      delete widget.options;
      delete widget.widgets;

      Object.assign(group, widget);
      Object.assign(group, option_data);

      let dest_index =
        "before" === to.drop_direction
          ? to.widget_group_key - 1
          : to.widget_group_key;
      dest_index =
        "after" === to.drop_direction
          ? to.widget_group_key + 1
          : to.widget_group_key;
      dest_index = dest_index < 0 ? 0 : dest_index;
      dest_index =
        dest_index >= this.active_widget_groups.length
          ? this.active_widget_groups.length
          : dest_index;

      this.active_widget_groups.splice(dest_index, 0, group);

      this.$emit("updated-state");
      this.$emit("active-widgets-updated");
    },

    insertWidgetFromAvailableSectionWidgets(widgets) {
      if (!isObject(widgets)) {
        return [];
      }

      const insertWidgetAndGetKey = (widget_key, widget) => {
        const field_data_options = this.getOptionDataFromWidget(widget);

        field_data_options.widget_key = this.genarateWidgetKeyForActiveWidgets(
          widget_key
        );

        if (field_data_options.field_key) {
          field_data_options.field_key = this.genarateFieldKeyForActiveWidgets(
            field_data_options
          );
        }

        if (!isObject(this.active_widget_fields)) {
          this.active_widget_fields = {};
        }

        Vue.set(
          this.active_widget_fields,
          field_data_options.widget_key,
          field_data_options
        );

        return field_data_options.widget_key;
      };

      return Object.keys(widgets).map((widgetKey) =>
        insertWidgetAndGetKey(widgetKey, widgets[widgetKey])
      );
    },

    trashGroup(widget_group_key) {
      let group_fields = this.active_widget_groups[widget_group_key].fields;

      if (group_fields.length) {
        for (let widget_key of group_fields) {
          Vue.delete(this.active_widget_fields, widget_key);
        }
      }

      Vue.delete(this.active_widget_groups, widget_group_key);

      this.$emit("updated-state");
      this.$emit("group-updated");
      this.$emit("group-trashed");
      this.$emit("active-widgets-updated");
    },

    // Other Tasks
    toggleEnableWidgetGroupDragging() {
      this.forceExpandStateTo = !this.forceExpandStateTo ? "collapse" : ""; // expand | 'collapse'
      this.isEnabledGroupDragging = !this.isEnabledGroupDragging;
    },

    ...mapGetters(["getFieldsValue"]),

    updateSubmitButtonLabel(payload) {
      if (!payload.field) {
        return;
      }
      if (typeof payload.value === "undefined") {
        return;
      }

      this.$store.commit("updateSubmitButtonLabel", payload);
    },

    maybeJSON(data) {
      let value = typeof data === "undefined" ? "" : data;

      if (
        ("object" === typeof value && Object.keys(value)) ||
        Array.isArray(value)
      ) {
        let json_encoded_value = JSON.stringify(value);
        let base64_encoded_value = this.encodeUnicodedToBase64(
          json_encoded_value
        );
        value = base64_encoded_value;
      }

      return value;
    },

    encodeUnicodedToBase64(str) {
      // first we use encodeURIComponent to get percent-encoded UTF-8,
      // then we convert the percent encodings into raw bytes which
      // can be fed into btoa.
      return btoa(
        encodeURIComponent(str).replace(
          /%([0-9A-F]{2})/g,
          function toSolidBytes(match, p1) {
            return String.fromCharCode("0x" + p1);
          }
        )
      );
    },

    handleBeforeUnload(event) {
      if (this.isDataChanged) {
        event.preventDefault();
        event.returnValue = ""; // Display default warning dialog
      }
    },
  },
};
</script>
