<template>
  <div
    class="cptm-option-card cptm-option-card--draggable"
    :class="mainWrapperClass"
  >
    <div class="cptm-option-card-header">
      <div class="cptm-option-card-header-title-section">
        <h3 class="cptm-option-card-header-title">Edit Element</h3>
        <div class="cptm-header-action-area">
          <a
            href="#"
            class="cptm-header-action-link cptm-header-action-close"
            @click.prevent="$emit('close')"
          >
            <span class="las la-times"></span>
          </a>
        </div>
      </div>
    </div>

    <div class="cptm-option-card-body">
      <!-- Avatar Toggle Switch -->
      <div class="cptm-input-toggle-wrap">
        <div class="cptm-input-toggle-content">
          <label>
            <span>Avatar</span>
          </label>
        </div>
        <div class="directorist_vertical-align-m cptm-input-toggle-btn">
          <div class="directorist_item">
            <label
              class="cptm-input-toggle"
              :class="{ active: isAvatarEnabled }"
              :for="`avatar-toggle-${id}`"
            ></label>
            <input
              type="checkbox"
              :id="`avatar-toggle-${id}`"
              :name="`avatar-toggle-${id}`"
              class="cptm-toggle-input"
              v-model="isAvatarEnabled"
              @change="handleAvatarToggleChange"
              style="display: none"
            />
          </div>
        </div>
      </div>

      <!-- Avatar Options -->
      <div v-if="isAvatarEnabled" class="cptm-widget-options-container">
        <div
          v-for="(field, field_key) in widgetTypeField('user_avatar')"
          :key="field_key"
        >
          <component
            v-if="field"
            :is="getFormFieldName(field.type)"
            :field-id="`user_avatar-${field_key}`"
            :fieldKey="`user_avatar-${field_key}`"
            v-bind="field"
            @update="updateWidgetOptionValue($event)"
          />
        </div>

        <div
          v-for="(field, field_key) in widgetFields('user_avatar')"
          class="cptm-widget-options-wrap"
          :key="field_key"
        >
          <component
            v-if="field"
            :is="getFormFieldName(field.type)"
            :field-id="`user_avatar-${field_key}`"
            :fieldKey="`user_avatar-${field_key}`"
            v-bind="field"
            @update="updateWidgetFieldValue(field_key, $event)"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "avatar-settings-window",
  props: {
    id: {
      type: [String, Number],
      default: "",
    },
    active: {
      type: Boolean,
      default: false,
    },
    availableWidgets: {
      type: Object,
    },
    selectedWidgets: {
      type: Array,
    },
    maxWidgetInfoText: {
      type: String,
      default: "Up to __DATA__ item{s} can be added",
    },
  },
  data() {
    return {
      isAvatarEnabled: false,
      activeWidget: {},
      activeWidgetKey: "user_avatar",
      activeWidgetOptionType: "",
    };
  },
  computed: {
    mainWrapperClass() {
      return {
        active: this.active,
      };
    },
  },
  created() {
    this.isAvatarEnabled =
      this.selectedWidgets && this.selectedWidgets.includes("user_avatar");

    if (this.isAvatarEnabled && this.availableWidgets?.["user_avatar"]) {
      this.activeWidget = this.availableWidgets["user_avatar"];
      this.activeWidgetOptionType =
        this.activeWidget.options?.type?.value || "";
    }
  },
  mounted() {
    if (this.isAvatarEnabled && this.availableWidgets?.["user_avatar"]) {
      this.$nextTick(() => {
        this.activeWidget = this.availableWidgets["user_avatar"];
        this.activeWidgetOptionType =
          this.activeWidget.options?.type?.value || "";
      });
    }
  },
  watch: {
    selectedWidgets: {
      handler(newVal) {
        this.isAvatarEnabled =
          newVal && Array.isArray(newVal) && newVal.includes("user_avatar");

        if (this.isAvatarEnabled && this.availableWidgets?.["user_avatar"]) {
          this.activeWidget = this.availableWidgets["user_avatar"];
          this.activeWidgetOptionType =
            this.activeWidget.options?.type?.value || "";
        }
      },
      immediate: true,
      deep: true,
    },
    availableWidgets: {
      handler() {
        if (this.isAvatarEnabled && this.availableWidgets?.["user_avatar"]) {
          this.activeWidget = this.availableWidgets["user_avatar"];
          this.activeWidgetOptionType =
            this.activeWidget.options?.type?.value || "";
        }
      },
      deep: true,
    },
  },
  methods: {
    handleAvatarToggleChange() {
      if (this.isAvatarEnabled) {
        if (
          !this.selectedWidgets ||
          !this.selectedWidgets.includes("user_avatar")
        ) {
          const updatedWidgets = [
            ...(this.selectedWidgets || []),
            "user_avatar",
          ];
          this.$emit("insert-widget", {
            key: "user_avatar",
            selected_widgets: updatedWidgets,
          });
        }
      } else {
        this.$emit("trash-widget", "user_avatar");
      }
    },

    getFormFieldName(field_type) {
      return field_type + "-field";
    },

    widgetTypeField(widgetKey) {
      if (
        !widgetKey ||
        !this.availableWidgets ||
        !this.availableWidgets[widgetKey]
      ) {
        return {};
      }

      const hasRadioField = this.availableWidgets[widgetKey].options?.type;
      if (!hasRadioField) {
        return {};
      }

      const activeWidgetFields = this.availableWidgets[widgetKey].options;
      return activeWidgetFields || {};
    },

    widgetFields(widgetKey) {
      if (
        !widgetKey ||
        !this.availableWidgets ||
        !this.availableWidgets[widgetKey]
      ) {
        return {};
      }

      const hasRadioField = this.availableWidgets[widgetKey].options?.type;
      const activeWidgetOptions = hasRadioField
        ? this.availableWidgets[widgetKey].fields?.[this.activeWidgetOptionType]
        : this.availableWidgets[widgetKey].options?.fields;

      return activeWidgetOptions || {};
    },

    updateWidgetOptionValue(value) {
      this.activeWidgetOptionType = value;

      if (this.activeWidget.options) {
        this.activeWidget.options.type.value = value;
      }

      if (this.availableWidgets[this.activeWidgetKey]?.options) {
        this.availableWidgets[this.activeWidgetKey].options.type.value = value;
      }

      if (value === "icon") {
        const iconValue = this.activeWidget?.fields?.icon?.field_icon?.value;
        if (this.activeWidget) {
          this.activeWidget.icon = iconValue;
        }
        if (this.availableWidgets[this.activeWidgetKey]) {
          this.availableWidgets[this.activeWidgetKey].icon = iconValue;
        }
      }

      this.$emit("update-active-widget", {
        widgetKey: this.activeWidgetKey,
        updatedWidget: this.activeWidget,
      });
    },

    updateWidgetFieldValue(field_key, value) {
      const activeWidgetFields =
        this.activeWidget.fields || this.activeWidget.options?.fields;

      if (this.activeWidgetOptionType) {
        if (activeWidgetFields?.[this.activeWidgetOptionType]?.[field_key]) {
          activeWidgetFields[this.activeWidgetOptionType][field_key].value =
            value;
        }
      } else {
        if (activeWidgetFields?.[field_key]) {
          activeWidgetFields[field_key].value = value;
        }
      }

      if (field_key === "field_icon" || field_key === "icon") {
        if (this.activeWidget) {
          this.activeWidget.icon = value;
        }
        if (this.availableWidgets[this.activeWidgetKey]) {
          this.availableWidgets[this.activeWidgetKey].icon = value;
        }
      }

      this.$emit("update-active-widget", {
        widgetKey: this.activeWidgetKey,
        updatedWidget: this.activeWidget,
      });
    },
  },
};
</script>
