<template>
  <div
    class="cptm-checkbox-accordion"
    :class="{ 'cptm-checkbox-accordion--open': isOpen }"
  >
    <button
      type="button"
      class="cptm-checkbox-accordion__toggle"
      :aria-expanded="isOpen ? 'true' : 'false'"
      @click.stop="toggleOpen"
    >
      <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2.5"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="cptm-checkbox-accordion__chevron"
      >
        <polyline points="9 18 15 12 9 6" />
      </svg>
      <span class="cptm-checkbox-accordion__title" v-html="labelText"></span>
    </button>

    <div v-if="isOpen" class="cptm-checkbox-accordion__body">
      <label
        v-for="option in parsedOptions"
        :key="option.value"
        class="cptm-checkbox-accordion__row"
      >
        <span
          class="cptm-checkbox-accordion__label"
          v-html="option.label"
        ></span>

        <span
          class="cptm-checkbox-accordion__switch"
          :class="{ 'cptm-checkbox-accordion__switch--active': isSelected(option.value) }"
          aria-hidden="true"
        ></span>

        <input
          type="checkbox"
          class="cptm-checkbox-accordion__input"
          :checked="isSelected(option.value)"
          :value="option.value"
          @change="toggleValue(option.value)"
        />
      </label>

      <p v-if="!parsedOptions.length" class="cptm-checkbox-accordion__empty">
        No options available.
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: "settings-checkbox-array-accordion",

  props: {
    field: {
      type: Object,
      default: () => ({}),
    },
    fieldKey: {
      type: String,
      required: true,
    },
    isHighlighted: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      isOpen: !!this.isHighlighted,
    };
  },

  computed: {
    labelText() {
      return this.field.label || "";
    },

    currentValue() {
      return this.normalizeValue(this.field.value);
    },

    parsedOptions() {
      if (!Array.isArray(this.field.options)) {
        return [];
      }

      return this.field.options.map((option) => ({
        value:
          typeof option.value === "undefined" || option.value === null
            ? ""
            : String(option.value),
        label: option.label || "",
      }));
    },

    optionValues() {
      return this.parsedOptions.map((option) => option.value);
    },
  },

  watch: {
    isHighlighted(isHighlighted) {
      if (isHighlighted) {
        this.isOpen = true;
      }
    },
  },

  methods: {
    normalizeValue(value) {
      if (!Array.isArray(value)) {
        return [];
      }

      return value.map((item) => String(item));
    },

    isSelected(value) {
      return this.currentValue.includes(String(value));
    },

    toggleOpen() {
      this.isOpen = !this.isOpen;
    },

    toggleValue(value) {
      const optionValue = String(value);
      let selectedValues = [...this.currentValue];

      if (selectedValues.includes(optionValue)) {
        selectedValues = selectedValues.filter((item) => item !== optionValue);
      } else {
        selectedValues.push(optionValue);
      }

      const knownSelectedValues = this.optionValues.filter((item) =>
        selectedValues.includes(item)
      );
      const unknownSelectedValues = selectedValues.filter(
        (item) => !this.optionValues.includes(item)
      );

      this.$emit("update-field", {
        fieldKey: this.fieldKey,
        value: [...knownSelectedValues, ...unknownSelectedValues],
      });
    },
  },
};
</script>
