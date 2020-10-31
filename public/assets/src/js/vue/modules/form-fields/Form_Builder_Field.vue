<template>
  <div class="cptm-form-builder cptm-row">
    <div class="cptm-col-6">
      <div class="cptm-form-builder-active-fields">
        <h3 class="cptm-title-3">Active Fields</h3>
        <p class="cptm-description-text">
          Click on a field to edit, Drag & Drop to reorder
        </p>

        <div class="cptm-form-builder-active-fields-container">
          <div class="cptm-form-builder-active-fields-group" v-for="(group, group_key) in groups" :key="group_key">
            <div class="cptm-form-builder-group-header-section" v-if="has_group">
              <div class="cptm-form-builder-group-header">
                <div class="cptm-form-builder-group-title-area" draggable="true" @drag="activeGroupOnDragStart(group_key)" @dragend="activeGroupOnDragEnd()">
                  <h3 class="cptm-form-builder-group-title">
                    {{ group.label }}
                  </h3>

                  <div class="cptm-form-builder-group-title-actions">
                    <a href="#" class="cptm-form-builder-header-action-link" :class="getActiveGroupCollapseClass(group_key)" @click.prevent="toggleActiveGroupCollapseState(group_key)">
                      <span class="fa fa-angle-up" aria-hidden="true"></span>
                    </a>
                  </div>
                </div>

                <div class="cptm-form-builder-group-actions">
                  <a href="#" class="cptm-form-builder-group-field-item-action-link action-trash" v-if="typeof group.lock !== 'undefined' ? !group.lock : true" @click.prevent="trashActiveGroupItem(group_key)">
                    <span class="uil uil-trash-alt" aria-hidden="true"></span>
                  </a>
                </div>
              </div>

              <slide-up-down :active="getActiveGroupCollapseState(group_key)" :duration="500">
                <div class="cptm-form-builder-group-options">
                  <template v-for="(option, option_key) in getGroupOptions( group_key )">
                    <component 
                      :is="option.type + '-field'" :key="option_key" 
                      :feild-id="group_key + '_' + option_key"
                      v-bind="getSanitizedFieldsOptions(option)" 
                      :value="option.value"
                      @update="updateActiveGroupOptionData( option_key, group_key, $event )">
                    </component>
                  </template>
                </div>
              </slide-up-down>
            </div>

            <slide-up-down :active="'' === current_dragging_group ? true : false" :duration="500">
              <div class="cptm-form-builder-group-fields">
                <div class="cptm-form-builder-group-field-item" v-for="(field_key, field_index) in group.fields" :key="field_index">
                  <div class="cptm-form-builder-group-field-item-actions">
                    <a href="#" class="cptm-form-builder-group-field-item-action-link action-trash" v-if="! getActiveFieldsSettings(field_key, 'lock')" @click.prevent="trashActiveFieldItem(field_key, field_index, group_key)">
                      <span class="uil uil-trash-alt" aria-hidden="true"></span>
                    </a>
                  </div>

                  <div class="cptm-form-builder-group-field-item-header" draggable="true" @drag="activeFieldOnDragStart(field_key, field_index, group_key)" @dragend="activeFieldOnDragEnd()">
                    <h4 class="cptm-title-3">
                      {{ getActiveFieldsHeaderTitle(field_key) }}
                    </h4>
                    <div class="cptm-form-builder-group-field-item-header-actions">
                      <a href="#" class="cptm-form-builder-header-action-link action-collapse-up" :class="getActiveFieldCollapseClass(field_key)" @click.prevent="toggleActiveFieldCollapseState(field_key)">
                        <span class="fa fa-angle-up" aria-hidden="true"></span>
                      </a>
                    </div>
                  </div>

                  <slide-up-down :active="getActiveFieldCollapseState(field_key)" :duration="300">
                    <div class="cptm-form-builder-group-field-item-body" v-if="getActiveFieldsSettings(field_key, 'options')">
                      <template v-for="(option, option_key) in getWidgetOptions( field_key )">
                        <component
                          :is="option.type + '-field'"
                          :key="option_key"
                          :field-id="option_key"
                          v-bind="getSanitizedFieldsOptions(option)"
                          :value="active_fields[field_key][option_key]"
                          @update="updateActiveFieldsOptionData({ field_key, option_key, value: $event })">
                        </component>
                      </template>
                    </div>
                  </slide-up-down>
                  
                  <div class="cptm-form-builder-group-field-item-drop-area" :class="field_key === active_field_drop_area ? 'drag-enter' : ''"
                    @dragenter="activeFieldOnDragEnter(field_key, field_index, group_key)"
                    @dragover.prevent="activeFieldOnDragOver(field_key, field_index, group_key)"
                    @dragleave="activeFieldOnDragLeave()"
                    @drop.prevent="activeFieldOnDrop({ field_key, field_index, group_key })">
                  </div>
                </div>
              </div>
            </slide-up-down>

            <slide-up-down :active="'' === current_dragging_group ? true : false" :duration="500">
              <div class="cptm-form-builder-group-field-drop-area" 
                :class="group_key === active_group_drop_area ? 'drag-enter' : ''" 
                v-if="!group.fields.length"
                @dragenter="activeGroupOnDragEnter(group_key)"
                @dragover.prevent="activeGroupOnDragOver(group_key)"
                @dragleave="activeGroupOnDragLeave(group_key)"
                @drop="activeFieldOnDrop({ group_key })">
                <p class="cptm-form-builder-group-field-drop-area-label">Drop Here</p>
              </div>
            </slide-up-down>

            <div class="cptm-item-footer-drop-area cptm-group-item-drop-area"
              v-if="canAddSections"
              :class="group_key === current_drag_enter_group_item ? 'drag-enter' : ''"
              @dragenter="activeGroupItemOnDragEnter(group_key)"
              @dragover.prevent="activeGroupItemOnDragOver(group_key)"
              @dragleave="activeGroupItemOnDragLeave(group_key)"
              @drop="activeGroupItemOnDrop(group_key)"
            ></div>
          </div>

          <div class="cptm-form-builder-active-fields-footer">
            <button type="button" class="cptm-btn cptm-btn-secondery" v-if="canAddSections" @click="addNewActiveFieldSection()">
              Add new section
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="cptm-col-6" v-if="theWidgetGroups">
      <div class="cptm-form-builder-preset-fields" v-for="(widget_group, group_key) in theWidgetGroups" :key="group_key">
        <h3 class="cptm-title-3">{{ widget_group.title }}</h3>
        <p class="cptm-description-text">{{ widget_group.description }}</p>

        <ul class="cptm-form-builder-field-list">
          <template v-for="(field, field_key) in widget_group.widgets">
            <li class="cptm-form-builder-field-list-item" draggable="true" :key="field_key" @drag="widgetItemOnDrag(group_key, field_key)" @dragend="widgetItemOnDragEnd()">
              <span class="cptm-form-builder-field-list-icon">
                <span v-if="field.icon && field.icon.length" :class="field.icon"></span>
              </span>
              <span class="cptm-form-builder-field-list-label">{{ field.label }}</span>
            </li>
          </template>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue';
