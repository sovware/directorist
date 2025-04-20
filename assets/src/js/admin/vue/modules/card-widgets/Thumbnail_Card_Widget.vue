<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-inline-wrap cptm-widget-badge-card-wrap cptm-widget-thumb-card-wrap"
  >
    <div
      class="cptm-widget-card cptm-widget-thumb cptm-has-widget-control cptm-widget-actions-tools-wrap"
    >
      <div class="cptm-widget-thumb-icon">
        <i class="uil uil-image-v"></i>
      </div>
      <div class="cptm-widget-label">
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
  name: "thumbnail-card-widget",
  props: {
    label: {
      type: String,
    },

    options: {
      type: Object,
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
