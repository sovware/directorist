<template>
  <div class="cptm-builder-section">  
    <!-- cptm-preview-area -->
    <div class="cptm-preview-area">
      <div class="cptm-card-preview-widget cptm-card-list-view">
        <!-- cptm-listing-card-preview-header -->
        <div class="cptm-listing-card-preview-header">
          <div class="cptm-card-preview-thumbnail">
            <div class="cptm-card-preview-thumbnail-overlay">
              <!-- cptm-card-preview-top-rignt -->
              <div class="cptm-card-preview-top-rignt">
                <card-widget-placeholder
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

              <div class="cptm-card-preview-thumbnail-bg">
                  <span class="uil uil-scenery"></span>
              </div>
            </div>
          </div>
        </div>

        <div class="cptm-listing-card-content">
          <!-- cptm-listing-card-preview-body -->
          <div class="cptm-listing-card-preview-body">
            <!-- cptm-listing-card-author-avatar -->
            <div class="cptm-listing-card-body-header">
              <div class="cptm-listing-card-body-header-left">
                <card-widget-placeholder
                  containerClass="cptm-listing-card-body-header-title-placeholder cptm-mb-10 cptm-card-light"
                  :label="local_layout.body.header_top.label"
                  :availableWidgets="available_widgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="local_layout.body.header_top.acceptedWidgets"
                  :selectedWidgets="local_layout.body.header_top.selectedWidgets"
                  :maxWidget="local_layout.body.header_top.maxWidget"
                  :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'body_header_top' )"
                  :widgetDropable="widgetIsDropable( local_layout.body.header_top )"
                  @insert-widget="insertWidget( $event, local_layout.body.header_top )"
                  @drag-widget="onDragStartWidget( $event, local_layout.body.header_top )"
                  @drop-widget="appendWidget( $event, local_layout.body.header_top )"
                  @dragend-widget="onDragEndWidget()"
                  @edit-widget="editWidget( $event )"
                  @trash-widget="trashWidget( $event, local_layout.body.header_top )"
                  @placeholder-on-drop="handleDropOnPlaceholder( local_layout.body.header_top )"
                  @open-widgets-picker-window="activeInsertWindow( 'body_header_top' )"
                  @close-widgets-picker-window="closeInsertWindow()"
                />

                <card-widget-placeholder
                  containerClass="cptm-listing-card-body-header-badge-placeholder cptm-mb-10 cptm-card-light"
                  :label="local_layout.body.header_bottom.label"
                  :availableWidgets="available_widgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="local_layout.body.header_bottom.acceptedWidgets"
                  :selectedWidgets="local_layout.body.header_bottom.selectedWidgets"
                  :maxWidget="local_layout.body.header_bottom.maxWidget"
                  :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'body_header_bottom' )"
                  :widgetDropable="widgetIsDropable( local_layout.body.header_bottom )"
                  @insert-widget="insertWidget( $event, local_layout.body.header_bottom )"
                  @drag-widget="onDragStartWidget( $event, local_layout.body.header_bottom )"
                  @drop-widget="appendWidget( $event, local_layout.body.header_bottom )"
                  @dragend-widget="onDragEndWidget()"
                  @edit-widget="editWidget( $event )"
                  @trash-widget="trashWidget( $event, local_layout.body.header_bottom )"
                  @placeholder-on-drop="handleDropOnPlaceholder( local_layout.body.header_bottom )"
                  @open-widgets-picker-window="activeInsertWindow( 'body_header_bottom' )"
                  @close-widgets-picker-window="closeInsertWindow()"
                />
              </div>

              <div class="cptm-listing-card-body-header-right">
                <card-widget-placeholder
                  containerClass="cptm-listing-card-body-header-actions-placeholder cptm-card-light"
                  :label="local_layout.body.header_right.label"
                  :availableWidgets="available_widgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="local_layout.body.header_right.acceptedWidgets"
                  :selectedWidgets="local_layout.body.header_right.selectedWidgets"
                  :maxWidget="local_layout.body.header_right.maxWidget"
                  :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'body_header_right' )"
                  :widgetDropable="widgetIsDropable( local_layout.body.header_right )"
                  @insert-widget="insertWidget( $event, local_layout.body.header_right )"
                  @drag-widget="onDragStartWidget( $event, local_layout.body.header_right )"
                  @drop-widget="appendWidget( $event, local_layout.body.header_right )"
                  @dragend-widget="onDragEndWidget()"
                  @edit-widget="editWidget( $event )"
                  @trash-widget="trashWidget( $event, local_layout.body.header_right )"
                  @placeholder-on-drop="handleDropOnPlaceholder( local_layout.body.header_right )"
                  @open-widgets-picker-window="activeInsertWindow( 'body_header_right' )"
                  @close-widgets-picker-window="closeInsertWindow()"
                />
              </div>
            </div>

            <card-widget-placeholder
              containerClass="cptm-listing-card-preview-body-placeholder cptm-card-light"
              :label="local_layout.body.body_contents.label"
              :availableWidgets="available_widgets"
              :activeWidgets="active_widgets"
              :acceptedWidgets="local_layout.body.body_contents.acceptedWidgets"
              :selectedWidgets="local_layout.body.body_contents.selectedWidgets"
              :maxWidget="local_layout.body.body_contents.maxWidget"
              :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_body_contents' )"
              :widgetDropable="widgetIsDropable( local_layout.body.body_contents )"
              @insert-widget="insertWidget( $event, local_layout.body.body_contents )"
              @drag-widget="onDragStartWidget( $event, local_layout.body.body_contents )"
              @drop-widget="appendWidget( $event, local_layout.body.body_contents )"
              @dragend-widget="onDragEndWidget()"
              @edit-widget="editWidget( $event )"
              @trash-widget="trashWidget( $event, local_layout.body.body_contents )"
              @placeholder-on-drop="handleDropOnPlaceholder( local_layout.body.body_contents )"
              @open-widgets-picker-window="activeInsertWindow( 'thumbnail_body_contents' )"
              @close-widgets-picker-window="closeInsertWindow()"
            />
          </div>

          <!-- cptm-listing-card-preview-footer -->
          <div class="cptm-listing-card-preview-footer">
            <!-- cptm-listing-card-preview-footer-left-placeholder -->
            <card-widget-placeholder
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
  name: "card-builder-list-view-field",
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
      let layout = this.local_layout ;

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
          if (typeof layout[section][section_area].selectedWidgets !== "object") {
            continue;
          }

          for (let widget in layout[section][section_area].selectedWidgets) {
            const widget_name = layout[section][section_area].selectedWidgets[widget];

            if (typeof this.active_widgets[widget_name] !== "object") {
              continue;
            }
            if (typeof this.active_widgets[widget_name].options !== "object") {
              continue;
            }

            let widget_options = this.active_widgets[widget_name].options.fields;
            let widget_options_data = { id: widget_name };

            if (typeof this.active_widgets[widget_name].type !== "undefined") {
              widget_options_data["type"] = this.active_widgets[ widget_name ].type;
            }

            for (let option in widget_options) {
              widget_options_data[option] = widget_options[option].value;
            }

            output[section][section_area].push(widget_options_data);
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
          avater: {
            label: 'Avater',
            selectedWidgets: [],
          },
        },

        body: { 
          header_top: {
            label: 'Header Top',
            selectedWidgets: [],
          },
          header_right: {
            label: 'Header Right',
            selectedWidgets: [],
          },
          header_bottom: {
            label: 'Header Bottom',
            selectedWidgets: [],
          },
          body_contents: {
            label: 'Body Contents',
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
      if ( ! this.isTruthyObject( this.value ) ) { return; }

      let selectedWidgets = [];
      
      // Get Active Widgets Data
      let active_widgets_data = {};
      for ( let section in this.value ) {
        if ( ! this.value[ section ] && typeof this.value[ section ] !== 'object' ) { continue }

        for ( let area in this.value[ section ] ) {
          if ( ! this.value[ section ][ area ] && typeof this.value[ section ][ area ] !== 'object' ) { continue }

          for ( let widget of this.value[ section ][ area ] ) {
            if ( typeof widget.id === 'undefined' ) { continue }

            active_widgets_data[ widget.id ] = widget;
            selectedWidgets.push( { section: section, area: area, widget: widget.id } );
          }
        }
      }

      // Load Active Widgets
      for ( let widget_key in active_widgets_data ) {
        if ( typeof this.available_widgets[ widget_key ] === 'undefined' ) { continue; }

        let widgets_template = { ...this.available_widgets[ widget_key ] };

        for ( let option_key in widgets_template.options.fields ) {
          if ( typeof active_widgets_data[ widget_key ][ option_key ] === 'undefined' ) { continue; }

          widgets_template.options.fields[ option_key ].value = active_widgets_data[ widget_key ][ option_key ];
        }

        Vue.set( this.active_widgets, widget_key, widgets_template );
      }

      // Load Selected Widgets Data
      for ( let item of selectedWidgets ) {
        let length = this.local_layout[ item.section ][ item.area ].selectedWidgets.length;
        this.local_layout[ item.section ][ item.area ].selectedWidgets.splice( length, 0, item.widget );
      }
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
      const dest_index = dest_path.selectedWidgets.indexOf( dest_key ) + 1;
      const key        = this.currentDraggingWidget.key;
      const from       = this.currentDraggingWidget.origin.selectedWidgets;

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