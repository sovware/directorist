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
                  containerClass="cptm-card-preview-top-left-placeholder"
                  :label="local_layout.thumbnail.top_left.label"
                  :availableWidgets="available_widgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="local_layout.thumbnail.top_left.acceptedWidgets"
                  :selectedWidgets="local_layout.thumbnail.top_left.selectedWidgets"
                  :maxWidget="local_layout.thumbnail.top_left.maxWidget"
                  :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_top_left' )"
                  @insert-widget="local_layout.thumbnail.top_left.selectedWidgets = $event.selected_widgets, insertWidget( $event )"
                  @drag-widget="dragWidget( $event )"
                  @edit-widget="editWidget( $event )"
                  @trash-widget="trashWidget( $event, 'thumbnail', 'top_left' )"
                  @open-widgets-picker-window="activeInsertWindow( 'thumbnail_top_left' )"
                  @close-widgets-picker-window="closeInsertWindow()"
                />
              </div>

              <!-- cptm-card-preview-top-rignt -->
              <div class="cptm-card-preview-top-rignt">
                <card-widget-placeholder
                  containerClass="cptm-card-preview-top-rignt-placeholder"
                  :label="local_layout.thumbnail.top_right.label"
                  :availableWidgets="available_widgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="local_layout.thumbnail.top_right.acceptedWidgets"
                  :selectedWidgets="local_layout.thumbnail.top_right.selectedWidgets"
                  :maxWidget="local_layout.thumbnail.top_right.maxWidget"
                  :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_top_right' )"
                  @insert-widget="local_layout.thumbnail.top_right.selectedWidgets = $event.selected_widgets, insertWidget( $event )"
                  @drag-widget="dragWidget( $event )"
                  @edit-widget="editWidget( $event )"
                  @trash-widget="trashWidget( $event, 'thumbnail', 'top_right' )"
                  @open-widgets-picker-window="activeInsertWindow( 'thumbnail_top_right' )"
                  @close-widgets-picker-window="closeInsertWindow()"
                />
              </div>

              <!-- cptm-card-preview-bottom-left -->
              <div class="cptm-card-preview-bottom-left">
                <card-widget-placeholder
                  containerClass="cptm-card-preview-bottom-left-placeholder"
                  :label="local_layout.thumbnail.bottom_left.label"
                  :availableWidgets="available_widgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="local_layout.thumbnail.bottom_left.acceptedWidgets"
                  :selectedWidgets="local_layout.thumbnail.bottom_left.selectedWidgets"
                  :maxWidget="local_layout.thumbnail.bottom_left.maxWidget"
                  :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_bottom_left' )"
                  @insert-widget="local_layout.thumbnail.bottom_left.selectedWidgets = $event.selected_widgets, insertWidget( $event )"
                  @drag-widget="dragWidget( $event )"
                  @edit-widget="editWidget( $event )"
                  @trash-widget="trashWidget( $event, 'thumbnail', 'bottom_left' )"
                  @open-widgets-picker-window="activeInsertWindow( 'thumbnail_bottom_left' )"
                  @close-widgets-picker-window="closeInsertWindow()"
                />
              </div>

              <!-- cptm-card-preview-bottom-right -->
              <div class="cptm-card-preview-bottom-right">
                <card-widget-placeholder
                  containerClass="cptm-card-preview-bottom-right-placeholder"
                  :label="local_layout.thumbnail.bottom_right.label"
                  :availableWidgets="available_widgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="local_layout.thumbnail.bottom_right.acceptedWidgets"
                  :selectedWidgets="local_layout.thumbnail.bottom_right.selectedWidgets"
                  :maxWidget="local_layout.thumbnail.bottom_right.maxWidget"
                  :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_bottom_right' )"
                  @insert-widget="local_layout.thumbnail.bottom_right.selectedWidgets = $event.selected_widgets, insertWidget( $event )"
                  @drag-widget="dragWidget( $event )"
                  @edit-widget="editWidget( $event )"
                  @trash-widget="trashWidget( $event, 'thumbnail', 'bottom_right' )"
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
              containerClass="cptm-listing-card-author-avatar-placeholder"
              :label="local_layout.thumbnail.avater.label"
              :availableWidgets="available_widgets"
              :activeWidgets="active_widgets"
              :acceptedWidgets="local_layout.thumbnail.avater.acceptedWidgets"
              :selectedWidgets="local_layout.thumbnail.avater.selectedWidgets"
              :maxWidget="local_layout.thumbnail.avater.maxWidget"
              :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_avater' )"
              @insert-widget="local_layout.thumbnail.avater.selectedWidgets = $event.selected_widgets, insertWidget( $event )"
              @drag-widget="dragWidget( $event )"
              @edit-widget="editWidget( $event )"
              @trash-widget="trashWidget( $event, 'thumbnail', 'avater' )"
              @open-widgets-picker-window="activeInsertWindow( 'thumbnail_avater' )"
              @close-widgets-picker-window="closeInsertWindow()"
            />
          </div>

          <card-widget-placeholder
            containerClass="cptm-listing-card-preview-body-placeholder"
            :label="local_layout.middle.body.label"
            :availableWidgets="available_widgets"
            :activeWidgets="active_widgets"
            :acceptedWidgets="local_layout.middle.body.acceptedWidgets"
            :selectedWidgets="local_layout.middle.body.selectedWidgets"
            :maxWidget="local_layout.middle.body.maxWidget"
            :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_body_contents' )"
            @insert-widget="local_layout.middle.body.selectedWidgets = $event.selected_widgets, insertWidget( $event )"
            @drag-widget="dragWidget( $event )"
            @edit-widget="editWidget( $event )"
            @trash-widget="trashWidget( $event, 'middle', 'body' )"
            @open-widgets-picker-window="activeInsertWindow( 'thumbnail_body_contents' )"
            @close-widgets-picker-window="closeInsertWindow()"
          />
        </div>

        <!-- cptm-listing-card-preview-footer -->
        <div class="cptm-listing-card-preview-footer">
          <!-- cptm-listing-card-preview-footer-left-placeholder -->
          <card-widget-placeholder
            containerClass="cptm-listing-card-preview-footer-left-placeholder"
            :label="local_layout.footer.left.label"
            :availableWidgets="available_widgets"
            :activeWidgets="active_widgets"
            :acceptedWidgets="local_layout.footer.left.acceptedWidgets"
            :selectedWidgets="local_layout.footer.left.selectedWidgets"
            :maxWidget="local_layout.footer.left.maxWidget"
            :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_footer_left' )"
            @insert-widget="local_layout.footer.left.selectedWidgets = $event.selected_widgets, insertWidget( $event )"
            @drag-widget="dragWidget( $event )"
            @edit-widget="editWidget( $event )"
            @trash-widget="trashWidget( $event, 'middle', 'body' )"
            @open-widgets-picker-window="activeInsertWindow( 'thumbnail_footer_left' )"
            @close-widgets-picker-window="closeInsertWindow()"
          />

          <!-- cptm-listing-card-preview-footer-right-placeholder -->
          <card-widget-placeholder
            containerClass="cptm-listing-card-preview-footer-right-placeholder"
            :label="local_layout.footer.right.label"
            :availableWidgets="available_widgets"
            :activeWidgets="active_widgets"
            :acceptedWidgets="local_layout.footer.right.acceptedWidgets"
            :selectedWidgets="local_layout.footer.right.selectedWidgets"
            :maxWidget="local_layout.footer.right.maxWidget"
            :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_footer_right' )"
            @insert-widget="local_layout.footer.right.selectedWidgets = $event.selected_widgets, insertWidget( $event )"
            @drag-widget="dragWidget( $event )"
            @edit-widget="editWidget( $event )"
            @trash-widget="trashWidget( $event, 'middle', 'body' )"
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
export default {
  name: "card-builder",
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

  computed: {
    output_data() {
      let output = {};
      let layout = JSON.parse(JSON.stringify(this.layout));

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
            let widget_options_data = {};

            if (typeof this.active_widgets[widget_name].type !== "undefined") {
              widget_options_data["type"] = this.active_widgets[
                widget_name
              ].type;
            }

            if (typeof this.active_widgets[widget_name].id !== "undefined") {
              widget_options_data["id"] = this.active_widgets[widget_name].id;
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
            maxWidget: 1,
            selectedWidgets: [],
          },
        },

        middle: {
          body: {
            label: 'Body',
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
    },

    isTruthyObject( obj ) {
      if ( ! obj && typeof obj !== 'object' ) {
        return false;
      }

      return true;
    },

    importWidgets() {
      if ( ! this.isTruthyObject( this.widgets ) ) { return; }
      this.available_widgets = this.widgets;
    },

    importLayout() {
      if (  ! this.isTruthyObject( this.layout ) ) {
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

    dragWidget( key, path ) {
      console.log( 'dragWidget', { key, path } );
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

      this.$emit( 'update', this.output_data );
    },

    closeWidgetOptionsWindow() {
      this.widgetOptionsWindow = this.widgetOptionsWindowDefault;
    },

    trashWidget( key, section, area ) {
      if ( ! this.local_layout[ section ][ area ].selectedWidgets.includes( key ) ) { return; }
      
      let index = this.local_layout[ section ][ area ].selectedWidgets.indexOf( key );
      this.local_layout[ section ][ area ].selectedWidgets.splice( index, 1 );

      if ( typeof this.active_widgets[ key ] === 'undefined' ) { return; }
      delete this.active_widgets[ key ];

      if ( key === this.widgetOptionsWindow.widget ) {
        this.closeWidgetOptionsWindow();
      }
    },

    activeInsertWindow( current_item_key ) {
      this.active_insert_widget_key = current_item_key;
    },

    insertWidget( payload ) {
      this.active_widgets[ payload.key ] = { ...this.available_widgets[ payload.key ] };
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