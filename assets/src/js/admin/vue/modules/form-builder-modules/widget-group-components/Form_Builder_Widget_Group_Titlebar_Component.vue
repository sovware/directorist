<!-- <template>
  <div
    class="cptm-form-builder-group-header-content"
    :class="widgetsExpanded ? 'expanded' : ''"
  >
    <div class="cptm-form-builder-header-toggle">
      <a
        href="#"
        class="cptm-form-builder-header-toggle-link"
        :class="widgetsExpanded ? 'action-collapse-down' : 'action-collapse-up'"
        @click.prevent="$emit('toggle-expand-widgets')"
      >
        <span aria-hidden="true" class="uil uil-angle-down"></span>
      </a>
    </div>

    <h3 class="cptm-form-builder-group-title">
      <span class="cptm-form-builder-group-title-icon">
        <span aria-hidden="true" class="uil uil-angle-down"></span>
      </span>
      <span class="cptm-form-builder-group-title-label">
        <span v-html="label"></span>
      </span>
    </h3>
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
  },
};
</script> -->
