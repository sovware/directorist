<template>
  <div class="cptm-form-group">
    <div class="atbdp-row">
      <div class="atbdp-col atbdp-col-4">
        <label v-if="label.length">
          <component :is="labelType">{{ label }}</component>
        </label>

        <p
          class="cptm-form-group-info"
          v-if="description.length"
          v-html="description"
        ></p>
      </div>

      <div class="atbdp-col atbdp-col-8">
        <div class="cptm-radio-images-grid">
          <div
            class="cptm-radio-images-item"
            v-for="(option, option_index) in theOptions"
            :key="option_index"
            :class="{ active: local_value === (typeof option.value !== 'undefined' ? option.value : '') }"
          >
            <input
              type="radio"
              class="cptm-radio"
              :id="getOptionID(option, option_index, sectionId)"
              :name="name"
              :value="typeof option.value !== 'undefined' ? option.value : ''"
              v-model="local_value"
            />
            <label
              :for="getOptionID(option, option_index, sectionId)"
              class="cptm-radio-images-label"
            >
              <div class="cptm-radio-images-thumbnail-wrapper" v-if="preview && preview[option.value]">
                <img
                  class="cptm-radio-images-thumbnail"
                  :src="preview[option.value]"
                  :alt="option.label"
                />
              </div>
              <div class="cptm-radio-images-footer">
                <span class="cptm-radio-images-indicator"></span>
                <span class="cptm-radio-images-label-text">{{ option.label }}</span>
              </div>
            </label>
          </div>
        </div>

        <p class="cptm-info-text" v-if="!theOptions.length">
          {{ infoTextForNoOption }}
        </p>

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
  </div>
</template>

<script>
import radio_feild from "./../../../../mixins/form-fields/radio-field";

export default {
  name: "radio-images-field-theme-butterfly",
  mixins: [radio_feild],
};
</script>
