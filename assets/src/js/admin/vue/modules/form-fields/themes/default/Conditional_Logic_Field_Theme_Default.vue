<template>
  <div class="cptm-form-group" :class="formGroupClass">
    <div class="cptm-input-toggle-wrap">
      <div class="cptm-input-toggle-content">
        <label v-if="label.length">
          <component :is="labelType">{{ label }}</component>
        </label>
        <p
          v-html="description"
          v-if="description.length"
          class="cptm-form-group-info"
        ></p>
      </div>

      <div class="directorist_vertical-align-m cptm-input-toggle-btn">
        <div class="directorist_item">
          <span
            class="cptm-input-toggle"
            :class="toggleClass"
            @click="toggleEnabled()"
          ></span>
          <input
            type="checkbox"
            :id="fieldId + '_enabled'"
            style="display: none"
            v-model="localValue.enabled"
            @change="updateValue"
          />
        </div>
      </div>
    </div>

    <div
      class="directorist-conditional-logic-builder"
      v-if="localValue.enabled"
    >
      <div class="directorist-conditional-logic-builder__header">
        <select
          class="directorist-conditional-logic-builder__action"
          v-model="localValue.action"
          @change="updateValue"
        >
          <option value="show">Show</option>
          <option value="hide">Hide</option>
        </select>
        <span class="directorist-conditional-logic-builder__label"
          >this field if</span
        >
      </div>

      <div class="directorist-conditional-logic-builder__rules-and-groups">
        <!-- Single Rules and Groups -->
        <template v-for="(group, groupIndex) in localValue.groups">
          <!-- Separator between items (rules or groups) -->
          <div
            class="directorist-conditional-logic-builder__rule-separator"
            v-if="groupIndex > 0"
          >
            <span
              class="directorist-conditional-logic-builder__separator-text"
              >{{ localValue.globalOperator }}</span
            >
          </div>

          <!-- Single Rules (standalone, not in group container) -->
          <template v-if="!group.isGroup">
            <div class="directorist-conditional-logic-builder__rule">
              <div class="directorist-conditional-logic-builder__condition">
                <select
                  class="directorist-conditional-logic-builder__field"
                  v-model="group.conditions[0].field"
                  @change="onFieldChange(group.conditions[0])"
                >
                  <option value="">Select a field</option>
                  <option
                    v-for="field in filteredAvailableFields"
                    :key="field.value"
                    :value="field.value"
                  >
                    {{ field.label }}
                  </option>
                </select>

                <select
                  class="directorist-conditional-logic-builder__operator-select"
                  v-model="group.conditions[0].operator"
                  :key="`operator-${group.conditions[0].field || 'empty'}`"
                  @change="updateValue"
                >
                  <option
                    v-for="operator in getOperatorOptions(group.conditions[0])"
                    :key="operator.value"
                    :value="operator.value"
                  >
                    {{ operator.label }}
                  </option>
                </select>

                <!-- Select dropdown for fields with options (category, select, radio, checkbox, file) -->
                <div
                  v-if="
                    !isValueHidden(group.conditions[0].operator) &&
                    needsSelectInput(group.conditions[0])
                  "
                  class="directorist-conditional-logic-builder__value-select-wrapper"
                  :key="`value-select-wrapper-${group.conditions[0].field || 'empty'}-${group.conditions[0].operator || 'empty'}`"
                >
                  <select
                    :key="`value-select-${group.conditions[0].field || 'empty'}-${group.conditions[0].operator || 'empty'}`"
                    class="directorist-conditional-logic-builder__value directorist-conditional-logic-builder__value-select"
                    v-model="group.conditions[0].value"
                    @change="
                      onConditionValueUpdate(
                        group.conditions[0],
                        $event.target.value,
                      )
                    "
                  >
                    <option value="">Select value</option>
                    <option
                      v-for="option in getValueOptions(group.conditions[0])"
                      :key="option.value"
                      :value="option.value"
                    >
                      {{ option.label }}
                    </option>
                  </select>
                  <button
                    v-if="group.conditions[0].value"
                    type="button"
                    class="directorist-conditional-logic-builder__value-clear"
                    @click="onConditionValueUpdate(group.conditions[0], '')"
                    title="Clear selection"
                  >
                    <span class="fa fa-times"></span>
                  </button>
                </div>

                <!-- Date input for date fields -->
                <input
                  v-if="
                    !isValueHidden(group.conditions[0].operator) &&
                    !needsSelectInput(group.conditions[0]) &&
                    isDateField(group.conditions[0])
                  "
                  :key="`value-date-${group.conditions[0].field || 'empty'}-${group.conditions[0].operator || 'empty'}`"
                  type="date"
                  class="directorist-conditional-logic-builder__value"
                  v-model="group.conditions[0].value"
                  @input="
                    onConditionValueUpdate(
                      group.conditions[0],
                      $event.target.value,
                    )
                  "
                />

                <!-- Time input for time fields -->
                <input
                  v-if="
                    !isValueHidden(group.conditions[0].operator) &&
                    !needsSelectInput(group.conditions[0]) &&
                    isTimeField(group.conditions[0])
                  "
                  :key="`value-time-${group.conditions[0].field || 'empty'}-${group.conditions[0].operator || 'empty'}`"
                  type="time"
                  class="directorist-conditional-logic-builder__value"
                  v-model="group.conditions[0].value"
                  @input="
                    onConditionValueUpdate(
                      group.conditions[0],
                      $event.target.value,
                    )
                  "
                />

                <!-- Color input for color fields (empty state supported) -->
                <div
                  v-if="
                    !isValueHidden(group.conditions[0].operator) &&
                    !needsSelectInput(group.conditions[0]) &&
                    isColorField(group.conditions[0])
                  "
                  :key="`value-color-${group.conditions[0].field || 'empty'}-${group.conditions[0].operator || 'empty'}`"
                  class="directorist-conditional-logic-builder__value-color-wrapper"
                >
                  <div
                    class="directorist-conditional-logic-builder__value-color-swatch"
                    :class="{ 'is-empty': !group.conditions[0].value }"
                  >
                    <span
                      v-if="group.conditions[0].value"
                      class="directorist-conditional-logic-builder__value-color-preview"
                      :style="{
                        backgroundColor: group.conditions[0].value,
                      }"
                    ></span>
                    <span
                      v-if="group.conditions[0].value"
                      class="directorist-conditional-logic-builder__value-color-code"
                    >
                      {{ group.conditions[0].value }}
                    </span>
                    <span
                      v-else
                      class="directorist-conditional-logic-builder__value-color-placeholder"
                    >
                      Select color
                    </span>
                    <input
                      type="color"
                      class="directorist-conditional-logic-builder__value-color-input"
                      :value="group.conditions[0].value || '#000000'"
                      @input="
                        onConditionValueUpdate(
                          group.conditions[0],
                          $event.target.value,
                        )
                      "
                    />
                    <button
                      v-if="group.conditions[0].value"
                      type="button"
                      class="directorist-conditional-logic-builder__value-clear directorist-conditional-logic-builder__value-clear--color"
                      :style="{
                        color: '#f00',
                      }"
                      @click.stop="
                        onConditionValueUpdate(group.conditions[0], '')
                      "
                      title="Clear selection"
                    >
                      <span class="fa fa-times"></span>
                    </button>
                  </div>
                </div>

                <!-- Text input for fields without options -->
                <input
                  v-if="
                    !isValueHidden(group.conditions[0].operator) &&
                    !needsSelectInput(group.conditions[0]) &&
                    !isDateField(group.conditions[0]) &&
                    !isTimeField(group.conditions[0]) &&
                    !isColorField(group.conditions[0])
                  "
                  :key="`value-text-${group.conditions[0].field || 'empty'}-${group.conditions[0].operator || 'empty'}`"
                  type="text"
                  class="directorist-conditional-logic-builder__value"
                  v-model="group.conditions[0].value"
                  placeholder="VALUE"
                  @input="
                    onConditionValueUpdate(
                      group.conditions[0],
                      $event.target.value,
                    )
                  "
                />

                <button
                  type="button"
                  class="directorist-conditional-logic-builder__remove"
                  @click="removeRule(groupIndex)"
                  :disabled="!canDeleteRule"
                  :title="__('Remove rule', 'directorist')"
                >
                  <i class="las la-times"></i>
                </button>
              </div>
            </div>
          </template>

          <!-- Groups (grouped rules - always show as container) -->
          <template v-else-if="group.isGroup">
            <div class="directorist-conditional-logic-builder__group">
              <div class="directorist-conditional-logic-builder__conditions">
                <template
                  v-for="(condition, conditionIndex) in group.conditions"
                >
                  <div
                    class="directorist-conditional-logic-builder__condition-separator"
                    v-if="conditionIndex > 0"
                  >
                    <span
                      class="directorist-conditional-logic-builder__separator-text"
                      >{{ group.operator }}</span
                    >
                  </div>
                  <div class="directorist-conditional-logic-builder__condition">
                    <select
                      class="directorist-conditional-logic-builder__field"
                      v-model="condition.field"
                      @change="onFieldChange(condition)"
                    >
                      <option value="">Select a field</option>
                      <option
                        v-for="field in filteredAvailableFields"
                        :key="field.value"
                        :value="field.value"
                      >
                        {{ field.label }}
                      </option>
                    </select>

                    <select
                      class="directorist-conditional-logic-builder__operator-select"
                      v-model="condition.operator"
                      :key="`operator-${condition.field || 'empty'}`"
                      @change="updateValue"
                    >
                      <option
                        v-for="operator in getOperatorOptions(condition)"
                        :key="operator.value"
                        :value="operator.value"
                      >
                        {{ operator.label }}
                      </option>
                    </select>

                    <!-- Select dropdown for fields with options (category, select, radio, checkbox, file) -->
                    <div
                      v-if="
                        !isValueHidden(condition.operator) &&
                        needsSelectInput(condition)
                      "
                      class="directorist-conditional-logic-builder__value-select-wrapper"
                      :key="`value-select-wrapper-${condition.field || 'empty'}-${condition.operator || 'empty'}`"
                    >
                      <select
                        :key="`value-select-${condition.field || 'empty'}-${condition.operator || 'empty'}`"
                        class="directorist-conditional-logic-builder__value directorist-conditional-logic-builder__value-select"
                        v-model="condition.value"
                        @change="
                          onConditionValueUpdate(condition, $event.target.value)
                        "
                      >
                        <option value="">Select value</option>
                        <option
                          v-for="option in getValueOptions(condition)"
                          :key="option.value"
                          :value="option.value"
                        >
                          {{ option.label }}
                        </option>
                      </select>
                      <button
                        v-if="condition.value"
                        type="button"
                        class="directorist-conditional-logic-builder__value-clear"
                        @click="onConditionValueUpdate(condition, '')"
                        title="Clear selection"
                      >
                        <span class="fa fa-times"></span>
                      </button>
                    </div>

                    <!-- Date input for date fields -->
                    <input
                      v-if="
                        !isValueHidden(condition.operator) &&
                        !needsSelectInput(condition) &&
                        isDateField(condition)
                      "
                      :key="`value-date-${condition.field || 'empty'}-${condition.operator || 'empty'}`"
                      type="date"
                      class="directorist-conditional-logic-builder__value"
                      v-model="condition.value"
                      @input="
                        onConditionValueUpdate(condition, $event.target.value)
                      "
                    />

                    <!-- Time input for time fields -->
                    <input
                      v-if="
                        !isValueHidden(condition.operator) &&
                        !needsSelectInput(condition) &&
                        isTimeField(condition)
                      "
                      :key="`value-time-${condition.field || 'empty'}-${condition.operator || 'empty'}`"
                      type="time"
                      class="directorist-conditional-logic-builder__value"
                      v-model="condition.value"
                      @input="
                        onConditionValueUpdate(condition, $event.target.value)
                      "
                    />

                    <!-- Color input for color fields (empty state supported) -->
                    <div
                      v-if="
                        !isValueHidden(condition.operator) &&
                        !needsSelectInput(condition) &&
                        isColorField(condition)
                      "
                      :key="`value-color-${condition.field || 'empty'}-${condition.operator || 'empty'}`"
                      class="directorist-conditional-logic-builder__value-color-wrapper"
                    >
                      <div
                        class="directorist-conditional-logic-builder__value-color-swatch"
                        :class="{ 'is-empty': !condition.value }"
                      >
                        <span
                          v-if="condition.value"
                          class="directorist-conditional-logic-builder__value-color-preview"
                          :style="{
                            backgroundColor: condition.value,
                          }"
                        ></span>
                        <span
                          v-if="condition.value"
                          class="directorist-conditional-logic-builder__value-color-code"
                        >
                          {{ condition.value }}
                        </span>
                        <span
                          v-else
                          class="directorist-conditional-logic-builder__value-color-placeholder"
                        >
                          Select color
                        </span>
                        <input
                          type="color"
                          class="directorist-conditional-logic-builder__value-color-input"
                          :value="condition.value || '#000000'"
                          @input="
                            onConditionValueUpdate(
                              condition,
                              $event.target.value,
                            )
                          "
                        />
                        <button
                          v-if="condition.value"
                          type="button"
                          class="directorist-conditional-logic-builder__value-clear directorist-conditional-logic-builder__value-clear--color"
                          :style="{
                            color: condition.value,
                          }"
                          @click.stop="onConditionValueUpdate(condition, '')"
                          title="Clear selection"
                        >
                          <span class="fa fa-times"></span>
                        </button>
                      </div>
                    </div>

                    <!-- Text input for fields without options -->
                    <input
                      v-if="
                        !isValueHidden(condition.operator) &&
                        !needsSelectInput(condition) &&
                        !isDateField(condition) &&
                        !isTimeField(condition) &&
                        !isColorField(condition)
                      "
                      :key="`value-text-${condition.field || 'empty'}-${condition.operator || 'empty'}`"
                      type="text"
                      class="directorist-conditional-logic-builder__value"
                      v-model="condition.value"
                      placeholder="VALUE"
                      @input="
                        onConditionValueUpdate(condition, $event.target.value)
                      "
                    />

                    <button
                      type="button"
                      class="directorist-conditional-logic-builder__remove"
                      @click="removeCondition(groupIndex, conditionIndex)"
                      :disabled="
                        !canDeleteRule && group.conditions.length === 1
                      "
                      :title="__('Remove condition', 'directorist')"
                    >
                      <i class="las la-times"></i>
                    </button>
                  </div>
                </template>
              </div>

              <div class="directorist-conditional-logic-builder__group-footer">
                <span
                  class="directorist-conditional-logic-builder__group-footer__label"
                  >Match:</span
                >
                <select
                  class="directorist-conditional-logic-builder__operator"
                  v-model="group.operator"
                  @change="updateValue"
                >
                  <option value="AND">All Conditions (AND)</option>
                  <option value="OR">Any Condition (OR)</option>
                </select>

                <button
                  type="button"
                  class="cptm-btn directorist-conditional-logic-builder__group-footer__add-rule"
                  @click="addCondition(groupIndex)"
                >
                  <span>+</span> Add Condition
                </button>

                <button
                  type="button"
                  class="directorist-conditional-logic-builder__group-footer__remove-group"
                  @click="removeGroup(groupIndex)"
                  :disabled="!canDeleteRule"
                  :title="__('Remove group', 'directorist')"
                >
                  <i class="las la-times"></i>
                </button>
              </div>
            </div>
          </template>
        </template>
      </div>

      <div
        class="directorist-conditional-logic-builder__footer"
        v-if="localValue.enabled"
      >
        <span class="directorist-conditional-logic-builder__footer__label"
          >Match:</span
        >
        <select
          class="directorist-conditional-logic-builder__operator"
          v-model="localValue.globalOperator"
          @change="updateValue"
        >
          <option value="AND">All Conditions (AND)</option>
          <option value="OR">Any Condition (OR)</option>
        </select>

        <div
          class="directorist-conditional-logic-builder__footer__add-group-wrap"
        >
          <button
            type="button"
            class="cptm-btn cptm-btn-secondery directorist-conditional-logic-builder__footer__add-group"
            @click="addGroup"
          >
            <span>+</span> Add Group
          </button>
          <button
            type="button"
            class="cptm-btn directorist-conditional-logic-builder__footer__add-rule"
            @click="addRule"
          >
            <span>+</span> Add Condition
          </button>
        </div>
      </div>
    </div>

    <form-field-validatior
      :section-id="sectionId"
      :field-id="fieldId"
      :root="root"
      :value="value"
      :rules="rules"
      v-model="validationLog"
      @validate="$emit('validate', $event)"
    />
  </div>
