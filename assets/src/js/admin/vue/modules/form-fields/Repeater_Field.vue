<template>
  <div>
    <!-- Draggable container for repeating fields -->
    <Container
      class="form-repeater__container"
      group-name="repeater-fields"
      drag-handle-selector=".form-repeater__drag-handle"
      @drop="onDrop"
    >
      <Draggable
        v-for="(group, index) in active_fields_groups"
        :key="group.id"
        :data="{ group, index }"
      >
        <div
          :id="'form-repeater__group-' + (index + 1)"
          class="form-repeater__group"
        >
          <!-- Drag handle button for the group -->
          <button
            class="form-repeater__drag-handle form-repeater__drag-btn"
            :disabled="active_fields_groups.length <= 1"
          >
            <i class="uil uil-draggabledots"></i>
          </button>

          <!-- Input field for the group name -->
          <input
            :id="group.id"
            :value="group.value"
            :class="{ 'form-repeater__input-value-added': group.value }"
            class="form-repeater__input"
            :placeholder="placeholder"
            @input="updateGroupField(index, $event.target.value)"
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
      </Draggable>
    </Container>

    <!-- Button to add a new option group -->
    <button
      @click="addNewOptionGroup"
      class="form-repeater__add-group-btn"
      :disabled="active_fields_groups.length >= maxGroups"
    >
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
import { Container, Draggable } from "vue-dndrop";
import { applyDrag } from "../../helpers/vue-dndrop";
import helpers from "../../mixins/helpers";
import ConfirmationModal from "../form-builder-modules/widget-component/Form_Builder_Widget_Trash_Confirmation.vue";

export default {
  name: "repeater-field",
  mixins: [helpers],
  components: {
    Container,
    Draggable,
    ConfirmationModal,
  },
  props: {
    fieldId: {
      type: [String, Number],
      required: false,
      default: "",
    },
    name: {
      type: String,
      default: "",
    },
    label: {
      type: String,
      default: "",
    },
    value: {
      type: Array,
      default: [],
    },
    fieldType: {
      type: String,
      default: "text",
    },
    placeholder: {
      type: String,
      default: "e.g Service Quality, Price...",
    },
    addNewButtonLabel: {
      type: String,
      default: "Add new",
    },
    removeButtonLabel: {
      type: String,
      default: "Remove",
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
      default: "will completely remove from the single listing page.",
    },
    reviewDeleteMsg: {
      type: String,
      default: "Yes, Delete It", // Default text
    },
    reviewCancelBtnText: {
      type: String,
      default: "Keep It", // Default text
    },
  },

  created() {
    if (this.value.length) {
      // Ensure each group has a unique ID
      this.active_fields_groups = this.value
        .slice(0, this.maxGroups)
        .map((group, index) => ({
          id: group.id || Date.now() + index,
          value: group.value || "",
        }));
    } else {
      this.active_fields_groups = [{ id: Date.now(), value: "" }];
    }
  },

  watch: {
    active_fields_groups() {
      this.$emit("update", this.active_fields_groups);
    },
  },

  data() {
    return {
      showConfirmationModal: false,
      active_fields_groups: [{ id: 1, value: "" }],
      maxGroups: this.maxGroup,
      widgetName: "",
      groupToDelete: null, // To store the index of the group to be deleted
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
      const modal = this.$el.querySelector(".confirmation-modal");
      if (modal && !modal.contains(event.target)) {
        this.closeConfirmationModal();
      }
    },

    updateGroupField(index, value) {
      this.active_fields_groups.splice(index, 1, {
        id: this.active_fields_groups[index].id,
        value: value,
      });
    },

    // Prepares and shows the confirmation modal for deletion
    handleTrashClick(index) {
      this.groupToDelete = index; // Store the index of the group to be deleted
      this.widgetName = this.active_fields_groups[index].value
        ? this.active_fields_groups[index].value
        : `Group ${index + 1}`; // Default to 'Group X' if name is not defined
      this.openConfirmationModal(); // Show the confirmation modal
    },

    // Show the confirmation modal
    openConfirmationModal() {
      this.showConfirmationModal = true;
      const parentElement = this.$el.closest(".atbdp-cpt-manager");
      if (parentElement) {
        parentElement.classList.add("directorist-overlay-visible");
      }
    },

    // Close the confirmation modal
    closeConfirmationModal() {
      this.showConfirmationModal = false;
      const parentElement = this.$el.closest(".atbdp-cpt-manager");
      if (parentElement) {
        parentElement.classList.remove("directorist-overlay-visible");
      }
    },

    // Perform the deletion of the group
    trashWidget() {
      if (
        this.groupToDelete !== null &&
        this.groupToDelete >= 0 &&
        this.groupToDelete < this.active_fields_groups.length
      ) {
        this.active_fields_groups.splice(this.groupToDelete, 1); // Remove the group
        this.closeConfirmationModal(); // Close the modal after deletion
      } else {
        console.error("Invalid group index for deletion");
      }
    },

    // Handle drop event for drag and drop
    onDrop(dropResult) {
      this.active_fields_groups = applyDrag(
        this.active_fields_groups,
        dropResult,
      );
    },

    // Add a new group to the active fields
    addNewOptionGroup() {
      if (this.active_fields_groups.length < this.maxGroups) {
        this.active_fields_groups.push({ id: Date.now(), value: "" });
      }
    },
  },
};
</script>
