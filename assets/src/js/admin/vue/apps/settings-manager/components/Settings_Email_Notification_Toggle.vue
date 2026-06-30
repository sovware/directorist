<template>
  <div class="cptm-email-notification-toggle">
    <div class="cptm-email-notification-toggle__content">
      <label>{{ labelText }}</label>
      <p v-if="descriptionText">{{ descriptionText }}</p>
    </div>

    <button
      type="button"
      class="cptm-input-toggle"
      :class="{ active: isEmailEnabled }"
      :aria-pressed="isEmailEnabled ? 'true' : 'false'"
      @click="toggleEmailNotifications"
    ></button>
  </div>
</template>

<script>
export default {
  name: "settings-email-notification-toggle",

  props: {
    field: {
      type: Object,
      default: () => ({}),
    },
    fieldKey: {
      type: String,
      required: true,
    },
  },

  computed: {
    labelText() {
      return this.field.label || "Enable email notifications";
    },

    descriptionText() {
      return this.field.description || "";
    },

    isEmailDisabled() {
      return this.normalizeBoolean(this.field.value);
    },

    isEmailEnabled() {
      return !this.isEmailDisabled;
    },
  },

  methods: {
    normalizeBoolean(value) {
      return (
        value === true ||
        value === "true" ||
        value === 1 ||
        value === "1"
      );
    },

    toggleEmailNotifications() {
      this.$emit("update-field", {
        fieldKey: this.fieldKey,
        value: this.isEmailEnabled,
      });
    },
  },
};
</script>
