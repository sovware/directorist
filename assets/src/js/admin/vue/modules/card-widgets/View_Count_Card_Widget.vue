<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-inline-wrap cptm-widget-badge-card-wrap"
  >
    <div
      class="cptm-widget-card cptm-widget-badge cptm-has-widget-control cptm-widget-actions-tools-wrap"
    >
      <span>
        <i :class="icon ? icon : 'uil uil-eye'"></i>
        0
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
