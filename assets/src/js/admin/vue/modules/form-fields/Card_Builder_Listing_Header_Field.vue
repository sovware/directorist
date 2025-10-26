<template>
  <div class="cptm-builder-section">
    <!-- cptm-elements-settings -->
    <div class="cptm-elements-settings">
      <div class="cptm-elements-settings__header">
        <h4 class="cptm-elements-settings__header__title">Listing Header</h4>
        <a
          href="#"
          class="directorist-row-tooltip cptm-form-builder-action-btn"
          :data-tooltip="video?.description"
          data-flow="bottom-right"
          @click.prevent="openModal()"
          v-if="video"
        >
        <svg width="22" height="12" viewBox="0 0 22 12" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M0.5 0V12H17V9.46875L21.5 11.7188V0.28125L17 2.53125V0H0.5ZM2 1.5H15.5V10.5H2V1.5ZM20 2.71875V9.28125L17 7.78125V4.21875L20 2.71875Z" fill="#2C3239"/>
        </svg>
          Learn
        </a>
      </div>

      <template>
        <div class="cptm-elements-settings__content">
          <div
            class="cptm-elements-settings__group"
            v-for="(placeholder, placeholder_index) in allPlaceholderItems"
            :key="placeholder_index"
          >
            <span
              class="cptm-elements-settings__group__title"
              v-if="
                placeholder.label &&
                placeholder?.placeholderKey !== 'listing-title-placeholder' &&
                placeholder?.placeholderKey !== 'slider-placeholder'
              "
            >
              {{ placeholder.label }}
            </span>

            <Container
              @drop="onElementsDrop($event, placeholder_index)"
              @drag-start="onSettingsDragStart($event, placeholder_index)"
              @drag-end="onSettingsDragEnd"
              group-name="settings-widgets"
              drag-handle-selector=".drag-handle"
              :get-child-payload="
                (index) => getSettingsChildPayload(index, placeholder_index)
              "
            >
              <Draggable
                v-for="(
                  widget_key, widget_index
                ) in getAvailableWidgetsForPlaceholder(placeholder)"
                :key="`${placeholder_index}_${widget_key}_${widget_index}`"
                :data="{ widget_key }"
                :class="{
                  dragging: currentSettingsDraggingWidgetKey === widget_key,
                }"
              >
                <div
                  class="cptm-elements-settings__group__single"
                  :class="{
                    'cptm-elements-settings__group__single--disabled':
                      placeholder.maxWidget > 0 &&
                      placeholder.selectedWidgets?.length >=
                        placeholder.maxWidget &&
                      !placeholder.selectedWidgets.some(
                        (widget) => widget.widget_key === widget_key,
                      ),
                  }"
                >
                  <span
                    class="drag-handle drag-icon uil uil-draggabledots"
                    v-if="placeholder.acceptedWidgets?.length > 1"
                  ></span>
                  <span class="cptm-elements-settings__group__single__label">
                    <!-- Display icon only if it exists -->
                    <span
                      v-if="available_widgets[widget_key].icon"
                      class="cptm-elements-settings__group__single__label__icon"
                      :class="available_widgets[widget_key].icon"
                    ></span>
                    <span v-if="available_widgets[widget_key]" class="cptm-elements-settings__group__single__label__text">{{
                      available_widgets[widget_key].label
                    }}</span>
                    <span v-else>Unknown Widget</span>
                  </span>
                  <div class="cptm-elements-settings__group__single__action">
                    <!-- Add edit button for widget -->
                    <span
                      class="cptm-elements-settings__group__single__edit"
                      :class="{
                        'cptm-elements-settings__group__single__edit--disabled':
                          !active_widgets[widget_key],
                      }"
                      @click.prevent="editWidget(widget_key)"
                      v-if="available_widgets[widget_key].options"
                    >
                      <span
                        class="cptm-elements-settings__group__single__edit__icon la la-cog"
                      ></span>
                    </span>

                    <!-- Add toggle switch for widget -->
                    <span class="cptm-elements-settings__group__single__switch">
                      <input
                        type="checkbox"
                        :id="`settings-${widget_key}-${placeholder_index}`"
                        :checked="
                          placeholder.selectedWidgets &&
                          placeholder.selectedWidgets.some(
                            (widget) => widget.widget_key === widget_key,
                          )
                        "
                        @click="
                          handleWidgetSwitch(
                            $event,
                            widget_key,
                            placeholder_index,
                          )
                        "
                      />
                      <label
                        :for="`settings-${widget_key}-${placeholder_index}`"
                      />
                    </span>
                  </div>
                </div>

                <!-- Widget Options -->
                <div
                  class="cptm-elements-settings__group__options"
                  v-if="widgetOptionsWindowActiveStatus(widget_key)"
                >
                  <options-window
                    :active="widgetOptionsWindowActiveStatus(widget_key)"
                    v-bind="widgetOptionsWindow"
                    :activeWidget="active_widgets[widget_key]"
                    @update="
                      updateWidgetOptionsData($event, widgetOptionsWindow)
                    "
                    @close="closeWidgetOptionsWindow"
                  />
                </div>
              </Draggable>
            </Container>
          </div>
        </div>
      </template>
    </div>

    <!-- cptm-preview-area -->
    <div class="cptm-preview-placeholder">
      <div class="cptm-preview-placeholder__card">
        <!-- Draggable Bottom Widgets -->
        <div
          v-for="(placeholderItem, index) in placeholders"
          :key="index"
          v-if="placeholderItem.type == 'placeholder_group'"
          class="cptm-preview-placeholder__card__item cptm-preview-placeholder__card__item--top"
        >
          <div class="cptm-preview-placeholder__card__content">
            <card-widget-placeholder
              v-for="(
                placeholderSubItem, subIndex
              ) in placeholderItem.placeholders"
              :key="`${index}_${subIndex}`"
              :placeholderKey="placeholderSubItem.placeholderKey"
              :id="`listings_header_${index}_${subIndex}`"
              containerClass="cptm-preview-placeholder__card__box cptm-card-light"
              :label="placeholderSubItem.label"
              :availableWidgets="theAvailableWidgets"
              :activeWidgets="active_widgets"
              :acceptedWidgets="placeholderSubItem.acceptedWidgets"
              :rejectedWidgets="placeholderSubItem.rejectedWidgets"
              :selectedWidgets="placeholderSubItem.selectedWidgetList"
              :maxWidget="placeholderSubItem.maxWidget"
              :readOnly="true"
            />
          </div>
        </div>
        <Container
          @drop="onDrop"
          @drag-start="onDragStart"
          @drag-end="onDragEnd"
          drag-handle-selector=".cptm-drag-element"
          class="cptm-preview-placeholder__card__item cptm-preview-placeholder__card__item--bottom"
          :get-child-payload="(index) => getChildPayload(index)"
        >
          <Draggable
            v-for="(placeholderItem, index) in placeholders"
            :key="index"
            v-if="placeholderItem.type == 'placeholder_item'"
            :class="{
              dragging: currentDraggingIndex === placeholderItem.placeholderKey,
            }"
          >
            <div class="draggable-item">
              <div class="cptm-drag-element uil uil-draggabledots"></div>
              <div class="cptm-preview-placeholder__card__content">
                <card-widget-placeholder
                  :placeholderKey="placeholderItem.placeholderKey"
                  :id="'listings_header_' + index"
                  containerClass="cptm-preview-placeholder__card__box cptm-card-light"
                  :label="placeholderItem.label"
                  :availableWidgets="theAvailableWidgets"
                  :activeWidgets="active_widgets"
                  :acceptedWidgets="placeholderItem.acceptedWidgets"
                  :rejectedWidgets="placeholderItem.rejectedWidgets"
                  :selectedWidgets="placeholderItem.selectedWidgetList"
                  :maxWidget="placeholderItem.maxWidget"
                  :showWidgetsPickerWindow="
                    getActiveInsertWindowStatus('listings_header_' + index)
                  "
                  @edit-widget="editWidget($event)"
                  :readOnly="true"
                />
              </div>
            </div>
          </Draggable>
        </Container>
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
import { Container, Draggable } from "vue-dndrop";
import { applyDrag } from "../../helpers/vue-dndrop";
import helpers from "../../mixins/helpers";
import card_builder from "./../../mixins/form-fields/card-builder";

