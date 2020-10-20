<template>
  <div class="cptm-builder-section">
    <!-- cptm-preview-area -->
    <div class="cptm-preview-area">
      <div class="cptm-card-preview-widget">
        <!-- cptm-listing-card-preview-header -->
        <div class="cptm-listing-card-preview-header">
          <div class="cptm-card-preview-thumbnail">
            <div class="cptm-card-preview-thumbnail-overlay">
              
              <div class="cptm-card-preview-top-left">
                <!-- cptm-card-preview-top-left -->
                <card-widget-placeholder
                  id="thumbnail_top_left"
                  containerClass="cptm-card-preview-top-left-placeholder cptm-card-dark"
                  :label="local_layout.thumbnail.top_left.label"
                  :availableWidgets="available_widgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="local_layout.thumbnail.top_left.acceptedWidgets"
                  :selectedWidgets="local_layout.thumbnail.top_left.selectedWidgets"
                  :maxWidget="local_layout.thumbnail.top_left.maxWidget"
                  :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_top_left' )"
                  :widgetDropable="widgetIsDropable( local_layout.thumbnail.top_left )"
                  @insert-widget="insertWidget( $event, local_layout.thumbnail.top_left )"
                  @drag-widget="onDragStartWidget( $event, local_layout.thumbnail.top_left )"
                  @drop-widget="appendWidget( $event, local_layout.thumbnail.top_left )"
                  @dragend-widget="onDragEndWidget()"
                  @edit-widget="editWidget( $event )"
                  @trash-widget="trashWidget( $event, local_layout.thumbnail.top_left )"
                  @placeholder-on-drop="handleDropOnPlaceholder( local_layout.thumbnail.top_left )"
                  @placeholder-on-dragover="handleDragOverOnPlaceholder( local_layout.thumbnail.top_left )"
                  @placeholder-on-dragenter="handleDragEnterOnPlaceholder( local_layout.thumbnail.top_left )"
                  @placeholder-on-dragleave="handleDragleaveOnPlaceholder( local_layout.thumbnail.top_left )"
                  @open-widgets-picker-window="activeInsertWindow( 'thumbnail_top_left' )"
                  @close-widgets-picker-window="closeInsertWindow()"
                />
              </div>

              <!-- cptm-card-preview-top-rignt -->
              <div class="cptm-card-preview-top-rignt">
                <card-widget-placeholder
                  id="thumbnail_top_right"
                  containerClass="cptm-card-preview-top-rignt-placeholder cptm-card-dark"
                  :label="local_layout.thumbnail.top_right.label"
                  :availableWidgets="available_widgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="local_layout.thumbnail.top_right.acceptedWidgets"
                  :selectedWidgets="local_layout.thumbnail.top_right.selectedWidgets"
                  :maxWidget="local_layout.thumbnail.top_right.maxWidget"
                  :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_top_right' )"
                  :widgetDropable="widgetIsDropable( local_layout.thumbnail.top_right )"
                  @insert-widget="insertWidget( $event, local_layout.thumbnail.top_right )"
                  @drag-widget="onDragStartWidget( $event, local_layout.thumbnail.top_right )"
                  @drop-widget="appendWidget( $event, local_layout.thumbnail.top_right )"
                  @dragend-widget="onDragEndWidget()"
                  @edit-widget="editWidget( $event )"
                  @trash-widget="trashWidget( $event, local_layout.thumbnail.top_right )"
                  @placeholder-on-drop="handleDropOnPlaceholder( local_layout.thumbnail.top_right )"
                  @placeholder-on-dragover="handleDragOverOnPlaceholder( local_layout.thumbnail.top_right )"
                  @placeholder-on-dragenter="handleDragEnterOnPlaceholder( local_layout.thumbnail.top_right )"
                  @open-widgets-picker-window="activeInsertWindow( 'thumbnail_top_right' )"
                  @close-widgets-picker-window="closeInsertWindow()"
                />
              </div>

              <!-- cptm-card-preview-bottom-left -->
              <div class="cptm-card-preview-bottom-left">
                <card-widget-placeholder
                  id="thumbnail_bottom_left"
                  containerClass="cptm-card-preview-bottom-left-placeholder cptm-card-dark"
                  :label="local_layout.thumbnail.bottom_left.label"
                  :availableWidgets="available_widgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="local_layout.thumbnail.bottom_left.acceptedWidgets"
                  :selectedWidgets="local_layout.thumbnail.bottom_left.selectedWidgets"
                  :maxWidget="local_layout.thumbnail.bottom_left.maxWidget"
                  :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_bottom_left' )"
                  :widgetDropable="widgetIsDropable( local_layout.thumbnail.bottom_left )"
                  @insert-widget="insertWidget( $event, local_layout.thumbnail.bottom_left )"
                  @drag-widget="onDragStartWidget( $event, local_layout.thumbnail.bottom_left )"
                  @drop-widget="appendWidget( $event, local_layout.thumbnail.bottom_left )"
                  @dragend-widget="onDragEndWidget()"
                  @edit-widget="editWidget( $event )"
                  @trash-widget="trashWidget( $event, local_layout.thumbnail.bottom_left )"
                  @placeholder-on-drop="handleDropOnPlaceholder( local_layout.thumbnail.bottom_left )"
                  @placeholder-on-dragover="handleDragOverOnPlaceholder( local_layout.thumbnail.bottom_left )"
                  @placeholder-on-dragenter="handleDragEnterOnPlaceholder( local_layout.thumbnail.bottom_left )"
                  @open-widgets-picker-window="activeInsertWindow( 'thumbnail_bottom_left' )"
                  @close-widgets-picker-window="closeInsertWindow()"
                />
              </div>

              <!-- cptm-card-preview-bottom-right -->
              <div class="cptm-card-preview-bottom-right">
                <card-widget-placeholder
                  id="thumbnail_bottom_right"
                  containerClass="cptm-card-preview-bottom-right-placeholder cptm-card-dark"
                  :label="local_layout.thumbnail.bottom_right.label"
                  :availableWidgets="available_widgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="local_layout.thumbnail.bottom_right.acceptedWidgets"
                  :selectedWidgets="local_layout.thumbnail.bottom_right.selectedWidgets"
                  :maxWidget="local_layout.thumbnail.bottom_right.maxWidget"
                  :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_bottom_right' )"
                  :widgetDropable="widgetIsDropable( local_layout.thumbnail.bottom_right )"
                  @insert-widget="insertWidget( $event, local_layout.thumbnail.bottom_right )"
                  @drag-widget="onDragStartWidget( $event, local_layout.thumbnail.bottom_right )"
                  @drop-widget="appendWidget( $event, local_layout.thumbnail.bottom_right )"
                  @dragend-widget="onDragEndWidget()"
                  @edit-widget="editWidget( $event )"
                  @trash-widget="trashWidget( $event, local_layout.thumbnail.bottom_right )"
                  @placeholder-on-drop="handleDropOnPlaceholder( local_layout.thumbnail.bottom_right )"
                  @placeholder-on-dragover="handleDragOverOnPlaceholder( local_layout.thumbnail.bottom_right )"
                  @placeholder-on-dragenter="handleDragEnterOnPlaceholder( local_layout.thumbnail.bottom_right )"
                  @open-widgets-picker-window="activeInsertWindow( 'thumbnail_bottom_right' )"
                  @close-widgets-picker-window="closeInsertWindow()"
                />
              </div>

              <div class="cptm-card-preview-thumbnail-bg">
                  <span class="uil uil-scenery"></span>
              </div>
            </div>
          </div>
        </div>

        <!-- cptm-listing-card-preview-body -->
        <div class="cptm-listing-card-preview-body">
          <!-- cptm-listing-card-author-avatar -->
          <div class="cptm-listing-card-author-avatar">
            <card-widget-placeholder
              id="thumbnail_avatar"
              containerClass="cptm-listing-card-author-avatar-placeholder cptm-card-dark-light cptm-mb-20"
              :label="local_layout.thumbnail.avatar.label"
              :availableWidgets="available_widgets"
              :activeWidgets="active_widgets"
              :acceptedWidgets="local_layout.thumbnail.avatar.acceptedWidgets"
              :selectedWidgets="local_layout.thumbnail.avatar.selectedWidgets"
              :maxWidget="local_layout.thumbnail.avatar.maxWidget"
              :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_avatar' )"
              :widgetDropable="widgetIsDropable( local_layout.thumbnail.avatar )"
              @insert-widget="insertWidget( $event, local_layout.thumbnail.avatar )"
              @drag-widget="onDragStartWidget( $event, local_layout.thumbnail.avatar )"
              @drop-widget="appendWidget( $event, local_layout.thumbnail.avatar )"
              @dragend-widget="onDragEndWidget()"
              @edit-widget="editWidget( $event )"
              @trash-widget="trashWidget( $event, local_layout.thumbnail.avatar )"
              @placeholder-on-drop="handleDropOnPlaceholder( local_layout.thumbnail.avatar )"
              @open-widgets-picker-window="activeInsertWindow( 'thumbnail_avatar' )"
              @close-widgets-picker-window="closeInsertWindow()"
            />
          </div>

          <card-widget-placeholder
            id="thumbnail_body_top"
            containerClass="cptm-listing-card-preview-title-placeholder cptm-card-light cptm-mb-20 cptm-align-left"
            :label="local_layout.body.top.label"
            :availableWidgets="available_widgets"
            :activeWidgets="active_widgets"
            :acceptedWidgets="local_layout.body.top.acceptedWidgets"
            :selectedWidgets="local_layout.body.top.selectedWidgets"
            :maxWidget="local_layout.body.top.maxWidget"
            :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_body_top' )"
            :widgetDropable="widgetIsDropable( local_layout.body.top )"
            @insert-widget="insertWidget( $event, local_layout.body.top )"
            @drag-widget="onDragStartWidget( $event, local_layout.body.top )"
            @drop-widget="appendWidget( $event, local_layout.body.top )"
            @dragend-widget="onDragEndWidget()"
            @edit-widget="editWidget( $event )"
            @trash-widget="trashWidget( $event, local_layout.body.top )"
            @placeholder-on-drop="handleDropOnPlaceholder( local_layout.body.top )"
            @open-widgets-picker-window="activeInsertWindow( 'thumbnail_body_top' )"
            @close-widgets-picker-window="closeInsertWindow()"
          />

          <card-widget-placeholder
            id="thumbnail_body_bottom"
            containerClass="cptm-listing-card-preview-body-placeholder cptm-card-light"
            :label="local_layout.body.bottom.label"
            :availableWidgets="available_widgets"
            :activeWidgets="active_widgets"
            :acceptedWidgets="local_layout.body.bottom.acceptedWidgets"
            :selectedWidgets="local_layout.body.bottom.selectedWidgets"
            :maxWidget="local_layout.body.bottom.maxWidget"
            :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_body_bottom' )"
            :widgetDropable="widgetIsDropable( local_layout.body.bottom )"
            @insert-widget="insertWidget( $event, local_layout.body.bottom )"
            @drag-widget="onDragStartWidget( $event, local_layout.body.bottom )"
            @drop-widget="appendWidget( $event, local_layout.body.bottom )"
            @dragend-widget="onDragEndWidget()"
            @edit-widget="editWidget( $event )"
            @trash-widget="trashWidget( $event, local_layout.body.bottom )"
            @placeholder-on-drop="handleDropOnPlaceholder( local_layout.body.bottom )"
            @open-widgets-picker-window="activeInsertWindow( 'thumbnail_body_bottom' )"
            @close-widgets-picker-window="closeInsertWindow()"
          />
        </div>

        <!-- cptm-listing-card-preview-footer -->
        <div class="cptm-listing-card-preview-footer">
          <!-- cptm-listing-card-preview-footer-left-placeholder -->
          <card-widget-placeholder
            id="thumbnail_footer_left"
            containerClass="cptm-listing-card-preview-footer-left-placeholder cptm-card-light"
            :label="local_layout.footer.left.label"
            :availableWidgets="available_widgets"
            :activeWidgets="active_widgets"
            :acceptedWidgets="local_layout.footer.left.acceptedWidgets"
            :selectedWidgets="local_layout.footer.left.selectedWidgets"
            :maxWidget="local_layout.footer.left.maxWidget"
            :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_footer_left' )"
            :widgetDropable="widgetIsDropable( local_layout.footer.left )"
            @insert-widget="insertWidget( $event, local_layout.footer.left )"
            @drag-widget="onDragStartWidget( $event, local_layout.footer.left )"
            @drop-widget="appendWidget( $event, local_layout.footer.left )"
            @dragend-widget="onDragEndWidget()"
            @edit-widget="editWidget( $event )"
            @trash-widget="trashWidget( $event, local_layout.footer.left )"
            @placeholder-on-drop="handleDropOnPlaceholder( local_layout.footer.left )"
            @open-widgets-picker-window="activeInsertWindow( 'thumbnail_footer_left' )"
            @close-widgets-picker-window="closeInsertWindow()"
          />

          <!-- cptm-listing-card-preview-footer-right-placeholder -->
          <card-widget-placeholder
            id="thumbnail_footer_right"
            containerClass="cptm-listing-card-preview-footer-right-placeholder cptm-card-light"
            :label="local_layout.footer.right.label"
            :availableWidgets="available_widgets"
            :activeWidgets="active_widgets"
            :acceptedWidgets="local_layout.footer.right.acceptedWidgets"
            :selectedWidgets="local_layout.footer.right.selectedWidgets"
            :maxWidget="local_layout.footer.right.maxWidget"
            :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_footer_right' )"
            :widgetDropable="widgetIsDropable( local_layout.footer.right )"
            @insert-widget="insertWidget( $event, local_layout.footer.right )"
            @drag-widget="onDragStartWidget( $event, local_layout.footer.right )"
            @drop-widget="appendWidget( $event, local_layout.footer.right )"
            @dragend-widget="onDragEndWidget()"
            @edit-widget="editWidget( $event )"
            @trash-widget="trashWidget( $event, local_layout.footer.right )"
            @placeholder-on-drop="handleDropOnPlaceholder( local_layout.footer.right )"
            @open-widgets-picker-window="activeInsertWindow( 'thumbnail_footer_right' )"
            @close-widgets-picker-window="closeInsertWindow()"
          />
        </div>
      </div>
    </div>

    <div class="cptm-options-area">
      <options-window 
        :active="widgetOptionsWindowActiveStatus" 
        v-bind="widgetOptionsWindow" 
        @update="updateWidgetOptionsData( $event, widgetOptionsWindow )"
        @close="closeWidgetOptionsWindow()"
      />
    </div>
  </div>