</template>

<script>
import conditional_logic_field from "../../../../mixins/form-fields/conditional-logic-field";

export default {
  name: "conditional-logic-field-theme-default",
  mixins: [conditional_logic_field],
  computed: {
    /**
     * Available operator options for conditional logic conditions
     * Centralized in one place for easy maintenance
     */
    operatorOptions() {
      return [
        { value: "is", label: "is" },
        { value: "is not", label: "is not" },
        { value: "contains", label: "contains" },
        { value: "does not contain", label: "does not contain" },
        { value: "empty", label: "empty" },
        { value: "not empty", label: "not empty" },
        { value: "greater than", label: "greater than" },
        { value: "less than", label: "less than" },
        {
          value: "greater than or equal",
          label: "greater than or equal",
        },
        { value: "less than or equal", label: "less than or equal" },
        { value: "starts with", label: "starts with" },
        { value: "ends with", label: "ends with" },
      ];
    },

    /**
     * Filtered available fields - excludes the current field being edited
     */
    filteredAvailableFields() {
      if (!this.availableFields || !Array.isArray(this.availableFields)) {
        return [];
      }

      // Use the stored field key (set when conditional logic was enabled)
      const currentFieldKey = this.currentFieldKeyForExclusion;

      const skipKeys = [
        "logic",
        "conditional_logic",
        "conditional-logic",
        "conditionalLogic",
        "submission_form_fields",
        "search_form_fields",
        "widgets",
        "fields",
        "social",
        "pricing",
        "map",
        "listing_type",
      ];

      // Filter out the current field, conditional logic keys, and excluded types
      const filtered = this.availableFields.filter((field) => {
        if (!field || !field.value) {
          return false;
        }

        const fieldValue = field.value.toString().trim().toLowerCase();

        // Skip conditional logic keys
        if (skipKeys.includes(fieldValue)) {
          return false;
        }

        // If we have a stored field key, skip if it matches
        if (currentFieldKey) {
          const currentKey = currentFieldKey.toString().trim().toLowerCase();
          // Check both exact match and widget_key vs field_key variations
          if (fieldValue === currentKey) {
            return false;
          }

          // Also check field.widget.field_key (which is used in formatFieldsForDropdown: widget.field_key || widgetKey)
          if (field.widget && field.widget.field_key) {
            const widgetFieldKey = field.widget.field_key
              .toString()
              .trim()
              .toLowerCase();
            if (widgetFieldKey === currentKey) {
              return false;
            }
            // Also check without "custom-" prefix
            const widgetFieldKeyWithoutCustom = widgetFieldKey.replace(
              /^custom-/,
              "",
            );
            const currentKeyWithoutCustom = currentKey.replace(/^custom-/, "");
            if (
              widgetFieldKeyWithoutCustom === currentKeyWithoutCustom &&
              widgetFieldKeyWithoutCustom
            ) {
              return false;
            }
          }

          // Also check if field.value matches currentFieldKey when removing "custom-" prefix
          const fieldValueWithoutCustom = fieldValue.replace(/^custom-/, "");
          const currentKeyWithoutCustom = currentKey.replace(/^custom-/, "");
          if (
            fieldValueWithoutCustom === currentKeyWithoutCustom &&
            fieldValueWithoutCustom
          ) {
            return false;
          }
        }

        return true;
      });

      return filtered;
    },
  },
  methods: {
    /**
     * Get filtered operator options based on the selected field type
     * Number fields show numeric operators (greater than, less than, etc.)
     * Other fields hide numeric operators
     * @param {Object} condition - The condition object containing the selected field
     * @returns {Array} Filtered array of operator options
     */
    getOperatorOptions(condition) {
      if (!condition || !condition.field) {
        return this.operatorOptions;
      }

      const fieldData = this.getFieldData(condition.field);
      if (!fieldData) {
        return this.operatorOptions;
      }

      const fieldType = (fieldData.type || "").toString().trim().toLowerCase();
      const fieldValue = (condition.field || "")
        .toString()
        .trim()
        .toLowerCase();

      // File fields (including listing_img), radio fields, privacy policy field: only show "is" and "is not"
      if (
        fieldType === "file" ||
        fieldType === "file_upload" ||
        fieldType === "radio" ||
        fieldValue === "privacy_policy"
      ) {
        return this.operatorOptions.filter((operator) =>
          ["is", "is not"].includes(operator.value),
        );
      }

      // Date, time and color fields: only show "is", "is not", "empty", "not empty"
      if (
        fieldType === "date" ||
        fieldType === "time" ||
        fieldType === "color" ||
        fieldType === "color_picker"
      ) {
        return this.operatorOptions.filter((operator) =>
          ["is", "is not", "empty", "not empty"].includes(operator.value),
        );
      }

      // Checkbox & Select fields: only show "is", "is not", "empty", "not empty", "contains", "does not contain"
      if (fieldType === "checkbox" || fieldType === "select") {
        return this.operatorOptions.filter((operator) =>
          [
            "is",
            "is not",
            "empty",
            "not empty",
            "contains",
            "does not contain",
          ].includes(operator.value),
        );
      }

      // Number fields: show all operators (including numeric comparison)
      const isNumberField = fieldType === "number" || fieldType === "numeric";
      if (isNumberField) {
        return this.operatorOptions;
      }

      // For other fields, filter out numeric comparison operators
      const numericOperators = [
        "greater than",
        "less than",
        "greater than or equal",
        "less than or equal",
      ];

      return this.operatorOptions.filter(
        (operator) => !numericOperators.includes(operator.value),
      );
    },
  },
};
</script>
