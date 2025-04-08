<template>
  <div
    class="cptm-option-card cptm-option-card--draggable test"
    :class="mainWrapperClass"
  >
    <div class="cptm-option-card-header">
      <div class="cptm-option-card-header-title-section">
        <h3 class="cptm-option-card-header-title">Edit Element</h3>

        <div class="cptm-header-action-area">
          <a
            href="#"
            class="cptm-header-action-link cptm-header-action-close"
            @click.prevent="$emit('close')"
          >
            <span class="fa fa-times"></span>
          </a>
        </div>
      </div>
    </div>

    <div class="cptm-option-card-body">
      <div v-if="infoTexts.length" class="cptm-info-text-area">
        <p
          class="cptm-info-text"
          :class="'cptm-' + info.type"
          v-for="(info, text_key) in infoTexts"
          :key="text_key"
        >
          {{ info.text }}
        </p>
      </div>

      <Container
        @drop="onElementsDrop($event)"
        group-name="card-widgets"
        drag-handle-selector=".drag-handle"
        class="cptm-form-builder-field-list"
        :get-child-payload="(index) => getSettingsChildPayload(index)"
        v-if="Object.keys(widgetsList).length"
      >
        <Draggable
          v-for="(widget, widget_key) in widgetsList"
          :key="widget_key"
          :data="{ widget }"
        >
          <div class="cptm-form-builder-field-list-item-wrapper">
            <span class="cptm-form-builder-field-list-item-drag drag-handle">
              <span class="uil uil-draggabledots"></span>
            </span>
            <span class="cptm-form-builder-field-list-item">
              <span class="cptm-form-builder-field-list-item-content">
                <span class="cptm-form-builder-field-list-item-icon">
                  <span :class="widget?.icon"></span>
                </span>
                <span class="cptm-form-builder-field-list-item-label">
                  {{ widget?.label }}
                </span>
              </span>
              <span
                class="cptm-form-builder-field-list-item-action"
                @click.prevent="trash(widget_key)"
              >
                <span class="uil uil-trash-alt"></span>
              </span>
            </span>
          </div>
        </Draggable>
      </Container>

      <ul
        class="cptm-form-builder-field-list"
        v-if="Object.keys(widgetsList).length"
      >
        <li
          class="cptm-form-builder-field-list-item-wrapper"
          v-for="(widget, widget_key) in widgetsList"
          :key="widget_key"
        >
          <span class="cptm-form-builder-field-list-item-drag">
            <span class="uil uil-draggabledots"></span>
          </span>
          <span class="cptm-form-builder-field-list-item">
            <span class="cptm-form-builder-field-list-item-content">
              <span class="cptm-form-builder-field-list-item-icon">
                <span :class="widget.icon"></span>
              </span>
              <span class="cptm-form-builder-field-list-item-label">
                {{ widget.label }}
              </span>
            </span>
            <span
              class="cptm-form-builder-field-list-item-action"
              @click.prevent="trash(widget_key)"
            >
              <span class="uil uil-trash-alt"></span>
            </span>
          </span>
        </li>
      </ul>

      <p v-else class="cptm-info-text">Nothing available</p>
    </div>
  </div>
</template>

<script>
import { Container, Draggable } from "vue-dndrop";