</template>

<script>
import Vue from 'vue';

export default {
  name: "card-builder-grid-view-field",
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
  },

  created() {
    this.init();
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

          for (let widget in layout[section][section_area].selectedWidgets) {
            const widget_name = layout[section][section_area].selectedWidgets[widget];

            if ( ! this.active_widgets[widget_name] && typeof this.active_widgets[widget_name] !== "object") {
              continue;
            }
            
            let widget_data = {};
            for ( let root_option in this.active_widgets[widget_name] ) {
              if ( 'options' === root_option ) { continue; }

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
            
            widget_data.options = {};
            let widget_options = this.active_widgets[widget_name].options.fields;

            for ( let option in widget_options ) {
              widget_data.options[option] = widget_options[ option ].value;
            }

            output[section][section_area].push(widget_data);
          }
        }
      }

      return output;
    },

    widgetOptionsWindowActiveStatus() {
      if ( ! this.widgetOptionsWindow.widget.length ) { return false; }
      if ( typeof this.active_widgets[ this.widgetOptionsWindow.widget ] === 'undedined'  ) { return false; }

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

      widgetOptionsWindow: {
        animation: 'cptm-animation-flip',
        widget: ''
      },

      currentDraggingWidget: { origin: {}, key: '' },

      // Available Widgets
      available_widgets: {},

      // Active Widgets
      active_widgets: {},

      // Layout
      local_layout: {
        thumbnail: {
          top_right: {
            label: 'Top Right',
            selectedWidgets: [],
          },
          top_left: {
            label: 'Top Left',
            selectedWidgets: [],
          },
          bottom_right: {
            label: 'Bottom Right',
            selectedWidgets: [],
          },
          bottom_left: {
            label: 'Bottom Left',
            selectedWidgets: [],
          },
          avatar: {
            label: 'Avatar',
            selectedWidgets: [],
          },
        },

        body: {
          top: {
            label: 'Body Top',
            selectedWidgets: [],
          },
          bottom: {
            label: 'Body Bottom',
            selectedWidgets: [],
          },
        },

        footer: {
          right: {
            label: 'Footer Right',
            selectedWidgets: [],
          },
          left: {
            label: 'Footer Left',
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

    isTruthyObject( obj ) {
      if ( ! obj && typeof obj !== 'object' ) {
        return false;
      }

      return true;
    },

    importOldData() {
      let value = this.value;

      if ( typeof value === 'string' ) {
        value = JSON.parse( this.value );
      }
      if ( ! this.isTruthyObject( value ) ) { return; }

      let selectedWidgets = [];
      
      // Get Active Widgets Data
      let active_widgets_data = {};
      for ( let section in value ) {
        if ( ! value[ section ] && typeof value[ section ] !== 'object' ) { continue; }

        for ( let area in value[ section ] ) {
          if ( ! value[ section ][ area ] && typeof value[ section ][ area ] !== 'object' ) { continue; }

          for ( let widget of value[ section ][ area ] ) {
            if ( typeof widget.id === 'undefined' ) { continue }
            if ( typeof this.available_widgets[ widget.id ] === 'undefined' ) { continue; }
            if ( typeof this.local_layout[ section ] === 'undefined' ) { continue; }
            if ( typeof this.local_layout[ section ][ area ] === 'undefined' ) { continue; }

            active_widgets_data[ widget.id ] = widget;
            selectedWidgets.push( { section: section, area: area, widget: widget.id } );
          }
        }
      }

      // Load Active Widgets
      for ( let widget_key in active_widgets_data ) {
        if ( typeof this.available_widgets[ widget_key ] === 'undefined' ) { continue; }

        let widgets_template = { ...this.available_widgets[ widget_key ] };

        if ( typeof active_widgets_data[ widget_key ].options !== 'undefined' ) {
          for ( let option_key in widgets_template.options.fields ) {
            if ( typeof active_widgets_data[ widget_key ].options[ option_key ] === 'undefined' ) { continue; }

            widgets_template.options.fields[ option_key ].value = active_widgets_data[ widget_key ].options[ option_key ];
          }
        }
        

        Vue.set( this.active_widgets, widget_key, widgets_template );
      }

      // Load Selected Widgets Data
      for ( let item of selectedWidgets ) {
        let length = this.local_layout[ item.section ][ item.area ].selectedWidgets.length;
        this.local_layout[ item.section ][ item.area ].selectedWidgets.splice( length, 0, item.widget );
      }

      // console.log( {value: this.value, selectedWidgets} );
    },

    importWidgets() {
      if ( ! this.isTruthyObject( this.widgets ) ) { return; }
      this.available_widgets = this.widgets;
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

    widgetIsAccepted( path,  key ) {
      if ( ! path.acceptedWidgets  ) { return true; }
      if ( ! this.isTruthyObject( path.acceptedWidgets )  ) { return true; }
      if ( path.acceptedWidgets.includes( key ) ) { return true; }

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
    },

    updateWidgetOptionsData( data, widget ) {

      if ( typeof this.active_widgets[ widget.widget ] === 'undefined' ) {
        return;
      }

      this.active_widgets[ widget.widget ].fields = data;
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
      this.active_insert_widget_key = current_item_key;
    },

    insertWidget( payload, where ) {

      if ( ! this.isTruthyObject( this.available_widgets[ payload.key ] ) ) {
        return;
      }

      Vue.set( this.active_widgets, payload.key, { ...this.available_widgets[ payload.key ] } );
      Vue.set( where, 'selectedWidgets', payload.selected_widgets );
    },

    closeInsertWindow( widget_insert_window ) {
      this.active_insert_widget_key = '';
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