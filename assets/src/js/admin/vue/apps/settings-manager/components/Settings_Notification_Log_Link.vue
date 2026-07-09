<template>
  <div class="cptm-notification-log-link">
    <div class="cptm-notification-log-link__content">
      <h4>{{ title }}</h4>
      <p>{{ descriptionText }}</p>
    </div>

    <a
      v-if="logUrl"
      class="cptm-notification-log-link__button"
      :href="logUrl"
      @click="openLogPage"
    >
      View log
    </a>
  </div>
</template>

<script>
export default {
  name: "settings-notification-log-link",

  props: {
    field: {
      type: Object,
      default: () => ({}),
    },
  },

  computed: {
    title() {
      return this.field.title || "Notification Log";
    },

    descriptionText() {
      const text = this.stripTags(this.field.description || "");

      return (
        text ||
        "Review recent Web Push delivery attempts, status, and failure reasons."
      );
    },

    logUrl() {
      const match = String(this.field.description || "").match(
        /href=["']([^"']+)["']/i,
      );

      return match ? this.decodeEntities(match[1]) : "";
    },
  },

  methods: {
    decodeEntities(value) {
      const textarea = document.createElement("textarea");

      textarea.innerHTML = String(value);

      const decoded = textarea.value
        .replace(/&amp;/g, "&")
        .replace(/&#0?38;/g, "&");

      try {
        return new URL(decoded, window.location.href).href;
      } catch (error) {
        return decoded;
      }
    },

    openLogPage(event) {
      if (!this.logUrl) {
        return;
      }

      event.preventDefault();
      window.location.assign(this.logUrl);
    },

    stripTags(value) {
      return String(value)
        .replace(/<[^>]*>/g, " ")
        .replace(/\s+/g, " ")
        .trim();
    },
  },
};
</script>