export default {
  name: "widgets-option-window",
  components: {
    Container,
    Draggable,
  },

  props: {
    id: {
      type: [String, Number],
      default: "",
    },
    active: {
      type: Boolean,
      default: false,
    },
    animation: {
      type: String,
      default: "cptm-animation-slide-up",
    },
    availableWidgets: {
      type: Object,
    },
    selectedWidgets: {
      type: Array,
    },
    maxWidgetInfoText: {
      type: String,
      default: "Up to __DATA__ item{s} can be added",
    },
  },

  created() {
    this.init();
  },

  watch: {
    selectedWidgets() {
      this.localSelectedWidgets = this.selectedWidgets;
    },
  },

  computed: {
    widgetsList() {
      let availableWidgets = JSON.parse(JSON.stringify(this.availableWidgets));
      let selected_widgets = this.selectedWidgets;

      let widgets_list = Object.keys(availableWidgets)
        .filter((key) =>
          selected_widgets.includes(availableWidgets[key].widget_name)
        )
        .reduce((obj, key) => {
          obj[key] = availableWidgets[key];

          return obj;
        }, {});

      return widgets_list;
    },

    infoTexts() {
      let info_texts = [];

      if (
        this.maxWidgetLimitIsReached &&
        Object.keys(this.unSelectedWidgetsList).length
      ) {
        info_texts.push({
          type: "info",
          text: this.decodeInfoText(this.maxWidget, this.maxWidgetInfoText),
        });
      }

      return info_texts;
    },

    mainWrapperClass() {
      return {
        active: this.active,
        [this.animation]: true,
      };
    },
  },

  data() {
    return {
      localSelectedWidgets: [],
    };
  },

  methods: {
    init() {
      if (typeof this.selectedWidgets !== "object") {
        return;
      }

      let unique_selected_widgets = new Set(this.selectedWidgets);
      this.localSelectedWidgets = [...unique_selected_widgets];
    },

    close() {
      this.$emit("close");
    },

    trash(widget_key) {
      this.$emit("trash-widget", widget_key);
    },

    decodeInfoText(data, text) {
      let doceded = text.replace(/__DATA__/gi, data);

      const filter_single_pare = function (str) {
        if (data < 2) {
          return "";
        }

        let filtered = str.replace(/{/gi, "");
        filtered = filtered.replace(/}/gi, "");

        return filtered;
      };

      const filter_double_pare = function (str) {
        let pares = str.match(/\w+|w+/gi);
        if (typeof pares !== "object" && pares.length < 2) {
          return "";
        }
        if (data < 2) {
          return pares[0];
        }

        return pares[1];
      };

      let filtered_single_pare = doceded.replace(
        /({\w+})/gi,
        filter_single_pare
      );
      let filtered_double_pare = filtered_single_pare.replace(
        /({\w+\|\w+})/gi,
        filter_double_pare
      );

      return filtered_double_pare;
    },

    getSettingsChildPayload(draggedItemIndex) {
      console.log("@getSettingsChildPayload", {
        draggedItemIndex,
      });

      // Return the payload containing both pieces of data
      return {
        draggedItemIndex: draggedItemIndex,
      };
    },

    onElementsDrop(dropResult) {
      const { removedIndex, addedIndex, payload } = dropResult;
      const { draggedItemIndex } = payload;

      console.log("@onElementsDrop", {
        dropResult,
        payload,
      });

      return;

      if (removedIndex !== null || addedIndex !== null) {
        let destinationItemIndex;
        let destinationPlaceholderIndex;
        const sourceItemIndex = draggedItemIndex;
        const sourcePlaceholderIndex = placeholderIndex;

        if (addedIndex !== null) {
          destinationItemIndex = addedIndex;
          destinationPlaceholderIndex = placeholder_index;
        } else {
          destinationItemIndex = null;
          destinationPlaceholderIndex = null;
        }

        // Get the widget key from the source placeholder
        const widgetKey = this.allPlaceholderItems[sourcePlaceholderIndex]
          ?.acceptedWidgets[draggedItemIndex];

        if (widgetKey !== undefined) {
          if (sourcePlaceholderIndex === destinationPlaceholderIndex) {
            // Moving within the same placeholder
            const widgets = this.allPlaceholderItems[sourcePlaceholderIndex]
              .acceptedWidgets;
            const selectedWidgets = this.allPlaceholderItems[
              sourcePlaceholderIndex
            ].selectedWidgets;
            const selectedWidgetList = this.allPlaceholderItems[
              sourcePlaceholderIndex
            ].selectedWidgetList;

            // Remove the widget from the source position
            const [movedWidget] = widgets.splice(sourceItemIndex, 1);

            // Insert the widget at the destination position
            widgets.splice(destinationItemIndex, 0, movedWidget);

            // Update selectedWidgetList position based on acceptedWidgets
            const selectedWidgetIndex =
              selectedWidgetList && selectedWidgetList.indexOf(movedWidget);
            if (selectedWidgetIndex && selectedWidgetIndex !== -1) {
              // Remove the widget from the selected position
              selectedWidgetList.splice(selectedWidgetIndex, 1);

              // Insert the widget at the new position
              const newSelectedIndex = widgets.indexOf(movedWidget);
              selectedWidgetList.splice(newSelectedIndex, 0, movedWidget);
            }

            // Reorder `selectedWidgets` based on `selectedWidgetList`
            selectedWidgets &&
              selectedWidgets.sort((a, b) => {
                return (
                  selectedWidgetList.indexOf(a.widget_key) -
                  selectedWidgetList.indexOf(b.widget_key)
                );
              });

            // Update Placeholders
            const updatedPlaceholders = this.syncPlaceholdersWithAllPlaceholderItems(
              this.allPlaceholderItems,
              this.placeholders || []
            );

            this.placeholders = updatedPlaceholders;
          } else if (destinationPlaceholderIndex !== null) {
            // Moving between different placeholders
            // this.allPlaceholderItems[destinationPlaceholderIndex].selectedWidgetList.splice(destinationItemIndex, 0, widgetKey);
            // this.allPlaceholderItems[sourcePlaceholderIndex].selectedWidgetList.splice(sourceItemIndex, 1);
          }
        }
      } else {
        return;
      }
    },
  },
};
</script>
