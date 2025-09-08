<template>
  <div
    class="cptm-form-builder-group-header-section"
    :class="widgetsExpanded || groupFieldsExpandState ? 'expanded' : ''"
  >
    <!-- Group Header Top -->
    <draggable-list-item
      :can-drag="isEnabledGroupDragging"
      @drag-start="$emit('drag-start')"
      @drag-end="$emit('drag-end')"
      :drag-handle="'.cptm-form-builder-group-item-drag'"
    >
      <div class="cptm-form-builder-group-header">
        <div class="cptm-form-builder-group-item-drag" v-if="draggable">
          <span aria-hidden="true" class="uil uil-draggabledots"></span>
        </div>

        <div class="cptm-form-builder-group-header-content">
          <div class="cptm-form-builder-header-toggle" v-if="canExpand">
            <a
              href="#"
              class="cptm-form-builder-header-toggle-link"
              :class="
                widgetsExpanded ? 'action-collapse-down' : 'action-collapse-up'
              "
              @click.prevent="$emit('toggle-expand-widgets')"
            >
              <span aria-hidden="true" class="uil uil-angle-down"></span>
            </a>
          </div>

          <h3 class="cptm-form-builder-group-title">
            <span class="cptm-form-builder-group-title-icon">
              <span
                v-html="groupData.icon"
                v-if="groupData?.icon_type === 'svg'"
              ></span>
              <span aria-hidden="true" :class="groupData?.icon" v-else></span>
            </span>
            <span class="cptm-form-builder-group-title-label">
              <span v-html="groupData.label"></span>
            </span>
          </h3>

          <div class="cptm-form-builder-header-actions" v-if="!groupData.lock">
            <a
              href="#"
              class="cptm-form-builder-header-action-link"
              v-if="groupFields && typeof groupFields === 'object'"
              @click.prevent="toggleGroupFieldsExpand"
            >
              <span class="fa fa-cog" aria-hidden="true"></span>
            </a>
            <a
              href="#"
              class="cptm-form-builder-header-action-link"
              :class="widgetsExpanded ? 'disabled' : ''"
              @click.prevent="handleGroupDelete"
            >
              <span aria-hidden="true" class="uil uil-trash-alt"></span>
            </a>
          </div>
        </div>
      </div>
    </draggable-list-item>

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
    canExpand: {
      default: true,
    },
    draggable: {
      default: true,
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
        this.avilableWidgets,
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
        null,
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
      if (
        this.groupExpandedDropdown &&
        !this.$refs.dropdownContent?.contains(event.target)
      ) {
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
      const parentElement = this.$el.closest(".atbdp-cpt-manager");
      if (parentElement) {
        parentElement.classList.add("directorist-overlay-visible");
      }
    },

    closeConfirmationModal() {
      this.showConfirmationModal = false;

      // Remove class to parent with class 'atbdp-cpt-manager'
      const parentElement = this.$el.closest(".atbdp-cpt-manager");
      if (parentElement) {
        parentElement.classList.remove("directorist-overlay-visible");
      }
    },

    trashGroup() {
      this.$emit("trash-group");
      this.closeConfirmationModal();
    },
  },
};
</script>
