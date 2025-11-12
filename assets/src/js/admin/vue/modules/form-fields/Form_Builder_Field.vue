<template>
  <div class="cptm-form-builder" :class="fieldKey">
    <div class="cptm-form-builder-sidebar">
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
            width="22"
            height="12"
            viewBox="0 0 22 12"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M0.5 0V12H17V9.46875L21.5 11.7188V0.28125L17 2.53125V0H0.5ZM2 1.5H15.5V10.5H2V1.5ZM20 2.71875V9.28125L17 7.78125V4.21875L20 2.71875Z"
              fill="#2C3239"
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
    <div class="cptm-form-builder-content cptm-col-sticky">
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
      <div
        class="cptm-form-builder-active-fields"
        :class="{ 'empty-content': !active_widget_groups.length }"
      >
        <div class="cptm-form-builder-active-fields-container">
          <div v-if="active_widget_groups.length">
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
                :expanded-group-key="expandedGroupKey"
                :expanded-group-fields-key="expandedGroupFieldsKey"
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
                @group-expanded="handleGroupExpanded"
                @group-fields-expanded="handleGroupFieldsExpanded"
                @append-widget="handleAppendWidget(widget_group_key)"
              />
            </draggable-list-item-wrapper>
          </div>

          <div class="cptm-form-builder-active-fields-empty" v-else>
            <div class="cptm-form-builder-active-fields-empty-img">
              <svg
                width="88"
                height="88"
                viewBox="0 0 88 88"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g
                  style="mix-blend-mode: luminosity"
                  clip-path="url(#clip0_9482_8623)"
                >
                  <path
                    d="M25.0537 27.9609H48.5117C53.293 27.9609 57.1689 31.8369 57.1689 36.6182V47.7891C57.1688 52.5702 53.2929 56.4463 48.5117 56.4463H16.3965V36.6182C16.3965 31.8369 20.2724 27.9609 25.0537 27.9609Z"
                    fill="#979EAB"
                    stroke="#0D0B27"
                    stroke-width="0.558532"
                  />
                  <path
                    d="M29.2425 37.1767C29.2425 30.2509 25.57 28.2402 23.7082 27.9609H54.3765C61.9725 27.9609 63.6854 34.1048 63.5923 37.1767V79.3458H29.2425V37.1767Z"
                    fill="#F1F6FF"
                  />
                  <path
                    d="M23.7082 27.9609C25.57 28.2402 29.2425 30.2509 29.2425 37.1767C29.2425 44.1025 29.2425 68.1752 29.2425 79.3458H63.5923V37.1767C63.6854 34.1048 61.9725 27.9609 54.3765 27.9609C46.7805 27.9609 30.732 27.9609 23.6572 27.9609"
                    stroke="#0D0B27"
                    stroke-width="0.558532"
                  />
                  <line
                    x1="28.9631"
                    y1="79.2061"
                    x2="93.7529"
                    y2="79.2061"
                    stroke="#0D0B27"
                    stroke-width="0.837798"
                  />
                  <line
                    x1="32.8733"
                    y1="36.3399"
                    x2="61.3584"
                    y2="36.3399"
                    stroke="#0D0B27"
                    stroke-width="0.558532"
                  />
                  <line
                    x1="32.8733"
                    y1="41.9239"
                    x2="61.3584"
                    y2="41.9239"
                    stroke="#0D0B27"
                    stroke-width="0.558532"
                  />
                  <line
                    x1="32.8733"
                    y1="47.5098"
                    x2="61.3584"
                    y2="47.5098"
                    stroke="#0D0B27"
                    stroke-width="0.558532"
                  />
                  <line
                    x1="32.8733"
                    y1="53.0957"
                    x2="61.3584"
                    y2="53.0957"
                    stroke="#0D0B27"
                    stroke-width="0.558532"
                  />
                  <line
                    x1="32.8733"
                    y1="58.6797"
                    x2="61.3584"
                    y2="58.6797"
                    stroke="#0D0B27"
                    stroke-width="0.558532"
                  />
                  <line
                    x1="32.8733"
                    y1="64.2657"
                    x2="61.3584"
                    y2="64.2657"
                    stroke="#0D0B27"
                    stroke-width="0.558532"
                  />
                  <line
                    x1="32.8733"
                    y1="69.8496"
                    x2="61.3584"
                    y2="69.8496"
                    stroke="#0D0B27"
                    stroke-width="0.558532"
                  />
                  <rect
                    x="0.0992054"
                    y="0.425111"
                    width="4.32146"
                    height="27.7808"
                    transform="matrix(0.849301 0.527909 -0.52791 0.8493 27.5558 41.3281)"
                    fill="#F1F6FF"
                    stroke="#0D0B27"
                    stroke-width="0.617352"
                  />
                  <rect
                    x="0.0992054"
                    y="0.425111"
                    width="6.79087"
                    height="23.8434"
                    transform="matrix(0.849301 0.527909 -0.52791 0.8493 18.034 54.3105)"
                    fill="#00C1FF"
                    stroke="#0D0B27"
                    stroke-width="0.617352"
                  />
                  <rect
                    x="0.0992054"
                    y="0.425111"
                    width="6.79087"
                    height="3.08676"
                    transform="matrix(0.849301 0.527909 -0.52791 0.8493 23.9003 44.8691)"
                    fill="#00C1FF"
                    stroke="#0D0B27"
                    stroke-width="0.617352"
                  />
                  <g clip-path="url(#clip1_9482_8623)">
                    <rect
                      width="43.2146"
                      height="43.2146"
                      rx="21.6073"
                      transform="matrix(0.849301 0.527909 -0.52791 0.8493 30.6172 -0.248047)"
                      fill="#F1F6FF"
                    />
                    <g clip-path="url(#clip2_9482_8623)">
                      <rect
                        width="38.2758"
                        height="38.2758"
                        rx="19.1379"
                        transform="matrix(0.849301 0.527909 -0.52791 0.8493 31.4106 3.15234)"
                        fill="#404040"
                      />
                      <path
                        d="M21.8235 21.6992L50.5403 21.6992C56.3933 21.6992 61.1388 26.444 61.1389 32.2969L61.1389 45.9717C61.1389 51.8247 56.3933 56.5693 50.5403 56.5693L11.2258 56.5693L11.2258 32.2969C11.226 26.4441 15.9707 21.6994 21.8235 21.6992Z"
                        fill="#979EAB"
                        stroke="#0D0B27"
                        stroke-width="0.683733"
                      />
                      <path
                        d="M26.9514 32.9808C26.9514 24.5025 22.4555 22.0411 20.1764 21.6992L57.7194 21.6992C67.0182 21.6992 69.1149 29.2203 69.001 32.9808L69.001 84.6026L26.9514 84.6026L26.9514 32.9808Z"
                        fill="#F1F6FF"
                      />
                      <path
                        d="M20.1764 21.6992C22.4555 22.0411 26.9514 24.5025 26.9514 32.9808C26.9514 41.4591 26.9514 70.928 26.9514 84.6026L69.001 84.6026L69.001 32.9808C69.1149 29.2203 67.0182 21.6992 57.7194 21.6992C48.4206 21.6992 28.7746 21.6992 20.114 21.6992"
                        stroke="#0D0B27"
                        stroke-width="0.683733"
                      />
                      <line
                        x1="31.396"
                        y1="31.955"
                        x2="66.2664"
                        y2="31.955"
                        stroke="#0D0B27"
                        stroke-width="0.683733"
                      />
                      <line
                        x1="31.396"
                        y1="38.7929"
                        x2="66.2664"
                        y2="38.7929"
                        stroke="#0D0B27"
                        stroke-width="0.683733"
                      />
                      <line
                        x1="31.396"
                        y1="45.6308"
                        x2="66.2664"
                        y2="45.6308"
                        stroke="#0D0B27"
                        stroke-width="0.683733"
                      />
                      <line
                        x1="31.396"
                        y1="52.4687"
                        x2="66.2664"
                        y2="52.4687"
                        stroke="#0D0B27"
                        stroke-width="0.683733"
                      />
                    </g>
                    <rect
                      x="0.0992054"
                      y="0.425111"
                      width="37.6585"
                      height="37.6584"
                      rx="18.8292"
                      transform="matrix(0.849301 0.527909 -0.52791 0.8493 31.65 3.16404)"
                      stroke="#0D0B27"
                      stroke-width="0.617352"
                    />
                  </g>
                  <rect
                    x="0.0992054"
                    y="0.425111"
                    width="42.5973"
                    height="42.5972"
                    rx="21.2986"
                    transform="matrix(0.849301 0.527909 -0.52791 0.8493 30.8566 -0.236354)"
                    stroke="#0D0B27"
                    stroke-width="0.617352"
                  />
                  <line
                    x1="29.5215"
                    y1="79.3437"
                    x2="-4.54898"
                    y2="79.3437"
                    stroke="#0D0B27"
                    stroke-width="0.558532"
                  />
                </g>
                <defs>
                  <clipPath id="clip0_9482_8623">
                    <rect width="88" height="88" fill="white" />
                  </clipPath>
                  <clipPath id="clip1_9482_8623">
                    <rect
                      width="43.2146"
                      height="43.2146"
                      rx="21.6073"
                      transform="matrix(0.849301 0.527909 -0.52791 0.8493 30.6172 -0.248047)"
                      fill="white"
                    />
                  </clipPath>
                  <clipPath id="clip2_9482_8623">
                    <rect
                      width="38.2758"
                      height="38.2758"
                      rx="19.1379"
                      transform="matrix(0.849301 0.527909 -0.52791 0.8493 31.4106 3.15234)"
                      fill="white"
                    />
                  </clipPath>
                </defs>
              </svg>
            </div>
            <p class="cptm-form-builder-active-fields-empty-text">
              No section added yet
            </p>
          </div>

          <div
            class="cptm-form-builder-active-fields-footer"
            v-if="showAddNewGroupButton"
          >
            <button
              type="button"
              class="cptm-btn"
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
          icon: "las la-align-left",
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

      expandedGroupKey: null, // Track which group is currently expanded
      expandedGroupFieldsKey: null, // Track which group has its fields/config expanded

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

    handleGroupExpanded(groupKey) {
      // Update the expanded group key - this will trigger child components to collapse if they're not the expanded one
      this.expandedGroupKey = groupKey;
    },

    handleGroupFieldsExpanded(groupKey) {
      // Update the expanded group fields key to implement accordion behavior for group configuration sections
      this.expandedGroupFieldsKey = groupKey;
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
