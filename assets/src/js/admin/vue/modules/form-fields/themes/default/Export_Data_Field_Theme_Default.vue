<template>
    <div class="cptm-form-group">
        <label v-if="( label.length )">
            <component :is="labelType">{{ label }}</component>
        </label>
        
        <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>

        <a href="#" class="settings-save-btn" 
            target="_blank" 
            v-html="button_label"
            @click.prevent="exportData">
        </a>
    </div>
</template>

<script>
import export_data_feild from './../../../../mixins/form-fields/export-data-field';

export default {
    name: 'export-data-field-theme-default',
    mixins: [ export_data_feild ],
}
</script>