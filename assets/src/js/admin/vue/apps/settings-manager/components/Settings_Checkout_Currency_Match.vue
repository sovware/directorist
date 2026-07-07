<template>
  <div class="cptm-checkout-currency-match">
    <div class="cptm-checkout-currency-match__content">
      <label>{{ labelText }}</label>
      <p v-if="descriptionText">{{ descriptionText }}</p>
    </div>

    <button
      type="button"
      class="cptm-input-toggle"
      :class="{ active: isMatchEnabled }"
      :aria-pressed="isMatchEnabled ? 'true' : 'false'"
      @click="toggleMatch"
    ></button>
  </div>
</template>

<script>
export default {
  name: "settings-checkout-currency-match",

  props: {
    config: {
      type: Object,
      default: () => ({}),
    },
    fields: {
      type: Object,
      default: () => ({}),
    },
    customMode: {
      type: Boolean,
      default: false,
    },
  },

  computed: {
    labelText() {
      return this.config.label || "Match display currency";
    },

    descriptionText() {
      return this.config.description || "";
    },

    displayCurrency() {
      return this.fields.g_currency?.value || "";
    },

    displayCurrencyPosition() {
      return this.fields.g_currency_position?.value || "";
    },

    checkoutCurrency() {
      return this.fields.payment_currency?.value || "";
    },

    checkoutCurrencyPosition() {
      return this.fields.payment_currency_position?.value || "";
    },

    valuesMatchDisplay() {
      return (
        this.normalizeCurrency(this.checkoutCurrency) ===
          this.normalizeCurrency(this.displayCurrency) &&
        String(this.checkoutCurrencyPosition || "") ===
          String(this.displayCurrencyPosition || "")
      );
    },

    isMatchEnabled() {
      return !this.customMode;
    },
  },

  watch: {
    displayCurrency() {
      this.syncCheckoutCurrency();
    },

    displayCurrencyPosition() {
      this.syncCheckoutCurrency();
    },
  },

  mounted() {
    this.$emit("set-custom-mode", !this.valuesMatchDisplay);
  },

  methods: {
    normalizeCurrency(value) {
      return String(value || "").trim().toUpperCase();
    },

    emitFieldUpdate(fieldKey, value) {
      this.$emit("update-field", {
        fieldKey,
        value,
      });
    },

    syncCheckoutCurrency() {
      if (this.customMode) {
        return;
      }

      if (this.checkoutCurrency !== this.displayCurrency) {
        this.emitFieldUpdate("payment_currency", this.displayCurrency);
      }

      if (this.checkoutCurrencyPosition !== this.displayCurrencyPosition) {
        this.emitFieldUpdate(
          "payment_currency_position",
          this.displayCurrencyPosition
        );
      }
    },

    toggleMatch() {
      if (this.isMatchEnabled) {
        this.$emit("set-custom-mode", true);
        return;
      }

      this.emitFieldUpdate("payment_currency", this.displayCurrency);
      this.emitFieldUpdate(
        "payment_currency_position",
        this.displayCurrencyPosition
      );
      this.$emit("set-custom-mode", false);
    },
  },
};
</script>
