<template>
  <div class="cptm-form-builder-group-field-item-header">
    <h4 class="cptm-title-3">
      <span aria-hidden="true" class="cptm-title-icon fa fa-angle-up"></span>
      <span v-html="label"></span>
    </h4>

    <div class="cptm-form-builder-group-field-item-header-actions">
      <a
        href="#"
        class="cptm-form-builder-header-action-link"
        :class="expanded ? 'action-collapse-up' : 'action-collapse-down'"
        @click.prevent="$emit('toggle-expand')"
      >
        <span aria-hidden="true" class="fa fa-angle-up"></span>
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
    expanded: {
      default: false,
    },
  },
};
</script>
