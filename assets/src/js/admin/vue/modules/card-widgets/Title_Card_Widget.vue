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

      <span class="cptm-widget-card-disabled-badge" v-if="disabled">
        Disable
      </span>
    </div>
  </div>
</template>

<script>
export default {
  name: "title-card-widget",
  props: {
    label: {
      type: String,
      default: "",
    },

    options: {
      type: Object,
      default: () => ({}),
    },

    disabled: {
      type: Boolean,
      default: false,
    },

    readOnly: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
