<template>
    <div class="cptm-form-group">
        <label v-if="label.length">{{ label }}</label>
        <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>

        <button type="button" class="cptm-btn cptm-btn-secondery" @click="exportJSON()">
            <span class="fa fa-upload"></span>
            {{ buttonLabel }}
        </button>

        <div class="cptm-form-group-feedback" v-if="validation_message">
            <div class="cptm-form-alert" :class="'cptm-' + validation_message.type">
                {{ validation_message.message }}
            </div>
        </div>
    </div>
</template>

<script>
import export_feild from './../../../../mixins/form-fields/export-field';

export default {
    name: 'export-field-theme-butterfly',
    mixins: [ export_feild ],
}
</script>