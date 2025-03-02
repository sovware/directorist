<template>
    <div>
      <draggable v-model="active_fields_groups" handle=".drag-handle">
        <div v-for="(group, index) in active_fields_groups" :key="group.id" :id="'group-' + (index + 1)" class="group">
          <h3>Group {{ index + 1 }}</h3>
            <!-- Drag Button (Initially Disabled) -->
            <button class="drag-handle" :disabled="active_fields_groups.length <= 1">⠿</button>
          <input v-model="group.name" placeholder="Enter group name" />

          <!-- Remove Button (Initially Disabled) -->
          <button @click="removeGroup(index)" :disabled="active_fields_groups.length <= 1">Remove</button>
        </div>
      </draggable>
  
      <button @click="addNewOptionGroup" :disabled="active_fields_groups.length >= maxGroups">
        Add New Group
      </button>
    </div>
  </template>
  
  <script>
  import draggable from "vuedraggable";
  
  export default {
    components: {
      draggable,
    },
    data() {
      return {
        active_fields_groups: [{ id: 1, name: "" }], // Initially 1 group
        maxGroups: 5,
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
  