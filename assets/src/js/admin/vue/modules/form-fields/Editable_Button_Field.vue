<template>
  <div class="directorist-input-wrap directorist-footer-wrap">
    <div class="directorist-input" v-if="!isButtonEditable">
      <button
        type="button"
        class="cptm-btn"
        @click="showEditableButton"
        data-info="Click to edit button text"
      >
        <span class="cptm-save-text" v-html="value"></span>
        <span class="cptm-save-icon la la-pen"></span>
      </button>
    </div>

    <div class="directorist-input" v-if="isButtonEditable">
      <component
        is="text-field"
        ref="formGroup"
        :value="value"
        @enter="hideEditableButton"
        @blur="hideEditableButton"
        @update="$emit('update', $event)"
        @do-action="$emit('do-action', $event)"
        @validate="$emit('validate', $event)"
      />
    </div>
  </div>
</template>


<script>
import feild_helper from "./../../mixins/form-fields/helper";
import props from "./../../mixins/form-fields/input-field-props";

export default {
  name: "editable-button-field",
  mixins: [props, feild_helper],

  data() {
    return {
      isButtonEditable: false,
    };
  },

  methods: {
    showEditableButton() {
      this.isButtonEditable = true;
      this.$nextTick(() => {
        const inputElement = this.$refs.formGroup.$el.querySelector('input');
        if (inputElement) {
          inputElement.focus();
        }
      });
    },
    hideEditableButton() {
      this.isButtonEditable = false;
    },
  },
};
</script>
