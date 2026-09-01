<template>
  <div class="cptm-notification-events">
    <table class="cptm-notification-events__table">
      <thead>
        <tr>
          <th class="cptm-notification-events__event-column">Event</th>
          <th class="cptm-notification-events__channel-column">Email</th>
          <th
            v-if="hasWebPushChannel"
            class="cptm-notification-events__channel-column"
          >
            Web Push
          </th>
          <th class="cptm-notification-events__action-column">Action</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="group in eventGroups">
          <tr
            :key="group.key"
            class="cptm-notification-events__group-row"
          >
            <td :colspan="tableColumnCount">{{ group.label }}</td>
          </tr>

          <tr
            v-for="event in group.events"
            :key="group.key + '-' + event.key"
            class="cptm-notification-events__row"
            :class="eventRowClass(event)"
          >
            <td>
              <div class="cptm-notification-events__event">
                <span class="cptm-notification-events__name">{{ event.label }}</span>
                <span class="cptm-notification-events__description">
                  {{ event.description }}
                </span>
              </div>
            </td>
            <td class="cptm-notification-events__channel-cell">
              <span
                v-if="event.templateOnly"
                class="cptm-notification-events__template-only"
              >
                Template
              </span>
              <span
                v-else-if="event.alwaysOn"
                class="cptm-notification-events__always-on"
                title="Always on for account emails"
              >
                <button
                  type="button"
                  class="cptm-input-toggle active"
                  aria-pressed="true"
                  disabled
                ></button>
              </span>
              <button
                v-else-if="event.emailFieldKey"
                type="button"
                class="cptm-input-toggle"
                :class="{ active: eventIsEnabled(event, 'email') }"
                :aria-pressed="eventIsEnabled(event, 'email') ? 'true' : 'false'"
                @click="toggleEmailEvent(event)"
              ></button>
              <span v-else class="cptm-notification-events__unavailable">-</span>
            </td>
            <td
              v-if="hasWebPushChannel"
              class="cptm-notification-events__channel-cell"
            >
              <button
                v-if="event.webPushFieldKey"
                type="button"
                class="cptm-input-toggle"
                :class="{ active: webPushSwitchIsActive(event) }"
                :aria-pressed="webPushSwitchIsActive(event) ? 'true' : 'false'"
                :disabled="webPushSwitchIsDisabled(event)"
                @click="toggleWebPushEvent(event)"
              ></button>
              <span v-else class="cptm-notification-events__unavailable">-</span>
            </td>
            <td class="cptm-notification-events__action-cell">
              <button
                v-if="eventHasEditableTemplate(event)"
                type="button"
                class="cptm-notification-events__edit"
                @click="openTemplateModal(event)"
              >
                Edit
              </button>
              <button
                v-else
                type="button"
                class="cptm-notification-events__edit cptm-notification-events__edit--disabled"
                title="No editable template is registered for this event"
                disabled
              >
                Edit
              </button>
            </td>
          </tr>
        </template>
      </tbody>
    </table>

    <div
      v-if="modalEvent"
      class="cptm-notification-modal"
      role="dialog"
      aria-modal="true"
      :aria-labelledby="'cptm-notification-modal-title'"
      @keydown.esc="closeTemplateModal"
    >
      <div class="cptm-notification-modal__backdrop" @click="closeTemplateModal"></div>
      <div class="cptm-notification-modal__panel">
        <div class="cptm-notification-modal__header">
          <div>
            <h3 id="cptm-notification-modal-title">Edit &middot; {{ modalEvent.label }}</h3>
            <span class="cptm-notification-modal__badge">{{ modalEvent.badge || modalEvent.value }}</span>
          </div>

          <button
            type="button"
            class="cptm-notification-modal__close"
            aria-label="Close"
            @click="closeTemplateModal"
          >
            &times;
          </button>
        </div>

        <div class="cptm-notification-modal__body">
          <template v-if="modalEvent.template">
            <div class="cptm-notification-modal__section-title">
              <span>Email template</span>
            </div>

            <div class="cptm-notification-modal__placeholders">
              <p>Click a placeholder to insert it into the active field.</p>
              <div class="cptm-notification-modal__placeholder-list">
                <button
                  v-for="placeholder in modalPlaceholders"
                  :key="placeholder"
                  type="button"
                  @click="insertPlaceholder(placeholder)"
                >
                  {{ placeholder }}
                </button>
              </div>
            </div>

            <label class="cptm-notification-modal__field">
              <span>Subject</span>
              <input
                ref="subjectInput"
                type="text"
                v-model="modalDraft.subject"
                @focus="activeDraftField = 'subject'"
              />
            </label>

            <label class="cptm-notification-modal__field">
              <span>Body</span>
              <small>HTML is allowed.</small>
              <textarea
                ref="bodyInput"
                v-model="modalDraft.body"
                @focus="activeDraftField = 'body'"
              ></textarea>
            </label>
          </template>

          <template v-if="modalEvent.webPushTemplate">
            <div
              class="cptm-notification-modal__section-title"
              :class="{
                'cptm-notification-modal__section-title--disabled':
                  webPushTemplateDisabled,
              }"
            >
              <span>Web push template</span>
            </div>

            <p
              v-if="webPushTemplateDisabled"
              class="cptm-notification-modal__disabled-note"
            >
              Enable web push notifications to edit this template.
            </p>

            <div class="cptm-notification-modal__grid">
              <label
                class="cptm-notification-modal__field"
                :class="{
                  'cptm-notification-modal__field--disabled':
                    webPushTemplateDisabled,
                }"
              >
                <span>
                  Title
                  <small>{{ webPushTitleLength }} / 60</small>
                </span>
                <input
                  ref="webPushTitleInput"
                  type="text"
                  v-model="modalDraft.webPushTitle"
                  :disabled="webPushTemplateDisabled"
                  @focus="activeDraftField = 'webPushTitle'"
                />
              </label>

              <label
                class="cptm-notification-modal__field"
                :class="{
                  'cptm-notification-modal__field--disabled':
                    webPushTemplateDisabled,
                }"
              >
                <span>
                  Message
                  <small>{{ webPushMessageLength }} / 100</small>
                </span>
                <input
                  ref="webPushMessageInput"
                  type="text"
                  v-model="modalDraft.webPushMessage"
                  :disabled="webPushTemplateDisabled"
                  @focus="activeDraftField = 'webPushMessage'"
                />
              </label>
            </div>

            <p class="cptm-notification-modal__hint">
              Placeholders supported: ==SITE_NAME==, ==LISTING_TITLE==, ==NAME==.
            </p>
          </template>
        </div>

        <div class="cptm-notification-modal__footer">
          <span>Send a test to yourself before going live.</span>
          <div>
            <button
              type="button"
              class="cptm-notification-modal__cancel"
              @click="closeTemplateModal"
            >
              Cancel
            </button>
            <button
              type="button"
              class="cptm-notification-modal__save"
              @click="saveTemplateModal"
            >
              Save
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const EVENT_TEMPLATE_MAP = {
  listing_submitted: {
    subject: "email_sub_new_listing",
    body: "email_tmpl_new_listing",
  },
  listing_published: {
    subject: "email_sub_pub_listing",
    body: "email_tmpl_pub_listing",
  },
  listing_rejected: {
    subject: "email_sub_rejected_listing",
    body: "email_tmpl_rejected_listing",
  },
  listing_edited: {
    subject: "email_sub_edit_listing",
    body: "email_tmpl_edit_listing",
  },
  listing_to_expire: {
    subject: "email_sub_to_expire_listing",
    body: "email_tmpl_to_expire_listing",
  },
  listing_expired: {
    subject: "email_sub_expired_listing",
    body: "email_tmpl_expired_listing",
  },
  remind_to_renew: {
    subject: "email_sub_to_renewal_listing",
    body: "email_tmpl_to_renewal_listing",
  },
  listing_renewed: {
    subject: "email_sub_renewed_listing",
    body: "email_tmpl_renewed_listing",
  },
  listing_deleted: {
    subject: "email_sub_deleted_listing",
    body: "email_tmpl_deleted_listing",
  },
  order_created: {
    subject: "email_sub_new_order",
    body: "email_tmpl_new_order",
  },
  order_completed: {
    subject: "email_sub_completed_order",
    body: "email_tmpl_completed_order",
  },
  listing_contact_form: {
    subject: "email_sub_listing_contact_email",
    body: "email_tmpl_listing_contact_email",
  },
};

