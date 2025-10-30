<template>
  <div
    class="cptm-form-builder-group-header-section"
    :class="[widgetsExpanded ? 'expanded' : '', { 'locked': groupData.lock }]"
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
          <div class="cptm-form-builder-header-toggle">
            <a
              href="#"
              class="cptm-form-builder-header-toggle-link"
              :class="
                widgetsExpanded
                  ? 'action-collapse-down'
                  : 'action-collapse-up' + ' ' + (canExpand ? '' : 'disabled')
              "
              @click.prevent="$emit('toggle-expand-widgets', groupKey)"
            >
              <span aria-hidden="true" class="uil uil-angle-down"></span>
            </a>
          </div>

          <h3 class="cptm-form-builder-group-title">
            <span class="cptm-form-builder-group-title-icon">
              <span
                v-html="getSearchIconContent()"
                v-if="getSearchGroup()"
              ></span>
              <span
                v-html="groupData.icon"
                v-else-if="groupData?.icon_type === 'svg'"
              ></span>
              <span aria-hidden="true" :class="groupData.icon" v-else></span>
            </span>
            <span class="cptm-form-builder-group-title-label">
              <span
                v-html="getSearchLabelContent()"
                v-if="getSearchGroup()"
              ></span>
              <span v-html="groupData.label" v-else></span>
            </span>
          </h3>

          <div class="cptm-form-builder-header-actions">
            <a
              href="#"
              class="cptm-form-builder-header-action-link"
              v-if="groupFields && typeof groupFields === 'object'"
              @click.prevent="toggleGroupFieldsExpand"
            >
              <span class="la la-cog" aria-hidden="true"></span>
            </a>
            <a
              href="#"
              class="cptm-form-builder-header-action-link"
              :class="widgetsExpanded ? 'disabled' : ''"
              @click.prevent="handleGroupDelete" 
              v-if="!groupData.lock"
            >
              <span aria-hidden="true" class="uil uil-trash-alt"></span>
            </a>
          </div>
        </div>
      </div>
    </draggable-list-item>

    <!-- Group Header Body -->
    <slide-up-down
      :active="groupFieldsExpandState"
      :duration="500"
      class="cptm-form-builder-group-options-wrapper"
    >
      <div class="cptm-form-builder-group-options">
        <div class="cptm-form-builder-group-options-header">
          <h3 class="cptm-form-builder-group-options-header-title">
            Configure Section
          </h3>
          <a
            href="#"
            class="cptm-form-builder-group-options-header-close"
            @click.prevent="toggleGroupFieldsExpand"
          >
            <span aria-hidden="true" class="uil uil-times"></span>
          </a>
        </div>
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
    groupKey: {
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
    expandedGroupFieldsKey: {
      default: null,
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
      // Check if this group is the one that should be expanded based on parent state
      let state = this.expandedGroupFieldsKey === this.groupKey;

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
      // Emit event to parent to handle accordion behavior
      // If this group is already expanded, collapse it (pass null), otherwise expand it
      const newExpandedKey = this.groupFieldsExpandState ? null : this.groupKey;
      this.$emit("toggle-group-fields-expand", newExpandedKey);
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

    getSearchGroup() {
      // Check if the group is a search group
      if (
        this.groupData.id === "basic" || this.groupData.id === "basic-search-form" ||
        this.groupData.id === "advanced" || this.groupData.id === "advanced-search-form"
      ) {
        return true;
      }

      return false;
    },

    getSearchIconContent() {
      let groupIcon = "";

      if (this.groupData.id === "basic" || this.groupData.id === "basic-search-form") {
        groupIcon = '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17.5 17.5L13.875 13.875M9.16667 5C11.4679 5 13.3333 6.86548 13.3333 9.16667M15.8333 9.16667C15.8333 12.8486 12.8486 15.8333 9.16667 15.8333C5.48477 15.8333 2.5 12.8486 2.5 9.16667C2.5 5.48477 5.48477 2.5 9.16667 2.5C12.8486 2.5 15.8333 5.48477 15.8333 9.16667Z" stroke="#141921" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
      }

      if (this.groupData.id === "advanced" || this.groupData.id === "advanced-search-form") {
        groupIcon = '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.5 6.66602L12.5 6.66602M12.5 6.66602C12.5 8.04673 13.6193 9.16602 15 9.16602C16.3807 9.16602 17.5 8.04673 17.5 6.66602C17.5 5.2853 16.3807 4.16602 15 4.16602C13.6193 4.16602 12.5 5.2853 12.5 6.66602ZM7.5 13.3327L17.5 13.3327M7.5 13.3327C7.5 14.7134 6.38071 15.8327 5 15.8327C3.61929 15.8327 2.5 14.7134 2.5 13.3327C2.5 11.952 3.61929 10.8327 5 10.8327C6.38071 10.8327 7.5 11.952 7.5 13.3327Z" stroke="#141921" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
      }

      return groupIcon;
    },

    getSearchLabelContent() {
      let groupLabel = "";
      if (this.groupData.id === "basic" || this.groupData.id === "basic-search-form") {
        groupLabel = "Search Bar";
      }

      if (this.groupData.id === "advanced" || this.groupData.id === "advanced-search-form") {
        groupLabel = "Search Filter";
      }

      return groupLabel;
    },
  },
};
</script>
