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
                <input type="file" accept=".json" :id="fieldId" class="cptm-d-none" @input="importJSON">
                <label :for="fieldId" class="cptm-btn cptm-label-btn cptm-btn-secondery">
                    <span class="fa fa-download"></span>
                    {{ buttonLabel }}
                </label>

                <div class="cptm-form-group-feedback" v-if="validation_message">
                    <div class="cptm-form-alert" :class="'cptm-' + validation_message.type">
                        {{ validation_message.message }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import import_feild from './../../../../mixins/form-fields/import-field';

export default {
    name: 'import-field-theme-butterfly',
    mixins: [ import_feild ],
}
</script>