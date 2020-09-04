<template>
  <div class="">
    Manage Form
    <form-builder :form="contact_form"/>
  </div>
</template>

<script>
import form_builder from './form-builder/FormBuilder.vue';
import forms from '../forms';

export default {
  name: 'manage_form',
  components: {
    'form-builder': form_builder
  },

  data() {
    return {
      contact_form: forms[0]
    }
  }
}
</script>