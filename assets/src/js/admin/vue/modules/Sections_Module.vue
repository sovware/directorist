<template>
  <div class="cptm-tab-content" :class="containerClass">
    <div
      v-if="
        sectionShouldRender(section, section_key) &&
        !shouldSkipSection(section, section_key)
      "
      class="cptm-section"
      :class="sectionClass(section, section_key)"
      :data-section-highlight-key="sectionHighlightKey(section_key)"
      v-for="(section, section_key) in sections"
      :key="section_key"
    >
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
        <template v-for="(field, field_key) in sectionFields(section)">
          <settings-default-location-address
            v-if="shouldRenderDefaultLocationAddress(section, field)"
            :key="'default-location-address-' + section_key"
            :config="section.defaultLocationAddress"
            @update-field="updateFieldValue($event.fieldKey, $event.value)"
          />

          <settings-notification-events
            v-if="shouldRenderNotificationEvents(section, field)"
            :key="'notification-events-' + section_key"
            :fields="fields"
            :sections="sections"
            :highlighted-field-key="highlightedFieldKey"
            @update-field="updateFieldValue($event.fieldKey, $event.value)"
          />

          <settings-seo-meta-fields
            v-if="shouldRenderSeoMetaFields(section, field)"
            :key="'seo-meta-fields-' + section_key"
            :fields="fields"
            :pairs="section.seoMetaPairs"
            :advanced-label="section.seoAdvancedLabel"
            @update-field="updateFieldValue($event.fieldKey, $event.value)"
          />

          <settings-checkout-currency-match
            v-if="shouldRenderCheckoutCurrencyMatch(section, field)"
            :key="'checkout-currency-match-' + section_key"
            :config="section.checkoutCurrencyMatch"
            :fields="fields"
            :custom-mode="isCheckoutCurrencyCustomMode(section_key)"
            @set-custom-mode="setCheckoutCurrencyCustomMode(section_key, $event)"
            @update-field="updateFieldValue($event.fieldKey, $event.value)"
          />

          <div
            v-if="shouldRenderNestedAdvancedToggle(section, field)"
            class="cptm-card-advanced"
            :class="nestedAdvancedToggleClass(section_key)"
            :key="'nested-advanced-toggle-' + section_key"
          >
            <button
              type="button"
              class="cptm-card-advanced__toggle"
              :aria-expanded="isNestedAdvancedOpen(section_key) ? 'true' : 'false'"
              @click.stop="toggleNestedAdvanced(section_key)"
            >
              <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="cptm-card-advanced__chevron"
              >
                <polyline points="9 18 15 12 9 6" />
              </svg>
              {{ nestedAdvancedToggleLabel(section) }}
            </button>
          </div>

          <div
            v-if="fieldShouldRenderInSection(section, field)"
            v-show="fieldIsVisibleInSection(section_key, section, field)"
            :key="field_key"
            :class="fieldWrapperClass(field, fields[field], section)"
            :data-field-suffix="fieldSuffix(field)"
          >
          <!-- Render the regular fields -->
            <settings-email-notification-toggle
              v-if="shouldRenderEmailNotificationToggle(field)"
              :field="fields[field]"
              :field-key="field"
              @update-field="updateFieldValue($event.fieldKey, $event.value)"
            />

            <settings-web-push-notification-toggle
              v-else-if="shouldRenderWebPushNotificationToggle(field)"
              :fields="fields"
              @update-field="updateFieldValue($event.fieldKey, $event.value)"
              @update-field-data="
                updateFieldData($event.fieldKey, $event.optionKey, $event.value)
              "
            />

            <settings-web-push-setup-card
              v-else-if="shouldRenderWebPushSetupCard(field)"
              :field="fields[field]"
              :is-locked="!webPushNotificationsEnabled"
            />

            <settings-notification-log-link
              v-else-if="shouldRenderNotificationLogLink(field)"
              :field="fields[field]"
            />

            <settings-extension-promotion
              v-else-if="shouldRenderExtensionPromotion(field)"
              :field="fields[field]"
            />

            <settings-active-gateways-toggle
              v-else-if="shouldRenderActiveGatewaysToggle(field)"
              :field="fields[field]"
              :field-key="field"
              @update-field="updateFieldValue($event.fieldKey, $event.value)"
            />

            <settings-restricted-countries-select
              v-else-if="shouldRenderRestrictedCountriesSelect(field)"
              :field="fields[field]"
              :field-key="field"
              @update-field="updateFieldValue($event.fieldKey, $event.value)"
            />

            <settings-page-setup-row
              v-else-if="shouldRenderPageSetupRow(field)"
              :field="fields[field]"
              :field-key="field"
              @update-field="updateFieldValue($event.fieldKey, $event.value)"
            />

            <settings-color-text-field
              v-else-if="shouldRenderPreviewBackgroundColor(field)"
              :field="fields[field]"
              :field-key="field"
              @update-field="updateFieldValue($event.fieldKey, $event.value)"
            />

            <settings-checkbox-array-accordion
              v-else-if="shouldRenderCheckboxArrayAccordion(field)"
              :field="fields[field]"
              :field-key="field"
              :is-highlighted="getHighlightState(field)"
              @update-field="updateFieldValue($event.fieldKey, $event.value)"
            />

            <component
              v-else-if="fields[field]"
              :is="getFormFieldName(fields[field].type)"
              :field-id="field_key"
              :fieldKey="field"
              :id="menuKey + '__' + section_key + '__' + field"
              :ref="field"
              :cached-data="cached_fields[field]"
              :listing_type_id="listing_type_id"
              :video="video"
              v-bind="fields[field]"
              @update="updateFieldValue(field, $event)"
              @save="$emit('save', $event)"
              @validate="updateFieldValidationState(field, $event)"
              @is-visible="updateFieldData(field, 'isVisible', $event)"
              @do-action="doAction($event, 'sections-module')"
            />

            <span
              v-if="fieldSuffix(field)"
              class="cptm-field-suffix"
              aria-hidden="true"
            >
              {{ fieldSuffix(field) }}
            </span>

          <div
            v-if="
              field === 'listings_card_grid_view' ||
              field === 'listings_card_list_view'
            "
            class="cptm-preview-notice"
          >
            <div class="cptm-preview-notice-content">
              <svg
                width="16"
                height="16"
                viewBox="0 0 16 16"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g clip-path="url(#clip0_8301_5081)">
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M7.99984 1.99984C4.68613 1.99984 1.99984 4.68613 1.99984 7.99984C1.99984 11.3135 4.68613 13.9998 7.99984 13.9998C11.3135 13.9998 13.9998 11.3135 13.9998 7.99984C13.9998 4.68613 11.3135 1.99984 7.99984 1.99984ZM0.666504 7.99984C0.666504 3.94975 3.94975 0.666504 7.99984 0.666504C12.0499 0.666504 15.3332 3.94975 15.3332 7.99984C15.3332 12.0499 12.0499 15.3332 7.99984 15.3332C3.94975 15.3332 0.666504 12.0499 0.666504 7.99984ZM7.33317 5.33317C7.33317 4.96498 7.63165 4.6665 7.99984 4.6665H8.0065C8.37469 4.6665 8.67317 4.96498 8.67317 5.33317C8.67317 5.70136 8.37469 5.99984 8.0065 5.99984H7.99984C7.63165 5.99984 7.33317 5.70136 7.33317 5.33317ZM7.99984 7.33317C8.36803 7.33317 8.6665 7.63165 8.6665 7.99984V10.6665C8.6665 11.0347 8.36803 11.3332 7.99984 11.3332C7.63165 11.3332 7.33317 11.0347 7.33317 10.6665V7.99984C7.33317 7.63165 7.63165 7.33317 7.99984 7.33317Z"
                    fill="#3E62F5"
                  />
                </g>
                <defs>
                  <clipPath id="clip0_8301_5081">
                    <rect width="16" height="16" fill="white" />
                  </clipPath>
                </defs>
              </svg>
              <p class="cptm-preview-notice-text">
                Want to enable/disable <strong>Grid</strong>,
                <strong>List</strong> or <strong>Map</strong> views for the All
                Listings Page?
              </p>
            </div>
            <div class="cptm-preview-notice-action">
              <a
                href="/wp-admin/edit.php?post_type=at_biz_dir&page=atbdp-settings#listing_settings__listings_page"
                target="_blank"
                class="cptm-preview-notice-btn"
              >
                Go to settings
                <svg
                  width="14"
                  height="14"
                  viewBox="0 0 14 14"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M6.48424 1.38007C6.769 1.09531 7.23068 1.09531 7.51544 1.38007L12.6196 6.48424C12.9044 6.769 12.9044 7.23068 12.6196 7.51544L7.51544 12.6196C7.23068 12.9044 6.769 12.9044 6.48424 12.6196C6.19948 12.3348 6.19948 11.8732 6.48424 11.5884L10.3436 7.729H1.89567C1.49296 7.729 1.1665 7.40254 1.1665 6.99984C1.1665 6.59713 1.49296 6.27067 1.89567 6.27067H10.3436L6.48424 2.41127C6.19948 2.12651 6.19948 1.66483 6.48424 1.38007Z"
                    fill="#4D5761"
                  />
                </svg>
              </a>
            </div>
          </div>

          <!-- Insert the wrapped container fields right after "way_to_show_preview" -->
          <div
            v-if="
              field === 'way_to_show_preview' &&
              sectionGroupedContainerFields(section).length > 0
            "
            class="cptm-field-group-container"
          >
            <div class="atbdp-row">
              <div class="atbdp-col atbdp-col-4">
                <label class="cptm-field-group-container__label">
                  <span>{{ containerGroupLabel(section) }}</span>
                </label>
              </div>
              <div class="atbdp-col atbdp-col-8">
                <div class="cptm-container-group-fields">
                  <component
                    v-for="(
                      groupedField, groupedFieldKey
                    ) in sectionGroupedContainerFields(section)"
                    :is="getFormFieldName(fields[groupedField].type)"
                    :field-id="groupedFieldKey"
                    :id="menuKey + '__' + section_key + '__' + groupedField"
                    :ref="groupedField"
                    :class="[
                      fieldWrapperClass(groupedField, fields[groupedField], section),
                      { ['highlight-field']: getHighlightState(groupedField) },
                    ]"
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
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import helpers from "./../mixins/helpers";
import SettingsActiveGatewaysToggle from "../apps/settings-manager/components/Settings_Active_Gateways_Toggle.vue";
import SettingsCheckboxArrayAccordion from "../apps/settings-manager/components/Settings_Checkbox_Array_Accordion.vue";
import SettingsCheckoutCurrencyMatch from "../apps/settings-manager/components/Settings_Checkout_Currency_Match.vue";
import SettingsColorTextField from "../apps/settings-manager/components/Settings_Color_Text_Field.vue";
import SettingsDefaultLocationAddress from "../apps/settings-manager/components/Settings_Default_Location_Address.vue";
import SettingsEmailNotificationToggle from "../apps/settings-manager/components/Settings_Email_Notification_Toggle.vue";
import SettingsExtensionPromotion from "../apps/settings-manager/components/Settings_Extension_Promotion.vue";
import SettingsNotificationEvents from "../apps/settings-manager/components/Settings_Notification_Events.vue";
import SettingsNotificationLogLink from "../apps/settings-manager/components/Settings_Notification_Log_Link.vue";
import SettingsPageSetupRow from "../apps/settings-manager/components/Settings_Page_Setup_Row.vue";
import SettingsRestrictedCountriesSelect from "../apps/settings-manager/components/Settings_Restricted_Countries_Select.vue";
import SettingsSeoMetaFields from "../apps/settings-manager/components/Settings_SEO_Meta_Fields.vue";
import SettingsWebPushNotificationToggle from "../apps/settings-manager/components/Settings_Web_Push_Notification_Toggle.vue";
import SettingsWebPushSetupCard from "../apps/settings-manager/components/Settings_Web_Push_Setup_Card.vue";

