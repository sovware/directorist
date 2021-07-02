<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <div class="atbdp-row">
            <div class="atbdp-col atbdp-col-4">
                <label v-if="( label.length )">{{ label }}</label>
                <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
            </div>

            <div class="atbdp-col atbdp-col-8">
                <input 
                    type="button" 
                    class="cptm-btn cptm-generate-shortcode-button"
                    v-if="! shortcodes.length"
                    :value="buttonLabel" 
                    @click="generateShortcode"
                >

                <input 
                    type="button" 
                    class="cptm-btn cptm-generate-shortcode-button"
                    v-if="shortcodes.length"
                    value="Copy all" 
                    @click="copyToClip('all-shortcodes')"
                >

                <div v-if="dirty">
                    <div v-if="successMsg.length" class="cptm-info-text cptm-info-success">{{ successMsg }}</div>
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