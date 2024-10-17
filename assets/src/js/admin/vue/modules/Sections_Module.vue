<template>
  <div class="cptm-tab-content" :class="containerClass">
    <div
      class="cptm-section"
      :class="sectionClass(section)"
      v-for="(section, section_key) in sections"
      :key="section_key"
    >
      <div
        class="directorist-form-doc"
        v-if="['submission_form_fields', 'search_form_fields', 'single_listing_header', 'single_listings_contents', 'listings_card_grid_view', 'listings_card_list_view'].includes(section.fields[0])"
      >
        <div class="directorist-form-doc-left">
          <div class="directorist-form-doc-title" v-html="section.title"></div>
          <a
            href="#"
            class="directorist-form-doc__watch-tutorial"
            v-if="video && ['submission_form_fields', 'search_form_fields'].includes(section.fields[0])"
            @click.prevent="openVideoPopup"
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
            {{video.button_text}}
          </a>
          <a
            :href="learn_more.url"
            target="_blank"
            class="directorist-form-doc__link"
            v-if="learn_more"
            v-html="learn_more.title"
          ></a>
        </div>
        <div class="directorist-form-doc-right">
          <a
            href=""
            target="_blank"
            class="directorist-form-doc__preview"
            @click="saveData()"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="16"
              height="16"
              viewBox="0 0 16 16"
              fill="none"
            >
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M5.33749 1.86822C5.34546 1.87353 5.35345 1.87886 5.36147 1.88421L12.394 6.57255C12.5975 6.70817 12.7861 6.83392 12.931 6.95077C13.0822 7.07273 13.2605 7.24185 13.3631 7.48925C13.4987 7.81625 13.4987 8.18376 13.3631 8.51077C13.2605 8.75817 13.0822 8.92729 12.931 9.04924C12.7861 9.1661 12.5975 9.29184 12.394 9.42746L5.33751 14.1318C5.08879 14.2976 4.86513 14.4468 4.67535 14.5496C4.48544 14.6525 4.22477 14.7701 3.92052 14.752C3.53135 14.7287 3.17185 14.5363 2.93665 14.2254C2.75276 13.9823 2.70602 13.7002 2.68631 13.4851C2.66662 13.2701 2.66664 13.0014 2.66666 12.7024L2.66666 3.32643C2.66666 3.31679 2.66666 3.30718 2.66666 3.2976C2.66664 2.99867 2.66662 2.72987 2.68631 2.51492C2.70602 2.29982 2.75276 2.01769 2.93665 1.77461C3.17185 1.46369 3.53135 1.27129 3.92052 1.24806C4.22477 1.22989 4.48545 1.3475 4.67536 1.45042C4.86513 1.55326 5.08878 1.70238 5.33749 1.86822ZM4.01674 2.61031C4.01584 2.61839 4.01495 2.62713 4.01409 2.63658C4.00066 2.78307 3.99999 2.98946 3.99999 3.32643V12.6736C3.99999 13.0106 4.00066 13.2169 4.01409 13.3634C4.01495 13.3729 4.01584 13.3816 4.01674 13.3897C4.02396 13.386 4.03173 13.3819 4.04007 13.3773C4.1694 13.3073 4.3415 13.1933 4.62187 13.0064L11.6322 8.33283C11.8655 8.17735 12.0022 8.08542 12.094 8.01137C12.099 8.00736 12.1036 8.00357 12.1079 8.00001C12.1036 7.99644 12.099 7.99265 12.094 7.98864C12.0022 7.9146 11.8655 7.82267 11.6322 7.66719L4.62187 2.99361C4.3415 2.80669 4.1694 2.69277 4.04007 2.62267C4.03173 2.61815 4.02396 2.61405 4.01674 2.61031Z"
                fill="currentColor"
              />
            </svg>
            preview
          </a>
        </div>
      </div>

      <div
        class="cptm-title-area"
        :class="sectionTitleAreaClass(section)"
        v-if="section.fields[0] !== 'submission_form_fields' && section.fields[0] !== 'search_form_fields' && section.fields[0] !== 'single_listing_header' && section.fields[0] !== 'single_listing_header' && section.fields[0] !== 'single_listings_contents' && section.fields[0] !== 'listings_card_grid_view' && section.fields[0] !== 'listings_card_list_view'"
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
    <form-builder-widget-video-component
      v-if="video"
      :videoOpened="showVideo"
      :video="video"
      @close-video="closeVideoPopup"
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
      showVideo: false,
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
    learn_more: {
      type: Object,
    },
  },

  computed: {
    ...mapState(["metaKeys", "fields", "cached_fields"]),

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
      return section.fields[0]
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

    // Open the video popup
    openVideoPopup() {
      this.showVideo = true;
    },

    // Close the video popup
    closeVideoPopup() {
      this.showVideo = false;
    },
    saveData() {
      // Emit the save event before redirecting
      this.$emit("save");
    },
  },
};
</script>
