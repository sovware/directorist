<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-block-wrap cptm-widget-badge-card-wrap"
  >
    <div
      class="cptm-widget-card cptm-list-item-card cptm-has-widget-control cptm-widget-actions-tools-wrap"
    >
      <div class="cptm-list-item">
        <div class="cptm-list-item-content">
          <span class="cptm-list-item-icon">
            <span :class="listIcon"></span>
          </span>
          <span class="cptm-list-item-label">
            <span class="cptm-list-item-label-text">{{ label }}</span>
          </span>
        </div>
        <div class="cptm-list-item-actions">
          <span
            class="cptm-list-item-action cptm-list-item-edit"
            v-if="isEditable(options)"
            @click.stop="edit(widgetKey)"
          >
            <span class="las la-cog"></span>
          </span>
          <span
            class="cptm-list-item-action cptm-list-item-trash"
            @click.stop="$emit('trash')"
          >
            <span class="las la-trash"></span>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "list-item-card-widget",
  props: {
    label: {
      type: String,
    },

    icon: {
      type: String,
      default: "",
    },

    widgetKey: {
      type: String,
      default: "",
    },

    options: {
      type: Object,
      default: () => ({}),
    },

    readOnly: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      activeWidgetKey: "",
      activeWidget: {},
      activeWidgetOptionType: "",
    };
  },

  computed: {
    listIcon() {
      if (!this.options && typeof this.options !== "object") {
        // console.log( 'no options' );
        return this.icon;
      }

      if (!this.options.fields && typeof this.options.fields !== "object") {
        // console.log( 'no fields' );
        return this.icon;
      }

      if (
        !this.options.fields.icon &&
        typeof this.options.fields.icon !== "object"
      ) {
        // console.log( 'no icon', this.options );
        return this.icon;
      }

      if (
        typeof this.options.fields.icon.value !== "string" &&
        !this.options.fields.icon.value.length
      ) {
        // console.log( 'empty icon' );
        return this.icon;
      }

      return this.options.fields.icon.value;
    },
  },

  methods: {
    // Check if the widget is editable
    isEditable(widgetOptions) {
      if (!widgetOptions || typeof widgetOptions !== "object") return false;

      // Add more custom checks if needed
      return true;
    },

    // Edit Widget
    edit(widgetKey) {
      // Emit the edit event with the widget key
      this.$emit("edit", widgetKey);
    },
  },
};
</script>
