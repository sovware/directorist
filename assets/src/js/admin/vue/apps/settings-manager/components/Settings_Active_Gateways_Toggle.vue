<template>
  <div class="cptm-active-gateways-toggle">
    <div class="cptm-active-gateways-toggle__content">
      <label>{{ labelText }}</label>
      <p v-if="descriptionText">{{ descriptionText }}</p>
    </div>

    <button
      type="button"
      class="cptm-input-toggle"
      :class="{ active: isBankTransferEnabled }"
      :aria-pressed="isBankTransferEnabled ? 'true' : 'false'"
      @click="toggleBankTransfer"
    ></button>
  </div>
</template>

<script>
export default {
  name: "settings-active-gateways-toggle",

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
      return this.field.label || "Bank transfer";
    },

    descriptionText() {
      return this.field.description || "";
    },

    currentValue() {
      return this.normalizeValue(this.field.value);
    },

    isBankTransferEnabled() {
      return this.currentValue.includes("bank_transfer");
    },
  },

  methods: {
    normalizeValue(value) {
      if (Array.isArray(value)) {
        return value.map((item) => String(item));
      }

      if (value === null || typeof value === "undefined" || value === "") {
        return [];
      }

      return [String(value)];
    },

    toggleBankTransfer() {
      let nextValue = [...this.currentValue];

      if (this.isBankTransferEnabled) {
        nextValue = nextValue.filter((item) => item !== "bank_transfer");
      } else {
        nextValue.push("bank_transfer");
      }

      this.$emit("update-field", {
        fieldKey: this.fieldKey,
        value: nextValue,
      });
    },
  },
};
</script>
