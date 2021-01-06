<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <label v-if="( label.length )" :for="name">{{ label }}</label>
        <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
        
        <button 
            type="button" 
            class="settings-save-btn" 
            v-html="button.label"
            :disabled="button.is_disabled"
            @click="submitAjaxRequest()"
        >
        </button>

        <div class="cptm-form-group-feedback cptm-my-10" v-if="validation_message">
            <div class="cptm-form-alert" :class="'cptm-' + validation_message.type">
                {{ validation_message.message }}
            </div>
        </div>
    </div>
</template>

<script>
import ajax_action_feild from './../../../../mixins/form-fields/ajax-action-field';
export default {
    name: 'ajax-action-field-theme-default',
    mixins: [ ajax_action_feild ],
}
</script>