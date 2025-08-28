<template>
  <div class="cptm-option-card" :class="mainWrapperClass">
    <div class="cptm-option-card-header">
      <div class="cptm-option-card-header-title-section">
        <h3 class="cptm-option-card-header-title">{{ title }}</h3>
        <div class="cptm-header-action-area">
          <a
            href="#"
            class="cptm-header-action-link cptm-header-action-close"
            @click.prevent="$emit('close')"
          >
            <span class="fa fa-times"></span>
          </a>
        </div>
      </div>
    </div>

    <div class="cptm-option-card-body">
      <template v-if="local_fields">
        <component
          v-for="(field, field_key) in local_fields"
          :is="field.type + '-field'"
          v-bind="field"
          :key="fieldKeys[field_key]"
          @update="updateFieldData($event, field_key)"
        >
        </component>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  name: "options-window",

  model: {
    prop: "fields",
    event: "update",
  },

  props: {
    id: {
      type: [String, Number],
      default: "",
    },
    title: {
      type: String,
      default: "Edit",
    },
    fields: {
      type: Object,
    },
    active: {
      type: Boolean,
      default: false,
    },
    animation: {
      type: String,
      default: "cptm-animation-slide-up",
    },
    bottomAchhor: {
      type: Boolean,
      default: false,
    },
  },

  created() {
    this.init();
  },

  watch: {
    fields: {
      handler(newFields, oldFields) {
        console.log("@@handler", { newFields, oldFields });
        if (newFields && newFields !== oldFields) {
          // Only update if fields actually changed
          this.local_fields = { ...newFields };
          this.$emit("update", this.local_fields);
          console.log("@@local_fields", { local_fields: this.local_fields });
        }
      },
    },
  },

  computed: {
    mainWrapperClass() {
      return {
        active: this.active,
        [this.animation]: true,
      };
    },

    // Generate unique keys for components to ensure proper re-rendering
    fieldKeys() {
      if (!this.local_fields) return {};
      const keys = {};
      Object.keys(this.local_fields).forEach((key) => {
        const field = this.local_fields[key];
        keys[key] =
          `${key}-${field.value || field.id || field.updated || Date.now()}`;
      });
      return keys;
    },
  },

  data() {
    return {
      local_fields: null,
    };
  },

  methods: {
    init() {
      if (this.fields) {
        this.local_fields = { ...this.fields };
      }
    },

    updateFieldData(value, field_key) {
      // Use Vue.set to ensure reactivity
      // this.$set(this.local_fields[field_key], "value", value);
      this.local_fields[field_key].value = value;
      // this.$emit("update", this.local_fields);

      console.log("@@updateFieldData - options window", {
        local_fields: this.local_fields,
      });
    },
  },
};
</script>