const ADMIN_EVENTS = [
  ["order_created", "Order created", "A new order has been placed"],
  ["order_completed", "Order completed", "An order has been fulfilled"],
  ["listing_submitted", "New listing submitted", "A listing is waiting for review"],
  ["listing_published", "Listing approved or published", "A listing has gone live"],
  ["listing_edited", "Listing edited", "A listing was updated by its owner"],
  ["listing_deleted", "Listing deleted", "A listing has been removed"],
  ["listing_renewed", "Listing renewed", "A listing plan has been renewed"],
  ["listing_contact_form", "Listing contact form", "A visitor messaged via a listing"],
  ["listing_review", "Listing review", "A new review has been posted"],
];

const USER_EVENTS = [
  ["listing_submitted", "Listing submitted", "Confirmation their listing was received"],
  ["listing_published", "Listing approved or published", "Their listing is now live"],
  ["listing_rejected", "Listing rejected", "Their listing was not approved"],
  ["listing_edited", "Listing edited", "Confirmation their edit was saved"],
  ["listing_deleted", "Listing deleted", "Their listing has been removed"],
  ["listing_to_expire", "Listing nearly expired", "Their listing expires soon"],
  ["listing_expired", "Listing expired", "Their listing plan has ended"],
  ["remind_to_renew", "Remind to renew", "Renewal reminder after expiry"],
  ["listing_renewed", "Listing renewed", "Confirmation their listing was renewed"],
  ["listing_contact_form", "Listing contact form", "A visitor messaged via their listing"],
  ["listing_review", "Listing review", "Someone reviewed their listing"],
];

