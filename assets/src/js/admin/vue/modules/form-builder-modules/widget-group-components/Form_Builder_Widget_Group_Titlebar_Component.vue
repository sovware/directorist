<template>
  <div
    class="cptm-form-builder-group-title-area"
    :class="widgetsExpanded ? 'expanded' : ''"
  >
    <h3 class="cptm-form-builder-group-title">
      <span v-html="label"></span>
      <a
        href="#"
        class="cptm-form-builder-header-action-link cptm-ml-5 cptm-link-light"
        v-if="groupFields && typeof groupFields === 'object'"
        @click.prevent="toggleExpandGroup"
      >
        <span class="fa fa-cog" aria-hidden="true"></span>
        {{ optionsText }}
      </a>
    </h3>

    <div
      class="cptm-form-builder-group-title-actions"
      v-if="groupData && groupData.fields && groupData.fields.length"
    >
      <a
        href="#"
        class="cptm-form-builder-header-action-link"
        :class="widgetsExpanded ? 'action-collapse-down' : 'action-collapse-up'"
        @click.prevent="$emit('toggle-expand-widgets')"
      >
        <span aria-hidden="true" class="uil uil-angle-down"></span>
      </a>
    </div>
  </div>
</template>

<script>
export default {
  name: "form-builder-widget-group-titlebar-component",
  props: {
    groupData: {
      default: "",
    },
    groupSettings: {
      default: "",
    },
    groupFields: {
      default: "",
    },
    widgetsExpanded: {
      default: false,
    },
  },

  data() {
    return {
      optionsExpanded: false, // Track the state of the options text
    };
  },

  computed: {
    label() {
      let label = "";

      if (!this.groupData.defaultGroupLabel) {
        label = this.groupData.defaultGroupLabel;
      }

      if (!this.groupSettings.label) {
        label = this.groupData.label;
      }

      return label;
    },

    optionsText() {
      return this.optionsExpanded ? "Hide" : "Options";
    },
  },

  methods: {
    toggleExpandGroup() {
      this.optionsExpanded = !this.optionsExpanded;
      this.$emit("toggle-expand-group");
    },
  },
};
</script>
