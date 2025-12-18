<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-inline-wrap cptm-widget-badge-card-wrap cptm-widget-thumb-card-wrap"
  >
    <div
      class="cptm-widget-card cptm-widget-thumb cptm-has-widget-control cptm-widget-actions-tools-wrap"
    >
      <div class="cptm-widget-thumb-icon">
        <svg
          width="134"
          height="108"
          viewBox="0 0 134 108"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M120.333 0.742188H13.6667C6.31337 0.742188 0.333374 6.72219 0.333374 14.0755V94.0755C0.333374 101.429 6.31337 107.409 13.6667 107.409H120.333C127.687 107.409 133.667 101.429 133.667 94.0755V14.0755C133.667 6.72219 127.687 0.742188 120.333 0.742188ZM30.3334 20.7422C32.9855 20.7422 35.5291 21.7958 37.4044 23.6711C39.2798 25.5465 40.3334 28.09 40.3334 30.7422C40.3334 33.3944 39.2798 35.9379 37.4044 37.8133C35.5291 39.6886 32.9855 40.7422 30.3334 40.7422C27.6812 40.7422 25.1377 39.6886 23.2623 37.8133C21.3869 35.9379 20.3334 33.3944 20.3334 30.7422C20.3334 28.09 21.3869 25.5465 23.2623 23.6711C25.1377 21.7958 27.6812 20.7422 30.3334 20.7422ZM67 87.4089H20.3334L47 54.0755L57 67.4089L77 40.7422L113.667 87.4089H67Z"
            fill="#A1A9B2"
          />
        </svg>
      </div>
      <div class="cptm-widget-label">
        {{ label }}
      </div>

      <span
        class="cptm-widget-thumb-edit"
        @click.stop="$emit('edit-widget')"
        v-if="!readOnly"
      >
        <span class="las la-cog"></span>
      </span>

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
