<template>
  <div class="cptm-form-group cptm-form-group--dropdown cptm-form-group--api-select" :class="formGroupClass">
    <label v-if="label.length">
      <component :is="labelType">{{ label }}</component>
    </label>

    <div
      class="directorist_dropdown"
      :class="{ ['--open']: show_option_modal, ['--disabled']: isLoading || hasError }"
    >
      <a
        href="#"
        class="directorist_dropdown-toggle"
        @click.prevent="toggleTheOptionModal()"
      >
        <span class="directorist_dropdown-toggle__text">{{
          theCurrentOptionLabel
        }}</span>
      </a>

      <div
        class="directorist_dropdown-option"
        v-if="theOptions && theOptions.length && !isLoading && !hasError"
        :class="{ ['--show']: show_option_modal }"
        @scroll="handleDropdownScroll"
        ref="dropdownOptions"
      >
        <ul>
          <li v-for="(option, option_key) in theOptions" :key="option_key">
            <a
              href="#"
              :class="{ active: option.value == value ? true : false }"
              v-html="option.label ? option.label : ''"
              @click.prevent="updateOption(option.value)"
            >
            </a>
          </li>
          <li 
            v-if="enableInfiniteScroll && isLoadingMore" 
            class="directorist_dropdown-option-loading"
            style="text-align: center; padding: 12px; color: #666;"
          >
            <span class="loading-spinner" style="display: inline-block; width: 16px; height: 16px; border: 2px solid #e0e0e0; border-top-color: #333; border-radius: 50%; animation: spin 0.8s linear infinite;"></span>
            <span style="margin-left: 8px;">Loading more...</span>
          </li>
          <li 
            v-if="enableInfiniteScroll && !hasMore && theOptions.length >= perPage" 
            class="directorist_dropdown-option-end"
            style="text-align: center; padding: 12px; color: #999; font-size: 12px;"
          >
            No more options
          </li>
        </ul>
      </div>
    </div>

    <div 
      style="text-align: center; padding: 40px 20px; background: #ffffff; border: 1px solid #E5E7EB; border-radius: 8px; margin-top: 10px; box-shadow: 0px 2px 8px 0px rgba(16, 24, 40, 0.08);"
    >
      <div class="cptm-form-group--api-select-icon">
        <span class="la la-file-text"></span>
      </div>
      <h4 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 600; color: #333;">No page found yet</h4>
      <p style="margin: 0 0 20px 0; color: #666; font-size: 14px;">
        Click the Re-Load button below to sync<br>newly created pages here.
      </p>
      <button
        v-if="showResyncButton"
        type="button"
        class="cptm-form-group--api-select-re-sync"
        @click="handleResync"
      >
        <span class="la la-refresh"></span>
        {{ resyncLabel }}
      </button>
    </div>
    <div 
      v-if="hasError" 
      style="text-align: center; padding: 40px 20px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 4px; margin-top: 10px;"
    >
      <div style="margin-bottom: 15px;">
        <svg 
          xmlns="http://www.w3.org/2000/svg" 
          width="48" 
          height="48" 
          viewBox="0 0 24 24" 
          fill="none" 
          stroke="#dc2626" 
          stroke-width="2"
          style="margin: 0 auto; display: block;"
        >
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
      </div>
      <h4 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 600; color: #dc2626;">Error Loading Data</h4>
      <p style="margin: 0 0 20px 0; color: #991b1b; font-size: 14px;">{{ errorMessage }}</p>
      <button
        type="button"
        @click="handleResync"
        style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #dc2626; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer; font-weight: 500;"
      >
        <svg 
          xmlns="http://www.w3.org/2000/svg" 
          width="16" 
          height="16" 
          viewBox="0 0 24 24" 
          fill="none" 
          stroke="currentColor" 
          stroke-width="2"
        >
          <polyline points="23 4 23 10 17 10"></polyline>
          <polyline points="1 20 1 14 7 14"></polyline>
          <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
        </svg>
        Retry
      </button>
    </div>

    <select
      @change="update_value($event.target.value)"
      :value="value"
      :disabled="isLoading || hasError"
      class="cptm-d-none"
    >
      <option
        v-if="showDefaultOption && default_option"
        :value="default_option.value"
      >
        {{ default_option.label }}
      </option>
      <option 
        v-for="(option, option_key) in theOptions" 
        :key="option_key" 
        :value="option.value"
      >
        {{ option.label }}
      </option>
    </select>
    <p
      class="cptm-form-group-info"
      v-if="description.length"
      v-html="description"
    ></p>
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
import select_api_field from "../../../../mixins/form-fields/select-api-field";

export default {
  name: "select-api-field-theme-default",
  mixins: [select_api_field],
  mounted() {
    // Inject spin animation if not already present
    if (!document.getElementById('select-api-field-spin-animation')) {
      const style = document.createElement('style');
      style.id = 'select-api-field-spin-animation';
      style.textContent = `
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
      `;
      document.head.appendChild(style);
    }
  }
};
</script>