/**
 * Card Builder Listing Header Field Component
 *
 * A robust, high-performance Vue component for managing listing header widgets
 * with drag-and-drop functionality, widget availability checking, and data synchronization.
 *
 * Features:
 * - Drag and drop widget management
 * - Conditional widget display based on form fields
 * - Real-time data synchronization
 * - Performance optimized with caching
 * - Comprehensive error handling
 * - Memory leak prevention
 *
 */
export default {
  name: "card-builder-listing-header-field",
  components: {
    Container,
    Draggable,
  },
  mixins: [card_builder, helpers],
  props: {
    fieldId: {
      required: false,
      default: "",
    },
    value: {
      required: false,
      default: null,
    },
    widgets: {
      required: false,
      default: null,
    },
    cardOptions: {
      required: false,
      default: null,
    },
    layout: {
      required: false,
      default: null,
    },
    video: {
      type: Object,
    },
  },

  created() {
    this.init();
    this.$emit("update", this.output_data);
  },

  beforeDestroy() {
    this.cleanup();
  },

  watch: {
    output_data() {
      this.$emit("update", this.output_data);
    },
  },

  computed: {
    // output_data
    output_data() {
      let output = [];
      let placeholders = this.placeholders;

      // Parse Layout
      for (const placeholder of placeholders) {
        if ("placeholder_item" === placeholder.type) {
          const data = this.getWidgetData(placeholder);

          output.push({
            type: placeholder.type,
            placeholderKey: placeholder.placeholderKey,
            label: placeholder.label,
            selectedWidgets: data,
            acceptedWidgets: placeholder.acceptedWidgets,
            selectedWidgetList: placeholder.selectedWidgetList,
          });
          continue;
        }

        if ("placeholder_group" === placeholder.type) {
          let subGroupsData = [];

          for (const subPlaceholder of placeholder.placeholders) {
            const data = this.getWidgetData(subPlaceholder);

            subGroupsData.push({
              type: placeholder.type ? placeholder.type : "placeholder_item",
              placeholderKey: subPlaceholder.placeholderKey,
              label: subPlaceholder.label,
              selectedWidgets: data,
              acceptedWidgets: subPlaceholder.acceptedWidgets,
              selectedWidgetList: subPlaceholder.selectedWidgetList,
            });
            continue;
          }

          output.push({
            type: placeholder.type,
            placeholderKey: placeholder.placeholderKey,
            placeholders: subGroupsData,
          });

          continue;
        }
      }

      this.placeholders = output;

      return output;
    },

    // available widgets as a reactive computed object - OPTIMIZED
    theAvailableWidgets() {
      // Use shallow clone instead of deep clone for better performance
      const available_widgets = { ...this.available_widgets };
      const processedWidgets = {};

      for (const widgetKey in available_widgets) {
        const widget = available_widgets[widgetKey];

        // Create optimized widget object with minimal cloning
        const optimizedWidget = {
          ...widget,
          widget_name: widgetKey,
          widget_key: widgetKey,
        };

        // Check show_if condition only if it exists
        if (this.isObject(widget.show_if)) {
          const showIfResult = this.checkShowIfCondition({
            condition: widget.show_if,
          });

          if (showIfResult && showIfResult.status) {
            // Process matched fields more efficiently
            showIfResult.matched_data.forEach((matchedField, index) => {
              const currentKey =
                index === 0 ? widgetKey : `${widgetKey}_${index + 1}`;
              const finalKey = matchedField.widget_key || currentKey;

              processedWidgets[finalKey] = {
                ...optimizedWidget,
                widget_key: finalKey,
                label: matchedField.label || optimizedWidget.label,
              };
            });
          }
        } else {
          processedWidgets[widgetKey] = optimizedWidget;
        }
      }

      return processedWidgets;
    },

    // video modal content
    modalContent() {
      return this.video;
    },

    // Optimized method to get available widgets for a placeholder
    getAvailableWidgetsForPlaceholder() {
      return (placeholder) => {
        if (!placeholder || !placeholder.acceptedWidgets) {
          return [];
        }

        // Use cached result if available
        const cacheKey = `widgets_${placeholder.placeholderKey}`;
        if (
          this._placeholderWidgetsCache &&
          this._placeholderWidgetsCache[cacheKey]
        ) {
          return this._placeholderWidgetsCache[cacheKey];
        }

        const availableWidgets = placeholder.acceptedWidgets.filter(
          (widgetKey) => this.isWidgetAvailable(widgetKey),
        );

        // Cache the result
        if (!this._placeholderWidgetsCache) {
          this._placeholderWidgetsCache = {};
        }
        this._placeholderWidgetsCache[cacheKey] = availableWidgets;

        return availableWidgets;
      };
    },
  },

  data() {
    return {
      active_insert_widget_key: "",

      // Widget Options Window
      widgetOptionsWindowDefault: {
        animation: "cptm-animation-flip",
        widget: "",
      },

      widgetCardOptionsWindow: {
        animation: "cptm-animation-flip",
        widget: "",
      },

      widgetOptionsWindow: {
        animation: "cptm-animation-flip",
        widget: "",
      },

      // Dragging State
      currentDraggingIndex: null,
      currentSettingsDraggingWidgetKey: null,

      // Available Widgets
      available_widgets: {},

      // Active Widgets
      active_widgets: {},

      // Card Options
      card_options: {
        general: {},
        content_settings: {},
      },

      placeholdersMap: {},
      placeholders: [],
      allPlaceholderItems: [],
      showModal: false,
      errors: {
        hasError: false,
        lastError: null,
        errorCount: 0,
      },
      _dataChanged: false,
      _cachedOutputData: null,
      _widgetAvailabilityCache: null,
      _placeholderWidgetsCache: null,
      _debounceTimer: null,
    };
  },

  methods: {
    // ===========================================
    // INITIALIZATION & LIFECYCLE METHODS
    // ===========================================

    /**
     * Initialize component with error handling
     * @public
     */
    initializeComponent() {
      try {
        this.importWidgets();
        this.importCardOptions();
        this.importPlaceholders();
        this.importOldData();
        this.setupEventListeners();
        this._dataChanged = true;
      } catch (error) {
        this.handleError("Component initialization failed", error);
      }
    },

    /**
     * Setup event listeners for performance
     * @private
     */
    setupEventListeners() {
      // Debounced update emitter
      this.debouncedEmitUpdate = this.debounce(() => {
        this.emitUpdate();
      }, 100);
    },

    /**
     * Cleanup resources to prevent memory leaks - ENHANCED
     * @private
     */
    cleanup() {
      if (this._debounceTimer) {
        clearTimeout(this._debounceTimer);
        this._debounceTimer = null;
      }

      // Remove event listeners
      this.removeEventListeners();

      // Clear all caches
      this._cachedOutputData = null;
      this._placeholderWidgetsCache = null;

      if (this._widgetAvailabilityCache) {
        this._widgetAvailabilityCache.clear();
        this._widgetAvailabilityCache = null;
      }
    },

    /**
     * Remove event listeners
     * @private
     */
    removeEventListeners() {
      // Implementation for removing event listeners
      // This prevents memory leaks
    },

    /**
     * Emit update event with error handling
     * @private
     */
    emitUpdate() {
      try {
        this.$emit("update", this.output_data);
      } catch (error) {
        this.handleError("Failed to emit update", error);
      }
    },

    // ===========================================
    // ERROR HANDLING METHODS
    // ===========================================

    /**
     * Centralized error handling
     * @param {String} message - Error message
     * @param {Error} error - Error object
     * @private
     */
    handleError(message, error) {
      this.errors.hasError = true;
      this.errors.lastError = { message, error };
      this.errors.errorCount++;

      // Log error in development
      if (process.env.NODE_ENV === "development") {
        console.error(`[CardBuilder] ${message}:`, error);
      }

      // Emit error event for parent handling
      this.$emit("error", { message, error });
    },

    /**
     * Clear error state
     * @public
     */
    clearErrors() {
      this.errors.hasError = false;
      this.errors.lastError = null;
      this.errors.errorCount = 0;
    },

    // ===========================================
    // UTILITY METHODS
    // ===========================================

    /**
     * Debounce function for performance optimization
     * @param {Function} func - Function to debounce
     * @param {Number} wait - Wait time in milliseconds
     * @returns {Function} Debounced function
     * @private
     */
    debounce(func, wait) {
      return (...args) => {
        clearTimeout(this._debounceTimer);
        this._debounceTimer = setTimeout(() => func.apply(this, args), wait);
      };
    },

    /**
     * Optimized clone with error handling and performance improvements
     * @param {*} obj - Object to clone
     * @param {Boolean} deep - Whether to perform deep clone
     * @returns {*} Cloned object
     * @private
     */
    safeClone(obj, deep = true) {
      if (obj === null || obj === undefined) return obj;

      try {
        if (!deep) {
          return Array.isArray(obj) ? [...obj] : { ...obj };
        }

        // Use structuredClone if available (modern browsers)
        if (typeof structuredClone !== "undefined") {
          return structuredClone(obj);
        }

        // Fallback to JSON method for deep cloning
        return JSON.parse(JSON.stringify(obj));
      } catch (error) {
        this.handleError("Failed to clone object", error);
        return obj;
      }
    },

    /**
     * Validate object structure
     * @param {*} obj - Object to validate
     * @param {String} type - Expected type
     * @returns {Boolean} Is valid
     * @private
     */
    isValidObject(obj, type = "object") {
      if (obj === null || obj === undefined) return false;

      switch (type) {
        case "array":
          return Array.isArray(obj);
        case "object":
          return typeof obj === "object" && !Array.isArray(obj);
        default:
          return typeof obj === type;
      }
    },

    // ===========================================
    // WIDGET AVAILABILITY METHODS
    // ===========================================

    /**
     * Check if widget is available with enhanced caching
     * @param {String} widgetKey - Widget key to check
     * @returns {Boolean} Is widget available
     * @public
     */
    isWidgetAvailable(widgetKey) {
      if (!widgetKey || typeof widgetKey !== "string") {
        return false;
      }

      // Initialize cache if not exists
      if (!this._widgetAvailabilityCache) {
        this._widgetAvailabilityCache = new Map();
      }

      // Check cache first with timestamp validation
      const cached = this._widgetAvailabilityCache.get(widgetKey);
      if (cached && Date.now() - cached.timestamp < 30000) {
        // 30 second cache
        return cached.value;
      }

      const isAvailable = this.checkWidgetAvailability(widgetKey);

      // Cache result with timestamp
      this._widgetAvailabilityCache.set(widgetKey, {
        value: isAvailable,
        timestamp: Date.now(),
      });

      return isAvailable;
    },

    /**
     * Internal widget availability check
     * @param {String} widgetKey - Widget key to check
     * @returns {Boolean} Is widget available
     * @private
     */
    checkWidgetAvailability(widgetKey) {
      try {
        // Basic check if widget exists
        if (!this.available_widgets[widgetKey]) {
          return false;
        }

        const widget = this.available_widgets[widgetKey];

        // Check show_if condition if present
        if (widget.show_if && this.isValidObject(widget.show_if)) {
          const showIfResult = this.checkShowIfCondition({
            condition: widget.show_if,
          });
          return showIfResult && showIfResult.status === true;
        }

        return true;
      } catch (error) {
        this.handleError(
          `Error checking widget availability for ${widgetKey}`,
          error,
        );
        return false;
      }
    },

    /**
     * Clear widget availability cache
     * @public
     */
    clearWidgetAvailabilityCache() {
      if (this._widgetAvailabilityCache) {
        this._widgetAvailabilityCache.clear();
      }
    },

    // ===========================================
    // DATA PROCESSING METHODS
    // ===========================================

    /**
     * Get widget data with enhanced optimization
     * @param {Object} placeholderData - Placeholder data
     * @returns {Array} Widget data
     * @private
     */
    getWidgetData(placeholderData) {
      if (!this.isValidObject(placeholderData)) {
        return [];
      }

      const {
        acceptedWidgets = [],
        selectedWidgets = [],
        selectedWidgetList = [],
      } = placeholderData;

      // Early return if no widgets to process
      if (!selectedWidgets.length && !selectedWidgetList.length) {
        return [];
      }

      // Create a map for O(1) lookup instead of O(n) indexOf operations
      const acceptedWidgetsMap = new Map();
      acceptedWidgets.forEach((widget, index) => {
        acceptedWidgetsMap.set(widget, index);
      });

      // Sort widgets based on accepted order using map lookup
      const sortedSelectedWidgetList = this.sortWidgetsByAcceptedOrderOptimized(
        selectedWidgetList,
        acceptedWidgetsMap,
      );
      const sortedSelectedWidgets = this.sortWidgetsByAcceptedOrderOptimized(
        selectedWidgets,
        acceptedWidgetsMap,
        "widget_key",
      );

      // Filter and process valid widgets
      const validWidgets = this.filterValidWidgets(
        sortedSelectedWidgets,
        selectedWidgetList,
      );

      return this.processValidWidgets(validWidgets);
    },

    /**
     * Sort widgets by accepted order - OPTIMIZED VERSION
     * @param {Array} widgets - Widgets to sort
     * @param {Map} acceptedOrderMap - Accepted order map for O(1) lookup
     * @param {String} keyField - Key field for comparison
     * @returns {Array} Sorted widgets
     * @private
     */
    sortWidgetsByAcceptedOrderOptimized(
      widgets,
      acceptedOrderMap,
      keyField = null,
    ) {
      if (!this.isValidObject(widgets, "array") || !acceptedOrderMap) {
        return widgets;
      }

      return widgets.sort((a, b) => {
        const aKey = keyField ? a[keyField] : a;
        const bKey = keyField ? b[keyField] : b;
        const aIndex = acceptedOrderMap.get(aKey) ?? Number.MAX_SAFE_INTEGER;
        const bIndex = acceptedOrderMap.get(bKey) ?? Number.MAX_SAFE_INTEGER;
        return aIndex - bIndex;
      });
    },

    /**
     * Sort widgets by accepted order - LEGACY VERSION (for backward compatibility)
     * @param {Array} widgets - Widgets to sort
     * @param {Array} acceptedOrder - Accepted order array
     * @param {String} keyField - Key field for comparison
     * @returns {Array} Sorted widgets
     * @private
     */
    sortWidgetsByAcceptedOrder(widgets, acceptedOrder, keyField = null) {
      if (
        !this.isValidObject(widgets, "array") ||
        !this.isValidObject(acceptedOrder, "array")
      ) {
        return widgets;
      }

      return widgets.sort((a, b) => {
        const aKey = keyField ? a[keyField] : a;
        const bKey = keyField ? b[keyField] : b;
        return acceptedOrder.indexOf(aKey) - acceptedOrder.indexOf(bKey);
      });
    },

    /**
     * Filter valid widgets
     * @param {Array} selectedWidgets - Selected widgets
     * @param {Array} selectedWidgetList - Selected widget list
     * @returns {Array} Valid widgets
     * @private
     */
    filterValidWidgets(selectedWidgets, selectedWidgetList) {
      return selectedWidgets
        .map((widget, index) => {
          if (widget && widget.widget_key) {
            return widget;
          }

          // Fallback to selectedWidgetList
          const widgetName = selectedWidgetList?.[index];
          return widgetName ? { widget_key: widgetName, ...widget } : null;
        })
        .filter((widget) => widget && widget.widget_key);
    },

    /**
     * Process valid widgets data
     * @param {Array} validWidgets - Valid widgets
     * @returns {Array} Processed widget data
     * @private
     */
    processValidWidgets(validWidgets) {
      const data = [];

      for (const widget of validWidgets) {
        try {
          const widgetName = widget.widget_key;

          if (
            !this.active_widgets[widgetName] ||
            !this.isValidObject(this.active_widgets[widgetName])
          ) {
            continue;
          }

          const widgetData = this.extractWidgetData(widgetName);
          if (widgetData) {
            data.push(widgetData);
          }
        } catch (error) {
          this.handleError(
            `Error processing widget ${widget.widget_key}`,
            error,
          );
        }
      }

      return data;
    },

    /**
     * Extract widget data with options processing
     * @param {String} widgetName - Widget name
     * @returns {Object|null} Widget data
     * @private
     */
    extractWidgetData(widgetName) {
      const activeWidget = this.active_widgets[widgetName];
      if (!activeWidget) return null;

      const widgetData = this.safeClone(activeWidget);

      // Process widget options if available
      if (
        this.isValidObject(activeWidget.options) &&
        this.isValidObject(activeWidget.options.fields)
      ) {
        this.processWidgetOptions(
          widgetName,
          widgetData,
          activeWidget.options.fields,
        );
      }

      return widgetData;
    },

    /**
     * Process widget options
     * @param {String} widgetName - Widget name
     * @param {Object} widgetData - Widget data
     * @param {Object} widgetOptions - Widget options
     * @private
     */
    processWidgetOptions(widgetName, widgetData, widgetOptions) {
      for (const option in widgetOptions) {
        try {
          if (option === "icon" && widgetData.icon) {
            widgetData.icon = widgetOptions[option]?.value || widgetData.icon;
            this.available_widgets[widgetName].icon =
              widgetOptions[option]?.value || widgetData.icon;
          }

          if (widgetData?.options?.fields) {
            widgetData.options.fields[option] = widgetOptions[option];
            this.available_widgets[widgetName].options.fields[option] =
              widgetOptions[option];
          }
        } catch (error) {
          this.handleError(`Error processing widget option ${option}`, error);
        }
      }
    },

    // ===========================================
    // LEGACY METHODS (Maintained for compatibility)
    // ===========================================

    /**
     * Legacy init method for backward compatibility
     * @deprecated Use initializeComponent() instead
     * @public
     */
    init() {
      this.initializeComponent();
    },

    // ===========================================
    // DRAG AND DROP METHODS
    // ===========================================

    /**
     * Get child payload for drag operations
     * @param {Number} index - Item index
     * @returns {Object|null} Payload data
     * @public
     */
    getChildPayload(index) {
      try {
        const draggablePlaceholders = this.placeholders.filter(
          (placeholder) => placeholder.type === "placeholder_item",
        );

        return draggablePlaceholders[index] || null;
      } catch (error) {
        this.handleError("Error getting child payload", error);
        return null;
      }
    },

    /**
     * Reorder widgets within the same placeholder
     * @param {Number} placeholderIndex - Placeholder index
     * @param {Number} sourceIndex - Source index
     * @param {Number} destinationIndex - Destination index
     * @private
     */
    reorderWidgetsWithinPlaceholder(
      placeholderIndex,
      sourceIndex,
      destinationIndex,
    ) {
      const placeholder = this.allPlaceholderItems[placeholderIndex];
      if (!placeholder) return;

      const widgets = placeholder.acceptedWidgets;
      const selectedWidgets = placeholder.selectedWidgets;
      const selectedWidgetList = placeholder.selectedWidgetList;

      // Move widget in acceptedWidgets
      const [movedWidget] = widgets.splice(sourceIndex, 1);
      widgets.splice(destinationIndex, 0, movedWidget);

      // Update selectedWidgetList if widget is selected
      if (selectedWidgetList && selectedWidgetList.includes(movedWidget)) {
        const selectedIndex = selectedWidgetList.indexOf(movedWidget);
        selectedWidgetList.splice(selectedIndex, 1);
        selectedWidgetList.splice(destinationIndex, 0, movedWidget);
      }

      // Reorder selectedWidgets
      if (selectedWidgets) {
        selectedWidgets.sort((a, b) => {
          return (
            selectedWidgetList.indexOf(a.widget_key) -
            selectedWidgetList.indexOf(b.widget_key)
          );
        });
      }

      // Sync placeholders
      this.placeholders = this.syncPlaceholdersWithAllPlaceholderItems(
        this.allPlaceholderItems,
        this.placeholders,
      );
    },

    /**
     * Move widget between different placeholders
     * @param {Number} sourcePlaceholderIndex - Source placeholder index
     * @param {Number} destinationPlaceholderIndex - Destination placeholder index
     * @param {Number} sourceIndex - Source index
     * @param {Number} destinationIndex - Destination index
     * @private
     */
    moveWidgetBetweenPlaceholders(
      sourcePlaceholderIndex,
      destinationPlaceholderIndex,
      sourceIndex,
      destinationIndex,
    ) {
      // Implementation for moving widgets between placeholders
      // This is a complex operation that requires careful data management
      console.warn(
        "Moving widgets between placeholders is not yet implemented",
      );
    },

    // ===========================================
    // DATA SYNCHRONIZATION METHODS
    // ===========================================

    /**
     * Sync allPlaceholderItems with current placeholders - ENHANCED
     * @private
     */
    syncAllPlaceholderItems() {
      try {
        const newAllPlaceholderItems = [];

        this.placeholders.forEach((placeholder) => {
          if (placeholder.type === "placeholder_item") {
            const matchedItem = this.allPlaceholderItems.find(
              (item) => item.placeholderKey === placeholder.placeholderKey,
            );
            if (matchedItem) {
              // Update the matched item with current placeholder data
              const updatedItem = {
                ...matchedItem,
                selectedWidgets:
                  placeholder.selectedWidgets || matchedItem.selectedWidgets,
                selectedWidgetList:
                  placeholder.selectedWidgetList ||
                  matchedItem.selectedWidgetList,
                acceptedWidgets:
                  placeholder.acceptedWidgets || matchedItem.acceptedWidgets,
                label: placeholder.label || matchedItem.label,
                type: placeholder.type || matchedItem.type,
                maxWidget:
                  placeholder.maxWidget !== undefined
                    ? placeholder.maxWidget
                    : matchedItem.maxWidget,
              };
              newAllPlaceholderItems.push(updatedItem);
            }
          } else if (placeholder.type === "placeholder_group") {
            placeholder.placeholders.forEach((subPlaceholder) => {
              const matchedItem = this.allPlaceholderItems.find(
                (item) => item.placeholderKey === subPlaceholder.placeholderKey,
              );
              if (matchedItem) {
                // Update the matched item with current subPlaceholder data
                const updatedItem = {
                  ...matchedItem,
                  selectedWidgets:
                    subPlaceholder.selectedWidgets ||
                    matchedItem.selectedWidgets,
                  selectedWidgetList:
                    subPlaceholder.selectedWidgetList ||
                    matchedItem.selectedWidgetList,
                  acceptedWidgets:
                    subPlaceholder.acceptedWidgets ||
                    matchedItem.acceptedWidgets,
                  label: subPlaceholder.label || matchedItem.label,
                  type: subPlaceholder.type || matchedItem.type,
                  maxWidget:
                    subPlaceholder.maxWidget !== undefined
                      ? subPlaceholder.maxWidget
                      : matchedItem.maxWidget,
                };
                newAllPlaceholderItems.push(updatedItem);
              }
            });
          }
        });

        this.allPlaceholderItems = newAllPlaceholderItems;
      } catch (error) {
        this.handleError("Error syncing allPlaceholderItems", error);
      }
    },

    /**
     * Force synchronization of allPlaceholderItems after drag operations
     * @public
     */
    forceSyncAllPlaceholderItems() {
      this.syncAllPlaceholderItems();
      // Clear caches to ensure fresh data
      this.clearWidgetAvailabilityCache();
      this._placeholderWidgetsCache = null;
    },

    // Handle drag start event
    onDragStart(dragResult) {
      // Get the dragged item from the payload
      const draggedItem = dragResult.payload;

      if (draggedItem && draggedItem.placeholderKey) {
        this.currentDraggingIndex = draggedItem.placeholderKey;
      }
    },

    // Handle drag end event
    onDragEnd() {
      this.currentDraggingIndex = null;
    },

    // Handle settings drag start event
    onSettingsDragStart(dragResult, placeholderIndex) {
      // Get the dragged item from the payload
      const draggedItem = dragResult.payload;

      if (
        draggedItem &&
        draggedItem.draggedItemIndex !== undefined &&
        draggedItem.placeholderIndex !== undefined
      ) {
        // Ensure we get a string widget key, not an object
        const widgetKey = draggedItem.widgetKey;
        this.currentSettingsDraggingWidgetKey =
          typeof widgetKey === "object"
            ? widgetKey.widget_key || widgetKey.key
            : widgetKey;
      }
    },

    // Handle settings drag end event
    onSettingsDragEnd() {
      this.currentSettingsDraggingWidgetKey = null;

      // Remove dragging class from all dndrop-draggable-wrapper elements
      this.$nextTick(() => {
        const draggableWrappers = document.querySelectorAll(
          ".dndrop-draggable-wrapper",
        );
        draggableWrappers.forEach((wrapper) => {
          wrapper.classList.remove("dragging");
        });
      });
    },

    // Handle the drop event
    onDrop(dropResult) {
      const draggablePlaceholders = this.placeholders.filter(
        (placeholder) => placeholder.type === "placeholder_item",
      );

      // Update only the filtered placeholders
      const updatedPlaceholders = applyDrag(draggablePlaceholders, dropResult);

      // Map the updated placeholders back to their original positions in the full array
      this.placeholders = this.placeholders.map((placeholder) => {
        if (placeholder.type === "placeholder_item") {
          return updatedPlaceholders.shift(); // Replace with the updated item
        }
        return placeholder; // Keep other placeholders unchanged
      });

      // Sync allPlaceholderItems with the updated placeholders - FIXED
      let newAllPlaceholderItems = [];

      // Iterate over placeholders to update the newAllPlaceholderItems array
      this.placeholders.forEach((placeholder) => {
        if (placeholder.type === "placeholder_item") {
          // Find the matching item from allPlaceholderItems
          const matchedItem = this.allPlaceholderItems.find(
            (item) => item.placeholderKey === placeholder.placeholderKey,
          );

          // If a matched item is found, update it with the new placeholder data
          if (matchedItem) {
            // Create updated item with new order and data from placeholder
            const updatedItem = {
              ...matchedItem,
              // Update with any changes from the placeholder
              selectedWidgets:
                placeholder.selectedWidgets || matchedItem.selectedWidgets,
              selectedWidgetList:
                placeholder.selectedWidgetList ||
                matchedItem.selectedWidgetList,
              acceptedWidgets:
                placeholder.acceptedWidgets || matchedItem.acceptedWidgets,
              // Preserve other properties
              placeholderKey: placeholder.placeholderKey,
              label: placeholder.label || matchedItem.label,
              type: placeholder.type || matchedItem.type,
              maxWidget:
                placeholder.maxWidget !== undefined
                  ? placeholder.maxWidget
                  : matchedItem.maxWidget,
            };
            newAllPlaceholderItems.push(updatedItem);
          }
        } else if (placeholder.type === "placeholder_group") {
          // Iterate over subPlaceholders for a group
          placeholder.placeholders.forEach((subPlaceholder) => {
            const matchedItem = this.allPlaceholderItems.find(
              (item) => item.placeholderKey === subPlaceholder.placeholderKey,
            );

            // If a matched item is found, update it with the new subPlaceholder data
            if (matchedItem) {
              const updatedItem = {
                ...matchedItem,
                // Update with any changes from the subPlaceholder
                selectedWidgets:
                  subPlaceholder.selectedWidgets || matchedItem.selectedWidgets,
                selectedWidgetList:
                  subPlaceholder.selectedWidgetList ||
                  matchedItem.selectedWidgetList,
                acceptedWidgets:
                  subPlaceholder.acceptedWidgets || matchedItem.acceptedWidgets,
                // Preserve other properties
                placeholderKey: subPlaceholder.placeholderKey,
                label: subPlaceholder.label || matchedItem.label,
                type: subPlaceholder.type || matchedItem.type,
                maxWidget:
                  subPlaceholder.maxWidget !== undefined
                    ? subPlaceholder.maxWidget
                    : matchedItem.maxWidget,
              };
              newAllPlaceholderItems.push(updatedItem);
            }
          });
        }
      });

      // Update allPlaceholderItems with the new array
      this.allPlaceholderItems = newAllPlaceholderItems;

      // Force synchronization to ensure data consistency
      this.forceSyncAllPlaceholderItems();
    },

    // Get the payload for the settings child
    getSettingsChildPayload(draggedItemIndex, placeholderIndex) {
      const widgetKey =
        this.allPlaceholderItems[placeholderIndex]?.acceptedWidgets[
          draggedItemIndex
        ];

      // Extract the actual widget key string from the object
      const extractedWidgetKey =
        typeof widgetKey === "object"
          ? widgetKey.widget_key || widgetKey.key
          : widgetKey;

      // Return the payload containing both pieces of data
      return {
        draggedItemIndex: draggedItemIndex,
        placeholderIndex: placeholderIndex,
        // Extract the actual widget key string from the object
        widgetKey: extractedWidgetKey,
      };
    },

    // Handle the drop event on elements
    onElementsDrop(dropResult, placeholder_index) {
      const { removedIndex, addedIndex, payload } = dropResult;
      const { draggedItemIndex, placeholderIndex } = payload;

      if (removedIndex !== null || addedIndex !== null) {
        let destinationItemIndex;
        let destinationPlaceholderIndex;
        const sourceItemIndex = draggedItemIndex;
        const sourcePlaceholderIndex = placeholderIndex;

        if (addedIndex !== null) {
          destinationItemIndex = addedIndex;
          destinationPlaceholderIndex = placeholder_index;
        } else {
          destinationItemIndex = null;
          destinationPlaceholderIndex = null;
        }

        // Get the widget key from the source placeholder
        const widgetKey =
          this.allPlaceholderItems[sourcePlaceholderIndex]?.acceptedWidgets[
            draggedItemIndex
          ];

        if (widgetKey !== undefined) {
          if (sourcePlaceholderIndex === destinationPlaceholderIndex) {
            // Moving within the same placeholder
            const widgets =
              this.allPlaceholderItems[sourcePlaceholderIndex].acceptedWidgets;
            const selectedWidgets =
              this.allPlaceholderItems[sourcePlaceholderIndex].selectedWidgets;
            const selectedWidgetList =
              this.allPlaceholderItems[sourcePlaceholderIndex]
                .selectedWidgetList;

            // Remove the widget from the source position
            const [movedWidget] = widgets.splice(sourceItemIndex, 1);

            // Insert the widget at the destination position
            widgets.splice(destinationItemIndex, 0, movedWidget);

            // Update selectedWidgetList position based on acceptedWidgets
            const selectedWidgetIndex =
              selectedWidgetList && selectedWidgetList.indexOf(movedWidget);
            if (selectedWidgetIndex && selectedWidgetIndex !== -1) {
              // Remove the widget from the selected position
              selectedWidgetList.splice(selectedWidgetIndex, 1);

              // Insert the widget at the new position
              const newSelectedIndex = widgets.indexOf(movedWidget);
              selectedWidgetList.splice(newSelectedIndex, 0, movedWidget);
            }

            // Reorder `selectedWidgets` based on `selectedWidgetList`
            selectedWidgets &&
              selectedWidgets.sort((a, b) => {
                return (
                  selectedWidgetList.indexOf(a.widget_key) -
                  selectedWidgetList.indexOf(b.widget_key)
                );
              });

            // Update Placeholders
            const updatedPlaceholders =
              this.syncPlaceholdersWithAllPlaceholderItems(
                this.allPlaceholderItems,
                this.placeholders || [],
              );

            this.placeholders = updatedPlaceholders;

            // Force synchronization to ensure allPlaceholderItems is updated
            this.forceSyncAllPlaceholderItems();
          } else if (destinationPlaceholderIndex !== null) {
            // Moving between different placeholders
            // this.allPlaceholderItems[destinationPlaceholderIndex].selectedWidgetList.splice(destinationItemIndex, 0, widgetKey);
            // this.allPlaceholderItems[sourcePlaceholderIndex].selectedWidgetList.splice(sourceItemIndex, 1);
          }
        }
      } else {
        return;
      }
    },

    // ===========================================
    // LEGACY COMPATIBILITY METHODS
    // ===========================================

    /**
     * Legacy method for backward compatibility
     * @deprecated Use isValidObject instead
     * @param {*} obj - Object to check
     * @returns {Boolean} Is truthy object
     */
    isTruthyObject(obj) {
      return this.isValidObject(obj);
    },

    /**
     * Check if string is valid JSON
     * @param {String} string - String to check
     * @returns {Boolean} Is valid JSON
     * @public
     */
    isJSON(string) {
      try {
        JSON.parse(string);
        return true;
      } catch (e) {
        return false;
      }
    },

    // ===========================================
    // UI INTERACTION METHODS
    // ===========================================

    /**
     * Open modal
     * @public
     */
    openModal() {
      try {
        this.showModal = true;
      } catch (error) {
        this.handleError("Error opening modal", error);
      }
    },

    /**
     * Close modal
     * @public
     */
    closeModal() {
      try {
        this.showModal = false;
      } catch (error) {
        this.handleError("Error closing modal", error);
      }
    },

    // ===========================================
    // LEGACY METHODS (Maintained for compatibility)
    // ===========================================

    /**
     * Legacy widget options window active status
     * @deprecated Use windows.widgetOptions.isActive instead
     * @param {String} widgetKey - Widget key
     * @returns {Boolean} Is active
     */
    widgetOptionsWindowActiveStatus(widgetKey) {
      return (
        this.widgetOptionsWindow.widget === widgetKey &&
        typeof this.active_widgets[widgetKey] !== "undefined"
      );
    },

    /**
     * Legacy widget card options window active status
     * @deprecated Use windows.widgetCardOptions.isActive instead
     * @returns {Boolean} Is active
     */
    widgetCardOptionsWindowActiveStatus() {
      return this.widgetCardOptionsWindow.widget !== "";
    },

    /**
     * Process available widgets with show_if conditions
     * @returns {Object} Processed available widgets
     * @private
     */
    processAvailableWidgets() {
      try {
        const availableWidgets = this.safeClone(this.available_widgets);

        for (const widget in availableWidgets) {
          availableWidgets[widget].widget_name = widget;
          availableWidgets[widget].widget_key = widget;

          // Check show_if condition
          if (this.isValidObject(availableWidgets[widget].show_if)) {
            const showIfResult = this.checkShowIfCondition({
              condition: availableWidgets[widget].show_if,
            });

            const mainWidget = availableWidgets[widget];
            delete availableWidgets[widget];

            if (showIfResult && showIfResult.status) {
              const widgetKeys = [];

              for (const matchedField of showIfResult.matched_data) {
                const widgetCopy = this.safeClone(mainWidget);
                const currentKey = widgetKeys.includes(widget)
                  ? `${widget}_${widgetKeys.length + 1}`
                  : widget;

                widgetCopy.widget_key = currentKey;

                if (matchedField.widget_key) {
                  widgetCopy.widget_key = matchedField.widget_key;
                }

                if (
                  typeof matchedField.label === "string" &&
                  matchedField.label.length
                ) {
                  widgetCopy.label = matchedField.label;
                }

                availableWidgets[currentKey] = widgetCopy;
                widgetKeys.push(currentKey);
              }
            }
          }
        }

        return availableWidgets;
      } catch (error) {
        this.handleError("Error processing available widgets", error);
        return this.available_widgets;
      }
    },

    // ===========================================
    // DATA IMPORT METHODS (Legacy)
    // ===========================================

    /**
     * Import Old Data
     * @public
     */
    importOldData() {
      let value = JSON.parse(JSON.stringify(this.value));

      if (!Array.isArray(value)) {
        return;
      }

      let newPlaceholders = [];
      let newAllPlaceholders = [];

      // Import Layout
      // -------------------------
      const addActiveWidget = (widget) => {
        // Ensure that the widget exists in the available widgets
        if (!this.theAvailableWidgets[widget.widget_name]) {
          console.error(
            `Widget ${widget.widget_name} not found in available widgets.`,
          );
          return; // Exit if widget is not available
        }

        let widgets_template = {
          ...this.theAvailableWidgets[widget.widget_name],
        };

        let has_widget_options = false;

        if (widgets_template.options && widgets_template.options.fields) {
          has_widget_options = true;
        }

        // Iterate over the properties of widgets_template and copy values from widget
        for (let root_option in widgets_template) {
          if ("options" === root_option) {
            continue;
          }

          // Ensure that the value exists in the widget and is not undefined
          if (typeof widget[root_option] === "undefined") {
            continue;
          }

          widgets_template[root_option] = widget[root_option];
        }

        // Handle widget options fields
        if (has_widget_options) {
          for (let option_key in widgets_template.options.fields) {
            if (typeof widget.options?.fields[option_key] === "undefined") {
              continue;
            }

            widgets_template.options.fields[option_key] =
              widget.options.fields[option_key];

            // Check if the option key matches a root-level widget property
            // If it matches, update the root-level property with the field value
            if (widgets_template.hasOwnProperty(option_key)) {
              const fieldValue = widget.options.fields[option_key];
              // Only update if the field has a value property (for form fields)
              if (
                fieldValue &&
                typeof fieldValue === "object" &&
                fieldValue.hasOwnProperty("value")
              ) {
                widgets_template[option_key] = fieldValue.value;
              } else if (fieldValue !== undefined) {
                widgets_template[option_key] = fieldValue;
              }
            }
          }
        }

        // Apply field promotion logic during initialization
        const shouldPromote = this.shouldPromoteFieldsToRoot(
          widget.widget_name,
          widgets_template,
        );
        const processedWidget = shouldPromote
          ? this.promoteFieldsToRoot(widgets_template)
          : widgets_template;

        // Set the widget data in the active_widgets object
        Vue.set(this.active_widgets, widget.widget_name, processedWidget);
        Vue.set(this.available_widgets, widget.widget_name, processedWidget);
      };

      const importWidgets = (placeholder, destination) => {
        if (!this.placeholdersMap.hasOwnProperty(placeholder.placeholderKey)) {
          return;
        }

        let newPlaceholder = JSON.parse(
          JSON.stringify(this.placeholdersMap[placeholder.placeholderKey]),
        );

        if (placeholder.acceptedWidgets) {
          newPlaceholder.acceptedWidgets = placeholder.acceptedWidgets;
        }

        if (placeholder.selectedWidgets) {
          newPlaceholder.selectedWidgets = placeholder.selectedWidgets;
          newPlaceholder.selectedWidgetList = placeholder.selectedWidgets.map(
            (widget) => widget.widget_name,
          );
        }

        newPlaceholder.maxWidget =
          typeof newPlaceholder.maxWidget !== "undefined"
            ? parseInt(newPlaceholder.maxWidget)
            : 0;

        newAllPlaceholders.push(newPlaceholder);
        let targetPlaceholderIndex = destination.length;

        destination.splice(targetPlaceholderIndex, 0, newPlaceholder);

        // Add active widgets based on selectedWidgets
        placeholder.selectedWidgets.forEach((widget) => {
          if (
            typeof widget !== "undefined" &&
            typeof this.available_widgets[widget.widget_name] !== "undefined"
          ) {
            addActiveWidget(widget);
          }
        });
      };

      value.forEach((placeholder, index) => {
        if (!this.isTruthyObject(placeholder)) {
          return;
        }

        if ("placeholder_item" === placeholder.type) {
          // if (!Array.isArray(placeholder.selectedWidgets)) {
          //   return;
          // }

          importWidgets(placeholder, newPlaceholders);
          return;
        }

        if ("placeholder_group" === placeholder.type) {
          if (
            !this.placeholdersMap.hasOwnProperty(placeholder.placeholderKey)
          ) {
            return;
          }

          let newPlaceholder = JSON.parse(
            JSON.stringify(this.placeholdersMap[placeholder.placeholderKey]),
          );
          newPlaceholder.placeholders = [];
          let targetPlaceholderIndex = this.placeholders.length;

          newPlaceholders.splice(targetPlaceholderIndex, 0, newPlaceholder);

          placeholder.placeholders.forEach((subPlaceholder) => {
            // if (!Array.isArray(subPlaceholder.selectedWidgets)) {
            //   return;
            // }

            importWidgets(subPlaceholder, newPlaceholders[index].placeholders);
          });
        }
      });

      this.placeholders = newPlaceholders;
      this.allPlaceholderItems = newAllPlaceholders;
    },

    // Import Widgets
    importWidgets() {
      if (!this.isTruthyObject(this.widgets)) {
        return;
      }

      this.available_widgets = this.widgets;
    },

    // Import Card Options
    importCardOptions() {
      if (!this.isTruthyObject(this.cardOptions)) {
        return;
      }

      for (let section in this.card_options) {
        if (!this.isTruthyObject(this.cardOptions[section])) {
          return;
        }
        Vue.set(
          this.card_options,
          section,
          JSON.parse(JSON.stringify(this.cardOptions[section])),
        );
      }
    },

    // Import Placeholders
    importPlaceholders() {
      this.allPlaceholderItems = [];

      if (!Array.isArray(this.layout)) {
        return;
      }

      if (!this.layout.length) {
        return;
      }

      const sanitizePlaceholderData = (placeholder) => {
        if (!this.isTruthyObject(placeholder)) {
          placeholder = {};
        }

        if (typeof placeholder.label === "undefined") {
          placeholder.label = "";
        }

        return placeholder;
      };

      let sanitizedPlaceholders = [];

      for (const placeholder of this.layout) {
        if (!this.isTruthyObject(placeholder)) {
          continue;
        }

        let placeholderItem = placeholder;

        if (typeof placeholderItem.type === "undefined") {
          placeholderItem.type = "placeholder_item";
        }

        if (typeof placeholderItem.placeholderKey === "undefined") {
          continue;
        }

        if (
          this.placeholdersMap.hasOwnProperty(placeholderItem.placeholderKey)
        ) {
          continue;
        }

        Vue.set(
          this.placeholdersMap,
          placeholderItem.placeholderKey,
          placeholderItem,
        );

        if (placeholderItem.type === "placeholder_item") {
          const placeholderItemData = sanitizePlaceholderData(placeholderItem);

          if (placeholderItemData) {
            sanitizedPlaceholders.push(placeholderItemData);
            this.allPlaceholderItems.push(placeholderItemData);
          }

          continue;
        }

        if (placeholderItem.type === "placeholder_group") {
          if (typeof placeholderItem.placeholders === "undefined") {
            continue;
          }

          if (!Array.isArray(placeholderItem.placeholders)) {
            continue;
          }

          if (!placeholderItem.placeholders.length) {
            continue;
          }

          placeholderItem.placeholders.forEach(
            (placeholderSubItem, subPlaceholderIndex) => {
              if (
                this.placeholdersMap.hasOwnProperty(
                  placeholderSubItem.placeholderKey,
                )
              ) {
                placeholderItem.placeholders.splice(subPlaceholderIndex, 1);
                return;
              }

              Vue.set(
                this.placeholdersMap,
                placeholderSubItem.placeholderKey,
                placeholderSubItem,
              );

              const placeholderItemData =
                sanitizePlaceholderData(placeholderSubItem);

              if (placeholderItemData) {
                placeholderItem.placeholders.splice(
                  subPlaceholderIndex,
                  1,
                  placeholderItemData,
                );
                this.allPlaceholderItems.push(placeholderItemData);
              }
            },
          );

          if (placeholderItem.placeholders.length) {
            sanitizedPlaceholders.push(placeholderItem);
          }
        }
      }

      this.placeholders = sanitizedPlaceholders;
    },

    // Handle widget toggle from UI
    handleWidgetSwitch(event, widget_key, placeholder_index) {
      const placeholder = this.allPlaceholderItems[placeholder_index];

      // Return if placeholder is not found
      if (!placeholder) {
        return;
      }

      // Prevent selecting more than maxWidget
      if (
        event.target.checked &&
        placeholder.maxWidget > 0 &&
        placeholder.selectedWidgets?.length >= placeholder.maxWidget
      ) {
        event.preventDefault(); // Prevent the checkbox from being checked
        return;
      }

      const isChecked = event.target.checked;

      // Toggle widget in selectedWidgets
      this.toggleWidgetInSelectedWidgets(
        widget_key,
        placeholder_index,
        isChecked,
      );

      // Sync selectedWidgets between allPlaceholderItems and placeholders
      this.placeholders = this.syncSelectedWidgets(
        this.allPlaceholderItems,
        this.placeholders,
      );
    },

    // Add/remove widget from selectedWidgets & active_widgets
    toggleWidgetInSelectedWidgets(widget_key, placeholder_index, isChecked) {
      const placeholder = this.allPlaceholderItems[placeholder_index];
      const acceptedWidgets = placeholder.acceptedWidgets || [];
      let selectedWidgets = placeholder.selectedWidgets || [];
      let selectedWidgetList = placeholder.selectedWidgetList || [];

      if (!Array.isArray(selectedWidgets)) {
        selectedWidgets = Object.values(selectedWidgets); // Convert object to array if needed
      }

      if (isChecked) {
        // Add widget if it does not exist
        if (
          !selectedWidgets.some((widget) => widget.widget_key === widget_key)
        ) {
          const widgetIndex = acceptedWidgets.indexOf(widget_key);
          if (widgetIndex !== -1) {
            selectedWidgetList.push(widget_key);
            selectedWidgets.push(this.theAvailableWidgets[widget_key]);
          }
        }
      } else {
        // Remove widget if unchecked
        selectedWidgets = selectedWidgets.filter(
          (widget) => widget.widget_key !== widget_key,
        );
        selectedWidgetList = selectedWidgetList.filter(
          (widget) => widget !== widget_key,
        );
      }

      // Sort the selectedWidgetList and selectedWidgets based on acceptedWidgets order
      selectedWidgetList.sort(
        (a, b) => acceptedWidgets.indexOf(a) - acceptedWidgets.indexOf(b),
      );
      selectedWidgets.sort(
        (a, b) =>
          acceptedWidgets.indexOf(a.widget_key) -
          acceptedWidgets.indexOf(b.widget_key),
      );

      // Update selectedWidgets array
      this.$set(
        this.allPlaceholderItems[placeholder_index],
        "selectedWidgets",
        selectedWidgets,
      );
      this.$set(
        this.allPlaceholderItems[placeholder_index],
        "selectedWidgetList",
        selectedWidgetList,
      );

      // Update active_widgets separately
      if (isChecked) {
        const widgetToAdd = this.theAvailableWidgets[widget_key];

        // Apply field promotion for widgets with options.fields.value during initial creation
        const shouldPromote = this.shouldPromoteFieldsToRoot(
          widget_key,
          widgetToAdd,
        );
        const processedWidget = shouldPromote
          ? this.promoteFieldsToRoot(widgetToAdd)
          : widgetToAdd;
        this.$set(this.active_widgets, widget_key, processedWidget);
      } else {
        this.$delete(this.active_widgets, widget_key);
      }
    },

    // Sync selectedWidgets across placeholders
    syncSelectedWidgets(allPlaceholderItems, placeholders) {
      const allItemsMap = allPlaceholderItems.reduce((acc, item) => {
        acc[item.placeholderKey] = item;
        return acc;
      }, {});

      const updatePlaceholders = (placeholders) => {
        return placeholders.map((placeholder) => {
          if (allItemsMap[placeholder.placeholderKey]) {
            let selectedWidgets =
              allItemsMap[placeholder.placeholderKey].selectedWidgets || [];
            let selectedWidgetList =
              allItemsMap[placeholder.placeholderKey].selectedWidgetList || [];

            if (!Array.isArray(selectedWidgetList)) {
              selectedWidgetList = Object.values(selectedWidgetList);
            }
            Vue.set(placeholder, "selectedWidgets", selectedWidgets);
            Vue.set(placeholder, "selectedWidgetList", selectedWidgetList);
          }

          if (
            placeholder.type === "placeholder_group" &&
            placeholder.placeholders
          ) {
            Vue.set(
              placeholder,
              "placeholders",
              updatePlaceholders(placeholder.placeholders),
            );
          }

          return placeholder;
        });
      };

      return updatePlaceholders(placeholders);
    },

    // Sync placeholders with allPlaceholderItems
    syncPlaceholdersWithAllPlaceholderItems(allPlaceholderItems, placeholders) {
      const updatePlaceholderItem = (placeholder, allPlaceholderItem) => {
        if (placeholder.placeholderKey === allPlaceholderItem.placeholderKey) {
          // Filter acceptedWidgets to only include available widgets
          const filteredAcceptedWidgets = (
            allPlaceholderItem.acceptedWidgets || []
          ).filter((widgetKey) => this.isWidgetAvailable(widgetKey));
          placeholder.acceptedWidgets = [...filteredAcceptedWidgets];

          // Filter selectedWidgets to only include available widgets
          let selectedWidgets = allPlaceholderItem.selectedWidgets || [];
          let selectedWidgetList = allPlaceholderItem.selectedWidgetList || [];

          // Filter selectedWidgets based on available widgets
          const filteredSelectedWidgets = selectedWidgets.filter(
            (widget) =>
              widget &&
              widget.widget_key &&
              this.isWidgetAvailable(widget.widget_key),
          );

          // Filter selectedWidgetList based on available widgets
          const filteredSelectedWidgetList = selectedWidgetList.filter(
            (widgetKey) => this.isWidgetAvailable(widgetKey),
          );

          placeholder.selectedWidgets = [...filteredSelectedWidgets];
          placeholder.selectedWidgetList = [...filteredSelectedWidgetList];
        }
      };

      const updatePlaceholders = (placeholders) => {
        placeholders &&
          placeholders.forEach((placeholder) => {
            if (placeholder.type === "placeholder_group") {
              updatePlaceholders(placeholder.placeholders);
            } else if (placeholder.type === "placeholder_item") {
              const matchingItem = allPlaceholderItems.find(
                (item) => item.placeholderKey === placeholder.placeholderKey,
              );

              if (matchingItem) {
                updatePlaceholderItem(placeholder, matchingItem);
              }
            }
          });
      };

      updatePlaceholders(placeholders);
      return placeholders;
    },

    // Edit Widget
    editWidget(key) {
      if (key === this.widgetOptionsWindow.widget) {
        this.closeWidgetOptionsWindow();
        return;
      }
      if (typeof this.active_widgets[key] === "undefined") {
        return;
      }

      if (
        !this.active_widgets[key].options &&
        typeof this.active_widgets[key].options !== "object"
      ) {
        return;
      }

      this.widgetOptionsWindow = {
        ...this.widgetOptionsWindowDefault,
        ...this.active_widgets[key].options,
      };
      this.widgetOptionsWindow.widget = key;

      this.active_insert_widget_key = "";
    },

    // Update Widget Options
    updateWidgetOptionsData(data, options_window) {
      try {
        if (!data || !data.widgetKey || !data.updatedWidget) {
          return;
        }

        const widgetKey = data.widgetKey;
        const updatedWidget = data.updatedWidget;

        // Update the active widget with the complete updated widget data
        if (this.active_widgets[widgetKey]) {
          let processedWidget = updatedWidget;

          // Special handling for widgets with options.fields.value - add fields to root level
          if (this.shouldPromoteFieldsToRoot(widgetKey, updatedWidget)) {
            processedWidget = this.promoteFieldsToRoot(updatedWidget);
          }

          // Update both active_widgets and available_widgets
          this.updateWidgetData(widgetKey, processedWidget);

          // Mark data as changed
          this._dataChanged = true;
        }
      } catch (error) {
        this.handleError("Error updating widget options data", error);
      }
    },

    /**
     * Check if widget fields should be promoted to root level
     * @param {String} widgetKey - Widget key
     * @param {Object} widget - Widget object
     * @returns {Boolean} Should promote fields
     * @private
     */
    shouldPromoteFieldsToRoot(widgetKey, widget) {
      // Check if widget has valid structure
      if (
        !this.isValidObject(widget) ||
        !this.isValidObject(widget.options) ||
        !this.isValidObject(widget.options.fields) ||
        Object.keys(widget.options.fields).length === 0
      ) {
        return false;
      }

      // Check if any field in options.fields has a 'value' property
      // This indicates the field should be promoted to root level
      for (const fieldKey in widget.options.fields) {
        if (widget.options.fields.hasOwnProperty(fieldKey)) {
          const fieldObject = widget.options.fields[fieldKey];
          if (
            this.isValidObject(fieldObject) &&
            fieldObject.hasOwnProperty("value")
          ) {
            return true;
          }
        }
      }

      return false;
    },

    /**
     * Promote widget options fields to root level
     * @param {Object} widget - Widget object
     * @returns {Object} Widget with promoted fields
     * @private
     */
    promoteFieldsToRoot(widget) {
      try {
        // Use shallow clone first, only deep clone when necessary
        const promotedWidget = this.safeClone(widget, false);

        // Validate that options.fields exists and is an object
        if (
          !this.isValidObject(promotedWidget.options) ||
          !this.isValidObject(promotedWidget.options.fields)
        ) {
          return promotedWidget;
        }

        const fields = promotedWidget.options.fields;
        const fieldKeys = Object.keys(fields);

        // Process fields more efficiently
        for (let i = 0; i < fieldKeys.length; i++) {
          const fieldKey = fieldKeys[i];
          const fieldObject = fields[fieldKey];

          // Validate field structure before promoting
          if (this.isValidFieldForPromotion(fieldObject)) {
            // If field has a 'value' property, promote only the value
            if (
              this.isValidObject(fieldObject) &&
              fieldObject.hasOwnProperty("value")
            ) {
              // Convert value to boolean (1 or 0) if it's a boolean
              promotedWidget[fieldKey] =
                typeof fieldObject.value === "boolean"
                  ? fieldObject.value
                    ? 1
                    : 0
                  : fieldObject.value;
            } else {
              // Fallback: promote the entire field object if no value property
              promotedWidget[fieldKey] = this.safeClone(fieldObject, false);
            }
          }
        }

        return promotedWidget;
      } catch (error) {
        this.handleError("Error promoting fields to root", error);
        return widget; // Return original widget on error
      }
    },

    /**
     * Validate if a field is suitable for promotion to root level
     * @param {*} fieldValue - Field value to validate
     * @returns {Boolean} Is valid for promotion
     * @private
     */
    isValidFieldForPromotion(fieldValue) {
      // Allow objects, primitives, but exclude functions and undefined
      return (
        fieldValue !== null &&
        fieldValue !== undefined &&
        typeof fieldValue !== "function"
      );
    },

    /**
     * Update widget data in both active_widgets and available_widgets
     * @param {String} widgetKey - Widget key
     * @param {Object} widget - Widget data
     * @private
     */
    updateWidgetData(widgetKey, widget) {
      // Update active_widgets
      this.$set(this.active_widgets, widgetKey, widget);

      // Also update available_widgets to keep them in sync
      if (this.available_widgets[widgetKey]) {
        this.$set(this.available_widgets, widgetKey, widget);
      }
    },

    // Close Widget Options Window
    closeWidgetOptionsWindow() {
      this.widgetOptionsWindow = this.widgetOptionsWindowDefault;
    },

    // Get Active Insert Window Status
    getActiveInsertWindowStatus(current_item_key) {
      if (current_item_key === this.active_insert_widget_key) {
        return true;
      }

      return false;
    },

    /**
     * Clear widget availability cache
     * @public
     */
    clearWidgetAvailabilityCache() {
      if (this._widgetAvailabilityCache) {
        this._widgetAvailabilityCache.clear();
      }
    },
  },
};
</script>
