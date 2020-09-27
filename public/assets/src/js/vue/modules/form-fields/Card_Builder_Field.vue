<template>
  <div class>
    <div class="cptm-builder-section">
      <!-- cptm-preview-area -->
      <div class="cptm-preview-area">
        <div class="cptm-card-preview-widget">
          <!-- cptm-listing-card-preview-header -->
          <div class="cptm-listing-card-preview-header">
            <div class="cptm-card-preview-thumbnail">
              <div class="cptm-card-preview-thumbnail-overlay">

                <!-- cptm-card-preview-top-left -->
                <div class="cptm-card-preview-top-left">
                  <div class="cptm-card-preview-top-left-placeholder cptm-placeholder-blcok">
                    <p class="cptm-placeholder-label">{{ layout.thumbnail.top_right.label }}</p>

                    <div class="cptm-widget-insert-area">
                      <div class="cptm-widget-insert-wrap">
                        <div class="cptm-widget-insert-modal-container">
                          <widgets-window 
                            v-bind="layout.thumbnail.top_right"
                            :availableWidgets="available_widgets"
                            :active="getActiveInsertWindowStatus( 'thumbnail_top_right' )"
                            @close="closeInsertWindow()"
                          />
                        </div>

                        <a href="#" class="cptm-widget-insert-link" @click.prevent="activeInsertWindow( 'thumbnail_top_right' )">
                          <span class="fa fa-plus"></span>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- cptm-card-preview-top-rignt -->
                <div class="cptm-card-preview-top-rignt">
                  <div class="cptm-card-preview-top-rignt-placeholder cptm-placeholder-blcok">
                    <p class="cptm-placeholder-label hide">Top Rignt</p>

                    <div class="cptm-widget-insert-area">
                      <div class="cptm-widget-insert-wrap">
                        <div class="cptm-widget-insert-modal-container">
                          <widgets-window :active="false" :bottom-achhor="true"/>
                        </div>

                        <a href="#" class="cptm-widget-insert-link">
                          <span class="fa fa-plus"></span>
                        </a>
                      </div>
                    </div>

                    <div class="cptm-widget-badge">Badge Text</div>
                    <!-- <div class="cptm-widget-badge">Badge Text 2</div> -->
                  </div>
                </div>

                <!-- cptm-card-preview-bottom-left -->
                <div class="cptm-card-preview-bottom-left">
                  <div class="cptm-card-preview-bottom-left-placeholder cptm-placeholder-blcok">
                    <p class="cptm-placeholder-label">Bottom Left</p>

                    <div class="cptm-widget-insert-area">
                      <div class="cptm-widget-insert-wrap">
                        <div class="cptm-widget-insert-modal-container">
                          <widgets-window :args="{ active: false, bottom_achhor: true }"/>
                        </div>

                        <a href="#" class="cptm-widget-insert-link">
                          <span class="fa fa-plus"></span>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- cptm-card-preview-bottom-right -->
                <div class="cptm-card-preview-bottom-right">
                  <div class="cptm-card-preview-bottom-right-placeholder cptm-placeholder-blcok">
                    <p class="cptm-placeholder-label">Bottom Rignt</p>

                    <div class="cptm-widget-insert-area">
                      <div class="cptm-widget-insert-wrap">
                        <div class="cptm-widget-insert-modal-container">
                          <widgets-window :args="{ active: false, bottom_achhor: true }"/>
                        </div>

                        <a href="#" class="cptm-widget-insert-link">
                          <span class="fa fa-plus"></span>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="cptm-card-preview-thumbnail-bg">
                    <span class="fa fa-picture-o"></span>
                </div>
              </div>
            </div>
          </div>

          <!-- cptm-listing-card-preview-body -->
          <div class="cptm-listing-card-preview-body">
            <div class="cptm-listing-card-preview-body-placeholder cptm-placeholder-blcok">
              <p class="cptm-placeholder-label">Body Contents</p>

              <div class="cptm-widget-insert-area">
                <div class="cptm-widget-insert-wrap">
                  <div class="cptm-widget-insert-modal-container">
                    <widgets-window :args="{ active: false, bottom_achhor: true }"/>
                  </div>

                  <a href="#" class="cptm-widget-insert-link">
                    <span class="fa fa-plus"></span>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- cptm-listing-card-preview-footer -->
          <div class="cptm-listing-card-preview-footer">
            <!-- cptm-listing-card-preview-footer-left-placeholder -->
            <div class="cptm-listing-card-preview-footer-left-placeholder cptm-placeholder-blcok">
              <p class="cptm-placeholder-label">Footer Left</p>
              <div class="cptm-widget-insert-area">
                <div class="cptm-widget-insert-wrap">
                  <div class="cptm-widget-insert-modal-container">
                    <widgets-window :args="{ active: false, bottom_achhor: true }"/>
                  </div>

                  <a href="#" class="cptm-widget-insert-link">
                    <span class="fa fa-plus"></span>
                  </a>
                </div>
              </div>
            </div>


            <!-- cptm-listing-card-preview-footer-right-placeholder -->
            <div class="cptm-listing-card-preview-footer-right-placeholder cptm-placeholder-blcok">
              <p class="cptm-placeholder-label">Footer Right</p>

              <div class="cptm-widget-insert-area">
                <div class="cptm-widget-insert-wrap">
                  <div class="cptm-widget-insert-modal-container">
                    <widgets-window :args="{ active: false, bottom_achhor: true }"/>
                  </div>

                  <a href="#" class="cptm-widget-insert-link">
                    <span class="fa fa-plus"></span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="cptm-options-area">
        <widgets-window :args="{ active: true, animation: 'cptm-animation-flip' }"/>
      </div>
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
          if (typeof layout[section][section_area].widgets !== "object") {
            continue;
          }

          for (let widget in layout[section][section_area].widgets) {
            const widget_name = layout[section][section_area].widgets[widget];

            if (typeof this.active_widgets[widget_name] !== "object") {
              continue;
            }
            if (typeof this.active_widgets[widget_name].options !== "object") {
              continue;
            }

            let widget_options = this.active_widgets[widget_name].options;
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
  },

  data() {
    return {
      active_insert_widget_key: 'thumbnail_top_right',

      available_widgets: {
        listing_title: {
          type: "title",
          id: "listing_title",
          label: "Listing Title",
          icon: "",
          options: {
            label: {
              type: "text",
              label: "Label",
              value: "text",
            },
          },
        },

        open_now_badge: {
          type: "badge",
          id: "open_now_badge",
          label: "Badge",
          icon: "",
          options: {
            label: {
              type: "text",
              label: "Label",
              value: "Open Now",
            },
          },
        },

        favorite_badge: {
          type: "badge",
          id: "favorite_badge",
          label: "Favorite",
          icon: "",
          options: {
            icon: {
              type: "icon",
              label: "Icon",
              value: "fa fa-heart",
            },
          },
        },

        category: {
          type: "category",
          id: "category",
          label: "Category",
          icon: "",
          options: {
            icon: {
              type: "icon",
              label: "Icon",
              value: "fa fa-folder",
            },
          },
        },

        user_avater: {
          type: "avater",
          id: "user_avater",
          label: "User Avater",
          icon: "",
          options: {
            align: {
              type: "select",
              label: "Align",
              value: "center",
            },
          },
        },
      },

      active_widgets: {
        listing_title: {
          type: "title",
          id: "listing_title",
          label: "Listing Title",
          icon: "",
          options: {
            label: {
              type: "text",
              label: "Label",
              value: "text",
            },
          },
        },

        open_now_badge: {
          type: "badge",
          id: "open_now_badge",
          label: "Badge",
          icon: "",
          options: {
            label: {
              type: "text",
              label: "Label",
              value: "Open Now",
            },
          },
        },

        favorite_badge: {
          type: "badge",
          id: "favorite_badge",
          label: "Favorite",
          icon: "",
          options: {
            icon: {
              type: "icon",
              label: "Icon",
              value: "fa fa-heart",
            },
          },
        },

        category: {
          type: "category",
          id: "category",
          label: "Category",
          icon: "",
          options: {
            icon: {
              type: "icon",
              label: "Icon",
              value: "fa fa-folder",
            },
          },
        },

        user_avater: {
          type: "avater",
          id: "user_avater",
          label: "User Avater",
          icon: "",
          options: {
            align: {
              type: "select",
              label: "Align",
              value: "center",
            },
          },
        },
      },

      layout: {
        thumbnail: {
          top_right: {
            label: 'Top Right',
            max_widget: 2,
            max_widget_info_text: "Up to 2 items can be added",
            accepted_widgets: ["open_now_badge", "favorite_badge"],
            active_widgets: ["open_now_badge"],
            bottomAchhor: true,
          },
          top_left: {
            label: 'Top Left',
            widget_insert_window: { active: false, bottom_achhor: true },
            max_widget: 2,
            max_widget_info_text: "Up to 2 items can be added",
            accepted_widgets: ["open_now_badge", "favorite_badge"],
            widgets: ["favorite_badge", "favorite_badge"],
          },
          bottom_right: {
            label: 'Bottom Right',
            widget_insert_window: { active: false, bottom_achhor: true },
            max_widget: 2,
            max_widget_info_text: "Up to 2 items can be added",
            accepted_widgets: ["open_now_badge", "favorite_badge"],
            widgets: ["open_now_badge", "favorite_badge"],
          },
          bottom_left: {
            label: 'Bottom Left',
            widget_insert_window: { active: false, bottom_achhor: true },
            max_widget: 2,
            max_widget_info_text: "Up to 2 items can be added",
            accepted_widgets: ["open_now_badge", "favorite_badge"],
            widgets: ["open_now_badge", "favorite_badge"],
          },
          avater: {
            label: 'Avater',
            widget_insert_window: { active: false, bottom_achhor: true },
            max_widget: 1,
            max_widget_info_text: "Up to 1 item can be added",
            accepted_widgets: ["user_avater"],
            widgets: ["user_avater"],
          },
        },

        middle: {
          body: {
            label: 'Body',
            widget_insert_window: { active: false, bottom_achhor: true },
            max_widget: 2,
            accepted_widgets: [
              { label: "Preset", widgets: ["listing_title"] },
              { label: "Custom", widgets: ["listing_title"] },
            ],
            widgets: ["listing_title"],
          },
        },

        footer: {
          right: {
            label: 'Footer Right',
            widget_insert_window: { active: false, bottom_achhor: true },
            max_widget: 2,
            accepted_widgets: ["category"],
            widgets: ["category"],
          },
          left: {
            label: 'Footer Left',
            widget_insert_window: { active: false, bottom_achhor: true },
            max_widget: 2,
            accepted_widgets: ["category"],
            widgets: ["category"],
          },
        },
      },
    };
  },

  methods: {
    activeInsertWindow( current_item_key ) {
      // console.log( current_item_key, this.active_insert_widget_key );
      this.active_insert_widget_key = current_item_key;
    },

    closeInsertWindow( widget_insert_window ) {
      this.active_insert_widget_key = '';
    },

    getActiveInsertWindowStatus( current_item_key ) {
      // console.log( current_item_key, this.active_insert_widget_key );

      if ( current_item_key === this.active_insert_widget_key ) {
        return true;
      }

      return false;
    }
  },
};
</script>