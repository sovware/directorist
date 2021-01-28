<template>
    <div class="cptm-form-group">
        <label v-if="label.length">{{ label }}</label>
        <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>
        
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
</template>

<script>
import import_feild from './../../../../mixins/form-fields/import-field';

export default {
    name: 'import-field-theme-default',
    mixins: [ import_feild ],
}
</script>