import { mapState } from "vuex";
import helpers from '../../mixins/helpers';

export default {
  name: "form-builder",
  mixins: [ helpers ],
  props: {
    fieldId: {
        type: [ String, Number ],
        required: false,
        default: '',
    },
    template: {
      required: false,
      default: '',
    },
    widgets: {
      required: false,
      default: false,
    },
    groupOptions: {
      required: false,
      default: false,
    },
    value: {
      required: false,
      default: "",
    },
    dependency: {
      required: false,
      default: "",
    },
    has_group: {
      type: Boolean,
      default: true,
      required: false,
    },
    allow_add_new_section: {
      type: Boolean,
      default: true,
      required: false,
    },
    validation: {
      type: Array,
      required: false,
    },
  },
  created() {
    this.impportOldData();
    this.$emit("update", this.updated_value);
  },

  watch: {
    updated_value() {
      this.$emit("update", this.updated_value);
    }
  },

  computed: {
    ...mapState({
      fields: "fields",
    }),

    canAddSections() {
      if ( ! this.has_group ) { return false }
      if ( ! this.allow_add_new_section ) { return false }

      return true;
    },

    updated_value() {
      if ( ! this.has_group ) {
        return { fields: this.active_fields, fields_order: this.groups[0].fields };
      }

      return { fields: this.active_fields, groups: this.groups };
    },

    theWidgets() {
      if ( ! this.widgets && typeof this.widgets !== 'object' ) { return {} }

      // Add the widget group & name to all the widget fields
      let widgets = JSON.parse( JSON.stringify( this.widgets ) );

      for ( let widget_group in widgets ) {
        // Template
        // Get widget keys from field list
        let has_template = ( typeof widgets[ widget_group ].template === 'string' ) ? true : false;
        has_template = ( has_template && widgets[ widget_group ].template.length ) ? widgets[ widget_group ].template : false;

        let template_field  = ( has_template ) ? this.getTergetFields( { path: has_template } ) : null;
        template_field      = ( this.isObject( template_field ) ) ? JSON.parse( JSON.stringify( template_field ) ) : null;

        let template_fields = ( this.isObject( template_field ) && template_field.value ) ? template_field.value : null;
        template_fields     = ( this.isObject( template_fields ) ) && template_fields.fields ? template_fields.fields : null;

        if ( has_template && this.isObject( template_fields ) ) {
          let template_widgets = {};

          for ( let widget_key in template_fields ) {
            let _widget_name = template_fields[ widget_key ].widget_name;
            let _widget_group = template_fields[ widget_key ].widget_group;

            if ( ! widgets[ widget_group ].widgets[ _widget_name ] ) { continue; }
            
            let root_options = template_field.widgets[ _widget_group ].widgets[ _widget_name ];

            if ( ! root_options ) { continue; }

            if ( typeof root_options.options !== 'undefined' ) { delete root_options.options; }
            if ( typeof root_options.lock !== 'undefined' ) { delete root_options.lock; }

            let widget_label = ( widgets[ widget_group ].widgets[ _widget_name ].label ) ? widgets[ widget_group ].widgets[ _widget_name ].label : '';
            widget_label = ( template_fields[ widget_key ].label && template_fields[ widget_key ].label.length ) ? template_fields[ widget_key ].label : widget_label;
            root_options.label = widget_label;
    
            Object.assign( widgets[ widget_group ].widgets[ _widget_name ], root_options );
          
            if ( ! widgets[ widget_group ].widgets[ _widget_name ].options ) {
              widgets[ widget_group ].widgets[ _widget_name ].options = {};
            }

            let ref_option = { type: 'hidden', value: template_fields[ widget_key ] };
            widgets[ widget_group ].widgets[ _widget_name ].options['reference'] = ref_option;

            template_widgets[ widget_key ] = widgets[ widget_group ].widgets[ _widget_name ];
          }

          widgets[widget_group].widgets = template_widgets;
        }
      }
      
      return widgets;
    },

    theWidgetGroups() {
      // Add the widget group & name to all the widget fields
      let widgets = JSON.parse( JSON.stringify( this.theWidgets ) );

      for ( let widget_group in widgets ) {
        // Filter fields if required
        let filter_field_keys = null;
        let has_filter_by = ( typeof widgets[ widget_group ].filter_by === 'string' ) ? true : false;
        has_filter_by = ( has_filter_by && widgets[ widget_group ].filter_by.length ) ? widgets[ widget_group ].filter_by : false;

        if ( has_filter_by ) {
          let filter_field = this.getTergetFields( { path: has_filter_by } );

          if ( this.isObject( filter_field ) ) {
            filter_field_keys = Object.keys( filter_field );
          }
        }

        // ----------------
        for (let widget in widgets[widget_group].widgets) {
          // Filter fields if required
          if ( Array.isArray( filter_field_keys ) && ! filter_field_keys.includes( widget ) ) {
            delete widgets[ widget_group ].widgets[ widget ];
            continue;
          }

          // Check show_if_key_exists
          let check_show_if_key_exists = false;
          let show_if_key_exists_field_path = null;
          let show_if_key_exists_field = null;

          if ( typeof widgets[widget_group].widgets[widget].show_if_key_exists !== 'undefined' ) {
            check_show_if_key_exists = true;
            show_if_key_exists_field_path = widgets[widget_group].widgets[widget].show_if_key_exists;
          }

          if ( check_show_if_key_exists && ! show_if_key_exists_field_path.length ) {
            check_show_if_key_exists = false;
          }

          if ( check_show_if_key_exists ) {
            show_if_key_exists_field = this.getTergetFields( { path: show_if_key_exists_field_path } );
          }

          if ( check_show_if_key_exists && ! this.isObject( show_if_key_exists_field ) ) {
            delete widgets[ widget_group ].widgets[ widget ];
            continue;
          }

          // Check if allow multiple
          let allow_multiple = ( typeof widgets[ widget_group ].allow_multiple !== 'undefined' && widgets[ widget_group ].allow_multiple ) ? true : false;
          if ( ! allow_multiple && typeof this.active_fields[ widget ] !== 'undefined' ) {
            delete widgets[ widget_group ].widgets[ widget ];
            continue;
          }

          if ( ! widgets[widget_group].widgets[widget].options ) {
            widgets[widget_group].widgets[widget].options = {};
          }

          widgets[widget_group].widgets[widget].options.widget_group = {
            type: "hidden",
            value: widget_group,
          };

          widgets[widget_group].widgets[widget].options.widget_name = {
            type: "hidden",
            value: widget,
          };
        }
      }

      return widgets;
    },
  },
  data() {
    return {
      groups: [
        { label: "General", fields: [] },
      ],

      active_fields: {},

      state: {},
      active_fields_ref: {},
      current_dragging_widget_window: {},
      active_field_drop_area: "",
      active_group_drop_area: "",
      current_drag_enter_group_item: "",
      current_dragging_group: "",
      ative_field_collapse_states: {},
      ative_group_collapse_states: {},
    };
  },
  methods: {
    impportOldData() {
      if ( ! this.isObject( this.value ) ) { return; }

      if ( Array.isArray( this.value.fields_order ) && ! this.has_group ) {
        Vue.set( this.groups[0], 'fields', this.value.fields_order );
      }

      if ( Array.isArray( this.value.groups ) && this.has_group ) {
        this.groups = this.value.groups;
      }

      if ( this.isObject( this.value.fields ) ) {
        this.active_fields = this.value.fields;
      }
    },

    getGroupOptions( group_key ) {
      let group_options = JSON.parse( JSON.stringify( this.groupOptions ) );

      if ( ! this.isObject( group_options ) ) {
        return group_options;
      }

      let groups = JSON.parse( JSON.stringify( this.groups[ group_key ] ) );

      for ( let field in groups ) {
        if ( typeof group_options[ field ] === 'undefined' ) { continue }
        group_options[ field ].value = groups[ field ];
      }

      // console.log( { group_options } );

      for ( let field in group_options ) {
        if ( typeof group_options[ field ].show_if === 'undefined' ) {
          continue;
        }

        let show_if_cond = this.checkShowIfCondition({
          id: field,
          root: group_options,
          condition: group_options[ field ].show_if
        });

        if ( 'plans' == field ) {
          // console.log( { field, show_if_cond } );
        }
    
        if ( ! show_if_cond.status ) {
          delete group_options[ field ];
        }
      }

      return group_options;
    },

    getWidgetOptions( field_key ) {
      let options = JSON.parse( JSON.stringify( this.getActiveFieldsSettings( field_key, 'options' ) ) );
      let options_data = JSON.parse( JSON.stringify( this.active_fields[ field_key ] ) );

      for ( let field in options_data ) {
        if ( typeof options[ field ] === 'undefined' ) { continue; }
        options[ field ].value = options_data[ field ];
      }

      for ( let field in options ) {
        if ( typeof options[ field ].show_if === 'undefined' ) {
          continue;
        }

        let show_if_cond = this.checkShowIfCondition({
          root:  options,
          condition: options[ field ].show_if
        });
    
        if ( ! show_if_cond.status ) {
          delete options[ field ];
        }
      }

      return options;
    },

    getActiveFieldsOption(field_key, data_key) {
      return this.active_fields[field_key][data_key];
    },

    getActiveFieldsSettings(field_key, data_key) {
      const widget_group = this.active_fields[field_key].widget_group;
      const widget_name = this.active_fields[field_key].widget_name;

      if ( typeof widget_group === "undefined") { return false; }
      if ( typeof widget_name === "undefined") { return false; }

      if ( typeof this.theWidgets[widget_group] === "undefined") { return false; }
      if ( typeof this.theWidgets[widget_group].widgets === "undefined" ) { return false; }
      if ( typeof this.theWidgets[widget_group].widgets[ widget_name ] === "undefined" ) { return false; }
      if ( typeof this.theWidgets[widget_group].widgets[ widget_name ][data_key] === "undefined" ) { return false; }

      return this.theWidgets[widget_group].widgets[widget_name][data_key];
    },
    
    getSanitizedFieldsOptions(field_options) {
      if (typeof field_options !== "object") {
        return field_options;
      }
      let options = JSON.parse(JSON.stringify(field_options));
      delete options.value;
      return options;
    },
    
    getActiveFieldsHeaderTitle(field_key) {
      let settings_label = this.getActiveFieldsSettings(field_key, "label");
      settings_label = ( settings_label ) ? settings_label : 'Not Available';
      const option_label = this.active_fields[field_key]["label"];
      
      return option_label && option_label.length ? option_label : settings_label;
    },
    
    toggleActiveFieldCollapseState(field_key) {
      if (typeof this.ative_field_collapse_states[field_key] === "undefined") {
        this.$set(this.ative_field_collapse_states, field_key, {});
        this.$set( this.ative_field_collapse_states[field_key], "collapsed", false );
      }
      this.ative_field_collapse_states[field_key].collapsed = !this
        .ative_field_collapse_states[field_key].collapsed;
    },
    
    getActiveFieldCollapseClass(field_key) {
      if (typeof this.ative_field_collapse_states[field_key] === "undefined") {
        return "action-collapse-down";
      }
      return this.ative_field_collapse_states[field_key].collapsed
        ? "action-collapse-up"
        : "action-collapse-down";
    },
    
    getActiveFieldCollapseState(field_key) {
      if (typeof this.ative_field_collapse_states[field_key] === "undefined") {
        return false;
      }
      return this.ative_field_collapse_states[field_key].collapsed
        ? true
        : false;
    },

    toggleActiveGroupCollapseState(group_key) {
      if (typeof this.ative_group_collapse_states[group_key] === "undefined") {
        this.$set(this.ative_group_collapse_states, group_key, {});
        this.$set(
          this.ative_group_collapse_states[group_key],
          "collapsed",
          false
        );
      }
      this.ative_group_collapse_states[group_key].collapsed = !this
        .ative_group_collapse_states[group_key].collapsed;
    },
    
    getActiveGroupCollapseState(group_key) {
      if (typeof this.ative_group_collapse_states[group_key] === "undefined") {
        return false;
      }
      return this.ative_group_collapse_states[group_key].collapsed
        ? true
        : false;
    },
    
    getActiveGroupOptionValue(option_key, group_key) {
      if (typeof this.groups[group_key][option_key] === "undefined") {
        return "";
      }
      return this.groups[group_key][option_key];
    },
    
    updateActiveGroupOptionData(option_key, group_key, $event) {
      Vue.set( this.groups[group_key], option_key, $event );
      this.$emit("update", this.updated_value);
    },
    
    getActiveGroupCollapseClass(group_key) {
      if (typeof this.ative_group_collapse_states[group_key] === "undefined") {
        return "action-collapse-down";
      }
      return this.ative_group_collapse_states[group_key].collapsed
        ? "action-collapse-up"
        : "action-collapse-down";
    },
    
    trashActiveGroupItem(group_key) {
      for (let field of this.groups[group_key].fields) {
        if (typeof this.active_fields[field] === "undefined") {
          continue;
        }
        
        Vue.delete( this.active_fields, field );
      }
      
      Vue.delete( this.groups, group_key );
    },
    
    activeFieldOnDragStart(field_key, field_index, group_key) {
      this.current_dragging_widget_window = {
        field_key,
        field_index,
        group_key,
      };
    },
    
    activeFieldOnDragEnd() {
      this.current_dragging_widget_window = '';
    },
    
    activeFieldOnDragEnd(field_key) {
      this.active_field_drop_area = '';
    },
    
    activeFieldOnDragOver(field_key) {
      this.active_field_drop_area = field_key;
    },
    
    activeFieldOnDragEnter(field_key) {
      this.active_field_drop_area = field_key;
    },
    
    activeFieldOnDragLeave() {
      this.active_field_drop_area = "";
    },
    //
    
    activeGroupOnDragOver(group_key) {
      this.active_field_drop_area = group_key;
    },
    
    activeGroupOnDragEnter(group_key) {
      this.active_group_drop_area = group_key;
    },
    
    activeGroupOnDragLeave() {
      this.active_group_drop_area = "";
    },
    //
    
    activeGroupOnDragStart(group_key) {
      this.current_dragging_group = group_key;
    },
    
    activeGroupOnDragEnd() {
      this.current_dragging_group = "";
    },
    
    activeGroupItemOnDragOver(group_key) {
      this.current_drag_enter_group_item = group_key;
    },
    
    activeGroupItemOnDragEnter(group_key) {
      this.current_drag_enter_group_item = group_key;
    },
    
    activeGroupItemOnDragLeave() {
      this.current_drag_enter_group_item = "";
    },
    
    activeGroupItemOnDrop(dest_group_key) {
      if (this.current_dragging_group === "") {
        this.current_dragging_group = "";
        this.current_drag_enter_group_item = "";
        return;
      }
      const origin_value = this.groups[this.current_dragging_group];
      this.groups.splice(this.current_dragging_group, 1);
      const des_ind =
        this.current_dragging_group === 0 ? dest_group_key : dest_group_key + 1;
      this.groups.splice(des_ind, 0, origin_value);
      this.current_dragging_group = "";
      this.current_drag_enter_group_item = "";
    },
    //
   
    widgetItemOnDrag(group_key, field_key) {
      this.current_dragging_widget_window = {
        inserting_field_key: field_key,
        inserting_from: group_key,
      };
      // console.log( inserting_field_key:  );
    },

    widgetItemOnDragEnd(){
      this.current_dragging_widget_window = '';
    },

    activeFieldOnDrop(args) {
      // console.log( 'activeFieldOnDrop', {field_key: args.field_key, field_index: args.field_index, group_key: args.group_key} );
      const inserting_from          = this.current_dragging_widget_window.inserting_from;
      const inserting_field_key     = this.current_dragging_widget_window.inserting_field_key;
      const origin_group_index      = this.current_dragging_widget_window.group_key;
      const origin_field_index      = this.current_dragging_widget_window.field_index;
      const destination_group_index = args.group_key;
      const destination_field_index = args.field_index;

      this.active_group_drop_area = '';
      this.active_field_drop_area = '';
      this.current_dragging_widget_window = '';
      
      /* console.log({
          inserting_from,
          inserting_field_key,
          origin_group_index,
          origin_field_index,
          destination_group_index,
          destination_field_index,
      }); */

      // Reorder
      if (
        typeof origin_group_index !== "undefined" &&
        typeof origin_field_index !== "undefined" &&
        typeof destination_group_index !== "undefined" &&
        typeof destination_field_index !== "undefined" &&
        origin_group_index === destination_group_index
      ) {
        // console.log("Reorder");
        this.reorderActiveFieldsItems({
          group_index: destination_group_index,
          origin_field_index,
          destination_field_index,
        });
      }

      // Move
      if (
        typeof origin_group_index !== "undefined" &&
        typeof origin_field_index !== "undefined" &&
        typeof destination_group_index !== "undefined" &&
        origin_group_index !== destination_group_index
      ) {
        // console.log("Move");
        this.moveActiveFieldsItems({
          origin_group_index,
          origin_field_index,
          destination_group_index,
          destination_field_index,
        });
      }

      // Insert
      if (
        typeof inserting_from !== "undefined" &&
        typeof inserting_field_key !== "undefined" &&
        typeof destination_group_index !== "undefined"
      ) {
        // console.log("Insert");
        this.insertActiveFieldsItem({
          inserting_from,
          inserting_field_key,
          destination_group_index,
          destination_field_index,
        });
      }
    },
    
    trashActiveFieldItem(field_key, field_index, group_key) {
      if (typeof this.ative_field_collapse_states[field_key] !== "undefined") {
        Vue.delete( this.ative_field_collapse_states, field_key );
      }

      const the_field_key = this.groups[group_key].fields[field_index];
      this.groups[group_key].fields.splice(field_index, 1);

      let base_field_key = this.active_fields[ the_field_key ].widget_name;
      if ( typeof this.active_fields_ref[ base_field_key ] !== 'undefined' ) {
        let target_index = this.active_fields_ref[ base_field_key ].indexOf( field_key );
        Vue.delete( this.active_fields_ref[ base_field_key ], target_index );
      }
      
      Vue.delete( this.active_fields, the_field_key );
    },
    
    updateActiveFieldsOptionData( payload ) {
      Vue.set( this.active_fields[payload.field_key], payload.option_key, payload.value );
      this.$emit("update", this.updated_value);
    },
    
    addNewActiveFieldSection() {
      this.groups.push({ label: "Section", fields: [] });
    },
    
    reorderActiveFieldsItems(payload) {
      const origin_value = this.groups[payload.group_index].fields[
        payload.origin_field_index
      ];
      this.groups[payload.group_index].fields.splice(
        payload.origin_field_index,
        1
      );
      const des_ind =
        payload.origin_field_index === 0
          ? payload.destination_field_index
          : payload.destination_field_index + 1;
      this.groups[payload.group_index].fields.splice(des_ind, 0, origin_value);
    },
    
    moveActiveFieldsItems(payload) {
      const origin_value = this.groups[payload.origin_group_index].fields[ payload.origin_field_index ];
      // Remove from origin group
      this.groups[payload.origin_group_index].fields.splice( payload.origin_field_index, 1 );
      
      // Insert to destination group
      // const des_ind = ( payload.origin_field_index === 0 ) ? payload.destination_field_index : payload.destination_field_index + 1 ;
      const des_ind = payload.destination_field_index + 1;
      this.groups[payload.destination_group_index].fields.splice( des_ind, 0, origin_value );
    },
    
    insertActiveFieldsItem(payload) {
      let inserting_field_key = payload.inserting_field_key;

      if ( typeof this.active_fields_ref[payload.inserting_field_key] === "undefined" ) {
        this.active_fields_ref[payload.inserting_field_key] = [];
      }

      if ( ! this.active_fields_ref[payload.inserting_field_key].length ) {
        this.active_fields_ref[payload.inserting_field_key].push( payload.inserting_field_key );
      } else {
        let new_key = payload.inserting_field_key + "_" + (this.active_fields_ref[payload.inserting_field_key].length + 1);
        this.active_fields_ref[inserting_field_key].push(new_key);
        inserting_field_key = new_key;
      }

      const field_data = this.theWidgetGroups[payload.inserting_from].widgets[ payload.inserting_field_key ];

      let field_data_options = {};
      for (let option_key in field_data.options) {
        field_data_options[option_key] = typeof field_data.options[option_key].value !== "undefined" ? field_data.options[option_key].value : "";
      }

      Vue.set( this.active_fields, inserting_field_key, field_data_options );

      const widget_group = this.active_fields[inserting_field_key].widget_group;
      const widget_name = this.active_fields[inserting_field_key].widget_name;
      let terget_index = this.groups[ payload.destination_group_index ].fields.length;

      if ( typeof payload.destination_field_index !== "undefined" ) {
        terget_index = payload.destination_field_index + 1;
      }

      this.groups[ payload.destination_group_index ].fields.splice( terget_index, 0, inserting_field_key );
    },
    
    widgetCanShow(widget_group, field_key) {
      if (typeof widget_group.allow_multiple === "undefined") {
        widget_group.allow_multiple = false;
      }
      if (!widget_group.allow_multiple && this.groupHasKey(field_key)) {
        return false;
      }
      return true;
    },
    
    groupHasKey(field_key) {
      let match_found = false;
      if (!this.groups) {
        return match_found;
      }
      for (let i = 0; i < this.groups.length; i++) {
        if (this.groups[i].fields.indexOf(field_key) > -1) {
          match_found = true;
          break;
        }
      }
      return match_found;
    },
  },
};
</script>