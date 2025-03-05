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
export default {
  name: "widgets-option-window",

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

      let unique_selecte_widgets = new Set(this.selectedWidgets);
      this.localSelectedWidgets = [...unique_selecte_widgets];
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
  },
};
</script>
