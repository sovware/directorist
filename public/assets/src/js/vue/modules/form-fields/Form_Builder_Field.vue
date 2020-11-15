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

                <div class="cptm-form-builder-group-title-area"
                  :draggable="typeof group.draggable !== 'undefined' ? group.draggable : true"
                  @dragstart="activeGroupOnDragStart(group_key)"
                  @dragend="activeGroupOnDragEnd()"
                >
                  <h3 class="cptm-form-builder-group-title">
                    {{ ( group.label ) ? group.label : '' }}
                  </h3>

                  <div class="cptm-form-builder-group-title-actions">
                    <a href="#" class="cptm-form-builder-header-action-link" v-if="hasGroupOptions( group_key )" :class="getActiveGroupOptionCollapseClass(group_key)" @click.prevent="toggleActiveGroupOptionCollapseState(group_key)">
                      <span class="fa fa-angle-up" aria-hidden="true"></span>
                    </a>

                    <a href="#" class="cptm-form-builder-header-action-link" v-if="group.type !== 'widget_group'" :class="getActiveGroupCollapseClass(group_key)" @click.prevent="toggleActiveGroupCollapseState(group_key)">
                      <span class="uil uil-angle-double-up" aria-hidden="true"></span>
                    </a>
                  </div>
                </div>

                <div class="cptm-form-builder-group-actions">
                  <a href="#" class="cptm-form-builder-group-field-item-action-link action-trash" v-if="typeof group.lock !== 'undefined' ? !group.lock : true" @click.prevent="trashActiveGroupItem(group_key)">
                    <span class="uil uil-trash-alt" aria-hidden="true"></span>
                  </a>
                </div>
              </div>

              <slide-up-down :active="getActiveGroupOptionCollapseState(group_key)" :duration="500">
                <div class="cptm-form-builder-group-options" v-if="getGroupOptions( group_key )">
                  <template v-for="(option, option_key) in getGroupOptions( group_key )">
                    <component 
                      :is="option.type + '-field'" 
                      :key="option_key" 
                      :feild-id="fieldId + '_' + group_key + '_' + option_key"
                      v-bind="option"
                      @update="updateActiveGroupOptionData( option_key, group_key, $event )">
                    </component>
                  </template>
                </div>
              </slide-up-down>
            </div>

            <slide-up-down :active="getActiveGroupCollapseState( group_key )" :duration="500">
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
                          :test="{ field_key, option_key, value: active_fields[field_key][option_key] }"
                          :key="option_key"
                          :field-id="fieldId + '_' + field_key + '_' + option_key"
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

            <slide-up-down :active="getActiveGroupCollapseState( group_key )" :duration="500">
              <div class="cptm-form-builder-group-field-drop-area" 
                :class="group_key === active_group_drop_area ? 'drag-enter' : ''" 
                v-if="!group.fields.length && group.type !== 'widget_group'"
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

        <ul class="cptm-form-builder-field-list" v-if="widget_group.widgets">
          <template v-for="(field, field_key) in widget_group.widgets">
            <li class="cptm-form-builder-field-list-item" draggable="true" :key="field_key" @dragstart="widgetItemOnDragStart(group_key, field_key, field)" @dragend="widgetItemOnDragEnd()">
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
    this.groups = this.parseGroups();
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
    theGroups() {
      let groups = JSON.parse( JSON.stringify( this.groups ) );
      if ( ! Array.isArray( groups ) ) { return []; }
      if ( ! groups.length ) { return groups; }
      let group_index = 0;
      for ( let group of groups ) {
        
        if ( typeof group.options === 'undefined' ) { continue }
        for ( let option_key in group.options ) {
          groups[ group_index ][ option_key ] = group.options[ option_key ].value;
        }
        delete groups[ group_index ].options;
        group_index++;
      }
      return groups;
    },
    updated_value() {
      let groups = this.groups;
      if ( groups.length ) {
        groups = JSON.parse( JSON.stringify( this.groups ) );
        for ( let group_index in groups ) {
          if ( typeof groups[ group_index ].options === 'undefined' ) { continue; }
          for( let option in groups[ group_index ].options ) {
            groups[ group_index ][ option ] = groups[ group_index ].options[ option ].value;
          }
          delete groups[ group_index ].options;
        } 
      }
      if ( ! this.has_group ) {
        return { fields: this.active_fields, fields_order: groups[0].fields };
      }
      
      return { fields: this.active_fields, groups: groups };
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
            
            let template_root_options = template_field.widgets[ _widget_group ].widgets[ _widget_name ];
            if ( ! template_root_options ) { continue; }
            if ( typeof template_root_options.options !== 'undefined' ) { delete template_root_options.options; }
            if ( typeof template_root_options.lock !== 'undefined' ) { delete template_root_options.lock; }
            let widget_label = ( widgets[ widget_group ].widgets[ _widget_name ].label ) ? widgets[ widget_group ].widgets[ _widget_name ].label : '';
            let template_widget_label = ( template_fields[ widget_key ].label && template_fields[ widget_key ].label.length ) ? template_fields[ widget_key ].label : widget_label;
            widget_label = ( widget_label ) ? widget_label : template_widget_label;
            template_root_options.label = widget_label;
            Object.assign( widgets[ widget_group ].widgets[ _widget_name ], template_root_options );
          
            if ( ! widgets[ widget_group ].widgets[ _widget_name ].options ) {
              widgets[ widget_group ].widgets[ _widget_name ].options = {};
            }

            let widgets_options = widgets[ widget_group ].widgets[ _widget_name ].options;

            if ( typeof widgets_options.label !== 'undefined' ) {
              widgets_options.label.value = widget_label;
            }

            widgets[ widget_group ].widgets[ _widget_name ].options = widgets_options;
            template_widgets[ widget_key ] = widgets[ widget_group ].widgets[ _widget_name ];
          }
          widgets[widget_group].widgets = template_widgets;
        }
        for ( let widget in widgets[ widget_group ].widgets ) {
          if ( ! widgets[ widget_group ].widgets[ widget ].options ) {
            widgets[ widget_group ].widgets[ widget ].options = {};
          }
          widgets[ widget_group ].widgets[ widget ].options.widget_name = {
            type: 'hidden',
            value: widget,
          };
          widgets[ widget_group ].widgets[ widget ].options.widget_group = {
            type: 'hidden',
            value: widget_group,
          };
        }
      }
      
      return widgets;
    },
    elementIsDragging() {
      // console.log( this.current_dragging_widget, this.current_dragging_group );
      if ( this.current_dragging_widget || this.current_dragging_group  ) { return true }
      return false;
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
          if ( typeof widgets[widget_group].widgets[widget].show_if !== 'undefined' ) {
            let show_if_cond = this.checkShowIfCondition({ condition: widgets[widget_group].widgets[widget].show_if });
         
            if ( ! show_if_cond.status ) {
              delete widgets[ widget_group ].widgets[ widget ];
              continue;
            }
          }
          // Check if allow multiple
          let allow_multiple = ( typeof widgets[ widget_group ].allow_multiple !== 'undefined' && widgets[ widget_group ].allow_multiple ) ? true : false;
          if ( ! allow_multiple && typeof this.active_fields[ widget ] !== 'undefined' ) {
            delete widgets[ widget_group ].widgets[ widget ];
            continue;
          }
          
          if ( ! allow_multiple && this.active_widget_groups.includes( widget ) ) {
            delete widgets[ widget_group ].widgets[ widget ];
            continue;
          }
        }
      }
      return widgets;
    },
  },
  data() {
    return {
      default_groups: [ { label: "General", fields: [] } ],
      groups: [],
      active_fields: {},
      state: {},
      active_fields_ref: {},
      current_dragging_widget: "",
      active_field_drop_area: "",
      active_group_drop_area: "",
      current_drag_enter_group_item: "",
      current_dragging_group: "", 
      current_dragging_widget_group: "",
      active_widget_groups: [],
      active_field_collapse_states: {},
      active_group_collapse_states: {},
      active_group_option_collapse_states: {},
    };
  },
  methods: {
    impportOldData() {
      if ( ! this.isObject( this.value ) ) { return; }
      if ( Array.isArray( this.value.fields_order ) && ! this.has_group ) {
        Vue.set( this.default_groups[0], 'fields', this.value.fields_order );
      }
      if ( Array.isArray( this.value.groups ) && this.has_group ) {
        this.default_groups = this.value.groups;
      }
      // Trace active_widget_groups
      if ( this.default_groups.length ) {
        for ( let group of this.default_groups ) {
          if ( 'widget_group' !==  group.type ) { continue; }
          if ( typeof group.widget_name === 'undefined' ) { continue; }
          this.active_widget_groups.push( group.widget_name );
        }
      }
      if ( this.isObject( this.value.fields ) ) {
        this.active_fields = this.value.fields;
      }
    },
    parseGroups() {
        let groups = this.default_groups;
        if ( ! groups.length ) { return groups; }
        groups = JSON.parse( JSON.stringify( groups ) );
        let group_fields = {};
        let fixed_options = [ 'lock', 'fields', 'type' ];
        let group_index = 0;
        for ( let group of groups ) {
          // general_group
          if ( typeof group.type === 'undefined' || group.type === 'general_group' ) {
            let group_options = JSON.parse( JSON.stringify( this.groupOptions ) );
            for ( let option_key in group ) {
              if ( fixed_options.includes( option_key ) ) { continue; }
              if ( typeof group_options[ option_key ] === 'undefined' ) { continue } 
              
              group_options[ option_key ].value = group[ option_key ];
            }
            groups[ group_index ].type = 'general_group';
            groups[ group_index ].options = group_options;
          }
          // widget_group
          if ( group.type === 'widget_group' && typeof group.widget_group === 'string' && typeof group.widget_name === 'string' ) {
            if ( typeof this.theWidgets[ group.widget_group ] === 'undefined' ) { continue; }
            if ( typeof this.theWidgets[ group.widget_group ].widgets === 'undefined' ) { continue; }
            if ( typeof this.theWidgets[ group.widget_group ].widgets[ group.widget_name ] === 'undefined' ) { continue; }
            if ( typeof this.theWidgets[ group.widget_group ].widgets[ group.widget_name ].options === 'undefined' ) { continue; }
            let group_options = this.theWidgets[ group.widget_group ].widgets[ group.widget_name ].options;
            for ( let option_key in group ) {
              if ( fixed_options.includes( option_key ) ) { continue; }
              if ( typeof group_options[ option_key ] === 'undefined' ) { continue } 
              
              group_options[ option_key ].value = group[ option_key ];
            }
            groups[ group_index ].options = group_options;
          }
          group_index++;
        }
        return groups;
    },
    getGroupOptions( group_key ) {
      if ( ! this.isObject( this.groups[ group_key ].options ) ) { return false; }
      let group_options = JSON.parse( JSON.stringify( this.groups[ group_key ].options ) );
      for ( let field in group_options ) {
          if ( typeof group_options[ field ].show_if === 'undefined' ) {
              continue;
          }
          let show_if_cond = this.checkShowIfCondition({
              id: field,
              root: group_options,
              condition: group_options[ field ].show_if
          });
          if ( ! show_if_cond.status ) {
              delete group_options[ field ];
          }
      }
      return group_options;
    },
    hasWidgets(  widgets ) {
      if ( ! widgets ) { return false }
      if ( ! this.isObject( widgets ) ) { return false }
      if ( widgets == {} ) { return false }
      return true;
    },
    hasGroupOptions( group_key ) {
      // let group_options = JSON.parse( JSON.stringify( this.groups[ group_key ].options ) );
      let group_options = { ...this.groups[ group_key ].options };
      if ( ! group_options ) { return false; }
      let has_visible_field = false;
      for ( let field in group_options ) {
        if ( group_options[ field ].type !== 'hidden' ) {
          has_visible_field = true;
          break;
        }
      }
      
      return has_visible_field;
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
          root: options,
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
      settings_label = ( settings_label ) ? settings_label : '';
      const option_label = this.active_fields[field_key]["label"];
      
      return option_label && option_label.length ? option_label : settings_label;
    },
    
    toggleActiveFieldCollapseState(field_key) {
      if (typeof this.active_field_collapse_states[field_key] === "undefined") {
        this.$set(this.active_field_collapse_states, field_key, {});
        this.$set( this.active_field_collapse_states[field_key], "collapsed", false );
      }
      this.active_field_collapse_states[field_key].collapsed = !this
        .active_field_collapse_states[field_key].collapsed;
    },
    
    getActiveFieldCollapseClass(field_key) {
      if (typeof this.active_field_collapse_states[field_key] === "undefined") {
        return "action-collapse-up";
      }
      return this.active_field_collapse_states[field_key].collapsed
        ? "action-collapse-up"
        : "action-collapse-down";
    },
    
    getActiveFieldCollapseState(field_key) {
      if (typeof this.active_field_collapse_states[field_key] === "undefined") {
        return false;
      }
      return this.active_field_collapse_states[field_key].collapsed
        ? true
        : false;
    },
    toggleActiveGroupCollapseState(group_key) {
      if ( typeof this.active_group_collapse_states[group_key] === "undefined" ) {
        Vue.set(this.active_group_collapse_states, group_key, {});
        Vue.set( this.active_group_collapse_states[group_key], "collapsed", true );
      }
      this.active_group_collapse_states[group_key].collapsed = !this.active_group_collapse_states[group_key].collapsed;
    },
    getActiveGroupCollapseState(group_key) {
      let group_is_collapsed = true;
    
      if ( typeof this.active_group_collapse_states[group_key] !== "undefined" ) {
        group_is_collapsed = this.active_group_collapse_states[group_key].collapsed;
      }
      let group_is_dragging = ('' !== this.current_dragging_group) ? true : false;
      let widget_group_is_dragging = ('' !== this.current_dragging_widget_group) ? true : false;
      // console.log( { group_is_collapsed,  group_is_dragging } );
      if ( group_is_collapsed && ! group_is_dragging && ! widget_group_is_dragging ) {
        return true;
      }
      return false;
    },
    toggleActiveGroupOptionCollapseState(group_key) {
      if ( typeof this.active_group_option_collapse_states[group_key] === "undefined" ) {
        this.$set(this.active_group_option_collapse_states, group_key, {});
        this.$set( this.active_group_option_collapse_states[group_key], "collapsed", false );
      }
      this.active_group_option_collapse_states[group_key].collapsed = !this.active_group_option_collapse_states[group_key].collapsed;
    },
    
    getActiveGroupOptionCollapseState(group_key) {
      if (typeof this.active_group_option_collapse_states[group_key] === "undefined") {
        return false;
      }
      return this.active_group_option_collapse_states[group_key].collapsed ? true : false;
    },
    
    getActiveGroupOptionValue(option_key, group_key) {
      if (typeof this.groups[group_key][option_key] === "undefined") {
        return "";
      }
      return this.groups[group_key][option_key];
    },
    
    updateActiveGroupOptionData(option_key, group_key, $event) {
      // console.log( 'updateActiveGroupOptionData', option_key, group_key, $event );
      Vue.set( this.groups[group_key].options[option_key], 'value', $event );
      if ( typeof this.groups[group_key][option_key] !== 'undefined' ) {
        Vue.set( this.groups[group_key], option_key, $event );
      }
      this.$emit("update", this.updated_value);
    },
    
    getActiveGroupCollapseClass(group_key) {
      return this.getActiveGroupCollapseState( group_key ) ? "action-collapse-up" : "action-collapse-down";
    },
    getActiveGroupOptionCollapseClass(group_key) {
      if (typeof this.active_group_option_collapse_states[group_key] === "undefined") {
        return "action-collapse-down";
      }
      return this.active_group_option_collapse_states[group_key].collapsed
        ? "action-collapse-up"
        : "action-collapse-down";
    },
    
    trashActiveGroupItem(group_key) {
      if ( this.groups[group_key].type === 'widget_group' ) {
        let index = this.active_widget_groups.indexOf( this.groups[group_key].widget_name );
        Vue.delete( this.active_widget_groups, index );
      }
      for (let field of this.groups[group_key].fields) {
        if (typeof this.active_fields[field] === "undefined") {
          continue;
        }
        
        Vue.delete( this.active_fields, field );
      }
      
      Vue.delete( this.groups, group_key );
    },
    
    activeFieldOnDragStart(field_key, field_index, group_key) {
      this.current_dragging_widget = {
        field_key,
        field_index,
        group_key,
      };
    },
    
    activeFieldOnDragEnd() {
      this.current_dragging_widget = '';
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
      if ( typeof this.groups[ group_key ].draggable !== 'undefined' ) {
        if ( ! this.groups[ group_key ].draggable ) {
          return;
        }
      }
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
        if ( this.current_dragging_widget_group && 'group' === this.current_dragging_widget_group.widget_type ) {
            let group = {
                label: this.current_dragging_widget_group.field.label, fields: [],
                type: 'widget_group',
                widget_group: this.current_dragging_widget_group.inserting_from,
                widget_name: this.current_dragging_widget_group.inserting_field_key,
                options: this.current_dragging_widget_group.field.options
            };
            const des_ind = dest_group_key + 1;
            this.groups.splice(des_ind, 0, JSON.parse( JSON.stringify( group ) ));
            // Trace Widget Group
            this.active_widget_groups.push( group.widget_name );
            this.current_dragging_widget_group = '';
            this.current_dragging_group        = '';
            this.current_drag_enter_group_item = '';
            return;
        }   
        if ( this.current_dragging_group === "" ) {
            this.current_drag_enter_group_item = "";
            return;
        }
        const origin_value = this.groups[this.current_dragging_group];
        this.groups.splice(this.current_dragging_group, 1);
        const des_ind = this.current_dragging_group === 0 ? dest_group_key : dest_group_key + 1;
        this.groups.splice(des_ind, 0, origin_value);
        this.current_dragging_group = "";
        this.current_drag_enter_group_item = "";
    },
    //
   
    widgetItemOnDragStart( group_key, field_key, field ) {
        let data = {
            widget_type: 'widget',
            inserting_field_key: field_key,
            inserting_from: group_key,
            field: field,
        };
        if ( typeof field.type !== 'undefined' ) {
            data.widget_type = 'group';
            this.current_dragging_widget_group = data;
            return;
        }
        
        this.current_dragging_widget = data;
    },
    widgetItemOnDragEnd(){
      this.current_dragging_widget = '';
      this.current_dragging_widget_group = '';
    },
    activeFieldOnDrop(args) {
      // console.log( 'activeFieldOnDrop', {field_key: args.field_key, field_index: args.field_index, group_key: args.group_key} );
      const inserting_from          = this.current_dragging_widget.inserting_from;
      const inserting_field_key     = this.current_dragging_widget.inserting_field_key;
      const origin_group_index      = this.current_dragging_widget.group_key;
      const origin_field_index      = this.current_dragging_widget.field_index;
      const destination_group_index = args.group_key;
      const destination_field_index = args.field_index;
      this.active_group_drop_area = '';
      this.active_field_drop_area = '';
      this.current_dragging_widget = '';
      
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
      if (typeof this.active_field_collapse_states[field_key] !== "undefined") {
        Vue.delete( this.active_field_collapse_states, field_key );
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
      let group = { label: "Section", fields: [], type: 'general_group' };
      if ( this.groupOptions && typeof this.groupOptions === 'object' ) {
          group.options = this.groupOptions;
      }
      Vue.set( this.groups, this.groups.length, JSON.parse( JSON.stringify( group ) ) );
    },
    
    reorderActiveFieldsItems(payload) {
      const origin_value = this.groups[payload.group_index].fields[
        payload.origin_field_index
      ];
      this.groups[payload.group_index].fields.splice( payload.origin_field_index, 1 );
      const des_ind = payload.origin_field_index === 0 ? payload.destination_field_index : payload.destination_field_index + 1;
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