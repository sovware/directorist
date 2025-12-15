<template>
    <div class="cptm-form-group cptm-shortcode-generator" :class="formGroupClass">
        <label v-if="label.length">
            <component :is="labelType">{{ label }}</component>
        </label>

        <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>

        <!-- Generate Button - Show only when no shortcodes generated -->
        <button 
            v-if="!dirty" 
            type="button" 
            class="cptm-btn cptm-btn-primary cptm-generate-shortcode-button" 
            @click="generateShortcode"
            tabindex="0"
            aria-label="Generate Shortcodes"
        >
            <i class="fas fa-code"></i>
            <span v-html="generateButtonLabel || 'Generate Shortcodes'"></span>
        </button>

        <!-- Shortcodes Display - Show after generation -->
        <div v-if="dirty">
            <div v-if="shortcodes_list.length" class="cptm-shortcodes-wrapper">
                <div class="cptm-shortcodes-box">
                    <button 
                        type="button" 
                        class="cptm-copy-icon-button" 
                        @click="handleCopyAll"
                        @keydown="handleCopyKeydown"
                        tabindex="0"
                        aria-label="Click to copy all shortcodes"
                        title="Click to copy this"
                    >
                        <i class="far fa-copy"></i>
                    </button>
                    <div class="cptm-shortcodes-content" ref="all-shortcodes">
                        <p 
                            v-for="(shortcode, i) in shortcodes_list" 
                            :key="i" 
                            class="cptm-shortcode-item" 
                            v-html="shortcode"
                        ></p>
                    </div>
                </div>
                <div class="cptm-shortcodes-footer">
                    <span class="cptm-footer-text">Copy & Paste shortcodes into a new or existing page</span>
                    <span class="cptm-footer-separator">|</span>
                    <a 
                        href="#" 
                        class="cptm-regenerate-link" 
                        @click.prevent="handleRegenerate"
                        @keydown="handleRegenerateKeydown"
                        tabindex="0"
                        aria-label="Regenerate shortcodes"
                    >
                        Re-Generate Code
                    </a>
                </div>
            </div>

            <div v-else class="cptm-no-shortcodes">
                <p class="directorist-alert">Nothing to generate</p>
            </div>
        </div>
    </div>
</template>

<script>
import shortcode_list_feild from '../../../../mixins/form-fields/shortcode-list-field';

export default {
    name: 'shortcode-list-field-theme-default',
    mixins: [ shortcode_list_feild ],
    methods: {
        handleCopyAll() {
            this.copyToClip('all-shortcodes');
        },
        
        handleCopyKeydown(event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                this.handleCopyAll();
            }
        },
        
        handleRegenerate() {
            this.dirty = false;
            this.shortcodes_list = [];
            this.$nextTick(() => {
                this.generateShortcode();
            });
        },
        
        handleRegenerateKeydown(event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                this.handleRegenerate();
            }
        },
    },
}
</script>