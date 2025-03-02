<template>
    <div class="cptm-form-repeater-container">
      <draggable v-model="active_fields_groups" handle=".cptm-form-repeater-drag-handle">
        <div v-for="(group, index) in active_fields_groups" :key="group.id" :id="'cptm-form-repeater-group-' + (index + 1)" class="cptm-form-repeater-group">
          <div class="">
            <!-- Drag Button (Initially Disabled) -->
            <button class="cptm-form-repeater-drag-handle cptm-form-repeater-drag-btn" :disabled="active_fields_groups.length <= 1">⠿</button>
  
            <!-- Use options.options_value.value as placeholder only for the first group -->
            <input 
              v-model="group.name" 
              class="cptm-form-repeater-input" 
              :placeholder="index === 0 ? value : ''" 
            />
  
            <!-- Remove Button (Initially Disabled) -->
            <button @click="removeGroup(index)" class="cptm-form-repeater-remove-btn" :disabled="active_fields_groups.length <= 1">Remove</button>
          </div>
        </div>
      </draggable>
  
      <button @click="addNewOptionGroup" class="cptm-form-repeater-add-group-btn" :disabled="active_fields_groups.length >= maxGroups">
        {{ addNewButtonLabel }}
      </button>
    </div>
  </template>
  
  <script>
  import draggable from "vuedraggable"; // Install via npm: `npm install vuedraggable`
  import helpers from '../../mixins/helpers';
  
  export default {
    name: 'repeater-field',
    mixins: [helpers],
    components: {
      draggable,
    },
    props: {
      fieldId: {
        type: [String, Number],
        required: false,
        default: '',
      },
      name: {
        type: String,
        default: '',
      },
      label: {
        type: String,
        default: '',
      },
      value: {
        type: String,
        default: 'e.g Service Quality, Price...',
      },
      addNewButtonLabel: {
        type: String,
        default: 'Add new',
      },
      removeButtonLabel: {
        type: String,
        default: 'Remove',
      },
      validation: {
        type: Array,
        required: false,
      },
      maxGroup: {
        type: Number,
        default: 5,  // Set the default maxGroup value directly as a number
      },
    },
    data() {
      return {
        active_fields_groups: [{ id: 1, name: "" }], // Initially 1 group
        maxGroups: this.maxGroup, // Set maxGroup directly here
      };
    },
    methods: {
      addNewOptionGroup() {
        if (this.active_fields_groups.length < this.maxGroups) {
          this.active_fields_groups.push({ id: Date.now(), name: "" });
        }
      },
      removeGroup(index) {
        if (this.active_fields_groups.length > 1) {
          this.active_fields_groups.splice(index, 1);
        }
      },
    },
  };
  </script>
  