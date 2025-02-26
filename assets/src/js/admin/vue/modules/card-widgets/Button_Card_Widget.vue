<template>
  <div
    class="cptm-widget-card-wrap cptm-widget-card-inline-wrap cptm-widget-badge-card-wrap"
    @click.prevent="editOnClick ? $emit('edit') : null"
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

    <span
      class="cptm-widget-card-drop-append"
      :class="dropAppendClass"
      @dragover.prevent=""
      @dragenter="handleDragEnter()"
      @dragleave="handleDragLeave()"
      @drop="handleDrop()"
      v-if="!readOnly"
    >
    </span>
  </div>
</template>

<script>
export default {
  name: "button-card-widget",
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

    handleDragEnter() {
      this.$emit("dragenter");
      this.drop_append_drag_enter = true;
    },

    handleDragLeave() {
      this.$emit("dragleave");
      this.drop_append_drag_enter = false;
    },

    handleDrop() {
      this.$emit("drop");

      this.dragging = false;
      this.drop_append_dropable = false;
      this.drop_append_drag_enter = false;
    },
  },
};
</script>
