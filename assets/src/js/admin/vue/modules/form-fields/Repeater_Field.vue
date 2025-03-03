<template>
  <div>
    <!-- Draggable container for repeating fields -->
    <draggable class="form-repeater__container" v-model="active_fields_groups" handle=".form-repeater__drag-handle" @start="onDragStart" @end="onDragEnd">
      <div v-for="(group, index) in active_fields_groups" :key="group.id" :id="'form-repeater__group-' + (index + 1)" class="form-repeater__group">
        <!-- Drag handle button for the group -->
        <button class="form-repeater__drag-handle form-repeater__drag-btn" :disabled="active_fields_groups.length <= 1">
          <i class="uil uil-draggabledots"></i>
        </button>

        <!-- Input field for the group name -->
        <input 
          :value="group.name" 
          :class="{'form-repeater__input-value-added': group.name}"
          class="form-repeater__input" 
          :placeholder="placeholder"
          @input="updateGroupField( index, $event.target.value )"
        />

        <!-- Button to remove a group -->
        <button
          @click="handleTrashClick(index)"
          class="form-repeater__remove-btn"
          :disabled="active_fields_groups.length <= 1"
        >
          <i class="uil uil-trash-alt"></i>
        </button>
      </div>
    </draggable>

    <!-- Button to add a new option group -->
    <button @click="addNewOptionGroup" class="form-repeater__add-group-btn" :disabled="active_fields_groups.length >= maxGroups">
      <i class="uil uil-plus"></i>{{ addNewButtonLabel }}
    </button>

    <!-- Confirmation Modal -->
    <confirmation-modal
      :visible="showConfirmationModal"
      :widgetName="widgetName"
      @confirm="trashWidget"
      @cancel="closeConfirmationModal"
      :reviewDeleteTitle="reviewDeleteTitle"
      :reviewDeleteMsg="reviewDeleteMsg"
      :reviewCancelBtnText="reviewCancelBtnText"
    />
  </div>
</template>

<script>
import draggable from "vuedraggable"; 
import helpers from '../../mixins/helpers';
import ConfirmationModal from "../form-builder-modules/widget-component/Form_Builder_Widget_Trash_Confirmation.vue";

export default {
  name: 'repeater-field',
  mixins: [helpers],
  components: {
    draggable,
    ConfirmationModal,
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
      type: Array,
      default: [],
    },
    fieldType: {
      type: String,
      default: 'text',
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
      default: 5,  
    },
    reviewDeleteTitle: {
      type: String,
      default: 'will completely remove from the single listing page.',
    },
    reviewDeleteMsg: {
      type: String,
      default: 'Yes, Delete It', // Default text
    },
    reviewCancelBtnText: {
      type: String,
      default: 'Keep It', // Default text
    },
  },

  created() {
    console.log('Repeater Field created with value:', this.value);

    if ( this.value.length ) {
      this.active_fields_groups = this.value.slice( 0, this.maxGroups );
    }
  },

  watch: {
    active_fields_groups() {
      console.log('active_fields_groups:', this.active_fields_groups);

      this.$emit( 'update', this.active_fields_groups );
    },
  },

  data() {
    return {
      showConfirmationModal: false,
      active_fields_groups: [{ id: 1, name: "" }],
      maxGroups: this.maxGroup,
      isDragging: false,
      widgetName: '',
      groupToDelete: null,  // To store the index of the group to be deleted
    };
  },
  mounted() {
    document.addEventListener("mousedown", this.handleClickOutside);
  },
  beforeDestroy() {
    document.removeEventListener("mousedown", this.handleClickOutside);
  },
  methods: {
    // Handle click outside to close the confirmation modal
    handleClickOutside(event) {
      const modal = this.$el.querySelector('.confirmation-modal');
      if (modal && !modal.contains(event.target)) {
        this.closeConfirmationModal();
      }
    },

    updateGroupField( index, value ) {

      console.log('Updating group at index:', index, 'with value:', value); 

      this.active_fields_groups.splice( index, 1, { id: this.active_fields_groups[index].id, name: value });
    },
    
    // Prepares and shows the confirmation modal for deletion
    handleTrashClick(index) {
      console.log('Preparing to remove group at index:', index);
      this.groupToDelete = index;  // Store the index of the group to be deleted
      this.widgetName = this.active_fields_groups[index].name 
                        ? this.active_fields_groups[index].name 
                        : `Group ${index + 1}`;  // Default to 'Group X' if name is not defined
      this.openConfirmationModal();  // Show the confirmation modal
    },

    // Show the confirmation modal
    openConfirmationModal() {
      this.showConfirmationModal = true;
      const parentElement = this.$el.closest('.atbdp-cpt-manager');
      if (parentElement) {
        parentElement.classList.add('directorist-overlay-visible');
      }
    },

    // Close the confirmation modal
    closeConfirmationModal() {
      this.showConfirmationModal = false;
      const parentElement = this.$el.closest('.atbdp-cpt-manager');
      if (parentElement) {
        parentElement.classList.remove('directorist-overlay-visible');
      }
    },

    // Perform the deletion of the group
    trashWidget() {
      console.log("trashWidget called!");
      if (this.groupToDelete !== null && this.groupToDelete >= 0 && this.groupToDelete < this.active_fields_groups.length) {
        console.log('Deleting group at index:', this.groupToDelete);
        this.active_fields_groups.splice(this.groupToDelete, 1);  // Remove the group
        this.closeConfirmationModal();  // Close the modal after deletion
      } else {
        console.error('Invalid group index for deletion');
      }
    },

    // Triggered when dragging starts
    onDragStart() {
      this.isDragging = true;  
    },

    // Triggered when dragging ends
    onDragEnd() {
      this.isDragging = false;  
    },

    // Add a new group to the active fields
    addNewOptionGroup() {
      if (this.active_fields_groups.length < this.maxGroups) {
        this.active_fields_groups.push({ id: Date.now(), name: "" });
      }
    },
  },
};
</script>
