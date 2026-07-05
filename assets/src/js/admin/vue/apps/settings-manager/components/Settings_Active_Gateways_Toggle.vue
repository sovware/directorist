<template>
  <div class="cptm-active-gateways-toggle">
    <div
      class="cptm-active-gateways-toggle__options"
      role="group"
      :aria-label="labelText"
    >
      <label
        class="cptm-active-gateways-toggle__option"
        v-for="option in gatewayOptions"
        :key="option.value"
      >
        <span
          class="cptm-active-gateways-toggle__label"
          v-html="option.label"
        ></span>
        <span
          class="cptm-active-gateways-toggle__switch"
          :class="{ 'cptm-active-gateways-toggle__switch--active': isGatewayEnabled(option.value) }"
          aria-hidden="true"
        ></span>
        <input
          type="checkbox"
          class="cptm-active-gateways-toggle__checkbox"
          :value="option.value"
          :checked="isGatewayEnabled(option.value)"
          @change="toggleGateway(option.value)"
        />
      </label>

      <p
        class="cptm-active-gateways-toggle__empty"
        v-if="!gatewayOptions.length"
      >
        No payment gateways available.
      </p>
    </div>
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
      return this.field.label || "Payment Methods";
    },

    currentValue() {
      return this.normalizeValue(this.field.value);
    },

    gatewayOptions() {
      if (!Array.isArray(this.field.options)) {
        return [];
      }

      return this.field.options
        .map((option) => ({
          value:
            typeof option.value === "undefined" ? "" : String(option.value),
          label: option.label || "",
        }))
        .filter((option) => option.value);
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

    isGatewayEnabled(gateway) {
      return this.currentValue.includes(String(gateway));
    },

    toggleGateway(gateway) {
      gateway = String(gateway);
      let nextValue = [...this.currentValue];

      if (this.isGatewayEnabled(gateway)) {
        nextValue = nextValue.filter((item) => item !== gateway);
      } else {
        nextValue.push(gateway);
      }

      this.$emit("update-field", {
        fieldKey: this.fieldKey,
        value: nextValue,
      });
    },
  },
};
</script>
