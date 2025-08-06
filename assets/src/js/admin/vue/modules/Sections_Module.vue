<template>
  <div class="cptm-tab-content" :class="containerClass">
    <div
      class="cptm-section"
      :class="sectionClass(section)"
      v-for="(section, section_key) in sections"
      :key="section_key"
    >
      <div
        class="directorist-form-action"
        v-if="
          ['submission_form_fields', 'search_form_fields'].includes(
            section.fields[0]
          )
        "
      >
        <a
          href="#"
          class="directorist-row-tooltip directorist-form-action__modal-btn"
          v-if="
            video &&
            ['submission_form_fields', 'search_form_fields'].includes(
              section.fields[0]
            )
          "
          :data-tooltip="video?.description"
          data-flow="bottom-right"
          @click.prevent="openModal()"
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
        </a>
        <a
          href="#"
          target="_blank"
          class="directorist-row-tooltip directorist-form-action__view"
          data-tooltip="View the form"
          data-flow="bottom-right"
          @click="saveData()"
          v-if="['submission_form_fields'].includes(section.fields[0])"
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
        </a>
      </div>

      <div
        class="cptm-title-area"
        :class="sectionTitleAreaClass(section)"
        v-if="
          ![
            'submission_form_fields',
            'search_form_fields',
            'single_listing_header',
            'single_listings_contents',
            'listings_card_grid_view',
            'listings_card_list_view',
          ].includes(section.fields[0])
        "
      >
        <h3 v-if="section.title" class="cptm-title" v-html="section.title"></h3>
        <div
          v-if="section.description"
          class="cptm-des"
          v-html="section.description"
        ></div>
      </div>

      <div class="cptm-form-fields" v-if="sectionFields(section)">
        <div
          v-for="(field, field_key) in sectionFields(section)"
          v-if="fields[field].group !== 'container'"
          :key="field_key"
        >
          <!-- Render the regular fields -->
          <component
            v-if="fields[field]"
            :is="getFormFieldName(fields[field].type)"
            :field-id="field_key"
            :fieldKey="field"
            :id="menuKey + '__' + section_key + '__' + field"
            :ref="field"
            :class="{ ['highlight-field']: getHighlightState(field) }"
            :cached-data="cached_fields[field]"
            :listing_type_id="listing_type_id"
            v-bind="fields[field]"
            @update="updateFieldValue(field, $event)"
            @save="$emit('save', $event)"
            @validate="updateFieldValidationState(field, $event)"
            @is-visible="updateFieldData(field, 'isVisible', $event)"
            @do-action="doAction($event, 'sections-module')"
          />

          <!-- Insert the wrapped container fields right after "way_to_show_preview" -->
          <div
            v-if="
              field === 'way_to_show_preview' &&
              groupedContainerFields.length > 0
            "
            class="cptm-field-group-container"
          >
            <div class="atbdp-row">
              <div class="atbdp-col atbdp-col-4">
                <label class="cptm-field-group-container__label">
                  <span>{{ containerGroupLabel }}</span>
                </label>
              </div>
              <div class="atbdp-col atbdp-col-8">
                <div class="cptm-container-group-fields">
                  <component
                    v-for="(
                      groupedField, groupedFieldKey
                    ) in groupedContainerFields"
                    :is="getFormFieldName(fields[groupedField].type)"
                    :field-id="groupedFieldKey"
                    :id="menuKey + '__' + section_key + '__' + groupedField"
                    :ref="groupedField"
                    :class="{
                      ['highlight-field']: getHighlightState(groupedField),
                    }"
                    :cached-data="cached_fields[groupedField]"
                    v-bind="fields[groupedField]"
                    @update="updateFieldValue(groupedField, $event)"
                    @save="$emit('save', $event)"
                    @validate="updateFieldValidationState(groupedField, $event)"
                    @is-visible="
                      updateFieldData(groupedField, 'isVisible', $event)
                    "
                    @do-action="doAction($event, 'sections-module')"
                    :key="groupedFieldKey"
                  />
                </div>
              </div>
            </div>
          </div>
          <!-- ends: .field-group-container -->
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
import { mapState } from "vuex";
import helpers from "./../mixins/helpers";

export default {
  name: "sections-module",
  mixins: [helpers],

  data() {
    return {
      showModal: false,
    };
  },

  props: {
    sections: {
      type: Object,
    },
    container: {
      type: String,
      default: "",
    },
    menuKey: {
      type: String,
      default: "",
    },
    listing_type_id: {
      type: String,
      default: "",
    },
    video: {
      type: Object,
    },
  },

  computed: {
    ...mapState(["metaKeys", "fields", "cached_fields"]),
    ...mapState({
      layout: (state) => state.layouts,
    }),

    containerClass() {
      return {
        "tab-wide": "wide" === this.container ? true : false,
        "tab-short-wide": "short-wide" === this.container ? true : false,
        "tab-full-width": "full-width" === this.container ? true : false,
      };
    },

    // Get the grouped container fields
    groupedContainerFields() {
      return this.groupFieldsByContainer().container || [];
    },

    // Get the label for the container group
    containerGroupLabel() {
      const firstContainerField = this.groupedContainerFields[0];
      return firstContainerField
        ? this.fields[firstContainerField].group_label
        : "";
    },

    modalContent() {
      return this.video;
    },
  },

  methods: {
    sectionFields(section) {
      if (!this.isObject(section)) {
        return false;
      }
      if (!Array.isArray(section.fields)) {
        return false;
      }
      return section.fields;
    },

    // Group fields by their group value, focusing on the container group
    groupFieldsByContainer() {
      let groupedFields = {
        container: [],
      };

      Object.keys(this.fields).forEach((field) => {
        if (this.fields[field].group === "container") {
          groupedFields.container.push(field);
        }
      });

      return groupedFields;
    },

    sectionClass(section) {
      return section.fields[0];
      // return {
      //   "cptm-short-wide": "short-width" === section.container ? true : false,
      // };
    },

    sectionTitleAreaClass(section) {
      return {
        "directorist-no-header": !section.title && !section.description,
        "cptm-text-center": "center" === section.title_align ? true : false,
      };
    },

    fieldWrapperClass(field_key, field) {
      let type_class =
        field && field.type
          ? "cptm-field-wraper-type-" + field.type
          : "cptm-field-wraper";
      let key_class = "cptm-field-wraper-key-" + field_key;

      return {
        [type_class]: true,
        [key_class]: true,
      };
    },

    fieldWrapperID(field) {
      let type_id = "";
      if (field && field.editor !== undefined) {
        type_id = field.editor === "wp_editor" ? "cptm-field_wp_editor" : "";
      }
      return type_id;
    },

    // Open the modal
    openModal() {
      this.showModal = true;
    },

    // Close the modal
    closeModal() {
      this.showModal = false;
    },

    saveData() {
      // Emit the save event before redirecting
      this.$emit("save");
    },
  },

  mounted() {
 
  },
};
</script>
