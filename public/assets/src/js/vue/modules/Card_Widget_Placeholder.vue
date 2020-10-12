<template>
  <div class="cptm-placeholder-blcok" :class="containerClass">
    <p class="cptm-placeholder-label" :class="{ hide: selectedWidgets.length }">
      {{ label }}
    </p>

    <div class="cptm-widget-insert-area">
      <div class="cptm-widget-insert-wrap">
        <div class="cptm-widget-insert-modal-container">
          <widgets-window
            :availableWidgets="availableWidgets"
            :acceptedWidgets="acceptedWidgets"
            :activeWidgets="activeWidgets"
            :active="showWidgetsPickerWindow"
            :maxWidget="maxWidget"
            :maxWidgetInfoText="maxWidgetInfoText"
            :bottomAchhor="true"
            @widget-selection="$emit('insert-widget', $event)"
            @close="$emit('close-widgets-picker-window')"
          />
        </div>

        <a
          href="#"
          class="cptm-widget-insert-link"
          @click.prevent="$emit('open-widgets-picker-window')"
        >
          <span class="fa fa-plus"></span>
        </a>
      </div>
    </div>

    <div class="cptm-widget-preview-area" v-if="selectedWidgets.length">
      <template v-for="(widget, widget_index) in selectedWidgets">
        <template v-if="hasValidWidget(widget)">
          <component
            :is="activeWidgets[widget].type + '-card-widget'"
            :key="widget_index"
            :label="activeWidgets[widget].label"
            :options="activeWidgets[widget].options"
            @drag="$emit('drag-widget', widget)"
            @edit="$emit('edit-widget', widget)"
            @trash="$emit('trash-widget', widget)"
          >
          </component>
        </template>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  name: "card-widget-placeholder",
  props: {
    test: {
      type: String,
    },
    containerClass: {
      type: String,
      default: "",
    },
    label: {
      type: String,
      default: "",
    },
    availableWidgets: {
      type: Object,
    },
    activeWidgets: {
      type: Object,
    },
    acceptedWidgets: {
      type: Array,
    },
    selectedWidgets: {
      type: Array,
    },
    showWidgetsPickerWindow: {
      type: Boolean,
      default: false,
    },
    maxWidget: {
      type: Number,
      default: 0,
    },
    maxWidgetInfoText: {
      type: String,
      default: "Up to __DATA__ item{s} can be added",
    },
  },

  methods: {
    hasValidWidget(widget_key) {
      if (
        !this.activeWidgets[widget_key] &&
        typeof this.activeWidgets[widget_key] !== "object"
      ) {
        console.log(widget_key);
        return false;
      }

      if (typeof this.activeWidgets[widget_key].type !== "string") {
        return false;
      }

      return true;
    },
  },
};
</script>