const ORDER_EMAIL_EVENTS = ["order_created", "order_completed"];

const ACCOUNT_EVENTS = [
  [
    "registration_confirmation",
    "Registration confirmation",
    "Welcome email sent after a new account is created",
    {
      subject: "email_sub_registration_confirmation",
      body: "email_tmpl_registration_confirmation",
    },
  ],
  [
    "email_verification",
    "Email verification",
    "Verification link sent when email verification is required",
    {
      subject: "email_sub_email_verification",
      body: "email_tmpl_email_verification",
    },
  ],
];

export default {
  name: "settings-notification-events",

  props: {
    fields: {
      type: Object,
      default: () => ({}),
    },
    sections: {
      type: Object,
      default: () => ({}),
    },
    highlightedFieldKey: {
      type: String,
      default: "",
    },
  },

  data() {
    return {
      modalEvent: null,
      modalDraft: {
        subject: "",
        body: "",
        webPushTitle: "",
        webPushMessage: "",
      },
      activeDraftField: "body",
      placeholders: [
        "==NAME==",
        "==USERNAME==",
        "==SITE_NAME==",
        "==SITE_LINK==",
        "==LISTING_TITLE==",
        "==LISTING_LINK==",
        "==LISTING_URL==",
        "==LISTING_ID==",
        "==EXPIRATION_DATE==",
        "==RENEWAL_LINK==",
        "==ORDER_ID==",
        "==ORDER_DETAILS==",
        "==DASHBOARD_LINK==",
        "==TODAY==",
        "==REJECTION_REASON==",
      ],
    };
  },

  computed: {
    adminField() {
      return this.fields.notify_admin || {};
    },

    userField() {
      return this.fields.notify_user || {};
    },

    webPushAdminField() {
      return this.fields.web_push_notify_admin || {};
    },

    webPushUserField() {
      return this.fields.web_push_notify_user || {};
    },

    adminValue() {
      return this.normalizeArray(this.adminField.value);
    },

    userValue() {
      return this.normalizeArray(this.userField.value);
    },

    webPushAdminValue() {
      return this.normalizeArray(this.webPushAdminField.value);
    },

    webPushUserValue() {
      return this.normalizeArray(this.webPushUserField.value);
    },

    hasWebPushChannel() {
      return !!(
        this.fields.web_push_notify_admin ||
        this.fields.web_push_notify_user
      );
    },

    webPushChannelEnabled() {
      return this.webPushAdminValue.length > 0 || this.webPushUserValue.length > 0;
    },

    tableColumnCount() {
      return this.hasWebPushChannel ? 4 : 3;
    },

    eventGroups() {
      const groups = [
        {
          key: "admin",
          label: "Admin notifications",
          events: this.buildChannelEvents(
            ADMIN_EVENTS,
            "notify_admin",
            "web_push_notify_admin",
            "admin"
          ),
        },
        {
          key: "owner",
          label: "Listing owner notifications",
          events: this.buildChannelEvents(
            USER_EVENTS,
            "notify_user",
            "web_push_notify_user",
            "owner"
          ),
        },
        {
          key: "account",
          label: "Account emails",
          events: this.buildAccountEvents(),
        },
        ...this.buildExtensionTemplateGroups(),
      ];

      return groups
        .map((group) => ({
          ...group,
          events: group.events.filter((event) => event),
        }))
        .filter((group) => group.events.length);
    },

    modalPlaceholders() {
      const placeholders = [...this.placeholders];

      Object.keys(this.modalDraft).forEach((fieldKey) => {
        if (this.modalDraft[fieldKey]) {
          placeholders.push(...this.extractPlaceholders(this.modalDraft[fieldKey]));
        }
      });

      return [...new Set(placeholders)];
    },

    webPushTitleLength() {
      return String(this.modalDraft.webPushTitle || "").length;
    },

    webPushMessageLength() {
      return String(this.modalDraft.webPushMessage || "").length;
    },

    webPushTemplateDisabled() {
      return !!(
        this.modalEvent &&
        this.modalEvent.webPushTemplate &&
        !this.webPushChannelEnabled
      );
    },
  },

  beforeDestroy() {
    this.clearModalBodyClass();
  },

  methods: {
    setModalBodyClass() {
      if (typeof document === "undefined" || !document.body) {
        return;
      }

      document.body.classList.add("cptm-notification-modal-open");
    },

    clearModalBodyClass() {
      if (typeof document === "undefined" || !document.body) {
        return;
      }

      document.body.classList.remove("cptm-notification-modal-open");
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

    normalizeOptions(options) {
      if (!Array.isArray(options)) {
        return [];
      }

      return options
        .filter((option) => option && typeof option.value !== "undefined")
        .map((option) => ({
          value: String(option.value),
          label: option.label ? String(option.label) : String(option.value),
        }));
    },

    optionValues(fieldKey) {
      const field =
        fieldKey === "notify_admin"
          ? this.adminField
          : fieldKey === "notify_user"
            ? this.userField
            : fieldKey === "web_push_notify_admin"
              ? this.webPushAdminField
              : this.webPushUserField;

      return this.normalizeOptions(field.options).map((option) => option.value);
    },

    valueForField(fieldKey) {
      if (fieldKey === "notify_admin") {
        return this.adminValue;
      }

      if (fieldKey === "notify_user") {
        return this.userValue;
      }

      if (fieldKey === "web_push_notify_admin") {
        return this.webPushAdminValue;
      }

      if (fieldKey === "web_push_notify_user") {
        return this.webPushUserValue;
      }

      return [];
    },

    fieldByKey(fieldKey) {
      if (fieldKey === "notify_admin") {
        return this.adminField;
      }

      if (fieldKey === "notify_user") {
        return this.userField;
      }

      if (fieldKey === "web_push_notify_admin") {
        return this.webPushAdminField;
      }

      if (fieldKey === "web_push_notify_user") {
        return this.webPushUserField;
      }

      return this.fields[fieldKey] || {};
    },

    disabledDisplayValueForField(fieldKey) {
      return this.normalizeArray(this.fieldByKey(fieldKey).disabledDisplayValue);
    },

    backupFieldKeyForField(fieldKey) {
      if (fieldKey === "web_push_notify_admin") {
        return "directorist_web_push_notify_admin_backup";
      }

      if (fieldKey === "web_push_notify_user") {
        return "directorist_web_push_notify_user_backup";
      }

      return "";
    },

    backupValueForField(fieldKey) {
      const backupFieldKey = this.backupFieldKeyForField(fieldKey);

      if (!backupFieldKey || !this.fields[backupFieldKey]) {
        return [];
      }

      return this.normalizeArray(this.fields[backupFieldKey].value);
    },

    displayValueForField(fieldKey) {
      const value = this.valueForField(fieldKey);

      if (
        this.webPushChannelEnabled ||
        !["web_push_notify_admin", "web_push_notify_user"].includes(fieldKey) ||
        value.length
      ) {
        return value;
      }

      const disabledDisplayValue = this.disabledDisplayValueForField(fieldKey);

      return disabledDisplayValue.length
        ? disabledDisplayValue
        : this.backupValueForField(fieldKey);
    },

    templateForEvent(eventKey) {
      const template = EVENT_TEMPLATE_MAP[eventKey];

      if (!template || !this.templateExists(template)) {
        return null;
      }

      return template;
    },

    webPushTemplateForEvent(eventKey, recipient) {
      const template = {
        title: `web_push_${recipient}_${eventKey}_title`,
        message: `web_push_${recipient}_${eventKey}_message`,
      };

      if (!this.webPushTemplateExists(template)) {
        return null;
      }

      return template;
    },

    templateExists(template) {
      return !!(
        template &&
        this.fields[template.subject] &&
        this.fields[template.body]
      );
    },

    webPushTemplateExists(template) {
      return !!(
        template &&
        this.fields[template.title] &&
        this.fields[template.message]
      );
    },

    eventHasEditableTemplate(event) {
      return !!(event && (event.template || event.webPushTemplate));
    },

    buildExtensionTemplateGroups() {
      const events = Object.keys(this.sections || {})
        .filter((sectionKey) => this.isExtensionTemplateSection(sectionKey))
        .map((sectionKey) =>
          this.buildExtensionTemplateEvent(sectionKey, this.sections[sectionKey])
        )
        .filter((event) => event);

      if (!events.length) {
        return [];
      }

      return [
        {
          key: "extension-templates",
          label: this.extensionTemplateGroupLabel(events),
          events,
        },
      ];
    },

    isExtensionTemplateSection(sectionKey) {
      return (
        String(sectionKey || "").indexOf("routed_email_settings_email_templates_") ===
        0
      );
    },

    buildExtensionTemplateEvent(sectionKey, section) {
      if (!section || !Array.isArray(section.fields)) {
        return null;
      }

      const template = this.getTemplatePairFromFields(section.fields);

      if (!this.templateExists(template)) {
        return null;
      }

      return {
        key: `extension-${sectionKey}`,
        value: sectionKey,
        badge: this.extensionTemplateBadge(sectionKey),
        emailFieldKey: "",
        webPushFieldKey: "",
        label: this.cleanExtensionTemplateLabel(section.title || sectionKey),
        description: section.description || "",
        template,
        webPushTemplate: null,
        alwaysOn: false,
        templateOnly: true,
      };
    },

    getTemplatePairFromFields(fieldKeys) {
      const subject = fieldKeys.find((fieldKey) => {
        const field = this.fields[fieldKey] || {};
        const label = String(field.label || "");

        return (
          field.type === "text" &&
          /subject/i.test(`${fieldKey} ${label}`)
        );
      });
      const body = fieldKeys.find((fieldKey) => {
        const field = this.fields[fieldKey] || {};
        const label = String(field.label || "");

        return (
          field.type === "textarea" &&
          /(body|template|tmpl)/i.test(`${fieldKey} ${label}`)
        );
      });

      if (!subject || !body) {
        return null;
      }

      return { subject, body };
    },

    extensionTemplateGroupLabel(events) {
      const isBooking = events.some((event) => {
        return (
          /booking/i.test(event.label) ||
          /^bdb_/i.test(event.template.subject) ||
          /^bdb_/i.test(event.template.body)
        );
      });

      return isBooking ? "Booking emails" : "Extension email templates";
    },

    extensionTemplateBadge(sectionKey) {
      return String(sectionKey || "").replace(
        /^routed_email_settings_email_templates_/,
        ""
      );
    },

    cleanExtensionTemplateLabel(label) {
      return String(label || "")
        .replace(/^For\s+/i, "")
        .replace(/\s+/g, " ")
        .replace(/\(\s+/g, "(")
        .replace(/\s+\)/g, ")")
        .trim();
    },

    buildChannelEvents(eventDefinitions, emailFieldKey, webPushFieldKey, recipient) {
      const emailValues = this.optionValues(emailFieldKey);
      const webPushValues = this.optionValues(webPushFieldKey);
      const ownerEmailValues = this.optionValues("notify_user");
      const availableValues = [...new Set([...emailValues, ...webPushValues])];

      return eventDefinitions
        .filter(([value]) => availableValues.includes(value))
        .map(([value, label, description]) => {
          const hasEmail = emailValues.includes(value);
          const hasWebPush = webPushValues.includes(value);

          return {
            key: `${emailFieldKey}-${value}`,
            value,
            emailFieldKey: hasEmail ? emailFieldKey : "",
            secondaryEmailFieldKey:
              emailFieldKey === "notify_admin" &&
              ORDER_EMAIL_EVENTS.includes(value) &&
              ownerEmailValues.includes(value)
                ? "notify_user"
                : "",
            webPushFieldKey: hasWebPush ? webPushFieldKey : "",
            label,
            description,
            template: this.templateForEvent(value),
            webPushTemplate: hasWebPush
              ? this.webPushTemplateForEvent(value, recipient)
              : null,
            alwaysOn: false,
            templateOnly: false,
          };
        });
    },

    buildAccountEvents() {
      return ACCOUNT_EVENTS.filter((event) => this.templateExists(event[3])).map(
        ([value, label, description, template]) => ({
          key: `account-${value}`,
          value,
          emailFieldKey: "",
          webPushFieldKey: "",
          label,
          description,
          template,
          webPushTemplate: null,
          alwaysOn: true,
          templateOnly: false,
        })
      );
    },

    eventRowClass(event) {
      const classes = {};

      this.eventFieldKeys(event).forEach((fieldKey) => {
        classes[`cptm-field-wraper-key-${fieldKey}`] = true;
      });

      if (this.eventIncludesHighlightedField(event)) {
        classes["highlight-field"] = true;
      }

      return classes;
    },

    eventFieldKeys(event) {
      const keys = [];

      if (!event) {
        return keys;
      }

      if (event.emailFieldKey) {
        keys.push(event.emailFieldKey);
      }

      if (event.secondaryEmailFieldKey) {
        keys.push(event.secondaryEmailFieldKey);
      }

      if (event.webPushFieldKey) {
        keys.push(event.webPushFieldKey);
      }

      if (event.template) {
        keys.push(event.template.subject, event.template.body);
      }

      if (event.webPushTemplate) {
        keys.push(event.webPushTemplate.title, event.webPushTemplate.message);
      }

      return keys.filter(Boolean);
    },

    eventIncludesHighlightedField(event) {
      if (!event || !this.highlightedFieldKey) {
        return false;
      }

      return this.eventFieldKeys(event).includes(this.highlightedFieldKey);
    },

    eventIsEnabled(event, channel) {
      if (!event) {
        return false;
      }

      if (channel === "webPush") {
        return event.webPushFieldKey
          ? this.valueForField(event.webPushFieldKey).includes(event.value)
          : false;
      }

      const fieldKeys = this.emailFieldKeysForEvent(event);

      if (!fieldKeys.length) {
        return false;
      }

      return fieldKeys.every((fieldKey) =>
        this.valueForField(fieldKey).includes(event.value)
      );
    },

    eventIsDisplayedEnabled(event, channel) {
      if (!event) {
        return false;
      }

      const fieldKey =
        channel === "webPush" ? event.webPushFieldKey : event.emailFieldKey;

      if (!fieldKey) {
        return false;
      }

      return this.displayValueForField(fieldKey).includes(event.value);
    },

    webPushSwitchIsActive(event) {
      return this.eventIsDisplayedEnabled(event, "webPush");
    },

    webPushSwitchIsDisabled(event) {
      return !!(event && event.webPushFieldKey && !this.webPushChannelEnabled);
    },

    toggleEvent(fieldKey, eventKey) {
      if (!fieldKey) {
        return;
      }

      let nextValue = [...this.valueForField(fieldKey)];

      if (nextValue.includes(eventKey)) {
        nextValue = nextValue.filter((item) => item !== eventKey);
      } else {
        nextValue.push(eventKey);
      }

      this.$emit("update-field", {
        fieldKey,
        value: nextValue,
      });
    },

    emailFieldKeysForEvent(event) {
      if (!event) {
        return [];
      }

      return [...new Set([event.emailFieldKey, event.secondaryEmailFieldKey])]
        .filter(Boolean);
    },

    toggleEmailEvent(event) {
      const fieldKeys = this.emailFieldKeysForEvent(event);

      if (!fieldKeys.length) {
        return;
      }

      const shouldEnable = !this.eventIsEnabled(event, "email");

      fieldKeys.forEach((fieldKey) => {
        let nextValue = [...this.valueForField(fieldKey)];

        if (shouldEnable) {
          if (!nextValue.includes(event.value)) {
            nextValue.push(event.value);
          }
        } else {
          nextValue = nextValue.filter((item) => item !== event.value);
        }

        this.$emit("update-field", {
          fieldKey,
          value: nextValue,
        });
      });
    },

    toggleWebPushEvent(event) {
      if (this.webPushSwitchIsDisabled(event)) {
        return;
      }

      this.toggleEvent(event.webPushFieldKey, event.value);
    },

    openTemplateModal(event) {
      if (!this.eventHasEditableTemplate(event)) {
        return;
      }

      this.modalEvent = event;
      this.modalDraft = {
        subject: event.template
          ? this.fields[event.template.subject]?.value || ""
          : "",
        body: event.template ? this.fields[event.template.body]?.value || "" : "",
        webPushTitle: event.webPushTemplate
          ? this.fields[event.webPushTemplate.title]?.value || ""
          : "",
        webPushMessage: event.webPushTemplate
          ? this.fields[event.webPushTemplate.message]?.value || ""
          : "",
      };
      this.activeDraftField = event.template ? "body" : "webPushMessage";
      this.setModalBodyClass();

      this.$nextTick(() => {
        const firstInput = event.template
          ? this.$refs.subjectInput
          : this.$refs.webPushTitleInput;

        if (firstInput && firstInput.focus) {
          firstInput.focus();
        }
      });
    },

    closeTemplateModal() {
      this.modalEvent = null;
      this.modalDraft = {
        subject: "",
        body: "",
        webPushTitle: "",
        webPushMessage: "",
      };
      this.activeDraftField = "body";
      this.clearModalBodyClass();
    },

    saveTemplateModal() {
      if (!this.modalEvent) {
        return;
      }

      if (this.modalEvent.template) {
        this.$emit("update-field", {
          fieldKey: this.modalEvent.template.subject,
          value: this.modalDraft.subject,
        });

        this.$emit("update-field", {
          fieldKey: this.modalEvent.template.body,
          value: this.modalDraft.body,
        });
      }

      if (this.modalEvent.webPushTemplate && !this.webPushTemplateDisabled) {
        this.$emit("update-field", {
          fieldKey: this.modalEvent.webPushTemplate.title,
          value: this.modalDraft.webPushTitle,
        });

        this.$emit("update-field", {
          fieldKey: this.modalEvent.webPushTemplate.message,
          value: this.modalDraft.webPushMessage,
        });
      }

      this.closeTemplateModal();
    },

    extractPlaceholders(value) {
      const matches = String(value || "").match(/==[A-Z0-9_]+==/g);

      return matches || [];
    },

    insertPlaceholder(placeholder) {
      const draftField = this.activeDraftField || "body";

      if (
        this.webPushTemplateDisabled &&
        ["webPushTitle", "webPushMessage"].includes(draftField)
      ) {
        return;
      }

      const refByField = {
        subject: "subjectInput",
        body: "bodyInput",
        webPushTitle: "webPushTitleInput",
        webPushMessage: "webPushMessageInput",
      };
      const refName = refByField[draftField] || "bodyInput";
      const input = this.$refs[refName];
      const currentValue = this.modalDraft[draftField] || "";

      if (!input || typeof input.selectionStart !== "number") {
        this.modalDraft[draftField] = `${currentValue}${placeholder}`;
        return;
      }

      const start = input.selectionStart;
      const end = input.selectionEnd;

      this.modalDraft[draftField] =
        currentValue.slice(0, start) + placeholder + currentValue.slice(end);

      this.$nextTick(() => {
        input.focus();
        const caret = start + placeholder.length;
        input.setSelectionRange(caret, caret);
      });
    },
  },
};
</script>
