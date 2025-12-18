<template>
  <div class="directorist-input-wrap directorist-footer-wrap">
    <label :for="id" class="directorist-input-label">
      <svg
        width="20"
        height="20"
        viewBox="0 0 20 20"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M2.5 2.5H15.8333"
          stroke="#141B34"
          stroke-width="1.5"
          stroke-linecap="square"
          stroke-linejoin="round"
        />
        <path
          d="M2.5 5.83398H10"
          stroke="#141B34"
          stroke-width="1.5"
          stroke-linecap="square"
          stroke-linejoin="round"
        />
        <path
          d="M17.5 9.16602V17.4993H2.5V9.16602H17.5Z"
          stroke="#141B34"
          stroke-width="1.5"
          stroke-linecap="square"
          stroke-linejoin="round"
        />
      </svg>
      Listing form submit button text
    </label>
    <div class="directorist-input" v-if="!isButtonEditable">
      <button
        type="button"
        class="cptm-btn"
        :id="id"
        @click="showEditableButton"
        data-info="Click box to edit button text"
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
        const inputElement = this.$refs.formGroup.$el.querySelector("input");
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
