<template>
  <div
    class="cptm-form-group cptm-preview-image-upload"
    :class="thumbnailSrc.length ? 'cptm-preview-image-upload--show' : ''"
  >
    <label v-if="label.length">
      <component :is="labelType">{{ label }}</component>
    </label>

    <p
      class="cptm-form-group-info"
      v-if="description.length"
      v-html="description"
    ></p>

    <div class="cptm-thumbnail">
      <span
        v-if="hasThumbnail"
        class="cptm-thumbnail-action action-trash"
        @click="deleteThumbnail()"
      >
        <i class="uil uil-trash-alt"></i>
      </span>
      <div class="cptm-thumbnail-img-wrap" v-if="thumbnailSrc.length">
        <img
          :src="thumbnailSrc"
          class="cptm-thumbnail-img"
          width="100%"
          height="auto"
        />
      </div>
      <span v-if="!thumbnailSrc.length" class="cptm-thumbnail-placeholder">
        <span class="cptm-thumbnail-placeholder-icon">
          <i class="uil uil-image"></i>
        </span>
      </span>
      <label
        class="cptm-upload-btn cptm-btn cptm-btn-dark directorist-row-tooltip"
        data-tooltip="Change image"
        data-flow="bottom"
      >
        <i class="uil uil-top-arrow-to-top"></i>
        <input
          type="button"
          @click.prevent="openMediaPicker"
          :value="theButtonLabel"
        />
      </label>
      <div class="cptm-thumbnail-drag-text">upload image here</div>
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
import image_feild from "../../../../mixins/form-fields/wp-media-picker-field";

export default {
  name: "wp-media-picker-field-theme-default",
  mixins: [image_feild],
};
</script>
