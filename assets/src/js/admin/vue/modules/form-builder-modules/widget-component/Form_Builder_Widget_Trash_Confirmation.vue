<template>
  <div
    v-if="visible"
    class="cptm-widget-trash-confirmation-modal-overlay"
    @click="handleOverlayClick"
  >
    <div class="cptm-widget-trash-confirmation-modal" @click.stop>
      <h2>Are you sure you want to proceed?</h2>
      <p>
        Deleting "<strong>{{ widgetName }}</strong
        >" {{reviewDeleteTitle}}
      </p>
      <button @click="confirmDelete">{{ reviewDeleteMsg }}</button>
      <button
        class="cptm-widget-trash-confirmation-modal-action-btn__cancel"
        @click="cancelDelete"
      >
      {{ reviewCancelBtnText }}
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
    reviewDeleteTitle: {
      type: String,
      default: 'field will also remove it from the single and search pages.',
    },
    reviewDeleteMsg: {
      type: String,
      default: 'Yes, Delete it!',
    },
    reviewCancelBtnText: {
      type: String,
      default: 'Cancel',
    },
  },
  mounted() {
    // Move modal to body to avoid z-index issues
    this.moveModalToBody();
  },
  updated() {
    // Re-move modal to body when updated
    this.moveModalToBody();
  },
  beforeDestroy() {
    // Clean up when component is destroyed
    this.cleanupModal();
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
    moveModalToBody() {
      if (this.visible && this.$el) {
        // Check if modal is already in body
        if (this.$el.parentNode !== document.body) {
          document.body.appendChild(this.$el);
        }
      }
    },
    cleanupModal() {
      // Remove modal from body if it exists
      if (this.$el && this.$el.parentNode === document.body) {
        document.body.removeChild(this.$el);
      }
    },
  },
  watch: {
    visible(newVal) {
      if (newVal) {
        // When modal opens, move it to body
        this.$nextTick(() => {
          this.moveModalToBody();
        });
      } else {
        // When modal closes, cleanup
        this.cleanupModal();
      }
    },
  },
};
</script>
