<template>
    <div class="cptm-form-group">
        <div class="atbdp-row">
            <div class="atbdp-col atbdp-col-4">
                <label v-if="( label.length )">
                    <component :is="labelType">{{ label }}</component>
                </label>
                
                <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
            </div>
            
            <div class="atbdp-col atbdp-col-8">
                <div v-if="asyncAction" class="directorist-async-button-field">
                    <div class="directorist-async-button-field__row">
                        <a :href="formattedUrl"
                            class="settings-save-btn"
                            :aria-disabled="async_processing ? 'true' : null"
                            @click.prevent="submitAsyncAction"
                        >{{ asyncButtonLabel }}</a>
                        <span
                            class="directorist-async-button-field__status"
                            :class="{ 'directorist-async-button-field__status--connected': async_connected }"
                            role="status"
                        >{{ asyncStatusLabel }}</span>
                    </div>
                    <p
                        v-if="async_feedback"
                        class="directorist-async-button-field__feedback"
                        :class="'directorist-async-button-field__feedback--' + async_feedback.type"
                        :role="async_feedback.type === 'error' ? 'alert' : 'status'"
                    >{{ async_feedback.message }}</p>
                </div>
                <a v-else :href="formattedUrl"
                    class="settings-save-btn" 
                    :target="( openInNewTab ) ? '_blank' : '_self'" 
                    v-html="buttonLabel">
                </a>
            </div>
        </div>
    </div>
</template>

<script>
import button_feild from './../../../../mixins/form-fields/button-field';

export default {
    name: 'button-field-theme-butterfly',
    mixins: [ button_feild ],
    computed: {
        formattedUrl() {
            return this.url.replace(/&amp;/g, '&');
        }
    },
}
</script>
