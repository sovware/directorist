<template>
  <div
    class="cptm-option-card cptm-option-card--draggable"
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
        ref="container"
        :key="dragDropKey"
        @drop="onElementsDrop($event)"
        group-name="card-widget-options"
        drag-handle-selector=".options-drag-handle"
        class="cptm-form-builder-field-list"
        :class="{
          'cptm-widget-options-container-draggable':
            Object.keys(widgetsList).length > 1,
        }"
        v-if="Object.keys(widgetsList).length"
        :get-ghost-parent="getGhostParent"
      >
        <Draggable
          v-for="(widget, widget_key) in widgetsList"
          :key="widget_key"
          :data="{ widget }"
        >
          <div class="cptm-form-builder-field-list-item-wrapper">
            <span
              class="cptm-form-builder-field-list-item-drag options-drag-handle"
              v-if="Object.keys(widgetsList).length > 1"
            >
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
                class="cptm-form-builder-field-list-item-edit"
                :class="activeWidgetKey === widget_key ? 'active' : ''"
                v-if="
                  isEditable(widget) && widget.widget_key !== 'listing_title'
                "
                @click.prevent="edit(widget_key)"
              >
                <span class="las la-cog"></span>
              </span>
              <span
                class="cptm-form-builder-field-list-item-action"
                @click.prevent="trash(widget_key)"
              >
                <span class="uil uil-trash-alt"></span>
              </span>
            </span>
          </div>
          <div
            v-if="activeWidgetKey === widget_key"
            class="cptm-widget-options-container"
          >
            <div
              v-for="(field, field_key) in widgetTypeField(widget_key)"
              :key="field_key"
            >
              <!-- Render the regular fields -->
              <component
                v-if="field"
                :is="getFormFieldName(field.type)"
                :field-id="`${widget_key}-${field_key}`"
                :fieldKey="`${widget_key}-${field_key}`"
                :ref="field"
                v-bind="field"
                @update="updateWidgetOptionValue($event)"
              />
            </div>

            <div
              v-for="(field, field_key) in widgetFields(widget_key)"
              class="cptm-widget-options-wrap"
              :key="field_key"
            >
              <!-- Render option fields -->
              <component
                v-if="field"
                :is="getFormFieldName(field.type)"
                :field-id="`${widget_key}-${field_key}`"
                :fieldKey="`${widget_key}-${field_key}`"
                :ref="field"
                v-bind="field"
                @update="updateWidgetFieldValue(field_key, $event)"
              />
            </div>
          </div>
        </Draggable>
      </Container>

      <p v-else class="cptm-info-text">Nothing available</p>
    </div>
  </div>
</template>

<script>
import { Container, Draggable } from "vue-dndrop";
import { mapState } from "vuex";
import helpers from "../mixins/helpers";

