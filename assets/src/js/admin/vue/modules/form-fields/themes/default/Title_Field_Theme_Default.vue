<template>
  <div class="cptm-form-title-field">
    <div>
      <h2 class="cptm-form-title-field__label" v-html="title"></h2>
      <div
        class="cptm-form-title-field__description"
        v-if="description.length"
        v-html="description"
      ></div>
    </div>
  </div>
</template>

<script>
import note_feild from "../../../../mixins/form-fields/note-field";

export default {
  name: "title-field-theme-default",
  mixins: [note_feild],
};
</script>