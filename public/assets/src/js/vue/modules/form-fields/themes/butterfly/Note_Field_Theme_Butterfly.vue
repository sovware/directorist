<template>
  <div class="cptm-form-note">
    <i class="fa fa-info-circle"></i>
    <div>
      <h2 class="cptm-form-note-title" v-html="title"></h2>
      <div
        class="cptm-form-note-content"
        v-if="description.length"
        v-html="description"
      ></div>
    </div>
  </div>
</template>

<script>
import note_feild from "./../../../../mixins/form-fields/note-field";

export default {
  name: "toggle-field-theme-butterfly",
  mixins: [note_feild],
};
</script>