<template>
  <div class="cptm-form-group tab-field">
    <div class="cptm-schema-tab-area" v-if="schema.length">
        <div class="cptm-schema-tab-label">
          {{ schema }}
        </div>
          <div class="cptm-schema-tab-wrapper" :class="{ 'cptm-schema-multi-directory-disabled': !multi_directory_status }">
            <div
            class="cptm-schema-tab-item"
            v-for="(option, option_index) in theOptions"
            :key="option_index"
            :class="{ 'active': local_value === option.value }"
          >
            <input
              type="radio"
              class="cptm-schema-radio"
              :id="getOptionID(option, option_index, sectionId)"
              :name="name"
              :value="typeof option.value !== 'undefined' ? option.value : ''"
              v-model="local_value"
            />
            <label class="cptm-schema-label-wrapper" :for="getOptionID(option, option_index, sectionId)">
              <div class="cptm-schema-label">
                {{ option.label }}
                <span class="cptm-schema-label-badge" v-if="!multi_directory_status.length">Multi Directory Disabled</span>
              </div>
                <div class="cptm-schema-label-description">
                  {{ option.description }}
                </div>
              </label>
          </div>
        </div>
    </div>
    <div v-else class="cptm-preview-wrapper">
      <label v-if="label.length">
        <component :is="labelType">{{ label }}</component>
      </label>

      <p
        class="cptm-form-group-info"
        v-if="description.length"
        v-html="description"
      ></p>
      <div class="cptm-tab-area" >
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
  name: "tab-field-theme-butterfly",
  mixins: [tab_field],
  mounted() {},
};
</script>
