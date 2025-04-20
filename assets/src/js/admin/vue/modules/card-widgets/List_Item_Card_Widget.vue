<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-block-wrap cptm-widget-badge-card-wrap"
  >
    <div
      class="cptm-widget-card cptm-list-item-card cptm-has-widget-control cptm-widget-actions-tools-wrap"
    >
      <div class="cptm-list-item">
        <span class="cptm-list-item-icon">
          <span :class="listIcon"></span>
        </span>
        <span class="cptm-list-item-label">{{ label }}</span>
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

    options: {
      type: Object,
    },

    readOnly: {
      type: Boolean,
      default: false,
    },

    editOnClick: {
      type: Boolean,
      default: false,
    },
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
};
</script>
