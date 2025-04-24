<template>
  <div class="cptm-form-group cptm-preview-wrapper tab-field">
      <label v-if="label.length">
        <component :is="labelType">{{ label }}</component>
      </label>

      <p
        class="cptm-form-group-info"
        v-if="description.length"
        v-html="description"
      ></p>
      
    <div class="cptm-preview-tab-area">
      <div class="cptm-tab-area">
        <div
          class="cptm-tab-item"
          v-for="(option, option_index) in theOptions"
          :key="option_index"
        >
          <input
            type="radio"
            class="cptm-radio"
            :id="getOptionID(option, option_index, sectionId)"
            :name="name"
            :value="typeof option.value !== 'undefined' ? option.value : ''"
            v-model="local_value"
          />
          <label :for="getOptionID(option, option_index, sectionId)">
            {{ option.label }}
          </label>
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
  </div>
</template>

<script>
import tab_field from "../../../../mixins/form-fields/tab-field";

export default {
  name: "tab-field-theme-default",
  mixins: [tab_field],
};
</script>
