<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-inline-wrap cptm-widget-badge-card-wrap"
  >
    <div
      class="cptm-widget-card cptm-widget-badge cptm-has-widget-control cptm-widget-actions-tools-wrap"
    >
      <span :class="icon" v-if="icon"></span>
      <span>{{ label }}</span>

      <widget-action-tools
        :canEdit="canEdit"
        :canMove="canMove"
        :canTrash="canTrash"
        @drag="dragStart()"
        @dragend="dragEnd()"
        @edit="$emit('edit')"
        @trash="$emit('trash')"
        v-if="!readOnly && !editOnClick"
      />
    </div>
  </div>
</template>

<script>
export default {
  name: "ratings-count-card-widget",
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

    readOnly: {
      type: Boolean,
      default: false,
    },

    editOnClick: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
