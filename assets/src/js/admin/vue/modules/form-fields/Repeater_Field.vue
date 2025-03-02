<template>
  <div>
    <draggable class="form-repeater__container" v-model="active_fields_groups" handle=".form-repeater__drag-handle" @start="onDragStart"
    @end="onDragEnd">
      <div v-for="(group, index) in active_fields_groups" :key="group.id" :id="'form-repeater__group-' + (index + 1)" class="form-repeater__group">
        <!-- Drag Button (Initially Disabled) -->
        <button class="form-repeater__drag-handle form-repeater__drag-btn" :disabled="active_fields_groups.length <= 1">
          <i class="uil uil-draggabledots"></i>
        </button>

        <!-- Use options.options_value.value as placeholder only for the first group -->
        <input 
          v-model="group.name" 
          class="form-repeater__input" 
          :placeholder="placeholder"
        />

        <!-- Remove Button (Initially Disabled) -->
        <button @click="removeGroup(index)" class="form-repeater__remove-btn" :disabled="active_fields_groups.length <= 1">
          <i class="uil uil-trash-alt"></i>
        </button>
      </div>
    </draggable>

    <button @click="addNewOptionGroup" class="form-repeater__add-group-btn" :disabled="active_fields_groups.length >= maxGroups">
      <i class="uil uil-plus"></i>{{ addNewButtonLabel }}
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
        default: 'Service..',
      },
      placeholder: {
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
        isDragging: false,
      };
    },
    methods: {
      onDragStart() {
        this.isDragging = true;  // Set dragging to true
      },
      onDragEnd() {
        this.isDragging = false;  // Set dragging to false
      },
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
  