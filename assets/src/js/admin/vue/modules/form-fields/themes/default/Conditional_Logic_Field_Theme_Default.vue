<template>
  <div class="cptm-form-group" :class="formGroupClass">
    <div class="cptm-input-toggle-wrap">
      <div class="cptm-input-toggle-content">
        <label v-if="label.length">
          <component :is="labelType">{{ label }}</component>
        </label>
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
      <p
        v-html="description"
        v-if="description.length"
        class="directorist-conditional-logic-builder__description"
      ></p>
      <div class="directorist-conditional-logic-builder__header">
        <select
          class="directorist-conditional-logic-builder__action"
          v-model="localValue.action"
          @change="updateValue"
        >
          <option value="show">Show</option>
          <option value="hide">Hide</option>
        </select>
        <span
          class="directorist-conditional-logic-builder__label"
          v-if="label.length"
          >{{ label }}</span
        >
        <span class="directorist-conditional-logic-builder__label">if</span>
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
                  @change="updateValue"
                >
                  <option value="is">is</option>
                  <option value="is not">is not</option>
                  <option value="contains">contains</option>
                  <option value="does not contain">does not contain</option>
                  <option value="empty">empty</option>
                  <option value="not empty">not empty</option>
                  <option value="greater than">greater than</option>
                  <option value="less than">less than</option>
                  <option value="greater than or equal">
                    greater than or equal
                  </option>
                  <option value="less than or equal">less than or equal</option>
                  <option value="starts with">starts with</option>
                  <option value="ends with">ends with</option>
                </select>

                <!-- Select dropdown for fields with options (category, select, radio, checkbox) -->
                <select
                  v-if="
                    !isValueHidden(group.conditions[0].operator) &&
                    needsSelectInput(group.conditions[0])
                  "
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

                <!-- Text input for fields without options -->
                <input
                  v-if="
                    !isValueHidden(group.conditions[0].operator) &&
                    !needsSelectInput(group.conditions[0])
                  "
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
                      @change="updateValue"
                    >
                      <option value="is">is</option>
                      <option value="is not">is not</option>
                      <option value="contains">contains</option>
                      <option value="does not contain">does not contain</option>
                      <option value="empty">empty</option>
                      <option value="not empty">not empty</option>
                      <option value="greater than">greater than</option>
                      <option value="less than">less than</option>
                      <option value="greater than or equal">
                        greater than or equal
                      </option>
                      <option value="less than or equal">
                        less than or equal
                      </option>
                      <option value="starts with">starts with</option>
                      <option value="ends with">ends with</option>
                    </select>

                    <!-- Select dropdown for fields with options (category, select, radio, checkbox) -->
                    <select
                      v-if="
                        !isValueHidden(condition.operator) &&
                        needsSelectInput(condition)
                      "
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

                    <!-- Text input for fields without options -->
                    <input
                      v-if="
                        !isValueHidden(condition.operator) &&
                        !needsSelectInput(condition)
                      "
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
                <select
                  class="directorist-conditional-logic-builder__operator"
                  v-model="group.operator"
                  @change="updateValue"
                >
                  <option value="AND">AND</option>
                  <option value="OR">OR</option>
                </select>

                <button
                  type="button"
                  class="cptm-btn"
                  @click="addCondition(groupIndex)"
                >
                  <span>+</span> Rule
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
        <select
          class="directorist-conditional-logic-builder__operator"
          v-model="localValue.globalOperator"
          @change="updateValue"
        >
          <option value="AND">AND</option>
          <option value="OR">OR</option>
        </select>

        <button type="button" class="cptm-btn" @click="addRule">
          <span>+</span> Rule
        </button>

        <button
          type="button"
          class="cptm-btn cptm-btn-secondery"
          @click="addGroup"
        >
          <span>+</span> Group
        </button>
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
        "widgets",
        "fields",
      ];

      // Filter out the current field and conditional logic keys
      const filtered = this.availableFields.filter((field) => {
        if (!field || !field.value) {
          return true;
        }

        const fieldValue = field.value.toString().trim().toLowerCase();

        // Skip conditional logic keys
        if (skipKeys.includes(fieldValue)) {
          return false;
        }

        // If we have a stored field key, skip if it matches
        if (currentFieldKey) {
          const currentKey = currentFieldKey.toString().trim().toLowerCase();
          if (fieldValue === currentKey) {
            return false;
          }
        }

        return true;
      });

      return filtered;
    },
  },
};
</script>
