<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <div class="atbdp-row">
            <div class="atbdp-col atbdp-col-4">
                <label v-if="( label.length )">
                    <component :is="labelType">{{ label }}</component>
                </label>
                
                <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
            </div>

            <div class="atbdp-col atbdp-col-8">
                <div class="directorist-mb-n20">
                    <span v-if="successMsg.length" class="cptm-info-text cptm-info-success directorist-center-content-inline" v-html="successMsg"></span>

                    <button type="button" class="cptm-btn cptm-generate-shortcode-button" v-if="shortcodes.length" @click="copyToClip('all-shortcodes')">
                        <span v-html="copyButtonLabel"></span>
                    </button>

                    <button type="button" class="cptm-btn cptm-generate-shortcode-button" @click="generateShortcode">
                        <span v-html="generateButtonLabel"></span>
                    </button>
                </div>

                <div v-if="dirty">
                    <div v-if="shortcodes.length" class="cptm-shortcodes">
                        <p class="directorist-alert" v-for="( shortcode, i ) in shortcodes" :key="i" v-html="shortcode" ref="shortcodes"></p>
                    </div>

                    <div v-else>
                        <p  class="directorist-alert">Nothing to generate</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import text_feild from '../../../../mixins/form-fields/shortcode-list-field';

export default {
    name: 'shortcode-list-field-theme-butterfly',
    mixins: [ text_feild ],
}
</script>