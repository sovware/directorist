<template>
  <div class="cptm-email-notification-toggle cptm-web-push-notification-toggle">
    <div class="cptm-email-notification-toggle__content">
      <label>Enable web push notifications</label>
      <p>Send browser push to admins and listing owners who have opted in.</p>
    </div>

    <button
      type="button"
      class="cptm-input-toggle"
      :class="{ active: isEnabled }"
      :aria-pressed="isEnabled ? 'true' : 'false'"
      @click="toggleWebPushNotifications"
    ></button>
  </div>
</template>

<script>
const DEFAULT_ADMIN_EVENTS = [
  "order_completed",
  "payment_received",
  "listing_submitted",
  "listing_published",
];

const DEFAULT_OWNER_EVENTS = [
  "order_completed",
  "payment_received",
  "listing_submitted",
  "listing_published",
  "listing_renewed",
  "listing_expired",
  "listing_contact_form",
];

export default {
  name: "settings-web-push-notification-toggle",

  props: {
    fields: {
      type: Object,
      default: () => ({}),
    },
    adminFieldKey: {
      type: String,
      default: "web_push_notify_admin",
    },
    ownerFieldKey: {
      type: String,
      default: "web_push_notify_user",
    },
    adminBackupFieldKey: {
      type: String,
      default: "directorist_web_push_notify_admin_backup",
    },
    ownerBackupFieldKey: {
      type: String,
      default: "directorist_web_push_notify_user_backup",
    },
  },

  data() {
    return {
      lastAdminValue: [],
      lastOwnerValue: [],
    };
  },

  computed: {
    adminField() {
      return this.fields[this.adminFieldKey] || {};
    },

    ownerField() {
      return this.fields[this.ownerFieldKey] || {};
    },

    adminBackupField() {
      return this.fields[this.adminBackupFieldKey] || {};
    },

    ownerBackupField() {
      return this.fields[this.ownerBackupFieldKey] || {};
    },

    adminValue() {
      return this.normalizeArray(this.adminField.value);
    },

    ownerValue() {
      return this.normalizeArray(this.ownerField.value);
    },

    adminBackupValue() {
      return this.normalizeArray(this.adminBackupField.value);
    },

    ownerBackupValue() {
      return this.normalizeArray(this.ownerBackupField.value);
    },

    isEnabled() {
      return this.adminValue.length > 0 || this.ownerValue.length > 0;
    },
  },

  methods: {
    normalizeArray(value) {
      if (Array.isArray(value)) {
        return value.map((item) => String(item));
      }

      if (value === null || typeof value === "undefined" || value === "") {
        return [];
      }

      return [String(value)];
    },

    optionValues(field) {
      if (!Array.isArray(field.options)) {
        return [];
      }

      return field.options
        .filter((option) => option && typeof option.value !== "undefined")
        .map((option) => String(option.value));
    },

    defaultValues(field, defaults) {
      const availableValues = this.optionValues(field);
      const filteredDefaults = defaults.filter((value) =>
        availableValues.includes(value),
      );

      return filteredDefaults.length ? filteredDefaults : availableValues;
    },

    emitFieldUpdate(fieldKey, value) {
      this.$emit("update-field", {
        fieldKey,
        value,
      });
    },

    emitFieldData(fieldKey, optionKey, value) {
      this.$emit("update-field-data", {
        fieldKey,
        optionKey,
        value,
      });
    },

    toggleWebPushNotifications() {
      if (this.isEnabled) {
        this.lastAdminValue = [...this.adminValue];
        this.lastOwnerValue = [...this.ownerValue];
        this.emitFieldUpdate(this.adminBackupFieldKey, this.lastAdminValue);
        this.emitFieldUpdate(this.ownerBackupFieldKey, this.lastOwnerValue);
        this.emitFieldData(
          this.adminFieldKey,
          "disabledDisplayValue",
          this.lastAdminValue
        );
        this.emitFieldData(
          this.ownerFieldKey,
          "disabledDisplayValue",
          this.lastOwnerValue
        );
        this.emitFieldUpdate(this.adminFieldKey, []);
        this.emitFieldUpdate(this.ownerFieldKey, []);
        return;
      }

      const nextAdminValue = this.lastAdminValue.length
        ? this.lastAdminValue
        : this.adminBackupValue.length
          ? this.adminBackupValue
          : this.defaultValues(this.adminField, DEFAULT_ADMIN_EVENTS);
      const nextOwnerValue = this.lastOwnerValue.length
        ? this.lastOwnerValue
        : this.ownerBackupValue.length
          ? this.ownerBackupValue
          : this.defaultValues(this.ownerField, DEFAULT_OWNER_EVENTS);

      this.emitFieldData(this.adminFieldKey, "disabledDisplayValue", []);
      this.emitFieldData(this.ownerFieldKey, "disabledDisplayValue", []);
      this.emitFieldUpdate(this.adminBackupFieldKey, []);
      this.emitFieldUpdate(this.ownerBackupFieldKey, []);
      this.emitFieldUpdate(this.adminFieldKey, nextAdminValue);
      this.emitFieldUpdate(this.ownerFieldKey, nextOwnerValue);
    },
  },
};
</script>
