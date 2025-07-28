<template>
  <div
    v-if="show"
    class="confirm-leave-modal-overlay"
    @click="handleOverlayClick"
  >
    <div class="confirm-leave-modal" @click.stop>
      <div class="confirm-leave-modal-header">
        <h3>Unsaved Changes</h3>
        <button @click="closeModal">×</button>
      </div>
      <div class="confirm-leave-modal-body">
        <p>
          You have unsaved changes. Are you sure you want to leave without
          saving?
        </p>
      </div>
      <div class="confirm-leave-modal-footer">
        <button @click="discardChanges">Leave</button>
        <button @click="closeModal">Cancel</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ConfirmLeaveModal",
  props: {
    show: {
      type: Boolean,
      required: true,
    },
  },
  methods: {
    closeModal() {
      this.$emit("close");
    },
    discardChanges() {
      this.$emit("confirm");
      this.closeModal();
    },
    handleOverlayClick() {
      this.cancelDelete();
    },
  },
};
</script>
