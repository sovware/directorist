<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-inline-wrap cptm-widget-badge-card-wrap"
  >
    <div
      class="cptm-widget-card cptm-widget-badge cptm-has-widget-control cptm-widget-actions-tools-wrap"
    >
      <span class="cptm-widget-badge-icon" :class="displayIcon" v-if="displayIcon"></span>
      <span class="cptm-widget-badge-label" v-if="label">{{ label }}</span>
      <span class="cptm-widget-badge-trash" @click.stop="$emit('trash')" v-if="!readOnly">
        <span class="las la-times"></span>
      </span>
    </div>
  </div>
</template>

<script>
export default {
  name: "button-card-widget",
  props: {
    icon: {
      type: String,
      default: "",
    },

    label: {
      type: String,
      default: "",
    },

    options: {
      type: [Object, Array],
      default: () => ({}),
    },

    readOnly: {
      type: Boolean,
      default: false,
    },
  },

  computed: {
    displayIcon() {
      if (!this.options && typeof this.options !== "object") {
        // console.log( 'no options' );
        return this.icon;
      }

      if (!this.options.fields && typeof this.options.fields !== "object") {
        // console.log( 'no fields' );
        return this.icon;
      }

      return this.options.fields.icon.value || "";
    },
  },
};
</script>
