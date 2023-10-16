<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <label v-if="( label.length )">
            <component :is="labelType">{{ label }}</component>
        </label>
        
        <p class="cptm-form-group-info" v-if="description.length" v-html="description"></p>

        <div v-if="editor === 'wp_editor'">
            <div :id="editor + '_' + fieldId"></div>
        </div>
        
        <textarea v-else name="" :cols="cols" :rows="rows" :placeholder="placeholder" class="cptm-form-control" v-model="local_value"></textarea>

        <form-field-validatior 
            :section-id="sectionId"
            :field-id="fieldId"
            :root="root"
            :value="local_value" 
            :rules="rules" 
            v-model="validationLog" 
            @validate="$emit( 'validate', $event )"
        />
    </div>
</template>

<script>
import textarea_feild from './../../../../mixins/form-fields/textarea-field';

export default {
    name: 'textarea-field-theme-default',
    mixins: [ textarea_feild ],
    props: {
        editor: {
            required: false,
            default: '',
        },
        fieldId: {
            required: false,
            default: '',
        },
    },

    mounted() {
        tinymce.init({
            selector: `#${this.editor}_${this.fieldId}`,
            plugins: 'lists link media',
            toolbar: 'undo redo | formatselect | bold italic | bullist numlist | link | media',
            menubar: false,
            branding: false,
        });
    },

}
</script>