<template>
  <div class="cptm-form-builder" :class="fieldKey">
    <div class="cptm-form-builder-sidebar cptm-col-sticky">
      <div
        class="cptm-form-builder-action"
        v-if="
          [
            'submission_form_fields',
            'search_form_fields',
            'single_listing_header',
            'single_listings_contents',
            'listings_card_grid_view',
            'listings_card_list_view',
          ].includes(fieldKey)
        "
      >
        <div class="cptm-form-builder-action-title">Form fields</div>
        <a
          href="#"
          class="directorist-row-tooltip cptm-form-builder-action-btn"
          :data-tooltip="video?.description"
          data-flow="bottom-right"
          @click.prevent="openModal()"
          v-if="video"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="14"
            height="14"
            viewBox="0 0 14 14"
            fill="none"
          >
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M3.94256 2.33333H7.14074C7.6103 2.33332 7.99785 2.33331 8.31355 2.35911C8.64143 2.3859 8.94285 2.44339 9.22596 2.58765C9.665 2.81135 10.022 3.16831 10.2457 3.60735C10.3899 3.89046 10.4474 4.19187 10.4742 4.51976C10.4981 4.81257 10.4999 5.16718 10.5 5.59171L11.6396 4.45212C11.7511 4.34058 11.8607 4.23096 11.9567 4.15052C12.0424 4.07876 12.223 3.93485 12.473 3.91517C12.7522 3.8932 13.0251 4.00622 13.207 4.21921C13.3699 4.40993 13.3958 4.63932 13.4056 4.75068C13.4167 4.87549 13.4167 5.03051 13.4166 5.18822V8.81177C13.4167 8.96948 13.4167 9.1245 13.4056 9.24931C13.3958 9.36067 13.3699 9.59006 13.207 9.78078C13.0251 9.99377 12.7522 10.1068 12.473 10.0848C12.223 10.0651 12.0424 9.92123 11.9567 9.84947C11.8607 9.76904 11.7511 9.65941 11.6396 9.54787L10.5 8.40828C10.4999 8.83281 10.4981 9.18742 10.4742 9.48023C10.4474 9.80812 10.3899 10.1095 10.2457 10.3926C10.022 10.8317 9.665 11.1886 9.22596 11.4123C8.94285 11.5566 8.64144 11.6141 8.31355 11.6409C7.99784 11.6667 7.6103 11.6667 7.14072 11.6667H3.94257C3.473 11.6667 3.08545 11.6667 2.76975 11.6409C2.44186 11.6141 2.14045 11.5566 1.85734 11.4123C1.41829 11.1886 1.06134 10.8317 0.837632 10.3926C0.693379 10.1095 0.635883 9.80812 0.609093 9.48023C0.5833 9.16453 0.583306 8.77699 0.583313 8.30742V5.69257C0.583306 5.22301 0.5833 4.83546 0.609093 4.51976C0.635883 4.19187 0.693379 3.89046 0.837632 3.60735C1.06134 3.16831 1.41829 2.81135 1.85734 2.58765C2.14045 2.44339 2.44186 2.3859 2.76975 2.35911C3.08545 2.33331 3.47299 2.33332 3.94256 2.33333ZM9.33331 5.71666C9.33331 5.21699 9.33286 4.87732 9.31141 4.61477C9.29051 4.35903 9.25264 4.22824 9.20615 4.13701C9.0943 3.91748 8.91582 3.73901 8.6963 3.62715C8.60507 3.58067 8.47428 3.5428 8.21854 3.5219C7.95599 3.50045 7.61632 3.5 7.11665 3.5H3.96665C3.46698 3.5 3.1273 3.50045 2.86475 3.5219C2.60901 3.5428 2.47822 3.58067 2.38699 3.62715C2.16747 3.73901 1.98899 3.91748 1.87714 4.13701C1.83065 4.22824 1.79278 4.35903 1.77189 4.61477C1.75043 4.87732 1.74998 5.21699 1.74998 5.71666V8.28333C1.74998 8.783 1.75043 9.12267 1.77189 9.38522C1.79278 9.64097 1.83065 9.77175 1.87714 9.86298C1.98899 10.0825 2.16747 10.261 2.38699 10.3728C2.47822 10.4193 2.60901 10.4572 2.86475 10.4781C3.1273 10.4995 3.46698 10.5 3.96665 10.5H7.11665C7.61632 10.5 7.95599 10.4995 8.21854 10.4781C8.47428 10.4572 8.60507 10.4193 8.6963 10.3728C8.91582 10.261 9.0943 10.0825 9.20615 9.86298C9.25264 9.77175 9.29051 9.64097 9.31141 9.38522C9.33286 9.12267 9.33331 8.783 9.33331 8.28333V5.71666ZM10.7416 7L12.25 8.50837V5.49162L10.7416 7Z"
              fill="currentColor"
            />
          </svg>
          Learn
        </a>
      </div>
      <div class="cptm-form-builder-sidebar-content">
        <template v-for="(widget_group, widget_group_key) in widgets">
          <form-builder-widget-list-section-component
            :field-id="fieldId"
            v-bind="widget_group"
            :widget-group="widget_group_key"
            :selected-widgets="active_widget_fields"
            :active-widget-groups="active_widget_groups"
            @update-widget-list="updateWidgetList"
            @drag-start="
              handleWidgetListItemDragStart(widget_group_key, $event)
            "
            @drag-end="handleWidgetListItemDragEnd(widget_group_key, $event)"
          />
        </template>
      </div>
    </div>
    <div class="cptm-form-builder-content">
      <div
        class="cptm-form-builder-action"
        v-if="fieldKey === 'submission_form_fields'"
      >
        <div class="cptm-form-builder-action-title">Customize listing form</div>
        <a
          href="#"
          target="_blank"
          class="directorist-row-tooltip cptm-form-builder-action-btn"
          data-tooltip="View the form"
          data-flow="bottom-right"
          @click="saveData()"
        >
          <svg
            width="16"
            height="16"
            viewBox="0 0 16 16"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M4.23904 5.49535C3.23485 6.33346 2.53211 7.31833 2.177 7.88061C2.15344 7.91792 2.13696 7.94405 2.12319 7.96673C2.1141 7.9817 2.1081 7.99205 2.10409 7.99927C2.10409 7.99954 2.10409 7.99981 2.10409 8.00008C2.10409 8.00035 2.10409 8.00062 2.10409 8.00089C2.1081 8.00811 2.1141 8.01846 2.12319 8.03343C2.13696 8.05611 2.15344 8.08224 2.177 8.11955C2.53211 8.68183 3.23485 9.6667 4.23904 10.5048C5.24166 11.3416 6.50463 12.0001 8.00019 12.0001C9.49574 12.0001 10.7587 11.3416 11.7613 10.5048C12.7655 9.6667 13.4683 8.68183 13.8234 8.11955C13.8469 8.08224 13.8634 8.05611 13.8772 8.03343C13.8863 8.01846 13.8923 8.0081 13.8963 8.00089C13.8963 8.00062 13.8963 8.00035 13.8963 8.00008C13.8963 7.99981 13.8963 7.99954 13.8963 7.99927C13.8923 7.99206 13.8863 7.9817 13.8772 7.96673C13.8634 7.94405 13.8469 7.91792 13.8234 7.88061C13.4683 7.31833 12.7655 6.33346 11.7613 5.49535C10.7587 4.65855 9.49574 4.00008 8.00019 4.00008C6.50463 4.00008 5.24166 4.65855 4.23904 5.49535ZM3.38469 4.4717C4.53709 3.50989 6.09241 2.66675 8.00019 2.66675C9.90797 2.66675 11.4633 3.50989 12.6157 4.4717C13.7665 5.4322 14.5555 6.54294 14.9507 7.16865C14.9559 7.17691 14.9613 7.18535 14.9668 7.19397C15.0452 7.3174 15.147 7.47765 15.1985 7.70219C15.24 7.88349 15.24 8.11667 15.1985 8.29797C15.147 8.52251 15.0452 8.68277 14.9668 8.80619C14.9613 8.81481 14.9559 8.82325 14.9507 8.83152C14.5555 9.45722 13.7665 10.568 12.6157 11.5285C11.4633 12.4903 9.90797 13.3334 8.00019 13.3334C6.09241 13.3334 4.53709 12.4903 3.38469 11.5285C2.23385 10.568 1.44483 9.45722 1.04967 8.83152C1.04445 8.82325 1.03908 8.81481 1.03361 8.80619C0.955196 8.68277 0.853387 8.52251 0.801919 8.29797C0.760363 8.11667 0.760363 7.88349 0.801919 7.70219C0.853387 7.47765 0.955197 7.3174 1.03361 7.19397C1.03908 7.18535 1.04445 7.17691 1.04967 7.16865C1.44483 6.54294 2.23385 5.4322 3.38469 4.4717ZM8.00019 6.66675C7.26381 6.66675 6.66686 7.2637 6.66686 8.00008C6.66686 8.73646 7.26381 9.33341 8.00019 9.33341C8.73657 9.33341 9.33352 8.73646 9.33352 8.00008C9.33352 7.2637 8.73657 6.66675 8.00019 6.66675ZM5.33352 8.00008C5.33352 6.52732 6.52743 5.33341 8.00019 5.33341C9.47295 5.33341 10.6669 6.52732 10.6669 8.00008C10.6669 9.47284 9.47295 10.6667 8.00019 10.6667C6.52743 10.6667 5.33352 9.47284 5.33352 8.00008Z"
              fill="#4D5761"
            />
          </svg>
          Preview
        </a>
      </div>
      <div class="cptm-form-builder-active-fields">
        <div class="cptm-form-builder-active-fields-container cptm-col-sticky">
          <draggable-list-item-wrapper
            list-id="widget-group"
            :is-dragging-self="
              currentDraggingGroup &&
              widget_group_key === currentDraggingGroup.widget_group_key
            "
            :droppable="currentDraggingGroup"
            v-for="(widget_group, widget_group_key) in active_widget_groups"
            :key="widget_group_key"
            @drop="handleGroupDrop(widget_group_key, $event)"
          >
            <form-builder-widget-group-component
              :group-key="widget_group_key"
              :field-id="fieldId"
              :active-widgets="active_widget_fields"
              :avilable-widgets="avilable_widgets"
              :group-data="widget_group"
              :group-settings="groupSettingsProp"
              :group-fields="groupFields"
              :widget-is-dragging="widgetIsDragging"
              :current-dragging-group="currentDraggingGroup"
              :current-dragging-widget="currentDraggingWidget"
              :is-enabled-group-dragging="isEnabledGroupDragging"
              @update-group-field="updateGroupField(widget_group_key, $event)"
              @update-widget-field="updateWidgetField"
              @trash-widget="trashWidget(widget_group_key, $event)"
              @trash-group="trashGroup(widget_group_key)"
              @widget-drag-start="
                handleWidgetDragStart(widget_group_key, $event)
              "
              @widget-drag-end="handleWidgetDragEnd()"
              @drop-widget="handleWidgetDrop(widget_group_key, $event)"
              @group-drag-start="handleGroupDragStart(widget_group_key)"
              @group-drag-end="handleGroupDragEnd()"
              @append-widget="handleAppendWidget(widget_group_key)"
            />
          </draggable-list-item-wrapper>

          <div
            class="cptm-form-builder-active-fields-footer"
            v-if="showAddNewGroupButton"
          >
            <button
              type="button"
              class="cptm-btn cptm-btn-secondery"
              @click="addNewGroup()"
              v-html="addNewGroupButtonLabel"
            ></button>
          </div>
        </div>
      </div>
    </div>

    <!-- Video Popup Modal -->
    <form-builder-widget-modal-component
      v-if="modalContent"
      :modalOpened="showModal"
      :content="modalContent"
      :type="modalContent.type"
      @close-modal="closeModal"
    />
  </div>
