<template>
  <draggable-list-item
    v-if="widget_fields && Object.keys(widget_fields).length > 0"
    :drag-handle="'.cptm-form-builder-group-field-item-drag'"
    @drag-start="$emit('drag-start')"
    @drag-end="$emit('drag-end')"
    :can-drag="canMoveWidget"
  >
    <div
      class="cptm-form-builder-group-field-item"
      :class="expandState ? 'expanded' : ''"
    >
      <!-- Widget Header -->
      <div class="cptm-form-builder-group-field-item-header">
        <!-- Drag Handle -->
        <div
          class="cptm-form-builder-group-field-item-drag"
          v-if="canMoveWidget"
        >
          <span aria-hidden="true" class="uil uil-draggabledots"></span>
        </div>

        <!-- Widget Titlebar -->
        <div class="cptm-form-builder-group-field-item-header-content">
          <div class="cptm-form-builder-header-toggle">
            <a
              href="#"
              class="cptm-form-builder-header-toggle-link"
              :class="
                expandState ? 'action-collapse-down' : 'action-collapse-up'
              "
              @click.prevent="toggleExpand"
            >
              <span aria-hidden="true" class="uil uil-angle-down"></span>
            </a>
          </div>

          <h4 class="cptm-form-builder-group-field-item-title">
            <span
              class="cptm-form-builder-group-field-item-icon"
              v-if="widgetIcon"
            >
              <span v-if="widgetIconType !== 'svg'" :class="widgetIcon"></span>
              <span
                v-else-if="widgetIconType === 'svg'"
                v-html="widgetIcon"
                class="cptm-title-icon-svg"
              ></span>
            </span>
            <span class="cptm-form-builder-group-field-item-label">
              <span class="cptm-title-wrapper">
                {{ widgetTitle }}
                <span
                  v-if="alert"
                  class="cptm-title-info"
                  :data-label="alert.message"
                >
                  <span class="cptm-title-info-icon las la-info-circle"></span>
                  <span
                    class="cptm-title-info-text"
                    v-html="alert.message"
                  ></span>
                </span>
              </span>
              <span
                v-if="widgetSubtitle"
                class="cptm-form-builder-group-field-item-subtitle"
              >
                ({{ widgetSubtitle }})
              </span>
              <span
                v-if="widgetInfo"
                class="cptm-title-info-tooltip"
                :data-info="widgetInfo"
              >
                <span
                  class="cptm-title-info-icon uil uil-question-circle"
                ></span>
              </span>
            </span>
          </h4>

          <div class="cptm-form-builder-group-field-item-header-actions">
            <a
              href="#"
              class="cptm-form-builder-header-action-link"
              v-if="canTrashWidget"
              @click.prevent="handleWidgetDelete"
            >
              <span aria-hidden="true" class="uil uil-trash-alt"></span>
            </a>
          </div>
        </div>
      </div>

      <!-- Widget Body -->
      <slide-up-down :active="expandState" :duration="500">
        <div
          class="cptm-form-builder-group-field-item-body"
          v-if="widget_fields && typeof widget_fields === 'object'"
        >
          <field-list-component
            :root="activeWidgets"
            :section-id="widgetKey"
            :field-list="widget_fields"
            :value="activeWidgets[widgetKey] ? activeWidgets[widgetKey] : ''"
            @alert="updateAlert"
            @update="
              $emit('update-widget-field', {
                widget_key: widgetKey,
                payload: $event,
              })
            "
          />
        </div>
      </slide-up-down>

      <!-- Confirmation Modal -->
      <confirmation-modal
        :visible="showConfirmationModal"
        :widgetName="widgetName"
        @confirm="trashWidget"
        @cancel="closeConfirmationModal"
      />
    </div>
  </draggable-list-item>
</template>

<script>
import Vue from "vue";
import { findObjectItem } from "../../../../../helper";
import ConfirmationModal from "./Form_Builder_Widget_Trash_Confirmation.vue";

