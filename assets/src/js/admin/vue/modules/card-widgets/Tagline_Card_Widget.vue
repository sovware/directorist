<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-inline-wrap cptm-widget-tagline-card-wrap"
  >
    <div
      class="cptm-widget-card cptm-widget-tagline-card cptm-has-widget-control cptm-widget-actions-tools-wrap"
    >
      <div class="cptm-widget-tagline-block">
        {{ label }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "tagline-card-widget",
  props: {
    label: {
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
};
</script>
