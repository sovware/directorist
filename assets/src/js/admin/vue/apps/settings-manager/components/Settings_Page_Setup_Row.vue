<template>
  <div class="cptm-page-setup-row" :class="{ 'cptm-page-setup-row--open': isOpen }">
    <div class="cptm-page-setup-row__content">
      <label class="cptm-page-setup-row__label" v-html="fieldLabel"></label>
      <p
        v-if="fieldDescription"
        class="cptm-page-setup-row__description"
        v-html="fieldDescription"
      ></p>
    </div>

    <div class="cptm-page-setup-row__control">
      <div class="directorist_dropdown" :class="{ '--open': isOpen }">
        <a
          href="#"
          class="directorist_dropdown-toggle"
          @click.prevent.stop="toggleDropdown"
        >
          <span
            class="directorist_dropdown-toggle__text"
            :title="currentOptionText"
          >
            {{ currentOptionText }}
          </span>
        </a>

        <div class="directorist_dropdown-option" :class="{ '--show': isOpen }">
          <ul>
            <li v-for="option in parsedOptions" :key="option.value">
              <a
                href="#"
                :class="{ active: option.value === normalizedValue }"
                :title="plainText(option.label)"
                v-html="option.label"
                @click.prevent.stop="selectOption(option.value)"
              ></a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "settings-page-setup-row",

  props: {
    field: {
      type: Object,
      required: true,
    },
    fieldKey: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      isOpen: false,
    };
  },

  computed: {
    fieldLabel() {
      return this.field.label || "";
    },

    normalizedValue() {
      return typeof this.field.value === "undefined" || this.field.value === null
        ? ""
        : String(this.field.value);
    },

    parsedOptions() {
      if (!Array.isArray(this.field.options)) {
        return [];
      }

      return this.field.options.map((option) => ({
        ...option,
        value:
          typeof option.value === "undefined" || option.value === null
            ? ""
            : String(option.value),
        label: option.label || "",
      }));
    },

    currentOptionText() {
      const currentOption = this.parsedOptions.find(
        (option) => option.value === this.normalizedValue
      );

      return currentOption ? this.plainText(currentOption.label) : "Select page";
    },

    fieldDescription() {
      const pageDescriptions = {
        add_listing_page: "Where users submit a new listing.",
        all_listing_page: "Shows the main directory archive.",
        user_dashboard: "Where users manage their listings and account.",
        signin_signup_page: "Used for login, registration, and account access.",
        author_profile_page: "Shows public author profile pages.",
        all_categories_page: "Lists all directory categories.",
        single_category_page: "Shows listings from one category.",
        all_locations_page: "Lists all directory locations.",
        single_location_page: "Shows listings from one location.",
        single_tag_page: "Shows listings from one tag.",
        search_listing: "Displays the directory search form.",
        search_result_page: "Shows results after a directory search.",
        checkout_page: "Used when a user pays for a listing.",
        payment_receipt_page: "Shown after a successful payment.",
        transaction_failure_page: "Shown when a payment fails.",
        privacy_policy: "Linked from directory registration and submission flows.",
        terms_conditions: "Linked from directory registration and submission flows.",
      };

      if (pageDescriptions[this.fieldKey]) {
        return pageDescriptions[this.fieldKey];
      }

      return this.field.description || "";
    },

  },

  mounted() {
    document.addEventListener("click", this.closeDropdown);
  },

  beforeDestroy() {
    document.removeEventListener("click", this.closeDropdown);
  },

  methods: {
    plainText(value) {
      if (typeof value === "undefined" || value === null) {
        return "";
      }

      const element = document.createElement("div");
      element.innerHTML = String(value);

      return (element.textContent || element.innerText || "").trim();
    },

    toggleDropdown() {
      this.isOpen = !this.isOpen;
    },

    closeDropdown() {
      this.isOpen = false;
    },

    selectOption(value) {
      this.$emit("update-field", {
        fieldKey: this.fieldKey,
        value,
      });
      this.closeDropdown();
    },

  },
};
</script>
