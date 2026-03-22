<template>
  <div
    class="cptm-form-builder-group-header-section"
    :class="[widgetsExpanded ? 'expanded' : '', { locked: groupData.lock }]"
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

            <!-- 
              EDITABLE GROUP LABEL FEATURE
              =============================
              This section implements inline editing for group labels.
              - Click on the label to enter edit mode
              - The label is replaced with an input field
              - Press Enter or blur to save changes
              - Press Escape to cancel editing
              - Search groups (Search Bar, Search Filter) are not editable
            -->

            <!-- Display Mode: Show the label as clickable text -->
            <span
              class="cptm-form-builder-group-title-label"
              @click="startEditingLabel"
              v-if="!isEditingLabel"
            >
              <!-- Show hardcoded label for search groups (not editable) -->
              <span
                v-html="getSearchLabelContent()"
                v-if="getSearchGroup()"
              ></span>
              <!-- Show editable label for regular groups -->
              <span v-html="groupData.label" v-else></span>
            </span>

            <!-- Edit Mode: Show input field for editing -->
            <input
              v-else
              type="text"
              class="cptm-form-builder-group-title-label-input"
              v-model="editedLabelValue"
              @blur="saveLabel"
              @keyup.enter="saveLabel"
              @keyup.esc="cancelEditingLabel"
              ref="labelInput"
              v-focus
            />
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
          :key="fieldListComponentKey"
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
    autoEditLabel: {
      default: false,
      type: Boolean,
    },
  },

  created() {
    this.setup();
  },

  watch: {
    groupData() {
      this.setup();
    },
    autoEditLabel(newValue, oldValue) {
      // Watcher triggers when the prop changes (false -> true or true -> false)
      // Only act when the value changes from false to true
      // Note: This watcher won't trigger on initial mount if the prop is already true
      // That's why we also check in the mounted() hook below
      if (
        newValue === true &&
        oldValue === false &&
        !this.getSearchGroup() &&
        !this.isEditingLabel
      ) {
        // Use $nextTick to ensure the component is fully rendered
        this.$nextTick(() => {
          if (!this.isEditingLabel) {
            this.startEditingLabel();
          }
        });
      }
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

    /**
     * Generate a stable key for field-list-component.
     *
     * Important: Do NOT include groupData.label (or any value the user is
     * actively typing) in this key. Doing so causes Vue to destroy and
     * recreate the field-list-component on every keystroke, which makes
     * the Section Name input lose focus after a single character.
     *
     * Reactivity for label changes coming from other sources (e.g. inline
     * header editing) is handled by the deep watcher on the `value` prop
     * inside Field_List_Component.vue.
     *
     * @returns {string} Stable key based on groupKey only
     */
    fieldListComponentKey() {
      return `group_${this.groupKey}`;
    },
  },

  data() {
    return {
      finalGroupFields: {},
      header_title_component_props: {},
      groupExpandedDropdown: false,
      showConfirmationModal: false,
      groupName: "",
      // Editable Label Feature: State management
      isEditingLabel: false, // Tracks whether the label is currently being edited
      editedLabelValue: "", // Stores the label value while editing (bound to input via v-model)
    };
  },

  mounted() {
    document.addEventListener("mousedown", this.handleClickOutside);

    // Handle case where component mounts with autoEditLabel already true
    // (Watcher won't trigger for initial prop value, only on changes)
    // This happens when Vue creates the component AFTER newlyCreatedGroupKey is set
    if (
      this.autoEditLabel === true &&
      !this.getSearchGroup() &&
      !this.isEditingLabel
    ) {
      // Use $nextTick to ensure everything is rendered before focusing
      this.$nextTick(() => {
        if (!this.isEditingLabel) {
          this.startEditingLabel();
        }
      });
    }
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
        this.groupData.id === "basic" ||
        this.groupData.id === "basic-search-form" ||
        this.groupData.id === "search-bar" ||
        this.groupData.id === "advanced" ||
        this.groupData.id === "advanced-search-form" ||
        this.groupData.id === "search-filter"
      ) {
        return true;
      }

      return false;
    },

    getSearchIconContent() {
      let groupIcon = "";

      if (
        this.groupData.id === "basic" ||
        this.groupData.id === "basic-search-form" ||
        this.groupData.id === "search-bar"
      ) {
        groupIcon =
          '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17.5 17.5L13.875 13.875M9.16667 5C11.4679 5 13.3333 6.86548 13.3333 9.16667M15.8333 9.16667C15.8333 12.8486 12.8486 15.8333 9.16667 15.8333C5.48477 15.8333 2.5 12.8486 2.5 9.16667C2.5 5.48477 5.48477 2.5 9.16667 2.5C12.8486 2.5 15.8333 5.48477 15.8333 9.16667Z" stroke="#141921" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
      }

      if (
        this.groupData.id === "advanced" ||
        this.groupData.id === "advanced-search-form" ||
        this.groupData.id === "search-filter"
      ) {
        groupIcon =
          '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.5 6.66602L12.5 6.66602M12.5 6.66602C12.5 8.04673 13.6193 9.16602 15 9.16602C16.3807 9.16602 17.5 8.04673 17.5 6.66602C17.5 5.2853 16.3807 4.16602 15 4.16602C13.6193 4.16602 12.5 5.2853 12.5 6.66602ZM7.5 13.3327L17.5 13.3327M7.5 13.3327C7.5 14.7134 6.38071 15.8327 5 15.8327C3.61929 15.8327 2.5 14.7134 2.5 13.3327C2.5 11.952 3.61929 10.8327 5 10.8327C6.38071 10.8327 7.5 11.952 7.5 13.3327Z" stroke="#141921" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
      }

      return groupIcon;
    },

    getSearchLabelContent() {
      let groupLabel = "";
      if (
        this.groupData.id === "basic" ||
        this.groupData.id === "basic-search-form" ||
        this.groupData.id === "search-bar"
      ) {
        groupLabel = "Search Bar";
      }

      if (
        this.groupData.id === "advanced" ||
        this.groupData.id === "advanced-search-form" ||
        this.groupData.id === "search-filter"
      ) {
        groupLabel = "Search Filter";
      }

      return groupLabel;
    },

    /**
     * Start editing the group label
     *
     * This method is triggered when the user clicks on the group label.
     * It switches from display mode to edit mode by:
     * 1. Checking if the group is editable (search groups are not editable)
     * 2. Setting isEditingLabel to true (which shows the input field)
     * 3. Extracting plain text from the label (handles HTML labels)
     * 4. Auto-focusing and selecting the input text for better UX
     *
     * @returns {void}
     */
    startEditingLabel() {
      // Don't allow editing for search groups (Search Bar, Search Filter)
      // These have hardcoded labels that shouldn't be changed
      if (this.getSearchGroup()) {
        return;
      }

      // Enter edit mode - this will hide the label span and show the input
      this.isEditingLabel = true;

      // Extract plain text from label (in case it contains HTML)
      // This ensures users edit the actual text content, not HTML tags
      this.editedLabelValue = this.getPlainTextFromLabel(
        this.groupData.label || "",
      );

      // Wait for Vue to render the input, then focus and select all text
      // This provides better UX - user can immediately start typing to replace the label
      this.$nextTick(() => {
        if (this.$refs.labelInput) {
          this.$refs.labelInput.focus();
          this.$refs.labelInput.select();
        }
      });
    },

    /**
     * Extract plain text from a label that may contain HTML
     *
     * Since group labels can be rendered with v-html (allowing HTML content),
     * we need to extract just the text content when editing. This method:
     * 1. Validates the input is a string
     * 2. Creates a temporary DOM element
     * 3. Sets the HTML content and extracts the text
     * 4. Returns plain text without HTML tags
     *
     * Example:
     * Input:  "<strong>My Label</strong>"
     * Output: "My Label"
     *
     * @param {string} label - The label that may contain HTML
     * @returns {string} Plain text content without HTML tags
     */
    getPlainTextFromLabel(label) {
      // Validate input - return empty string if invalid
      if (!label || typeof label !== "string") {
        return "";
      }

      // Create a temporary div element to parse HTML
      // This is a safe way to extract text from HTML without affecting the DOM
      const tempDiv = document.createElement("div");
      tempDiv.innerHTML = label;

      // Extract text content (textContent is preferred, innerText as fallback)
      // textContent gets all text including hidden elements
      // innerText only gets visible text (respects CSS)
      return tempDiv.textContent || tempDiv.innerText || "";
    },

    /**
     * Save the edited label
     *
     * This method is called when:
     * - User presses Enter key (@keyup.enter)
     * - User clicks outside the input (@blur)
     *
     * It:
     * 1. Trims whitespace from the edited value
     * 2. Compares with the current label (as plain text)
     * 3. Only emits update event if the value actually changed
     * 4. Exits edit mode (returns to display mode)
     *
     * The update event follows the same pattern as other group field updates:
     * - Event: "update-group-field"
     * - Payload: { key: "label", value: "new label text" }
     * - Parent component (Form_Builder_Field.vue) handles the update
     *
     * @param {Event} event - The blur or keyup event (not directly used, but kept for consistency)
     * @returns {void}
     */
    saveLabel(event) {
      // Get the trimmed value from the input (via v-model binding)
      const newLabel = this.editedLabelValue.trim();

      // Get the current label value as plain text for comparison
      // This ensures we compare text-to-text, not text-to-HTML
      const currentLabel = this.getPlainTextFromLabel(
        this.groupData.label || "",
      );

      // Only emit update if:
      // 1. The new label is not empty
      // 2. The new label is different from the current label
      // This prevents unnecessary updates and API calls
      if (newLabel && newLabel !== currentLabel) {
        // Emit update event to parent component
        // The parent will update the groupData.label and persist the change
        this.$emit("update-group-field", {
          key: "label", // Field name to update
          value: newLabel, // New label value
        });
      }

      // Exit edit mode regardless of whether value changed
      // This closes the input and shows the label again
      this.isEditingLabel = false;
      this.editedLabelValue = "";
    },

    /**
     * Cancel editing the label
     *
     * This method is called when:
     * - User presses Escape key (@keyup.esc)
     *
     * It discards any changes and returns to display mode without saving.
     * The original label value is preserved.
     *
     * @returns {void}
     */
    cancelEditingLabel() {
      // Exit edit mode without saving
      // This discards any changes made in the input field
      this.isEditingLabel = false;
      this.editedLabelValue = "";
    },
  },

  /**
   * Custom Vue Directives
   * =====================
   *
   * focus: Auto-focus directive
   * ---------------------------
   * This directive automatically focuses an element when it's inserted into the DOM.
   * Used on the label input field to provide immediate focus when edit mode starts.
   *
   * Note: We also use $nextTick in startEditingLabel() to ensure the element exists
   * before focusing. The directive provides an additional layer of focus handling.
   */
  directives: {
    focus: {
      /**
       * Called when the element is inserted into the DOM
       * @param {HTMLElement} el - The element the directive is bound to
       */
      inserted(el) {
        el.focus();
      },
    },
  },
};
</script>
