<template>
  <div
    class="cptm-form-builder-group-field-drop-area"
    :class="className"
    @dragover.prevent=""
    @dragenter="handleDragenter"
    @dragleave="handleDragleave"
    @drop="handleDrop"
  >
    <p class="cptm-form-builder-group-field-drop-area-label">
      {{ dropText }}
    </p>
  </div>
</template>

<script>
export default {
  name: "form-builder-droppable-placeholder",

  computed: {
    className() {
      return {
        ["drag-enter"]: this.dragenter,
      };
    },

    dropText() {
      return this.dragenter ? "Drop anywhere" : "Simply drag a field here...";
    },
  },

  data() {
    return {
      dragenter: false,
    };
  },

  methods: {
    handleDragenter() {
      this.dragenter = true;
      this.$emit("drag-enter");
    },

    handleDragleave() {
      this.dragenter = false;
      this.$emit("drag-enter");
    },

    handleDrop() {
      this.dragenter = false;
      this.$emit("drop");
    },
  },
};
</script>
