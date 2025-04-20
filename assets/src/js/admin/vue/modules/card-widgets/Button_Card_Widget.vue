<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-inline-wrap cptm-widget-badge-card-wrap"
  >
    <div
      class="cptm-widget-card cptm-widget-badge cptm-has-widget-control cptm-widget-actions-tools-wrap"
    >
      <span :class="icon" v-if="icon"></span>
      <span>{{ label }}</span>
    </div>
  </div>
</template>

<script>
export default {
  name: "button-card-widget",
  props: {
    icon: {
      type: String,
    },

    label: {
      type: String,
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

};
</script>
