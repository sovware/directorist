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
          v-if="label"
          >{{ label }}</span
        >
        <span
          class="cptm-widget-badge-trash"
          @click.stop="$emit('trash')"
          v-if="!readOnly"
        >
          <span class="las la-times"></span>
        </span>
      </span>
    </div>
  </div>
</template>

<script>
export default {
  name: "badge-card-widget",
  props: {
    widgetKey: {
      type: String,
    },

    icon: {
      type: String,
      default: "",
    },

    label: {
      type: String,
      default: "",
    },

    options: {
      type: [Object, Array],
      default: () => ({}),
    },

    fields: {
      type: Object,
      default: () => ({}),
    },

    readOnly: {
      type: Boolean,
      default: false,
    },
  },

  computed: {
    isIconType() {
      // Handle cases where options might be an array or undefined
      if (!this.options || Array.isArray(this.options)) {
        return false;
      }
      return this.options?.type?.value === "icon";
    },
  },
};
</script>
