<template>
  <div class="cptm-form-builder-group-field-item-header">
    <h4 class="cptm-title-3">
      <span class="cptm-title-icon" :class="icon"></span>
      <span v-html="label"></span>
      <span v-if="sublabel.length" class="cptm-text-gray cptm-px-5" v-html="sublabel"></span>
    </h4>

    <div class="cptm-form-builder-group-field-item-header-actions">
      <a
        href="#"
        class="cptm-form-builder-header-action-link"
        :class="expanded ? 'action-collapse-down' : 'action-collapse-up'"
        @click.prevent="$emit('toggle-expand')"
      >
        <span aria-hidden="true" class="uil uil-angle-down"></span>
      </a>
    </div>
  </div>
</template>

<script>
export default {
  name: "form-builder-widget-titlebar-component",
  props: {
    label: {
      default: "",
    },
    sublabel: {
      default: "",
    },
    icon: {
      default: "",
    },
    expanded: {
      default: false,
    },
  },
};
</script>
