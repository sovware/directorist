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
  name: "avatar-card-widget",
  props: {
    label: {
      type: String,
    },

    options: {
      type: Object,
    },

    widgetDropable: {
      type: Boolean,
      default: false,
    },

    canMove: {
      type: Boolean,
      default: true,
    },

    canEdit: {
      type: Boolean,
      default: true,
    },

    canTrash: {
      type: Boolean,
      default: true,
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
    dropAppendClass() {
      return {
        dropable:
          !this.dragging && (this.drop_append_dropable || this.widgetDropable),
        "drag-enter": this.drop_append_drag_enter,
      };
    },
  },

  data() {
    return {
      drop_append_dropable: false,
      drop_append_drag_enter: false,
      dragging: false,
    };
  },

  methods: {
    dragStart() {
      this.dragging = true;
      this.$emit("drag");
    },

    dragEnd() {
      this.dragging = false;
      this.$emit("dragend");
    },
  },
};
</script>
