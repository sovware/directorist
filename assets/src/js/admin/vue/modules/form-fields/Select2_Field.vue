<template>
  <div class="cptm-form-group" :class="formGroupClass">
    <label v-if="label.length">{{ label }}</label>

    <multiselect
      v-model="selected"
      :options="theOptions"
      :multiple="true"
      :close-on-select="false"
      :clear-on-select="true"
      :preserve-search="true"
      :track-by="'value'"
      :label="'label'"
      :placeholder="label"
      class="cptm-multiselect"
    >
      <!-- Custom Tag Slot -->
      <template #tag="{ option, remove }">
        <span class="multiselect__tag">
          {{ option.label }}
          <span
            class="multiselect__tag__remove"
            @mousedown.prevent
            @click.stop="remove(option)"
            >×</span
          >
        </span>
      </template>

      <!-- Custom Option Slot -->
      <template #option="{ option, index, search, isSelected }">
        <div
          class="multiselect__option"
          :class="{
            'is-selected': isSelected,
            'multiselect__option--selected': isSelected, // custom class
          }"
        >
          {{ option.label }}
        </div>
      </template>
    </multiselect>

    <div class="cptm-form-group-feedback" v-if="validationMessages">
      <div class="cptm-form-alert" :class="'cptm-' + validationMessages.type">
        {{ validationMessages.message }}
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import { mapState } from "vuex";
import helpers from "./../../mixins/helpers";
import validation from "./../../mixins/validation";

export default {
  name: "select2-field",
  mixins: [helpers, validation],
  components: { Multiselect },
  model: {
    event: "input",
  },
  props: {
    label: {
      type: String,
      required: false,
      default: "",
    },
    value: {
      type: [Array, String],
      required: false,
      default: () => [],
    },
    options: {
      type: Array,
      required: false,
    },
    defaultOption: {
      type: Object,
      required: false,
    },
    optionsSource: {
      type: Object,
      required: false,
    },
    name: {
      type: [String, Number],
      required: false,
      default: "",
    },
    placeholder: {
      type: [String, Number],
      required: false,
      default: "",
    },
    validation: {
      type: Array,
      required: false,
    },
  },

  mounted() {
    this.setup();
  },

  watch: {
    local_value() {
      this.$emit("update", this.local_value);
    },

    theOptions() {
      if (!this.valueIsValid(this.local_value)) {
        this.local_value = [];
      }
    },

    selected: {
      handler(newValue) {
        if (Array.isArray(newValue)) {
          const onlyValues = newValue.map((item) => item.value);
          this.local_value = onlyValues;
          this.$emit("input", onlyValues); // emit the array of values
        }
      },
      deep: true,
    },
  },

  computed: {
    ...mapState({
      fields: "fields",
    }),

    theOptions() {
      const decodeHtml = (html) => {
        const txt = document.createElement("textarea");
        txt.innerHTML = html;
        return txt.value;
      };

      let rawOptions = [];

      if (this.hasOptionsSource) {
        rawOptions = this.hasOptionsSource;
      } else if (!this.options || typeof this.options !== "object") {
        rawOptions = this.defaultOption ? [this.defaultOption] : [];
      } else {
        rawOptions = this.options;
      }

      // Handle grouped and flat options
      const decodeLabels = (opts) =>
        opts.map((opt) => {
          if (opt.options && Array.isArray(opt.options)) {
            // group with nested options
            return {
              ...opt,
              options: decodeLabels(opt.options),
            };
          }
          return {
            ...opt,
            label: decodeHtml(opt.label),
          };
        });

      return decodeLabels(rawOptions);
    },

    hasOptionsSource() {
      if (!this.optionsSource || typeof this.optionsSource !== "object") {
        return false;
      }

      if (typeof this.optionsSource.where !== "string") {
        return false;
      }

      let target_fields = this.getTergetFields({
        path: this.optionsSource.where,
      });

      if (!target_fields || typeof target_fields !== "object") {
        return false;
      }

      let filter_by = null;
      if (
        typeof this.optionsSource.filter_by === "string" &&
        this.optionsSource.filter_by.length
      ) {
        filter_by = this.optionsSource.filter_by;
      }

      if (filter_by) {
        filter_by = this.getTergetFields({
          path: this.optionsSource.filter_by,
        });
      }

      let has_sourcemap = false;

      if (
        this.optionsSource.source_map &&
        typeof this.optionsSource.source_map === "object"
      ) {
        has_sourcemap = true;
      }

      if (!has_sourcemap && !filter_by) {
        return target_fields;
      }

      if (has_sourcemap) {
        target_fields = this.mapDataByMap(
          target_fields,
          this.optionsSource.source_map,
        );
      }

      if (filter_by) {
        target_fields = this.filterDataByValue(target_fields, filter_by);
      }

      if (!target_fields && typeof target_fields !== "object") {
        return false;
      }

      return target_fields;
    },
  },

  data() {
    return {
      local_value: [],
      selected: [],
    };
  },

  methods: {
    setup() {
      if (this.defaultOption || typeof this.defaultOption === "object") {
        this.default_option = this.defaultOption;
      }

      if (this.valueIsValid(this.value)) {
        this.local_value = this.value;

        // Set the initial selected options from local_value
        this.selected = this.getSelectedOptions(this.local_value);
      }
    },

    valueIsValid(value) {
      // Ensure we always work with an array
      const valuesToCheck = Array.isArray(value) ? value : [value];

      const optionsValues = this.theOptions
        .map((option) => {
          return typeof option.value !== "undefined"
            ? !isNaN(Number(option.value))
              ? Number(option.value)
              : option.value
            : null;
        })
        .filter((v) => v !== null); // filter out any nulls just in case

      const allValuesValid = valuesToCheck.every((val) =>
        optionsValues.includes(!isNaN(Number(val)) ? Number(val) : val),
      );

      return allValuesValid;
    },

    // Helper method to get the selected option objects based on the values
    getSelectedOptions(values) {
      // Ensure the values are numbers
      const numericValues = values.map((value) => Number(value));

      // Flatten the options if necessary
      const flatOptions = Array.isArray(this.theOptions[0])
        ? this.theOptions.flat()
        : this.flattenOptions(this.theOptions);

      // Filter the options by comparing numeric values
      return flatOptions.filter((opt) =>
        numericValues.includes(Number(opt.value)),
      );
    },

    // Helper method to flatten grouped options
    flattenOptions(options) {
      return options.reduce((acc, opt) => {
        if (opt.options && Array.isArray(opt.options)) {
          return acc.concat(opt.options);
        }
        acc.push(opt);
        return acc;
      }, []);
    },
  },
};
</script>