export default {
  name: "widgets-option-window",
  components: {
    Container,
    Draggable,
  },
  mixins: [helpers],

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
    selectedWidgets: {
      handler() {
        this.localSelectedWidgets = this.selectedWidgets;
        // Force reinitialize drag and drop after DOM updates
        this.$nextTick(() => {
          // Small delay to ensure DOM is fully updated
          setTimeout(() => {
            this.reinitializeDragAndDrop();
          }, 50);
        });
      },
      deep: true,
    },
  },

  computed: {
    ...mapState(["fields"]),
    ...mapState({
      fields: (state) => state.fields,
    }),

    // Widget List from selected_widgets
    widgetsList() {
      let availableWidgets = JSON.parse(JSON.stringify(this.availableWidgets));
      let selected_widgets = this.localSelectedWidgets;

      // Create a new object that maintains the order of selected_widgets
      let widgets_list = selected_widgets.reduce((obj, widget_name) => {
        // Find the widget by its widget_name in availableWidgets
        const widget = Object.values(availableWidgets).find(
          (w) => w.widget_name === widget_name,
        );

        // If the widget is found, add it to the object
        if (widget) {
          obj[widget_name] = widget;
        }

        return obj;
      }, {});

      return widgets_list;
    },

    // Widget Info Text
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
      };
    },
  },

  data() {
    return {
      localSelectedWidgets: [],
      activeWidget: {},
      activeWidgetKey: "",
      activeWidgetOptionType: "",
      dragDropKey: 0, // Key to force reinitialization of drag and drop
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

    // Check if the widget is editable
    isEditable(widget) {
      if (!widget || typeof widget !== "object" || widget.type === "avatar")
        return false;
      if (
        !widget.options ||
        typeof widget.options !== "object" ||
        widget.options.length === 0
      )
        return false;

      // Add more custom checks if needed
      return true;
    },

    // Update widget option value
    updateWidgetOptionValue(value) {
      this.activeWidgetOptionType = value;

      this.activeWidget.options.type.value = value;
      this.availableWidgets[this.activeWidgetKey].options.type.value = value;

      if (value === "icon") {
        this.activeWidget.icon =
          this.activeWidget?.fields?.icon?.field_icon?.value;
        this.availableWidgets[this.activeWidgetKey].icon =
          this.activeWidget?.fields?.icon?.field_icon?.value;
      }

      // Emit updated activeWidget to parent
      this.$emit("update-active-widget", {
        widgetKey: this.activeWidgetKey,
        updatedWidget: this.activeWidget,
      });

      return;
    },

    // Update widget field value
    updateWidgetFieldValue(field_key, value) {
      const activeWidgetFields =
        this.activeWidget.fields || this.activeWidget.options.fields;

      if (this.activeWidgetOptionType) {
        activeWidgetFields[this.activeWidgetOptionType][field_key].value =
          value;
      } else {
        activeWidgetFields[field_key].value = value;
      }

      if (field_key === "field_icon" || field_key === "icon") {
        this.activeWidget.icon = value;
        this.availableWidgets[this.activeWidgetKey].icon = value;
      }

      // Emit updated activeWidget to parent
      this.$emit("update-active-widget", {
        widgetKey: this.activeWidgetKey,
        updatedWidget: this.activeWidget,
      });
    },

    // Edit Widget
    edit(widget_key) {
      if (this.activeWidgetKey === widget_key) {
        this.activeWidgetKey = null; // toggle off
        this.activeWidget = {};
        this.activeWidgetOptionType = "";
      } else {
        this.activeWidgetKey = widget_key; // set active
        this.activeWidget = this.widgetsList[widget_key];
        this.activeWidgetOptionType = this.activeWidget.options?.type?.value;
      }
    },

    // Trash Widget
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
        filter_single_pare,
      );
      let filtered_double_pare = filtered_single_pare.replace(
        /({\w+\|\w+})/gi,
        filter_double_pare,
      );

      return filtered_double_pare;
    },

    // Get Widget Type Field
    widgetTypeField(widgetKey) {
      const hasRadioField = this.availableWidgets[widgetKey].options?.type;
      if (!hasRadioField) {
        return;
      }

      const activeWidgetFields = this.availableWidgets[widgetKey].options;

      return activeWidgetFields;
    },

    // Get Widget Type Options
    widgetFields(widgetKey) {
      const hasRadioField = this.availableWidgets[widgetKey].options?.type;

      const activeWidgetOptions = hasRadioField
        ? this.availableWidgets[widgetKey].fields[this.activeWidgetOptionType]
        : this.availableWidgets[widgetKey].options?.fields;

      return activeWidgetOptions;
    },

    // Get Ghost Parent for drag operations
    getGhostParent() {
      return document.body;
    },

    // Widget on Drop
    onElementsDrop(dropResult) {
      const { removedIndex, addedIndex } = dropResult;

      if (removedIndex === null || addedIndex === null) return;

      // Clone the array (no mutation)
      const updatedWidgets = [...this.selectedWidgets];

      // Remove item
      const [movedItem] = updatedWidgets.splice(removedIndex, 1);

      // Add item at new position
      updatedWidgets.splice(addedIndex, 0, movedItem);

      // Emit to parent to update prop
      this.$emit("update", { selectedWidgets: updatedWidgets });

      return;
    },

    // Reinitialize drag and drop functionality
    reinitializeDragAndDrop() {
      // Force vue-dndrop to reinitialize by changing the key
      // This ensures drag and drop works immediately after adding new items
      this.dragDropKey += 1;
    },
  },
};
</script>
