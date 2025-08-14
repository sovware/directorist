<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-inline-wrap cptm-widget-badge-card-wrap"
  >
    <div
      class="cptm-widget-card cptm-widget-badge cptm-has-widget-control cptm-widget-actions-tools-wrap"
    >
      <span class="cptm-widget-badge-icon" :class="icon" v-if="icon"></span>
      <span class="cptm-widget-badge-label">
        {{ label }}
      </span>
      <span class="cptm-widget-badge-trash" @click.stop="$emit('trash')">
        <span class="las la-times"></span>
      </span>
    </div>
  </div>
</template>

<script>
export default {
  name: "view-count-card-widget",
  props: {
    label: {
      type: String,
    },

    icon: {
      type: String,
    },

    options: {
      type: Object,
    },

    readOnly: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