export default {
  name: "form-builder-widget-component",
  components: {
    ConfirmationModal,
  },
  props: {
    widgetKey: {
      default: "",
    },
    activeWidgets: {
      default: "",
    },
    avilableWidgets: {
      default: "",
    },
    groupData: {
      default: "",
    },
    isEnabledGroupDragging: {
      default: false,
    },
    untrashableWidgets: {
      default: "",
    },
    isExpanded: {
      type: Boolean,
      default: false,
    },
  },

  created() {
    this.sync();
  },

  watch: {
    widgetKey() {
      if (this.activeWidgetsIsUpdating) {
        return;
      }
      this.sync();
    },

    activeWidgets() {
      this.activeWidgetsIsUpdating = true;
      this.sync();
      this.activeWidgetsIsUpdating = false;
    },

    groupDataFields() {
      if (this.activeWidgetsIsUpdating) {
        return;
      }
      this.sync();
    },
  },

  computed: {
    isPresetOrCustomGroup() {
      return (
        this.widget_fields?.widget_group?.value === "preset" ||
        this.widget_fields?.widget_group?.value === "custom"
      );
    },

    groupDataFields() {
      return this.groupData.fields;
    },

    widgetTitle() {
      let label = "";
      if (
        this.activeWidgets[this.widgetKey] &&
        this.activeWidgets[this.widgetKey].label
      ) {
        label = this.activeWidgets[this.widgetKey].label;
      }

      if (!label.length && this.current_widget && this.current_widget.label) {
        label = this.current_widget.label;
      }

      return label;
    },

    widgetSubtitle() {
      let label = "";

      if (
        !(
          this.activeWidgets[this.widgetKey] &&
          this.activeWidgets[this.widgetKey].label
        )
      ) {
        return "";
      }

      if (this.current_widget && this.current_widget.label) {
        label = this.current_widget.label;
      }

      return label;
    },

    widgetIcon() {
      let icon = "";

      if (this.current_widget && this.current_widget.icon) {
        icon = this.current_widget.icon;
      }

      return icon;
    },

    widgetInfo() {
      let info = "";

      if (
        this.activeWidgets[this.widgetKey] &&
        this.activeWidgets[this.widgetKey].info
      ) {
        info = this.activeWidgets[this.widgetKey].info;
      }

      if (!info.length && this.current_widget && this.current_widget.info) {
        info = this.current_widget.info;
      }

      return info;
    },

    widgetIconType() {
      let iconType = "";

      if (this.current_widget && this.current_widget.iconType) {
        iconType = this.current_widget.iconType;
      }

      return iconType;
    },

    expandState() {
      let state = this.isExpanded;

      if (!this.isEnabledGroupDragging) {
        state = false;
      }

      return state;
    },

    canTrashWidget() {
      if (typeof this.current_widget.canTrash === "undefined") {
        return true;
      }
      return this.current_widget.canTrash;
    },

    canMoveWidget() {
      if (typeof this.current_widget.canMove === "undefined") {
        return true;
      }
      return this.current_widget.canMove;
    },

    emptySlideUpDownClass() {
      return !this.widget_fields || Object.keys(this.widget_fields).length === 0
        ? "cptm-empty-slide-up-down"
        : "";
    },

    alert() {
      const widgetKeys = Object.keys(this.alerts);

      if (widgetKeys.length < 1) {
        return null;
      }

      const widgetKey = widgetKeys[0];

      if (!this.alerts[widgetKey] || this.alerts.widgetKey !== this.widgetKey) {
        return null;
      }

      const alertKeys = Object.keys(this.alerts[widgetKey]);

      if (alertKeys.length < 1) {
        return null;
      }

      const alertKey = alertKeys[0];

      return this.alerts[widgetKey][alertKey];
    },
  },

  data() {
    return {
      current_widget: "",
      widget_fields: "",
      widgetIsDragging: false,

      activeWidgetsIsUpdating: false,
      showConfirmationModal: false,
      widgetName: "",
      alerts: {},
    };
  },

  methods: {
    updateAlert(payload) {
      if (!payload.data) {
        this.removeAlert(payload.key);
        return;
      }

      this.addAlert(payload.key, payload.data);
    },

    addAlert(key, data) {
      this.alerts = {
        ...this.alerts,
        [key]: data,
        widgetKey: this.widgetKey,
      };
    },

    removeAlert(key) {
      if (this.alerts.hasOwnProperty(key)) {
        Vue.delete(this.alerts, key);
      }

      // If only one key remains and it's "widgetKey", remove that too
      const remainingKeys = Object.keys(this.alerts);
      if (remainingKeys.length === 1 && remainingKeys[0] === "widgetKey") {
        Vue.delete(this.alerts, "widgetKey");
      }
    },

    handleWidgetDelete() {
      this.openConfirmationModal();
    },

    sync() {
      this.syncCurrentWidget();
      this.syncWidgetFields();
    },

    openConfirmationModal() {
      this.widgetName = this.widgetTitle;
      this.showConfirmationModal = true;

      // Add class to parent with class 'atbdp-cpt-manager'
      const parentElement = this.$el.closest(".atbdp-cpt-manager");
      if (parentElement) {
        parentElement.classList.add("directorist-overlay-visible");
      }
    },

    closeConfirmationModal() {
      this.showConfirmationModal = false;

      // Remove class from parent with class 'atbdp-cpt-manager'
      const parentElement = this.$el.closest(".atbdp-cpt-manager");
      if (parentElement) {
        parentElement.classList.remove("directorist-overlay-visible");
      }
    },

    trashWidget() {
      this.$emit("trash-widget");
      this.closeConfirmationModal();
    },

    syncCurrentWidget() {
      const current_widget = findObjectItem(
        `${this.widgetKey}`,
        this.activeWidgets,
      );

      if (!current_widget) {
        return;
      }

      const widget_group = current_widget.widget_group
        ? current_widget.widget_group
        : "";

      const widget_name = current_widget.original_widget_key
        ? current_widget.original_widget_key
        : current_widget.widget_name
          ? current_widget.widget_name
          : "";

      const widget_child_name = current_widget.widget_child_name
        ? current_widget.widget_child_name
        : "";

      if (!this.avilableWidgets[widget_group]) {
        return;
      }

      let the_current_widget = null;
      let current_widget_name = "";
      let current_widget_child_name = "";

      if (this.avilableWidgets[widget_group][widget_name]) {
        the_current_widget = this.avilableWidgets[widget_group][widget_name];
        current_widget_name = widget_name;
      }

      if (
        the_current_widget &&
        the_current_widget.widgets &&
        the_current_widget.widgets[widget_child_name]
      ) {
        the_current_widget = the_current_widget.widgets[widget_child_name];
        current_widget_child_name = widget_child_name;
      }

      if (!the_current_widget) {
        return;
      }

      this.checkIfHasUntrashableWidget(
        widget_group,
        current_widget_name,
        current_widget_child_name,
      );

      this.current_widget = the_current_widget;
    },

    syncWidgetFields() {
      if (!this.current_widget) {
        return "";
      }
      if (typeof this.current_widget !== "object") {
        return "";
      }
      if (!this.current_widget.options) {
        return "";
      }

      this.widget_fields = this.current_widget.options;
    },

    toggleExpand() {
      this.$emit("toggle-expand");
    },

    checkIfHasUntrashableWidget(widget_group, widget_name, widget_child_name) {
      if (!this.untrashableWidgets) {
        return;
      }
      if (typeof this.untrashableWidgets !== "object") {
        return;
      }

      for (let widget in this.untrashableWidgets) {
        if (this.untrashableWidgets[widget].widget_group !== widget_group) {
          continue;
        }

        if (this.untrashableWidgets[widget].widget_name !== widget_name) {
          continue;
        }

        if (
          widget_child_name &&
          this.untrashableWidgets[widget].widget_child_name !==
            widget_child_name
        ) {
          continue;
        }

        this.$emit("found-untrashable-widget");
        return;
      }
    },
  },
};
</script>