</template>

<script>
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { findObjectItem, isObject } from "../../../../helper";
import helpers from "../../mixins/helpers";

export default {
  name: "form-builder",
  mixins: [helpers],

  props: {
    fieldId: {
      type: [String, Number],
      required: false,
      default: "",
    },
    fieldKey: {
      type: String,
      required: false,
      default: "",
    },
    widgets: {
      default: false,
    },
    generalSettings: {
      default: false,
    },
    groupSettings: {
      default: false,
    },
    groupFields: {
      default: false,
    },
    value: {
      default: "",
    },
    video: {
      type: Object,
    },
  },

  created() {
    this.setupActiveWidgetFields();

    if (this.$root.fields) {
      this.$store.commit("updateFields", this.$root.fields);
    }

    if (this.$root.layouts) {
      this.$store.commit("updatelayouts", this.$root.layouts);
    }

    if (this.$root.options) {
      this.$store.commit("updateOptions", this.$root.options);
    }

    if (this.$root.config) {
      this.$store.commit("updateConfig", this.$root.config);
    }
  },

  mounted() {
    this.setupActiveWidgetGroups();
  },

  watch: {
    finalValue() {
      this.$emit("update", this.finalValue);
    },
  },

  computed: {
    finalValue() {
      return {
        fields: this.active_widget_fields,
        groups: this.active_widget_groups,
      };
    },

    widgetIsDragging() {
      return this.currentDraggingWidget ? true : false;
    },

    groupSettingsProp() {
      if (!this.generalSettings) {
        return this.groupSettings;
      }
      if (typeof this.generalSettings.minGroup === "undefined") {
        return this.groupSettings;
      }

      if (this.active_widget_groups.length <= this.groupSettings.minGroup) {
        this.groupSettings.canTrash = false;
      }

      return this.groupSettings;
    },

    showGroupDragToggleButton() {
      let show_button = true;

      if (!this.active_widget_groups) {
        show_button = false;
      }

      if (
        this.groupSettings &&
        typeof this.groupSettings.draggable !== "undefined" &&
        !this.groupSettings.draggable
      ) {
        show_button = false;
      }

      return show_button;
    },

    showAddNewGroupButton() {
      let show_button = true;

      if (
        this.generalSettings &&
        typeof this.generalSettings.allowAddNewGroup !== "undefined" &&
        !this.generalSettings.allowAddNewGroup
      ) {
        show_button = false;
      }

      return show_button;
    },

    addNewGroupButtonLabel() {
      let button_label = "Add New";

      let button_icon = '<span aria-hidden="true" class="la la-plus"></span>';

      if (this.generalSettings && this.generalSettings.addNewGroupButtonLabel) {
        button_label = this.generalSettings.addNewGroupButtonLabel;
      }

      return button_icon + button_label;
    },

    modalContent() {
      return this.video;
    },

    buttonText() {
      return this.$store.state.is_saving
        ? "Saving"
        : 'Save & Preview <span class="la la-pen"></span>';
    },

    ...mapState({
      options: "options",
    }),
  },

  data() {
    return {
      local_value: {},

      active_widget_fields: {},
      active_widget_groups: [],
      avilable_widgets: {},
      isDataChanged: false,

      default_group: [
        {
          type: "general_group",
          label:
            this.groupSettings && this.groupSettings.defaultGroupLabel
              ? this.groupSettings.defaultGroupLabel
              : "Section",
          fields: [],
        },
      ],

      forceExpandStateTo: "", // expand | 'collapse'
      isEnabledGroupDragging: true,

      currentDraggingGroup: null,
      currentDraggingWidget: null,

      listing_type_id: null,

      showModal: false,
    };
  },

  methods: {
    setup() {
      this.setupActiveWidgetFields();
      this.setupActiveWidgetGroups();
    },

    // setupActiveWidgetFields
    setupActiveWidgetFields() {
      if (!this.value) {
        return;
      }

      this.active_widget_fields = this.sanitizeActiveWidgetFields(
        findObjectItem("fields", this.value, {}),
      );

      this.$emit("updated-state");
      this.$emit("active-widgets-updated");
    },

    // sanitizeActiveWidgetFields
    sanitizeActiveWidgetFields(activeWidgetFields) {
      if (!isObject(activeWidgetFields)) {
        return {};
      }

      if (activeWidgetFields.hasOwnProperty("field_key")) {
        delete activeWidgetFields.field_key;
      }

      for (let widget_key in activeWidgetFields) {
        if (!isObject(activeWidgetFields[widget_key])) {
          delete activeWidgetFields[widget_key];
          continue;
        }

        activeWidgetFields[widget_key].widget_key = widget_key;
      }

      return activeWidgetFields;
    },

    // setupActiveWidgetGroups
    setupActiveWidgetGroups() {
      if (!this.value) return;
      if (typeof this.value !== "object") return;

      if (Array.isArray(this.value.groups)) {
        this.active_widget_groups = this.sanitizeActiveWidgetGroups(
          this.value.groups,
        );
      }

      this.$emit("active-group-updated");
    },

    // sanitizeActiveWidgetGroups
    sanitizeActiveWidgetGroups(_active_widget_groups) {
      let active_widget_groups = _active_widget_groups;
      if (!Array.isArray(active_widget_groups)) {
        active_widget_groups = [];
      }

      let existingGroupIds = [];

      for (
        let group_index = 0;
        group_index < active_widget_groups.length;
        group_index++
      ) {
        let widget_group = active_widget_groups[group_index];

        // Ensure label exists
        if (typeof widget_group.label === "undefined") {
          widget_group.label = "";
        }

        // Ensure fields is an array
        if (
          typeof widget_group.fields === "undefined" ||
          !Array.isArray(widget_group.fields)
        ) {
          widget_group.fields = [];
        }

        // Filter valid fields
        let valid_fields = [];
        for (let field of widget_group.fields) {
          if (typeof this.active_widget_fields[field] !== "undefined") {
            valid_fields.push(field);
          }
        }
        widget_group.fields = valid_fields;

        // Generate ID if missing
        if (!widget_group.id && widget_group.label) {
          let baseId = widget_group.label
            .toLowerCase()
            .trim()
            .replace(/\s+/g, "-");
          let uniqueId = baseId;
          let suffix = 1;

          while (existingGroupIds.includes(uniqueId)) {
            uniqueId = `${baseId}-${suffix}`;
            suffix++;
          }

          widget_group.id = uniqueId;
        }

        // Track all IDs for uniqueness
        existingGroupIds.push(widget_group.id);
      }

      return active_widget_groups;
    },

    // updateWidgetList
    updateWidgetList(widget_list) {
      if (!widget_list) {
        return;
      }
      if (typeof widget_list !== "object") {
        return;
      }
      if (typeof widget_list.widget_group === "undefined") {
        return;
      }
      if (typeof widget_list.base_widget_list === "undefined") {
        return;
      }

      Vue.set(
        this.avilable_widgets,
        widget_list.widget_group,
        widget_list.base_widget_list,
      );
    },

    updateGroupField(widget_group_key, payload) {
      Vue.set(
        this.active_widget_groups[widget_group_key],
        payload.key,
        payload.value,
      );

      this.$emit("update", this.finalValue);
      this.$emit("updated-state");
      this.$emit("group-field-updated");
    },

    updateWidgetField(props) {
      this.isDataChanged = true;
      let activeWidget = this.active_widget_fields[props.widget_key];
      let updatedValue = props.payload.value;

      if (props.payload.key === "placeholder" && !props.payload.value) {
        if (!activeWidget.label) {
          updatedValue = directorist_admin.search_form_default_placeholder;
        }
      } else if (props.payload.key === "label" && !props.payload.value) {
        if (!activeWidget.placeholder) {
          updatedValue = directorist_admin.search_form_default_label;
        }
      }

      Vue.set(
        this.active_widget_fields[props.widget_key],
        props.payload.key,
        updatedValue,
      );

      this.$emit("update", this.finalValue);
      this.$emit("updated-state");
      this.$emit("widget-field-updated");
    },

    // Widget Tasks
    handleAppendWidget(widget_group_key) {
      if (!this.currentDraggingWidget) {
        return;
      }

      let payload = {
        widget_index:
          this.active_widget_groups[widget_group_key].fields.length - 1,
      };
      this.handleWidgetDrop(widget_group_key, payload);
    },

    handleWidgetDragStart(widget_group_key, payload) {
      this.currentDraggingWidget = {
        from: "active_widgets",
        widget_group_key,
        widget_index: payload.widget_index,
        widget_key: payload.widget_key,
      };
    },

    handleWidgetDragEnd() {
      this.currentDraggingWidget = null;
    },

    isAcceptedSectionWidget(widgetKey, destinationSection) {
      const widgetPath = `${destinationSection.widget_group}.widgets.${destinationSection.widget_name}`;
      const widget = findObjectItem(widgetPath, this.widgets, {});

      if (!widget.hasOwnProperty("accepted_widgets")) {
        return true;
      }

      if (!Array.isArray(widget.accepted_widgets)) {
        return true;
      }

      if (!widget.accepted_widgets.length) {
        return true;
      }

      const droppedWidget = this.active_widget_fields[widgetKey];

      let hasMissMatchWidget = false;

      for (const acceptedWidget of widget.accepted_widgets) {
        for (const acceptedWidgetKey of Object.keys(acceptedWidget)) {
          if (
            droppedWidget[acceptedWidgetKey] !==
            acceptedWidget[acceptedWidgetKey]
          ) {
            hasMissMatchWidget = true;
            break;
          }
        }
      }

      if (hasMissMatchWidget) {
        return false;
      }
    },

    handleWidgetDrop(widget_group_key, payload) {
      const dropped_in = {
        widget_group_key,
        widget_key: payload.widget_key,
        widget_index: payload.widget_index,
        drop_direction: payload.drop_direction,
      };

      const activeGroup = this.active_widget_groups[widget_group_key];

      if (
        "section" === activeGroup.type &&
        !this.isAcceptedSectionWidget(payload.widget_key, activeGroup)
      ) {
        return false;
      }

      // handleWidgetReorderFromActiveWidgets
      if ("active_widgets" === this.currentDraggingWidget.from) {
        this.handleWidgetReorderFromActiveWidgets(
          this.currentDraggingWidget,
          dropped_in,
        );
        this.currentDraggingWidget = null;
        return;
      }

      // handleWidgetInsertFromAvailableWidgets
      if ("available_widgets" === this.currentDraggingWidget.from) {
        this.handleWidgetInsertFromAvailableWidgets(
          this.currentDraggingWidget,
          dropped_in,
        );
        this.currentDraggingWidget = null;
      }
    },

    handleWidgetReorderFromActiveWidgets(from, to) {
      let from_fields = this.active_widget_groups[from.widget_group_key].fields;
      let to_fields = this.active_widget_groups[to.widget_group_key].fields;

      // If Reordering in same group
      if (from.widget_group_key === to.widget_group_key) {
        let origin_data = from_fields[from.widget_index];
        let dest_index =
          from.widget_index < to.widget_index
            ? to.widget_index - 1
            : to.widget_index;

        dest_index =
          "after" === to.drop_direction ? dest_index + 1 : dest_index;

        this.active_widget_groups[from.widget_group_key].fields.splice(
          from.widget_index,
          1,
        );
        this.active_widget_groups[to.widget_group_key].fields.splice(
          dest_index,
          0,
          origin_data,
        );

        return;
      }

      // If Reordering to diffrent group
      let origin_data = from_fields[from.widget_index];
      let dest_index =
        "before" === to.drop_direction ? to.widget_index - 1 : to.widget_index;
      dest_index =
        "after" === to.drop_direction ? to.widget_index + 1 : to.widget_index;
      dest_index = dest_index < 0 ? 0 : dest_index;
      dest_index =
        dest_index >= to_fields.length ? to_fields.length : dest_index;

      this.active_widget_groups[from.widget_group_key].fields.splice(
        from.widget_index,
        1,
      );
      this.active_widget_groups[to.widget_group_key].fields.splice(
        dest_index,
        0,
        origin_data,
      );

      this.$emit("updated-state");
      this.$emit("active-widgets-updated");
    },

    handleWidgetInsertFromAvailableWidgets(from, to) {
      const field_data_options = this.getOptionDataFromWidget(from.widget);

      field_data_options.widget_key = this.genarateWidgetKeyForActiveWidgets(
        from.widget_key,
      );

      if (field_data_options.field_key) {
        field_data_options.field_key =
          this.genarateFieldKeyForActiveWidgets(field_data_options);
      }

      if (!isObject(this.active_widget_fields)) {
        this.active_widget_fields = {};
      }

      Vue.set(
        this.active_widget_fields,
        field_data_options.widget_key,
        field_data_options,
      );

      let to_fields = this.active_widget_groups[to.widget_group_key].fields;
      let dest_index =
        "before" === to.drop_direction ? to.widget_index - 1 : to.widget_index;

      dest_index =
        "after" === to.drop_direction ? to.widget_index + 1 : to.widget_index;
      dest_index = dest_index < 0 ? 0 : dest_index;
      dest_index =
        dest_index >= to_fields.length ? to_fields.length : dest_index;

      this.active_widget_groups[to.widget_group_key].fields.splice(
        dest_index,
        0,
        field_data_options.widget_key,
      );

      this.$emit("updated-state");
      this.$emit("active-widgets-updated");
    },

    handleWidgetListItemDragStart(widget_group_key, payload) {
      if (
        payload.widget &&
        typeof payload.widget.type !== "undefined" &&
        "section" === payload.widget.type
      ) {
        this.currentDraggingGroup = {
          from: "available_widgets",
          widget_group_key,
          widget_key: payload.widget_key,
          widget: payload.widget,
        };

        this.forceExpandStateTo = "collapse";
        this.isEnabledGroupDragging = true;

        return;
      }

      this.currentDraggingWidget = {
        from: "available_widgets",
        widget_group_key,
        widget_key: payload.widget_key,
        widget: payload.widget,
      };
    },

    handleWidgetListItemDragEnd() {
      this.currentDraggingWidget = null;
      this.currentDraggingGroup = null;
    },

    trashWidget(widget_group_key, payload) {
      let index = this.active_widget_groups[widget_group_key].fields.indexOf(
        payload.widget_key,
      );

      this.active_widget_groups[widget_group_key].fields.splice(index, 1);

      Vue.delete(this.active_widget_fields, payload.widget_key);

      this.$emit("updated-state");
      this.$emit("widget-field-trashed");
      this.$emit("active-widgets-updated");
    },

    getOptionDataFromWidget(widget) {
      const widgetOptions = findObjectItem("options", widget);

      if (!isObject(widgetOptions)) {
        return {};
      }

      const fieldDataOptions = {};

      for (let option_key in widgetOptions) {
        fieldDataOptions[option_key] =
          typeof widgetOptions[option_key].value !== "undefined"
            ? widgetOptions[option_key].value
            : "";
      }

      return fieldDataOptions;
    },

    genarateWidgetKeyForActiveWidgets(widget_key) {
      if (typeof this.active_widget_fields[widget_key] !== "undefined") {
        let matched_keys = Object.keys(this.active_widget_fields);

        const getUniqueKey = function (current_key, new_key) {
          if (matched_keys.includes(new_key)) {
            let field_id = new_key.match(/[_](\d+)$/);
            field_id = field_id ? parseInt(field_id[1]) : 1;

            const new_field_key = current_key + "_" + (field_id + 1);

            return getUniqueKey(current_key, new_field_key);
          }

          return new_key;
        };

        return getUniqueKey(widget_key, widget_key);
      }

      return widget_key;
    },

    genarateFieldKeyForActiveWidgets(field_data_options) {
      if (!field_data_options.field_key) {
        return "";
      }
      const current_field_key = field_data_options.field_key;

      let field_keys = [];

      for (let key in this.active_widget_fields) {
        if (!this.active_widget_fields[key].field_key) {
          continue;
        }

        field_keys.push(this.active_widget_fields[key].field_key);
      }

      const getUniqueKey = function (field_key) {
        if (field_keys.includes(field_key)) {
          let field_id = field_key.match(/[-](\d+)$/);
          field_id = field_id ? parseInt(field_id[1]) : 1;

          const new_field_key = current_field_key + "-" + (field_id + 1);

          return getUniqueKey(new_field_key);
        }

        return field_key;
      };

      const unique_field_key = getUniqueKey(current_field_key);

      return unique_field_key;
    },

    handleGroupDragStart(widget_group_key) {
      this.currentDraggingGroup = {
        from: "active_widgets",
        widget_group_key,
      };
      this.isEnabledGroupDragging = false;
    },

    handleGroupDragEnd() {
      this.currentDraggingGroup = null;
      this.isEnabledGroupDragging = true;
    },

    handleGroupDrop(widget_group_key, payload) {
      let dropped_in = {
        widget_group_key,
        drop_direction: payload.drop_direction,
      };

      if ("active_widgets" === this.currentDraggingGroup.from) {
        this.handleGroupReorderFromActiveWidgets(
          this.currentDraggingGroup,
          dropped_in,
        );
      }

      if ("available_widgets" === this.currentDraggingGroup.from) {
        this.handleGroupInsertFromAvailableWidgets(
          this.currentDraggingGroup,
          dropped_in,
        );
      }

      this.currentDraggingGroup = null;
    },

    addNewGroup() {
      let group = JSON.parse(JSON.stringify(this.default_group[0]));

      if (this.groupSettings) {
        Object.assign(group, this.groupSettings);
      }

      if (this.groupFields && this.groupFields.section_id) {
        group.section_id = this.getUniqueSectionID();
      }

      let dest_index = this.active_widget_groups.length;
      this.active_widget_groups.splice(dest_index, 0, group);

      this.$emit("updated-state");
    },

    getUniqueSectionID() {
      let existing_ids = [];

      if (!Array.isArray(this.active_widget_groups)) {
        return 1;
      }

      for (let group of this.active_widget_groups) {
        if (typeof group.section_id !== undefined && !isNaN(group.section_id)) {
          existing_ids.push(parseInt(group.section_id));
        }
      }

      if (existing_ids.length) {
        return Math.max(...existing_ids) + 1;
      }

      return 1;
    },

    handleGroupReorderFromActiveWidgets(from, to) {
      let origin_data = this.active_widget_groups[from.widget_group_key];

      let dest_index =
        from.widget_group_key < to.widget_group_key
          ? to.widget_group_key - 1
          : to.widget_group_key;
      dest_index = "after" === to.drop_direction ? dest_index + 1 : dest_index;

      this.active_widget_groups.splice(from.widget_group_key, 1);
      this.active_widget_groups.splice(dest_index, 0, origin_data);

      this.$emit("updated-state");
      this.$emit("group-reordered");
    },

    handleGroupInsertFromAvailableWidgets(from, to) {
      let group = JSON.parse(JSON.stringify(this.default_group[0]));

      if (this.groupSettings) {
        Object.assign(group, this.groupSettings);
      }

      if (this.groupFields && this.groupFields.section_id) {
        group.section_id = this.getUniqueSectionID();
      }

      let widget = from.widget;
      let option_data = this.getOptionDataFromWidget(widget);

      group.fields = this.insertWidgetFromAvailableSectionWidgets(
        widget.widgets,
      );

      delete widget.options;
      delete widget.widgets;

      Object.assign(group, widget);
      Object.assign(group, option_data);

      let dest_index =
        "before" === to.drop_direction
          ? to.widget_group_key - 1
          : to.widget_group_key;
      dest_index =
        "after" === to.drop_direction
          ? to.widget_group_key + 1
          : to.widget_group_key;
      dest_index = dest_index < 0 ? 0 : dest_index;
      dest_index =
        dest_index >= this.active_widget_groups.length
          ? this.active_widget_groups.length
          : dest_index;

      this.active_widget_groups.splice(dest_index, 0, group);

      this.$emit("updated-state");
      this.$emit("active-widgets-updated");
    },

    insertWidgetFromAvailableSectionWidgets(widgets) {
      if (!isObject(widgets)) {
        return [];
      }

      const insertWidgetAndGetKey = (widget_key, widget) => {
        const field_data_options = this.getOptionDataFromWidget(widget);

        field_data_options.widget_key =
          this.genarateWidgetKeyForActiveWidgets(widget_key);

        if (field_data_options.field_key) {
          field_data_options.field_key =
            this.genarateFieldKeyForActiveWidgets(field_data_options);
        }

        if (!isObject(this.active_widget_fields)) {
          this.active_widget_fields = {};
        }

        Vue.set(
          this.active_widget_fields,
          field_data_options.widget_key,
          field_data_options,
        );

        return field_data_options.widget_key;
      };

      return Object.keys(widgets).map((widgetKey) =>
        insertWidgetAndGetKey(widgetKey, widgets[widgetKey]),
      );
    },

    trashGroup(widget_group_key) {
      let group_fields = this.active_widget_groups[widget_group_key].fields;

      if (group_fields.length) {
        for (let widget_key of group_fields) {
          Vue.delete(this.active_widget_fields, widget_key);
        }
      }

      Vue.delete(this.active_widget_groups, widget_group_key);

      this.$emit("updated-state");
      this.$emit("group-updated");
      this.$emit("group-trashed");
      this.$emit("active-widgets-updated");
    },

    // Other Tasks
    toggleEnableWidgetGroupDragging() {
      this.forceExpandStateTo = !this.forceExpandStateTo ? "collapse" : ""; // expand | 'collapse'
      this.isEnabledGroupDragging = !this.isEnabledGroupDragging;
    },

    ...mapGetters(["getFieldsValue"]),

    updateSubmitButtonLabel(payload) {
      if (!payload.field) {
        return;
      }
      if (typeof payload.value === "undefined") {
        return;
      }

      this.$store.commit("updateSubmitButtonLabel", payload);
    },

    maybeJSON(data) {
      let value = typeof data === "undefined" ? "" : data;

      if (
        ("object" === typeof value && Object.keys(value)) ||
        Array.isArray(value)
      ) {
        let json_encoded_value = JSON.stringify(value);
        let base64_encoded_value =
          this.encodeUnicodedToBase64(json_encoded_value);
        value = base64_encoded_value;
      }

      return value;
    },

    encodeUnicodedToBase64(str) {
      // first we use encodeURIComponent to get percent-encoded UTF-8,
      // then we convert the percent encodings into raw bytes which
      // can be fed into btoa.
      return btoa(
        encodeURIComponent(str).replace(
          /%([0-9A-F]{2})/g,
          function toSolidBytes(match, p1) {
            return String.fromCharCode("0x" + p1);
          },
        ),
      );
    },

    handleBeforeUnload(event) {
      if (this.isDataChanged) {
        event.preventDefault();
        event.returnValue = ""; // Display default warning dialog
      }
    },

    // Open the modal
    openModal() {
      this.showModal = true;
    },

    // Close the modal
    closeModal() {
      this.showModal = false;
    },

    // Save the data
    saveData() {
      // Emit the save event before redirecting
      this.$emit("save");
    },
  },
};
</script>
