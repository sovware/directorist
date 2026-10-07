<template>
    <div class="cptm-form-group" :class="{ 'directorist-async-button-group': asyncAction }">
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
                        <span
                            class="directorist-async-button-field__action"
                            :class="{ 'directorist-async-button-field__action--blocked': asyncCredentialsUnsaved || asyncCredentialsMissing }"
                            :tabindex="asyncCredentialsUnsaved || asyncCredentialsMissing ? 0 : null"
                            :aria-describedby="asyncCredentialsUnsaved || asyncCredentialsMissing ? `directorist-async-save-tip-${fieldKey}` : null"
                        >
                            <a :href="asyncActionBlocked ? null : asyncActionUrl"
                                class="settings-save-btn"
                                :aria-disabled="asyncActionBlocked ? 'true' : null"
                                :tabindex="asyncActionBlocked ? -1 : null"
                                @click.prevent="submitAsyncAction"
                            >{{ asyncButtonLabel }}</a>
                            <span
                                v-if="asyncCredentialsUnsaved || asyncCredentialsMissing"
                                :id="`directorist-async-save-tip-${fieldKey}`"
                                class="directorist-async-button-field__tooltip"
                                role="tooltip"
                            >{{ asyncActionBlockReason }}</span>
                        </span>
                        <span
                            class="directorist-async-button-field__status"
                            :class="{
                                'directorist-async-button-field__status--connected': async_connected && !asyncCredentialsMissing,
                                'directorist-async-button-field__status--attention': async_connected && asyncCredentialsMissing,
                            }"
                            role="status"
                        >{{ asyncStatusLabel }}</span>
                    </div>
                    <p v-if="async_connected" class="directorist-async-button-field__details">
                        {{ asyncCredentialsMissing
                            ? 'Webhook ID is still saved. Add valid keys for the same gateway account, save changes, then disconnect. Removing keys does not remove the gateway webhook.'
                            : 'Webhook ID saved on this site; provider status has not been rechecked. Disconnect to remove the gateway webhook.' }}
                    </p>
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
