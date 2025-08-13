<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-inline-wrap cptm-widget-badge-card-wrap"
    @click.prevent="$emit('edit')"
  >
    <div
      class="cptm-widget-card cptm-has-widget-control cptm-widget-actions-tools-wrap"
    >
      <div class="cptm-placeholder-author-thumb">
        <svg
          width="40"
          height="40"
          viewBox="0 0 40 40"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M35.1667 20.8327L37.5 23.1827L26.6167 34.166L20.8333 28.3327L23.1667 25.9827L26.6167 29.4493L35.1667 20.8327ZM16.6667 28.3327L21.6667 33.3327H5V29.9993C5 26.316 10.9667 23.3327 18.3333 23.3327L21.4833 23.516L16.6667 28.3327ZM18.3333 6.66602C20.1014 6.66602 21.7971 7.36839 23.0474 8.61864C24.2976 9.86888 25 11.5646 25 13.3327C25 15.1008 24.2976 16.7965 23.0474 18.0467C21.7971 19.297 20.1014 19.9993 18.3333 19.9993C16.5652 19.9993 14.8695 19.297 13.6193 18.0467C12.369 16.7965 11.6667 15.1008 11.6667 13.3327C11.6667 11.5646 12.369 9.86888 13.6193 8.61864C14.8695 7.36839 16.5652 6.66602 18.3333 6.66602Z"
            fill="#141921"
          />
        </svg>
        <span
          class="cptm-placeholder-author-thumb-trash"
          @click.stop="$emit('trash-widget')"
        >
          <span class="las la-trash-alt"></span>
        </span>
      </div>
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
