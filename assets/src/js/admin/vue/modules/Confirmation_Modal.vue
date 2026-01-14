<template>
  <!-- Model -->
  <div class="cptm-modal-container cptm-toggle-modal active" v-if="show">
    <div class="cptm-modal-wrap">
      <div class="cptm-modal">
        <div class="cptm-modal-content">
          <div class="cptm-modal-header" v-if="showModelHeader">
            <h3 class="cptm-modal-header-title" v-html="modelHeaderText"></h3>
            <div class="cptm-modal-actions">
              <a
                href="#"
                class="cptm-modal-action-link"
                @click.prevent="cancel()"
              >
                <span class="fa fa-times"></span>
              </a>
            </div>
          </div>

          <div class="cptm-modal-body cptm-center-content cptm-content-wide">
            <form action="#" method="post" class="cptm-import-directory-form">
              <div
                class="cptm-form-group-feedback cptm-text-center cptm-mb-10"
              ></div>

              <h2
                class="cptm-modal-confirmation-title"
                v-html="confirmationText"
              ></h2>

              <div class="cptm-file-input-wrap">
                <button
                  type="button"
                  class="cptm-btn cptm-btn-rounded"
                  :class="cancelButtonClass"
                  v-html="cancelButtonLabel"
                  @click="cancel()"
                ></button>

                <button
                  type="button"
                  class="cptm-btn cptm-btn-rounded"
                  :class="confirmButtonClass"
                  v-html="confirmButtonLabel"
                  @click="confirm()"
                ></button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "confirmation-modal",
  props: {
    show: {
      type: Boolean,
      default: false,
    },
    showModelHeader: {
      type: Boolean,
      default: true,
    },
    modelHeaderText: {
      type: String,
      default: "Confirm",
    },
    confirmationText: {
      type: String,
      default: "Are you sure?",
    },
    confirmButtonLabel: {
      type: String,
      default: "Yes",
    },
    confirmButtonType: {
      type: String,
      default: "primary",
    },
    cancelButtonLabel: {
      type: String,
      default: "Cancel",
    },
    cancelButtonType: {
      type: String,
      default: "secondary",
    },
    onConfirm: {
      required: false,
    },
  },

  computed: {
    confirmButtonClass() {
      let button_type = "cptm-btn-" + this.confirmButtonType;

      return {
        [button_type]: true,
      };
    },

    cancelButtonClass() {
      let button_type = "cptm-btn-" + this.cancelButtonType;

      return {
        [button_type]: true,
      };
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
    confirm() {
      if (typeof this.onConfirm !== "function") {
        return;
      }
      this.$emit("cancel");
      this.onConfirm();
    },

    cancel() {
      this.$emit("cancel");
    },

    moveModalToBody() {
      if (this.show && this.$el) {
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
    show(newVal) {
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
