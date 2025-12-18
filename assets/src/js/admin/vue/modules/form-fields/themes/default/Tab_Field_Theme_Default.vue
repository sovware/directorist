<template>
  <div class="cptm-form-group tab-field" :class="formGroupClass">
    <label v-if="label.length">
      <component :is="labelType">{{ label }}</component>
    </label>

    <p
      class="cptm-form-group-info"
      v-if="description.length"
      v-html="description"
    ></p>

    <div
      class="cptm-form-group-tab"
      v-if="theOptions"
      :class="{ ['--show']: show_option_modal }"
    >
      <ul class="cptm-form-group-tab-list">
        <li
          v-for="(option, option_key) in theOptions"
          :key="option_key"
          class="cptm-form-group-tab-item"
          :class="option.value"
        >
          <a
            href="#"
            class="cptm-form-group-tab-link"
            :class="{ active: option.value == value ? true : false }"
            v-html="option.label ? option.label : ''"
            @click.prevent="updateOption(option.value)"
          >
          </a>
        </li>
      </ul>
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
import select_field from "../../../../mixins/form-fields/select-field";

export default {
  name: "select-field-theme-default",
  mixins: [select_field],
};
</script>
