<template>
    <div class="cptm-form-group">
        <sub-fields-module
            :option-fields="optionFields"
            :value="value"
            @update="updateOptionData( $event )"
        />

        <div class="atbdp-row">
            <div class="atbdp-col atbdp-col-4">
                <label v-if="( label.length )">
                    <component :is="labelType">{{ label }}</component>
                </label>
                
                <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
            </div>

            <div class="atbdp-col atbdp-col-8">
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
        </div>
    </div>
</template>

<script>
import ajax_action_feild from './../../../../mixins/form-fields/ajax-action-field';
export default {
    name: 'ajax-action-field-theme-butterfly',
    mixins: [ ajax_action_feild ],
}
</script>