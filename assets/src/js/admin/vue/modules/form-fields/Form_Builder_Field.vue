<template>
  <div class="cptm-form-builder cptm-row">
    <div class="cptm-col-6">
      <div class="cptm-form-builder-active-fields">
        <h3 class="cptm-title-3">Active Fields</h3>
        <p class="cptm-description-text">
          Click on a field to edit, Drag & Drop to reorder
        </p>

        <button
          type="button"
          class="cptm-btn"
          v-if="showGroupDragToggleButton"
          :class="forceExpandStateTo ? 'cptm-btn-primary' : ''"
          @click="toggleEnableWidgetGroupDragging"
        >
          {{ forceExpandStateTo ? "Disable Section Dragging" : "Enable Section Dragging" }}
        </button>
        
        <div class="cptm-form-builder-active-fields-container">
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
              @widget-drag-start="handleWidgetDragStart(widget_group_key, $event)"
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
              @click="addNewGroup"
              v-html="addNewGroupButtonLabel"
            ></button>
          </div>
        </div>
      </div>
    </div>

    <div class="cptm-col-6 cptm-col-sticky">
      <template v-for="(widget_group, widget_group_key) in widgets">
        <form-builder-widget-list-section-component
          :key="widget_group_key"
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
  </div>
</template>

<script>
import Vue from "vue";
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

      if (this.generalSettings && this.generalSettings.addNewGroupButtonLabel) {
        button_label = this.generalSettings.addNewGroupButtonLabel;
      }

      return button_label;
    },
  },

  data() {
    return {
      local_value: {},

      active_widget_fields: {},
      active_widget_groups: [],
      avilable_widgets: {},

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
      isEnabledGroupDragging: false,

      currentDraggingGroup: null,
      currentDraggingWidget: null,
    };
  },

  methods: {
    setup() {
      this.setupActiveWidgetFields();
      this.setupActiveWidgetGroups();
    },

    // setupActiveWidgetFields
    setupActiveWidgetFields() {
      if ( ! this.value ) { return; }
      if ( typeof this.value !== "object" ) { return; }

      if ( ! this.value.fields) { return; }
      if ( typeof this.value.fields !== "object") { return; }

      let active_widget_fields =  Array.isArray( this.value.fields ) ? {} : this.value.fields;
      active_widget_fields = this.sanitizeActiveWidgetFields( active_widget_fields );

      this.active_widget_fields = active_widget_fields;

      this.$emit("updated-state");
      this.$emit("active-widgets-updated");
    },

    // sanitizeActiveWidgetFields
    sanitizeActiveWidgetFields( active_widget_fields ) {
      if ( ! active_widget_fields ) { return {} };
      if ( typeof active_widget_fields !== 'object' ) { return {} };

      if ( typeof active_widget_fields.field_key !== 'undefined' ) {
        delete active_widget_fields.field_key;
      }

      for ( let widget_key in active_widget_fields ) {

        if ( typeof active_widget_fields[ widget_key ] !== 'object' ) {
          delete active_widget_fields[ widget_key ];
        }

        active_widget_fields[ widget_key ].widget_key = widget_key;
      }

      return active_widget_fields;
    },

    // setupActiveWidgetGroups
    setupActiveWidgetGroups() {
      if ( ! this.value ) return;
      if ( typeof this.value !== "object" ) return;

      if ( Array.isArray( this.value.groups ) ) {
        this.active_widget_groups = this.sanitizeActiveWidgetGroups( this.value.groups );
      }

      this.$emit("active-group-updated");
    },

    // sanitizeActiveWidgetGroups
    sanitizeActiveWidgetGroups( _active_widget_groups ) {
      let active_widget_groups = _active_widget_groups;
      if ( ! Array.isArray( active_widget_groups ) ) { 
        active_widget_groups = [];
      }

      let group_index = 0;
      for ( let widget_group of active_widget_groups ) {
        if ( typeof widget_group.label === 'undefined' ) {
          active_widget_groups[ group_index ].label = '';
        }

        if ( typeof widget_group.fields === 'undefined' || ! Array.isArray( widget_group.fields ) ) {
          active_widget_groups[ group_index ].fields = [];
        }

        let field_index = 0;
        for ( let field of widget_group.fields ) {
          if ( typeof this.active_widget_fields[ field ] === 'undefined' ) {
            delete active_widget_groups[ group_index ].fields[ field_index ];
          }

          field_index++;
        }

        group_index++;
      }

      return active_widget_groups;
    },

    // updateWidgetList
    updateWidgetList(widget_list) {
      if ( ! widget_list) { return; }
      if (typeof widget_list !== "object") { return; }
      if (typeof widget_list.widget_group === "undefined") { return; }
      if (typeof widget_list.base_widget_list === "undefined") { return; }

      Vue.set( this.avilable_widgets, widget_list.widget_group, widget_list.base_widget_list );
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

    updateWidgetField(payload) {
      Vue.set(
        this.active_widget_fields[payload.widget_key],
        payload.payload.key,
        payload.payload.value
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

    handleWidgetDrop(widget_group_key, payload) {
      let dropped_in = {
        widget_group_key,
        widget_key: payload.widget_key,
        widget_index: payload.widget_index,
        drop_direction: payload.drop_direction,
      };

      // handleWidgetReorderFromActiveWidgets
      if ("active_widgets" === this.currentDraggingWidget.from) {
        this.handleWidgetReorderFromActiveWidgets( this.currentDraggingWidget, dropped_in );
        this.currentDraggingWidget = null;
        return;
      }

      // handleWidgetInsertFromAvailableWidgets
      if ("available_widgets" === this.currentDraggingWidget.from) {
        this.handleWidgetInsertFromAvailableWidgets( this.currentDraggingWidget, dropped_in );
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
      let field_data_options = this.getOptionDataFromWidget(from.widget);
      let inserting_widget_key = this.genarateWidgetKeyForActiveWidgets( from.widget_key );

      if ( field_data_options.field_key ) {
        let unique_field_key = this.genarateFieldKeyForActiveWidgets( field_data_options );
        field_data_options.field_key = unique_field_key;
      }
      
      field_data_options.widget_key = inserting_widget_key;

      if ( Array.isArray( this.active_widget_fields ) ) {
        this.active_widget_fields = {};
      }
      
      Vue.set( this.active_widget_fields, inserting_widget_key, field_data_options );

      let to_fields = this.active_widget_groups[to.widget_group_key].fields;
      let dest_index = "before" === to.drop_direction ? to.widget_index - 1 : to.widget_index;
      
      dest_index = "after" === to.drop_direction ? to.widget_index + 1 : to.widget_index;
      dest_index = dest_index < 0 ? 0 : dest_index;
      dest_index = dest_index >= to_fields.length ? to_fields.length : dest_index;

      this.active_widget_groups[to.widget_group_key].fields.splice( dest_index, 0, inserting_widget_key );

      this.$emit("updated-state");
      this.$emit("active-widgets-updated");
    },

    handleWidgetListItemDragStart(widget_group_key, payload) {
      // console.log( 'handleWidgetListItemDragStart', { widget_group_key, payload } );

      if ( payload.widget && typeof payload.widget.type !== "undefined" && "section" === payload.widget.type ) {
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
      let field_data_options = {};
      if (widget.options && typeof widget.options === "object") {
        for (let option_key in widget.options) {
          field_data_options[option_key] = typeof widget.options[option_key].value !== "undefined" ? widget.options[option_key].value : "";
        }
      }

      return field_data_options;
    },

    genarateWidgetKeyForActiveWidgets( widget_key ) {
      if ( typeof this.active_widget_fields[widget_key] !== "undefined" ) {
        let matched_keys = Object.keys( this.active_widget_fields );
        
        const getUniqueKey = function( current_key, new_key  ) {
          if ( matched_keys.includes( new_key ) ) {

            let field_id = new_key.match( /[_](\d+)$/ );
            field_id = ( field_id ) ? parseInt( field_id[1] ) : 1;

            const new_field_key = current_key + '_' + ( field_id + 1 );

            return getUniqueKey( current_key, new_field_key );
          }

          return new_key;
        };

        const new_widget_key = getUniqueKey( widget_key, widget_key );
        return new_widget_key;
      }

      return widget_key;
    },

    genarateFieldKeyForActiveWidgets( field_data_options ) {

      if ( ! field_data_options.field_key ) { return ''; }
      const current_field_key = field_data_options.field_key;

      let field_keys = [];

      for ( let key in this.active_widget_fields ) {
        if ( ! this.active_widget_fields[ key ].field_key ) {
          continue;
        }

        field_keys.push( this.active_widget_fields[ key ].field_key );
      }

      const getUniqueKey = function( field_key ) {
        if ( field_keys.includes( field_key ) ) {

          let field_id = field_key.match( /[-](\d+)$/ );
          field_id = ( field_id ) ? parseInt( field_id[1] ) : 1;

          const new_field_key = current_field_key + '-' + ( field_id + 1 );

          return getUniqueKey( new_field_key );
        }

        return field_key;
      };

      const unique_field_key = getUniqueKey( current_field_key );

      return unique_field_key;
    },

    handleGroupDragStart(widget_group_key) {
      this.currentDraggingGroup = {
        from: "active_widgets",
        widget_group_key,
      };
    },

    handleGroupDragEnd() {
      this.currentDraggingGroup = null;
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

      let dest_index = this.active_widget_groups.length;
      this.active_widget_groups.splice(dest_index, 0, group);

      this.$emit("updated-state");
    },

    handleGroupReorderFromActiveWidgets(from, to) {
      let origin_data = this.active_widget_groups[from.widget_group_key];

      let dest_index = from.widget_group_key < to.widget_group_key ? to.widget_group_key - 1 : to.widget_group_key;
      dest_index = "after" === to.drop_direction ? dest_index + 1 : dest_index;

      this.active_widget_groups.splice(from.widget_group_key, 1);
      this.active_widget_groups.splice(dest_index, 0, origin_data);

      this.$emit("updated-state");
      this.$emit("group-reordered");
    },

    handleGroupInsertFromAvailableWidgets(from, to) {
      let group = JSON.parse(JSON.stringify(this.default_group[0]));

      let widget = from.widget;
      let option_data = this.getOptionDataFromWidget(widget);
      delete widget.options;

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

    trashGroup(widget_group_key) {
      let group_fields = this.active_widget_groups[widget_group_key].fields;

      if (group_fields.length) {
        for ( let widget_key of group_fields ) {
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
  },
};
</script>
