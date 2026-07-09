<template>
  <div
    class="cptm-web-push-setup-card"
    :class="{ 'cptm-web-push-setup-card--locked': isLocked }"
  >
    <div class="cptm-web-push-setup-card__content">
      <div class="cptm-web-push-setup-card__header">
        <h4>{{ title }}</h4>
      </div>

      <div
        ref="description"
        class="cptm-web-push-setup-card__description"
        v-html="description"
      ></div>
    </div>
  </div>
</template>

<script>
export default {
  name: "settings-web-push-setup-card",

  data() {
    return {
      adminButtonObserver: null,
      isSyncingAdminButton: false,
    };
  },

  props: {
    field: {
      type: Object,
      default: () => ({}),
    },
    isLocked: {
      type: Boolean,
      default: false,
    },
  },

  computed: {
    title() {
      return this.field.title || "Admin Browser Notifications";
    },

    description() {
      return this.field.description || "";
    },
  },

  watch: {
    isLocked() {
      this.$nextTick(this.syncLockedState);
    },
    description() {
      this.$nextTick(this.syncLockedState);
    },
  },

  mounted() {
    this.observeAdminButton();
    this.syncLockedState();
  },

  beforeDestroy() {
    this.disconnectAdminButtonObserver();
  },

  methods: {
    disconnectAdminButtonObserver() {
      if (!this.adminButtonObserver) {
        return;
      }

      this.adminButtonObserver.disconnect();
      this.adminButtonObserver = null;
    },

    observeAdminButton() {
      const root = this.$refs.description;

      if (!root || typeof MutationObserver === "undefined") {
        return;
      }

      this.disconnectAdminButtonObserver();
      this.adminButtonObserver = new MutationObserver(() => {
        if (this.isSyncingAdminButton) {
          return;
        }

        this.syncLockedState();
      });
      this.adminButtonObserver.observe(root, {
        attributes: true,
        attributeFilter: ["data-admin-action"],
        childList: true,
        subtree: true,
      });
    },

    syncLockedState() {
      const root = this.$refs.description;

      if (!root) {
        return;
      }

      const supportIssue = this.isLocked ? "" : this.getBrowserSupportIssue();

      this.isSyncingAdminButton = true;

      try {
        root
          .querySelectorAll(".directorist-notifications-pro-admin-subscribe")
          .forEach((button) => {
          const lockAttribute = "data-directorist-settings-locked";
          const supportAttribute = "data-directorist-settings-unsupported";
          const isLockedBySettings = button.getAttribute(lockAttribute) === "true";
          const isUnsupportedBySettings =
            button.getAttribute(supportAttribute) === "true";

          if (this.isLocked) {
            if (isUnsupportedBySettings) {
              button.removeAttribute(supportAttribute);
              this.restoreAdminStatus(root);
            }

            if (!button.disabled) {
              button.setAttribute(lockAttribute, "true");
              this.setButtonDisabled(button, true);
            } else if (isLockedBySettings) {
              button.setAttribute("aria-disabled", "true");
            }

            return;
          }

          if (isLockedBySettings) {
            this.setButtonDisabled(button, false);
            button.removeAttribute(lockAttribute);
          }

          if (supportIssue) {
            if (!isUnsupportedBySettings) {
              button.setAttribute(supportAttribute, "true");
            }

            this.setButtonDisabled(button, true);
            this.setAdminStatus(root, supportIssue);
            return;
          }

          if (isUnsupportedBySettings) {
            this.setButtonDisabled(button, false);
            button.removeAttribute(supportAttribute);
            this.restoreAdminStatus(root);
          }
        });
      } finally {
        this.isSyncingAdminButton = false;
      }
    },

    setButtonDisabled(button, disabled) {
      if (button.disabled !== disabled) {
        button.disabled = disabled;
      }

      if (disabled) {
        if (button.getAttribute("aria-disabled") !== "true") {
          button.setAttribute("aria-disabled", "true");
        }

        return;
      }

      if (button.hasAttribute("aria-disabled")) {
        button.removeAttribute("aria-disabled");
      }
    },

    getBrowserSupportIssue() {
      if (typeof window === "undefined") {
        return "";
      }

      if (!window.isSecureContext) {
        return "Web Push requires HTTPS or localhost. Open this admin page over HTTPS to enable browser notifications.";
      }

      if (
        !("Notification" in window) ||
        !("PushManager" in window) ||
        !("serviceWorker" in navigator)
      ) {
        return "This browser does not support Web Push notifications.";
      }

      return "";
    },

    setAdminStatus(root, message) {
      root
        .querySelectorAll(".directorist-notifications-pro-admin-status")
        .forEach((status) => {
          const statusAttribute = "data-directorist-settings-status";

          if (!status.hasAttribute(statusAttribute)) {
            status.setAttribute(statusAttribute, status.textContent);
          }

          if (status.textContent !== message) {
            status.textContent = message;
          }

          status.classList.add(
            "directorist-notifications-pro-admin-status--error"
          );
        });
    },

    restoreAdminStatus(root) {
      root
        .querySelectorAll(".directorist-notifications-pro-admin-status")
        .forEach((status) => {
          const statusAttribute = "data-directorist-settings-status";
          const previousStatus = status.getAttribute(statusAttribute);

          if (previousStatus !== null && status.textContent !== previousStatus) {
            status.textContent = previousStatus;
          }

          status.removeAttribute(statusAttribute);
          status.classList.remove(
            "directorist-notifications-pro-admin-status--error"
          );
        });
    },
  },
};
</script>