export default {
  name: "sections-module",
  components: {
    SettingsActiveGatewaysToggle,
    SettingsCheckboxArrayAccordion,
    SettingsCheckoutCurrencyMatch,
    SettingsColorTextField,
    SettingsDefaultLocationAddress,
    SettingsEmailNotificationToggle,
    SettingsExtensionPromotion,
    SettingsNotificationEvents,
    SettingsNotificationLogLink,
    SettingsPageSetupRow,
    SettingsRestrictedCountriesSelect,
    SettingsSeoMetaFields,
    SettingsWebPushNotificationToggle,
    SettingsWebPushSetupCard,
  },
  mixins: [helpers],

  props: {
    sections: {
      type: Object,
    },
    tabKey: {
      type: String,
      default: "",
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
      fields: (state) => state.fields,
      highlightedFieldKey: (state) => state.highlighted_field_key,
      highlightedSectionKey: (state) => state.highlighted_section_key,
    }),

    containerClass() {
      return {
        "tab-wide": this.container === "wide",
        "tab-short-wide": this.container === "short-wide",
        "tab-full-width": this.container === "full-width",
        [`cptm-tab-content-${this.tabKey}`]: !!this.tabKey,
        [`cptm-tab-content-menu-${this.menuKey}`]: !!this.menuKey,
      };
    },

    webPushNotificationsEnabled() {
      const adminField = this.fields.web_push_notify_admin || {};
      const userField = this.fields.web_push_notify_user || {};

      return (
        this.normalizeArray(adminField.value).length > 0 ||
        this.normalizeArray(userField.value).length > 0
      );
    },
  },

  data() {
    return {
      checkoutCurrencyCustomOpen: {},
      nestedAdvancedOpen: {},
    };
  },

  mounted() {
    this.openNestedAdvancedForHighlightedField();
  },

  watch: {
    highlightedFieldKey() {
      this.openNestedAdvancedForHighlightedField();
    },

    sections: {
      deep: true,
      handler() {
        this.openNestedAdvancedForHighlightedField();
      },
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

    sectionShouldRender(section, sectionKey = "") {
      if (!section || typeof section !== "object") {
        return false;
      }

      if (this.isRoutedEmailTemplateSection(sectionKey)) {
        return false;
      }

      const showIf = section.showIf || section.show_if || section["show-if"];

      if (!showIf) {
        return true;
      }

      return !!this.checkShowIfCondition({
        condition: showIf,
        root: this.fields,
      }).status;
    },

    isRoutedEmailTemplateSection(sectionKey) {
      const isNotificationTemplates =
        this.menuKey === "email_settings__email_events" ||
        this.tabKey === "email_events";

      return (
        isNotificationTemplates &&
        String(sectionKey || "").indexOf("routed_email_settings_email_templates_") === 0
      );
    },

    nestedAdvancedFields(section) {
      if (!section || !Array.isArray(section.advancedFields)) {
        return [];
      }

      return section.advancedFields;
    },

    nestedAdvancedToggleLabel(section) {
      return section && section.advancedLabel ? section.advancedLabel : "Advanced";
    },

    nestedAdvancedToggleClass(sectionKey) {
      return {
        "cptm-card-advanced--open": this.isNestedAdvancedOpen(sectionKey),
        "cptm-card-advanced--collapsed": !this.isNestedAdvancedOpen(sectionKey),
      };
    },

    nestedAdvancedKey(sectionKey) {
      return `${this.menuKey}__${sectionKey}`;
    },

    isNestedAdvancedOpen(sectionKey) {
      return !!this.nestedAdvancedOpen[this.nestedAdvancedKey(sectionKey)];
    },

    toggleNestedAdvanced(sectionKey) {
      const key = this.nestedAdvancedKey(sectionKey);
      this.$set(this.nestedAdvancedOpen, key, !this.nestedAdvancedOpen[key]);
    },

    isNestedAdvancedField(section, field) {
      return this.nestedAdvancedFields(section).includes(field);
    },

    shouldRenderNestedAdvancedToggle(section, field) {
      return this.nestedAdvancedFields(section)[0] === field;
    },

    shouldRenderDefaultLocationAddress(section, field) {
      if (!section || !section.defaultLocationAddress) {
        return false;
      }

      return section.defaultLocationAddress.beforeField === field;
    },

    shouldRenderNotificationEvents(section, field) {
      if (!section || !section.notificationEvents) {
        return false;
      }

      return section.notificationEvents.beforeField === field;
    },

    shouldRenderSeoMetaFields(section, field) {
      if (!section || !Array.isArray(section.seoMetaPairs)) {
        return false;
      }

      return section.seoMetaPairs[0]?.titleField === field;
    },

    shouldRenderCheckoutCurrencyMatch(section, field) {
      if (!section || !section.checkoutCurrencyMatch) {
        return false;
      }

      return section.checkoutCurrencyMatch.beforeField === field;
    },

    checkoutCurrencyCustomModeKey(sectionKey) {
      return `${this.menuKey}__${sectionKey}`;
    },

    isCheckoutCurrencyCustomMode(sectionKey) {
      const key = this.checkoutCurrencyCustomModeKey(sectionKey);

      return !!this.checkoutCurrencyCustomOpen[key];
    },

    setCheckoutCurrencyCustomMode(sectionKey, isOpen) {
      this.$set(
        this.checkoutCurrencyCustomOpen,
        this.checkoutCurrencyCustomModeKey(sectionKey),
        !!isOpen
      );
    },

    checkoutCurrencyMatchesDisplay() {
      const displayCurrency = String(this.fields.g_currency?.value || "")
        .trim()
        .toUpperCase();
      const checkoutCurrency = String(this.fields.payment_currency?.value || "")
        .trim()
        .toUpperCase();
      const displayPosition = String(this.fields.g_currency_position?.value || "");
      const checkoutPosition = String(
        this.fields.payment_currency_position?.value || ""
      );

      return (
        displayCurrency === checkoutCurrency &&
        displayPosition === checkoutPosition
      );
    },

    isCheckoutCurrencyField(field) {
      return ["payment_currency", "payment_currency_position"].includes(field);
    },

    shouldRenderRestrictedCountriesSelect(field) {
      const isMapSettings =
        this.menuKey === "listing_settings__map" || this.tabKey === "map";

      return (
        isMapSettings &&
        field === "restricted_countries"
      );
    },

    shouldRenderEmailNotificationToggle(field) {
      const isNotificationChannels =
        this.menuKey === "email_settings__email_general" ||
        this.tabKey === "email_general";

      return isNotificationChannels && field === "disable_email_notification";
    },

    shouldRenderWebPushNotificationToggle(field) {
      const isNotificationChannels =
        this.menuKey === "email_settings__email_general" ||
        this.tabKey === "email_general";

      return isNotificationChannels && field === "web_push_notify_admin";
    },

    shouldRenderWebPushSetupCard(field) {
      const isNotificationChannels =
        this.menuKey === "email_settings__email_general" ||
        this.tabKey === "email_general";

      return isNotificationChannels && field === "web_push_admin_subscription";
    },

    shouldRenderNotificationLogLink(field) {
      const isNotificationChannels =
        this.menuKey === "email_settings__email_general" ||
        this.tabKey === "email_general";

      return isNotificationChannels && field === "web_push_log_note";
    },

    normalizeArray(value) {
      if (Array.isArray(value)) {
        return value.map((item) => String(item));
      }

      if (value === null || typeof value === "undefined" || value === "") {
        return [];
      }

      return [String(value)];
    },

    shouldRenderExtensionPromotion(field) {
      const isExtensionsSettings =
        this.menuKey.indexOf("extension_settings") === 0 ||
        this.menuKey.indexOf("extensions_settings") === 0 ||
        this.tabKey.indexOf("extension_settings") === 0 ||
        this.tabKey.indexOf("extensions_settings") === 0 ||
        this.tabKey === "extensions_general";

      return isExtensionsSettings && field === "extension_promotion";
    },

    shouldRenderActiveGatewaysToggle(field) {
      const isPaymentGatewaySettings =
        this.menuKey === "monetization_settings__gateway" ||
        this.tabKey === "gateway";

      return isPaymentGatewaySettings && field === "active_gateways";
    },

    shouldRenderPageSetupRow(field) {
      const isPagesSettings =
        this.menuKey === "page_setup__pages" || this.tabKey === "pages";

      return isPagesSettings && this.fields[field]?.type === "select";
    },

    shouldRenderPreviewBackgroundColor(field) {
      const isListingsPageSettings =
        this.menuKey === "listing_settings__listings_page" ||
        this.tabKey === "listings_page";

      return isListingsPageSettings && field === "prv_background_color";
    },

    shouldRenderCheckboxArrayAccordion(field) {
      const staticAccordionFields = [
        "listings_view_as_items",
        "listings_sort_by_items",
        "search_view_as_items",
        "search_sort_by_items",
        "search_filters",
        "all_authors_contact",
        "booking_type",
      ];

      if (staticAccordionFields.includes(field)) {
        return true;
      }

      return (
        this.isExtensionSettingsContext() &&
        this.isMultiOptionCheckboxField(field)
      );
    },

    isExtensionSettingsContext() {
      const menuKey = String(this.menuKey || "");
      const tabKey = String(this.tabKey || "");

      return (
        menuKey.indexOf("extension_settings") === 0 ||
        menuKey.indexOf("extensions_settings") === 0 ||
        tabKey.indexOf("extension_settings") === 0 ||
        tabKey.indexOf("extensions_settings") === 0 ||
        tabKey === "extensions_general"
      );
    },

    isMultiOptionCheckboxField(field) {
      const fieldConfig = this.fields[field] || {};

      return (
        fieldConfig.type === "checkbox" &&
        Array.isArray(fieldConfig.options) &&
        fieldConfig.options.length > 1
      );
    },

    hiddenFieldsInSection(section) {
      if (!section || !Array.isArray(section.hiddenFields)) {
        return [];
      }

      return section.hiddenFields;
    },

    fieldShouldRenderInSection(section, field) {
      if (this.hiddenFieldsInSection(section).includes(field)) {
        return false;
      }

      return this.fieldShouldRender(field);
    },

    fieldIsVisibleInSection(sectionKey, section, field) {
      if (
        section?.checkoutCurrencyMatch &&
        this.isCheckoutCurrencyField(field)
      ) {
        return (
          this.isCheckoutCurrencyCustomMode(sectionKey) ||
          !this.checkoutCurrencyMatchesDisplay()
        );
      }

      if (!this.isNestedAdvancedField(section, field)) {
        return true;
      }

      return this.isNestedAdvancedOpen(sectionKey);
    },

    openNestedAdvancedForHighlightedField() {
      if (!this.highlightedFieldKey || !this.sections) {
        return;
      }

      Object.keys(this.sections).forEach((sectionKey) => {
        const section = this.sections[sectionKey];

        if (this.isNestedAdvancedField(section, this.highlightedFieldKey)) {
          this.$set(this.nestedAdvancedOpen, this.nestedAdvancedKey(sectionKey), true);
        }

      });
    },

    sectionGroupedContainerFields(section) {
      if (!section || !Array.isArray(section.fields)) {
        return [];
      }

      return section.fields.filter((field) => {
        return this.fields[field]?.group === "container";
      });
    },

    containerGroupLabel(section) {
      const firstContainerField = this.sectionGroupedContainerFields(section)[0];

      return firstContainerField
        ? this.fields[firstContainerField].group_label
        : "";
    },

    sectionHighlightKey(sectionKey) {
      return `${this.menuKey}__${sectionKey}`;
    },

    getSectionHighlightState(sectionKey) {
      return this.highlightedSectionKey === this.sectionHighlightKey(sectionKey);
    },

    sectionClass(section, sectionKey = "") {
      const firstField = section.fields[0];
      const isDisabled =
        firstField !== "disable_email_notification" &&
        this.fields[firstField]?.type === "toggle" &&
        this.fields[firstField].value !== true;

      return {
        "cptm-section--disabled": isDisabled,
        "highlight-section": this.getSectionHighlightState(sectionKey),
        [firstField]: !!firstField,
      };
    },

    sectionTitleAreaClass(section) {
      return {
        "directorist-no-header": !section.title && !section.description,
        "cptm-text-center": "center" === section.title_align ? true : false,
      };
    },

    fieldWrapperClass(field_key, field, section = null) {
      let type_class =
        field && field.type
          ? "cptm-field-wraper-type-" + field.type
          : "cptm-field-wraper";
      let key_class = "cptm-field-wraper-key-" + field_key;

      return {
        [type_class]: true,
        [key_class]: true,
        "highlight-field": this.getHighlightState(field_key),
        "cptm-field-wraper--checkbox-accordion":
          this.shouldRenderCheckboxArrayAccordion(field_key),
        "cptm-field-wraper--nested-advanced": this.isNestedAdvancedField(
          section,
          field_key
        ),
      };
    },

    fieldSuffix(field) {
      if (field === "featured_listing_price") {
        const paymentCurrency = this.fields.payment_currency?.value;
        const displayCurrency = this.fields.g_currency?.value;
        const currency = paymentCurrency || displayCurrency || "";

        return currency ? String(currency).toUpperCase() : "";
      }

      if (field === "featured_listing_time") {
        return "days";
      }

      if (field === "email_to_expire_day") {
        return "days before";
      }

      if (field === "email_renewal_day") {
        return "days after";
      }

      return "";
    },

    fieldWrapperID(field) {
      let type_id = "";
      if (field && field.editor !== undefined) {
        type_id = field.editor === "wp_editor" ? "cptm-field_wp_editor" : "";
      }
      return type_id;
    },

    fieldShouldRender(field) {
      if (!this.fields[field]) {
        return false;
      }

      if (this.fields[field].group === "container") {
        return false;
      }

      return this.fieldIsVisible(field);
    },

    fieldIsVisible(field) {
      const fieldData = this.fields[field];
      const showIf =
        fieldData.showIf || fieldData.show_if || fieldData["show-if"];

      if (!showIf) {
        return true;
      }

      return !!this.checkShowIfCondition({
        condition: showIf,
        root: this.fields,
      }).status;
    },

    // Skip specific sections that are rendered elsewhere (e.g. preview_mode in form builder)
    shouldSkipSection(section, section_key) {
      if (!section || !Array.isArray(section.fields)) {
        return false;
      }

      // Skip the form_options section whose first field is preview_mode,
      // because preview_mode is rendered inside the form-builder content instead.
      if (
        section_key === "form_options" &&
        section.fields[0] === "preview_mode"
      ) {
        return true;
      }

      return false;
    },
  },
};
</script>
