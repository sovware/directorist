<template>
  <div class="cptm-builder-section">
    <div class="cptm-options-area" v-if="widgetCardOptionsWindowActiveStatus || widgetOptionsWindowActiveStatus">
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
    <div class="cptm-preview-area">
      <div class="cptm-card-preview-area-wrap">
        <div class="cptm-card-preview-widget">
          <div class="cptm-title-bar">
            <div class="cptm-title-bar-headings cptm-card-light">
              <div class="cptm-card-options-widgets-area" v-if="Object.keys( card_options.general ).length">
                <template v-for="( widget, widget_key ) in card_options.general">
                  <component
                    :is="widget.type + '-card-widget'"
                    :label="getWidgetLabel( widget )"
                    :key="widget_key"
                    :canMove="false"
                    :canTrash="false"
                    @edit="editOption( card_options.general, widget_key )"
                  />
                </template>
              </div>
            </div>

            <div class="cptm-title-bar-actions">
              <div class="cptm-card-preview-quick-action">
                <!-- cptm-card-preview-quick-action -->
                <card-widget-placeholder
                  id="listings_header_quick_actions"
                  containerClass="cptm-card-preview-quick-action-placeholder cptm-card-light"
                  :label="local_layout.listings_header.quick_actions.label"
                  :availableWidgets="theAvailableWidgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="local_layout.listings_header.quick_actions.acceptedWidgets"
                  :selectedWidgets="local_layout.listings_header.quick_actions.selectedWidgets"
                  :maxWidget="local_layout.listings_header.quick_actions.maxWidget"
                  :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'listings_header_quick_actions' )"
                  :widgetDropable="widgetIsDropable( local_layout.listings_header.quick_actions )"
                  @insert-widget="insertWidget( $event, local_layout.listings_header.quick_actions )"
                  @drag-widget="onDragStartWidget( $event, local_layout.listings_header.quick_actions )"
                  @drop-widget="appendWidget( $event, local_layout.listings_header.quick_actions )"
                  @dragend-widget="onDragEndWidget()"
                  @edit-widget="editWidget( $event )"
                  @trash-widget="trashWidget( $event, local_layout.listings_header.quick_actions )"
                  @placeholder-on-drop="handleDropOnPlaceholder( local_layout.listings_header.quick_actions )"
                  @placeholder-on-dragover="handleDragOverOnPlaceholder( local_layout.listings_header.quick_actions )"
                  @placeholder-on-dragenter="handleDragEnterOnPlaceholder( local_layout.listings_header.quick_actions )"
                  @placeholder-on-dragleave="handleDragleaveOnPlaceholder( local_layout.listings_header.quick_actions )"
                  @open-widgets-picker-window="activeInsertWindow( 'listings_header_quick_actions' )"
                  @close-widgets-picker-window="closeInsertWindow()"
                />
              </div>
            </div>
          </div>

          <!-- cptm-listing-card-preview-header -->
          <div class="cptm-listing-card-preview-header">
            <div class="cptm-card-preview-thumbnail">
              <div class="cptm-card-preview-thumbnail-overlay">
                <!-- cptm-card-preview-thumbnail -->
                <div class="cptm-card-preview-thumbnail-placeholer">
                  <card-widget-placeholder
                    id="listings_header_thumbnail"
                    containerClass="cptm-card-preview-thumbnail-placeholder cptm-card-dark"
                    :label="local_layout.listings_header.thumbnail.label"
                    :availableWidgets="theAvailableWidgets"
                    :activeWidgets="active_widgets"
                    :acceptedWidgets="local_layout.listings_header.thumbnail.acceptedWidgets"
                    :selectedWidgets="local_layout.listings_header.thumbnail.selectedWidgets"
                    :maxWidget="local_layout.listings_header.thumbnail.maxWidget"
                    :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'listings_header_thumbnail' )"
                    :widgetDropable="widgetIsDropable( local_layout.listings_header.thumbnail )"
                    @insert-widget="insertWidget( $event, local_layout.listings_header.thumbnail )"
                    @drag-widget="onDragStartWidget( $event, local_layout.listings_header.thumbnail )"
                    @drop-widget="appendWidget( $event, local_layout.listings_header.thumbnail )"
                    @dragend-widget="onDragEndWidget()"
                    @edit-widget="editWidget( $event )"
                    @trash-widget="trashWidget( $event, local_layout.listings_header.thumbnail )"
                    @placeholder-on-drop="handleDropOnPlaceholder( local_layout.listings_header.thumbnail )"
                    @placeholder-on-dragover="handleDragOverOnPlaceholder( local_layout.listings_header.thumbnail )"
                    @placeholder-on-dragenter="handleDragEnterOnPlaceholder( local_layout.listings_header.thumbnail )"
                    @open-widgets-picker-window="activeInsertWindow( 'listings_header_thumbnail' )"
                    @close-widgets-picker-window="closeInsertWindow()"
                  />
                </div>

                <div class="cptm-card-preview-thumbnail-bg">
                    <span class="uil uil-scenery"></span>
                </div>
              </div>
            </div>
          </div>

          <!-- cptm-listing-card-preview-quick-info -->
          <div class="cptm-listing-card-preview-footer">
            <card-widget-placeholder
              id="listings_header_quick_info"
              containerClass="cptm-listing-card-preview-quick-info-placeholder cptm-card-light"
              :label="local_layout.listings_header.quick_info.label"
              :availableWidgets="theAvailableWidgets"
              :activeWidgets="active_widgets"
              :acceptedWidgets="local_layout.listings_header.quick_info.acceptedWidgets"
              :selectedWidgets="local_layout.listings_header.quick_info.selectedWidgets"
              :maxWidget="local_layout.listings_header.quick_info.maxWidget"
              :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'listings_header_quick_info' )"
              :widgetDropable="widgetIsDropable( local_layout.listings_header.quick_info )"
              @insert-widget="insertWidget( $event, local_layout.listings_header.quick_info )"
              @drag-widget="onDragStartWidget( $event, local_layout.listings_header.quick_info )"
              @drop-widget="appendWidget( $event, local_layout.listings_header.quick_info )"
              @dragend-widget="onDragEndWidget()"
              @edit-widget="editWidget( $event )"
              @trash-widget="trashWidget( $event, local_layout.listings_header.quick_info )"
              @placeholder-on-drop="handleDropOnPlaceholder( local_layout.listings_header.quick_info )"
              @open-widgets-picker-window="activeInsertWindow( 'listings_header_quick_info' )"
              @close-widgets-picker-window="closeInsertWindow()"
            />
          </div>

          <div class="cptm-card-options-widgets-area" v-if="Object.keys( card_options.content_settings ).length">
            <template v-for="( widget, widget_key ) in card_options.content_settings">
              <component
                :is="widget.type + '-card-widget'"
                :label="getWidgetLabel( widget )"
                :key="widget_key"
                :canMove="false"
                :canTrash="false"
                @edit="editOption( card_options.content_settings, widget_key )"
              />
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue';
import card_builder from './../../mixins/form-fields/card-builder';
import helpers from '../../mixins/helpers';

