<template>
  <div class="cptm-builder-section">
    <!-- cptm-options-area -->
    <div class="cptm-options-area" v-if="widgetOptionsWindowActiveStatus">
      <options-window
        :active="widgetOptionsWindowActiveStatus"
        v-bind="widgetOptionsWindow"
        @update="updateWidgetOptionsData($event, widgetOptionsWindow)"
        @close="closeWidgetOptionsWindow()"
      />

    </div>

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
                  :availableWidgets="theAvailableWidgets"
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

              <!-- cptm-card-preview-top-right -->
              <div class="cptm-card-preview-top-right">
                <card-widget-placeholder
                  id="thumbnail_top_right"
                  containerClass="cptm-card-preview-top-right-placeholder cptm-card-dark"
                  :label="local_layout.thumbnail.top_right.label"
                  :availableWidgets="theAvailableWidgets"
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
                  :availableWidgets="theAvailableWidgets"
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
                  :availableWidgets="theAvailableWidgets"
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
              :containerClass="getAvatarPlaceholderClass"
              :label="local_layout.thumbnail.avatar.label"
              :availableWidgets="theAvailableWidgets"
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
            :availableWidgets="theAvailableWidgets"
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
            :availableWidgets="theAvailableWidgets"
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

          <br>
          <card-widget-placeholder
            id="body_excerpt"
            containerClass="cptm-listing-card-preview-body-excerpt-placeholder cptm-card-light"
            v-if="placeholderIsActive( local_layout.body.excerpt )"
            :label="local_layout.body.excerpt.label"
            :availableWidgets="theAvailableWidgets"
            :activeWidgets="active_widgets"
            :acceptedWidgets="local_layout.body.excerpt.acceptedWidgets"
            :selectedWidgets="local_layout.body.excerpt.selectedWidgets"
            :maxWidget="local_layout.body.excerpt.maxWidget"
            :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'body_excerpr' )"
            :widgetDropable="widgetIsDropable( local_layout.body.excerpt )"
            @insert-widget="insertWidget( $event, local_layout.body.excerpt )"
            @drag-widget="onDragStartWidget( $event, local_layout.body.excerpt )"
            @drop-widget="appendWidget( $event, local_layout.body.excerpt )"
            @dragend-widget="onDragEndWidget()"
            @edit-widget="editWidget( $event )"
            @trash-widget="trashWidget( $event, local_layout.body.excerpt )"
            @placeholder-on-drop="handleDropOnPlaceholder( local_layout.body.excerpt )"
            @open-widgets-picker-window="activeInsertWindow( 'body_excerpr' )"
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
            :availableWidgets="theAvailableWidgets"
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
            :availableWidgets="theAvailableWidgets"
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
</template>

<script>
import Vue from 'vue';
import card_builder from './../../mixins/form-fields/card-builder';
import helpers from '../../mixins/helpers';

export default {
  name: "card-builder-grid-view-field",
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

      for ( let section in layout ) {
        output[section] = {};

        if (typeof layout[section] !== "object") {
          continue;
        }

        for ( let section_area in layout[section] ) {
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
            
            // widget_data.options = {};
            let widget_options = this.active_widgets[widget_name].options.fields;

            for ( let option in widget_options ) {
              widget_data[option] = widget_options[ option ].value;
            }

            output[section][section_area].push(widget_data);
          }
        }
      }

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

      // console.log( { available_widgets } );
      return available_widgets;
    },

    widgetOptionsWindowActiveStatus() {
      if ( ! this.widgetOptionsWindow.widget.length ) { return false; }
      if ( typeof this.active_widgets[ this.widgetOptionsWindow.widget ] === 'undedined'  ) { return false; }

      return true;
    },

    _currentDraggingWidget() {
      return this.currentDraggingWidget;
    },

    getAvatarPlaceholderClass() {
      
      let accepted_align_options = [ 'right', 'center', 'left' ];
      let align_option = '';
      let active_widgets = JSON.parse( JSON.stringify( this.active_widgets ) );

      let has_option = false;

      if ( this.isObject( active_widgets ) ) {
        has_option = true;
      }
      if ( has_option && ! active_widgets.user_avatar ) {
        has_option = false;
      }
      if ( has_option && ! active_widgets.user_avatar.options ) {
        has_option = false;
      }
      if ( has_option && ! active_widgets.user_avatar.options.fields ) {
        has_option = false;
      }
      if ( has_option && ! active_widgets.user_avatar.options.fields.align ) {
        has_option = false;
      }

      if ( has_option && ! ( typeof active_widgets.user_avatar.options.fields.align.value === 'string' ) ) {
        has_option = false;
      }

      if ( has_option ) {
        align_option = active_widgets.user_avatar.options.fields.align.value;
      }

      if ( ! accepted_align_options.includes( align_option ) ) {
        align_option = 'center';
      }

      return {
        'cptm-listing-card-author-avatar-placeholder cptm-card-dark-light cptm-mb-20': true,
        'cptm-text-right': ( 'right' === align_option ) ? true : false,
        'cptm-text-center': ( 'center' === align_option ) ? true : false,
        'cptm-text-left': ( 'left' === align_option ) ? true : false,
      }
    },
  },

  data() {
    return {
      active_insert_widget_is_open: false,
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
          excerpt: {
            label: 'Excerpt',
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
      let selectedWidgets = [];

      // Get Active Widgets Data
      let active_widgets_data = {};
      for ( let section in value ) {
        if ( ! value[ section ] && typeof value[ section ] !== 'object' ) { continue; }

        for ( let area in value[ section ] ) {
          if ( ! value[ section ][ area ] && typeof value[ section ][ area ] !== 'object' ) { continue; }

          for ( let widget of value[ section ][ area ] ) {
            if ( typeof widget.widget_name === 'undefined' ) { continue }
            if ( typeof widget.widget_key === 'undefined' ) { continue }
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
      
        for ( let root_option in widgets_template ) {
          if ( 'options' === root_option ) { continue; }
          if ( active_widgets_data[widget_key][root_option] === "undefined" ) { continue; }

          widgets_template[ root_option ] = active_widgets_data[widget_key][root_option];
        }

        let has_widget_options = false;
        if ( widgets_template.options && widgets_template.options.fields ) {
          has_widget_options = true;
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
      
      // let opt = JSON.parse( JSON.stringify( this.active_widgets[ key ].options ) );
      let opt = this.active_widgets[ key ].options;
      this.widgetOptionsWindow = { ...this.widgetOptionsWindowDefault, ...opt };
      this.widgetOptionsWindow.widget = key;

      this.closeInsertWindow();
    },

    updateWidgetOptionsData( data, widget ) {
      return;

      if ( typeof this.active_widgets[ widget.widget ] === 'undefined' ) {
        return;
      }

      if ( typeof this.active_widgets[ widget.widget ].options === 'undefined' ) {
        return;
      }

      Vue.set( this.active_widgets[ widget.widget ].options, 'fields', data );
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

    getActiveInsertWindowStatus( current_item_key ) {

      if ( current_item_key === this.active_insert_widget_key ) {
        return true;
      }

      return false;
    },

    placeholderIsActive( layout ) {
      
      if ( ! this.isObject( layout.show_if ) ) {
        return true;
      }

      let check_condition = this.checkShowIfCondition( { condition: layout.show_if } );
      return check_condition.status;
    }
  },
};
</script>