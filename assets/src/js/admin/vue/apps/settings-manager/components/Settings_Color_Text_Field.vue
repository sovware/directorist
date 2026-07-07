<template>
  <div class="cptm-form-group cptm-preview-background-color">
    <div class="atbdp-row">
      <div class="atbdp-col atbdp-col-4">
        <label v-if="labelText" v-html="labelText"></label>
        <p
          v-if="descriptionText"
          class="cptm-form-group-info"
          v-html="descriptionText"
        ></p>
      </div>

      <div class="atbdp-col atbdp-col-8">
        <div class="cptm-preview-background-color__control">
          <label
            class="cptm-preview-background-color__swatch"
            :style="{ backgroundColor: swatchColor }"
            :title="`Choose color for ${plainLabel}`"
          >
            <input
              type="color"
              :value="nativeColorValue"
              :aria-label="plainLabel"
              @input="updateValue($event.target.value)"
            />
          </label>

          <input
            type="text"
            class="cptm-preview-background-color__input"
            :value="fieldValue"
            autocomplete="off"
            spellcheck="false"
            @input="updateValue($event.target.value)"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "settings-color-text-field",

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
      return this.field.label || "";
    },

    plainLabel() {
      return this.stripHtml(this.labelText) || "Color";
    },

    descriptionText() {
      return this.field.description || "";
    },

    fieldValue() {
      if (typeof this.field.value === "undefined" || this.field.value === null) {
        return "";
      }

      return String(this.field.value);
    },

    nativeColorValue() {
      return this.toNativeColorValue(this.fieldValue);
    },

    swatchColor() {
      const value = this.fieldValue.trim();

      if (!value) {
        return "#ffffff";
      }

      if (
        typeof CSS !== "undefined" &&
        CSS.supports &&
        CSS.supports("color", value)
      ) {
        return value;
      }

      return this.nativeColorValue;
    },
  },

  methods: {
    stripHtml(value) {
      if (typeof value === "undefined" || value === null) {
        return "";
      }

      const element = document.createElement("div");
      element.innerHTML = String(value);

      return (element.textContent || element.innerText || "").trim();
    },

    normalizeHex(value) {
      const color = String(value || "").trim();
      const shortHex = color.match(/^#([0-9a-f]{3})$/i);

      if (shortHex) {
        return `#${shortHex[1]
          .split("")
          .map((char) => `${char}${char}`)
          .join("")
          .toLowerCase()}`;
      }

      if (/^#[0-9a-f]{6}$/i.test(color)) {
        return color.toLowerCase();
      }

      return "";
    },

    toNativeColorValue(value) {
      const normalizedHex = this.normalizeHex(value);

      if (normalizedHex) {
        return normalizedHex;
      }

      if (typeof document === "undefined") {
        return "#ffffff";
      }

      const context = document.createElement("canvas").getContext("2d");

      if (!context) {
        return "#ffffff";
      }

      context.fillStyle = "#ffffff";
      context.fillStyle = String(value || "").trim();

      return this.normalizeHex(context.fillStyle) || "#ffffff";
    },

    updateValue(value) {
      this.$emit("update-field", {
        fieldKey: this.fieldKey,
        value,
      });
    },
  },
};
</script>
