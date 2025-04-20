<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-inline-wrap cptm-widget-badge-card-wrap"
    @click.prevent="$emit('edit')"
  >
    <div
      class="cptm-widget-card cptm-has-widget-control cptm-widget-actions-tools-wrap"
    >
      <p class="cptm-placeholder-author-thumb">
        <img src="https://placehold.co/150" alt="" />
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: "avatar-card-widget",
  props: {
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
  },

};
</script>