export default {
  name: "card-builder-listing-header-field",
  mixins: [ card_builder, helpers ],
  props: {
    fieldId: {
      required: false,
      default: '',
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
    document.addEventListener('click', function( e ) {
      self.closeInsertWindow();
    });
  },

  created() {
    this.init();
    this.$emit( 'update', this.output_data );
  },

  watch: {
    output_data() {
      this.$emit( 'update', this.output_data );
    }
  },

  computed: {
    output_data() {
      let output = {};
      let layout = this.local_layout;
      
      // Parse Layout
      for ( let section in layout ) {
        output[section] = {};

        if (typeof layout[section] !== "object") {
          continue;
        }

        for (let section_area in layout[section]) {
          output[section][section_area] = [];

          if (typeof layout[section][section_area] !== "object") {
            continue;
          }
          if (typeof layout[section][section_area].selectedWidgets !== "object") {
            continue;
          }

          for ( let widget in layout[section][section_area].selectedWidgets ) {
            const widget_name = layout[section][section_area].selectedWidgets[widget];

            if ( ! this.active_widgets[widget_name] && typeof this.active_widgets[widget_name] !== "object") {
              continue;
            }
            
            let widget_data = {};
            for ( let root_option in this.active_widgets[widget_name] ) {
              if ( 'options' === root_option ) { continue; }
              if ( 'icon' === root_option ) { continue; }
              if ( 'show_if' === root_option ) { continue; }
              if ( 'fields' === root_option ) { continue; }

              widget_data[ root_option ] = this.active_widgets[ widget_name ][ root_option ];
            }

            if ( typeof this.active_widgets[widget_name].options !== "object" ) {
              output[section][section_area].push(widget_data);
              continue;
            }

            if ( typeof this.active_widgets[widget_name].options.fields !== "object" ) {
              output[section][section_area].push(widget_data);
              continue;
            }

            let widget_options = this.active_widgets[widget_name].options.fields;

            for ( let option in widget_options ) {
              widget_data[option] = widget_options[ option ].value;
            }

            output[section][section_area].push(widget_data);
          }
        }
      }


      // Parse Card Options
      let options = {};
      for ( let section in this.card_options ) {

        if ( ! this.isObject( this.card_options[ section ] ) ) { continue; }
        options[ section ] = {};

        for ( let option in this.card_options[ section ] ) {
          if ( ! this.isObject( this.card_options[ section ][ option ] ) ) { continue; }
          if ( ! this.isObject( this.card_options[ section ][ option ].options ) ) { continue; }
          if ( ! this.isObject( this.card_options[ section ][ option ].options.fields ) ) { continue; }

          options[ section ][ option ] = {};

          for ( let field in this.card_options[ section ][ option ].options.fields ) {
            if ( typeof this.card_options[ section ][ option ].options.fields[ field ].value === 'undefined' ) { continue; }
            options[ section ][ option ][ field ] = this.card_options[ section ][ option ].options.fields[ field ].value;
          }
        }
      }

      output.options = options;

      return output;
    },

    theAvailableWidgets() {
      let available_widgets = JSON.parse( JSON.stringify( this.available_widgets ) );

      for ( let widget in available_widgets ) {
        available_widgets[ widget ].widget_name = widget;
        available_widgets[ widget ].widget_key = widget;

        // Check show if condition
        let show_if_cond_state = null;

        if ( this.isObject( available_widgets[ widget ].show_if ) ) {
          show_if_cond_state = this.checkShowIfCondition( { condition: available_widgets[ widget ].show_if } );
          let main_widget = available_widgets[ widget ];
          
          delete available_widgets[ widget ];

          if ( show_if_cond_state.status ) {
            let widget_keys = [];
            for ( let matched_field of show_if_cond_state.matched_data ) {
              // console.log( {matched_field} );
              let _main_widget = JSON.parse( JSON.stringify( main_widget ) );
              let current_key = ( widget_keys.includes( widget ) ) ? widget + '_' + (widget_keys.length + 1) : widget;
              _main_widget.widget_key = current_key;

              if ( matched_field.widget_key ) {
                _main_widget.widget_key = matched_field.widget_key;
              }

              if ( typeof matched_field.label === 'string' && matched_field.label.length ) {
                _main_widget.label = matched_field.label;
              }
 
              available_widgets[ current_key ] = _main_widget;
              widget_keys.push( current_key );
            }
          }
        }
      
      }

      return available_widgets;
    },

    widgetOptionsWindowActiveStatus() {
      if ( ! this.widgetOptionsWindow.widget.length ) { return false; }
      if ( typeof this.active_widgets[ this.widgetOptionsWindow.widget ] === 'undedined'  ) { return false; }

      return true;
    },

    widgetCardOptionsWindowActiveStatus() {
      if ( ! this.isObject( this.widgetCardOptionsWindow.widget ) ) { return false; }

      return true;
    },

    _currentDraggingWidget() {
      return this.currentDraggingWidget;
    }
  },

  data() {
    return {
      active_insert_widget_key: '',

      // Widget Options Window
      widgetOptionsWindowDefault: {
        animation: 'cptm-animation-flip',
        widget: ''
      },

      widgetCardOptionsWindow: {
        animation: 'cptm-animation-flip',
        widget: ''
      },

      widgetOptionsWindow: {
        animation: 'cptm-animation-flip',
        widget: ''
      },

      currentDraggingWidget: { origin: {}, key: '' },

      // Available Widgets
      available_widgets: {},

      // Active Widgets
      active_widgets: {},

      // Card Options
      card_options: {
        general: {},
        content_settings: {},
      },

      // Layout
      local_layout: {
        listings_header: {
          quick_actions: {
            label: 'Quick Actions',
            selectedWidgets: [],
          },
          thumbnail: {
            label: 'Thumbnail',
            selectedWidgets: [],
          },
          quick_info: {
            label: 'Quick Info',
            selectedWidgets: [],
          },
        },
      },
    };
  },

  methods: {
    init() {
      this.importWidgets();
      this.importCardOptions();
      this.importLayout();
      this.importOldData();
    },

    isTruthyObject( obj ) {
      if ( ! obj && typeof obj !== 'object' ) {
        return false;
      }

      return true;
    },

    isJSON( string ) {
      try {
        JSON.parse( string );
      } catch (e) {
        return false;
      }

      return true;
    },

    importOldData() {
      let value = JSON.parse( JSON.stringify( this.value ) );
      if ( ! this.isTruthyObject( value ) ) { return; }

      // Import Layout
      // -------------------------
      let selectedWidgets = [];
      
      // Get Active Widgets Data
      let active_widgets_data = {};
      for ( let section in value ) {
        if ( 'options' === section ) { continue; }
        if ( ! value[ section ] && typeof value[ section ] !== 'object' ) { continue; }

        for ( let area in value[ section ] ) {
          if ( ! value[ section ][ area ] && typeof value[ section ][ area ] !== 'object' ) { continue; }

          for ( let widget of value[ section ][ area ] ) {
            if ( typeof widget.widget_key === 'undefined' ) { continue }
            if ( typeof widget.widget_name === 'undefined' ) { continue }
            if ( typeof this.available_widgets[ widget.widget_name ] === 'undefined' ) { continue; }
            if ( typeof this.local_layout[ section ] === 'undefined' ) { continue; }
            if ( typeof this.local_layout[ section ][ area ] === 'undefined' ) { continue; }

            active_widgets_data[ widget.widget_key ] = widget;
            selectedWidgets.push( { section: section, area: area, widget: widget.widget_key } );
          }
        }
      }


      // Load Active Widgets
      for (let widget_key in active_widgets_data) {
        if (typeof this.theAvailableWidgets[widget_key] === "undefined") {
          continue;
        }

        let widgets_template = { ...this.theAvailableWidgets[widget_key] };
        let widget_options = ( ! active_widgets_data[widget_key].options && typeof active_widgets_data[widget_key].options !== "object" ) ? false : active_widgets_data[widget_key].options;
       
        let has_widget_options = false;
        if ( widgets_template.options && widgets_template.options.fields ) {
          has_widget_options = true;
        }

        for ( let root_option in widgets_template ) {
          if ( 'options' === root_option ) { continue; }
          if ( active_widgets_data[widget_key][root_option] === "undefined" ) { continue; }

          widgets_template[ root_option ] = active_widgets_data[widget_key][root_option];
        }

        if ( has_widget_options ) {
          for ( let option_key in widgets_template.options.fields ) {
            if ( typeof active_widgets_data[widget_key][option_key] === "undefined" ) {
              continue;
            }
            widgets_template.options.fields[ option_key ].value = active_widgets_data[widget_key][option_key];
          }
        }

        Vue.set(this.active_widgets, widget_key, widgets_template);
      }

      // Load Selected Widgets Data
      for ( let item of selectedWidgets ) {
        let length = this.local_layout[ item.section ][ item.area ].selectedWidgets.length;
        this.local_layout[ item.section ][ item.area ].selectedWidgets.splice( length, 0, item.widget );
      }

      
      // Import Options
      // -------------------------
      
      if ( ! this.isTruthyObject( value.options ) ) { return; }

      for ( let section in value.options ) {
        if ( ! this.isTruthyObject( this.card_options[ section ] ) ) { continue; }
        if ( ! this.isTruthyObject( value.options[ section ] ) ) { continue; }

        for ( let option in value.options[ section ] ) {
          if ( ! this.isTruthyObject( this.card_options[ section ][ option ] ) ) { continue; }
          if ( ! this.isTruthyObject( this.card_options[ section ][ option ].options ) ) { continue; }
          if ( ! this.isTruthyObject( this.card_options[ section ][ option ].options.fields ) ) { continue; }
          if ( ! this.isTruthyObject( value.options[ section ][ option ] ) ) { continue; }

          for ( let field in value.options[ section ][ option ] ) {
            if ( ! this.isTruthyObject( this.card_options[ section ][ option ].options.fields[ field ] ) ) { continue; }
            Vue.set( this.card_options[ section ][ option ].options.fields[ field ], 'value', value.options[ section ][ option ][ field ] );
          }
        }
      }

    },

    importWidgets() {
      if ( ! this.isTruthyObject( this.widgets ) ) { return; }

      this.available_widgets = this.widgets;
    },

    importCardOptions() {
      if ( ! this.isTruthyObject( this.cardOptions ) ) { return; }

      for ( let section in this.card_options ) {
        if ( ! this.isTruthyObject( this.cardOptions[ section ] ) ) { return; }
        Vue.set( this.card_options, section, JSON.parse( JSON.stringify( this.cardOptions[ section ] ) ) );
      }
    },

    importLayout() {
      if ( ! this.isTruthyObject( this.layout ) ) {
        return;
      }

      for ( let section in this.local_layout ) {
        
        if ( ! this.isTruthyObject( this.layout[ section ] ) ) {
          continue;
        }

        for ( let area in this.local_layout[ section ] ) {
          if ( ! this.isTruthyObject( this.layout[ section ][ area ] ) ) {
            continue;
          }

          Object.assign( this.local_layout[ section ][ area ], this.layout[ section ][ area ] );
        }
      }
    },

    onDragStartWidget( key, origin ) {
      this.currentDraggingWidget.key = key;
      this.currentDraggingWidget.origin = origin;
    },

    onDragEndWidget() {
      this.currentDraggingWidget.key = '';
      this.currentDraggingWidget.origin = '';
    },

    maxWidgetLimitIsReached( path ) {
      if ( ! path.maxWidget  ) { return false; }
      if ( path.selectedWidgets.length >= path.maxWidget  ) { return true; }

      return false;
    },

    widgetIsAccepted( path, key ) {

      if ( ! path.acceptedWidgets  ) { return true; }
      if ( ! this.isTruthyObject( path.acceptedWidgets )  ) { return true; }

      if ( path.acceptedWidgets.includes( this.theAvailableWidgets[ key ].widget_name ) ) { return true; }

      return false;
    },

    widgetIsDropable( path ) {

      if ( ! this._currentDraggingWidget.key.length ) {
        return false;
      }

      if ( ! this.isTruthyObject( this._currentDraggingWidget.origin ) ) {
        return false;
      }

       if ( path.selectedWidgets.includes( this._currentDraggingWidget.key ) ) {
        return true;
      }

      if ( this.maxWidgetLimitIsReached( path ) ) {
        return false;
      }

      if ( ! this.widgetIsAccepted( path, this._currentDraggingWidget.key ) ) {
        return false;
      }
      
      return true;
    },

    appendWidget( dest_key, dest_path ) {
      const key         = this.currentDraggingWidget.key;
      const from        = this.currentDraggingWidget.origin.selectedWidgets;
      const orign_index = from.indexOf( key );
      let dest_index    = dest_path.selectedWidgets.indexOf( dest_key ) + 1;

      if ( dest_path.selectedWidgets.includes( key ) && 0 === orign_index ) {
        dest_index--;
      }

      Vue.delete( from , from.indexOf( key ) );
      dest_path.selectedWidgets.splice( dest_index, 0, this.currentDraggingWidget.key );

      this.onDragEndWidget();
    },
    
    
    handleDropOnPlaceholder( dest ) {
      // return;
      const key  = this.currentDraggingWidget.key;
      const from = this.currentDraggingWidget.origin.selectedWidgets;
      const to   = dest.selectedWidgets;

      if ( ! this.isTruthyObject( from ) ) { return; }
      if ( ! this.isTruthyObject( to ) ) { return; }
      if ( this.maxWidgetLimitIsReached( dest ) ) { return; }
      if ( ! this.widgetIsAccepted( dest, key ) ) { return; }

      if ( ! to.includes( key ) ) {
        Vue.delete( from, from.indexOf( key ) );
        Vue.set( to, to.length, key );
      }

      this.onDragEndWidget();
    },

    handleDragEnterOnPlaceholder( where ) {
      // console.log( 'handleDragEnterOnPlaceholder', where );
    },

    handleDragOverOnPlaceholder( where ) {
      // console.log( 'handleDragOverOnPlaceholder', where );
    },
    
    handleDragleaveOnPlaceholder( where ) {
      // console.log( 'handleDragleaveOnPlaceholder', where );
    },

    editWidget( key ) {

      if ( typeof this.active_widgets[ key ] === 'undefined' ) {
        return;
      }

      if ( ! this.active_widgets[ key ].options && typeof this.active_widgets[ key ].options !== 'object' ) {
        return;
      }

      this.widgetOptionsWindow = { ...this.widgetOptionsWindowDefault, ...this.active_widgets[ key ].options };
      this.widgetOptionsWindow.widget = key;

      this.active_insert_widget_key = '';
    },

    editOption( widget_path, widget_key ) {

      if ( ! this.isObject( widget_path[ widget_key ].options ) ) {
        return;
      }

      let widget_options = widget_path[ widget_key ].options;
      // let window_default = JSON.parse( JSON.stringify( this.widgetOptionsWindowDefault ) );
      let window_default = this.widgetOptionsWindowDefault;

      this.widgetCardOptionsWindow = { ...window_default, ...widget_options };
      this.widgetCardOptionsWindow.widget = { path: widget_path, widget_key };
    },

    updateCardWidgetOptionsData( data, options_window ) {
      return;

      if ( typeof this.card_option_widgets[ options_window.widget ] === 'undefined' ) {
        return;
      }
      
      if ( typeof this.card_option_widgets[ options_window.widget ].options === 'undefined' ) {
        return;
      }

      Vue.set( this.card_option_widgets[ options_window.widget ].options, 'fields', data );
    },

    updateWidgetOptionsData( data, options_window ) {
      return;

      if ( typeof this.active_widgets[ widget.widget ] === 'undefined' ) {
        return;
      }

      if ( typeof this.active_widgets[ widget.widget ].options === 'undefined' ) {
        return;
      }

      Vue.set( this.active_widgets[ widget.widget ].options, 'fields', data );
    },

    closeCardWidgetOptionsWindow() {
      this.widgetCardOptionsWindow = this.widgetOptionsWindowDefault;
    },

    closeWidgetOptionsWindow() {
      this.widgetOptionsWindow = this.widgetOptionsWindowDefault;
    },

    trashWidget( key, where ) {
      if ( ! where.selectedWidgets.includes( key ) ) { return; }
      
      let index = where.selectedWidgets.indexOf( key );
      Vue.delete( where.selectedWidgets, index);

      if ( typeof this.active_widgets[ key ] === 'undefined' ) { return; }
      Vue.delete( this.active_widgets, key);

      if ( key === this.widgetOptionsWindow.widget ) {
        this.closeWidgetOptionsWindow();
      }
    },

    activeInsertWindow( current_item_key ) {
      let self = this;

      setTimeout( function() {
        if ( self.active_insert_widget_key === current_item_key ) {
          self.active_insert_widget_key = '';
          return;
        }

        self.active_insert_widget_key = current_item_key;
      }, 0);
    },

    insertWidget( payload, where ) {

      if ( ! this.isTruthyObject( this.theAvailableWidgets[ payload.key ] ) ) {
        return;
      }

      Vue.set( this.active_widgets, payload.key, { ...this.theAvailableWidgets[ payload.key ] } );
      Vue.set( where, 'selectedWidgets', payload.selected_widgets );

      this.editWidget( payload.key );
    },

    closeInsertWindow( widget_insert_window ) {
      this.active_insert_widget_key = '';
    },

    getWidgetLabel( widget ) {
      let label = '';

      if ( typeof widget.label === 'string' ) {
        label = widget.label;
      }

      if ( 
        this.isObject( widget.options ) && 
        widget.options.fields && 
        widget.options.fields.label && 
        widget.options.fields.label.value &&
        widget.options.fields.label.value.length
      ) {
        label = widget.options.fields.label.value;
      }

      return label;
    },

    getActiveInsertWindowStatus( current_item_key ) {

      if ( current_item_key === this.active_insert_widget_key ) {
        return true;
      }

      return false;
    }
  },
};
</script>