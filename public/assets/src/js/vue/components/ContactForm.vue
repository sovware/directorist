<template>
  <div>
    <h2>Contact Form</h2>
    <form-reader :form="contact_form"/>
  </div>
</template>

<script>
import form_reader from './form-builder/FormReader.vue';
import forms from '../forms';

export default {
  name: 'contact-form',
  components: {
    'form-reader': form_reader
  },

  data() {
    return {
      contact_form: forms[0]
    }
  }
}
</script>
