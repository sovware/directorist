<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-block-wrap cptm-widget-title-card-wrap"
  >
    <div
      class="cptm-widget-card cptm-widget-title-card cptm-has-widget-control cptm-widget-actions-tools-wrap"
    >
      <div class="cptm-widget-title-block">
        {{ label }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "section-title-card-widget",
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
