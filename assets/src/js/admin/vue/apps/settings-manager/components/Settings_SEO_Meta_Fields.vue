<template>
  <div class="cptm-seo-meta-fields">
    <template v-for="pair in primaryPairs">
      <div
        v-if="fields[pair.titleField]"
        :key="pair.key + '-title'"
        class="cptm-seo-meta-row"
      >
        <label
          class="cptm-seo-meta-row__field cptm-seo-meta-row__field--title"
          :for="fieldId(pair.titleField)"
        >
          <span>{{ pair.titleLabel }}</span>
          <small v-if="pair.titleDescription">{{ pair.titleDescription }}</small>
          <input
            :id="fieldId(pair.titleField)"
            class="cptm-form-control cptm-seo-meta-row__input"
            type="text"
            :value="fieldValue(pair.titleField)"
            :title="fieldValue(pair.titleField)"
            @input="updateField(pair.titleField, $event.target.value)"
          />
        </label>
      </div>

      <div
        v-if="fields[pair.descriptionField]"
        :key="pair.key + '-description'"
        class="cptm-seo-meta-row"
      >
        <label
          class="cptm-seo-meta-row__field cptm-seo-meta-row__field--description"
          :for="fieldId(pair.descriptionField)"
        >
          <span>{{ pair.descriptionLabel }}</span>
          <small v-if="pair.descriptionHelp">{{ pair.descriptionHelp }}</small>
          <textarea
            :id="fieldId(pair.descriptionField)"
            class="cptm-form-control cptm-seo-meta-row__textarea"
            :value="fieldValue(pair.descriptionField)"
            :title="fieldValue(pair.descriptionField)"
            @input="updateField(pair.descriptionField, $event.target.value)"
          ></textarea>
        </label>
      </div>
    </template>

    <div
      v-if="advancedPairs.length"
      class="cptm-seo-meta-advanced"
      :class="{ 'cptm-seo-meta-advanced--open': isAdvancedOpen }"
    >
      <button
        type="button"
        class="cptm-seo-meta-advanced__toggle"
        :aria-expanded="isAdvancedOpen ? 'true' : 'false'"
        @click="toggleAdvanced"
      >
        <svg
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2.5"
          stroke-linecap="round"
          stroke-linejoin="round"
          class="cptm-seo-meta-advanced__chevron"
        >
          <polyline points="9 18 15 12 9 6" />
        </svg>
        {{ advancedLabel }}
      </button>

      <template v-if="isAdvancedOpen">
        <template v-for="pair in advancedPairs">
          <div
            v-if="fields[pair.titleField]"
            :key="pair.key + '-title'"
            class="cptm-seo-meta-row"
          >
            <label
              class="cptm-seo-meta-row__field cptm-seo-meta-row__field--title"
              :for="fieldId(pair.titleField)"
            >
              <span>{{ pair.titleLabel }}</span>
              <small v-if="pair.titleDescription">{{ pair.titleDescription }}</small>
              <input
                :id="fieldId(pair.titleField)"
                class="cptm-form-control cptm-seo-meta-row__input"
                type="text"
                :value="fieldValue(pair.titleField)"
                :title="fieldValue(pair.titleField)"
                @input="updateField(pair.titleField, $event.target.value)"
              />
            </label>
          </div>

          <div
            v-if="fields[pair.descriptionField]"
            :key="pair.key + '-description'"
            class="cptm-seo-meta-row"
          >
            <label
              class="cptm-seo-meta-row__field cptm-seo-meta-row__field--description"
              :for="fieldId(pair.descriptionField)"
            >
              <span>{{ pair.descriptionLabel }}</span>
              <small v-if="pair.descriptionHelp">{{ pair.descriptionHelp }}</small>
              <textarea
                :id="fieldId(pair.descriptionField)"
                class="cptm-form-control cptm-seo-meta-row__textarea"
                :value="fieldValue(pair.descriptionField)"
                :title="fieldValue(pair.descriptionField)"
                @input="updateField(pair.descriptionField, $event.target.value)"
              ></textarea>
            </label>
          </div>
        </template>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  name: "settings-seo-meta-fields",

  props: {
    fields: {
      type: Object,
      required: true,
    },
    pairs: {
      type: Array,
      default: () => [],
    },
    advancedLabel: {
      type: String,
      default: "Meta for the other pages",
    },
  },

  data() {
    return {
      isAdvancedOpen: true,
    };
  },

  computed: {
    visiblePairs() {
      return this.pairs.filter((pair) => this.pairShouldRender(pair));
    },

    primaryPairs() {
      return this.visiblePairs.filter((pair) => pair.primary);
    },

    advancedPairs() {
      return this.visiblePairs.filter((pair) => !pair.primary);
    },
  },

  methods: {
    pairShouldRender(pair) {
      return !!(
        pair &&
        (this.fields[pair.titleField] || this.fields[pair.descriptionField])
      );
    },

    fieldValue(fieldKey) {
      const value = this.fields[fieldKey]?.value;

      return value === false || typeof value === "undefined" || value === null
        ? ""
        : value;
    },

    fieldId(fieldKey) {
      return `settings-seo-meta-${fieldKey}`;
    },

    toggleAdvanced() {
      this.isAdvancedOpen = !this.isAdvancedOpen;
    },

    updateField(fieldKey, value) {
      this.$emit("update-field", {
        fieldKey,
        value,
      });
    },
  },
};
</script>
