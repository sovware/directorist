<template>
  <div class="cptm-form-group" :class="formGroupClass">
    <label v-if="label.length">
      <component :is="labelType">{{ label }}</component>
    </label>

    <p
      class="cptm-form-group-info"
      v-if="description.length"
      v-html="description"
    ></p>

    <div v-if="editor" :id="editorID" class="cptm-form-control"></div>

    <textarea
      v-else
      name=""
      :cols="cols"
      :rows="rows"
      :placeholder="placeholder"
      class="cptm-form-control"
      v-model="local_value"
    ></textarea>

    <form-field-validatior
      :section-id="sectionId"
      :field-id="fieldId"
      :root="root"
      :value="local_value"
      :rules="rules"
      v-model="validationLog"
      @validate="$emit('validate', $event)"
    />
  </div>
</template>

<script>
import textarea_field from "./../../../../mixins/form-fields/textarea-field";

export default {
  name: "textarea-field-theme-default",
  mixins: [textarea_field],

  props: {
    editor: {
      required: false,
      default: "",
    },
    editorID: {
      required: false,
      default: "",
    },
    fieldId: {
      required: false,
      default: "",
    },
    value: {
      required: false,
      default: "",
    },
  },

  data() {
    return {
      local_value: this.value,
      editorInstance: null,
    };
  },

  watch: {
    value(newValue) {
      if (newValue !== this.local_value) {
        this.local_value = newValue;
      }
    },
    local_value(newValue) {
      this.$emit("input", newValue);
    },
  },

  mounted() {
    this.initializeEditor();
  },

  beforeDestroy() {
    this.destroyEditor();
  },

  methods: {
    initializeEditor() {
      if (!this.editor || !this.editorID || this.editorInstance) return;

      let editorID = this.editorID;
      let value = this.local_value;

      tinymce.init({
        selector: `#${editorID}`,
        plugins: "link",
        toolbar: "undo redo | formatselect | bold italic | link",
        menubar: false,
        branding: false,
        init_instance_callback: (editor) => {
          editor.setContent(value);
          editor.on("Change KeyUp", () => {
            this.local_value = editor.getContent();
          });
        },
      });

      this.editorInstance = tinymce.get(editorID);
    },

    destroyEditor() {
      if (this.editorInstance) {
        this.editorInstance.destroy();
      }
    },
  },

  updated() {
    this.editorInstance = null; // Make sure to clean up
    this.initializeEditor();
  },
};
</script>
