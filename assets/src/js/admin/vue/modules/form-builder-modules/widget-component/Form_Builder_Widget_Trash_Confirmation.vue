<template>
  <div
    v-if="visible"
    class="confirmation-modal-overlay"
    @click="handleOverlayClick"
  >
    <div class="confirmation-modal" @click.stop>
      <p>
        Removing "<strong>{{
          widgetName
        }}</strong
        >" field will also remove it from the single and search pages. Are you sure you want to proceed?
      </p>
      <button @click="confirmDelete">Yes, delete</button>
      <button
        class="confirmation-modal-action-btn__cancel"
        @click="cancelDelete"
      >
        Cancel
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: "ConfirmationModal",
  props: {
    visible: {
      type: Boolean,
      default: false,
    },
    widgetName: {
      type: String,
      default: "",
    },
  },
  methods: {
    confirmDelete() {
      this.$emit("confirm");
    },
    cancelDelete() {
      this.$emit("cancel");
    },
    handleOverlayClick() {
      this.cancelDelete();
    },
  },
};
</script>
