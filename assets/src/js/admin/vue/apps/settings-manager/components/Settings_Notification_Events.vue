<template>
  <div class="cptm-notification-events">
    <table class="cptm-notification-events__table">
      <thead>
        <tr>
          <th>Event</th>
          <th>Email</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="group in eventGroups">
          <tr
            :key="group.key"
            class="cptm-notification-events__group-row"
          >
            <td colspan="3">{{ group.label }}</td>
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
            <td>
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
                v-else
                type="button"
                class="cptm-input-toggle"
                :class="{ active: eventIsEnabled(event) }"
                :aria-pressed="eventIsEnabled(event) ? 'true' : 'false'"
                @click="toggleEvent(event.fieldKey, event.value)"
              ></button>
            </td>
            <td>
              <button
                v-if="event.template"
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
          <div class="cptm-notification-modal__section-title">
            <span>EMAIL TEMPLATE</span>
          </div>

          <div class="cptm-notification-modal__placeholders">
            <p>Click a placeholder to insert it into the body.</p>
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
  ["payment_received", "Payment received", "A payment has been confirmed"],
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
  ["order_created", "Order created", "Confirmation their order was placed"],
  ["order_completed", "Order completed", "Their order has been fulfilled"],
  ["payment_received", "Payment received", "Confirmation of a successful payment"],
  ["listing_contact_form", "Listing contact form", "A visitor messaged via their listing"],
  ["listing_review", "Listing review", "Someone reviewed their listing"],
];

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

    adminValue() {
      return this.normalizeArray(this.adminField.value);
    },

    userValue() {
      return this.normalizeArray(this.userField.value);
    },

    adminOptions() {
      return this.normalizeOptions(this.adminField.options);
    },

    userOptions() {
      return this.normalizeOptions(this.userField.options);
    },

    eventGroups() {
      const groups = [
        {
          key: "admin",
          label: "Admin notifications",
          events: this.buildChannelEvents(ADMIN_EVENTS, "notify_admin"),
        },
        {
          key: "owner",
          label: "Listing owner notifications",
          events: this.buildChannelEvents(USER_EVENTS, "notify_user"),
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

      if (this.modalDraft.subject) {
        placeholders.push(...this.extractPlaceholders(this.modalDraft.subject));
      }

      if (this.modalDraft.body) {
        placeholders.push(...this.extractPlaceholders(this.modalDraft.body));
      }

      return [...new Set(placeholders)];
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

    templateForEvent(eventKey) {
      const template = EVENT_TEMPLATE_MAP[eventKey];

      if (!template || !this.templateExists(template)) {
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
        fieldKey: "",
        label: this.cleanExtensionTemplateLabel(section.title || sectionKey),
        description: section.description || "",
        template,
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

    buildChannelEvents(eventDefinitions, fieldKey) {
      const availableValues =
        fieldKey === "notify_admin"
          ? this.adminOptions.map((option) => option.value)
          : this.userOptions.map((option) => option.value);

      return eventDefinitions
        .filter(([value]) => availableValues.includes(value))
        .map(([value, label, description]) => ({
          key: `${fieldKey}-${value}`,
          value,
          fieldKey,
          label,
          description,
          template: this.templateForEvent(value),
          alwaysOn: false,
          templateOnly: false,
        }));
    },

    buildAccountEvents() {
      return ACCOUNT_EVENTS.filter((event) => this.templateExists(event[3])).map(
        ([value, label, description, template]) => ({
          key: `account-${value}`,
          value,
          fieldKey: "",
          label,
          description,
          template,
          alwaysOn: true,
          templateOnly: false,
        })
      );
    },

    eventRowClass(event) {
      const classes = {};

      if (event && event.template) {
        classes[`cptm-field-wraper-key-${event.template.subject}`] = true;
        classes[`cptm-field-wraper-key-${event.template.body}`] = true;
      }

      if (this.eventIncludesHighlightedField(event)) {
        classes["highlight-field"] = true;
      }

      return classes;
    },

    eventIncludesHighlightedField(event) {
      if (!event || !event.template || !this.highlightedFieldKey) {
        return false;
      }

      return [
        event.template.subject,
        event.template.body,
      ].includes(this.highlightedFieldKey);
    },

    eventIsEnabled(event) {
      if (!event || !event.fieldKey) {
        return false;
      }

      return event.fieldKey === "notify_admin"
        ? this.adminValue.includes(event.value)
        : this.userValue.includes(event.value);
    },

    toggleEvent(fieldKey, eventKey) {
      if (!fieldKey) {
        return;
      }

      const currentValue =
        fieldKey === "notify_admin" ? this.adminValue : this.userValue;
      let nextValue = [...currentValue];

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

    openTemplateModal(event) {
      if (!event || !event.template) {
        return;
      }

      this.modalEvent = event;
      this.modalDraft = {
        subject: this.fields[event.template.subject]?.value || "",
        body: this.fields[event.template.body]?.value || "",
      };
      this.activeDraftField = "body";
      this.setModalBodyClass();

      this.$nextTick(() => {
        if (this.$refs.subjectInput && this.$refs.subjectInput.focus) {
          this.$refs.subjectInput.focus();
        }
      });
    },

    closeTemplateModal() {
      this.modalEvent = null;
      this.modalDraft = {
        subject: "",
        body: "",
      };
      this.activeDraftField = "body";
      this.clearModalBodyClass();
    },

    saveTemplateModal() {
      if (!this.modalEvent || !this.modalEvent.template) {
        return;
      }

      this.$emit("update-field", {
        fieldKey: this.modalEvent.template.subject,
        value: this.modalDraft.subject,
      });

      this.$emit("update-field", {
        fieldKey: this.modalEvent.template.body,
        value: this.modalDraft.body,
      });

      this.closeTemplateModal();
    },

    extractPlaceholders(value) {
      const matches = String(value || "").match(/==[A-Z0-9_]+==/g);

      return matches || [];
    },

    insertPlaceholder(placeholder) {
      const fieldName = this.activeDraftField === "subject" ? "subject" : "body";
      const refName = fieldName === "subject" ? "subjectInput" : "bodyInput";
      const input = this.$refs[refName];
      const currentValue = this.modalDraft[fieldName] || "";

      if (!input || typeof input.selectionStart !== "number") {
        this.modalDraft[fieldName] = `${currentValue}${placeholder}`;
        return;
      }

      const start = input.selectionStart;
      const end = input.selectionEnd;

      this.modalDraft[fieldName] =
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
