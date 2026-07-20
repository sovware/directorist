<template>
  <div class="cptm-badges-manager">
    <div class="cptm-badges-card">
      <div class="cptm-badges-card__head">
        <h3>Badges</h3>
        <p>
          Create as many badges as you want. Each badge has its own conditions
          and style. A listing can match multiple badges at once.
        </p>
      </div>

      <div class="cptm-badges-toolbar">
        <div class="cptm-badges-toolbar__info">
          Tip: keep each badge's conditions simple. One badge answers one
          question.
        </div>
        <div class="cptm-badges-toolbar__actions">
          <button
            type="button"
            class="cptm-badges-link-button"
            @click.stop="openResetConfirm"
          >
            Reset defaults
          </button>
          <button
            type="button"
            class="cptm-badges-primary-button"
            @click.stop="addBadge"
          >
            + Add badge
          </button>
        </div>
      </div>

      <div class="cptm-badges-list">
        <div
          v-for="badge in badges"
          :key="badge.key"
          class="cptm-badge-item"
          :class="{
            'cptm-badge-item--open': isOpen(badge.key),
            'highlight-section': badgeHasHighlightedField(badge),
          }"
        >
          <button
            type="button"
            class="cptm-badge-item__head"
            :aria-expanded="isOpen(badge.key) ? 'true' : 'false'"
            @click.stop="toggleBadge(badge.key)"
          >
            <span class="cptm-badge-item__left">
              <span
                class="cptm-badge-chip"
                :class="{
                  'cptm-badge-chip--icon': badgeType(badge) === 'icon',
                  'cptm-badge-chip--has-icon': badgePreviewShowsIcon(badge),
                }"
                :style="chipStyle(badge)"
                :title="
                  badgeType(badge) === 'icon' ? badgeTooltipText(badge) : null
                "
              >
                <span
                  v-if="badgePreviewShowsIcon(badge)"
                  class="cptm-badge-chip__icon"
                  :class="badgeIcon(badge)"
                  aria-hidden="true"
                ></span>
                <span v-if="badgeType(badge) !== 'icon'">{{
                  chipLabel(badge)
                }}</span>
              </span>

              <span class="cptm-badge-titlebox">
                <strong>{{ badgeInternalName(badge) }}</strong>
                <span>{{ conditionSummary(badge) }}</span>
              </span>
            </span>

            <span class="cptm-badge-item__right">
              <span class="cptm-badge-pill">
                Match
                <strong>{{ matchModeLabel(badge) }}</strong>
              </span>
              <span class="cptm-badge-chevron" aria-hidden="true">
                <svg
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <polyline points="9 18 15 12 9 6" />
                </svg>
              </span>
            </span>
          </button>

          <div v-show="isOpen(badge.key)" class="cptm-badge-item__body">
            <div class="cptm-badge-field cptm-badge-field--full">
              <span class="cptm-badge-field__label">Badge type</span>
              <div
                class="cptm-badges-type-picker"
                role="radiogroup"
                :aria-label="`${badgeInternalName(badge)} badge type`"
              >
                <label
                  v-for="option in badgeTypeOptions"
                  :key="option.value"
                  class="cptm-badges-type-option"
                  :class="{
                    selected: badgeType(badge) === option.value,
                  }"
                >
                  <input
                    type="radio"
                    :name="`directorist-badge-type-${badge.key}`"
                    :value="option.value"
                    :checked="badgeType(badge) === option.value"
                    @change="updateBadgeType(badge, option.value)"
                  />
                  <span class="cptm-badges-type-option__title">{{
                    option.label
                  }}</span>
                  <span class="cptm-badges-type-option__description">{{
                    option.description
                  }}</span>
                </label>
              </div>
            </div>

            <div class="cptm-badge-edit-grid">
              <label class="cptm-badge-field">
                <span class="cptm-badge-field__label">Internal name</span>
                <input
                  type="text"
                  :value="badgeInternalName(badge)"
                  @input="updateInternalName(badge, $event.target.value)"
                />
                <span class="cptm-badge-field__help">For admin only.</span>
              </label>

              <label
                class="cptm-badge-field"
                :class="fieldHighlightClass(badge.labelField)"
              >
                <span class="cptm-badge-field__label">Visible label</span>
                <input
                  type="text"
                  :value="badgeVisibleLabel(badge)"
                  @input="updateVisibleLabel(badge, $event.target.value)"
                />
                <span class="cptm-badge-field__help">
                  {{
                    badgeType(badge) === "icon"
                      ? "Used as the tooltip when Hover tooltip is empty."
                      : "Visitors see this text on the badge."
                  }}
                </span>
              </label>

              <div class="cptm-badge-field cptm-badge-field--icon-picker">
                <span class="cptm-badge-field__label">Icon</span>
                <div
                  v-if="iconPickerAvailable"
                  :ref="`iconPicker-${badge.key}`"
                  class="cptm-badge-icon-picker"
                ></div>
                <input
                  v-else
                  type="text"
                  :value="badgeIcon(badge)"
                  spellcheck="false"
                  autocomplete="off"
                  @input="updateBadgeIcon(badge, $event.target.value)"
                />
              </div>

              <label
                class="cptm-badge-field"
                :class="fieldHighlightClass(badge.colorField)"
              >
                <span class="cptm-badge-field__label">Background color</span>
                <span class="cptm-badge-color-input">
                  <input
                    type="color"
                    :value="nativeColorValue(badgeStyleValue(badge, 'bg'))"
                    :aria-label="`${badgeInternalName(badge)} background color`"
                    @input="updateBadgeStyle(badge, 'bg', $event.target.value)"
                  />
                  <input
                    type="text"
                    :value="badgeStyleValue(badge, 'bg')"
                    spellcheck="false"
                    autocomplete="off"
                    @input="updateBadgeStyle(badge, 'bg', $event.target.value)"
                  />
                </span>
              </label>

              <label class="cptm-badge-field">
                <span class="cptm-badge-field__label">{{
                  badgeColorControlLabel(badge)
                }}</span>
                <span class="cptm-badge-color-input">
                  <input
                    type="color"
                    :value="nativeColorValue(badgeStyleValue(badge, 'text'))"
                    :aria-label="badgeColorControlAriaLabel(badge)"
                    @input="
                      updateBadgeStyle(badge, 'text', $event.target.value)
                    "
                  />
                  <input
                    type="text"
                    :value="badgeStyleValue(badge, 'text')"
                    spellcheck="false"
                    autocomplete="off"
                    @input="
                      updateBadgeStyle(badge, 'text', $event.target.value)
                    "
                  />
                </span>
              </label>

              <label class="cptm-badge-field">
                <span class="cptm-badge-field__label">Border color</span>
                <span class="cptm-badge-color-input">
                  <input
                    type="color"
                    :value="nativeColorValue(badgeStyleValue(badge, 'border'))"
                    :aria-label="`${badgeInternalName(badge)} border color`"
                    @input="
                      updateBadgeStyle(badge, 'border', $event.target.value)
                    "
                  />
                  <input
                    type="text"
                    :value="badgeStyleValue(badge, 'border')"
                    spellcheck="false"
                    autocomplete="off"
                    @input="
                      updateBadgeStyle(badge, 'border', $event.target.value)
                    "
                  />
                </span>
              </label>

              <label
                v-if="badgeType(badge) === 'icon'"
                class="cptm-badge-field"
              >
                <span class="cptm-badge-field__label">Hover tooltip</span>
                <input
                  type="text"
                  :value="badgeTooltipText(badge)"
                  :placeholder="badgeVisibleLabel(badge)"
                  @input="updateBadgeHover(badge, 'text', $event.target.value)"
                />
                <span class="cptm-badge-field__help">
                  Shown when a visitor hovers over the badge.
                </span>
              </label>

              <div
                v-if="badgeType(badge) === 'icon'"
                class="cptm-badge-field cptm-badge-field--tooltip-colors"
              >
                <span class="cptm-badge-field__label">Tooltip background</span>
                <span class="cptm-badge-tooltip-colors">
                  <span class="cptm-badge-tooltip-colors__item">
                    <span class="cptm-badge-color-input">
                      <input
                        type="color"
                        :value="
                          nativeColorValue(badgeTooltipColor(badge, 'bg'))
                        "
                        :aria-label="`${badgeInternalName(badge)} tooltip background color`"
                        @input="
                          updateBadgeHover(badge, 'bg', $event.target.value)
                        "
                      />
                      <input
                        type="text"
                        :value="badgeTooltipColor(badge, 'bg')"
                        spellcheck="false"
                        autocomplete="off"
                        @input="
                          updateBadgeHover(badge, 'bg', $event.target.value)
                        "
                      />
                    </span>
                  </span>
                  <span class="cptm-badge-tooltip-colors__item">
                    <span class="cptm-badge-color-input">
                      <input
                        type="color"
                        :value="
                          nativeColorValue(
                            badgeTooltipColor(badge, 'textColor'),
                          )
                        "
                        :aria-label="`${badgeInternalName(badge)} tooltip text color`"
                        @input="
                          updateBadgeHover(
                            badge,
                            'textColor',
                            $event.target.value,
                          )
                        "
                      />
                      <input
                        type="text"
                        :value="badgeTooltipColor(badge, 'textColor')"
                        spellcheck="false"
                        autocomplete="off"
                        @input="
                          updateBadgeHover(
                            badge,
                            'textColor',
                            $event.target.value,
                          )
                        "
                      />
                    </span>
                  </span>
                </span>
                <span class="cptm-badge-field__help">
                  Second swatch controls the tooltip text color.
                </span>
              </div>
            </div>

            <div class="cptm-badge-match">
              <div class="cptm-badge-match__head">
                <div class="cptm-badge-match__title">
                  <span>Match conditions</span>
                  <div
                    class="cptm-badge-match-segment"
                    role="group"
                    :aria-label="`${badgeInternalName(badge)} match mode`"
                  >
                    <button
                      type="button"
                      :class="{
                        active: badgeMatchMode(badge) === 'all',
                      }"
                      @click.stop="updateMatchMode(badge, 'all')"
                    >
                      All (AND)
                    </button>
                    <button
                      type="button"
                      :class="{
                        active: badgeMatchMode(badge) === 'any',
                      }"
                      @click.stop="updateMatchMode(badge, 'any')"
                    >
                      Any (OR)
                    </button>
                  </div>
                </div>
                <span class="cptm-badge-preview-hint">Live preview</span>
              </div>

              <div class="cptm-badge-condition-list">
                <div
                  v-for="(condition, conditionIndex) in conditionRows(badge)"
                  :key="condition.id"
                  class="cptm-badge-condition-row"
                  :class="conditionRowClass(condition)"
                >
                  <select
                    class="cptm-badge-condition-row__control"
                    :value="condition.source"
                    :aria-label="conditionAriaLabel(badge, 'condition source')"
                    @change="
                      updateConditionSource(
                        badge,
                        conditionIndex,
                        $event.target.value,
                      )
                    "
                  >
                    <option
                      v-for="option in conditionSourceOptions"
                      :key="option.value"
                      :value="option.value"
                      :disabled="option.disabled"
                    >
                      {{ option.label }}
                    </option>
                  </select>

                  <select
                    class="cptm-badge-condition-row__control"
                    :class="conditionHighlightClass(condition)"
                    :value="condition.key"
                    :aria-label="conditionAriaLabel(badge, 'condition key')"
                    @change="
                      updateConditionKey(
                        badge,
                        conditionIndex,
                        $event.target.value,
                      )
                    "
                  >
                    <option
                      v-for="option in condition.keyOptions"
                      :key="option.value"
                      :value="option.value"
                    >
                      {{ option.label }}
                    </option>
                  </select>

                  <select
                    class="cptm-badge-condition-row__control"
                    :value="condition.operator"
                    :aria-label="
                      conditionAriaLabel(badge, 'condition operator')
                    "
                    @change="
                      updateConditionOperator(
                        badge,
                        conditionIndex,
                        $event.target.value,
                      )
                    "
                  >
                    <option
                      v-for="operator in condition.operatorOptions"
                      :key="operator.value"
                      :value="operator.value"
                    >
                      {{ operator.label }}
                    </option>
                  </select>

                  <span
                    class="cptm-badge-condition-row__value"
                    :class="conditionHighlightClass(condition)"
                  >
                    <span
                      v-if="!operatorNeedsValue(condition.operator)"
                      class="cptm-badge-condition-row__empty-value"
                    >
                      no value
                    </span>
                    <input
                      v-else-if="condition.valueType === 'number'"
                      type="number"
                      :min="condition.min"
                      :max="condition.max"
                      :step="condition.step"
                      :value="condition.value"
                      :aria-label="conditionAriaLabel(badge, 'condition value')"
                      @input="
                        updateConditionValue(
                          badge,
                          conditionIndex,
                          $event.target.value,
                        )
                      "
                    />
                    <select
                      v-else-if="condition.valueType === 'boolean'"
                      :value="condition.value"
                      :aria-label="conditionAriaLabel(badge, 'condition value')"
                      @change="
                        updateConditionValue(
                          badge,
                          conditionIndex,
                          $event.target.value,
                        )
                      "
                    >
                      <option value="true">True</option>
                      <option value="false">False</option>
                    </select>
                    <select
                      v-else-if="condition.valueType === 'select'"
                      :value="condition.value"
                      :aria-label="conditionAriaLabel(badge, 'condition value')"
                      @change="
                        updateConditionValue(
                          badge,
                          conditionIndex,
                          $event.target.value,
                        )
                      "
                    >
                      <option
                        v-for="option in condition.valueOptions"
                        :key="option.value"
                        :value="option.value"
                      >
                        {{ option.label }}
                      </option>
                    </select>
                    <input
                      v-else
                      type="text"
                      :value="condition.value"
                      :aria-label="conditionAriaLabel(badge, 'condition value')"
                      @input="
                        updateConditionValue(
                          badge,
                          conditionIndex,
                          $event.target.value,
                        )
                      "
                    />
                  </span>

                  <button
                    type="button"
                    class="cptm-badge-condition-row__remove"
                    :disabled="!condition.canRemove"
                    :aria-label="conditionAriaLabel(badge, 'remove condition')"
                    @click.stop="removeCondition(badge, conditionIndex)"
                  >
                    &times;
                  </button>
                </div>
                <div
                  v-if="!conditionRows(badge).length"
                  class="cptm-badge-condition-empty"
                >
                  {{ conditionlessSummary(badge) }}
                </div>
              </div>

              <div class="cptm-badge-foot">
                <button
                  type="button"
                  class="cptm-badge-condition-add"
                  @click.stop="addCondition(badge)"
                >
                  + Add condition
                </button>
                <button
                  v-if="!badge.core"
                  type="button"
                  class="cptm-badge-delete"
                  @click.stop="deleteBadge(badge)"
                >
                  Delete badge
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="showResetConfirm"
      ref="resetModal"
      class="cptm-badge-reset-modal"
      role="dialog"
      aria-modal="true"
      aria-labelledby="cptm-badge-reset-title"
      @click.self="closeResetConfirm"
    >
      <div class="cptm-badge-reset-modal__dialog">
        <div class="cptm-badge-reset-modal__head">
          <h3 id="cptm-badge-reset-title">Reset badge defaults?</h3>
          <button
            type="button"
            class="cptm-badge-reset-modal__close"
            aria-label="Close reset confirmation"
            @click.stop="closeResetConfirm"
          >
            x
          </button>
        </div>
        <p>
          This will restore New, Popular, and Featured badge labels, colors,
          icons, display type, and default conditions. Custom badges will be
          removed. You can review the changes before saving.
        </p>
        <div class="cptm-badge-reset-modal__actions">
          <button
            type="button"
            class="cptm-badges-link-button"
            @click.stop="closeResetConfirm"
          >
            Cancel
          </button>
          <button
            type="button"
            class="cptm-badges-primary-button"
            @click.stop="confirmResetDefaults"
          >
            Reset defaults
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "settings-badges-manager",

  props: {
    fields: {
      type: Object,
      default: () => ({}),
    },
    highlightedFieldKey: {
      type: String,
      default: "",
    },
    cachedFields: {
      type: Object,
      default: () => ({}),
    },
  },

  data() {
    return {
      openBadges: {
        new: true,
        popular: false,
        featured: false,
      },
      iconPickers: {},
      rulesDraft: null,
      showResetConfirm: false,
    };
  },

  computed: {
    badgeTypeOptions() {
      return [
        {
          value: "text",
          label: "Text badge",
          description: "Label with an optional icon.",
        },
        {
          value: "icon",
          label: "Icon badge",
          description: "Icon with hover text.",
        },
      ];
    },

    defaultBadgeDisplayType() {
      return this.fieldValue("badge_display_type", "text_badge") ===
        "icon_badge"
        ? "icon"
        : "text";
    },

    coreBadgeMetas() {
      return [
        {
          key: "new",
          core: true,
          defaultLabel: "New",
          defaultColor: "#2C99FF",
          defaultIcon: "la la-bolt",
          labelField: "new_badge_text",
          colorField: "new_back_color",
          fields: ["new_badge_text", "new_listing_day", "new_back_color"],
        },
        {
          key: "popular",
          core: true,
          defaultLabel: "Popular",
          defaultColor: "#f51957",
          defaultIcon: "la la-fire",
          labelField: "popular_badge_text",
          colorField: "popular_back_color",
          fields: [
            "popular_badge_text",
            "listing_popular_by",
            "views_for_popular",
            "average_review_for_popular",
            "popular_back_color",
          ],
        },
        {
          key: "featured",
          core: true,
          defaultLabel: "Featured",
          defaultColor: "#fa8b0c",
          defaultIcon: "la la-star-o",
          labelField: "feature_badge_text",
          colorField: "featured_back_color",
          fields: ["feature_badge_text", "featured_back_color"],
        },
      ];
    },

    badges() {
      const rules = this.rulesDraft || this.defaultBadgeRules(false);
      const badges = this.coreBadgeMetas.map((meta) => ({
        ...meta,
        rule: this.badgeRuleByKey(meta.key),
      }));

      Object.keys(rules.badges || {})
        .filter((key) => this.isCustomBadgeKey(key))
        .forEach((key) => {
          badges.push({
            key,
            core: false,
            defaultLabel: "Badge",
            defaultColor: "#3e62f5",
            defaultIcon: "la la-certificate",
            labelField: "",
            colorField: "",
            fields: [],
            rule: this.badgeRuleByKey(key),
          });
        });

      return badges;
    },

    defaultGeneralConditionOptions() {
      return [
        {
          value: "age_days",
          label: "Listing age (days)",
          valueType: "number",
          defaultOperator: "<=",
          defaultValue: this.fieldValue("new_listing_day", "3"),
          min: "0",
          step: "1",
        },
        {
          value: "view_count",
          label: "View count",
          valueType: "number",
          defaultOperator: ">=",
          defaultValue: this.fieldValue("views_for_popular", "5"),
          min: "0",
          step: "1",
        },
        {
          value: "average_rating",
          label: "Average rating",
          valueType: "number",
          defaultOperator: ">=",
          defaultValue: this.fieldValue("average_review_for_popular", "4"),
          min: "0",
          max: "5",
          step: ".5",
        },
        {
          value: "review_count",
          label: "Review count",
          valueType: "number",
          defaultOperator: ">=",
          defaultValue: "1",
          min: "0",
          step: "1",
        },
        {
          value: "is_featured",
          label: "Is featured",
          valueType: "boolean",
          defaultOperator: "is",
          defaultValue: true,
        },
      ];
    },

    badgeRuleConditionSources() {
      const ruleField = this.field("directorist_badge_rules");

      return ruleField.condition_sources || ruleField.conditionSources || {};
    },

    generalConditionOptions() {
      const general = this.badgeRuleConditionSources.general || [];

      if (!Array.isArray(general) || !general.length) {
        return this.defaultGeneralConditionOptions;
      }

      return general
        .map((option) => this.normalizeSourceOption(option, "text"))
        .filter((option) => !!option.value);
    },

    fieldConditionOptions() {
      const fields = this.badgeRuleConditionSources.fields || [];

      if (!Array.isArray(fields)) {
        return [];
      }

      return fields
        .map((field) => this.normalizeSourceOption(field, "text"))
        .filter((field) => !!field.value);
    },

    pricingPlanOptions() {
      const pricing = this.badgeRuleConditionSources.pricing || [];

      if (!Array.isArray(pricing)) {
        return [];
      }

      return pricing
        .map((plan) => ({
          value: this.stringifyValue(plan.value),
          label: this.stringifyValue(plan.label, plan.value),
        }))
        .filter((plan) => !!plan.value);
    },

    pricingConditionOptions() {
      const planOptions = this.pricingPlanOptions;

      return [
        {
          value: "has_plan",
          label: "Has pricing plan",
          valueType: "boolean",
          defaultOperator: "is",
          defaultValue: true,
        },
        {
          value: "plan_id",
          label: "Pricing plan",
          valueType: planOptions.length ? "select" : "text",
          defaultOperator: planOptions.length ? "is" : "=",
          defaultValue: planOptions.length ? planOptions[0].value : "",
          valueOptions: planOptions,
        },
      ];
    },

    conditionSourceOptions() {
      return [
        { value: "general", label: "General", disabled: false },
        {
          value: "field",
          label: "Field",
          disabled: !this.fieldConditionOptions.length,
        },
        { value: "pricing", label: "Pricing plan", disabled: false },
      ];
    },

    badgeRulesSignature() {
      return JSON.stringify(this.badgeRuleSourceValues());
    },

    iconPickerAvailable() {
      return (
        typeof IconPicker !== "undefined" &&
        typeof directoriistFontAwesomeIcons !== "undefined" &&
        typeof directoriistLineAwesomeIcons !== "undefined"
      );
    },
  },

  watch: {
    highlightedFieldKey() {
      this.openHighlightedBadge();
    },

    badgeRulesSignature() {
      this.syncBadgeRules();
    },

    showResetConfirm(isVisible) {
      if (isVisible) {
        this.$nextTick(this.moveResetModalToBody);
        return;
      }

      this.cleanupResetModal();
    },
  },

  created() {
    this.rulesDraft = this.initialRulesDraft();
  },

  mounted() {
    this.openHighlightedBadge();
    this.$nextTick(this.refreshIconPickers);
  },

  beforeDestroy() {
    this.cleanupResetModal();
    this.destroyIconPickers();
  },

  methods: {
    field(key) {
      return this.fields[key] || {};
    },

    cachedField(key) {
      return this.cachedFields[key] || {};
    },

    rawFieldValue(key) {
      return this.field(key).value;
    },

    rawCachedFieldValue(key) {
      return this.cachedField(key).value;
    },

    fieldValue(key, fallback = "") {
      return this.stringifyValue(this.rawFieldValue(key), fallback);
    },

    cachedFieldValue(key, fallback = "") {
      return this.stringifyValue(this.rawCachedFieldValue(key), fallback);
    },

    stringifyValue(value, fallback = "") {
      if (typeof value === "undefined" || value === null || value === false) {
        return fallback;
      }

      return String(value);
    },

    decodeBadgeOperator(value, fallback = "=") {
      const operator = this.stringifyValue(value, fallback)
        .trim()
        .replace(/≤/g, "<=")
        .replace(/≥/g, ">=")
        .replace(/≠/g, "!=");

      if (!operator || operator.indexOf("&") === -1) {
        return operator;
      }

      return operator
        .replace(/&lt;/gi, "<")
        .replace(/&#60;/g, "<")
        .replace(/&#x3c;/gi, "<")
        .replace(/&gt;/gi, ">")
        .replace(/&#62;/g, ">")
        .replace(/&#x3e;/gi, ">")
        .replace(/&ne;/gi, "!=")
        .replace(/&#8800;/g, "!=")
        .replace(/&#x2260;/gi, "!=")
        .trim();
    },

    booleanValue(value) {
      return value === true || value === "true" || value === 1 || value === "1";
    },

    normalizeSourceOption(option, fallbackValueType = "text") {
      if (!option || typeof option !== "object") {
        return null;
      }

      const valueType = this.stringifyValue(
        option.valueType || option.value_type,
        fallbackValueType,
      );
      const hasDefaultValue = typeof option.defaultValue !== "undefined";
      const hasDefaultValueAlias = typeof option.default_value !== "undefined";

      return {
        value: this.stringifyValue(option.value),
        label: this.stringifyValue(option.label, option.value),
        valueType,
        defaultOperator: this.operatorAlias(
          option.defaultOperator ||
            option.default_operator ||
            this.defaultOperatorForValueType(valueType),
        ),
        defaultValue:
          !hasDefaultValue && !hasDefaultValueAlias
            ? ""
            : hasDefaultValue
              ? option.defaultValue
              : option.default_value,
        min: this.stringifyValue(option.min),
        max: this.stringifyValue(option.max),
        step: this.stringifyValue(option.step),
        valueOptions: Array.isArray(option.valueOptions)
          ? this.normalizeValueOptions(option.valueOptions)
          : Array.isArray(option.value_options)
            ? this.normalizeValueOptions(option.value_options)
            : [],
      };
    },

    normalizeValueOptions(options) {
      return options
        .map((item) => ({
          value: this.stringifyValue(item.value || item.option_value),
          label: this.stringifyValue(
            item.label || item.option_label,
            item.value || item.option_value,
          ),
        }))
        .filter((item) => !!item.value);
    },

    updateField(fieldKey, value) {
      if (!fieldKey) {
        return;
      }

      this.$emit("update-field", {
        fieldKey,
        value,
      });
    },

    badgeRuleSourceKeys() {
      return [
        "badge_display_type",
        "new_badge_text",
        "new_listing_day",
        "new_back_color",
        "popular_badge_text",
        "listing_popular_by",
        "views_for_popular",
        "average_review_for_popular",
        "popular_back_color",
        "feature_badge_text",
        "featured_back_color",
      ];
    },

    badgeRuleSourceValues() {
      return this.badgeRuleSourceKeys().reduce((values, fieldKey) => {
        values[fieldKey] = this.fieldValue(fieldKey);
        return values;
      }, {});
    },

    badgeRuleSourceFieldsChanged() {
      return this.badgeRuleSourceKeys().some(
        (fieldKey) =>
          this.fieldValue(fieldKey) !== this.cachedFieldValue(fieldKey),
      );
    },

    syncBadgeRules(forceUpdate = false) {
      if (!this.fields.directorist_badge_rules || !this.rulesDraft) {
        return;
      }

      const nextRules = this.buildBadgeRules();
      const baselineRules = this.baselineBadgeRules();
      const shouldUseCachedValue =
        !forceUpdate &&
        !this.badgeRuleSourceFieldsChanged() &&
        this.rulesAreSame(nextRules, baselineRules);

      this.updateField(
        "directorist_badge_rules",
        shouldUseCachedValue
          ? this.rawCachedFieldValue("directorist_badge_rules") || ""
          : nextRules,
      );
    },

    buildBadgeRules() {
      const badges = {};

      this.badges.forEach((badge) => {
        const rule = this.badgeRuleByKey(badge.key);
        const style = this.normalizedStyle(rule.style, badge.defaultColor);

        badges[badge.key] = {
          enabled: true,
          internalName: this.badgeInternalName(badge),
          label: this.badgeVisibleLabel(badge),
          type: this.badgeType(badge),
          typeEdited: !!rule.typeEdited,
          icon: this.badgeIcon(badge),
          iconEdited: !!rule.iconEdited,
          color: style.bg,
          style,
          hover: this.normalizedHover(rule.hover),
          match: rule.match === "any" ? "any" : "all",
          conditions: this.savableConditions(rule.conditions),
        };
      });

      return {
        version: 1,
        badges,
      };
    },

    initialRulesDraft() {
      const savedRules = this.normalizeBadgeRules(
        this.parseBadgeRules(this.rawFieldValue("directorist_badge_rules")),
        false,
      );

      return savedRules || this.defaultBadgeRulesForMissingStore(false);
    },

    baselineBadgeRules() {
      const cachedRules = this.normalizeBadgeRules(
        this.parseBadgeRules(
          this.rawCachedFieldValue("directorist_badge_rules"),
        ),
        true,
      );

      return cachedRules || this.defaultBadgeRulesForMissingStore(true);
    },

    defaultBadgeRulesForMissingStore(useCachedValues) {
      const rules = this.defaultBadgeRules(useCachedValues);
      const useLegacyConditions =
        this.hasSavedLegacyBadgeOptions(useCachedValues);

      ["new", "popular", "featured"].forEach((badgeKey) => {
        if (!rules.badges[badgeKey]) {
          return;
        }

        const conditionState = useLegacyConditions
          ? this.legacyDefaultCoreConditionState(badgeKey, useCachedValues)
          : this.factoryDefaultCoreConditionState(badgeKey);

        rules.badges[badgeKey].conditions = conditionState.conditions;
        rules.badges[badgeKey].match = conditionState.match;
      });

      return rules;
    },

    factoryDefaultCoreConditionState(badgeKey) {
      return {
        match: badgeKey === "popular" ? "any" : "all",
        conditions: this.factoryDefaultCoreConditions(badgeKey),
      };
    },

    legacyDefaultCoreConditionState(badgeKey, useCachedValues) {
      if (badgeKey === "new") {
        return {
          match: "all",
          conditions: [
            {
              source: "general",
              key: "age_days",
              operator: "<=",
              value: this.savedBadgeFieldValue(
                "new_listing_day",
                "3",
                useCachedValues,
              ),
            },
          ],
        };
      }

      if (badgeKey === "popular") {
        const viewCondition = {
          source: "general",
          key: "view_count",
          operator: ">=",
          value: this.savedBadgeFieldValue(
            "views_for_popular",
            "5",
            useCachedValues,
          ),
        };
        const ratingCondition = {
          source: "general",
          key: "average_rating",
          operator: ">=",
          value: this.savedBadgeFieldValue(
            "average_review_for_popular",
            "4",
            useCachedValues,
          ),
        };
        const popularBy = this.savedBadgeFieldValue(
          "listing_popular_by",
          "",
          useCachedValues,
        );
        const hasSavedViewThreshold = this.hasSavedBadgeFieldValue(
          "views_for_popular",
          useCachedValues,
        );
        const hasSavedRatingThreshold = this.hasSavedBadgeFieldValue(
          "average_review_for_popular",
          useCachedValues,
        );
        const hasSavedPopularMode = this.hasSavedBadgeFieldValue(
          "listing_popular_by",
          useCachedValues,
        );

        if (hasSavedViewThreshold && hasSavedRatingThreshold) {
          return {
            match: "any",
            conditions: [viewCondition, ratingCondition],
          };
        }

        if (popularBy === "view_count") {
          return { match: "all", conditions: [viewCondition] };
        }

        if (popularBy === "average_rating") {
          return { match: "all", conditions: [ratingCondition] };
        }

        if (
          hasSavedViewThreshold ||
          hasSavedRatingThreshold ||
          hasSavedPopularMode
        ) {
          return {
            match: "all",
            conditions: [viewCondition, ratingCondition],
          };
        }

        return this.factoryDefaultCoreConditionState("popular");
      }

      if (badgeKey === "featured") {
        return {
          match: "all",
          conditions: this.factoryDefaultCoreConditions("featured"),
        };
      }

      return { match: "all", conditions: [] };
    },

    hasSavedLegacyBadgeOptions(useCachedValues) {
      return this.badgeRuleSourceKeys().some((fieldKey) =>
        this.hasSavedBadgeFieldValue(fieldKey, useCachedValues),
      );
    },

    hasSavedBadgeFieldValue(fieldKey, useCachedValues) {
      const field = useCachedValues
        ? this.cachedField(fieldKey)
        : this.field(fieldKey);

      return !!field && field.forceUpdate !== true;
    },

    savedBadgeFieldValue(fieldKey, fallback = "", useCachedValues = false) {
      if (!this.hasSavedBadgeFieldValue(fieldKey, useCachedValues)) {
        return fallback;
      }

      return useCachedValues
        ? this.cachedFieldValue(fieldKey, fallback)
        : this.fieldValue(fieldKey, fallback);
    },

    defaultBadgeRules(useCachedValues) {
      const getValue = (fieldKey, fallback = "") =>
        useCachedValues
          ? this.cachedFieldValue(fieldKey, fallback)
          : this.fieldValue(fieldKey, fallback);
      const type =
        getValue("badge_display_type", "text_badge") === "icon_badge"
          ? "icon"
          : "text";

      return {
        version: 1,
        badges: this.coreBadgeRuleDefaults(getValue, type, false),
      };
    },

    factoryDefaultBadgeRules() {
      return {
        version: 1,
        badges: this.coreBadgeRuleDefaults(
          (fieldKey, fallback = "") => fallback,
          "text",
          true,
        ),
      };
    },

    coreBadgeRuleDefaults(getValue, type, includeDefaultConditions = false) {
      return this.coreBadgeMetas.reduce((badges, meta) => {
        const label = getValue(meta.labelField, meta.defaultLabel);
        const color = getValue(meta.colorField, meta.defaultColor);
        const conditionState = includeDefaultConditions
          ? this.factoryDefaultCoreConditionState(meta.key)
          : { match: "all", conditions: [] };

        badges[meta.key] = {
          enabled: true,
          internalName: label,
          label,
          type,
          typeEdited: false,
          icon: meta.defaultIcon,
          iconEdited: false,
          color,
          style: {
            bg: color,
            text: "#ffffff",
            border: color,
          },
          hover: { text: "", bg: "", textColor: "" },
          match: conditionState.match,
          conditions: conditionState.conditions,
        };

        return badges;
      }, {});
    },

    factoryDefaultCoreConditions(badgeKey) {
      if (badgeKey === "new") {
        return [
          {
            source: "general",
            key: "age_days",
            operator: "<=",
            value: "3",
          },
        ];
      }

      if (badgeKey === "popular") {
        return [
          {
            source: "general",
            key: "view_count",
            operator: ">=",
            value: "5",
          },
          {
            source: "general",
            key: "average_rating",
            operator: ">=",
            value: "4",
          },
        ];
      }

      if (badgeKey === "featured") {
        return [
          {
            source: "general",
            key: "is_featured",
            operator: "is",
            value: true,
          },
        ];
      }

      return [];
    },

    parseBadgeRules(value) {
      if (!value) {
        return null;
      }

      if (typeof value === "object") {
        return value;
      }

      if (typeof value !== "string") {
        return null;
      }

      const parsedJson = this.parseJSON(value);

      if (parsedJson) {
        return parsedJson;
      }

      const decodedValue = this.decodeBase64(value);

      return decodedValue ? this.parseJSON(decodedValue) : null;
    },

    parseJSON(value) {
      try {
        return JSON.parse(value);
      } catch (error) {
        return null;
      }
    },

    decodeBase64(value) {
      try {
        const decoded = atob(value);
        const bytes = decoded
          .split("")
          .map((char) => `%${`00${char.charCodeAt(0).toString(16)}`.slice(-2)}`)
          .join("");

        return decodeURIComponent(bytes);
      } catch (error) {
        return "";
      }
    },

    normalizeBadgeRules(rules, useCachedValues = false) {
      if (!rules || typeof rules !== "object" || !rules.badges) {
        return null;
      }

      const fallbackRules = this.defaultBadgeRules(useCachedValues);
      const normalizedRules = {
        version: 1,
        badges: {},
      };

      this.coreBadgeMetas.forEach((badge) => {
        normalizedRules.badges[badge.key] = this.normalizedBadgeRule(
          badge.key,
          rules.badges[badge.key],
          fallbackRules.badges[badge.key],
        );
      });

      Object.keys(rules.badges)
        .filter((key) => this.isCustomBadgeKey(key))
        .forEach((key) => {
          normalizedRules.badges[key] = this.normalizedBadgeRule(
            key,
            rules.badges[key],
            this.customBadgeDefaults(),
          );
        });

      return normalizedRules;
    },

    customBadgeDefaults() {
      return {
        enabled: true,
        internalName: "Untitled badge",
        label: "Badge",
        type: "text",
        typeEdited: false,
        icon: "la la-certificate",
        iconEdited: false,
        color: "#3e62f5",
        style: { bg: "#3e62f5", text: "#ffffff", border: "#3e62f5" },
        hover: { text: "", bg: "", textColor: "" },
        match: "all",
        conditions: [],
      };
    },

    normalizedBadgeRule(key, rule, fallbackRule) {
      const source = rule && typeof rule === "object" ? rule : {};
      const style = this.normalizedStyle(
        source.style || { bg: source.color },
        fallbackRule.style && fallbackRule.style.bg
          ? fallbackRule.style.bg
          : fallbackRule.color,
        fallbackRule.style,
      );
      const label = this.stringifyValue(source.label, fallbackRule.label);
      const typeEdited = this.booleanValue(
        source.typeEdited || source.type_edited,
      );
      const iconEdited = this.booleanValue(
        source.iconEdited || source.icon_edited,
      );
      const type = this.normalizedBadgeRuleType(
        key,
        source.type,
        fallbackRule.type,
        typeEdited,
      );
      const icon = this.normalizedBadgeRuleIcon(
        key,
        source.icon,
        fallbackRule.icon,
        iconEdited,
        type,
      );

      return {
        enabled: true,
        internalName: this.stringifyValue(
          source.internalName || source.internal_name,
          this.stringifyValue(fallbackRule.internalName, label),
        ),
        label,
        type,
        typeEdited,
        icon,
        iconEdited,
        color: style.bg,
        style,
        hover: this.normalizedHover(source.hover, fallbackRule.hover),
        match: source.match === "any" ? "any" : "all",
        conditions: this.normalizedConditions(
          source.conditions,
          this.defaultConditionKey({ key }),
        ),
      };
    },

    normalizedStyle(style = {}, fallbackColor = "#3e62f5", fallbackStyle = {}) {
      const bg = this.normalizeHex(style.bg || fallbackColor) || fallbackColor;
      const text =
        this.normalizeHex(style.text || fallbackStyle.text) || "#ffffff";
      const border =
        this.normalizeHex(style.border || fallbackStyle.border) || bg;

      return { bg, text, border };
    },

    normalizedHover(hover = {}, fallbackHover = {}) {
      const source = hover && typeof hover === "object" ? hover : {};
      const fallback =
        fallbackHover && typeof fallbackHover === "object" ? fallbackHover : {};
      const text = this.stringifyValue(source.text, fallback.text || "");
      const bg = this.normalizeHex(source.bg || fallback.bg) || "";
      const textColor =
        this.normalizeHex(
          source.textColor || source.text_color || fallback.textColor,
        ) || "";

      return { text, bg, textColor };
    },

    normalizedBadgeRuleType(key, type, fallbackType, typeEdited) {
      if (this.coreBadgeUsesGlobalType(key, typeEdited)) {
        return this.normalizeBadgeType(fallbackType);
      }

      return this.normalizeBadgeType(type || fallbackType);
    },

    coreBadgeUsesGlobalType(key, typeEdited) {
      return ["new", "popular", "featured"].includes(key) && !typeEdited;
    },

    normalizedBadgeRuleIcon(key, icon, fallbackIcon, iconEdited, type) {
      if (this.coreBadgeUsesUneditedStarFallback(key, icon, iconEdited)) {
        return this.stringifyValue(fallbackIcon, "la la-certificate");
      }

      const iconClass = this.stringifyValue(icon).trim();

      if (iconClass) {
        return iconClass;
      }

      if (this.normalizeBadgeType(type) === "text" && iconEdited) {
        return "";
      }

      return this.stringifyValue(fallbackIcon, "la la-certificate");
    },

    coreBadgeUsesUneditedStarFallback(key, icon, iconEdited) {
      if (!["new", "popular"].includes(key) || iconEdited) {
        return false;
      }

      return this.legacyGenericStarIcons().includes(
        this.stringifyValue(icon).trim(),
      );
    },

    legacyGenericStarIcons() {
      return ["la la-star-o", "las la-star", "far fa-star", "fas fa-star"];
    },

    normalizedConditions(conditions) {
      const rows = Array.isArray(conditions) ? conditions : [];

      return rows
        .map((condition) => this.normalizedCondition(condition))
        .filter((condition) => !!condition);
    },

    normalizedCondition(condition) {
      if (!condition || typeof condition !== "object") {
        return null;
      }

      const source = this.sourceIsAllowed(condition.source || condition.type)
        ? condition.source || condition.type
        : "general";
      const requestedKey = this.conditionKeyAlias(
        source,
        this.stringifyValue(condition.key),
      );
      const hasRequestedKey = !!requestedKey;
      const definition = this.conditionDefinition(
        source,
        hasRequestedKey
          ? requestedKey
          : this.defaultConditionKeyForSource(source),
        !hasRequestedKey,
      );

      if (!definition) {
        return this.unsupportedCondition(source, requestedKey, condition);
      }

      const operator = this.operatorAlias(condition.operator);
      const normalizedOperator = this.operatorIsAllowed(
        source,
        definition.value,
        operator,
      )
        ? operator
        : definition.defaultOperator;

      return {
        source,
        key: definition.value,
        operator: normalizedOperator,
        value: this.operatorNeedsValue(normalizedOperator)
          ? this.normalizeConditionValue(
              source,
              definition.value,
              condition.value,
            )
          : "",
      };
    },

    savableConditions(conditions) {
      return this.normalizedConditions(conditions).map((condition) => ({
        source: condition.source,
        key: condition.key,
        operator: condition.operator,
        value: condition.value,
      }));
    },

    unsupportedCondition(source, key, condition) {
      if (!key) {
        return null;
      }

      return {
        source,
        key,
        operator: this.operatorAlias(condition.operator),
        value: this.stringifyValue(condition.value),
        unsupported: true,
      };
    },

    unsupportedConditionDefinition(condition) {
      return {
        value: condition.key,
        label: "Unsupported condition",
        valueType: "text",
        defaultOperator: condition.operator,
        defaultValue: condition.value,
        valueOptions: [],
      };
    },

    createCondition(source = "general", key = "") {
      const normalizedSource = this.sourceIsAllowed(source)
        ? source
        : "general";
      const normalizedKey =
        key || this.defaultConditionKeyForSource(normalizedSource);
      const definition = this.conditionDefinition(
        normalizedSource,
        normalizedKey,
      );

      return {
        source: normalizedSource,
        key: definition.value,
        operator: definition.defaultOperator,
        value: this.normalizeConditionValue(
          normalizedSource,
          definition.value,
          definition.defaultValue,
        ),
      };
    },

    conditionKeyAlias(source, key) {
      const aliases = {
        general: {
          ageDays: "age_days",
          viewCount: "view_count",
          avgRating: "average_rating",
          reviewCount: "review_count",
          isFeatured: "is_featured",
        },
        pricing: {
          hasAnyPlan: "has_plan",
          planName: "plan_id",
        },
      };

      return aliases[source] && aliases[source][key]
        ? aliases[source][key]
        : key;
    },

    sourceIsAllowed(source) {
      const sourceKey = this.stringifyValue(source, "general");

      return ["general", "field", "pricing"].includes(sourceKey);
    },

    defaultConditionKeyForSource(source) {
      const options = this.conditionOptionsForSource(source);

      return options.length ? options[0].value : "view_count";
    },

    conditionOptionsForSource(source) {
      if (source === "field") {
        return this.fieldConditionOptions;
      }

      if (source === "pricing") {
        return this.pricingConditionOptions;
      }

      return this.generalConditionOptions;
    },

    conditionDefinition(source, key, useFallback = true) {
      const conditionKey = String(key || "");
      const options = this.conditionOptionsForSource(source);
      const definition =
        options.find((option) => option.value === conditionKey) || null;

      if (definition) {
        return definition;
      }

      if (source === "field" && conditionKey) {
        return this.normalizeSourceOption(
          {
            value: conditionKey,
            label: conditionKey,
            valueType: "text",
            defaultOperator: "contains",
            defaultValue: "",
          },
          "text",
        );
      }

      return useFallback ? options[0] || this.generalConditionOptions[0] : null;
    },

    conditionKeyOptions(source, key = "", includeUnsupported = false) {
      const options = this.conditionOptionsForSource(source).map((option) => ({
        value: option.value,
        label: option.label,
      }));

      if (
        includeUnsupported &&
        key &&
        !options.some((option) => option.value === key)
      ) {
        options.push({ value: key, label: "Unsupported condition" });
      }

      if (
        source === "field" &&
        key &&
        !options.some((option) => option.value === key)
      ) {
        options.push({ value: key, label: key });
      }

      return options;
    },

    operatorOptions(source, key) {
      const definition = this.conditionDefinition(source, key);

      if (!definition) {
        return [];
      }

      if (
        definition.valueType === "boolean" ||
        definition.valueType === "select"
      ) {
        return [
          { value: "is", label: "is" },
          { value: "is_not", label: "is not" },
        ];
      }

      if (definition.valueType === "text") {
        return [
          { value: "=", label: "equals" },
          { value: "is_not", label: "does not equal" },
          { value: "contains", label: "contains" },
          { value: "not_contains", label: "does not contain" },
          { value: "is_empty", label: "is empty" },
          { value: "is_not_empty", label: "is not empty" },
        ];
      }

      return [
        { value: ">=", label: ">=" },
        { value: ">", label: ">" },
        { value: "<=", label: "<=" },
        { value: "<", label: "<" },
        { value: "=", label: "=" },
        { value: "is_not", label: "!=" },
      ];
    },

    defaultOperatorForValueType(valueType) {
      if (valueType === "boolean" || valueType === "select") {
        return "is";
      }

      if (valueType === "number") {
        return ">=";
      }

      return "contains";
    },

    operatorAlias(operator) {
      const normalizedOperator = this.decodeBadgeOperator(operator);

      if (normalizedOperator === "equals") {
        return "=";
      }

      if (normalizedOperator === "not_equals" || normalizedOperator === "!=") {
        return "is_not";
      }

      return normalizedOperator || "=";
    },

    operatorIsAllowed(source, key, operator) {
      return this.operatorOptions(source, key).some(
        (item) => item.value === operator,
      );
    },

    operatorNeedsValue(operator) {
      return !["is_empty", "is_not_empty"].includes(operator);
    },

    normalizeConditionValue(source, key, value) {
      const definition = this.conditionDefinition(source, key);

      if (!definition) {
        return "";
      }

      if (definition.valueType === "boolean") {
        return value === true || value === "true";
      }

      if (definition.valueType === "number") {
        return this.normalizeRuleNumber(value);
      }

      if (definition.valueType === "select") {
        const options = Array.isArray(definition.valueOptions)
          ? definition.valueOptions
          : [];

        if (!options.length) {
          return this.stringifyValue(value, definition.defaultValue);
        }

        const optionExists = options.some(
          (option) => option.value === String(value),
        );

        return optionExists ? String(value) : String(definition.defaultValue);
      }

      return this.stringifyValue(value, definition.defaultValue);
    },

    normalizeRuleNumber(value) {
      const normalizedValue = String(value || "").trim();
      const numberValue = Number(normalizedValue);

      return Number.isFinite(numberValue) ? numberValue : normalizedValue;
    },

    badgeRuleByKey(key) {
      if (!this.rulesDraft || !this.rulesDraft.badges) {
        this.rulesDraft = this.defaultBadgeRules(false);
      }

      if (!this.rulesDraft.badges[key]) {
        this.$set(
          this.rulesDraft.badges,
          key,
          this.isCustomBadgeKey(key)
            ? this.customBadgeDefaults()
            : this.defaultBadgeRules(false).badges[key],
        );
      }

      return this.rulesDraft.badges[key];
    },

    badgeMatchMode(badge) {
      const rule = this.badgeRuleByKey(badge.key);

      return rule.match === "any" ? "any" : "all";
    },

    matchModeLabel(badge) {
      return this.badgeMatchMode(badge) === "any" ? "Any" : "All";
    },

    badgeInternalName(badge) {
      const rule = this.badgeRuleByKey(badge.key);
      const fallback = badge.core
        ? this.fieldValue(badge.labelField, badge.defaultLabel)
        : "Untitled badge";

      return this.stringifyValue(rule.internalName, fallback);
    },

    badgeVisibleLabel(badge) {
      const rule = this.badgeRuleByKey(badge.key);

      if (badge.core && !rule.label) {
        return this.fieldValue(badge.labelField, badge.defaultLabel);
      }

      return this.stringifyValue(rule.label, badge.defaultLabel);
    },

    badgeType(badge) {
      return this.normalizeBadgeType(this.badgeRuleByKey(badge.key).type);
    },

    normalizeBadgeType(type) {
      return type === "icon" || type === "icon_badge" ? "icon" : "text";
    },

    badgeIcon(badge) {
      return this.sanitizeIconClass(
        this.badgeRuleByKey(badge.key).icon,
        this.badgeIconFallback(badge),
      );
    },

    badgePreviewShowsIcon(badge) {
      return this.badgeHasIcon(badge);
    },

    badgeHasIcon(badge) {
      return !!this.badgeIcon(badge);
    },

    badgeIconFallback(badge) {
      const rule = this.badgeRuleByKey(badge.key);

      if (this.badgeType(badge) === "icon" || !rule.iconEdited) {
        return badge.defaultIcon;
      }

      return "";
    },

    sanitizeIconClass(icon, fallback = "") {
      const iconClass = this.stringifyValue(icon, fallback).trim();

      return iconClass || fallback;
    },

    badgeStyleValue(badge, key) {
      return this.normalizedStyle(
        this.badgeRuleByKey(badge.key).style,
        badge.defaultColor,
      )[key];
    },

    badgeHoverFallback(badge, key) {
      if (key === "text") {
        return this.badgeVisibleLabel(badge);
      }

      if (key === "bg") {
        return this.badgeStyleValue(badge, "bg");
      }

      if (key === "textColor") {
        return this.badgeStyleValue(badge, "text");
      }

      return "";
    },

    badgeHoverValue(badge, key) {
      const rule = this.badgeRuleByKey(badge.key);
      const hover = this.normalizedHover(rule.hover);
      const value = hover[key];

      return value || this.badgeHoverFallback(badge, key);
    },

    badgeTooltipText(badge) {
      return this.badgeHoverValue(badge, "text");
    },

    badgeTooltipColor(badge, key) {
      return this.badgeHoverValue(badge, key);
    },

    badgeColorControlLabel(badge) {
      return this.badgeType(badge) === "icon" ? "Icon color" : "Text color";
    },

    badgeColorControlAriaLabel(badge) {
      return `${this.badgeInternalName(badge)} ${
        this.badgeType(badge) === "icon" ? "icon" : "text"
      } color`;
    },

    updateInternalName(badge, value) {
      const rule = this.badgeRuleByKey(badge.key);

      this.$set(rule, "internalName", value);
      this.syncBadgeRules();
    },

    updateVisibleLabel(badge, value) {
      const rule = this.badgeRuleByKey(badge.key);

      this.$set(rule, "label", value);

      if (badge.core) {
        this.updateField(badge.labelField, value);
      }

      this.syncBadgeRules();
    },

    updateBadgeType(badge, value) {
      const rule = this.badgeRuleByKey(badge.key);
      const type = this.normalizeBadgeType(value);

      this.$set(rule, "type", type);
      this.$set(rule, "typeEdited", true);

      if (type === "icon" && !this.sanitizeIconClass(rule.icon)) {
        this.$set(rule, "icon", badge.defaultIcon);
        this.$set(rule, "iconEdited", false);
        this.destroyIconPicker(badge.key);
      }

      this.syncBadgeRules();
      this.$nextTick(this.refreshIconPickers);
    },

    updateBadgeIcon(badge, value) {
      const rule = this.badgeRuleByKey(badge.key);
      const icon = this.sanitizeIconClass(value);
      const shouldRestoreIcon = this.badgeType(badge) === "icon" && !icon;

      this.$set(rule, "icon", shouldRestoreIcon ? badge.defaultIcon : icon);
      this.$set(rule, "iconEdited", !shouldRestoreIcon);
      this.syncBadgeRules();

      if (shouldRestoreIcon) {
        this.$nextTick(() => this.refreshIconPickers(true));
      }
    },

    refreshIconPickers(forceRebuild = false) {
      if (!this.iconPickerAvailable) {
        return;
      }

      if (forceRebuild) {
        this.destroyIconPickers();
      }

      const activeKeys = this.badges.map((badge) => badge.key);

      Object.keys(this.iconPickers).forEach((key) => {
        if (!activeKeys.includes(key)) {
          this.destroyIconPicker(key);
        }
      });

      this.badges.forEach((badge) => {
        const container = this.iconPickerContainer(badge.key);

        if (!container || this.iconPickers[badge.key]) {
          return;
        }

        container.innerHTML = "";

        const picker = new IconPicker({
          container,
          value: this.badgeIcon(badge),
          icons: {
            fontAwesome: directoriistFontAwesomeIcons,
            lineAwesome: directoriistLineAwesomeIcons,
          },
          labels:
            window.directorist_admin &&
            window.directorist_admin.icon_picker_labels
              ? window.directorist_admin.icon_picker_labels
              : {},
          onSelect: (value) => this.updateBadgeIcon(badge, value),
        });

        picker.init();
        this.$set(this.iconPickers, badge.key, picker);
      });
    },

    iconPickerContainer(key) {
      const ref = this.$refs[`iconPicker-${key}`];

      return Array.isArray(ref) ? ref[0] : ref;
    },

    destroyIconPicker(key) {
      if (!this.iconPickers[key]) {
        return;
      }

      if (typeof this.iconPickers[key].destroy === "function") {
        this.iconPickers[key].destroy();
      }

      const container = this.iconPickerContainer(key);

      if (container) {
        container.innerHTML = "";
      }

      this.$delete(this.iconPickers, key);
    },

    destroyIconPickers() {
      Object.keys(this.iconPickers).forEach((key) =>
        this.destroyIconPicker(key),
      );
    },

    updateBadgeStyle(badge, key, value) {
      const rule = this.badgeRuleByKey(badge.key);
      const style = this.normalizedStyle(rule.style, badge.defaultColor);
      const nextValue = this.normalizeHex(value) || value;

      if (key === "bg") {
        const previousBg = style.bg;

        style.bg = nextValue;

        if (!style.border || style.border === previousBg) {
          style.border = nextValue;
        }

        this.$set(rule, "color", nextValue);

        if (badge.core) {
          this.updateField(badge.colorField, nextValue);
        }
      } else {
        style[key] = nextValue;
      }

      this.$set(rule, "style", style);
      this.syncBadgeRules();
    },

    updateBadgeHover(badge, key, value) {
      const rule = this.badgeRuleByKey(badge.key);
      const hover = this.normalizedHover(rule.hover);
      const fallback = this.badgeHoverFallback(badge, key);
      const nextValue =
        key === "text" ? this.stringifyValue(value) : this.normalizeHex(value);

      this.$set(hover, key, nextValue === fallback ? "" : nextValue);
      this.$set(rule, "hover", hover);
      this.syncBadgeRules();
    },

    conditionRows(badge) {
      const rule = this.badgeRuleByKey(badge.key);
      const conditions = this.normalizedConditions(
        rule.conditions,
        this.defaultConditionKey(badge),
        "general",
      );

      return conditions.map((condition, index) => {
        const definition = condition.unsupported
          ? this.unsupportedConditionDefinition(condition)
          : this.conditionDefinition(condition.source, condition.key);

        return {
          ...condition,
          id: `${badge.key}-${index}-${condition.source}-${condition.key}`,
          canRemove: true,
          keyOptions: this.conditionKeyOptions(
            condition.source,
            condition.key,
            condition.unsupported,
          ),
          operatorOptions: condition.unsupported
            ? [{ value: condition.operator, label: "unsupported" }]
            : this.operatorOptions(condition.source, condition.key),
          valueType: definition.valueType,
          value:
            definition.valueType === "boolean"
              ? String(condition.value)
              : String(condition.value),
          valueOptions: definition.valueOptions || [],
          min: definition.min,
          max: definition.max,
          step: definition.step,
          fields: this.conditionLegacyFields(badge, condition, index),
        };
      });
    },

    updateMatchMode(badge, matchMode) {
      const rule = this.badgeRuleByKey(badge.key);

      this.$set(rule, "match", matchMode === "any" ? "any" : "all");
      this.syncBadgeRules();
    },

    addCondition(badge) {
      const rule = this.badgeRuleByKey(badge.key);
      const conditions = this.normalizedConditions(
        rule.conditions,
        this.defaultConditionKey(badge),
        "general",
      );

      conditions.push(
        this.createCondition("general", this.defaultConditionKey(badge)),
      );
      this.$set(rule, "conditions", conditions);
      this.syncBadgeRules();
    },

    removeCondition(badge, index) {
      const rule = this.badgeRuleByKey(badge.key);
      const conditions = this.normalizedConditions(
        rule.conditions,
        this.defaultConditionKey(badge),
        "general",
      );

      if (index < 0 || index >= conditions.length) {
        return;
      }

      conditions.splice(index, 1);
      this.$set(rule, "conditions", conditions);
      this.syncBadgeRules();
    },

    updateConditionSource(badge, index, source) {
      const rule = this.badgeRuleByKey(badge.key);
      const conditions = this.normalizedConditions(
        rule.conditions,
        this.defaultConditionKey(badge),
        "general",
      );
      const nextCondition = this.createCondition(
        source,
        this.defaultConditionKeyForSource(source),
      );

      conditions.splice(index, 1, nextCondition);
      this.$set(rule, "conditions", conditions);
      this.syncLegacyCondition(badge, index, nextCondition);
      this.syncBadgeRules();
    },

    updateConditionKey(badge, index, key) {
      const rule = this.badgeRuleByKey(badge.key);
      const conditions = this.normalizedConditions(
        rule.conditions,
        this.defaultConditionKey(badge),
        "general",
      );
      const source =
        conditions[index] && conditions[index].source
          ? conditions[index].source
          : "general";
      const nextCondition = this.createCondition(source, key);

      conditions.splice(index, 1, nextCondition);
      this.$set(rule, "conditions", conditions);
      this.syncLegacyCondition(badge, index, nextCondition);
      this.syncBadgeRules();
    },

    updateConditionOperator(badge, index, operator) {
      const rule = this.badgeRuleByKey(badge.key);
      const conditions = this.normalizedConditions(
        rule.conditions,
        this.defaultConditionKey(badge),
        "general",
      );
      const condition = { ...conditions[index] };
      const normalizedOperator = this.operatorAlias(operator);

      if (condition.unsupported) {
        return;
      }

      if (
        !this.operatorIsAllowed(
          condition.source,
          condition.key,
          normalizedOperator,
        )
      ) {
        return;
      }

      condition.operator = normalizedOperator;
      condition.value = this.operatorNeedsValue(normalizedOperator)
        ? condition.value
        : "";
      conditions.splice(index, 1, condition);
      this.$set(rule, "conditions", conditions);
      this.syncLegacyCondition(badge, index, condition);
      this.syncBadgeRules();
    },

    updateConditionValue(badge, index, value) {
      const rule = this.badgeRuleByKey(badge.key);
      const conditions = this.normalizedConditions(
        rule.conditions,
        this.defaultConditionKey(badge),
        "general",
      );
      const condition = { ...conditions[index] };

      condition.value = condition.unsupported
        ? this.stringifyValue(value)
        : this.normalizeConditionValue(condition.source, condition.key, value);
      conditions.splice(index, 1, condition);
      this.$set(rule, "conditions", conditions);
      this.syncLegacyCondition(badge, index, condition);
      this.syncBadgeRules();
    },

    defaultConditionKey(badge) {
      if (badge.key === "new") {
        return "age_days";
      }

      if (badge.key === "featured") {
        return "is_featured";
      }

      return "view_count";
    },

    syncLegacyCondition(badge, index, condition) {
      if (!badge.core || condition.source !== "general") {
        return;
      }

      if (badge.key === "new" && index === 0 && condition.key === "age_days") {
        this.updateField("new_listing_day", String(condition.value));
        return;
      }

      if (badge.key !== "popular") {
        return;
      }

      if (condition.key === "view_count") {
        this.updateField("listing_popular_by", "view_count");
        this.updateField("views_for_popular", String(condition.value));
      }

      if (condition.key === "average_rating") {
        this.updateField("average_review_for_popular", String(condition.value));
      }
    },

    conditionLegacyFields(badge, condition, index) {
      if (!badge.core || condition.source !== "general") {
        return [];
      }

      if (badge.key === "new" && index === 0 && condition.key === "age_days") {
        return ["new_listing_day"];
      }

      if (badge.key === "popular" && condition.key === "view_count") {
        return ["listing_popular_by", "views_for_popular"];
      }

      if (badge.key === "popular" && condition.key === "average_rating") {
        return ["average_review_for_popular"];
      }

      return [];
    },

    openResetConfirm() {
      this.showResetConfirm = true;
    },

    closeResetConfirm() {
      this.showResetConfirm = false;
    },

    confirmResetDefaults() {
      this.showResetConfirm = false;
      this.resetDefaults();
    },

    moveResetModalToBody() {
      if (
        !this.showResetConfirm ||
        typeof document === "undefined" ||
        !this.$refs.resetModal
      ) {
        return;
      }

      document.body.classList.add("directorist-badge-reset-modal-open");

      if (this.$refs.resetModal.parentNode !== document.body) {
        document.body.appendChild(this.$refs.resetModal);
      }
    },

    cleanupResetModal() {
      if (typeof document === "undefined") {
        return;
      }

      document.body.classList.remove("directorist-badge-reset-modal-open");

      if (
        this.$refs.resetModal &&
        this.$refs.resetModal.parentNode === document.body
      ) {
        document.body.removeChild(this.$refs.resetModal);
      }
    },

    addBadge() {
      const key = this.newCustomBadgeKey();
      const rule = this.customBadgeDefaults();

      if (!this.rulesDraft || !this.rulesDraft.badges) {
        this.rulesDraft = this.defaultBadgeRules(false);
      }

      Object.keys(this.openBadges).forEach((badgeKey) => {
        this.$set(this.openBadges, badgeKey, false);
      });

      this.$set(this.rulesDraft.badges, key, rule);
      this.$set(this.openBadges, key, true);
      this.syncBadgeRules();
      this.$nextTick(this.refreshIconPickers);
    },

    deleteBadge(badge) {
      if (badge.core || !this.rulesDraft || !this.rulesDraft.badges) {
        return;
      }

      this.$delete(this.rulesDraft.badges, badge.key);
      this.$delete(this.openBadges, badge.key);
      this.destroyIconPicker(badge.key);
      this.syncBadgeRules();
    },

    resetDefaults() {
      this.rulesDraft = this.factoryDefaultBadgeRules();
      this.openBadges = {
        new: true,
        popular: false,
        featured: false,
      };
      this.destroyIconPickers();

      this.updateField("badge_display_type", "text_badge");
      this.updateField("new_badge_text", "New");
      this.updateField("new_listing_day", "3");
      this.updateField("new_back_color", "#2C99FF");
      this.updateField("popular_badge_text", "Popular");
      this.updateField("listing_popular_by", "view_count");
      this.updateField("views_for_popular", "5");
      this.updateField("average_review_for_popular", "4");
      this.updateField("popular_back_color", "#f51957");
      this.updateField("feature_badge_text", "Featured");
      this.updateField("featured_back_color", "#fa8b0c");
      this.syncBadgeRules(true);
      this.$nextTick(this.refreshIconPickers);
    },

    newCustomBadgeKey() {
      if (!this.rulesDraft || !this.rulesDraft.badges) {
        return "custom_badge_" + Date.now().toString(36);
      }

      let key = "";

      do {
        key =
          "custom_badge_" +
          Math.random()
            .toString(36)
            .slice(2, 8)
            .replace(/[^a-z0-9]/g, "");
      } while (this.rulesDraft.badges[key]);

      return key;
    },

    isCustomBadgeKey(key) {
      return /^custom_badge_[a-z0-9_]+$/.test(String(key || ""));
    },

    rulesAreSame(firstRules, secondRules) {
      return (
        JSON.stringify(firstRules || null) ===
        JSON.stringify(secondRules || null)
      );
    },

    isOpen(key) {
      return !!this.openBadges[key];
    },

    toggleBadge(key) {
      this.$set(this.openBadges, key, !this.openBadges[key]);
    },

    badgeHasHighlightedField(badge) {
      if (!this.highlightedFieldKey || !Array.isArray(badge.fields)) {
        return false;
      }

      return badge.fields.includes(this.highlightedFieldKey);
    },

    openHighlightedBadge() {
      if (!this.highlightedFieldKey) {
        return;
      }

      this.badges.forEach((badge) => {
        if (this.badgeHasHighlightedField(badge)) {
          this.$set(this.openBadges, badge.key, true);
        }
      });
    },

    fieldHighlightClass(fieldKey) {
      if (!fieldKey) {
        return {};
      }

      return {
        [`cptm-field-wraper-key-${fieldKey}`]: true,
        "highlight-field": this.highlightedFieldKey === fieldKey,
      };
    },

    conditionHighlightClass(condition) {
      const fields = Array.isArray(condition.fields) ? condition.fields : [];

      return fields.reduce((classes, fieldKey) => {
        classes[`cptm-field-wraper-key-${fieldKey}`] = true;
        classes["highlight-field"] =
          classes["highlight-field"] || this.highlightedFieldKey === fieldKey;

        return classes;
      }, {});
    },

    conditionRowClass(condition) {
      const fields = Array.isArray(condition.fields) ? condition.fields : [];

      if (
        !this.highlightedFieldKey ||
        !fields.includes(this.highlightedFieldKey)
      ) {
        return {};
      }

      return { "highlight-field": true };
    },

    chipLabel(badge) {
      return this.badgeVisibleLabel(badge) || this.badgeInternalName(badge);
    },

    chipStyle(badge) {
      const style = this.normalizedStyle(
        this.badgeRuleByKey(badge.key).style,
        badge.defaultColor,
      );

      return {
        backgroundColor: style.bg,
        borderColor: style.border || style.bg,
        color: style.text || "#ffffff",
      };
    },

    conditionAriaLabel(badge, label) {
      return `${this.badgeInternalName(badge)} ${label}`;
    },

    conditionSummary(badge) {
      const conditions = this.conditionRows(badge);

      if (!conditions.length) {
        return this.conditionlessSummary(badge);
      }

      const glue = this.badgeMatchMode(badge) === "any" ? " OR " : " AND ";

      return conditions
        .map((condition) => this.conditionSummaryText(condition))
        .join(glue);
    },

    conditionlessSummary(badge) {
      return badge.core
        ? "No conditions - default badge behavior"
        : "No conditions - shows wherever placed";
    },

    conditionSummaryText(condition) {
      if (condition.unsupported) {
        return `${this.conditionSourceLabel(
          condition.source,
        )}: Unsupported condition`;
      }

      const definition = this.conditionDefinition(
        condition.source,
        condition.key,
      );
      const operator = this.operatorLabel(condition.operator);
      const sourceLabel = this.conditionSourceLabel(condition.source);

      if (!this.operatorNeedsValue(condition.operator)) {
        return `${sourceLabel}: ${definition.label} ${operator}`;
      }

      return `${sourceLabel}: ${definition.label} ${operator} ${this.conditionDisplayValue(
        condition,
      )}`;
    },

    conditionSourceLabel(source) {
      const option = this.conditionSourceOptions.find(
        (item) => item.value === source,
      );

      return option ? option.label : "General";
    },

    operatorLabel(operator) {
      const normalizedOperator = this.operatorAlias(operator);

      if (normalizedOperator === "is_not") {
        return "is not";
      }

      if (normalizedOperator === "not_contains") {
        return "does not contain";
      }

      if (normalizedOperator === "=") {
        return "is";
      }

      if (normalizedOperator === "is_empty") {
        return "is empty";
      }

      if (normalizedOperator === "is_not_empty") {
        return "is not empty";
      }

      return normalizedOperator;
    },

    conditionDisplayValue(condition) {
      if (condition.valueType === "boolean") {
        return condition.value === "true" || condition.value === true
          ? "true"
          : "false";
      }

      if (condition.valueType === "select") {
        const option = condition.valueOptions.find(
          (item) => item.value === condition.value,
        );

        return option ? option.label : condition.value;
      }

      return condition.value;
    },

    normalizeHex(value) {
      const color = String(value || "").trim();
      const shortHex = color.match(/^#([0-9a-f]{3})$/i);

      if (shortHex) {
        return `#${shortHex[1]
          .split("")
          .map((char) => `${char}${char}`)
          .join("")
          .toLowerCase()}`;
      }

      if (/^#[0-9a-f]{6}$/i.test(color)) {
        return color.toLowerCase();
      }

      return "";
    },

    nativeColorValue(value) {
      return this.normalizeHex(value) || "#ffffff";
    },
  },
};
</script>
