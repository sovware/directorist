<template>
  <div
    class="cptm-form-builder-group-header-section"
    :class="groupFieldsExpandState ? 'expanded' : ''"
  >
    <!-- Group Header Top -->
    <div class="cptm-form-builder-group-header">
      <!-- Group Header Titlebar -->
      <draggable-list-item
        :can-drag="isEnabledGroupDragging"
        @drag-start="$emit('drag-start')"
        @drag-end="$emit('drag-end')"
      >
        <div class="cptm-form-builder-group-field-item-drag">
          <span aria-hidden="true" class="uil uil-draggabledots"></span>
        </div>
      </draggable-list-item>

      <form-builder-widget-group-titlebar-component
        v-bind="$props"
        :widgets-expanded="widgetsExpanded"
        @toggle-expand-group="toggleGroupFieldsExpand"
        @toggle-expand-widgets="$emit('toggle-expand-widgets')"
      />

      <!-- Group Header Actions -->
      <div
        class="cptm-form-builder-group-actions-dropdown cptm-form-builder-group-actions-dropdown--group"
        ref="dropdownContent"
      >
        <a
          href="#"
          class="cptm-form-builder-group-actions-dropdown-btn"
          v-if="canTrash"
          @click.prevent="toggleGroupExpandedDropdown"
        >
          <span aria-hidden="true" class="uil uil-ellipsis-h"></span>
        </a>
        <!-- Widget Action Dropdown -->
        <slide-up-down :active="groupExpandedDropdown" :duration="500">
          <div
            class="cptm-form-builder-group-actions-dropdown-content"
            :class="groupExpandedDropdown ? 'expanded' : ''"
          >
            <a
              href="#"
              class="cptm-form-builder-field-item-action-link"
              @click.prevent="handleGroupDelete"
            >
              <span aria-hidden="true" class="uil uil-trash-alt"></span>
              Remove Section
            </a>
          </div>
        </slide-up-down>
      </div>
    </div>

    <!-- Group Header Body -->
    <slide-up-down :active="groupFieldsExpandState" :duration="500">
      <div class="cptm-form-builder-group-options">
        <field-list-component
          :field-list="finalGroupFields"
          :value="groupData"
          @update="$emit('update-group-field', $event)"
        />
      </div>
    </slide-up-down>

    <!-- Confirmation Modal -->
    <confirmation-modal
      :visible="showConfirmationModal"
      :groupName="groupName"
      @confirm="trashGroup"
      @cancel="closeConfirmationModal"
    />
  </div>
</template>

<script>
import { findObjectItem, isObject } from "../../../../../helper";
import ConfirmationModal from "./Form_Builder_Widget_Trash_Confirmation.vue";

export default {
  name: "form-builder-widget-group-header-component",
  components: {
    ConfirmationModal,
  },

  props: {
    groupData: {
      default: "",
    },
    groupSettings: {
      default: "",
    },
    groupFields: {
      default: "",
    },
    avilableWidgets: {
      default: "",
    },
    widgetsExpanded: {
      default: "",
    },
    canTrash: {
      default: false,
    },
    currentDraggingGroup: {
      default: "",
    },
    isEnabledGroupDragging: {
      default: false,
    },
    forceExpandStateTo: {
      default: "",
    },
  },

  created() {
    this.setup();
  },

  watch: {
    groupData() {
      this.setup();
    },
  },

  computed: {
    groupFieldsExpandState() {
      let state = this.groupFieldsExpanded;

      if ("expand" === this.forceExpandStateTo) {
        state = true;
      }

      if (!this.isEnabledGroupDragging) {
        state = false;
      }

      return state;
    },
  },

  data() {
    return {
      finalGroupFields: {},
      header_title_component_props: {},
      groupFieldsExpanded: false,
      groupExpandedDropdown: false,
      showConfirmationModal: false,
      groupName: "",
    };
  },

  mounted() {
    document.addEventListener("mousedown", this.handleClickOutside);
  },

  beforeDestroy() {
    document.removeEventListener("mousedown", this.handleClickOutside);
  },

  methods: {
    setup() {
      if (isObject(this.groupFields)) {
        this.finalGroupFields = this.groupFields;
      }

      const widgetOptions = this.findWidgetOptions(
        this.groupData,
        this.avilableWidgets
      );

      if (widgetOptions) {
        this.finalGroupFields = { ...this.finalGroupFields, ...widgetOptions };
      }
    },

    findWidgetOptions(groupData, avilableWidgets) {
      if (!isObject(groupData)) {
        return null;
      }

      if (!isObject(avilableWidgets)) {
        return null;
      }

      const widgetGroup = groupData.widget_group;
      const widgetName = groupData.widget_name;

      return findObjectItem(
        `${widgetGroup}.${widgetName}.options`,
        avilableWidgets,
        null
      );
    },

    toggleGroupFieldsExpand() {
      this.groupFieldsExpanded = !this.groupFieldsExpanded;
    },

    toggleGroupExpandedDropdown() {
      this.groupExpandedDropdown = !this.groupExpandedDropdown;
    },

    handleBlur() {
      setTimeout(() => {
        if (!this.isClickedInsideDropdown) {
          this.groupExpandedDropdown = false;
        }
      }, 100); // Delay to ensure clicks inside dropdown content are not missed
    },

    handleClickOutside(event) {
      if (this.groupExpandedDropdown && !this.$refs.dropdownContent.contains(event.target)) {
        this.groupExpandedDropdown = false; 
      }
    },

    handleGroupDelete() {
      this.groupExpandedDropdown = !this.groupExpandedDropdown;
      this.openConfirmationModal();
    },

    openConfirmationModal() {
      this.groupName = this.groupData.label;
      this.showConfirmationModal = true;

      // Add class to parent with class 'atbdp-cpt-manager'
      const parentElement = this.$el.closest('.atbdp-cpt-manager');
      if (parentElement) {
        parentElement.classList.add('trash-overlay-visible');
      }
    },

    closeConfirmationModal() {
      this.showConfirmationModal = false;
    },

    trashGroup() {
      this.$emit("trash-group");
      this.closeConfirmationModal();
    },
  },
};
</script>
