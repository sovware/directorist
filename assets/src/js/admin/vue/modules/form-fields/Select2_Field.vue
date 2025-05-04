<template>
  <div class="cptm-form-group test-select2">
    <label class="typo__label">Tagging</label>
    <multiselect
      id="tagging"
      v-model="localValue"
      tag-placeholder="Add this as new tag"
      placeholder="Search or add a tag"
      label="label"
      track-by="id"
      :options="localOptions"
      :multiple="true"
      :taggable="true"
      @tag="addTag"
    />
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import helpers from "./../../mixins/helpers";
import validation from "./../../mixins/validation";

export default {
  name: "select2-field",
  mixins: [helpers, validation],
  components: {
    Multiselect,
  },
  props: {
    label: {
      type: String,
      default: "",
    },
    value: {
      type: [Array, String],
      default: () => [],
    },
    options: {
      type: Array,
      default: () => [],
    },
    defaultOption: Object,
    optionsSource: Object,
    name: {
      type: [String, Number],
      default: "",
    },
    placeholder: {
      type: [String, Number],
      default: "",
    },
    validation: Array,
  },

  data() {
    return {
      localValue: [],
      localOptions: [],
    };
  },

  watch: {
    value: {
      immediate: true,
      handler(newVal) {
        this.localValue = Array.isArray(newVal) ? [...newVal] : [];
      },
    },
    options: {
      immediate: true,
      handler(newOptions) {
        this.localOptions = Array.isArray(newOptions) ? [...newOptions] : [];
      },
    },
    localValue(newVal) {
      this.$emit("input", newVal); // Emit update to parent
    },
  },

  methods: {
    addTag(newTag) {
      const tag = {
        id: Date.now(), // Simple unique ID
        label: newTag,
        value: newTag,
      };

      this.localOptions.push(tag);
      this.localValue.push(tag);
    },
  },
};
</script>
