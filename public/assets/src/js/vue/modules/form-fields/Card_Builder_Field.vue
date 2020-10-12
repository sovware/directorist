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
                  :label="layout.thumbnail.top_left.label"
                  :availableWidgets="available_widgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="layout.thumbnail.top_left.acceptedWidgets"
                  :selectedWidgets="layout.thumbnail.top_left.selectedWidgets"
                  :maxWidget="layout.thumbnail.top_left.maxWidget"
                  :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_top_left' )"
                  @insert-widget="layout.thumbnail.top_left.selectedWidgets = $event.selected_widgets, insertWidget( $event )"
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
                  :label="layout.thumbnail.top_right.label"
                  :availableWidgets="available_widgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="layout.thumbnail.top_right.acceptedWidgets"
                  :selectedWidgets="layout.thumbnail.top_right.selectedWidgets"
                  :maxWidget="layout.thumbnail.top_right.maxWidget"
                  :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_top_right' )"
                  @insert-widget="layout.thumbnail.top_right.selectedWidgets = $event.selected_widgets, insertWidget( $event )"
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
                  :label="layout.thumbnail.bottom_left.label"
                  :availableWidgets="available_widgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="layout.thumbnail.bottom_left.acceptedWidgets"
                  :selectedWidgets="layout.thumbnail.bottom_left.selectedWidgets"
                  :maxWidget="layout.thumbnail.bottom_left.maxWidget"
                  :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_bottom_left' )"
                  @insert-widget="layout.thumbnail.bottom_left.selectedWidgets = $event.selected_widgets, insertWidget( $event )"
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
                  :label="layout.thumbnail.bottom_right.label"
                  :availableWidgets="available_widgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="layout.thumbnail.bottom_right.acceptedWidgets"
                  :selectedWidgets="layout.thumbnail.bottom_right.selectedWidgets"
                  :maxWidget="layout.thumbnail.bottom_right.maxWidget"
                  :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_bottom_right' )"
                  @insert-widget="layout.thumbnail.bottom_right.selectedWidgets = $event.selected_widgets, insertWidget( $event )"
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
              :label="layout.thumbnail.avater.label"
              :availableWidgets="available_widgets"
              :activeWidgets="active_widgets"
              :acceptedWidgets="layout.thumbnail.avater.acceptedWidgets"
              :selectedWidgets="layout.thumbnail.avater.selectedWidgets"
              :maxWidget="layout.thumbnail.avater.maxWidget"
              :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_avater' )"
              @insert-widget="layout.thumbnail.avater.selectedWidgets = $event.selected_widgets, insertWidget( $event )"
              @drag-widget="dragWidget( $event )"
              @edit-widget="editWidget( $event )"
              @trash-widget="trashWidget( $event, 'thumbnail', 'avater' )"
              @open-widgets-picker-window="activeInsertWindow( 'thumbnail_avater' )"
              @close-widgets-picker-window="closeInsertWindow()"
            />
          </div>

          <card-widget-placeholder
            containerClass="cptm-listing-card-preview-body-placeholder"
            :label="layout.middle.body.label"
            :availableWidgets="available_widgets"
            :activeWidgets="active_widgets"
            :acceptedWidgets="layout.middle.body.acceptedWidgets"
            :selectedWidgets="layout.middle.body.selectedWidgets"
            :maxWidget="layout.middle.body.maxWidget"
            :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_body_contents' )"
            @insert-widget="layout.middle.body.selectedWidgets = $event.selected_widgets, insertWidget( $event )"
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
            :label="layout.footer.left.label"
            :availableWidgets="available_widgets"
            :activeWidgets="active_widgets"
            :acceptedWidgets="layout.footer.left.acceptedWidgets"
            :selectedWidgets="layout.footer.left.selectedWidgets"
            :maxWidget="layout.footer.left.maxWidget"
            :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_footer_left' )"
            @insert-widget="layout.footer.left.selectedWidgets = $event.selected_widgets, insertWidget( $event )"
            @drag-widget="dragWidget( $event )"
            @edit-widget="editWidget( $event )"
            @trash-widget="trashWidget( $event, 'middle', 'body' )"
            @open-widgets-picker-window="activeInsertWindow( 'thumbnail_footer_left' )"
            @close-widgets-picker-window="closeInsertWindow()"
          />

          <!-- cptm-listing-card-preview-footer-right-placeholder -->
          <card-widget-placeholder
            containerClass="cptm-listing-card-preview-footer-right-placeholder"
            :label="layout.footer.right.label"
            :availableWidgets="available_widgets"
            :activeWidgets="active_widgets"
            :acceptedWidgets="layout.footer.right.acceptedWidgets"
            :selectedWidgets="layout.footer.right.selectedWidgets"
            :maxWidget="layout.footer.right.maxWidget"
            :showWidgetsPickerWindow="getActiveInsertWindowStatus( 'thumbnail_footer_right' )"
            @insert-widget="layout.footer.right.selectedWidgets = $event.selected_widgets, insertWidget( $event )"
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
  props: [],

  created() {
    // console.log( this.output_data );
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
      active_insert_widget_key: 'thumbnail_top_right',

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
      available_widgets: {
        listing_title: {
          type: "title",
          id: "listing_title",
          label: "Listing Title",
          icon: '<span class="uil uil-text-fields"></span>',
          options: {
            title: "Listing Title Settings",
            fields: {
              label: {
                type: "text",
                label: "Label",
                value: "text",
              },
            },
          },
        },

        open_close_badge: {
          type: "badge",
          id: "open_close_badge",
          label: "Open Now",
          icon: '<span class="uil uil-text-fields"></span>',
          options: {
            title: "Open/Closed Settings",
            fields: {
              open_label: {
                type: "text",
                label: "Open Badge Text",
                value: "Open Now",
              },
              close_label: {
                type: "text",
                label: "Close Badge Text",
                value: "Close Now",
              },
            },
          },
        },

        popular_badge: {
          type: "badge",
          id: "popular_badge",
          label: "Popular",
          icon: '<span class="uil uil-text-fields"></span>',
          options: {
            title: "Popular Badge Settings",
            fields: {
              popular_label: {
                type: "text",
                label: "Popular Badge Text",
                value: "Popular",
              },
              listing_popular_by: {
                type: 'select',
                label: "Popular Based on",
                value: "view_count",
                options: [
                  {value: 'view_count', label: 'View Count'},
                  {value: 'average_rating', label: 'Average Rating'},
                  {value: 'both', label: 'Both'},
                ],
              },
              views_for_popular: {
                type: "number",
                label: "Threshold in Views Count",
                value: "5",
                show_if: [
                  {
                    condition: [
                      {key: 'listing_popular_by', value: 'view_count'},
                      {key: 'listing_popular_by', value: 'both'},
                    ]
                  }
                ],
              },
              count_loggedin_user: {
                type: "toggle",
                label: "Count Logged-in User View",
                value: "",
              },
            },
          },
        },

        featured_badge: {
          type: "badge",
          id: "featured_badge",
          label: "Featured",
          icon: '<span class="uil uil-text-fields"></span>',
          options: {
            title: "Featured Badge Settings",
            fields: {
              featured_label: {
                type: "text",
                label: "Featured Badge Text",
                value: "Featured",
              },
            },
          },
        },

        new_badge: {
          type: "badge",
          id: "new_badge",
          label: "New",
          icon: '<span class="uil uil-text-fields"></span>',
          options: {
            title: "New Badge Settings",
            fields: {
              new_label: {
                type: "text",
                label: "New Badge Text",
                value: "New",
              },
              new_badge_duration: {
                type: "number",
                label: "New Badge Duration in Days",
                value: "3",
              },
            },
          },
        },

        favorite_badge: {
          type: "badge",
          id: "favorite_badge",
          label: "Favorite",
          icon: '<span class="uil uil-text-fields"></span>',
          options: {
            title: "Favorite Settings",
            fields: {
              icon: {
                type: "icon",
                label: "Icon",
                value: "fa fa-heart",
              },
            },
          },
        },

        category: {
          type: "badge",
          id: "category",
          label: "Category",
          icon: '<span class="uil uil-text-fields"></span>',
          options: {
            title: "Category Settings",
            fields: {
              icon: {
                type: "icon",
                label: "Icon",
                value: "fa fa-folder",
              },
            },
          },
        },

        user_avater: {
          type: "avater",
          id: "user_avater",
          label: "User Avater",
          icon: '<span class="uil uil-text-fields"></span>',
          options: {
            title: "Category Settings",
            fields: {
              align: {
                type: "select",
                label: "Align",
                value: "center",
              },
            },
          },
        },
      },

      // Active Widgets
      active_widgets: {},

      // Layout
      layout: {
        thumbnail: {
          top_right: {
            label: 'Top Right',
            maxWidget: 2,
            maxWidgetInfoText: "Up to __DATA__ item{s} can be added",
            acceptedWidgets: ["open_close_badge", "favorite_badge", "new_badge", "popular_badge", "featured_badge"],
            selectedWidgets: [],
          },
          top_left: {
            label: 'Top Left',
            maxWidget: 2,
            maxWidgetInfoText: "Up to __DATA__ item{s} can be added",
            acceptedWidgets: ["open_close_badge", "favorite_badge", "new_badge", "popular_badge", "featured_badge"],
            selectedWidgets: [],
          },
          bottom_right: {
            label: 'Bottom Right',
            maxWidget: 2,
            maxWidgetInfoText: "Up to __DATA__ item{s} can be added",
            acceptedWidgets: ["open_close_badge", "favorite_badge", "new_badge", "popular_badge", "featured_badge"],
            selectedWidgets: [],
          },
          bottom_left: {
            label: 'Bottom Left',
            maxWidget: 2,
            maxWidgetInfoText: "Up to __DATA__ item{s} can be added",
            acceptedWidgets: ["open_close_badge", "favorite_badge", "new_badge", "popular_badge", "featured_badge"],
            selectedWidgets: [],
          },
          avater: {
            label: 'Avater',
            maxWidget: 1,
            maxWidgetInfoText: "Up to 1 item can be added",
            acceptedWidgets: ["user_avater"],
            selectedWidgets: [],
          },
        },

        middle: {
          body: {
            label: 'Body',
            maxWidget: 2,
            widget_groups: [
              { label: 'Preset', widgets: ['listing_title'] },
              { label: 'Custom', widgets: ['listing_title'] },
            ],
            acceptedWidgets: ["listing_title"],
            selectedWidgets: [],
          },
        },

        footer: {
          right: {
            label: 'Footer Right',
            maxWidget: 2,
            acceptedWidgets: ["category", "favorite_badge"],
            selectedWidgets: [],
          },
          left: {
            label: 'Footer Left',
            maxWidget: 1,
            acceptedWidgets: ["category", "favorite_badge"],
            selectedWidgets: [],
          },
        },
      },
    };
  },

  methods: {
    dragWidget( key, path ) {
      console.log( 'dragWidget', { key, path } );
    },

    editWidget( key ) {
      // console.log( 'editWidget', { key } );
      this.widgetOptionsWindow = { ...this.widgetOptionsWindowDefault, ...this.active_widgets[ key ].options };
      this.widgetOptionsWindow.widget = key;
    },

    updateWidgetOptionsData( data, widget ) {
      // console.log( { data, widget } );

      if ( typeof this.active_widgets[ widget.widget ] === 'undefined' ) {
        return;
      }

      this.active_widgets[ widget.widget ].fields = data;

      // console.log( this.active_widgets );

      this.$emit( 'update', this.output_data );
    },

    closeWidgetOptionsWindow() {
      this.widgetOptionsWindow = this.widgetOptionsWindowDefault;
    },

    trashWidget( key, section, area ) {
      if ( ! this.layout.[ section ][ area ].selectedWidgets.includes( key ) ) { return; }
      
      let index = this.layout.[ section ][ area ].selectedWidgets.indexOf( key );
      this.layout.[ section ][ area ].selectedWidgets.splice( index, 1 );

      if ( typeof this.active_widgets[ key ] === 'undefined' ) { return; }
      delete this.active_widgets[ key ];

      if ( key === this.widgetOptionsWindow.widget ) {
        this.closeWidgetOptionsWindow();
      }
    },

    activeInsertWindow( current_item_key ) {
      // console.log( current_item_key, this.active_insert_widget_key );
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