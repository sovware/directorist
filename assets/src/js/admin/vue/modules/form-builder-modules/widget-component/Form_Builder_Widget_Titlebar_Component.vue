<template>
  <div class="cptm-form-builder-group-field-item-header">
    <h4 class="cptm-title-3">
      <span class="cptm-title-icon" :class="icon"></span>
      <span v-html="label"></span>
      <span v-if="sublabel.length" class="cptm-text-gray cptm-px-5" v-html="sublabel"></span>
      <span v-if="info.length" class="cptm-title-info" :data-info="info">
        <i class="uil uil-question-circle"></i>
      </span>
    </h4>

    <div class="cptm-form-builder-group-field-item-header-actions">
      <a
        href="#"
        class="cptm-form-builder-header-action-link"
        :class="expanded ? 'action-collapse-down' : 'action-collapse-up'"
        @click.prevent="$emit('toggle-expand')"
      >
        <span aria-hidden="true" class="uil uil-angle-down"></span>
      </a>
    </div>
  </div>
</template>

<script>
export default {
  name: "form-builder-widget-titlebar-component",
  props: {
    label: {
      default: "",
    },
    sublabel: {
      default: "",
    },
    icon: {
      default: "",
    },
    info: {
      default: "",
    },
    expanded: {
      default: false,
    },
  },
  watch: {
    info(newVal) {
      if (newVal.length) {
        this.setZIndex(1);
      } else {
        this.setZIndex(0);
      }
    },
  },
  mounted() {
    if (this.info.length) {
      this.setZIndex(1);
    }
  },
  methods: {
    setZIndex(zIndexValue) {
      const parent = this.$el.closest(".cptm-form-builder-group-fields .directorist-draggable-list-item-wrapper");
      if (parent) {
        parent.style.zIndex = zIndexValue;
      }

      // If you want to set z-index to 0 for other sibling elements
      const allParents = document.querySelectorAll('.cptm-form-builder-group-fields .directorist-draggable-list-item-wrapper');
      allParents.forEach((el) => {
        if (el !== parent) {
          el.style.zIndex = 0;
        }
      });
    },
  },
};
</script>
