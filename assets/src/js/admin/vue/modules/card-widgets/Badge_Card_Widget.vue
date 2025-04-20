<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-inline-wrap cptm-widget-badge-card-wrap"
  >
    <div
      class="cptm-widget-card cptm-widget-badge cptm-has-widget-control cptm-widget-actions-tools-wrap"
      :class="{ 'cptm-widget-badge--icon': isIconType && icon }"
      :style="{
        background:
          isIconType && icon
            ? fields?.icon?.icon_background?.value
            : fields?.text?.text_background?.value || '',
      }"
    >
      <span
        class="cptm-widget-badge-icon"
        :class="icon"
        :style="{
          color: fields?.icon?.icon_color?.value,
        }"
        v-if="isIconType && icon"
      ></span>
      <span class="cptm-widget-badge-wrapper" v-else>
        <span
          class="cptm-widget-badge-icon"
          :class="icon"
          :style="{
            color: fields?.text?.text_color?.value || '',
          }"
          v-if="icon"
        ></span>
        <span
          class="cptm-widget-badge-label"
          :style="{
            color: fields?.text?.text_color?.value || '',
          }"
          >{{ label }}</span
        >
      </span>
    </div>
  </div>
</template>

<script>
export default {
  name: "badge-card-widget",
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

    fields: {
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

  computed: {
    isIconType() {
      return this.options?.type?.value === "icon";
    },
  },
};
</script>
