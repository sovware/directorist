<template>
    <div class="cptm-form-group">
        <div class="atbdp-row">
            <div class="atbdp-col atbdp-col-4">
                <label v-if="label.length">{{ label }}</label>
                <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
            </div>
            
            <div class="atbdp-col atbdp-col-8">
                <a :href="url" 
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
}
</script>