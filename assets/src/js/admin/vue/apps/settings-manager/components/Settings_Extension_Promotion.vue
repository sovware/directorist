<template>
  <div class="cptm-extension-promotion">
    <div class="cptm-extension-promotion__row">
      <div>
        <h3>{{ title }}</h3>
        <p>{{ description }}</p>
      </div>
    </div>

    <div class="cptm-extension-promotion__row">
      <div>
        <h3>{{ browseTitle }}</h3>
        <p>{{ browseDescription }}</p>
      </div>
      <a :href="browseUrl" class="cptm-extension-promotion__button">
        {{ browseButtonLabel }}
      </a>
    </div>
  </div>
</template>

<script>
export default {
  name: "settings-extension-promotion",

  props: {
    field: {
      type: Object,
      default: () => ({}),
    },
    extensionContext: {
      type: Object,
      default: () => ({}),
    },
  },

  computed: {
    title() {
      return this.field.title || "Installed extensions";
    },

    description() {
      const labels = Array.isArray(this.extensionContext.extensionLabels)
        ? this.extensionContext.extensionLabels
        : [];

      if (this.extensionContext.hasExtensionSettings) {
        const activeDescription =
          "Extension settings are available in the settings panel";

        if (labels.length) {
          return `${activeDescription}: ${labels.join(", ")}.`;
        }

        return `${activeDescription}.`;
      }

      return (
        this.field.description ||
        "No extension settings available yet. Each extension you install can add its own settings section here."
      );
    },

    browseTitle() {
      return this.field.browseTitle || "Browse extensions";
    },

    browseDescription() {
      return (
        this.field.browseDescription ||
        "30+ extensions available including PayPal, Stripe, Live Chat, Universal Search, Booking, and Pricing Plans."
      );
    },

    browseButtonLabel() {
      return this.field.browseButtonLabel || "View directory";
    },

    browseUrl() {
      return (
        this.field.browseUrl ||
        "/wp-admin/edit.php?post_type=at_biz_dir&page=atbdp-extension"
      );
    },
  },
};
</script>
